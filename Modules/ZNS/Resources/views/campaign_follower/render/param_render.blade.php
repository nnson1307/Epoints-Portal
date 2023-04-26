@php
$arr = [
    'shipping_method',
    'payment_method',
    'number',
    'date',
    'name_spa',
    'product',
 ];
@endphp
@foreach($params as $param)
    @if(in_array($param->value,$arr))
        <div class="row  m-3 ml-0">
            <div class="col-md-6 col-sm-12">
                <span class="p-3 bg-secondary">{{$param->value}}</span>
            </div>
            <div class="col-md-6 col-sm-12">
                @if($param->type == "NUMBER")
                    <input type="number" name="params[{{$param->value}}]" minlength="{{$param->min_length}}" maxlength="{{$param->max_length}}" class="form-control">
                @elseif($param->type == "STRING")
                    <input type="text" name="params[{{$param->value}}]" minlength="{{$param->min_length}}" maxlength="{{$param->max_length}}" class="form-control">
                @elseif($param->type == "DATE")
                    <div class="m-input-icon m-input-icon--right w-50">
                        <input readonly class="form-control m-input daterange-picker date-time" name="params[{{$param->value}}]"
                               autocomplete="off" placeholder="{{ __('Ngày') }}">
                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                            <span><i class="la la-calendar"></i></span></span>
                    </div>
                @endif
            </div>
        </div>
    @endif
@endforeach
