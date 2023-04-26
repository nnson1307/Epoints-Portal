@extends('layout')

@section('content')
    <p>
        This view is loaded from module: {!! config('admin.name') !!}
    </p>
    <div class="row">
        <div class="col-xl-12">
            <div class="m-portlet m-portlet--mobile ">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Thêm nhãn hiệu
                            </h3>
                        </div>
                    </div>
                </div>
                {!! Form::open(['route'=>'admin.product-label.submitadd','class' => 'm-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed']) !!}
                <div class="m-portlet__body">
                    <div class="form-group m-form__group row">
                        <label class="col-lg-2 col-form-label">
                            Tên nhãn hiệu :
                        </label>
                        <div class="col-lg-3 {{ $errors->has('product_label_name') ? ' has-danger' : '' }}">
                            {!! Form::text('product_label_name', null, ['class' => 'form-control m-input', 'placeholder' => 'Nhập tên nhãn hiệu']) !!}
                            @if ($errors->has('product_label_name'))
                                <span class="form-control-feedback"> {{ $errors->first('product_label_name') }}</span>
                            @endif
                            <span class="m-form__help">
									Vui lòng nhập mã nhãn hiệu
                            </span>
                        </div>

                        <label class="col-lg-2 col-form-label">
                            Mã nhãn hiệu :
                        </label>
                        <div class="col-lg-3 {{ $errors->has('product_label_code') ? ' has-danger' : '' }}">
                            {!! Form::text('product_label_code', null, ['class' => 'form-control m-input', 'placeholder' => 'Nhập mã']) !!}
                            @if ($errors->has('product_label_code'))
                                <span class="form-control-feedback"> {{ $errors->first('product_label_code') }}</span>
                            @endif
                            <span class="m-form__help">
									Vui lòng nhập tên nhãn hiệu
                            </span>
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-lg-2 col-form-label">
                            Thông tin mô tả:
                        </label>
                        <div class="col-lg-8{{ $errors->has('product_label_description') ? ' has-danger' : '' }}">
                            {!! Form::textarea('product_label_description', null, ['style'=>'max-height:100px;', 'class' => 'form-control m-input', 'placeholder' => 'Nhập thông tin mô tả']) !!}
                            @if ($errors->has('product_label_description'))
                                <span class="form-control-feedback">
                                    	{{ $errors->first('product_label_description') }}
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
                        <div class="col-lg-3 {{ $errors->has('is_active') ? ' has-danger' : '' }}">
                            <select name="is_active" class="form-control">
                                <option value="1">Đang hoạt động</option>
                                <option value="0">Tạm ngưng</option>
                            </select>
                        </div>
                    </div>

                    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                        <div class="m-form__actions m-form__actions--solid">
                            <div class="row">
                                <div class="col-lg-2"></div>
                                <div class="col-lg-10">
                                    <button type="submit" class="btn btn-success btn-md"><i class="fa fa-save"></i> Lưu</button>
                                    <a href="{{ route('admin.product-label') }}" class="btn-md btn btn-secondary"> <i class="fa fa-btn fa fa-reply-all"></i>  Hủy</a>
                                    <input type="reset" value="Xóa" class="btn btn-danger pull-right">
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@stop
