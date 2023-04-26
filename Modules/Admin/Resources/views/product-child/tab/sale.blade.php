<div id="autotable_sale">
    <form class="frmFilter m--margin-bottom-20">
        <div class="row m--margin-bottom-20">
            <div class="col-lg-6">

            </div>
            <div class="col-lg-6">
                <a href="javascript:void(0)"
                   onclick="productChild.popAdd('sale')"
                   class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill pull-right">
                        <span>
						    <i class="fa fa-plus-circle"></i>
							<span> THÊM</span>
                        </span>
                </a>
            </div>
        </div>
        <div class="ss--background">
            <div class="row ss--bao-filter">
                <div class="col-lg-3">
                    <div class="form-group m-form__group">
                        <div class="input-group">
                            <input type="hidden" name="type_tab" value="sale">
                            <input type="text" class="form-control" name="keyword_product_childs$product_child_name"
                                   placeholder="Nhập tên sản phẩm">
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 form-group">
                    <select style="width: 100%" name="product_childs$is_actived"
                            class="form-control m-input ss--select-2">
                        <option value="">Chọn trạng thái</option>
                        <option value="1">Hoạt động</option>
                        <option value="0">Tạm ngưng</option>
                    </select>
                </div>
                <div class="col-lg-3 form-group">
                    <select style="width: 100%" name="products$product_category_id"
                            class="form-control m-input ss--select-2">
                        <option value="">Chọn danh mục</option>
                        @foreach($productCategoryList as $key => $value)
                            <option value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 form-group">
                    <select style="width: 100%" name="products$product_model_id"
                            class="form-control m-input ss--select-2">
                        <option value="">Chọn nhãn</option>
                        @foreach($productModelList as $key => $value)
                            <option value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row m--padding-left-15 m--padding-right-15">
                <div class="col-lg-3 form-group">
                    <select style="width: 100%" name="product_branch_prices$branch_id"
                            class="form-control m-input ss--select-2">
                        <option value="">Chọn chi nhánh</option>
                        @foreach($branch as $key => $value)
                            <option value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 form-group">
                    <div class="m-input-icon m-input-icon--right">
                        <input onkeyup="productChild.notEnterInput(this)" type="text"
                               class="form-control m-input daterange-picker" id="created_at_sale"
                               name="created_at"
                               autocomplete="off" placeholder="Chọn ngày tạo">
                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                    </div>
                </div>
                <div class="col-lg-4"></div>
                <div class="col-lg-2">
                    <button href="javascript:void(0)" onclick="productChild.tab('sale')"
                            class="btn ss--btn-search ss--float-right">
                            <span class="m--margin-right-10 m--margin-left-15 m--margin-right-15">
                                {{__('TÌM KIẾM')}}
                            <i class="fa fa-search ss--icon-search"></i>
                            </span>
                    </button>
                </div>
            </div>
        </div>
    </form>
    <div class="table-content-sale">
        <div class="table-content">
            <div class="table-responsive">
                <table class="table table-striped m-table ss--header-table">
                    <thead>
                    <tr class="ss--nowrap">
                        <th class="ss--font-size-th">#</th>
                        <th class="ss--font-size-th">TÊN</th>
                        <th class="ss--font-size-th">DANH MỤC</th>
                        <th class="ss--font-size-th">NHÃN</th>
                        <th class="ss--font-size-th">GIÁ</th>
                        <th class="ss--font-size-th ss--text-center">TÌNH TRẠNG</th>
                        <th class="ss--font-size-th ss--text-center">% GIẢM GIÁ</th>
                        <th class="ss--font-size-th"></th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>