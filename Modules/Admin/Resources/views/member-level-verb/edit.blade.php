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
                                Sửa công thức quy đổi
                            </h3>
                        </div>
                    </div>
                </div>
                {!! Form::model($item,['method'=>'POST','route'=>['admin.member-level-verb.submitedit',$item->member_level_verb_id],'class' => 'm-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed']) !!}
                <div class="m-portlet__body">
                    <div class="form-group m-form__group row">
                        <div class="col-lg-6 {{ $errors->has('member_level_verb_name') ? ' has-danger' : '' }}">
                            <label class="col-lg-6 col-form-label">
                                Tên :
                            </label>
                            {!! Form::text('member_level_verb_name', null, ['class' => 'form-control m-input', 'placeholder' => 'Tên công thức quy đổi']) !!}
                            @if ($errors->has('member_level_verb_name'))
                                <span class="form-control-feedback"> {{ $errors->first('member_level_verb_name') }}</span>
                            @endif
                        </div>
                        <div class="col-lg-6 {{ $errors->has('is_active') ? ' has-danger' : '' }}">
                            <label class="col-lg-6 col-form-label">
                                {{__('Trạng thái')}} :
                            </label>
                            <select name="is_active" class="form-control">
                                <option value="1" {!! ($item->is_active==1)?'selected':'' !!}>Hoạt động</option>
                                <option value="0" {!! ($item->is_active==0)?'selected':'' !!}>Tạm ngưng</option>
                            </select>
                            @if ($errors->has('is_active'))
                                <span class="form-control-feedback"> {{ $errors->first('is_active') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group m-form__group row">

                        <div class="col-lg-6 {{ $errors->has('member_level_id') ? ' has-danger' : '' }}">
                            <label class="col-lg-6 col-form-label">
                                Cấp độ / hạng khách hàng :
                            </label>
                            <select name="member_level_id" class="form-control">
                                @foreach($optionMemberLevelName as $key=>$value)
                                    @if($item->member_level_id==$key)
                                        <option value="{{$key}} selected">{{$value}}</option>
                                    @else
                                        <option value="{{$key}}">{{$value}}</option>
                                    @endif
                                @endforeach
                            </select>
                            @if ($errors->has('member_level_id'))
                                <span class="form-control-feedback"> {{ $errors->first('member_level_id') }}</span>
                            @endif
                        </div>
                        <div class="col-lg-6 {{ $errors->has('member_level_verb_point') ? ' has-danger' : '' }}">
                            <label class="col-lg-6 col-form-label">
                                Số điểm được nhận :
                            </label>
                            {!! Form::number('member_level_verb_point', null, ['class' => 'form-control m-input', 'placeholder' => '0']) !!}
                            @if ($errors->has('member_level_verb_point'))
                                <span class="form-control-feedback"> {{ $errors->first('member_level_verb_point') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-3 {{ $errors->has('order_price_min') ? ' has-danger' : '' }}">
                            <label class="col-lg-12 col-form-label">
                                Giá trị đơn hàng tối thiểu :
                            </label>
                            {!! Form::number('order_price_min', null, ['class' => 'form-control m-input', 'placeholder' => '0']) !!}
                            @if ($errors->has('order_price_min'))
                                <span class="form-control-feedback"> {{ $errors->first('order_price_min') }}</span>
                            @endif
                        </div>
                        <div class="col-lg-3 {{ $errors->has('order_price_max') ? ' has-danger' : '' }}">
                            <label class="col-lg-12 col-form-label">
                                Giá trị đơn hàng tối đa :
                            </label>
                            {!! Form::number('order_price_max', null, ['class' => 'form-control m-input', 'placeholder' => '0']) !!}
                            @if ($errors->has('order_price_max'))
                                <span class="form-control-feedback"> {{ $errors->first('order_price_max') }}</span>
                            @endif
                        </div>
                        <div class="col-lg-3 {{ $errors->has('product_number_min') ? ' has-danger' : '' }}">
                            <label class="col-lg-12 col-form-label">
                                Số lượng sản phẩm tối thiểu :
                            </label>
                            {!! Form::number('product_number_min', null, ['class' => 'form-control m-input', 'placeholder' => '0']) !!}
                            @if ($errors->has('product_number_min'))
                                <span class="form-control-feedback"> {{ $errors->first('product_number_min') }}</span>
                            @endif
                        </div>
                        <div class="col-lg-3 {{ $errors->has('product_number_max') ? ' has-danger' : '' }}">
                            <label class="col-lg-12 col-form-label">
                                Số lượng sản phẩm tối đa :
                            </label>
                            {!! Form::number('product_number_max', null, ['class' => 'form-control m-input', 'placeholder' => '0']) !!}
                            @if ($errors->has('product_number_max'))
                                <span class="form-control-feedback"> {{ $errors->first('product_number_max') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                        <div class="m-form__actions m-form__actions--solid">
                            <div class="row">
                                <div class="col-lg-2"></div>
                                <div class="col-lg-12">
                                    <input type="submit" style="width: 100px" class="btn btn-success"
                                           value="Lưu lại"></input>
                                    <a href="{{ route('admin.member-level-verb') }}" style="width: 100px"
                                       class="btn btn-secondary">Cancel</a>
                                    <input type="reset" class="btn btn-danger pull-right" style="width: 100px"
                                           value="Xóa">
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