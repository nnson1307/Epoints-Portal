$('#autotable').PioTable({
    baseUrl: laroute.route('fnb.table.list')
});

var table = {
    jsonLang: JSON.parse(localStorage.getItem("tranlate")),
    _init : function (){
        $('select').select2();

        var arrRange = {};
        arrRange[table.jsonLang["Hôm nay"]] = [moment(), moment()];
        arrRange[table.jsonLang["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
        arrRange[table.jsonLang["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
        arrRange[table.jsonLang["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
        arrRange[table.jsonLang["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
        arrRange[table.jsonLang["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
        $(".daterange_picker").daterangepicker({
            // autoUpdateInput: false,
            autoApply: true,
            // maxDate: moment().endOf("day"),
            // startDate:moment().subtract(6, "days"),
            // endDate: moment(),
            locale: {
                cancelLabel: 'Clear',
                format: 'DD/MM/YYYY',
                "applyLabel": table.jsonLang["Đồng ý"],
                "cancelLabel": table.jsonLang["Thoát"],
                "customRangeLabel": table.jsonLang['Tùy chọn ngày'],
                daysOfWeek: [
                    table.jsonLang["CN"],
                    table.jsonLang["T2"],
                    table.jsonLang["T3"],
                    table.jsonLang["T4"],
                    table.jsonLang["T5"],
                    table.jsonLang["T6"],
                    table.jsonLang["T7"]
                ],
                "monthNames": [
                    table.jsonLang["Tháng 1 năm"],
                    table.jsonLang["Tháng 2 năm"],
                    table.jsonLang["Tháng 3 năm"],
                    table.jsonLang["Tháng 4 năm"],
                    table.jsonLang["Tháng 5 năm"],
                    table.jsonLang["Tháng 6 năm"],
                    table.jsonLang["Tháng 7 năm"],
                    table.jsonLang["Tháng 8 năm"],
                    table.jsonLang["Tháng 9 năm"],
                    table.jsonLang["Tháng 10 năm"],
                    table.jsonLang["Tháng 11 năm"],
                    table.jsonLang["Tháng 12 năm"]
                ],
                "firstDay": 1
            },
            ranges: arrRange
        }).on('apply.daterangepicker', function (ev) {

        });

        $(".daterange_picker").val('');

    },

}

var configColumn = {
    showPopupConfigTable : function (){
        $.ajax({
            url: laroute.route("fnb.table.show-popup-config"),
            method: "POST",
            data: {},
            success: function (res) {
                if (res.error == false){
                    $('.append-popup').empty();
                    $('.append-popup').append(res.view);
                    $('#modal-config').modal('show');
                }
            },
        });
    },
    saveConfig : function (){
        $.ajax({
            url: laroute.route("fnb.table.save-config"),
            method: "POST",
            data: $('#form-config').serialize(),
            success: function (res) {
                if (res.error == false){
                    swal(res.message, '', "success").then(function (){
                        location.reload();
                    });
                } else {
                    swal(res.message, '', "error");
                }
            },
        });
    }

}
var Table = {
    showPopupTable : function (item = null) {
        $.ajax({
            url: laroute.route("fnb.table.show-popup"),
            method: "POST",
            data: {
                item : item
            },
            success: function (res) {
                if (res.error != false) {
                    swal("Lỗi", res.message, "error")
                } else if(res.job == 'edit'){
                    $('.append-popup').empty();
                    $('.append-popup').append(res.view);
                    $('#edit-table').modal('show');
                }else {
                    $('.append-popup').empty();
                    $('.append-popup').append(res.view);
                    $('#create-table').modal('show');
                }
            }
        });
    },
    saveNewTable: function () {
        Swal.fire({
            title: 'Thông báo',
            text: 'Bạn chắc chắn muốn thêm bàn này?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Tiếp tục',
            cancelButtonText: 'Hủy'
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: laroute.route("fnb.table.create"),
                    method: "POST",
                    data: $('#created-table').serialize(),
                    success: function (res) {
                        if (res.error != false) {
                            swal("Lỗi", res.message, "error")
                        } else {
                            swal("Thêm thành công", "Nhấn OK để tiếp tục", "success").then(function () {
                                window.location.href = laroute.route("fnb.table");
                            });
                        }
                    },
                    error: function (response) {
                        var mess_error = '';
                        $.map(response.responseJSON.errors, function (a) {
                            mess_error = mess_error.concat(a + '<br/>');
                        });
                        swal(mess_error, '', "error");
                    }
                })
            }
        })
    },
    cancel : function () {
        Swal.fire({
            title: 'Thông báo',
            text: 'Bạn chắc chắn muốn hủy thao tác?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Tiếp tục',
            cancelButtonText: 'Ở lại'
        }).then (function (result) {
            if (result.value) {
                window.location.href = laroute.route("fnb.table");
            }
        })
    },
    saveEditTable: function () {
        Swal.fire({
            title: 'Thông báo',
            text: 'Bạn chắc chắn muốn chỉnh sửa thông tin bàn này?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Tiếp tục',
            cancelButtonText: 'Hủy'
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: laroute.route("fnb.table.edit"),
                    method: "POST",
                    data: $('#edited-table').serialize(),
                    success: function (res) {
                        if (res.error != false) {
                            swal("Lỗi", res.message, "error")
                        } else {
                            swal("Chỉnh sửa thành công", "Nhấn OK để tiếp tục", "success").then(function () {
                                window.location.href = laroute.route("fnb.table");
                            });
                        }
                    },
                    error: function (response) {
                        var mess_error = '';
                        $.map(response.responseJSON.errors, function (a) {
                            mess_error = mess_error.concat(a + '<br/>');
                        });
                        swal(mess_error, '', "error");
                    }
                })
            }
        })
    },
    deleteTable: function (id, code) {
        Swal.fire({
            title: 'Thông báo',
            text: 'Bạn chắc chắn muốn xóa bàn ' + code + '?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Tiếp tục',
            cancelButtonText: 'Hủy'
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: laroute.route("fnb.table.delete"),
                    method: "POST",
                    data: {
                        id: id,
                    },
                    success: function (res) {
                        if (res.error == true) {
                            swal("Lỗi", res.message , "error")
                        } else {
                            swal(res.message , "Nhấn OK để tiếp tục", "success").then(function () {
                                window.location.href = laroute.route("fnb.table");
                            });
                        }
                    }
                })
            }
        })
    }
}