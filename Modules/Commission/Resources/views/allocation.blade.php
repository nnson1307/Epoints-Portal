@extends('layout')
@section('after_style')
    <link rel="stylesheet" href="{{ asset('static/backend/css/hao.css') }}">
@endsection
@section('title_header')
    <span class="title_header"><img src="{{ asset('uploads/admin/icon/icon-product.png') }}" alt=""
                                    style="height: 20px;">
        {{ __('QUẢN LÝ HOA HỒNG') }}
    </span>
@endsection
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                        <span class="m-portlet__head-icon">
                            <i class="fa fa-plus-circle"></i>
                        </span>
                    <h3 class="m-portlet__head-text">
                        {{ __('PHÂN BỔ HOA HỒNG') }}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="{{route('admin.commission')}}"
                   class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('HỦY')</span>
                            </span>
                </a>
            </div>
        </div>

        <div class="m-wizard m-wizard--1 m-wizard--success m-wizard--step-between" id="m_wizard">

            <!--begin: Form Wizard Head -->
            <div class="m-wizard__head m-portlet__padding-x">

                <!--begin: Form Wizard Nav -->
                <div class="m-wizard__nav">
                    <div class="m-wizard__steps" style="width: auto; margin: auto;">
                        <div class="m-wizard__step m-wizard__step--current" m-wizard-target="m_wizard_form_step_1"
                             style="padding-right: 10px;">
                            <div class="m-wizard__step-info">
                                <a href="#" class="m-wizard__step-number">
                                    <span><span>1</span></span>
                                </a>
                                <div class="m-wizard__step-line">
                                    <span></span>
                                </div>
                                <div class="m-wizard__step-label">
                                    @lang('Chọn nhân viên')
                                </div>
                            </div>
                        </div>
                        <div class="m-wizard__step" m-wizard-target="m_wizard_form_step_2"
                             style="padding-right: 10px;">
                            <div class="m-wizard__step-info">
                                <a href="#" class="m-wizard__step-number">
                                    <span><span>2</span></span>
                                </a>
                                <div class="m-wizard__step-line">
                                    <span></span>
                                </div>
                                <div class="m-wizard__step-label">
                                    @lang('Chọn hoa hồng')
                                </div>
                            </div>
                        </div>
                        <div class="m-wizard__step" m-wizard-target="m_wizard_form_step_3">
                            <div class="m-wizard__step-info">
                                <a href="#" class="m-wizard__step-number">
                                    <span><span>3</span></span>
                                </a>
                                <div class="m-wizard__step-line">
                                    <span></span>
                                </div>
                                <div class="m-wizard__step-label">
                                    @lang('Phân bổ hoa hồng')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--end: Form Wizard Nav -->
            </div>

            <!--end: Form Wizard Head -->

            <!--begin: Form Wizard Form-->
            <div class="m-wizard__form">


                <!--begin: Form Body -->
                <div class="m-portlet__body">

                    <!--begin: Form Wizard Step 1-->
                    <div class="m-wizard__form-step" id="m_wizard_form_step_1">
                        <div id="autotable-staff">
                            <form class="frmFilter">
                                <div class="padding_row row filter-block">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="search"
                                                   placeholder="@lang("Nhập thông tin tìm kiếm")">
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <div class="row">
                                            @php $i = 0; @endphp
                                            @foreach ($STAFF_FILTER as $name => $item)
                                                @if ($i > 0 && ($i % 5 == 0))
                                        </div>
                                        <div class="form-group m-form__group row align-items-center">
                                            @endif
                                            @php $i++; @endphp
                                            <div class="col-lg-3 form-group input-group">
                                                @if(isset($item['text']))
                                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            {{ $item['text'] }}
                                        </span>
                                                    </div>
                                                @endif
                                                {!! Form::select($name, $item['data'], $item['default'] ?? null, ['class' => 'form-control m-input m_selectpicker']) !!}
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="form-group m-form__group">
                                            <button class="btn btn-primary color_button btn-search btn-search-filter">{{ __('TÌM KIẾM') }} <i
                                                        class="fa fa-search ic-search m--margin-left-5"></i></button>

                                            <a href="javascript:void(0)" onclick="allowance.clearFilterStaff()" class="btn btn-primary color_button btn-search padding9x">
                                                <span>
                                                    <i class="flaticon-refresh"></i>
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="table-content m--padding-top-30">
                                @include('commission::components.allocation.list-staff')
                            </div>
                        </div>
                    </div>

                    <!--end: Form Wizard Step 1-->

                    <!--begin: Form Wizard Step 2-->
                    <div class="m-wizard__form-step" id="m_wizard_form_step_2">
                        <div id="autotable-commission">
                            <form class="frmFilter">
                                <div class="padding_row row filter-block">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="commission_name"
                                                   placeholder="@lang("Nhập thông tin tìm kiếm")">
                                        </div>
                                    </div>
                                    <div class="col-lg-9">
                                        <div class="row">
                                            @php $i = 0; @endphp
                                            @foreach ($COMMISSION_FILTER as $name => $item)
                                                @if ($i > 0 && ($i % 5 == 0))
                                        </div>
                                        <div class="form-group m-form__group row align-items-center">
                                            @endif
                                            @php $i++; @endphp
                                            <div class="col-lg-3 form-group input-group">
                                                @if(isset($item['text']))
                                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            {{ $item['text'] }}
                                        </span>
                                                    </div>
                                                @endif
                                                {!! Form::select($name, $item['data'], $item['default'] ?? null, ['class' => 'form-control m-input m_selectpicker']) !!}
                                            </div>
                                            @endforeach
                                            <div class="col-lg-2 form-group">
                                                <button class="btn btn-primary color_button btn-search"
                                                        style="display: block">
                                                    @lang('TÌM KIẾM') <i
                                                            class="fa fa-search ic-search m--margin-left-5"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="table-content m--padding-top-30">
                                @include('commission::components.allocation.list-commission')
                            </div>
                        </div>
                    </div>

                    <!--end: Form Wizard Step 2-->

                    <!--begin: Form Wizard Step 3-->
                    <div class="m-wizard__form-step" id="m_wizard_form_step_3">

                    </div>

                    <!--end: Form Wizard Step 3-->
                </div>

                <!--end: Form Body -->
                <form class="m-form m-form--label-align-left- m-form--state-" id="m_form" novalidate="novalidate">
                    <!--begin: Form Actions -->
                    <div class="m-portlet__foot m-portlet__foot--fit m--margin-top-40">
                        <div class="m-form__actions m-form__actions">
                            <div class="m--align-right">
                                <button class="btn btn-metal m-btn m-btn--custom m-btn--icon"
                                        data-wizard-action="prev">
																	<span>
																		<i class="la la-arrow-left"></i>&nbsp;&nbsp;
																		<span>@lang('Trở về')</span>
																	</span>
                                </button>
                                <button class="btn btn-success m-btn m-btn--custom m-btn--icon"
                                        data-wizard-action="submit">
																	<span>
																		<i class="la la-check"></i>&nbsp;&nbsp;
																		<span>@lang('Lưu')</span>
																	</span>
                                </button>
                                <button class="btn btn-info m-btn m-btn--custom m-btn--icon"
                                        data-wizard-action="next">
																	<span>
																		<span>@lang('Tiếp theo')</span>&nbsp;&nbsp;
                                                                        <i class="la la-arrow-right"></i>
																	</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!--end: Form Actions -->
                </form>
            </div>

            <!--end: Form Wizard Form-->
        </div>
    </div>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>

    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>

    <script src="{{ asset('static/backend/js/admin/commission/allowance.js?v=' . time()) }}"></script>

    <script>
        WizardDemo.init();
        allowance._init();

        $('.m_selectpicker').select2({
            width: '100%'
        });
    </script>
@stop