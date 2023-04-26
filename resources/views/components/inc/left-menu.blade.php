<!-- BEGIN: Left Aside -->
<button class="m-aside-left-close m-aside-left-close--skin-dark" id="m_aside_left_close_btn">
    <i class="la la-close"></i>
</button>
<div id="m_aside_left" class="m-grid__item m-aside-left m-aside-left--skin-light">
    <!-- BEGIN: Aside Menu -->
    <div id="m_ver_menu" class="m-aside-menu m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark " style="position: relative;">
        <ul class="m-menu__nav m-menu__nav--dropdown-submenu-arrow m-scroller">
            @if(in_array('call-center.list',session('routeList')))
            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true">
                <a href="javascript:void(0)" onclick="callCenter.showModalSearchCustomer()" class="m-menu__link" >
                    <div class="menu-icon m-menu__link-icon position-relative">
                        <object  type="image/svg+xml" style="pointer-events: none;"
                                 data="{{asset('static/backend/images/menu/svg/icon-callcenter.svg')}}"
                                 class="icon-menu icon-arrow" >
                        </object>
                        <div class="fake-tooltip position-absolute ">{{__('Tiếp nhận yêu cầu')}}</div>
                    </div>
                </a>
            </li>
            @endif
            
            @foreach($key = session('menuVertical') as $k => $item)
                @if(in_array($item['admin_menu_route'],session('routeList')))
                    <li class="m-menu__item m-menu__item--submenu {{str_replace('.', '_', $item['admin_menu_route'])}}" aria-haspopup="true">
                        <a href="{{route($item['admin_menu_route'])}}" class="m-menu__link">
                            <div class="menu-icon m-menu__link-icon position-relative">
                                <object type="image/svg+xml" style="pointer-events: none;"
                                        data="{{asset($item['admin_menu_icon'].'.svg')}}"
                                        class="icon-menu icon-arrow">
                                </object>
                                <div class="fake-tooltip position-absolute ">{{__($item['admin_menu_name'])}}</div>
                                @if(str_replace('.', '_', $item['admin_menu_route']) === 'chathub_chat')
                                    <span class="noti-chat" style="display:none">
                                        0
                                    </span>
                                @endif
                                @if(str_replace('.', '_', $item['admin_menu_route']) === 'chathub_inbox')
                                    <span class="noti-chathub" style="display:none">
                                        0
                                    </span>
                                @endif
                            </div>
                        </a>
                    </li>
                @endif
            @endforeach
            <li class="m-menu__item m-menu__item--submenu {{str_replace('.', '_', $item['admin_menu_route'])}}" aria-haspopup="true">
                <a  href="{{route('admin.menu-all')}}" class="m-menu__link" >
                    <div class="menu-icon m-menu__link-icon position-relative">
                        <object  type="image/svg+xml" style="pointer-events: none;"
                                 data="{{asset('static/backend/images/menu/svg/nav.svg')}}"
                                 class="icon-menu icon-arrow" >
                        </object>
                        <div class="fake-tooltip position-absolute ">{{__('Tất cả chức năng')}}</div>
                    </div>
                </a>
            </li>
        </ul>
        {{-- <div class="mx-auto text-center nt-nav">
            <a  href="{{route('admin.menu-all')}}" class="m-menu__link1" >
                <div class="menu-icon m-menu__link-icon">
                    <object  type="image/svg+xml" style="pointer-events: none;"
                             data="{{asset('static/backend/images/menu/svg/nav.svg')}}"
                             class="icon icon-arrow" >
                    </object>
                </div>
            </a>
        </div> --}}
    </div>
    <!-- END: Aside Menu -->

    @include('components.inc.mobile-menu')
</div>
<style>
.icon-menu {

    height: 24px;

}
.chathub_chat .noti-chat {
    color: #fff;
    font-weight: bold;
    font-size: 10px;
    position: absolute;
    background-color: red;
    height: 20px;
    width: 20px;
    border-radius: 20px;
    line-height: 19px;
    top: 0px;
    right: -5px;
}

.chathub_inbox .noti-chathub {
    color: #fff;
    font-weight: bold;
    font-size: 10px;
    position: absolute;
    background-color: red;
    height: 20px;
    width: 20px;
    border-radius: 20px;
    line-height: 19px;
    top: 0px;
    right: -5px;
}
</style>
<script type="text/javascript">
    var appBanners = document.getElementsByClassName("hidecate");
    for (var i = 0; i < appBanners.length; i++) {
        // appBanners[i].style.display = 'none';
        const elem = document.getElementsByClassName(appBanners[i].value);
        while (elem.length > 0) elem[0].remove();
    }
</script>
<!-- END: Left Aside aa-->
