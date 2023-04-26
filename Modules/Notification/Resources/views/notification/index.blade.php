@extends('layout')
@section('title_header')

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
                            {{__('THÔNG BÁO')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                {{--                @if(in_array('customer-group.add',session('routeList')))--}}
                <a href="{{route('admin.notification.create')}}"
                   class="btn btn-info btn-sm m-btn m-btn--icon m-btn--pill color_button">
                            <span>
                                <i class="fa fa-plus-circle"></i>
                                <span> {{__('TẠO THÔNG BÁO')}}</span>
                            </span>
                </a>
                {{--                @endif--}}
            </div>
        </div>
        <div class="m-portlet__body">
            <form class="frmFilter ss--background">
                <div class="row ss--bao-filter">
                    <div class="col-lg-3">
                        <div class="form-group m-form__group">
                            <div class="input-group">
                                <button class="btn btn-primary btn-search" style="display: none">
                                    <i class="fa fa-search"></i>
                                </button>
                                <input type="text" class="form-control" name="search_title"
                                       placeholder="{{__('Tiêu đề')}}">
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
                            <button onclick="customerGroup.search()"
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
                @include('notification::notification.list')
            </div>
        </div>
    </div>
@stop
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script>
        statusPending = '@lang('admin::notification.index.search.IS_SEND.WAIT')';
        statusNot = '@lang('admin::notification.index.search.IS_SEND.DONT_SEND')';
    </script>
    <script src="{{asset('static/backend/js/notification/notification/script.js')}}" type="text/javascript"></script>
@stop
