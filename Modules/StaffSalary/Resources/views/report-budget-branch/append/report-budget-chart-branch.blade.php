<form class="bg">
    <div class="padding_row">
        <div class="row">
            <div class="form-group col-lg-3">
                <select class="form-control m_selectpicker" id="date_type" name="date_type" onchange="list.changeDateType(this)">
                    <option value="by_week">@lang('Theo tuần')</option>
                    <option value="by_month" selected>@lang('Theo tháng')</option>
                </select>
            </div>
            <div class="form-group col-lg-3">
                <select class="form-control m_selectpicker" id="date_object" name="date_object">
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{$i}}" {{$i == \Carbon\Carbon::now()->format('m') ? 'selected': ''}}>
                            {{ __('Tháng ' . $i) }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="form-group col-lg-3">
                <select class="select2-multiple2" id="optionBranch" name="optionBranch" multiple>
                    @foreach ($optionBranch as $key => $item)
                    <option selected value="{{$key}}">{{$item}}</option>
                    @endforeach
                    
                  </select>
            </div>
        </div>
    </div>
</form>
<div class="m--padding-top-30">
    <div class="row">
        <div class="col-lg-6">
            <div id="container-time"></div>
        </div>
        <div class="col-lg-6">
            <div id="container-money"></div>
        </div>
    </div>
   
</div>
<script>
    $(document).ready(function() {
        jQuery(function($) {
            $.fn.select2.amd.require([
                'select2/selection/single',
                'select2/selection/placeholder',
                'select2/selection/allowClear',
                'select2/dropdown',
                'select2/dropdown/search',
                'select2/dropdown/attachBody',
                'select2/utils'
            ], function (SingleSelection, Placeholder, AllowClear, Dropdown, DropdownSearch, AttachBody, Utils) {

                var SelectionAdapter = Utils.Decorate(
                    SingleSelection,
                    Placeholder
                );
                
                SelectionAdapter = Utils.Decorate(
                    SelectionAdapter,
                    AllowClear
                );
                    
                var DropdownAdapter = Utils.Decorate(
                    Utils.Decorate(
                        Dropdown,
                        DropdownSearch
                    ),
                    AttachBody
                );
                
                var base_element = $('.select2-multiple2')
                $(base_element).select2({
                    placeholder: 'Select multiple items',
                    selectionAdapter: SelectionAdapter,
                    dropdownAdapter: DropdownAdapter,
                    allowClear: true,
                    templateResult: function (data) {

                        if (!data.id) { return data.text; }

                        var $res = $('<div></div>');

                        $res.text(data.text);
                        $res.addClass('wrap');
                       
                        return $res;
                    },
                    templateSelection: function (data) {
                        list.getReportChart();
                        if (!data.id) { return data.text; }
                        var selected = ($(base_element).val() || []).length;
                        var total = $('option', $(base_element)).length;
                        return list.jsonLang['Đã chọn'] + " " + selected + " " + list.jsonLang['trên'] + " " + total + " " + list.jsonLang['chi nhánh'];
                    }
                })
            
            });
            
        });
    })
</script>