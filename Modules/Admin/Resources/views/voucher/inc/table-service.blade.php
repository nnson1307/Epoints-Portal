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

<form class="m-form m-form--fit m-form--label-align-right frmFilter" onsubmit="return null;">
    <div class="m-form m-form--label-align-right m--margin-bottom-30">
        <div class="row align-items-center">
            <div class="col-xl-6 order-2 order-xl-1">
                <div class="form-group m-form__group row align-items-center">
                    <div class="input-group col-xs-10">
                        {{--<div class="input-group-append">--}}
                        {{--<select class="form-control search-type" name="search_type">--}}

                        {{--<option value="service_cards.name">{{__('Tên Thẻ')}} </option>--}}
                        {{--<option value="code">{{__('Mã thẻ')}}  </option>--}}
                        {{--</select>--}}
                        {{--</div>--}}
                        <input type="text" class="form-control search_keyword" name="search_keyword"
                               placeholder="{{__('Nhập nội dung tìm kiếm')}}" value="{{isset($param) ? $param["keyword"] : ""}}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button" onclick="Voucher.FilterService('{{isset($object) ? implode(",",$object) :null}}')">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="row align-items-center m--margin-bottom-10">
            <div class="col-xl-3 order-2 order-xl-1">
                <div class="form-group m-form__group row align-items-center">
                    <div class="input-group">
                        {!! Form::select("service_type",$service_cate,(isset($param) ? $param["service_type"] : ""),["class"=>"form-control service_type","autocomplete"=>"off","onchange"=>"Voucher.FilterService('".(isset($object) ? implode(",",$object) :null)."')"]); !!}

                        {{--<select class="form-control product_type" name="product_type" onchange="Voucher.FilterProduct()">--}}

                        {{--<option value="name">{{__('Tên Thẻ')}} </option>--}}
                        {{--<option value="code">{{__('Mã thẻ')}}  </option>--}}
                        {{--</select>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="table-responsive" style="max-height: 520px; overflow: auto">
    <table class="table table-striped m-table m-table--head-bg-primary" id="card_list">

        <thead>
        <tr>
            <th>#</th>
            <th>{{__('Tên Dịch vụ')}}</th>
            <th>{{__('Mã Dịch vụ')}}</th>
            <th>{{__('Loại dịch vụ')}}</th>
            <th>
                <label class="m-checkbox m-checkbox--solid m-checkbox--success" style="margin-bottom: 14px">
                    <input type="checkbox" autocomplete="off" class="ckb-all" @if(isset($object) && count($LIST) == count($object)) checked @endif>
                    <span></span>
                </label>
            </th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach($LIST as $key =>$item)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td class="id-name">{{$item->service_name}}</td>
                        <td>{{$item->service_code}}</td>
                        <td>
                            {{$item->name}}
                        </td>
                        <td>
                            <label class="m-checkbox m-checkbox--solid" style="margin-bottom: 14px">
                                <input type="checkbox" @if(isset($object) && in_array($item->service_id, $object)) checked @endif autocomplete="off" class="ckb-item" name="service_id[]" value="{{$item->service_id}}">
                                <span></span>
                            </label>
                        </td>
                    </tr>
            @endforeach
        @endif
        </tbody>

    </table>
</div>
