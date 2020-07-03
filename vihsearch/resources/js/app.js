/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

require("bootstrap-css-only/css/bootstrap.min.css");
require("@fortawesome/fontawesome-free/css/all.min.css");

import vSelect from 'vue-select'

import 'vue-select/dist/vue-select.css';


window.Vue = require('vue');
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('viheclemodal', require('./components/VihecleModal.Component.vue').default);
Vue.component('vihecle', require('./components/Vihecle.Component.vue').default);

Vue.component('v-select', vSelect)

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.filter('formatDate', function(d) {
	if(!window.Intl) return d;
	return new Intl.DateTimeFormat('en-US').format(new Date(d));
}); 


const app = new Vue({
    el:'#app',
    components: {
        vSelect
    },
	data:{
        /* Deselect: {
            render: createElement => createElement('span', '❌'),
          }, */
        options: [],
        searchText: '',
        results:[], 
        filterData: {years_size:{minYear:null, maxYear:null,sizeFrom:null, sizeTo:null}},
        categ:"2",
        producer:null,
        model:null,
        yearFrom:null,
        yearTo:null,
        yearRange:[],

        sizeFrom:null,
        sizeTo:null,
        sizeRange:[],

        page_size:30,
        page:1,
        more: true,

		noResults:false,
        searching:false,
        modalVal:""

		//mySlider:null
	},
	methods:{
        filterChanged:function ()
        {
            console.log(this.producer,this.model);
            
            this.results =[];
            this.more = true;
            this.page=1;
            this.searching = true;
                
                axios({
                    method: 'post',
                    url: `/api/getFilterData`,
                    data:  {
                                "categ":    this.categ,
                                "producer": this.producer,
                                "model":    this.model,
                                "yearFrom": this.yearFrom,
                                "yearTo":   this.yearTo,
                                "sizeFrom": this.sizeFrom,
                                "sizeTo":   this.sizeTo
                            },
                    config: { headers: { 'Content-Type': 'multipart/form-data' }}
                    })
                    .then(res => {
                        console.log("filter model",res.data );
                        this.searching = false;

                        this.filterData.categs =  res.data.categs;

                        this.filterData.producers =  res.data.producers;
                        
                        this.filterData.models =  res.data.models;

                        try {
                            const trimArray = array => array.map(string => string.label.trim());
                            const modelsArr= trimArray(Object.values(res.data.models));
                            if(!modelsArr.includes(this.model.trim())) this.model ="";
                        } catch (error) {
                            
                        }

                        this.filterData.years_size =  res.data.years_size;

                        this.yearRange = [];
                        for(let i = Number(res.data.years_size.minYear); i<= Number(res.data.years_size.maxYear) ; i++)
                        this.yearRange.push({'label':i,'code':i}) 
                        //if(!this.yearRange.includes(this.yearFrom)) this.yearFrom =null;
                        //if(!this.yearRange.includes(this.yearTo)) this.yearTo = null;

                        this.sizeRange = [];
                        const maxValuesCount = 15;
                        const Steps = res.data.years_size.maxSize - res.data.years_size.minSize < maxValuesCount ? 1 : (Math.round((res.data.years_size.maxSize - res.data.years_size.minSize)/maxValuesCount)) || 1 

                        // List 15 Size from range
                        for(let i = Number(res.data.years_size.minSize); i<= Number(res.data.years_size.maxSize); i+= Steps)
                        {
                            let sizeVal = Steps>10 ? Math.round(i/10)*10 : i;
                            this.sizeRange.push({'label':sizeVal,'code':sizeVal}) 
                        }

                        // Add Last when not listed
                        if(this.sizeRange[this.sizeRange.length-1].code < Number(res.data.years_size.maxSize))
                        this.sizeRange.push({'label':res.data.years_size.maxSize,'code':res.data.years_size.maxSize}) 
                        
                       
                        
                        //if(!this.sizeRange.includes(this.sizeFrom)) this.sizeFrom =null;
                        //if(!this.sizeRange.includes(this.sizeTo)) this.sizeTo =null;

                        this.search();
                    })
                    .catch(e => {
                        console.log(e);
                })
        },

		search:function() {
           
            if(this.more && !this.searching)
            {
                this.searching = true;
                axios({
                    method: 'post',
                    url: `/api/getSearchData`,
                    data:  {
                                "categ"     :this.categ,
                                "producer": this.producer,
                                "model":    this.model,
                                "page_size" :this.page_size,
                                "page":     this.page,
                                "yearFrom": this.yearFrom,
                                "yearTo":   this.yearTo,
                                "sizeFrom": this.sizeFrom,
                                "sizeTo":   this.sizeTo
                            },
                    config: { headers: { 'Content-Type': 'multipart/form-data' }}
                    })
                    .then(res => {
                        this.searching = false;
                        this.results =  this.results.concat(res.data.data);
                        console.log("ress", this.results);
                        this.page ++;
                        this.more = res.data.total > this.results.length;
                        this.noResults = this.results.length === 0;
                        if(!this.more) console.log("thats all");
                        
                        if(this.resHeight()-window.innerHeight <= window.scrollY) this.search();     // get More data when not enogh 
                    })
                    .catch(e => {
                        console.log(e);
                })
            }

			
        },

        handleScroll:function(e) {
            if(this.resHeight()-window.innerHeight <= window.scrollY) this.search();            
          },

        resHeight:function () {
            return this.$refs.resultsDiv.clientHeight;
          },
        showModal:function(e) {
            let from = e.target
            this.modalVal = $(from).attr("ds")
            let modal = this.$refs.modal.$el
            $(modal).modal('show')
        }
    },
    watch:{
        "categ": function(n,o)
        {
            console.log("categgg",n,o);
            if(n === null) this.categ = o || 2;
            this.producer =null;
            this.model =null;
            this.filterChanged();
        },
        "producer": function(n,o)
        {
            this.filterChanged();
        },
        "model": function(n,o)
        {
            this.filterChanged();
        },
        'sizeFrom': function(n,o)
        {
            this.filterChanged();
        },
        'sizeTo': function(n,o)
        {
            this.filterChanged();
        },
        'yearFrom': function(n,o)
        {
            this.filterChanged();
        },
        'yearTo': function(n,o)
        {
            this.filterChanged();
        }
    },
    created:function() {
        window.addEventListener('scroll', this.handleScroll);
    },
    mounted () {
        this.filterChanged();  
    },
    destroyed() {
        window.removeEventListener('scroll', this.handleScroll);
    }
});