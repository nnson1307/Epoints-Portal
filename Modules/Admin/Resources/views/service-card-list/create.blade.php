@extends('layout')
@section('content')
    <style>
        .form-control-feedback {
            color: #ff0000;
        }
    </style>

    <div class="row">
        <div class="col-xl-12">
            <div class="m-portlet m-portlet--creative m-portlet--first m-portlet--bordered-semi">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <div class="m-input-icon m-portlet__head-icon">
                            </div>
                            <h3 class="m-portlet__head-text">
                            </h3>
                            <h2 style=" white-space:nowrap"
                                class="m-portlet__head-label m-portlet__head-label--primary">
                                <span><i class="fa flaticon-plus m--margin-right-5"></i>{{__('TẠO THẺ IN DỊCH VỤ')}} </span>
                            </h2>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <a href="javascript:void(0)"
                           onclick="ServiceCardList.getCardCode()"
                           class="btn btn-primary m-btn m-btn--icon m-btn--pill">
                        <span>
						    <i class="fa flaticon-plus"></i>
							<span> {{__('Tạo mã thẻ in')}}</span>
                        </span>
                        </a>
                    </div>
                </div>
                {!! Form::open(["id"=>"form-create", 'class' => 'm--margin-top-20 m-form--group-seperator-dashed ',"route"=>"admin.service-card-list.create-submit","method"=>"POST"]) !!}
                <div class="m-portlet__body">
                    <div class="col-md-12 row form-group">
                        <div class="col-md-6">
                            <div class="col-sm-12 row form-group">
                                <label>{{__('Thẻ dịch vụ')}} <span class="required">*</span></label>
                                {!! Form::select("service_card_id",$_service_card,0,["class"=>"form-control","autocomplete"=>"off","id"=>"search-name-field"]); !!}

                                @if ($errors->has('service_card_id'))
                                    <span class="form-control-feedback">
                                     {{ $errors->first('service_card_id') }}
                                </span>
                                    <br>
                                @endif

                            </div>

                            <div class="col-md-12 row form-group">
                                <label>{{__('Giá thẻ')}} : <span class="required">*</span></label>
                                {!! Form::text("price",null,["class"=>"form-control","disabled"=>"disabled","id"=>"card-price"]); !!}
                            </div>
                            <div class="col-sm-12 row form-group">
                                <label>{{__('Chi nhánh')}} : <span class="required">*</span></label>
                                {!! Form::select("branch_id",$_branch,0,["class"=>"form-control",'id'=>"branch"]) !!}
                                @if ($errors->has('date_using'))
                                    <span class="form-control-feedback">
                                     {{ $errors->first('date_using') }}
                                </span>
                                    <br>
                                @endif
                            </div>
                            <div class="col-md-12 row form-group">
                                <label>{{__('Số lượng')}} : <span class="required">*</span></label>
                                {!! Form::text("quantity",null,["class"=>"form-control","id"=>"quantity"]); !!}

                                @if ($errors->has('quantity'))
                                    <span class="form-control-feedback">
                                     {{ $errors->first('quantity') }}
                                </span>
                                    <br>
                                @endif
                            </div>
                            <div class="col-sm-12 row form-group">
                                <label>{{__('Chọn mẫu in')}} : <span class="required">*</span></label>
                                {!! Form::select("template",[''=>__('Chọn mẫu in')],0,["class"=>"form-control","id"=>"template"]) !!}
                                @if ($errors->has('date_using'))
                                    <span class="form-control-feedback">
                                     {{ $errors->first('date_using') }}
                                </span>
                                    <br>
                                @endif
                            </div>
                            <div class="col-sm-12 row form-group">
                                <div class="image-preview">
                                    {{--@if(isset($_card->image) && $_card->image != null)--}}
                                    {{--<div class="img-section">--}}
                                    {{--<a href="{{asset("uploads/".$_card->image)}}" target="_blank"--}}
                                    {{--data-lightbox="image-{{$_card->service_card_id}}">--}}
                                    {{--<img src="{{asset("uploads/".$_card->image)}}" class="img-preview">--}}
                                    {{--</a>--}}
                                    {{--<button type="button" class="btn btn-danger btn-delete-img">--}}
                                    {{--Xóa--}}
                                    {{--</button>--}}
                                    {{--</div>--}}


                                    {{--@else--}}
                                    <h3 class="m-dropzone__msg-title">
                                        {{__('Không có hình ảnh')}}
                                    </h3>
                                    {{--@endif--}}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="col-sm-12 row form-group">
                                <div class="table-responsive" style="max-height: 520px; overflow: auto">
                                    <table class="table table-striped m-table m-table--head-bg-primary">

                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{__('Mã thẻ dịch vụ')}}</th>
                                            <th>{{__('Ngày tạo')}}</th>
                                            <th>
                                                <label class="m-checkbox m-checkbox--solid m-checkbox--success"
                                                       style="margin-bottom: 14px">
                                                    <input type="checkbox"  autocomplete="off" class="ckb-all">
                                                    <span></span>
                                                </label>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>

                                    </table>
                                </div>
                                @if ($errors->has('code'))
                                    <span class="form-control-feedback">
                                     {{ $errors->first('code') }}
                                </span>
                                    <br>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
                {{--<div class="modal-footer">--}}
                {{--<button type="submit" class="btn btn-success" id="addnew" onclick="staff.add(0)">Lưu & tạo mới--}}
                {{--</button>--}}
                {{--<button type="submit" class="btn btn-primary" id="addclose" onclick="staff.add(1)">Lưu & đóng--}}
                {{--</button>--}}
                {{--<input type="submit" value="Hủy" class="btn btn-default" data-dismiss="modal">--}}
                {{--</div>--}}
                <div style="margin-left: 58%">
                    <input type="hidden" name="action" value="">
                    <a href="javascript:void(0)" onclick="ServiceCardList.saveCard()" class="btn btn-secondary">
                    {{__('Lưu thông tin và in thẻ sau')}}</a>
                    <a href="javascript:void(0)" class="btn btn-success">{{__('Lưu thông tin và in thẻ ngay')}}</a>
                    {{--<button type="submit" class="btn btn-primary">Lưu & đóng</button>--}}

                </div>
                <br/>
                {{--button lưu--}}


                {!! Form::close() !!}

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
    <script src="{{asset('static/backend/js/admin/service-card-list/create.js')}}" type="text/javascript"></script>

    <script>
        @if(Session::has("error"))
        $.notify({
            // options
            message: '{{Session::get("error")}}'
        }, {
            // settings
            type: 'danger'
        });
        @endif
    </script>
@stop
