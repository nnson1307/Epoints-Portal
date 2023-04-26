@extends('layout')
@section('page_subheader')
    @include('components.subheader', ['title' => 'Đơn hàng'])
@stop
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
                                Thêm lý do hủy
                            </h3>
                        </div>
                    </div>
                </div>
                {!! Form::open(['route'=>'admin.order-reason-cancel.submitadd','class' => 'm-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed']) !!}
                <div class="m-portlet__body">
                    <div class="form-group m-form__group row">
                        <label class="col-lg-2 col-form-label">
                            Lý do
                        </label>
                        <div class="col-lg-4 {{ $errors->has('order_reason_cancel_name') ? ' has-danger' : '' }}">
                            {!! Form::text('order_reason_cancel_name', null, ['class' => 'form-control m-input', 'placeholder' => 'Nhập thông tin lý do']) !!}
                            @if ($errors->has('order_reason_cancel_name'))
                                <span class="form-control-feedback"> {{ $errors->first('order_reason_cancel_name') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-lg-2 col-form-label">
                            {{__('Trạng thái')}} :
                        </label>
                        <div class="col-lg-3 {{ $errors->has('is_active') ? ' has-danger' : '' }}">
                            <select name="is_active" class="form-control">
                                <option value="1">Hoạt động</option>
                                <option value="0">Tạm ngưng</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-lg-2 col-form-label">
                            {{__('Ghi chú')}}
                        </label>
                        <div class="col-lg-3 {{ $errors->has('order_reason_cancel_description') ? ' has-danger' : '' }}">
                            {!! Form::textarea('order_reason_cancel_description', null, ['class' => 'form-control m-input','style'=>'height:150px; width:390px', 'placeholder' => 'Nhập ghi chú']) !!}
                            @if ($errors->has('order_reason_cancel_description'))
                                <span class="form-control-feedback"> {{ $errors->first('order_reason_cancel_description') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                        <div class="m-form__actions m-form__actions--solid">
                            <div class="row">
                                <div class="col-lg-2"></div>
                                <div class="col-lg-10">
                                    <input type="submit" class="btn btn-success" style="width: 100px; margin-right: 10px" value="Lưu lại"></input>
                                    <a href="{{ route('admin.order-reason-cancel') }}" style="width: 100px;margin-right: 10px" class="btn btn-secondary" >Cancel</a>
                                    <button type="reset" style="width: 100px" class="btn btn-danger pull-right">Xóa</button>
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
@section('after_script')
    <script src="{{asset('static/backend/js/admin/staff-department/list.js')}}" type="text/javascript"></script>
@stop