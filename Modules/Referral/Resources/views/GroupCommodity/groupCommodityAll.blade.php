@if($data == 'choose_type_commodity')
    <option value="">{{__('Chọn nhóm hàng hóa')}}</option>
@else
    <option value="">{{__('Chọn nhóm hàng hóa')}}</option>
    <option value="{{$typeGroup.'|all'}}">{{__('Tất cả')}}</option>
    @foreach($data as $k => $v)
            <option value={{$v['type'].'|'.$v['id']}}>{{$v['name']}}</option>
    @endforeach
@endif