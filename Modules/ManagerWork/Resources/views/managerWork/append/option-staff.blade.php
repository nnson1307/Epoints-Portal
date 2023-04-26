@if(isset($optionNull))
    <option value="">{{__('Chọn nhân viên')}}</option>
@endif
@foreach($listStaff as $item)
    <option value="{{ $item['staff_id'] }}">{{ $item['full_name'] }}</option>
@endforeach