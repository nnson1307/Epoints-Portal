$(document).ready(function () {
    $.getJSON(laroute.route('translate'), function (json) {
        $('.select').select2();
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
            locale: {
                cancelLabel: 'Clear',
                format: 'DD/MM/YYYY',
                // "applyLabel": "Đồng ý",
                // "cancelLabel": "Thoát",
                "customRangeLabel": json['Tùy chọn ngày'],
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
        });

        //onchange object accounting type -> append corresponding input with object type
        $('[name="object_accounting_type_code"]').on('change',function(){
            var data = $('[name="object_accounting_type_code"] option:selected').val();
            if(data == ''){
                $('#object_render').children().remove();
                $('#object_render').append(`<input type="text" class="form-control m-input btn-sm"
                             name="accounting_name" id="accounting_name"  placeholder="Nhập tên người nhận">`);
                return;
            }
            $.ajax({
                url: laroute.route("payment.append-by-object-type"),
                method: "POST",
                data: {
                    code: data
                },
                success: function (result) {
                    $('#object_render').children().remove();
                    var stringAppend = `<select name="accounting_id" id="accounting_id" class="form-control m-input select">`;
                    if(data == 'OAT_CUSTOMER'){
                        result.forEach(e=>{
                            stringAppend+= `<option value="${e['customer_id']}">${e['full_name']}</option>`;
                        })
                        stringAppend+= `</select>`;
                        $('#object_render').append(stringAppend);
                    }
                    else if(data == 'OAT_SUPPLIER'){
                        result.forEach(e=>{
                            stringAppend+= `<option value="${e['supplier_id']}">${e['supplier_name']}</option>`;
                        })
                        stringAppend+= `</select>`;
                        $('#object_render').append(stringAppend);
                    }
                    else if(data == 'OAT_EMPLOYEE'){
                        result.forEach(e=>{
                            stringAppend+= `<option value="${e['staff_id']}">${e['full_name']}</option>`;
                        })
                        stringAppend+= `</select>`;
                        $('#object_render').append(stringAppend);
                    }
                    else{
                        $('#object_render').append(`<input type="text" class="form-control m-input btn-sm"
                             name="accounting_name" id="accounting_name"  placeholder="Nhập tên người nhận">`);
                    }
                    $('.select').select2();
                }
            });
        });
        // $('[name="edit_object_accounting_type_code"]').on('change',function(){
        //     var data = $('[name="edit_object_accounting_type_code"] option:selected').val();
        //     console.log(data);
        //     if(data == ''){
        //         $('#edit_object_render').children().remove();
        //         $('#edit_object_render').append(`<input type="text" class="form-control m-input btn-sm"
        //                      name="edit_accounting_name" id="edit_accounting_name"  placeholder="Nhập tên người nhận">`);
        //         return;
        //     }
        //     $.ajax({
        //         url: laroute.route("payment.append-by-object-type"),
        //         method: "POST",
        //         data: {
        //             code: data
        //         },
        //         success: function (result) {
        //             $('#edit_object_render').children().remove();
        //             var stringAppend = `<select name="edit_accounting_id" id="edit_accounting_id" class="form-control m-input select">`;
        //             if(data == 'OAT_CUSTOMER'){
        //                 result.forEach(e=>{
        //                     stringAppend+= `<option value="${e['customer_id']}">${e['full_name']}</option>`;
        //                 })
        //                 stringAppend+= `</select>`;
        //                 $('#edit_object_render').append(stringAppend);
        //             }
        //             else if(data == 'OAT_SUPPLIER'){
        //                 result.forEach(e=>{
        //                     stringAppend+= `<option value="${e['supplier_id']}">${e['supplier_name']}</option>`;
        //                 })
        //                 stringAppend+= `</select>`;
        //                 $('#edit_object_render').append(stringAppend);
        //             }
        //             else if(data == 'OAT_EMPLOYEE'){
        //                 result.forEach(e=>{
        //                     stringAppend+= `<option value="${e['staff_id']}">${e['full_name']}</option>`;
        //                 })
        //                 stringAppend+= `</select>`;
        //                 $('#edit_object_render').append(stringAppend);
        //             }
        //             else{
        //                 $('#edit_object_render').append(`<input type="text" class="form-control m-input btn-sm"
        //                      name="edit_accounting_name" id="edit_accounting_name"  placeholder="Nhập tên người nhận">`);
        //             }
        //             $('.select').select2();
        //         }
        //     });
        // });
    });
});
var dashboardConfig = {
    _init: function () {
        $(document).ready(function () {
            $.getJSON(laroute.route('translate'), function (json) {
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
                    locale: {
                        cancelLabel: 'Clear',
                        format: 'DD/MM/YYYY',
                        // "applyLabel": "Đồng ý",
                        // "cancelLabel": "Thoát",
                        "customRangeLabel": json['Tùy chọn ngày'],
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
                });
            });
        });
    },
    popCreateConfig: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            $.ajax({
                url: laroute.route('dashbroad.dashboard-config.pop-create'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                },
                success: function (res) {
                    $('#my-modal').html(res.html);
                    $('#modal-dashboard-config').modal('show');

                    $('.select').select2();

                    $('.date_picker').datepicker({
                        language: 'vi',
                        orientation: "bottom left",
                        todayHighlight: !0
                    });
                }
            });
        });
    },
    savePopCreateConfig: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-dashboard-config');

            form.validate({
                rules: {
                    dashboard_id: {
                        required: true,
                    },
                    name_vi: {
                        required: true,
                        maxlength: 191,
                    },
                    name_en: {
                        required: true,
                        maxlength: 191,
                    },
                },
                messages: {
                    dashboard_id: {
                        required: json['Hãy bản sao']
                    },
                    name_vi: {
                        required: json['Hãy nhập tên bố cục (tiếng viêt)'],
                        maxlength: json['Tên bố cục (tiếng viêt) tối đa 191 kí tự'],
                    },
                    name_en: {
                        required: json['Hãy nhập tên bố cục (tiếng anh)'],
                        maxlength: json['Tên bố cục (tiếng anh) tối đa 191 kí tự'],
                    },
                },
            });

            if (!form.valid()) {
                return false;
            }
            $.ajax({
                url: laroute.route('dashbroad.dashboard-config.submit-create-pop'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    'dashboard_id': $('#dashboard_id').val(),
                    'name_vi': $('#name_vi').val(),
                    'name_en': $('#name_en').val(),
                    'is_actived': $('#is_actived').is(":checked") ? 1 : 0,
                },
                success: function (res) {
                    $('#body_create').html(res.html);
                    $('#modal-dashboard-config').modal('hide');
                },
                error: function (res) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal(json['Thêm thất bại'], mess_error, "error");
                }
            });
        });
    },
    remove: function (obj, id) {
        $.getJSON(laroute.route('translate'), function (json) {
            swal({
                title: json["Thông báo"],
                text: json["Bạn có muốn xóa không?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json["Xóa"],
                cancelButtonText: json["Hủy"],
                onClose: function () {
                    // remove hightlight row
                    $(obj).closest('tr').removeClass('m-table__row--danger');
                }
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: laroute.route('dashbroad.dashboard-config.remove-dashboard'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            'dashboard_id': id,
                        },
                        success: function (res) {
                            if (res.error == false) {
                                swal.fire(res.message, "", "success");
                                window.location.href = '/dashbroad/dashboard-config'
                            } else {
                                swal.fire(res.message, '', "error");
                            }
                        }
                    });
                }
            });
        });
    },
    changeStatus: function (id, e) {
        let is_actived = 0;
        if($(e).is(":checked")){
            is_actived = 1;
        }
        $.ajax({
            url: laroute.route('dashbroad.dashboard-config.change-status'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                'dashboard_id': id,
                'is_actived': is_actived,
            },
            success: function (res) {
                if (res.error == false) {
                    swal.fire(res.message, "", "success");
                    window.location.href = '/dashbroad/dashboard-config'
                } else {
                    swal.fire(res.message, '', "error");
                }
            }
        });
    },
    popEditConfig: function (id) {
        $.getJSON(laroute.route('translate'), function (json) {
            $.ajax({
                url: laroute.route('dashbroad.dashboard-config.pop-edit'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    'dashboard_id': id
                },
                success: function (res) {
                    $('#my-modal').html(res.html);
                    $('#modal-dashboard-config').modal('show');

                    $('.select').select2();
                    $('.date_picker').datepicker({
                        language: 'vi',
                        orientation: "bottom left",
                        todayHighlight: !0
                    });
                }
            });
        });
    },
    savePopEditConfig: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-dashboard-config');
            form.validate({
                rules: {
                    name_vi: {
                        required: true,
                        maxlength: 191,
                    },
                    name_en: {
                        required: true,
                        maxlength: 191,
                    },
                },
                messages: {
                    name_vi: {
                        required: json['Hãy nhập tên bố cục (tiếng viêt)'],
                        maxlength: json['Tên bố cục (tiếng viêt) tối đa 191 kí tự'],
                    },
                    name_en: {
                        required: json['Hãy nhập tên bố cục (tiếng anh)'],
                        maxlength: json['Tên bố cục (tiếng anh) tối đa 191 kí tự'],
                    },
                },
            });

            if (!form.valid()) {
                return false;
            }
            $.ajax({
                url: laroute.route('dashbroad.dashboard-config.submit-edit-pop'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    'dashboard_id': $('#dashboard_id').val(),
                    'name_vi': $('#name_vi').val(),
                    'name_en': $('#name_en').val(),
                    'is_actived': $('#is_actived').is(":checked") ? 1 : 0,
                },
                success: function (res) {
                    $('#body_edit').html(res.html);
                    $('#modal-dashboard-config').modal('hide');
                },
                error: function (res) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal(json['Thêm thất bại'], mess_error, "error");
                }
            });
        });
    },
};
function inArray(needle, haystack) {
    var length = haystack.length;
    for(var i = 0; i < length; i++) {
        if(haystack[i] == needle) return true;
    }
    return false;
}
$('#autotable').PioTable({
    baseUrl: laroute.route('dashbroad.dashboard-config.list')
});