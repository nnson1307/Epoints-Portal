$('#autotable').PioTable({
    baseUrl: laroute.route('fnb.areas.list')
});

var area = {
    jsonLang: JSON.parse(localStorage.getItem("tranlate")),
    _init : function (){
        $('select').select2();

        var arrRange = {};
        arrRange[area.jsonLang["Hôm nay"]] = [moment(), moment()];
        arrRange[area.jsonLang["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
        arrRange[area.jsonLang["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
        arrRange[area.jsonLang["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
        arrRange[area.jsonLang["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
        arrRange[area.jsonLang["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
        $(".daterange_picker").daterangepicker({
            // autoUpdateInput: false,
            autoApply: true,
            // maxDate: moment().endOf("day"),
            // startDate:moment().subtract(6, "days"),
            // endDate: moment(),
            locale: {
                cancelLabel: 'Clear',
                format: 'DD/MM/YYYY',
                "applyLabel": area.jsonLang["Đồng ý"],
                "cancelLabel": area.jsonLang["Thoát"],
                "customRangeLabel": area.jsonLang['Tùy chọn ngày'],
                daysOfWeek: [
                    area.jsonLang["CN"],
                    area.jsonLang["T2"],
                    area.jsonLang["T3"],
                    area.jsonLang["T4"],
                    area.jsonLang["T5"],
                    area.jsonLang["T6"],
                    area.jsonLang["T7"]
                ],
                "monthNames": [
                    area.jsonLang["Tháng 1 năm"],
                    area.jsonLang["Tháng 2 năm"],
                    area.jsonLang["Tháng 3 năm"],
                    area.jsonLang["Tháng 4 năm"],
                    area.jsonLang["Tháng 5 năm"],
                    area.jsonLang["Tháng 6 năm"],
                    area.jsonLang["Tháng 7 năm"],
                    area.jsonLang["Tháng 8 năm"],
                    area.jsonLang["Tháng 9 năm"],
                    area.jsonLang["Tháng 10 năm"],
                    area.jsonLang["Tháng 11 năm"],
                    area.jsonLang["Tháng 12 năm"]
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
    showPopupConfig : function (){
        $.ajax({
            url: laroute.route("fnb.areas.show-popup-config"),
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
            url: laroute.route("fnb.areas.save-config"),
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
var areas = {
    showPopup : function (item = null) {
        $.ajax({
            url: laroute.route("fnb.areas.show-popup"),
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
                    $('#edit-areas').modal('show');
                }else {
                    $('.append-popup').empty();
                    $('.append-popup').append(res.view);
                    $('#create-areas').modal('show');
                }
            }
        });
    },
    saveNewAreas: function () {
        Swal.fire({
            title: 'Thông báo',
            text: 'Bạn chắc chắn muốn thêm khu vực này?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Tiếp tục',
            cancelButtonText: 'Hủy'
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: laroute.route("fnb.areas.create"),
                    method: "POST",
                    data: $('#created-areas').serialize(),
                    success: function (res) {
                        if (res.error != false) {
                            swal("Lỗi", res.message, "error")
                        } else {
                            swal("Thêm thành công", "Nhấn OK để tiếp tục", "success").then(function () {
                                window.location.href = laroute.route("fnb.areas");
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
                window.location.href = laroute.route("fnb.areas");
            }
        })
    },
    saveEditAreas: function () {
        Swal.fire({
            title: 'Thông báo',
            text: 'Bạn chắc chắn muốn chỉnh sửa thông tin khu vực này?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Tiếp tục',
            cancelButtonText: 'Hủy'
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: laroute.route("fnb.areas.edit"),
                    method: "POST",
                    data: $('#edited-areas').serialize(),
                    success: function (res) {
                        if (res.error != false) {
                            swal("Lỗi", res.message, "error")
                        } else {
                            swal("Chỉnh sửa thành công", "Nhấn OK để tiếp tục", "success").then(function () {
                                window.location.href = laroute.route("fnb.areas");
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
    deleteAreas: function (id, code) {
        Swal.fire({
            title: 'Thông báo',
            text: 'Bạn chắc chắn muốn xóa khu vực ' + code + '?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Tiếp tục',
            cancelButtonText: 'Hủy'
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: laroute.route("fnb.areas.delete"),
                    method: "POST",
                    data: {
                        id: id,
                    },
                    success: function (res) {
                        if (res.error == true) {
                            swal("Lỗi", res.message , "error")
                        } else {
                            swal(res.message , "Nhấn OK để tiếp tục", "success").then(function () {
                                window.location.href = laroute.route("fnb.areas");
                            });
                        }
                    }
                })
            }
        })
    }
}