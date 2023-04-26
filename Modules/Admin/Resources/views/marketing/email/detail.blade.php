@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-email.png')}}" alt="" style="height: 20px;"> {{__('EMAIL')}}</span>
@stop
@section('content')
    <style>
        .form-control-feedback {
            color: #ff0000;
        }

        .modal-backdrop {
            position: relative !important;
        }

        .modal-lg {
            max-width: 65% !important;
        }

    </style>
    @include('admin::marketing.email.modal-customer')
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h2 class="m-portlet__head-text title_index">
                        <span><i class="la la-outdent"></i> {{__('CHI TIẾT CHIẾN DỊCH')}}</span>
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <form id="form-edit">
            <div class="m-portlet__body" id="autotable">
                    <input type="hidden"  id="campaign_id" name="campaign_id" value="{{$item['campaign_id']}}">
                    <div class="form-group m-form__group">
                    <span class="sz_dt">{{__('Tên chiến dịch')}}:
                        <strong> {{$item['name']}}</strong>
                    </span>
                    </div>
                    <div class="form-group m-form__group">
                     <span class="sz_dt">{{__('Số lượng email')}}:
                        <strong> {{$groupNew['number']+$groupSent['number']+$groupCancel['number']}}</strong>
                    </span>
                    </div>
                    <div class="form-group m-form__group">
                     <span class="sz_dt">{{__('Gửi email thành công')}}:
                         @if($groupSent['number']!=null)
                             <strong> {{$groupSent['number']}}</strong>
                         @else
                             <strong> 0</strong>
                         @endif
                    </span>
                    </div>
                    <div class="form-group m-form__group">
                    <span class="sz_dt">{{__('Hủy')}}:
                        @if($groupCancel['number']!=null)
                            <strong> {{$groupCancel['number']}}</strong>
                        @else
                            <strong> 0</strong>
                        @endif

                    </span>
                    </div>

                <div class="table-content">
                    @include('admin::marketing.email.list-detail')
                    <span class="tb_log" style="color: #ff0000"></span>
                </div>

            </div>
            <div class="m-portlet__foot">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m-form__actions--solid m--align-right">
                        <a href="{{route('admin.email')}}"
                           class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md m--margin-right-10">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                        </a>

                    </div>
                </div>
            </div>
        </form>
    </div>



@stop
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/marketing/email/detail.js')}}" type="text/javascript"></script>
@stop
