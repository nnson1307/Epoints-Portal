var serviceType = {

    remove:function (obj , id) {
        $(obj).closest('tr').addClass('m-table__row--danger');

        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            onClose: function()
            {
                $(obj).closest('tr').removeClass('m-table__row--danger');
            }
        }).then(function(result) {
            if (result.value)
            {
                $.post(laroute.route('service-type.remove', {id:id}), function() {
                    swal(
                        'Deleted!',
                        'Your selected Item has been deleted.',
                        'success'
                    );
                    $('#autotable').PioTable('refresh');
                });
            }
        });
    },
    changeStatus:function (obj , id , action) {
        $.post(laroute.route('service-type.change-status'), {id: id, action: action}, function (data) {
            $('#autotable').PioTable('refresh');
        }, 'JSON');
    }
};

$('#autotable').PioTable({
    baseUrl: laroute.route('service-type.list')
});