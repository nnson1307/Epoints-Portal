<!-- BEGIN: Left Aside -->
{{--<button class="m-aside-left-close m-aside-left-close--skin-dark" id="m_aside_left_close_btn">--}} {{-- <i class="la la-close"></i>--}} {{--</button>--}}
<div id="m_aside_left" class="m-grid__item m-aside-left m-aside-left--skin-light">
    <!-- BEGIN: Aside Menu -->
    <div id="m_ver_menu" class="m-aside-menu m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark" m-menu-vertical="1" m-menu-scrollable="1" m-menu-dropdown-timeout="500" style="position: relative;">
        <ul class="m-menu__nav m-menu__nav--dropdown-submenu-arrow m-scroller ps ps--active-y">
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true">
                <a href="{{route('dashbroad')}}" class="m-menu__link">
                    <i class="m-menu__link-icon flaticon-line-graph"></i>
                    <span class="m-menu__link-title">
                        <span class="m-menu__link-wrap">
                            <span class="m-menu__link-text">
                                Trang chủ
                            </span>
                        </span>
                    </span>
                </a>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true">
                <a href="{{route('admin.customer_appointment.list-day')}}" class="m-menu__link">
                    <div class="menu-icon m-menu__link-icon"><img src="{{asset('static/backend/images/icon/icon-calendar.png')}}" alt="" /></div>

                    <span class="m-menu__link-text">
                        Lịch hẹn
                    </span>
                </a>
                m-menu__item m-menu__item--submenu
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true">
                <a href="{{route('admin.order')}}" class="m-menu__link">
                    <div class="menu-icon m-menu__link-icon">
                        <img src="{{asset('static/backend/images/icon/icon-order.png')}}" alt="" />
                    </div>

                    <span class="m-menu__link-text">
                        Đơn hàng
                    </span>
                </a>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.customer')}}" class="m-menu__link">
                    <div class="menu-icon m-menu__link-icon"><img src="{{asset('static/backend/images/icon/icon-member.png')}}" alt="" /></div>

                    <span class="m-menu__link-text">
                        Khách hàng
                    </span>
                </a>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true">
                <a href="{{route('admin.voucher')}}" class="m-menu__link">
                    <div class="menu-icon m-menu__link-icon"><img src="{{asset('uploads/admin/icon/icon-promotion.png')}}" alt="" /></div>

                    <span class="m-menu__link-text">
                        Khuyến mãi (Voucher)
                    </span>
                </a>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.service-card')}}" class="m-menu__link">
                    <div class="menu-icon m-menu__link-icon"><img src="{{asset('uploads/admin/icon/icon-services-card.png')}}" alt="" /></div>

                    <span class="m-menu__link-text">
                        Thẻ dịch vụ
                    </span>
                </a>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.service-card.sold.service-card')}}" class="m-menu__link">
                    <div class="menu-icon m-menu__link-icon">
                        <i class="fa fa-id-card"></i>
                    </div>
                    <span class="m-menu__link-text">
                        Thẻ dịch vụ đã bán
                    </span>
                </a>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.service-card.sold.service-money')}}" class="m-menu__link">
                    <div class="menu-icon m-menu__link-icon">
                        <i class="fa fa-credit-card"></i>
                    </div>
                    <span class="m-menu__link-text">
                        Thẻ tiền đã bán
                    </span>
                </a>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.sms.config-sms')}}" class="m-menu__link">
                    <div class="menu-icon m-menu__link-icon"><img src="{{asset('uploads/admin/icon/icon-sms.png')}}" alt="" /></div>

                    <span class="m-menu__link-text">
                        Cấu hình gửi SMS
                    </span>
                </a>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.sms.sms-campaign')}}" class="m-menu__link">
                    <div class="menu-icon m-menu__link-icon"><img src="{{asset('uploads/admin/icon/icon-sms.png')}}" alt="" /></div>

                    <span class="m-menu__link-text">
                        Chiến dịch SMS
                    </span>
                </a>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.email-auto')}}" class="m-menu__link">
                    <div class="menu-icon m-menu__link-icon"><img src="{{asset('uploads/admin/icon/icon-email.png')}}" alt="" /></div>

                    <span class="m-menu__link-text">
                        Cấu hình Email tự động
                    </span>
                </a>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.email')}}" class="m-menu__link">
                    <div class="menu-icon m-menu__link-icon"><img src="{{asset('uploads/admin/icon/icon-email.png')}}" alt="" /></div>

                    <span class="m-menu__link-text">
                        Chiến dịch Email
                    </span>
                </a>
            </li>
            <li class="m-menu__section">
                <h4 class="m-menu__section-text">
                    Quản lý dịch vụ
                </h4>
                <i class="m-menu__section-icon flaticon-more-v3"></i>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.service')}}" class="m-menu__link">
                    <div class="menu-icon m-menu__link-icon"><img src="{{asset('uploads/admin/icon/icon-services.png')}}" alt="" /></div>

                    <span class="m-menu__link-text">
                        Danh sách dịch vụ
                    </span>
                </a>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.service-branch-price')}}" class="m-menu__link">
                    <div class="menu-icon m-menu__link-icon"><img src="{{asset('static/backend/images/icon/icon-price.png')}}" alt="" /></div>

                    <span class="m-menu__link-text">
                        Giá dịch vụ
                    </span>
                </a>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.service_category')}}" class="m-menu__link">
                    <div class="menu-icon m-menu__link-icon">
                        <i class="la la-object-group"></i>
                    </div>
                    <span class="m-menu__link-text">
                        Nhóm dịch vụ
                    </span>
                </a>
            </li>
            <li class="m-menu__section">
                <h4 class="m-menu__section-text">
                    Quản lý sản phẩm
                </h4>
                <i class="m-menu__section-icon flaticon-more-v3"></i>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.product')}}" class="m-menu__link">
                    <div class="menu-icon m-menu__link-icon"><img src="{{asset('static/backend/images/icon/icon-product.png')}}" alt="" /></div>

                    <span class="m-menu__link-text">
                        Danh sách sản phẩm
                    </span>
                </a>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.product-branch-price')}}" class="m-menu__link">
                    <div class="menu-icon m-menu__link-icon"><img src="{{asset('static/backend/images/icon/icon-price.png')}}" alt="" /></div>

                    <span class="m-menu__link-text">
                        Giá sản phẩm
                    </span>
                </a>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.product-category')}}" class="m-menu__link">
                    <div class="menu-icon m-menu__link-icon">
                        <i class="la la-list"></i>
                    </div>
                    <span class="m-menu__link-text">
                        Danh mục
                    </span>
                </a>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.product-attribute-group')}}" class="m-menu__link">
                    <div class="menu-icon m-menu__link-icon">
                        <i class="la la-cubes"></i>
                    </div>
                    <span class="m-menu__link-text">
                        Nhóm thuộc tính
                    </span>
                </a>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.product-attribute')}}" class="m-menu__link">
                    <div class="menu-icon m-menu__link-icon">
                        <i class="la la-cube"></i>
                    </div>
                    <span class="m-menu__link-text">
                        Thuộc tính
                    </span>
                </a>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.product-model')}}" class="m-menu__link">
                    <div class="menu-icon m-menu__link-icon">
                        <i class="la la-skyatlas"></i>
                    </div>
                    <span class="m-menu__link-text">
                        Nhãn hiệu
                    </span>
                </a>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.supplier')}}" class="m-menu__link">
                    <div class="menu-icon m-menu__link-icon">
                        <i class="la la-suitcase"></i>
                    </div>
                    <span class="m-menu__link-text">
                        Nhà cung cấp
                    </span>
                </a>
            </li>
            <li class="m-menu__section">
                <h4 class="m-menu__section-text">
                    Quản lý kho
                </h4>
                <i class="m-menu__section-icon flaticon-more-v3"></i>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.product-inventory')}}" class="m-menu__link">
                    <div class="menu-icon m-menu__link-icon"><img src="{{asset('uploads/admin/icon/icon-kho.png')}}" alt="" /></div>

                    <span class="m-menu__link-text">
                        Tồn kho
                    </span>
                </a>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.warehouse')}}" class="m-menu__link">
                    <div class="menu-icon m-menu__link-icon">
                        <i class="la la-list-ol"></i>
                    </div>
                    <span class="m-menu__link-text">
                        Danh sách kho
                    </span>
                </a>
            </li>
            <li class="m-menu__section">
                <h4 class="m-menu__section-text">
                    Quản lý nhân viên
                </h4>
                <i class="m-menu__section-icon flaticon-more-v3"></i>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.staff')}}" class="m-menu__link">
                    <div class="menu-icon m-menu__link-icon"><img src="{{asset('static/backend/images/icon/icon-staff.png')}}" alt="" /></div>

                    <span class="m-menu__link-text">
                        Danh sách nhân viên
                    </span>
                </a>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.department')}}" class="m-menu__link">
                    <div class="menu-icon m-menu__link-icon">
                        <i class="fa fa-home"></i>
                    </div>
                    <span class="m-menu__link-text">
                        Danh sách phòng ban
                    </span>
                </a>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.shift')}}" class="m-menu__link">
                    <div class="menu-icon m-menu__link-icon">
                        <i class="la la-clock-o"></i>
                    </div>
                    <span class="m-menu__link-text">
                        Ca làm việc
                    </span>
                </a>
            </li>
            <li class="m-menu__section">
                <h4 class="m-menu__section-text">
                    Quản lý chung
                </h4>
                <i class="m-menu__section-icon flaticon-more-v3"></i>
            </li>
            @php($menuAdmin= [ ['route'=>'dsada','name'=>'name menu','icon'=>'link hinh icon'] ])
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.branch')}}" class="m-menu__link">
                    <div class="menu-icon m-menu__link-icon">
                        <i class="fa fa-university"></i>
                    </div>
                    <span class="m-menu__link-text">
                        Danh sách chi nhánh
                    </span>
                </a>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.bussiness')}}" class="m-menu__link">
                    <div class="menu-icon m-menu__link-icon">
                        <i class="la la-deviantart"></i>
                    </div>
                    <span class="m-menu__link-text">
                        Ngành nghề kinh doanh
                    </span>
                </a>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.order-source')}}" class="m-menu__link">
                    <div class="menu-icon m-menu__link-icon">
                        <i class="la la-clipboard"></i>
                    </div>
                    <span class="m-menu__link-text">
                        Nguồn đơn hàng
                    </span>
                </a>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('customer-group')}}" class="m-menu__link">
                    <div class="menu-icon m-menu__link-icon">
                        <i class="la la-users"></i>
                    </div>
                    <span class="m-menu__link-text">
                        Nhóm khách hàng
                    </span>
                </a>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('customer-source')}}" class="m-menu__link">
                    <div class="menu-icon m-menu__link-icon">
                        <i class="la la-gear"></i>
                    </div>
                    <span class="m-menu__link-text">
                        Nguồn khách hàng
                    </span>
                </a>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.member-level')}}" class="m-menu__link">
                    <div class="menu-icon m-menu__link-icon">
                        <i class="la la-level-up"></i>
                    </div>
                    <span class="m-menu__link-text">
                        Cấp độ thành viên
                    </span>
                </a>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.transport')}}" class="m-menu__link">
                    <i class="m-menu__link-icon flaticon-truck"></i>
                    <span class="m-menu__link-text">
                        Đơn vị vận chuyển
                    </span>
                </a>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.room')}}" class="m-menu__link">
                    <i class="m-menu__link-icon flaticon-interface-9"></i>
                    <span class="m-menu__link-text">
                        Phòng phục vụ
                    </span>
                </a>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.unit')}}" class="m-menu__link">
                    <i class="m-menu__link-icon flaticon-profile-1"></i>
                    <span class="m-menu__link-text">
                        Danh sách đơn vị tính
                    </span>
                </a>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.unit_conversion')}}" class="m-menu__link">
                    <i class="m-menu__link-icon flaticon-interface-9"></i>
                    <span class="m-menu__link-text">
                        Danh sách quy đổi
                    </span>
                </a>
            </li>
            <li class="m-menu__section">
                <h4 class="m-menu__section-text">
                    Cấu hình
                </h4>
                <i class="m-menu__section-icon flaticon-more-v3"></i>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.config.page-appointment')}}" class="m-menu__link">
                    <i class="m-menu__link-icon la la-calendar"></i>
                    <span class="m-menu__link-text">
                        Cấu hình trang đặt lịch
                    </span>
                </a>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.config-email-template')}}" class="m-menu__link">
                    <i class="m-menu__link-icon flaticon-truck"></i>
                    <span class="m-menu__link-text">
                        Cấu hình template email
                    </span>
                </a>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.config-print-service-card')}}" class="m-menu__link">
                    <i class="m-menu__link-icon flaticon-truck"></i>
                    <span class="m-menu__link-text">
                        Cấu hình thẻ in
                    </span>
                </a>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.config-print-bill')}}" class="m-menu__link">
                    <i class="m-menu__link-icon la la-print"></i>
                    <span class="m-menu__link-text">
                        In hóa đơn
                    </span>
                </a>
            </li>
            <li class="m-menu__section">
                <h4 class="m-menu__section-text">
                    Báo cáo doanh thu
                </h4>
                <i class="m-menu__section-icon flaticon-more-v3"></i>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.report-revenue.branch')}}" class="m-menu__link">
                    <div class="menu-icon m-menu__link-icon"><img src="{{asset('static/backend/images/icon/icon-report.png')}}" alt="" /></div>

                    <span class="m-menu__link-text">
                        Doanh thu chi nhánh
                    </span>
                </a>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.report-revenue.customer')}}" class="m-menu__link">
                    <div class="menu-icon m-menu__link-icon"><img src="{{asset('static/backend/images/icon/icon-report.png')}}" alt="" /></div>

                    <span class="m-menu__link-text">
                        Doanh thu khách hàng
                    </span>
                </a>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.report-revenue.staff')}}" class="m-menu__link">
                    <div class="menu-icon m-menu__link-icon"><img src="{{asset('static/backend/images/icon/icon-report.png')}}" alt="" /></div>

                    <span class="m-menu__link-text">
                        Doanh thu nhân viên
                    </span>
                </a>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.report-revenue.product')}}" class="m-menu__link">
                    <div class="menu-icon m-menu__link-icon"><img src="{{asset('static/backend/images/icon/icon-report.png')}}" alt="" /></div>

                    <span class="m-menu__link-text">
                        Doanh thu sản phẩm
                    </span>
                </a>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.report-revenue.service')}}" class="m-menu__link">
                    <div class="menu-icon m-menu__link-icon"><img src="{{asset('static/backend/images/icon/icon-report.png')}}" alt="" /></div>

                    <span class="m-menu__link-text">
                        Doanh thu dịch vụ
                    </span>
                </a>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.report-revenue.service-card')}}" class="m-menu__link">
                    <div class="menu-icon m-menu__link-icon"><img src="{{asset('static/backend/images/icon/icon-report.png')}}" alt="" /></div>

                    <span class="m-menu__link-text">
                        Doanh thu thẻ dịch vụ
                    </span>
                </a>
            </li>
            <li class="m-menu__section">
                <h4 class="m-menu__section-text">
                    Thống kê
                </h4>
                <i class="m-menu__section-icon flaticon-more-v3"></i>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.report-growth.customer')}}" class="m-menu__link">
                    <div class="menu-icon m-menu__link-icon"><img src="{{asset('uploads/admin/icon/icon-thong-ke.png')}}" alt="" /></div>

                    <span class="m-menu__link-text">
                        Khách hàng
                    </span>
                </a>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.report-growth.branch')}}" class="m-menu__link">
                    <div class="menu-icon m-menu__link-icon"><img src="{{asset('uploads/admin/icon/icon-thong-ke.png')}}" alt="" /></div>

                    <span class="m-menu__link-text">
                        Chi nhánh
                    </span>
                </a>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.report-growth.service-card')}}" class="m-menu__link">
                    <div class="menu-icon m-menu__link-icon"><img src="{{asset('uploads/admin/icon/icon-thong-ke.png')}}" alt="" /></div>

                    <span class="m-menu__link-text">
                        Thẻ dịch vụ
                    </span>
                </a>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.report-customer-appointment')}}" class="m-menu__link">
                    <div class="menu-icon m-menu__link-icon"><img src="{{asset('uploads/admin/icon/icon-thong-ke.png')}}" alt="" /></div>

                    <span class="m-menu__link-text">
                        Lịch hẹn
                    </span>
                </a>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.statistical.order')}}" class="m-menu__link">
                    <div class="menu-icon m-menu__link-icon"><img src="{{asset('uploads/admin/icon/icon-thong-ke.png')}}" alt="" /></div>

                    <span class="m-menu__link-text">
                        Đơn hàng
                    </span>
                </a>
            </li>
            <li class="m-menu__section">
                <h4 class="m-menu__section-text">
                    Phân quyền
                </h4>
                <i class="m-menu__section-icon flaticon-more-v3"></i>
            </li>
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{{route('admin.staff-title')}}" class="m-menu__link">
                    <div class="menu-icon m-menu__link-icon"><img src="{{asset('uploads/admin/icon/icon-thong-ke.png')}}" alt="" /></div>

                    <span class="m-menu__link-text">
                        Chức vụ
                    </span>
                </a>
            </li>
        </ul>
    </div>
    <!-- END: Aside Menu -->
</div>
<!-- END: Left Aside aa-->
