<option value="">{{__('Chọn chi nhánh')}}</option>
@if(isset($list) && count($list) != 0)
    @foreach($list as $key => $item)
        <option value="{{$key}}">{{$item}}</option>
    @endforeach
@endif