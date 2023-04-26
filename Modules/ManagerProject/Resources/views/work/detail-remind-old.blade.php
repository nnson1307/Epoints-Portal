@extends('layout')
@section('title_header')
    <span class="title_header">{{ __('managerwork::managerwork.manage_work') }}</span>
@stop
@section('after_style')
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/sinh-custom.css') }}">
    <link rel="stylesheet" href="{{ asset('static/backend/css/son.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/phu-custom.css')}}">
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
    @include('manager-project::work.detail-work')
    <div class="m-portlet m-portlet--head-sm tab_work_detail">
        <nav class="nav">
            <a class="nav-link" href="{{route('manager-project.work.detail',['id' => $detail['manage_work_id']])}}">{{ __('managerwork::managerwork.comment') }}</a>
            <a class="nav-link " href="{{route('manager-project.work.detail-child-work',['id' => $detail['manage_work_id']])}}">{{ __('managerwork::managerwork.child_task') }}</a>
            <a class="nav-link" href="{{route('manager-project.work.detail-document',['id' => $detail['manage_work_id']])}}">{{ __('managerwork::managerwork.document') }}</a>
            <a class="nav-link active" href="{{route('manager-project.work.detail-remind',['id' => $detail['manage_work_id']])}}">{{ __('managerwork::managerwork.remind') }}</a>
            <a class="nav-link" href="{{route('manager-project.work.detail-history',['id' => $detail['manage_work_id']])}}">{{ __('managerwork::managerwork.history') }}</a>
        </nav>
        @if(count($listRemind) != 0)
            <div class="col-12 mt-3 ml-2">
                <form id="form-search">
                    <div class="row">
                        <input type="hidden" name="sort_date_remind" id="sort_date_remind" value="DESC">
                        <div class="col-2">
                            <input type="text" class="form-control" id="description" name="description" placeholder="{{ __('managerwork::managerwork.content') }}">
                        </div>
                        <div class="col-2">
                            <select class="form-control selectForm" name="staff_id">
                                <option value="">{{ __('managerwork::managerwork.remind_for') }}</option>
                                @foreach($listStaff as $item)
                                    <option value="{{$item['staff_id']}}">{{$item['staff_name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-2">
                            <div class="m-input-icon m-input-icon--right">
                                <input type="text" class="form-control searchDate" name="date_remind" >
                                <span class="m-input-icon__icon m-input-icon__icon--right">
                                        <span><i class="la la-calendar"></i></span></span>
                            </div>
                        </div>
                        <div class="col-4">
                            <button type="button" data-dismiss="modal" class="btn btn-metal" onclick="Remind.removeSearchRemind()">
                                <span class="ss--text-btn-mobi">
                                    <span>{{ __('managerwork::managerwork.delete_th') }}</span>
                                </span>
                            </button>
                            <button type="button" onclick="Remind.search({{$detail['manage_work_id']}})" class="btn ss--btn-search">
                                {{ __('managerwork::managerwork.search') }}
                                <i class="fa fa-search ss--icon-search"></i>
                            </button>
                            <a href="javascript:void(0)" onclick="Remind.sortListRemind()"><i class="fas fa-sort-numeric-down fa-sort-numeric-down-fix"></i></a>
                        </div>
                        @if(count($listRemind) != 0)
                            <div class="col-2 text-right">
                                <button type="button" style="border-radius:20px" onclick="Remind.showPopup()" class=" ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10  m--margin-bottom-5">
                                    <i class="fas fa-plus-circle"></i> {{ __('managerwork::managerwork.ADD_REMIND') }}
                                </button>
                            </div>
                        @endif
                    </div>
                </form>
            </div>
        @endif
        <div class="col-12 pb-5">
            <div class="row append-list-remind">
                @if(count($listRemind) == 0)
                    <div class="col-12 mt-3 ml-2 block-list-history pt-5 pb-5">
                        <div class="h-50">
                            <div class="d-flex align-items-center text-center justify-content-center" style="height: 300px" >
                                <div>
                                    <h5 class="d-block">{{ __('managerwork::managerwork.no_remind') }}</h5>
                                    <button type="button" style="border-radius:20px" onclick="detailCommon.showPopup()" class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10  m--margin-bottom-5">
                                        <i class="fas fa-plus-circle"></i> {{ __('managerwork::managerwork.ADD_REMIND') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    @include('manager-project::work.append.append-list-remind')
                @endif
            </div>
        </div>
    </div>

@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script src="{{ asset('static/backend/js/manager-project/managerWork/list.js?v=' . time()) }}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/manager-project/managerWork/detail-work-remind.js')}}"></script>
    <script src="{{ asset('static/backend/js/manager-project/managerWork/detail-work.js?v=' . time()) }}" type="text/javascript"></script>
@stop
