<div class="row">
    <div class="col-12 block_hot_1 text-center">
        <p class="mb-0"><strong>{{__('NHÂN VIÊN CHƯA BẮT ĐẦU CÔNG VIỆC TRONG NGÀY (:listStaff)',['listStaff' => count($list_staff_no_started_work)])}}</strong></p>
    </div>
    @if(count($list_staff_no_started_work) != 0)
        <div class="col-12 block_hot_2">
            <form id="list_staff_not_start_work">
                <div class="text-right mb-3">
                    <button type="button" class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm" onclick="StaffOverview.remindStaffNotStartWork()"><i class="fas fa-plus-circle"></i> {{__('Nhắc nhở')}}</button>
                </div>
                <table class="w-100">
                    <thead>
                    <tr>
                        <th width="10%"></th>
                        <th width="70%"></th>
                        <th width="20%"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($list_staff_no_started_work as $item)
                        <tr style="height: 40px">
                            <td>
                                <img tabindex="-1" class="w-100" style="width: 25px;height: 25px;border-radius: 50%" src="{{$item['staff_avatar']}}"
                                     onerror="this.src='https://ui-avatars.com/api/?background=5867dd&color=FFFFFF&rounded=true&name={{strtoupper(substr(str_slug($item['staff_name']),0,1))}}';">
                            </td>
                            <td class="pl-2">{{$item['staff_name']}}</td>
                            <td class="text-right">
                                <label class="m-checkbox m-checkbox--state-success mt-0">
                                    <input type="checkbox" class="staff_not_start_work" name="staff_not_start_work[]" value="{{$item['staff_id']}}">
                                    <span></span>
                                </label>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </form>
        </div>
    @endif
</div>