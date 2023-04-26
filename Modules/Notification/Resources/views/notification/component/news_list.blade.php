
<div class="form-group table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list"></th>
            <th class="tr_thead_list">{{__('Hình ảnh')}}</th>
            <th class="tr_thead_list">{{__('Tiêu đề')}}</th>
            <th class="tr_thead_list">{{__('Mô tả')}}</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                <tr>
                    <td>
                        <label class="m-checkbox m-checkbox--air">
                            <input id="promo" name="example_3" type="radio" onclick="clickRadioEndPoint({{$item['new_id']}},'{{$item['title_vi']}}');">
                            <span></span>
                        </label>
                    </td>
                    <td>
                        <img class="m--bg-metal m-image img-sd"
                             src="{{$item['image'] != '' ? $item['image'] :'https://vignette.wikia.nocookie.net/recipes/images/1/1c/Avatar.svg/revision/latest/scale-to-width-down/480?cb=20110302033947'}}"
                             alt="Hình ảnh" width="100px" height="100px">
                    </td>
                    <td>{{$item['title_vi']}}</td>
                    <td>{{$item['description_vi']}}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}