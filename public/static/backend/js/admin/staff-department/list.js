var StaffDepartment = {
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
                $.post(laroute.route('admin.staff-department.remove', {id: id}), function () {
                    swal(
                        'Deleted!',
                        'Your selected staff department has been deleted.',
                        'success'
                    );

                    // window.location.reload();

                    $('#autotable').PioTable('refresh');
                });
            }
        });
    },
    changeStatus:function (obj,id,action) {
        $.ajax({

            url:laroute.route('admin.staff-department.change-status'),
            method: "POST",
            data:{
                id:id,action: action
            },
            dataType:"JSON"
        }).done(function (data) {
            $('#autotable').PioTable('refresh');
        });
    }
}

$('#autotable').PioTable({
    baseUrl:laroute.route('admin.staff-department.list')
});
