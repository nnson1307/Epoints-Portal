@extends('layout')

@section('page_subheader')
    @include('components.subheader', ['title' => 'Đơn hàng'])
@stop
@section('content')
    <style>
        .btn-file {
            position: relative;
            overflow: hidden;
        }
        .btn-file input[type=file] {
            position: absolute;
            top: 0;
            right: 0;
            min-width: 100%;
            min-height: 100%;
            font-size: 100px;
            text-align: right;
            filter: alpha(opacity=0);
            opacity: 0;
            outline: none;
            background: white;
            cursor: inherit;
            display: block;
        }

        #img-upload{
            width: 100%;
        }
    </style>
    <div class="row">
        <div class="col-xl-12">
            <div class="m-portlet m-portlet--mobile ">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">{!! $TITLE !!}
                            </h3>
                        </div>
                    </div>
                </div>
                {!! Form::model($item, ['method' => 'post','class' => 'm-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed','route' => ['order-payment-type.edit',$item->order_payment_type_id]]) !!}
                <div class="m-portlet__body">
                    <div class="form-group m-form__group row">
                        <label class="col-lg-2 col-form-label">
                            Tên nhóm
                        </label>
                        <div class="col-lg-3 {{ $errors->has('order_payment_type_name') ? ' has-danger' : '' }}">
                            {!! Form::text('order_payment_type_name', null, ['class' => 'form-control m-input', 'placeholder' => 'Nhập tên hình thức thanh toán']) !!}
                            @if ($errors->has('order_payment_type_name'))
                                <span class="form-control-feedback">
                                    	{{ $errors->first('order_payment_type_name') }}
                                    </span>
                                <br>
                            @endif
                            <span class="m-form__help">Vui lòng nhập tên hình thức thanh toán </span>
                        </div>
                        <label class="col-lg-2 col-form-label">{{__('Trạng thái')}}</label>
                        <div class="col-lg-3">
                            <select class="form-control" name="is_active">
                                <option value="1" >Đang hoạt đông</option>
                                <option value="0" >Tạm ngưng</option>
                            </select>
                            <span class="m-form__help">Vui lòng chon trạng thái</span>
                        </div>
                    </div>

                    <div class="form-group m-form__group row">
                        <label class="col-lg-2 col-form-label">
                            {{__('Mô tả')}}
                        </label>
                        <div class="col-lg-8">
                            {!! Form::textarea('order_payment_type_description', null, ['style'=>'max-height : 100px;','class' => 'form-control m-input','placeholder' => __('Nhập mô tả')]) !!}

                            <span class="m-form__help">Vui lòng nhập ghi chú</span>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m-form__actions--solid">
                        <div class="row">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-10">
                                <button type="submit" class="btn btn-success btn-md"><i class="fa fa-save"></i> Lưu</button>
                                <a href="{{ route('order-payment-type') }}" class="btn-md btn btn-secondary"> <i class="fa fa-btn fa fa-reply-all"></i>  Hủy</a>
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
@section('after_script')
    <script src="{{asset('static/backend/js/admin/order-payment-type/list.js')}}" type="text/javascript"></script>
@stop