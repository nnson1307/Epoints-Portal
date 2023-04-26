<header id="m_header" class="m-grid__item    m-header " m-minimize-offset="200" m-minimize-mobile-offset="200">
    <div class="m-container m-container--fluid m-container--full-height">
        <div class="m-stack m-stack--ver m-stack--desktop">

            <!-- BEGIN: Brand -->
            <div class="m-stack__item m-brand  m-brand--skin-light ">
                <div class="m-stack m-stack--ver m-stack--general">
                    <div class="m-stack__item m-stack__item--middle m-brand__logo">
                        <a href="#" class="m-brand__logo-wrapper">
                            <img alt="" src="{{asset('static/backend/images/logo-matthew.png')}}"/>
                        </a>
                    </div>
                    <div class="m-stack__item m-stack__item--middle m-brand__tools">

                        <!-- BEGIN: Left Aside Minimize Toggle -->
                        <a href="javascript:;" id="m_aside_left_minimize_toggle"
                           class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-desktop-inline-block  ">
                            <span></span>
                        </a>

                        <!-- END -->

                        <!-- BEGIN: Responsive Aside Left Menu Toggler -->
                        <a href="javascript:;" id="m_aside_left_offcanvas_toggle"
                           class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-tablet-and-mobile-inline-block">
                            <span></span>
                        </a>

                        <!-- END -->

                        <!-- BEGIN: Responsive Header Menu Toggler -->
                    {{--<a id="m_aside_header_menu_mobile_toggle" href="javascript:;" class="m-brand__icon m-brand__toggler m--visible-tablet-and-mobile-inline-block">--}}
                    {{--<span></span>--}}
                    {{--</a>--}}

                    <!-- END -->

                        <!-- BEGIN: Topbar Toggler -->
                        <a id="m_aside_header_topbar_mobile_toggle" href="javascript:;"
                           class="m-brand__icon m--visible-tablet-and-mobile-inline-block">
                            <i class="flaticon-more"></i>
                        </a>

                        <!-- BEGIN: Topbar Toggler -->
                    </div>
                </div>
            </div>

            <!-- END: Brand -->
            <div class="m-stack__item m-stack__item--fluid m-header-head" id="m_header_nav">

                <!-- BEGIN: Horizontal Menu -->
                <button class="m-aside-header-menu-mobile-close  m-aside-header-menu-mobile-close--skin-dark "
                        id="m_aside_header_menu_mobile_close_btn"><i class="la la-close"></i></button>
                <div id="m_header_menu"
                     class="m-header-menu m-aside-header-menu-mobile m-aside-header-menu-mobile--offcanvas  m-header-menu--skin-light m-header-menu--submenu-skin-light m-aside-header-menu-mobile--skin-dark m-aside-header-menu-mobile--submenu-skin-dark ">
                    <ul class="m-menu__nav  m-menu__nav--submenu-arrow ">
                        <li class="m-menu__item  m-menu__item--submenu m-menu__item--rel"
                            m-menu-submenu-toggle="click" m-menu-link-redirect="1" aria-haspopup="true">
                            @yield('title_header')
                        </li>

                    </ul>
                </div>

                <!-- END: Horizontal Menu -->

                <!-- BEGIN: Topbar -->
                <div id="m_header_topbar" class="m-topbar  m-stack m-stack--ver m-stack--general m-stack--fluid">
                    <div class="m-stack__item m-topbar__nav-wrapper">
                        <ul class="m-topbar__nav m-nav m-nav--inline">
                            <li class="m-nav__item m-dropdown m-dropdown--large m-dropdown--arrow m-dropdown--align-center m-dropdown--mobile-full-width m-dropdown--skin-light	m-list-search m-list-search--skin-light"
                                m-dropdown-toggle="click" id="m_quicksearch"
                                m-quicksearch-mode="dropdown" m-dropdown-persistent="1">
                                <div class="m-nav__link m-dropdown__toggle">
                                    <form id="form-search" method="GET" action="{{route('admin.layout.search-result')}}"
                                          class="input-group m-input-group m-input-group--pill inp">
                                        {{csrf_field()}}
                                        {{--<div class="m-typeahead">--}}
                                        {{--<span class="twitter-typeahead"--}}
                                        {{--style="position: relative; display: inline-block;">--}}
                                        {{--<input onkeyup="Layout.searchDashboard(this)" type="text"--}}
                                        {{--class="form-control m-input btn-sm w-ip"--}}
                                        {{--placeholder="Nhập nội dung tìm kiếm" aria-describedby="basic-addon1">--}}
                                        {{--</span>--}}
                                        {{--</div>--}}
                                        <div class="m-typeahead">
                                        <span class="twitter-typeahead" style="position: relative; display: inline-block;">
                                        <div class="m-input-icon m-input-icon--right">
                                            <input name="keyword" aria-describedby="basic-addon1"
                                               class="form-control m-input btn-sm w-ip typeahead m-input--pill  "
                                               onkeyup="Layout.searchTypeahead(this)"
                                               id="m_typeahead_2" type="text"
                                               placeholder="Nhập nội dung tìm kiếm" autocomplete="off"
                                               spellcheck="false"
                                               dir="auto"
                                               style="position: relative; vertical-align: top; background-color: transparent;
                                        border-top-left-radius: 1.3rem;border-bottom-left-radius: 1.3rem;">
                                                    <span id="sm-form" class="m-input-icon__icon m-input-icon__icon--right"><span><i
                                                class="la la-search"></i></span></span>
                                        </div>

                                            {{--<div class="m-input-icon m-input-icon--right">--}}
                                            {{--<input type="text"--}}
                                            {{--class="form-control m-input m-input--pill btn-sm w-ip typeahead"--}}
                                            {{--placeholder="Nhập thông tin tìm kiếm" name="keyword"--}}
                                            {{--autocomplete="off"--}}
                                            {{--spellcheck="false"--}}
                                            {{--dir="auto"--}}
                                            {{--style="position: relative; vertical-align: top; background-color: transparent;--}}
                                            {{--border-top-left-radius: 1.3rem;border-bottom-left-radius: 1.3rem;"--}}
                                            {{--aria-describedby="basic-addon1"--}}
                                            {{--onkeyup="Layout.searchTypeahead(this)">--}}
                                            {{--<span class="m-input-icon__icon m-input-icon__icon--right"><span><i--}}
                                            {{--class="la la-search"></i></span></span>--}}
                                            {{--</div>--}}
                                        </span>
                                        </div>


                                        {{--<div class="input-group-append" id="sm-form">--}}
                                        {{--<span class="input-group-text" id="basic-addon1"--}}
                                        {{--style="background-color: #4fc4ca">--}}
                                        {{--<i class="fa fa-search" style="color: #fff"></i>--}}
                                        {{--</span>--}}
                                        {{--</div>--}}


                                        {{--<div class="m-input-icon m-input-icon--right">--}}
                                        {{--<input type="text" class="form-control m-input m-input--pill"--}}
                                        {{--placeholder="Nhập thông tin tìm kiếm" id="">--}}
                                        {{--<span class="m-input-icon__icon m-input-icon__icon--right"><span><i--}}
                                        {{--class="la la-search"></i></span></span>--}}
                                        {{--</div>--}}
                                    </form>
                                    <form id="form-search-hhidden"
                                          action="{{route('admin.layout.search.detail-search')}}"
                                          method="GET">                                        {{csrf_field()}}
                                        <input type="hidden" name="idSearchDashboard" id="idSearchDashboard" value="">
                                        <input type="hidden" name="nameSearchDashboard" id="nameSearchDashboard"
                                               value="">
                                    </form>
                                </div>

                            </li>
                            <li class="m-nav__item m-topbar__notifications m-topbar__notifications--img m-dropdown m-dropdown--large m-dropdown--header-bg-fill m-dropdown--arrow m-dropdown--align-center m-dropdown--mobile-full-width bd-right"
                                data-dropdown-toggle="click" data-dropdown-persistent="true">
                                <a href="#" class="m-nav__link m-dropdown__toggle" id="m_topbar_notification_icon">
                                    <span class="m-nav__link-badge m-badge m-badge--dot m-badge--dot-small m-badge--danger"></span>
                                    <span class="m-nav__link-icon">
													<i class="flaticon-music-2"></i>
												</span>
                                </a>
                                <div class="m-dropdown__wrapper">
                                    <span class="m-dropdown__arrow m-dropdown__arrow--center"></span>
                                    <div class="m-dropdown__inner">
                                        <div class="m-dropdown__header m--align-center"
                                             style="background: url(../../assets/app/media/img/misc/notification_bg.jpg); background-size: cover;">
														<span class="m-dropdown__header-title">
															9 New
														</span>
                                            <span class="m-dropdown__header-subtitle">
															User Notifications
														</span>
                                        </div>
                                        <div class="m-dropdown__body">
                                            <div class="m-dropdown__content">
                                                <ul class="nav nav-tabs m-tabs m-tabs-line m-tabs-line--brand"
                                                    role="tablist">
                                                    <li class="nav-item m-tabs__item">
                                                        <a class="nav-link m-tabs__link active" data-toggle="tab"
                                                           href="#topbar_notifications_notifications" role="tab">
                                                            Alerts
                                                        </a>
                                                    </li>
                                                    <li class="nav-item m-tabs__item">
                                                        <a class="nav-link m-tabs__link" data-toggle="tab"
                                                           href="#topbar_notifications_events" role="tab">
                                                            Events
                                                        </a>
                                                    </li>
                                                    <li class="nav-item m-tabs__item">
                                                        <a class="nav-link m-tabs__link" data-toggle="tab"
                                                           href="#topbar_notifications_logs" role="tab">
                                                            Logs
                                                        </a>
                                                    </li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane active" id="topbar_notifications_notifications"
                                                         role="tabpanel">
                                                        <div class="m-scrollable" data-scrollable="true"
                                                             data-max-height="250" data-mobile-max-height="200">
                                                            <div class="m-list-timeline m-list-timeline--skin-light">
                                                                <div class="m-list-timeline__items">
                                                                    <div class="m-list-timeline__item">
                                                                        <span class="m-list-timeline__badge -m-list-timeline__badge--state-success"></span>
                                                                        <span class="m-list-timeline__text">
																						12 new users registered
																					</span>
                                                                        <span class="m-list-timeline__time">
																						Just now
																					</span>
                                                                    </div>
                                                                    <div class="m-list-timeline__item">
                                                                        <span class="m-list-timeline__badge"></span>
                                                                        <span class="m-list-timeline__text">
																						System shutdown
																						<span class="m-badge m-badge--success m-badge--wide">
																							pending
																						</span>
																					</span>
                                                                        <span class="m-list-timeline__time">
																						14 mins
																					</span>
                                                                    </div>
                                                                    <div class="m-list-timeline__item">
                                                                        <span class="m-list-timeline__badge"></span>
                                                                        <span class="m-list-timeline__text">
																						New invoice received
																					</span>
                                                                        <span class="m-list-timeline__time">
																						20 mins
																					</span>
                                                                    </div>
                                                                    <div class="m-list-timeline__item">
                                                                        <span class="m-list-timeline__badge"></span>
                                                                        <span class="m-list-timeline__text">
																						DB overloaded 80%
																						<span class="m-badge m-badge--info m-badge--wide">
																							settled
																						</span>
																					</span>
                                                                        <span class="m-list-timeline__time">
																						1 hr
																					</span>
                                                                    </div>
                                                                    <div class="m-list-timeline__item">
                                                                        <span class="m-list-timeline__badge"></span>
                                                                        <span class="m-list-timeline__text">
																						System error -
																						<a href="#" class="m-link">
																							Check
																						</a>
																					</span>
                                                                        <span class="m-list-timeline__time">
																						2 hrs
																					</span>
                                                                    </div>
                                                                    <div class="m-list-timeline__item m-list-timeline__item--read">
                                                                        <span class="m-list-timeline__badge"></span>
                                                                        <span href="" class="m-list-timeline__text">
																						New order received
																						<span class="m-badge m-badge--danger m-badge--wide">
																							urgent
																						</span>
																					</span>
                                                                        <span class="m-list-timeline__time">
																						7 hrs
																					</span>
                                                                    </div>
                                                                    <div class="m-list-timeline__item m-list-timeline__item--read">
                                                                        <span class="m-list-timeline__badge"></span>
                                                                        <span class="m-list-timeline__text">
																						Production server down
																					</span>
                                                                        <span class="m-list-timeline__time">
																						3 hrs
																					</span>
                                                                    </div>
                                                                    <div class="m-list-timeline__item">
                                                                        <span class="m-list-timeline__badge"></span>
                                                                        <span class="m-list-timeline__text">
																						Production server up
																					</span>
                                                                        <span class="m-list-timeline__time">
																						5 hrs
																					</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane" id="topbar_notifications_events"
                                                         role="tabpanel">
                                                        <div class="m-scrollable" data-max-height="250"
                                                             data-mobile-max-height="200">
                                                            <div class="m-list-timeline m-list-timeline--skin-light">
                                                                <div class="m-list-timeline__items">
                                                                    <div class="m-list-timeline__item">
                                                                        <span class="m-list-timeline__badge m-list-timeline__badge--state1-success"></span>
                                                                        <a href="" class="m-list-timeline__text">
                                                                            New order received
                                                                        </a>
                                                                        <span class="m-list-timeline__time">
																						Just now
																					</span>
                                                                    </div>
                                                                    <div class="m-list-timeline__item">
                                                                        <span class="m-list-timeline__badge m-list-timeline__badge--state1-danger"></span>
                                                                        <a href="" class="m-list-timeline__text">
                                                                            New invoice received
                                                                        </a>
                                                                        <span class="m-list-timeline__time">
																						20 mins
																					</span>
                                                                    </div>
                                                                    <div class="m-list-timeline__item">
                                                                        <span class="m-list-timeline__badge m-list-timeline__badge--state1-success"></span>
                                                                        <a href="" class="m-list-timeline__text">
                                                                            Production server up
                                                                        </a>
                                                                        <span class="m-list-timeline__time">
																						5 hrs
																					</span>
                                                                    </div>
                                                                    <div class="m-list-timeline__item">
                                                                        <span class="m-list-timeline__badge m-list-timeline__badge--state1-info"></span>
                                                                        <a href="" class="m-list-timeline__text">
                                                                            New order received
                                                                        </a>
                                                                        <span class="m-list-timeline__time">
																						7 hrs
																					</span>
                                                                    </div>
                                                                    <div class="m-list-timeline__item">
                                                                        <span class="m-list-timeline__badge m-list-timeline__badge--state1-info"></span>
                                                                        <a href="" class="m-list-timeline__text">
                                                                            System shutdown
                                                                        </a>
                                                                        <span class="m-list-timeline__time">
																						11 mins
																					</span>
                                                                    </div>
                                                                    <div class="m-list-timeline__item">
                                                                        <span class="m-list-timeline__badge m-list-timeline__badge--state1-info"></span>
                                                                        <a href="" class="m-list-timeline__text">
                                                                            Production server down
                                                                        </a>
                                                                        <span class="m-list-timeline__time">
																						3 hrs
																					</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane" id="topbar_notifications_logs"
                                                         role="tabpanel">
                                                        <div class="m-stack m-stack--ver m-stack--general"
                                                             style="min-height: 180px;">
                                                            <div class="m-stack__item m-stack__item--center m-stack__item--middle">
																			<span class="">
																				All caught up!
																				<br>
																				No new logs.
																			</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img  m-dropdown m-dropdown--medium m-dropdown--arrow m-dropdown--header-bg-fill m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light"
                                data-dropdown-toggle="click">
                                <a href="#" class="m-nav__link m-dropdown__toggle">
												<span class="m-topbar__userpic">
                                                    @if(Auth::user()->staff_avatar!=null)
                                                        <img src=" /{{ Auth::user()->staff_avatar }} "
                                                             class="m--img-rounded m--marginless m--img-centered"
                                                             style="height: 30px;width: 30px;"/>
                                                    @else
                                                        <img src=" {{asset('static/backend/images/image-user.png')}} "
                                                             class="m--img-rounded m--marginless m--img-centered"
                                                             style="height: 30px;width: 30px;"/>
                                                    @endif

												</span>
                                    <span class="m-topbar__username m--padding-left-10">
                                        {{ Auth::user()->full_name }}
												</span>
                                    <i class="m-menu__hor-arrow la la-angle-down ic-us"></i>
                                </a>
                                <div class="m-dropdown__wrapper">
                                    <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                                    <div class="m-dropdown__inner">
                                        <div class="m-dropdown__header m--align-center"
                                             style="background: url(../../assets/app/media/img/misc/user_profile_bg.jpg); background-size: cover;">
                                            <div class="m-card-user m-card-user--skin-dark">
                                                <div class="m-card-user__pic">
                                                    @if(Auth::user()->staff_avatar!=null)
                                                        <img src=" /{{ Auth::user()->staff_avatar }} "
                                                             class="m--img-rounded m--marginless m--img-centered"
                                                             style="height: 30px"/>
                                                    @else
                                                        <img src=" https://icons-for-free.com/free-icons/png/512/1216577.png "
                                                             class="m--img-rounded m--marginless m--img-centered"
                                                             style="height: 30px"/>
                                                    @endif
                                                </div>
                                                <div class="m-card-user__details">
																<span class="m-card-user__name m--font-weight-500">
																	 {{ Auth::user()->full_name }}
																</span>
                                                    {{--<a href="" class="m-card-user__email m--font-weight-300 m-link">--}}
                                                    {{--mark.andre@gmail.com--}}
                                                    {{--</a>--}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="m-dropdown__body">
                                            <div class="m-dropdown__content">
                                                <ul class="m-nav m-nav--skin-light">
                                                    {{--<li class="m-nav__section m--hide">--}}
                                                    {{--<span class="m-nav__section-text">--}}
                                                    {{--Section--}}
                                                    {{--</span>--}}
                                                    {{--</li>--}}
                                                    <li class="m-nav__item">
                                                        <a href="{{route('admin.staff.profile',[\Illuminate\Support\Facades\Auth::id()])}}"
                                                           class="m-nav__link">
                                                            <i class="m-nav__link-icon flaticon-profile-1"></i>
                                                            <span class="m-nav__link-title">
																			<span class="m-nav__link-wrap">
																				<span class="m-nav__link-text">
																					Cá nhân
																				</span>
                                                                                {{--<span class="m-nav__link-badge">--}}
                                                                                {{--<span class="m-badge m-badge--success">--}}
                                                                                {{--2--}}
                                                                                {{--</span>--}}
                                                                                {{--</span>--}}
																			</span>
																		</span>
                                                        </a>
                                                    </li>
                                                    <li class="m-nav__item">
                                                        <a href="../../header/profile.html" class="m-nav__link">
                                                            <i class="m-nav__link-icon flaticon-share"></i>
                                                            <span class="m-nav__link-text">
																			Báo cáo lương
																		</span>
                                                        </a>
                                                    </li>
                                                    {{--<li class="m-nav__item">--}}
                                                    {{--<a href="../../header/profile.html" class="m-nav__link">--}}
                                                    {{--<i class="m-nav__link-icon flaticon-chat-1"></i>--}}
                                                    {{--<span class="m-nav__link-text">--}}
                                                    {{--Messages--}}
                                                    {{--</span>--}}
                                                    {{--</a>--}}
                                                    {{--</li>--}}
                                                    {{--<li class="m-nav__separator m-nav__separator--fit"></li>--}}
                                                    {{--<li class="m-nav__item">--}}
                                                    {{--<a href="../../header/profile.html" class="m-nav__link">--}}
                                                    {{--<i class="m-nav__link-icon flaticon-info"></i>--}}
                                                    {{--<span class="m-nav__link-text">--}}
                                                    {{--FAQ--}}
                                                    {{--</span>--}}
                                                    {{--</a>--}}
                                                    {{--</li>--}}
                                                    {{--<li class="m-nav__item">--}}
                                                    {{--<a href="../../header/profile.html" class="m-nav__link">--}}
                                                    {{--<i class="m-nav__link-icon flaticon-lifebuoy"></i>--}}
                                                    {{--<span class="m-nav__link-text">--}}
                                                    {{--Support--}}
                                                    {{--</span>--}}
                                                    {{--</a>--}}
                                                    {{--</li>--}}
                                                    <li class="m-nav__separator m-nav__separator--fit"></li>
                                                    <li class="m-nav__item">
                                                        <a href="{{route('logout')}}"
                                                           class="btn m-btn--pill    btn-secondary m-btn m-btn--custom m-btn--label-brand m-btn--bolder">
                                                            Đăng xuất
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>

                        </ul>
                    </div>
                </div>

                <!-- END: Topbar -->
            </div>
        </div>
    </div>
</header>
{{--a--}}