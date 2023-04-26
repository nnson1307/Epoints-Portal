<!-- Modal -->
<div class="modal fade" id="popup-review-list-detail" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog " role="document" style="max-width: 600px;">
        <div class="modal-content">
            <div class="modal-header">
                @if(isset($detail))
                    <h2 class="modal-title" id="title">{{__('CHỈNH SỬA CHI TIẾT ĐÁNH GIÁ')}}</h2>
                @else
                    <h2 class="modal-title" id="title">{{__('TẠO CHI TIẾT ĐÁNH GIÁ')}}</h2>
                @endif
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="w-100" id="form-review-list-detail">
                <div class="modal-body">
                    <div class="col-12">
                        <span class="note-font" style="    margin-top: 12px;margin-right: 20px;">{{__('Cấp độ đánh giá')}}</span>
                        <div class="form-group m-form__group ">
                            <select class="form-control select-form w-100" id="popup_review_list_id" style="    width: 350px;"
                                    name="popup_review_list_id">
                                <option value="">{{__('Chọn cấp độ đánh giá')}}</option>
                                @foreach($listReview as $item)
                                    <option value="{{$item['review_list_id']}}" {{isset($detail) && $detail['review_list_id'] == $item['review_list_id'] ? 'selected' : ''}}>{{$item['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <span class="note-font" style="    margin-top: 12px;margin-right: 20px;">{{__('Tên đánh giá')}}</span>
                        <div class="form-group m-form__group ">
                            <input type="text" class="form-control" id="popup_name" name="popup_name" value="{{isset($detail) ? $detail['name'] : ''}}" placeholder="{{__('Nhập Tên đánh giá')}}">
                        </div>
                    </div>
                    <input type="hidden" name="popup_review_list_detail_id" value="{{isset($detail) ? $detail['review_list_detail_id'] : ''}}">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <span class="la 	la-arrow-left"></span>
                            HỦY
                        </button>
                        <button type="button" class="btn btn-primary" onclick="requestListDetail.saveReviewDetail()">
                            <span class="la la-check"></span>
                            LƯU THÔNG TIN
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>