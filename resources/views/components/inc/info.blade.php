<div id="m_header_topbar" class="m-topbar m-stack m-stack--ver m-stack--general m-stack--fluid">
    <div class="m-stack__item m-topbar__nav-wrapper">
        <ul class="m-topbar__nav m-nav m-nav--inline">
            <li
                    class="m-nav__item m-dropdown m-dropdown--large m-dropdown--arrow m-dropdown--align-center m-dropdown--mobile-full-width m-dropdown--skin-light m-list-search m-list-search--skin-light"
                    m-dropdown-toggle="click"
                    id="m_quicksearch"
                    m-quicksearch-mode="dropdown"
                    m-dropdown-persistent="1"
            >
                @include('components.inc.search-header')
            </li>
            <li
                    class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img m-dropdown m-dropdown--medium m-dropdown--arrow m-dropdown--header-bg-fill m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light"
                    m-dropdown-toggle="click"
            >
                <a href="#" class="m-nav__link m-dropdown__toggle">
                    <span class="m-topbar__userpic">
                        @if(Auth::user()->staff_avatar != null)
                            <img src="{{asset(Auth::user()->staff_avatar)}}" height="40px" width="40px" class="m--img-rounded m--marginless m--img-rounded-7" alt="" />
                        @else
                            <img src="{{asset('static/backend/images/menu/icon-admin.png')}}" class="m--marginless img-fluid-40" alt="" />
                        @endif
                    </span>
                    <span class="m-topbar__username m--padding-right-5 m--padding-left-5"> {{ Auth::user()->full_name }} <i class="m--padding-left-5 m-menu__hor-arrow la la-angle-down"></i></span>
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
                                    {{--
                                    <li class="m-nav__item">
                                        --}} {{--
                                        <a href="{{route('admin.staff.profile',[Auth::id()])}}" class="m-nav__link">
                                            --}} {{-- <i class="m-nav__link-icon flaticon-share"></i>--}} {{-- <span class="m-nav__link-text">@lang('Lich làm việc')</span>--}} {{--
                                        </a>
                                        --}} {{--
                                    </li>
                                    --}} {{--
                                    <li class="m-nav__item">
                                        --}} {{--
                                        <a href="{{route('admin.staff.profile',[Auth::id()])}}" class="m-nav__link">
                                            --}} {{-- <i class="m-nav__link-icon flaticon-chat-1"></i>--}} {{-- <span class="m-nav__link-text">@lang('Thông báo')</span>--}} {{--
                                        </a>
                                        --}} {{--
                                    </li>
                                    --}}
                                    <li class="m-nav__separator m-nav__separator--fit"></li>
                                    {{--
                                    <li class="m-nav__item">
                                        --}} {{--
                                        <a href="{{route('admin.staff.profile',[Auth::id()])}}" class="m-nav__link">--}} {{-- <i class="m-nav__link-icon flaticon-info"></i>--}} {{-- <span class="m-nav__link-text">FAQ</span>--}} {{-- </a>
                                        --}} {{--
                                    </li>
                                    --}} {{--
                                    <li class="m-nav__item">
                                        --}} {{--
                                        <a href="{{route('admin.staff.profile',[Auth::id()])}}" class="m-nav__link">
                                            --}} {{-- <i class="m-nav__link-icon flaticon-lifebuoy"></i>--}} {{-- <span class="m-nav__link-text">Support</span>--}} {{--
                                        </a>
                                        --}} {{--
                                    </li>
                                    --}}
                                    <li class="m-nav__separator m-nav__separator--fit"></li>
                                    <li class="m-nav__item">
                                        <a href="{{route('logout')}}" class="btn m-btn--pill btn-secondary m-btn m-btn--custom m-btn--label-brand m-btn--bolder">
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
