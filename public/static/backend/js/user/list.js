var List = {

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
            	$.post(laroute.route('user.remove', {id:id}), function() {
            		swal(
                        'Deleted!',
                        'Your selected user has been deleted.',
                        'success'
                    );
            		
            		// $('#autotable').PioTable('refresh');
            	});
            }
        });
	}
};


$('#autotable').PioTable({
    baseUrl: laroute.route('user.list')
    // baseUrl: laroute.route('user.list')
});