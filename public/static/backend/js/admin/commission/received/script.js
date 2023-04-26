var jsonLang = JSON.parse(localStorage.getItem('tranlate'));

var listStaff = {
    _init: function () {
        $(document).ready(function () {
            var arrRange = {};
            arrRange[jsonLang["Hôm nay"]] = [moment(), moment()];
            arrRange[jsonLang["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
            arrRange[jsonLang["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
            arrRange[jsonLang["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
            arrRange[jsonLang["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
            arrRange[jsonLang["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];

            $("#commission_day").daterangepicker({
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
                    "customRangeLabel": jsonLang['Tùy chọn ngày'],
                    daysOfWeek: [
                        jsonLang["CN"],
                        jsonLang["T2"],
                        jsonLang["T3"],
                        jsonLang["T4"],
                        jsonLang["T5"],
                        jsonLang["T6"],
                        jsonLang["T7"]
                    ],
                    "monthNames": [
                        jsonLang["Tháng 1 năm"],
                        jsonLang["Tháng 2 năm"],
                        jsonLang["Tháng 3 năm"],
                        jsonLang["Tháng 4 năm"],
                        jsonLang["Tháng 5 năm"],
                        jsonLang["Tháng 6 năm"],
                        jsonLang["Tháng 7 năm"],
                        jsonLang["Tháng 8 năm"],
                        jsonLang["Tháng 9 năm"],
                        jsonLang["Tháng 10 năm"],
                        jsonLang["Tháng 11 năm"],
                        jsonLang["Tháng 12 năm"]
                    ],
                    "firstDay": 1
                },
                ranges: arrRange
            });

            $('#autotable-staff').PioTable({
                baseUrl: laroute.route('admin.commission.list-received')
            });
        });
    },
    showPopEdit: function (idStaff) {
        $.ajax({
            url: laroute.route('admin.commission.show-pop-edit-received'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                staff_id: idStaff
            },
            success: function (res) {
                $('#my-modal').html(res.html);
                $('#modal-edit').modal('show');

                new AutoNumeric.multiple('.commission_coefficient', {
                    currencySymbol: '',
                    decimalCharacter: '.',
                    digitGroupSeparator: ',',
                    decimalPlaces: 2,
                    eventIsCancelable: true,
                    minimumValue: 0
                });
            }
        });
    },
    removeTr: function (obj) {
        $(obj).closest('tr').remove();
    },
    submitEdit: function (idStaff) {
        var arrData = [];

        $.each($('#table-allocation').find(".tr_allocation"), function (k, v) {
            var commission_id = $(this).closest('.tr_allocation').find('.commission_id').val();
            var commission_coefficient = $(this).closest('.tr_allocation').find('.commission_coefficient').val().replace(new RegExp('\\,', 'g'), '');

            arrData.push({
                commission_id: commission_id,
                commission_coefficient: commission_coefficient
            })
        });

        $.ajax({
            url: laroute.route('admin.commission.submit-edit-received'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                staff_id: idStaff,
                arrData: arrData
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                            $('#modal-edit').modal('hide');
                        }
                        if (result.value == true) {
                            $('#modal-edit').modal('hide');
                        }
                    });
                } else {
                    swal(res.message, '', "error");
                }
            }
        });
    }
};