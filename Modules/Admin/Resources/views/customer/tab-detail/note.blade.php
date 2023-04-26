<div style="text-align:right;">
    <a href="javascript:void(0)" onclick="detail.popCreateNote('{{$customer_id}}')" class="btn btn-primary btn-sm color_button m-btn m-btn--icon m-btn--pill">
        <span>
            <span>@lang('THÊM GHI CHÚ')</span>
        </span>
    </a>
</div>

<form class="frmFilter bg">
    <input type="text" hidden class="form-control" name="customer_notes$customer_id" value="{{$customer_id}}">
    <div class="col-lg-2 form-group" hidden>
        <button class="btn btn-primary color_button btn-search-note">
            @lang('TÌM KIẾM') <i class="fa fa-search ic-search m--margin-left-5"></i>
        </button>
    </div>
</form>
<div class="table-content m--padding-top-30">

</div>