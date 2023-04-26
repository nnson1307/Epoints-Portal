<div class="modal fade" id="pop-camera" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 1300;">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title title_index" id="exampleModalLabel">{{__('CHỤP ẢNH CAMERA')}}</h5>
            </div>
            <div class="modal-body">
                <div id="my_camera"></div>

                <input type=button class="btn btn-sm m-btn--icon color" style="width:35%" value="Chụp camera" onclick="index.takeSnapshot()">

                <div id="results" style="margin-top: 15px;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md ss--btn " data-dismiss="modal">
                    {{__('HUỶ')}}
                </button>
                <button onclick="index.saveSnapshot()" type="button" class="btn btn-primary color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                    {{__('THÊM ẢNH')}}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    Webcam.set({
        width: 160,
        height: 150,
        image_format: 'jpeg',
        jpeg_quality: 90
    });
    Webcam.attach('#my_camera');
</script>