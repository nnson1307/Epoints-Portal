<link href="{{asset('vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css')}}" rel="stylesheet"
      type="text/css"/>
<link href="{{asset('vendors/bootstrap-datetime-picker/css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet"
      type="text/css"/>
<link href="{{asset('vendors/bootstrap-timepicker/css/bootstrap-timepicker.min.css')}}" rel="stylesheet"
      type="text/css"/>
<link href="{{asset('vendors/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('vendors/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.css')}}" rel="stylesheet"
      type="text/css"/>
<link href="{{asset('vendors/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.css')}}" rel="stylesheet"
      type="text/css"/>
<link href="{{asset('vendors/bootstrap-select/dist/css/bootstrap-select.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('vendors/select2/dist/css/select2.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('vendors/nouislider/distribute/nouislider.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('vendors/owl.carousel/dist/assets/owl.carousel.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('vendors/owl.carousel/dist/assets/owl.theme.default.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('vendors/ion-rangeslider/css/ion.rangeSlider.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('vendors/ion-rangeslider/css/ion.rangeSlider.skinFlat.css')}}" rel="stylesheet"
      type="text/css"/>
<link href="{{asset('vendors/dropzone/dist/dropzone.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('vendors/summernote/dist/summernote.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('vendors/animate.css/animate.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('vendors/toastr/build/toastr.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('vendors/jstree/dist/themes/default/style.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('vendors/chartist/dist/chartist.min.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('vendors/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('vendors/socicon/css/socicon.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('vendors/vendors/line-awesome/css/line-awesome.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('vendors/vendors/flaticon/css/flaticon.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('vendors/vendors/metronic/css/styles.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('vendors/vendors/fontawesome5/css/all.min.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('static/backend/assets/vendors/custom/jquery-ui/jquery-ui.bundle.min.css')}}" rel="stylesheet"
      type="text/css"/>
<!--end:: Global Optional Vendors -->
<!--begin::Global Theme Styles -->
<link href="{{asset('static/backend/assets/demo/base/style.bundle.css')}}" rel="stylesheet" type="text/css"/>
<!--RTL version:<link href="{{asset('static/backend/assets/demo/base/style.bundle.rtl.css')}}" rel="stylesheet" type="text/css" />-->

<!-- Menu mobile -->
<link href="{{asset('vendors/menu-hc/hc-offcanvas-nav.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('static/backend/css/customize-hc-menu.css')}}" rel="stylesheet" type="text/css"/>

<!--end::Global Theme Styles -->
<link rel="shortcut icon"
      href="{{isset(config()->get('config.short_logo')->value) ? config()->get('config.short_logo')->value : ''}}"/>
<link href="{{asset('static/backend/css/customize.css')}}" rel="stylesheet" type="text/css"/>
<form class="container" id="">
    <div class="m-portlet__body">
        <div id="template_preview" class="d-flex justify-content-center">
            @if(isset($item['type_template_follower']) && $item['type_template_follower'] == 0)
                @include('zns::template.follower.preview.text')
            @elseif(isset($item['type_template_follower']) && $item['type_template_follower'] == 1)
                @include('zns::template.follower.preview.image')
            @elseif(isset($item['type_template_follower']) && $item['type_template_follower'] == 2)
                @include('zns::template.follower.preview.list')
            @elseif(isset($item['type_template_follower']) && $item['type_template_follower'] == 3)
                @include('zns::template.follower.preview.file')
            @elseif(isset($item['type_template_follower']) && $item['type_template_follower'] == 4)
                @include('zns::template.follower.preview.inforuser')
            @endif
        </div>
    </div>

</form>
<script src="{{asset('vendors/jquery/dist/jquery.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/bootstrap/dist/js/bootstrap.min.js')}}" type="text/javascript"></script>
