var listPackage = {
    _init: function () {
        $('#autotable').PioTable({
            baseUrl: laroute.route('warranty-package.list')
        });

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
            });
        });
    },
    // Cập nhật trạng thái gói bảo hành
    updateStatus: function (id, is_actived) {
        $.ajax({
            url: laroute.route('warranty-package.update-status'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                packageId: id,
                is_actived: is_actived
            },
            success: function (res) {
                if (res.error == false) {
                    swal.fire(res.message, "", "success");
                    window.location = laroute.route('warranty-package');
                } else {
                    swal.fire(res.message, '', "error");
                }
            }
        });
    },
    // Xoá gói bảo hành
    delete: function (id) {
        $.getJSON(laroute.route('translate'), function (json) {
            swal({
                title: json['Thông báo'],
                text: json["Bạn có muốn xóa không?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json['Xóa'],
                cancelButtonText: json['Hủy'],
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: laroute.route('warranty-package.delete'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            packageId: id
                        },
                        success: function (res) {
                            if (res.error == false) {
                                swal.fire(res.message, "", "success");
                                window.location = laroute.route('warranty-package');
                            } else {
                                swal.fire(res.message, '', "error");
                            }
                        }
                    });
                }
            });
        });
    }
}

var create = {
    _init: function () {
        // Mô tả chi tiết
        $(".summernote").summernote({height: 160});
        // Tab thời gian bảo hành (ngày, tuần, tháng, năm)
        $('.rdo').click(function () {
            $('.rdo').attr('class', 'btn btn-default rdo');
            $(this).attr('class', 'btn ss--button-cms-piospa active rdo');
        });
        // Thời gian bảo hành
        $('#time_warranty_unlimited').change(function () {
            let checked = $(this).prop("checked");
            if (checked) {
                $('#time_warranty').val('');
                $('#time_warranty').attr("disabled", true);
            } else
                $('#time_warranty').attr("disabled", false);
        });
        // Số lần được bảo hành
        $('#number_warranty_unlimited').change(function () {
            let checked = $(this).prop("checked");
            if (checked) {
                $('#number_warranty').val('');
                $('#number_warranty').attr("disabled", true);
            } else
                $('#number_warranty').attr("disabled", false);
        });
        //
        // $('#percent').ForceNumericOnly();
        new AutoNumeric.multiple('#money_maximum, #percent', {
            currencySymbol: '',
            decimalCharacter: '.',
            digitGroupSeparator: ',',
            decimalPlaces: decimal_number,
            eventIsCancelable: true,
            minimumValue: 0
        });
        new AutoNumeric.multiple('#time_warranty, #number_warranty', {
            currencySymbol: '',
            decimalCharacter: '.',
            digitGroupSeparator: ',',
            decimalPlaces: 0,
            eventIsCancelable: true,
            minimumValue: 0
        });
    },

    save: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-create');
            form.validate({
                rules: {
                    package_name: { required: true },
                    percent: {required: true },
                    money_maximum: {required: true }
                },
                messages: {
                    package_name: { required: json['Hãy nhập tên gói bảo hành'] },
                    percent: {required: json['Hãy nhập giá trị bảo hành'] },
                    money_maximum: {required: json['Hãy nhập số tiền tối đa được bảo hành'] }
                },
            });

            if (!form.valid()) {
                return false;
            }
            let flag = true;
            // check time_warranty_unlimited
            let isTimeUnlimited = $('#time_warranty_unlimited').is(":checked");
            let timeType = $('.active').find('input[name="date-use"]').val();
            let timeWarranty = 0;
            if (isTimeUnlimited) {
                isTimeUnlimited = 1;
                timeType = 'infinitive';
                $('.error-time-warranty').text('');
            } else {
                isTimeUnlimited = 0;
                timeWarranty = $('#time_warranty').val();
                if (timeWarranty == "") {
                    $('.error-time-warranty').text(json['Vui lòng nhập thời hạn bảo hành']);
                    flag = false;
                } else {
                    $('.error-time-warranty').text('');
                }
            }
            // check number_warranty_unlimited
            let isNumberUnlimited = $('#number_warranty_unlimited').is(":checked");
            let numberWarranty = 0;
            if (isNumberUnlimited) {
                isNumberUnlimited = 1;
                $('.error-number-warranty').text('');
            } else {
                isNumberUnlimited = 0;
                numberWarranty = $('#number_warranty').val();
                if (numberWarranty == "") {
                    $('.error-number-warranty').text(json['Vui lòng nhập số lần được bảo hành']);
                    flag = false;
                } else {
                    $('.error-number-warranty').text('');
                }
            }
            if (flag) {
                $.ajax({
                    url: laroute.route('warranty-package.store'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        packageName: $('#package_name').val(),
                        percent: $('#percent').val(),
                        moneyMaximum: $('#money_maximum').val(),
                        timeType: timeType,
                        isTimeUnlimited: isTimeUnlimited,
                        timeWarranty: timeWarranty,
                        isNumberUnlimited: isNumberUnlimited,
                        numberWarranty: numberWarranty,
                        shortDescription: $('#short_description').val(),
                        detailDescription: $('.summernote').summernote('code'),

                    },
                    success: function (res) {
                        if (res.error == false) {
                            swal(res.message, "", "success").then(function (result) {
                                if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                    window.location.href = laroute.route('warranty-package');
                                }
                                if (result.value == true) {
                                    window.location.href = laroute.route('warranty-package');
                                }
                            });
                        } else {
                            swal(res.message, '', "error");
                        }
                    },
                    error: function (res) {
                        var mess_error = '';
                        $.map(res.responseJSON.errors, function (a) {
                            mess_error = mess_error.concat(a + '<br/>');
                        });
                        swal(json['Thêm mới thất bại'], mess_error, "error");
                    }
                });
            }
        });
    }
}

var edit = {
    _init: function () {
        // Load init (Mô tả chi tiết + checkbox)
        $(".summernote").summernote({height: 160});
        if ($('#time_warranty_unlimited').is(":checked")) {
            $('#time_warranty').val('');
            $('#time_warranty').attr("disabled", true);
        }
        if ($('#number_warranty_unlimited').is(":checked")) {
            $('#number_warranty').val('');
            $('#number_warranty').attr("disabled", true);
        }

        // Tab thời gian bảo hành (ngày, tuần, tháng, năm)
        $('.rdo').click(function () {
            $('.rdo').attr('class', 'btn btn-default rdo');
            $(this).attr('class', 'btn ss--button-cms-piospa active rdo');
        });
        // Thời gian bảo hành
        $('#time_warranty_unlimited').change(function () {
            let checked = $(this).prop("checked");
            if (checked) {
                $('#time_warranty').val('');
                $('#time_warranty').attr("disabled", true);
            } else
                $('#time_warranty').attr("disabled", false);
        });
        // Số lần được bảo hành
        $('#number_warranty_unlimited').change(function () {
            let checked = $(this).prop("checked");
            if (checked) {
                $('#number_warranty').val('');
                $('#number_warranty').attr("disabled", true);
            } else
                $('#number_warranty').attr("disabled", false);
        });
        //
        // $('#percent').ForceNumericOnly();
        new AutoNumeric.multiple('#money_maximum, #percent', {
            currencySymbol: '',
            decimalCharacter: '.',
            digitGroupSeparator: ',',
            decimalPlaces: decimal_number,
            eventIsCancelable: true,
            minimumValue: 0
        });
        new AutoNumeric.multiple('#time_warranty, #number_warranty', {
            currencySymbol: '',
            decimalCharacter: '.',
            digitGroupSeparator: ',',
            decimalPlaces: 0,
            eventIsCancelable: true,
            minimumValue: 0
        });
        $('#autotable-discount').PioTable({
            baseUrl: laroute.route('warranty-package.list-discount')
        });
    },
    
    save: function (code) {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-edit');
            form.validate({
                rules: {
                    package_name: { required: true },
                    percent: {required: true },
                    money_maximum: {required: true }
                },
                messages: {
                    package_name: { required: json['Hãy nhập tên gói bảo hành'] },
                    percent: {required: json['Hãy nhập giá trị bảo hành'] },
                    money_maximum: {required: json['Hãy nhập số tiền tối đa được bảo hành'] }
                },
            });

            if (!form.valid()) {
                return false;
            }
            let flag = true;
            // check time_warranty_unlimited
            let isTimeUnlimited = $('#time_warranty_unlimited').is(":checked");
            let timeType = $('.active').find('input[name="date-use"]').val();
            let timeWarranty = 0;
            if (isTimeUnlimited) {
                isTimeUnlimited = 1;
                timeType = 'infinitive';
                $('.error-time-warranty').text('');
            } else {
                isTimeUnlimited = 0;
                timeWarranty = $('#time_warranty').val();
                if (timeWarranty == "") {
                    $('.error-time-warranty').text(json['Vui lòng nhập thời hạn bảo hành']);
                    flag = false;
                } else {
                    $('.error-time-warranty').text('');
                }
            }
            // check number_warranty_unlimited
            let isNumberUnlimited = $('#number_warranty_unlimited').is(":checked");
            let numberWarranty = 0;
            if (isNumberUnlimited) {
                isNumberUnlimited = 1;
                $('.error-number-warranty').text('');
            } else {
                isNumberUnlimited = 0;
                numberWarranty = $('#number_warranty').val();
                if (numberWarranty == "") {
                    $('.error-number-warranty').text(json['Vui lòng nhập số lần được bảo hành']);
                    flag = false;
                } else {
                    $('.error-number-warranty').text('');
                }
            }
            if (flag) {
                $.ajax({
                    url: laroute.route('warranty-package.update'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        packageCode: code,
                        packageName: $('#package_name').val(),
                        percent: $('#percent').val(),
                        moneyMaximum: $('#money_maximum').val(),
                        timeType: timeType,
                        isTimeUnlimited: isTimeUnlimited,
                        timeWarranty: timeWarranty,
                        isNumberUnlimited: isNumberUnlimited,
                        numberWarranty: numberWarranty,
                        shortDescription: $('#short_description').val(),
                        detailDescription: $('.summernote').summernote('code'),

                    },
                    success: function (res) {
                        if (res.error == false) {
                            swal(res.message, "", "success").then(function (result) {
                                if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                    window.location.href = laroute.route('warranty-package');
                                }
                                if (result.value == true) {
                                    window.location.href = laroute.route('warranty-package');
                                }
                            });
                        } else {
                            swal(res.message, '', "error");
                        }
                    },
                    error: function (res) {
                        var mess_error = '';
                        $.map(res.responseJSON.errors, function (a) {
                            mess_error = mess_error.concat(a + '<br/>');
                        });
                        swal(json['Thêm mới thất bại'], mess_error, "error");
                    }
                });
            }
        });
    }
}

var view = {
    showModal: function (type) {
        $.ajax({
            url: laroute.route('warranty-package.popup'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                type: type
            },
            success: function (res) {
                $('#my-modal').html(res.html);
                $('#modal-product').modal('show');

                if (type == 'product') {
                    $('#autotable').PioTable({
                        baseUrl: laroute.route('warranty-package.list-product')
                    });
                } else if (type == 'service') {
                    $('#autotable').PioTable({
                        baseUrl: laroute.route('warranty-package.list-service')
                    });
                } else if (type == 'service_card') {
                    $('#autotable').PioTable({
                        baseUrl: laroute.route('warranty-package.list-service-card')
                    });
                }
            }
        });
    },
    chooseAll: function (obj, type) {
        if ($(obj).is(':checked')) {
            $('.check_one').prop('checked', true);

            var arrCheck = [];
            $('.check_one').each(function () {
                if (type == 'product') {
                    arrCheck.push({
                        object_id: $(this).parents('label').find('.product_id').val(),
                        object_code: $(this).parents('label').find('.product_code').val(),
                        object_name: $(this).parents('label').find('.product_name').val(),
                        base_price: $(this).parents('label').find('.base_price').val()
                    });
                } else if (type == 'service') {
                    arrCheck.push({
                        object_id: $(this).parents('label').find('.service_id').val(),
                        object_code: $(this).parents('label').find('.service_code').val(),
                        object_name: $(this).parents('label').find('.service_name').val(),
                        base_price: $(this).parents('label').find('.base_price').val()
                    });
                } else if (type == 'service_card') {
                    arrCheck.push({
                        object_id: $(this).parents('label').find('.service_card_id').val(),
                        object_code: $(this).parents('label').find('.service_card_code').val(),
                        object_name: $(this).parents('label').find('.service_card_name').val(),
                        base_price: $(this).parents('label').find('.base_price').val()
                    });
                }
            });

            $.ajax({
                url: laroute.route('warranty-package.choose-all'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    arr_check: arrCheck,
                    type: type
                }
            });
        } else {
            $('.check_one').prop('checked', false);
            var arrUnCheck = [];
            $('.check_one').each(function () {
                if (type == 'product') {
                    arrUnCheck.push({
                        object_id: $(this).parents('label').find('.product_id').val(),
                        object_code: $(this).parents('label').find('.product_code').val(),
                        object_name: $(this).parents('label').find('.product_name').val(),
                        base_price: $(this).parents('label').find('.base_price').val()
                    });
                } else if (type == 'service') {
                    arrUnCheck.push({
                        object_id: $(this).parents('label').find('.service_id').val(),
                        object_code: $(this).parents('label').find('.service_code').val(),
                        object_name: $(this).parents('label').find('.service_name').val(),
                        base_price: $(this).parents('label').find('.base_price').val()
                    });
                } else if (type == 'service_card') {
                    arrUnCheck.push({
                        object_id: $(this).parents('label').find('.service_card_id').val(),
                        object_code: $(this).parents('label').find('.service_card_code').val(),
                        object_name: $(this).parents('label').find('.service_card_name').val(),
                        base_price: $(this).parents('label').find('.base_price').val()
                    });
                }
            });

            $.ajax({
                url: laroute.route('warranty-package.un-choose-all'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    arr_un_check: arrUnCheck,
                    type: type
                }
            });
        }
    },
    choose: function (obj, type) {
        if ($(obj).is(":checked")) {
            var objectId = '';
            var objectCode = '';
            var objectName = '';
            var basePrice = '';

            if (type == 'product') {
                objectId = $(obj).parents('label').find('.product_id').val();
                objectCode = $(obj).parents('label').find('.product_code').val();
                objectName = $(obj).parents('label').find('.product_name').val();
                basePrice = $(obj).parents('label').find('.base_price').val();
            } else if (type == 'service') {
                objectId = $(obj).parents('label').find('.service_id').val();
                objectCode = $(obj).parents('label').find('.service_code').val();
                objectName = $(obj).parents('label').find('.service_name').val();
                basePrice = $(obj).parents('label').find('.base_price').val();
            } else if (type == 'service_card') {
                objectId = $(obj).parents('label').find('.service_card_id').val();
                objectCode = $(obj).parents('label').find('.service_card_code').val();
                objectName = $(obj).parents('label').find('.service_card_name').val();
                basePrice = $(obj).parents('label').find('.base_price').val();
            }

            $.ajax({
                url: laroute.route('warranty-package.choose'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    object_id: objectId,
                    object_code: objectCode,
                    object_name: objectName,
                    base_price: basePrice,
                    type: type
                }
            });
        } else {
            var objectId = '';
            var objectCode = '';
            var objectName = '';
            var basePrice = '';

            if (type == 'product') {
                objectId = $(obj).parents('label').find('.product_id').val();
                objectCode = $(obj).parents('label').find('.product_code').val();
                objectName = $(obj).parents('label').find('.product_name').val();
                basePrice = $(obj).parents('label').find('.base_price').val();
            } else if (type == 'service') {
                objectId = $(obj).parents('label').find('.service_id').val();
                objectCode = $(obj).parents('label').find('.service_code').val();
                objectName = $(obj).parents('label').find('.service_name').val();
                basePrice = $(obj).parents('label').find('.base_price').val();
            } else if (type == 'service_card') {
                objectId = $(obj).parents('label').find('.service_card_id').val();
                objectCode = $(obj).parents('label').find('.service_card_code').val();
                objectName = $(obj).parents('label').find('.service_card_name').val();
                basePrice = $(obj).parents('label').find('.base_price').val();
            }

            $.ajax({
                url: laroute.route('warranty-package.un-choose'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    object_id: objectId,
                    object_code: objectCode,
                    object_name: objectName,
                    base_price: basePrice,
                    type: type
                }
            });
        }
    },
    submitChoose: function (type) {
        $.ajax({
            url: laroute.route('warranty-package.submit-choose'),
            dataType: 'JSON',
            method: 'POST',
            data: {
                type: type
            },
            success: function (res) {
                $('.div_table_gift').empty();

                $('.div_table_discount').html(res.html);
                $('#modal-product').modal('hide');

                $('#autotable-discount').PioTable({
                    baseUrl: laroute.route('warranty-package.list-discount')
                });
                $('.btn-search').trigger('click');

            }
        });
    },
    removeTr: function (obj, object_code, type_table, object_type) {
        $.ajax({
            url: laroute.route('warranty-package.remove-tr'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                object_code: object_code,
                object_type: object_type
            },
            success: function (res) {
                $(obj).closest('tr').remove();
                $('#autotable-discount').PioTable('refresh');
            }
        });
    },
}

var detail = {
    _init: function () {
        // Load init (Mô tả chi tiết + checkbox)
        $(".summernote").summernote({height: 160});
        $('.summernote').summernote('disable');
        if ($('#time_warranty_unlimited').is(":checked")) {
            $('#time_warranty').val('');
            $('#time_warranty').attr("disabled", true);
        }
        if ($('#number_warranty_unlimited').is(":checked")) {
            $('#number_warranty').val('');
            $('#number_warranty').attr("disabled", true);
        }

        // Tab thời gian bảo hành (ngày, tuần, tháng, năm)
        $('.rdo').click(function () {
            $('.rdo').attr('class', 'btn btn-default rdo');
            $(this).attr('class', 'btn ss--button-cms-piospa active rdo');
        });
        // Thời gian bảo hành
        $('#time_warranty_unlimited').change(function () {
            let checked = $(this).prop("checked");
            if (checked) {
                $('#time_warranty').val('');
                $('#time_warranty').attr("disabled", true);
            } else
                $('#time_warranty').attr("disabled", false);
        });
        // Số lần được bảo hành
        $('#number_warranty_unlimited').change(function () {
            let checked = $(this).prop("checked");
            if (checked) {
                $('#number_warranty').val('');
                $('#number_warranty').attr("disabled", true);
            } else
                $('#number_warranty').attr("disabled", false);
        });
        new AutoNumeric.multiple('#money_maximum, #percent', {
            currencySymbol: '',
            decimalCharacter: '.',
            digitGroupSeparator: ',',
            decimalPlaces: decimal_number,
            eventIsCancelable: true,
            minimumValue: 0
        });
        new AutoNumeric.multiple('#time_warranty, #number_warranty', {
            currencySymbol: '',
            decimalCharacter: '.',
            digitGroupSeparator: ',',
            decimalPlaces: 0,
            eventIsCancelable: true,
            minimumValue: 0
        });
        $('#autotable-discount').PioTable({
            baseUrl: laroute.route('warranty-package.list-discount-detail')
        });
    },
}

jQuery.fn.ForceNumericOnly =
    function () {
        return this.each(function () {
            $(this).keydown(function (e) {
                var key = e.charCode || e.keyCode || 0;
                // allow backspace, tab, delete, enter, arrows, numbers and keypad numbers ONLY
                // home, end, period, and numpad decimal
                return (
                    key == 8 ||
                    key == 9 ||
                    key == 13 ||
                    key == 46 ||
                    key == 110 ||
                    key == 190 ||
                    (key >= 35 && key <= 40) ||
                    (key >= 48 && key <= 57) ||
                    (key >= 96 && key <= 105));
            });
        });
    };