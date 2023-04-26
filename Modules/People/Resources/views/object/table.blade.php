<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table ss--nowrap">
        <thead>
        <tr class="ss--first-uppercase text-uppercase">
            <th class="ss--font-size-th">#</th>
            <th class="ss--font-size-th">{{__('Mã đối tượng')}}</th>
            <th class="ss--font-size-th">{{__('Tên đối tượng')}}</th>
            <th class="ss--font-size-th">{{__('Người tạo')}}</th>
            <th class="ss--font-size-th">{{__('Thời gian tạo')}}</th>
            <th class="ss--font-size-th">{{__('Trạng thái')}}</th>
            <th class="ss--font-size-th">{{__('Hành động')}}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if (isset($list))
            @foreach ($list as $key=>$item)
                @include('People::object.item')
            @endforeach
        @endif
        </tbody>
    </table>
</div>
@isset($list)
    <div class="laravel-paginator" target=".ajax-people-object-list-form">
        {{ $list->links('helpers.paging') }}
    </div>
@endisset