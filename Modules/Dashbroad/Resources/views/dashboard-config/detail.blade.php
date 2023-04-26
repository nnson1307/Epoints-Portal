@extends('layout')
@section('title_header')
    <span class="title_header">@lang('DANH SÁCH CẤU HÌNH BỐ CỤC TỔNG QUAN')</span>
@stop
<style>
    div.widget-style{
        -webkit-box-flex: 0;
        -ms-flex: 0 0 33.33333333%;
        flex: 0 0 33.33333333%;
        max-width: 100%;
        min-height: 1px;
        padding-right: 5px;
        padding-left: 5px;
        box-sizing: border-box;
        margin-bottom: 10px;
        text-align: center;
    }
    div>span>img{
        display: block;
        cursor: move;
        padding: 12px 10px;
        font-size: 12px;
        color: #53595f;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        transition: all 400ms;
        -webkit-transition: all 400ms;
        background: #fff;
        border-radius: 3px;
        border: 1px solid #e8e8e8;
    }
    div>span>span{
        display: block;
        cursor: move;
        padding: 12px 10px;
        font-size: 12px;
        color: #53595f;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        transition: all 400ms;
        -webkit-transition: all 400ms;
        background: #fff;
        border-radius: 3px;
        border: 1px solid #e8e8e8;
    }
    div>span>span>span{
        display: block;
        overflow: hidden;
    }
    div div>span>span>i {
        margin-bottom: 3px;
        color: #53595f;
        font-size: 18px;
        transition: 400ms;
        display: block;
    }
    div div>span>span>span {
        transition: all 400ms;
        -webkit-transition: all 400ms;
        position: relative;
        top: 1px;
    }
    h5 {
        text-align: center;
        padding: 0;
        margin: 15px 10px 20px;
        font-weight: 400;
        color: #969ca2;
        position: relative;
        font-size: 14px;
    }
    div.widget-padding{
        padding: 3px 3px;
    }
    div.widget-drag{
        opacity: 0.4;
    }
    div.parent-col-style{
        border-radius: 3px;
        border: 1px solid #e8e8e8;
        margin: 1px -1px;
    }
    div.on-hover-img {
        display: none;
    }
</style>
@section('content')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="flaticon-list-1"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        @lang("CHI TIẾT BỐ CỤC TỔNG QUAN")
                    </h2>
                </div>
            </div>

            <div class="m-portlet__head-tools">
            <a href="{{route('dashbroad.dashboard-config')}}"
               class="btn btn-metal bold-huy  m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>@lang("QUAY LẠI")</span>
						</span>
            </a>
            </div>
        </div>
        <div class="m-portlet__body">
            @foreach($lstComponentDefault as $key => $value)
                <div data-component-type="{{$value['component_type']}}" ondragover="dragOver(event);" ondrop="drop(this,event);"  class="ui-state-default ui-sortable-handle dashboard-sortable ui-sortable row widget-style" style="min-height: 100px">
                    <div class="col-lg-12 unsortable">
                    <span class="float-right">
                        <i class="la la-remove" draggable="false"  onclick="removeComponent(this)"></i>
                    </span>
                    </div>
                    @foreach($value['widget'] as $k => $v)
                        <div class="col-lg-{{$v['size_column']}}">
                            <input type="hidden" name="dashboard_widget_id" value="{{$v['dashboard_widget_id']}}">
                            <input type="hidden" name="component-widget" value="{{$v['widget_code']}}">
                            <div class="ui-state-default ui-sortable-handle widget-padding" data-col="{{$v['size_column']}}">
                        <span title="{{$v['widget_name']}}">
                            <label class="float-left">
                                {{$v['widget_display_name']}}
                            </label>
                            <div class="on-hover-img float-right">
                                <span>
                                    <i class="la la-edit" draggable="true" onclick="editWidget(this, '{{$v['widget_code']}}','{{$v['widget_display_name']}}')"></i>
                                    <i class="la la-remove" draggable="true"  onclick="removeWidget(this, '{{$v['widget_code']}}')"></i>
                                </span>
                            </div>
                            <img class="m--bg-metal m-image img-sd " src="{{asset('static/backend/images/dashboard') .'/' . $v['image']}}"
                                 alt="Hình ảnh" width="100%" height="{{$v['size_column']/3*100}}px !important">
                        </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
    <div id="my-modal"></div>
@endsection
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
@stop
