
<div class="form-group table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list"></th>
            <th class="tr_thead_list">{{__('Hình ảnh')}}</th>
            <th class="tr_thead_list">{{__('Tên dịch vụ')}}</th>
            <th class="tr_thead_list">{{__('Mã dịch vụ')}}</th>
            <th class="tr_thead_list">{{__('Giá')}}</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                <tr>
                    <td>
                        <label class="m-checkbox m-checkbox--air">
                            <input id="promo" name="example_3" type="radio" onclick="clickRadioEndPoint({{$item['service_id']}},'{{$item['service_name']}}');">
                            <span></span>
                        </label>
                    </td>
                    <td>
                        <img class="m--bg-metal m-image img-sd"
                             src="{{$item['service_avatar'] != '' ? $item['service_avatar'] :'https://vignette.wikia.nocookie.net/recipes/images/1/1c/Avatar.svg/revision/latest/scale-to-width-down/480?cb=20110302033947'}}"
                             alt="Hình ảnh" width="100px" height="100px">
                    </td>
                    <td>{{$item['service_name']}}</td>
                    <td>{{$item['service_code']}}</td>
                    <td>{{number_format($item['price_standard'])}}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}