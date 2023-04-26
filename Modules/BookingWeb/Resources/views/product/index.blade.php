@extends('bookingweb::layout')

@section('content')
    {{session('service_category_id')}}
    <div class="form-group">
        <div class="kt-subheader__breadcrumbs fix-select2">
            <a href="{{route('product')}}" class="kt-subheader__breadcrumbs-link">
                {{__('Sản phẩm')}}                       </a>
            <span class="kt-subheader__breadcrumbs-separator "></span>
            <select class="product-select" onchange="product.change()" id="product_category">
                <?php $tmp = 0; ?>
                <option value="all" selected>{{__('Tất cả')}}</option>
                @foreach($product['product_category'] as $key => $value)
                    <option value="{{$key}}">{{$value}}</option>
                @endforeach
            </select>
            <!-- <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Active link</span> -->
        </div>
    </div>
    <div class="form-group">
        <div class="kt-grid kt-wizard-v3 kt-wizard-v3--white" id="kt_wizard_v3" data-ktwizard-state="first">
            <div class="kt-grid__item">
                <div id="list-product">

                </div>
            </div>
        </div>
    </div>
@stop
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/booking-template/css/custom.css?v='.time())}}">
@endsection
@section('after_script')
    <script>
        var display = '{{$display}}';
        $('#product_category').select2({
            minimumResultsForSearch: -1,
            allowClear: false
        });
    </script>
    <script src="{{asset('static/booking-template/js/booking/product.js?v='.time())}}" type="text/javascript"></script>
    @if (isset($product_category_id))
        <script>
            $('#product_category').val({{ $product_category_id }});
            $('#product_category').trigger('change');
        </script>
    @endif
@stop