<div class="modal fade" id="popup-list-serial" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form id="form-list-serial">
                <input type="hidden" name="product_code" value="{{$detailProduct['product_code']}}">
                <div class="modal-header">
                    <h4 class="modal-title ss--title m--font-bold">
                        <i class="fa fa-plus-circle ss--icon-title m--margin-right-5"></i>
                        {{__('Danh sách số seri sản phẩm:')}} {{$detailProduct['product_child_name']}}
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="ss--background">
                        <div class="row ss--bao-filter2">
                            <div class="col-lg-4">
                                <div class="form-group m-form__group">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="serial" name="serial"
                                               placeholder="{{__('Nhập số serial')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group m-form__group">
                                    <div class="input-group">
                                        <select name="inventory_checking_status_id" id="inventory_checking_status_id" class="form-control select2">
                                            <option value="">{{__('Chọn trạng thái')}}</option>
                                            @foreach($listStatus as $itemStatus)
                                                <option value="{{$itemStatus['inventory_checking_status_id']}}">{{$itemStatus['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group m-form__group">
                                    <div class="input-group">
                                        <select name="warehouse_id" id="warehouse_id" class="form-control select2">
                                            <option value="">{{__('Chọn chi nhánh')}}</option>
                                            @foreach($listWarehouse as $itemWarehouse)
                                                <option value="{{$itemWarehouse['warehouse_id']}}">{{$itemWarehouse['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="page" id="page_serial" value="1">
                            <div class="col-12 text-right pb-3">
                                <button type="button" onclick="detailProduct.removeSearchSerial()" class="btn btn-primary color_button btn-search">
                                    {{ __('XÓA BỘ LỌC') }}
                                    <i class="fa fa-eraser" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="btn btn-primary color_button btn-search" onclick="detailProduct.changePageSerial(1)">
                                    @lang('TÌM KIẾM') <i class="fa fa-search ic-search m--margin-left-5"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="block-list-serial mt-3">
                        @include('admin::product.append.list-serial')
                    </div>
                </div>
                <div class="modal-footer" style="justify-content: center">
                    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                        <div class="m-form__actions m--align-center">
                            <button data-dismiss="modal"
                                    class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                            <span class="ss--text-btn-mobi">
                                <span>{{ __('ĐÓNG') }}</span>
                            </span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>