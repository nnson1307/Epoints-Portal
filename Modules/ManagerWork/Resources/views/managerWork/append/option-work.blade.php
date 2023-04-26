@if(isset($optionNull))
    <option value="">{{__('Chọn công việc cha')}}</option>
@endif
@foreach($listWork as $item)
    <option value="{{ $item['manage_work_id'] }}">{{ $item['manage_work_title'] }}</option>
@endforeach