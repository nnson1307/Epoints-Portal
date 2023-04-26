<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-primary">

        <thead>
        <tr>
            <th>#</th>
            <th>{{__('Khung giờ hẹn')}}</th>
            <th>{{__('Ngày tạo')}}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{date("H:i",strtotime($item['time']))}}</td>
                    <td>{{date("d/m/Y",strtotime($item['created_at']))}}</td>
                    <td>
                        <button onclick="customer_appointment_time.edit({{$item['customer_appointment_time_id']}})" class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"      >
                            <i class="la la-edit"></i>
                        </button>
                        <button onclick="customer_appointment_time.remove(this, {{$item['customer_appointment_time_id']}})" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                title="Delete">
                            <i class="la la-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>

    </table>
</div>
{{ $LIST->links('helpers.paging') }}
