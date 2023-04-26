@extends('layout')
@section('title_header')
    <span class="title_header">{{ __('managerwork::managerwork.manage_work') }}</span>
@stop
@section('after_style')
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/sinh-custom.css') }}">
    <link rel="stylesheet" href="{{ asset('static/backend/css/son.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/phu-custom.css?v='.time())}}">
    <style>
        .rate {
            float: left;
            height: 46px;
            padding: 0 10px;
        }

        .rate:not(:checked)>input {
            position: absolute;
            top: -9999px;
        }

        .rate:not(:checked)>label {
            float: right;
            width: 1em;
            overflow: hidden;
            white-space: nowrap;
            cursor: pointer;
            font-size: 30px;
            color: #ccc;
        }

        .rate:not(:checked)>label:before {
            content: '★ ';
        }

        .rate>input:checked~label {
            color: #ffc700;
        }

        .rate:not(:checked)>label:hover,
        .rate:not(:checked)>label:hover~label {
            color: #deb217;
        }

        .rate>input:checked+label:hover,
        .rate>input:checked+label:hover~label,
        .rate>input:checked~label:hover,
        .rate>input:checked~label:hover~label,
        .rate>label:hover~input:checked~label {
            color: #c59b08;
        }
        /* material */
        .modal .select2.select2-container,.select2-search__field{
            width: 100% !important;
        }
        span {cursor:pointer; }
        .number{
            -webkit-user-select: none;
            user-select: none;
        }
		.minus, .plus{
            height: 20px;
            width: 20px;
            text-align: center;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 4px;
            display: inline-block;
            vertical-align: middle;
		}
		.number .number-input{
			height:20px;
            width: auto;
            text-align: center;
            font-size: 16px;
			border:1px solid #ddd;
			border-radius:4px;
            display: inline-block;
            vertical-align: middle;
        }
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            /* display: none; <- Crashes Chrome on hover */
            -webkit-appearance: none;
            margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
        }

        input[type=number] {
            -moz-appearance:textfield; /* Firefox */
        }
        .blockUI{
            z-index: 1051 !important;
        }
        .number .form-control-feedback{
            position: absolute;
            color: red;
        }
        .mw-100px{
            min-width: 100px;
        }
    </style>
@endsection
@section('content')
    @include('manager-work::managerWork.detail-work')
    <div class="m-portlet m-portlet--head-sm tab_work_detail pb-5">
        <nav class="nav">
            <a class="nav-link" href="{{route('manager-work.detail',['id' => $detail['manage_work_id']])}}">{{ __('managerwork::managerwork.comment') }}</a>
            <a class="nav-link active" href="{{route('manager-work.detail-child-work',['id' => $detail['manage_work_id']])}}">{{ __('managerwork::managerwork.child_task') }}</a>
            <a class="nav-link" href="{{route('manager-work.detail-document',['id' => $detail['manage_work_id']])}}">{{ __('managerwork::managerwork.document') }}</a>
            <a class="nav-link" href="{{route('manager-work.detail-remind',['id' => $detail['manage_work_id']])}}">{{ __('managerwork::managerwork.remind') }}</a>
            <a class="nav-link" href="{{route('manager-work.detail-history',['id' => $detail['manage_work_id']])}}">{{ __('managerwork::managerwork.history') }}</a>
        </nav>
{{--        @if(count($listWorkChild) != 0)--}}
            <div class="col-12 mt-3 ml-2">
                <form id="form-search" autocomplete="off">
                    <div class="row">
                        <div class="col-2">
                            <select class="form-control selectForm" name="manage_status_id" id="manage_status_id_search">
                                <option value="">{{ __('managerwork::managerwork.status') }}</option>
                                @foreach($listStatus as $item)
                                    <option value="{{$item['manage_status_id']}}">{{$item['manage_status_name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-2">
                            <div class="m-input-icon m-input-icon--right">
                                <input type="text" class="form-control searchDateForm" name="date_created_detail" placeholder="{{ __('managerwork::managerwork.date_created') }}">
                                <span class="m-input-icon__icon m-input-icon__icon--right">
                                        <span><i class="la la-calendar"></i></span></span>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="m-input-icon m-input-icon--right">
                                <input type="text" class="form-control searchDateForm" name="date_end" placeholder="{{ __('managerwork::managerwork.date_expiration') }}">
                                <span class="m-input-icon__icon m-input-icon__icon--right">
                                        <span><i class="la la-calendar"></i></span></span>
                            </div>
                        </div>
                        <div class="col-2">
                            <select class="form-control selectForm selectFormSearch" name="date_overdue">
                                <option value="">{{ __('managerwork::managerwork.date_overdue') }}</option>
                                <option value="10">10 {{ __('managerwork::managerwork.day') }}</option>
                                <option value="20">20 {{ __('managerwork::managerwork.day') }}</option>
                                <option value="30">30 {{ __('managerwork::managerwork.day') }}</option>
                                <option value="40">40 {{ __('managerwork::managerwork.day') }}</option>
                            </select>
                        </div>
                        <div class="col-4">
                            <button type="button" data-dismiss="modal" class="btn btn-metal" onclick="WorkChild.removeSearchWork()">
                                <span class="ss--text-btn-mobi">
                                    <span>{{ __('managerwork::managerwork.delete_th') }}</span>
                                </span>
                            </button>
                            <button type="button" onclick="WorkChild.search({{$detail['manage_work_id']}})" class="btn ss--btn-search">
                                {{ __('managerwork::managerwork.search') }}
                                <i class="fa fa-search ss--icon-search"></i>
                            </button>
                        </div>
{{--                        @if(count($listWorkChild) != 0)--}}
                            <div class="col-12 text-right">
                                <button type="button" style="border-radius:20px" onclick="WorkChild.showPopup()" class=" ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10  m--margin-bottom-5">
                                    <i class="fas fa-plus-circle"></i> {{ __('managerwork::managerwork.add_child_task') }}
                                </button>
                            </div>
{{--                        @endif--}}
                    </div>
                </form>
            </div>
{{--        @endif--}}
        <div class="col-12">
            <div class="row append-list-remind">
{{--                @if(count($listWorkChild) == 0)--}}
{{--                    <div class="col-12 mt-3 ml-2 block-list-history pt-5 pb-5">--}}
{{--                        <div class="h-50">--}}
{{--                            <div class="d-flex align-items-center text-center justify-content-center" style="height: 300px" >--}}
{{--                                <div>--}}
{{--                                    <h5 class="d-block">{{ __('managerwork::managerwork.not_yet_task_connect') }}</h5>--}}
{{--                                    <button type="button" style="border-radius:20px" onclick="WorkChild.showPopup()" class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10  m--margin-bottom-5">--}}
{{--                                        <i class="fas fa-plus-circle"></i> {{ __('managerwork::managerwork.add_child_task') }}--}}
{{--                                    </button>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                @else--}}
                    @include('manager-work::managerWork.append.append-list-work-child')
{{--                @endif--}}
            </div>
        </div>
    </div>
    <form id="form-file" autocomplete="off">
        <div id="block_append"></div>
        <input type="hidden" id="manage_work_id" name="manage_work_id" value="{{$detail['manage_work_id']}}">
    </form>

@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script src="{{ asset('static/backend/js/manager-work/managerWork/list.js?v=' . time()) }}" type="text/javascript"></script>
    <script src="{{ asset('static/backend/js/manager-work/managerWork/detail-work.js?v='. time()) }}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/manager-work/managerWork/detail-work-child.js?v='.time())}}" type="text/javascript"></script>
    <script>
        var ManagerWork = {

            submitCopy: function (id) {

                swal({
                    title: "{{ __('managerwork::managerwork.copy_work') }}",
                    text: "{{ __('managerwork::managerwork.are_you_copy') }}",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: "{{ __('managerwork::managerwork.yes') }}",
                    cancelButtonText: "{{ __('managerwork::managerwork.cancel') }}"
                }).then(function (result) {
                    if (result.value) {
                        $.post(laroute.route('manager-work.copy', {id: id}), function () {
                            swal(
                                "{{ __('managerwork::managerwork.copy_success') }}",
                                '',
                                'success'
                            );
                        });
                    }
                });
            },
        }
    </script>
@stop
