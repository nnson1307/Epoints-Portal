<style>

</style>

<div id="print-card">
    <div class="img-card" style="margin: 0 auto;">
        @foreach($list_image as $item)
            <img src="{{$item}}" height="auto" width="100%">
        @endforeach
    </div>
</div>

