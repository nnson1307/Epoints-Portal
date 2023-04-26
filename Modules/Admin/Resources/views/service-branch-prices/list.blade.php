<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table ss--nowrap" id="tb-branch-price">
        <thead>
        <tr class="ss--font-size-th">
            <th>#</th>
            <th>{{__('Dịch vụ')}}</th>
            <th class="ss--text-center">{{__('NHÓM')}}</th>
            <th class="ss--text-center">{{__('GIÁ CHUẨN')}}</th>
            @foreach ($BRANCH_LIST as $key => $value)
                <th class="ss--text-center">{{ $value }}</th>
            @endforeach
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                <tr class="ss--font-size-13">
                    <td>{{$key+1}}</td>
                    <td class="ss--width-150">{{$item[0]['service_name']}}</td>
                    <td class="ss--text-center">{{$item[0]['service_category_name']}}</td>
                    <td class="ss--text-center">{{number_format($item[0]['price_standard'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0) }}</td>
                    @foreach ($item[1] as $v)
                        <td class="ss--text-center">{{ ($v == 0) ? 'Không có' : number_format($v, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0) }}</td>
                    @endforeach
                    <td class="pull-right">
                        @if(in_array('admin.service-branch-price.edit',session('routeList')))
                            <a href="{{route('admin.service-branch-price.edit',array ('id'=>$item[0]['service_id']))}}"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                               title="View">
                                <i class="la la-edit"></i>
                            </a>
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>

    </table>
</div>
{{ $SERVICE_LIST->links('helpers.paging') }}
