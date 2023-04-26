@if (isset($list))
<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table ss--nowrap">
        <thead>
        <tr>
            <th class="ss--font-size-th" style="width:10%">#</th>
            <th class="ss--font-size-th" style="width:15%">{{__('Tên tham số')}}</th>
            <th class="ss--font-size-th" style="width:20%">{{__('Nội dung tham số')}}</th>
            <th class="ss--font-size-th" style="width:30%">{{__('Mô tả')}}</th>
            <th class="ss--text-center ss--font-size-th" style="width:10%">{{__('Hành động')}}</th>
        </tr>
        </thead>
        <tbody>
            @foreach ($list as $key => $item)
                <tr>
                    <td class="ss--font-size-13">
                        {{ isset($params['page']) ? ($params['page']-1)*10 + $key+1 :$key+1 }}
                    </td>
                    <td class="ss--font-size-13">
                        {{ $item->name }}
                    </td>
                    <td class="ss--font-size-13">
                       <div style="max-width:200px;overflow: hidden;">
                        <span class="coppy_button"><i class="fa fa-clone mr-2"></i>{{ $item->value }}</span>
                       </div>
                    </td>
                    <td class="ss--font-size-13">
                        {{ $item->description }}
                    </td>
                    <td class="ss--text-center">
                        <a href="javascript:void(0)" onclick="Params.edit({{$item->params_id}})"
                        class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                        title="{{__('Cập nhật')}}"><i class="la la-edit"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
{{ $list->links('helpers.paging') }}
@endif
