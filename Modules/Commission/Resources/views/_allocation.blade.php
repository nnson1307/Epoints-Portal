@extends('layout')
@section('after_style')
    <link rel="stylesheet" href="{{ asset('static/backend/css/hao.css') }}">
@endsection
@section('title_header')
    <span class="title_header"><img src="{{ asset('uploads/admin/icon/icon-product.png') }}" alt=""
                                    style="height: 20px;">
        {{ __('QUẢN LÝ HOA HỒNG') }}
    </span>
@endsection
@section('content')
    <form id="form-banner" autocomplete="off">
        <div class="m-portlet">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <span class="m-portlet__head-icon">
                            <i class="fa fa-plus-circle"></i>
                        </span>
                        <h3 class="m-portlet__head-text">
                            {{ __('PHÂN BỔ HOA HỒNG') }}
                        </h3>
                    </div>
                </div>
            </div>

            <div class="m-portlet__body">
                <!-- Allocate commission tab -->
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link tab-allocate active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                           aria-controls="home" aria-selected="true">Chọn nhân viên</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link tab-allocate" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                           aria-controls="profile" aria-selected="false">Chọn hoa hồng</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link tab-allocate" id="contact-tab" data-toggle="tab" href="#contact" role="tab"
                           aria-controls="contact" aria-selected="false">Bảng review</a>
                    </li>
                </ul>


                <!-- Allocate commission table -->
                <div class="tab-content" id="myTabContent">

                    <!-- Chọn nhân viên -->
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

                        <!-- Filter -->
                        <div class="row padding_row filter-block">
                            <div class="col-lg-2">
                                <div class="form-group m-form__group">
                                    <div class="input-group">
                                        <select style="width: 100%;" name="staff_type_id"
                                                class="form-control m-input ss--select-2">
                                            <option value="">Loại nhân viên</option>
                                            @if (isset($TYPE_DATA))
                                                @foreach ($TYPE_DATA as $staffTypeItem)
                                                    <option value="{{ $staffTypeItem['staff_type_id'] }}">
                                                        {{ $staffTypeItem['type_name'] }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-2 form-group">
                                <select style="width: 100%;" name="branch_id" class="form-control m-input ss--select-2">
                                    <option value="">Chọn chi nhánh</option>
                                    @if (isset($BRANCH_DATA))
                                        @foreach ($BRANCH_DATA as $branchIdItem)
                                            <option value="{{ $branchIdItem['branch_id'] }}">
                                                {{ $branchIdItem['branch_name'] }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="col-lg-2 form-group">
                                <select style="width: 100%;" name="department_id"
                                        class="form-control m-input ss--select-2">
                                    <option value="">Chọn phòng ban</option>
                                    @if (isset($DEPARTMENT_DATA))
                                        @foreach ($DEPARTMENT_DATA as $departmentIdItem)
                                            <option value="{{ $departmentIdItem['department_id'] }}">
                                                {{ $departmentIdItem['department_name'] }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="col-lg-2 form-group">
                                <select style="width: 100%;" name="staff_title_id"
                                        class="form-control m-input ss--select-2">
                                    <option value="">Chọn chức vụ</option>
                                    @if (isset($TITLE_DATA))
                                        @foreach ($TITLE_DATA as $staffTitleItem)
                                            <option value="{{ $staffTitleItem['staff_title_id'] }}">
                                                {{ $staffTitleItem['staff_title_name'] }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="col-lg-2">
                                <div class="form-group m-form__group">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="full_name"
                                               placeholder="{{ __('Tên nhân viên') }}"/>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-2">
                                <div class="form-group m-form__group">
                                    <button class="btn btn-primary color_button btn-search">{{ __('TÌM KIẾM') }} <i
                                                class="fa fa-search ic-search m--margin-left-5"></i></button>
                                    <a href="{{ route('admin.commission') }}"
                                       class="btn btn-metal btn-search padding9x">
                                        <span>
                                            <i class="flaticon-refresh"></i>
                                        </span>
                                    </a>
                                </div>
                            </div>

                        </div>

                        <!-- Table chọn nhân viên -->
                        <div class="table-content m--padding-top-30">
                            @include('commission::components.allocate-staff-table')
                        </div>

                        <!-- Nút hủy và tiếp theo -->
                        <div class="modal-footer save-attribute m--margin-right-20">
                            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                                <div class="m-form__actions m--align-right">
                                    <a href="{{ route('admin.commission') }}"
                                       class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                                        <span class="ss--text-btn-mobi">
                                            <i class="la la-arrow-left"></i>
                                            <span>HỦY</span>
                                        </span>
                                    </a>
                                    <button type="button"
                                            class="ss--btn-mobiles m--margin-bottom-5 btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 nexttab">
                                        <span class="ss--text-btn-mobi">
                                            <i class="la la-check"></i>
                                            <span>TIẾP THEO</span>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Chọn hoa hồng -->
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">

                        <!-- Filter -->
                        <div class="row padding_row filter-block">

                            <div class="col-lg-2">
                                <div class="form-group m-form__group">
                                    <div class="input-group">
                                        <select style="width: 100%;" name="status"
                                                class="form-control m-input ss--select-2">
                                            <option value="">Chọn loại hoa hồng</option>
                                            @foreach ($TYPE_COMMISSION as $typeItem)
                                                @if ($typeItem == 'order')
                                                    <option value="{{ $typeItem }}">Theo doanh thu đơn hàng</option>
                                                @elseif ($typeItem == 'kpi')
                                                    <option value="{{ $typeItem }}">Theo KPI</option>
                                                @else
                                                    <option value="{{ $typeItem }}">Theo hợp đồng</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-2 form-group">
                                <select style="width: 100%;" name="status" class="form-control m-input ss--select-2">
                                    <option value="">{{__('Chọn tags')}}</option>
                                    @foreach ($TAG_DATA as $tagItem)
                                        <option value="{{ $tagItem['tags_id'] }}">{{ $tagItem['tags_name'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-2">
                                <div class="form-group m-form__group">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="commission_name"
                                               placeholder="{{ __('Tên hoa hồng') }}"/>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-2">
                                <div class="form-group m-form__group">
                                    <button class="btn btn-primary color_button btn-search">{{ __('TÌM KIẾM') }} <i
                                                class="fa fa-search ic-search m--margin-left-5"></i></button>
                                    <a href="{{ route('admin.commission') }}"
                                       class="btn btn-metal btn-search padding9x">
                                        <span>
                                            <i class="flaticon-refresh"></i>
                                        </span>
                                    </a>
                                </div>
                            </div>

                        </div>

                        <!-- Table chọn hoa hồng -->
                        <div class="table-content m--padding-top-30">
                            @include('commission::components.allocate-commission-table')
                        </div>

                        <!-- Nút hủy và tiếp theo -->
                        <div class="modal-footer save-attribute m--margin-right-20">
                            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                                <div class="m-form__actions m--align-right">
                                    <a href="#"
                                       class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5 prevtab">
                                        <span class="ss--text-btn-mobi">
                                            <i class="la la-arrow-left"></i>
                                            <span>TRỞ VỀ</span>
                                        </span>
                                    </a>
                                    <button type="button"
                                            class="ss--btn-mobiles m--margin-bottom-5 btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 nexttab">
                                        <span class="ss--text-btn-mobi">
                                            <i class="la la-check"></i>
                                            <span>TIẾP THEO</span>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bảng review -->
                    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">

                        <!-- Table review -->
                        <div class="table-content m--padding-top-30">
                            @include('commission::components.allocate-review-table')
                        </div>

                        <!-- Nút hủy và tiếp theo -->
                        <div class="modal-footer save-attribute m--margin-right-20">
                            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                                <div class="m-form__actions m--align-right">
                                    <a href="#"
                                       class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5 prevtab">
                                        <span class="ss--text-btn-mobi">
                                            <i class="la la-arrow-left"></i>
                                            <span>TRỞ VỀ</span>
                                        </span>
                                    </a>
                                    <button type="button" id="save-allocate-btn"
                                            class="ss--btn-mobiles m--margin-bottom-5 btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 nexttab">
                                        <span class="ss--text-btn-mobi">
                                            <i class="la la-check"></i>
                                            <span>LƯU THÔNG TIN</span>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </form>
@endsection

@section('after_script')
    <script src="{{ asset('static/backend/js/admin/commission/script.js?v=' . time()) }}"></script>
@stop
