<button type="button" id="action-button" class="btn btn-bold btn-label-brand btn-sm" data-toggle="modal" data-target="#kt_modal_2" style="display: none;">Launch Modal</button>
<div class="modal fade show" id="kt_modal_2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-modal="true">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 80%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ $trans['create']['group']['title'] }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body" style="overflow: auto;">
                <div class="form-group">
                    <div class="row">
                        <div class="col-lg-3">
                            <input type="text" class="form-control" id="search-name"
                                   placeholder="{{ $trans['create']['group']['placeholder']['NAME'] }}">
                        </div>
                        <div class="col-lg-3">
                            <select type="text" class="form-control" id="search-type">
                                <option value="">
                                    {{ $trans['create']['group']['placeholder']['TYPE'] }}
                                </option>
                                <option value="user_define">
                                    {{ $trans['create']['group']['type']['USER_DEFINE'] }}
                                </option>
                                <option value="auto">
                                    {{ $trans['create']['group']['type']['AUTO'] }}
                                </option>
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <button type="button" class="btn btn-primary color_button" id="submit-search">
                                {{ $trans['create']['group']['BTN_SEARCH'] }}
                            </button>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="kt-section__content" id="group-item-list"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="close-btn">
                    {{ $trans['create']['group']['BTN_CLOSE'] }}
                </button>
                <button type="button" class="btn btn-primary color_button" id="choose-group">
                    {{ $trans['create']['group']['BTN_ADD'] }}
                </button>
            </div>
        </div>
    </div>
</div>

