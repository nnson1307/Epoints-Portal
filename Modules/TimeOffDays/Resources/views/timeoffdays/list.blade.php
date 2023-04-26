<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">@lang('NHÂN VIÊN')</th>
            <th class="tr_thead_list">@lang('LOẠI PHÉP')</th>
            <th class="tr_thead_list">@lang('THỜI GIAN NGHỈ')</th>
            <th class="tr_thead_list">@lang('THỜI GIAN TẠO')</th>
            <th class="tr_thead_list">@lang('NGƯỜI DUYỆT CẤP 1')</th>
            <th class="tr_thead_list">@lang('NGƯỜI DUYỆT CẤP 2')</th>
            <th class="tr_thead_list">@lang('NGƯỜI DUYỆT CẤP 3')</th>
            <th class="tr_thead_list">@lang('TRẠNG THÁI')</th>
            <th class="tr_thead_list">@lang('HÀNH ĐỘNG')</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST) && count($LIST) > 0)
            
            @foreach ($LIST as $k => $item)

                <tr>
                    <td>
                        {{isset($page) ? ($page-1)*10 + $k+1 : $k+1}}
                    </td>
                    <td>
                        <img src="{{$item['staff_avatar']}}" onerror="if (this.src != '/static/backend/images/default-placeholder.png') this.src = '/static/backend/images/default-placeholder.png';" class="m--img-rounded m--marginless" alt="photo" width="50px" height="50px">    
                         {{$item['full_name']}}
                    </td>
                    <td>{{$item['time_off_type_name']}}</td>

                    
                    <td>
                        {{\Carbon\Carbon::parse($item['time_off_days_start'])->format('d/m/Y')}} - 
                        {{\Carbon\Carbon::parse($item['time_off_days_end'])->format('d/m/Y')}}
                    </td>
                   
                    <td>{{\Carbon\Carbon::parse($item['created_at'])->format('H:i d/m/Y')}}</td>
                    <td>
                        @if($item['full_name_level1'] != null)
                            @if ($item['is_approve_level1'] === 1)
                                <i class="la la-check-circle" style="color : #00a650"></i>
                            @elseif ($item['is_approve_level1'] === 0)   
                                <i class="la la-times-circle-o" style="color : #ed2e24"></i>
                            @else
                                <i class="la la-clock-o" style="color : #ffb927"></i>
                            @endif
                        
                            {{$item['full_name_level1'] ?? ''}}
                        @endif
                        
                       
                    </td>
                    <td>
                        @if ($item['full_name_level2'] != null)
                            @if ($item['is_approve_level2'] === 1)
                                <i class="la la-check-circle" style="color : #00a650"></i>
                            @elseif ($item['is_approve_level2'] === 0)   
                                <i class="la la-times-circle-o" style="color : #ed2e24"></i>
                            @else
                                <i class="la la-clock-o" style="color : #ffb927"></i>
                            @endif
                            {{$item['full_name_level2'] ?? ''}}
                        @endif
                    </td>
                    <td>
                        @if ($item['full_name_level3'] != null)
                            @if ($item['is_approve_level3'] ===1)
                                <i class="la la-check-circle" style="color : #00a650"></i>
                            @elseif ($item['is_approve_level3'] === 0)   
                                <i class="la la-times-circle-o" style="color : #ed2e24"></i>
                            @else
                                <i class="la la-clock-o" style="color : #ffb927"></i>
                            @endif
                            {{$item['full_name_level3'] ?? ''}}
                        @endif
                    </td>
                    <td>
         
                        @if(is_null($item['is_approve']))
                            <span class="m-badge m-badge--warning m-badge--wide">@lang('Chờ duyệt')</span>
                        @elseif($item['is_approve'] === 1)
                            <span class="m-badge m-badge--success m-badge--wide">@lang('Chấp nhận')</span>
                        @elseif($item['is_approve'] === 0)
                            <span class="m-badge m-badge--danger m-badge--wide">@lang('Từ chối')</span>
                        @endif
                    </td>
                    <td>

                        @if(is_null($item['is_approve']))
                            @if ((Auth()->id() == $item['staff_id_approve_level1'] && $item['is_approve_level1'] === null) || (in_array(Auth()->id(), json_decode($item['staff_id_approve_level2'] ?? "") ?? []) && $item['is_approve_level2'] === null) || (in_array(Auth()->id(), json_decode($item['staff_id_approve_level3']) ?? [])))
                                <a href="javascript:void(0);" onclick="timeoffdays.approve({{ $item ['time_off_days_id']}})"
                                        class="test m-portlet__nav-link btn m-btn m-btn--hover-success m-btn--icon m-btn--icon-only m-btn--pill"
                                        title="{{__('Duyệt')}}">
                                    <i class="la la-check-circle"></i>
                                </a>
                                <a href="javascript:void(0);" onclick="timeoffdays.unApprove({{ $item ['time_off_days_id']}})"
                                        class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                        title="{{__('Hủy')}}">
                                    <i class="la la-times-circle-o"></i>
                                </a>
                            @endif
                        @endif
                        
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}