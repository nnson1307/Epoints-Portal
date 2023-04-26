<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Nhân viên</th>
                <th class="text-center">Tổng số</th>
                @foreach($listJourney as $item)
                    <th class="text-center">{{$item['journey_name']}}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($listLead as $key => $item)
                <tr>
                    <td>{{($listLead->currentPage() - 1)* $listLead->perPage() + $key+1 }}</td>
                    <td>{{$item['full_name']}}</td>
                    <td class="text-center">{{$item['total_pipeline']}}</td>
                    @foreach($listJourney as $itemJourney)
                        <td class="text-center">{{isset($listLead[$key]['pipeline'][$itemJourney['journey_code']]) ? count($listLead[$key]['pipeline'][$itemJourney['journey_code']]) : 0}}</td>
                    @endforeach
                </tr>
            @endforeach

        </tbody>
    </table>
</div>

{{ $listLead->links('customer-lead::helpers.paging-funnel-lead') }}