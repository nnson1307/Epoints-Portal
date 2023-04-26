var staffAccount = {
		
	remove: function(obj, id)
	{
		// hightlight row
		$(obj).closest('tr').addClass('m-table__row--danger');
		
		swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            onClose: function()
            {
            	// remove hightlight row
            	$(obj).closest('tr').removeClass('m-table__row--danger');
            }
        }).then(function(result) {
            if (result.value)
            {
            	$.post(laroute.route('staff-account.remove', {id:id}), function() {
            		swal(
                        'Deleted!',
                        'Your selected user has been deleted.',
                        'success'
                    );
            		// window.location.reload();
            		$('#autotable').PioTable('refresh');
            	});
            }
        });
	},
    changeStatus:function (obj , id , action) {

        $.post(laroute.route('staff-account.change-status'), {id: id, action: action}, function (data) {
            $('#autotable').PioTable('refresh');
        }, 'JSON');
    }


}
$('#autotable').PioTable({
    baseUrl: laroute.route('staff-account.list')
});



