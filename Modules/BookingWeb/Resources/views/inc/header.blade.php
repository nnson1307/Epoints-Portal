<button class="navbar-toggler btn-menu-mb" type="button" >
    <span class="dark-blue-text">
        <i class="fas fa-bars fa-1x font-menu-btn"></i>
    </span>
</button>
<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">

    <div class="carousel-inner">
        <?php $tmp = 0; ?>
        @if(count($slider) == 0)
            <div class="carousel-item active">
                <a href="javascript:void(0)" target="_blank" ><img class="d-block w-100 hero relative " src="{{asset('static/booking-template/image/default-placeholder.png')}}"></a>
            </div>
        @else
            @foreach($slider as $value)
                @if($tmp == 0)
                    <div class="carousel-item active">
                        <a href="{{$value['link']}}" target="_blank" ><img class="d-block w-100 hero relative " src="{{$value['name']}}"></a>
                    </div>
                    <?php $tmp = 1; ?>
                @else
                    <div class="carousel-item">
                        <a href="{{$value['link']}}" target="_blank" ><img class="d-block w-100 hero relative " src="{{$value['name']}}"></a>
                    </div>
                @endif
            @endforeach
        @endif
    </div>
    @if(isset($logo))
{{--        @if(count($slider) != 0)--}}
            <div class="box-logo">
                <img src="{{$logo}}" height="100%" width="100%">
            </div>
{{--        @else--}}
{{--            <div class=" box-logo box-logo-slider-null">--}}
{{--                <img src="/{{$logo}}" height="100%" width="100%">--}}
{{--            </div>--}}
{{--        @endif--}}
    @else
        <div class="box-logo">
            <img src="{{asset('static/booking-template/image/default-placeholder.png')}}" height="100%" width="100%">
        </div>
    @endif
    @if(count($slider) > 1)
        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    @endif
</div>
<script>
    $('.btn-menu-mb').click(function () {
        var hClass = $('.div_menu').hasClass('active_menu');
        if (hClass){
            $('.div_menu').removeClass('active_menu');
        } else {
            $('.div_menu').addClass('active_menu');
        }
    })
</script>