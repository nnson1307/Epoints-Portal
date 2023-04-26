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
                        {{__('Sản phẩm yêu thích')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">


            </div>
        </div>
        <div class="m-portlet__body">
            @include('admin::product-favourite.filters')
            <div class="table-content product-favourite-table">
                @include('admin::product-favourite.table')
            </div><!-- end table-content -->
        </div>
    </div>

@stop
@section('after_script')
    <script src="{{asset('static/backend/js/huniel.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/product-favourite/script.js?v='.time())}}" type="text/javascript"></script>

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
        TableInput.startListen({
            table: '.hu-table-input',
            addButton: '.table-input-add-item',
            callback: function (param) {
                $('.hu-table-input').find('tbody>tr:last-child .this-is-select2').select2();
            }
        });

        function item_delete(aaa) {
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

        function item_verify_delete(aaa) {
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

        function uploadImage3(input, target = '#avatar') {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.readAsDataURL(input.files[0]);
                var file_data = $(input).prop('files')[0];
                var form_data = new FormData();
                form_data.append('file', file_data);
                form_data.append('link', '_brand.');

                $.ajax({
                    url: laroute.route("admin.upload-image"),
                    method: "POST",
                    data: form_data,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (res) {
                        console.log(target)
                        if (res.error == 0) {
                            $(target).val(res.file);
                            $(target).attr('src', res.file);
                        }

                    }
                });
            }
        }

        $('.m_selectpicker').selectpicker();
        $('.select2').select2({width: '100%'});
    </script>

@stop
