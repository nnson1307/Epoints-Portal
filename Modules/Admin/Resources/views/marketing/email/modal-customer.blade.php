<div class="modal fade show" id="add-customer">
    <div class="modal-dialog modal-dialog-centered modal-lg-email-campaign" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> {{__('THÊM KHÁCH HÀNG')}}
                </h5>
                {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
                {{--<span aria-hidden="true">×</span>--}}
                {{--</button>--}}
            </div>
            <div class="modal-body">
                <div class="padding_row bg">
                    <div class=" row">
                        <div class="form-group m-form__group col-lg-3">
                            <input class="form-control" id="name" name="name" placeholder="{{__('Tìm kiếm khách hàng')}}">
                        </div>
                        <div class="col-lg-3">
                            <div class="input-group input-group date">
                                <div class="m-input-icon m-input-icon--right">
                                    <input type="text" readonly class="form-control m-input"
                                           placeholder="{{__('Chọn ngày')}}" style="background-color: white"
                                           id="birthday" name="birthday">
                                    <span class="m-input-icon__icon m-input-icon__icon--right">
                                        <span><i class="la la-calendar"></i></span></span>
                                </div>

                            </div>
                        </div>
                        <div class="form-group m-form__group input-group date gen col-lg-2">
                            <select class="form-control" style="width: 100%" id="gender" name="gender">
                                <option value="">{{__('Giới tính')}}</option>
                                <option value="male">{{__('Nam')}}</option>
                                <option value="female">{{__('Nữ')}}</option>
                                <option value="other">{{__('Khác')}}</option>
                            </select>
                        </div>
                        <div class="form-group m-form__group input-group date col-lg-2">
                            <select class="form-control" id="branch_id" name="branch_id" style="width: 100%">
                                <option></option>
                                @foreach($optionBranch as $key=>$value)
                                    <option value="{{$key}}">{{$value}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group m-form__group col-lg-2">
                            <button type="button" class="btn btn-info btn-sm color_button height-40" onclick="edit.search()">
                                {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive m--margin-top-30">
                    <div class="m-scrollable m-scroller ps ps--active-y w-100 pr-0" data-scrollable="true"
                         style="height: 300px; overflow: hidden;">
                        <table class="table table-striped m-table m-table--head-bg-default customer_list">
                            <thead class="bg">
                            <tr>
                                <th width="2%" class="tr_thead_list">#</th>
                                <th width="50%" class="tr_thead_list">{{__('KHÁCH HÀNG')}}</th>
                                <th width="10%" class="tr_thead_list">{{__('EMAIL')}}</th>
                                <th width="8%" class="tr_thead_list">{{__('NGÀY SINH')}}</th>
                                <th width="10%" class="tr_thead_list">{{__('GIỚI TÍNH')}}</th>
                                <th width="10%" class="tr_thead_list">{{__('CHI NHÁNH')}}</th>
                                <th width="10%" class="tr_thead_list">
                                    <label class="m-checkbox m-checkbox--bold m-checkbox--state-success m--padding-top-5">
                                        <input class="check_all" name="check_all" type="checkbox">
                                        <span></span>
                                    </label> {{__('TẤT CẢ')}}
                                </th>
                            </tr>
                            </thead>
                            <tbody class="customer_list_body" style="font-size: 13px">

                            </tbody>
                        </table>

                    </div>
                    <span class="error_append" style="color: red"></span>
                </div>
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
                    <button type="submit" onclick="edit.click_append()"
                            class="btn btn-info  color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<i class="la la-check"></i>
							<span>{{__('CHỌN')}}</span>
							</span>
                    </button>

                </div>
            </div>
        </div>
    </div>
</div>