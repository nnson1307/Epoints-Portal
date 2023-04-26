<div class="modal fade" id="modal-end-point" role="dialog" style="display: none">
    <div class="modal-dialog modal-dialog-centered modal-lg-email-auto modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title title_index">
                    {{__('Chọn đích đến')}}
                </h4>
            </div>
            <div class="m-widget4  m-section__content" id="load">
                <div class="modal-body">
                    <div id="autotable-product">
                        <form class="frmFilter bg">
                            <div class="row padding_row form-group">
                                <div class="col-lg-4">
                                    <div class="form-group m-form__group">
                                        <div class="input-group">
                                            @switch($detail_type)
                                                @case('product_detail'):
                                                    <input type="text" class="form-control" name="product_name" placeholder="{{__('Nhập tên sản phẩm')}}">
                                                    @break
                                                @case('service_detail'):
                                                    <input type="text" class="form-control" name="search" placeholder="{{__('Nhập tên dịch vụ')}}">
                                                    @break
                                                @case('promotion_detail'):
                                                    <input type="text" class="form-control" name="search" placeholder="{{__('Nhập tên chương trình khuyến mãi')}}">
                                                    @break
                                                @case('news_detail'):
                                                    <input type="text" class="form-control" name="search" placeholder="{{__('Nhập tiêu đề')}}">
                                                    @break
                                            @endswitch
                                            <input type="hidden" class="form-control" name="detail_type" value="{{$detail_type}}">
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
                        <div class="table-content m--padding-top-15">
                            @include('notification::notification.component.product_list')
                        </div><!-- end table-content -->
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="m-form__actions m--align-right btn_receipt w-100">
                        <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                           <span>
                            <i class="la la-arrow-left"></i>
                               <span>{{__('HỦY')}}</span>
                           </span>
                        </a>
                    </div>
                    <a href="javascript:void(0)" onclick="script.popupSuccess()";
                            class="btn  btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md btn-add-close m--margin-left-10">
							<span>
							<i class="la la-check"></i>
							<span>{{__('LƯU THÔNG TIN')}}</span>
							</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
