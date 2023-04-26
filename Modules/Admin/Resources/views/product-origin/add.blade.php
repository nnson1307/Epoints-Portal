@extends('layout')
@section('page_subheader')
    @include('components.subheader', ['title' => 'Xuất xứ'])
@stop
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="m-portlet m-portlet--mobile ">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Thêm xuất xứ
                            </h3>
                        </div>
                    </div>
                </div>
                {!! Form::open(['route'=>'admin.product-origin.submitadd', 'class' => 'm-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed']) !!}
                <div class="m-portlet__body">
                    <div class="form-group m-form__group row">
                        <label class="col-lg-2 col-form-label">
                            Tên quốc gia :
                        </label>

                        <div class="col-lg-3 {{ $errors->has('product_origin_name') ? ' has-danger' : '' }}">
                            {!! Form::text('product_origin_name',null,['class' => 'form-control m-input', 'placeholder' => 'Nhập tên quốc gia']) !!}
                            @if ($errors->has('product_origin_name'))
                                <span class="form-control-feedback">
                                     {{ $errors->first('product_origin_name') }}
                                </span>
                                <br>
                            @endif
                            <span class="m-form__help">Vui lòng nhập tên quốc gia</span>
                        </div>
                        <label class="col-lg-2 col-form-label">
                            Mã quốc gia :
                        </label>
                        <div class="col-lg-3 {{ $errors->has('product_origin_code') ? ' has-danger' : '' }}">
                            {!! Form::text('product_origin_code',null,['class' => 'form-control m-input', 'placeholder' => 'Nhập tên nhóm']) !!}
                            @if ($errors->has('product_origin_code'))
                                <span class="form-control-feedback">
                                     {{ $errors->first('product_origin_code') }}
                                </span>
                                <br>
                            @endif
                            <span class="m-form__help">Vui lòng nhập mã quốc gia</span>
                        </div>

                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-lg-2 col-form-label">
                            Thông tin mô tả:
                        </label>
                        <div class="col-lg-8{{ $errors->has('product_origin_description') ? ' has-danger' : '' }}">
                            {!! Form::textarea('product_origin_description', null, ['style'=>'max-height:100px;','class' => 'form-control m-input', 'placeholder' => 'Nhập thông tin mô tả']) !!}
                            @if ($errors->has('product_origin_description'))
                                <span class="form-control-feedback">
                                    	{{ $errors->first('product_origin_description') }}
                                </span>
                                <br>
                            @endif
                            <span class="m-form__help">
									Vui lòng nhập thông tin mô tả
                            </span>
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-lg-2 col-form-label">
                            {{__('Trạng thái')}} :
                        </label>
                        <div class="col-lg-3">
                            <select name="is_active" class="form-control">
                                <option value="1">Hoạt động</option>
                                <option value="0">Tạm ngừng</option>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m-form__actions--solid">
                        <div class="row">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-10">
                                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i>  Lưu lại</button>
                                <a href="{{ route('admin.product-origin') }}" class="btn btn-secondary"><i class="fa fa-btn fa fa-reply-all"></i>  Hủy</a>
                                <input type="reset" class="btn btn-danger pull-right" style="width: 100px" value="Xóa">
                            </div>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop