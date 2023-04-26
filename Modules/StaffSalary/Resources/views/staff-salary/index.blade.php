@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-staff.png')}}" alt=""
                style="height: 20px;"> {{__('QUẢN LÝ NHÂN VIÊN')}}</span>
@stop
@section('content')
<div class="m-portlet m-portlet--head-sm">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon">
                    <i class="flaticon-list-1"></i>
                </span>
                <h2 class="m-portlet__head-text">
                    @lang("Danh sách bảng lương")
                </h2>
            </div>
        </div>
        <div class="m-portlet__head-tools">
            
            {{-- @if(in_array('customer-lead.create',session('routeList')))
                <a href="javascript:void(0)" onclick="holiday.showModalAdd()"
                   class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc">
                                <span>
                                    <i class="fa fa-plus-circle"></i>
                                    <span> @lang('THÊM NGÀY LỄ')</span>
                                </span>
                </a>
              
            @endif --}}
        </div>
    </div>
    <div class="m-portlet__body">
        <div class="m-portlet">
            <div class="m-portlet__body">
                <div id="autotable">
                    <form class="frmFilter bg">
                        <div class="row padding_row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="search" placeholder=" {{__('Nhập thông tin tìm kiếm')}}">
                                </div>
                            </div>
                            <div class="col-lg-2 form-group">
                                <button class="btn btn-primary color_button btn-search">
                                    @lang("TÌM KIẾM") <i class="fa fa-search ic-search m--margin-left-5"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="table-content m--padding-top-30">
                        @include('staff-salary::staff-salary.list')
                    </div>
                </div>
            </div>
        </div>
       
    </div>
</div>
<div id="modal-holiday-add"></div>
<div id="modal-holiday-edit"></div>
@stop
@section("after_style")
    
@stop
@section('after_script')
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
<script src="{{asset('static/backend/js/staff-salary/staff-salary/list.js?v='.time())}}"></script>
<script>
     $('#autotable').PioTable({
          baseUrl: laroute.route('staff-salary.list')
      });
</script>
@stop
