@if (isset($list))
<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table ss--nowrap">
        <thead>
        <tr>
            <th class="ss--text-center ss--font-size-th" style="width:10%">{{__('Trạng thái')}}</th>
            <th class="ss--text-center ss--font-size-th" style="width:15%">{{__('Tên mẫu')}}</th>
            <th class="ss--font-size-th" style="width:20%">{{__('Tên mẫu ZNS')}}</th>
            <th class="ss--font-size-th" style="width:30%">{{__('Nội dung mẫu tin ZNS')}}</th>
            <th class="ss--text-center ss--font-size-th"style="width:15%">{{__('Các tham số có thể sử dụng')}}</th>
            <th class="ss--text-center ss--font-size-th" style="width:10%">{{__('Hành động')}}</th>
        </tr>
        </thead>
        <tbody>
            @foreach ($list as $key => $item)
                <tr>
                    <td class="ss--text-center ss--font-size-13">
                        <div class="m-widget4__checkbox m--margin-left-15">
                            <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                                <input onclick="Config.changeStatus(this, '{!! $item->id !!}', {{$item->is_active}})" type="checkbox"{{ $item->is_active ? ' checked' : '' }}>
                                <span></span>
                            </label>
                        </div>    
                    </td>
                    <td class="ss--font-size-13 ss--text-center">
                        <b>{{ $item->name }}</b>  <br>
                        <span class="small-text">{{$item->hint}}</span>
                    </td>
                    <td class="ss--font-size-13">{{ $item->template_name }}</td>
                    <td class="ss--font-size-13">
                        <a href="{{ $item->preview }}" target="_blank" class="text-primary"><span style="max-width:50%">{{ $item->preview }}</span></a>
                    </td>
                    <td class="ss--font-size-13">
                       <div style="white-space: pre-line;">
                        @php
                            $params = $mTriggerParamsTable->getParamsByTriggerConfig($item->id);
                        @endphp
                        @if ($params)
                            @foreach ($params as $value)
                            <span class="mr-3 coppy_button"><i class="fa fa-clone mr-2"></i>{{ $value->value }}</span>
                            @endforeach
                        @endif
                       </div>
                    </td>
                    <td class="ss--text-center">
                        <a href="javascript:void(0)" onclick="Config.edit({{$item->id}})"
                        class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                        title="{{__('Cập nhật')}}"><i class="la la-edit"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
{{ $list->links('helpers.paging') }}
@endif
