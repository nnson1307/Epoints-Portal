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
                {!! Form::model($item,['method'=>'post','route'=>['admin.order-status.submitedit', $item->order_status_id, 'class' => 'm-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed']]) !!}
                <div class="m-portlet__body">
                    @if (session('messages'))
                        <div class="alert alert-warning alert-dismissible">
                            <strong>Warning!</strong> {!! session('messages') !!}.
                        </div>
                    @endif
                    <div class="form-group m-form__group row">

                        <label class="col-lg-2 col-form-label">
                            Tên trạng thái đơn hàng :
                        </label>

                        <div class="col-lg-3">
                            {!! Form::text('order_status_name',null,['class' => 'form-control m-input','placeholder' => 'Nhập thông tin trạng thái']) !!}
                            <br>
                            @if ($errors->has('order_status_name'))
                                <span class="form-control-feedback">
                                     {{ $errors->first('order_status_name') }}
                                </span>
                                <br>
                            @endif
                            <span class="m-form__help">
									{{__('Trạng thái')}} đơn hàng
                            </span>
                        </div>


                        <label class="col-lg-2 col-form-label">
                            {{__('Ghi chú')}} :
                        </label>

                        <div class="col-lg-3">
                            {!! Form::textarea('order_status_description',null,['class' => 'form-control m-input','style'=>'height:200px; width:450px;','placeholder' => 'Nhập tên nhóm']) !!}
                            <br>
                            @if ($errors->has('order_status_description'))
                                <span class="form-control-feedback">
                                     {{ $errors->first('order_status_description') }}
                                </span>
                                <br>
                            @endif

                        </div>
                    </div>

                    <div class="form-group m-form__group row">
                        <label class="col-lg-2 col-form-label">
                            {{__('Trạng thái')}} :
                        </label>
                        <div class="col-lg-3">
                            <select name="is_active" class="form-control">
                                <option value="1" {!!($item->is_active==1) ? 'selected' : ''!!}>Hoạt động</option>
                                <option value="0" {!!($item->is_active==0) ? 'selected' : ''!!}>Tạm đóng</option>
                            </select>
                        </div>
                    </div>


                </div>
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m-form__actions--solid">
                        <div class="row">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-10">
                                <button type="submit" class="btn btn-success">Lưu lại</button>
                                <a href="{{ route('admin.order-status') }}" class="btn btn-secondary">Hủy</a>
                                <input type="reset" value="Xóa" class="btn btn-danger pull-right">
                            </div>
                        </div>
                    </div>
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop