var Month = {
    search : function (){
        $.ajax({
            url: laroute.route('report-kpi.search-month'),
            method: 'POST',
            data: $('.frmFilterMonth').serialize(),
            success: function (res) {
                if (res.error == false){
                    $('#insert_table').empty();
                    $('#insert_table').append(res.view);
                } else {
                    swal.fire(res.message,'','error');
                }
            }
        });
    }
}

var Week = {
    search : function (){
        $.ajax({
            url: laroute.route('report-kpi.search-week'),
            method: 'POST',
            data: $('.frmFilterWeek').serialize(),
            success: function (res) {
                if (res.error == false){
                    $('#insert_table').empty();
                    $('#insert_table').append(res.view);
                } else {
                    swal.fire(res.message,'','error');
                }
            }
        });
    }
}

var Day = {
    search : function (){
        $.ajax({
            url: laroute.route('report-kpi.search-day'),
            method: 'POST',
            data: $('.frmFilterDay').serialize(),
            success: function (res) {
                if (res.error == false){
                    $('#insert_table').empty();
                    $('#insert_table').append(res.view);
                } else {
                    swal.fire(res.message,'','error');
                }
            }
        });
    }
}