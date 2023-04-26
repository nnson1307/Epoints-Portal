<option value="">Chọn bàn</option>
@foreach($listTable as $item)
    <option value="{{$item['table_id']}}">{{$item['table_name']}}</option>
@endforeach