var SalaryData = {
    importExcel: function() {
        $('#modal-excel').modal('show');
        $('#show').val('');
        $('input[type=file]').val('');
    },

    importSubmit: function() {
        mApp.block(".modal-body", {
            overlayColor: "#000000",
            type: "loader",
            state: "success",
            message: "Xin vui lòng chờ..."
        });

        var file_data = $('#file_excel').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('salary_id', $('#salary_id').val());
        $.ajax({
            url: laroute.route("salary.import-excel-salary"),
            method: "POST",
            data: form_data,
            contentType: false,
            cache: false,
            processData: false,
            success: function(res) {

                // mApp.unblock(".modal-body");
                if (res.success == 1) {
                    swal(res.message, "", "success").then(function() {
                        location.reload();
                    });
                } else {
                    swal(res.message, '', "error");
                }
            }
        });
    },
    fileName: function() {
        var fileNamess = $('input[type=file]').val();
        $('#show').val(fileNamess);
    },
    closeModalImport: function() {
        $('#modal-excel').modal('hide');
        $('#autotable').PioTable('refresh');
    },

    lockSalary: function(salaryId) {

        Swal.fire({
            title: 'Thông báo',
            text: "Bạn có chắc muốn khoá bảng lương không?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Khoá',
            cancelButtonText: 'Huỷ',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: laroute.route('salary.lock-salary'),
                    data: {
                        salary_id: salaryId
                    },
                    success: function(res) {
                        if (res.error == false) {
                            swal(res.message, '', "success").then(function() {
                                window.location.href = laroute.route('salary');
                            });
                        } else {
                            swal(res.message, '', "error");
                        }
                    }
                });
            }
        })
    },

    showModal: function(salaryId) {
        $.ajax({
            type: "POST",
            url: laroute.route('salary.show-modal-edit-salary'),
            data: {
                salary_id: salaryId
            },
            success: function(res) {
                if (res.error == false) {
                    $('#addModel').empty();
                    $('#addModel').append(res.view);
                    $('#modalEdit').modal('show');
                } else {
                    swal(res.message, '', "error");
                }
            }
        });
    },

    editClose: function() {
        $.ajax({
            type: "POST",
            url: laroute.route('salary.edit-salary'),
            data: $('#editForm').serialize(),
            success: function(res) {
                if (res.error == false) {
                    swal(res.message, '', "success").then(function() {
                        location.reload();
                    });
                } else {
                    swal(res.message, '', "error");
                }
            },
            error: function(res) {
                var mess_error = '';
                $.map(res.responseJSON.errors, function(a) {
                    mess_error = mess_error.concat(a + '<br/>');
                });
                swal('', mess_error, "error");
            }
        });
    },

    changeMoney: function() {
        var salary = formatNumberFix($('#salary').val());
        var total_commission = $('#total_commission').val();
        var total_kpi = $('#total_kpi').val();
        var total_allowance = $('#total_allowance').val();
        var plus = $('#plus').val();
        var minus = $('#minus').val();

        salary = checkNumber(salary);
        total_commission = checkNumber(total_commission);
        total_kpi = checkNumber(total_kpi);
        total_allowance = checkNumber(total_allowance);
        plus = checkNumber(plus);
        minus = checkNumber(minus);

        var total = salary + total_commission + total_kpi + total_allowance + plus - minus;

        $('#total').val(formatNumberFix(total));
    },

    saveChangeMoney: function() {
        let minus = $('#minus').val().replace(new RegExp('\\,', 'g'), '');
        let salarry = $('#salary').val().replace(new RegExp('\\,', 'g'), '');
        if (minus > salarry) {
            swal("Tiền giảm lương phải bé hơn hoặc bằng lương cơ bản", '', "error");
            return false;
        }
        $.ajax({
            type: "POST",
            url: laroute.route('salary.edit-salary-save'),
            data: $('#editSalary').serialize(),
            success: function(res) {
                if (res.error == false) {
                    swal(res.message, '', "success").then(function() {
                        // location.reload();
                        $('#cancle-button')[0].click();
                    });
                } else {
                    swal(res.message, '', "error");
                }
            },
            error: function(res) {
                var mess_error = '';
                $.map(res.responseJSON.errors, function(a) {
                    mess_error = mess_error.concat(a + '<br/>');
                });
                swal('', mess_error, "error");
            }
        });
    },

    showTableCommission: function() {
        $.ajax({
            type: "POST",
            url: laroute.route('salary.show-table-commission'),
            data: {
                page: $('#page').val(),
                salary_staff_id: $('#salary_staff_id').val()
            },
            success: function(res) {

                if (res.error == false) {
                    $('.table-commission').empty();
                    $('.table-commission').append(res.view);
                } else {
                    swal(res.message, '', "error");
                }
            }
        });
    },

    changePage(page) {
        $('#page').val(page);
        SalaryData.showTableCommission();
    },

    exportSalaryCommission: function(salaryId, type = "kt") {
        console.log(salaryId)
        if (!salaryId) {
            return;
        }
        data = {
            salary_id: salaryId,
            department_id: $('.frmFilter [name="department_id"]').val(),
            type: type,
        };
        submitData(laroute.route('salary.export-excel-salary-commission'), data, "GET");
    }
}

function checkNumber(value) {
    if (value == '') {
        value = '0';
    }

    var totalNumber = value.replace(/[^0-9 ]/g, "");

    return parseInt(totalNumber);
}

function formatNumberFix(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
}

function submitData(path, parameters, method = "get") {
    var form = $('<form></form>');

    form.attr("method", method);
    form.attr("action", path);

    $.each(parameters, function(key, value) {
        var field = $('<input></input>');

        field.attr("type", "hidden");
        field.attr("name", key);
        field.attr("value", value);

        form.append(field);
    });

    // The form needs to be a part of the document in
    // order for us to be able to submit it.
    $(document.body).append(form);
    form.submit();
}