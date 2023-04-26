@php
$isShowButton = $site == 'edit' ? '' : 'disabled';
@endphp
<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
            <tr>
                <th>
                    <p style="margin-top:10px; font-weight:bold">{{ __('Thứ tự hiển thị') }}</p>
                </th>
                <th>
                    <p style="margin-top:10px; font-weight:bold">{{ __('Ảnh banner') }}</p>
                </th>
                <th>
                    <p style="margin-top:10px; font-weight:bold">{{ __('Tiêu đề chính') }}</p>
                </th>
                <th>
                    <p style="margin-top:10px; font-weight:bold">{{ __('Đích đến') }}</p>
                </th>
                <th>
                    <p style="margin-top:10px; font-weight:bold">{{ __('Đích đến chi tiết') }}</p>
                </th>
                <th>
                    <p style="margin-top:10px; font-weight:bold">{{ __('Ngày tạo') }}</p>
                </th>
                <th>
                    <p style="margin-top:10px; font-weight:bold">{{ __('Trạng thái') }}</p>
                </th>
                <th>
                    <p style="margin-top:10px; font-weight:bold">{{ __('Hành động') }}</p>
                </th>

            </tr>
        </thead>
        <tbody>

            @foreach ($listConfigDisplayDetail as $key => $item)
                <tr>
                    <td style="vertical-align: baseline;">{{ $item['position'] }}</td>
                    <td style="vertical-align: baseline;">
                        <div class="banner">
                            <img src="{{ $item['image'] }}" class="banner__image" alt="">
                        </div>
                    </td>
                    <td style="vertical-align: baseline;">{{ $item['main_title'] }}</td>
                    <td style="vertical-align: baseline;">{{ $item['category_config_name'] }}</td>
                    <td style="vertical-align: baseline;">
                        @if ($item['category_config_key'] == 'survey')
                            {{ $item['survey_name'] }}
                        @elseif($item['category_config_key'] == 'promotion')
                            {{ $item['promotion_name'] }}
                        @elseif($item['category_config_key'] == 'product_detail')
                            {{ $item['product_name'] }}
                        @elseif($item['category_config_key'] == 'post_detail')
                            {{ $item['title_vi'] }}
                        @endif
                    </td>
                    <td style="vertical-align: baseline;">{{ $item['created_at'] }}</td>
                    <td style="vertical-align: baseline;">
                        @if ($item['status'] == '1')
                            {{ __('Hoạt động') }}
                        @else
                            {{ __('Ngưng hoạt động') }}
                        @endif
                    </td>
                    <td style="vertical-align: baseline;">
                        <div class="kt-portlet__head-toolbar">
                            <div class="btn-group" role="group">
                                <button id="btnGroupVerticalDrop1" {{ $isShowButton }} type="button"
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
                                                route('config-display-detail.configDisplay.show', [
                                                    $item['id_config_display'],
                                                    $item['id_config_display_detail'],
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
                                            'route' => 'config-display.configDisplay.edit',
                                            'html' =>
                                                '<a href="' .
                                                route('config-display-detail.configDisplay.edit', [
                                                    $item['id_config_display'],
                                                    $item['id_config_display_detail'],
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
                                            'route' => '',
                                            'html' =>
                                                '<a href="javascript:void(0)" onclick="configDisplayDetail.showModalDestroy(' .
                                                $item['id_config_display'] .
                                                ',' .
                                                $item['id_config_display_detail'] .
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
        </tbody>
    </table>
    {{ $listConfigDisplayDetail->links('config-display::helpers.paging-config-display-detail') }}
</div>
