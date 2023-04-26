
@if(isset($listJourneyDefault) && count($listJourneyDefault) > 0)
<div class="append-journey sortable ui-sortable">
    @foreach($listJourneyDefault as $key => $value)
        @if($key == 1)

        @endif
        <div class="row mt-2 count-journey">
            <div class="col-4">
                <input type="text" class="form-control m-input journey_name"
                       name="journey_name_default_{{$key+1}}" value="{{$value['pipeline_journey_default_name']}}" disabled>
                <input type="hidden" class="journey_default_code"
                       value="{{$value['pipeline_journey_default_code']}}">
            </div>
            <div class="col-1">
                <label>
                    @if($value['pipeline_journey_default_code'] == 'PJD_CUSTOMER_NEW')
                        {{ __('Mới') }}
                    @elseif($value['pipeline_journey_default_code'] == 'PJD_DEAL_START')
                        {{ __('Bắt đầu') }}
                    @elseif($value['pipeline_journey_default_code'] == 'PJD_CUSTOMER_FAIL')
                        {{ __('Thất bại') }}
                    @elseif($value['pipeline_journey_default_code'] == 'PJD_CUSTOMER_SUCCESS')
                        {{ __('Thành công') }}
                    @elseif($value['pipeline_journey_default_code'] == 'PJD_DEAL_END')
                        {{ __('Kết thúc') }}
                    @endif
                </label>
            </div>
            <div class="col-4">
                <select class="form-control status" name="journey_status"
                        style="width:100%" multiple="multiple" disabled>
                    <option></option>
                    @foreach($listJourneyDefault as $key2 => $value2)
                        @if($key != $key2)
                            <option value="{{$value2['pipeline_journey_default_name']}}">
                                {{$value2['pipeline_journey_default_name']}}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="col-1" {{$value['pipeline_category_code'] == 'CUSTOMER' ? '' : 'hidden'}}>
                 <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                    <label>
                        <input type="checkbox" id="is_deal_created"
                               onchange=""
                               class="manager-btn is_deal_created">
                        <span></span>
                    </label>
                </span>
            </div>
            <div class="col-1" {{$value['pipeline_category_code'] == 'CUSTOMER' ? 'hidden' : ''}}>
                 <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                    <label>
                        <input type="checkbox" id="is_contract_created"
                               onchange=""
                               class="manager-btn is_contract_created">
                        <span></span>
                    </label>
                </span>
            </div>
            <div class="col-1 row_icon">
                <a href="javascript:void(0)" onclick="create.editJourney(this)"
                   class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill edit_journey"
                   title="@lang('Chỉnh sửa')"><i class="la la-edit"></i>
                </a>
                <i class="fa fa-sort"></i>
            </div>
        </div>
    @endforeach
</div>
@endif