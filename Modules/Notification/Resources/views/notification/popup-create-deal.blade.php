<div class="modal fade show" id="modal-create" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg-email-campaign" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> @lang('TẠO DEAL')
                </h5>
            </div>
            <div class="modal-body">
                <form id="form-create-deal">
                    <div class="row">
                        <div class="form-group m-form__group col-12">
                            <label class="black_title">
                                @lang('Pipeline'):<b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <select class="form-control" id="pipeline_code" name="pipeline_code"
                                        style="width:100%;">
                                    <option></option>
                                    @foreach($optionPipeline as $v)
                                        <option value="{{$v['pipeline_code']}}">
                                            {{$v['pipeline_name']}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group m-form__group col-12">
                            <label class="black_title">
                                @lang('Hành trình'):<b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <select class="form-control journey" id="journey_code" name="journey_code"
                                        style="width:100%;">
                                    <option></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group m-form__group col-12">
                            <label class="black_title">
                                @lang('Ngày kết thúc dự kiến'):<b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control m-input" id="end_date_expected"
                                       name="end_date_expected"
                                       value=""
{{--                                           value="{{\Carbon\Carbon::parse($item['closing_date'])->format('d/m/Y')}}"--}}
                                       placeholder="@lang('Chọn ngày kết thúc dự kiến')">
                            </div>
                        </div>
                        <div class="form-group m-form__group col-12">
                            <label class="black_title">
                                @lang('Tổng tiền'):
                            </label>
                            <div class="input-group" id="amount-remove">
                                <input type="text" class="form-control m-input" id="amount" name="amount"
                                       value="">
{{--                                       value="{{number_format($item['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">--}}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <table class="table table-striped m-table m-table--head-bg-default" id="table_add">
                            <thead class="bg">
                            <tr>
                                <th class="tr_thead_list">@lang('Loại')</th>
                                <th class="tr_thead_list">@lang('Đối tượng')</th>
                                <th class="tr_thead_list">@lang('Giá')</th>
                                <th class="tr_thead_list">@lang('Số lượng')</th>
                                <th class="tr_thead_list">@lang('Giảm giá')</th>
                                <th class="tr_thead_list">@lang('Tổng tiền')</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody style="font-size: 13px" class="append-object">

                            </tbody>
                        </table>
                        <button type="button" class="btn btn-outline-info btn-sm m-btn m-btn--custom"
                                onclick="dealNoti.addObject()">
                            <i class="la la-plus"></i> @lang('THÊM')
                        </button>

                        <input type="hidden" class="form-control m-input" id="deal_type_code" name="deal_type_code" value="lead"
                               placeholder="">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="m-form__actions m--align-right w-100">
                    <button type="button" onclick="script.closeModalAddDeal()"
                            class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                    </button>
                    <button type="button" onclick="script.saveModalDeal()"
                            class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('LƯU THÔNG TIN')</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
