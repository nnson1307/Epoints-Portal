@extends('layout')

@section('page_subheader')
    @include('components.subheader', ['title' => 'Dịch vụ'])
@stop
@section('content')
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
                <!--begin::Form-->
                {{--@if($ACTION == 'ADD')--}}
                {!! Form::open(['method' => 'post','route' => 'service-type.add', 'class' => 'm-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed']) !!}

                <div class="m-portlet__body">
                    <div class="form-group m-form__group row">
                        <label class="col-lg-2 col-form-label">
                            Tên nhóm
                        </label>
                        <div class="col-lg-3 {{ $errors->has('service_type_name') ? ' has-danger' : '' }}">
                            {!! Form::text('service_type_name', null, ['class' => 'form-control m-input', 'placeholder' => 'Nhập tên gói dịch vụ']) !!}
                            @if ($errors->has('service_group_name'))
                                <span class="form-control-feedback">
                                    	{{ $errors->first('service_type_name') }}
                                    </span>
                                <br>
                            @endif
                            <span class="m-form__help">Vui lòng nhập tên gói dịch vụ</span>
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
                </div>
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m-form__actions--solid">
                        <div class="row">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-10">
                                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Lưu</button>
                                <a href="{{ route('services') }}" class="btn-md btn btn-secondary"><i class="fa fa-btn fa fa-reply-all"></i> Hủy </a>
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
