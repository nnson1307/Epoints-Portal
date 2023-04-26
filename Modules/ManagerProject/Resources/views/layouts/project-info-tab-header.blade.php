<ul class="nav nav-pills nav-fill nav-custom-new nav-custom-new-fix" role="tablist"
    style="margin-top: 12px;    width: 100%;">
    <li class="nav-item">
        <label onclick="redirectLink('project-info-overview')" class="nav-link {{\Request::route()->getName() == 'manager-project.project.project-info-overview' ? 'active' : ''}}">
            <a id="project-info-overview" href="{{route('manager-project.project.project-info-overview',['id' => $info['project_id']])}}"
               style="font-weight: bold">{{__('Tổng quan dự án')}}</a>
        </label>
    </li>
    <li class="nav-item">
        <label onclick="redirectLink('project-info-report')" class="nav-link {{\Request::route()->getName() == 'manager-project.project.project-info-report' ? 'active' : ''}}">
            <a id="project-info-report" href="{{route('manager-project.project.project-info-report',['id' => $info['project_id']])}}"
               style="font-weight: bold">{{__('Báo cáo')}}</a>
        </label>
    </li>
    <li class="nav-item">
        <label onclick="redirectLink('comment')" class="nav-link {{\Request::route()->getName() == 'manager-project.comment' ? 'active' : ''}}">
            <a id="comment" href="{{route('manager-project.comment',['id' => $info['project_id']])}}"
               style="font-weight: bold">{{__('Bình luận')}}</a>
        </label>
    </li>
    <li class="nav-item">
        <label onclick="redirectLink('work')" class="nav-link {{in_array(\Request::route()->getName(),['manager-project.work','manager-project.work.kanban-view']) ? 'active' : ''}}">
            <a id="work" href="{{route('manager-project.work',['manage_project_id' => $info['project_id']])}}"
               style="font-weight: bold">{{__('Danh sách công việc')}}</a>
        </label>
    </li>
    <li class="nav-item">
        <label onclick="redirectLink('document')" class="nav-link {{\Request::route()->getName() == 'manager-project.document' ? 'active' : ''}}">
            <a id="document" href="{{route('manager-project.document',['manage_project_id' => $info['project_id']])}}"
               style="font-weight: bold">{{__('Tài liệu')}}</a>
        </label>
    </li>
    <li class="nav-item">
        <label for="project-info-phase" class="nav-link {{\Request::route()->getName() == 'manager-project.project.project-info-phase' ? 'active' : ''}}">
            <a id="project-info-phase" href="{{route('manager-project.project.project-info-phase',['id' => $info['project_id']])}}"
               style="font-weight: bold">{{__('Giai đoạn')}}</a>
        </label>
    </li>
    <li class="nav-item">
        <label onclick="redirectLink('project-info-issue')" class="nav-link {{\Request::route()->getName() == 'manager-project.project.project-info-issue' ? 'active' : ''}} ">
            <a id="project-info-issue" href="{{route('manager-project.project.project-info-issue',['id' => $info['project_id']])}}"
               style="font-weight: bold">{{__('Vấn đề')}}</a>
        </label>
    </li>
    <li class="nav-item">
        <label onclick="redirectLink('member')" class="nav-link {{\Request::route()->getName() == 'manager-project.member' ? 'active' : ''}}">
            <a id="member" href="{{route('manager-project.member',['id' => $info['project_id']])}}"
               style="font-weight: bold">{{__('Thành viên')}}</a>
        </label>
    </li>
    <li class="nav-item">
        <label onclick="redirectLink('project-info-expenditure')" class="nav-link {{\Request::route()->getName() == 'manager-project.project.project-info-expenditure' ? 'active' : ''}}">
            <a id="project-info-expenditure" href="{{route('manager-project.project.project-info-expenditure',['id' => $info['project_id']])}}"
               style="font-weight: bold">{{__('Phiếu thu - chi')}}</a>
        </label>
    </li>
    <li class="nav-item">
        <label onclick="redirectLink('history')" class="nav-link {{\Request::route()->getName() == 'manager-project.history' ? 'active' : ''}}">
            <a id="history" href="{{route('manager-project.history',['manage_project_id' => $info['project_id']])}}"
               style="font-weight: bold">{{__('Lịch sử hoạt động')}}</a>
        </label>
    </li>

</ul>

<style>
    .nav-item .nav-link.active {
        font-weight: bold !important;
        background-color: #4fc4cb !important;
        color: white !important;
        border-radius: 0px;
    }

    /*.nav-custom-new .nav-item .nav-link.active, .nav-item:hover {*/
    .nav-custom-new .nav-item .nav-link.active {
        background-color: #00BCD4 !important;
    }
    .nav-link{
        width:100%;
        heigh : 100%
    }
</style>

<script>
    function redirectLink(type){
        var link = $('#'+type).attr('href');
        window.location.href = link;
    }

</script>