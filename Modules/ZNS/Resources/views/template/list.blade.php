@if (isset($list))
    <div class="table-responsive">
        <table class="table table-striped m-table ss--header-table ss--nowrap">
            <thead>
            <tr>
                <th class="ss--text-center ss--font-size-th">#</th>
                <th class="ss--font-size-th">{{ __('Tên mẫu ZNS') }}</th>
                <th class="ss--font-size-th">{{ __('ID mẫu ZNS') }}</th>
{{--                <th class="ss--font-size-th">{{ __('Tên OA') }}</th>--}}
                <th class="ss--text-center ss--font-size-th">{{ __('Ngày tạo') }}</th>
                <th class="ss--text-center ss--font-size-th">{{ __('Đơn giá') }}</th>
                <th class="ss--text-center ss--font-size-th">{{ __('Trạng thái') }}</th>
                <th class="ss--text-center ss--font-size-th">{{ __('Số tin đã gửi') }}</th>
                <th class="ss--text-center ss--font-size-th" style="width:10%">{{ __('Hành động') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($list as $key => $item)
                <tr>
                    <td class="ss--text-center ss--font-size-13">
                        {{ isset($params['page']) ? ($params['page']-1)*10 + $key+1 :$key+1 }}
                    </td>
                    <td class="ss--font-size-13">{{ $item->template_name }}</td>
                    <td class="ss--font-size-13">{{ $item->template_id }}</td>
{{--                    <td class="ss--font-size-13"></td>--}}
                    <td class="ss--text-center ss--font-size-13">
                        {{ date_format(new DateTime($item->created_at), 'd/m/Y') }}</td>
                    <td class="ss--text-center ss--font-size-13"><span
                                class="text-primary">{{ $item->price }}/ZNS</span></td>
                    <td class="ss--text-center ss--font-size-13">
                        @if ($item->status == 1)
                            <span class="m-badge m-badge--success m-badge--wide w-50">
                                {{ __('Nháp') }}
                            </span>
                        @elseif($item->status == 2)
                            <span class="m-badge m-badge--warning m-badge--wide w-50">
                                {{ __('Đang duyệt') }}
                            </span>
                        @elseif($item->status == 3)
                            <span class="m-badge m-badge--danger m-badge--wide w-50">
                                {{ __('Bị từ chối') }}
                            </span>
                        @elseif($item->status == 4)
                            <span class="m-badge m-badge--primary m-badge--wide w-50">
                                {{ __('Đã duyệt') }}
                            </span>
                        @elseif($item->status == 5)
                            <span class="m-badge m-badge--secondary m-badge--wide w-50">
                                {{ __('Bị khóa') }}
                            </span>
                        @endif
                    </td>
                    <td class="ss--text-center ss--font-size-13">{{$item->number_sent?$item->number_sent:0}}</td>
                    <td class="float-right">
                        <div class="input-group w-50">
                            <div class="input-group-append">
                                <a class="btn btn-primary" target="_blank"
                                   href="{{$item->preview}}">{{__('Xem nội dung')}}</a>
                                <button class="btn btn-danger" type="button"><i class="fa fa-ellipsis-h"
                                                                                aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $list->links('helpers.paging') }}
@endif
