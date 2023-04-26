<div id="editForm" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <style>
            .form-control-feedback {
                color: #ff0000;
            }
            .modal-lg {
                max-width: 60%;
            }
        </style>
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title title_index">
                    <i class="la la-edit"></i> {{__('CHỈNH SỬA NHÀ KHO')}}
                </h4>
            </div>
            <form id="formEdit">
                <div class="modal-body">
                    <input type="hidden" id="hhidden">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>
                                    {{__('Tên kho')}}:<b class="text-danger">*</b>
                                </label>
                                <input type="text" name="name" class="form-control m-input btn-sm" id="name"
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
                                        <input type="text" name="phone" class="form-control m-input btn-sm" id="phone_edit"
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
                                        <select name="province_edit" id="province_edit" class="form-control m-input"
                                                style="width: 100%">
                                            <option></option>
                                            {{--@foreach($province as $key=>$value)--}}
                                                {{--<option {{$key==79?'selected':''}} value="{{$key}}">{{$value}}</option>--}}
                                            {{--@endforeach--}}
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <select name="district_edit" id="district_edit" class="form-control m-input"
                                                style="width: 100%">
                                            {{--@foreach($district as $item)--}}
                                                {{--<option value="{{$item['id']}}">{{$item['type'].' '. $item['name'] }}</option>--}}
                                            {{--@endforeach--}}
                                        </select>
                                    </div>

                                    <div class="col-lg-6">
                                        <select name="ward_edit" id="ward_edit" class="form-control m-input"
                                                style="width: 100%">
                                            <option value="">{{__('Chọn Phường/xã')}}</option>
                                            {{--@foreach($district as $item)--}}
                                            {{--<option value="{{$item['id']}}">{{$item['type'].' '. $item['name'] }}</option>--}}
                                            {{--@endforeach--}}
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <input type="text" name="address" class="form-control m-input btn-sm" id="address"
                                               placeholder="{{__('Hãy nhập địa chỉ')}}">
                                        @if ($errors->has('address'))
                                            <span class="form-control-feedback">
                                     {{ $errors->first('address') }}
                                </span>
                                            <br>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>
                                    {{__('Chi nhánh')}}:<b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <select name="branch_id" id="h_branch_id" class="form-control m-input"
                                            style="width: 100%">
                                        <option value="">{{__('Tên chi nhánh')}}</option>
                                        @foreach($branch as $key=>$value)
                                            <option value="{{$key}}">{{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <div class="col-lg-4">
                                    <label class="m-checkbox m-checkbox--air">
                                        <input id="h_is_retail" name="example_3" type="checkbox">
                                        {{__('Kho bán lẻ')}}
                                        <span></span>
                                    </label>
                                </div>
                                <div class="col-lg-8">
                                    <span class="text-danger error-h-is-retail"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>
                                   {{__('Mô tả')}}:
                                </label>
                                <textarea cols="39" rows="5" class="form-control m-input" name="description"
                                          id="description">

                                </textarea>
                            </div>
                        </div>
                    </div>
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

                            <button type="submit" id="btnLuu"
                                    class="btn btn-primary color_button  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                                    <span>
                                    <i class="la la-edit"></i>
                                    <span>{{__('CẬP NHẬT')}}</span>
                                    </span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>
