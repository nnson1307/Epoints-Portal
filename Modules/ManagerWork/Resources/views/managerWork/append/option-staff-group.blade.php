@if (count($dataOptionSupport) > 0)
    @foreach($dataOptionSupport as $v)
        <option class="service-option" disabled>{{$v['branch_name']}}</option>
        @if (count($v['dataChild']) > 0)
            @foreach($v['dataChild'] as $v1)
                <optgroup label="{{$v1['department_name']}}" class="m--font-bold">
                    @if (count($v1['list_staff']) > 0)
                        @foreach($v1['list_staff'] as $v2)
                            <option value="{{$v2['staff_id'] }}" >{{ $v2['staff_name'] }}</option>
                        @endforeach
                    @endif
                </optgroup>
            @endforeach
        @endif
    @endforeach
@endif