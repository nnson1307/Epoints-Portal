@extends('layout')

@section('page_subheader')
    @include('components.subheader', ['title' => 'Thuế'])
@stop

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="m-portlet m-portlet--mobile ">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">Cập nhật loại hình thuế</h3>
                        </div>
                    </div>
                </div>
                <!--begin::Form-->
                {!! Form::model($item, ['method' => 'post','class' => 'm-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed','route' => ['admin.tax.submitedit',$item->tax_id]]) !!}
                <div class="m-portlet__body">
                    <div class="form-group m-form__group row">
                    @if (session('messages'))
                        <div class="alert alert-warning alert-dismissible">
                            <strong>Warning!</strong> {!! session('messages') !!}.
                        </div>
                    @endif
                        <div class="col-lg-6">
                            <div class="col-sm-12 {{ $errors->has('name') ? ' has-danger' : '' }}">
                                <label class="col-lg-12 col-form-label">
                                    Tên:
                                </label>
                                {!! Form::text('name',null,['class' => 'form-control m-input','placeholder' => 'Nhập tên thuế']) !!}
                                @if ($errors->has('name'))
                                    <span class="form-control-feedback">
                                        {{ $errors->first('name') }}
                                    </span>
                                    <br>
                                @endif
                            </div>

                            <div class="col-sm-12 {{ $errors->has('value') ? ' has-danger' : '' }}">
                                <div class="input-group col-xs-10">
                                    <label class="col-lg-12 col-form-label">
                                        Hình thức:
                                    </label>
                                    {!! Form::text('value',null,['class' => 'form-control m-input','placeholder' => '0']) !!}
                                    <div class="input-group-append">
                                        <select class="form-control search-type" name="type">
                                            <option value="money" {!!($item->type =='money') ? 'selected' :'' !!}>VNĐ</option>
                                            <option value="percent" {!!($item->type =='percent') ? 'selected' :'' !!}>%</option>
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('value'))
                                    <span class="form-control-feedback">
                                             {{ $errors->first('value') }}
                                        </span>
                                @endif
                            </div>

                            <div class="col-sm-12">
                                <label class="col-lg-12 col-form-label">
                                    {{__('Trạng thái')}}:
                                </label>
                                <div class="form-group {{ $errors->has('is_active') ? ' has-danger' : '' }}">
                                    <select name="is_active" class="form-control">
                                        <option value="1" {!!($item->is_active ==1) ? 'selected' :'' !!}>Đang hoạt đông</option>
                                        <option value="0" {!!($item->is_active ==0) ? 'selected' :'' !!}>Tạm ngưng</option>
                                    </select>
                                    @if ($errors->has('is_active'))
                                        <span class="form-control-feedback">
                                             {{ $errors->first('is_active') }}
                                        </span>
                                        <br>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <label class="col-lg-2 col-form-label">
                                {{__('Ghi chú')}}
                            </label>
                            <div class="col-lg-8 {{ $errors->has('descripton') ? ' has-danger' : '' }}">
                                {!! Form::textarea('descripton', null, ['style'=>'width : 500px;','class' => 'form-control m-input','placeholder' => 'Nhập ghi chú']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m-form__actions--solid">
                        <div class="row">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-10">
                                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Lưu</button>
                                <a href="{{ route('admin.tax') }}" class="btn btn-secondary"> <i class="fa fa-btn fa fa-reply-all"></i>Hủy</a>
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
@section('after_script')
    <script src="{{asset('static/backend/js/admin/tax/list.js')}}" type="text/javascript"></script>
@stop
