@if (isset($list))
    <div class="table-responsive">
        <table class="table table-striped m-table ss--header-table ss--nowrap">
            <thead>
            <tr>
                <th class="ss--text-center ss--font-size-th">#</th>
                <th class="ss--text-center ss--font-size-th" style="width:10%">{{ __('Hành động') }}</th>
                <th class="ss--font-size-th">{{ __('Tên mẫu ZNS') }}</th>
                {{--                <th class="ss--font-size-th">{{ __('Tên OA') }}</th>--}}
                <th class="ss--font-size-th">{{ __('Loại mẫu') }}</th>
                <th class="ss--text-center ss--font-size-th">{{ __('Ngày tạo') }}</th>
                <th class="ss--text-center ss--font-size-th">{{ __('Số tin đã gửi') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($list as $key => $item)
                <tr>
                    <td class="ss--text-center ss--font-size-13">
                        {{ isset($params['page']) ? ($params['page']-1)*10 + $key+1 :$key+1 }}
                    </td>
                    <td class="text-center">
                        <a href="{{ route('zns.template-follower.preview',$item->zns_template_id) }}"
                           class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                           title="{{__('Xem trước')}}"><i class="la la-eye"></i>
                        </a>
                        <a href="{{ route('zns.template-follower.edit',$item->zns_template_id) }}"
                           class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                           title="{{__('Cập nhật')}}"><i class="la la-edit"></i>
                        </a>
                        <a href="javascript:void(0);" onclick="Template.cloneModal({{$item->zns_template_id}})"
                           class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                           title="{{__('sao chép')}}"><i class="fa fa-clone"></i>
                        </a>

                    </td>
                    <td class="ss--font-size-13">
                        <a href="{{route('zns.template-follower.view',$item->zns_template_id)}}">
                            {{ $item->template_name }}
                        </a>
                    </td>
                    <td class="ss--font-size-13">
                        @if($item->type_template_follower == 0)
                            {{__('Gửi thông báo văn bản')}}
                        @elseif($item->type_template_follower == 1)
                            {{__('Gửi thông báo theo mẫu đính kèm ảnh')}}
                        @elseif($item->type_template_follower == 2)
                            {{__('Gửi thông báo theo mẫu đính kèm danh sách')}}
                        @elseif($item->type_template_follower == 3)
                            {{__('Gửi thông báo theo mẫu đính kèm file')}}
                        @elseif($item->type_template_follower == 4)
                            {{__('Gửi thông báo theo mẫu yêu cầu thông tin người dùng')}}
                        @endif
                    </td>
                    <td class="ss--text-center ss--font-size-13">
                        {{ date_format(new DateTime($item->created_at), 'd/m/Y') }}</td>
                    <td class="ss--text-center ss--font-size-13">{{$item->number_sent?$item->number_sent:0}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $list->links('helpers.paging') }}
@endif
