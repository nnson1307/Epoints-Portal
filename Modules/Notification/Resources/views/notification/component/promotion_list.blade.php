
<div class="form-group table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list"></th>
            <th class="tr_thead_list">{{__('Hình ảnh')}}</th>
            <th class="tr_thead_list">{{__('Tên chương trình khuyến mãi')}}</th>
            <th class="tr_thead_list">{{__('Ngày bắt đầu')}}</th>
            <th class="tr_thead_list">{{__('Ngày kết thúc')}}</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                <tr>
                    <td>
                        <label class="m-checkbox m-checkbox--air">
                            <input id="promo" name="example_3" type="radio" onclick="clickRadioEndPoint({{$item['promotion_id']}},'{{$item['promotion_name']}}');">
                            <span></span>
                        </label>
                    </td>
                    <td>
                        <img class="m--bg-metal m-image img-sd"
                             src="{{$item['image'] != '' ? $item['image'] :'https://vignette.wikia.nocookie.net/recipes/images/1/1c/Avatar.svg/revision/latest/scale-to-width-down/480?cb=20110302033947'}}"
                             alt="Hình ảnh" width="100px" height="100px">
                    </td>
                    <td>{{$item['promotion_name']}}</td>
                    <td>{{$item['start_date']}}</td>
                    <td>{{$item['end_date']}}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}