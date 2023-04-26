<div class="modal fade" role="dialog" id="modal-card-after">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="la la-credit-card"></i> @lang("Danh sách thẻ dịch vụ đã mua")</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="form-receipt">
                <div class="modal-body">
                    <div class="m-scrollable m-scroller ps ps--active-y" data-scrollable="true"
                         style="height: 300px; overflow: hidden;">
                        <div class="m-widget4 m-section__content">
                            @if(count($data)>0)
                                @foreach($data as $item_card)
                                    <div class="m-widget4__item card_check_{{$item_card['customer_service_card_id']}}">
                                        <div class="m-widget4__img m-widget4__img--logo">
                                            @if($item_card['image']!=null)
                                                <img src="/uploads/{{$item_card['image']}}" alt="" width="50px"
                                                     height="50px">
                                            @else
                                                <img src="https://secure.bankofamerica.com/content/images/ContextualSiteGraphics/Instructional/en_US/Banner_Credit_Card_Activation.png"
                                                     alt="" width="50px"
                                                     height="50px">
                                            @endif

                                        </div>
                                        <div class="m-widget4__info b">
                                            <span class="m-widget4__title">{{$item_card['card_name']}}</span><br>
                                            <span class="m-widget4__sub">{{$item_card['card_code']}}</span><br>
                                            <span class="m-widget4__text m--font-brand quantity">@lang("Còn") {{$item_card['count_using']}}
                                                (@lang("lần"))</span>
                                        </div>
                                        <span class="m-widget4__ext">
                                            {{--<input type="hidden" class="card_hide" name="card_hide" value="{{$item_card['card_code']}}">--}}
                    <input type="hidden" class="quantity_card_modal" value="{{$item_card['count_using']}}">
                                              <input type="hidden" class="quantity_using"
                                                     value="{{$item_card['count_using']}}">
                   <a href="javascript:void(0)"
                      onclick="list.append_table_card('{{$item_card['customer_service_card_id']}}','0','member_card','{{$item_card['card_name']}}','{{$item_card['count_using']}}','{{$item_card['card_code']}}',this)"
                      class="tag m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary">@lang("Chọn")</a>
            </span>
                                    </div>
                                @endforeach
                            @endif


                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                        <div class="m-form__actions m--align-right">
                            <button data-dismiss="modal"
                                    class="btn btn-danger m-btn m-btn--icon m-btn--wide m-btn--md m--margin-right-10">
						<span>
						<i class="la la-arrow-left"></i>
						<span>@lang("Thoát")</span>
						</span>
                            </button>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
