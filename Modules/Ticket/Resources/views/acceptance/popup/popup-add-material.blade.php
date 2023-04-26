<div class="modal fade" id="appendModelAdd" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="config_search">
                <div class="modal-header">
                    <h4 class="modal-title ss--title m--font-bold">
                        <i class="fa fa-plus ss--icon-title m--margin-right-5"></i>
                        {{ __('ticket::acceptance.product_incurred') }}
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="row m-0">
                        <div class="col-12">
                            <select class="form-control select2-popup select2-product-incurred" onchange="Acceptance.changeProductSelect()">
                                <option value="">{{ __('ticket::acceptance.select_product_incurred') }}</option>
                                @foreach ($listMaterial as $key => $value)
                                    <option value="{{ $value['product_id'] }}" data-code="{{ $value['product_code'] }}" data-name="{{ $value['product_name'] }}" data-money="{{ number_format($value['new_price'],0) }}" data-unit="{{ $value['unit_name'] }}">{{ $value['product_name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 pt-3">
                            <h5>@lang('Vật tư phát sinh')</h5>
                            <table class="table table-striped m-table m-table--head-bg-default mt-3" id="table-config">
                                <thead class="bg">
                                <tr>
                                    <th width="10%">#</th>
                                    <th width="20%">{{__('ticket::acceptance.product_incurred_code')}}</th>
                                    <th width="20%">{{__('ticket::acceptance.product_incurred_name')}}</th>
                                    <th width="30%" class="text-center">{{__('ticket::acceptance.quantity')}}</th>
                                    <th width="5%" class="text-center">{{__('ticket::acceptance.unit')}}</th>
                                    <th width="15%" class="text-center">{{__('ticket::acceptance.total')}} VNĐ</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody class="listProductMaterialIncurredPopup">
                                    <tr class="text-center listProductMaterialIncurredPopupNone">
                                        <td colspan="7">{{__('ticket::acceptance.no_data')}}</td>
                                    </tr>
                                </tbody>
                            </table>
{{--                            <button type="button" class="btn btn-primary color_button btn-search" style="display: block" onclick="Acceptance.addProductIncurred()">--}}
{{--                                <i class="fa fa-plus ic-search m--margin-left-5"></i> {{__('ticket::acceptance.add')}}--}}
{{--                            </button>--}}

                            <a href="javascript:void(0)" onclick="Acceptance.addProductIncurred()"
                               class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                                <span>
                                    <i class="fa fa-plus-circle"></i>
                                    <span> {{__('ticket::acceptance.add')}}</span>
                                </span>
                            </a>

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                    <div class="m-form__actions m--align-right">
                        <button data-dismiss="modal"
                            class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                            <span class="ss--text-btn-mobi">
                                <i class="la la-arrow-left"></i>
                                <span>{{ __('ticket::acceptance.cancel') }}</span>
                            </span>
                        </button>

                        <button type="button" onclick="Acceptance.saveProductIncurred()"
                            class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                            <span class="ss--text-btn-mobi">
                                <i class="la la-check"></i>
                                <span>{{ __('ticket::acceptance.save') }}</span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
