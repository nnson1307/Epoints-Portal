$('.--select2').select2();
var listUserGroup = {
    inputFiler: function () {
        $('.input-filter').show();
    },
    init: function () {
    },
    sort: function (o, col) {
        var sort = $(o).data('sort');
        switch (col) {
            case 'name':
                $("#sort_name").val(sort);
                $("#sort_filter_group_type").val(null);
                $("#sort_created_at").val(null);
                break;
            case 'type':
                $("#sort_name").val(null);
                $("#sort_filter_group_type").val(sort);
                $("#sort_created_at").val(null);
                break;
            case 'created_at':
                $("#sort_name").val(null);
                $("#sort_filter_group_type").val(null);
                $("#sort_created_at").val(sort);
                break;
        }
        listUserGroup.filter();
    },
    filter: function () {
        $('#form-filter').submit();
    },
    search: function () {
        $(".btn-search").trigger("click");
    },
    deleteGroup: function(obj, type , id){
        $(obj).closest('tr').addClass('m-table__row--danger');
        $.getJSON(laroute.route('translate'), function (json) {
            swal({
                title: json['Thông báo'],
                text: json["Bạn có muốn xóa không?"],
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
                    if(type == 'define'){
                        $.ajax({
                            url: laroute.route('admin.customer-group-filter.delete-group-define'),
                            method: "GET",
                            data: {
                                id: id
                            },
                            dataType: "JSON",
                            success: function (res) {
                                swal(json["Xoá nhóm khách hàng thành công"], "", "success");
                                window.location.reload();
                            }
                        });
                    }
                    else{
                        $.ajax({
                            url: laroute.route('admin.customer-group-filter.delete-group-auto'),
                            method: "GET",
                            data: {
                                id: id
                            },
                            dataType: "JSON",
                            success: function (res) {
                                swal(json["Xoá nhóm khách hàng thành công"], "", "success");
                                window.location.reload();
                            }
                        });
                    }
                }
            })
        });
    }
};

listUserGroup.init();

$('#autotable').PioTable({
    baseUrl: laroute.route('admin.customer-group-filter.list')
});
