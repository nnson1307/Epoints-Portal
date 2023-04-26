<template>
    <div class="frmFilter bg clear-form">
        <div class="row padding_row" v-if="searchConfig.length > 0">
            <div class="col-lg-3" v-for="(config, index) in searchConfig" :key="index">
                <div class="form-group" v-if="config.type == 'text'">
                    <input type="text" 
                        class="form-control" 
                        v-model="dataFilters[config.name]" 
                        :placeholder="config.placeholder" />
                </div>
                <div class="form-group" v-if="config.type == 'select2'">
                    <Select2 v-if="config.name == 'manage_status_id'"
                        :placeholder="config.placeholder"
                        v-model="dataFilters[config.name]"
                        :options="convertDataSelect2(config.data)"
                        multiple="true"
                    />
                    <Select2 v-else
                        :placeholder="config.placeholder"
                        v-model="dataFilters[config.name]"
                        :options="convertDataSelect2(config.data)"
                    />
                </div>
                <div class="form-group" v-if="config.type == 'daterange_picker'">
                        <div class="m-input-icon m-input-icon--right">
                            <input readonly class="form-control daterange-picker"
                                    style="background-color: #fff" 
                                    autocomplete="off" 
                                    :placeholder="config.placeholder">
                            <span class="m-input-icon__icon m-input-icon__icon--right">
                                <span><i class="la la-calendar"></i></span></span>
                        </div>
                    </div>
                    <div class="form-group" v-if="config.type == 'date_picker'">
                        <div class="m-input-icon m-input-icon--right">
                            <date-picker 
                                v-model="dataFilters[config.name]" 
                                valueType="format"
                                :format="formatDate">
                            </date-picker>    
                        </div>
                    </div>
            </div>
            <div class="col-lg-3">
                <a href="#" class="btn btn-refresh ss--button-cms-piospa text-uppercase m-btn--icon mr-3" @click="clearSearch">
                    {{ translate['Xóa bộ lọc'] }} <i class="fa fa-eraser" aria-hidden="true"></i>
                </a>
                <button class="btn btn-primary color_button btn-search text-uppercase" @click="submitSearch">
                    {{ translate['Tìm kiếm'] }} <i class="fa fa-search ic-search m--margin-left-5"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import store from "../store/index";
import moment from 'moment';
import { mapActions, mapGetters } from "vuex";
import { getSearchOptionApi } from "@/utils/api";
import Select2 from 'v-select2-component';
import Multiselect from 'vue-multiselect'
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';

export default {
    name: "Search",
    components: {
        Select2,
        Multiselect,
        DatePicker
    },
    props: ["options"],
    data() {
        return {
            inputName: "",
            inputPlaceholder: "",
            searchConfig: [],
            formatDate: 'DD/MM/YYYY',
            dataFilters: {},
            isLoadingConfig: false,
            translate: {}
        };
    },
    created() {

    },
    mounted() {
        this.getSearchConfig();
        this.getTranslate();
    },
    computed: {
    },
    methods: {
        ...mapActions({
            getListWorkAction: "getListWorkAction",
            setCurrentFilterAction: "setCurrentFilterAction",
        }),

        getTranslate(){
            let translateCache = localStorage.getItem('tranlate');
            this.translate = JSON.parse(translateCache);
        },

        async getSearchConfig(){
            const response = await getSearchOptionApi();
            if(response.data){
                const dataFilter = response.data;
                const config = (dataFilter && dataFilter.searchConfig) ? Object.values(dataFilter.searchConfig).filter(e => e.active) : [];
                
                //Get filters by config
                let fitlers = {};
                config.forEach(item => {
                    let value = '';

                    if(item.name == 'date_start'){
                        value = moment().startOf('month').format('DD/MM/YYYY'); //Ngày đầu của tháng
                    }
                    
                    if(item.name == 'date_end'){
                        value = moment().endOf('month').format('DD/MM/YYYY'); //Ngày cuối của tháng
                    }

                    fitlers[item.name] = value;
                });

                this.dataFilters = fitlers;
                this.setCurrentFilterAction(fitlers);
                this.searchConfig = config;
            }
        },

        convertDataSelect2(lists){
            return Object.entries(lists).map(item => ({id: item[0], text: item[1]}));
        },

        submitSearch(){
            console.log('filter', this.dataFilters);
            this.setCurrentFilterAction(this.dataFilters);
            this.getListWorkAction(this.dataFilters);
        },

        clearSearch(){
            let currentFilters = this.dataFilters;
            Object.keys(currentFilters).forEach(function(key, index) {
                let value = "";

                if(key == 'date_start'){
                    value = moment().startOf('month').format('DD/MM/YYYY'); //Ngày đầu của tháng
                }
                
                if(key == 'date_end'){
                    value = moment().endOf('month').format('DD/MM/YYYY'); //Ngày cuối của tháng
                }

                currentFilters[key] = value;
            });
        }
    }
};
</script>

<style lang="scss" scoped>
    .frmFilter{
        min-height: 210px;
    }
    .mx-datepicker{
        width: 100%;
    }
    .mx-input{
        display: block;
        width: 100%;
        height: 48px;
        padding: 0.85rem 1.15rem;
        font-size: 1rem;
        line-height: 1.25;
        color: #181818;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
    }
</style>
