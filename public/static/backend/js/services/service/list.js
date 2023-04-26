var service = {
    remove: function (obj, id) {
        // hightlight row
        $(obj).closest('tr').addClass('m-table__row--danger');

        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            onClose: function () {
                // remove hightlight row
                $(obj).closest('tr').removeClass('m-table__row--danger');
            }
        }).then(function (result) {
            if (result.value) {
                $.post(laroute.route('services.remove', {id: id}), function () {
                    swal(
                        'Deleted!',
                        'Your selected services has been deleted.',
                        'success'
                    );

                    // window.location.reload();

                    $('#autotable').PioTable('refresh');
                });
            }
        });
    },
    changeStatus: function (obj, id, action) {
        $.ajax({
            url: laroute.route('services.change-status'),
            method: "POST",
            data: {
                id: id, action: action
            },
            dataType: "JSON"
        }).done(function (data) {
            $('#autotable').PioTable('refresh');
        });
    }

}
$('#autotable').PioTable({
    baseUrl: laroute.route('services.list')
});

$(document).ready(function () {
    $("#formExport").validate({
        submitHandler: function (form) {
            var boxes = $('.roles:checkbox');
            if (boxes.length > 0) {
                if ($('.roles:checkbox:checked').length < 1) {
                    alert('Bạn vui lòng chọn ít nhất 1 trường dữ liệu');
                    return false;
                }
            }
            form.submit();
        }
    });
    $('#checkAll').on('click', function () {
        //$('input[type=checkbox]').prop('checked',true);
        $('.roles').prop('checked', ($(this).val() == 'Check all'));
        $(this).val( ($(this).val() == 'Check all' ? 'Uncheck' : 'Check all') );
       // $("input[type=checkbox]").attr('checked', !$(this).is(":checked"));
    });

});
