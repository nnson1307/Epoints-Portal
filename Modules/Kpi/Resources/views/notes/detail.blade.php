@extends('layout')

@section('after_style')
    <link rel="stylesheet" href="{{ asset('static/backend/css/hao.css') }}">
@endsection

@section('title_header')
    <span class="title_header">
        <img src="{{ asset('uploads/admin/icon/icon-product.png') }}" alt="" style="height: 20px;" />
        {{ __('QUẢN LÝ PHIẾU GIAO KPI') }}
    </span>
@endsection

@section('content')
    <form id="form-banner" autocomplete="off">
        <div class="m-portlet">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <span class="m-portlet__head-icon">
                            <i class="la la-eye"></i>
                        </span>
                        <h3 class="m-portlet__head-text">
                            {{ __('XEM CHI TIẾT PHIẾU GIAO KPI') }}
                        </h3>
                        @if ($DETAIL_DATA['generalDetail']['status'] !== 'N')
                            @if (round($DETAIL_DATA['totalPercentKpi'], 2) < 100)
                                <span class="badge badge-pill badge-danger total-kpi">{{ round($DETAIL_DATA['totalPercentKpi'], 2) }}%</span>
                            @else 
                                <span class="badge badge-pill badge-success total-kpi-success">{{ round($DETAIL_DATA['totalPercentKpi'], 2) }}%</span>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <div class="m-portlet__body">
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active">
                        @if (isset($DETAIL_DATA))
                            <div class="row">

                                <!-- Bảng tiêu chí -->
                                <div class="col-lg-12">
                                    @if ($DETAIL_DATA['generalDetail']['kpi_note_type'] !== 'S') 
                                        @include('kpi::notes.components.detail-table-by-group')
                                    @else
                                        @include('kpi::notes.components.detail-table-by-staff')
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
@endsection

@section('after_script')
    <script src="{{ asset('static/backend/js/kpi/notes/detail.js?v=' . time()) }}" type="text/javascript">
    </script>
@stop
