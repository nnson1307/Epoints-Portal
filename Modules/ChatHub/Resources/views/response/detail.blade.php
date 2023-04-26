@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-staff.png')}}" alt=""
                style="height: 20px;"> @lang('CHI TIẾT PHẢN HỒI')</span>
@endsection

@section('content')
    <!-- Default box -->
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h2 class="m-portlet__head-text title_index">
                        @if($response_id == 'all')
                            <span><i class="la la-server"></i> {{__('CHI TIẾT CẤU HÌNH PHẢN HỒI')}}</span>
                        @else
                            <span><i class="la la-server"></i> {{__('CẤU HÌNH PHẢN HỒI')}}</span>
                        @endif
                    </h2>
                    <h3 class="m-portlet__head-text">

                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body" id="autotable">
            <form id="form-search" class="frmFilter">
                <input type="hidden" name="response_id" value="{{$response_id}}">
                <div class="ss--bao-filter">
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <span class="input-group-addon" style="border:none;padding-left:0">@lang('Nhãn hiệu'):</span>
                                <select name="brand" class="form-control m-input select2">
                                    <option value="0">--------</option>
                                    @foreach($arrBrand as $brand)
                                        <option @if(isset($params['brand']) && $params['brand'] == $brand['entities']) selected @endif value="{{$brand['entities']}}">{{$brand['brand_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <span class="input-group-addon" style="border:none;padding-left:0">@lang('Nhãn hiệu con'):</span>
                                <select name="sub_brand" class="form-control m-input select2">
                                    <option value="0">--------</option>
                                    @foreach($arrSubBrand as $subBrand)
                                        <option @if(isset($params['sub_brand']) && $params['sub_brand'] == $subBrand['entities']) selected @endif  value="{{$subBrand['entities']}}">{{$subBrand['sub_brand_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <span class="input-group-addon" style="border:none;padding-left:0">@lang('Sản phẩm'):</span>
                                <select name="sku" class="form-control m-input select2">
                                    <option value="0">--------</option>
                                    @foreach($arrSku as $sku)
                                        <option @if(isset($params['sku']) && $params['sku'] == $sku['entities']) selected @endif  value="{{$sku['entities']}}">{{$sku['sku_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <span class="input-group-addon" style="border:none;padding-left:0">@lang('Thuộc tính'):</span>
                                <select name="attribute" class="form-control m-input select2">
                                    <option value="0">--------</option>
                                    @foreach($arrAttribute as $attr)
                                        <option @if(isset($params['attribute']) && $params['attribute'] == $attr['entities']) selected @endif  value="{{$attr['entities']}}">{{$attr['attribute_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
{{--                @include('helpers.filter')--}}
            </form>
            <hr style="clear: both;">
            <div class="table-content m--padding-top-15">
                @include('chathub::response.detail_list')
            </div>
        </div><!-- /.box-body -->
    </div><!-- /.box -->
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/chathub/response/detail_list.js?v='.time())}}" type="text/javascript"></script>
@stop