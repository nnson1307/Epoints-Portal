<form class="frmFilter">
    <input type="text" hidden class="form-control" name="customer_code" value="{{$info['customer_code']}}">
    <div class="col-lg-2 form-group" hidden>
        <button class="btn btn-primary color_button btn-search-care">
            @lang('TÌM KIẾM') <i class="fa fa-search ic-search m--margin-left-5"></i>
        </button>
        
      
    </div> 

    @if(in_array('admin.customer.customer-care', session('routeList')))
    <button type="button"
    onclick="layout.getModalFromIcon('{{Auth()->id()}}', '', '{{$info['customer_id']}}', 'customer', '{{$info['phone1']}}', '{{session()->get('brand_code')}}')"
            class="btn btn-info  color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
        <span>
            <i class="fa fa-plus-circle m--margin-left-5"></i>
            <span>{{__('CHĂM SÓC KHÁCH HÀNG')}}</span>

        </span>
    </button>
@endif
</form>
<div class="table-content m--padding-top-30">

</div>