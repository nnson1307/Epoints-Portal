<style>
    .low-risk {
        border: 1px solid #FFF0F5;
        background: #33CC99;
        border-radius: 5px;
        color: green;
        font-weight: 600;
    }

    .normal-risk {
        border: 1px solid #FFF0F5;
        background: #99CCFF;
        border-radius: 5px;
        color: dodgerblue;
        font-weight: 600;
    }
    .m-portlet__head-update .nav-item .nav-link:hover {
        /*background-color: #00BCD4 !important;*/
    }
</style>

<div class="m-portlet__body" style="padding: 20px 0px">
    <div class="card-title">
        <h4 style="display:flex">
            <div style="color:#008B00">
                <i class="fa 	fa-eye"></i>
                <b>{{$info['project_name']}}</b>
            </div>
            @if(isset($info['project_status_name']))
                <b class="card-status" style="padding:5px 10px"> &#x2022; {{$info['project_status_name']}}</b>
            @endif
            @if(isset($info['important_name']) && $info['important_name'] == 'Quan trọng' )
                <b class="card-status-important" style=" padding-top: 4px;padding-right: 5px;">
                    &#x2022; {{__('Quan trọng')}}</b>
            @else
                <b class="card-status" style=" padding-top: 4px;padding-right: 5px;">
                    &#x2022; {{__('Bình thường')}}</b>
            @endif
            @if(isset($info['date_late']) && $info['date_late'] > 0)
                <b class="card-status-red" style=" padding-top: 4px;padding-right: 5px;"> &#x2022; {{__('Quá hạn ').$info['date_late'].__( ' ngày')}}</b>
            @else
                <b class="card-status" style=" padding-top: 4px;padding-right: 5px;"> &#x2022; {{__('Bình thường')}}</b>
            @endif
            <a class="card-status"
               style="    right: 1.5%;position: absolute;margin-top: -20px;    background-color: #0067AC;color: white;"
               title="Chỉnh sửa" href="{{route('manager-project.project.edit',['id' => $info['project_id']])}}">
                <i class="fa fa-pencil-alt"></i>
                {{__('CHỈNH SỬA')}}
            </a>
        </h4>
    </div>

    <div class="info-card" style="display:flex">
        <div class="col-6">
            <table class="table-hover">
                <tr>
                    <th>{{__('Ngày hoạt động')}}</th>
                    <td class="font-weight-bold">
                        {{isset($info['from_date']) ? \Illuminate\Support\Carbon::createFromFormat('Y-m-d' , $info['from_date'])->format('d/m/Y - ') : ''}}
                        {{isset($info['to_date']) ? \Illuminate\Support\Carbon::createFromFormat('Y-m-d' , $info['to_date'])->format('d/m/Y') : ''}}
                    </td>
                </tr>

                <tr>
                    <th>{{__('Người quản trị')}}</th>
                    @if(isset($info['manager']) && $info['manager'] != [])
{{--                        <td style="color:#5CACEE;font-weight: bold">{{$info['manager'][0]['manager_name']}}</td>--}}
                        <td style="color:#5CACEE;font-weight: bold">
                            <a target="_blank" href="{{route('admin.staff.show',['id' => $info['manager'][0]['manager_id']])}}">{{$info['manager'][0]['manager_name']}}</a>
                        </td>
                    @else
                        <td></td>
                    @endif
                </tr>
                <tr>
                    <th>{{__('Tags')}}</th>
                    <td>
                        @if(isset($info['tag']) && count($info['tag'])>0)
                            @foreach($info['tag'] as $key => $value)
                                <b class="card-status">{{$value['tag_name']}}</b>
                            @endforeach
                        @else
                            <b class=""></b>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-6">
            <div class="row" style="display:flex">
                <div class="col-6" style="    text-align: center;">
                    <p class="mb-0 font-weight-bold">{{__('Mức độ rủi ro')}}</p>
                    @if(isset($info['risk']))
                        @if($info['risk'] == 'high')
                            <p class="mb-0 hight-risk">{{__('Cao')}}</p>
                        @elseif($info['risk'] == 'low')
                            <p class="mb-0 low-risk">{{__('Thấp')}}</p>
                        @elseif($info['risk'] == 'normal')
                            <p class="mb-0 normal-risk">{{__('Bình thường')}}</p>
                        @endif
                    @else
                        <p class="mb-0 normal-risk">{{__('Bình thường')}}</p>
                    @endif
                </div>
                <div class="col-6" style="    text-align: center;">
                    <p class="mb-0 font-weight-bold">{{__('Tình trạng')}}</p>
                    @if($info['condition']['condition_name'] == 'Quá hạn')
                        <p class="mb-0 hight-risk">Quá hạn {{$info['date_late']}} ngày</p>
                    @else
                        <p class="mb-0 normal-risk">{{$info['condition']['condition_name']}}</p>
                    @endif
                </div>
            </div>
            <div class="row">
                <p class="mb-0 font-weight-bold" style="margin:15px">{{__('Tiến độ')}}</p>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="progress" style="width: 100%;height: 1.5rem;position: relative">
                        @if(isset($info['progress']) && $info['progress']!=null)
                            <div class="progress-bar font-weight-bold" role="progressbar"
                                 style="width: {{$info['progress']}}%;color: black !important;background-color: dodgerblue"
                                 title="{{' '.$info['progress']}}%"
                                 aria-valuenow="{{$info['progress']}}" aria-valuemin="0" aria-valuemax="100">
                                {{' '.$info['progress']}}%
                            </div>
                        @else
                            <div class="progress-bar font-weight-bold" role="progressbar"
                                 style="width: {{$info['progress']}}%;color: black !important;background-color: #e9ecef"
                                 aria-valuenow="{{$info['progress']}}" aria-valuemin="0" aria-valuemax="100">
                                &ensp; 0%
                            </div>
                        @endif

                        <span style="position: absolute;right: 10px;top: 0;bottom: 0;margin: auto;height: fit-content;font-weight: bold">100%</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <p class="mb-0 font-weight-bold" style="margin:15px">{{__('Nguồn lực')}}</p>
                <br>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="progress" style="width: 100%;height: 1.5rem;position: relative">
                        @if($info['resource_implement'] != 0 && ($info['resource'] != 0 && $info['resource'] != null))

                            <div class="progress-bar font-weight-bold" role="progressbar"
                                 style="width: {{$info['resource_implement']/$info['resource']*100}}%;color: black !important;background-color: dodgerblue"
                                 title="{{$info['resource_implement'].__(' ngày')}}"
                                 aria-valuenow="{{$info['resource_implement']/$info['resource']*100}}"
                                 aria-valuemin="0" aria-valuemax="100">{{$info['resource_implement'].__(' ngày')}}
                            </div>
                        @else
                            <div class="progress-bar font-weight-bold" role="progressbar"
                                 style="width: 0%;color: black !important;background-color: #0066cc"
                                 aria-valuenow="0"
                                 aria-valuemin="0" aria-valuemax="100">&ensp;0 {{__(' ngày')}}
                            </div>
                        @endif

                        <span style="position: absolute;right: 10px;top: 0;bottom: 0;margin: auto;height: fit-content;font-weight: bold">{{$info['resource'].__(' ngày')}}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
