<option value="">{{ __('Tất cả nhân viên') }}</option>
@foreach($list as $item)
    <option value="{{$item['staff_id']}}">{{$item['full_name']}}</option>
@endforeach