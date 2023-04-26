<div class="row">
    <div class="col-lg-12">
        <div class="row">
            @php $i = 0; @endphp
            @foreach ($FILTER as $name => $item)
                @if ($i > 0 && ($i % 4 == 0))
        </div>
        <div class="form-group m-form__group row align-items-center">
            @endif
            @php $i++; @endphp
            <div class="col-lg-3 input-group">
                @if(isset($item['text']))
                    <div class="input-group-append">
                        <span class="input-group-text">
                            {{ $item['text'] }}
                        </span>
                    </div>
                @endif
                {!! Form::select($name, $item['data'], $item['default'] ?? null, ['class' => 'form-control m-input m_selectpicker']) !!}
            </div>
            @endforeach
        </div>
    </div>
</div>