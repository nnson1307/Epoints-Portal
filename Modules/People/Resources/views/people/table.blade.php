<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table ss--nowrap">
        <thead class="text-uppercase">
        <tr class="ss--first-uppercase">
            <th class="ss--font-size-th">
                <label class="m-checkbox m-checkbox--bold m-checkbox--state-success m--padding-top-5">
                    <input class="check_shift" type="checkbox"
                           onclick="index.chooseAll(this)">
                    <span></span>
                </label>
            </th>
            <th class="ss--font-size-th">#</th>
            <th class="ss--font-size-th">{{__('Tên công dân')}}</th>
            <th class="ss--font-size-th">{{__('Ngày sinh')}}</th>
            <th class="ss--font-size-th">{{__('CMND/CCCD')}}</th>
            <th class="ss--font-size-th">{{__('Địa chỉ tạm trú')}}</th>
            <th class="ss--font-size-th">{{__('Kết quả phúc tra gần nhất')}}</th>
            <th class="ss--font-size-th">{{__('Hành động')}}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if (isset($list))
            @foreach ($list as $key=>$item)
                @include('People::people.item')
            @endforeach
        @endif
        </tbody>
    </table>
</div>
@isset($list)
    <div class="laravel-paginator" target=".ajax-people-list-form">
        {{ $list->links('helpers.paging') }}
    </div>
@endisset