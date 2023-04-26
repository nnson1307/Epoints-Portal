<table class="table table-striped">
    <thead>
    <tr>
        <th scope="col">@lang('Ngày đăng kiểm')</th>
        <th scope="col">@lang('Sản phẩm')</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $key => $regisDate)
        <tr>
            <td>{{$key}}</td>
            <td>
                @foreach($regisDate as $item)
                    {{$item['product_name']}}
                    <br><br>
                @endforeach
            </td>
        </tr>
    @endforeach
    </tbody>
</table>