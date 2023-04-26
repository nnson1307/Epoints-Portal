<div id="autotable">

    <form id="form-edit">
        {!! csrf_field() !!}
        <input type="hidden" id="district_hidden" name="district_hidden" value="{{$item['districtid']}}">
        <input type="hidden" id="id_hidden" name="id_hidden" value="{{$item['id']}}">
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group m-form__group">
                    <label class="black_title">
                        {{__('Tên đơn vị')}}:<b class="text-danger">*</b>
                    </label>
                    <input type="text" name="name" class="form-control m-input"
                           id="name" value="{{$item['name']}}"
                           placeholder="{{__('Nhập tên đơn vị')}}...">
                    <span class="error_name" style="color:red"></span>
                </div>
                <div class="form-group m-form__group">
                    <label class="black_title">
                        {{__('Mã đại diện')}}:<b class="text-danger">*</b>
                    </label>
                    <input type="text" name="code" class="form-control m-input"
                           id="code" value="{{$item['code']}}"
                           placeholder="{{__('Nhập mã đại diện')}}...">
                </div>
                <div class="form-group m-form__group">
                    <label class="black_title">
                        {{__('Số điện thoại')}}:<b class="text-danger">*</b>
                    </label>
                    <input type="text" name="phone" class="form-control m-input" id="phone"
                           placeholder="{{__('Hãy nhập số điện thoại')}}..." value="{{$item['phone']}}">
                </div>
                <div class="form-group m-form__group">
                    <label>
                        {{__('Email')}}:
                    </label>
                    <input type="text" name="email" class="form-control m-input" id="email"
                           placeholder="{{__('Hãy nhập email')}}..." value="{{$item['email']}}">
                    <span class="error_email" style="color: red"></span>
                </div>
                <div class="form-group m-form__group">
                    <label>
                        {{__('Hot line')}}:
                    </label>
                    <input type="text" name="hot_line" class="form-control m-input" id="hot_line"
                           placeholder="{{__('Hãy nhập hot line')}}..." value="{{$item['hot_line']}}">
                </div>
                <div class="form-group m-form__group">
                    <label>
                        Facebook Fanpage:
                    </label>
                    <input type="text" name="fanpage" class="form-control m-input" id="fanpage"
                           placeholder="{{__('Hãy nhập link fanpage')}}..." value="{{$item['fanpage']}}">
                </div>
                <div class="form-group m-form__group">
                    <label>
                        Zalo:
                    </label>
                    <input type="text" name="zalo" class="form-control m-input" id="zalo"
                           placeholder="{{__('Hãy nhập số zalo')}}..." value="{{$item['zalo']}}">
                </div>
                <div class="form-group m-form__group">
                    <label>
                        Instagram page:
                    </label>
                    <input type="text" name="instagram_page" class="form-control m-input"
                           id="instagram_page"
                           placeholder="{{__('Hãy nhập link instagram')}}..." value="{{$item['instagram_page']}}">
                </div>
                {{--<div class="row">--}}
                {{--<div class="col-md-2 ">--}}
                {{--<span class="m-switch m-switch--icon m-switch--success m-switch--sm">--}}
                {{--<label>--}}
                {{--<input id="is_actived" name="is_actived" type="checkbox"--}}
                {{--{{$item['is_actived']==1?'checked':''}}>--}}
                {{--<span></span>--}}
                {{--</label>--}}
                {{--</span>--}}
                {{--</div>--}}
                {{--<div class="col-md-10  m--margin-top-5">--}}
                {{--<i>{{__('Select to activate status')}}</i>--}}
                {{--</div>--}}
                {{--</div>--}}
            </div>
            <div class="col-lg-6">
                <div class="form-group m-form__group">
                    <label class="black_title">
                        {{__('Tỉnh/ Thành phố')}}:<b class="text-danger">*</b>
                    </label>
                    <div class="input-group">
                        <select class="form-control" style="width: 100%" id="provinceid" name="provinceid">
                            <option></option>
                            @foreach($optionProvince as $key=>$value)
                                @if($key==$item['provinceid'])
                                    <option value="{{$key}}" selected>{{$value}}</option>
                                @else
                                    <option value="{{$key}}">{{$value}}</option>
                                @endif

                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group m-form__group">
                    <label class="black_title">
                        {{__('Quận/ Huyện')}}:<b class="text-danger">*</b>
                    </label>
                    <div class="input-group">
                        <select class="form-control" style="width: 100%" id="districtid" name="districtid">
                            <option></option>
                        </select>
                    </div>
                </div>
                <div class="form-group m-form__group">
                    <label class="black_title">
                        {{__('Địa chỉ')}}:<b class="text-danger">*</b>
                    </label>
                    <input type="text" name="address" class="form-control m-input btn-sm" id="address"
                           placeholder="{{__('Hãy nhập địa chỉ')}}..." value="{{$item['address']}}">
                </div>
                <div class="form-group m-form__group">
                    <div class="row">
                        <div class="col-lg-3  w-col-mb-100">
                            <a href="javascript:void(0)"
                               onclick="document.getElementById('getFileInfo').click()"
                               class="btn  btn-sm m-btn--icon color">
                                            <span>
                                                <i class="la la-plus"></i>
                                                <span>
                                                    {{__('Thêm logo')}}
                                                </span>
                                            </span>
                            </a>
                        </div>

                        <div class="col-lg-9  w-col-mb-100 div_avatar">
                            <input type="hidden" id="logo_edit" name="logo_edit" value="{{$item['logo']}}">
                            <div class="wrap-img avatar">
                                @if($item['logo']!=null)
                                    <img class="m--bg-metal m-image img-sd" id="blah_info"
                                         src="{{$item['logo']}}"
                                         alt="{{__('Hình ảnh')}}" width="100px" height="100px">
                                    <span class="delete-img" style="display:block;">
                                                    <a href="javascript:void(0)"
                                                       onclick="spa_info.remove_avatar_edit()">
                                                        <i class="la la-close"></i>
                                                    </a>
                                                </span>
                                @else
                                    <img class="m--bg-metal m-image img-sd" id="blah_info"
                                         src="{{asset('static/backend/images/service-card/default/hinhanh-default3.png')}}"
                                         alt="{{__('Hình ảnh')}}" width="100px" height="100px">
                                    <span class="delete-img">
                                                    <a href="javascript:void(0)"
                                                       onclick="spa_info.remove_avatar_edit()">
                                                        <i class="la la-close"></i>
                                                    </a>
                                                </span>
                                @endif

                                <input type="hidden" id="logo" name="logo" value="">
                            </div>

                            <input accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg" id="getFileInfo"
                                   type="file" data-msg-accept="{{__('Hình ảnh không đúng định dạng')}}"
                                   onchange="uploadImage(this);" class="form-control"
                                   style="display:none">
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            Slogan:
                        </label>
                        <textarea rows="5" cols="40"
                                  name="slogan" id="slogan" class="form-control">{{$item['slogan']}}</textarea>
                    </div>
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            {{__('Ngành nghề kinh doanh')}}
                        </label>
                        <div class="input-group">
                            <select class="form-control" style="width: 100%" id="bussiness_id"
                                    name="bussiness_id">
                                <option></option>
                                @foreach($optionBussiness as $key=>$value)
                                    @if($item['bussiness_id']==$key)
                                        <option value="{{$key}}" selected>{{$value}}</option>
                                    @else
                                        <option value="{{$key}}">{{$value}}</option>
                                    @endif

                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="input-group">
                            <label class="m-checkbox m-checkbox--air m-checkbox--solid m-checkbox--state-success sz_dt">
                                <input type="checkbox" name="is_part_paid"
                                       id="is_part_paid" {{$item['is_part_paid'] == 1 ? 'checked' : ''}}> 
                                       {{__('Thanh toán nhiều lần')}}
                                <span></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group m-form__group">
                    <label class="black_title">
                        {{__('Chi nhánh nhận đơn hàng từ App')}}:
                    </label>
                    <select class="form-control" id="branch_apply_order" name="branch_apply_order" style="width:100%;">
                        <option></option>
                        @foreach($optionBranch as $k => $v)
                            <option value="{{$k}}" {{$item['branch_apply_order'] == $k ? 'selected' : ''}}>{{$v}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group m-form__group">
                    <label class="black_title">
                        {{__('Số lượng lịch hẹn tối đa trong khung giờ')}}:
                    </label>
                    <input type="number" class="form-control m-input btn-sm" id="total_booking_time"
                           name="total_booking_time"
                           placeholder="{{__('Hãy nhập địa chỉ')}}..." value="{{$item['total_booking_time']}}">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit w-100">
                <div class="m-form__actions m--align-right">

                    <button type="button" onclick="spa_info.submit_edit()"
                            class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>{{__('LƯU THÔNG TIN')}}</span>
                        </span>
                    </button>

                    {{--<button type="submit" onclick="branch.add(0)"--}}
                    {{--class="btn btn-success color_button  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">--}}
                    {{--<span>--}}
                    {{--<i class="fa fa-plus-circle"></i>--}}
                    {{--<span>{{__('LƯU & TẠO MỚI')}}</span>--}}
                    {{--</span>--}}
                    {{--</button>--}}


                </div>
            </div>
        </div>

    </form>

</div>
{{--@section("after_style")--}}
{{--<link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/config-page-appointment.css')}}">--}}
{{--@stop--}}
{{--@section('after_script')--}}
{{----}}
{{--@stop--}}


