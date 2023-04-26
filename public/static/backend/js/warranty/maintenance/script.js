var stt = 0;

var view = {
    _init: function () {
        $(document).ready(function () {
            $.getJSON(laroute.route('translate'), function (json) {
                $('#staff_id').select2({
                    placeholder: json['Chọn nhân viên thực hiện']
                });

                $("#date_estimate_delivery").datetimepicker({
                    todayHighlight: !0,
                    autoclose: !0,
                    format: "dd/mm/yyyy hh:ii"
                });

                $('#object_type').select2({
                    placeholder: json['Chọn loại đối tượng']
                });

                $('#customer_code').select2({
                    placeholder: json['Chọn khách hàng']
                });

                new AutoNumeric.multiple('#insurance_pay, #maintenance_cost', {
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
    modalWarrantyCard: function () {
        $.ajax({
            url: laroute.route('maintenance.modal-warranty'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                customer_code: $('#customer_code').val(),
                object_type: $('#object_type').val(),
                object_type_id: $('#object_type_id').val()
            },
            success: function (res) {
                $('#my-modal').html(res.html);
                $('#my-modal').find('#modal-warranty').modal({
                    backdrop: 'static', keyboard: false
                });

                $('#customer_code_search').val($('#customer_code').val());
                $('#object_type_search').val($('#object_type').val());
                $('#object_id_search').val($('#object_type_id').val());

                $('#autotable-warranty').PioTable({
                    baseUrl: laroute.route('maintenance.list-warranty')
                });
                $('.btn-search').trigger('click');
            }
        });
    },
    closeModalWarrantyCard: function () {
        $.ajax({
            url: laroute.route('maintenance.close-modal-warranty'),
            method: 'POST',
            dataType: 'JSON',
            data: {}
        });

        $('#modal-warranty').modal('hide');
    },
    chooseWarrantyCard: function (obj, warrantyCardCode = null) {
        var isCheck = 0;

        var warranty_card_code = warrantyCardCode != null ? warrantyCardCode : $(obj).closest('tr').find('.warranty_code').val();

        if ($(obj).is(':checked') || warranty_card_code != null) {
            isCheck = 1;
        }

        $.ajax({
            method: 'POST',
            dataType: 'JSON',
            url: laroute.route('maintenance.choose-warranty'),
            data: {
                warranty_code: warranty_card_code,
                isCheck: isCheck
            },
            success: function (res) {

            }
        });
    },
    submitChooseWarranty: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            $.ajax({
                url: laroute.route('maintenance.submit-choose-warranty'),
                method: 'POST',
                dataType: 'JSON',
                data: {},
                success: function (res) {
                    if (res.info != null) {
                        $('.div_warranty_code').css('display', 'block');
                        $('#warranty_code').val(res.info.warranty_card_code);
                        $('#warranty_value').val(res.warrantyValue);
                        $('#object_serial').val(res.info.object_serial);
                        $('#object_code').val(res.info.object_code);
                        //Load đối tượng được khuyến mãi
                        $('#object_type_hidden').val(res.info.object_type);
                        $('#object_type').val(res.info.object_type).trigger('change');

                        view.loadObject(json);

                        $('#object_type_id').append('<option value="' + res.info.object_type_id + '" selected>' + res.info.object_name + '</option>');
                    }

                    $('#modal-warranty').modal('hide');
                    view.loadAmountPay();
                }
            });
        });
    },
    chooseCustomer: function (obj) {
        $.ajax({
            url: laroute.route('maintenance.clear-session'),
            method: 'POST',
            dataType: 'JSON'
        });

        $('.div_choose_warranty_code').css('display', 'block');
        $('.div_warranty_code').css('display', 'none');

        $('#warranty_code').val('');
        $('#warranty_value').val('');
        $('#object_serial').val('');
        $('#object_type_id').val('').trigger('change');
        $('#object_code').val('');
        view.loadAmountPay();
    },
    changeType: function (obj) {
        var typeHidden = $('#object_type_hidden').val();
        var typeCurrent = $(obj).val();

        if (typeCurrent != typeHidden) {
            $('#warranty_code').val('');
            $('#warranty_value').val('');
            $('#object_serial').val('');
            $('#object_code').val('');
            $('#object_type_id').val('').trigger('change');
        }

        $('#object_type_hidden').val(typeCurrent);

        view.loadAmountPay();
    },
    loadObject: function (json) {
        $('#object_type_id').select2({
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
            $('#warranty_code').val('');
            $('#warranty_value').val('');
            $('#object_serial').val('');
            $('#object_code').val(event.params.data.code);
        }).on('select2:unselect', function (event) {
            $('#warranty_code').val('');
            $('#warranty_value').val('');
            $('#object_serial').val('');
            $('#object_code').val('');
            $('.div_warranty_code').css('display', 'none');

            view.loadAmountPay();
        });
    },
    loadAmountPay: function () {
        //Chi phí bảo trì
        var maintenanceCost = $('#maintenance_cost').val().replace(new RegExp('\\,', 'g'), '');
        //Bảo hiểm chi trả
        var insurancePay = $('#insurance_pay').val().replace(new RegExp('\\,', 'g'), '');
        //Giá trị bảo hành
        var warrantyValue = $('#warranty_value').val().replace(new RegExp('\\,', 'g'), '');
        //Tính chi phí phải trả
        var amountPay = Number(maintenanceCost) - Number(insurancePay) - Number(warrantyValue);

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
};

var create = {
    save: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-register');

            form.validate({
                rules: {
                    customer_code: {
                        required: true
                    },
                    object_type: {
                        required: true
                    },
                    object_type_id: {
                        required: true
                    },
                    object_status: {
                        maxlength: 191
                    },
                    staff_id: {
                        required: true
                    },
                    date_estimate_delivery: {
                        required: true
                    }
                },
                messages: {
                    customer_code: {
                        required: json['Hãy chọn khách hàng']
                    },
                    object_type: {
                        required: json['Hãy chọn loại đối tượng']
                    },
                    object_type_id: {
                        required: json['Hãy chọn đối tượng']
                    },
                    object_status: {
                        maxlength: json['Tình trạng đối tượng tối đa 191 kí tự']
                    },
                    staff_id: {
                        required: json['Hãy chọn nhân viên thực hiện']
                    },
                    date_estimate_delivery: {
                        required: json['Hãy chọn ngày trả hàng dự kiến']
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
                    url: laroute.route('maintenance.store'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        customer_code: $('#customer_code').val(),
                        warranty_code: $('#warranty_code').val(),
                        warranty_value: Number($('#warranty_value').val().replace(new RegExp('\\,', 'g'), '')),
                        object_serial: $('#object_serial').val(),
                        object_type: $('#object_type').val(),
                        object_type_id: $('#object_type_id').val(),
                        object_code: $('#object_code').val(),
                        object_status: $('#object_status').val(),
                        staff_id: $('#staff_id').val(),
                        date_estimate_delivery: $('#date_estimate_delivery').val(),
                        maintenance_cost: Number($('#maintenance_cost').val().replace(new RegExp('\\,', 'g'), '')),
                        insurance_pay: Number($('#insurance_pay').val().replace(new RegExp('\\,', 'g'), '')),
                        maintenance_content: $('#maintenance_content').val(),
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
                                    window.location.href = laroute.route('maintenance');
                                }
                                if (result.value == true) {
                                    window.location.href = laroute.route('maintenance');
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
};

var edit = {
    save: function (maintenanceId, maintenanceCode) {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-edit');

            form.validate({
                rules: {
                    customer_code: {
                        required: true
                    },
                    object_type: {
                        required: true
                    },
                    object_type_id: {
                        required: true
                    },
                    object_status: {
                        maxlength: 191
                    },
                    staff_id: {
                        required: true
                    },
                    date_estimate_delivery: {
                        required: true
                    }
                },
                messages: {
                    customer_code: {
                        required: json['Hãy chọn khách hàng']
                    },
                    object_type: {
                        required: json['Hãy chọn loại đối tượng']
                    },
                    object_type_id: {
                        required: json['Hãy chọn đối tượng']
                    },
                    object_status: {
                        maxlength: json['Tình trạng đối tượng tối đa 191 kí tự']
                    },
                    staff_id: {
                        required: json['Hãy chọn nhân viên thực hiện']
                    },
                    date_estimate_delivery: {
                        required: json['Hãy chọn ngày trả hàng dự kiến']
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
                    url: laroute.route('maintenance.update'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        maintenance_id: maintenanceId,
                        maintenance_code: maintenanceCode,
                        customer_code: $('#customer_code').val(),
                        warranty_code: $('#warranty_code').val(),
                        warranty_value: Number($('#warranty_value').val().replace(new RegExp('\\,', 'g'), '')),
                        object_serial: $('#object_serial').val(),
                        object_type: $('#object_type').val(),
                        object_type_id: $('#object_type_id').val(),
                        object_code: $('#object_code').val(),
                        object_status: $('#object_status').val(),
                        staff_id: $('#staff_id').val(),
                        date_estimate_delivery: $('#date_estimate_delivery').val(),
                        maintenance_cost: Number($('#maintenance_cost').val().replace(new RegExp('\\,', 'g'), '')),
                        insurance_pay: Number($('#insurance_pay').val().replace(new RegExp('\\,', 'g'), '')),
                        maintenance_content: $('#maintenance_content').val(),
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
                                    window.location.href = laroute.route('maintenance');
                                }
                                if (result.value == true) {
                                    window.location.href = laroute.route('maintenance');
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
};

var list = {
    _init: function () {
        $('#autotable').PioTable({
            baseUrl: laroute.route('maintenance.list')
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
    }
};

var receipt = {
    modalReceipt: function (maintenanceId) {
        $.getJSON(laroute.route('translate'), function (json) {
            $.ajax({
                url: laroute.route('maintenance.modal-receipt'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    maintenance_id: maintenanceId
                },
                success: function (res) {
                    $('#my-modal').html(res.html);
                    $('#my-modal').find('#modal-receipt').modal({
                        backdrop: 'static', keyboard: false
                    });
                    //Load sẵn hình thức thanh toán = tiền mặt
                    $('#receipt_type').val('CASH').trigger('change');

                    new AutoNumeric.multiple('#payment_method_CASH', {
                        currencySymbol: '',
                        decimalCharacter: '.',
                        digitGroupSeparator: ',',
                        decimalPlaces: decimal_number,
                        eventIsCancelable: true,
                        minimumValue: 0
                    });

                    if ($('#member_money').val() <= 0 || typeof $('#member_money').val() == 'undefined') {
                        $("#receipt_type option[value='MEMBER_MONEY']").remove();
                    } else {
                        $('#receipt_type').append('<option value="MEMBER_MONEY">' + json['Tài khoản thành viên'] + '</option>');
                    }

                    $('#receipt_type').select2({
                        placeholder: json['Chọn hình thức thanh toán']
                    }).on('select2:select', function (event) {
                        // Lấy id và tên của phương thức thanh toán
                        let methodId = event.params.data.id;
                        let methodName = event.params.data.text;
                        let tpl = $('#payment_method_tpl').html();
                        tpl = tpl.replace(/{label}/g, methodName);
                        tpl = tpl.replace(/{id}/g, methodId);
                        tpl = tpl.replace(/{id}/g, methodId);
                        if (methodId == 'MEMBER_MONEY') {
                            let money = $('#member_money').val();
                            tpl = tpl.replace(/{money}/g, json['(Còn '] + formatNumber(money) + ')');
                        } else {
                            tpl = tpl.replace(/{money}/g, '*');
                        }

                        if (methodId == 'VNPAY') {
                            tpl = tpl.replace(/{displayQrCode}/g, 'block');
                        } else {
                            tpl = tpl.replace(/{displayQrCode}/g, 'none');
                        }

                        $('.payment_method').append(tpl);
                        new AutoNumeric.multiple('#payment_method_' + methodId, {
                            currencySymbol: '',
                            decimalCharacter: '.',
                            digitGroupSeparator: ',',
                            decimalPlaces: decimal_number,
                            eventIsCancelable: true,
                            minimumValue: 0
                        });
                    }).on('select2:unselect', function (event) {
                        // UPDATE 15/03/2021
                        let moneyTobePaid = $('#receipt_amount').val().replace(new RegExp('\\,', 'g'), ''); // tiền phải thanh toán
                        let methodId = event.params.data.id;
                        let amountThis = $('#payment_method_' + methodId).val().replace(new RegExp('\\,', 'g'), '');
                        $('.payment_method_' + methodId).remove();
                        // tính lại tổng tiền trả (tổng tiền trả ban đầu - tiền unselect)
                        let amountAllOld = $('#amount_all').val().replace(new RegExp('\\,', 'g'), '');
                        let amountAllNew = amountAllOld - amountThis;
                        $('#amount_all').val(formatNumber(amountAllNew.toFixed(decimal_number)));
                        $('.cl_amount_all').text(formatNumber(amountAllNew.toFixed(decimal_number)));
                        // tính lại tiền nợ
                        if (moneyTobePaid - amountAllNew > 0) {
                            $('#amount_rest').val(formatNumber((moneyTobePaid - amountAllNew).toFixed(decimal_number)));
                            $('.cl_amount_rest').text(formatNumber((moneyTobePaid - amountAllNew).toFixed(decimal_number)));
                        } else {
                            $('#amount_rest').val(0);
                            $('.cl_amount_rest').text(0);
                        }
                        // tính lại tiền trả khách
                        if (amountAllNew - moneyTobePaid > 0) {
                            $('#amount_return').val(formatNumber((amountAllNew - moneyTobePaid).toFixed(decimal_number)));
                            $('.cl_amount_return').text(formatNumber((amountAllNew - moneyTobePaid).toFixed(decimal_number)));
                        } else {
                            $('#amount_return').val(0);
                            $('.cl_amount_return').text(0);
                        }
                        // END UPDATE 15/03/2021
                    });
                }
            });
        });
    },
    changeAmountReceipt: function (obj) {
        // UPDATE 15/03/2021
        // tính tổng tiền trả
        let total = 0
        $.each($('.payment_method').find('.method'), function () {
            let moneyEachMethod = $(this).find("input[name='payment_method']").val().replace(new RegExp('\\,', 'g'), '');
            total += Number(moneyEachMethod);
        });
        // END UPDATE 15/03/2021
        var amount_all = total;

        $('#amount_all').val(formatNumber(amount_all.toFixed(decimal_number)));
        $('.cl_amount_all').text(formatNumber(amount_all.toFixed(decimal_number)));

        var rest = $('#receipt_amount').val().replace(new RegExp('\\,', 'g'), '');
        if (rest - amount_all > 0) {
            $('#amount_rest').val(formatNumber((rest - amount_all).toFixed(decimal_number)));
            $('.cl_amount_rest').text(formatNumber((rest - amount_all).toFixed(decimal_number)));
            if ($(obj).val() != '') {
                if (rest - amount_all < 0) {
                    $('#amount_return').val(formatNumber((amount_all - rest).toFixed(decimal_number)));
                    $('.cl_amount_return').text(formatNumber((amount_all - rest).toFixed(decimal_number)));
                } else {
                    $('#amount_return').val(0);
                    $('.cl_amount_return').text(0);
                }
            }
        } else {
            if (rest - amount_all == 0) {
                $('#amount_rest').val(0);
                $('#amount_return').val(0);
                $('.cl_amount_rest').text(0);
                $('.cl_amount_return').text(0);
            } else {
                $('#amount_rest').val(0);
                $('#amount_return').val(formatNumber((amount_all - rest).toFixed(decimal_number)));
                $('.cl_amount_rest').text(0);
                $('.cl_amount_return').text(formatNumber((amount_all - rest).toFixed(decimal_number)));
            }
        }
    },
    submitReceipt: function (maintenanceId, customerId) {
        $.getJSON(laroute.route('translate'), function (json) {
            if ($('#receipt_type').val() == '') {
                $('.error_type').text(json['Hãy chọn hình thức thanh toán']);
                return false;
            } else {
                $('.error_type').text('');
            }
            let arrayMethod = {};
            $.each($('.payment_method').find('.method'), function () {
                let moneyEachMethod = $(this).find("input[name='payment_method']").val().replace(new RegExp('\\,', 'g'), '');
                let getId = $(this).find("input[name='payment_method']").attr('id');
                let methodCode = getId.slice(15);
                arrayMethod[methodCode] = moneyEachMethod;
            });
            $.ajax({
                url: laroute.route('maintenance.submit-receipt'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    maintenance_id: maintenanceId,
                    customer_id: customerId,
                    receipt_type: $('#receipt_type').val(),
                    // receipt_money: $('#amount_receipt_money').val().replace(new RegExp('\\,', 'g'), ''),
                    amount_all: $('#amount_all').val(),
                    array_method: arrayMethod,
                    amount_bill: $('#receipt_amount').val(),
                    amount_return: $('#amount_return').val(),
                    note: $('#note').val(),
                    receipt_id: $('#maintenance_receipt_id').val()
                },
                success: function (res) {
                    if (res.error == false) {
                        swal(res.message, "", "success").then(function (result) {
                            if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                $('#modal-receipt').modal('hide');

                                $('#autotable').PioTable('refresh');
                                $('.tab_detail').PioTable('refresh');
                            }
                            if (result.value == true) {
                                $('#modal-receipt').modal('hide');

                                $('#autotable').PioTable('refresh');
                                $('.tab_detail').PioTable('refresh');
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
                    swal(res.message, mess_error, "error");
                }
            });
        });
    },
    genQrCode: function (obj, methodCode) {
        $.ajax({
            url: laroute.route('maintenance.gen-qr-code'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                amount: $(obj).closest('.method').find('input[name="payment_method"]').val().replace(new RegExp('\\,', 'g'), ''),
                payment_method_code: methodCode,
                maintenance_id: $('#maintenance_id').val()
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        $('#maintenance_receipt_id').val(res.receipt_id);
                        window.open(res.url, '_blank');
                    });
                } else {
                    swal(res.message, '', "error");
                }
            }
        });
    }
};

function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
}