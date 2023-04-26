@extends('layout')

@section('content')
    <style>
        .modal-backdrop {
            position: relative !important;
        }

    </style>
    <div class="m-portlet m-portlet--creative m-portlet--first m-portlet--bordered-semi">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <div class="m-input-icon m-portlet__head-icon">
                    </div>
                    <h3 class="m-portlet__head-text">
                    </h3>
                    <h2 style=" white-space:nowrap"
                        class="m-portlet__head-label m-portlet__head-label--primary">
                        <span><i class="fa fa-id-card m--margin-right-5"></i>
                            {{__('THẺ IN')}}</span>
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="{{route("admin.service-card-list.create")}}"
                   class="btn btn-primary m-btn m-btn--icon m-btn--pill">
                        <span>
						    <i class="fa flaticon-plus"></i>
							<span> {{__('Tạo thẻ in')}}</span>
                        </span>
                </a>
            </div>
        </div>
        <div class="m-portlet__body" id="autotable">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group m-form__group">
                        <div class="input-group">
                            <button class="btn btn-primary btn-search" style="display: none">
                                <i class="fa fa-search"></i>
                            </button>
                            <input type="hidden" name="search_type" value="service_cards.name">
                            <input type="text" class="form-control" name="search_keyword"
                                   placeholder="{{__('Nhập thẻ dịch vụ')}}">
                            <div class="input-group-append">
                                <button onclick="refresh()"
                                        class="btn btn-primary m-btn--icon">
                                    <i class="la la-refresh"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="m-form m-form--label-align-right m--margin-bottom-20">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            @php $i = 0; @endphp
                            @foreach ($FILTER as $name => $item)
                                @if ($i > 0 && ($i % 4 == 0))
                        </div>
                        <div class="form-group m-form__group row align-items-center">
                            @endif
                            @php $i++; @endphp
                            <div class="col-lg-3 input-group">
                                @if(isset($item['text']))
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            {{ $item['text'] }}
                                        </span>
                                    </div>
                                @endif
                                @if($name=='service_cards$service_card_type')
                                    {!! Form::select('service_cards$service_card_type', $item['data'], $item['default'] ?? null, ['class' => 'form-control m-input']) !!}
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @if (session('status'))
                <div class="alert alert-success alert-dismissible">
                    <strong>Success : </strong> {!! session('status') !!}.
                </div>
            @endif
            <div class="table-content list-service-card-list">
                @include('admin::service-card-list.list')
            </div><!-- end table-content -->
        </div>
    </div>


@stop
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/demo/css/admin/service-card/service-card.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service-card-list/index.js')}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/general/tableHeadFixer.js')}}" type="text/javascript"></script>
@stop
