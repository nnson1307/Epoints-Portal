@extends('layout')

@section('page_subheader')
    @include('components.subheader', ['title' => 'Đơn vị sản phẩm'])
@stop

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="m-portlet m-portlet--mobile ">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">{{$TITLE}}</h3>
                        </div>
                    </div>
                </div>
                <!--begin::Form-->
                {!! Form::open(['route' => 'product-unit.submitadd', 'class' => 'm-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed']) !!}
                <div class="m-portlet__body">
                    <div class="form-group m-form__group row">
                        <label class="col-lg-2 col-form-label"> Tên đơn vị sản phẩm </label>
                        <div class="col-lg-3 {{ $errors->has('product_unit_name') ? ' has-danger' : '' }}" >
                            {!! Form::text('product_unit_name', null, ['class' => 'form-control m-input', 'placeholder' => 'Nhập tên đơn vi']) !!}
                            @if ($errors->has('product_unit_name'))
                                <span class="form-control-feedback">
                                    	{{ $errors->first('product_unit_name') }}
                                    </span>
                                <br>
                            @endif
                            <span class="m-form__help">Vui lòng nhập tên đơn vị sản phẩm</span>
                        </div>
                        <label class="col-lg-2 col-form-label">
                            {{__('Trạng thái')}}:
                        </label>
                        <div class="col-lg-3">
                            <select name="is_active" class="form-control">
                                <option value="1">Đang hoạt động</option>
                                <option value="0">Tạm ngưng</option>
                            </select>
                            <span class="m-form__help">{{__('Trạng thái')}} hoạt động</span>
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-lg-2 col-form-label">
                            {{__('Mô tả')}}:
                        </label>
                        <div class="col-lg-8{{ $errors->has('product_unit_description') ? ' has-danger' : '' }}">
                            {!! Form::textarea('product_unit_description', null, ['style'=>'max-height:100px;','class' => 'form-control m-input', 'placeholder' => 'Nhập thông tin mô tả']) !!}
                            @if ($errors->has('product_unit_description'))
                                <span class="form-control-feedback">
                                    	{{ $errors->first('product_unit_description') }}
                                </span>
                                <br>
                            @endif
                            <span class="m-form__help">
									Vui lòng nhập thông tin mô tả
                            </span>
                        </div>
                    </div>
                </div>

                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m-form__actions--solid">
                        <div class="row">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-10">
                                <button type="submit" class="btn btn-success btn-md"><i class="fa fa-save"></i> Lưu</button>
                                <a href="{{ route('product-unit') }}" class="btn-md btn btn-secondary"> <i class="fa fa-btn fa fa-reply-all"></i>  Hủy</a>
                                <input type="reset" value="Xóa" class="btn btn-danger pull-right">
                            </div>
                        </div>
                    </div>
                </div>
            {!! Form::close() !!}
            <!--end::Form-->
            </div>
        </div>
    </div>
@stop
