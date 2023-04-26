
var ChangeTab = {
    tabComment : function (view = 'comment'){
        $.ajax({
            url: laroute.route('manager-project.work.detail.change-tab-detail-work'),
            data: {
                id : $('#manage_work_id').val(),
                view : view
            },
            method: "POST",
            dataType: "JSON",
            success: function(res) {
                if (res.error == false) {
                    $('.tab_work_detail').empty();
                    $('.tab_work_detail').append(res.view);
                } else {
                    swal(res.message,'','error');
                }
            },
        });
    }
}