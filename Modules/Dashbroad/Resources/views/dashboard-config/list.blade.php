<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">{{__('Tiêu đề')}}</th>
            <th class="tr_thead_list">{{__('Ngày tạo')}}</th>
            <th class="tr_thead_list">{{__('Người tạo')}}</th>
            <th class="tr_thead_list">{{__('Trạng thái')}}</th>
            <th class="tr_thead_list">{{__('Hành động')}}</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                <tr>
                    @if(isset($page))
                        <td>{{ ($page-1)*10 + $key+1}}</td>
                    @else
                        <td>{{$key+1}}</td>
                    @endif
                        <td>
                            <a href="{{route('dashbroad.dashboard-config.detail', array ('id'=>$item['dashboard_id']))}}"
                                    target="_blank"
                                    class="ss--text-black"
                                    title="{{__('Chi tiết')}}">{{$item['name']}}
                            </a>
                        </td>
                        <td>{{\Carbon\Carbon::parse($item['created_at'])->format('d/m/Y H:i')}}</td>
                        <td>{{$item['full_name']}}</td>
                        <td>
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label>
                                    <input type="checkbox" id="is_actived" name="is_actived" onchange="dashboardConfig.changeStatus({{$item['dashboard_id']}}, this)" {{$item['is_actived'] == 1 ? 'checked' : ''}}>
                                    <span></span>
                                </label>
                            </span>
                        </td>
                        <td>
                            <a href="{{route('dashbroad.dashboard-config.edit', array ('id'=>$item['dashboard_id']))}}"
                                    class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                    title="{{__('Sửa')}}"
                                    id="edit1">
                                <i class="la la-edit"></i>
                            </a>
                            <button onclick="dashboardConfig.remove(this, {{$item['dashboard_id']}})"
                                    class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                    title="{{__('Xoá')}}">
                                <i class="la la-trash"></i>
                            </button>
                        </td>
                </tr>
            @endforeach
        @endif
        </tbody>

    </table>
</div>
{{ $LIST->links('helpers.paging') }}
