<form class="m-form m-form--fit m-form--label-align-right frmFilter" onsubmit="return false">
    <div class="m-form m-form--label-align-right m--margin-bottom-30">
        <div class="row align-items-center m--margin-bottom-10">
            <div class="col-xl-12 order-2 order-xl-1">
                <div class="form-group m-form__group row align-items-center kil-padding-right kill-padding-left">
                    <div class="input-group col-sm-6 kill-padding-left kil-padding-right">
                        <input name="service_card_id" value="{{$params["service_card_id"]}}" type="hidden">
                        <input name="branch_id" value="{{$params["branch_id"]}}" type="hidden">
                        <input type="text" class="form-control" name="search_keyword"
                               placeholder="Nhập nội dung tìm kiếm" value="{{isset($params["search_keyword"]) ? $params["search_keyword"] : ""}}">
                        <div class="input-group-append">
                            <button class="btn btn-primary">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>

                    </div>
                    <div class="col-sm-6 input-group" style="padding-left: 10px">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                {{__('Trạng thái')}}
                            </span>
                        </div>
                        <select class="form-control m-input" name="is_actived" autocomplete="off">
                            <option value="" >{{__('Tất cả')}}</option>
                            <option value="1" @if( isset($params["is_actived"]) && $params["is_actived"] == 1 ) selected @endif>{{__('Hoạt động')}}</option>
                            <option value="0" @if( isset($params["is_actived"]) && $params["is_actived"] == 0 ) selected @endif>{{__('Tạm ngưng')}}</option>
                        </select>
                    </div>
                </div>

            </div>
        </div>
        <div class="form-group m-form__group row align-items-center kil-padding-right" style="padding:5px 15px;">

            <div class="col-sm-6 input-group kil-padding-right kill-padding-left">
                <div class="input-group-append">
                    <span class="input-group-text">
                        {{__('Ngày tạo')}}
                    </span>
                </div>
                {!! Form::text("created_at",null,["class"=>"form-control m-input daterange-picker","autocomplete"=>"off","id"=>"created_at"]); !!}
            </div>
            <div class="col-sm-6 input-group" style="padding-left: 10px">
                <div class="input-group-append">
                    <span class="input-group-text ">
                        {{__('Ngày sử dụng')}}
                    </span>
                </div>
                {!! Form::text("actived_date",null,["class"=>"form-control m-input daterange-picker","autocomplete"=>"off","id"=>"actived_date"]); !!}
            </div>
        </div>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-primary">
        <thead>
        <tr>
            <th>#</th>
            <th>{{__('Mã Thẻ dịch vụ')}}</th>
            <th>{{__('Khách hàng')}}</th>
            <th>{{__('Ngày sử dụng')}}</th>
            <th>{{__('Ngày tạo')}}</th>
            <th>{{__('Trạng thái')}}</th>
        </tr>
        </thead>
        <tbody>

        @foreach ($LIST as $key => $item)
            <tr>
                <td>{{$key+1}}</td>
                <td class="c_code">{{$item->code}}</td>
                <td>{{$item->customer_name}}</td>
                <td>{{\Carbon\Carbon::parse($item->actived_date)->format("d-m-Y")}}</td>
                <td>{{\Carbon\Carbon::parse($item->created_at)->format("d-m-Y")}}</td>
                <td>
                    @php($now = \Carbon\Carbon::now())
                    @php($expire = \Carbon\Carbon::parse($item->expired_date))
                    @if($expire->diffInDays($now, false) < 0)
                       <span class="m--font-success">{{__('Đang sử dụng')}}</span>
                    @else
                        <span class="m--font-danger">{{__('Hết hạn')}}</span>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<script>
    $("#actived_date").daterangepicker({
        autoUpdateInput: false,
        autoApply:true,
        locale: {
            format: 'DD/MM/YYYY'
        }
    });

    $("#created_at").daterangepicker({
        autoUpdateInput: false,
        autoApply:true,
        locale: {
            format: 'DD/MM/YYYY'
        }
    });

    var table = $('#autotable').PioTable({
        baseUrl: laroute.route('admin.service-card-list.detail-list-inuse')
    });
</script>