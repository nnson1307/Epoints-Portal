<option value="">{{ __('Chọn vật tư phát sinh') }}</option>
@foreach ($listMaterial as $key => $value)
    <option value="{{ $value['product_id'] }}" data-code="{{ $value['product_code'] }}" data-name="{{ $value['product_name'] }}" data-money="{{ number_format($value['new_price'],0) }}" data-unit="{{ $value['unit_name'] }}">{{ $value['product_name'] }}</option>
@endforeach