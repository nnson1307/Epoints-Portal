<div class="statistical" style="padding: 0 10px;    font-weight: 500;">
    <table style="border:1px solid white">
        <td>
            <i class="fa fa-users style-icon-statistical"></i>
            <div class="inline-block">
                <p class="fs-15 mb-0 text-center">
                    <a href="{{route('manager-project.member',['id' => $info['project_id']])}}" title="{{__('Thông tin thành viên')}}">
                        {{__('Thành viên')}}
                    </a>
                </p>
                <p class="mb-0 font-weight-bold" style="font-size: 20px;text-align: center;">{{isset($info['member']) ? $info['member'] : 0 }}</p>
            </div>
        </td>
        <td>
            <i class="fa fa-file-alt style-icon-statistical"></i>
            <div class="inline-block">
                <p class="fs-15 mb-0 text-center">
                    <a href="{{route('manager-project.work',['manage_project_id' => $info['project_id']])}}" title="{{__('Thông tin danh sách công việc')}}">
                        {{__('Công việc')}}
                    </a>
                </p>
                <p class="mb-0 font-weight-bold" style="font-size: 20px;text-align: center;">{{isset($info['work']) ? $info['work'] : 0 }}</p>
            </div>
        </td>
        <td>
            <i class="fa fa-book style-icon-statistical"></i>
            <div class="inline-block">
                <p class="fs-15 mb-0 text-center">
                    <a href="{{route('manager-project.document',['manage_project_id' => $info['project_id']])}}" title="{{__('Danh sách tài liệu')}}">
                        {{__('Tài liệu')}}
                    </a>
                </p>
                <p class="mb-0 font-weight-bold" style="font-size: 20px;text-align: center;">{{isset($info['document']) ? $info['document'] : 0 }}</p>
            </div>
        </td>
        <td>
            <i class="fa fa-dollar-sign style-icon-statistical"></i>
            <div class="inline-block">
                <p class="fs-15 mb-0 text-center">
                    <a href="{{route('manager-project.project.project-info-expenditure',['id' => $info['project_id']])}}" title="{{__('Thông tin ngân sách')}}">
                        {{__('Ngân sách')}}
                    </a>
                </p>
                <p class="mb-0 font-weight-bold" style="font-size: 20px;text-align: center;">{{isset($info['budget']) ? number_format($info['budget']) : 0 }} {{__('VNĐ')}}</p>
            </div>
        </td>
        <td>
            <i class="fa 	fa-calendar-alt style-icon-statistical"></i>
            <div class="inline-block">
                <p class="fs-15 mb-0 text-center" style="color:#5867dd" title="{{__('Nguồn lực')}}">{{__('Nguồn lực')}}</p>
                <p class="mb-0 font-weight-bold" style="font-size: 20px;text-align: center;">{{isset($info['resource']) ? $info['resource'].__(' ngày') : 0 }}</p>
            </div>
        </td>
    </table>
</div>
