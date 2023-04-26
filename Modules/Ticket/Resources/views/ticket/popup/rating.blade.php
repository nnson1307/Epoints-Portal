<div class="modal fade show" id="modal-rating-ticket" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> {{ __('ĐÁNH GIÁ TICKET') }}
                </h5>
            </div>
            <div class="modal-body">
                <form id="form-img">
                    <div class="form-group m-form__group">
                        <label for="">@lang('Chấm điểm') <b class="text-danger">*</b></label>
                        <div class="form-group form-control p-0">
                            <div class="rate">
                                <input type="radio" id="star5" name="rate" value="5" {{ ( isset($item->rating->point) && $item->rating->point == 5 )?'checked':'' }} />
                                <label for="star5" title="text">5 stars</label>
                                <input type="radio" id="star4" name="rate" value="4" {{ ( isset($item->rating->point) && $item->rating->point == 4 )?'checked':'' }} />
                                <label for="star4" title="text">4 stars</label>
                                <input type="radio" id="star3" name="rate" value="3" {{ ( isset($item->rating->point) && $item->rating->point == 3 )?'checked':'' }} />
                                <label for="star3" title="text">3 stars</label>
                                <input type="radio" id="star2" name="rate" value="2" {{ ( isset($item->rating->point) && $item->rating->point == 2 )?'checked':'' }} />
                                <label for="star2" title="text">2 stars</label>
                                <input type="radio" id="star1" name="rate" value="1" {{ ( isset($item->rating->point) && $item->rating->point == 1 )?'checked':'' }} />
                                <label for="star1" title="text">1 star</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label class="black_title w-100">
                            @lang('Nội dung'):
                        </label>
                        <textarea class="form-control m-input" name="description" rows="6" cols="5"
                            placeholder="@lang('Nhập nội dung đánh giá')...">{{ isset($item->rating->description)?$item->rating->description:'' }}</textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                    <div class="m-form__actions m--align-right">
                        <button data-dismiss="modal"
                            class="ss--btn-mobiles btn btn-metal ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-bottom-5">
                            <span class="ss--text-btn-mobi">
                                <i class="la la-arrow-left"></i>
                                <span>{{ __('HỦY') }}</span>
                            </span>
                        </button>
                        <button type="button" onclick="edit.submitrate({{ isset($id)?$id:'' }})"
                            class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10  m--margin-bottom-5">
                            <span class="ss--text-btn-mobi">
                                <i class="la la-check"></i>
                                <span>{{ __('CẬP NHẬT THÔNG TIN') }}</span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
