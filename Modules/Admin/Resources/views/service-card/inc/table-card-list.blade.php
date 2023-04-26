<style>

    .dropbtn {
        background-color: #4CAF50;
        color: white;
        padding: 16px;
        font-size: 16px;
        border: none;
    }


    .dropdown {
        position: relative;
        display: inline-block;
    }


    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f1f1f1;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 9;
    }

    /*/ Links inside the dropdown /*/
    .dropdown-content a {
        color: #ff7652;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }


    .dropdown-content a:hover {
        background-color: #ddd;
    }
    .dropdown:hover .dropdown-content {
        display: block;
    }
    .dropdown:hover .dropbtn{
        background-color: #3e8e41;
    }
</style>


<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-primary" id="card_list">

        <thead>
        <tr>
            <th>#</th>
            <th>{{__('Mã thẻ')}}</th>
            <th>{{__('Chi nhánh')}}</th>
            <th>{{__('Người sử dụng')}}</th>
            <th>{{__('Ngày tạo')}}</th>
            <th>{{__('Ngày sử dụng')}}</th>
            <th>{{__('Người tạo')}}</th>
            <th>{{__('Trạng thái')}}</th>
        </tr>
        </thead>
        <tbody>
        {{--@if(isset($LIST))--}}
            {{--@foreach($LIST as $key =>$card)--}}
                {{--<tr>--}}
                    {{--<td>{{$key+1}}</td>--}}
                    {{--<td>{{$card->code}}</td>--}}
                    {{--<td>{{$card->branch_name}}</td>--}}
                    {{--<td>{{$card->customer_name}}</td>--}}
                    {{--<td>{{($card->created_at != null) ? \Carbon\Carbon::parse($card->created_at)->format('d-m-Y h:i') :""}}</td>--}}
                    {{--<td>{{($card->actived_date != null) ? \Carbon\Carbon::parse($card->actived_date)->format('d-m-Y h:i') : ""}}</td>--}}
                    {{--<td>{{$card->staff_name}}</td>--}}
                    {{--<td>--}}
                        {{--@if($card->customer_service_card_id!=null)--}}
                            {{--<span class="m-badge  m-badge--success m-badge--wide">{{__('Đang sử dụng')}}</span>--}}
                        {{--@else--}}
                            {{--<span class="m-badge  m-badge--danger m-badge--wide">Chưa sử dụng</span>--}}
                        {{--@endif--}}
                    {{--</td>--}}
                {{--</tr>--}}
            {{--@endforeach--}}
        {{--@endif--}}
        </tbody>

    </table>
</div>
{{ $LIST->links('helpers.paging') }}

