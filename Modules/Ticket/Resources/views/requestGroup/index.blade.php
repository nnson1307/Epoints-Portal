@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-staff.png')}}" alt=""
                style="height: 20px;"> {{__('QUẢN LÝ LOẠI YÊU CẦU')}}</span>
@endsection
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
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
                        {{__('DANH SÁCH LOẠI YÊU CẦU')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                {{-- @if(in_array('ticket.requestGroup.add',session('routeList'))) --}}
                    {{-- <a href="javascript:void(0)"
                       data-toggle="modal"
                       data-target="#modalAdd"
                       onclick="Shift.clear()"
                       class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                        <span>
						    <i class="fa fa-plus-circle"></i>
							<span> {{__('THÊM LOẠI YÊU CẦU')}}</span>
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
                    </a> --}}
                {{-- @endif --}}
            </div>
        </div>
        <div class="m-portlet__body">
            <form class="frmFilter ss--background m--margin-bottom-30">
                <div class="ss--bao-filter">
                   <div class="row">
                       <div class="col-lg-9">
                        <div class="row">
                            <div class="col-lg-4 form-group">
                                <div class="m-form__group">
                                    <div class="input-group">
                                        <button class="btn btn-primary btn-search" style="display: none">
                                            <i class="fa fa-search"></i>
                                        </button>
                                        <input type="text" class="form-control" name="search"
                                               placeholder="{{__('Nhập tên loại yêu cầu')}}">
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-lg-4 form-group">
                                <select name="type" class="form-control select2 select2-active" id="type">
                                    <option value="">{{__('Chọn loại ticket')}}</option>
                                    @foreach ($filterType as $key => $value)
                                    <option value="{{$key}}">{{$value}}</option>
                                    @endforeach
                                </select>
                            </div> --}}
                            <div class="col-lg-4 form-group">
                                <div class="m-input-icon m-input-icon--right">
                                    <input readonly class="form-control m-input daterange-picker"
                                           style="background-color: #fff"
                                           id="created_at"
                                           name="created_at"
                                           autocomplete="off" placeholder="{{__('Ngày tạo')}}">
                                    <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                                </div>
                            </div>
                            <div class="col-lg-4 form-group">
                                <div class="row">
                                    @php $i = 0; @endphp
                                    @foreach ($filter as $name => $item)
                                        @if ($i > 0 && ($i % 4 == 0))
                                </div>
                                <div class="form-group m-form__group row align-items-center">
                                    @endif
                                    @php $i++; @endphp
                                    <div class="col-lg-12 input-group">
                                        @if(isset($item['text']))
                                            <div class="input-group-append">
                                                        <span class="input-group-text">
                                                            {{ $item['text'] }}
                                                        </span>
                                            </div>
                                        @endif
                                        {!! Form::select($name, $item['data'], $item['default'] ?? null, ['class' => 'form-control m-input','title'=>'Chọn trạng thái']) !!}
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                       </div>
                       <div class="col-lg-3 form-group">
                        <div class="d-flex">
                            <button class="btn btn-clear-form btn-refresh ss--button-cms-piospa m-btn--icon mr-3">
                                {{ __('XÓA BỘ LỌC') }}
                                <i class="fa fa-eraser" aria-hidden="true"></i>
                            </button>
                            <button onclick="Shift.search()"
                                    class="btn ss--button-cms-piospa m-btn--icon">
                                {{__('TÌM KIẾM')}}
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                   </div>
                    {{--<div class="m-form m-form--label-align-right m--margin-bottom-20">--}}
                    {{--@include('helpers.filter')--}}
                    {{--</div>--}}
                </div>
            </form>
            <div class="table-content">
                @include('ticket::requestGroup.list')
            </div><!-- end table-content -->
        </div>
    </div>
    <div class="modal fade" id="modalAdd" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <!-- Modal content-->
            @include('ticket::requestGroup.add')
        </div>
    </div>
    <div class="modal fade" id="modalEdit" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <!-- Modal content-->
            @include('ticket::requestGroup.edit')
        </div>
    </div>
    <div class="modal fade" id="modalView" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <!-- Modal content-->
            @include('ticket::requestGroup.view')
        </div>
    </div>
@stop
@section('after_script')
    @include('ticket::language.lang')
    <script src="{{asset('static/backend/js/ticket/requestGroup/list.js?v='.time())}}" type="text/javascript"></script>
@stop
