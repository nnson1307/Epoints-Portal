{{-- <div class="modal fade show" id="add-group-potential"> --}}
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title">
                    <i class="fa fa-address-book"></i> {{__('Xác nhận gửi tin')}}
                </h5>
                {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
                {{--<span aria-hidden="true">×</span>--}}
                {{--</button>--}}
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-5">
                        {{__('Tên loại chiến dịch:')}}
                    </div>
                    <div class="col-md-7">
                        {{$type}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        {{__('Thời gian gửi:')}}
                    </div>
                    <div class="col-md-7">
                        @if($check_type == 1)
                            {{__('Gửi ngay')}}
                        @else
                            {{__('Vào lúc')}} {{$time_send}}
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        {{__('Tên chiến dịch:')}}
                    </div>
                    <div class="col-md-7">
                        {{$name_campaign}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        {{__('Số người nhận:')}}
                    </div>
                    <div class="col-md-7">
                        {{$number_get}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        {{__('Số tin gửi:')}}
                    </div>
                    <div class="col-md-7">
                        {{$number_send}}
                    </div>
                </div>
{{--                <div class="row">--}}
{{--                    <div class="col-md-5">--}}
{{--                        {{__('Chi phí mỗi tin:')}}--}}
{{--                    </div>--}}
{{--                    <div class="col-md-7">--}}
{{--                        {{ $template_price }} {{__('Đồng')}}--}}
{{--                    </div>--}}
{{--                </div>--}}
            </div>
            <div class="modal-footer">
                <div class="m-form__actions m--align-right w-100">
                    <button data-dismiss="modal"
                            class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                    </button>
                    <button type="button" onclick="AddCampaign.save()"
                            class="btn btn-info  color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<i class="la la-check"></i>
							<span>{{__('Đồng ý')}}</span>
							</span>
                    </button>

                </div>
            </div>
        </div>
    </div>
{{-- </div> --}}