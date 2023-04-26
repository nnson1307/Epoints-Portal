<div id="add" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title title_index">
                    <i class="fa fa-plus-circle"></i> {{__('THÊM NHÓM DỊCH VỤ')}}
                </h4>

            </div>
            <form id="form">
                <div class="modal-body">

                    {{--{!! csrf_field() !!}--}}

                    <div class="form-group m-form__group">
                        <label>
                            {{__('Nhóm dịch vụ')}}:<b class="text-danger">*</b>
                        </label>
                        {{--{!! Form::text('branch_name',null,['class' => 'form-control m-input','id'=>'name']) !!}--}}
                        <input type="text" name="name" class="form-control m-input" id="name"
                               placeholder="{{__('Nhập tên nhóm dịch vụ')}}...">
                        <span class="error-name"></span>

                    </div>
                    <div class="form-group m-form__group">
                        <label>
                            {{__('Mô tả')}}:
                        </label>
                        {{--{!! Form::text('branch_name',null,['class' => 'form-control m-input','id'=>'name']) !!}--}}
                        <textarea rows="4" name="description" id="description" cols="31"
                                  class="form-control m-input" placeholder="{{__('Nhập thông tin mô tả')}}..."></textarea>
                    </div>
                    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                        <div class="m-form__actions m--align-right">
                            <button data-dismiss="modal"
                                    class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md ss--btn">
                                <span>
                                    <i class="la la-arrow-left"></i>
                                <span>{{__('HỦY')}}</span>
                                </span>
                            </button>

                            <button type="button" onclick="service.add_service_category(1)"
                                    class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<span>{{__('THÊM')}}</span>
							</span>
                            </button>
                            {{--<button type="button"--}}
                            {{--class="btn btn-success  dropdown-toggle dropdown-toggle-split m-btn m-btn--md"--}}
                            {{--data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
                            {{--</button>--}}
                            {{--<div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end"--}}
                            {{--style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(160px, 49px, 0px);">--}}
                            {{--<button type="submit" class="dropdown-item" href="javascript:void(0)"--}}
                            {{--onclick="service.add_service_category(0)"><i class="la la-plus"></i> Lưu &amp; Tạo mới--}}
                            {{--</button>--}}
                            {{--<button type="submit" class="dropdown-item" href="javascript:void(0)"--}}
                            {{--onclick="service.add_service_category(1)"><i class="la la-undo"></i> Lưu &amp; Đóng--}}
                            {{--</button>--}}
                            {{--</div>--}}

                        </div>
                    </div>
                    <input type="hidden" name="type_add" id="type_add" value="0">
                </div>
            </form>
        </div>

    </div>
</div>
