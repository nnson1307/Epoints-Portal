<template>
  <div id="app">
    <router-view />
  </div>
</template>

<script>
import moment from 'moment';
import Dashboard from './components/Dashboard.vue'
import { getSearchOptionApi } from "@CustomerLead/utils/api";
import { mapGetters } from "vuex"
import { mapActions } from "vuex";
import { db } from './utils/db'
import axios from "axios"


export default {
    name: 'app', 
    components: {
        Dashboard,
    },
    computed: {
        ...mapGetters({
            currentFilter: "currentFilter",
            searchOption: "searchOption"
        }),
    },
    methods: {
        ...mapActions({
            getListCustomerLeadAction: "getListCustomerLeadAction",
            getSearchOptionAction: "getSearchOptionAction",
            setCurrentFilterAction: "setCurrentFilterAction",
        }),

        async main(){
            await this.getSearchOptionAction();
            let searchOption = this.searchOption;

            if(searchOption && searchOption.optionPipeline){
                let optionPipeline = searchOption.optionPipeline;
                let defaultOptionPipeline = optionPipeline ? optionPipeline.find(i => i.is_default) : null;
                let defaultOptionPipelineId = defaultOptionPipeline ? defaultOptionPipeline.pipeline_id : null;
                let currentFilter = this.currentFilter;
                currentFilter.pipeline_id = defaultOptionPipelineId;
                this.setCurrentFilterAction(currentFilter);
            }

            this.getListCustomerLeadAction(this.currentFilter);
        }
    },
    created() {
        this.main();
    },
    data() {
        return {
            
        }
    },
    // firebase: {
    //     projects: db.ref('projetcs'),
    // },
    mounted() {
        
    }
}
</script>

<style lang="scss">
    #app{
        width: 100%;
    }

    .vue-daterange-picker{
        width: 100%;
    }

    .reportrange-text{
        padding: 0.85rem 1.15rem !important;
    }
</style>
