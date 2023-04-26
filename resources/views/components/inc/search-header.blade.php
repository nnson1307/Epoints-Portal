
<a href="#" class="m-nav__link m-dropdown__toggle">
    <form class="m-list-search__form" id="form-search" method="GET" action="{{route('admin.layout.search-result')}}">
        {{csrf_field()}}
        <div class="form-group m-form__group ">
            <div class="m-input-icon m-input-icon--left">
                <span class="">
                        <input id="m_quicksearch_input" autocomplete="off"
                               type="text" name="keyword" class="form-control m-input--pill m-input"
                               value="" placeholder="{{__('Nhập thông tin tìm kiếm')}}">
                </span>
                <span class="m-input-icon__icon m-input-icon__icon--left"><span><i class="la la-search"></i></span></span>
            </div>
        </div>
    </form>
</a>
<div class="m-dropdown__wrapper">
    <span class="m-dropdown__arrow m-dropdown__arrow--center"></span>
    <div class="m-dropdown__inner">

        <div class="m-dropdown__header">

            <div class="m-list-search__form">
                <div class="m-list-search__form-wrapper">
                        <span class="m-list-search__form-input-wrapper">
                            @lang('Thông tin tìm kiếm')
                        </span>
                    <span class="m-list-search__form-icon-close" id="m_quicksearch_close">
                        <i class="la la-remove"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="m-dropdown__body">
            <div class="m-dropdown__scrollable m-scrollable" data-scrollable="true" data-height="300" data-mobile-height="200">
                <div class="m-dropdown__content">

                </div>
            </div>
        </div>
        {{--<div class="m-dropdown__footer">--}}
            {{--Xem chi tiết--}}
        {{--</div>--}}
    </div>
</div>
<form id="form-search-hhidden"
      action="{{route('admin.layout.search.detail-search')}}"
      method="GET">                                        {{csrf_field()}}
    <input type="hidden" name="idSearchDashboard" id="idSearchDashboard" value="">
    <input type="hidden" name="nameSearchDashboard" id="nameSearchDashboard"
           value="">
</form>
{{--a--}}