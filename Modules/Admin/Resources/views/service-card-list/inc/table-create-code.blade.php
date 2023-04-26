@foreach ($LIST as $key => $item)
    <tr>
        <td>{{$key+1}}</td>
        <td class="c_code">{{$item}}</td>
        <td>{{\Carbon\Carbon::now()->format("d/m/Y")}}</td>
        <td>
            <label class="m-checkbox m-checkbox--solid" style="margin-bottom: 14px">
                <input type="checkbox" autocomplete="off" checked class="ckb-item">
                <span></span>
            </label>
        </td>
    </tr>
@endforeach