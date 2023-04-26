<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table">
        <thead>
            <tr class="ss--nowrap">
                <th class="ss--font-size-th">{{ __('#') }}</th>
                <th class="ss--font-size-th">{{ __('Phòng ban') }}</th>
                <th class="ss--font-size-th">{{ __('Nhóm') }}</th>
                <th class="ss--font-size-th">{{ __('Ngân sách tháng') }}</th>
                <th class="ss--font-size-th">{{ __('Tháng áp dụng') }}</th>
                <th class="ss--font-size-th">{{ __('Người tạo') }}</th>
                <th class="ss--font-size-th">{{ __('Thời gian tạo') }}</th>
                <th class="ss--font-size-th">{{ __('Hành động') }}</th>  
            </tr>
        </thead>

        <tbody>
            @if (isset($data) && $data->isNotEmpty())
                @foreach ($data as $key => $item)
                    <tr class="ss--font-size-13 ss--nowrap">
                        <td class="text_middle">
                            @if(isset($page))
                                {{ ($page-1)*10 + $key+1}}
                            @else
                                {{$key+1}}
                            @endif
                        </td>
                        <td>{{ $item['department_name'] }}</td>
                        <td>{{ $item['team_name'] }}</td>
                        <td>{{ number_format($item['budget']) }}</td>
                        <td>Tháng {{ date("m/Y",strtotime($item['effect_time'])) }} </td>
                        <td>{{ $item['created_by'] }}</td>
                        <td>{{ Carbon\Carbon::parse($item['created_at'])->format('d/m/Y H:i') }}</td>
                        <td>
                           @if (in_array('kpi.marketing.budget.month', session('routeList')))
                                @if (date("m/Y",strtotime($item['effect_time'])) >= Carbon\Carbon::now()->format('m/Y')) 
                                    <a href="#" 
                                        class="btn-edit-budget m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" 
                                        title="{{ __('Chỉnh sửa') }}"
                                        data-id="{{ $item['budget_marketing_id'] }}"
                                        data-department="{{ $item['department_id'] }}"
                                        data-team="{{ $item['team_id'] }}"
                                        data-time="{{ date("Y-m",strtotime($item['effect_time'])) }}"
                                        data-budget="{{ $item['budget'] }}">
                                        <i class="la la-edit"></i>
                                    </a>

                                    <button onclick="BudgetMarketing.remove(this, {{ $item['budget_marketing_id'] }})"
                                        class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                        title="{{ __('Xóa') }}">
                                        <i class="la la-trash"></i>
                                    </button>
                                @endif
                           @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td align="center" colspan="8">{{ __('Chưa có dữ liệu') }}</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

{{ $data->links('helpers.paging') }}
