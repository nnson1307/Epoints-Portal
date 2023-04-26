<h4 class="text-danger pb-3">
    <i class="fa fa-file fz-1_5rem" aria-hidden="true"></i>
    {{ __('KẾT QUẢ HOÀN ỨNG') }}
</h4>
@if (count($resultRefundMaterial) > 0)
    <div class="material_temple">
        <h4 class="fz-1_5rem mb-4">{{ __('Nhập kho') }}:</h4>
            <div class="table-responsive">
                <table class="table table-striped m-table s--header-table ss--nowrap text-center">
                    <thead class="bg">
                        <tr>
                            <th class="ss--font-size-th">#</th>
                            <th class="ss--font-size-th">{{ __('Tên') }}</th>
                            <th class="ss--font-size-th">{{ __('Số lượng nhập') }}</th>
                            <th class="ss--font-size-th">{{ __('Đơn vị tính') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($resultRefundMaterial as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }} 
                                </td>
                                <td style="white-space: initial;">{{ $item->product_name }}</td>
                                <td>{{ $item->sum_quantity_refund }}</td>
                                <td>{{ $item->unit_name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
    </div>
    @endif
    @if (count($resultRefundAcceptanceIncurred) > 0)
        <div class="material-incurred mt-3">
            <h4 class="fz-1_5rem mb-4">{{ __('Phiếu chi') }}:</h4>
            <div class="table-responsive">
                <table class="table table-striped m-table s--header-table ss--nowrap text-center">
                    <thead class="bg">
                        <tr>
                            <th class="ss--font-size-th">#</th>
                            <th class="ss--font-size-th">{{ __('Tên vật tư') }}</th>
                            <th class="ss--font-size-th">{{ __('Số lượng') }}</th>
                            <th class="ss--font-size-th">{{ __('Đơn vị tính') }}</th>
                            <th class="ss--font-size-th">{{ __('Số tiền chi') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($resultRefundAcceptanceIncurred as $key => $item)
                            <tr>
                                <td>
                                    {{ $key + 1 }}
                                </td>
                                <td style="white-space: initial;">{{ $item->product_name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->unit_name }}</td>
                                <td>{{ number_format($item->money_refund, 0, '', '.') }} VND</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
