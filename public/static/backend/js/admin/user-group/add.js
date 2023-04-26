

$('.select-2').select2();
var userGroupAuto = {
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
        if (ROUTE == '' || ID_SURVEY == '') {
            if (countCondition >= 16) {
                $('.btn-add-condition-A').hide();
            }
        } else {
            if (countCondition >= 19) {
                $('.btn-add-condition-A').hide();
            }
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
        if (ROUTE == '' || ID_SURVEY == '') {
            if (countCondition >= 16) {
                $('.btn-add-condition-B').hide();
            }
        } else {
            if (countCondition >= 19) {
                $('.btn-add-condition-B').hide();
            }
        }

    },
    loadCondition: function (type) {
        var jsonLang = JSON.parse(localStorage.getItem('tranlate'));
        var arrayCondition = [0];
        if (type == 'A') {
            //Danh sách điều kiện A.
            $('.condition-A').each(function () {
                var val = $(this).val();
                arrayCondition.push(val);
            });
        } else {
            //Danh sách điều kiện B.
            $('.condition-B').each(function () {
                var val = $(this).val();
                arrayCondition.push(val);
            });
        }
        // if (ROUTE == '' || ID_SURVEY == '') {
        //     arrayConditionSurvey = [18, 19, 20, 21];
        //     arrayCondition = arrayCondition.concat(arrayConditionSurvey);
        // }
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
    addItemAddress: function (t) {
        var flag = true;
        $('.province_id').each(function (index, item) {
            if ($(item).val() == '') {
                flag = false;
            }
        })
        if (flag) {
            var divContentConditionAddress = $(t).parents('.A-condition-1').find('.div-content-condition-address .list__address');
            var tpl = $('#tpl-address').html();
            divContentConditionAddress.append(tpl);
        }
    },
    addItemAddressB: function (t) {
        var flag = true;
        $('.province_id_b').each(function (index, item) {
            if ($(item).val() == '') {
                flag = false;
            }
        })
        if (flag) {
            var divContentConditionAddress = $(t).parents('.B-condition-1').find('.div-content-condition-address .list__address');
            var tpl = $('#tpl-address-b').html();
            divContentConditionAddress.append(tpl);
        }
    },
    removeItemAddress: function (t) {
        $(t).closest('.item__address').remove();
    },
    chooseConditionA: function (t) {
        var idCondition = $(t).val();
        var divContentCondition = $(t).parents('.A-condition-1').find('.div-content-condition');
        var divContentConditionAddress = $(t).parents('.A-condition-1').find('.div-content-condition-address .list__address');
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

        if (idCondition == 18) {
            divContentConditionAddress.append(tpl);
            $(t).parents('.A-condition-1').find(".button__addd--address button").show();
        } else {
            divContentCondition.append(tpl);
        }
        userGroupAuto.select2();
    },
    chooseConditionB: function (t) {
        var idCondition = $(t).val();
        var divContentCondition = $(t).parents('.B-condition-1').find('.div-content-condition');
        var divContentConditionAddress = $(t).parents('.B-condition-1').find('.div-content-condition-address .list__address');
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
            tpl = $('#tpl-address-b').html();
        } else if (idCondition == 19) {
            tpl = $('#tpl-type_customer').html();
        } else if (idCondition == 20) {
            tpl = $('#tpl-group_customer').html();
        } else if (idCondition == 21) {
            tpl = $('#tpl-source_customer').html();
        }

        if (idCondition == 18) {
            divContentConditionAddress.append(tpl);
            $(t).parents('.B-condition-1').find(".button__addd--address_b button").show();
        } else {
            divContentCondition.append(tpl);
        }
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
        var errorName = $('.error-name');
        var andOrA = $('#A-or-and').val();
        var andOrB = $('#B-or-and').val();
        var arrayConditionA = [];
        var arrayConditionB = [];
        if (ROUTE != '' || ID_SURVEY != '') {
            var isSurvey = 'Y';
        } else {
            var isSurvey = null;

        }
        var flag = true;
        $('.A-condition-1').each(function () {
            var condition = $(this).find('.condition-A').val();
            var value = $(this).find('.chooses-condition-A').val();
            if (value == 'on') {
                value = $(this).find('.chooses-condition-A').is(":checked") ? 'on' : '0';
            }

            if (condition != '' && value != '') {
                if (condition == 18) {
                    listAddress = [];
                    $('.div-A-1-condition .div-content-condition-address .list__address .item__address').each(function (index, item) {
                        var province = $(item).find('.province_id').val();
                        var district = $(item).find('.district_id').val();
                        var ward = $(item).find('.ward_main').val();
                        if (province != '') {
                            itemAddress = { province: province, district: district, ward: ward }
                            listAddress.push(itemAddress)
                        }
                    })
                    var temp = { condition: condition, value: listAddress };
                    arrayConditionA.push(temp);

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
                    listAddress = [];
                    $('.div-B-1-condition .div-content-condition-address .list__address .item__address').each(function (index, item) {
                        var province = $(item).find('.province_id_b').val();
                        var district = $(item).find('.district_id_b').val();
                        var ward = $(item).find('.ward_main_b').val();
                        if (province != '') {
                            itemAddress = { province: province, district: district, ward: ward }
                            listAddress.push(itemAddress)
                        }
                    })
                    var temp = { condition: condition, value: listAddress };
                    arrayConditionB.push(temp);
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
                        url: laroute.route('admin.customer-group-filter.store-customer-group-auto'),
                        method: "POST",
                        data: {
                            name: name,
                            andOrA: andOrA,
                            andOrB: andOrB,
                            arrayConditionA: arrayConditionA,
                            arrayConditionB: arrayConditionB,
                            isSurvey: isSurvey,

                        },
                        success: function (res) {
                            if (res.error == false) {
                                swal.fire(json["Thêm thành công!"], "", "success").then(function (result) {
                                    if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                        if (ROUTE != '' && ID_SURVEY != '') {
                                            window.location.href = laroute.route('survey.edit-branch', { id: ID_SURVEY, type: 'customer_auto' });
                                        } else if (type == 0) {
                                            window.location.href = laroute.route('admin.customer-group-filter');
                                        } else {
                                            location.reload();
                                        }
                                    }
                                    if (result.value == true) {
                                        if (ROUTE != '' && ID_SURVEY != '') {
                                            window.location.href = laroute.route('survey.edit-branch', { id: ID_SURVEY, type: 'customer_auto' });
                                        } else if (type == 0) {
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
                                swal.fire(json['Thêm thất bại!'], mess_error, "error");
                            }
                        }
                    });
                }
            }
        });
    },
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
};

var getAddress = {

    getDistrict: function (o) {
        var district = $(o).parents(".item__address").find('.district_id');
        $.ajax({
            url: laroute.route('admin.customer.load-district'),
            dataType: 'JSON',
            data: {
                id_province: $(o).val(),
            },
            method: 'POST',
            success: function (res) {
                district.empty();
                $.map(res.optionDistrict, function (a) {
                    district.append('<option value="' + a.id + '">' + a.type + ' ' + a.name + '</option>');
                });
            }
        });
    },
    getDistrictB: function (o) {
        var district = $(o).parents(".item__address").find('.district_id_b');
        $.ajax({
            url: laroute.route('admin.customer.load-district'),
            dataType: 'JSON',
            data: {
                id_province: $(o).val(),
            },
            method: 'POST',
            success: function (res) {
                district.empty();
                $.map(res.optionDistrict, function (a) {
                    district.append('<option value="' + a.id + '">' + a.type + ' ' + a.name + '</option>');
                });
            }
        });
    },
    getWard: function (o) {
        var ward = $(o).parents('.item__address').find('.ward_main');
        $.ajax({
            url: laroute.route('admin.customer.load-ward'),
            dataType: 'JSON',
            data: {
                id_district: $(o).val(),
            },
            method: 'POST',
            success: function (res) {
                ward.empty();
                $.map(res.optionWard, function (a) {
                    ward.append('<option value="' + a.id + '">' + a.type + ' ' + a.name + '</option>');
                });
            }
        });

    },

    getWardB: function (o) {
        var ward = $(o).parents('.item__address').find('.ward_main_b');

        $.ajax({
            url: laroute.route('admin.customer.load-ward'),
            dataType: 'JSON',
            data: {
                id_district: $(o).val(),
            },
            method: 'POST',
            success: function (res) {
                ward.empty();
                $.map(res.optionWard, function (a) {
                    ward.append('<option value="' + a.id + '">' + a.type + ' ' + a.name + '</option>');
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
userGroupAuto.addConditionA();
userGroupAuto.addConditionB();




