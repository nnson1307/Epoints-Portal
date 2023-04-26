@extends('layout')

@section('page_subheader')
    @include('components.subheader', ['title' => 'Chi tiết In Thẻ dịch vụ'])
@stop

@section('content')
    <style>
        .form-control-feedback {
            color: #ff0000;
        }
    </style>

    <div class="row">
        <div class="col-xl-12">
            <div class="m-portlet m-portlet--creative m-portlet--bordered-semi">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            {{--<span class="m-portlet__head-icon">--}}
                            {{--<i class="flaticon-statistics"></i>--}}
                            {{--</span>--}}
                            {{--<h3 class="m-portlet__head-text">--}}
                            {{--Danh sách Thẻ dịch vụ--}}
                            {{--</h3>--}}
                            <h2 class="m-portlet__head-label m-portlet__head-label--primary">
                                <span>{{__('Chi tiết In Thẻ dịch vụ')}}</span>
                            </h2>
                        </div>
                    </div>

                </div>
                <div class="m-portlet__body" id="autotable">
                    <div class="col-md-12 row form-group">
                        <div class="col-md-6">
                            {!! Form::open(["id"=>"form", 'class' => ' m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed ']) !!}
                            <div class="col-md-12">
                                <div class="col-sm-12 row form-group">
                                    <label>{{__('Thẻ dịch vụ')}} : </label>
                                    {!! Form::hidden("service_card_id",$card_detail->service_card_id,["class"=>"form-control","disabled"]); !!}
                                    {!! Form::hidden("branch_id",$card_detail->branch_id,["class"=>"form-control","disabled"]); !!}

                                    {!! Form::text("card_name",$card_detail->name,["class"=>"form-control","disabled"]); !!}
                                </div>

                                <div class="col-sm-12 row form-group">
                                    <label>{{__('Mã định danh')}} : </label>
                                    {!! Form::text("code",$card_detail->code,["class"=>"form-control","disabled"]); !!}
                                </div>

                                <div class="col-sm-12 row form-group">
                                    <div class="col-sm-6 kil-padding-right kill-padding-left">
                                        <label>{{__('Giá bán')}} : </label>
                                        <div class="col-sm-12 row form-group" style="padding: 0 0 0 15px">
                                            <div class="col-md-9 kil-padding-right kill-padding-left">
                                                {!! Form::text("price",$card_detail->price,["class"=>"form-control","disabled"]); !!}

                                            </div>
                                            <div class="col-md-3 kil-padding-right kill-padding-left">
                                                <div class="btn-unit">{{__('VNĐ')}}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 kil-padding-right kill-padding-left">
                                        <label style="margin-left: 45px">{{__('Giá thẻ')}} : </label>
                                        <div class="col-sm-12 row form-group pull-right">
                                            <div class="col-md-9 kil-padding-right kill-padding-left">
                                                {!! Form::text("money",$card_detail->money,["class"=>"form-control","disabled"]); !!}

                                            </div>
                                            <div class="col-md-3 kil-padding-right kill-padding-left">
                                                <div class="btn-unit">{{__('VNĐ')}}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 row form-group">
                                        <label>{{__('Chi nhánh')}} : </label>
                                        {!! Form::text("branch_name",$card_detail->branch_name,["class"=>"form-control","disabled"]); !!}

                                </div>
                                <div class="col-sm-12 row form-group">
                                    <label>{{__('Người tạo')}} : </label>
                                    {!! Form::text("staff_name",$card_detail->staff_name,["class"=>"form-control","disabled"]); !!}

                                </div>
                                <div class="col-sm-12 row form-group">
                                    <label>{{__('Mẫu in')}} : </label>
                                    <div class="image-preview">
                                        @if(isset($_card->image) && $_card->image != null)
                                            <a href="{{asset("uploads/".$_card->image)}}" target="_blank"
                                               data-lightbox="image-{{$_card->service_card_id}}">
                                                <img src="{{asset("uploads/".$_card->image)}}" class="img-preview">
                                            </a>
                                        @else
                                            <h3 class="m-dropzone__msg-title">
                                                {{__('Không có hình ảnh')}}
                                            </h3>
                                        @endif
                                    </div>

                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="col-sm-12 row form-group kil-padding-right kill-padding-left">
                                <button type="button" style="width: 50%" class="btn m-btn--square active-btn btn-secondary m-btn--wide btnCardType" data-type="Unuse">
                                    {{__('DANH SÁCH THẺ IN')}}
                                </button>
                                <button type="button" style="width: 50%" class="btn m-btn--square btn-secondary m-btn--wide btnCardType" data-type="Inuse">
                                    {{__('DANH SÁCH THẺ ĐÃ SỬ DỤNG')}}
                                </button>
                            </div>
                            <div class="col-sm-12 row form-group kill-padding-left kil-padding-right table-content">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 row form-group" style="justify-content: right">
                        <div>
                            <a href="#" class="btn btn-primary btn-print">{{__('In thẻ')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



@stop

@section("modal_section")
    @include("admin::service-card.popup.create-group")
@stop

@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/demo/css/admin/service-card/service-card.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service-card-list/detail.js')}}" type="text/javascript"></script>

@stop
