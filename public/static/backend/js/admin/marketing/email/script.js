var email={
    filter: function () {
        $.ajax({
            url:laroute.route('admin.email.filter'),
            method:'POST',
            data:{
                keyWord:$('input[name="search"]').val(),
                createdBy:$('select[name="created_by"]').val(),
                sentBy:$('select[name="sent_by"]').val(),
                status:$('select[name="status"]').val(),
                daySent:$('#day-sent').val(),
                createdAt:$('#created_at').val(),
            },
            success:function (data) {
                $('.list-campaign').empty();
                $('.list-campaign').append(data);
            }
        });
    },
    refresh: function () {
        $('input[name="search"]').val('');
        $('#day-sent').val('');
        $('#created_at').val('');
        $('select[name="created_by"]').val('').trigger('change');
        $('select[name="sent_by"]').val('').trigger('change');
        $('select[name="status"]').val('').trigger('change');
        email.filter();
    },
    cancel: function (obj, id) {
        $.getJSON(laroute.route('translate'), function (json) {
        // hightlight row
            $(obj).closest('tr').addClass('m-table__row--danger');

            swal({
                title: json['Thông báo'],
                text: json["Bạn có muốn hủy chiến dịch không?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json['Xóa'],
                cancelButtonText: json['Hủy'],
                onClose: function () {
                    // remove hightlight row
                    $(obj).closest('tr').removeClass('m-table__row--danger');
                }
            }).then(function (result) {
                if (result.value) {
                    $.post(laroute.route('admin.email.cancel', {id: id}), function () {
                        swal(
                            json['Hủy thành công'],
                            '',
                            'success'
                        );

                        email.filter();
                    });
                }
            });
        });
    },
}
$(document).ready(function () {
    $('select[name=created_by]').select2().on("select2:select", function (e) {
        email.filter();
    });
    $('select[name=sent_by]').select2().on("select2:select", function (e) {
        email.filter();
    });
    $('select[name=status]').select2().on("select2:select", function (e) {
        email.filter();
    });
    $('#day-sent').datepicker({
        format: "dd/mm/yyyy",
        language: 'vi',
    });
    $('input[name=search]').keyup(function (e) {
        if (e.keyCode == 13) {
            $(this).trigger("enterKey");
        }
    });
    $('input[name=search]').bind("enterKey", function (e) {
        email.filter();
    });
    $("#created_at").datepicker({format: 'dd/mm/yyyy'});
    $('#created_at').change(function () {

    });
});

function pageClick(page) {
    $.ajax({
        url: laroute.route('admin.email.paging'),
        method: "POST",
        data: {
            page: page
        },
        success: function (data) {
            console.log(data);
            $('.list-campaign').empty();
            $('.list-campaign').append(data);
        }
    })
}
function pageClickFilter(page) {
    $.ajax({
        url: laroute.route('admin.email.paging-filter'),
        method: "POST",
        data: {
            keyWord:$('input[name="search"]').val(),
            createdBy:$('select[name="created_by"]').val(),
            sentBy:$('select[name="sent_by"]').val(),
            status:$('select[name="status"]').val(),
            daySent:$('#day-sent').val(),
            createdAt:$('#created_at').val(),
            page:page
        },
        success: function (data) {
            $('.list-campaign').empty();
            $('.list-campaign').append(data);
        }
    })
}