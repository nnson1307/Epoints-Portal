<div class="border-bot">
    <table class="table table-striped m-table" id="table-time">
        <tbody style="font-size: 13px;white-space: nowrap;">
        @if(isset($LIST_BANNER))
            @foreach ($LIST_TIME as $key => $item)
                <tr class="tr_time" style="background-color: #fff;">
                    <td class="w-20">
                        {{__($item['vi_name'])}}
                        <input type="hidden" class="id_time" id="id_time" name="id_time" value="{{$item['id']}}">
                    </td>
                    <td>
                        @if ($item['is_actived'])
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           onclick="time_working.change_status(this, '{!! $item['id'] !!}')"
                                           checked class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                        @else
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           onclick="time_working.change_status(this, '{!! $item['id'] !!}')"
                                           class="manager-btn">
                                    <span></span>
                                </label>
                            </span>
                        @endif
                    </td>
                    <td>
                        <?php
                        $time = [
                            '0:15', '0:30', '0:45', '01:00', '01:15', '01:30', '01:45', '02:00', '02:15', '02:30', '02:45',
                            '02:45', '03:00', '03:15', '03:30', '03:45', '04:00', '04:15', '04:30', '04:45', '05:00', '05:15', '05:30', '05:45',
                            '06:00', '06:15', '06:30', '06:45', '07:00', '07:15', '07:30', '07:45', '08:00', '08:15', '08:30', '08:45', '09:00',
                            '09:15', '09:30', '09:45', '10:00', '10:15', '10:30', '10:45', '11:00', '11:15', '11:30', '11:45', '12:00', '12:15',
                            '12:30', '12:45', '13:00', '13:15', '13:30', '13:45', '14:00', '14:15', '14:30', '14:45', '15:00', '15:15', '15:30',
                            '15:45', '16:00', '16:15', '16:30', '16:45', '17:00', '17:15', '17:30', '17:45', '18:00', '18:15', '18:30', '18:45',
                            '19:00', '19:15', '19:30', '19:45', '20:00', '20:15', '20:30', '20:45', '21:00', '21:15', '21:30', '21:45', '22:00',
                            '22:15', '22:30', '22:45', '23:00', '23:15', '23:30', '23:45'
                        ];

                        ?>
                        <div class="row">
                            <div class="form-group m-form__group col-lg-4">
                                <select class="form-control start_time" id="start_time_{{$item['id']}}"
                                        name="start_time"
                                        style="width: 100%">
                                    <option></option>
                                    @foreach($time as $v)
                                        @if($v==date("H:i",strtotime($item['start_time'])))
                                            <option value="{{$v}}" selected>{{$v}}</option>
                                        @else
                                            <option value="{{$v}}">{{$v}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group m-form__group col-lg-1">
                                <span>{{__('Đến')}}</span>
                            </div>
                            <div class="form-group m-form__group col-lg-4">
                                <select class="form-control end_time" id="end_time_{{$item['id']}}" name="end_time"
                                        style="width: 100%">
                                    <option></option>
                                    @foreach($time as $v)
                                        @if($v==date("H:i",strtotime($item['end_time'])))
                                            <option value="{{$v}}" selected>{{$v}}</option>
                                        @else
                                            <option value="{{$v}}">{{$v}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <span class="error_time" style="color: red"></span>
                    </td>

                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
<div class="form-group m-form__group m--margin-top-10" style="text-align: right">
    <button type="submit" onclick="time_working.submit_edit()"
            class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md">
							<span>
							<i class="la la-check"></i>
							<span>{{__('LƯU THÔNG TIN')}}</span>
							</span>
    </button>
</div>

