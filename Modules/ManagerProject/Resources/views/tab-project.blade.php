<div class="m-portlet m-portlet--head-sm">
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__body">
            <div class="row form-group" style="margin:0px">
                <div class="col-xl-12 col-lg-12">
                    <div class="btn-group btn-group project-detail__tab" role="group" aria-label="...">
                        <a href="{{ route('manager-project.project.project-info-overview', ['id' => $manage_project_id]) }}"
                           class="btn btn-secondary kt-padding-l-40 kt-padding-r-40  {{\Request::route()->getName() == 'manager-project.project.project-info-overview' ? 'color_button' : ''}}">
                            {{ __('Thông tin dự án') }}
                        </a>
                        <a href="{{ route('manager-project.work', ['manage_project_id' => $manage_project_id]) }}"
                           class="btn btn-secondary kt-padding-l-40 kt-padding-r-40 {{in_array(\Request::route()->getName(),['manager-project.work','manager-project.work.kanban-view']) ? 'color_button' : ''}} ">
                            {{ __('Danh sách công việc') }}
                        </a>
{{--                        <a href="javascript:void(0)"--}}
{{--                           class="btn btn-secondary kt-padding-l-40 kt-padding-r-40">--}}
{{--                            {{ __('Biểu đồ Gantt') }}--}}
{{--                        </a>--}}
                        <a href="{{ route('manager-project.document', ['manage_project_id' => $manage_project_id]) }}"
                           class="btn btn-secondary kt-padding-l-40 kt-padding-r-40 {{\Request::route()->getName() == 'manager-project.document' ? 'color_button' : ''}}">
                            {{ __('Tài liệu') }}
                        </a>
                        <a class="btn btn-secondary kt-padding-l-40 kt-padding-r-40 {{\Request::route()->getName() == 'manager-project.member' ? 'color_button' : ''}}"
                           href="{{ route('manager-project.member', ['id' => $manage_project_id]) }}">
                            {{ __('Thành viên') }}
                        </a>
{{--                        <a class="btn btn-secondary kt-padding-l-40 kt-padding-r-40">--}}
{{--                            {{ __('Cấu hình vai trò') }}--}}
{{--                        </a>--}}
                        <a class="btn btn-secondary kt-padding-l-40 kt-padding-r-40 {{\Request::route()->getName() == 'manager-project.history' ? 'color_button' : ''}}"
                           href="{{ route('manager-project.history', ['manage_project_id' => $manage_project_id]) }}">
                            {{ __('Lịch sử hoạt động') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
