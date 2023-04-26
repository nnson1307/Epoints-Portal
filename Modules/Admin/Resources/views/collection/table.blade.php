<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table ss--nowrap">
        <thead class="text-uppercase">
        <tr class="ss--first-uppercase">

            <th class="ss--font-size-th">#</th>
            <th class="ss--font-size-th">{{__('Image web')}}</th>
            <th class="ss--font-size-th">{{__('Image app')}}</th>
            <th class="ss--font-size-th">{{__('Source')}}</th>
            <th class="ss--font-size-th">{{__('url')}}</th>
            <th class="ss--font-size-th">{{__('Thời gian')}}</th>
            <th class="ss--font-size-th">{{__('Hành động')}}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if (isset($list))
            @foreach ($list as $key=>$item)
                @include('admin::collection.item')
            @endforeach
        @endif
        </tbody>
    </table>
</div>
@isset($list)
    <div class="laravel-paginator" target=".ajax-collection-list-form">
        {{ $list->links('helpers.paging') }}
    </div>
@endisset