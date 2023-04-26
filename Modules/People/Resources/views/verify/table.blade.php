<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table ss--nowrap">
        <thead class="text-uppercase">
        <tr class="ss--first-uppercase">
            <th class="ss--font-size-th">#</th>
            <th class="ss--font-size-th">{{__('Đợt phúc tra')}}</th>
            <th class="ss--font-size-th">{{__('Kết quả')}}</th>
            <th class="ss--font-size-th">{{__('Lý do cụ thể')}}</th>
            <th class="ss--font-size-th">{{__('Sức khỏe loại')}}</th>
            <th class="ss--font-size-th">{{__('Ghi chú')}}</th>
            <th class="ss--font-size-th">{{__('Hành động')}}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if (isset($list))
            @foreach ($list as $key=>$item)
                @include('People::verify.item')
            @endforeach
        @endif
        </tbody>
    </table>
</div>
@isset($list)
    <div class="people-verify-paginator" target=".ajax-people-verify-list-form">
        {{ $list->links('helpers.paging') }}
    </div>
@endisset