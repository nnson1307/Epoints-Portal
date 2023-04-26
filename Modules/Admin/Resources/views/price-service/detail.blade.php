@extends('layout')
@section('content')
    <div class="m-portlet m-portlet--creative m-portlet--first m-portlet--bordered-semi">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h2 class="m-portlet__head-label m-portlet__head-label--primary">
                        <span>CHI TIẾT GIÁ DỊCH VỤ</span>
                    </h2>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-xl-12">
                    <input type="hidden" name="service_id" value="{{$item['service_id']}}">
                    <form action="" method="post" id="formDetail" novalidate="novalidate">
                        {!! csrf_field() !!}
                        <br/>
                        <div class="row">
                            <div class="form-group m-form__group col-6">
                                <div class="form-group m-form__group">
                                    <div class="row">
                                        <div class="col-lg-8" style="margin-left: -15px;">
                                            <label>
                                                Dịch vụ:
                                            </label>
                                            <div class="input-group">
                                                <div class="input-group m-input-group m-input-group--solid">
                                                    <div class="input-group">
                                                        <input readonly type="text" class="form-control m-input"
                                                               name="creditcard"
                                                               value="{{$item['service_name']}}">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group m-form__group">
                                    <div class="row">
                                        <div class="col-lg-8" style="margin-left: -15px;">
                                            <label>
                                                Giá dịch vụ:
                                            </label>
                                            <div class="input-group">
                                                <div class="input-group m-input-group m-input-group--solid">
                                                    <div class="input-group">
                                                        <input readonly type="text" class="form-control m-input"
                                                               name="creditcard"
                                                               value="{{$item['price_standard']}}">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group m-form__group">
                                    <div class="row">
                                        <div class="col-lg-8" style="margin-left: -15px;">
                                            <label class="m-checkbox">
                                                @if($item['is_sale']==1)
                                                    <input type="checkbox" checked>
                                                @else
                                                    <input type="checkbox">
                                                @endif
                                                Có giảm giá
                                                <span></span>
                                            </label>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group m-form__group">
                                    <div class="row">
                                        <div class="col-lg-8" style="margin-left: -15px;">
                                            <label>
                                                {{__('Hình ảnh')}}:
                                            </label>
                                            <div class="input-group">
                                                <div class="input-group m-input-group m-input-group--solid">
                                                    <div class="input-group">
                                                        @foreach($itemImage as $item)
                                                            <img src="{{$item['name']}}" height="120px;" width="70px">
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="form-group m-form__group col-6">
                                <div class="form-group m-form__group">
                                    <div class="col-lg-6" style="margin-left: -15px;">
                                        <div class="input-group">
                                            {{--<div class="input-group m-input-group m-input-group--solid">--}}
                                            <select class="form-control m-input" name="branch_id" id="branch_id" multiple title="Chọn chi nhánh" data-idservice="{{ $item['service_id']  }}">

                                                @foreach($itemBranch as $key=>$value)
                                                    <option value="{{$value['branch_id']}}" selected>{{$value['branch_name']}}</option>
                                                @endforeach
                                            </select>
                                            {{--</div>--}}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group m-form__group">
                                    @include('admin::price-service.list-price-branch')
                                </div>
                            </div>

                        </div>


                        <div align="right" style="margin-right: 50px;">
                            <a href="{{route('admin.service-branch-price')}}" style="color: black"
                               class="btn btn-default"
                               title="View">
                                <i class="fa fa-reply"></i>Trở lại
                            </a>
                        </div>
                    </form>
                    <br/>


                </div>
            </div>
        </div>

    </div>

@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/price-service/script.js')}}" type="text/javascript"></script>
@stop