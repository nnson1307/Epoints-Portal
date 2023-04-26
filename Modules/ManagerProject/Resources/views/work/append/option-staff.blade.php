@foreach($listStaff as $item)
    <option value="{{ $item['staff_id'] }}">{{ $item['full_name'] }}</option>
@endforeach