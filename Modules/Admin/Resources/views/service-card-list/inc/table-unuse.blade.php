<div class="table-responsive" style="max-height: 520px; overflow: auto">
<table class="table table-striped m-table m-table--head-bg-primary">
    <thead>
    <tr>
        <th>#</th>
        <th>{{__('Mã Thẻ dịch vụ')}}</th>
        <th>{{__('Ngày tạo')}}</th>
        <th>
            <label class="m-checkbox m-checkbox--solid m-checkbox--success" style="margin-bottom: 14px">
                <input type="checkbox" autocomplete="off" class="ckb-all">
                <span></span>
            </label>
        </th>
    </tr>
    </thead>
    <tbody>

        @foreach ($LIST as $key => $item)
            <tr>
                <td>{{$key+1}}</td>
                <td class="c_code">{{$item->code}}</td>
                <td>{{\Carbon\Carbon::parse($item->created_at)->format("d-m-Y")}}</td>
                <td>
                    <label class="m-checkbox m-checkbox--solid" style="margin-bottom: 14px">
                        <input type="checkbox" autocomplete="off" class="ckb-item">
                        <span></span>
                    </label>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
</div>