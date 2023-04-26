<div class="block-status-chart">
    <ul>
        @foreach($color as $key => $item)
            @if(!isset($link) && isset($statusId[$key]))
                <li class="d-flex align-items-center"><a href="{{route('manager-work',[
                        'manage_status_id[]' => $statusId[$key],
                        'date_start' => \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d H:i:s'),
                        'date_end' => \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d H:i:s'),
                        'assign_by' => isset($type) ? ($type == 'staff-overview' ? 4 : ($type == 'support' ? 1 : '')) : '',
                        'department_id' => isset($chart_department_id) ? $chart_department_id : '',
                        'manage_project_id' => isset($chart_manage_project_id) ? $chart_manage_project_id : '',
                        'processor_id' => isset($processor_id) ? $processor_id : '',
                        'support_id' => isset($support_id) ? $support_id : '',
                        'type-page' => isset($typePage) ? $typePage : ''
                        ])}}"><p class="mb-0 d-inline-block p-color" style="background: {{$item}}"></p><span style="text-decoration: underline;"><strong>{{$label[$key]}}</strong></span></a> </li>
            @else
                <li class="d-flex align-items-center"><p class="mb-0 d-inline-block p-color" style="background: {{$item}}"></p><span class="">{{$label[$key]}}</span></li>
            @endif
        @endforeach
    </ul>

</div>