var x = "";
var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
for (let z = 0; z < 10; z++) {
    x += possible.charAt(Math.floor(Math.random() * possible.length));
}
var d = new Date();
var code = x + d.getFullYear() + d.getMonth() + d.getHours() + d.getMinutes();
var Acceptance = {
    clear: function() {
        clear();
    },
    refresh: function() {
        $('input[name="search_keyword"]').val('');
        $('.m_selectpicker').val('');
        $('.m_selectpicker').selectpicker('refresh');
        $(".btn-search").trigger("click");
    },
    search: function() {
        $(".btn-search").trigger("click");
    },
    configSearch: function() {
        $('#modal-config').modal();
    },
    saveConfig: function() {
        $.getJSON(laroute.route('ticket.translate'), function(json) {
            let search = $('.config_search [name="search[]"]:checked').map(function() {
                return this.value;
            }).get();
            let column = $('.config_column [name="column[]"]:checked').map(function() {
                return this.value;
            }).get();
            $.ajax({
                url: laroute.route('ticket.acceptance.save-config'),
                data: {
                    search: search,
                    column: column,
                },
                method: "POST",
                dataType: "JSON",
                success: function(data) {
                    if (data.status == 1) {
                        swal(
                            json.config_success,
                            '',
                            'success'
                        ).then(function() {
                            location.reload();
                        })
                    } else {
                        swal(
                            json.config_error,
                            '',
                            'warning'
                        );
                    }
                }
            });
        });
    },

    changeTicket: function(name = null, type = null) {
        var ticketId = $('#ticket_id').val();
        if (ticketId != '') {
            $.ajax({
                url: laroute.route('ticket.acceptance.change-ticket'),
                data: {
                    ticket_id: ticketId,
                    type: type
                },
                method: "POST",
                dataType: "JSON",
                success: function(res) {
                    if (res.error == false) {
                        $('#customer_id').val(res.customer_id);
                        $('#customer_full_name').val(res.customer_name);
                        if (name != null) {
                            $('#title').val(name);
                        } else {
                            $('#title').val(res.acceptance_title);
                        }

                        countFile = res.countFile;

                        $('.listFile').append(res.viewFile);

                        $('.listProductMaterial').empty();
                        $('.listProductMaterial').append(res.viewProduct);

                        // $('input').prop('disabled',true);
                        // $('button').prop('disabled',true);
                    }
                }
            });
        }
    },

    // Thay đổi số lượng thực tế
    changeNumber: function(ticketRequestMaterialDetailId, quantityApprove, type) {
        var number = $('#material_product_' + ticketRequestMaterialDetailId).val();

        if (!isNumber(number)) {
            number = '0';
        }

        if (type == 'sub') {
            number = parseFloat(number) - 1;
        }

        if (type == 'plus') {
            number = parseFloat(number) + 1;
        }

        // Kiểm tra số lượng thực tế lớn hơn số lượng duyệt
        if (number > quantityApprove) {
            number = quantityApprove;
        }

        // Kiểm tra số lượng thực tế nhỏ hơn 0
        if (number < 0) {
            number = 0;
        }

        if (Number.isInteger(number)) {
            var changeNumber = parseFloat(number).toFixed(0);
        } else {
            var changeNumber = parseFloat(number).toFixed(1);
        }
        // var changeNumber = parseFloat(number).toFixed(1);
        // var changeNumber = parseFloat(number).toFixed(0);
        $('#material_product_' + ticketRequestMaterialDetailId).val(changeNumber);

        var total = parseFloat(quantityApprove) - parseFloat(changeNumber);

        if (Number.isInteger(total)) {
            $('#quantity_return_' + ticketRequestMaterialDetailId).text(parseFloat(total).toFixed(0));
        } else {
            $('#quantity_return_' + ticketRequestMaterialDetailId).text(parseFloat(total).toFixed(1));
        }

    },

    // Thay đổi số lượng phát sinh
    changeNumberIncurred: function(numberRow, type) {
        var numberIncurred = $('#incurred_material_product_' + numberRow).val();

        if (!isNumber(numberIncurred)) {
            numberIncurred = '0';
        }

        if (type == 'sub') {
            numberIncurred = parseFloat(numberIncurred) - 1;
        }

        if (type == 'plus') {
            numberIncurred = parseFloat(numberIncurred) + 1;
        }

        // Kiểm tra số lượng thực tế nhỏ hơn 0
        if (numberIncurred < 0) {
            numberIncurred = 0;
        }

        if (Number.isInteger(numberIncurred)) {
            var changeNumber = parseFloat(numberIncurred).toFixed(0);
        } else {
            var changeNumber = parseFloat(numberIncurred).toFixed(1);
        }

        $('#incurred_material_product_' + numberRow).val(changeNumber);

    },

    // Show popup thêm vật tư phát sinh
    showPopupAdd: function() {
        $.ajax({
            url: laroute.route('ticket.acceptance.show-popup-add-product'),
            data: {
                arrProductIdNot: objIdProduct
            },
            method: "POST",
            dataType: "JSON",
            success: function(res) {
                if (res.error == false) {
                    objIdProductSelect = {};
                    $('#appendpopupAdd').empty();
                    $('#appendpopupAdd').append(res.view);
                    $('.select2-popup').select2();
                    $('#appendModelAdd').modal('show');
                }
            }
        });
    },

    // Thêm vật tư phát sinh từ popup
    addProductIncurred: function() {

        $('.listProductMaterialIncurredPopupNone').remove();

        count++;

        let tpl = $('#productIncurred').html();
        tpl = tpl.replace(/{number}/g, count);
        tpl = tpl.replace(/{numberShow}/g, $('.listProductMaterialIncurredPopup tr').length + 1);

        $('.listProductMaterialIncurredPopup').append(tpl);
        new AutoNumeric.multiple('#money_' + count, {
            // currencySymbol: '',
            // decimalCharacter: '',
            // digitGroupSeparator: ',',
            // decimalPlaces: 0,
            // eventIsCancelable: true,
            minimumValue: 0
        });

    },

    // Xoá dòng sản phẩm phát sinh
    deleteRowIncurred: function(number) {

        if ($('#incurred_block_product_' + number + ' .product_id').val() != '') {
            var id = $('#incurred_block_product_' + number + ' .product_id').val();
            delete objIdProductSelect[id];
            // Cập nhật danh sách vật tư
            $.ajax({
                url: laroute.route('ticket.acceptance.list-product-select'),
                data: {
                    arrProductIdNot: objIdProduct
                },
                method: "POST",
                dataType: "JSON",
                success: function(res) {
                    if (res.error == false) {
                        $('.select2-product-incurred').empty();
                        $('.select2-product-incurred').append(res.view);
                        $('.select2-product-incurred').select2();
                    }
                }
            });
        }

        // Xoá hàng
        $('#incurred_block_product_' + number).remove();
        // Cập nhật lại số thứ tự
        var n = 1;
        $.each($('.listProductMaterialIncurredPopup').find('.incurred_block_product'), function() {
            $(this).find('.col_1').text(n);
            n++;
        });
    },

    // Lưu các sản phẩm đã chọn
    saveProductIncurred: function() {
        $.getJSON(laroute.route('ticket.translate'), function(json) {
            var message = '';
            var n = 0;
            $.each($('.listProductMaterialIncurredPopup').find('.incurred_block_product'), function() {
                n++;
                var product_id = $(this).find('.product_id').val();
                var product_code = $(this).find('.product_code').val();
                var product_name = $(this).find('.product_name').val();
                var product_quantity = $(this).find('.product_quantity').val();
                var product_unit = $(this).find('.product_unit').val();
                var product_money = $(this).find('.product_money').val();

                // if (product_code == ''){
                //     message = message+ json.incurred + n + json.request_code +'<br>';
                // } else {
                //     if (product_code.length > 255){
                //         message = message+ json.incurred + n + json.length_code +'<br>';
                //     }
                // }

                if (product_name == '') {
                    message = message + json.incurred + n + json.request_name + '<br>';
                } else {
                    if (product_name.length > 255) {
                        message = message + json.incurred + n + json.length_name + '<br>';
                    }
                }

                if (product_unit == '') {
                    message = message + json.incurred + n + json.request_unit + '<br>';
                } else {
                    if (product_unit.length > 255) {
                        message = message + json.incurred + n + json.length_unit + '<br>';
                    }
                }
                if (product_money == '' || product_money == 0) {
                    message = message + json.incurred + n + ' thành tiền không được để trống' + '<br>';
                } else {

                }

                if (message != '') {
                    swal(
                        message, '', 'error'
                    );
                } else {
                    obj[product_name] = {
                        product_id: product_id,
                        product_code: product_code,
                        product_name: product_name,
                        product_quantity: product_quantity,
                        product_unit: product_unit,
                        product_money: product_money
                    };
                }
            });

            // Acceptance.addIncurredEdit();
            if (obj.length != 0) {
                $.ajax({
                    url: laroute.route('ticket.acceptance.add-product-incurred-list'),
                    data: obj,
                    method: "POST",
                    dataType: "JSON",
                    success: function(res) {
                        if (res.error == false) {
                            $('.listProductMaterialIncurred').empty();
                            $('.listProductMaterialIncurred').append(res.view);
                            if (message == '') {
                                $('#appendModelAdd').modal('hide');
                            }
                        }
                    }
                });
            }
            console.log(objIdProductSelect);
            objIdProduct = $.extend(objIdProduct, objIdProductSelect);
            console.log(objIdProduct);
        });
    },

    addIncurredEdit: function() {
        if (obj.length != 0) {
            $.ajax({
                url: laroute.route('ticket.acceptance.add-product-incurred-list'),
                data: obj,
                method: "POST",
                dataType: "JSON",
                success: function(res) {
                    if (res.error == false) {
                        $('.listProductMaterialIncurred').empty();
                        $('.listProductMaterialIncurred').append(res.view);
                        $('#appendModelAdd').modal('hide');
                    }
                }
            });
        }
    },

    // Xoá vật tư phát sinh
    deleteRowIncurredMain: function(code, id) {
        delete obj[code];
        console.log(objIdProduct[id], obj);
        if (id != '') {
            delete objIdProduct[id];
        }
        $.ajax({
            url: laroute.route('ticket.acceptance.add-product-incurred-list'),
            data: obj,
            method: "POST",
            dataType: "JSON",
            success: function(res) {
                if (res.error == false) {
                    $('.listProductMaterialIncurred').empty();
                    $('.listProductMaterialIncurred').append(res.view);
                    $('#appendModelAdd').modal('hide');

                }
            }
        });
    },

    // Chọn product
    changeProductSelect: function() {
        var id = $('.select2-product-incurred').find(':selected').val();
        var code = $('.select2-product-incurred').find(':selected').attr('data-code');
        var name = $('.select2-product-incurred').find(':selected').attr('data-name');
        var unit = $('.select2-product-incurred').find(':selected').attr('data-unit');
        var money = $('.select2-product-incurred').find(':selected').attr('data-money');
        objIdProductSelect[id] = { id };
        count++;
        $('.listProductMaterialIncurredPopupNone').remove();
        let tpl = $('#productIncurredSelect').html();
        tpl = tpl.replace(/{number}/g, count);
        tpl = tpl.replace(/{product_id}/g, id);
        tpl = tpl.replace(/{product_code}/g, code);
        tpl = tpl.replace(/{product_name}/g, name);
        tpl = tpl.replace(/{product_unit}/g, unit);
        tpl = tpl.replace(/{product_money}/g, money);
        tpl = tpl.replace(/{numberShow}/g, $('.listProductMaterialIncurredPopup tr').length + 1);

        $('.select2-product-incurred').find(':selected').remove();
        $('.select2-product-incurred').select2();

        $('.listProductMaterialIncurredPopup').append(tpl);
        new AutoNumeric.multiple('#money_' + count, {
            currencySymbol: '',
            decimalCharacter: '.',
            digitGroupSeparator: ',',
            decimalPlaces: 0,
            eventIsCancelable: true,
            minimumValue: 0
        });
    },

    // Lưu biên bản nghiệm thu
    saveAcceptance: function() {
        $.getJSON(laroute.route('ticket.translate'), function(json) {
            $('#form-acceptance').validate({
                rules: {
                    ticket_id: {
                        required: true
                    },
                    title: {
                        required: true,
                        maxlength: 255
                    },
                },
                messages: {
                    ticket_id: {
                        required: json.select_ticket
                    },
                    title: {
                        required: json.request_acceptance,
                        maxlength: json.length_acceptance,
                    },
                },
            });

            if (!$('#form-acceptance').valid()) {
                return false;
            } else {
                $.ajax({
                    url: laroute.route('ticket.acceptance.create-acceptance'),
                    data: $('#form-acceptance').serialize(),
                    method: "POST",
                    dataType: "JSON",
                    success: function(res) {
                        if (res.error == false) {
                            swal(
                                res.message,
                                '',
                                'success'
                            ).then(function() {
                                window.location.href = laroute.route('ticket.acceptance');
                            });
                        } else {
                            swal(
                                res.message,
                                '',
                                'error'
                            );
                        }
                    }
                });
            }
        });
    },
    // Chỉnh sửa biên bản nghiệm thu
    saveAcceptanceEdit: function() {
        $.getJSON(laroute.route('ticket.translate'), function(json) {
            $('#form-acceptance').validate({
                rules: {
                    ticket_id: {
                        required: true
                    },
                    title: {
                        required: true,
                        maxlength: 255
                    },
                    sign_by: {
                        maxlength: 255
                    }
                },
                messages: {
                    ticket_id: {
                        required: json.select_ticket
                    },
                    title: {
                        required: json.request_acceptance,
                        maxlength: json.length_acceptance,
                    },
                    sign_by: {
                        maxlength: json.length_sign_by,
                    }
                },
            });

            if (!$('#form-acceptance').valid()) {
                return false;
            } else {
                $.ajax({
                    url: laroute.route('ticket.acceptance.edit-acceptance'),
                    data: $('#form-acceptance').serialize(),
                    method: "POST",
                    dataType: "JSON",
                    success: function(res) {
                        if (res.error == false) {
                            swal(
                                res.message,
                                '',
                                'success'
                            ).then(function() {
                                window.location.href = laroute.route('ticket.acceptance');
                            });
                        } else {
                            swal(
                                res.message,
                                '',
                                'error'
                            );
                        }
                    }
                });
            }
        });
    },
    removeFile: function(number) {
        swal({
            title: 'Thông báo',
            text: "Bạn có muốn xóa không?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy',
            onClose: function() {
                $(obj).closest('tr').removeClass('m-table__row--danger');
            }
        }).then(function(result) {
            if (result.value) {
                $('.block-file-' + number).remove();
            }
        });
    }
};

function isNumber(num) {
    return !isNaN(parseFloat(num)) && isFinite(num);
}

$('#autotable').PioTable({
    baseUrl: laroute.route('ticket.acceptance.list')
});

$('.m_selectpicker').selectpicker();
$('select[name="is_actived"]').select2();

var arrRange = {};
arrRange[lang['Hôm nay']] = [moment(), moment()],
    arrRange[lang['Hôm qua']] = [moment().subtract(1, "days"), moment().subtract(1, "days")],
    arrRange[lang["7 ngày trước"]] = [moment().subtract(6, "days"), moment()],
    arrRange[lang["30 ngày trước"]] = [moment().subtract(29, "days"), moment()],
    arrRange[lang["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")],
    arrRange[lang["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
$(".daterange-picker").daterangepicker({
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
        "applyLabel": lang["Đồng ý"],
        "cancelLabel": lang["Thoát"],
        "customRangeLabel": lang["Tùy chọn ngày"],
        daysOfWeek: [
            lang["CN"],
            lang["T2"],
            lang["T3"],
            lang["T4"],
            lang["T5"],
            lang["T6"],
            lang["T7"]
        ],
        "monthNames": [
            lang["Tháng 1 năm"],
            lang["Tháng 2 năm"],
            lang["Tháng 3 năm"],
            lang["Tháng 4 năm"],
            lang["Tháng 5 năm"],
            lang["Tháng 6 năm"],
            lang["Tháng 7 năm"],
            lang["Tháng 8 năm"],
            lang["Tháng 9 năm"],
            lang["Tháng 10 năm"],
            lang["Tháng 11 năm"],
            lang["Tháng 12 năm"]
        ],
        "firstDay": 1
    },
    ranges: arrRange
}).on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'))
});

function uploadImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#getFile').attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
        var file_data = $('#getFile').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('link', '_acceptance.');

        var fsize = input.files[0].size;

        var fileInput = input,
            file = fileInput.files && fileInput.files[0];
        var img = new Image();

        img.src = window.URL.createObjectURL(file);

        img.onload = function() {
            var imageWidth = img.naturalWidth;
            var imageHeight = img.naturalHeight;

            window.URL.revokeObjectURL(img.src);

            $('.image-size').text(imageWidth + "x" + imageHeight + "px");

        };
        $('.image-capacity').text(Math.round(fsize / 1024) + 'kb');
        $('.image-format').text(input.files[0].name.split('.').pop().toUpperCase());
        console.log(countFile);
        if (Math.round(fsize / 1024) < 10241) {
            $.ajax({
                url: laroute.route("ticket.upload-file"),
                method: "POST",
                data: form_data,
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    let tpl = $('#addFile').html();
                    tpl = tpl.replace(/{countFile}/g, countFile);
                    tpl = tpl.replace(/{link}/g, data.file);
                    $('.listFile').append(tpl);
                    countFile++;
                }
            });
        } else {
            $('.max-size').addClass('text-danger');
        }
    }
}