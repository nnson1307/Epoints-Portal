<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Nguồn</th>
                <th class="text-center">Tổng số</th>
                @foreach($listJourney as $item)
                    <th class="text-center">{{$item['journey_name']}}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($listSource as $key => $item)
                <tr>
                    <td>{{($listSource->currentPage() - 1)* $listSource->perPage() + $key+1 }}</td>
                    <td>{{$item['customer_source_name']}}</td>
                    <td class="text-center">{{$item['total_pipeline']}}</td>
                    @foreach($listJourney as $itemJourney)
                        <td class="text-center">{{isset($listSource[$key]['pipeline'][$itemJourney['journey_code']]) ? count($listSource[$key]['pipeline'][$itemJourney['journey_code']]) : 0}}</td>
                    @endforeach
                </tr>
            @endforeach

        </tbody>
    </table>
</div>

{{ $listSource->links('customer-lead::helpers.paging-funnel-source') }}