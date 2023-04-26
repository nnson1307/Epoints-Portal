$('#autotable').PioTable({
    baseUrl: laroute.route('admin.commission.list')
});

var jsonLang = JSON.parse(localStorage.getItem('tranlate'));

var stt = 1;

var arrNumberOrder = [1];
var arrNumberKpi = [1];
var arrNumberContract = [1];

$(document).ready(function () {
    $('#apply_time_type').select2({
        width: '100px'
    });

    $('#calc_apply_time').select2({
        width: '100%',
        placeholder: jsonLang['Chọn giá trị tính']
    });

    $('#commission_type, #commission_scope').select2({
        width: '100%',
    });

    $("#start_effect_time, #end_effect_time").datepicker({
        todayHighlight: !0,
        autoclose: !0,
        // pickerPosition: "bottom-left",
        format: "dd/mm/yyyy",
        // minDate: new Date(),
        // locale: 'vi'
    });

    new AutoNumeric.multiple('#apply_time', {
        currencySymbol: '',
        decimalCharacter: '.',
        digitGroupSeparator: '',
        decimalPlaces: 0,
        eventIsCancelable: true,
        minimumValue: 0
    });

    // Lấy giá trị tính dựa trên thời gian áp dụng khi thêm hoa hồnA hải rung
    $('#apply_time').on('blur', function () {
        var html = '';
        for (let i = 1; i <= $(this).val(); i++) {
            if ($(this).val() % i === 0) {
                html += '<option value="' + i + '">' + i + '</option>';
            }
        }
        $('#calc_apply_time').html(html);
    });

    // Enable nhập custom value trong field nhập tag khi thêm hoa hồng
    $(".js-tags").select2({
        tags: true,
        createTag: function (newTag) {
            return {
                id: "new:" + newTag.term,
                text: newTag.term,
                isNew: true,
            };
        }
    }).on("select2:select", function (e) {
        if (e.params.data.isNew) {
            // store the new tag:
            $.ajax({
                type: "POST",
                url: laroute.route("admin.commission.create-tag"),
                data: {
                    tag_name: e.params.data.text,
                },
                success: function (res) {
                    // append the new option element end replace id
                    $(".js-tags")
                        .find('[value="' + e.params.data.id + '"]')
                        .replaceWith(
                            '<option selected value="' +
                            res.tag_id +
                            '">' +
                            e.params.data.text +
                            "</option>"
                        );
                },
            });
        }
    });
});

var commission = {
    changeStatus(commissionId, obj) {
        var status = 0;
        if ($(obj).is(':checked')) {
            status = 1;
        }

        swal({
            title: jsonLang['Thông báo'],
            text: jsonLang["Bạn có muốn thay đổi trạng thái không?"],
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: jsonLang['Đồng ý'],
            cancelButtonText: jsonLang['Hủy'],
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: laroute.route('admin.commission.change-status'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        commission_id: commissionId,
                        status: status
                    },
                    success: function (res) {
                        if (res.error == false) {
                            swal.fire(res.message, "", "success");
                            $('#autotable').PioTable('refresh');
                        } else {
                            swal.fire(res.message, '', "error");
                        }
                    }
                });
            }
        });
    },

    add: function () {
        var status = 0;
        if ($('#status').is(':checked')) {
            status = 1;
        }

        var type = $('#commission_type').val();

        var arrayTable = [];

        var continute = true;

        if (type == 'order') {
            var lengthTable = $('#order-table').find(".tr_template").length;

            // check các trường dữ liệu rỗng thì báo lỗi
            $.each($('#order-table').find(".tr_template"), function (k, v) {
                var number = parseInt($(this).find($('.number')).val());

                // Lấy giá trị tối đa dòng trước đó
                var prev_max_value = 0;

                if (k > 0) {
                    var i = arrNumberOrder.indexOf(number);

                    if (arrNumberOrder[i - 1] !== 'undefined') {
                        prev_max_value = $('#max-order-' + arrNumberOrder[i - 1]).val().replace(new RegExp('\\,', 'g'), '');
                    }
                }

                // Lấy giá trị dòng hiện tại
                var min_value = $(this).find($('#min-order-' + number)).val().replace(new RegExp('\\,', 'g'), '');
                var max_value = $(this).find($('#max-order-' + number)).val().replace(new RegExp('\\,', 'g'), '');
                var commission_value = $(this).find($('#order-commission-value-' + number)).val().replace(new RegExp('\\,', 'g'), '');

                /**
                 * Check null
                 */
                if (min_value == '') {
                    $('.error_valid_min_value_' + number + '').text('Hãy nhập giá trị tối thiểu');
                    continute = false;
                } else {
                    $('.error_valid_min_value_' + number + '').text('');
                }

                if (max_value == '' && k + 1 != lengthTable) {
                    $('.error_valid_max_value_' + number + '').text('Hãy nhập giá trị tối đa');
                    console.log('vào đây', max_value);
                    continute = false;
                } else {
                    $('.error_valid_max_value_' + number + '').text('');
                }

                if (commission_value == '') {
                    $('.error_valid_commission_value_' + number + '').text('Hãy nhập hoa hồng cho nhân viên');
                    continute = false;
                } else {
                    $('.error_valid_commission_value_' + number + '').text('');
                }

                /**
                 * Giá trị tối đa phải lớn hơn giá trị tối thiểu
                 */
                if (min_value != '' && max_value != '' && parseFloat(min_value) >= parseFloat(max_value)) {
                    $('.error_valid_max_value_' + number + '').text('Giá trị tối đa phải lớn hơn giá trị tối thiểu');
                    continute = false;
                }

                /**
                 * Giá trị tối thiểu lớn hơn giá trị tối đa dòng trước
                 */
                if (parseFloat(min_value) < parseFloat(prev_max_value) && k > 0) {
                    $('.error_valid_min_value_' + number + '').text('Giá trị tối thiểu phải lớn hơn hoặc bằng giá trị tối đa dòng trước');
                    continute = false;
                }
            });

            if (continute == true) {
                $('#order-table tbody tr').each(function () {
                    var number = $(this).find($('.number')).val();

                    var minOrder = $(this).find($("[name='min-order']")).val().replace(new RegExp('\\,', 'g'), '');
                    var maxOrder = $(this).find($("[name='max-order']")).val().replace(new RegExp('\\,', 'g'), '');
                    var commissionValue = $(this).find($("[name='order-commission-value']")).val().replace(new RegExp('\\,', 'g'), '');
                    var configOperation = $(this).find($('input[name=config-operation-' + number + ']:checked')).val();

                    arrayTable.push({
                        min_value: minOrder,
                        max_value: maxOrder,
                        commission_value: commissionValue,
                        config_operation: configOperation
                    });
                });
            }
        }

        if (type == 'kpi') {
            var lengthTableKpi = $('#kpi-table').find(".tr_template").length;

            // check các trường dữ liệu rỗng thì báo lỗi
            $.each($('#kpi-table').find(".tr_template"), function (k, v) {
                var number = parseInt($(this).find($('.number')).val());

                // Lấy giá trị tối đa dòng trước đó
                var prev_max_value = 0;

                if (k > 0) {
                    var i = arrNumberKpi.indexOf(number);

                    if (arrNumberKpi[i - 1] !== 'undefined') {
                        prev_max_value = $('#max-kpi-' + arrNumberKpi[i - 1]).val().replace(new RegExp('\\,', 'g'), '');
                    }
                }

                // Lấy giá trị dòng hiện tại
                var min_value = $(this).find($('#min-kpi-' + number)).val().replace(new RegExp('\\,', 'g'), '');
                var max_value = $(this).find($('#max-kpi-' + number)).val().replace(new RegExp('\\,', 'g'), '');
                var commission_value = $(this).find($('#kpi-commission-value-' + number)).val().replace(new RegExp('\\,', 'g'), '');

                if (min_value == '') {
                    $('.error_valid_min_kpi_' + number + '').text('Hãy nhập giá trị tối thiểu');
                    continute = false;
                } else {
                    if (min_value != '' && parseFloat(min_value) > 100) {
                        $('.error_valid_min_kpi_' + number + '').text('Giá trị tối thiểu nhỏ hơn 100');
                        continute = false;
                    } else {
                        $('.error_valid_min_kpi_' + number + '').text('');
                    }
                }

                if (max_value == '' && k + 1 != lengthTableKpi) {
                    $('.error_valid_max_kpi_' + number + '').text('Hãy nhập giá trị tối đa');
                    continute = false;
                } else {
                    if (max_value != '' && parseFloat(max_value) > 100) {
                        $('.error_valid_max_kpi_' + number + '').text('Giá trị tối đa nhỏ hơn 100');
                        continute = false;
                    } else {
                        $('.error_valid_max_kpi_' + number + '').text('');
                    }
                }

                if (commission_value == '') {
                    $('.error_valid_commission_value_' + number + '').text('Hãy nhập hoa hồng cho nhân viên');
                    commission_value = false;
                } else {
                    $('.error_valid_commission_value_' + number + '').text('');
                }

                if (min_value != '' && max_value != '' && parseFloat(min_value) >= parseFloat(max_value)) {
                    $('.error_valid_max_kpi_' + number + '').text('Giá trị tối đa phải lớn hơn giá trị tối thiểu');
                    continute = false;
                }

                if (parseFloat(min_value) < parseFloat(prev_max_value) && k > 0) {
                    $('.error_valid_min_kpi_' + number + '').text('Giá trị tối thiểu phải lớn hơn hoặc bằng giá trị tối đa dòng trước');
                    continute = false;
                }
            });

            if (continute == true) {
                $('#kpi-table tbody tr').each(function () {
                    var number = $(this).find($('.number')).val();

                    var minKpi = $(this).find($("[name='min-kpi']")).val().replace(new RegExp('\\,', 'g'), '');
                    var maxKpi = $(this).find($("[name='max-kpi']")).val().replace(new RegExp('\\,', 'g'), '');
                    var commissionValue = $(this).find($("[name='kpi-commission-value']")).val().replace(new RegExp('\\,', 'g'), '');
                    var configOperation = $(this).find($('input[name=config-operation-' + number + ']:checked')).val();

                    arrayTable.push({
                        min_value: minKpi,
                        max_value: maxKpi,
                        commission_value: commissionValue,
                        config_operation: configOperation
                    });
                });
            }
        }

        if (type == 'contract') {
            var lengthTableContract = $('#contract-table').find(".tr_template").length;

            // check các trường dữ liệu rỗng thì báo lỗi
            $.each($('#contract-table').find(".tr_template"), function (k, v) {
                var number = parseInt($(this).find($('.number')).val());

                // Lấy giá trị tối đa dòng trước đó
                var prev_max_value = 0;

                if (k > 0) {
                    var i = arrNumberContract.indexOf(number);

                    if (arrNumberContract[i - 1] !== 'undefined') {
                        prev_max_value = $('#max-contract-' + arrNumberContract[i - 1]).val().replace(new RegExp('\\,', 'g'), '');
                    }
                }

                // Lấy giá trị dòng hiện tại
                var min_value = $(this).find($('#min-contract-' + number)).val().replace(new RegExp('\\,', 'g'), '');
                var max_value = $(this).find($('#max-contract-' + number)).val().replace(new RegExp('\\,', 'g'), '');
                var commission_value = $(this).find($('#contract-commission-value-' + number)).val().replace(new RegExp('\\,', 'g'), '');

                if (min_value == '') {
                    $('.error_valid_min_contract_' + number + '').text('Hãy nhập giá trị tối thiểu');
                    continute = false;
                } else {
                    $('.error_valid_min_contract_' + number + '').text('');
                }

                if (max_value == '' && k + 1 != lengthTableContract) {
                    $('.error_valid_max_contract_' + number + '').text('Hãy nhập giá trị tối đa');
                    continute = false;
                } else {
                    $('.error_valid_max_contract_' + number + '').text('');
                }

                if (commission_value == '') {
                    $('.error_valid_commission_value_' + number + '').text('Hãy nhập hoa hồng cho nhân viên');
                    continute = false;
                } else {
                    $('.error_valid_commission_value_' + number + '').text('');
                }

                if (min_value != '' && max_value != '' && parseFloat(min_value) >= parseFloat(max_value)) {
                    $('.error_valid_max_contract_' + number + '').text('Giá trị tối đa phải lớn hơn giá trị tối thiểu');
                    continute = false;
                }

                if (parseFloat(min_value) < parseFloat(prev_max_value) && k > 0) {
                    $('.error_valid_min_contract_' + number + '').text('Giá trị tối thiểu phải lớn hơn hoặc bằng giá trị tối đa dòng trước');
                    continute = false;
                }
            });

            if (continute == true) {
                $('#contract-table tbody tr').each(function () {
                    var number = $(this).find($('.number')).val();

                    var minContract = $(this).find($("[name='min-contract']")).val().replace(new RegExp('\\,', 'g'), '');
                    var maxContract = $(this).find($("[name='max-contract']")).val().replace(new RegExp('\\,', 'g'), '');
                    var commissionValue = $(this).find($("[name='contract-commission-value']")).val().replace(new RegExp('\\,', 'g'), '');
                    var configOperation = $(this).find($('input[name=config-operation-' + number + ']:checked')).val();

                    arrayTable.push({
                        min_value: minContract,
                        max_value: maxContract,
                        commission_value: commissionValue,
                        config_operation: configOperation
                    });
                });
            }
        }

        if (continute == true) {
            $.ajax({
                url: laroute.route('admin.commission.submit'),
                method: 'POST',
                dataType: "JSON",
                data: {
                    commission_name: $('#commission_name').val(),
                    status: status,
                    apply_time: $('#apply_time').val(),
                    calc_apply_time: $('#calc_apply_time').val(),
                    description: $('#description').val(),
                    start_effect_time: $('#start_effect_time').val(),
                    end_effect_time: $('#end_effect_time').val(),
                    tags_id: $('#tags_id').val(),
                    commission_type: $('#commission_type').val(),
                    commission_calc_by: $('input[name=commission_calc_by]:checked').val(),
                    commission_scope: $('#commission_scope').val(),

                    order_commission_type: $('#order_commission_type').val(),
                    order_commission_group_type: $('#order_commission_group_type').val(),
                    order_commission_object_type: $('#order_commission_object_type').val(),
                    order_commission_calc_by: $('#order_commission_calc_by').val(),

                    kpi_commission_calc_by: $('#kpi_commission_calc_by').val(),

                    contract_commission_calc_by: $('#contract_commission_calc_by').val(),
                    contract_commission_type: $('#contract_commission_type').val(),
                    contract_commission_condition: $('#contract_commission_condition').val(),
                    contract_commission_operation: $('#contract_commission_operation').val(),
                    contract_commission_time: $('#contract_commission_time').val(),
                    contract_commission_apply: $('#contract_commission_apply').val(),
                    tableData: arrayTable
                },
                success: function (res) {
                    if (res.error == false) {
                        swal(res.message, "", "success").then(function (result) {
                            if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                window.location.href = laroute.route('admin.commission');
                            }
                            if (result.value == true) {
                                window.location.href = laroute.route('admin.commission');
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
                    swal(jsonLang['Thêm thất bại'], mess_error, "error");
                }
            });
        }
    },

    //Thay đổi loại hoa hồng
    changeType: function () {
        $.ajax({
            url: laroute.route('admin.commission.change-type'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                commission_type: $('#commission_type').val()
            },
            success: function (res) {
                $('.div_col_right').empty();
                $('.div_table').empty();

                $('#commission_scope').empty();

                switch (res.commission_type) {
                    case 'order':
                        //Append col bên phải
                        var tpl = $('#col-right-order-tpl').html();
                        // tpl = tpl.replace(/{stt}/g, stt);
                        $('.div_col_right').append(tpl);

                        $('#order_commission_type, #order_commission_group_type, #order_commission_calc_by').select2({
                            width: '100%',
                        });

                        $('#order_commission_object_type').select2({
                            width: '100%',
                        }).on('select2:unselect', function (event) {
                            if ($('#order_commission_object_type').val().length == 0) {
                                $('#order_commission_object_type').val('all').trigger('change');
                            }
                        });

                        //Render html table
                        $('.div_table').html(res.htmlTable);

                        new AutoNumeric.multiple('.numeric_child', {
                            currencySymbol: '',
                            decimalCharacter: '.',
                            digitGroupSeparator: ',',
                            decimalPlaces: decimal_number,
                            eventIsCancelable: true,
                            minimumValue: 0
                        });

                        $('#commission_scope').append('<option value="personal">' + jsonLang['Cá nhân'] + '</option>');
                        $('#commission_scope').append('<option value="group">' + jsonLang['Theo nhóm'] + '</option>');

                        arrNumberOrder = [1];
                        arrNumberKpi = [];
                        arrNumberContract = [];

                        stt = 1;

                        break;
                    case 'kpi':
                        //Append col bên phải
                        var tpl = $('#col-right-kpi-tpl').html();
                        $('.div_col_right').append(tpl);

                        $('#kpi_commission_calc_by').select2();

                        //Render html table
                        $('.div_table').html(res.htmlTable);

                        new AutoNumeric.multiple('.numeric_child', {
                            currencySymbol: '',
                            decimalCharacter: '.',
                            digitGroupSeparator: ',',
                            decimalPlaces: decimal_number,
                            eventIsCancelable: true,
                            minimumValue: 0
                        });

                        $('#commission_scope').append('<option value="personal">' + jsonLang['Cá nhân'] + '</option>');
                        $('#commission_scope').append('<option value="group">' + jsonLang['Theo nhóm'] + '</option>');
                        $('#commission_scope').append('<option value="branch">' + jsonLang['Theo chi nhánh'] + '</option>');
                        $('#commission_scope').append('<option value="department">' + jsonLang['Theo phòng ban'] + '</option>');

                        commission.changeScope();

                        arrNumberOrder = [];
                        arrNumberKpi = [1];
                        arrNumberContract = [];

                        stt = 1;

                        break;
                    case 'contract':
                        //Append col bên phải
                        var tpl = $('#col-right-contract-tpl').html();
                        $('.div_col_right').append(tpl);

                        //Render html table
                        $('.div_table').html(res.htmlTable);

                        $('#contract_commission_calc_by, #contract_commission_type, #contract_commission_condition, #contract_commission_operation, #contract_commission_apply').select2();

                        new AutoNumeric.multiple('.numeric_child', {
                            currencySymbol: '',
                            decimalCharacter: '.',
                            digitGroupSeparator: ',',
                            decimalPlaces: decimal_number,
                            eventIsCancelable: true,
                            minimumValue: 0
                        });

                        $('#commission_scope').append('<option value="personal">' + jsonLang['Cá nhân'] + '</option>');
                        $('#commission_scope').append('<option value="group">' + jsonLang['Theo nhóm'] + '</option>');

                        arrNumberOrder = [];
                        arrNumberKpi = [];
                        arrNumberContract = [1];

                        stt = 1;

                        commission.changeContractCalcBy();

                        break;
                }
            }
        });
    },
    //Thay đổi loại hàng hoá
    changeOrderType: function () {
        $.ajax({
            url: laroute.route('admin.commission.change-order-type'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                order_commission_type: $('#order_commission_type').val()
            },
            success: function (res) {
                $('#order_commission_group_type').empty();
                $('#order_commission_group_type').append('<option value="all">' + jsonLang['Tất cả'] + '</option>');

                switch (res.order_commission_type) {
                    case 'product':
                        $.each(res.data, function (k, v) {
                            $('#order_commission_group_type').append('<option value="' + v['product_category_id'] + '">' + v['category_name'] + '</option>');
                        });

                        break;
                    case 'service':
                        $.each(res.data, function (k, v) {
                            $('#order_commission_group_type').append('<option value="' + v['service_category_id'] + '">' + v['name'] + '</option>');
                        });

                        break;
                    case 'service_card':
                        $.each(res.data, function (k, v) {
                            $('#order_commission_group_type').append('<option value="' + v['service_card_group_id'] + '">' + v['name'] + '</option>');
                        });

                        break;
                }

                commission.changeOrderGroup();
            }
        });
    },
    //Thay đổi nhóm hàng hoá
    changeOrderGroup: function () {
        $('#order_commission_object_type').empty();
        $('#order_commission_object_type').append('<option value="all" selected>' + jsonLang['Tất cả'] + '</option>');

        // if ($('#order_commission_group_type').val() != 'all') {
        $('#order_commission_object_type').select2({
            width: '100%',
            placeholder: jsonLang["Chọn hàng hoá"],
            ajax: {
                url: laroute.route('admin.commission.option-order-object'),
                data: function (params) {
                    return {
                        search: params.term,
                        page: params.page || 1,
                        type: $('#order_commission_type').val(),
                        object_group_id: $('#order_commission_group_type').val()
                    };
                },
                dataType: 'json',
                method: 'POST',
                processResults: function (data) {
                    data.page = data.page || 1;
                    return {
                        results: data.items.map(function (item) {
                            if ($('#order_commission_type').val() == 'product') {
                                return {
                                    id: item.product_child_id,
                                    text: item.product_child_name,
                                    code: item.product_code
                                };
                            } else if ($('#order_commission_type').val() == 'service') {
                                return {
                                    id: item.service_id,
                                    text: item.service_name,
                                    code: item.service_code
                                };
                            } else if ($('#order_commission_type').val() == 'service_card') {
                                return {
                                    id: item.service_card_id,
                                    text: item.name,
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
            if (event.params.data.id == 'all') {
                $('#order_commission_object_type').val('all').trigger('change');
            } else {
                var arrayChoose = [];

                $.map($('#order_commission_object_type').val(), function (val) {
                    if (val != 'all') {
                        arrayChoose.push(val);
                    }
                });
                $('#order_commission_object_type').val(arrayChoose).trigger('change');
            }
        }).on('select2:unselect', function (event) {
            if ($('#order_commission_object_type').val().length == 0) {
                $('#order_commission_object_type').val('all').trigger('change');
            }
        });
        // }
    },
    //Thay đổi tính thời hạn hợp đồng
    changeContractOperation: function () {
        var contractOperation = $('#contract_commission_operation').val();

        if (contractOperation == 'no_limit') {
            $('#contract_commission_time').prop('disabled', true);
        } else {
            $('#contract_commission_time').prop('disabled', false)
        }
    },

    remove: function (obj, id, name, count) {
        // hightlight row
        $(obj).closest('tr').addClass('m-table__row--danger');
        swal({
            title: "Thông báo",
            text: "Hoa hồng '" + name + "' đã được áp dụng cho " + count + " nhân viên. Khi bạn xoá hoa hồng này sẽ được thu hồi. Bạn xác nhận muốn xoá hoa hồng này?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: "Xóa",
            cancelButtonText: "Hủy",
            onClose: function () {
                // remove hightlight row
                $(obj).closest('tr').removeClass('m-table__row--danger');
            }
        }).then(function (result) {
            if (result.value) {
                $.post(laroute.route('admin.commission.remove', {id: id}), function () {
                    swal(
                        "Xóa thành công",
                        '',
                        'success'
                    );
                    // window.location.reload();
                    $('#autotable').PioTable('refresh');
                });
            }
        });
    },

    addOrderTemplate: function () {
        var continute = true;

        // check các trường dữ liệu rỗng thì báo lỗi
        $.each($('#order-table').find(".tr_template"), function (k, v) {
            var number = parseInt($(this).find($('.number')).val());

            // Lấy giá trị dòng hiện tại
            var min_value = $(this).find($('#min-order-' + number)).val().replace(new RegExp('\\,', 'g'), '');
            var max_value = $(this).find($('#max-order-' + number)).val().replace(new RegExp('\\,', 'g'), '');
            var commission_value = $(this).find($('#order-commission-value-' + number)).val().replace(new RegExp('\\,', 'g'), '');

            /**
             * Check null
             */
            if (min_value == '') {
                $('.error_valid_min_value_' + number + '').text('Hãy nhập giá trị tối thiểu');
                continute = false;
            } else {
                $('.error_valid_min_value_' + number + '').text('');
            }

            if (max_value == '') {
                $('.error_valid_max_value_' + number + '').text('Hãy nhập giá trị tối đa');
                continute = false;
            } else {
                $('.error_valid_max_value_' + number + '').text('');
            }

            if (commission_value == '') {
                $('.error_valid_commission_value_' + number + '').text('Hãy nhập hoa hồng cho nhân viên');
                continute = false;
            } else {
                $('.error_valid_commission_value_' + number + '').text('');
            }

            /**
             * Giá trị tối đa phải lớn hơn giá trị tối thiểu
             */
            if ((min_value != '' && max_value != '' && commission_value != '') && parseFloat(min_value) >= parseFloat(max_value)) {
                $('.error_valid_max_value_' + number + '').text('Giá trị tối đa phải lớn hơn giá trị tối thiểu');
                continute = false;
            }
        });

        if (continute == true) {
            stt++;
            //append tr table
            var tpl = $('#order-template').html();
            tpl = tpl.replace(/{stt}/g, stt);
            $('#order-table > tbody').append(tpl);



            if (stt > 1) {
                var lengthTable = $('#order-table').find(".tr_template").length;

                //Lấy giá trị tối đa của dòng trước đó
                var max_value = lengthTable > 1 ? parseFloat($('#max-order-' + (stt - 1)).val().replace(new RegExp('\\,', 'g'), '')) : 0;

                $('#min-order-' + stt).val(max_value);
                $('#max-order-' + stt).val(max_value + 1);
            }

            new AutoNumeric.multiple('#min-order-' + stt + ', #max-order-' + stt + ', #order-commission-value-' + stt + '', {
                currencySymbol: '',
                decimalCharacter: '.',
                digitGroupSeparator: ',',
                decimalPlaces: decimal_number,
                eventIsCancelable: true,
                minimumValue: 0
            });

            arrNumberOrder.push(stt);
        }
    },

    addKpiTemplate: function () {
        var continute = true;

        //check các trường dữ liệu rỗng thì báo lỗi
        $.each($('#kpi-table').find(".tr_template"), function (k, v) {
            var number = $(this).find($('.number')).val();

            // Lấy giá trị dòng hiện tại
            var min_value = $(this).find($('#min-kpi-' + number)).val().replace(new RegExp('\\,', 'g'), '');
            var max_value = $(this).find($('#max-kpi-' + number)).val().replace(new RegExp('\\,', 'g'), '');
            var commission_value = $(this).find($('#kpi-commission-value-' + number)).val().replace(new RegExp('\\,', 'g'), '');

            if (min_value == '') {
                $('.error_valid_min_kpi_' + number + '').text('Hãy nhập giá trị tối thiểu');
                continute = false;
            } else {
                if (min_value != '' && parseFloat(min_value) > 100) {
                    $('.error_valid_min_kpi_' + number + '').text('Giá trị tối thiểu nhỏ hơn 100');
                    continute = false;
                } else {
                    $('.error_valid_min_kpi_' + number + '').text('');
                }
            }

            if (max_value == '') {
                $('.error_valid_max_kpi_' + number + '').text('Hãy nhập giá trị tối đa');
                continute = false;
            } else {
                if (max_value != '' && parseFloat(max_value) > 100) {
                    $('.error_valid_max_kpi_' + number + '').text('Giá trị tối đa nhỏ hơn 100');
                    continute = false;
                } else {
                    $('.error_valid_max_kpi_' + number + '').text('');
                }
            }

            if (commission_value == '') {
                $('.error_valid_commission_value_' + number + '').text('Hãy nhập hoa hồng cho nhân viên');
                continute = false;
            } else {
                $('.error_valid_commission_value_' + number + '').text('');
            }

            if (min_value != '' && max_value != '' && commission_value != '' && parseFloat(min_value) >= parseFloat(max_value)) {
                $('.error_valid_max_kpi_' + number + '').text('Giá trị tối đa phải lớn hơn giá trị tối thiểu');
                continute = false;
            }
        });

        if (continute == true) {
            stt++;
            //append tr table
            var tpl = $('#kpi-template').html();
            tpl = tpl.replace(/{stt}/g, stt);
            $('#kpi-table > tbody').append(tpl);

            if (stt > 1) {
                var lengthTable = $('#kpi-table').find(".tr_template").length;
                //Lấy giá trị tối đa của dòng trước đó
                var max_value = lengthTable > 1 ? parseFloat($('#max-kpi-' + (stt - 1)).val().replace(new RegExp('\\,', 'g'), '')) : 0;

                $('#min-kpi-' + stt).val(max_value);
                $('#max-kpi-' + stt).val(max_value + 1);
            }

            new AutoNumeric.multiple('#min-kpi-' + stt + ', #max-kpi-' + stt + ', #kpi-commission-value-' + stt + '', {
                currencySymbol: '',
                decimalCharacter: '.',
                digitGroupSeparator: ',',
                decimalPlaces: decimal_number,
                eventIsCancelable: true,
                minimumValue: 0
            });

            arrNumberKpi.push(stt);
        }
    },

    addContractTemplate: function () {
        var continute = true;


        //check các trường dữ liệu rỗng thì báo lỗi
        $.each($('#contract-table').find(".tr_template"), function (k, v) {
            var number = $(this).find($('.number')).val();

            // Lấy giá trị dòng hiện tại
            var min_value = $(this).find($('#min-contract-' + number)).val().replace(new RegExp('\\,', 'g'), '');
            var max_value = $(this).find($('#max-contract-' + number)).val().replace(new RegExp('\\,', 'g'), '');
            var commission_value = $(this).find($('#contract-commission-value-' + number)).val().replace(new RegExp('\\,', 'g'), '');

            if (min_value == '') {
                $('.error_valid_min_contract_' + number + '').text('Hãy nhập giá trị tối thiểu');
                continute = false;
            } else {
                $('.error_valid_min_contract_' + number + '').text('');
            }

            if (max_value == '') {
                $('.error_valid_max_contract_' + number + '').text('Hãy nhập giá trị tối đa');
                continute = false;
            } else {
                $('.error_valid_max_contract_' + number + '').text('');
            }

            if (commission_value == '') {
                $('.error_valid_commission_value_' + number + '').text('Hãy nhập hoa hồng cho nhân viên');
                continute = false;
            } else {
                $('.error_valid_commission_value_' + number + '').text('');
            }

            if (min_value != '' && max_value != '' && commission_value != '' && parseFloat(min_value) >= parseFloat(max_value)) {
                $('.error_valid_max_contract_' + number + '').text('Giá trị tối đa phải lớn hơn giá trị tối thiểu');
                continute = false;
            }
        });

        if (continute == true) {
            stt++;
            //append tr table
            var tpl = $('#contract-template').html();
            tpl = tpl.replace(/{stt}/g, stt);
            $('#contract-table > tbody').append(tpl);

            if (stt > 1) {
                var lengthTable = $('#contract-table').find(".tr_template").length;
                //Lấy giá trị tối đa của dòng trước đó
                var max_value = lengthTable > 1 ? parseFloat($('#max-contract-' + (stt - 1)).val().replace(new RegExp('\\,', 'g'), '')) : 0;

                $('#min-contract-' + stt).val(max_value);
                $('#max-contract-' + stt).val(max_value + 1);
            }

            new AutoNumeric.multiple('#min-contract-' + stt + ', #max-contract-' + stt + ', #contract-commission-value-' + stt + '', {
                currencySymbol: '',
                decimalCharacter: '.',
                digitGroupSeparator: ',',
                decimalPlaces: decimal_number,
                eventIsCancelable: true,
                minimumValue: 0
            });

            arrNumberContract.push(stt);

            var arr = ['all-paid', 'all-half-paid'];

            if (arr.includes($('#contract_commission_calc_by').val())) {
                // $('input:radio[name=config-operation-' + stt + '][value=0]').click();
                $('.label_config_operation_percent_' + stt + '').css('display', 'none');
            }
        }
    },

    removeTr: function (obj, objectType) {
        var number = parseInt($(obj).closest('tr').find($('.number')).val());

        switch (objectType) {
            case 'order':
                arrNumberOrder.remove(number);
                break;
            case 'kpi':
                arrNumberKpi.remove(number);
                break;
            case 'contract':
                arrNumberOrder.remove(number);
                break;
        }

        $(obj).closest('tr').remove();
    },
    //Thay đổi giá trị của
    changeScope: function () {
        var commissionType = $('#commission_type').val();

        if (commissionType == 'kpi') {
            $.ajax({
                url: laroute.route('admin.commission.change-scope'),
                method: "POST",
                dataType: "JSON",
                data: {
                    commission_type: commissionType,
                    commission_scope: $('#commission_scope').val()
                },
                success: function (res) {
                    $('#kpi_commission_calc_by').empty();
                    $('#kpi_commission_calc_by').append('<option value="all">' + jsonLang['Tất cả'] + '</option>');

                    $.each(res.optionCriteria, function (k, v) {
                        $('#kpi_commission_calc_by').append('<option value="' + v.kpi_criteria_id + '">' + v.kpi_criteria_name + '</option>');
                    });
                }
            });
        }
    },
    //Thay đổi tính theo của hợp đồng
    changeContractCalcBy: function () {
        var arr = ['all-paid', 'all-half-paid'];

        if (arr.includes($('#contract_commission_calc_by').val())) {
            $('#contract-table tbody tr').each(function () {
                var number = $(this).find($('.number')).val();

                $('input:radio[name=config-operation-' + number + '][value=0]').click();
                $('.label_config_operation_percent_' + number + '').css('display', 'none');
            });
        } else {
            $('#contract-table tbody tr').each(function () {
                var number = $(this).find($('.number')).val();

                $('.label_config_operation_percent_' + number + '').css('display', 'block');
            });
        }
    }
};

var detail = {
    _init: function () {
        $(document).ready(function () {
            $('#order_commission_type, #order_commission_calc_by, #kpi_commission_calc_by, #contract_commission_calc_by, ' +
                '#contract_commission_type, #contract_commission_condition, #contract_commission_operation, #contract_commission_apply').select2({
                width: '100%'
            });

        });
    }
};


function nextStep() {
    var form = $('#form-banner');

    form.validate({
        rules: {
            commission_name: {
                required: true,
            },
            apply_time: {
                required: true,
            },
            calc_apply_time: {
                required: true,
            },
            start_effect_time: {
                required: true,
            }
        },
        messages: {
            commission_name: {
                required: 'Hãy nhập tên hoa hồng'
            },
            apply_time: {
                required: 'Hãy nhập thời gian áp dụng'
            },
            calc_apply_time: {
                required: 'Hãy nhập giá trị tính dựa trên thời gian áp dụng',
            },
            start_effect_time: {
                required: 'Hãy chọn thời gian bắt đầu hiệu lực',
            }
        },
    });

    if (!form.valid()) {
        return false;
    }

    $('#tab-calc').trigger('click');
}

function previousStep() {
    $('#tab-commission').trigger('click');
}

function generateRowHeader(commissionData) {
    var str = '';
    var std = '';

    std += '<tr>';
    $.each(commissionData, function (k, v) {
        sessionStorage.count = k;

        if (k == 0) {
            str += '<th class="ss--font-size-th"></th>';
            str += '<th class="ss--font-size-th">Hệ số hoa hồng</th>';
        }

        str += '<th class="ss--font-size-th">' + v.commission_name + '</th>';
    });
    std += '</tr>';
    $('#review-table thead').html(str);
    $('#review-table tbody').html(std);
}

function generateRow(staffData) {
    var std = '';
    $.each(staffData, function (k, v) {
        std += '<tr>';

        std += '<td class="ss--font-size-th">' + v.staff_name + '</td>';

        std += '<td>' + v.commission_coefficient + '</td>';

        for (i = 0; i <= sessionStorage.count; i++) {
            std += '<td><input type="checkbox" checked disabled></td>';
        }
        ;
        std += '</tr>';
    });
    $('#review-table tbody').append(std);
}

function bootstrapTabControl(commissionData, staffData) {
    var i, items = $('.tab-allocate'),
        pane = $('.tab-pane');
    // next
    $('.nexttab').on('click', function () {
        for (i = 0; i < items.length; i++) {
            if ($(items[i]).hasClass('active') == true) {
                break;
            }
        }

        if (i < items.length - 1) {
            // for tab
            $(items[i]).removeClass('active');
            $(items[i + 1]).addClass('active');
            // for pane
            $(pane[i]).removeClass('show active');
            $(pane[i + 1]).addClass('show active');
        }

        if (i == 1) {

            generateRowHeader(commissionData);

            generateRow(staffData);
        }

    });

    // Prev
    $('.prevtab').on('click', function () {
        for (i = 0; i < items.length; i++) {
            if ($(items[i]).hasClass('active') == true) {
                break;
            }
        }
        if (i != 0) {
            // for tab
            $(items[i]).removeClass('active');
            $(items[i - 1]).addClass('active');
            // for pane
            $(pane[i]).removeClass('show active');
            $(pane[i - 1]).addClass('show active');
        }
    });
}

var WizardDemo = function (view) {
    $("#m_wizard");
    var e, r, i = $("#m_form");
    return {
        init: function () {
            var n;
            $("#m_wizard"), i = $("#m_form"), (r = new mWizard("m_wizard", {startStep: 1})).on("beforeNext", function (r) {
                !0 !== e.form() && r.stop()
            }), r.on("change", function (e) {
                mUtil.scrollTop()
            }), r.on("change", function (e) {
                1 === e.getStep()
            }), e = i.validate({
                ignore: ":hidden",
                rules: {
                    commission_name: {
                        required: !0,
                        maxlength: 190
                    },
                    apply_time: {
                        required: !0
                    },
                    calc_apply_time: {
                        required: !0
                    },
                    start_effect_time: {
                        required: !0
                    },
                    // end_effect_time: {
                    //     required: !0
                    // },
                    contract_commission_time: {
                        required: !0,
                        min: 1,
                        max: 12
                    }
                },
                messages: {
                    commission_name: {
                        required: jsonLang['Hãy nhập tên hoa hồng'],
                        maxlength: jsonLang['Tên hoa hồng tối đa 190 kí tự']
                    },
                    apply_time: {
                        required: jsonLang['Hãy nhập thời gian áp dụng hoa hồng']
                    },
                    calc_apply_time: {
                        required: jsonLang['Hãy chọn giá trị tính']
                    },
                    start_effect_time: {
                        required: jsonLang['Hãy chọn ngày bắt đầu có hiệu lực']
                    },
                    end_effect_time: {
                        required: jsonLang['Hãy chọn ngày kết thúc hiệu lực']
                    },
                    contract_commission_time: {
                        required: jsonLang['Hãy nhập thời hạn hợp đồng'],
                        min: jsonLang['Thời hạn hợp đồng tối thiểu 1'],
                        max: jsonLang['Thời hạn hợp đồng tối đa 12']
                    }
                },
                invalidHandler: function (e, r) {

                },
                submitHandler: function (e) {
                    commission.add();
                }
            });
        }
    }
}();

Array.prototype.remove = function () {
    var what, a = arguments, L = a.length, ax;
    while (L && this.length) {
        what = a[--L];
        while ((ax = this.indexOf(what)) !== -1) {
            this.splice(ax, 1);
        }
    }
    return this;
};
