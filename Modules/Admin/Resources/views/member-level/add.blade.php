<div id="add1" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <style>
            .form-control-feedback {
                color: #ff0000;
            }
        </style>
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title title_index">
                    <i class="fa fa-plus-circle"></i> {{__('THÊM CẤP ĐỘ THÀNH VIÊN')}}
                </h4>
                {{--<button type="button" class="close" data-dismiss="modal">&times;</button>--}}
            </div>
            <form id="form">
                <div class="modal-body">
                    <div class="form-group">
                        <label>
                            {{__('Tên cấp độ thành viên')}}:<b class="text-danger">*</b>
                        </label>
                        <input placeholder="{{__('Nhập tên cấp độ')}}" type="text" name="name" class="form-control btn-sm m-input"
                               id="name">
                        <span class="error-name"></span>
                    </div>

                    <div class="form-group">
                        <label>
                            {{__('Điểm quy đổi cấp độ')}}:<b class="text-danger">*</b>
                        </label>
                        <input placeholder="{{__('Nhập điểm quy đổi')}}" onkeydown="onKeyDownInput(this)" type="text" name="point"
                               class="form-control m-input btn-sm" id="point">
                        <br>
                        @if ($errors->has('seat'))
                            <span class="form-control-feedback">
                                     {{ $errors->first('point') }}
                                </span>
                            <br>
                        @endif
                    </div>
                    <div class="row" style="display:none;">
                        <div class="form-group row col-12">
                            <label class="col-sm-4">
                                {{__('Trạng thái')}}
                            </label>
                            <div class="col-lg-8">
                                <label class="m-checkbox">
                                    <input type="checkbox" checked name="is_actived" id="is_actived" value="1"> {{__('Hoạt động')}}
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="type_add" name="type_add" value="0">
                <div class="modal-footer">
                    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                        <div class="m-form__actions m--align-right">
                            <button data-dismiss="modal"
                                    class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                            </button>


                            <button type="submit" onclick="member_level.add(1)"
                                    class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<i class="la la-check"></i>
							<span>{{__('LƯU THÔNG TIN')}}</span>
							</span>
                            </button>
                            <button type="submit" onclick="member_level.add(0)"
                                    class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<i class="fa fa-plus-circle"></i>
							<span>{{__('LƯU & TẠO MỚI')}}</span>
							</span>
                            </button>
                            {{--<button type="button"--}}
                            {{--class="btn btn-success  dropdown-toggle dropdown-toggle-split m-btn m-btn--md"--}}
                            {{--data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
                            {{--</button>--}}
                            {{--<div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end"--}}
                            {{--style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(160px, 49px, 0px);">--}}
                            {{--<button type="submit" class="dropdown-item" href="javascript:void(0)"--}}
                            {{--onclick="member_level.add(0)"><i class="la la-plus"></i> Lưu &amp; Tạo mới--}}
                            {{--</button>--}}
                            {{--<button type="submit" class="dropdown-item" href="javascript:void(0)"--}}
                            {{--onclick="member_level.add(1)"><i class="la la-undo"></i> Lưu &amp; Đóng--}}
                            {{--</button>--}}
                            {{--<div class="dropdown-divider"></div>--}}
                            {{--<button class="dropdown-item" data-dismiss="modal"><i class="la la-close"></i> Hủy--}}
                            {{--</button>--}}
                            {{--</div>--}}

                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>
