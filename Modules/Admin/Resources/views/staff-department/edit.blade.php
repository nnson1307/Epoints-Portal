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
                                Sửa phòng ban
                            </h3>
                        </div>
                    </div>
                </div>
                {!! Form::model($item, array('method' => 'POST','route' => array('admin.staff-department.submitedit',$item->staff_department_id) ,'class' => 'm-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed')) !!}
                <div class="m-portlet__body">
                    @if (session('messages'))
                        <div class="alert alert-warning alert-dismissible">
                            <strong>Warning!</strong> {!! session('messages') !!}.
                        </div>
                    @endif
                    <div class="form-group m-form__group row">
                        <label class="col-lg-2 col-form-label">
                            Tên phòng ban(bộ phận)
                        </label>
                        <div class="col-lg-3 {{ $errors->has('staff_department_name') ? ' has-danger' : '' }}">
                            {!! Form::text('staff_department_name', null, ['class' => 'form-control m-input', 'placeholder' => 'Nhập tên phòng ban(bộ phận)']) !!}
                            @if ($errors->has('staff_department_name'))
                                <span class="form-control-feedback"> {{ $errors->first('staff_department_name') }}</span>
                            @endif
                        </div>

                        <label class="col-lg-2 col-form-label">
                            Mã phòng ban :
                        </label>
                        <div class="col-lg-3 {{ $errors->has('staff_department_code') ? ' has-danger' : '' }}">
                            {!! Form::text('staff_department_code', null, ['class' => 'form-control m-input', 'placeholder' => 'Nhập mã']) !!}
                            @if ($errors->has('staff_department_code'))
                                <span class="form-control-feedback"> {{ $errors->first('staff_department_code') }}</span>
                            @endif
                        </div>

                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-lg-2 col-form-label">
                            {{__('Trạng thái')}} :
                        </label>
                        <div class="col-lg-3 {{ $errors->has('is_active') ? ' has-danger' : '' }}">
                            <select name="is_active" class="form-control">
                                <option value="1" {!! ($item->is_active==1)?'selected':'' !!}>Hoạt động</option>
                                <option value="0" {!! ($item->is_active==0)?'selected':'' !!}>Tạm ngưng</option>
                            </select>
                        </div>
                    </div>

                    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                        <div class="m-form__actions m-form__actions--solid">
                            <div class="row">
                                <div class="col-lg-2"></div>
                                <div class="col-lg-10">
                                    <input type="submit" class="btn btn-success" value="Lưu lại"></input>
                                    <a href="{{ route('admin.staff-department.list') }}" class="btn btn-secondary">Cancel</a>
                                    <button type="reset" class="btn btn-danger pull-right">Xóa</button>
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