<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">@lang('TÊN DEAL')</th>
            <th class="tr_thead_list">@lang('GIÁ TRỊ DEAL')</th>
            <th class="tr_thead_list">@lang('HÀNH TRÌNH')</th>
            <th class="tr_thead_list">@lang('MÃ DEAL')</th>
            <th class="tr_thead_list">@lang('NGÀY DỰ KIẾN')</th>
            <th class="tr_thead_list">@lang('NGƯỜI SỞ HỮU')</th>
            <th class="tr_thead_list">@lang('NGƯỜI ĐƯỢC PHÂN BỔ')</th>
            <th class="tr_thead_list">@lang('PIPELINE')</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST_DEAL) && count($LIST_DEAL) > 0)
            @foreach ($LIST_DEAL as $key => $value)
                <tr>
                    <td>
                        <a href="{{route('customer-lead.customer-deal.show') . 'id=' . $value['deal_id']}}">
                            {{$value['deal_name']}}
                        </a>
                    </td>
                    <td>
                        {{number_format($value['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                    </td>
                    <td>{{$value['journey_name']}}</td>
                    <td>{{$value['deal_code']}}</td>
                    <td>{{\Carbon\Carbon::parse($value['closing_date'])->format('d/m/Y')}}</td>
                    <td>{{$value['owner_name']}}</td>
                    <td>{{$value['sale_name']}}</td>
                    <td>{{$value['pipeline_name']}}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>

@if(isset($LIST_DEAL) && count($LIST_DEAL) > 0)
        {{ $LIST_DEAL->links('helpers.paging') }}
@endif

<script>
    $('#oncall-deal-autotable').find('.m-datatable__pager-link').click(function(){
        $.ajax({
            url: laroute.route('extension.get-list-deal-paging'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                oncall_type: $('#oncall_type').val(),
                oncall_code: $('#oncall_code').val(),
                page: $(this).attr('data-page'),
            },
            success: function (res) {
                $('#oncall-deal-autotable').find('.table-content').html(res.html);

            }
        });
    });
</script>
