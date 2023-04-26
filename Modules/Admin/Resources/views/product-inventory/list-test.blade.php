<style>

    table {
        table-layout: fixed;
        width: 100%;
        *margin-left: -100px; /*ie7*/
    }

    td, th {
        vertical-align: top;
        border-top: 1px solid #ccc;
        padding: 10px;
        width: 100px;
    }

    th {
        /*  position:absolute;
          *position: relative; /*ie7*/
        /*  left:0; */
        width: 100px;
    }

    .hard_left {
        position: absolute;
        *position: relative; /*ie7*/
        left: 0;
        width: 100px;
    }

    .next_left {
        position: absolute;
        *position: relative; /*ie7*/
        left: 100px;
        width: 100px;
    }

    .next_left2 {
        position: absolute;
        *position: relative; /*ie7*/
        left: 200px;
        width: 100px;
    }

    .next_left3 {
        position: absolute;
        *position: relative; /*ie7*/
        left: 300px;
        width: 100px;
    }

    .outer {
        position: relative
    }

    .inner {
        overflow-x: scroll;
        overflow-y: visible;
        width: 63%;
        margin-left: 400px;
    }
</style>
<div class="outer table-responsive" role="tabpanel">
    <div class="">
        <table class="table m-table m-table--head-bg-primary">
            <thead>
            <tr>
                <th class="hard_left">#</th>
                <th class="next_left">{{__('Mã sản phẩm')}}</th>
                <th class="next_left2">{{__('Tên sản phẩm')}}</th>
                <th class="next_left3">{{__('Tất cả kho')}}</th>
                @foreach($wareHouse as $key=>$value)
                    <th>{{$value}}</th>
                @endforeach
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($result as $key=> $item)
                <tr>
                    <td class="hard_left">{{$key+1}}</td>
                    <td class="next_left">{{$item['productCode']}}</td>
                    <td class="next_left2">{{$item['productName']}}</td>
                    <td class="next_left3">{{$item['productInventory']}}</td>
                    @foreach($item['warehouse'] as $k=> $i)
                        <td>{{$i}}</td>
                    @endforeach
                    <td><a href="#">{{__('Xem lịch sử')}}</a></td>

                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>