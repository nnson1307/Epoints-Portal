<div class="col-md-12 row form-group">
    <button type="button" class="btn m-btn--square btn-secondary m-btn--wide btnServiceType" data-type="service" @if(isset($_card)) data-id="{{$_card->service_card_id}}" @endif>
        <span class="m--margin-left-20 m--margin-right-20 ss--font-size-13" >{{__('Thẻ dịch vụ')}}</span>
    </button>
    <button type="button" class="btn m-btn--square active-btn btn-secondary m-btn--wide btnServiceType ss--font-weight" @if(isset($_card)) data-type="money" data-id="{{$_card->service_card_id}}" @endif>
        <span class="m--margin-left-30 m--margin-right-30 ss--font-size-13" >{{__('Thẻ tiền')}}</span>
    </button>
</div>

<div class="form-group">
    <label>{{__('Tiền trong tài khoản')}} : <span class="required">*</span></label>
    {!! Form::text("money",(isset($_card) ? number_format($_card->money, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0) : null),["class"=>"form-control",'autofocus']); !!}
    <span class="err error-money"></span>
</div>
