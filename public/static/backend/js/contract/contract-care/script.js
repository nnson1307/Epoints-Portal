
$(document).ready(function () {
    $.getJSON(laroute.route('translate'), function (json) {
        var arrRange = {};
        arrRange[json["Hôm nay"]] = [moment(), moment()];
        arrRange[json["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
        arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
        arrRange[json["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
        arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
        arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
        $("#expire_date")
            .daterangepicker({
                autoUpdateInput: false,
                autoApply: true,
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
        $('.select-select-2').select2();
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
                    $('#status_code').append('<option value="">' + json['Chọn trạng thái'] + '</option>');
                    $.map(res.optionStatus, function (a) {
                        $('#status_code').append('<option value="' + a.status_code + '">' + a.status_name + '</option>');
                    });
                }
            });
        });
    });
});

var expireContract = {
    chooseAll: function (obj, type = 'expire') {
        if ($(obj).is(':checked')) {
            $('.check_one').prop('checked', true);
            let arrCheck = [];
            $('.check_one').each(function () {
                arrCheck.push({
                    contract_code: $(this).parents('label').find('.contract_code').val(),
                });
            });

            if (arrCheck.length > 0) {
                var url = type == 'expire' ?
                    laroute.route('contract.contract-care.choose-all-expire') :
                    laroute.route('contract.contract-care.choose-all-soon-expire');
                $.ajax({
                    url: url,
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        arr_check: arrCheck
                    }
                });
            }
        } else {
            $('.check_one').prop('checked', false);

            var arrUnCheck = [];
            $('.check_one').each(function () {
                arrUnCheck.push({
                    contract_code: $(this).parents('label').find('.contract_code').val(),

                });
            });

            if (arrUnCheck.length > 0) {
                var url = type == 'expire' ?
                    laroute.route('contract.contract-care.un-choose-all-expire') :
                    laroute.route('contract.contract-care.un-choose-all-soon-expire');
                $.ajax({
                    url: url,
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        arr_un_check: arrUnCheck
                    }
                });
            }
        }
    },
    choose: function (obj, type = 'expire') {
        if ($(obj).is(":checked")) {
            let contractCode = '';
            contractCode = $(obj).parents('label').find('.contract_code').val();
            var url = type == 'expire' ?
                laroute.route('contract.contract-care.choose-expire') :
                laroute.route('contract.contract-care.choose-soon-expire');
            $.ajax({
                url: url,
                method: 'POST',
                dataType: 'JSON',
                data: {
                    contract_code: contractCode,
                }
            });
        } else {
            let contractCode = '';
            contractCode = $(obj).parents('label').find('.contract_code').val();

            var url = type == 'expire' ?
                laroute.route('contract.contract-care.un-choose-expire') :
                laroute.route('contract.contract-care.un-choose-soon-expire');
            $.ajax({
                url: url,
                method: 'POST',
                dataType: 'JSON',
                data: {
                    contract_code: contractCode,
                }
            });
        }
    },
    perform: function (type) {
        $.getJSON(laroute.route('translate'), function (json) {
            let arrCheck = [];
            $('.check_one').each(function () {
                if($(this).is(':checked')){
                    arrCheck.push({
                        contract_code: $(this).parents('label').find('.contract_code').val(),
                    });
                }
            });
            if(arrCheck.length == 0){
                swal.fire(json['Vui lòng chọn hợp đồng để chăm sóc'], '', 'error');
            }
            else{
                $.ajax({
                    url: laroute.route('contract.contract-care.popup-create-deal'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        type: type
                    },
                    success: function (res) {
                        $('#my-modal').html(res.html);
                        $('#modal-create').modal('show');

                        $("#pop_end_date_expected, #end_date_actual").datepicker({
                            todayHighlight: !0,
                            autoclose: !0,
                            format: "dd/mm/yyyy",
                            startDate: "dateToday"
                        });
                        $('#pop_staff').select2({
                            placeholder: json['Chọn người sở hữu']
                        });

                        $('#pop_pipeline_code').select2({
                            placeholder: json['Chọn pipeline']
                        });

                        $('#pop_journey_code').select2({
                            placeholder: json['Chọn hành trình']
                        });

                        $('#pop_pipeline_code').change(function () {
                            $.ajax({
                                url: laroute.route('customer-lead.load-option-journey'),
                                dataType: 'JSON',
                                data: {
                                    pipeline_code: $('#pop_pipeline_code').val(),
                                },
                                method: 'POST',
                                success: function (res) {
                                    $('.journey').empty();
                                    $.map(res.optionJourney, function (a) {
                                        $('.journey').append('<option value="' + a.journey_code + '">' + a.journey_name + '</option>');
                                    });
                                }
                            });
                        });
                    }
                });
            }
        });
    },
    createDeal: function (type) {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-create');
            form.validate({
                rules: {
                    deal_name: {
                        required: true,
                        maxlength: 191
                    },
                    staff: {
                        required: true
                    },
                    pipeline_code: {
                        required: true
                    },
                    journey_code: {
                        required: true
                    },
                    end_date_expected: {
                        required: true
                    },
                },
                messages: {
                    deal_name: {
                        required: json['Hãy nhập tên deal'],
                        maxlength: json['Tên deal tối đa 255 kí tự']
                    },
                    staff: {
                        required: json['Hãy chọn người sở hữu deal']
                    },
                    pipeline_code: {
                        required: json['Hãy chọn pipeline']
                    },
                    journey_code: {
                        required: json['Hãy chọn hành trình khách hàng']
                    },
                    end_date_expected: {
                        required: json['Hãy chọn ngày kết thúc dự kiến']
                    },
                },
            });

            if (!form.valid()) {
                return false;
            }
            $.ajax({
                url: laroute.route('contract.contract-care.submit-create-deal'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    deal_name: $('#pop_deal_name').val(),
                    staff: $('#pop_staff').val(),
                    pipeline_code: $('#pop_pipeline_code').val(),
                    journey_code: $('#pop_journey_code').val(),
                    end_date_expected: $('#pop_end_date_expected').val(),
                    type: type
                },
                success: function (res) {
                    if (res.error == false) {
                        swal(res.message, "", "success").then(function (result) {
                            if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                window.location.reload();
                            }
                            if (result.value == true) {
                                window.location.reload();
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
        });
    }
};
//Hàm định dạng số.
function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}
$('#autotable-expire').PioTable({
    baseUrl: laroute.route('contract.contract-care.expire.list')
});
$('#autotable-soon-expire').PioTable({
    baseUrl: laroute.route('contract.contract-care.expire.soon-list')
});