<div id="add" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <style>
            .form-control-feedback {
                color: #ff0000;
            }

            .modal-lg {
                max-width: 60%;
            }
        </style>
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title title_index">
                    <i class="fa fa-plus-circle"></i> {{__('THÊM KHO')}}
                </h4>
            </div>
            <form id="form">
                <div class="modal-body">
                    {!! csrf_field() !!}
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>
                                    {{__('Tên kho')}}:<b class="text-danger">*</b>
                                </label>
                                {{--{!! Form::text('branch_name',null,['class' => 'form-control m-input','id'=>'name']) !!}--}}
                                <input type="text" name="name" class="form-control m-input btn-sm" id="house_name"
                                       placeholder="{{__('Hãy nhập tên kho')}}">
                                <span class="error-name"></span>
                                @if ($errors->has('name'))
                                    <span class="form-control-feedback">
                                     {{ $errors->first('name') }}
                                </span>
                                    <br>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>
                                    {{__('Số điện thoại')}}:<b class="text-danger">*</b>
                                </label>
                                <div class="row">
                                    <div class="form-group col-lg-12">
                                        <input type="text" name="phone" class="form-control m-input btn-sm" id="phone"
                                               placeholder="{{__('Hãy nhập số điện thoại')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>
                                    {{__('Địa chỉ')}}:<b class="text-danger">*</b>
                                </label>
                                <div class="row">
                                    <div class="col-lg-6 form-group">
                                        <select name="province" id="province" class="form-control m-input"
                                                style="width: 100%">
                                            @foreach($province as $key=>$value)
                                                <option value="{{$key}}">{{$value}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <select name="district" id="district" class="form-control m-input"
                                                style="width: 100%">
                                            @foreach($district as $item)
                                                <option value="{{$item['id']}}">{{$item['type'].' '. $item['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <select name="ward" id="ward" class="form-control m-input"
                                                style="width: 100%">
                                            <option value="">{{__('Chọn Phường/xã')}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <input type="text" name="address" class="form-control m-input btn-sm" id="house_address"
                                               placeholder="{{__('Hãy nhập địa chỉ')}}">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <labe>
                                    {{__('Chi nhánh')}}:<b class="text-danger">*</b>
                                </labe>
                                <div class="input-group">
                                    <select name="branch_id" id="house_branch_id" class="form-control m-input"
                                            style="width: 100%">
                                        <option value="">{{__('Chọn chi nhánh')}}</option>
                                        @foreach($branch as $key=>$value)
                                            <option value="{{$key}}">{{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <div class="col-lg-4">
                                    <label class="m-checkbox m-checkbox--air">
                                        <input id="is_retail" name="example_3" type="checkbox">
                                        {{__('Kho bán lẻ')}}
                                        <span></span>
                                    </label>
                                </div>
                                <div class="col-lg-8">
                                    <span class="text-danger error-is-retail"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>
                                    {{__('Mô tả')}}:
                                </label>
                                <textarea cols="30" rows="5" class="form-control m-input" name="description"
                                          id="house_description">
                                </textarea>
                            </div>
                        </div>
                    </div>


                    <input type="hidden" name="type_add" id="type_add" value="0">

                </div>
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

                            <button type="button" id="luu1" onclick="warehouse.add(1)"
                                    class="btn btn-success color_button  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<i class="la la-check"></i>
							<span>{{__('LƯU THÔNG TIN')}}</span>
							</span>
                            </button>

                            <button type="button" id="luu1" onclick="warehouse.add(0)"
                                    class="btn btn-success color_button  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
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
                            {{--<button type="submit" class="dropdown-item" id="luu" onclick="warehouse.add(0)"><i class="la la-plus"></i> Lưu &amp; Tạo mới--}}
                            {{--</button>--}}
                            {{--<button type="submit" class="dropdown-item"--}}
                            {{--id="luu" onclick="warehouse.add(1)"><i class="la la-undo"></i> Lưu &amp; Đóng--}}
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
