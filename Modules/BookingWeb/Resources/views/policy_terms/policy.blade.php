@extends('bookingweb::layout_policy_term')

@section('content')
    <div class="pb-5 form-group m-form__group">
        <h1><label style="font-size: 50px"><b>{{$policyTerms['faq_title']}}</b></label></h1><br>
        <label class="faq_content">{!! $policyTerms['faq_content'] !!}</label>
    </div>
@stop
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/booking-template/css/custom.css')}}">
@endsection
@section('after_script')
    <script src="{{asset('static/booking-template/js/booking/script.js?v='.time())}}" type="text/javascript">
    </script>
@stop