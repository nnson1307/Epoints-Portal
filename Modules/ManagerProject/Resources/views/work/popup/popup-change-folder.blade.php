<div class="modal fade" id="popup_show_file_change_folder" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title ss--title m--font-bold">
                    {{ __('DI CHUYỂN ĐẾN LƯU TRỮ TÀI LIỆU') }}
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="form-change-folder" class="w-100">
                    <div class="form-group m-form__group">
                        <div class="row">
                            <label class="black_title col-2 ">
                                @lang('Tên tài liệu'): <b class="text-danger">*</b>
                            </label>
                            <div class="input-group col-10">
                                <div class="ss--padding-left-0 col-12">
                                    <input type="text" class="form-control" name="new_folder_display_name" id="name_file_change" value="{{$detail['file_name']}}">
                                    <input type="hidden" class="form-control" name="old_folder_display_name" id="old_folder_display_name" value="{{$detail['file_name']}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="row">
                            <label class="black_title col-2">
                                @lang('Thư mục'): <b class="text-danger">*</b>
                            </label>
                            <div class="input-group col-10">
                                <div class="col-lg-12 col-md-12 col-sm-12 ss--padding-left-0">
                                    <span  class="form-control" name="name_folder_display_change" onclick="openChangeFolder('{{getDomain()}}/file/file/verify?token={{$access_token}}&folder=show&decode=1&epoints_token={{$access_token}}&access_token={{$access_token}}&site={{getDomain()}}&brand-code={{$brandCode}}'); return false;" id="name_folder_display_change" ></span>
                                    <input type="hidden" class="form-control" name="name_folder_display_change_text" id="name_folder_display_change_text" value="">
                                    <input type="hidden" class="form-control" name="name_folder_change" id="name_folder_change" value="">
                                    <input type="hidden" class="form-control" name="password" id="password_folder_change" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="row">
                            <label class="black_title col-2">
                                @lang('Tài liệu'): <b class="text-danger">*</b>
                            </label>
                            <div class="input-group col-10">
                                <img width="50px" src="{{$detail['type'] == 'image' ? $detail['path'] : asset('static/backend/images/document.png')}}">
                            </div>
                        </div>
                    </div>
{{--                    <input type="hidden" name="file_path" value="{{ explode(config('filesystems.disks.minio.endpoint').'/',$detail['path'])[1] }}">--}}
                    <input type="hidden" name="file_path" value="{{ explode($detailConfig['minio_endpoint'].'/',$detail['path'])[1] }}">
                </form>
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
                        <button type="button" onclick="Image.submitChangeFolder()"
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

<script>
    function openChangeFolder(link) {
        var popup = popupCenterChange({url: link, title: 'xtf', w: 1200, h: 700});


        // Listen for messages
        window.addEventListener("message", function(event) {
            // Ignore messages from unexpected origins
            if(event.origin !== "{{getDomain()}}") {
                return;
            }

            $('#name_folder_display_change').text(event.data.folder_name_display);
            $('#name_folder_display_change_text').val(event.data.folder_name_display);
            $('#name_folder_change').val(event.data.folder_name);
            $('#password_folder_change').val(event.data.password);

        });
    }

    const popupCenterChange = ({url, title, w, h}) => {
        // Fixes dual-screen position                             Most browsers      Firefox
        const dualScreenLeft = window.screenLeft !==  undefined ? window.screenLeft : window.screenX;
        const dualScreenTop = window.screenTop !==  undefined   ? window.screenTop  : window.screenY;

        const width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
        const height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

        const systemZoom = width / window.screen.availWidth;
        const left = (width - w) / 2 / systemZoom + dualScreenLeft
        const top = (height - h) / 2 / systemZoom + dualScreenTop
        const newWindow = window.open(url, title,
            `
              scrollbars=yes,
              width=${w / systemZoom},
              height=${h / systemZoom},
              top=${top},
              left=${left}
              status=yes, toolbar=no, menubar=no, location=no,addressbar=no
      `
        )

        if (window.focus) newWindow.focus();
    }


</script>