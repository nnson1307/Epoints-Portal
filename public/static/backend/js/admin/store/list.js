var store = {

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
                $.post(laroute.route('admin.store.remove', {id: id}), function () {
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
        $.ajax({
            url:laroute.route('admin.store.change-status'),
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
    baseUrl: laroute.route('admin.store.list')
});

var $elem = $( "#elem" ).data( "arr", [ 1 ] ),
    $clone = $elem.clone( true )
    // Deep copy to prevent data sharing
        .data( "arr", $.extend( [], $elem.data( "arr" ) ) );


$("form #Store").dropzone({ url: null, paramName: "file" });

$("#form1").validate({
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
