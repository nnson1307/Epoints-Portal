@extends('layout')
@section('title_header')
    <span class="title_header"><img src="{{ asset('static/backend/images/icon/icon-staff.png') }}" alt="" style="height: 20px;">
        {{ __('managerwork::managerwork.manage_work') }}</span>
@endsection
@section('after_style')
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/sinh-custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/phu-custom.css') }}">
    <link type="text/css" href="https://demos.creative-tim.com/argon-design-system/assets/css/argon-design-system.min.css?v=1.2.0">
    <link rel="stylesheet" href="{{asset('vue/kanban-managerwork/css/app.css')}}">
    <style>
        .modal .select2.select2-container,
        .select2-search__field {
            width: 100% !important;
        }

        .m-body .m-content{
            padding: 0 15px !important
        }

        .message_work_detai img{
            max-width: 100%;
        }
        
        .mkc_circle { animation: mck_progress 1000ms ease-in-out; }
        @keyframes mck_progress { 0% { stroke-dasharray: 0, 100; }}

        /* Layout styles only, not needed for functionality */
        .grid {
            display: grid;
            grid-column-gap: 1em;
            grid-row-gap: 1em;
            grid-template-columns: repeat(1, 1fr);
        }

        @media (min-width: 31em) {
            .grid { grid-template-columns: repeat(4, 1fr);}
        }
    </style>
@endsection
@section('content')
<div class="m-portlet" id="autotable">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon">
                    <i class="la la-th-list"></i>
                </span>
                <h3 class="m-portlet__head-text">
                    {{ __('managerwork::managerwork.list_work') }}
                </h3>
            </div>
        </div>
        <div class="m-portlet__head-tools">
            <a href="{{route('manager-work')}}" class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm mr-3">
                <span><i class="fa fa-cog"></i><span> {{ __('DANH SÁCH') }}</span></span>
            </a>
            <a href="javascript:void(0)" onclick="WorkChild.showPopup()" class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                <span>
                    <i class="fa fa-plus-circle"></i>
                    <span> {{ __('managerwork::managerwork.add_work') }}</span>
                </span>
            </a>
            <a href="javascript:void(0)" onclick="WorkChild.showPopup()" class="btn btn-outline-accent m-btn m-btn--icon m-btn--icon-only m-btn--pill color_button btn_add_mobile" style="display: none">
                <i class="fa fa-plus-circle" style="color: #fff"></i>
            </a>
        </div>
    </div>
    <div class="m-portlet__body">
        <div class="table-content">
            <div class="d-flex justify-contents-center overfollow-scroll">
                <div id="app"></div>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="comment-popup">
    <div class="modal-dialog modal-dialog-centered modal-lg">
    </div>
</div>

<form id="form-work" autocomplete="off">
    <div id="append-add-work"></div>
</form>
<div class="append-popup"></div>
<script src="{{asset('vue/kanban-managerwork/js/app.js?v=' . time())}}"></script>
@endsection

@section('after_script')
    <script src="{{ asset('static\backend/js/manager-work/table-excel/jquery.table2excel.js') }}"
            type="text/javascript">
    </script>
    <script src="{{ asset('static/backend/js/manager-work/managerWork/kanban.js?v=' . time()) }}"
            type="text/javascript">
    </script>
    <link rel="stylesheet" href="{{ asset('static/backend/js/manager-work/managerWork/kanban/jqwidgets/styles/jqx.base.css') }}" type="text/css"/>
    <script src="{{ asset('static/backend/js/admin/service/autoNumeric.min.js?v=' . time()) }}"></script>
    <script>
        var decimal_number = {{ isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0 }};
    </script>
    <script src="{{ asset('static/backend/js/manager-work/managerWork/kanban/jqwidgets/jqxcore.js?v=' . time()) }}"
            type="text/javascript"></script>
    <script src="{{ asset('static/backend/js/manager-work/managerWork/kanban/jqwidgets/jqxsortable.js?v=' . time()) }}"
            type="text/javascript"></script>
    <script src="{{ asset('static/backend/js/manager-work/managerWork/kanban/jqwidgets/jqxkanban.js?v=' . time()) }}"
            type="text/javascript"></script>
    <script src="{{ asset('static/backend/js/manager-work/managerWork/kanban/jqwidgets/jqxsplitter.js?v=' . time()) }}"
            type="text/javascript"></script>
    <script src="{{ asset('static/backend/js/manager-work/managerWork/kanban/jqwidgets/jqxdata.js?v=' . time()) }}"
            type="text/javascript"></script>
    <script src="{{ asset('static/backend/js/manager-work/managerWork/kanban/jqwidgets/demos.js?v=' . time()) }}"
            type="text/javascript"></script>
    <script>
        $(document).ready(function () {

            $(document).on('click', '.comments', function (e) {
                var mana_work_id = $(this).attr('manager-work-id');
                if (mana_work_id) {
                    $.ajax({
                        url: laroute.route('manager-work.load-comment'),
                        method: "POST",
                        data: {
                            manage_work_id: mana_work_id,
                        },
                        success: function (res) {
                            if (res.error == 0) {
                                $('#comment-popup .modal-dialog').html(res.data);
                                $('#comment-popup #description_comment').summernote('code');
                                $('#comment-popup').modal('show');
                            } else {
//                                swal.fire(res.message, '', 'error');
                            }
                        }
                    });
                }
                if (e.target === $(this).find('.comment-button .fa-comments')[0]) ;

            });

            $(document).on('click', '.assigned-users', function (e) {
                var mana_work_id = $(this).attr('manage_work_id');
                if (mana_work_id) {
                    $.ajax({
                        url: laroute.route('manager-work.kanban-view.show-popup-staff'),
                        data: {
                            manage_work_id : mana_work_id
                        },
                        method: "POST",
                        dataType: "JSON",
                        success: function(res) {
                            if (res.error == false) {
                                $('.append-popup').empty();
                                $('.append-popup').append(res.view);
                                $('#popup-list-staff').modal('show');
                            } else {
                                swal('',res.message,'error');
                            }
                        },
                        error: function (res) {
                            var mess_error = '';
                            $.map(res.responseJSON.errors, function (a) {
                                mess_error = mess_error.concat(a + '<br/>');
                            });
                            swal('', mess_error, "error");
                        }
                    });
                }
                // if (e.target === $(this).find('.comment-button .fa-comments')[0]) ;

            });

            $(document).on('click', '.title-item-comment', function () {
                var str = $(this).closest('[id^=kanban2_]').attr('id');
                var id = str.replace('kanban2_', '');
                // window.location.href = laroute.route('manager-work.detail', {id: id})
                window.open(laroute.route('manager-work.detail', {id: id}),'_blank')
            });
            /*
            cập nhật tiến độ
             */
            $(document).on('click', '.percentage-update', function () {
                var str = $(this).closest('[id^=kanban2_]').attr('id');
                var mana_work_id = str.replace('kanban2_', '');
                if (mana_work_id) {
                    $.ajax({
                        url: laroute.route('manager-work.load-form-update-process'),
                        method: "POST",
                        data: {
                            manage_work_id: mana_work_id,
                        },
                        success: function (res) {
                            if (res.error == 0) {
                                $('#comment-popup .modal-dialog').html(res.data);
                                $('#comment-popup').modal('show');
                            } else {
//                                swal.fire(res.message, '', 'error');
                            }
                        }
                    });
                }
            });
            /*
            cập nhật ngày hết hạn
             */
            $(document).on('click', '.date-end-update', function () {
                var str = $(this).closest('[id^=kanban2_]').attr('id');
                var mana_work_id = str.replace('kanban2_', '');
                if (mana_work_id) {
                    $.ajax({
                        url: laroute.route('manager-work.load-form-update-date-end'),
                        method: "POST",
                        data: {
                            manage_work_id: mana_work_id,
                        },
                        success: function (res) {
                            if (res.error == 0) {
                                $('#comment-popup .modal-dialog').html(res.data);
                                $(".time-input").timepicker({
                                    todayHighlight: !0,
                                    autoclose: !0,
                                    pickerPosition: "bottom-left",
                                    // format: "dd/mm/yyyy hh:ii",
                                    format: "HH:ii",
                                    defaultTime: "",
                                    showMeridian: false,
                                    minuteStep: 5,
                                    snapToStep: !0,
                                    // startDate : new Date()
                                    // locale: 'vi'
                                });

                                $(".daterange-input").datepicker({
                                    todayHighlight: !0,
                                    autoclose: !0,
                                    pickerPosition: "bottom-left",
                                    // format: "dd/mm/yyyy hh:ii",
                                    format: "dd/mm/yyyy",
                                    // startDate : new Date()
                                    // locale: 'vi'
                                });
                                $('#comment-popup').modal('show');
                            } else {
//                                swal.fire(res.message, '', 'error');
                            }
                        }
                    });
                }
            });
            $(document).on('submit', '#update_date_end', function () {
                console.log($('#update_date_end').serialize())
                var manage_work_id = $('#update_date_end [name="manage_work_id"]').val();
                if(manage_work_id){
                    $.ajax({
                        url: laroute.route('manager-work.edit-element-item'),
                        data: $('#update_date_end').serialize(),
                        method: "POST",
                        dataType: "JSON",
                        success: function (res) {
                            if (res.error == false) {
                                $('#comment-popup').modal('hide');
                                swal('', res.message, 'success');
                                location.reload();
                            } else {
                                swal('', res.message, 'error');
                            }
                        },
                        error: function (res) {
                            var mess_error = '';
                            $.map(res.responseJSON.errors, function (a) {
                                mess_error = mess_error.concat(a + '<br/>');
                            });
                            swal('', mess_error, "error");
                        }
                    });
                }
                return false;
            });
            $(document).on('submit', '#update_process', function () {
                var progress = $('#update_process [name="progress"]').val();
                var manage_work_id = $('#update_process [name="manage_work_id"]').val();
                if(manage_work_id){
                    $.ajax({
                        url: laroute.route('manager-work.edit-element-item'),
                        data: {
                            manage_work_id: manage_work_id,
                            progress: progress
                        },
                        method: "POST",
                        dataType: "JSON",
                        success: function (res) {
                            if (res.error == false) {
                                $('#comment-popup').modal('hide');
                                swal('', res.message, 'success');
                                location.reload();
                            } else {
                                swal('', res.message, 'error');
                            }
                        },
                        error: function (res) {
                            var mess_error = '';
                            $.map(res.responseJSON.errors, function (a) {
                                mess_error = mess_error.concat(a + '<br/>');
                            });
                            swal('', mess_error, "error");
                        }
                    });
                }
                return false;
            });
            $(document).on('submit', '#send_comment', function () {
                var code = $('#send_comment #description_comment').summernote('code');
                var manage_work_id = $('#send_comment #manage_work_id_comment').val();
                if(manage_work_id){
                    $.ajax({
                        url: laroute.route('manager-work.detail.add-comment'),
                        data: {
                            manage_work_id: manage_work_id,
                            description: code
                        },
                        method: "POST",
                        dataType: "JSON",
                        success: function (res) {
                            if (res.error == false) {
                                // $('.table-message-main > tbody').prepend(res.view);
                                // $('.description').summernote('code', '');
                                $('#comment-popup').modal('hide');
                                swal('', res.message, 'success');
                                location.reload();
                            } else {
                                swal('', res.message, 'error');
                            }
                        },
                        error: function (res) {
                            var mess_error = '';
                            $.map(res.responseJSON.errors, function (a) {
                                mess_error = mess_error.concat(a + '<br/>');
                            });
                            swal('', mess_error, "error");
                        }
                    });
                }
                return false;
            });
            $('#kanban2 .date-end-update').parent().css({"padding-left": "5px"});
            $('#kanban2 .date-end-update').parent().css({"overflow": "initial"});

            $('.jqx-kanban-column').css('width','400px');

            $('.jqx-kanban-column').click(function (){
                $('.jqx-kanban-column').each(function (i,obj){
                    var width = $(this).width();
                    if (width > 100){
                        $(this).css('width','400px');
                    }
                });

            });
        });
    </script>
@stop

