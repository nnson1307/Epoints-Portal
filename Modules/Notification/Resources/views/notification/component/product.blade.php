<div id="autotable-product">
    <form class="frmFilter bg">
        <div class="row padding_row form-group">
            <div class="col-lg-4">
                <div class="form-group m-form__group">
                    <div class="input-group">
                        <input type="text" class="form-control" name="product_name" placeholder="{{__('Nhập tên sản phẩm')}}">
                        <input type="hidden" class="form-control" name="detail_type" value="product">
                    </div>
                </div>
            </div>
            <div class="col-lg-2 form-group">
                <button class="btn btn-primary color_button btn-search">
                    {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                </button>
            </div>
        </div>
    </form>
    <div class="form-group table-responsive">
        <table class="table table-striped m-table m-table--head-bg-default">
            <thead class="bg">
            <tr>
                <th class="tr_thead_list"></th>
                <th class="tr_thead_list">{{__('Hình ảnh')}}</th>
                <th class="tr_thead_list">{{__('Tên sản phẩm"')}}</th>
                <th class="tr_thead_list">{{__('Mã sản phẩm')}}</th>
                <th class="tr_thead_list">{{__('Giá')}}</th>
                <th class="tr_thead_list">{{__('Đơn vị tính')}}</th>
            </tr>
            </thead>
            <tbody>
            @if(isset($LIST))
                @foreach ($LIST as $key => $item)
                    <tr>
                        <td>
                            <label class="m-checkbox m-checkbox--air">
                                <input id="promo" name="example_3" type="radio">
                                <span></span>
                            </label>
                        </td>
                        <td>
                            <img class="m--bg-metal m-image img-sd"
                                 src="{{$item['avatar']}}"
                                 alt="Hình ảnh" width="100px" height="100px">
                        </td>
                        <td>{{$item['product_name']}}</td>
                        <td>{{$item['product_code']}}</td>
                        <td>{{number_format($item['cost'])}}</td>
                        <td>{{$item['unit_name']}}</td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
    {{ $LIST->links('helpers.paging') }}
</div>
