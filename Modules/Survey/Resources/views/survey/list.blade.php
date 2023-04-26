<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
            <tr>
                <th class="tr_thead_list">
                    <p >#</p>
                </th>
                <th class="tr_thead_list">
                    <p >@lang('survey::survey.index.code_survey')</p>
                </th>
                <th class="tr_thead_list">
                    <p >@lang('survey::survey.index.name_survey')</p>
                </th>
                <th class="tr_thead_list">
                    <p >@lang('survey::survey.index.time_start')</p>
                </th>
                <th class="tr_thead_list">
                    <p >@lang('survey::survey.index.time_end')</p>
                </th>
                <th class="tr_thead_list">
                    <p >@lang('survey::survey.index.status')</p>
                </th>
                <th class="tr_thead_list">
                    <p>@lang('survey::survey.index.action')</p>
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($list as $key => $item)
                <tr>

                    <td>{{ $key + 1 + (int) $numberSttCurr }}</td>
                    <td> <a href="{{ route('survey.show', $item['survey_id']) }}">{{ $item['survey_code'] }}</a></td>
                    <td>{{ $item['survey_name'] }}</td>
                    <td>
                        @if (!empty($item['start_date']))
                            {{ (new DateTime($item['start_date']))->format('H:i:s d/m/Y') }}
                        @endif
                    </td>
                    <td>
                        @if (!empty($item['end_date']))
                            {{ (new DateTime($item['end_date']))->format('H:i:s d/m/Y') }}
                        @endif
                    </td>
                    <td>
                        @if ($item['status'] == 'N')
                            @lang('survey::survey.index.status_selected_draft')
                        @elseif($item['status'] == 'R')
                            @lang('survey::survey.index.status_selected_approved')
                        @elseif($item['status'] == 'C')
                            @lang('survey::survey.index.status_selected_end')
                        @elseif($item['status'] == 'D')
                            {{ __('Từ chối') }}
                        @elseif($item['status'] == 'P')
                        {{ __('Tạm dừng') }}
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
                                            'route' => 'survey.index',
                                            'html' =>
                                                '<a href="' .
                                                route('survey.show', [$item['survey_id']]) .
                                                '" class="dropdown-item">' .
                                                '<i class="la la-eye"></i>' .
                                                '<span class="kt-nav__link-text kt-margin-l-5">' .
                                                __('Xem chi tiết') .
                                                '</span>' .
                                                '</a>',
                                        ],
                                    ])
                                    @if ($item['status'] == 'N')
                                        @include('helpers.button', [
                                            'button' => [
                                                'route' => 'survey.index',
                                                'html' =>
                                                    '<a href="' .
                                                    route('survey.edit', [$item['survey_id']]) .
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
                                                    '<a href="javascript:void(0)" onclick="survey.destroy(' .
                                                    $item['survey_id'] .
                                                    ')" class="dropdown-item">' .
                                                    '<i class="la la-trash"></i>' .
                                                    '<span class="kt-nav__link-text kt-margin-l-5">' .
                                                    __('Xóa') .
                                                    '</span>' .
                                                    '</a>',
                                            ],
                                        ])
                                    @endif
                                    @if ($item['status'] == 'R' && $item['is_short_link'] == 1)
                                    @include('helpers.button', [
                                        'button' => [
                                            'route' => 'brand.campaign-on-invoice.deleteProgram',
                                            'html' =>
                                                '<a href="javascript:void(0)" onclick="survey.showModalCoppyUrl(' .
                                                $item['survey_id'] .
                                                ')" class="dropdown-item">' .
                                                '<i class="fa fa-link"></i>' .
                                                '<span class="kt-nav__link-text kt-margin-l-5">' .
                                                __('Sao chép URL') .
                                                '</span>' .
                                                '</a>',
                                        ],
                                    ])
                                    @endif
                                    @include('helpers.button', [
                                        'button' => [
                                            'route' => 'brand.campaign-on-invoice.deleteProgram',
                                            'html' =>
                                                '<a href="javascript:void(0)" onclick="survey.showModalCoppy(' .
                                                $item['survey_id'] .
                                                ')" class="dropdown-item">' .
                                                '<i class="la la-copy"></i>' .
                                                '<span class="kt-nav__link-text kt-margin-l-5">' .
                                                __('Sao chép') .
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
    {{ $list->links('survey::survey.helpers.paging-list') }}
</div>
