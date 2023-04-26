<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
            <tr>
                <th class="tr_thead_list">
                    <p style="margin-top:10px">#</p>
                </th>
                <th class="tr_thead_list">
                    <p style="margin-top:10px">{{ __('TÊN CHƯƠNG TRÌNH') }}</p>
                </th>
                <th class="tr_thead_list">
                    <p style="margin-top:10px">{{ __('TÊN KHẢO SÁT') }}</p>
                </th>
                <th class="tr_thead_list">
                    <p style="margin-top:10px">{{ __('THỜI GIAN TẠO') }}</p>
                </th>
                <th class="tr_thead_list">
                    <p style="margin-top:10px">{{ __('THỜI GIAN KẾT THÚC') }}</p>
                </th>
                <th class="tr_thead_list">
                    <p style="margin-top:10px">{{ __('TRẠNG THÁI') }}</p>
                </th>
                <th class="tr_thead_list">
                    <p>@lang('survey::survey.index.action')</p>
                </th>
            </tr>
        </thead>
        <tbody>
            @if ($data->count() > 0)
                @foreach ($data as $key => $item)
                    <tr>

                        <td>{{ $key + 1 }}</td>
                        <td> <a
                                href="{{ route('loyalty.accumulate-points.show', $item['accumulation_program_id']) }}">{{ $item['accumulation_program_name'] }}</a>
                        </td>
                        <td>{{ $item['survey_name'] }}</td>
                        <td>
                            @if (!empty($item['date_start']))
                                {{ (new DateTime($item['date_start']))->format('H:i:s d/m/Y') }}
                            @endif
                        </td>
                        <td>
                            @if (!empty($item['date_end']))
                                {{ (new DateTime($item['date_end']))->format('H:i:s d/m/Y') }}
                            @endif
                        </td>
                        <td>
                            @if ($item['is_active'] == '1')
                                {{ __('Hoạt động') }}
                            @else
                                {{ __('Ngưng hoạt động') }}
                            @endif
                        </td>
                        <td>
                            <div class="kt-portlet__head-toolbar">
                                <div class="btn-group" role="group">
                                    <button id="btnGroupVerticalDrop1" {{ $isDisabled ?? '' }} type="button"
                                        class="btn btn-secondary dropdown-toggle" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        @lang('Hành động')
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">
                                        @include('helpers.button', [
                                            'button' => [
                                                'route' => 'loyalty.accumulate-points.show',
                                                'html' =>
                                                    '<a href="' .
                                                    route('loyalty.accumulate-points.show', [
                                                        $item['accumulation_program_id'],
                                                    ]) .
                                                    '" class="dropdown-item">' .
                                                    '<i class="la la-eye"></i>' .
                                                    '<span class="kt-nav__link-text kt-margin-l-5">' .
                                                    __('Xem chi tiết') .
                                                    '</span>' .
                                                    '</a>',
                                            ],
                                        ])
                                        @include('helpers.button', [
                                            'button' => [
                                                'route' => 'loyalty.accumulate-points.edit',
                                                'html' =>
                                                    '<a href="' .
                                                    route('loyalty.accumulate-points.edit', [
                                                        $item['accumulation_program_id'],
                                                    ]) .
                                                    '" class="dropdown-item">' .
                                                    '<i class="la la-edit"></i>' .
                                                    '<span class="kt-nav__link-text kt-margin-l-5">' .
                                                    __('Chỉnh sửa') .
                                                    '</span>' .
                                                    '</a>',
                                            ],
                                        ])
                                        @include('helpers.button', [
                                            'button' => [
                                                'route' => 'brand.campaign-on-invoice.deleteProgram',
                                                'html' =>
                                                    '<a href="javascript:void(0)" onclick="loyalty.showModalDestroy(' .
                                                    $item['accumulation_program_id'] .
                                                    ')" class="dropdown-item">' .
                                                    '<i class="la la-trash"></i>' .
                                                    '<span class="kt-nav__link-text kt-margin-l-5">' .
                                                    __('Xóa') .
                                                    '</span>' .
                                                    '</a>',
                                            ],
                                        ])
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    {{ $data->links('loyalty::accumulate-points-program.helpers.paging-list') }}
</div>
