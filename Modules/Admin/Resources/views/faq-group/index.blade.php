@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-member.png')}}" alt=""
                style="height: 20px;"> {{__('QUẢN LÝ NHÓM HỖ TRỢ')}}</span>
@endsection
@section("after_css")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
@endsection
@section('content')
    <style>
        /*.modal-backdrop {*/
        /*position: relative !important;*/
        /*}*/

        .form-control-feedback {
            color: red;
        }

        .title_header {
            color: #008990;
            font-weight: 400;
        }
    </style>
    <!--begin::Portlet-->
    <div class="m-portlet" id="autotable">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                   <span class="m-portlet__head-icon">
                                <i class="la la-th-list"></i>
                             </span>
                    <h3 class="m-portlet__head-text">
                        {{__('DANH SÁCH NHÓM HỖ TRỢ')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                @if(in_array('admin.faq-group.create',session('routeList')))
                    <a href="{{route('admin.faq-group.create')}}"
                       class="btn btn-info btn-sm m-btn m-btn--icon m-btn--pill color_button">
                                <span>
                                    <i class="fa fa-plus-circle"></i>
                                    <span> {{__('THÊM NHÓM HỖ TRỢ')}}</span>
                                </span>
                    </a>
                @endif
            </div>
        </div>
        <div class="m-portlet__body">
            <form class="frmFilter ss--background">
                <div class="row ss--bao-filter">
                    <div class="col-lg-3">
                        <div class="form-group m-form__group">
                            <div class="input-group">
                                <input type="hidden" name="search_type" value="group_name">
                                <button class="btn btn-primary btn-search" style="display: none">
                                    <i class="fa fa-search"></i>
                                </button>
                                <input type="text" class="form-control" name="search_faq_group_title_vi"
                                       placeholder="{{__('Tên nhóm nội dung hỗ trợ (VI)')}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group m-form__group">
                            <div class="input-group">
                                <input type="hidden" name="search_type" value="group_name">
                                <button class="btn btn-primary btn-search" style="display: none">
                                    <i class="fa fa-search"></i>
                                </button>
                                <input type="text" class="form-control" name="search_faq_group_title_en"
                                       placeholder="{{__('Tên nhóm nội dung hỗ trợ (EN)')}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="row">
                            @php $i = 0; @endphp
                            @foreach ($FILTER as $name => $item)
                                @if ($i > 0 && ($i % 4 == 0))
                        </div>
                        <div class="form-group m-form__group row align-items-center">
                            @endif
                            @php $i++; @endphp
                            <div class="col-lg-12 form-group input-group">
                                @if(isset($item['text']))
                                    <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        {{ $item['text'] }}
                                                    </span>
                                    </div>
                                @endif
                                {!! Form::select($name, $item['data'], $item['default'] ?? null, ['class' => 'form-control m-input m_selectpicker ss--width-100-','title'=>'Chọn trạng thái']) !!}
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group m-form__group">
{{--                            <button onclick="customerGroup.search()"--}}
                            <button
                                    class="btn ss--btn-search">
                                {{__('TÌM KIẾM')}}
                                <i class="fa fa-search ss--icon-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <!--begin: Search Form -->
            </form>
            <!--end: Search Form -->
            <div class="table-content m--margin-top-30">
                @include('admin::faq-group.list')
            </div>
        </div>
    </div>
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/faq-group/script.js?v='.time())}}" type="text/javascript"></script>
    <script>
        $(".m_selectpicker").selectpicker();
    </script>
@stop