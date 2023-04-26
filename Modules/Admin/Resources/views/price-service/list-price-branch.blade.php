<div class="table-content">
    <div class="table-responsive">
        <table class="table table-striped m-table m-table--head-bg-primary" id="table_branch">

            <thead>
            <tr>
                <th>#</th>
                <th>{{__('Chi nhánh')}}</th>
                <th>Giá</th>
                <th>Giá khác</th>
            </tr>
            </thead>
            <tbody>
            @if(isset($LIST))
                @foreach ($LIST as $key => $item)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$item['branch_name']}}</td>
                        <td>{{$item['old_price']}}</td>
                        <td>{{$item['new_price']}}</td>
                    </tr>
                @endforeach
            @endif
            </tbody>

        </table>
    </div>
</div>
{{ $LIST->links('helpers.paging') }}