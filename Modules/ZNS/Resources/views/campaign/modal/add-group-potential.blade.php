{{-- <div class="modal fade show" id="add-group-potential"> --}}
    <div class="modal-dialog modal-dialog-centered modal-big">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> {{__('THÊM KHÁCH HÀNG TIỀM NĂNG')}}
                </h5>
                {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
                {{--<span aria-hidden="true">×</span>--}}
                {{--</button>--}}
            </div>
            <div class="modal-body">
                <div class="ss--background">
                    <form class="ss--bao-filter">
                        <div class="row">
                            <div class="col-lg-3 form-group">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="lead_search" id="lead_search"
                                           placeholder="@lang("Nhập tên khách hàng hoặc tên người được phân công")" value="{{ isset($params['lead_search'])?$params['lead_search']:'' }}">
                                </div>
                            </div>
                            <div class="col-lg-3 form-group">
                                <div class="m-input-icon m-input-icon--right">
                                    <select class="form-control" id="lead_type_customer" name="lead_type_customer">
                                        <option value="">@lang("Chọn loại khách hàng")</option>
                                        <option value="personal"{{ isset($params['lead_type_customer']) && $params['lead_type_customer'] == 'personal'?' selected':'' }}>@lang('Cá nhân')</option>
                                        <option value="business"{{ isset($params['lead_type_customer']) && $params['lead_type_customer'] == 'business'?' selected':'' }}>@lang('Doanh nghiệp')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 form-group">
                                <div class="m-input-icon m-input-icon--right">
                                    <select class="form-control"
                                            id="lead_customer_source"
                                            name="lead_customer_source_id">
                                        <option value="">@lang("Chọn nguồn khách hàng")</option>
                                        @foreach($optionCustomerSources as $key => $value)
                                            <option value="{{$value['customer_source_id']}}"{{ isset($params['lead_customer_source_id']) && $params['lead_customer_source_id'] == $value['customer_source_id']?' selected':'' }}>{{$value['customer_source_name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 form-group">
                                <div class="m-input-icon m-input-icon--right">
                                    <select class="form-control" id="lead_sale_status" name="lead_sale_status">
                                        <option value="">@lang('Chọn trạng thái')</option>
                                        <option value="1"{{ isset($params['lead_sale_status']) && $params['lead_sale_status'] == 1?' selected':'' }}>@lang('Đã phân công')</option>
                                        <option value="0"{{ isset($params['lead_sale_status']) && $params['lead_sale_status'] == 0?' selected':'' }}>@lang('Chưa phân công')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 form-group">
                                <div class="m-input-icon m-input-icon--right">
                                    <select class="form-control" id="lead_pipeline_code"
                                            name="lead_pipeline_code">
                                        <option value="">@lang("Chọn pipeline")</option>
                                        @foreach($optionPipeline as $key => $value)
                                            <option value="{{$value['pipeline_code']}}"{{ isset($params['lead_pipeline_code']) && $params['lead_pipeline_code'] == $value['pipeline_code']?' selected':'' }}>{{$value['pipeline_name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 form-group">
                                <div class="m-input-icon m-input-icon--right">
                                    <select class="form-control" id="lead_journey_code" name="lead_journey_code">
                                        <option value="">@lang("Chọn hành trình")</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 form-group">
                                <button type="button" class="btn btn-primary color_button btn-search" onclick="AddCampaign.showListCustomer('add-group-potential',1)">
                                    @lang('TÌM KIẾM') <i class="fa fa-search ic-search m--margin-left-5"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="table-responsive m--margin-top-30">
                    <div class="m-scrollable m-scroller ps--active-y w-100 pr-0" data-scrollable="true"
                         style="height: 300px; overflow: scroll;">
                        <table class="table table-striped m-table m-table--head-bg-default customer_lead_list">
                            <thead class="bg">
                            <tr>
                                <th width="2%" class="tr_thead_list">#</th>
                                <th width="50%" class="tr_thead_list">{{__('KHÁCH HÀNG')}}</th>
                                <th width="10%" class="tr_thead_list">{{__('SĐT')}}</th>
                                <th width="10%" class="tr_thead_list">{{__('NGƯỜI ĐƯỢC PHÂN BỔ')}}</th>
                                <th width="10%" class="tr_thead_list">{{__('LOẠI KHÁCH HÀNG')}}</th>
                                <th width="10%" class="tr_thead_list">{{__('NGUỒN KHÁCH HÀNG')}}</th>
                                <th width="10%" class="tr_thead_list">{{__('PIPELINE')}}</th>
                                <th width="10%" class="tr_thead_list">{{__('HÀNH TRÌNH')}}</th>
                                <th width="10%" class="tr_thead_list">
                                    <label class="m-checkbox m-checkbox--bold m-checkbox--state-success m--padding-top-5">
                                        <input class="check_all_lead" name="check_all_lead" type="checkbox">
                                        <span></span>
                                    </label> {{__('TẤT CẢ')}}
                                </th>
                            </tr>
                            </thead>
                            <tbody class="customer_lead_list_body" style="font-size: 13px">
                                @foreach ($list_customer as $key => $item)
                                <tr>
                                    <td>
                                        <input type="hidden" name="customer_id[{{ $item->customer_lead_id }}]" value="{{ $item->customer_lead_id }}" class="customer_id_class">
                                        <input type="hidden" name="type_customer[{{ $item['customer_id'] }}]" value="lead">
                                        {{$key+1}}
                                    </td>
                                    <td>{{$item->full_name}}</td>
                                    <td>{{$item->phone}}</td>
                                    <td>{{$item->sale_name}}</td>
                                    <td>{{$item->customer_type}}</td>
                                    <td>{{$item->customer_source_name}}</td>
                                    <td>{{$item->pipeline_name}}</td>
                                    <td>{{$item->journey_name}}</td>
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
                    <span class="error_append" style="color: red"></span>
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-form__actions m--align-right w-100">
                    <button data-dismiss="modal"
                            class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                    </button>
                    <button type="button" onclick="AddCampaign.chooseCustomer()"
                            class="btn btn-info  color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<i class="la la-check"></i>
							<span>{{__('CHỌN')}}</span>
							</span>
                    </button>

                </div>
            </div>
        </div>
    </div>
{{-- </div> --}}