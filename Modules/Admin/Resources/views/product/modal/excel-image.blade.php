<div class="modal fade show" id="modal-excel">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> {{__('THÊM FILE')}}
                </h5>
                {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
                {{--<span aria-hidden="true">×</span>--}}
                {{--</button>--}}
            </div>
            <div class="modal-body">
                <div class="form-group bg">
                    <div class="row padding_row" style="padding-bottom: 10px;">
                        <div class="col-lg-8">
                            <input class="form-control btn-sm" readonly id="show" style="background-color: #fff">
                        </div>
                        <div class="col-lg-4">
                            <label for="file_excel" class="form-control btn-sm color_button son-mb">
                                {{__('CHỌN TỆP')}} <i class="la la-chain"></i>
                            </label>
                            <input accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                   id="file_excel" onchange="product.showNameFile()" type="file" style="display: none">
                        </div>
                    </div>
                </div>
                <div class="form-group m-form__group">
{{--                    <a href="{{ route('admin.customer.export-excel') }}"--}}
{{--                       class="btn btn-outline-accent btn-sm m-btn m-btn--icon color_button">--}}
{{--                        <span>--}}
{{--                            <i class="la la-arrow-circle-down" style="color:#fff"></i>--}}
{{--                            <span style="color:#fff">{{__('TẢI FILE MẪU')}}</span>--}}
{{--                        </span>--}}
{{--                    </a>--}}
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-form__actions m--align-right w-100">
                    <button data-dismiss="modal"
                            class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HUỶ')}}</span>
						</span>
                    </button>
                    <button type="submit"
                            onclick="product.import()"
                            class="btn btn-info color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<i class="la la-check"></i>
							<span>{{__('ĐỒNG Ý')}}</span>
							</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>