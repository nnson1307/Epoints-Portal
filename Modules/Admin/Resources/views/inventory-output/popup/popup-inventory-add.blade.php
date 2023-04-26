<div class="modal fade" id="popup-add-inventory" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title ss--title m--font-bold">
                    <i class="fa fa-plus-circle ss--icon-title m--margin-right-5"></i>
                    {{__('TẠO PHIẾU XUẤT KHO')}}
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group m-form__group">
                    <input readonly id="code-inventory" type="hidden" name="pi_code" class="form-control m-input class" value="{{"NK".$code}}">
                    <input type="hidden" name="status" id="status" value="new">
                    <input type="hidden" name="normal" id="normal" value="normal">

                    <label>
                        <strong>{{__('Kho')}}</strong>: <b class="text-danger">*</b>
                    </label>
                    <div class="input-group">
                        <select id="warehouse" class="form-control m_selectpicker">
                            <option value="">{{__('Chọn nhà kho')}}</option>
                            @foreach($wareHouse as $key=>$value)
                                <option value="{{$key}}">{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                    <span class="errs error-warehouse"></span>
                </div>
                <div class="form-group m-form__group">
                    <label>
                        <strong>{{__('Nhập dữ liệu')}}:</strong>
                    </label>
                    <div class="col-12">
                        <div class="row">
                            <div class="col-10 pl-0">
                                <input class="form-control " readonly id="show" style="background-color: #fff" placeholder="{{__('Tải file mẫu bên dưới để nhập dữ liệu')}}">
                            </div>
                            <div class="col-2 pr-0">
                                <label for="file_excel" class="form-control btn-sm color_button son-mb pt-3">
                                    <i class="fas fa-download pr-2"></i> {{__('CHỌN TỆP')}}
                                </label>
                                <input accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                       id="file_excel" onchange="InventoryOut.fileName()" type="file" style="display: none">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group m-form__group">
                    <a href="{{ asset('static/backend/file/inventory/inventory_input.xlsx') }}"
                       class="btn btn-outline-accent m-btn m-btn--icon color_button">
                        <span>
                            <i class="la la-arrow-circle-down" style="color:#fff"></i>
                            <span style="color:#fff">{{__('TẢI FILE MẪU')}}</span>
                        </span>
                    </a>
                </div>
            </div>
            <form id="form-data-error" action="{{route('admin.inventory-input.export-add-inventory-input-error')}}" method="GET"></form>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                    <div class="m-form__actions m--align-right">
                        <button data-dismiss="modal"
                                class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                            <span class="ss--text-btn-mobi">
                                <i class="la la-arrow-left"></i>
                                <span>{{ __('HỦY') }}</span>
                            </span>
                        </button>
                        <button type="button" onclick="InventoryOut.addInventory()"
                                class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                            <span class="ss--text-btn-mobi">
                                <i class="la la-check"></i>
                                <span>{{ __('LƯU THÔNG TIN') }}</span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>