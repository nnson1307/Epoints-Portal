<div id="autotable-transfer">
    <form class="m--margin-bottom-20 frmFilter">
        <div class="row m--margin-bottom-20">
            <div class="col-lg-6">

            </div>
            <div class="col-lg-6">
                @if(Auth::user()->is_admin==1||in_array('admin.inventory-transfer.add',session('routeList')))
                    <a href="javascript:void(0)"
                       onclick="location.href='{{route('admin.inventory-transfer.add')}}'"
                       class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill pull-right">
                        <span>
						    <i class="fa fa-plus-circle"></i>
							<span> {{__('TẠO PHIẾU CHUYỂN KHO')}}</span>
                        </span>
                    </a>
                @endif
            </div>
        </div>
        <div class="ss--background">
            <div class="row ss--bao-filter">
                <div class="col-lg-4 form-group">
                    <div class="m-form__group">
                        <div class="input-group">
                            <input type="hidden" name="search_type" value="transfer_code">
                            <button id="search" class="btn btn-primary btn-search"
                                    style="display: none"></button>
                            <input type="text" class="form-control" name="search_keyword"
                                   placeholder="{{__('Nhập mã phiếu')}}">
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="row">
                        @php $i = 0; @endphp
                        @foreach ($FILTER as $name => $item)
                            @if ($i > 0 && ($i % 4 == 0))
                    </div>
                    <div class="form-group m-form__group row align-items-center">
                        @endif
                        @php $i++; @endphp
                        <div class="col-lg-4 input-group form-group">
                            {!! Form::select($name, $item['data'], $item['default'] ?? null, ['class' => 'form-control m-input m_selectpicker']) !!}
                        </div>
                        @endforeach
                        <div class="col-lg-4 form-group">
                            <div class="m-input-icon m-input-icon--right input-group">
                                <input class="form-control m-input daterange-picker col-lg-12" id="created_at4"
                                       name="created_at" autocomplete="off" placeholder="{{__('Chọn ngày tạo')}}">
                                <span class="m-input-icon__icon m-input-icon__icon--right">
                            <span><i class="la la-calendar"></i></span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row m--padding-bottom-15 m--padding-left-15 m--margin-right-2">
                <div class="col-lg-10"></div>
                <div class="col-lg-2">
                    <button href="javascript:void(0)" onclick="InventoryTransfer.search()"
                            class="btn ss--btn-search pull-right">
                        {{__('TÌM KIẾM')}}
                        <i class="fa fa-search ss--icon-search"></i>
                    </button>
                </div>
            </div>
        </div>
        <input type="hidden" name="warehouses" id="inventory-input-warehouse">
    </form>
    <div class="table-content">
        @include('admin::inventory-transfer.list')
    </div><!-- end table-content -->
</div>
<input type="hidden" id="totalInput" value="">
<script src="{{asset('static/backend/js/admin/inventory-transfer/list.js?v='.time())}}" type="text/javascript"></script>