var x = "";
var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
for (let z = 0; z < 10; z++) {
    x += possible.charAt(Math.floor(Math.random() * possible.length));
}
var d = new Date()
var code = x + d.getFullYear() + d.getMonth() + d.getHours() + d.getMinutes();
var Material = {
    remove: function(obj, id) {
        $(obj).closest('tr').addClass('m-table__row--danger');
        $.getJSON(laroute.route('translate'), function(json) {
            swal({
                title: json['Thông báo'],
                text: json["Bạn có muốn xóa không?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json['Xóa'],
                cancelButtonText: json['Hủy'],
                onClose: function() {
                    $(obj).closest('tr').removeClass('m-table__row--danger');
                }
            }).then(function(result) {
                if (result.value) {
                    $.post(laroute.route('ticket.material.remove', { id: id }), function() {
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
    changeStatus: function(obj, id, action) {
        $.post(laroute.route('ticket.material.change-status'), { id: id, action: action }, function(data) {
            $('#autotable').PioTable('refresh');
        }, 'JSON');
    },
    addClose: function() {
        $('#form-add-material').validate({
            rules: {
                ticket_code: {
                    required: true,
                },
                import_file: {
                    required: false,
                    accept: false
                },
                description: {
                    required: true,
                    maxlength: 191,
                    minlength: 1
                },
            },
            messages: {
                ticket_code: {
                    required: lang['Mã ticket là trường bắt buộc nhập'],
                },
                description: {
                    required: lang['Mô tả là trường bắt buộc nhập'],
                    maxlength: lang['Mô tả không quá 191 ký tự'],
                }
            },
            submitHandler: function() {
                $.ajax({
                    url: laroute.route('ticket.material.add'),
                    data: $("#form-add-material").serialize(),
                    method: "POST",
                    dataType: "JSON",
                    success: function(data) {
                        if (data.status == 1) {
                            swal(
                                lang['Thêm vật tư thành công'],
                                '',
                                'success'
                            );
                            $('#autotable').PioTable('refresh');
                            clear();
                            $('#modalAdd').modal('hide');
                        } else if (data.status == 2) {
                            swal(
                                lang['Vui lòng chọn vật tư đề xuất'],
                                '',
                                'warning'
                            );
                        }
                    }
                });
                return false;
            }
        });

    },
    edit: function(id) {
        $('#form-edit-material table tbody').html('');
        $.ajax({
            url: laroute.route('ticket.material.edit'),
            data: {
                ticket_request_material_id: id
            },
            method: "POST",
            dataType: 'JSON',
            success: function(data) {
                $('#modalEdit').modal('show');
                $('#modalEdit [name="ticket_request_material_id"]').val(data.ticket_request_material_id);
                $('#modalEdit [name="ticket_code"]').val(data.ticket_id).trigger('change');
                $('#modalEdit [name="description"]').val(data.description);
                $('#modalEdit [name="proposer_by"]').val(data.proposer_by);
                $('#modalEdit [name="proposer_date"]').val(data.proposer_date);
                $('#modalEdit [name="status_material"]').val(data.status).trigger('change');
                if (data['material_detail'].length) {
                    $.each(data.material_detail, function(index, val) {
                        var tr_id = $('#form-edit-material .table-add-material tbody tr').length;
                        tr_id += 1;
                        var edit_btn = '<button type="button" class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill replace-material-btn"><i class="la la-edit"></i><input type="hidden" name="ticket_request_material_detail_id[' + val.product_id + ']" value="' + val.ticket_request_material_detail_id + '"> </button>';
                        var input_counter = $('#input-counter').html();
                        input_counter = input_counter.replace(/{max}/g, val.quantity);
                        input_counter = input_counter.replace(/{value}/g, val.quantity_approve);
                        input_counter = input_counter.replace(/{product_id}/g, val.product_id);
                        var select_status = $('#select-status').html();
                        var name_status = 'status[' + val.product_id + ']';
                        select_status = select_status.replaceAll(/{product_id}/g, name_status);

                        var warehouse = '<input type="hidden" class="warehouse_' + val.warehouse_id + '_' + val.product_id + '" name="warehouse[' + val.product_id + ']" value="' + val.warehouse_id + '">';
                        var quantity = val.quantity_max + warehouse;
                        if (val.status == 'approve') {
                            input_counter = val.quantity_approve;
                            select_status = '';
                            edit_btn = '';
                        }
                        var tr = [tr_id, val.product_code, val.product_name, val.quantity, quantity, input_counter, val.unitName, (select_status + edit_btn)];
                        $('#form-edit-material .table-add-material tbody').append(generate_row_material(tr));
                        $('select[name="status[' + val.product_id + ']"]').val(val.status).change();
                        $('#modalEdit select[name^="status"]').addClass('d-none');
                    });
                }

            }
        })
    },
    submitEdit: function() {
        $('#form-edit-material').validate({
            rules: {
                ticket_code: {
                    required: true,
                },
                import_file: {
                    required: false,
                    accept: false
                },
                description: {
                    required: true,
                    maxlength: 191,
                    minlength: 1
                },
            },
            messages: {
                ticket_code: {
                    required: lang['Mã ticket là trường bắt buộc nhập'],
                },
                description: {
                    required: lang['Mô tả là trường bắt buộc nhập'],
                    maxlength: lang['Mô tả không quá 191 ký tự'],
                }
            },
            submitHandler: function() {
                $('#form-edit-material [name="ticket_code"],#form-edit-material [name="ticket_code"]', '#form-edit-material [name=description]').attr('disabled', false);
                $.ajax({
                    url: laroute.route('ticket.material.submit-approved'),
                    data: $("#form-edit-material").serialize(),
                    method: "POST",
                    dataType: "JSON",
                    success: function(data) {
                        if (data.status == 1) {
                            swal(
                                lang['Cập nhật vật tư thành công'],
                                '',
                                'success'
                            );
                            $('#autotable').PioTable('refresh');
                            clear();
                            $('#modalEdit').modal('hide');
                        } else if (data.status == 2) {
                            swal(
                                lang['Vui lòng chọn vật tư đề xuất'],
                                '',
                                'warning'
                            );
                        }
                    }
                });
                $('#form-edit-material [name="ticket_code"],#form-edit-material [name="ticket_code"]', '#form-edit-material [name=description]').attr('disabled', true);
                return false;
            }
        });
    },
    view: function(id) {
        clear();
        $.ajax({
            url: laroute.route('ticket.material.edit'),
            data: {
                ticket_request_material_id: id
            },
            method: "POST",
            dataType: 'JSON',
            success: function(data) {
                $('#modalView').modal('show');
                $('#modalView [name="ticket_request_material_id"]').val(data.ticket_request_material_id);
                $('#modalView [name="ticket_code"]').val(data.ticket_id).trigger('change');
                $('#modalView [name="description"]').val(data.description);
                $('#modalView [name="proposer_by"]').val(data.proposer_by);
                $('#modalView [name="proposer_date"]').val(data.proposer_date);
                $('#modalView [name="status_material"]').val(data.status).trigger('change');
                if (data['material_detail'].length) {
                    $.each(data.material_detail, function(index, val) {
                        var tr_id = $('#form-view-material .table-add-material tbody tr').length;
                        tr_id += 1;
                        var edit_btn = '<button type="button" class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill replace-material-btn"><i class="la la-edit"></i><input type="hidden" name="ticket_request_material_detail_id[' + val.product_id + ']" value="' + val.ticket_request_material_detail_id + '"> </button>';
                        var input_counter = $('#input-counter').html();
                        input_counter = input_counter.replace(/{max}/g, val.quantity);
                        input_counter = input_counter.replace(/{value}/g, val.quantity_approve);
                        input_counter = input_counter.replace(/{product_id}/g, val.product_id);
                        var select_status = $('#select-status').html();
                        var name_status = 'status[' + val.product_id + ']';
                        select_status = select_status.replaceAll(/{product_id}/g, name_status);
                        if (data.status == 'new' && val.status !== 'new') {
                            $('.button-action').removeClass('d-none');
                        }
                        var tr = [tr_id, val.product_code, val.product_name, val.quantity, val.quantity_max, input_counter, val.unitName];
                        $('#form-view-material .table-add-material tbody').append(generate_row_material(tr));
                        $('select[name="status[' + val.product_id + ']"]').val(val.status).change();
                    });
                    $('#form-view-material input', '#form-view-material select', '#form-view-material textarea').attr('disabled', true);
                    $('#modalView select[name^="status"]').addClass('d-none');
                }

            }
        })
    },
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
        let search = $('.config_search [name="search[]"]:checked').map(function() {
            return this.value;
        }).get();
        let column = $('.config_column [name="column[]"]:checked').map(function() {
            return this.value;
        }).get();
        $.ajax({
            url: laroute.route('ticket.material.save-config'),
            data: {
                search: search,
                column: column,
            },
            method: "POST",
            dataType: "JSON",
            success: function(data) {
                console.log(data.data);
                if (data.status == 1) {
                    swal(
                        lang['Cấu hình thành công'],
                        '',
                        'success'
                    );
                    location.reload();
                } else {
                    swal(
                        lang['Cấu hình thất bại'],
                        '',
                        'warning'
                    );
                }
            }
        });
    },
    upload: function() {
        var formData = new FormData();
        formData.append('import_file', $('#file-import')[0].files[0]);
        $.ajax({
            url: laroute.route('ticket.material.parserExcel'),
            data: formData,
            method: "POST",
            dataType: "JSON",
            contentType: false,
            processData: false,
            success: function(data) {
                console.log(data.data);
                if (data.status == 1) {
                    swal(
                        lang['Import thành công'],
                        '',
                        'success'
                    );
                    $.each(data.data, function(index, val) {
                        var tr_id = $('#form-add-material .table-add-material tbody tr').length;
                        tr_id += 1;
                        var remove_btn = '<button class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill remove-btn"><i class="la la-trash"></i></button>';
                        var input_counter = $('#input-counter').html();
                        input_counter = input_counter.replace(/{max}/g, val.quantity);
                        input_counter = input_counter.replace(/{value}/g, val.quantity_current);
                        input_counter = input_counter.replace(/{product_id}/g, val.product_id);
                        var warehouse = '<input type="hidden" class="warehouse_' + val.warehouse_id + '_' + val.product_id + '" name="warehouse[' + val.product_id + ']" value="' + val.warehouse_id + '">';
                        var quantity = val.quantity + warehouse;
                        var tr = [tr_id, val.product_code, val.product_name, input_counter, quantity, val.unitName, remove_btn];
                        $('#form-add-material .table-add-material tbody').append(generate_row_material(tr));
                        $('#form-edit-material .table-add-material tbody').append(generate_row_material(tr));
                    });
                } else {
                    swal(
                        lang['Import thất bại'],
                        '',
                        'warning'
                    );
                }
            }
        });
        // }

    }
};
// xử lý nút thêm vật tư
$('#form-add-material [name=material]').change(function() {
    var product_id = $(this).val();
    var warehouse_id = $('#form-add-material [name=warehouse_id]').val();
    if (!product_id) {
        return;
    }
    var check_warehouse_id = $(this).closest('body').find('.table-add-material tbody tr .warehouse_' + warehouse_id + '_' + product_id).length;
    if ($(this).closest('body').find('.table-add-material tbody tr [name="' + product_id + '"]').length && check_warehouse_id) {
        swal(
            lang['Đã có vật tư này'],
            '',
            'warning'
        );
        return;
    }
    var form_id = '#' + $(this).closest('form').attr('id') + ' ';
    var tr_id = $(form_id + '.table-add-material tbody tr').length;
    tr_id += 1;
    $.ajax({
        url: laroute.route('ticket.material.get-item-material'),
        data: {
            product_id: product_id,
            warehouse_id: warehouse_id,
        },
        method: "POST",
        dataType: "JSON",
        success: function(response) {
            if (response.status == 1) {
                var remove_btn = '<button class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill remove-btn"><i class="la la-trash"></i></button>';
                var input_counter = $('#input-counter').html(); //response.data.quantity
                input_counter = input_counter.replace(/{max}/g, response.data.quantity);
                input_counter = input_counter.replace(/{value}/g, 1);
                input_counter = input_counter.replace(/{product_id}/g, response.data.product_id);
                var warehouse = '<input type="hidden" class="warehouse_' + warehouse_id + '_' + response.data.product_id + '" name="warehouse[' + response.data.product_id + ']" value="' + warehouse_id + '">';
                var quantity = response.data.quantity + warehouse;
                var tr = [tr_id, response.data.product_code, response.data.product_name, input_counter, quantity, response.data.unitName, remove_btn];
                $(form_id + '.table-add-material tbody').append(generate_row_material(tr));
                $(form_id + '[name=material]').val('').trigger('change');
            }
        }
    });
});
// xử lý thay đổi kho
$('#form-add-material [name=warehouse_id]').change(function() {
    var warehouse_id = $(this).val();
    if (!warehouse_id) {
        $('#form-add-material [name=material]').html('');
        return;
    }
    $.ajax({
        url: laroute.route('ticket.material.get-product-by-warehouse'),
        data: {
            warehouse_id: warehouse_id,
        },
        method: "POST",
        dataType: "JSON",
        success: function(response) {
            if (response.status == 1) {
                $('#form-add-material [name=material]').html(response.html);
            }
        }
    });
});
// xu ly thay the vat tu
$('#form-replace-material [name=material_replace]').change(function() {
    var product_id = $(this).val();
    var warehouse_id = $('#form-replace-material [name=warehouse_id]').val();
    if (!product_id) {
        return;
    }
    var check_warehouse_id = $(this).closest('#form-replace-material').find('.table-add-material tbody tr .warehouse_' + warehouse_id + '_' + product_id).length;
    var check_warehouse_id_2 = $('#form-edit-material .warehouse_' + warehouse_id + '_' + product_id).length;
    console.log(check_warehouse_id, check_warehouse_id_2)
    if (check_warehouse_id > 0 || check_warehouse_id_2 > 0) {
        swal(
            lang['Đã có vật tư này'],
            '',
            'warning'
        );
        return;
    }
    var form_id = '#' + $(this).closest('form').attr('id') + ' ';
    var tr_id = $(form_id + '.table-add-material tbody tr').length;
    tr_id += 1;
    $.ajax({
        url: laroute.route('ticket.material.get-item-material'),
        data: {
            product_id: product_id,
            warehouse_id: warehouse_id,
        },
        method: "POST",
        dataType: "JSON",
        success: function(response) {
            if (response.status == 1) {
                var remove_btn = '<button class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill remove-btn"><i class="la la-trash"></i></button>';
                var input_counter = $('#input-counter').html(); //response.data.quantity
                input_counter = input_counter.replace(/{max}/g, response.data.quantity);
                input_counter = input_counter.replace(/{value}/g, 1);
                input_counter = input_counter.replace(/{product_id}/g, response.data.product_id);
                var select_status = $('#select-status').html();
                var name_status = 'status[' + response.data.product_id + ']';
                select_status = select_status.replaceAll(/{product_id}/g, name_status);
                var quantity_accept = '<div class="quantity_accept text-center">1</div><input type="number" class="d-none quantity_accept_value" name="' + response.data.product_id + '" value="1">';
                var warehouse = '<input type="hidden" class="warehouse_' + warehouse_id + '_' + response.data.product_id + '" name="warehouse[' + response.data.product_id + ']" value="' + warehouse_id + '">';
                var quantity = response.data.quantity + warehouse;
                var tr = [tr_id, response.data.product_code, response.data.product_name, input_counter, quantity, quantity_accept, response.data.unitName, (select_status + remove_btn)];
                $(form_id + '.table-add-material tbody').append(generate_row_material(tr));
                $(form_id + '[name=material_replace]').val('').trigger('change');
                $(form_id + '.table-add-material tbody tr select[name^="status["]').val("approve").change(); //.prop('disabled', 'disabled')
            }
            $(form_id + ' select[name^="status"]').addClass('d-none');
        }
    });
});
$('#form-replace-material [name=warehouse_id]').change(function() {
    var warehouse_id = $(this).val();
    if (!warehouse_id) {
        $('#form-replace-material [name=material_replace]').html('');
        return;
    }
    $.ajax({
        url: laroute.route('ticket.material.get-product-by-warehouse'),
        data: {
            warehouse_id: warehouse_id,
        },
        method: "POST",
        dataType: "JSON",
        success: function(response) {
            if (response.status == 1) {
                $('#form-replace-material [name=material_replace]').html(response.html);
            }
        }
    });
});
$(document).on('change', '#form-replace-material .table-add-material tbody tr .number-input', function() {
    $(this).closest('tr').find('td .quantity_accept').text($(this).val());
    $(this).closest('tr').find('td input.quantity_accept_value').attr('value', $(this).val());
});
$(document).on('click', '.table-add-material .remove-btn', function() {
    $(this).closest('tr').remove();
    $('#form-add-material .table-add-material tbody tr').each(function(index, value) {
        $(this).find('td:first-child').text((index + 1));
    });
    $('#form-replace-material .table-add-material tbody tr').each(function(index, value) {
        $(this).find('td:first-child').text((index + 1));
    });
});

$(document).on('change', '.table-add-material select[name^="status["]', function() {
    $status_val = $(this).val();
    if ($status_val != 'new') {
        $(this).closest('tr').find('.replace-material-btn').addClass('d-none');
    } else {
        $(this).closest('tr').find('.replace-material-btn').removeClass('d-none');
    }
});
$(document).on('change', '.table-propose select[name^="status["]', function() {
    $status_val = $(this).val();
    if ($status_val != 'new') {
        $(this).closest('tr').find('.replace-material-btn').addClass('d-none');
        $(this).closest('tr').find('.number-input').prop('disabled', 'disabled');
        $(this).closest('tr').find('.minus,.plus').addClass('d-none');
    } else {
        $(this).closest('tr').find('.replace-material-btn').removeClass('d-none');
        $(this).closest('tr').find('.number-input').prop('disabled', false);
        $(this).closest('tr').find('.minus,.plus').removeClass('d-none');
    }
});
$(document).on('click', '.replace-material-btn', function() {
    var tr = $(this).closest('tr').clone();
    tr.find('td:last-child').remove();
    tr.find('input.number-input').attr('disabled', true);
    tr.find('.number input').attr('disabled', true);
    tr.find('.number span').remove();
    $('#form-replace-material .table-propose tbody').html(tr);
    $('#form-replace-material .table-add-material tbody').html('');
    $('#form-replace-material [name="warehouse_id"]').val('').trigger('change');
    $('#modalReplace').modal('show');
});
$('#form-replace-material').submit(function() {
    $('#form-replace-material .table-add-material tbody tr td .remove-btn').remove();
    $('#form-replace-material .table-add-material tbody tr td .number').each(function() {
        $(this).parent().find('.number').addClass('d-none');
        $(this).closest('tr').find('select[name^="status["] option[value=approve]').attr('selected', 'selected');
    });
    var append_mateial = $('#form-replace-material .table-add-material tbody').html();
    $('#form-edit-material .table-add-material tbody').append(append_mateial);
    var product_replace = $('#form-replace-material .table-propose tbody tr .number-input').attr('name'); // option[value=approve]
    $('#form-edit-material .table-add-material tbody tr input[name="' + product_replace + '"]').closest('tr').find('select[name^="status["]').val('approve').change();

    $('#form-edit-material .table-add-material tbody tr').each(function(index, value) {
        $(this).find('td:first-child').text((index + 1));
    });
    $('#form-replace-material .table-propose tbody').html('');
    $('#form-replace-material .table-add-material tbody').html('');
    $('#modalReplace').modal('hide');
    return false;
});
$('#modalReplace').on('hidden.bs.modal', function(e) {
    $('#form-replace-material .table-propose tbody').html('');
    $('#form-replace-material .table-add-material tbody').html('');
});
$(document).on('click', '#table-config .view-material a', function() {
    var id_request_material = $(this).attr('data-id');
    Material.view(id_request_material);
});
// $('#form-replace-material .table-add-material .number-input').change(function() {
//     $(this).closest('tr').find('.quantity_accept').text($(this).val());
//     $(this).closest('tr').find('td input.quantity_accept_value').val($(this).val());
// });

function generate_row_material(arr) {
    var tr_append = '<tr>';
    $.each(arr, function(index, val) {
        tr_append += '<td>';
        tr_append += val;
        tr_append += '</td>';
    });
    tr_append += '</tr>';
    return tr_append;
}

function clear() {
    $('[name="ticket_code"]').val('').trigger('change');
    $('[name="warehouse_id"]').val('').trigger('change');
    $('[name="description"]').val('');
    $('.table-add-material tbody').find('tr').remove();
    $('#file-import').val('');
}

$('#autotable').PioTable({
    baseUrl: laroute.route('ticket.material.list')
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

$(document).on('click', '.minus', function() {
    let $input = $(this).parent().find('input');
    let count = parseInt($input.val()) - 1;
    count = count < 0 ? 0 : count;
    $input.val(count);
    $input.change();
    return false;
});
$(document).on('click', '.plus', function() {
    let $input = $(this).parent().find('input');
    let count = parseInt($input.val()) + 1;
    let i_max = parseInt($input.attr('max'));
    if (count > i_max) {
        count = i_max;
    }
    $input.val(count);
    $input.change();
    return false;
});
/*
validate số lượng duyệt và số lượng tạm ứng
*/
$(document).on('change', '.number-input', function() {
    let i_max = parseInt($(this).attr('max'));
    let i_min = parseInt($(this).attr('min'));
    if ($(this).val() > i_max) {
        $(this).val(i_max);
        return false;

    } else if ($(this).val() < 0) {
        $(this).val(i_min);
        return false;
    }
});