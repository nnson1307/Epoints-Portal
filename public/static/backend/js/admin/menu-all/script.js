$(document).ready(function(){
    // $("#anythingSearch").on("keyup", function() {
    //     var value = $(this).val().toLowerCase();
    //     $("#myList div").filter(function() {
    //         $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    //     });
    // });

    var timer = null;
    $('#anythingSearch').keydown(function(){
        clearTimeout(timer);
        timer = setTimeout(doSearch, 500);
    });
});

var menuAll = {
    refresh: function () {
        $("#anythingSearch").val('');
        // menuAll.search();
        doSearch();
    }
};

function doSearch() {
    $.ajax({
        url: laroute.route('admin.menu-all.search'),
        method: 'POST',
        dataType: 'JSON',
        data: {
            // search: $(obj).val()
            search: $('#anythingSearch').val()
        },
        success:function (res) {
            $('#list-menu-all').empty();
            $('#list-menu-all').append(res.html);
        }
    });
}