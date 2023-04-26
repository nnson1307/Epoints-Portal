<div class="form-group text-center">
    <span class="font-weight-bold">@lang('BÁO CÁO PHỄU CHUYỂN ĐỔI')</span> <br/>
    <span class="font-weight-bold">{{$created_at}}
    </span>  <br/>
    <span class="font-weight-bold">@lang("Pipeline"): {{$pipeline_name}}</span><br/>
</div> <br/>

<div class="form-group table-responsive">
    <table class="table table-bordered">
        <thead class="rowGroup">
        <tr class="text-center table-primary">
            <th scope="col" class="text-left">@lang('Nguồn lead')</th>
            @if(isset($listJourney) && count($listJourney) > 0)
                @foreach($listJourney as $item)
                    <th scope="col">{{$item['journey_name']}}</th>
                @endforeach
            @endif
        </tr>
        </thead>
        <tbody>
        @if(isset($listCustomerSource) && count($listCustomerSource) > 0)
            @foreach($listCustomerSource as $key => $item)
                <tr>
                    <th scope="row">{{$item['customer_source_name']}}</th>

                    @if(isset($quantity[$key]) && count($quantity[$key]) > 0)
                        @foreach($quantity[$key] as $key2 => $item)
                            <td>
                                {{$quantity[$key][$key2] }}
                            </td>
                        @endforeach
                    @endif
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>