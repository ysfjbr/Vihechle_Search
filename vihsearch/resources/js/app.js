/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

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
Vue.component('mdb-select', require('mdbvue'));


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
	data:{
        results:[], 
        filterData: {years_size:{minYear:"1995", maxYear:"2020",sizeFrom:"0", sizeTo:"99999"}},
        categ:"2",
        producer:'',
        model:'',
        yearFrom:'1900',
        yearTo:'2050',
        yearRange:[],

        sizeFrom:'0',
        sizeTo:'99999',
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
        filterChanged:function()
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
                                "yearFrom": this.yearFrom,
                                "model":    this.model,
                                "yearTo":   this.yearTo,
                                "sizeFrom": this.sizeFrom,
                                "sizeTo":   this.sizeTo
                            },
                    config: { headers: { 'Content-Type': 'multipart/form-data' }}
                    })
                    .then(res => {
                        this.searching = false;

                        this.filterData.categs =  res.data.categs;

                        this.filterData.producers =  res.data.producers;
                        
                        this.filterData.models =  res.data.models;
                        const trimArray = array => array.map(string => string.trim());
                        const modelsArr= trimArray(Object.values(res.data.models));
                        if(!modelsArr.includes(this.model.trim())) this.model ='';

                        this.filterData.years_size =  res.data.years_size;

                        this.yearRange = [];
                        for(let i = Number(res.data.years_size.minYear); i<= Number(res.data.years_size.maxYear) ; i++)
                        this.yearRange.push(i) 
                        if(!this.yearRange.includes(this.yearFrom)) this.yearFrom ='';
                        if(!this.yearRange.includes(this.yearTo)) this.yearTo ='';

                        this.sizeRange = [];
                        for(let i = Number(res.data.years_size.minSize); i<= Number(res.data.years_size.maxSize) ; i+=500)
                        this.sizeRange.push(i) 
                        console.log();
                        
                        if(!this.sizeRange.includes(this.sizeFrom)) this.sizeFrom ='0';
                        if(!this.sizeRange.includes(this.sizeTo)) this.sizeTo ='99999';

                        console.log("filter model",this.filterData );
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
                                "page_size" :this.page_size,
                                "page":     this.page,
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
                        this.searching = false;
                        this.results =  this.results.concat(res.data.data);
                        //console.log("ress", this.results);
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
            console.log(n,o);
            this.producer ="";
            this.model ="";
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