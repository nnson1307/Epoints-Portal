@extends('layout')
@section('page_subheader')
    @include('components.subheader', ['title' => 'Cấu hình'])
@stop

@section('content')
    <style>
        .modal-backdrop {
            position: relative !important;
        }
    </style>
    <div class="row">
        <div class="col-xl-12">
            <div class="m-portlet m-portlet--mobile ">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Thêm dịch vụ
                            </h3>
                        </div>
                    </div>
                </div>
                {!! Form::open(['route'=>'services.submitadd',"id"=>"form",'class' => 'm-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed']) !!}
                {!! csrf_field() !!}
                <div class="m-portlet__body">
                    <div class="form-group m-form__group row">
                        <div class="col-lg-6 {{ $errors->has('service_code') ? ' has-danger' : '' }}">
                            <label class="col-lg-6 col-form-label">
                                Mã dịch vụ :
                            </label>
                            {!! Form::number('service_code', null, ['class' => 'form-control m-input', 'placeholder' => 'Nhập mã dịch vụ']) !!}
                            @if ($errors->has('service_code'))
                                <span class="form-control-feedback"> {{ $errors->first('service_code') }}</span>
                            @endif
                            <label class="col-lg-12 col-form-label" style="color: gray">
                                Mã dịch vụ có thể tực động phát sinh hoặc tự điền mã
                            </label>
                        </div>
                        <div class="col-lg-6 {{ $errors->has('service_name') ? ' has-danger' : '' }}">
                            <label class="col-lg-6 col-form-label">
                                Tên dịch vụ :
                            </label>
                            {!! Form::text('service_name', null, ['class' => 'form-control m-input', 'placeholder' => 'Nhập tên dịch vụ']) !!}
                            @if ($errors->has('service_name'))
                                <span class="form-control-feedback"> {{ $errors->first('service_name') }}</span>
                            @endif

                            <label class="col-lg-6 col-form-label" style="color: gray">
                                Vui lòng nhập đầy đủ tên dịch vụ
                            </label>
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-6 {{ $errors->has('service_time_id') ? ' has-danger' : '' }}">
                            <label class="col-lg-6 col-form-label">
                                Thời gian sử dụng dịch vụ:
                            </label>
                            <select name="service_time_id" class="form-control">
                                <option value="">Chọn thời gian</option>
                                @foreach($optionServiceTime as $key=>$value)
                                    <option value="{{$key}}">{{$value}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('service_time_id'))
                                <span class="form-control-feedback"> {{ $errors->first('service_time_id') }}</span>
                            @endif
                            <label class="col-lg-6 col-form-label" style="color: gray">
                                Vui lòng chọn thời gian sử dụng dịch vụ
                            </label>
                        </div>

                        <div class="col-lg-6 {{ $errors->has('is_active') ? ' has-danger' : '' }}">
                            <label class="col-lg-6 col-form-label">
                                Trạng thái
                            </label>
                            <select name="is_active" class="form-control">
                                <option value="1">Hoạt động</option>
                                <option value="0">Tạm ngưng</option>
                            </select>
                            @if ($errors->has('is_active'))
                                <span class="form-control-feedback"> {{ $errors->first('is_active') }}</span>
                            @endif
                            <label class="col-lg-6 col-form-label" style="color: gray">
                                Vui lòng chọn trạng thái dịch vụ
                            </label>
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-6 {{ $errors->has('description') ? ' has-danger' : '' }}">
                            <label class="col-lg-6 col-form-label">
                                Thông tin mô tả
                            </label>
                            {!! Form::textarea('description', null, ['class' => 'form-control m-input', 'placeholder' => 'Ghi thông tin mô tả']) !!}
                            @if ($errors->has('description'))
                                <span class="form-control-feedback"> {{ $errors->first('description') }}</span>
                            @endif
                            <label class="col-lg-6 col-form-label" style="color: gray">
                                Vui lòng ghi đầy đủ thông tin mô tả
                            </label>
                        </div>
                        <div class="col-lg-6 {{ $errors->has('image') ? ' has-danger' : '' }}">
                            <label class="col-lg-6 col-form-label">
                                Hình ảnh
                            </label>
                            <div class="col-lg-12">
                                <div class="m-dropzone dropzone dz-clickable" action="{{route('services.uploads')}}"
                                     id="dropzoneone">
                                    <div class="m-dropzone__msg dz-message needsclick">
                                        <h3 class="m-dropzone__msg-title">Drop files here or click to upload.</h3>
                                        <span class="m-dropzone__msg-desc">This is just a demo dropzone. Selected files are <strong>not</strong> actually uploaded.</span>
                                    </div>
                                </div>
                            </div>
                            @if ($errors->has('image'))
                                <span class="form-control-feedback"> {{ $errors->first('image') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-12 {{ $errors->has('detail') ? ' has-danger' : '' }}">
                            <label class="col-lg-6 col-form-label">
                                Thông tin chi tiết
                            </label>
                            {!! Form::textarea('detail',null,['class' => 'summernote', 'placeholder' => 'Ghi thông tin chi tiet']) !!}
                        </div>
                    </div>
                    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                        <div class="m-form__actions m-form__actions--solid">
                            <div class="row">
                                <div class="col-lg-2"></div>
                                <div class="col-lg-12">
                                    <input type="submit" style="width: 100px" class="btn btn-success"
                                           value="Lưu lại">
                                    <a href="{{ route('services') }}" style="width: 100px"
                                       class="btn btn-secondary">Cancel</a>
                                    <input type="reset" class="btn btn-danger pull-right" style="width: 100px"
                                           value="Xóa">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/services/service/list.js')}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/services/service/dropzone.js')}}" type="text/javascript"></script>
    <script>
        var Summernote = {
            init: function () {
                $(".summernote").summernote({height: 300})
            }
        };
        jQuery(document).ready(function () {
            Summernote.init()
        });
    </script>
@stop