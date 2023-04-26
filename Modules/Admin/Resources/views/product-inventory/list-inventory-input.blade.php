<style>
    .select2 {
        width: 100% !important;
    }
</style>
<div id="autotable">
    <form class="frmFilter m--margin-bottom-20">
        <div class="row m--margin-bottom-20">
            <div class="col-lg-6">

            </div>
            <div class="col-lg-6">
                @if(Auth::user()->is_admin==1||in_array('admin.inventory-input.add',session('routeList')))
                    <a href="javascript:void(0)"
{{--                       onclick="location.href='{{route('admin.inventory-input.add')}}'"--}}
                        onclick="InventoryInput.showPopup()"
                       class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill pull-right">
                        <span>
						    <i class="fa fa-plus-circle"></i>
							<span> {{__('TẠO PHIẾU NHẬP KHO')}}</span>
                        </span>
                    </a>
                @endif
            </div>
        </div>
        <div class="ss--background">
            <div class="row ss--bao-filter">
                <div class="col-lg-6 form-group">
                    <div class="m-form__group">
                        <input type="hidden" name="search_type" value="pi_code">
                        <button id="search" class="btn btn-primary btn-search"
                                style="display: none"></button>
                        <input type="text" class="form-control" name="search_keyword"
                               placeholder="{{__('Nhập mã phiếu')}}">
                    </div>
                </div>
                <div class="col-lg-6 form-group">
                    <div class="m-input-icon m-input-icon--right">
                        <input class="form-control m-input daterange-picker" id="created_at"
                               onkeyup="InventoryInput.removeAllInput(this)" name="created_at" autocomplete="off"
                               placeholder="{{__('Chọn ngày tạo')}}">
                        <span class="m-input-icon__icon m-input-icon__icon--right">
                         <span><i class="la la-calendar"></i></span></span>
                    </div>
                </div>
            </div>
            <div class="row m--padding-left-15 m--padding-right-15">
                <div class="col-lg-10">
                    <div class="row">
                        @php $i = 0; @endphp
                        @foreach ($FILTER as $name => $item)
                            @if ($i > 0 && ($i % 4 == 0))
                    </div>
                    <div class="form-group m-form__group row align-items-center">
                        @endif
                        @php $i++; @endphp
                        <div class="col-lg-3 input-group form-group">
                            @if(isset($item['text']))
                                <div class="input-group-append">
                        <span class="input-group-text">
                            {{ $item['text'] }}
                        </span>
                                </div>
                            @endif
                            {!! Form::select($name, $item['data'], $item['default'] ?? null, ['class' => 'form-control m-input']) !!}
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group m-form__group">
                        <button href="javascript:void(0)" onclick="InventoryInput.search()"
                                class="btn ss--btn-search">
                            <span class="m--margin-right-10 m--margin-left-15 m--margin-right-15">
                                {{__('TÌM KIẾM')}}
                            <i class="fa fa-search ss--icon-search"></i>
                            </span>

                        </button>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="warehouses" id="inventory-input-warehouse">
    </form>
    <div class="table-content">
        @include('admin::inventory-input.list')
    </div><!-- end table-content -->
</div>
<input type="hidden" id="totalInput" value="">

<script src="{{asset('static/backend/js/admin/inventory-input/list.js?v='.time())}}" type="text/javascript"></script>
<script type="text-template" id="tpl-data-error">
    <input type="hidden" name="export[{keyNumber}][product_code]" value="{product_code}">
    <input type="hidden" name="export[{keyNumber}][quantity]" value="{quantity}">
    <input type="hidden" name="export[{keyNumber}][price]" value="{price}">
    <input type="hidden" name="export[{keyNumber}][barcode]" value="{barcode}">
    <input type="hidden" name="export[{keyNumber}][serial]" value="{serial}">
    <input type="hidden" name="export[{keyNumber}][error_message]" value="{error_message}">
</script>
