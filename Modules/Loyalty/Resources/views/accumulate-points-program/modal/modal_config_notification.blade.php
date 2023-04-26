<form action="" id="form-submit-template">
    <div class="modal fade" id="modal_notification" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-custom" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title m-portlet__head-text tab" id="exampleModalLabel">
                        <i class="fa fa-plus-circle"></i>
                        {{ __('CÀI ĐẶT HIỂN THỊ SAU KHI HOÀN THÀNH KHẢO SÁT') }}
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="form-group kt-margin-t-15">
                        <div class="row">
                            <div class="col-lg-3" style="color: black; font-weight:400">
                                {{ __('Hình ảnh') }}
                            </div>
                            <div class="col-lg-9">
                                <div class="kt-avatar kt-avatar--outline" id="kt_user_add_avatar">
                                    <div id="logo-image">
                                        <div class="kt-avatar__holder"
                                            style="background-image: url({{ $data['avatar'] }});
                                                     background-position: center;
                                                     background-size: 100% 100%;">
                                        </div>
                                    </div>
                                    <input type="hidden" id="detail_background" name="detail_background"
                                        value="{{ $data['avatar'] }}">
                                    <label class="kt-avatar__upload" data-toggle="kt-tooltip" title=""
                                        data-original-title="">
                                        <i class="fa fa-pen"></i>
                                        <input type="file" id="getFileLogo" name="getFileLogo"
                                            accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg"
                                            onchange="loyalty.uploadBackground(this);">
                                    </label>
                                    <span class="kt-avatar__cancel" data-toggle="kt-tooltip" title=""
                                        data-original-title="">
                                        <i class="fa fa-times"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-3" style="color: black; font-weight:400">
                                {{ __('Tiêu đề') }}
                            </div>
                            <div class="col-lg-9">
                                <input type="text" class="form-control" value="{{ $data['title'] }}" id="title_template" name="title_template">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-3" style="color: black; font-weight:400">
                                {{ __('Nội dung tóm tắt') }}
                            </div>
                            <div class="col-lg-9">
                                <textarea class="form-control" rows="3" name="message_template" id="message_template">{{ $data['message'] }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-3" style="color: black; font-weight:400">
                                {{ __('Nội dung chi tiết') }}
                            </div>
                            <div class="col-lg-9">
                                <textarea class="form-control" rows="3" name="des_detail_template" id="des_detail_template">{{ $data['detail_content'] }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-3" style="color: black; font-weight:400">
                                    {{__('Biến mặt định')}}
                                </div>
                                <div class="col-lg-9 d-flex">
                                    @php
                                        $paramShow = json_decode($data['params_show'], true);
                                    @endphp
                                    @foreach($paramShow as $key => $value)
                                        <div class="param-show-1 mr-2">
                                            <button
                                                    type="button" class="btn btn-default btn-popover pop"
                                                    
                                                    style="width:100%; color: #A8A0A0; text-align: center;" class="pop"
                                                    data-container="body" data-toggle="popover" data-placement="bottom"
                                                    data-content="{{!empty($param['is_show']) ? '' : __('Bạn đã copy thành công')}}"
                                                    onclick="loyalty.copyCode('#p1_{{$key}}', '{{$key}}')">
                                                {{$value}}
                                            </button>
                                            <p style="opacity: 0.001" id="p1_{{$key}}">{{$value}}</p>
                                            <input style="opacity: 0.001" class="textBox" type="text" id="code_here_{{$key}}"/>
                                            <input type="hidden" name="params_show_template" class="params_show_template"
                                                   id="params_show_template" value="{{$value}}">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Huỷ') }}</button>
                    <button type="button" class="btn btn-primary color_button btn-search"
                        onclick="loyalty.updateTemplate()">{{ __('Lưu') }}</button>
                </div>
            </div>
        </div>
    </div>
</form>
