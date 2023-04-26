@foreach($listFile as $key => $item)
    <div class="col-12 block-file-{{$key}} mb-2">
        <button type="button" class="delete-file" onclick="Acceptance.removeFile({{$key}})">X</button>
        <input type="hidden" name="pathFile[{{$key}}][path]" value="{{$item['path']}}">
        <a href="{{$item['path']}}">{{$item['path']}}</a>
    </div>
@endforeach