@for($i = 1; $i <= $week_in_year; $i++)
<option value="{{$i}}" {{$i == \Carbon\Carbon::now()->isoWeek && $year == \Carbon\Carbon::now()->format('Y') ? 'selected': ''}}>
    <?php
     $now = \Carbon\Carbon::parse($year.'-01-01');
    $date = $now->setISODate($now->format('Y'), $i);
    ?>
    @lang('Tuần') {{$i. ' ('.$date->startOfWeek()->format('d/m/Y'). ' - '. $date->endOfWeek()->format('d/m/Y'). ')'}}
</option>
@endfor