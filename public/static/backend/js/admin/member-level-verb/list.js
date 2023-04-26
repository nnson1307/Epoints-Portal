var memberLevelVerb = {
    remove: function (obj, id) {
        $(obj).closest('tr').addClass('m-table__row--danger');

        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            onClose: function () {
                $(obj).closest('tr').removeClass('m-table__row--danger');
            }
        }).then(function (result) {
            if (result.value) {
                $.post(laroute.route('admin.member-level-verb.remove', {id: id}), function () {
                    swal(
                        'Deleted!',
                        'Your selected member level verb has been deleted.',
                        'success'
                    );
                    $('#autotable').PioTable('refresh');
                });
            }
        });
    },
    changeStatus: function (obj, id, action) {
        $.post(laroute.route('admin.member-level-verb.change-status'), {id: id, action: action}, function (data) {
            $('#autotable').PioTable('refresh');
        }, 'JSON');
    }
}

$('#autotable').PioTable({
    baseUrl: laroute.route('admin.member-level-verb.list')
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