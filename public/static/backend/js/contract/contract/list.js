$(document).ready(function () {
    listContract.translateJson = JSON.parse(localStorage.getItem('tranlate'));
    
    var arrRange = {};
    arrRange[listContract.translateJson["Hôm nay"]] = [moment(), moment()];
    arrRange[listContract.translateJson["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
    arrRange[listContract.translateJson["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
    arrRange[listContract.translateJson["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
    arrRange[listContract.translateJson["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
    arrRange[listContract.translateJson["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
    $("#expired_date,#collection_date,#spend_date,#effective_date,#sign_date,#warranty_start_date,#warranty_end_date")
        .daterangepicker({
            autoUpdateInput: false,
            autoApply: true,
            locale: {
                cancelLabel: 'Clear',
                format: 'DD/MM/YYYY',
                // "applyLabel": "Đồng ý",
                // "cancelLabel": "Thoát",
                "customRangeLabel": listContract.translateJson['Tùy chọn ngày'],
                daysOfWeek: [
                    listContract.translateJson["CN"],
                    listContract.translateJson["T2"],
                    listContract.translateJson["T3"],
                    listContract.translateJson["T4"],
                    listContract.translateJson["T5"],
                    listContract.translateJson["T6"],
                    listContract.translateJson["T7"]
                ],
                "monthNames": [
                    listContract.translateJson["Tháng 1 năm"],
                    listContract.translateJson["Tháng 2 năm"],
                    listContract.translateJson["Tháng 3 năm"],
                    listContract.translateJson["Tháng 4 năm"],
                    listContract.translateJson["Tháng 5 năm"],
                    listContract.translateJson["Tháng 6 năm"],
                    listContract.translateJson["Tháng 7 năm"],
                    listContract.translateJson["Tháng 8 năm"],
                    listContract.translateJson["Tháng 9 năm"],
                    listContract.translateJson["Tháng 10 năm"],
                    listContract.translateJson["Tháng 11 năm"],
                    listContract.translateJson["Tháng 12 năm"]
                ],
                "firstDay": 1
            },
            ranges: arrRange
        });
    $('.select').select2();
    new AutoNumeric.multiple('#total_amount,#tax', {
        currencySymbol: '',
        decimalCharacter: '.',
        digitGroupSeparator: ',',
        decimalPlaces: decimal_number,
        eventIsCancelable: true,
        minimumValue: 0
    });
    $('#contract_category_id').change(function () {
        $.ajax({
            url: laroute.route('contract.contract.load-status'),
            dataType: 'JSON',
            data: {
                contract_category_id: $('#contract_category_id').val(),
            },
            method: 'POST',
            success: function (res) {
                $('#status_code').empty();
                $('#status_code').append('<option value="">' + listContract.translateJson['Chọn trạng thái'] + '</option>');
                $.map(res.optionStatus, function (a) {
                    $('#status_code').append('<option value="' + a.status_code + '">' + a.status_name + '</option>');
                });
                $('#status_code').trigger('change');
            }
        });
    });

});

var listContract = {
    translateJson: null,
    clickRemove: function (obj, contractId) {
        
        swal({
            title: listContract.translateJson['Thông báo'],
            text: listContract.translateJson["Bạn có muốn xóa không?"],
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: listContract.translateJson['Xóa'],
            cancelButtonText: listContract.translateJson['Hủy'],
        }).then(function (result) {
            if (result.value) {
                var checkIsReason = $(obj).closest('tr').find($("input[name='is_reason']")).val();

                if (checkIsReason == 1) {
                    //Show modal nhập lý do
                    $.ajax({
                        url: laroute.route('contract.contract.show-modal-reason'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            contract_id: contractId
                        },
                        success: function (res) {
                            $('#my-modal').html(res.html);
                            $('#modal-remove-contract').modal('show');
                        }
                    });
                } else {
                    //Xoá hợp đồng
                    listContract.destroy(contractId, null);
                }
            }
        });
    },
    clickRemoveReason: function (contractId) {
        
        var form = $('#form-remove');

        form.validate({
            rules: {
                reason: {
                    required: true,
                    maxlength: 190
                }
            },
            messages: {
                reason: {
                    required: listContract.translateJson['Lý do xoá không được trống'],
                    maxlength: listContract.translateJson['Lý do xoá tối đa 190 kí tự']
                }
            },
        });

        if (!form.valid()) {
            return false;
        }

        listContract.destroy(contractId, $('#reason').val());
    },
    destroy: function (contractId, reason) {
        $.ajax({
            url: laroute.route('contract.contract.destroy'),
            method: "POST",
            dataType: 'JSON',
            data: {
                contract_id: contractId,
                reason: reason
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                            $('#modal-remove-contract').modal('hide');

                            $('#autotable').PioTable('refresh');
                        }
                        if (result.value == true) {
                            $('#modal-remove-contract').modal('hide');

                            $('#autotable').PioTable('refresh');
                        }
                    });
                } else {
                    swal(res.message, '', "error");
                }
            }
        });
    },
    export: function () {
        var params = {
            search: $('[name="search"]').val(),
            contract_category_id: $('[name="contract_category_id"]').val(),
            status_code: $('[name="status_code"]').val(),
            customer_group_id: $('[name="customer_group_id"]').val(),
            expired_date: $('[name="expired_date"]').val(),
            sign_date: $('[name="sign_date"]').val(),
            staff_id: $('[name="staff_id"]').val(),
            staff_title_id: $('[name="staff_title_id"]').val(),
            department_id: $('[name="department_id"]').val(),
            contract_tag_id: $('[name="contract_tag_id"]').val(),
            tax: $('[name="tax"]').val(),
            compare_total_amount: $('[name="compare_total_amount"]').val(),
            total_amount: $('[name="total_amount"]').val(),
            payment_method_id: $('[name="payment_method_id"]').val(),
            warranty_start_date: $('[name="warranty_start_date"]').val(),
            warranty_end_date: $('[name="warranty_end_date"]').val(),
        };
        var url = laroute.route('contract.contract.export-excel') + '?' + $.param(params);
        window.location = url;

    },
    showModalImport: function () {
        $.ajax({
            url: laroute.route('contract.contract.show-modal-import'),
            method: 'POST',
            dataType: 'JSON',
            data: {},
            success: function (res) {
                $('#my-modal').html(res.html);
                $('#modal-excel').modal('show');
            }
        });
    },
    fileName: function () {
        var fileNamess = $('input[type=file]').val();
        $('#show').val(fileNamess);
    },
    importExcel: function () {
        mApp.block(".modal-body", {
            overlayColor: "#000000",
            type: "loader",
            state: "success",
            message: "Xin vui lòng chờ..."
        });

        var file_data = $('#file_excel').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        $.ajax({
            url: laroute.route("contract.contract.import-excel"),
            method: "POST",
            data: form_data,
            contentType: false,
            cache: false,
            processData: false,
            success: function (res) {
                mApp.unblock(".modal-body");

                if (res.error == false) {
                    swal(res.message, "", "success");
                    $('#autotable').PioTable('refresh');

                    if (res.number_error > 0) {
                        $('.export_error').css('display', 'block');
                        $('#data_error').empty();

                        $.map(res.data_error, function (val) {
                            var tpl = $('#tpl-data-error').html();
                            tpl = tpl.replace(/{contract_no}/g, val.contract_no);
                            tpl = tpl.replace(/{contract_name}/g, val.contract_name);
                            tpl = tpl.replace(/{contract_category_name}/g, val.contract_category_name);
                            tpl = tpl.replace(/{partner_type}/g, val.partner_type);
                            tpl = tpl.replace(/{partner_name}/g, val.partner_name);
                            tpl = tpl.replace(/{partner_phone}/g, val.partner_phone);
                            tpl = tpl.replace(/{sign_date}/g, val.sign_date);
                            tpl = tpl.replace(/{effective_date}/g, val.effective_date);
                            tpl = tpl.replace(/{expired_date}/g, val.expired_date);
                            tpl = tpl.replace(/{performer_by}/g, val.performer_by);
                            tpl = tpl.replace(/{sign_by}/g, val.sign_by);
                            tpl = tpl.replace(/{follow_by}/g, val.follow_by);
                            tpl = tpl.replace(/{warranty_start_date}/g, val.warranty_start_date);
                            tpl = tpl.replace(/{warranty_end_date}/g, val.warranty_end_date);
                            tpl = tpl.replace(/{error}/g, val.error);
                            $('#data_error').append(tpl);
                        });

                        //Download file lỗi sẵn
                        $("#form-error").submit();
                    } else {
                        $('.export_error').css('display', 'none');
                        $('#data_error').empty();
                    }
                } else {
                    swal(res.message, '', "error");
                }
            }
        });
    }
};

//Hàm định dạng số.
function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}

$('#autotable').PioTable({
    baseUrl: laroute.route('contract.contract.list')
});