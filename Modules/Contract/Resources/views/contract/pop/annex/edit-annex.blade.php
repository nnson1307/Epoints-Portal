<div class="modal fade show" id="edit-annex" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> @lang('CHỈNH SỬA PHỤ LỤC')
                </h5>
            </div>
            <div class="modal-body">
                <form id="form-edit-annex" class="row">
                    <input type="hidden" id="annex_contract_id" name="annex_contract_id" value="{{$contractId}}">
                    <input type="hidden" id="annex_contract_annex_id" name="annex_contract_annex_id" value="{{$item['contract_annex_id']}}">
                    <div class="form-group col-lg-12 float-right">
                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                            <label style="margin: 0 0 0 10px">
                                <input type="checkbox" {{$item['is_active'] == 1 ? 'checked' : ''}} class="manager-btn" name="is_active" id="is_active">
                                <span></span>
                            </label>
                        </span>
                    </div>
                    <div class="form-group m-form__group col-lg-6">
                        <label class="black_title">
                            @lang('Mã phụ lục'):<b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <input type="text" class="form-control m-input" value="{{$item['contract_annex_code']}}" id="annex_contract_annex_code" name="annex_contract_annex_code">
                        </div>
                    </div>
                    <div class="form-group m-form__group col-lg-6">
                        <label class="black_title">
                            @lang('Ngày có hiệu lực'):<b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <input type="text" class="form-control m-input" id="annex_effective_date" value="{{date('d/m/Y', strtotime($item['effective_date']))}}" name="annex_effective_date"
                                   placeholder="@lang('Chọn ngày có hiệu lực')">
                        </div>
                    </div>
                    <div class="form-group m-form__group col-lg-6">
                        <label class="black_title">
                            @lang('Ngày ký phụ lục'):<b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <input type="text" class="form-control m-input" id="annex_sign_date" value="{{date('d/m/Y',strtotime($item['sign_date']))}}" name="annex_sign_date"
                                   placeholder="@lang('Chọn ngày ký phụ lục')">
                        </div>
                    </div>
                    <div class="form-group m-form__group col-lg-6">
                        <label class="black_title">
                            @lang('Ngày hết hiệu lực'):<b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <input type="text" class="form-control m-input" id="annex_expired_date" value="{{date('d/m/Y',strtotime($item['expired_date']))}}" name="annex_expired_date"
                                   placeholder="@lang('Chọn ngày hết hiệu lực')">
                        </div>
                    </div>
                    <div class="form-group m-form__group col-lg-12">
                        <label class="black_title">
                            @lang('Loại điều chỉnh'):<b class="text-danger">*</b>
                        </label>
                        <div class="m-form__group form-group">
                            <div class="m-radio-inline">
                                <label class="m-radio cus">
                                    <input type="radio" name="annex_adjustment_type" onclick="contractAnnex.changeSubmitAnnex()"
                                           {{$item['adjustment_type'] == 'update_contract' ? 'checked' : ''}}
                                           value="update_contract"> {{__('Cập nhật hợp đồng')}}
                                    <span></span>
                                </label>
                                <label class="m-radio cus">
                                    <input type="radio" name="annex_adjustment_type" onclick="contractAnnex.changeSubmitAnnex()"
                                           {{$item['adjustment_type'] == 'renew_contract' ? 'checked' : ''}}
                                           value="renew_contract"> {{__('Gia hạn hợp đồng')}}
                                    <span></span>
                                </label>
                                <label class="m-radio cus">
                                    <input type="radio" name="annex_adjustment_type" onclick="contractAnnex.changeSubmitAnnex()"
                                           {{$item['adjustment_type'] == 'update_info' ? 'checked' : ''}}
                                           value="update_info"> {{__('Bổ sung thông tin')}}
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group col-lg-12">
                        <label class="black_title">
                            @lang('Nội dung'):<b class="text-danger">*</b>
                        </label>
                        <div class="form-group m-form__group">
                            <div class="input-group m-input-group">
                            <textarea id="annex_content" name="annex_content" class="form-control autosizeme" rows="12"
                                      placeholder="{{__('Nhập nội dung')}}"
                                      data-autosize-on="true"
                                      style="overflow: hidden; overflow-wrap: break-word; resize: horizontal;">{{$item['content']}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group col-lg-12">
                        <label>
                            {{__('Hồ sơ đính kèm')}}:
                        </label>
                        <div id="contract_annex_list_files" class="row">
                            @if($item['list_link'] != '')
                                @foreach(explode(',',$item['list_link']) as $key => $value)
                                    <div class="col-lg-12">
                                        <a href="{{$value}}" value="{{explode(',', $item['list_name'])[$key]}}" name="contract_annex_list_files[]" class="ss--text-black" download="{{explode(',', $item['list_name'])[$key]}}">{{explode(',', $item['list_name'])[$key]}}</a>
                                        <a href="javascript:void(0)" onclick="$(this).parent('div').remove()"><i class="la la-trash"></i></a>
                                        <br>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="m-widget19__action">
                            <input id="upload_file_cc" type="file"
                                   class="btn btn-primary btn-sm color_button m-btn text-center"
                                   style="display: none"
                                   onchange="contractAnnex.uploadFileCc(this)"
                            >
                            <button type="button" onclick="document.getElementById('upload_file_cc').click()"
                                    class="btn btn-primary btn-sm color_button m-btn text-center">
                                {{ __('Tải hồ sơ') }}
                            </button>
                        </div>
                    </div>
                </form>
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
                    <button type="button" onclick="contractAnnex.actionUpdateAnnexSaveOrContinue(0)"
                            {{$item['adjustment_type'] == 'update_info' ? '' : 'hidden'}}
                            class="annex_save btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('LƯU THÔNG TIN')</span>
                        </span>
                    </button>
                    <button type="button" onclick="contractAnnex.actionUpdateAnnexSaveOrContinue(1)"
                            {{$item['adjustment_type'] == 'update_info' ? 'hidden' : ''}}
                            class="annex_continue btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                        <span>
                            <i class="la la-arrow-right"></i>
                            <span>@lang('TIẾP THEO')</span>
                    </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
