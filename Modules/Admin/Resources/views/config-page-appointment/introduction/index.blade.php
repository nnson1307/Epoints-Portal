    <div class="m-portlet">
        <form class="description">
            <div class="m-portlet__body">
                <div class="ss--background">
                    <div class="form-group m-form__group">
                        {{--<div id="description" class="summernote" name="description"></div>--}}

                        <textarea name="description" id="description"
                                  class="form-control summernote" cols="5" rows="5">{!! $introduction !!}</textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer save-attribute m--margin-right-20">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                    <div class="m-form__actions m--align-right">
                        <button onclick="update('{{$id_introduction}}')" type="button"
                                class="ss--btn-mobiles m--margin-bottom-5 btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span class="ss--text-btn-mobi">
                                <i class="la la-check"></i>
                                <span>{{__('LƯU THÔNG TIN')}}</span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
