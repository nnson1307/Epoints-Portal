<div class="modal fade show" id="modal-call" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="la la-phone"></i>@lang('XÁC NHẬN CUỘC GỌI')
                </h5>
            </div>
            <form id="form-call">
                <div class="modal-body">
                    <div class="form-group m-form__group row">
                        <div class="col-lg-3">
                            <span class="font-weight-bold font-13">{{$item['phone']}}</span>
                        </div>
                        <div class="col-lg-2">
                            <a href="javascript:void(0)"
                               onclick="listDeal.call('{{$item['deal_id']}}', '{{$item['phone']}}')"
                               class="btn btn-outline-brand m-btn m-btn--icon btn-lg m-btn--icon-only m-btn--pill m-btn--air"
                               title="@lang('Gọi')">
                                <i class="la la-phone"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="m-form__actions m--align-right w-100">
                        <a href="javascript:void(0)" data-dismiss="modal"
                           class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                           <span>
                            <i class="la la-arrow-left"></i>
                               <span>{{__('HỦY')}}</span>
                           </span>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>