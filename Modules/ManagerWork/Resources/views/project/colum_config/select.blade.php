<div class="form-group m-form__group">
    <div class="input-group">
        <select id="{{ $item['id'] }}" name="{{ $item['id'] }}" {{ isset($item['attr']) ? $item['attr'] : '' }}
            {{ isset($item['function']) && isset($item['event']) ? $item['event'] . '=' . $item['function'] : '' }}
            class="form-control select2 ">
            <option value="">{{ $item['placeholder'] }}</option>
            @foreach ($item['data'] as $key => $item)
                <option value="{{ $key }}">
                    {{ $item }}</option>
            @endforeach
        </select>
    </div>
</div>
