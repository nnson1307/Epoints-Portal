@if(count($data) > 0)
    @foreach($data as $menuCategory)
        @if(count($menuCategory['menu']) > 0)
            <div class="row all-menu">
                <div class="col-lg-12">
                    <div class="m-portlet m-portlet--head-sm">
                        <div class="m-portlet__head">
                            <div class="m-portlet__head-caption">
                                <div class="m-portlet__head-title">
                                    <h3 class="m-portlet__head-text pt-3">
                                        <strong>{{__($menuCategory['menu_category_name'])}}</strong>
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <div class="m-portlet__body">
                            <div class="row mx-auto text-center">
                                @foreach($menuCategory['menu'] as $item)
                                    <div class="col-md-2 col-sm-4 col-6 pt-3 pb-3 mt-3 mb-3">
                                        <a href="{{route($item['admin_menu_route'])}}" class="nt-icon-menu">
                                            <div class="icon-logo">
{{--                                                <object type="image/svg+xml" style="pointer-events: none;"--}}
{{--                                                        data="{{asset(trim($item['admin_menu_icon']).'.svg')}}"--}}
{{--                                                        class="icon-arrow">--}}
{{--                                                </object>--}}
                                                <img loading="lazy" src="{{asset(trim($item['admin_menu_icon']).'.svg')}}">
                                                <h5 class="name-menu p-2">
                                                    @if(app()->getLocale() == 'vi')
                                                        {{__($item['admin_menu_name_vi'])}}
                                                    @elseif (app()->getLocale() == 'en')
                                                        {{__($item['admin_menu_name_en'])}}
                                                    @else
                                                        {{__($item['admin_menu_name'])}}
                                                    @endif
                                                    
                                                </h5>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endif