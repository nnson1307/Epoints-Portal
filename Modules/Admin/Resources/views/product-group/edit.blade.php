@extends('layout')

@section('page_subheader')
    @include('components.subheader', ['title' => 'Sản phẩm'])
@stop

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="m-portlet m-portlet--mobile ">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Sửa nhóm sản phẩm
                            </h3>
                        </div>
                    </div>
                </div>
                <!--begin::Form-->
                {!! Form::model($item,['method' => 'POST','route' => ['product-group.submitedit', $item->product_group_id],
                'class' => 'm-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed']) !!}
                @if (session('messages'))
                    <div class="alert alert-warning alert-dismissible">
                        <strong>Warning!</strong> {!! session('messages') !!}.
                    </div>
                @endif
                <div class="m-portlet__body">
                    <div class="form-group m-form__group row">
                        <label class="col-lg-2 col-form-label">
                            Tên nhóm:
                        </label>
                        <div class="col-lg-3 {{ $errors->has('product_group_name') ? ' has-danger' : '' }}">
                            {!! Form::text('product_group_name', null, ['class' => 'form-control m-input', 'placeholder' => 'nhập Tên nhóm']) !!}
                            @if ($errors->has('product_group_name'))
                                <span class="form-control-feedback">
                                    	{{ $errors->first('product_group_name') }}
                                    </span>
                            @endif
                            <span class="m-form__help">
									Vui lòng nhập tên nhóm
								</span>
                        </div>

                        <label class="col-lg-2 col-form-label">
                            Mã nhóm sản phẩm:
                        </label>
                        <div class="col-lg-3 {{ $errors->has('product_group_code') ? ' has-danger' : '' }}">
                            {!! Form::text('product_group_code', null, ['class' => 'form-control m-input', 'placeholder' => 'Nhập mã nhóm sản phẩm']) !!}
                            @if ($errors->has('product_group_code'))
                                <span class="form-control-feedback">
                                    	{{ $errors->first('product_group_code') }}
                                    </span>
                            @endif
                            <span class="m-form__help">
									Vui lòng nhập mã nhóm sản phẩm
								</span>
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-lg-2 col-form-label">
                            Thông tin mô tả:
                        </label>
                        <div class="col-lg-8 {{ $errors->has('product_group_description') ? ' has-danger' : '' }}">
                            {!! Form::textarea('product_group_description', null, ['class' => 'form-control m-input', 'placeholder' => 'Nhập thông tin mô tả']) !!}
                            @if ($errors->has('product_group_description'))
                                <span class="form-control-feedback">
                                    	{{ $errors->first('product_group_description') }}
                                </span>
                            @endif
                            <span class="m-form__help">
									Vui lòng nhập thông tin mô tả
                            </span>
                        </div>


                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-lg-2 col-form-label">
                            {{__('Trạng thái')}}:
                        </label>
                        <div class="col-lg-3">
                            <div class="m-radio-inline">
                                <label class="m-radio m-radio--solid">
                                    {!! Form::radio('is_active', 1, true) !!}
                                    Active
                                    <span></span>
                                </label>
                                <label class="m-radio m-radio--solid">
                                    {!! Form::radio('is_active', 0) !!}
                                    Deactive
                                    <span></span>
                                </label>
                            </div>
                            <span class="m-form__help">
									Please select user status
								</span>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m-form__actions--solid">
                        <div class="row">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-10">
                                <div class="col-lg-10">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i>  Lưu lại</button>
                                    <a href="{{ route('product-group') }}" class="btn btn-secondary"> <i class="fa fa-btn fa fa-reply-all"></i>  Hủy</a>
                                    <input type="reset" value="Xóa" class="btn btn-danger pull-right">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        <!--end::Form-->
        </div>
    </div>
@stop