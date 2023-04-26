<style>
    .modal-lg-staff {
        max-width: 60% !important
    }
</style>

<div class="modal fade" id="modal-add-staff" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg-staff">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title ss--title m--font-bold">
                    <i class="fa fa-plus-circle"></i> {{__('THÊM NHÂN VIÊN HỖ TRỢ')}}
                </h4>
            </div>

            <div class="modal-body">
                <div id="autotable-staff-pop">
                    <div class="padding_row bg">
                        <form class="frmFilter">
                            <div class="row">
                                <div class="col-lg-3 form-group">
                                    <input type="text" class="form-control" name="search"
                                           placeholder="@lang("Nhập thông tin tìm kiếm")">
                                </div>
                                @php $i = 0; @endphp
                                @foreach ($FILTER as $name => $item)
                                    @if ($i > 0 && ($i % 3 == 0))
                            </div>
                            <div class="form-group m-form__group row align-items-center">
                                @endif
                                @php $i++; @endphp
                                <div class="col-lg-3 form-group input-group">
                                    @if(isset($item['text']))
                                        <div class="input-group-append">
                                        <span class="input-group-text">
                                            {{ $item['text'] }}
                                        </span>
                                        </div>
                                    @endif
                                    {!! Form::select($name, $item['data'], $item['default'] ?? null, ['class' => 'form-control m-input m_selectpicker']) !!}
                                </div>
                                @endforeach

                                <div class="col-lg-2 form-group">
                                    <button class="btn btn-primary color_button btn-search" style="display: block">
                                        @lang('TÌM KIẾM') <i class="fa fa-search ic-search m--margin-left-5"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="table-content m--margin-top-30">
                        @include('manager-work::managerWork.popup.list-staff-support')
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-form__actions m--align-right w-100">
                    <button onclick="WorkChild.chosePopStaff()"
                            class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                    </button>
                    <button type="button"
                            onclick="WorkChild.submitChooseStaffSupport()"
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


