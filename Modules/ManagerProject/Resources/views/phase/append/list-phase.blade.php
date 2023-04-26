@foreach($listPhase as $itemPhase)
    <div class="col-lg-12 block">
        <div class="row">
            <div class="col-4">
                <div class="form-group m-form__group">
                    <label class="black-title">{{ __('Tên giai đoạn') }}:</label>
                    <input type="text" class="form-control m-input name" disabled value="{{$itemPhase['name']}}" placeholder="{{__('Nhập tên giai đoạn')}}">
                </div>
            </div>
            <div class="col-4">
                <div class="form-group m-form__group">
                    <label class="black-title">{{ __('Ngày bắt đầu') }}:</label>
                    <input type="text" class="form-control m-input date_start" disabled value="{{$itemPhase['date_start'] != null ? \Carbon\Carbon::parse($itemPhase['date_start'])->format('d/m/Y') : ''}}" placeholder="{{__('Ngày bắt đầu')}}">
                </div>
            </div>
            <div class="col-4">
                <div class="form-group m-form__group">
                    <label class="black-title">{{ __('Ngày kết thúc') }}:</label>
                    <input type="text" class="form-control m-input date_end" disabled value="{{$itemPhase['date_end'] != null ? \Carbon\Carbon::parse($itemPhase['date_end'])->format('d/m/Y') : ''}}" placeholder="{{__('Ngày kết thúc')}}">
                </div>
            </div>
        </div>
    </div>
@endforeach