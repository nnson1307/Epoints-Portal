$('#autotable').PioTable({
    baseUrl: laroute.route('admin.inventory-input.list')
});
var InventoryInput = {
    remove: function (obj, id) {

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
                $(obj).closest('tr').removeClass('m-table__row--danger');
            }
        }).then(function (result) {
            if (result.value) {
                $.post(laroute.route('admin.inventory-input.remove', {id: id}), function () {
                    swal(
                        json['Xóa thành công.'],
                        '',
                        'success'
                    );
                    $('#autotable').PioTable('refresh');
                });
            }
        });
    });
    },
    refresh: function () {
        $('input[name="search_keyword"]').val('');
        $('.m_selectpicker').val('');
        $('.m_selectpicker').selectpicker('refresh');
        $('#created_at').val('');
        $(".btn-search").trigger("click");
    },
    search: function () {
        $(".btn-search").trigger("click");
    },
    removeAllInput:function (thi) {
        $(thi).val('');
    },
    pageClick:function (page) {
        $.ajax({
            url:laroute.route('admin.inventory-input.paging-detail'),
            method:"POST",
            data:{
                page:page,
                id:$('#id').val(),
            },
            success:function (data) {
                $('.table-content').empty();
                $('.table-content').append(data);
            }
        });
    },

    showPopup : function () {
        $.ajax({
            url:laroute.route('admin.inventory-input.add'),
            method:"POST",
            data:{},
            success:function (data) {
                if(data.error == false){
                    $('#showPopup').empty();
                    $('#showPopup').append(data.view);
                    $('select').select2();
                    $('#popup-add-inventory').modal('show');
                }
            }
        });
    },

    fileName: function () {
        var fileNamess = $('input[type=file]').val();
        $('#show').val(fileNamess);
    },

    addInventory: function(){
        // mApp.block(".modal-body", {
        //     overlayColor: "#000000",
        //     type: "loader",
        //     state: "success",
        //     message: "Xin vui lòng chờ..."
        // });

        var file_data = $('#file_excel').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('warehouse_id', $('#warehouse').val());
        form_data.append('supplier_id', $('#supplier').val());
        form_data.append('pi_code', $('#code-inventory').val());
        form_data.append('status', $('#status').val());
        form_data.append('normal', $('#normal').val());
        $.ajax({
            url: laroute.route("admin.inventory-input.submit-add"),
            method: "POST",
            data: form_data,
            contentType: false,
            cache: false,
            processData: false,
            success: function (res) {

                // mApp.unblock(".modal-body");
                if (res.error == false) {
                    if (res.countError != 0){
                        $('#form-data-error').empty();
                        var n = 0;
                        $.map(res.dataError, function (val) {
                            var tpl = $('#tpl-data-error').html();
                            tpl = tpl.replace(/{keyNumber}/g, n);
                            tpl = tpl.replace(/{product_code}/g, val.product_code);
                            tpl = tpl.replace(/{quantity}/g, val.quantity);
                            tpl = tpl.replace(/{price}/g, val.price);
                            tpl = tpl.replace(/{barcode}/g, val.barcode);
                            tpl = tpl.replace(/{serial}/g, val.serial);
                            tpl = tpl.replace(/{error_message}/g, val.error_message);
                            n = n + 1;
                            $('#form-data-error').append(tpl);
                        });
                        $("#form-data-error").submit();
                    }

                    swal(res.message, "", "success").then(function(){
                        setTimeout(function(){
                            window.location.href = laroute.route('admin.inventory-input.edit', {id: res.id});
                        }, 3000);

                    });
                } else {
                    swal(res.message, '', "error");
                }
            },
            error: function(res){
                var mess_error = '';
                $.map(res.responseJSON.errors, function (a) {
                    mess_error = mess_error.concat(a + '<br/>');
                });
                swal(mess_error,'', "error");
            }
        });
    },

    showPopupListSerial:function(inventory_input_detail_id){
        $.ajax({
            url:laroute.route('admin.inventory-input.show-popup-list-serial'),
            method:"POST",
            data:{
                inventory_input_detail_id : inventory_input_detail_id
            },
            success:function (data) {
                if(data.error == false){
                    $('#showPopup').empty();
                    $('#showPopup').append(data.view);
                    $('select').select2();
                    $('#popup-list-serial').modal('show');
                    InventoryInput.getListSerial();
                }
            }
        });
    },

    getListSerial : function(){
        $.ajax({
            url:laroute.route('admin.inventory-input.get-list-serial'),
            method:"POST",
            data: $('#form-list-serial').serialize()+'&type=detail',
            success:function (data) {
                if(data.error == false){
                    $('.block-list-serial').empty();
                    $('.block-list-serial').append(data.view);
                }
            }
        });
    },

    changePageSerial : function(page){
        $('#page_serial').val(page);
        InventoryInput.getListSerial();
    },

    removeSearchSerial : function(){
        $('#serial').val('');
        InventoryInput.changePageSerial(1);
    }

};
// $("#created_at").daterangepicker({
//     autoUpdateInput: false,
//     autoApply: true,
//     locale: {
//         format: 'DD/MM/YYYY',
//         daysOfWeek: [
//             "CN",
//             "T2",
//             "T3",
//             "T4",
//             "T5",
//             "T6",
//             "T7"
//         ],
//         "monthNames": [
//             "Tháng 1 năm",
//             "Tháng 2 năm",
//             "Tháng 3 năm",
//             "Tháng 4 năm",
//             "Tháng 5 năm",
//             "Tháng 6 năm",
//             "Tháng 7 năm",
//             "Tháng 8 năm",
//             "Tháng 9 năm",
//             "Tháng 10 năm",
//             "Tháng 11 năm",
//             "Tháng 12 năm"
//         ],
//         "firstDay": 1
//     }
// });
$.getJSON(laroute.route('translate'), function (json) {

    //
    var arrRange = {};
    arrRange[json["Hôm nay"]] = [moment(), moment()];
    arrRange[json["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
    arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
    arrRange[json["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
    arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
    arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
$("#created_at").daterangepicker({
    autoUpdateInput: false,
    autoApply: true,
    buttonClasses: "m-btn btn",
    applyClass: "btn-primary",
    cancelClass: "btn-danger",

    maxDate: moment().endOf("day"),
    startDate: moment().startOf("day"),
    endDate: moment().add(1, 'days'),
    locale: {
        format: 'DD/MM/YYYY',
        "applyLabel": json["Đồng ý"],
        "cancelLabel": json["Thoát"],
        "customRangeLabel": json["Tùy chọn ngày"],
        daysOfWeek: [
            json["CN"],
            json["T2"],
            json["T3"],
            json["T4"],
            json["T5"],
            json["T6"],
            json["T7"]
        ],
        "monthNames": [
            json["Tháng 1 năm"],
            json["Tháng 2 năm"],
            json["Tháng 3 năm"],
            json["Tháng 4 năm"],
            json["Tháng 5 năm"],
            json["Tháng 6 năm"],
            json["Tháng 7 năm"],
            json["Tháng 8 năm"],
            json["Tháng 9 năm"],
            json["Tháng 10 năm"],
            json["Tháng 11 năm"],
            json["Tháng 12 năm"]
        ],
        "firstDay": 1
    },
    ranges: arrRange
}).on('apply.daterangepicker', function (ev) {

});
});
$('#autotableInventoryOutput').PioTable({
    baseUrl: laroute.route('admin.inventory-input.list')
});

$('select[name="inventory_inputs$type"]').select2();
$('select[name="inventory_inputs$status"]').select2();
$('select[name="inventory_inputs$created_by"]').select2();
$('select[name="inventory_inputs$warehouse_id"]').select2();


