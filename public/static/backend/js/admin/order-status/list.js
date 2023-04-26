var orderStatus = {

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
                $.post(laroute.route('admin.order-status.remove', {id: id}), function () {
                    swal(
                        'Deleted!',
                        'Your selected item has been deleted.',
                        'success'
                    );
                    // window.location.reload();
                    $('#autotable').PioTable('refresh');
                });
            }
        });

    },
    changeStatus: function (obj,id,action)
    {
        // alert(id);
        $.ajax({
            url:laroute.route('admin.order-status.change-status'),
            method: "POST",
            data:{
                id:id,action: action
            },
            dataType:"JSON"
        }).done(function (data) {
            $('#autotable').PioTable('refresh');
        });
    }


};
$('#autotable').PioTable({
    baseUrl: laroute.route('admin.order-status.list')
});

$("#formexport").validate({
    submitHandler: function(form) {
        // see if selectone is even being used
        var boxes = $('.messageCheckbox:checkbox');
        if(boxes.length > 0) {
            if( $('.messageCheckbox:checkbox:checked').length < 1) {
                alert('Bạn chưa chọn dữ liệu xuất ra');
                boxes[0].focus();
                return false;
            }
        }
        form.submit();
    }
});

$('#checkAll').click(function () {
    $(':checkbox.messageCheckbox').prop('checked', this.checked);
})