<div class="modal fade" id="edit-areas" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="    max-width: 700px;">
        <div class="modal-content">
            <div class="modal-header">
                <i class="fa fa-edit edit-icon"></i>
                <h2 class="modal-title" id="title" style=" color: #0067AC">{{__('CHỈNH SỬA KHU VỰC')}}</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="edited-areas">
                <input type="hidden" name="area_id" value="{{$item['area_id']}}">
                <div class="modal-body">
{{--                    <span class="note-font">{{__('Mã khu vực:')}}<b class="text-danger">*</b></span>--}}
{{--                    <div class="note-plus">--}}
{{--                        <div class="form-group m-form__group row">--}}
{{--                            <div class="col-lg-12">--}}
{{--                                <div class="form-group m-form__group" style="margin-bottom: 5px">--}}
{{--                                    <div class="input-group">--}}
{{--                                        <input id="config_content" name="area_code" type="text"--}}
{{--                                               class="form-control m-input class"--}}
{{--                                               placeholder='Nhập mã khu vực'--}}
{{--                                               value="{!! $item['area_code'] !!}"--}}
{{--                                               aria-describedby="basic-addon1">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <span class="note-font">{{__('Khu vực:')}}<b class="text-danger">*</b></span>
                    <div class="note-plus">
                        <div class="form-group m-form__group row">
                            <div class="col-lg-12">
                                <div class="form-group m-form__group" style="margin-bottom: 5px">
                                    <div class="input-group">
                                        <input id="config_content" name="name" type="text"
                                               class="form-control m-input class"
                                               placeholder='Nhập tên khu vực'
                                               value="{!! $item['area_name'] !!}"
                                               aria-describedby="basic-addon1">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <span class="note-font">{{__('Chi nhánh:')}}<b class="text-danger">*</b></span>
                    <div class="note-plus">
                        <div class="form-group m-form__group">
                            <select class="form-control select2" id="branch" style="    width: 650px;"
                                    name="branch_id" onchange="">
                            @foreach($getListBranch as $k => $v)
                                    <option value='{{$v['branch_id']}}' {{isset($item['branch_id']) && $item['branch_id'] == $v['branch_id'] ? 'selected' : ''}}>{{$v['branch_name']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <span class="note-font">{{__('Ghi chú:')}}<b class="text-danger">*</b></span>
                    <div class="note-plus">
                        <div class="form-group m-form__group row">
                            <div class="col-lg-12">
                                <div class="form-group m-form__group" style="margin-bottom: 5px">
                                    <div class="input-group">
                                        <input id="note-areas" name="note" type="text"
                                               class="form-control m-input class"
                                               placeholder='Nhập Ghi chú'
                                               value="{!! $item['area_note'] !!}"
                                               aria-describedby="basic-addon1">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="toggle-status">
                        <span class="note-font">{{__('Trạng thái:')}}</span>
                        <div style="display:flex;margin-top: 10px;">
                                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm"
                                              style="align-items: center;display: flex;}">
                                            <label style="margin: 0 0 0 0px; padding-top: 0px">
                                                <input type="checkbox"
                                                       checked class="manager-btn"
                                                       name="is_active">
                                                <span></span>
                                            </label>
                                        </span>
                            <span style="margin-top:5px"> &nbsp{{__(' (Chọn để kích hoạt trạng thái) ')}}</span>
                        </div>
                    </div>
                </div>
            </form>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="areas.cancel()">
                    <span class="la 	la-arrow-left"></span>
                    {{__('HỦY')}}
                </button>
                <button type="button" class="btn btn-primary" onclick="areas.saveEditAreas()">
                    <span class="la 		la-check"></span>
                    {{__('LƯU THÔNG TIN')}}
                </button>
            </div>
        </div>
    </div>
</div>