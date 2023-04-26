@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-staff.png')}}" alt=""
                style="height: 20px;">@lang('Chỉnh sửa nội dung phản hồi')</span>
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
                    @lang('Chỉnh sửa nội dung phản hồi')
                </h2>
            </div>
        </div>
        <div class="m-portlet__head-tools">
        </div>
    </div>
    <div class="col-md-12 ">
        <!-- Default box -->
{{--        <a href="{{route("chathub.response-content")}}"><i class="fa fa-angle-double-left"></i> Back to all  <span class="text-lowercase">Response</span></a><br><br>--}}
        <form id="frmEdit">
            <div class="box">
                <div class="box-body row">
                    <div class="form-group col-lg-12">
                        <label>@lang('Tiêu đề')</label>
                        <input type="text" name="title" value="{{$item["title"]}}" class="form-control">
                    </div>
                    <div class="form-group col-lg-12">
                        <label>@lang('Nội dung phản hồi')</label>
                        <textarea name="response_content" class="form-control">{{$item["response_content"]}}</textarea>
                    </div>
                    <div class="form-group col-lg-4">
                        <div class="checkbox">
                            <label>
                                @if($item["response_target"] == 1)
                                    <input type="checkbox" value="1" name="response_target" checked="checked">
                                @else
                                    <input type="checkbox" value="1" name="response_target">
                                @endif
                                    @lang('Câu trả lời đúng mục tiêu')
                            </label>
                        </div>
                    </div>
                    <div class="form-group col-lg-4">
                        <div class="checkbox">
                            <label>
                                @if($item["response_end"] == 1)
                                    <input type="checkbox" value="1" name="response_end" checked="checked">
                                @else
                                    <input type="checkbox" value="1" name="response_end">
                                @endif
                                    @lang('Câu trả lời kết thúc hội thoại')
                            </label>
                        </div>
                    </div>
                    <div class="form-group col-lg-4">
                        <div class="checkbox">
                            <label>
                                @if($item["is_personalized"] == 1)
                                    <input type="checkbox" value="1" name="is_personalized" checked="checked">
                                @else
                                    <input type="checkbox" value="1" name="is_personalized">
                                @endif
                                    @lang('Cá nhân hoá câu trả lời')
                            </label>
                        </div>
                    </div>
                    <div class="form-group col-lg-4">
                        <div class="checkbox">
                            <label>
                                @if($item["response_forward"] == 1)
                                    <input type="checkbox" value="1" name="response_forward" checked="checked">
                                @else
                                    <input type="checkbox" value="1" name="response_forward">
                                @endif
                                    @lang('Là câu trả lời điều hướng')
                            </label>
                        </div>
                    </div>
                    <div class="form-group col-sm-8">
                        <label>@lang('Nhãn hàng')</label>
                        <select name="brand_entities" class="form-control select">
                            <option value="">-</option>
                            @foreach($brand as $v)
                                @if($v['entities'] == $item['brand_entities'])
                                    <option value="{{$v['entities']}}" selected>{{$v['brand_name']}}</option>
                                @else
                                    <option value="{{$v['entities']}}">{{$v['brand_name']}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-lg-4">
                        <label>@lang('Loại mẫu')</label>
                        <select name="template_type" class="form-control select" id="template_type">
                            <option value="">-</option>
                            <option value="generic" {{$item['template_type'] == 'generic' ? 'selected' : ''}}>@lang('Generic')</option>
                            <option value="list" {{$item['template_type'] == 'list' ? 'selected' : ''}}>@lang('List')</option>
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
                            @if (isset($element))
                                @foreach($element as $v)

                                    <div class="form-group col-sm-12 d-flex row" style="margin-top: 35px" id="element{{$v['response_element_id']}}"  data-value="{{$v['response_element_id']}}"  name="response_element_id">
                                        <div class="col-sm-12">
                                            <img src="{{$v['image_url']}}" height="83px" width="159px">
                                            <h3>{{$v['title']}}</h3>
                                            <p>{{$v['subtitle']}}</p>
                                            <button type="button" class="btn btn-primary m-btn m-btn--icon" onclick="response.popupAddButton({{$v['response_element_id']}})">
                                                <i class="fa fa-plus" aria-hidden="true"></i>
                                            </button>
                                            <div class="d-flex" style="margin-bottom: 15px" id='button_{{$v['response_element_id']}}'>
                                                @foreach ($v['child'] as $it)
                                                    <div class="btn-group" id="btn_{{$it->response_button_id}}">
                                                        <button type="button" onclick="response.popupEditButton({{$it->response_button_id}})" class="btn btn-primary">{{$it->title}}</button>
                                                        <button type="button" onclick="response.removeButton({{$it->response_button_id}})" class="btn btn-primary">X</button>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div  class="col-sm-12">
                                        <button type="button" type="button" onclick="response.popupEditTemplate({{$v['response_element_id']}})" class="btn btn-success m-btn m-btn--icon" id="m_search">
                                            <span>
                                                <span>@lang('Sửa mẫu')</span>
                                            </span>
                                        </button>

                                        <button type="button" onclick="response.removeTemplate({{$v['response_element_id']}})" class="btn btn-warning m-btn m-btn--icon" id="m_search">
                                            <span>
                                                <span>@lang('Xoá mẫu')</span>
                                            </span>
                                        </button>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="form-group col-sm-12">
                        <button type="button" onclick="response.popupAddTemplate()" class="btn btn-primary ladda-button"><span class="ladda-label"><i class="fa fa-plus"></i> @lang('Thêm mẫu')</span></button>
                    </div>
                    <div class="form-group col-md-12">
                        <input type="hidden" name="response_content_id" value="{{$item["response_content_id"]}}" class="form-control">
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
                        <button type="button" onclick="response_content_submit.Edit()" class="btn btn-primary color_button  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                                    <span>
                                    <i class="la la-edit"></i>
                                    <span>@lang('CẬP NHẬT')</span>
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