@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-staff.png')}}" alt=""
                style="height: 20px;">@lang('Thêm nội dung phản hồi')</span>
@endsection
@section("after_style")
@endsection
@section('content')
    <style>
        .modal-backdrop {
            position: relative !important;
        }
    </style>

    <!--begin::Portlet-->
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon">
                    <i class="flaticon-open-box"></i>
                </span>
                    <h2 class="m-portlet__head-text">
                        @lang('Thêm nội dung phản hồi')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
            </div>
        </div>
        <div class="col-md-12 ">
            <!-- Default box -->
            {{--        <a href="{{route("chathub.response-content")}}"><i class="fa fa-angle-double-left"></i> Back to all  <span class="text-lowercase">Response</span></a><br><br>--}}
            <form id="frmCreate">
                <div class="box">
                    <div class="box-body row">
                        <div class="form-group col-lg-12">
                            <label>@lang('Tiêu đề')</label>
                            <input type="text" name="title" class="form-control">
                        </div>
                        <div class="form-group col-lg-12">
                            <label>@lang('Nội dung phản hồi')</label>
                            <textarea name="response_content" class="form-control"></textarea>
                        </div>
                        <div class="form-group col-lg-4">
                            <div class="checkbox">
                                <label>
                                        <input type="checkbox" value="1" name="response_target">
                                    @lang('Câu trả lời đúng mục tiêu')
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-4">
                            <div class="checkbox">
                                <label>
                                        <input type="checkbox" value="1" name="response_end">
                                    @lang('Câu trả lời kết thúc hội thoại')
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-4">
                            <div class="checkbox">
                                <label>
                                        <input type="checkbox" value="1" name="is_personalized">
                                    @lang('Cá nhân hoá câu trả lời')
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-4">
                            <div class="checkbox">
                                <label>
                                        <input type="checkbox" value="1" name="response_forward">
                                    @lang('Là câu trả lời điều hướng')
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-sm-8">
                            <label>@lang('Nhãn hàng')</label>
                            <select name="brand_entities" class="form-control select">
                                <option value="">-</option>
                                @foreach($brand as $v)
                                        <option value="{{$v['entities']}}">{{$v['brand_name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-4">
                            <label>@lang('Loại mẫu')</label>
                            <select name="template_type" class="form-control select" id="template_type">
                                <option value="">-</option>
                                <option value="generic">@lang('Generic')</option>
                                <option value="list">@lang('List')</option>
                            </select>
                            <script>
                                // function MyDisabled1(){
                                //     if($("#template_type").attr('disabled')){
                                //         $("#template_type").attr("disabled", false);
                                //     }else{
                                //         $("#template_type").val('');
                                //         $("#template_type").attr("disabled", true);
                                //     }
                                // }
                            </script>
                        </div>
                        <div class="form-group col-sm-12">
                            <label>@lang('Mẫu')</label>
                            <div id="append-template">
                            </div>
                        </div>
                        <div class="form-group col-sm-12">
                            <button type="button" onclick="response.popupAddTemplate()" class="btn btn-primary ladda-button"><span class="ladda-label"><i class="fa fa-plus"></i> @lang('Thêm mẫu')</span></button>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                        <div class="m-form__actions m--align-right">
                            <a href="{{route("chathub.response-content")}}" class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                                <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('HỦY')</span>
                                </span>
                            </a>
                            <button type="button" onclick="response_content_submit.Save()" class="btn btn-primary color_button  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                                    <span>
                                    <i class="la la-edit"></i>
                                    <span>@lang('LƯU THÔNG TIN')</span>
                                    </span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div id="modal"></div>
    <div id="modal"></div>
    <div hidden>
        <select id="response_element_id">

        </select>
    </div>
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/chathub/response_content/add.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/chathub/response_content/response.js?v='.time())}}" type="text/javascript"></script>
    <script>
        $('.select').select2();
        function MyDisabled(){
            if($('#url').attr('disabled')){
                $('#payload').val('');
                $("#url").attr("disabled", false);
                $("#payload").attr("disabled", true);
            }else{
                $('#url').val('');
                $("#url").attr("disabled", true);
                $("#payload").attr("disabled", false);
            }
        }
    </script>
@stop