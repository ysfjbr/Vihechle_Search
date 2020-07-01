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

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

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
        filterData: {years:{minYear:"1995", maxYear:"2020"}},

        producer:'',
        model:'',
        yearFrom:'1900',
        yearTo:'2050',
        yearRange:[],
        sizeFrom:'',
        sizeTo:'',

        page_size:30,
        page:1,
        more: true,

		noResults:false,
		searching:false,
		mySlider:null
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
                                "producer": this.producer,
                                "yearFrom": this.yearFrom,
                                "yearTo":   this.yearTo,
                                "sizeFrom": this.sizeFrom,
                                "sizeTo":   this.sizeTo
                            },
                    config: { headers: { 'Content-Type': 'multipart/form-data' }}
                    })
                    .then(res => {
                        this.searching = false;
                        this.filterData.producers =  res.data.producers;
                        
                        this.filterData.models =  res.data.models;
                        const trimArray = array => array.map(string => string.model.trim());
                        const modelsArr= trimArray(Object.values(res.data.models));
                        if(!modelsArr.includes(this.model.trim())) this.model ='';

                        this.filterData.years =  res.data.years;

                        this.yearRange = [];
                        for(let i = Number(res.data.years.minYear); i<= Number(res.data.years.maxYear) ; i++)
                        this.yearRange.push(i) 

                        if(!this.yearRange.includes(this.yearFrom)) this.yearFrom ='';
                        if(!this.yearRange.includes(this.yearTo)) this.yearTo ='';

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
                                "page_size":this.page_size,
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