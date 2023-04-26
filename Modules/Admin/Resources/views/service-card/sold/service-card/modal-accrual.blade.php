<div class="modal fade show" id="modal-create" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> @lang('CỘNG DỒN THẺ LIỆU TRÌNH')
                </h5>
            </div>
            <div class="modal-body">
                <form id="form-create">
                    <div class="table-content list-card">
                        <div class="table-responsive">
                            <table class="table table-striped m-table ss--header-table ss--nowrap">
                                <thead>
                                <tr>
                                    <th class="ss--font-size-th"></th>
                                    <th class="ss--font-size-th">{{__('MÃ THẺ')}}</th>
                                    <th class="ss--font-size-th">{{__('TÊN THẺ DỊCH VỤ')}}</th>
                                    <th class="ss--text-center ss--font-size-th">{{__('NGÀY KÍCH HOẠT')}}</th>
                                    <th class="ss--text-center ss--font-size-th">{{__('NGÀY HÉT HẠN')}}</th>
                                    <th class="ss--text-center ss--font-size-th">{{__('GHI CHÚ')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($listCardCanAccrual as $item)
                                    <tr>
                                        <td>
                                            <label class="m-radio m-radio--bold m-radio--state-success">
                                                <input type="radio" name="card_can_accrual" value="{{$item['card_code']}}">
                                                <span></span>
                                            </label>
                                        </td>
                                        <td>{{$item['card_code']}}</td>
                                        <td>{{$item['card_name']}}</td>
                                        <td class="ss--text-center">{{$item['actived_date']!=''?date_format(new DateTime($item['actived_date']), 'd/m/Y'):''}}</td>
                                        <td class="ss--text-center">{{$item['expired_date']!=''?date_format(new DateTime($item['expired_date']), 'd/m/Y'):''}}</td>
                                        <td>{{$item['note']}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row col-12">
                        <span class="err-choose-card" style="color: red"></span>
                    </div>
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
                    <button type="button" onclick="serviceCardSold.submitAccrual('{{$cardCodeCurrent}}')"
                            class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('CỘNG DỒN')</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

