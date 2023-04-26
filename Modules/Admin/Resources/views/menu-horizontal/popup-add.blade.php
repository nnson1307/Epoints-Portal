<div class="modal fade show" id="modal-add" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    {{__('THÊM MENU THANH ĐIỀU HƯỚNG')}}
                </h5>
            </div>
            <div class="modal-body">
                <form id="form-add">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Nhóm chức năng'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <select class="form-control" id="admin_menu_category" name="admin_menu_category" style="width:100%;">
                                        @if($menuCategory != null && count($menuCategory) > 0)
                                            @foreach($menuCategory as $item)
                                                <option value="{{$item['menu_category_id']}}">{{$item['menu_category_name']}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Chức năng'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <select class="form-control admin_menu" id="admin_menu" name="admin_menu"
                                            style="width:100%;" multiple>
                                    </select>
                                </div>
                            </div>
                        </div>
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
                    <button type="submit" onclick="add.save()"
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