<nav id="left-nav" style="display:none">
    <ul>
    @foreach($key = session('menuVertical') ?? [] as $k => $item)
        @if(in_array($item['admin_menu_route'],session('routeList')))
            <li>
                <a href="{{route($item['admin_menu_route'])}}">
                    <object type="image/svg+xml" style="pointer-events: none;"
                            data="{{asset($item['admin_menu_icon'].'.svg')}}"
                            class="icon icon-arrow">
                    </object>
                    <span class="hc-text-mnt">{{__($item['admin_menu_name'])}}</span>
                </a>
            </li>
        @endif
    @endforeach
        <li class="ddt-all-mn">
            <a href="{{route('admin.menu-all')}}" class="all-menu">&nbsp;</a>
        </li>
    </ul>
</nav>

<nav id="main-nav" style="display:none">
    <ul>
        @if(in_array('dashbroad',session('routeList') ?? []))
            <li>
                <a href="{{route('dashbroad')}}"> {{__('Trang chủ')}} </a>
            </li>
        @endif
        @foreach($key = session('menuHorizontal') ?? [] as $k => $item)
            @if ($item['menu'] != null && count($item['menu']) > 0)
                <li>
                    <a href="#">
                        {{__($item['menu_category_name'])}}
                    </a>
                    <ul>
                        @foreach($item['menu'] as $menu)
                            @if(in_array($menu['admin_menu_route'],session('routeList')))
                                <li>
                                    <a href="{{route($menu['admin_menu_route'])}}">
                                        <object type="image/svg+xml"
                                                data="{{asset($menu['admin_menu_icon'].'.svg')}}"
                                                class="icon icon-arrow">
                                        </object>
                                        <span class="hc-text-mnt">{{__($menu['admin_menu_name'])}}</span>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </li>
            @endif
        @endforeach
    </ul>
</nav>

<nav id="info-nav" style="display:none">
    <ul>
        <li>
            <a href="{{route('admin.staff.profile',\Illuminate\Support\Facades\Auth::id())}}">
                <object type="image/svg+xml" style="pointer-events: none;"
                        data="{{asset('static/backend/images/menu/user-icon.png')}}"
                        class="icon icon-arrow">
                </object>
                <span class="hc-text-mnt">{{__('Thông tin cá nhân')}}</span>
            </a>
        </li>
        <li>
            <a href="{{route('logout')}}">
                <object type="image/svg+xml" style="pointer-events: none;"
                        data="{{asset('static/backend/images/menu/logout-icon.png')}}"
                        class="icon icon-arrow">
                </object>
                <span class="hc-text-mnt">{{__('Đăng xuất')}}</span>
            </a>
        </li>
    </ul>
</nav>