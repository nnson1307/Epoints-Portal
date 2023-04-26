<option value="">{{__('Chọn Quận/Huyện')}}</option>
@foreach($listDistrict as $item)
    <option value="{{(int)$item['districtid']}}">{{$item['type'].' '.$item['name']}}</option>
@endforeach