<template>
    <div class="frmFilter">
        <div class="form-group m-form__group row">
            <div class="col-sm-3">
                <div class="input-group">
                    <input type="text" 
                        class="form-control" 
                        v-model="currentFilter.search" 
                        :placeholder="translate('Nhập thông tin tìm kiếm')" />
                </div>
            </div>
            <div class="col-sm-3">
                <Select2
                    :placeholder="translate('Chọn hành trình')"
                    v-model="currentFilter.pipeline_id"
                    :options="convertDataPipelineSelect2(searchOption.optionPipeline)"
                />
            </div>
            <div class="col-sm-3">
                <Select2
                    :placeholder="translate('Chọn loại khách hàng')"
                    v-model="currentFilter.customer_type"
                    :options="renderDataListCustomerTypeSelect2()"
                />
            </div>
            <div class="col-sm-3">
                <Select2
                    :placeholder="translate('Chọn loại chăm sóc khách hàng')"
                    v-model="currentFilter.select_manage_type_work_id"
                    :options="convertDataListWorkSelect2(searchOption.listWorkType)"
                />
            </div>
            <div class="col-sm-3 form-group mt-3">
                <div class="m-input-icon m-input-icon--right">
                    <date-picker 
                        v-model="currentFilter.date_from" 
                        valueType="format"
                        :format="formatDate">
                    </date-picker>    
                </div>
            </div>
            <div class="col-sm-3 form-group mt-3">
                <div class="m-input-icon m-input-icon--right">
                    <date-picker 
                        v-model="currentFilter.date_to" 
                        valueType="format"
                        :format="formatDate">
                    </date-picker>    
                </div>
            </div>
            <div class="col-sm-3 mt-3">
                <button class="btn btn-primary color_button btn-search text-uppercase" @click="submitSearch()">
                    {{ translate('Tìm kiếm') }} <i class="fa fa-search ic-search m--margin-left-5"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import store from "../store/index";
import moment from 'moment';
import { mapActions, mapGetters } from "vuex";
import { getSearchOptionApi } from "@CustomerLead/utils/api";
import Select2 from 'v-select2-component';
import Multiselect from 'vue-multiselect'
import DatePicker from 'vue2-datepicker';
import Helper from "@CustomerLead/utils/helper";
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
            formatDate: 'DD/MM/YYYY',
            ranges: [],
            autoApply: true,
            isLoadingConfig: false,
        };
    },
    created() {

    },
    mounted() {

    },
    computed: {
        ...mapGetters({
            currentFilter: "currentFilter",
            searchOption: "searchOption",
            isLoading: "isLoading"
        }),
    },
    methods: {
        ...mapActions({
            getListCustomerLeadAction: "getListCustomerLeadAction",
            setCurrentFilterAction: "setCurrentFilterAction",
        }),

        translate(key){
            return Helper.translate(key);
        },

        convertDataPipelineSelect2(lists){
            if(!lists){
                return;
            }

            return lists.map(item => {
                return ({id: item.pipeline_id, text: item.pipeline_name})
            });
        },

        convertDataListWorkSelect2(lists){
            if(!lists){
                return;
            }
            
            return lists.map(item => {
                return ({id: item.manage_type_work_key, text: item.manage_type_work_name})
            });
        },

        renderDataListCustomerTypeSelect2(){
            return [{id: 'personal', text: 'Cá nhân'}, {id: 'business', text: 'Doanh nghiệp'}]
        },

        tranlateDatePicker(){
            var arrRange = {};
            arrRange[this.translate('Hôm nay')] = [moment(), moment()];
            arrRange[this.translate('Hôm qua')] = [
                moment().subtract(1, "days"),
                moment().subtract(1, "days"),
            ];
            arrRange[this.translate('7 ngày trước')] = [
                moment().subtract(6, "days"),
                moment(),
            ];
            arrRange[this.translate('30 ngày trước')] = [
                moment().subtract(29, "days"),
                moment(),
            ];
            arrRange[this.translate('Trong tháng')] = [
                moment().startOf("month"),
                moment().endOf("month"),
            ];
            arrRange[this.translate('Tháng trước')] = [
                moment().subtract(1, "month").startOf("month"),
                moment().subtract(1, "month").endOf("month"),
            ];

            this.ranges = arrRange;
        },

        submitSearch(){
            this.setCurrentFilterAction(this.currentFilter);
            this.getListCustomerLeadAction(this.currentFilter);
        },
    }
};
</script>

<style lang="scss" scoped>
    .calendars{
        flex-wrap: nowrap
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
