@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-staff.png')}}" alt=""
                style="height: 20px;"> {{__('QUẢN LÝ NHÂN VIÊN')}}</span>
@endsection
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/huniel.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">

    <style>
        #my_camera{
            width: 320px;
            height: 240px;
            border: 1px solid black;
        }

        .swal2-container {
            z-index: 1300;
        }
    </style>
@endsection
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__head hu-first-uppercase text-uppercase">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-th-list"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        {{__('Danh sách công dân')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                @if(in_array('people.people.import-excel', session()->get('routeList')))
                    <a href="javascript:void(0)" onclick="index.importExcel()"
                       class="btn btn-info btn-sm m-btn m-btn--icon m-btn--pill color_button m--margin-right-5">
                                        <span>
                                            <i class="la la-files-o"></i>
                                            <span>
                                                {{__('Nhập file')}}
                                            </span>
                                        </span>
                    </a>
                @endif

                @if(in_array('people.people.ajax-add-modal',session('routeList')))
                    <a href="javascript:void(0)"
                       class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm m--margin-right-5">
                        <span class="ajax-people-add-modal ajax submit" method="POST"
                              action="{{route('people.people.ajax-add-modal')}}">
						    <i class="fa fa-plus-circle"></i>
							<span class=""> {{__('Thêm công dân')}}</span>
                        </span>
                    </a>

                    <a href="javascript:void(0)"
                       data-toggle="modal"
                       data-target="#modalAdd"
                       onclick="Shift.clear()"
                       class="btn btn-outline-accent m-btn m-btn--icon m-btn--icon-only m-btn--pill
                                 color_button btn_add_mobile"
                       style="display: none">
                        <i class="fa fa-plus-circle" style="color: #fff"></i>
                    </a>
                @endif

                @if(in_array('people.people.print-multiple',session('routeList')))
                    <a target="_blank" href="{{route('people.people.print-multiple')}}"
                       class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                    <span>
                         <i class="fas fa-print"></i>
                        <span> {{__('In hàng loạt')}}</span> <span class="total_choose_people">(0)</span>
                    </span>
                    </a>
                @endif
            </div>
        </div>
        <div class="m-portlet__body">
            @include('People::people.filters')
            <div class="table-content people-table">
                @include('People::people.table')
            </div><!-- end table-content -->
        </div>
    </div>

    @include('People::people.pop.modal-excel')

    <div id="div-camera"></div>
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/huniel.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/people/people/script.js?v='.time())}}" type="text/javascript"></script>

    <script type="text/javascript">
        AjaxHandle.startListen({
            form: '.ajax',
            button: '.submit',
            callback: function (response) {
                $.each(response.appendOrReplace, function (k, v) {
                    setTimeout(function () {
                        $(k).find('.this-is-select2:visible').select2({width: '100%'});
                    }, 500);
                    $(k).find('.datepicker-year').datepicker({
                        format: "yyyy",
                        viewMode: "years",
                        minViewMode: "years",
                        minDate: $(this).attr('min'),
                        maxDate: $(this).attr('max')
                    });
                    $(k).find('.datepicker').datepicker({
                        format: "dd/mm/yyyy",
                        viewMode: "years"
                    });
                });
            }
        });
        AjaxHandle.startListenButton('.ajax-button');
        AjaxLaravelPagination.startListen(".laravel-paginator");
        AjaxLaravelPagination.startListen(".people-verify-paginator");
        TableInput.startListen({
            table: '.hu-table-input',
            addButton: '.table-input-add-item',
            callback: function (param) {
                $('.hu-table-input').find('tbody>tr:last-child .this-is-select2').select2();
            }
        });

        function people_delete(aaa) {
            swal.fire({
                title: 'Bạn có muốn xóa không?',
                html: '',
                type: 'warning',
                confirmButtonText: 'Xóa',
                confirmButtonClass: "btn btn-success m-btn--wide m-btn--md",
                showCancelButton: true,
                cancelButtonText: 'Hủy',
                cancelButtonClass: "btn btn-default m-btn--wide m-btn--md"
            }).then(function (result) {
                if (result.value) {
                    AjaxHandle.submit(aaa);
                }
            });
        }

        function people_verify_delete(aaa) {
            swal.fire({
                title: 'Bạn có muốn xóa không?',
                html: '',
                type: 'warning',
                confirmButtonText: 'Xóa',
                confirmButtonClass: "btn btn-success m-btn--wide m-btn--md",
                showCancelButton: true,
                cancelButtonText: 'Hủy',
                cancelButtonClass: "btn btn-default m-btn--wide m-btn--md"
            }).then(function (result) {
                if (result.value) {
                    AjaxHandle.submit(aaa);
                }
            });
        }

        $(document).on('change', '.family-relationship-select', function () {
            var val = $(this).val();
            if (val == 1 || val == 2) {
                //$(this).parents('tr').find('td:nth-child(6),td:nth-child(7)').find(':input').prop('disabled', false);
                $(this).parents('tr').find('td:nth-child(6),td:nth-child(7),td:nth-child(8)').find(':input').removeClass('hu-disabled');
            } else {
                //$(this).parents('tr').find('td:nth-child(6),td:nth-child(7)').find(':input').prop('disabled', true);
                $(this).parents('tr').find('td:nth-child(6),td:nth-child(7),td:nth-child(8)').find(':input').addClass('hu-disabled');
            }
        });

        function uploadImage(input, target = '#avatar') {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.readAsDataURL(input.files[0]);
                var file_data = $(input).prop('files')[0];
                var form_data = new FormData();
                form_data.append('file', file_data);
                form_data.append('link', '_people.');

                $.ajax({
                    url: laroute.route("admin.upload-image"),
                    method: "POST",
                    data: form_data,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (res) {
                        if (res.error == 0) {
                            $(target).val(res.file);
                            $(target).attr('src', res.file);
                        }

                    }
                });
            }
        }

        $(document).on('change', '[name="people_verification_year"]', function () {
            var year = $(this).val();
            var birth_year = $(this).parents('.ajax:not(.ajax-people-list-form)').first().find('[name="birth_year"]').val();
            $(this).parents('.ajax:not(.ajax-people-list-form)').first().find('[name="age"]').val(year - birth_year);
        });
        $(document).on('change', '[name="people_verification_id"]', function () {
            var year = $(this).find(":selected").text();
            var birth_year = $(this).parents('.ajax:not(.ajax-people-list-form)').first().find('[name="birth_year"]').val();
            $(this).parents('.ajax:not(.ajax-people-list-form)').first().find('[name="age"]').val(year - birth_year);
        });

        $('.m_selectpicker').selectpicker();
        $('.select2').select2({width: '100%'});
    </script>

    <script type="text-template" id="tpl-data-error">
        <input type="hidden" name="code[]" value="{code}">
        <input type="hidden" name="full_name[]" value="{full_name}">
        <input type="hidden" name="gender[]" value="{gender}">
        <input type="hidden" name="id_number[]" value="{id_number}">
        <input type="hidden" name="id_license_date[]" value="{id_license_date}">
        <input type="hidden" name="people_id_license_place[]" value="{people_id_license_place}">
        <input type="hidden" name="birth_day[]" value="{birth_day}">
        <input type="hidden" name="birth_month[]" value="{birth_month}">
        <input type="hidden" name="birth_year[]" value="{birth_year}">
        <input type="hidden" name="permanent_address[]" value="{permanent_address}">
        <input type="hidden" name="temporary_address[]" value="{temporary_address}">
        <input type="hidden" name="birthplace[]" value="{birthplace}">
        <input type="hidden" name="hometown[]" value="{hometown}">
        <input type="hidden" name="people_group[]" value="{people_group}">
        <input type="hidden" name="people_quarter[]" value="{people_quarter}">
        <input type="hidden" name="ethnic[]" value="{ethnic}">
        <input type="hidden" name="religion[]" value="{religion}">
        <input type="hidden" name="people_family[]" value="{people_family}">
        <input type="hidden" name="educational_level[]" value="{educational_level}">

        <input type="hidden" name="group_join_date[]" value="{group_join_date}">
        <input type="hidden" name="graduation_year[]" value="{graduation_year}">
        <input type="hidden" name="specialized[]" value="{specialized}">
        <input type="hidden" name="foreign_language[]" value="{foreign_language}">
        <input type="hidden" name="union_join_date[]" value="{union_join_date}">

        <input type="hidden" name="people_job[]" value="{people_job}">
        <input type="hidden" name="elementary_school[]" value="{elementary_school}">
        <input type="hidden" name="middle_school[]" value="{middle_school}">
        <input type="hidden" name="high_school[]" value="{high_school}">
        <input type="hidden" name="from_18_to_21[]" value="{from_18_to_21}">
        <input type="hidden" name="from_21_to_now[]" value="{from_21_to_now}">
        <input type="hidden" name="full_name_dad[]" value="{full_name_dad}">
        <input type="hidden" name="birth_year_dad[]" value="{birth_year_dad}">
        <input type="hidden" name="job_dad[]" value="{job_dad}">
        <input type="hidden" name="before_30_04_dad[]" value="{before_30_04_dad}">
        <input type="hidden" name="after_30_04_dad[]" value="{after_30_04_dad}">
        <input type="hidden" name="current_dad[]" value="{current_dad}">
        <input type="hidden" name="full_name_mom[]" value="{full_name_mom}">
        <input type="hidden" name="birth_year_mom[]" value="{birth_year_mom}">
        <input type="hidden" name="job_mom[]" value="{job_mom}">
        <input type="hidden" name="before_30_04_mom[]" value="{before_30_04_mom}">
        <input type="hidden" name="after_30_04_mom[]" value="{after_30_04_mom}">
        <input type="hidden" name="current_mom[]" value="{current_mom}">
        <input type="hidden" name="info_brother_1[]" value="{info_brother_1}">
        <input type="hidden" name="info_brother_2[]" value="{info_brother_2}">
        <input type="hidden" name="info_brother_3[]" value="{info_brother_3}">
        <input type="hidden" name="info_brother_4[]" value="{info_brother_4}">
        <input type="hidden" name="info_brother_5[]" value="{info_brother_5}">
        <input type="hidden" name="info_brother_6[]" value="{info_brother_6}">
        <input type="hidden" name="full_name_couple[]" value="{full_name_couple}">
        <input type="hidden" name="birth_year_couple[]" value="{birth_year_couple}">
        <input type="hidden" name="job_couple[]" value="{job_couple}">
        <input type="hidden" name="info_child_1[]" value="{info_child_1}">
        <input type="hidden" name="info_child_2[]" value="{info_child_2}">
        <input type="hidden" name="error[]" value="{error}">
    </script>

    <script type="text/javascript" src="{{asset('static/backend/js/people/people/webcamjs/webcam.min.js')}}"></script>
@stop
