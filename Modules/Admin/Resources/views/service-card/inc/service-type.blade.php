<div class="col-md-12 row form-group">
    <button type="button" class="btn m-btn--square active-btn ss--button-cms-piospa m-btn--wide btnServiceType"
            data-type="service" @if(isset($_card)) data-id="{{$_card->service_card_id}}" @endif>
        <span class="m--margin-left-20 m--margin-right-20 ss--font-size-13" >{{__('Thẻ dịch vụ')}}</span>
    </button>
    <button type="button" class="btn m-btn--square btn-secondary m-btn--wide btnServiceType" data-type="money"
            @if(isset($_card)) data-id="{{$_card->service_card_id}}" @endif>
        <span class="m--margin-left-30 m--margin-right-30 ss--font-size-13" >{{__('Thẻ tiền')}}</span>
    </button>
</div>
<div class="form-group">
    <label>{{__('Chọn dịch vụ')}}: </label>
    <select style="width: 100%" name="service_id" id="service_id" class="form-control">
        <option>{{__('Chọn dịch vụ')}}</option>
        @foreach($_service as $key=>$value)
            @if(isset($_card)&&$key==$_card->service_id)
                <option value="{{$key}}" selected>{{$value}}</option>
            @else
                <option value="{{$key}}">{{$value}}</option>
            @endif
        @endforeach
    </select>
    <span class="err error-service"></span>
    {{--{!! Form::select("service_id",$_service,(isset($_card) ? $_card->service_id : null),["class"=>"form-control","style"=>"width:100%"]) !!}--}}
</div>

{{--<div class="col-md-12 row form-group">--}}
{{--<label>{{__('Giá thẻ')}} : <span class="required">*</span></label>--}}
{{--{!! Form::text("price",(isset($_card) ? $_card->price : null),["class"=>"form-control"]); !!}--}}

{{--@if ($errors->has('price'))--}}
{{--<span class="form-control-feedback">--}}
{{--{{ $errors->first('price') }}--}}
{{--</span>--}}
{{--<br>--}}
{{--@endif--}}

{{--</div>--}}