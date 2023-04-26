<form class="frmFilter">
    <input type="text" hidden class="form-control" name="customer_id" value="{{$customer_id}}">
    <div class="col-lg-2 form-group" hidden>
        <button class="btn btn-primary color_button btn-search-deals">
            @lang('TÌM KIẾM') <i class="fa fa-search ic-search m--margin-left-5"></i>
        </button>
    </div>
    @if(in_array('customer-lead.create',session('routeList')))
    <button type="button"
            onclick="callCenter.showModalCreateLead('{{$customer_id}}', 'customer')"
            class="btn btn-info  color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
        <span>
            <i class="fa fa-plus-circle m--margin-left-5"></i>
            <span>{{__('THÊM CƠ HỘI BÁN HÀNG')}}</span>

        </span>
    </button>
@endif
</form>

<div class="table-content m--padding-top-30">

</div>