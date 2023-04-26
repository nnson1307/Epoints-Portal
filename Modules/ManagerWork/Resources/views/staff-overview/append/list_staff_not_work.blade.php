<div class="row">
    <div class="col-12 block_hot_1 text-center">
        <p class="mb-0"><strong>{{__('NHÂN VIÊN CHƯA CÓ CÔNG VIỆC TRONG NGÀY (:listStaff)',['listStaff' => count($list_staff_no_job)])}}</strong></p>
    </div>
    @if(count($list_staff_no_job) != 0)
        <div class="col-12 block_hot_2">

            <table class="mt-5">
                <thead>
                <tr>
                    <th width="10%"></th>
                    <th width="90%"></th>
                    <th width="20%"></th>
                </tr>
                </thead>
                <tbody>
                @foreach($list_staff_no_job as $item)
                    <tr style="height: 40px">
                        <td>
                            <img tabindex="-1" class="w-75" style="width: 25px;height: 25px;border-radius: 50%;margin-right: 5px" src="{{$item['staff_avatar']}}"
                                 onerror="this.src='https://ui-avatars.com/api/?background=5867dd&color=FFFFFF&rounded=true&name={{strtoupper(substr(str_slug($item['staff_name']),0,1))}}';">
                        </td>
                        <td>{{$item['staff_name']}}</td>
                        <td class="text-right"><i class="fas fa-plus plus-icon-overview" onclick="WorkChild.showPopup({{$item['staff_id']}})" style="color:#0067AC"></i> </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>