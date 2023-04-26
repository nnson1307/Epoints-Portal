<template>
    <div class="frmFilter">
        <div class="form-group m-form__group row">
            <div class="col-sm-3">
                <div class="input-group">
                    <input type="text" 
                        class="form-control" 
                        v-model="currentFilter.search" 
                        :placeholder="translate('Nhập thông tin khách hàng')" />
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
                    v-model="currentFilter.type_customer"
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
            <div class="col-sm-3 mt-3">
                <Select2
                    :placeholder="translate('Chọn chi nhánh')"
                    v-model="currentFilter.branch_code"
                    :options="convertDataBranchSelect2(searchOption.optionBranches)"
                />
            </div>
            <div class="col-sm-3 mt-3">
                <Select2
                    :placeholder="translate('Chọn nguồn đơn hàng')"
                    v-model="currentFilter.order_source_id"
                    :options="convertDataOrderSourceSelect2(searchOption.optionOrderSource)"
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
            <div class="col-sm-12 text-right mt-3">
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
import { getSearchOptionApi } from "@CustomerDeal/utils/api";
import Select2 from 'v-select2-component';
import Multiselect from 'vue-multiselect'
import DatePicker from 'vue2-datepicker';
import Helper from "@CustomerDeal/utils/helper";
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
            getListCustomerDealAction: "getListCustomerDealAction",
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

        convertDataBranchSelect2(lists){
            if(!lists){
                return;
            }

            return lists.map(item => {
                return ({id: item.branch_code, text: item.branch_name})
            });
        },

        convertDataOrderSourceSelect2(lists){
            if(!lists){
                return;
            }

            return lists.map(item => {
                return ({id: item.order_source_id, text: item.order_source_name})
            });
        },

        convertDataListWorkSelect2(lists){
            if(!lists){
                return;
            }
            
            return lists.map(item => {
                return ({id: item.manage_type_work_id, text: item.manage_type_work_name})
            });
        },

        renderDataListCustomerTypeSelect2(){
            return [{id: 'customer', text: this.translate('Khách hàng')}, {id: 'lead', text: this.translate('Khách hàng tiềm năng')}]
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
            this.getListCustomerDealAction(this.currentFilter);
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
