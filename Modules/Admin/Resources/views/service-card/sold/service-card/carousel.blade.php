<div class="modal fade show" id="setting-template" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title">
                    {{__('HÌNH ẢNH')}}
                </h5>

            </div>
            <form id="form-content">
                <div class="modal-body">
                    <div id="carouselExampleControls" class="carousel slide" data-interval="false">
                        <input type="hidden" id="link_hidden" name="link_hidden"
                               value="{{asset('static/backend/images/template-email')}}">
                        <div class="carousel-inner append_carousel">

                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleControls" role="button"
                           data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">{{__('Previous')}}</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleControls" role="button"
                           data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">{{__('Next')}}</span>
                        </a>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="m-form__actions m--align-right w-100">
                        <a href="javascript:void(0)" data-dismiss="modal"
                           class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                           <span>
                            <i class="la la-arrow-left"></i>
                               <span>{{__('HỦY')}}</span>
                           </span>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>