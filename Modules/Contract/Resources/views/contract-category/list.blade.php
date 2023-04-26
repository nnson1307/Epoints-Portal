<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">{{__('MÃ LOẠI HỢP ĐỒNG')}}</th>
            <th class="tr_thead_list">{{__('TÊN LOẠI HỢP ĐỒNG')}}</th>
            <th class="tr_thead_list">{{__('CẤU HÌNH MÃ HỢP ĐỒNG')}}</th>
            <th class="tr_thead_list">{{__('LOẠI HỢP ĐỒNG')}}</th>
            <th class="tr_thead_list">{{__('Trạng thái')}}</th>
            <th class="tr_thead_list">{{__('NGÀY TẠO')}}</th>
            <th class="tr_thead_list">{{__('TỆP ĐÍNH KÈM')}}</th>
            <th class="tr_thead_list"></th>
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
                        @if(in_array('contract.contract-category.detail', session()->get('routeList')))
                        <a href="{{route("contract.contract-category.detail",[ 'id' => $item['contract_category_id']])}}">
                            {{$item['contract_category_code']}}
                        </a>
                        @else
                            <a href="javascript:void(0)">
                                {{$item['contract_category_code']}}
                            </a>
                        @endif
                        </td>
                    <td>{{$item['contract_category_name']}}</td>
                    <td>{{$item['contract_code_format']}}</td>
                    <td>{{$item['type'] == 'sell' ? 'Bán' : 'Mua'}}</td>
                    <td>
                        @switch($item['is_actived'])
                            @case('1')
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label style="margin: 0 0 0 10px; padding-top: 4px">
                                        <input type="checkbox" onclick="contractCategories.submitChangeStatus(this, '{{$item['contract_category_id']}}', 0)" checked="" class="manager-btn" name="">
                                        <span></span>
                                    </label>
                                </span>
                                @break;
                            @case('0')
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label style="margin: 0 0 0 10px; padding-top: 4px">
                                        <input type="checkbox" onclick="contractCategories.submitChangeStatus(this, '{{$item['contract_category_id']}}', 1)" class="manager-btn" name="">
                                        <span></span>
                                    </label>
                                </span>
                            @break;
                        @endswitch
                    </td>
                    <td>
                        {{date("d/m/Y",strtotime($item['created_at']))}}
                    </td>
                    <td>
                        @if(isset($item['contract_category_link_files']) != '')
                            @foreach(explode(',', $item['contract_category_link_files']) as $key => $value)
                                <a href="{{$value}}"  class="ss--text-black" download="{{explode(',', $item['contract_category_name_files'])[$key]}}">{{explode(',', $item['contract_category_name_files'])[$key]}}</a><br>
{{--                                <a href="{{$value}}"  class="ss--text-black" download="{{explode(',', $item['contract_category_name_files'])[$key]}}">{{$value}}</a><br>--}}
                            @endforeach
                        @else
                            &nbsp;
                        @endif
                    </td>
                    <td>
                        @if(in_array('contract.contract-category.edit', session()->get('routeList')))
                            <a href="{{route("contract.contract-category.edit",[ 'id' => $item['contract_category_id']])}}"
                                    class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                    title="{{__('Sửa')}}">
                                <i class="la la-edit"></i>
                            </a>
                        @endif
                        @if(in_array('contract.contract-category.delete', session()->get('routeList')))
                            <button onclick="contractCategories.remove(this,{{$item['contract_category_id']}})"
                                    class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                    title="{{__('Xoá')}}">
                                <i class="la la-trash"></i>
                            </button>
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>

    </table>
</div>
{{ $LIST->links('helpers.paging') }}
