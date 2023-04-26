@extends('layout')

@section('title_header')
    <span class="title_header"><img src="{{ asset('static/backend/images/icon/icon-member.png') }}" alt=""
            style="height: 20px;"> @lang('survey::survey.create.survey_manager')</span>
@stop
@section('after_style')
    <style>
        .kt-radio.kt-radio--brand.kt-radio--bold>input:checked~span {
            border: 2px solid #000000 !important;
        }

        .kt-avatar.kt-avatar--circle .kt-avatar__holder {
            border-radius: 0% !important;
        }

        .ss--kt-avatar__upload {
            width: 20px !important;
            height: 20px !important;
        }

        .kt-checkbox.kt-checkbox--bold>input:checked~span {
            background: #4FC4CA;
            border: 2px solid #4FC4CA !important;
            border-radius: 3px !important;
        }

        .kt-checkbox>span:after {
            border: solid #fff;
        }

        .kt-radio.kt-radio--bold>input:checked~span {
            border: 2px solid #4FC4CA;
        }

        .kt-radio>span:after {
            border: solid #027177;
            background: #027177;
            margin-left: -4px;
            margin-top: -4px;
            width: 8px;
            height: 8px;
        }

        .kt-checkbox-fix {
            padding: 15px 15px;
        }

        .kt-checkbox-fix span {
            position: absolute;
            top: unset !important;
            bottom: -10px !important;
            left: 30px !important;
        }

        .primary-color {
            color: #027177 !important;
            font-weight: 500;
        }

        .form-control-feedback {
            color: red;
        }

        .m-radio>span:after {
            background: #4FC4CA !important;
            border: 1px solid #4FC4CA !important;
        }

        .m-radio>span {
            border: 1px solid #4FC4CA !important;
        }


        .kt-checkbox.kt-checkbox--bold span {
            border: 1px solid #4FC4CA;
        }

        .fw_title {
            font-weight: bold !important;
            color: #000000;
            font-size: 18px;
        }

        .m-portlet__head-text {
            font-weight: bold !important;
        }

        .form-control-feedback {
            font-weight: 400 !important;
            padding: 5px 0px !important;
        }

        .project-detail__tab {
            color: black;
        }

        .m-portlet--head-sm {
            margin-bottom: 5px !important;
        }

        .kt-portlet--mobile {
            margin: 0 !important;
        }
    </style>
@endsection
@section('after_css')
    <link href="{{ asset('static/backend/css/survey/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('static/backend/css/survey/vu-custom.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/sinh-custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/phu-custom.css') }}">
@endsection
@section('content')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-eye"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        {{ $project->manage_project_name }}
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="{{ route('manager-work.project') }}" class="btn btn-secondary btn-search ml-2"
                    style="color:black; border:1px solid">
                    <span class="m-portlet__head-icon">
                        <i class="la la-arrow-left"></i>
                    </span>
                    {{ __('TRỞ VỀ') }}
                </a>
            </div>
        </div>
    </div>
    <!-- Danh sách tab !-->
{{--    <div class="m-portlet m-portlet--head-sm">--}}
{{--        <div class="kt-portlet kt-portlet--mobile">--}}
{{--            <div class="kt-portlet__body">--}}
{{--                <div class="row form-group" style="margin:0px">--}}
{{--                    <div class="col-xl-12 col-lg-12">--}}
{{--                        <div class="btn-group btn-group project-detail__tab" role="group" aria-label="...">--}}
{{--                            <a href="{{ route('manager-work.project.show', ['id' => $project->manage_project_id]) }}"--}}
{{--                                class="btn btn-primary color_button btn-search kt-padding-l-40 kt-padding-r-40">--}}
{{--                                {{ __('Thông tin dự án') }}--}}
{{--                            </a>--}}
{{--                            <a href="{{ route('manager-project::work', ['manage_project_id' => $project->manage_project_id]) }}"--}}
{{--                                    class="btn btn-secondary kt-padding-l-40 kt-padding-r-40">--}}
{{--                                {{ __('Danh sách công việc') }}--}}
{{--                            </a>--}}
{{--                            <a href="javascript:void(0)"--}}
{{--                                    class="btn btn-secondary kt-padding-l-40 kt-padding-r-40">--}}
{{--                                {{ __('Biểu đồ Gantt') }}--}}
{{--                            </a>--}}
{{--                            <a href="{{ route('project.document.index', ['manage_project_id' => $project->manage_project_id]) }}"--}}
{{--                                    class="btn btn-secondary kt-padding-l-40 kt-padding-r-40">--}}
{{--                                {{ __('Tài liệu') }}--}}
{{--                            </a>--}}
{{--                            <a class="btn btn-secondary kt-padding-l-40 kt-padding-r-40"--}}
{{--                                href="{{ route('manager-work.project.member', $project->manage_project_id) }}">--}}
{{--                                {{ __('Thành viên') }}--}}
{{--                            </a>--}}
{{--                            <a class="btn btn-secondary kt-padding-l-40 kt-padding-r-40">--}}
{{--                                {{ __('Cấu hình vai trò') }}--}}
{{--                            </a>--}}
{{--                            <a class="btn btn-secondary kt-padding-l-40 kt-padding-r-40">--}}
{{--                                {{ __('Lịch sử hoạt động') }}--}}
{{--                            </a>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
    @include('manager-project::tab-project',['manage_project_id' => $project->manage_project_id])
    <!-- Thông tin dự án !-->
    <div class="m-portlet m-portlet--head-sm">
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__body">

                <div class="row project__detail--info">
                    <div class="m-portlet m-portlet--head-sm project__info--user">
{{--                        <p>{{ __('Người quản trị') }} : <a href="{{route('manager-project::work', ['manage_project_id' => $idProject, 'processor_id' => $project->manager->staff_id])}}">{{ $project->manager->full_name }}</a> </p>--}}
                        <p>{{ __('Khách hàng') }} :
{{--                            <a href="{{route('manager-project::work', ['manage_project_id' => $idProject, 'manage_work_customer_type' => $project['customer_type'],'customer_id' => $project->customer->customer_id])}}">--}}
                                {{ $project->customer ? $project->customer->full_name : '' }}
{{--                            </a>--}}
                        </p>
                        <p>{{ __('Thành viên') }}</p>
                        @if (count($project->listStaffs) > 0)
                            @foreach ($project->listStaffs as $key => $staffs)
                                <p>{{ $key }}:
                                    @foreach ($staffs as $staff)
                                        @if ($loop->last)
                                            <a href="{{route('manager-project::work', ['manage_project_id' => $idProject, 'processor_id' => $staff->staff_id])}}">{{ $staff->full_name }}</a>
                                        @else
                                            <a href="{{route('manager-project::work', ['manage_project_id' => $idProject, 'processor_id' => $staff->staff_id])}}">{{ $staff->full_name }}</a> ,
                                        @endif
                                    @endforeach
                                </p>
                            @endforeach
                        @endif
                    </div>

                    <div class="m-portlet m-portlet--head-sm project__info--main">
                        <table>
                            <tbody>
                                <!-- Trạng thái !-->
                                <tr>
                                    <td>{{ __('Trạng thái') }}</td>
                                    <td>

                                        <p class="status"
                                            style="background-color:{{ $project->status->manage_project_status_color }}">
                                            {{ $project->status->manage_project_status_name }}
                                        </p>
                                    </td>
                                </tr>
                                <!-- Tiến độ dự án !-->
                                <tr>
                                    <td>{{ __('Tiến độ dự án') }}</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar bg-warning progress-bar-striped"
                                                style="width:{{ $project->progressProject . '%' }}">
                                                {{ $project->progressProject }}%</div>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Thời gian hoạt động !-->
                                <tr>
                                    <td>{{ __('Thời gian hoạt động') }}</td>
                                    <td class="color-primary__info">
                                        {{ $project->date_start && $project->date_end
                                            ? \Carbon\Carbon::parse($project->date_start)->format('d/m/Y') .
                                                ' - ' .
                                                \Carbon\Carbon::parse($project->date_end)->format('d/m/Y')
                                            : '' }}
                                    </td>
                                </tr>
                                <!-- Ngày hoàn thành !-->
                                <tr>
                                    <td>{{ __('Ngày hoàn thành') }}</td>
                                    <td class="color-primary__info">
                                        {{ $project->status->manage_project_status_id == 6 && isset($project->date_finish) ? \Carbon\Carbon::parse($project->date_finish)->format('d/m/Y') : '' }}
                                    </td>
                                </tr>
                                <!-- Tổng thời gian thực hiện !-->
                                <tr>
                                    <td>{{ __('Tổng thời gian thực hiện') }}</td>
                                    <td class="color-primary__info">{{ $project->totalWorkTime . ' ' . __('Giờ') }}</td>
                                </tr>
                                <!-- Tags !-->
                                <tr>
                                    <td>{{ __('Tags') }}</td>
                                    <td>
                                        @if ($project->tags->count() > 0)
                                            @foreach ($project->tags as $item)
                                                @if ($loop->last)
                                                    <a href="">{{ $item->manage_tag_name }}</a>
                                                @else
                                                    <a href="">{{ $item->manage_tag_name }}</a> ,
                                                @endif
                                            @endforeach
                                        @endif
                                    </td>
                                </tr>
                                <!-- Tiền tố công việc !-->
                                <tr>
                                    <td>{{ __('Tiền tố công việc') }}</td>
                                    <td class="color-primary__info">{{ $project->prefix_code }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Mô tả dự án !-->
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h6 class="descrip-main__title">
                        {{ __('MÔ TẢ') }}
                    </h6>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <button class="btn" data-toggle="collapse" data-target="#moreDescription" aria-expanded="false"
                    aria-controls="moreDescription">
                    <span class="m-portlet__head-icon">
                        <i class="la la-angle-down"></i>
                    </span>
                </button>

            </div>
        </div>
        <div class="kt-portlet kt-portlet--mobile collapse show multi-collapse" id="moreDescription">
            <div class="kt-portlet__body">
                <div class="row">
                    <div class="col-lg-12">
                        {!! $project->manage_project_describe !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Tóm tắt !-->
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h6 class="descrip-main__title">
                        {{ __('TÓM TẮT') }}
                    </h6>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <button class="btn" data-toggle="collapse" data-target="#moreSummary" aria-expanded="false"
                    aria-controls="moreSummary">
                    <span class="m-portlet__head-icon">
                        <i class="la la-angle-down"></i>
                    </span>
                </button>

            </div>
        </div>
        <div class="kt-portlet kt-portlet--mobile collapse show multi-collapse" id="moreSummary">
            <div class="kt-portlet__body">
                <div class="table-responsive">
                    <table class="table table-striped m-table ss--header-table ss--nowrap">
                        <thead>
                            <tr>
{{--                                @foreach ($project->columDefault as $item)--}}
{{--                                    <th class="ss--font-size-th">{{ $item }}</th>--}}
{{--                                @endforeach--}}
                                <th class="ss--font-size-th">{{ __('Phòng ban') }}</th>
                                <th class="ss--font-size-th">{{ __('Tổng thành viên') }}</th>
                                <th class="ss--font-size-th">{{ __('Tổng công việc') }}</th>
                                @foreach($project->listStatus as $item)
                                    <th class="ss--font-size-th">{{ $item['manage_status_config_title'] }}</th>
                                @endforeach
                                <th class="ss--font-size-th">{{ __('Quá hạn') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($project->dataShow as $key => $item)
                                <tr>
                                    <td>{{$item['department_name']}}</td>
                                    <td>
                                        @if($key == 0)
                                            <a href="{{ route('manager-work.project.member', ['manage_project_id' => $idProject,'department_id' => $item['department_id'],'none_time' => true]) }}">
                                                {{$item['total_staff']}}</td>
                                            </a>
                                        @else
                                            <a href="{{ route('manager-work.project.member', ['manage_project_id' => $idProject,'department_id' => $item['department_id'],'manage_project_id' => $idProject,'none_time' => true]) }}">
                                                {{$item['total_staff']}}</td>
                                            </a>
                                        @endif

                                    <td>
                                        @if($key == 0)
                                            <a href="{{ route('manager-project::work', ['manage_project_id' => $idProject,'none_time' => true]) }}">
                                                {{$item['total_work']}}
                                            </a>
                                        @else
                                            <a href="{{ route('manager-project::work', ['department_id' => $key,'manage_project_id' => $idProject,'none_time' => true]) }}">
                                                {{$item['total_work']}}
                                            </a>
                                        @endif
                                    </td>
                                    @foreach($item['status'] as $keyStatus => $itemStatus)
                                        <td>
                                            @if($key == 0)
                                                <a href="{{ route('manager-project::work', ['manage_project_id' => $idProject,'manage_status_id[]' => $keyStatus,'none_time' => true]) }}">
                                                    {{$itemStatus}}
                                                </a>
                                            @else
                                                <a href="{{ route('manager-project::work', ['department_id' => $key,'manage_project_id' => $idProject,'manage_status_id[]' => $keyStatus,'none_time' => true]) }}">
                                                    {{$itemStatus}}
                                                </a>
                                            @endif

                                        </td>
                                    @endforeach
                                    <td>{{$item['total_overdue']}}</td>
                                </tr>
                            @endforeach
{{--                            <!-- Tổng !-->--}}
{{--                            <tr>--}}
{{--                                @if (!empty($project->totalInfoWorkDepartment))--}}
{{--                                    <td class=" ss--text-center ss--font-size-13">Tổng</td>--}}
{{--                                    @foreach ($project->totalInfoWorkDepartment as $item)--}}
{{--                                        @if (is_array($item))--}}
{{--                                            @foreach ($item as $key => $status)--}}
{{--                                                <td class=" ss--text-center ss--font-size-13">--}}
{{--                                                    <a target="_blank"--}}
{{--                                                        href="{{ route('manager-project::work', ['manage_project_id' => $idProject, 'manage_status_id[]' => [$key]]) }}">{{ $status }}</a>--}}
{{--                                                </td>--}}
{{--                                            @endforeach--}}
{{--                                        @elseif ($loop->last)--}}
{{--                                            <td class=" ss--text-center ss--font-size-13">--}}
{{--                                                <a href="{{ route('manager-project::work', ['manage_project_id' => $idProject]) }}">--}}
{{--                                                    {{ $item }}</a>--}}
{{--                                            </td>--}}
{{--                                        @else--}}
{{--                                            <td class=" ss--text-center ss--font-size-13">--}}
{{--                                                <a--}}
{{--                                                    href="{{ route('manager-project::work', ['manage_project_id' => $idProject]) }}">{{ $item }}</a>--}}

{{--                                            </td>--}}
{{--                                        @endif--}}
{{--                                    @endforeach--}}
{{--                                @endif--}}
{{--                            </tr>--}}
{{--                            <!-- Chi tiết !-->--}}
{{--                            @if (!empty($project->listTotalInfoWorkDepartment) > 0)--}}
{{--                                @foreach ($project->listTotalInfoWorkDepartment as $kd => $totalInfoWorkDepartment)--}}
{{--                                    <tr>--}}
{{--                                        @foreach ($totalInfoWorkDepartment as $key => $item)--}}
{{--                                            @if (strpos($key, 'status') == true)--}}
{{--                                                @php--}}
{{--                                                    $idStatus = (int) explode('_', $key)[0];--}}
{{--                                                @endphp--}}

{{--                                                <td class=" ss--text-center ss--font-size-13">--}}
{{--                                                    <a target="_blank"--}}
{{--                                                        href="{{ route('manager-project::work', ['department_id' => $kd, 'manage_project_id' => $idProject, 'manage_status_id[]' => (int)$idStatus]) }}">{{ $item }}</a>--}}
{{--                                                </td>--}}
{{--                                            @elseif($loop->last)--}}
{{--                                                <td class=" ss--text-center ss--font-size-13">--}}
{{--                                                    <a target="_blank"--}}
{{--                                                        href="{{ route('manager-project::work', ['department_id' => $kd, 'manage_project_id' => $idProject]) }}">{{ $item }}</a>--}}
{{--                                                </td>--}}
{{--                                            @else--}}
{{--                                                <td class=" ss--text-center ss--font-size-13">--}}
{{--                                                    <a target="_blank"--}}
{{--                                                        href="{{ route('manager-project::work', ['department_id' => $kd, 'manage_project_id' => $idProject]) }}">{{ $item }}</a>--}}
{{--                                                </td>--}}
{{--                                            @endif--}}
{{--                                        @endforeach--}}
{{--                                    </tr>--}}
{{--                                @endforeach--}}
{{--                            @endif--}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <input type="hidden" name="image" id="image" value="">
    @endsection

    @section('after_script')
        <script>
            $("#moreDescription").collapse("hide");
        </script>
    @endsection
