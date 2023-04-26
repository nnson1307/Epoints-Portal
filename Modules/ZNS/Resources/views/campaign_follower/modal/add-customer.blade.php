{{-- <div class="modal fade" id="add-customer" role="dialog"> --}}
<div class="modal-dialog modal-dialog-centered modal-big">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title ss--title m--font-bold">
                <i class="la la-user-plus ss--icon-title m--margin-right-5"></i>
                {{__('THÊM KHÁCH HÀNG')}}
            </h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            {{--Filter--}}
            <div class="ss--background">
                <form class="ss--bao-filter">
                    <div class="row">
                        <div class="form-group m-input col-lg-9">
                            <input class="form-control"
                                   id="keyword" name="keyword" autocomplete="off"
                                   placeholder="{{__('Nhập tên hoặc sđt khách hàng')}}"
                                   value="{{ isset($params['keyword'])?$params['keyword']:'' }}">
                        </div>
                        <div class="form-group m-form__group col-lg-3">
                            <button type="button" onclick="AddCampaign.showListCustomer('add-customer')"
                                    class="btn ss--button-cms-piospa ss--btn">
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
                    <table id="add-customer"
                           class="table table-striped m-table ss--header-table ss--nowrap">
                        <thead>
                        <tr class="ss--font-size-th">
                            <th width="5%">#</th>
                            <th width="20%">{{__('ẢNH ĐẠI DIỆN')}}</th>
                            <th width="30%" class="ss--text-center">{{__('NGƯỜI QUAN TÂM')}}</th>
                            <th width="30%" class="ss--text-center">{{__('TÊN KHÁCH HÀNG')}}</th>
                            <th width="15%">
                                <label class="m-checkbox m-checkbox--state-success m--margin-bottom-15">
                                    <input name="check_all_lead" class="check_all_lead" type="checkbox"
                                           style="background-color: #fff">
                                    <span></span>
                                </label>
                            </th>
                        </tr>
                        </thead>
                        <tbody class="customer_list">
                        @foreach ($list_customer as $key => $item)
                            <tr class="{{ $item['is_hide'] == 1 ?'d-none':'' }}">
                                <td>
                                    <input type="hidden" name="customer_id[{{ $item['zalo_customer_care_id'] }}]"
                                           value="{{ $item['zalo_customer_care_id'] }}" class="customer_id_class">
                                    {{$key+1}}
                                </td>
                                <td><img src="{{ $item['avatar'] }}" style="width:50px;height: 50px"></td>
                                <td class="ss--text-center">{{$item['zalo_user_id']}}</td>
                                <td class="ss--text-center">{{$item['full_name']}}</td>
                                <td>
                                    <label class="m-checkbox m-checkbox--bold m-checkbox--state-success m--padding-top-5">
                                        <input class="check_lead" name="check-lead" type="checkbox"{{ in_array($item['zalo_customer_care_id'],$id_customer_checked)?' checked':'' }}>
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
                    <button type="button" onclick="AddCampaign.chooseCustomer()"
                            class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md  m--margin-bottom-5 m--margin-left-10">
                                    <span class="ss--text-btn-mobi">
                                    <i class="la la-check"></i>
                                    <span>{{__('THÊM KHÁCH HÀNG')}}</span>
                                    </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- </div> --}}
