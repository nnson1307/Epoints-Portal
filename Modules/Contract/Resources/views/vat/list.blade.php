<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">{{__('% VAT')}}</th>
            <th class="tr_thead_list">{{__('MÔ TẢ')}}</th>
            <th class="tr_thead_list">{{__('TRẠNG THÁI')}}</th>
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
                    <td>{{$item['vat']}}</td>
                    <td>{{$item['description']}}</td>
                    <td>
                         <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label style="margin: 0 0 0 10px; padding-top: 4px">
                                        <input type="checkbox"
                                               onclick="viewVat.changeStatus(this, '{{$item['vat_id']}}')"
                                               {{$item['is_actived'] == 1 ? 'checked': ''}} class="manager-btn" name="">
                                        <span></span>
                                    </label>
                        </span>
                    </td>
                    <td>
                        @if(in_array('contract.vat.show-pop-edit', session()->get('routeList')))
                            <a href="javasript:void(0)" onclick="viewVat.showPopEdit('{{$item['vat_id']}}')"
                               class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                               title="{{__('Sửa')}}">
                                <i class="la la-edit"></i>
                            </a>
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>

    </table>
</div>
{{ $LIST->links('helpers.paging') }}
