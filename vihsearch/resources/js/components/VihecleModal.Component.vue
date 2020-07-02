<template id="example-modal">
  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content" v-if="!searching">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{ results.series.name }}  {{ results.series.producer.name }} {{ results.size }} {{ results.config }} {{ results.year }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <div class="modal-body">
            <div>
                
            </div>
            <div v-for="part in results.parts" :key="part.id" :class="{'text-muted':(part.active==='0')}">
                {{ part.name }}
            </div>
          
        </div>
      </div>
    </div>
  </div>
</template>

<script>
    export default {
        name: "viheclemodal",
        props: {
                "vid":"",
                "searching":false
                },
        data:
        {
            "results":null
        },
        watch:{
            "vid":function(val, oldVal){
                //console.log(val, oldVal);
                this.getData(val);
            }
        },
        methods:
        {
            getData:function(id)
            {
                this.searching = true;
                axios({
                    method: 'post',
                    url: `/api/getItemData`,
                    data:  {"vid":id},
                })
                //axios.post("/api/getItemData",{data:})
                .then(response => {
                    this.searching = false;
                    console.log(response.data)
                    this.results = response.data;
                });
            }
        },

        mounted() {
            
        }
    }
</script>
<style scoped>
.text-muted{
    color:#ccc !important;
}
</style>