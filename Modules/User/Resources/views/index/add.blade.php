@extends('layout')

@section('page_subheader')
    @include('components.subheader', ['title' => 'Người dùng'])
@stop

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="m-portlet m-portlet--mobile ">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Thêm người dùng
                            </h3>
                        </div>
                    </div>
                </div>
                <!--begin::Form-->
                {!! Form::open(['route' => 'user.submitadd', 'class' => 'm-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed']) !!}
                    <div class="m-portlet__body">
                        <div class="form-group m-form__group row">
                            <label class="col-lg-2 col-form-label">
                                Full Name:
                            </label>
                            <div class="col-lg-3 {{ $errors->has('name') ? ' has-danger' : '' }}">
                            	{!! Form::text('name', null, ['class' => 'form-control m-input', 'placeholder' => 'Enter full name']) !!}
								@if ($errors->has('name'))
                                    <span class="form-control-feedback">
                                    	{{ $errors->first('name') }}
                                    </span>
                                @endif
                                <span class="m-form__help">
									Please enter your full name
								</span>
                            </div>
                            
                            <label class="col-lg-2 col-form-label">
                                Email:
                            </label>
                            <div class="col-lg-3 {{ $errors->has('email') ? ' has-danger' : '' }}">
                            	{!! Form::text('email', null, ['class' => 'form-control m-input', 'placeholder' => 'Enter email']) !!}
                            	@if ($errors->has('email'))
                                    <span class="form-control-feedback">
                                    	{{ $errors->first('email') }}
                                    </span>
                                @endif
                                <span class="m-form__help">
									Please enter your email
								</span>
                            </div>
                        </div>
                        
                        <div class="form-group m-form__group row">
                            <label class="col-lg-2 col-form-label">
                                Password:
                            </label>
                            <div class="col-lg-3 {{ $errors->has('password') ? ' has-danger' : '' }}">
                            	{!! Form::password('password', ['class' => 'form-control m-input', 'placeholder' => 'Enter password']) !!}
                            	@if ($errors->has('password'))
                                    <span class="form-control-feedback">
                                    	{{ $errors->first('password') }}
                                    </span>
                                @endif
                                <span class="m-form__help">
									Please enter your password
								</span>
                            </div>
                            
                            <label class="col-lg-2 col-form-label">
                                Confirm Password:
                            </label>
                            <div class="col-lg-3 {{ $errors->has('password_confirmation') ? ' has-danger' : '' }}">
                            	{!! Form::password('password_confirmation', ['class' => 'form-control m-input', 'placeholder' => 'Enter confirm password']) !!}
                            	@if ($errors->has('password_confirmation'))
                                    <span class="form-control-feedback">
                                    	{{ $errors->first('password_confirmation') }}
                                    </span>
                                @endif
                                <span class="m-form__help">
									Please enter your confirm password
								</span>
                            </div>
                        </div>
                        
                        <div class="form-group m-form__group row">
                            <label class="col-lg-2 col-form-label">
                                Status:
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
                                    <button type="submit" class="btn btn-success">Submit</button>
                                    <a href="{{ route('user') }}" class="btn btn-secondary">Cancel</a>
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
    <script src="{{asset('static/backend/js/user/list.js')}}" type="text/javascript"></script>
@stop