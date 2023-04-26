@if($typeCustomer == 'deal')
    <option value="">{{ __('managerwork::managerwork.select_deal') }}</option>
@else
    <option value="">{{ __('managerwork::managerwork.select_customer') }}</option>
@endif
@foreach($arrCustomer as $item)
    @if($typeCustomer == 'lead')
        <option value="{{$item['customer_lead_id']}}" {{$detail != null && $detail['customer_id'] == $item['customer_lead_id'] ? 'selected' : ''}}>{{$item['full_name']}}</option>
    @elseif($typeCustomer == 'deal')
        <option value="{{$item['deal_id']}}" {{$detail != null && $detail['customer_id'] == $item['deal_id'] ? 'selected' : ''}}>{{$item['deal_name']}}</option>
    @elseif($typeCustomer == 'customer')
        <option value="{{$item['customer_id']}}" {{$detail != null && $detail['customer_id'] == $item['customer_id'] ? 'selected' : ''}}>{{$item['customer_name']}}</option>
    @endif
@endforeach