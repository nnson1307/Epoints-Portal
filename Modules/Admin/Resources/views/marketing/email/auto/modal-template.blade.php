<div class="modal fade show" id="setting-template" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title">
                    {{__('CHỌN TEMPLATE')}}
                </h5>

            </div>
            <form id="form-content">
                <div class="modal-body">
                    <div id="carouselExampleControls" class="carousel slide" data-interval="false">
                        <input type="hidden" id="link_hidden" name="link_hidden"
                               value="{{asset('static/backend/images/template-email')}}">
                        <div class="carousel-inner append_carousel">
                            {{--<div class="carousel-item active">--}}
                                {{--<img class="d-block w-100" src="https://static1.squarespace.com/static/52bf4341e4b0f14b4cc98955/t/52f7a002e4b07ae93f19ed8c/1391960071215/iStock_000014541666Medium.jpg?format=750w"--}}
                                     {{--alt="1" >--}}
                            {{--</div>--}}
                            {{--<div class="carousel-item">--}}
                                {{--<img class="d-block w-100" src="https://www.windowscentral.com/sites/wpcentral.com/files/styles/xlarge/public/field/image/2015/06/windows-10-hero.jpg?itok=Dtu_g-7-"--}}
                                     {{--alt="2">--}}
                            {{--</div>--}}
                            {{--<div class="carousel-item">--}}
                                {{--<img class="d-block w-100" src="https://www.studentdebtrelief.us/wp-content/uploads/2018/01/defaulted-student-loan.jpg"--}}
                                     {{--alt="3">--}}
                            {{--</div>--}}
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
                        <button type="button" onclick="auto.submit_template(1)"
                                class="btn btn-info color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<i class="la la-check"></i>
							<span>{{__('ĐỒNG Ý')}}</span>
							</span>
                        </button>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>