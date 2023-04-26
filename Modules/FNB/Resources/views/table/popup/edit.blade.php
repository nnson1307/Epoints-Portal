<div class="modal fade" id="edit-table" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="    max-width: 700px;">
        <div class="modal-content">
            <div class="modal-header">
                <i class="fa fa-edit edit-icon"></i>
                <h2 class="modal-title" id="title" style=" color: #0067AC">{{__('CHỈNH SỬA BÀN')}}</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="edited-table">
                <input type="hidden" name="table_id" value="{{$item['table_id']}}">
                <div class="modal-body">
                    <span class="note-font">{{__('Khu vực:')}}<b class="text-danger">*</b></span>
                    <div class="note-plus">
                        <div class="form-group m-form__group">
                            <select class="form-control select2" id="area_id" style="    width: 650px;"
                                    name="area_id" onchange="">
                                <option value="{!! $item['area_id'] !!}">{{__('Chọn khu vực')}}</option>
                                @foreach($listAreas as $k => $v)
                                    <option value='{{$v['area_id']}}' {{isset($item['area_id']) && $item['area_id'] == $v['area_id'] ? 'selected' : ''}}>{{$v['area_name']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
{{--                    <span class="note-font">{{__('Mã bàn:')}}<b class="text-danger">*</b></span>--}}
{{--                    <div class="note-plus">--}}
{{--                        <div class="form-group m-form__group row">--}}
{{--                            <div class="col-lg-12">--}}
{{--                                <div class="form-group m-form__group" style="margin-bottom: 5px">--}}
{{--                                    <div class="input-group">--}}
{{--                                        <input id="config_content" name="code" type="text"--}}
{{--                                               class="form-control m-input class"--}}
{{--                                               placeholder='{{__('Nhập mã bàn')}}'--}}
{{--                                               value="{!! $item['code'] !!}"--}}
{{--                                               aria-describedby="basic-addon1">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <span class="note-font">{{__('Tên bàn:')}}<b class="text-danger">*</b></span>
                    <div class="note-plus">
                        <div class="form-group m-form__group row">
                            <div class="col-lg-12">
                                <div class="form-group m-form__group" style="margin-bottom: 5px">
                                    <div class="input-group">
                                        <input id="config_content" name="name" type="text"
                                               class="form-control m-input class"
                                               placeholder='{{__('Nhập tên bàn')}}'
                                               value="{!! $item['table_name'] !!}"
                                               aria-describedby="basic-addon1">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <span class="note-font">{{__('Số ghế:')}}<b class="text-danger">*</b></span>
                    <div class="note-plus">
                        <div class="form-group m-form__group row">
                            <div class="col-lg-12">
                                <div class="form-group m-form__group" style="margin-bottom: 5px">
                                    <div class="input-group">
                                        <input id="config_content" name="seats" type="number"
                                               class="form-control m-input class"
                                               placeholder='{{__('Nhập số ghế')}}'
                                               value="{!! $item['seats'] !!}"
                                               aria-describedby="basic-addon1">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <span class="note-font">{{__('Ghi chú:')}}<b class="text-danger">*</b></span>
                    <div class="note-plus">
                        <div class="form-group m-form__group row">
                            <div class="col-lg-12">
                                <div class="form-group m-form__group" style="margin-bottom: 5px">
                                    <div class="input-group">
                                        <input id="note-areas" name="description" type="text"
                                               class="form-control m-input class"
                                               placeholder='Nhập ghi chú'
                                               value="{!! $item['description'] !!}"
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
                <button type="button" class="btn btn-secondary" onclick="Table.cancel()">
                    <span class="la 	la-arrow-left"></span>
                    {{__('HỦY')}}
                </button>
                <button type="button" class="btn btn-primary" onclick="Table.saveEditTable()">
                    <span class="la 		la-check"></span>
                    {{__('LƯU THÔNG TIN')}}
                </button>
            </div>
        </div>
    </div>
</div>