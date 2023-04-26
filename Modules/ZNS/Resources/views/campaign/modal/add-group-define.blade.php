{{-- <div class="modal fade" id="add-group-defind" role="dialog"> --}}
    <div class="modal-dialog modal-dialog-centered modal-big">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title ss--title m--font-bold">
                    <i class="la la-user-plus ss--icon-title m--margin-right-5"></i>
                    {{__('THÊM KHÁCH HÀNG TỰ ĐỊNH NGHĨA')}}
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                {{--Filter--}}
                <div class="ss--background">
                    <form class="ss--bao-filter">
                        <div class="row">
                            <div class="form-group m-form__group col-lg-4">
                                <select name="filter_type_group" class="form-control" style="width: 100%">
                                    <option value="">{{__('Chọn loại nhóm khách hàng')}}</option>
                                    <option value="user_define"{{ isset($params['filter_type_group']) && $params['filter_type_group'] == 'user_define'?' selected':'' }}>{{__('Nhóm khách hàng tự định nghĩa')}}</option>
                                    <option value="auto"{{ isset($params['filter_type_group']) && $params['filter_type_group'] == 'auto'?' selected':'' }}>{{__('Nhóm khách hàng tự động')}}</option>
                                </select>
                            </div>
                            <div class="form-group m-form__group col-lg-4">
                                <select name="customer_group_filter" id="customer_group_filter" class="form-control" style="width: 100%">
                                    <option value="">{{__('Chọn nhóm khách hàng')}}</option>
                                </select>
                            </div>
                            <div class="form-group m-form__group col-lg-4">
                                <button type="button" onclick="AddCampaign.showListCustomer('add-group-define',1)" class="btn ss--button-cms-piospa ss--btn">
                                    {{__('TÌM KIẾM')}}<i class="fa fa-search ic-search m--margin-left-5"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <br>
                <div class="table-responsive">
                    <div class="m-scrollable m-scroller ps--active-y w-100 pr-0" data-scrollable="true"
                         style="height: 350px; overflow: scroll;">
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
                                        <input class="check_all_lead" name="check_all_lead" type="checkbox" style="background-color: #fff">
                                        <span></span>
                                    </label>
                                </th>
                            </tr>
                            </thead>
                            <tbody class="customer_group_list">
                                @foreach ($list_customer as $key => $item)
                                <tr>
                                    <td>
                                        <input type="hidden" name="customer_id[{{ $item['customer_id'] }}]" value="{{ $item['customer_id'] }}" class="customer_id_class">
                                        <input type="hidden" name="type_customer[{{ $item['customer_id'] }}]" value="customer">
                                        {{$key+1}}
                                    </td>
                                    <td>{{$item['full_name']}}</td>
                                    <td class="ss--text-center">{{$item['phone']}}</td>
                                    <td class="ss--text-center">{{$item['birthday']}}</td>
                                    <td class="ss--text-center">{{$item['gender_name']}}</td>
                                    <td class="">{{$item['branch_name']}}</td>
                                    <td>
                                        <label class="m-checkbox m-checkbox--bold m-checkbox--state-success m--padding-top-5">
                                            <input class="check_lead" name="check-lead" type="checkbox">
                                            <span></span>
                                        </label>
                                    </td>
                                </tr>
                                @endforeach
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
                        <button onclick="AddCampaign.chooseCustomer()"
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
    </div>
{{-- </div> --}}

