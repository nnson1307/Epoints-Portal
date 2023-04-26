<option value="">{{__('Chọn Phường/Xã')}}</option>
@foreach($listWard as $item)
    <option value="{{(int)$item['ward_id']}}" {{isset($ward_id) && (int)$ward_id == (int)$item['ward_id'] ? 'selected' : ''}}>{{$item['type'].' '.$item['name']}}</option>
@endforeach