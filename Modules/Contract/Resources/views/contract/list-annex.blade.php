<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">{{__('HÀNH ĐỘNG')}}</th>
            <th class="tr_thead_list">{{__('MÃ PHỤ LỤC')}}</th>
            <th class="tr_thead_list">{{__('NGÀY KÝ')}}</th>
            <th class="tr_thead_list">{{__('NỘI DUNG')}}</th>
            <th class="tr_thead_list">{{__('NGÀY BẮT ĐẦU')}}</th>
            <th class="tr_thead_list">{{__('NGÀY KẾT THÚC')}}</th>
            <th class="tr_thead_list">{{__('NGƯỜI TẠO')}}</th>
            <th class="tr_thead_list">{{__('NGÀY CẬP NHẬT')}}</th>
            <th class="tr_thead_list">{{__('TRẠNG THÁI')}}</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST_ANNEX))
            @foreach ($LIST_ANNEX as $key => $item)
                <tr>
                @if(isset($page))
                    <td>{{ ($page-1)*10 + $key+1}}</td>
                @else
                    <td>{{$key+1}}</td>
                @endif
                    <td>
                        @if($item['is_active'] == 0)
                        <button onclick="contractAnnex.popupEditContractAnnex({{$item['contract_annex_id']}})"
                                class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                title="{{__('Sửa')}}">
                            <i class="la la-edit"></i>
                        </button>
                        @endif
                        @if($key == 0)
                        <button onclick="contractAnnex.remove(this,{{$item['contract_annex_id']}})"
                                class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                title="{{__('Xoá')}}">
                            <i class="la la-trash"></i>
                        </button>
                        @endif
                    </td>
                    <td>
                        <a href="{{route("contract.contract.annex.detail",[ 'id' => $item['contract_annex_id']])}}">
                            {{$item['contract_annex_code']}}
                        </a>
                    </td>
                    <td>
                        {{date("d/m/Y",strtotime($item['sign_date']))}}
                    </td>
                    <td>{{$item['content']}}</td>
                    <td>
                        {{date("d/m/Y",strtotime($item['effective_date']))}}
                    </td>
                    <td>
                        {{date("d/m/Y",strtotime($item['expired_date']))}}
                    </td>
                    <td>{{$item['staff_created_by']}}</td>
                    <td>
                        {{date("d/m/Y H:i",strtotime($item['updated_at']))}}
                    </td>
                    <td>
                         <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                            <label style="margin: 0 0 0 10px; padding-top: 4px">
                                <input type="checkbox" disabled {{$item['is_active'] == 1 ? "checked" : ""}} class="manager-btn" name="">
                                <span></span>
                            </label>
                        </span>
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>

    </table>
</div>
@if(isset($LIST_ANNEX))
{{ $LIST_ANNEX->links('helpers.paging') }}
@endif
