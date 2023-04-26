var list = {
    _init: function () {
        $('#auto_table').PioTable({
            baseUrl: laroute.route('repair.list')
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
                locale: {
                    cancelLabel: 'Clear',
                    format: 'DD/MM/YYYY',
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
    }
}

var stt = 0;
var view = {
    _init: function () {
        $(document).ready(function () {
            $.getJSON(laroute.route('translate'), function (json) {
                $('#staff_id').select2({
                    placeholder: json['Chọn nhân viên thực hiện']
                });

                $("#repair_date").datetimepicker({
                    todayHighlight: !0,
                    autoclose: !0,
                    format: "dd/mm/yyyy hh:ii"
                });

                $('#object_type').select2({
                    placeholder: json['Chọn loại đối tượng']
                });

                new AutoNumeric.multiple('#insurance_pay, #repair_cost', {
                    currencySymbol: '',
                    decimalCharacter: '.',
                    digitGroupSeparator: ',',
                    decimalPlaces: decimal_number,
                    eventIsCancelable: true,
                    minimumValue: 0
                });

                view.loadObject(json);

                $('.maintenance_cost_type').select2({
                    placeholder: json['Hãy chọn loại chi phí']
                });

                new AutoNumeric.multiple('.cost', {
                    currencySymbol: '',
                    decimalCharacter: '.',
                    digitGroupSeparator: ',',
                    decimalPlaces: decimal_number,
                    eventIsCancelable: true,
                    minimumValue: 0
                });

                $('#status').select2();
            });
        });
    },
    changeType: function (obj) {
        var typeHidden = $('#object_type_hidden').val();
        var typeCurrent = $(obj).val();

        if (typeCurrent != typeHidden) {
            $('#object_code').val('');
            $('#object_id').val('').trigger('change');
        }

        $('#object_type_hidden').val(typeCurrent);

        view.loadAmountPay();
    },
    loadObject: function (json) {
        $('#object_id').select2({
            placeholder: json['Chọn đối tượng'],
            width: '100%',
            allowClear: true,
            ajax: {
                url: laroute.route('maintenance.load-object'),
                data: function (params) {
                    return {
                        search: params.term,
                        page: params.page || 1,
                        object_type: $('#object_type').val()
                    };
                },
                dataType: 'json',
                method: 'POST',
                processResults: function (data) {
                    data.page = data.page || 1;
                    return {
                        results: data.items.map(function (item) {
                            if ($('#object_type').val() == 'product') {
                                return {
                                    id: item.product_child_id,
                                    text: item.product_child_name,
                                    code: item.product_code
                                };
                            } else if ($('#object_type').val() == 'service') {
                                return {
                                    id: item.service_id,
                                    text: item.service_name,
                                    code: item.service_code
                                };
                            } else if ($('#object_type').val() == 'service_card') {
                                return {
                                    id: item.service_card_id,
                                    text: item.card_name,
                                    code: item.code
                                };
                            }
                        }),
                        pagination: {
                            more: data.pagination
                        }
                    };
                },
            }
        }).on('select2:select', function (event) {
            $('#object_code').val(event.params.data.code);
        }).on('select2:unselect', function (event) {
            $('#object_code').val('');

            view.loadAmountPay();
        });
    },
    loadAmountPay: function () {
        //Chi phí bảo dưỡng
        var repairCost = $('#repair_cost').val().replace(new RegExp('\\,', 'g'), '');
        //Bảo hiểm chi trả
        var insurancePay = $('#insurance_pay').val().replace(new RegExp('\\,', 'g'), '');
        //Tính chi phí phải trả
        var amountPay = Number(repairCost) - Number(insurancePay);

        if (amountPay < 0) {
            amountPay = 0;
        }

        $('#amount_pay').val(amountPay);
        //Lấy tổng chi phí phát sinh
        var totalCost = 0;

        $.each($('#table-maintenance-cost').find(".tr_code"), function () {
            var cost = $(this).find($('.cost')).val().replace(new RegExp('\\,', 'g'), '');

            totalCost = Number(totalCost) + Number(cost);
        });

        if (totalCost == 0 && amountPay < 0) {
            $('#amount_pay').val(0);
        }

        //Tính tổng chi phí phải trả
        var totalAmountPay = Number(amountPay) + Number(totalCost);

        // if (totalAmountPay < 0) {
        //     totalAmountPay = 0;
        // }
        $('#total_amount_pay').val(totalAmountPay);
        $('.div_total_amount_pay').text(formatNumber(totalAmountPay.toFixed(decimal_number)));
    },
    addCost: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var continute = true;

            $.each($('#table-maintenance-cost').find(".tr_code"), function () {
                var maintenanceCostType = $(this).find($('.maintenance_cost_type')).val();
                var cost = $(this).find($('.cost')).val();
                var number = $(this).find($('.number')).val();

                if (maintenanceCostType == '') {
                    $('.error_cost_type_' + number + '').text(json['Hãy chọn loại chi phí']);
                    continute = false;
                } else {
                    $('.error_cost_type_' + number + '').text('');
                }

                if (cost == '') {
                    $('.error_cost_' + number + '').text(json['Hãy nhập chi phí phát sinh']);
                    continute = false;
                } else {
                    $('.error_cost_' + number + '').text('');
                }
            });

            if (continute == true) {
                stt++;
                //append tr table
                var tpl = $('#tpl-tr-table').html();
                tpl = tpl.replace(/{stt}/g, stt);
                $('#table-maintenance-cost > tbody').append(tpl);

                $('.maintenance_cost_type').select2({
                    placeholder: json['Hãy chọn loại chi phí']
                });

                new AutoNumeric.multiple('.cost_' + stt + '', {
                    currencySymbol: '',
                    decimalCharacter: '.',
                    digitGroupSeparator: ',',
                    decimalPlaces: decimal_number,
                    eventIsCancelable: true,
                    minimumValue: 0
                });
            }
        });
    },
    changeCostType: function (obj) {
        $(obj).closest('tr').find('.cost').prop('disabled', false);
    },
    removeTr: function (obj) {
        $(obj).closest('tr').remove();

        view.loadAmountPay();
    },
    dropzoneBefore: function () {
        Dropzone.options.dropzoneBefore = {
            paramName: 'file',
            maxFilesize: 10, // MB
            maxFiles: 20,
            acceptedFiles: ".jpeg,.jpg,.png,.gif",
            addRemoveLinks: true,
            headers: {
                "X-CSRF-TOKEN": $('input[name=_token]').val()
            },
            renameFile: function (file) {
                var dt = new Date();
                var time = dt.getTime().toString() + dt.getDate().toString() + (dt.getMonth() + 1).toString() + dt.getFullYear().toString();
                var random = "";
                var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                for (let z = 0; z < 10; z++) {
                    random += possible.charAt(Math.floor(Math.random() * possible.length));
                }
                return time + "_" + random + "." + file.name.substr((file.name.lastIndexOf('.') + 1));
            },
            init: function () {
                this.on("sending", function (file, xhr, data) {
                    data.append("link", "_maintenance.");
                });

                this.on("success", function (file, response) {
                    var a = document.createElement('span');
                    a.className = "thumb-url btn btn-primary";
                    a.setAttribute('data-clipboard-text', laroute.route('admin.upload-image'));

                    if (response.error == 0) {
                        $("#up-image-before").append("<input type='hidden' class='" + file.upload.filename + "'  name='fileName' value='" + response.file + "'>");
                    }
                });

                this.on('removedfile', function (file, response) {
                    var checkImage = $('#up-image-before').find('input[name="fileName"]');

                    $.each(checkImage, function () {
                        if ($(this).attr('class') == file.upload.filename) {
                            $(this).remove();
                        }
                    });
                });
            }
        };
    },
    dropzoneAfter: function () {
        Dropzone.options.dropzoneAfter = {
            paramName: 'file',
            maxFilesize: 10, // MB
            maxFiles: 20,
            acceptedFiles: ".jpeg,.jpg,.png,.gif",
            addRemoveLinks: true,
            headers: {
                "X-CSRF-TOKEN": $('input[name=_token]').val()
            },
            renameFile: function (file) {
                var dt = new Date();
                var time = dt.getTime().toString() + dt.getDate().toString() + (dt.getMonth() + 1).toString() + dt.getFullYear().toString();
                var random = "";
                var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                for (let z = 0; z < 10; z++) {
                    random += possible.charAt(Math.floor(Math.random() * possible.length));
                }
                return time + "_" + random + "." + file.name.substr((file.name.lastIndexOf('.') + 1));
            },
            init: function () {
                this.on("sending", function (file, xhr, data) {
                    data.append("link", "_maintenance.");
                });

                this.on("success", function (file, response) {
                    var a = document.createElement('span');
                    a.className = "thumb-url btn btn-primary";
                    a.setAttribute('data-clipboard-text', laroute.route('admin.upload-image'));

                    if (response.error == 0) {
                        $("#up-image-after").append("<input type='hidden' class='" + file.upload.filename + "'  name='fileName' value='" + response.file + "'>");
                    }
                });

                this.on('removedfile', function (file, response) {
                    var checkImage = $('#up-image-after').find('input[name="fileName"]');

                    $.each(checkImage, function () {
                        if ($(this).attr('class') == file.upload.filename) {
                            $(this).remove();
                        }
                    });
                });
            }
        };
    },
    modalImageBefore: function () {
        $('#up-image-before').empty();
        $('#dropzoneBefore')[0].dropzone.files.forEach(function (file) {
            file.previewElement.remove();
        });
        $('#dropzoneBefore').removeClass('dz-started');

        $('#modal-image-before').modal({
            backdrop: 'static', keyboard: false
        });
    },
    removeImage: function (e) {
        $(e).closest('.image-show-child').remove();
    },
    submitImageBefore: function () {
        var checkImage = $('#up-image-before').find('input[name="fileName"]');

        $.each(checkImage, function () {
            let tpl = $('#tpl-image-before').html();
            tpl = tpl.replace(/{imageName}/g, $(this).val());
            tpl = tpl.replace(/{imageName}/g, $(this).val());
            $('.image_before').append(tpl);
            $('.delete-img-sv').css('display', 'block');
        });

        $('#modal-image-before').modal('hide');
    },
    modalImageAfter: function () {
        $('#up-image-after').empty();
        $('#dropzoneAfter')[0].dropzone.files.forEach(function (file) {
            file.previewElement.remove();
        });
        $('#dropzoneAfter').removeClass('dz-started');

        $('#modal-image-after').modal({
            backdrop: 'static', keyboard: false
        });
    },
    submitImageAfter: function () {
        var checkImage = $('#up-image-after').find('input[name="fileName"]');

        $.each(checkImage, function () {
            let tpl = $('#tpl-image-after').html();
            tpl = tpl.replace(/{imageName}/g, $(this).val());
            tpl = tpl.replace(/{imageName}/g, $(this).val());
            $('.image_after').append(tpl);
            $('.delete-img-sv').css('display', 'block');
        });

        $('#modal-image-after').modal('hide');
    }
}

var create = {
    save: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-register');
            form.validate({
                rules: {
                    object_type: {
                        required: true
                    },
                    object_id: {
                        required: true
                    },
                    object_status: {
                        maxlength: 191
                    },
                    staff_id: {
                        required: true
                    },
                    repair_date: {
                        required: true
                    },
                    repair_content: {
                        maxlength: 191
                    }
                },
                messages: {
                    object_type: {
                        required: json['Hãy chọn loại đối tượng']
                    },
                    object_id: {
                        required: json['Hãy chọn đối tượng']
                    },
                    object_status: {
                        maxlength: json['Tình trạng đối tượng tối đa 191 kí tự']
                    },
                    staff_id: {
                        required: json['Hãy chọn nhân viên đưa đi bảo dưỡng']
                    },
                    repair_date: {
                        required: json['Hãy chọn ngày đưa đi bảo dưỡng']
                    },
                    repair_content: {
                        maxlength: json['Nội dung bảo dưỡng tối đa 191 kí tự']
                    }
                },
            });

            if (!form.valid()) {
                return false;
            }

            var next = true;

            var imageBefore = [];
            var imageAfter = [];
            var arrayCost = [];

            //Lấy hình ảnh trc bảo trì
            $.each($('.image_before').find('input[name="img-before"]'), function () {
                imageBefore.push($(this).val());
            });
            //Lấy hình ảnh sau bảo trì
            $.each($('.image_after').find('input[name="img-after"]'), function () {
                imageAfter.push($(this).val());
            });
            //Lấy chi phí phát sinh
            $.each($('#table-maintenance-cost').find(".tr_code"), function () {
                var maintenanceCostType = $(this).find($('.maintenance_cost_type')).val();
                var cost = $(this).find($('.cost')).val();
                var number = $(this).find($('.number')).val();

                if (maintenanceCostType == '') {
                    $('.error_cost_type_' + number + '').text(json['Hãy chọn loại chi phí']);
                    next = false;
                } else {
                    $('.error_cost_type_' + number + '').text('');
                }

                if (cost == '') {
                    $('.error_cost_' + number + '').text(json['Hãy nhập chi phí phát sinh']);
                    next = false;
                } else {
                    $('.error_cost_' + number + '').text('');
                }

                arrayCost.push({
                    costType: maintenanceCostType,
                    cost: cost.replace(new RegExp('\\,', 'g'), '')
                });
            });

            if (next == true) {
                $.ajax({
                    url: laroute.route('repair.store'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        object_type: $('#object_type').val(),
                        object_id: $('#object_id').val(),
                        object_code: $('#object_code').val(),
                        object_status: $('#object_status').val(),
                        staff_id: $('#staff_id').val(),
                        repair_date: $('#repair_date').val(),
                        repair_cost: Number($('#repair_cost').val().replace(new RegExp('\\,', 'g'), '')),
                        insurance_pay: Number($('#insurance_pay').val().replace(new RegExp('\\,', 'g'), '')),
                        repair_content: $('#repair_content').val(),
                        amount_pay: Number($('#amount_pay').val().replace(new RegExp('\\,', 'g'), '')),
                        total_amount_pay: Number($('#total_amount_pay').val().replace(new RegExp('\\,', 'g'), '')),
                        imageBefore: imageBefore,
                        imageAfter: imageAfter,
                        arrayCost: arrayCost
                    },
                    success: function (res) {
                        if (res.error == false) {
                            swal(res.message, "", "success").then(function (result) {
                                if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                    window.location.href = laroute.route('repair');
                                }
                                if (result.value == true) {
                                    window.location.href = laroute.route('repair');
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
                        swal(json['Thêm thất bại'], mess_error, "error");
                    }
                });
            }
        });
    }
}

var edit = {
    save: function (repairId, repairCode) {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-edit');

            form.validate({
                rules: {
                    object_type: {
                        required: true
                    },
                    object_id: {
                        required: true
                    },
                    object_status: {
                        maxlength: 191
                    },
                    staff_id: {
                        required: true
                    },
                    repair_date: {
                        required: true
                    },
                    repair_content: {
                        maxlength: 191
                    }
                },
                messages: {
                    object_type: {
                        required: json['Hãy chọn loại đối tượng']
                    },
                    object_id: {
                        required: json['Hãy chọn đối tượng']
                    },
                    object_status: {
                        maxlength: json['Tình trạng đối tượng tối đa 191 kí tự']
                    },
                    staff_id: {
                        required: json['Hãy chọn nhân viên đưa đi bảo dưỡng']
                    },
                    repair_date: {
                        required: json['Hãy chọn ngày đưa đi bảo dưỡng']
                    },
                    repair_content: {
                        maxlength: json['Nội dung bảo dưỡng tối đa 191 kí tự']
                    }
                },
            });

            if (!form.valid()) {
                return false;
            }

            var next = true;

            var imageBefore = [];
            var imageAfter = [];
            var arrayCost = [];

            //Lấy hình ảnh trc bảo trì
            $.each($('.image_before').find('input[name="img-before"]'), function () {
                imageBefore.push($(this).val());
            });
            //Lấy hình ảnh sau bảo trì
            $.each($('.image_after').find('input[name="img-after"]'), function () {
                imageAfter.push($(this).val());
            });
            //Lấy chi phí phát sinh
            $.each($('#table-maintenance-cost').find(".tr_code"), function () {
                var maintenanceCostType = $(this).find($('.maintenance_cost_type')).val();
                var cost = $(this).find($('.cost')).val();
                var number = $(this).find($('.number')).val();

                if (maintenanceCostType == '') {
                    $('.error_cost_type_' + number + '').text(json['Hãy chọn loại chi phí']);
                    next = false;
                } else {
                    $('.error_cost_type_' + number + '').text('');
                }

                if (cost == '') {
                    $('.error_cost_' + number + '').text(json['Hãy nhập chi phí phát sinh']);
                    next = false;
                } else {
                    $('.error_cost_' + number + '').text('');
                }

                arrayCost.push({
                    costType: maintenanceCostType,
                    cost: cost.replace(new RegExp('\\,', 'g'), '')
                });
            });

            if (next == true) {
                $.ajax({
                    url: laroute.route('repair.update'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        repair_id: repairId,
                        repair_code: repairCode,
                        object_type: $('#object_type').val(),
                        object_id: $('#object_id').val(),
                        object_code: $('#object_code').val(),
                        object_status: $('#object_status').val(),
                        staff_id: $('#staff_id').val(),
                        repair_date: $('#repair_date').val(),
                        repair_cost: Number($('#repair_cost').val().replace(new RegExp('\\,', 'g'), '')),
                        insurance_pay: Number($('#insurance_pay').val().replace(new RegExp('\\,', 'g'), '')),
                        repair_content: $('#repair_content').val(),
                        amount_pay: Number($('#amount_pay').val().replace(new RegExp('\\,', 'g'), '')),
                        total_amount_pay: Number($('#total_amount_pay').val().replace(new RegExp('\\,', 'g'), '')),
                        imageBefore: imageBefore,
                        imageAfter: imageAfter,
                        arrayCost: arrayCost,
                        status: $('#status').val()
                    },
                    success: function (res) {
                        if (res.error == false) {
                            swal(res.message, "", "success").then(function (result) {
                                if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                    window.location.href = laroute.route('repair');
                                }
                                if (result.value == true) {
                                    window.location.href = laroute.route('repair');
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
                        swal(json['Chỉnh sửa thất bại'], mess_error, "error");
                    }
                });
            }
        });
    }
}
// phiếu chi
var payment = {
    modalPayment: function (repairId) {
        $.getJSON(laroute.route('translate'), function (json) {
            $.ajax({
                url: laroute.route('repair.modal-payment'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    repair_id: repairId
                },
                success: function (res) {
                    console.log(res);
                    $('#my-modal').html(res.html);
                    $('#my-modal').find('#modal-receipt').modal({
                        backdrop: 'static', keyboard: false
                    });
                    $('#payment_method').select2({
                        placeholder: json['Chọn loại hình thức thanh toán']
                    });
                    $('#payment_type').select2({
                        placeholder: json['Chọn loại phiếu chi']
                    });
                    new AutoNumeric.multiple('#money', {
                        currencySymbol: '',
                        decimalCharacter: '.',
                        digitGroupSeparator: ',',
                        decimalPlaces: decimal_number,
                        eventIsCancelable: true,
                        minimumValue: 0
                    });
                }
            });
        });
    },
    submitPayment: function (repairId) {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-payment');
            form.validate({
                rules: {
                    payment_type: {
                        required: true
                    },
                    money: {
                        required: true
                    },
                    payment_method: {
                        required: true
                    },
                    // note: {
                    //     required: true,
                    //     maxLength: 191
                    // }
                },
                messages: {
                    payment_type: {
                        required: json['Hãy chọn loại phiếu chi']
                    },
                    money: {
                        required: json['Hãy nhập số tiền']
                    },
                    payment_method: {
                        required: json['Hãy chọn phương thức chi']
                    },
                    // note: {
                    //     required: json['Hãy nhập nội dung'],
                    //     maxLength: json['Nội dung tối đa 191 ký tự']
                    // }
                },
            });

            if (!form.valid()) {
                return false;
            }
            $.ajax({
                url: laroute.route('repair.submit-payment'),
                method: 'POST',
                data: {
                    repair_id: repairId,
                    staff_id: $('#staff_id').val(),
                    payment_type: $('#payment_type option:selected').val(),
                    document_code: $('#document_code').val(),
                    money: $('#money').val(),
                    payment_method: $('#payment_method option:selected').val(),
                    note: $('#note').val(),
                },
                dataType: "JSON",
                success: function (res) {
                    if (res.error == false) {
                        swal(res.message, "", "success").then(function (result) {
                            if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                window.location.href = laroute.route('repair');
                            }
                            if (result.value == true) {
                                window.location.href = laroute.route('repair');
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
                    swal(json['Thất bại'], mess_error, "error");
                }
            })
        });
    }
}

function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
}