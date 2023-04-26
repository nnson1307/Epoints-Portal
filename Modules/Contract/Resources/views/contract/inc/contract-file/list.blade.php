<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">{{__('TÊN HỒ SƠ')}}</th>
            <th class="tr_thead_list">{{__('NỘI DUNG ĐÍNH KÈM')}}</th>
            <th class="tr_thead_list">{{__('GHI CHÚ')}}</th>
            <th class="tr_thead_list">{{__('NGƯỜI CẬP NHẬT')}}</th>
            <th class="tr_thead_list">{{__('NGÀY CẬP NHẬT')}}</th>
            <th class="tr_thead_list"></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                <tr>
                    <td>{{$item['name']}}</td>
                    <td>
                        @if (count($item['file']) > 0)
                            @foreach($item['file'] as $v)
                                <a href="{{$v['link']}}" class="ss--text-black"
                                   download="{{$v['file_name']}}">{{$v['file_name']}}</a><br>
                            @endforeach
                        @endif
                    </td>
                    <td>{{$item['note']}}</td>
                    <td>{{$item['update_by_name']}}</td>
                    <td>{{\Carbon\Carbon::parse($item['updated_at'])->format('d/m/Y')}}</td>
                    <td>
                        @if(session()->get('is_detail') == 0)
                            <a href="javascript:void(0)"
                               onclick="contractFile.showModalEdit('{{$item['contract_file_id']}}')"
                               class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                               title="{{__('Sửa')}}">
                                <i class="la la-edit"></i>
                            </a>
                            <button onclick="contractFile.remove(this, '{{$item['contract_file_id']}}')"
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
