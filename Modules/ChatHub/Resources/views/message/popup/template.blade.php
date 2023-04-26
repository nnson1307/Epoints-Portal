@if($detail['image_url'])
    <img src="{{$detail['image_url']}}" height="83px">
@endif
<h3>{{$detail['title']}}</h3>
<p>{{$detail['subtitle']}}</p>
@foreach($detail['child'] as $item)
    <button type="button" class="btn btn-primary">{{$item->title}}</button>
@endforeach