var jsonLang = JSON.parse(localStorage.getItem('tranlate'));

var viewVat = {
    //Thay đổi trạng thái
    changeStatus: function (obj, idVat) {
        var is_actived = 0;

        if ($(obj).is(':checked')) {
            is_actived = 1;
        }

        $.ajax({
            url: laroute.route('contract.vat.change-status'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                vat_id: idVat,
                is_actived: is_actived
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
    },

    //Show pop thêm VAT
    showPopCreate: function () {
        $.ajax({
            url: laroute.route('contract.vat.show-pop-create'),
            method: 'POST',
            dataType: 'JSON',
            data: {

            },
            success: function (res) {
                $('#my-modal').html(res.html);
                $('#modal-vat').modal('show');

                new AutoNumeric.multiple('#vat', {
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

    //Submit thêm mới VAT
    store: function () {
        var form = $('#form-vat');
        form.validate({
            rules: {
                vat: {
                    required: true,
                },
            },
            messages: {
                vat: {
                    required: jsonLang['Hãy nhập % VAT'],
                },
            }
        });

        if (!form.valid()) {
            return false;
        }

        $.ajax({
            url: laroute.route('contract.vat.store'),
            method: 'POST',
            dataType: "JSON",
            data: {
                vat: $('#vat').val().replace(new RegExp('\\,', 'g'), ''),
                description: $('#description').val()
            },
            success: function (res) {
                if (res.error == false) {
                    swal.fire(res.message, "", "success");

                    $('#modal-vat').modal('hide');
                    $('#autotable').PioTable('refresh');

                    $('#vat_id').append('<option value="'+ res.vat_id +'">'+ res.vat +'</option>');
                } else {
                    swal.fire(res.message, '', "error");
                }
            },
            error: function (res) {
                var mess_error = '';
                $.map(res.responseJSON.errors, function (a) {
                    mess_error = mess_error.concat(a + '<br/>');
                });
                swal(jsonLang['Thêm mới thất bại'], mess_error, "error");
            }
        });
    },

    //Show pop chỉnh sửa VAT
    showPopEdit: function (idVat) {
        $.ajax({
            url: laroute.route('contract.vat.show-pop-edit'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                vat_id: idVat
            },
            success: function (res) {
                $('#my-modal').html(res.html);
                $('#modal-vat').modal('show');

                new AutoNumeric.multiple('#vat', {
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

    //Submit thêm mới VAT
    update: function (idVat) {
        var form = $('#form-vat');

        form.validate({
            rules: {
                vat: {
                    required: true,
                },
            },
            messages: {
                vat: {
                    required: jsonLang['Hãy nhập % VAT'],
                },
            }
        });

        if (!form.valid()) {
            return false;
        }

        var is_actived = 0;
        if ($('#is_actived').is(":checked")) {
            is_actived = 1
        }

        $.ajax({
            url: laroute.route('contract.vat.update'),
            method: 'POST',
            dataType: "JSON",
            data: {
                vat_id: idVat,
                vat: $('#vat').val().replace(new RegExp('\\,', 'g'), ''),
                description: $('#description').val(),
                is_actived: is_actived
            },
            success: function (res) {
                if (res.error == false) {
                    swal.fire(res.message, "", "success");

                    $('#modal-vat').modal('hide');
                    $('#autotable').PioTable('refresh');
                } else {
                    swal.fire(res.message, '', "error");
                }
            },
            error: function (res) {
                var mess_error = '';
                $.map(res.responseJSON.errors, function (a) {
                    mess_error = mess_error.concat(a + '<br/>');
                });
                swal(jsonLang['Chỉnh sửa thất bại'], mess_error, "error");
            }
        });
    },
};

// $('#autotable').PioTable('refresh');

$('#autotable').PioTable({
    baseUrl: laroute.route('contract.vat.list')
});