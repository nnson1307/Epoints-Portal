@php
    $listKeyColumSearchConfig = !empty($listColumnConfig['listColumnSearchConfig']) ? array_keys($listColumnConfig['listColumnSearchConfig']) : [];
    $listKeyColumShowConfig = !empty($listColumnConfig['listColumShowConfig']) ? array_keys($listColumnConfig['listColumShowConfig']) : [];
@endphp
<div class="modal fade" id="modal-config" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="config_search">
                <div class="modal-header">
                    <h4 class="modal-title ss--title m--font-bold">
                        <i class="fa fa-cog ss--icon-title m--margin-right-5"></i>
                        {{ __('CẤU HÌNH TÌM KIẾM') }}
                    </h4>
                </div>
                <div class="modal-body modal-body-config">
                    <div class="row m-0">
                        @if (!empty($listColumnConfig['listColumSearchDefault']))
                            @foreach ($listColumnConfig['listColumSearchDefault'] as $key => $value)
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-3 p-3">
                                            <div class="ss--font-size-13 text-center">
                                                <label class="m-checkbox m-checkbox--air">
                                                    <input class="check-page" onchange="Project.configSearchColum()"
                                                        {{ in_array($key, $listKeyColumSearchConfig) ? 'checked' : '' }}
                                                        @if ($key <= 2) {{ 'disabled' }} @endif
                                                        type="checkbox" name="search" value="{{ $key }}">
                                                    <span></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-9 p-3">
                                            <div class="ss--font-size-13">{{ $value['nameConfig'] }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            <div class="config_column">
                <div class="modal-header">
                    <h4 class="modal-title ss--title m--font-bold">
                        <i class="fa fa-cog ss--icon-title m--margin-right-5"></i>
                        {{ __('CẤU HÌNH DANH SÁCH') }}
                    </h4>
                </div>
                <div class="modal-body modal-body-config">
                    <div class="row m-0">
                        @if (!empty($listColumnConfig['listColumShowDefault']))
                            @foreach ($listColumnConfig['listColumShowDefault'] as $key => $value)
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-3 p-3">
                                            <div class="ss--font-size-13 text-center">
                                                <label class="m-checkbox m-checkbox--air">

                                                    <input class="check-page" onchange="Project.configShowColum()"
                                                        {{ in_array($key, $listKeyColumShowConfig) ? 'checked' : '' }}
                                                        @if ($key <= 5) {{ 'disabled' }} @endif
                                                        type="checkbox" name="column" value="{{ $key }}">
                                                    <span></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-9 p-3">
                                            <div class="ss--font-size-13">{{ $value['nameConfig'] }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                    <div class="m-form__actions m--align-right">
                        <button data-dismiss="modal"
                            class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                            <span class="ss--text-btn-mobi">
                                <i class="la la-arrow-left"></i>
                                <span>{{ __('HỦY') }}</span>
                            </span>
                        </button>

                        <button type="button" onclick="Project.saveConfig()"
                            class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                            <span class="ss--text-btn-mobi">
                                <i class="la la-check"></i>
                                <span>{{ __('LƯU THÔNG TIN') }}</span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
