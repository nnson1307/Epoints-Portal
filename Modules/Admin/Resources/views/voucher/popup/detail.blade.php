<style>
    @media screen and (max-width: 480px) {
        .modal-lg {
            max-width: 100%;
        }
    }

    .ss--header-table thead th {
        background: #dff7f9 !important;
        color: #474548 !important;
        border-bottom: 0 !important;
        border-top: 0 !important;

    }
</style>

<div class="modal-dialog modal-lg modal-dialog-centered">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <span class="m-portlet__head-icon ss--title m--margin-right-5">
                        <i class="la la-outdent"></i>
                    </span>
            <h4 class="modal-title ss--title m--font-bold">
                {{__('CHI TIẾT KHUYẾN MÃI')}}
            </h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="col-md-12 row form-group">
                <div class="col-md-6">
                    <div class="form-group m-form__group">
                        <label for="">{{__('Mã giảm giá')}}: <b>{{$voucher->code}}</b> </label>
                        <div class="input-group m-input-group m-input-group--solid ss--display-none">
                            <input style="text-align: right" readonly="" class="form-control" type="text"
                                   value="{{$voucher->code}}">
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label for="">{{__('Loại mã giảm giá')}}:
                            @switch($voucher->type)
                                @case("sale_percent")
                                {{__('Giảm giá theo phần trăm')}}
                                @break
                                @case("sale_cash")
                                {{__('Giảm tiền')}}
                                @break
                                @default
                                <span></span>
                            @endswitch
                        </label>
                    </div>
                    <div class="form-group m-form__group">
                        <label for="">{{__('Giá trị giảm')}}:
                            @switch($voucher->type)
                                @case("sale_percent")
                                {{number_format($voucher->percent, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}%
                                @break
                                @case("sale_cash")
                                {{number_format($voucher->cash, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}{{__('đ')}}
                                @break
                                @default
                                <span></span>
                            @endswitch
                        </label>
                    </div>
                    <div class="form-group m-form__group">
                        <label for="">{{__('Tiền giảm tối đa')}}:
                            {{number_format($voucher->max_price, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                        </label>
                    </div>
                    <div class="form-group m-form__group">
                        <label for="">{{__('Đơn hàng tối thiểu')}}:
                            {{ number_format($voucher->required_price, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                        </label>
                    </div>
                    <div class="form-group m-form__group">
                        <label for="">{{__('Hạn mức sử dụng')}}:
                            {{$voucher->quota}}
                        </label>
                    </div>
                    <div class="form-group m-form__group">
                        <label for="">{{__('Hạn sử dụng')}}:
                            {{\Carbon\Carbon::parse($voucher->expire_date)->format('d/m/Y')}}
                        </label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group m-form__group">
                        <label>{{__('Hình thức')}} :
                            @switch($voucher->object_type)
                                @case("all")
                                {{__('Tất cả')}}
                                @break
                                @case("service_card")
                                {{__('Theo thẻ dịch vụ')}}
                                @break
                                @case("product")
                                {{__('Theo sản phẩm')}}
                                @break
                                @case("service")
                                {{__('Theo dịch vụ')}}
                                @break
                                @default
                                <span></span>
                            @endswitch
                        </label>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-4">
                            <label>
                                {{__('Trạng thái')}} :
                            </label>
                        </div>
                        <div class="col-lg-2">
                            <span style="margin-top: -3px;"
                                  class="m-switch m-switch--icon m-switch--success m-switch--sm m--margin-bottom-10">
                            <label>
                            <input disabled {{$voucher->is_actived==1?'checked':''}}
                            type="checkbox" class="manager-btn"
                                   name="">
                                <span></span>
                            </label>
                            </span>
                        </div>
                        <div class="col-lg-6">
                            <i>{{$voucher->is_actived == 1 ? __('Kích hoạt') : __('Chưa kích hoạt')}} </i>

                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label for="">{{__('Chi nhánh')}}:
                            @php($value = "")
                            @if($branch!=null)
                                @foreach($branch as $key=>$item)
                                    @if($key == count($branch) - 1)
                                        @php($value = $value . $item->branch_name)
                                    @else
                                        @php($value = $value . $item->branch_name." , ")
                                    @endif
                                @endforeach
                            @else
                                @php($value = __("Tất cả"))
                            @endif
                            {{$value}}
                        </label>
                    </div>
                </div>
            </div>
            @if($voucher->object_type !="all")
                <div class="table-content">
                    <div class="table-responsive">
                        <div>
                            <table class="table table-striped m-table m-table--head-bg-primary ss--header-table"
                                   id="card_list">
                                <thead style="text-transform: uppercase;">
                                @if($voucher->object_type == "product")
                                    <tr>
                                        <th>#</th>
                                        <th>{{__('Tên Sản phẩm')}}</th>
                                        <th>{{__('Loại Sản phẩm')}}</th>
                                        <th>{{__('Giá tiền')}}</th>
                                    </tr>
                                @elseif($voucher->object_type == "service")
                                    <tr>
                                        <th>#</th>
                                        <th>{{__('Tên Dịch vụ')}}</th>
                                        <th>{{__('Mã Dịch vụ')}}</th>
                                        <th>{{__('Loại dịch vụ')}}</th>
                                    </tr>
                                @elseif($voucher->object_type == "service_card")
                                    <tr>
                                        <th>#</th>
                                        <th>{{__('Tên Thẻ Dịch vụ')}}</th>
                                        <th>{{__('Mã Thẻ')}}</th>
                                        <th>{{__('Loại thẻ dịch vụ')}}</th>
                                    </tr>
                                @endif
                                </thead>
                                <tbody>
                                @if($object !=null)
                                    @if($voucher->object_type == "product")
                                        @foreach($object as $key =>$item)
                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td>{{$item->productName}}</td>
                                                <td>{{$item->categoryName}}</td>
                                                <td>{{number_format($item->price)}}</td>
                                            </tr>
                                        @endforeach
                                    @elseif($voucher->object_type == "service")
                                        @foreach($object as $key =>$item)
                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td class="id-name">{{$item->service_name}}</td>
                                                <td>{{$item->service_code}}</td>
                                                <td>
                                                    {{$item->name}}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @elseif($voucher->object_type == "service_card")
                                        @foreach($object as $key =>$item)
                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td class="id-name">{{$item->card_name}}</td>
                                                <td>{{$item->code}}</td>
                                                <td>
                                                    @if($item->service_card_type=="money")
                                                        Tiền
                                                    @elseif($item->service_card_type=="service")
                                                        Dịch vụ
                                                    @endif
                                                </td>
                                        @endforeach
                                    @endif
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="modal-footer">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                <div class="m-form__actions m--align-right">
                    <button data-dismiss="modal"
                            class="ss--btn-mobiles btn btn-metal ss--btn m-btn m-btn--icon m-btn--wide m-btn--md">
						<span class="ss--text-btn-mobi">
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
