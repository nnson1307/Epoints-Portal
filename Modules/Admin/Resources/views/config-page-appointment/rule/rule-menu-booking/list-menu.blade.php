<div class="table-responsive border-bot">
    <table class="table table-striped m-table" id="table-menu">
        <thead>
        <tr>
            <th></th>
            <th></th>
            <th style="max-width: 120px">{{__('Vị trí')}}</th>
        </tr>
        </thead>
        <tbody style="font-size: 13px;white-space: nowrap;">
        @if(isset($LIST_MENU))
            @foreach ($LIST_MENU as $key => $item)
                <tr class="tr_menu" style="background-color: #fff;">
                    <td style="width: 10%">
                        <input type="hidden" name="id_menu" class="id_menu" value="{{$item['id']}}">
                        @if ($item['is_actived'])
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           onclick="menu_booking.change_status_menu(this, '{!! $item['id'] !!}')"
                                           checked class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                        @else
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           onclick="menu_booking.change_status_menu(this, '{!! $item['id'] !!}')"
                                           class="manager-btn">
                                    <span></span>
                                </label>
                            </span>
                        @endif
                    </td>
                    <td style="vertical-align: middle;width: 15%">
                        @if($item['name']=='Đặt lịch hẹn')
                            <img src="{{asset('static/backend/images/icon/icon-calendar.png')}}" width="15px" height="15px">
                        @elseif($item['name']=='Dịch vụ')
                            <img src="{{asset('static/backend/images/icon/icon-services.png')}}" width="15px" height="15px">
                        @elseif($item['name']=='Sản phẩm')
                            <img src="{{asset('static/backend/images/icon/icon-product.png')}}" width="15px" height="15px">
                        @elseif($item['name']=='Giới thiệu')
                            <i class="la la-tag" style="vertical-align: middle;"></i>
                        @elseif($item['name']=='Chi nhánh')
                            <i class="la la-pencil" style="vertical-align: middle;"></i>
                        @elseif($item['name']=='Liên hệ')
                            <i class="la la-sitemap" style="vertical-align: middle;"></i>
                        @endif
                        {{__($item['name'])}}
                    </td>
                    <td>
                        <div class="form-group m-form__group">
                            <input type="text" class="form-control btn-sm position" name="position" value="{{$item['position']}}"
                                   style="width: 120px">
                        </div>
                        <span class="error_menu" style="color: red"></span>
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
<div class="form-group m-form__group m--margin-top-10" style="text-align: right">
    <button type="submit" onclick="menu_booking.edit_position()"
            class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md">
							<span>
							<i class="la la-check"></i>
							<span>{{__('LƯU THÔNG TIN')}}</span>
							</span>
    </button>
</div>

