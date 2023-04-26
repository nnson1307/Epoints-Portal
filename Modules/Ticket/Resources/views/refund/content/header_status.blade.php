<ol class="stepBar step{{ count($statusList) }}">
    @foreach ($statusList as $key => $value)
        @if ($key)
            <li class="step {{ $key == $item->status ? 'current' : '' }}"
                style="width:{{ 100 / (count($statusList) - 1) }}%">
                {{ $value }}
            </li>
        @endif
    @endforeach
</ol>