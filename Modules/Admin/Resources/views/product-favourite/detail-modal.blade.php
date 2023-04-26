<div class="modal fade ajax product-favourite-detail-modal" role="dialog">
    <style>
        .product-favourite-detail-modal .info .form-group{
            margin: 0;
            padding: 10px 0px;
            border-bottom: dashed 1px lightgray;
        }
    </style>
    <div class="modal-dialog modal-dialog-centered hu-modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title ss--title m--font-bold text-uppercase">
                    <i class="fa fa-plus-circle ss--icon-title m--margin-right-5"></i>
                    {{__('Danh sách sản phẩm yêu thích')}}
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 info"><div class="row">

                            @if($list??false)
                                @foreach($list as $product_item)
                                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                    @include('admin::product-favourite.product-item')
                                    </div>
                                @endforeach
                            @endif

                    </div></div>
                </div>


            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                    <div class="m-form__actions m--align-right">
                        <button data-dismiss="modal"
                            class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                            <span class="ss--text-btn-mobi">
                            <i class="la la-arrow-left"></i>
                            <span>{{__('ĐÓNG')}}</span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>