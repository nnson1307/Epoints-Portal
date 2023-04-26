<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">@lang('STT')</th>
            <th class="tr_thead_list">@lang('TÊN LOẠI CHI PHÍ PHÁT SINH (TIẾNG VIỆT)')</th>
            <th class="tr_thead_list">@lang('TÊN LOẠI CHI PHÍ PHÁT SINH (TIẾNG ANH)')</th>
            <th class="tr_thead_list">@lang('TRẠNG THÁI')</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @php $stt=1; @endphp
            @foreach ($LIST as $key => $item)
                <tr>
                    <td>{{$stt++}}</td>
                    <td>{{$item['maintenance_cost_type_name_vi']}}</td>
                    <td>{{$item['maintenance_cost_type_name_en']}}</td>
                    <td>
                        @if($item['is_active']=='1')
                            <span class="m-badge m-badge--success" style="width: 60%">@lang('Đang hoạt động')</span>
                        @else
                            <span class="m-badge m-badge--danger m-badge--wide"
                                  style="width: 60%">@lang('Vô hiệu hoá')</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{route("maintenance-cost-type.edit",$item["maintenance_cost_type_id"])}}"
                                class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                title="{{__('Sửa')}}"
                                id="edit1">
                            <i class="la la-edit"></i>
                        </a>
                        <button onclick="maintenanceCostType.remove(this, {{$item['maintenance_cost_type_id']}})"
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
