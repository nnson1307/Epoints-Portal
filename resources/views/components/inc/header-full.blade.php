<header id="m_header" class="m-grid__item m-header" m-minimize-offset="200" m-minimize-mobile-offset="200">
    <div class="m-container m-container--fluid m-container--full-height">
        <div class="m-stack m-stack--ver m-stack--desktop m_header_nav_nt">

            <!-- BEGIN: Brand -->
            <div class="m-stack__item m-brand  m-brand--skin-light ">
                <div class="m-stack m-stack--ver m-stack--general">
                    <div class="m-stack__item m-stack__item--middle m-brand__logo icon-header-left">
{{--                        <a href="#" class="m-brand__logo-wrapper icon-header">--}}
{{--                            <img alt="" src="{{isset(config()->get('config.logo')->value) ? config()->get('config.logo')->value : ''}}"/>--}}
{{--                        </a>--}}
                    </div>
                    <div class="m-stack__item m-stack__item--middle m-brand__tools">
                        <a href="javascript:void(0);" id="clickne" class="icon mobile-menu-handle">
                            <i class="fa fa-align-right"></i>
                        </a>

                        <a href="javascript:void(0);" id="toggle-main-nav" class="icon mobile-menu-handle">
                            <i class="fa fa-bars"></i>
                        </a>
                        <!-- BEGIN: Left Aside Minimize Toggle -->
                        <a href="javascript:void(0);" id="info-user-menu"  class="icon mobile-menu-handle icon-menu-info">
                            {{--                            <img src="{{asset('static/backend/images/menu/logo-brand.png')}}"--}}
                            {{--                                 style="background: url({{isset(config()->get('config.logo')->value) ? config()->get('config.logo')->value : asset('static/backend/images/menu/logo.png')}});"--}}
                            {{--                                 class="img-fluid img-fluid-55 nt-logo-brand" >--}}
                            <img src="{{asset('static/backend/images/menu/icon-admin-mobile.png')}}" class="m--marginless " alt="" />
                        </a>
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
                    <ul class="m-menu__nav  m-menu__nav--submenu-arrow list-inline">
                        {{-- <li class="m-menu__item  m-menu__item--submenu m-menu__item--rel list-inline-item"
                            m-menu-submenu-toggle="click" m-menu-link-redirect="1" aria-haspopup="true">
                            <div class="m-title-header">
                                <a class="m-menu__link m-menu__toggle" title="">
                                    <h3 class="m-menu__link-text">@yield('title_header') |</h3>
                                </a>
                            </div>
                        </li> --}}
                        @if(in_array('dashbroad',session('routeList')))
                            <li class="nav-item  nt-dropdown list-inline-item">
                                <a class="nav-link " href="{{route('dashbroad')}}"> {{__('Trang chủ')}} </a>
                            </li>
                        @endif
                        @foreach($key = session('menuHorizontal') as $k => $item)
                            @if ($item['menu'] != null && count($item['menu']) > 0)
                                <li class="nav-item dropdown nt-dropdown list-inline-item">
                                    <a class="nav-link  dropdown-toggle" href="#" data-toggle="dropdown">
                                        {{__($item['menu_category_name'])}} </a>
                                    <ul class="dropdown-menu">
                                        @foreach($item['menu'] as $menu)
                                            @if(in_array($menu['admin_menu_route'],session('routeList')))
                                                <li><a class="dropdown-item"
                                                       href="{{route($menu['admin_menu_route'])}}">
                                                        <object type="image/svg+xml"
                                                                data="{{asset($menu['admin_menu_icon'].'.svg')}}"
                                                                class="icon icon-arrow">
                                                        </object>
                                                        <span>{{__($menu['admin_menu_name'])}}</span></a></li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </li>
                            @endif
                        @endforeach
                    </ul>

                </div>
                <!-- END: Horizontal Menu -->

                <!-- BEGIN: Topbar -->
            @include('components.inc.info-full')
            <!-- END: Topbar -->

            </div>
        </div>
    </div>
</header>
