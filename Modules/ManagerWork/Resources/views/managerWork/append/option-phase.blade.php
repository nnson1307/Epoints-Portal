<option value="">{{__('Chọn giai đoạn')}}</option>
@foreach($listPhase as $item)
    <option value="{{$item['manage_project_phase_id']}}">{{$item['name']}}</option>
@endforeach