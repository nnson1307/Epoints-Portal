@extends('layout')
@section('title_header')
    <span class="title_header">@lang('CUỘC GỌI')</span>
@stop
@section('content')
    <style>
        .modal-backdrop {
            position: relative !important;
        }

        .form-control-feedback {
            color: red;
        }

    </style>
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="flaticon-list-1"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        @lang("LỊCH SỬ CUỘC GỌI")
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <div class="m-portlet__body">
            <div id="autotable">
                <form class="frmFilter bg">
                    <div class="padding_row">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-3 form-group">
                                    <input type="text" class="form-control" name="search"
                                           placeholder="@lang("Nhập thông tin tìm kiếm")">
                                </div>

                                @php $i = 0; @endphp
                                @foreach ($FILTER as $name => $item)
                                    @if ($i > 0 && ($i % 4 == 0))
                            </div>
                            <div class="form-group m-form__group row align-items-center">
                                @endif
                                @php $i++; @endphp
                                <div class="col-lg-3 form-group input-group">
                                    @if(isset($item['text']))
                                        <div class="input-group-append">
                                        <span class="input-group-text">
                                            {{ $item['text'] }}
                                        </span>
                                        </div>
                                    @endif
                                    {!! Form::select($name, $item['data'], $item['default'] ?? null, ['class' => 'form-control m-input m_selectpicker']) !!}
                                </div>
                                @endforeach
                                <div class="col-lg-3 form-group">
                                    <div class="m-input-icon m-input-icon--right">
                                        <input readonly class="form-control m-input daterange-picker" style="background-color: #fff"
                                               id="created_at" name="created_at" placeholder="@lang('Ngày gọi')">
                                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                                    </div>
                                </div>
                                <div class="col-lg-3 form-group">
                                    <button class="btn btn-primary color_button btn-search">
                                        @lang('TÌM KIẾM') <i class="fa fa-search ic-search m--margin-left-5"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="table-content m--padding-top-30">
                    @include('on-call::history.list')
                </div>
            </div>
        </div>
    </div>
    <div id="my-modal"></div>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/on-call/history/script.js')}}"
            type="text/javascript"></script>
    <script>
        list._init();

        $(".m_selectpicker").selectpicker();
        function historyGetModal(historyId, type, id, staffId, phone){

            @if(in_array('popup-care-oncall', session()->get('routeList')))
                let dataCustomer = {};
                let dataArray = {
                    "dataExtension": {
                        "staff_id": staffId
                    },
                    "history_id": historyId
                };
                if(type == 'lead'){
                    dataCustomer = {
                        "customer_lead_id": id,
                        "type": type,
                        "phone": phone,
                        "brand_code": '{{session()->get('brand_code')}}'
                    }
                    dataArray['dataCustomer'] = dataCustomer;
                    layout.getModal(dataArray);
                } else if(type == 'customer') {
                    dataCustomer = {
                        "customer_id": id,
                        "type": type,
                        "phone1": phone,
                        "brand_code": '{{session()->get('brand_code')}}'
                    }
                    dataArray['dataCustomer'] = dataCustomer;
                    layout.getModal(dataArray);
                } else if(type == 'deal') {
                    let deal_object_id = 0;
                    let deal_object_type = '';
                    // get info object of deal
                    $.ajax({
                        url: laroute.route('extension.get-info-deal'),
                        method: 'post',
                        dataType: "JSON",
                        data: {
                            deal_id: id
                        },
                        success: function(res){
                            if(res.deal_object_type == 'lead'){
                                dataCustomer = {
                                    "customer_lead_id": res.deal_object_id,
                                    "type": res.deal_object_type,
                                    "phone": phone,
                                    "brand_code": '{{session()->get('brand_code')}}'
                                }
                            } else {
                                dataCustomer = {
                                    "customer_id": res.deal_object_id,
                                    "type": res.deal_object_type,
                                    "phone1": phone,
                                    "brand_code": '{{session()->get('brand_code')}}'
                                }
                            }
                            dataArray['dataCustomer'] = dataCustomer;
                            layout.getModal(dataArray);
                        }
                    });

                }
            @endif
        }
    </script>
@stop
