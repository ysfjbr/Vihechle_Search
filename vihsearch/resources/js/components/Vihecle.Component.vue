<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card from-control">

                    <div class="card-body" :ds="rdata.id">
							{{rdata.producer}} {{rdata.series}} {{rdata.size}} {{rdata.config}} {{rdata.year}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "vihecle",
        props:['rdata'],
    
        mounted() {
        }
    }
</script>
