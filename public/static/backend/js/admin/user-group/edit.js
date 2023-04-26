$('.select-2').select2();
var userGroupAuto = {
    setRangepointA: function () {
        var from = $('#range_point_from_a').val();
        var to = $('#range_point_to_a').val();
        $('#range_point_value_a').val(`${from},${to}`);
    },
    setRangepointB: function () {
        var from = $('#range_point_from_b').val();
        var to = $('#range_point_to_b').val();
        $('#range_point_value_b').val(`${from},${to}`);
    },
    addConditionA: function () {
        let flag = true;
        $('.condition-A').each(function () {
            var val = $(this).val();
            if (val == '') {
                flag = false;
            } else {
                if (flag == true) {
                    $(this).prop('disabled', true)
                }
            }
        });
        if (flag == true) {
            let tpl = $('#choose-condition-A').html();
            tpl = tpl.replace(/{option}/g, userGroupAuto.loadCondition('A'));
            $('.div-condition-A').append(tpl);
            $('.ss--select-2').select2();
        }
        var countCondition = 0;
        $('.div-condition-A .chooses-condition-A').each(function () {
            countCondition++;
        });
        if (countCondition >= 19) {
            $('.btn-add-condition-A').hide();
        }
    },
    addConditionB: function () {
        let flag = true;
        $('.condition-B').each(function () {
            var val = $(this).val();
            if (val == '') {
                flag = false;
            } else {
                if (flag == true) {
                    $(this).prop('disabled', true)
                }
            }
        });
        if (flag == true) {
            let tpl = $('#choose-condition-B').html();
            tpl = tpl.replace(/{option}/g, userGroupAuto.loadCondition('B'));
            $('.div-condition-B').append(tpl);
            $('.ss--select-2').select2();
        }
        var countCondition = 0;
        $('.div-condition-B .chooses-condition-A').each(function () {
            countCondition++;
        });
        if (countCondition >= 19) {
            $('.btn-add-condition-B').hide();
        }
    },
    loadCondition: function (type) {
        var arrayCondition = [0];
        var jsonLang = JSON.parse(localStorage.getItem('tranlate'));
        if (type == 'A') {
            //Danh sách điều kiện A.
            $('.condition-A').each(function () {
                var val = $(this).val();
                arrayCondition.push(val);
            });
        } else {
            //Danh sách điều kiện A.
            $('.condition-B').each(function () {
                var val = $(this).val();
                arrayCondition.push(val);
            });
        }
        var option = '';
        $.ajax({
            url: laroute.route('admin.customer-group-filter.get-condition'),
            method: "POST",
            data: { arrayCondition: arrayCondition },
            async: false,
            success: function (res) {

                $.each(res, function (key, value) {
                    option += "<option value='" + value.id + "'>" + jsonLang[value.name] + "</option>";
                })

            }
        });
        return option;
    },
    removeConditionA: function (t) {
        $(t).closest('.A-condition-1').remove();
        $('.btn-add-condition-A').show();
    },
    removeConditionB: function (t) {
        $(t).closest('.B-condition-1').remove();
        $('.btn-add-condition-B').show();
    },
    chooseConditionA: function (t) {
        var idCondition = $(t).val();
        var divContentCondition = $(t).parents('.A-condition-1').find('.div-content-condition');
        var tpl;
        divContentCondition.empty();
        if (idCondition == '') {
            divContentCondition.empty();
        } else if (idCondition == 1) {
            tpl = $('#tpl-customer-group-define').html();
        } else if (idCondition == 2) {
            tpl = $('#tpl-day-appointment').html();
        } else if (idCondition == 3) {
            tpl = $('#tpl-status_appointment').html();
        } else if (idCondition == 4) {
            tpl = $('#tpl-time_appointment').html();
        } else if (idCondition == 5) {
            tpl = $('#tpl-not_appointment').html();
        } else if (idCondition == 6) {
            tpl = $('#tpl-use_service').html();
        } else if (idCondition == 7) {
            tpl = $('#tpl-use_service').html();
        } else if (idCondition == 8) {
            tpl = $('#tpl-use_product').html();
        } else if (idCondition == 9) {
            tpl = $('#tpl-use_product').html();
        } else if (idCondition == 10) {
            tpl = $('#tpl-day-appointment').html();
        } else if (idCondition == 11) {
            tpl = $('#tpl-not_appointment').html();
        } else if (idCondition == 12) {
            tpl = $('#tpl-not_appointment').html();
        } else if (idCondition == 13) {
            tpl = $('#tpl-is_rank').html();
        } else if (idCondition == 14) {
            tpl = $('#tpl-range_point_a').html();
        } else if (idCondition == 15) {
            tpl = $('#tpl-day-appointment').html();
        } else if (idCondition == 16) {
            tpl = $('#tpl-day-appointment').html();
        } else if (idCondition == 17) {
            tpl = $('#tpl-use_service_card').html();
        } else if (idCondition == 18) {
            tpl = $('#tpl-address').html();
        } else if (idCondition == 19) {
            tpl = $('#tpl-type_customer').html();
        } else if (idCondition == 20) {
            tpl = $('#tpl-group_customer').html();
        } else if (idCondition == 21) {
            tpl = $('#tpl-source_customer').html();
        }
        divContentCondition.append(tpl);
        userGroupAuto.select2();
    },
    chooseConditionB: function (t) {
        var idCondition = $(t).val();
        var divContentCondition = $(t).parents('.B-condition-1').find('.div-content-condition');
        var tpl;
        divContentCondition.empty();
        if (idCondition == '') {
            divContentCondition.empty();
        } else if (idCondition == 1) {
            tpl = $('#tpl-customer-group-define').html();
        } else if (idCondition == 2) {
            tpl = $('#tpl-day-appointment').html();
        } else if (idCondition == 3) {
            tpl = $('#tpl-status_appointment').html();
        } else if (idCondition == 4) {
            tpl = $('#tpl-time_appointment').html();
        } else if (idCondition == 5) {
            tpl = $('#tpl-not_appointment').html();
        } else if (idCondition == 6) {
            tpl = $('#tpl-use_service').html();
        } else if (idCondition == 7) {
            tpl = $('#tpl-use_service').html();
        } else if (idCondition == 8) {
            tpl = $('#tpl-use_product').html();
        } else if (idCondition == 9) {
            tpl = $('#tpl-use_product').html();
        } else if (idCondition == 10) {
            tpl = $('#tpl-day-appointment').html();
        } else if (idCondition == 11) {
            tpl = $('#tpl-not_appointment').html();
        } else if (idCondition == 12) {
            tpl = $('#tpl-not_appointment').html();
        } else if (idCondition == 13) {
            tpl = $('#tpl-is_rank').html();
        } else if (idCondition == 14) {
            tpl = $('#tpl-range_point_b').html();
        } else if (idCondition == 15) {
            tpl = $('#tpl-day-appointment').html();
        } else if (idCondition == 16) {
            tpl = $('#tpl-day-appointment').html();
        } else if (idCondition == 17) {
            tpl = $('#tpl-use_service_card').html();
        } else if (idCondition == 18) {
            tpl = $('#tpl-address').html();
        } else if (idCondition == 19) {
            tpl = $('#tpl-type_customer').html();
        } else if (idCondition == 20) {
            tpl = $('#tpl-group_customer').html();
        } else if (idCondition == 21) {
            tpl = $('#tpl-source_customer').html();
        }
        divContentCondition.append(tpl);
        userGroupAuto.select2();
    },
    select2: function () {
        $('.ss--select-2').select2();
        $(".inputmask").inputmask({
            "mask": "9",
            "repeat": 10,
            "greedy": false
        });
    },
    save: function (type) {
        var name = $('#name').val();
        var id = $('#customer_group_id_').val();
        var errorName = $('.error-name');
        var andOrA = $('#A-or-and').val();
        var andOrB = $('#B-or-and').val();
        var arrayConditionA = [];
        var arrayConditionB = [];
        var flag = true;
        $('.A-condition-1').each(function () {
            var condition = $(this).find('.condition-A').val();
            var value = $(this).find('.chooses-condition-A').val();
            if (value == 'on') {
                value = $(this).find('.chooses-condition-A').is(":checked") ? 'on' : '0';
            }
            if (condition != '' && value != '') {
                if (condition == 18) {
                    province = $('#province_id').val();
                    district = $('#district_id').val();
                    if (district != '') {
                        var temp = { condition: condition, value: { province: province, district: district } };
                        arrayConditionA.push(temp);
                    }
                } else {
                    var temp = { condition: condition, value: value };
                    arrayConditionA.push(temp);
                }
            }
        });

        $('.B-condition-1').each(function () {
            var condition = $(this).find('.condition-B').val();
            var value = $(this).find('.chooses-condition-A').val();
            if (value == 'on') {
                value = $(this).find('.chooses-condition-A').is(":checked") ? 'on' : '0';
            }
            if (condition != '' && value != '') {
                if (condition == 18) {
                    province = $('#province_id_b').val();
                    district = $('#district_id_b').val();
                    if (district != '') {
                        var temp = { condition: condition, value: { province: province, district: district } };
                        arrayConditionB.push(temp);
                    }
                } else {
                    var temp = { condition: condition, value: value };
                    arrayConditionB.push(temp);
                }
            }
        });
        $.getJSON(laroute.route('translate'), function (json) {
            if (name == '') {
                errorName.text(json['Vui lòng nhập tên nhóm khách hàng']);
            } else {
                errorName.text('');
                if (flag == false) {
                    swal(json["Vui lòng chọn ít nhất 1 điều kiện bao gồm những người đáp ứng"], "", "error");
                } else {
                    $.ajax({
                        url: laroute.route('admin.customer-group-filter.submit-edit-auto'),
                        method: "POST",
                        data: {
                            id: id,
                            name: name,
                            andOrA: andOrA,
                            andOrB: andOrB,
                            arrayConditionA: arrayConditionA,
                            arrayConditionB: arrayConditionB,
                        },
                        success: function (res) {
                            if (res.error == false) {
                                swal.fire(json["Chỉnh sửa thành công!"], "", "success").then(function (result) {
                                    if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                        if (type == 0) {
                                            window.location.href = laroute.route('admin.customer-group-filter');
                                        } else {
                                            location.reload();
                                        }
                                    }
                                    if (result.value == true) {
                                        if (type == 0) {
                                            window.location.href = laroute.route('admin.customer-group-filter');
                                        } else {
                                            location.reload();
                                        }
                                    }
                                });
                            }
                        },
                        error: function (res) {
                            if (res.responseJSON != undefined) {
                                var mess_error = '';
                                $.map(res.responseJSON.errors, function (a) {
                                    mess_error = mess_error.concat(a + '<br/>');
                                });
                                swal.fire(json['Chỉnh sửa thất bại!'], mess_error, "error");
                            }
                        }
                    });
                }
            }
        });
    },
};

var getAddress = {

    getDistrict: function (o) {
        $.ajax({
            url: laroute.route('admin.customer.load-district'),
            dataType: 'JSON',
            data: {
                id_province: $(o).val(),
            },
            method: 'POST',
            success: function (res) {
                $('#district_id').empty();
                $.map(res.optionDistrict, function (a) {
                    $('#district_id').append('<option value="' + a.id + '">' + a.type + ' ' + a.name + '</option>');
                });
            }
        });
    },
    getDistrictB: function (o) {
        $.ajax({
            url: laroute.route('admin.customer.load-district'),
            dataType: 'JSON',
            data: {
                id_province: $(o).val(),
            },
            method: 'POST',
            success: function (res) {
                $('#district_id_b').empty();
                $.map(res.optionDistrict, function (a) {
                    $('#district_id_b').append('<option value="' + a.id + '">' + a.type + ' ' + a.name + '</option>');
                });
            }
        });
    }
}

$("#A_2").inputmask({
    "mask": "9",
    "repeat": 10,
    "greedy": false
});
$("#B_2").inputmask({
    "mask": "9",
    "repeat": 10,
    "greedy": false
});



$.ajax({
    url: laroute.route('admin.customer-group-filter.get-customer-in-group-auto'),
    method: "POST",
    data: { id: $('#customer_group_id_').val() },
    success: function (res) {

    }
});




