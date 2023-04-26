<div class="modal fade show" id="modal-detail" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                     @lang('Chi tiết phiếu giao hàng')
                </h5>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <div class="col-lg-3">
                        @lang('Thời gian lấy hàng'):
                    </div>
                    <div class="col-lg-9">
                        <strong>{{$item['time_pick_up'] != null ? \Carbon\Carbon::parse($item['time_pick_up'])->format('d/m/Y H:i') : ''}}</strong>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-lg-3">
                        @lang('Hình ảnh lấy hàng'):
                    </div>
                    <div class="col-lg-9">
                        <img class="m--bg-metal m-image img-sd" id="blah"
                             src="{{$item['image_pick_up'] != null ? $item['image_pick_up'] : asset('static/backend/images/service-card/default/hinhanh-default3.png')}}"
                             alt="{{__('Hình ảnh')}}" width="100px" height="100px">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-lg-3">
                        @lang('Thời gian giao hàng'):
                    </div>
                    <div class="col-lg-9">
                        <strong>{{$item['time_drop'] != null ? \Carbon\Carbon::parse($item['time_drop'])->format('d/m/Y H:i') : ''}}</strong>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-lg-3">
                        @lang('Hình ảnh giao hàng'):
                    </div>
                    <div class="col-lg-9">
                        <img class="m--bg-metal m-image img-sd" id="blah"
                             src="{{$item['image_drop'] != null ? $item['image_drop'] : asset('static/backend/images/service-card/default/hinhanh-default3.png')}}"
                             alt="{{__('Hình ảnh')}}" width="100px" height="100px">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-lg-3">
                        @lang('Lý do hủy phiếu giao hàng'):
                    </div>
                    <div class="col-lg-9">
                        <strong>{{$item['reason_name']}}</strong>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit w-100">
                    <div class="m-form__actions m--align-right">
                        <button data-dismiss="modal"
                                class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md m--margin-right-10">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>