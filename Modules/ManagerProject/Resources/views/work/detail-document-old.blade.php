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
    <div class="m-portlet m-portlet--head-sm tab_work_detail pb-5">
        <nav class="nav">
            <a class="nav-link" href="{{route('manager-project.work.detail',['id' => $detail['manage_work_id']])}}">{{ __('managerwork::managerwork.comment') }}</a>
            <a class="nav-link" href="{{route('manager-project.work.detail-child-work',['id' => $detail['manage_work_id']])}}">{{ __('managerwork::managerwork.child_task') }}</a>
            <a class="nav-link active" href="{{route('manager-project.work.detail-document',['id' => $detail['manage_work_id']])}}">{{ __('managerwork::managerwork.document') }}</a>
            <a class="nav-link" href="{{route('manager-project.work.detail-remind',['id' => $detail['manage_work_id']])}}">{{ __('managerwork::managerwork.remind') }}</a>
            <a class="nav-link" href="{{route('manager-project.work.detail-history',['id' => $detail['manage_work_id']])}}">{{ __('managerwork::managerwork.history') }}</a>

        @if(count($listDocument) != 0)
            <div class="col-12 mt-3 ml-2 text-right">
                <button type="button" style="border-radius:20px" onclick="Document.showPopup()" class=" ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10  m--margin-bottom-5">
                    <i class="fas fa-plus-circle"></i> {{ __('managerwork::managerwork.add_document') }}
                </button>
            </div>
            <div class="col-12 mt-3 ml-2 append-list-document">

            </div>
        @else
            <div class="col-12 mt-3 ml-2 block-list-history pt-5 pb-5">
                <div class="h-50">
                    <div class="d-flex align-items-center text-center justify-content-center" style="height: 300px" >
                        <div>
                            <h5 class="d-block">{{ __('managerwork::managerwork.no_document') }}</h5>
                            <button type="button" style="border-radius:20px" onclick="Document.showPopup()" class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10  m--margin-bottom-5">
                                <i class="fas fa-plus-circle"></i> {{ __('managerwork::managerwork.add_document') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <form id="form-file" autocomplete="off">
        <div id="block_append"></div>
        <input type="hidden" id="manage_work_id" name="manage_work_id" value="{{$detail['manage_work_id']}}">
    </form>
@endsection
@section('after_script')
    <script type="text/template" id="imageShow">
        <div class="image-show col-12">
            <img class="img-fluid" src="{link}">

            <span class="delete-img-document" style="display: block;">
                <a href="javascript:void(0)" onclick="Document.removeImage()">
                    <i class="fas fa-trash-alt"></i>
                </a>
            </span>
        </div>
    </script>
    <script type="text/template" id="imageShowFile">
        <div class="image-show col-12">
            <img src="{{asset('static/backend/images/document.png')}}">

            <span class="delete-img-document" style="display: block;">
                <a href="javascript:void(0)" onclick="Document.removeImage()">
                    <i class="fas fa-trash-alt"></i>
                </a>
            </span>
        </div>
    </script>
    <script src="{{asset('static/backend/js/manager-project/managerWork/detail-work-document.js?v='.time())}}"></script>
    <script src="{{ asset('static/backend/js/manager-project/managerWork/list.js?v=' . time()) }}" type="text/javascript"></script>
    <script src="{{ asset('static/backend/js/manager-project/managerWork/detail-work.js?v=' . time()) }}" type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            Document.search(1);
        })
    </script>
@stop
