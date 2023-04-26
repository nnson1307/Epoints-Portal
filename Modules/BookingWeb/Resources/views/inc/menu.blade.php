<div class="col-lg-3 div_menu">
    <div class="menu">
        <p class="child_menu {{request()->route()->action['group-menu'] == 'booking' ? "active_child_menu" : ""}}">
            <a href="{{route('booking')}}">
                <i class="la la-calendar"></i><span class="text_menu">&nbsp;{{__('Đặt lịch giữ chổ')}}</span>
            </a>
        </p>
        @if(isset(request()->route()->action['group-menu']))
            <p class="child_menu {{request()->route()->action['group-menu'][1] == 'introducion' ? "active_child_menu" : ""}}" >
                <a href="{{route('introducion')}}" ><i class="la la-pagelines"></i><span class="text_menu">{{__('Giới thiệu')}}</span></a>
            </p>
            <p class="child_menu {{request()->route()->action['group-menu'][1] == 'service' ? "active_child_menu" : ""}}">
                <a href="{{route('service')}}" ><i class="la la-pagelines"></i><span class="text_menu">&nbsp;{{__('Dịch vụ')}}</span></a>
            </p>
            <p class="child_menu {{request()->route()->action['group-menu'][1] == 'product' ? "active_child_menu" : ""}}">
                <a href="{{route('product')}}" ><i class="la la-cubes"></i><span class="text_menu">&nbsp;{{__('Sản phẩm')}}</span></a>
            </p>
            <p class="child_menu {{request()->route()->action['group-menu'][1] == 'brand' ? "active_child_menu" : ""}}">
                <a href="{{route('brand')}}" > <i class="la la-university"></i><span class="text_menu">&nbsp;{{__('Chi nhánh')}}</span></a>
            </p>
{{--            <p class="child_menu">--}}
{{--                <i class="la la-phone"></i><span class="text_menu">&nbsp;{{__('Liên hệ')}}</span>--}}
{{--            </p>--}}
        @else
            <p class="child_menu" >
                <a href="{{route('introducion')}}" ><i class="la la-pagelines"></i><span class="text_menu">{{__('Giới thiệu')}}</span></a>
            </p>
            <p class="child_menu ">
                <a href="{{route('service')}}" ><i class="la la-pagelines"></i><span class="text_menu">&nbsp;{{__('Dịch vụ')}}</span></a>
            </p>
            <p class="child_menu ">
                <a href="{{route('product')}}" ><i class="la la-cubes"></i><span class="text_menu">&nbsp;{{__('Sản phẩm')}}</span></a>
            </p>
            <p class="child_menu ">
                <a href="{{route('brand')}}" > <i class="la la-university"></i><span class="text_menu">&nbsp;{{__('Chi nhánh')}}</span></a>
            </p>
{{--            <p class="child_menu">--}}
{{--                <i class="la la-phone"></i><span class="text_menu">&nbsp;{{__('Liên hệ')}}</span>--}}
{{--            </p>--}}
        @endif
    </div>
</div>
