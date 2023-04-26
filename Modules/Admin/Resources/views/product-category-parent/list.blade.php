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
                        {{__('Danh sách danh mục sản phẩm cha')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">


                @if(1||in_array('admin.product-category-parent.ajax-add-modal',session('routeList')))
                    <a href="javascript:void(0)"
                       class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm m--margin-right-5">
                        <span class="ajax-product-category-parent-add-modal ajax submit" method="POST"
                              action="{{route('admin.product-category-parent.ajax-add-modal')}}">
						    <i class="fa fa-plus-circle"></i>
							<span class=""> {{__('Thêm danh mục sản phẩm cha')}}</span>
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


            </div>
        </div>
        <div class="m-portlet__body">
            @include('admin::product-category-parent.filters')
            <div class="table-content product-category-parent-table">
                @include('admin::product-category-parent.table')
            </div><!-- end table-content -->
        </div>
    </div>

@stop
@section('after_script')
    <script src="{{asset('static/backend/js/huniel.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/product-category-parent/script.js?v='.time())}}" type="text/javascript"></script>

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
