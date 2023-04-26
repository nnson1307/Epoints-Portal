<div class="form-group m-form__group">
    <div class="input-group">
        <input type="text" name="{{ $item['id'] }}" class="progress_input form-control" id="{{ $item['id'] }}" placeholder="{{ $item['placeholder'] }}">
        @if(isset($item['group']))
            <div class="input-group-append">
                <span class="input-group-text">{{ $item['group'] }}</span>
            </div>
        @endif
    </div>
</div>
