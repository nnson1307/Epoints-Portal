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
{{--    @include('manager-project::work.detail-work')--}}
<div class="m-portlet m-portlet--head-sm">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">

                    </span>
                <h2 class="m-portlet__head-text">
                    <i class="fa fa-plus-circle ss--icon-title m--margin-right-5"></i>
                    {{ __('managerwork::managerwork.DETAIL_WORK') }}
                </h2>
            </div>
        </div>
        <div class="m-portlet__head-tools">
            @if(isset($detail['is_staff']) && $detail['is_staff'] == 1)
                @if(isset($detail['is_approve_id']) && $detail['is_approve_id'] == 1 && $detail['manage_status_id'] == 3 && ($detail['approve_id'] == \Auth::id() || in_array(\Illuminate\Support\Facades\Auth::id(),$listStaffManage)))
                    <button type="button" onclick="ManagerWork.reject('{{$detail['manage_work_id']}}')"
                            class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10  m--margin-bottom-5">
                            <span class="ss--text-btn-mobi">
                            <i class="la la-close"></i>
                            <span>{{ __('managerwork::managerwork.REJECT') }}</span>
                            </span>
                    </button>
                    <button type="button" onclick="ManagerWork.approve('{{$detail['manage_work_id']}}')"
                            class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10  m--margin-bottom-5">
                                <span class="ss--text-btn-mobi">
                                <i class="la la-check"></i>
                                <span>{{ __('managerwork::managerwork.APPROVE') }}</span>
                                </span>
                    </button>
                @endif
                <button type="button" onclick="ManagerWork.submitCopy('{{$detail['manage_work_id']}}')"
                        class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10  m--margin-bottom-5">
                                <span class="ss--text-btn-mobi">
                                <i class="la la-check"></i>
                                <span>{{ __('managerwork::managerwork.COPY_BTN') }}</span>
                                </span>
                </button>
                <button type="button" onclick="detailCommon.showPopup()"
                        class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10  m--margin-bottom-5">
                                <span class="ss--text-btn-mobi">
                                <i class="la la-check"></i>
                                <span>{{ __('managerwork::managerwork.REMIND_BTN') }}</span>
                                </span>
                </button>
                {{--            <button type="button" onclick="ManagerWork.edit('{{$detail['manage_work_id']}}')"--}}

                @if((in_array(\Auth::id(),[$detail['processor_id'],$detail['assignor_id']]) && $detail['is_edit'] == 1) || in_array(\Illuminate\Support\Facades\Auth::id(),$listStaffManage))
                    <button type="button" onclick="WorkDetail.showPopup('{{$detail['manage_work_id']}}')"
                            class="ss--btn-mobiles btn text-uppercase ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10  m--margin-bottom-5">
                                    <span class="ss--text-btn-mobi">
                                    <i class="la la-check"></i>
                                    <span>{{__('managerwork::managerwork.edit') }}</span>
                                    </span>
                    </button>
                @endif

                @if((in_array(\Auth::id(),[$detail['processor_id'],$detail['assignor_id']]) && $detail['is_deleted'] == 1) || in_array(\Illuminate\Support\Facades\Auth::id(),$listStaffManage))
    {{--                <button onclick="ManagerWork.remove(this, '{{ $detail['manage_work_id'] }}','{{ $detail['total_child_job'] }}')"--}}
    {{--                        class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"--}}
    {{--                        title="{{__('Xóa')}}"><i class="la la-trash"></i>--}}
    {{--                </button>--}}

                        <button type="button" onclick="ManagerWorkDetail.remove(this, '{{ $detail['manage_work_id'] }}','{{ $detail['total_child_job'] }}',true)"
                                class="ss--btn-mobiles btn text-uppercase btn-danger ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10  m--margin-bottom-5">
                                    <span class="ss--text-btn-mobi">
                                    <i class="la la-trash"></i>
                                    <span>{{__('Xóa')}}</span>
                                    </span>
                        </button>
                @endif
            @endif

            <a href="{{route('manager-project.work',['manage_project_id' => $param['manage_project_id']])}}"
               class="ss--btn-mobiles btn btn-metal ss--btn m-btn m-btn--icon m-btn--wide m-btn--md  m--margin-left-10 m--margin-bottom-5">
                    <span class="ss--text-btn-mobi">
                    <i class="la la-arrow-left"></i>
                    <span>{{__('BACK')}}</span>
                    </span>
            </a>
        </div>
    </div>
    <form id="form-edit">
        <div class="m-portlet__body pt-0">
            <div class="row">
                <div class="col-6">
                    <div class="row">
                        <div class="col-lg-12 mb-1 mt-3">
                            <h1 class="work_title_detail">#{{$detail['manage_work_code']}}: {{$detail['manage_work_title']}}</h1>
                        </div>
                        @if(isset($detail['parent_id']))
                            <div class="col-lg-12 mb-2">
                                <a style="color:#5BAABF;font-size: 15px;" href="{{route('manager-project.work.detail',['id' => $detail['parent_id']])}}">{{__('Parent task')}} #{{$detail['parent_manage_work_code']}}: </a> {{$detail['parent_manage_work_title']}}
                            </div>
                        @endif
                        <div class="col-lg-12">
                            <p class="info_detail_work">
                                <span>{{__('Tạo bởi'). ' ' . $detail['createdStaff_name']}}</span>
                                <a href="{{route('manager-project.project.project-info-overview',['id' => $detail['manage_project_id']])}}" class="text-black">
                                    <span>{{$detail['manage_project_name']}}</span>
                                </a>
                                <span><i class="far fa-comment-alt"></i> ({{$detail['total_message']}})</span>
                                <span><i class="fas fa-paperclip"></i> ({{$detail['total_attach']}})</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-6 pt-5">
                    <div class="row">
                        <div class="col-12 mb-1">
                            <div class="row d-flex align-items-center">
                                <div class="col-2">
                                    <p class="mb-0"><strong>{{ __('managerwork::managerwork.process') }}</strong></p>
                                </div>
                                <div class="col-6">
                                    <div class="w-50 d-inline-block">
                                        <div class="progress progress-lg ">
                                            <div class="progress-bar progress-bar-main kt-bg-warning" role="progressbar" style="width: {{$detail['progress']}}%;background: #38daca" aria-valuenow="{{$detail['progress']}}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <span class="d-inline-block progress-bar-main-text">{{$detail['progress'] == null || $detail['progress'] == '' ? 0 : $detail['progress']}}%</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mb-1">
                            <div class="row d-flex align-items-center">
                                <div class="col-2">
                                    <p class="mb-0"><strong>{{ __('managerwork::managerwork.status') }}</strong></p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-0 ml-0 status_work_priority " style="background-color:{{$detail['manage_color_code']}}">{{$detail['manage_status_name']}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mb-1">
                            <div class="row d-flex align-items-center">
                                <div class="col-2">
                                    <p class="mb-0"><strong>{{ __('managerwork::managerwork.date_expiration') }}</strong></p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-0 ml-0 ">{{\Carbon\Carbon::parse($detail['date_end'])->format('d/m/Y')}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="m-portlet m-portlet--head-sm">
    <div id="accordion_work">
        <div class="card">
            <div class="card-header" id="headingOne">
                <button class="btn btn-link" data-toggle="collapse" disabled data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    <h5 class="mb-0 text-uppercase">
                        {{ __('managerwork::managerwork.work_info') }}
                    </h5>
                </button>
            </div>

            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" >
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th width="30%"></th>
                                    <th width="60%"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>{{ __('managerwork::managerwork.type_work') }}</td>
                                    <td>{{$detail['manage_type_work_name']}}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('managerwork::managerwork.priority') }}</td>
                                    <td><p class="mb-0 ml-0 status_work_priority work_priority_{{$detail['priority']}}">{{$detail['priority'] == 1 ? __('Cao') : ($detail['priority'] == 2 ? __('Bình thường') : __('Thấp'))}}</p></td>
                                </tr>
                                <tr>
                                    <td>{{ __('managerwork::managerwork.staff_processor') }}</td>
                                    <td>{{$detail['staff_name']}}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('managerwork::managerwork.staff_support') }}</td>
                                    <td>
                                        @foreach($detail['list_support'] as $key => $item)
                                            @if($key != 0)
                                                {{' , '.$item['staff_name']}}
                                            @else
                                                {{$item['staff_name']}}
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tags</td>
                                    <td>
                                        @foreach($detail['list_tag'] as $key => $item)
                                            @if($key != 0)
                                                {{' , '.$item['manage_tag_name']}}
                                            @else
                                                {{$item['manage_tag_name']}}
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ __('managerwork::managerwork.type_tags') }}</td>
                                    <td><p class="mb-0 ml-0 status_work_priority work_priority_{{$detail['type_card_work']}}">{{$detail['type_card_work'] == 'kpi' ? __('Kpi') : __('Thường')}}</p></td>
                                </tr>
                                <tr>
                                    <td>{{ __('managerwork::managerwork.type_customer') }}</td>
                                    @if($detail['customer_id'] == null)
                                        <td></td>
                                    @else
                                        <td>{{$detail['manage_work_customer_type'] == 'customer' ? __('managerwork::managerwork.customer') : ($detail['manage_work_customer_type'] == 'lead' ? __('managerwork::managerwork.lead') : ($detail['manage_work_customer_type'] == 'deal' ? __('managerwork::managerwork.deal') : '' ))}}</td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>{{__('managerwork::managerwork.customer_work')}}</td>
                                    @if($detail['customer_id'] == null)
                                        <td></td>
                                    @else
                                        @if($detail['manage_work_customer_type'] == 'customer')
                                            <td><a href="{{route('admin.customer.detail', ['id' => $detail['customer_id']])}}" target="_blank">{{$detail['customer_name']}}</a> </td>
                                        @elseif($detail['manage_work_customer_type'] == 'lead')
                                            <td><a href="{{route('customer-lead', ['id' => $detail['customer_id']])}}" target="_blank">{{$detail['lead_name']}}</a> </td>
                                        @elseif($detail['manage_work_customer_type'] == 'deal')
                                            <td><a href="{{route('customer-lead.customer-deal', ['id' => $detail['customer_id']])}}" target="_blank">{{$detail['deal_name']}}</a> </td>
                                        @endif
                                    @endif
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <label class="m-checkbox m-checkbox--state-success mt-0">
                                            <input type="checkbox" disabled value="1" {{$detail['is_approve_id'] == 1 ? 'checked' : '' }}>
                                            <span style="top: -5px"></span>
                                        </label>
                                        <span class="pt-1">{{__('managerwork::managerwork.work_approve')}}</span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-6">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th width="30%"></th>
                                    <th width="60%"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>{{__('managerwork::managerwork.startdate')}}</td>
                                    <td>{{isset($detail['date_start']) && $detail['date_start'] != '0000-00-00 00:00:00' ? \Carbon\Carbon::parse($detail['date_start'])->format('d/m/Y H:i') : ''}}</td>
                                </tr>
                                <tr>
                                    <td>{{__('managerwork::managerwork.enddate')}}</td>
                                    <td>{{isset($detail['date_end']) && $detail['date_end'] != '0000-00-00 00:00:00' ? \Carbon\Carbon::parse($detail['date_end'])->format('d/m/Y H:i') : ''}}</td>
                                </tr>
                                <tr>
                                    <td>{{__('managerwork::managerwork.process')}}</td>
                                    <td>
                                        <div class="w-50 d-inline-block">
                                            <div class="progress progress-lg ">
                                                <div class="progress-bar progress-bar-main kt-bg-warning" role="progressbar" style="width: {{$detail['progress']}}%;background: #38daca" aria-valuenow="{{$detail['progress']}}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                        <span class="d-inline-block progress-bar-main-text">{{$detail['progress'] == null || $detail['progress'] == '' ? 0 : $detail['progress']}}%</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{__('managerwork::managerwork.time')}}</td>
                                    <td>
                                        @if(isset($detail['time']))
                                            {{$detail['time'].' '.($detail['time_type'] == 'd' ? __('managerwork::managerwork.day') : ($detail['time_type'] == 'h' ? __('managerwork::managerwork.hour') : ''))}}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{__('managerwork::managerwork.date_finish')}}</td>
                                    <td>{{isset($detail['date_finish']) && $detail['date_finish'] != '0000-00-00 00:00:00' ? \Carbon\Carbon::parse($detail['date_finish'])->format('d/m/Y') : ''}}</td>
                                </tr>
                                <tr>
                                    <td>{{__('managerwork::managerwork.repeat')}}</td>
                                    <td>
                                        @if($detail['repeat_type'] !=  NULL)
                                            @if($detail['repeat_type'] == 'daily')
                                                <label>{{__('Lặp lại')}} : {{__('managerwork::managerwork.every_day')}}</label>
                                                <br>
                                            @elseif($detail['repeat_type'] == 'weekly')
                                                <label>{{__('Lặp lại')}} : {{__('Hàng tuần một lần mỗi')}}</label>
                                                <div class="pl-3 block_weekly"
                                                     style="padding-left : 0px">
                                                    @for($i = 0; $i <= 6 ; $i++)
                                                        <label class="weekly-select
                                                        @if(isset($detail['repeat_time_list'][$i]))weekly-select-active @endif
                                                                ">
                                                            {{$i+2 == 8 ? __('CN') : __('T'.($i+2))}}
                                                        </label>
                                                    @endfor
                                                </div>
                                            @elseif($detail['repeat_type'] == 'monthly')
                                                <label>{{__('Lặp lại')}} : {{__('managerwork::managerwork.every_month') }}</label>
                                                <div class="pl-3 block_weekly"
                                                     style="padding-left : 0px">
                                                    @foreach($detail['repeat_time_list'] as $dayMonth)
                                                        <label class="weekly-select weekly-select- weekly-select-active">
                                                            {{$dayMonth}}
                                                        </label>
                                                    @endforeach
                                                </div>

                                            @endif

                                            @if($detail['repeat_end'] == 'none')
                                                <label>{{__('Kết thúc')}} : {{__('Không bao giờ')}}</label>
                                            @elseif($detail['repeat_end'] == 'after')
                                                <label>{{__('Kết thúc')}} : {{__('Sau')}} {{$detail['repeat_end_time']}} @if($detail['repeat_end_type'] == 'd') {{__('Ngày')}} @elseif($detail['repeat_end_type'] == 'w') {{__('Tuần')}} @else {{__('Tháng')}} @endif</label>
                                            @elseif($detail['repeat_end'] == 'date')
                                                <label>{{__('Kết thúc')}} : {{__('Vào ngày')}} {{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $detail['repeat_end_full_time'])->format('d/m/Y')}}</label>
                                            @endif
                                        @endif

                                    </td>
                                </tr>
                                <tr>
                                    <td>{{__('managerwork::managerwork.status')}}</td>
                                    <td><p class="mb-0 ml-0 status_work_priority " style="background-color:{{$detail['manage_color_code']}}">{{$detail['manage_status_name']}}</p></td>
                                </tr>
                                <tr>
                                    <td>{{__('managerwork::managerwork.staff_approve')}}</td>
                                    <td>{{$detail['approve_name']}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if($detail['description'] != null)
            <div class="card">
                <div class="card-header" id="headingTwo">
                    <button class="btn btn-link position-relative" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                        <h5 class="mb-0 text-uppercase">
                            {{__('managerwork::managerwork.description_work')}}
                        </h5>
                        <span class="arrow-des-work">
                            <i class="fa fa-chevron-down"></i>
                            <i class="fa fa-chevron-up"></i>
                        </span>
                    </button>
                </div>

                <div id="collapseTwo" class="collapse show " aria-labelledby="headingTwo" >
                    <div class="card-body ">
                        {!! $detail['description'] !!}
                    </div>
                    <a href="javascript:voi(0)" class="btn-xem-them-description">{{__('Xem thêm')}} <i class="fa fa-angle-double-down"></i></a>
                </div>
            </div>
        @endif
    </div>
</div>
<div class="modal fade" id="modalEdit" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <!-- Modal content-->
    </div>
</div>
<div class="modal fade" id="my_modal" role="dialog">

</div>
<form id="form-file" autocomplete="off">
    <div id="block_append"></div>
    <input type="hidden" id="manage_work_id" name="manage_work_id" value="{{$detail['manage_work_id']}}">
</form>
<form id="form-work-detail" autocomplete="off">
    <div id="append-add-work-detail"></div>
</form>

<div class="m-portlet m-portlet--head-sm tab_work_detail">

</div>
<div class="append_popup_show"></div>
<input type="hidden" id="manage_work_id" value="{{$detail['manage_work_id']}}">

<form id="frm-search-document">
    <input type="hidden" id="manage_project" name="manage_project_id" value="{{$param['manage_project_id']}}">
    <input type="hidden" id="manage_work_id" name="manage_work_id" value="{{$detail['manage_work_id']}}">
</form>

@endsection
@section('after_script')
<script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
{{--<script src="{{ asset('static/backend/js/manager-project/managerWork/detail-work.js?v=' . time()) }}" type="text/javascript"></script>--}}
<script src="{{ asset('static/backend/js/manager-project/managerWork/detail-tab.js?v=' . time()) }}" type="text/javascript"></script>

<script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
<script src="{{ asset('static/backend/js/manager-project/managerWork/list.js?v=' . time()) }}" type="text/javascript"></script>
<script src="{{asset('static/backend/js/manager-project/managerWork/detail-work-remind.js?v='.time())}}"></script>
<script src="{{ asset('static/backend/js/manager-project/managerWork/detail-work.js?v=' . time()) }}" type="text/javascript"></script>
    <script>
        $(document).ready(function (){
            @if($detail['parent_id'] == null)
                ChangeTab.tabComment();
            @else
                ChangeTab.tabComment('document');
            @endif

//            if ($('#collapseTwo .card-body').height() > 60) {
//                $('#collapseTwo').addClass('height-description-work-main');
//                $('#collapseTwo .card-body').addClass('height-description-work');
//            }

            $('.btn-xem-them-description').click(function (){
                $('#collapseTwo').removeClass('height-description-work-main');
                $('#collapseTwo .card-body').removeClass('height-description-work');
            })
        });
    </script>
@stop
