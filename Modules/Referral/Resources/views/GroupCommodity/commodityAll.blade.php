<option value="{{$typeGroup.'|all'}}">{{__('Tất cả')}}</option>
@foreach($data as $k => $v)
    <option value={{$v['type'].'|'.$v['id']}}>{{$v['name']}}</option>
@endforeach
