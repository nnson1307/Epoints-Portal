<div class="modal fade show" id="modal-care" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="la la-gratipay"></i> {{__('CHĂM SÓC KHÁCH HÀNG')}}
                </h5>
            </div>
            <div class="modal-body">
                <form id="form-care">
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Loại chăm sóc'): <b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <input type="text" id="type_name" name="type_name" class="form-control">
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Nội dung'):<b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <textarea class="form-control" id="content" name="content" rows="5"></textarea>
                        </div>
                    </div>
                    @if (count($dataCare) > 0)
                        <div style="width: 100%; height: 300px; overflow-y: scroll;">
                            <div class="m-scrollable m-scroller ps ps--active-y">
                                <!--Begin::Timeline 2 -->
                                @foreach($dataCare as $k => $v)
                                    <div class="m-timeline-2">
                                        <div class="m-timeline-2__items  m--padding-top-25 m--padding-bottom-30">
                                            <div class="m-timeline-2__item">
                                                <span class="m-timeline-2__item-time">
                                                    {{\Carbon\Carbon::createFromFormat('d/m/Y', $k)->format('d/m')}}
                                                </span>
                                            </div>
                                            @if (count($v) > 0)
                                                @foreach($v as $v1)
                                                    <div class="m-timeline-2__item m--margin-top-30">
                                                        <span class="m-timeline-2__item-time"></span>
                                                        <div class="m-timeline-2__item-cricle">
                                                            <i class="fa fa-genderless m--font-success"></i>
                                                        </div>
                                                        <div class="m-timeline-2__item-text">
                                                            <strong>{{$v1['time']}}</strong>
                                                            <br/>
                                                            @if ($v1['type'] == 'care')
                                                                @lang('Người chăm sóc'): {{$v1['staff_name']}} <br/>
                                                            @endif
                                                            @lang('Loại chăm sóc'):
                                                                @switch($v1['type'])
                                                                    @case('care')
                                                                        {{$v1['type_name']}}
                                                                    @break
                                                                    @case('email')
                                                                        @lang('Email')
                                                                    @break
                                                                    @case('sms')
                                                                        @lang('Sms')
                                                                    @break
                                                                    @case('notify')
                                                                        @lang('Gửi thông báo')
                                                                    @break
                                                                @endswitch
                                                            <br/>
                                                            @if($v1['type'] == 'care')
                                                                @lang('Nội dung'): {{$v1['content']}}
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </form>
            </div>
            <div class="modal-footer">
                <div class="m-form__actions m--align-right w-100">
                    <button data-dismiss="modal"
                            class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                    </button>
                    <button type="submit" onclick="remindUse.submitCare('{{$item['customer_remind_use_id']}}')"
                            class="btn btn-info  color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<i class="la la-check"></i>
							<span>{{__('LƯU THÔNG TIN')}}</span>
							</span>
                    </button>

                </div>
            </div>
        </div>
    </div>
</div>