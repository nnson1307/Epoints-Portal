<div id="add" class="modal fade" role="dialog">
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
                    <i class="fa fa-plus-circle"></i> {{__('THÊM ĐƠN VỊ VẬN CHUYỂN')}}
                </h4>
            </div>
            <form id="form">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>
                                    {{__('Đơn vị vận chuyển')}}:<b class="text-danger">*</b>
                                </label>
                                <input type="text" name="transport_name" class="form-control m-input btn-sm"
                                       id="transport_name"
                                       placeholder="{{__('Nhập tên đơn vị vận chuyển')}}">
                                <span class="error-name"></span>
                                @if ($errors->has('transport_name'))
                                    <span class="form-control-feedback">
                                     {{ $errors->first('transport_name') }}
                                </span>
                                    <br>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>
                                    {{__('Người đại diện')}}:<b class="text-danger">*</b>
                                </label>
                                <input type="text" name="contact_name" class="form-control m-input btn-sm"
                                       id="contact_name"
                                       placeholder="{{__('Nhập người đại diện')}}">
                            </div>
                            <div class="form-group">
                                <label>
                                    {{__('Số điện thoại')}}:<b class="text-danger">*</b>
                                </label>
                                <input type="text" name="contact_phone" class="form-control m-input btn-sm"
                                       id="contact_phone"
                                       placeholder="{{__('Nhập số điện thoại')}}">
                            </div>
                            <div class="form-group">
                                <label>
                                    {{__('Địa chỉ')}}:<b class="text-danger">*</b>
                                </label>
                                <input type="text" name="address" class="form-control m-input btn-sm" id="address"
                                       placeholder="{{__('Nhập địa chỉ')}}">
                            </div>
                        </div>
                        <div class="col-lg-6">
{{--                            <div class="form-group">--}}
{{--                                <label>--}}
{{--                                    {{__('Chi phí giao hàng')}}:<b class="text-danger">*</b>--}}
{{--                                </label>--}}
{{--                                <input onkeypress="maskNumberPriceProductChild()" data-thousands="," type="text"--}}
{{--                                       name="charge" class="form-control m-input charge-add btn-sm" id="charge"--}}
{{--                                       placeholder="{{__('Nhập chi phí giao hàng')}}">--}}
{{--                            </div>--}}
                            <div class="form-group">
                                <label>
                                    {{__('Chức danh')}}:
                                </label>
                                <input type="text" name="contact_title" class="form-control m-input btn-sm"
                                       id="contact_title"
                                       placeholder="{{__('Nhập chức danh')}}">
                            </div>
                            <div class="form-group">
                                <label>
                                    {{__('Mô tả')}}:
                                </label>
                                <textarea rows="5" name="description" class="form-control m-input"
                                          id="description"></textarea>

                                @if ($errors->has('description'))
                                    <span class="form-control-feedback">
                                     {{ $errors->first('description') }}
                                </span>
                                    <br>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="type_add" id="type_add" value="0">
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

                            <button type="button" id="luu" onclick="transport.add(1)"
                                    class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<i class="la la-check"></i>
							<span>{{__('LƯU THÔNG TIN')}}</span>
							</span>
                            </button>

                            <button type="button" id="luu" onclick="transport.add(0)"
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
                            {{--<button type="submit" class="dropdown-item"--}}
                            {{--id="luu1" onclick="transport.add(0)"><i class="la la-plus"></i> Lưu &amp;--}}
                            {{--Tạo mới--}}
                            {{--</button>--}}
                            {{--<button type="submit" class="dropdown-item" id="luu" onclick="transport.add(1)">--}}
                            {{--<i class="la la-undo"></i> Lưu &amp; Đóng--}}
                            {{--</button>--}}
                            {{--</div>--}}

                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>
