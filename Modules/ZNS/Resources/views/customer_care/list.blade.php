@if (isset($list))
    <div class="table-responsive">
        <table class="table table-striped m-table ss--header-table ss--nowrap">
            <thead>
            <tr>
                <th class="ss--text-center ss--font-size-th">#</th>
                <th class="ss--font-size-th">{{ __('Ảnh đại diện') }}</th>
                <th class="ss--font-size-th">{{ __('Tên hiển thị') }}</th>
{{--                <th class="ss--font-size-th">{{ __('Tên OA') }}</th>--}}
                <th class="ss--text-center ss--font-size-th">{{ __('Số điện thoại') }}</th>
                <th class="ss--text-center ss--font-size-th">{{ __('Thông tin nhãn') }}</th>
                <th class="ss--text-center ss--font-size-th" style="width:10%">{{ __('Hành động') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($list as $key => $item)
                <tr>
                    <td class="ss--text-center ss--font-size-13">
                        {{ isset($params['page']) ? ($params['page']-1)*10 + $key+1 :$key+1 }}
                    </td>
                    <td class="ss--font-size-13">
                        <img src="{{ $item->avatar }}" class="rounded-circle" style="width:50px;height: 50px">
                    </td>
                    <td class="ss--font-size-13">{{ $item->full_name }}</td>
                    <td class="ss--text-center ss--font-size-13">
                        {{ $item->phone_number }}
                    </td>
                    <td class="ss--text-center ss--font-size-13">
                        <span class="text-primary">
                        @if(($item->tagList()))
                                @php
                                    $tag =  '';
                                @endphp
                            @foreach ($item->tagList() as $item_tag)
                                @php
                                  $tag .=  $item_tag->tag_name.' ,';
                                @endphp
                            @endforeach
                            {{ rtrim($tag, ", ") }}
                        @endif
                        </span>
                    </td>
                    <td class="float-right">
                        <a href="javascript:void(0);" onclick="CustomerCare.edit({{$item->zalo_customer_care_id}})"
                           class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                           title="{{__('Cập nhật')}}"><i class="la la-edit"></i>
                        </a>
                        <a href="javascript:void(0);"
                           onclick="CustomerCare.removeAction({{$item->zalo_customer_care_id}})"
                           class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                           title="{{__('Xóa')}}"><i class="la la-trash"></i>
                        </a>
                        <a href="javascript:void(0)" onclick="customer_appointment.click_modal()"
                           class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                           title="{{__('Thêm lịch hẹn')}}">
                            <i class="fa fa-calendar-plus" aria-hidden="true"></i>
                        </a>
                        <a href="javascript:void(0)" onclick="create.popupCreate(false)"
                           class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                           title="{{__('Thêm khách hàng tiềm năng')}}">
                            <i class="fa fa-user-plus" aria-hidden="true"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $list->links('helpers.paging') }}
@endif
