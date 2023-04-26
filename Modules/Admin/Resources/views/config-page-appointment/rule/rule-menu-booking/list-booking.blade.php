<div class="table-responsive">
    <table class="table table-striped m-table" id="table-booking">
        <tbody style="font-size: 13px;white-space: nowrap;">
        @if(isset($LIST_BOOKING))
            @foreach ($LIST_BOOKING as $key => $item)
                <tr class="tr_menu" style="background-color: #fff;">
                    <td style="width: 10%">
                        <input type="hidden" name="id_booking" class="id_booking" value="{{$item['id']}}">
                        @if ($item['is_actived'])
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           onclick="menu_booking.change_status_booking(this, '{!! $item['id'] !!}')"
                                           checked class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                        @else
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           onclick="menu_booking.change_status_booking(this, '{!! $item['id'] !!}')"
                                           class="manager-btn">
                                    <span></span>
                                </label>
                            </span>
                        @endif
                    </td>
                    <td style="vertical-align: middle;">
                        @if($item['name']=='Thông tin cá nhân')
                            <i class="la la-street-view" style="vertical-align: middle;"></i>
                        @elseif($item['name']=='Dịch vụ')
                            <img src="{{asset('static/backend/images/icon/icon-services.png')}}" width="15px" height="15px">
                        @elseif($item['name']=='Kỹ thuật viên')
                            <img src="{{asset('static/backend/images/icon/icon-staff.png')}}" width="15px" height="15px">
                        @elseif($item['name']=='Thời gian')
                            <i class="la la-clock-o" style="vertical-align: middle;"></i>
                        @elseif($item['name']=='Chi nhánh')
                            <i class="la la-pencil" style="vertical-align: middle;"></i>
                        @elseif($item['name']=='Xác nhận')
                            <i class="la la-check" style="vertical-align: middle;"></i>
                        @endif
                        {{__($item['name'])}}
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>


