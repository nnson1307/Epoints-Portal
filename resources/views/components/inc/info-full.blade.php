<div id="m_header_topbar" class="m-topbar m-stack m-stack--ver m-stack--general m-stack--fluid">
    <div class="m-stack__item m-topbar__nav-wrapper">
        <ul class="m-topbar__nav m-nav m-nav--inline">
            {{--<li class="m-nav__item m-topbar__notifications m-topbar__notifications--img m-dropdown m-dropdown--large m-dropdown--header-bg-fill m-dropdown--arrow m-dropdown--align-center m-dropdown--mobile-full-width"--}}
            {{--m-dropdown-toggle="click" m-dropdown-persistent="1" aria-expanded="true">--}}

            {{--<a href="javascript:void(0)" class="m-nav__link m-dropdown__toggle" id="m_topbar_notification_icon"--}}
            {{--onclick="attendances.showModalCheckin()">--}}

            {{--<span class="m-nav__link-icon"><i class="flaticon-logout"></i></span>--}}
            {{--</a>--}}

            {{--</li>--}}
            <li class="m-nav__item m-topbar__notifications m-topbar__notifications--img m-dropdown m-dropdown--large m-dropdown--header-bg-fill m-dropdown--arrow m-dropdown--align-center m-dropdown--mobile-full-width"
                m-dropdown-toggle="click" m-dropdown-persistent="1" aria-expanded="true">
                <div class="notification" style="display: none">
                    <span class="notification-badge" id="number-noti-new">1</span>
                    <input type="hidden" id="number-noti-new_hidden" value="">
                </div>

                <a href="javascript:void(0)" class="m-nav__link m-dropdown__toggle" id="m_topbar_notification_icon"
                   onclick="notification.loadNotification()">
                    {{--                    <span class="m-nav__link-badge m-badge m-badge--dot m-badge--dot-small m-badge--danger"></span>--}}
                    <span class="m-nav__link-icon"><i class="flaticon-alarm"></i></span>
                </a>
                <div class="m-dropdown__wrapper" style="z-index: 101;">
                    <span class="m-dropdown__arrow m-dropdown__arrow--center"></span>
                    <div class="m-dropdown__inner">
                        <div class="m-dropdown__body">
                            <div class="m-dropdown__content">
                                {{--                                <ul class="nav nav-tabs m-tabs m-tabs-line m-tabs-line--brand" role="tablist">--}}
                                {{--                                    <li class="nav-item m-tabs__item">--}}
                                {{--                                        <a class="nav-link m-tabs__link active show" data-toggle="tab"--}}
                                {{--                                           href="#m_widget2_tab1_content" role="tab"--}}
                                {{--                                           aria-selected="true">@lang('Thông báo')</a>--}}
                                {{--                                    </li>--}}
                                {{--                                </ul>--}}
                                {{--                                <div class="tab-content">--}}
                                {{--                                    <div class="tab-pane active show" id="m_widget2_tab1_content">--}}
                                <div class="m-scrollable m-scroller ps" data-scrollable="true" data-height="250"
                                     data-mobile-height="200" style="height: 250px; overflow: hidden;"
                                     id="scroll-notify">
                                    <!--Begin::Timeline 3 -->
                                    <div class="m-timeline-3">
                                        <div class="m-timeline-3__items" id="list-notify">

                                        </div>
                                    </div>
                                </div>

                                <!--End::Timeline 3 -->
                                {{--                                    </div>--}}
                                {{--                                    <div class="tab-pane" id="topbar_notifications_events" role="tabpanel">--}}
                                {{--                                        <div class="m-scrollable m-scroller ps" data-scrollable="true" data-height="250"--}}
                                {{--                                             data-mobile-height="200" style="height: 250px; overflow: hidden;"--}}
                                {{--                                             id="scroll-notify">--}}
                                {{--                                            <div class="m-list-timeline m-list-timeline--skin-light">--}}
                                {{--                                                <div class="m-list-timeline__items" id="list-notify">--}}
                                {{--                                                    --}}
                                {{--                                                </div>--}}
                                {{--                                                --}}
                                {{--                                            </div>--}}
                                {{--                                            <div class="ps__rail-x" style="left: 0px; bottom: 0px;">--}}
                                {{--                                                <div class="ps__thumb-x" tabindex="0"--}}
                                {{--                                                     style="left: 0px; width: 0px;"></div>--}}
                                {{--                                            </div>--}}
                                {{--                                            <div class="ps__rail-y" style="top: 0px; right: 4px;">--}}
                                {{--                                                <div class="ps__thumb-y" tabindex="0"--}}
                                {{--                                                     style="top: 0px; height: 0px;"></div>--}}
                                {{--                                            </div>--}}
                                {{--                                        </div>--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            <li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img m-dropdown m-dropdown--medium m-dropdown--arrow m-dropdown--header-bg-fill m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light"
                m-dropdown-toggle="click">
                <a href="#" class="m-nav__link m-dropdown__toggle">
                    <span class="m-topbar__userpic">
                        @if(Auth::user()->staff_avatar != null)
                            <img src="{{asset(Auth::user()->staff_avatar)}}" height="40px" width="40px"
                                 class="m--img-rounded m--marginless m--img-rounded-7" alt=""/>
                        @else
                            <img src="{{asset('static/backend/images/menu/icon-admin.png')}}"
                                 class="m--marginless img-fluid-40" alt=""/>
                        @endif
                    </span>
                    <span class="m-topbar__username m--padding-right-5 m--padding-left-5"> {{ Auth::user()->full_name }}
                        <i class="m--padding-left-5 m-menu__hor-arrow la la-angle-down"></i></span>
                </a>
                <div class="m-dropdown__wrapper">
                    <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                    <div class="m-dropdown__inner">
                        <div class="m-dropdown__body">
                            <div class="m-dropdown__content">
                                <ul class="m-nav m-nav--skin-light">
                                    <li class="m-nav__section m--hide">
                                        <span class="m-nav__section-text">Section</span>
                                    </li>
                                    <li class="m-nav__item">
                                        <a href="{{route('admin.staff.profile',[Auth::id()])}}" class="m-nav__link">
                                            <i class="m-nav__link-icon flaticon-profile-1"></i>
                                            <span class="m-nav__link-title">
                                                <span class="m-nav__link-wrap">
                                                    <span class="m-nav__link-text">@lang('Thông tin cá nhân')</span>
                                                </span>
                                            </span>
                                        </a>
                                    </li>
                                    @if(in_array('timeofftype.index',session('routeList')))
                                        <li class="m-nav__item">
                                            <a href="{{route('timeoffdays.mylist')}}" class="m-nav__link">
                                                <i class="m-nav__link-icon flaticon-interface-10"></i>
                                                <span class="m-nav__link-title">
                                                <span class="m-nav__link-wrap">
                                                    <span class="m-nav__link-text">@lang('Đơn phép của tôi')</span>
                                                </span>
                                            </span>
                                            </a>
                                        </li>
                                    @endif
                                    {{--
                                    <li class="m-nav__item">
                                        --}} {{--
                                        <a href="{{route('admin.staff.profile',[Auth::id()])}}" class="m-nav__link">
                                    --}} {{-- <i class="m-nav__link-icon flaticon-share"></i>--}}
                                    {{-- <span class="m-nav__link-text">@lang('Lich làm việc')</span>--}} {{--
                                        </a>
                                        --}} {{--
                                    </li>
                                    --}} {{--
                                    <li class="m-nav__item">
                                        --}} {{--
                                        <a href="{{route('admin.staff.profile',[Auth::id()])}}" class="m-nav__link">
                                    --}} {{-- <i class="m-nav__link-icon flaticon-chat-1"></i>--}}
                                    {{-- <span class="m-nav__link-text">@lang('Thông báo')</span>--}} {{--
                                        </a>
                                        --}} {{--
                                    </li>
                                    --}}
                                    <li class="m-nav__separator m-nav__separator--fit"></li>
                                    {{--
                                    <li class="m-nav__item">
                                        --}} {{--
                                        <a href="{{route('admin.staff.profile',[Auth::id()])}}"
                                    class="m-nav__link">--}} {{-- <i class="m-nav__link-icon flaticon-info"></i>--}}
                                    {{-- <span class="m-nav__link-text">FAQ</span>--}} {{-- </a>
                                        --}} {{--
                                    </li>
                                    --}} {{--
                                    <li class="m-nav__item">
                                        --}} {{--
                                        <a href="{{route('admin.staff.profile',[Auth::id()])}}" class="m-nav__link">
                                    --}} {{-- <i class="m-nav__link-icon flaticon-lifebuoy"></i>--}}
                                    {{-- <span class="m-nav__link-text">Support</span>--}} {{--
                                        </a>
                                        --}} {{--
                                    </li>
                                    --}}
                                    <li class="m-nav__separator m-nav__separator--fit"></li>
                                    <li class="m-nav__item">
                                        <a href="{{route('logout')}}"
                                           class="btn m-btn--pill btn-secondary m-btn m-btn--custom m-btn--label-brand m-btn--bolder">
                                            @lang('Đăng xuất')
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
