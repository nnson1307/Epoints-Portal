<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
            <tr>
                <th>
                    <p style="margin-top:10px; font-weight:bold">#</p>
                </th>
                <th>
                    <p style="margin-top:10px; font-weight:bold">{{ __('Tên trang') }}</p>
                </th>
                <th>
                    <p style="margin-top:10px; font-weight:bold">{{ __('Vị trí trang') }}</p>
                </th>
                <th>
                    <p style="margin-top:10px; font-weight:bold">{{ __('Tên hiển thị') }}</p>
                </th>
                <th>
                    <p style="margin-top:10px; font-weight:bold">{{ __('Loại template') }}</p>
                </th>
                <th>
                    <p style="margin-top:10px; font-weight:bold">{{ __('Thời gian cập nhật') }}</p>
                </th>
                <th>
                    <p style="margin-top:10px; font-weight:bold">{{ __('Hành động') }}</p>
                </th>

            </tr>
        </thead>
        <tbody>

            @foreach ($listConfigDisplay as $key => $item)
                <tr>

                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item['name_page'] }}</td>
                    <td>{{ $item['position_page'] }}</td>
                    <td>{{ $item['title_page'] }}</td>
                    <td>{{ $item['type_template'] }}</td>
                    <td>
                        @if (!empty($item['update_at']))
                            {{ Carbon::parse($item['update_at'])->format('Y-m-d h:i:s') }}
                        @elseif(!empty($item['created_at']))
                            {{ Carbon::parse($item['created_at'])->format('Y-m-d h:i:s') }}
                        @else
                            {{ __('') }}
                        @endif
                    </td>
                    <td>
                        <div class="kt-portlet__head-toolbar">
                            <div class="btn-group" role="group">
                                <button id="btnGroupVerticalDrop1" type="button"
                                    class="btn btn-secondary dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    @lang('Hành động')
                                </button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">
                                    @include('helpers.button', [
                                        'button' => [
                                            'route' => 'config-display.configDisplay.show',
                                            'html' =>
                                                '<a href="' .
                                                route('config-display.configDisplay.show', [$item['id_config_display']]) .
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
                                            'route' => 'survey.index',
                                            'html' =>
                                                '<a href="' .
                                                route('config-display.configDisplay.edit', [$item['id_config_display']]) .
                                                '" class="dropdown-item">' .
                                                '<i class="la la-edit"></i>' .
                                                '<span class="kt-nav__link-text kt-margin-l-5">' .
                                                __('Chỉnh sửa') .
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
        </tbody>
    </table>
    {{ $listConfigDisplay->links('config-display::helpers.paging-config-display') }}
</div>
