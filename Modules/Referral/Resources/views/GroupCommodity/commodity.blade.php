@if($data == [])
    <option value="">{{__('Hiện hàng hóa này chưa tồn tại hoặc đã được thêm!')}}</option>
@else
    <option value="">{{__('Chọn hàng hóa')}}</option>
    <option value="{{$typeGroup.'|all'}}" >{{__('Tất cả')}}</option>
    {{--    <option value="{{$typeGroup.'|all'}}" {{isset($param['group_commodity']) && $param['group_commodity'] == $typeGroup.'|all' ? 'selected' : ''}}>{{__('Tất cả')}}</option>--}}
    @foreach($data as $k => $v)
{{--        <option value="{{$v['type'].'|'.$v['id']}}" {{isset($param['group_commodity']) && $param['group_commodity'] == $v['type'].'|'.$v['id'] ? 'selected' : ''}}>{{$v['name']}}</option>--}}
        <option value="{{$v['type'].'|'.$v['id']}}">{{$v['name']}}</option>
    @endforeach
@endif
