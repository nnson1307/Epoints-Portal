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
@endsection
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__head text-uppercase">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-th-list"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        {{__('Danh sách nhóm đối tượng')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                @if(in_array('people.object-group.ajax-add-modal',session('routeList')))
                    <a href="javascript:void(0)"
                       class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                        <span class="ajax-people-object-group-add-modal-form" method="POST" action="{{route('people.object-group.ajax-add-modal')}}">
						    <i class="fa fa-plus-circle"></i>
							<span class="ajax-people-object-group-add-modal-submit"> {{__('Thêm nhóm đối tượng')}}</span>
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
            @include('People::object-group.filters')
            <div class="table-content people-object-group-table">
                @include('People::object-group.table')
            </div><!-- end table-content -->
        </div>
    </div>
    <div class="xxx"></div>

@stop
@section('after_script')
    <script src="{{asset('static/backend/js/huniel.js?v='.time())}}" type="text/javascript"></script>
    <script type="text/javascript">
        AjaxHandle.startListen({
            form:'.ajax',
            button:'.submit',
            callback:function (respond) {

            }
        });

        function people_object_group_delete(aaa){
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
        // ajax open add popup
        AjaxHandle.startListen({
            form:'.ajax-people-object-group-add-modal-form',
            button:'.ajax-people-object-group-add-modal-submit',
        });
        // ajax action add
        AjaxHandle.startListen({
            form:'.ajax-people-object-group-add-form',
            button:'.ajax-people-object-group-add-submit',
            callback:function (respond) {
                if(respond.status == 'success'){
                    AjaxHandle.submitForm('.ajax-people-object-group-list-form');
                }
                if(respond.action2 == 'save-and-create-new'){
                    AjaxHandle.submitForm('.ajax-people-object-group-add-modal-form');
                }
            }
        });
        // ajax open edit popup
        AjaxHandle.startListen({
            button:'.ajax-people-object-group-edit-modal',
        });
        // ajax action edit
        AjaxHandle.startListen({
            form:'.ajax-people-object-group-edit-form',
            button:'.ajax-people-object-group-edit-submit',
            callback:function (respond) {
                if(respond.status == 'success'){
                    AjaxHandle.submitForm('.ajax-people-object-group-list-form');
                }
            }
        });
        // ajax action edit status
        AjaxHandle.startListen({
            form:'.ajax-object-group-status-edit',
            submitOnChange:'is_active,name',
            callback:function (respond) {
                AjaxHandle.submitForm('.ajax-people-object-group-list-form');
            }
        });
        // ajax open delete popup
        AjaxHandle.startListen({
            button:'.ajax-people-object-group-delete-modal',
        });
        // ajax action delete
        AjaxHandle.startListen({
            form:'.ajax-people-object-group-delete-form',
            button:'.ajax-people-object-group-delete-submit',
            callback:function (respond) {
                if(respond.status == 'success'){
                    AjaxHandle.submitForm('.ajax-people-object-group-list-form');
                }
            }
        });

        // ajax action search list
        AjaxHandle.startListen({
            form:'.ajax-people-object-group-list-form',
            button:'.ajax-people-object-group-list-submit',
        });
        AjaxLaravelPagination.startListen(".laravel-paginator");

        $('.m_selectpicker').selectpicker();
        $('.select2').select2({width:'100%'});
    </script>
@stop
