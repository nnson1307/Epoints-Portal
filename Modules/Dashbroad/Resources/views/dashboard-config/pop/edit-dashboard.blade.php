<div class="modal fade show" id="modal-dashboard-config" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> @lang('CHỈNH SỬA BỐ CỤC')
                </h5>
            </div>
            <div class="modal-body">
                <form id="form-dashboard-config">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Tên bố cục (tiếng việt)'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <input type="hidden" id="dashboard_id" name="dashboard_id" value="{{$item['dashboard_id']}}"/>
                                    <input class="form-control" id="name_vi" name="name_vi" value="{{$item['name_vi']}}"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Tên bố cục (tiếng anh)'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <input class="form-control" id="name_en" name="name_en" value="{{$item['name_en']}}"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Trạng thái')
                                </label>
                                <div class="input-group">
                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                        <label style="margin: 0 0 0 10px; padding-top: 4px">
                                            <input type="checkbox" class="manager-btn" {{$item['is_actived'] == 1 ? 'checked' : ''}} id="is_actived" name="is_actived">
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="m-form__actions m--align-right w-100">
                    <a href="{{route('dashbroad.dashboard-config')}}"
                       class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('HỦY')</span>
                            </span>
                    </a>
                    <button type="button" onclick="dashboardConfig.savePopEditConfig()"
                            class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('LƯU THÔNG TIN')</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
