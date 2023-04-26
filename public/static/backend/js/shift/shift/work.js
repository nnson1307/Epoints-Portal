var Work = {
    search : function() {
        $.ajax({
            url: laroute.route('customer-lead.search-work-lead'),
            method: 'POST',
            dataType: 'JSON',
            data: $('#form-search-support').serialize(),
            success: function (res) {
                if(res.error == false){
                    $('.list-table-work').empty();
                    $('.list-table-work').append(res.view);
                }
            }
        })
    },

    searchHistory : function() {
        $.ajax({
            url: laroute.route('customer-lead.search-work-lead'),
            method: 'POST',
            dataType: 'JSON',
            data: $('#form-search-history').serialize(),
            success: function (res) {
                if(res.error == false){
                    $('.list-table-work-history').empty();
                    $('.list-table-work-history').append(res.view);
                }
            }
        })
    },

    searchPage : function(page){
        $('#page_support').val(page);
        Work.search();
    },

    searchPageHistory : function(page){
        $('#page_history').val(page);
        Work.searchHistory();
    },

    removeSearchWork : function(){
        $('#page_support').val(1);
        $('#form-search-support input[type="text"]').val('');
        $('#form-search-support select').val('').trigger('change');
        Work.search();
    },

    removeSearchWorkHistory: function(){
        $('#page_history').val(1);
        $('#form-search-history input[type="text"]').val('');
        $('#form-search-history select').val('').trigger('change');
        Work.searchHistory();
    },

    popupChangeStatus : function(id){

        $.ajax({
            url: laroute.route('manager-work.staff-overview.popup-status'),
            data: {id : id},
            method: "POST",
            dataType: "JSON",
            success: function(res) {
                $('#vund_popup').html(res.html);
                $('#popup-staff-overview-status').modal('show');
                $('#popup-staff-overview-status select').select2();
            }
        });
    },

    removeWork : function (manage_work_id) {
        swal({
            title: 'Xoá công việc',
            text: "Bạn có muốn xóa không?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy',

        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: laroute.route('manager-work.detail.remove-work'),
                    method: "POST",
                    data: {
                        manage_work_id: manage_work_id
                    },
                    success: function (res) {
                        if (res.error == false){
                            swal.fire(res.message, '', 'success').then(function () {
                                Work.search();
                            });
                        } else {
                            swal.fire(res.message, '', 'error');
                        }
                    }
                });
            }
        });
    },

    changeBooking : function(){
        if($('#is_booking').is(':checked')){
            // $('.block-hide-work').show();
            $('.checkBookingAdd').prop('disabled',false);
        } else {
            // $('.block-hide-work').hide();
            $('.checkBookingAdd').prop('disabled',true);
        }
    },

    changeRemind(){
        if($('#is_remind').is(':checked')){
            $('.checkRemindAdd').prop('disabled',false);
        } else {
            $('.checkRemindAdd').prop('disabled',true);
        }
    }

}

var StaffOverview = {
    changeStatus : function (id){
        $.ajax({
            url: laroute.route('manager-work.staff-overview.change-status'),
            data: {id : id, status : $('#form-change-status').find('#manage_status_id').val()},
            method: "POST",
            dataType: "JSON",
            success: function(res) {
                swal('Đổi trạng thái thành công','','success').then(function(){
                    // window.location.reload();
                    $('#popup-staff-overview-status').modal('hide');
                    Work.search();
                });
            }
        });
    },
}