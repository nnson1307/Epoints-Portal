@extends('layout')

@section('page_subheader')
    @include('components.subheader', ['title' => 'Đơn hàng'])
@stop

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="m-portlet m-portlet--mobile ">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Thêm trạng thái đơn hàng
                            </h3>
                        </div>
                    </div>
                </div>
                <!--begin::Form-->
                {!! Form::open(['route' => 'order-delivery-status.submitadd', 'class' => 'm-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed']) !!}
                <div class="m-portlet__body">
                    <div class="form-group m-form__group row">
                        <label class="col-lg-2 col-form-label">
                            Tên trạng thái đơn hàng
                        </label>
                        <div class="col-lg-3 {{ $errors->has('order_delivery_status_name') ? ' has-danger' : '' }}">
                            {!! Form::text('order_delivery_status_name', null, ['class' => 'form-control m-input', 'placeholder' => 'Nhập trạng thái đơn hàng']) !!}
                            @if ($errors->has('order_delivery_status_name'))
                                <span class="form-control-feedback">
                                    	{{ $errors->first('order_delivery_status_name') }}
                                    </span>
                            @endif
                            <span class="m-form__help">
									Nhập trạng thái đơn hàng
								</span>
                        </div>

                        <label class="col-lg-2 col-form-label">
                            {{__('Ghi chú')}}
                        </label>
                        <div class="col-lg-3 {{ $errors->has('order_delivery_status_description') ? ' has-danger' : '' }}">
                            {!! Form::textarea('order_delivery_status_description', null, ['class' => 'form-control m-input', 'placeholder' => 'Nhập ghi chú']) !!}
                            @if ($errors->has('order_delivery_status_description'))
                                <span class="form-control-feedback">
                                    	{{ $errors->first('order_delivery_status_description') }}
                                    </span>
                            @endif
                            <span class="m-form__help">
									Nhập ghi chú
								</span>
                        </div>
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
                            <button type="submit" class="btn btn-success">Lưu</button>
                            <a href="{{ route('order-delivery-status') }}" class="btn btn-secondary">Hủy</a>
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