 <!-- The Modal -->
 <div class="modal fade" id="nhandt-modal-oncall">
     <div class="modal-dialog modal-dialog-centered modal-big">
         <form class="modal-content clear-form">
             <div class="modal-header" style="height: 6.1rem;padding: 20px!important;align-items: center;">
                 <div class="w-100 d-flex justify-content-between align-items-center">
                    <h2 class="m-portlet__head-text">
                        <i class="fa fa-plus-circle ss--icon-title m--margin-right-5"></i>
                        {{ __('CHĂM SÓC KHÁCH HÀNG') }}
                    </h2>
                    <div class="m-form__actions m--align-right">
                        <button data-dismiss="modal"
                            class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                            <span class="ss--text-btn-mobi">
                                <i class="la la-arrow-left"></i>
                                <span>{{ __('HỦY') }}</span>
                            </span>
                        </button>

                        <button type="button" class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                            <span class="ss--text-btn-mobi">
                                <i class="la la-check"></i>
                                <span>{{ __('123') }}</span>
                            </span>
                        </button>
                        <button type="button" class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                            <span class="ss--text-btn-mobi">
                                <i class="fa fa-plus-circle m--margin-right-10"></i>
                                <span>{{ __('LƯU & TẠO MỚI') }}</span>
                            </span>
                        </button>
                    </div>
                </div>
                 <button type="button" class="close" data-dismiss="modal">&times;</button>
             </div>
             <div class="modal-body">
                 <div class="row">
                     <div class="col-lg-12">
                         <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                             <li class="nav-item">
                                 <a class="nav-link active" id="pills-info-detail-tab" data-toggle="pill"
                                     href="#pills-info-detail" role="tab" aria-controls="pills-info-detail"
                                     aria-selected="true">@lang('Thông tin chi tiết')</a>
                             </li>
                             <li class="nav-item">
                                 <a class="nav-link" id="pills-history-care-tab" data-toggle="pill"
                                     href="#pills-history-care" role="tab" aria-controls="pills-history-care"
                                     aria-selected="false">@lang('Lịch sử chăm sóc')</a>
                             </li>
                             <li class="nav-item">
                                 <a class="nav-link" id="pills-history-buy-tab" data-toggle="pill"
                                     href="#pills-history-buy" role="tab" aria-controls="pills-history-buy"
                                     aria-selected="false">@lang('Lịch sử mua hàng')</a>
                             </li>
                             <li class="nav-item">
                                 <a class="nav-link" id="pills-list-contract-tab" data-toggle="pill"
                                     href="#pills-list-contract" role="tab" aria-controls="pills-list-contract"
                                     aria-selected="false">@lang('Danh sách hợp đồng')</a>
                             </li>
                             <li class="nav-item">
                                 <a class="nav-link" id="pills-history-ticket-tab" data-toggle="pill"
                                     href="#pills-history-ticket" role="tab" aria-controls="pills-history-ticket"
                                     aria-selected="false">@lang('Lịch sử ticket')</a>
                             </li>
                         </ul>
                         <div class="tab-content" id="pills-tabContent">
                             <div class="tab-pane fade show active" id="pills-info-detail" role="tabpanel"
                                 aria-labelledby="pills-info-detail-tab">
                                 <div class="row">
                                     <div class="col-lg-6">
                                         <div class="form-group m-form__group">
                                             <label class="black_title">
                                                 {{ __('Tên khách hàng') }} <b class="text-danger">*</b>
                                             </label>
                                             <input type="text" name="manage_work_title" class="form-control m-input"
                                                 placeholder="{{ __('Nhập tên khách hàng') }}...">
                                         </div>
                                         <div class="form-group m-form__group">
                                             <label class="black_title">
                                                 {{ __('Giới tính') }} <b class="text-danger">*</b>
                                             </label>
                                             <div class="input-group m-radio-inline">
                                                 <label class="m-radio cus">
                                                     <input type="radio" name="gender" value="male"
                                                         selected>{{ __('Nam') }}
                                                     <span></span>
                                                 </label>
                                                 <label class="m-radio cus">
                                                     <input type="radio" name="gender"
                                                         value="female">{{ __('Nữ') }}
                                                     <span></span>
                                                 </label>
                                                 <label class="m-radio cus">
                                                     <input type="radio" name="gender"
                                                         value="orther">{{ __('Khác') }}
                                                     <span></span>
                                                 </label>
                                             </div>
                                         </div>
                                         <div class="form-group m-form__group">
                                             <label class="black_title">
                                                 @lang('Ngày sinh') <b class="text-danger">*</b>
                                             </label>
                                             <div class="d-flex">
                                                 <select name="day"
                                                     class="form-control select-unset_arrow text-center mr-3">
                                                     <option value="">@lang('Chọn ngày')</option>
                                                     @for ($i = 1; $i < 31 + 1; $i++)
                                                         <option value="{{ $i }}">{{ $i }}
                                                         </option>
                                                     @endfor
                                                 </select>
                                                 <select name="month"
                                                     class="form-control select-unset_arrow text-center mr-3">
                                                     <option value="">@lang('Chọn tháng')</option>
                                                     @for ($i = 1; $i < 12 + 1; $i++)
                                                         <option value="{{ $i }}">{{ $i }}
                                                         </option>
                                                     @endfor
                                                 </select>
                                                 <select name="year"
                                                     class="form-control select-unset_arrow text-center">
                                                     <option value="">@lang('Chọn năm')</option>
                                                     @for ($i = 1970; $i < date('Y') + 1; $i++)
                                                         <option value="{{ $i }}">{{ $i }}
                                                         </option>
                                                     @endfor
                                                 </select>
                                             </div>
                                         </div>
                                         <div class="form-group m-form__group">
                                             <label class="black_title">
                                                 @lang('Số điện thoại') <b class="text-danger">*</b>
                                             </label>
                                             <div>
                                                 <div class="input-group mb-3">
                                                     <input type="text" class="form-control" name="time"
                                                         placeholder="Nhập số điện thoại">
                                                     <div class="input-group-append">
                                                         <span class="input-group-text" id="basic-addon2"><i
                                                                 class="fa fa-phone" aria-hidden="true"></i></span>
                                                     </div>
                                                 </div>
                                             </div>
                                             <div class="mt-3"></div>
                                             <a href="javascript:void(0)" class="btn  btn-sm m-btn--icon color">
                                                 <span>
                                                     <i class="la la-plus"></i>
                                                     <span>
                                                         {{ __('Thêm số điện thoại') }}
                                                     </span>
                                                 </span>
                                             </a>
                                         </div>
                                         <div class="form-group m-form__group">
                                             <label class="black_title">
                                                 @lang('Địa chỉ') <b class="text-danger">*</b>
                                             </label>
                                             <div class="input-group row">
                                                 <div class="col-lg-6">
                                                     <select name="province_id"
                                                         class="form-control select2 select2-active">
                                                         <option value="">@lang('Chọn tỉnh/thành')</option>
                                                         <option value="hcm">Hồ Chí Minh</option>
                                                     </select>
                                                 </div>
                                                 <div class="col-lg-6">
                                                     <select name="district_id"
                                                         class="form-control select2 select2-active">
                                                         <option value="">@lang('Chọn quận/huyện')</option>
                                                         <option value="hcm">TP.Thủ Đức</option>
                                                     </select>
                                                 </div>
                                                 <div class="col-lg-12 mt-3">
                                                     <input type="text" name="adress" class="form-control m-input"
                                                         placeholder="{{ __('Nhập địa chỉ khách hàng') }}">
                                                 </div>
                                                 <div class="col-lg-12 mt-3">
                                                     <input type="text" name="post_code" class="form-control m-input"
                                                         placeholder="{{ __('Nhập post code') }}">
                                                 </div>
                                             </div>
                                         </div>
                                         <div class="form-group m-form__group">
                                             <label class="black_title">
                                                 {{ __('Email') }}
                                             </label>
                                             <input type="text" name="email" class="form-control m-input"
                                                 placeholder="{{ __('Nhập email') }}">
                                         </div>
                                         <div class="form-group m-form__group">
                                             <div class="input-group row align-items-center">
                                                 <div class="col-lg-5">
                                                     <div class="d-flex align-items-center">
                                                         <span class="mr-3">{{ __('Trạng thái') }}</span>
                                                         <span
                                                             class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                             <label>
                                                                 <input type="checkbox" class="manager-btn"
                                                                     name="is-actived" checked="">
                                                                 <span></span>
                                                             </label>
                                                         </span>
                                                     </div>
                                                 </div>
                                                 <div class="col-lg-7">
                                                     <i>{{ __('Chọn để kích hoạt trạng thái') }}</i>
                                                 </div>
                                             </div>
                                         </div>
                                     </div>
                                     <div class="col-lg-6">
                                         <div class="form-group m-form__group">
                                             <label class="black_title">
                                                 @lang('Nhóm khách hàng') <b class="text-danger">*</b>
                                             </label>
                                             <div class="input-group">
                                                 <select name="customer_group"
                                                     class="form-control select2 select2-active">
                                                     <option value="">@lang('Chọn nhóm khách hàng')</option>
                                                     <option value="hcm">Hồ Chí Minh</option>
                                                 </select>
                                             </div>
                                         </div>
                                         <div class="form-group m-form__group">
                                             <label class="black_title">
                                                 @lang('Nguồn khách hàng')
                                             </label>
                                             <div class="input-group">
                                                 <select name="customer_source"
                                                     class="form-control select2 select2-active">
                                                     <option value="">@lang('Chọn nguồn khách hàng')</option>
                                                     <option value="hcm">Hồ Chí Minh</option>
                                                 </select>
                                             </div>
                                         </div>
                                         <div class="form-group m-form__group">
                                             <label class="black_title">
                                                 @lang('Người giới thiệu')
                                             </label>
                                             <div class="input-group">
                                                 <select name="customer_introduce"
                                                     class="form-control select2 select2-active">
                                                     <option value="">@lang('Chọn người giới thiệu')</option>
                                                     <option value="1">Le Kha Phieu</option>
                                                     <option value="2">Le Kha Phieu 2</option>
                                                 </select>
                                             </div>
                                         </div>
                                         <div class="form-group m-form__group">
                                             <label class="black_title">
                                                 {{ __('Facebook') }}
                                             </label>
                                             <input type="text" name="email" class="form-control m-input"
                                                 placeholder="{{ __('Nhập link facebook') }}">
                                         </div>
                                         <div class="form-group m-form__group">
                                             <label class="black_title">
                                                 @lang('Ghi chú'):
                                             </label>
                                             <textarea class="form-control m-input" name="note" rows="6" cols="5"
                                                 placeholder="@lang('Nhập thông tin ghi chú')..."></textarea>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                             <div class="tab-pane fade" id="pills-history-care" role="tabpanel"
                                 aria-labelledby="pills-history-care-tab">
                                 <div class="row">
                                     <div class="col-lg-6">
                                         <div class="form-group m-form__group">
                                             <label class="black_title">
                                                 Loại chăm sóc:<b class="text-danger">*</b>
                                             </label>
                                             <div class="input-group" data-select2-id="25">
                                                 <select class="form-control select2 select2-active" name="care_type">
                                                     <option data-select2-id="23"></option>
                                                     <option value="call" data-select2-id="27">Gọi</option>
                                                     <option value="chat" data-select2-id="28">Trò chuyện</option>
                                                     <option value="email" data-select2-id="29">Email</option>
                                                 </select>
                                             </div>
                                         </div>
                                         <div class="form-group m-form__group">
                                             <label class="black_title">
                                                 Nội dung:<b class="text-danger">*</b>
                                             </label>
                                             <div class="input-group">
                                                 <textarea class="form-control" id="content" name="content"
                                                     rows="5"></textarea>
                                             </div>
                                         </div>
                                     </div>
                                     <div class="col-lg-6">
                                         <div>
                                             <div class="m-scrollable m-scroller ps ps--active-y">
                                                 <!--Begin::Timeline 2 -->
                                                 <div class="m-timeline-2">
                                                     <div
                                                         class="m-timeline-2__items  m--padding-top-25 m--padding-bottom-30">
                                                         <div class="m-timeline-2__item">
                                                             <span class="m-timeline-2__item-time">
                                                                 06/12
                                                             </span>
                                                         </div>
                                                         <div class="m-timeline-2__item m--margin-top-30">
                                                             <span class="m-timeline-2__item-time"></span>
                                                             <div class="m-timeline-2__item-cricle">
                                                                 <i class="fa fa-genderless m--font-success"></i>
                                                             </div>
                                                             <div class="m-timeline-2__item-text">
                                                                 <strong>11:16</strong>
                                                                 <br>
                                                                 Người chăm sóc: Admin <br>
                                                                 Loại chăm sóc:
                                                                 Email <br>
                                                                 Nội dung: Gửi mail lits tính năng
                                                             </div>
                                                         </div>
                                                         <div class="m-timeline-2__item m--margin-top-30">
                                                             <span class="m-timeline-2__item-time"></span>
                                                             <div class="m-timeline-2__item-cricle">
                                                                 <i class="fa fa-genderless m--font-success"></i>
                                                             </div>
                                                             <div class="m-timeline-2__item-text">
                                                                 <strong>11:16</strong>
                                                                 <br>
                                                                 Người chăm sóc: Admin <br>
                                                                 Loại chăm sóc:
                                                                 Gọi <br>
                                                                 Nội dung: Gọi KH Nhân tư vấn
                                                             </div>
                                                         </div>
                                                     </div>
                                                 </div>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                             <div class="tab-pane fade" id="pills-history-buy" role="tabpanel"
                                 aria-labelledby="pills-history-buy-tab">
                                 <div class="table-responsive">
                                     <table class="table table-striped m-table ss--header-table ss--nowrap">
                                         <thead>
                                             <tr>
                                                 <th class="ss--font-size-th ss--text-center">
                                                     {{ __('Thời gian tạo') }}</th>
                                                 <th class="ss--text-center ss--font-size-th">{{ __('Mã đơn hàng') }}
                                                 </th>
                                                 <th class="ss--text-center ss--font-size-th">
                                                     {{ __('Sản phẩm/Dịch vụ') }}</th>
                                                 <th class="ss--text-center ss--font-size-th">{{ __('Tổng tiền') }}
                                                 </th>
                                                 <th class="ss--text-center ss--font-size-th">{{ __('Trạng thái') }}
                                                 </th>
                                                 <th class="ss--text-center ss--font-size-th">{{ __('Ghi chú') }}</th>
                                                 <th></th>
                                             </tr>
                                         </thead>
                                         <tbody>
                                             <tr>
                                                 <td>
                                                     12:30 15/12/2021
                                                 </td>
                                                 <td>
                                                     <a href="#">DH_15122021264</a>
                                                 </td>
                                                 <td>
                                                     Senka Deep Moist Gel Cream/80g
                                                 </td>
                                                 <td>
                                                     500,000 đ
                                                 </td>
                                                 <td><span class="m-badge m-badge--success m-badge--wide">
                                                         {{ __('Đã thanh toán') }}
                                                     </span></td>
                                                 <td></td>
                                                 <td>
                                                     <a class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                                                         href="#">
                                                         <i class="la la-file-text"></i>
                                                     </a>
                                                     <a class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                                                         href="#">
                                                         <i class="la la-eye"></i>
                                                     </a>
                                                 </td>
                                             </tr>
                                             <tr>
                                                 <td>
                                                     12:30 15/12/2021
                                                 </td>
                                                 <td>
                                                     <a href="#">DH_15122021264</a>
                                                 </td>
                                                 <td>
                                                     Senka Deep Moist Gel Cream/80g
                                                 </td>
                                                 <td>
                                                     500,000 đ
                                                 </td>
                                                 <td><span class="m-badge m-badge--danger m-badge--wide">
                                                         {{ __('Chưa thanh toán') }}
                                                     </span></td>
                                                 <td></td>
                                                 <td>
                                                     <a class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                                                         href="#">
                                                         <i class="la la-file-text"></i>
                                                     </a>
                                                     <a class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                                                         href="#">
                                                         <i class="la la-eye"></i>
                                                     </a>
                                                 </td>
                                             </tr>
                                         </tbody>
                                     </table>
                                 </div>
                             </div>
                             <div class="tab-pane fade" id="pills-list-contract" role="tabpanel"
                                 aria-labelledby="pills-list-contract-tab">
                                 <div class="table-responsive">
                              
                                    <table class="table table-striped m-table ss--header-table ss--nowrap">
                                        <thead>
                                            <tr>
                                                <th class="ss--font-size-th ss--text-center">
                                                    {{ __('Mã hợp đồng') }}</th>
                                                <th class="ss--text-center ss--font-size-th">{{ __('Tên hợp đồng') }}
                                                </th>
                                                <th class="ss--text-center ss--font-size-th">
                                                    {{ __('Loại hợp đồng') }}</th>
                                                <th class="ss--text-center ss--font-size-th">{{ __('Giá trị hợp đồng') }}
                                                </th>
                                                <th class="ss--text-center ss--font-size-th">{{ __('Giá trị đã thanh toán') }}
                                                </th>
                                                <th class="ss--text-center ss--font-size-th">{{ __('Trạng thái') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <a href="#">DH_15122021264</a>
                                                </td>
                                                <td>
                                                    Hợp đồng bán phần mềm
                                                </td>
                                                <td>
                                                    Senka Deep Moist Gel Cream/80g
                                                </td>
                                                <td>
                                                    500,000 đ
                                                </td>
                                                <td>
                                                    500,000 đ
                                                </td>
                                                <td><span class="m-badge m-badge--success m-badge--wide">
                                                        {{ __('Đã thanh toán') }}
                                                    </span></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <a href="#">DH_15122021264</a>
                                                </td>
                                                <td>
                                                    Hợp đồng bán phần mềm
                                                </td>
                                                <td>
                                                    Senka Deep Moist Gel Cream/80g
                                                </td>
                                                <td>
                                                    500,000 đ
                                                </td>
                                                <td>
                                                    500,000 đ
                                                </td>
                                                <td><span class="m-badge m-badge--danger m-badge--wide">
                                                        {{ __('Đang thực hiện') }}
                                                    </span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                             </div>
                             <div class="tab-pane fade" id="pills-history-ticket" role="tabpanel"
                                 aria-labelledby="pills-history-ticket-tab">
                                 <div class="table-responsive">
                                    <table class="table table-striped m-table ss--header-table ss--nowrap">
                                        <thead>
                                            <tr>
                                                <th class="ss--font-size-th ss--text-center">
                                                    {{ __('Mã ticket') }}</th>
                                                <th class="ss--text-center ss--font-size-th">{{ __('Tiêu đề') }}
                                                </th>
                                                <th class="ss--text-center ss--font-size-th">{{ __('Yêu cầu') }}
                                                </th>
                                                <th class="ss--text-center ss--font-size-th">
                                                    {{ __('Loại yêu cầu') }}</th>
                                                <th class="ss--text-center ss--font-size-th">{{ __('Thời gian tạo') }}
                                                </th>
                                                <th class="ss--text-center ss--font-size-th">{{ __('Người tạo') }}
                                                </th>
                                                <th class="ss--text-center ss--font-size-th">{{ __('Người xử lý') }}
                                                </th>
                                                <th class="ss--text-center ss--font-size-th">{{ __('Trạng thái') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <a href="#">TKTK_20211215_080</a>
                                                </td>
                                                <td>
                                                    Xử lý sự cố mất kết nối
                                                </td>
                                                <td>
                                                    Xử lý sự cố
                                                </td>
                                                <td>
                                                    Mất kết nối camera
                                                </td>
                                                <td>
                                                    15:30 15/12/2021
                                                </td>
                                                <td>
                                                    Admin
                                                </td>
                                                <td>
                                                    Admin
                                                </td>
                                                <td><span class="m-badge m-badge--warning m-badge--wide">
                                                        {{ __('Hoàn tất') }}
                                                    </span></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <a href="#">TKTK_20211215_080</a>
                                                </td>
                                                <td>
                                                    Xử lý sự cố mất kết nối
                                                </td>
                                                <td>
                                                    Xử lý sự cố
                                                </td>
                                                <td>
                                                    Mất kết nối camera
                                                </td>
                                                <td>
                                                    15:30 15/12/2021
                                                </td>
                                                <td>
                                                    Admin
                                                </td>
                                                <td>
                                                    Admin
                                                </td>
                                                <td><span class="m-badge m-badge--meta m-badge--wide">
                                                        {{ __('Đóng') }}
                                                    </span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
             <div class="modal-footer">
                 <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                     <div class="m-form__actions m--align-right">
                         <button data-dismiss="modal"
                             class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                             <span class="ss--text-btn-mobi">
                                 <i class="la la-arrow-left"></i>
                                 <span>{{ __('HỦY') }}</span>
                             </span>
                         </button>

                         <button type="button" onclick="ManagerWork.addClose()"
                             class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                             <span class="ss--text-btn-mobi">
                                 <i class="la la-check"></i>
                                 <span>{{ __('LƯU THÔNG TIN') }}</span>
                             </span>
                         </button>
                         <button type="button" onclick="ManagerWork.add()"
                             class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                             <span class="ss--text-btn-mobi">
                                 <i class="fa fa-plus-circle m--margin-right-10"></i>
                                 <span>{{ __('LƯU & TẠO MỚI') }}</span>
                             </span>
                         </button>
                     </div>
                 </div>
             </div>
         </form>
     </div>
 </div>
