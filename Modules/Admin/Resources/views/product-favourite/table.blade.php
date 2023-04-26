<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table ss--nowrap">
        <thead class="text-uppercase">
        <tr class="ss--first-uppercase">

            <th class="ss--font-size-th">#</th>
            <th class="ss--font-size-th">{{__('Tên khách hàng')}}</th>
            <th class="ss--font-size-th">{{__('Số điện thoại')}}</th>
            <th class="ss--font-size-th">{{__('Tổng sản phẩm yêu thích')}}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if (isset($list))
            @foreach ($list as $key=>$item)
                @include('admin::product-favourite.item')
            @endforeach
        @endif
        </tbody>
    </table>
</div>
@isset($list)
    <div class="laravel-paginator" target=".ajax-product-favourite-list-form">
        {{ $list->links('helpers.paging') }}
    </div>
@endisset