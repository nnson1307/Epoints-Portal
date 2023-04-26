<div class="modal fade show" id="modalAddCategory" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title m--font-bold"><i class="fa flaticon-plus m--margin-right-5"></i>{{__('Thêm danh mục')}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group m-form__group">
                    <label>
                        {{__('Tên danh mục')}}:<b class="text-danger">*</b>
                    </label>
                    <input type="text" id="category-name" class="form-control m-input"
                           placeholder="{{__('Nhập tên danh mục')}}">
                    <span class="error-category-name"></span>
                </div>
                <div class="form-group m-form__group">
                    <label>
                        {{__('Mô tả')}}:
                    </label>
                    <textarea class="form-control" rows="5" id="description" placeholder="{{__('Mô tả')}}"></textarea>

                </div>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        <button data-dismiss="modal"
                                class="btn btn-secondary m-btn m-btn--icon m-btn--wide m-btn--md m--margin-right-10">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('Hủy')}}</span>
						</span>
                        </button>

                        <div class="btn-group">
                            <button type="button" onclick="product.addCategoryClose()"
                                    class="btn btn-primary  m-btn m-btn--icon m-btn--wide m-btn--md">
							<span>
							<i class="la la-check"></i>
							<span>{{__('Lưu lại')}}</span>
							</span>
                            </button>
                            <button type="button"
                                    class="btn btn-primary  dropdown-toggle dropdown-toggle-split m-btn m-btn--md"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end"
                                 style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(160px, 49px, 0px);">
                                <a class="dropdown-item" href="javascript:void(0)" onclick="product.addCategory()"><i
                                            class="la la-plus"></i> {{__('Lưu')}} &amp; {{__('Tạo mới')}}</a>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="product.addCategoryClose()"><i
                                            class="la la-undo"></i> {{__('Lưu')}} &amp; {{__('Tạo mới')}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade show" id="modalAddModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title m--font-bold"><i class="fa flaticon-plus m--margin-right-5"></i>{{__('Thêm nhãn hiệu sản phẩm')}}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group m-form__group">
                    <label>
                        {{__('Tên nhãn hiệu sản phẩm')}}:<b class="text-danger">*</b>
                    </label>
                    <input type="text" id="product-model-name" class="form-control m-input"
                           placeholder="{{__('Nhập tên nhãn hiệu sản phẩm')}}">
                    <span class="error-product-model-name"></span>
                </div>
                <div class="form-group m-form__group">
                    <label>
                        {{__('Ghi chú')}}:
                    </label>
                    <textarea class="form-control" rows="5" id="product-model-note" placeholder="{{__('Ghi chú')}}"></textarea>
                    <span class="error-product-model-note"></span>
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        <button data-dismiss="modal"
                                class="btn btn-secondary m-btn m-btn--icon m-btn--wide m-btn--md m--margin-right-10">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('Hủy')}}</span>
						</span>
                        </button>

                        <div class="btn-group">
                            <button type="button" onclick="product.addProductModelClose()"
                                    class="btn btn-primary  m-btn m-btn--icon m-btn--wide m-btn--md">
							<span>
							<i class="la la-check"></i>
							<span>{{__('Lưu lại')}}</span>
							</span>
                            </button>
                            <button type="button"
                                    class="btn btn-primary  dropdown-toggle dropdown-toggle-split m-btn m-btn--md"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end"
                                 style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(160px, 49px, 0px);">
                                <a class="dropdown-item" href="javascript:void(0)"
                                   onclick="product.addProductModel()"><i
                                            class="la la-plus"></i> {{__('Lưu')}} &amp; {{__('Tạo mới')}}</a>
                                <a class="dropdown-item" href="javascript:void(0)"
                                   onclick="product.addProductModelClose()"><i
                                            class="la la-undo"></i> {{__('Lưu')}} &amp; {{__('Đóng')}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade show" id="modalAddSupplier" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title m--font-bold"><i class="fa flaticon-plus m--margin-right-5"></i>{{__('Thêm nhà cung cấp')}}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="form-add">
                    <div class="form-group m-form__group">
                        <label>
                            {{__('Tên nhà cung cấp')}}:
                        </label>
                        <input type="text" name="supplierName" id="supplierName" class="form-control m-input"
                               placeholder="Nhập tên nhà cung cấp">
                        <span class="error-supplier-name"></span>
                    </div>
                    <div class="form-group m-form__group">
                        <label>
                            {{__('Mô tả')}} :
                        </label>
                        <textarea placeholder="Nhập mô tả" rows="2" cols="50" name="description"
                                  id="description"
                                  class="form-control"></textarea>
                        <span class="description"></span>
                    </div>
                    <div class="form-group m-form__group">
                        <label>
                            {{__('Địa chỉ nhà chung cấp')}}:
                        </label>
                        <input type="text" name="address" id="address" class="form-control m-input"
                               placeholder="{{__('Nhập địa chỉ nhà chung cấp')}}">
                        <span class="error-address"></span>
                    </div>
                    <div class="form-group m-form__group">
                        <label>
                            {{__('Tên người đại diện')}}:
                        </label>
                        <input type="text" name="contact_name" id="contact_name" class="form-control m-input"
                               placeholder="{{__('Nhập tên người đại diện')}}">
                        <span class="error-contact-name"></span>
                    </div>
                    <div class="form-group m-form__group">
                        <label>
                            {{__('Chức vụ người đại diện')}}:
                        </label>
                        <input type="text" name="contact_title" id="contact_title" class="form-control m-input"
                               placeholder="{{__('Nhập chức vụ người đại diện')}}">
                        <span class="error-contact-title"></span>
                    </div>
                    <div class="form-group m-form__group">
                        <label>
                            {{__('SĐT người đại diện')}}:
                        </label>
                        <input onkeydown="onKeyDownInput(this)" type="number" name="contact_phone"
                               id="contact_phone"
                               class="form-control m-input"
                               placeholder="{{__('Nhập số điện thoại người đại diện')}}"></div>
                    <span class="error-contact-phone"></span>
                </form>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        <button data-dismiss="modal"
                                class="btn btn-secondary m-btn m-btn--icon m-btn--wide m-btn--md m--margin-right-10">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('Hủy')}}</span>
						</span>
                        </button>

                        <div class="btn-group">
                            <button type="button" onclick="product.addSupplierClose()"
                                    class="btn btn-primary  m-btn m-btn--icon m-btn--wide m-btn--md">
							<span>
							<i class="la la-check"></i>
							<span>{{__('Lưu lại')}}</span>
							</span>
                            </button>
                            <button type="button"
                                    class="btn btn-primary  dropdown-toggle dropdown-toggle-split m-btn m-btn--md"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end"
                                 style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(160px, 49px, 0px);">
                                <a class="dropdown-item" href="javascript:void(0)" onclick="product.addSupplier()"><i
                                            class="la la-plus"></i> {{__('Lưu')}} &amp; {{__('Tạo mới')}}</a>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="product.addSupplierClose()"><i
                                            class="la la-undo"></i> {{__('Lưu')}} &amp; {{__('Đóng')}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade show" id="modalAddUnit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <style>
            .form-control-feedback {
                color: #ff0000;
            }
        </style>
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title m--font-bold"><i class="fa flaticon-plus m--margin-right-5"></i>Thêm đơn vị tính</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="form">
                <div class="modal-body">
                    <div class="form-group m-form__group">
                        <div class="row">
                            <label class="col-lg-5">
                                {{__('Đơn vị tính')}}: <b class="text-danger">*</b>
                            </label>
                            <div class="col-lg-12">
                                <input type="text" placeholder="{{__('Đơn vị tính')}}" name="name" class="form-control m-input"
                                       id="name">
                                <span class="error-name-unit"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="row">
                            <label class="col-sm-4">
                                {{__('Đơn vị chuẩn')}}:
                            </label>
                            <div class="col-lg-2">
                                <label class="m-checkbox m-checkbox--air">
                                    <input checked id="is_standard" value="1" name="is_standard" type="checkbox">
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="type_add" id="type_add" value="0">
                <div class="modal-footer">
                    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                        <div class="m-form__actions m--align-right">
                            <button data-dismiss="modal"
                                    class="btn btn-secondary m-btn m-btn--icon m-btn--wide m-btn--md m--margin-right-10">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('Hủy')}}</span>
						</span>
                            </button>

                            <div class="btn-group">
                                <button type="submit" onclick="product.addUnit(1)"
                                        class="btn btn-primary  m-btn m-btn--icon m-btn--wide m-btn--md">
                                    <span>
                                    <i class="la la-check"></i>
                                    <span>{{__('Lưu lại')}}</span>
                                    </span>
                                </button>
                                <button type="button"
                                        class="btn btn-primary  dropdown-toggle dropdown-toggle-split m-btn m-btn--md"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end"
                                     style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(160px, 49px, 0px);">
                                    <a class="dropdown-item" href="javascript:$('#form').submit();"
                                       onclick="product.addUnit(0)"><i
                                                class="la la-plus"></i> {{__('Lưu')}} &amp; {{__('Tạo mới')}}</a>
                                    <a href="javascript:$('#form').submit();" class="dropdown-item"
                                       onclick="product.addUnit(1)"><i
                                                class="la la-undo"></i> {{__('Lưu')}} &amp; Đóng</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade show" id="modalAddBranch" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <style>
            .form-control-feedback {
                color: #ff0000;
            }
        </style>
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title m--font-bold"><i class="fa flaticon-plus m--margin-right-5"></i>{{__('Thêm chi nhánh')}}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="form-add-branch">
                <div class="modal-body">
                    <div class="form-group">
                        <label>
                            {{__('Tên chi nhánh')}}:<b class="text-danger">*</b>
                        </label>
                        <input placeholder="{{__('Tên chi nhánh')}}" type="text" name="branch_name"
                               class="form-control m-input"
                               id="branch_name">
                        <span class="error-name-branch"></span>
                    </div>
                    <div class="form-group">
                        <label>
                            {{__('Địa chỉ')}}:<b class="text-danger">*</b>
                        </label>
                        <input placeholder="{{__('Địa chỉ')}}" type="text" name="address"
                               class="form-control m-input" id="address">
                    </div>
                    <div class="form-group">
                        <label>
                            {{__('Số điện thoại')}}: <b class="text-danger">*</b>
                        </label>
                        <input placeholder="{{__('Số điện thoại')}}" type="text" name="phone"
                               class="form-control m-input" id="phone">
                        <br>
                    </div>
                    <div class="form-group">
                        <label>
                            {{__('Mô tả')}}
                        </label>
                        <textarea rows="5" placeholder="{{__('Mô tả')}}" type="text" name="description"
                                  class="form-control m-input"
                                  id="description"></textarea>
                    </div>
                </div>
                <input type="hidden" name="type_add_branch" id="type_add_branch" value="0">
                <div class="modal-footer">
                    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                        <div class="m-form__actions m--align-right">
                            <button data-dismiss="modal"
                                    class="btn btn-secondary m-btn m-btn--icon m-btn--wide m-btn--md m--margin-right-10">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('Hủy')}}</span>
						</span>
                            </button>

                            <div class="btn-group">
                                <button type="submit" onclick="product.addBranch(1)"
                                        class="btn btn-primary  m-btn m-btn--icon m-btn--wide m-btn--md">
                                    <span>
                                    <i class="la la-check"></i>
                                    <span>{{__('Lưu lại')}}</span>
                                    </span>
                                </button>
                                <button type="button"
                                        class="btn btn-primary  dropdown-toggle dropdown-toggle-split m-btn m-btn--md"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end"
                                     style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(160px, 49px, 0px);">
                                    <a class="dropdown-item" href="javascript:$('#form-add-branch').submit();"
                                       onclick="product.addBranch(0)"><i
                                                class="la la-plus"></i> {{__('Lưu')}} &amp; {{__('Tạo mới')}}</a>
                                    <a href="javascript:$('#form-add-branch').submit();" class="dropdown-item"
                                       onclick="product.addBranch(1)"><i
                                                class="la la-undo"></i> {{__('Lưu')}} &amp; {{__('Đóng')}}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>