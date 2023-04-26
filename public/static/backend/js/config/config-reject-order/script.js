var view = {
    _init: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            $('.province_id').select2({
                placeholder: json['Chọn tỉnh/thành']
            });

            $('.district_id').select2({
                placeholder: json['Chọn quận/huyện']
            });
        });
    },
    changeProvince: function (obj) {
        $.ajax({
            url: laroute.route('admin.customer.load-district'),
            dataType: 'JSON',
            data: {
                id_province: $(obj).val(),
            },
            method: 'POST',
            success: function (res) {
                $(obj).closest('.div_object').find($('.district_id')).empty();

                $.map(res.optionDistrict, function (a) {
                    $(obj).closest('.div_object').find($('.district_id')).append('<option value="' + a.id + '">' + a.type + ' ' + a.name + '</option>');
                });
            }
        });
    },
    addObject: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var continute = true;

            //check các trường dữ liệu rỗng thì báo lỗi
            $.each($('.div_province').find(".div_object"), function () {
                var provinceId = $(this).find($('.province_id')).val();
                var districtId = $(this).find($('.district_id')).val();

                if (provinceId == '') {
                    $(this).find($('.error_province_id')).text(json['Hãy chọn tỉnh thành']);
                    continute = false;
                } else {
                    $(this).find($('.error_province_id')).text('');
                }

                if (districtId == '') {
                    $(this).find($('.error_district_id')).text(json['Hãy chọn quận huyện']);
                    continute = false;
                } else {
                    $(this).find($('.error_district_id')).text('');
                }
            });

            if (continute == true) {
                //append div shift
                var tpl = $('#object-tpl').html();
                // tpl = tpl.replace(/{stt}/g, stt);
                $('.div_province').append(tpl);

                $('.province_id').select2({
                    placeholder: json['Chọn tỉnh/thành']
                });

                $('.district_id').select2({
                    placeholder: json['Chọn quận/huyện']
                });
            }
        });
    },
    removeObject: function (obj) {
        $(obj).closest('.div_object').remove();
    },
    save: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var continute = true;

            var listProvince = [];

            //check các trường dữ liệu rỗng thì báo lỗi
            $.each($('.div_province').find(".div_object"), function () {
                var provinceId = $(this).find($('.province_id')).val();
                var districtId = $(this).find($('.district_id')).val();

                if (provinceId == '') {
                    $(this).find($('.error_province_id')).text(json['Hãy chọn tỉnh thành']);
                    continute = false;
                } else {
                    $(this).find($('.error_province_id')).text('');
                }

                if (districtId == '') {
                    $(this).find($('.error_district_id')).text(json['Hãy chọn quận huyện']);
                    continute = false;
                } else {
                    $(this).find($('.error_district_id')).text('');
                }

                listProvince.push({
                    province_id: provinceId,
                    district_id: districtId,
                });
            });


            if (continute == true) {
                $.ajax({
                    url: laroute.route('config.reject-order.save'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        listProvince: listProvince
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
                        swal(json['Lưu thất bại'], mess_error, "error");
                    }
                });
            }
        });
    }
};