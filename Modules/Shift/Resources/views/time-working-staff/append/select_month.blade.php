@for($i = 1; $i <= 12; $i++)
<option value="{{$i}}" {{$i == \Carbon\Carbon::now()->format('m') && $year == \Carbon\Carbon::now()->format('Y') ? 'selected': ''}}>
    {{ __('Tháng ' . $i) }}
</option>
@endfor