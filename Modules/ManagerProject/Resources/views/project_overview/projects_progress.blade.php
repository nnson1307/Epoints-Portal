
<div class="row">
    <div class="column-status" style="background-color:#3399FF;">
        <p  class="mb-0">{{__('DỰ ÁN TRONG KÌ')}}</p>
        <p class="mb-0 number-status">{{$progressProjects['project_in_the_period']}}</p>
    </div>
    <div class="column-status" style="background-color:#FFCC00;">
        <p class="mb-0">{{__('NGUY CƠ TRỄ HẠN')}}</p>
        <p class="mb-0 number-status">{{$progressProjects['project_may_be_late']}}</p>
    </div>
    <div class="column-status" style="background-color:#66CC33;">
        <p class="mb-0">{{__('HOÀN THÀNH ĐÚNG HẠN')}}</p>
        <p class="mb-0 number-status">{{$progressProjects['project_on_time']}}</p>
    </div>
    <div class="column-status" style="background-color:#CC33FF;">
        <p class="mb-0">{{__('HOÀN THÀNH TRỄ HẠN')}}</p>
        <p class="mb-0 number-status">{{$progressProjects['project_complete_late']}}</p>
    </div>
    <div class="column-status" style="background-color:#FF3300;">
        <p class="mb-0">{{__('ĐÃ TRỄ HẠN')}}</p>
        <p class="mb-0 number-status">{{$progressProjects['project_late']}}</p>
    </div>
</div>

<style>
    .number-status{
        font-size: 35px
    }
    .column-status {
        float: left;
        width: 20%;
        padding: 10px;
        color: white;
        font-weight: bold;
        text-align: center;
    }
</style>

