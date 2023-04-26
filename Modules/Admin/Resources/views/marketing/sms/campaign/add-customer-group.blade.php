<style>
    @media screen and (max-width: 480px) {
        .modal-lg {
            max-width: 100%;
        }
    }
</style>
<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title ss--title m--font-bold">
            <i class="la la-user-plus ss--icon-title m--margin-right-5"></i>
            {{__('THÊM NHÓM KHÁCH HÀNG')}}
        </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
        {{--Filter--}}
        <div class="ss--background">
            <div class="ss--bao-filter">
                <div class="row">
                    <div class="form-group m-form__group col-lg-4">
                        <select name="filter_type_group" id="filter_type_group" onchange="EditCampaign.searchCusGroupFilter();" class="form-control" style="width: 100%">
                            <option value="">{{__('Chọn loại nhóm khách hàng')}}</option>
                            <option value="user_define">{{__('Nhóm khách hàng tự định nghĩa')}}</option>
                            <option value="auto">{{__('Nhóm khách hàng tự động')}}</option>
                        </select>
                    </div>
                    <div class="form-group m-form__group col-lg-4">
                        <select name="customer_group_filter" id="customer_group_filter" class="form-control" style="width: 100%">
                            <option value="">{{__('Chọn nhóm khách hàng')}}</option>
{{--                            append option--}}
                        </select>
                    </div>
                    <div class="form-group m-form__group col-lg-4">
                        <button onclick="EditCampaign.searchCustomerGroup()" class="btn ss--button-cms-piospa ss--btn">
                            {{__('TÌM KIẾM')}}<i class="fa fa-search ic-search m--margin-left-5"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="table-responsive">
            <div class="m-scrollable m-scroller ps ps--active-y w-100 pr-0" data-scrollable="true"
                 style="height: 350px; overflow: hidden;">
                <table id="add-customer-group"
                       class="table table-striped m-table ss--header-table ss--nowrap">
                    <thead>
                    <tr class="ss--font-size-th">
                        <th width="5%">#</th>
                        <th width="50%">{{__('KHÁCH HÀNG')}}</th>
                        <th width="10%" class="ss--text-center">{{__('SĐT')}}</th>
                        <th width="10%" class="ss--text-center">{{__('NGÀY SINH')}}</th>
                        <th width="10%" class="ss--text-center">{{__('GIỚI TÍNH')}}</th>
                        <th>{{__('CHI NHÁNH')}}</th>
                        <th width="10%">
                            <label class="m-checkbox m-checkbox--state-success m--margin-bottom-15">
                                <input id="check-all-customer-group" type="checkbox" style="background-color: #fff">
                                <span></span>
                            </label>
                        </th>
                    </tr>
                    </thead>
                    <tbody class="customer_group_list">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="m-form__actions m--align-right m--margin-bottom-10">
            <span class="text-danger error-add-customer"></span>
        </div>
        <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
            <div class="m-form__actions m--align-right">
                <button data-dismiss="modal"
                        class="ss--btn-mobiles m--margin-bottom-5 btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn">
                    <span class="ss--text-btn-mobi">
                    <i class="la la-arrow-left"></i>
                    <span>{{__('HỦY')}}</span>
                    </span>
                </button>
                <button onclick="EditCampaign.chooseCustomerGroup()"
                        class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md  m--margin-bottom-5 m--margin-left-10">
							<span class="ss--text-btn-mobi">
							<i class="la la-check"></i>
							<span>{{__('THÊM NHÓM KHÁCH HÀNG')}}</span>
							</span>
                </button>
            </div>
        </div>
    </div>
</div>
