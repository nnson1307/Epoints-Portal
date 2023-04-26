var jsonLang = JSON.parse(localStorage.getItem('tranlate'));
$('.select2').select2();
$.getJSON(laroute.route('translate'), function (json) {
    var arrRange = {};
    arrRange[json["Hôm nay"]] = [moment(), moment()];
    arrRange[json["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
    arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
    arrRange[json["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
    arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
    arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
    $("#m_datepicker_1").daterangepicker({
        autoUpdateInput: true,
        autoApply: true,
        // buttonClasses: "m-btn btn",
        // applyClass: "btn-primary",
        // cancelClass: "btn-danger",
        // maxDate: moment().endOf("day"),
        // startDate: moment().startOf("day"),
        // endDate: moment().add(1, 'days'),
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
    }).on('apply.daterangepicker', function (ev, picker) {
        var start = picker.startDate.format("DD/MM/YYYY");
        var end = picker.endDate.format("DD/MM/YYYY");
        search.choosePerfomer();
    });

    $("#m_datepicker_1").val("");
});
var projectInfo = {
    showPopupAddIssue: function (id) {
        $.ajax({
            url: laroute.route("manager-project.project.popup-add-issue"),
            method: "POST",
            data: {
                id: id,
            },
            success: function (res) {
                if (res.error != false) {
                    swal("Lỗi", res.message, "error")
                } else {
                    $('.append-popup').empty();
                    $('.append-popup').append(res.view);
                    $('#add-issue').modal('show');
                }
            }
        });
    },
    showPopupAddExchange: function (id,job) {
        $.ajax({
            url: laroute.route("manager-project.project.popup-add-issue"),
            method: "POST",
            data: {
                id: id,
                job:job
            },
            success: function (res) {
                if (res.error != false) {
                    swal("Lỗi", res.message, "error")
                } else {
                    $('.append-popup').empty();
                    $('.append-popup').append(res.view);
                    $('#add-exchange').modal('show');
                }
            }
        });
    },
    saveNewIssue: function () {
        Swal.fire({
            title: jsonLang['Thông báo'],
            text: jsonLang['Bạn chắc chắn muốn thêm vấn đề này?'],
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: jsonLang['Tiếp tục'],
            cancelButtonText: jsonLang['Hủy']
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: laroute.route("manager-project.project.add-issue"),
                    method: "POST",
                    data: $('#add-issue-form').serialize(),
                    success: function (res) {
                        if (res.error != false) {
                            swal("Lỗi", res.message, "error")
                        } else {
                            swal(jsonLang["Thêm thành công"], '', "success").then(function () {
                                location.reload()
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
    deleteIssue: function (id) {
        Swal.fire({
            title: jsonLang['Thông báo'],
            text: jsonLang['Bạn chắc chắn muốn xóa vấn đề này?'],
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: jsonLang['Tiếp tục'],
            cancelButtonText: jsonLang['Hủy']
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: laroute.route("manager-project.project.delete-issue"),
                    method: "POST",
                    data: {
                        id: id
                    },
                    success: function (res) {
                        if (res.error != false) {
                            swal("Lỗi", res.message, "error")
                        } else {
                            swal(jsonLang["Xóa vấn đề thành công"], '', "success").then(function () {
                                location.reload()
                            });
                        }
                    },
                    error: function (response) {
                        Xóa
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
    showPopupEditIssue: function (id) {
        $.ajax({
            url: laroute.route("manager-project.project.popup-edit-issue"),
            method: "POST",
            data: {
                id: id
            },
            success: function (res) {
                if (res.error != false) {
                    swal("Lỗi", res.message, "error")
                } else {
                    $('.append-popup').empty();
                    $('.append-popup').append(res.view);
                    $('#edit-issue').modal('show');
                }
            }
        });
    },
    saveEditIssue: function () {
        Swal.fire({
            title: jsonLang['Thông báo'],
            text: jsonLang['Bạn chắc chắn muốn chỉnh sửa vấn đề này?'],
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: jsonLang['Tiếp tục'],
            cancelButtonText: jsonLang['Hủy']
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: laroute.route("manager-project.project.edit-issue"),
                    method: "POST",
                    data: $('#edit-issue-form').serialize(),
                    success: function (res) {
                        if (res.error != false) {
                            swal("Lỗi", res.message, "error")
                        } else {
                            swal(jsonLang["Chỉnh sửa thành công"], '', "success").then(function () {
                                location.reload()
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
    showPopupAddPayment: function (id, job) {
        $.ajax({
            url: laroute.route("manager-project.project.popup-add-payment"),
            method: "POST",
            data: {
                id: id,
                job: job
            },
            success: function (res) {
                if (res.error != false) {
                    swal("Lỗi", res.message, "error")
                } else {
                    $('.append-popup').empty();
                    $('.append-popup').append(res.view);
                    $('#add-payment').modal('show');
                    new AutoNumeric.multiple('#total_amount', {
                        currencySymbol: '',
                        decimalCharacter: '.',
                        digitGroupSeparator: ',',
                        decimalPlaces: 0,
                        eventIsCancelable: true,
                        minimumValue: 0
                    });

                    changeReceipt();
                }
            }
        });
    },
    showPopupAddReceipt: function (id, job) {
        $.ajax({
            url: laroute.route("manager-project.project.popup-add-receipt"),
            method: "POST",
            data: {
                id: id,
                job: job
            },
            success: function (res) {
                if (res.error != false) {
                    swal("Lỗi", res.message, "error")
                } else {
                    $('.append-popup').empty();
                    $('.append-popup').append(res.view);
                    $('#add-receipt').modal('show');
                    new AutoNumeric.multiple('.money_receipt', {
                        currencySymbol: '',
                        decimalCharacter: '.',
                        digitGroupSeparator: ',',
                        decimalPlaces: 0,
                        eventIsCancelable: true,
                        minimumValue: 0
                    });

                    changeReceipt();
                }
            }
        });
    },
    addPayment: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#add-payment-form');
            form.validate({
                rules: {
                    object_accounting_type_code: {
                        required: true
                    },
                    accounting_id: {
                        required: true
                    },
                    accounting_name: {
                        required: true
                    },
                    payment_type: {
                        required: true
                    },
                    total_amount: {
                        required: true
                    },
                    payment_method: {
                        required: true
                    },
                    branch_code: {
                        required: true
                    },
                    note: {
                        required: true
                    }
                },
                messages: {
                    object_accounting_type_code: {
                        required: json['Hãy chọn nhóm người nhận']
                    },
                    accounting_id: {
                        required: json['Hãy chọn người nhận']
                    },
                    accounting_name: {
                        required: json['Hãy nhập tên người nhận']
                    },
                    payment_type: {
                        required: json['Hãy chọn loại chi']
                    },
                    total_amount: {
                        required: json['Hãy nhập số tiền']
                    },
                    payment_method: {
                        required: json['Hãy chọn phương thức chi']
                    },
                    branch_code: {
                        required: json['Hãy chọn chi nhánh']
                    },
                    note: {
                        required: json['Hãy nhập mô tả']
                    }
                },
            });

            if (!form.valid()) {
                return false;
            }
            Swal.fire({
                title: jsonLang['Thông báo'],
                text: jsonLang['Bạn chắc chắn muốn thêm phiếu thu này?'],
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: jsonLang['Tiếp tục'],
                cancelButtonText: jsonLang['Hủy']
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: laroute.route("manager-project.project.add-new-payment"),
                        method: "POST",
                        data: $('#add-payment-form').serialize(),
                        success: function (res) {
                            if (res.error != false) {
                                swal("Lỗi", res.message, "error")
                            } else {
                                swal(jsonLang["Thêm thành công"], '', "success").then(function () {
                                    location.reload()
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
        })
    },
    changeType: function (obj) {
        if ($(obj).val() == 'OAT_SHIPPER' || $(obj).val() == 'OAT_OTHER') {
            $('.div_add_name').css('display', 'block');
            $('.div_add_id').css('display', 'none');
            $('#object_accounting_id').empty();
        } else {
            $('.div_add_name').css('display', 'none');
            $('.div_add_id').css('display', 'block');
            // Load option theo type
            let objAccountingType = $(obj).val();
            $.ajax({
                url: laroute.route('receipt.load-option-obj-accounting'),
                dataType: 'JSON',
                data: {
                    objAccountingType: objAccountingType
                },
                method: 'POST',
                success: function (res) {
                    $('#object_accounting_id').empty();
                    $.map(res, function (item) {
                        $('#object_accounting_id').append('<option value="' + item.accounting_id + '">' + item.accounting_name + '</option>');
                    });
                }
            });
        }
    },
    addReceipt: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#add-receipt-form');
            form.validate({
                rules: {
                    receipt_type_code: {required: true},
                    money: {required: true},
                    object_accounting_type_code: {required: true},
                    payment_method: {required: true}
                },
                messages: {
                    receipt_type_code: {required: json['Hãy chọn loại phiếu thu']},
                    money: {required: json['Hãy nhập số tiền']},
                    object_accounting_type_code: {required: json['Hãy chọn thông tin người trả tiền']},
                    payment_method: {required: json['Hãy chọn hình thức thanh toán']}
                },
            });

            if (!form.valid()) {
                return false;
            }
            Swal.fire({
                title: jsonLang['Thông báo'],
                text: jsonLang['Bạn chắc chắn muốn thêm phiếu chi này?'],
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: jsonLang['Tiếp tục'],
                cancelButtonText: jsonLang['Hủy']
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: laroute.route("manager-project.project.add-new-receipt"),
                        method: "POST",
                        // data: $('#add-receipt-form').serialize(),
                        data : {
                            receiptTypeCode: $('#receipt_type_code').val(),
                            money: $('#money').val(),
                            objectAccountingTypeCode: $('#object_accounting_type_code').val(),
                            note: $('#note').val(),
                            objectAccountingId: $('#object_accounting_id').val(),
                            objectAccountingName: $('#object_accounting_name').val(),
                            paymentMethodId: $('#payment_method').val(),
                            manage_project_id: $('#manage_project_id').val(),
                        },
                        success: function (res) {
                            if (res.error != false) {
                                swal(jsonLang["Lỗi"], res.message, "error")
                            } else {
                                swal(jsonLang["Thêm thành công"], '', "success").then(function () {
                                    location.reload()
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
        })
    },

}


function changeReceipt (){
    $('[name="object_accounting_type_code"]').on('change',function(){
        var data = $('[name="object_accounting_type_code"] option:selected').val();
        if(data == ''){
            $('#object_render').children().remove();
            $('#object_render').append(`<input type="text" class="form-control m-input btn-sm"
                             name="accounting_name" id="accounting_name"  placeholder="Nháº­p tĂªn ngÆ°á»i nháº­n">`);
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
                             name="accounting_name" id="accounting_name"  placeholder="Nháº­p tĂªn ngÆ°á»i nháº­n">`);
                }
                $('.select').select2();
            }
        });
    });
}