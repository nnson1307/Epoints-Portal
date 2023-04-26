
function checkAllSearchDefault(e){
    if($(e).is(":checked")){
        $('.check-search-default').prop('checked',true);
    }
    else{
        $('.check-search-default').prop('checked',false);
    }
}
function checkSearchDefault(e){
    if($(e).is(":checked")){
        $('.check-all-search-default').prop('checked',true);
    }
    else{
        if(!$('.check-search-default').is(":checked")){
            $('.check-all-search-default').prop('checked',false);
        }
    }
}
function checkAllSearch(e){
    if($(e).is(":checked")){
        $('.check-search').prop('checked',true);
    }
    else{
        $('.check-search').prop('checked',false);
    }
}
function checkSearch(e){
    if($(e).is(":checked")){
        $('.check-all-search').prop('checked',true);
    }
    else{
        if(!$('.check-search').is(":checked")){
            $('.check-all-search').prop('checked',false);
        }
    }
}
function checkAllFilterDefault(e){
    if($(e).is(":checked")){
        $('.check-filter-default').prop('checked',true);
    }
    else{
        $('.check-filter-default').prop('checked',false);
    }
}
function checkFilterDefault(e){
    if($(e).is(":checked")){
        $('.check-all-filter-default').prop('checked',true);
    }
    else{
        if(!$('.check-filter-default').is(":checked")){
            $('.check-all-filter-default').prop('checked',false);
        }
    }
}
function checkAllFilter(e){
    if($(e).is(":checked")){
        $('.check-filter').prop('checked',true);
    }
    else{
        $('.check-filter').prop('checked',false);
    }
}
function checkFilter(e){
    if($(e).is(":checked")){
        $('.check-all-filter').prop('checked',true);
    }
    else{
        if(!$('.check-filter').is(":checked")){
            $('.check-all-column').prop('checked',false);
        }
    }
}
function checkAllColumnDefault(e){
    if($(e).is(":checked")){
        $('.check-column-default').prop('checked',true);
    }
    else{
        $('.check-column-default').prop('checked',false);
    }
}
function checkColumnDefault(e){
    if($(e).is(":checked")){
        $('.check-all-column-default').prop('checked',true);
    }
    else{
        if(!$('.check-column-default').is(":checked")){
            $('.check-all-column-default').prop('checked',false);
        }
    }
}
function checkAllColumn(e){
    if($(e).is(":checked")){
        $('.check-column').prop('checked',true);
    }
    else{
        $('.check-column').prop('checked',false);
    }
}
function checkColumn(e){
    if($(e).is(":checked")){
        $('.check-all-column').prop('checked',true);
    }
    else{
        if(!$('.check-column').is(":checked")){
            $('.check-all-column').prop('checked',false);
        }
    }
}

function addSearch() {
    var html = '';
    $('.check-all-search-default').prop('checked', false);
    $('.check-search-default:checked').closest('div:not([hidden])>label>input').each(function(){
        html += '<div class="form-group">';
        html += $(this).parent('label').parent('div').html();
        html += '</div>';
        $(this).parent('label').parent('div').remove();
        html = html.replace('check-search-default','check-search');
        html = html.replace('checkSearchDefault','checkSearch');
        $('#append_search').append(html);
        html = '';
    });
}
function removeSearch() {
    var html = '';
    $('.check-all-search').prop('checked', false);
    $('.check-search:checked').each(function(){
        html += '<div class="form-group">';
        html += $(this).parent('label').parent('div').html();
        html += '</div>';
        $(this).parent('label').parent('div').remove();
        html = html.replace('check-search','check-search-default');
        html = html.replace('checkSearch','checkSearchDefault');
        $('#append_search_default').append(html);
        html = '';
    });
}
function addFilter() {
    var html = '';
    $('.check-all-filter-default').prop('checked', false);
    $('.check-filter-default:checked').closest('div:not([hidden])>label>input').each(function(){
        html += '<div class="form-group">';
        html += $(this).parent('label').parent('div').html();
        html += '</div>';
        $(this).parent('label').parent('div').remove();
        html = html.replace('check-filter-default','check-filter');
        html = html.replace('checkFilterDefault','checkFilter');
        $('#append_filter').append(html);
        html = '';
    });
}
function removeFilter() {
    var html = '';
    $('.check-all-filter').prop('checked', false);
    $('.check-filter:checked').each(function(){
        html += '<div class="form-group">';
        html += $(this).parent('label').parent('div').html();
        html += '</div>';
        $(this).parent('label').parent('div').remove();
        html = html.replace('check-filter','check-filter-default');
        html = html.replace('checkFilter','checkFilterDefault');
        $('#append_filter_default').append(html);
        html = '';
    });
}
function addColumn() {
    var html = '';
    $('.check-all-column-default').prop('checked', false);
    $('.check-column-default:checked').closest('div:not([hidden])>label>input').each(function(){
        html += '<div class="form-group">';
        html += $(this).parent('label').parent('div').html();
        html += '</div>';
        $(this).parent('label').parent('div').remove();
        html = html.replace('check-column-default','check-column');
        html = html.replace('checkColumnDefault','checkColumn');
        $('#append_column').append(html);
        html = '';
    });
}
function removeColumn() {
    var html = '';
    $('.check-all-column').prop('checked', false);
    $('.check-column:checked').each(function(){
        html += '<div class="form-group">';
        html += $(this).parent('label').parent('div').html();
        html += '</div>';
        $(this).parent('label').parent('div').remove();
        html = html.replace('check-column','check-column-default');
        html = html.replace('checkColumn','checkColumnDefault');
        $('#append_column_default').append(html);
        html = '';
    });
}
function saveConfig() {
    $.getJSON(laroute.route('translate'), function (json) {
        var arrSearch = [];
        var arrFilter = [];
        var arrColumn = [];
        var text = '';
        $('.check-search').each(function(){
            text = $(this).parent('label').parent('div').find('.label_search').text();
            arrSearch.push({key: $(this).attr('name'), value : text});
            text = '';
        });
        $('.check-filter').each(function(){
            text = $(this).parent('label').parent('div').find('.label_filter').text();
            arrFilter.push({key: $(this).attr('name'), value : text});
            text = '';
        });
        $('.check-column').each(function(){
            text = $(this).parent('label').parent('div').find('.label_column').text();
            arrColumn.push({key: $(this).attr('name'), value : text});
            text = '';
        });
        $.ajax({
            url: laroute.route('contract.contract.save-config-cookie'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                arrSearch: arrSearch,
                arrFilter: arrFilter,
                arrColumn: arrColumn
            },
            success: function (res) {
                if (res.error == false) {
                    swal.fire(res.message, "", "success");
                    window.location.reload();
                } else {
                    swal.fire(res.message, '', "error");
                }
            }
        });
    });
}
function inArray(needle, haystack) {
    var length = haystack.length;
    for(var i = 0; i < length; i++) {
        if(haystack[i] == needle) return true;
    }
    return false;
}