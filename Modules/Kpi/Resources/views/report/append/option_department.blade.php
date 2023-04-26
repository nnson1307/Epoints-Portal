<option value="">{{ __('Tất cả phòng ban') }}</option>
@foreach($list as $item)
    <option value="{{$item['department_id']}}">{{$item['department_name']}}</option>
@endforeach