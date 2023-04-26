<form class="frmFilter bg">
    <div class="row padding_row">
            <input type="hidden" name="maintenance$warranty_code" value="{{$data['warranty_card_code']}}">

            <div class="col-lg-3">
                <div class="form-group">
                    <input type="text" class="form-control" name="search"
                           placeholder="@lang("Nhập thông tin tìm kiếm")">
                </div>
            </div>
            <div class="col-lg-3 form-group">
                <div class="m-input-icon m-input-icon--right">
                    <input readonly class="form-control m-input daterange-picker"
                           style="background-color: #fff"
                           id="created_at"
                           name="created_at"
                           autocomplete="off" placeholder="@lang('Ngày bảo trì')">
                    <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                </div>
            </div>
            <div class="col-lg-3 form-group">
                <div class="m-input-icon m-input-icon--right">
                    <input readonly class="form-control m-input daterange-picker"
                           style="background-color: #fff"
                           id="date_estimate_delivery"
                           name="date_estimate_delivery"
                           autocomplete="off" placeholder="@lang('Ngày trả hàng dự kiến')">
                    <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                </div>
            </div>

            @php $i = 0; @endphp
            @foreach ($FILTER as $name => $item)
                @if ($i > 0 && ($i % 4 == 0))

        <div class="form-group m-form__group row align-items-center">
            @endif
            @php $i++; @endphp
            <div class="col-lg-3 form-group input-group">
                @if(isset($item['text']))
                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            {{ $item['text'] }}
                                        </span>
                    </div>
                @endif
                {!! Form::select($name, $item['data'], $item['default'] ?? null, ['class' => 'form-control m-input select2']) !!}
            </div>
            @endforeach
            <div class="col-lg-2 form-group">
                <button class="btn btn-primary color_button btn-search">
                    @lang('TÌM KIẾM') <i class="fa fa-search ic-search m--margin-left-5"></i>
                </button>
            </div>
        </div>
    </div>
</form>

<div class="table-content m--padding-top-30">

</div>