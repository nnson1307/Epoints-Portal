var stt = 0;

var view = {
    _init: function () {
        $(document).ready(function () {
            $.getJSON(laroute.route('translate'), function (json) {
                $("#start_date, #end_date").datetimepicker({
                    todayHighlight: !0,
                    autoclose: !0,
                    // pickerPosition: "bottom-left",
                    format: "dd/mm/yyyy hh:ii",
                    // minDate: new Date(),
                    // locale: 'vi'
                });

                new AutoNumeric.multiple('#promotion_type_discount_value_same', {
                    currencySymbol: '',
                    decimalCharacter: '.',
                    digitGroupSeparator: ',',
                    decimalPlaces: decimal_number,
                    eventIsCancelable: true,
                    minimumValue: 0
                });
                // type = 2: gift, 1: percent
                if ($('input[name="promotion_type"]:checked').val() == 2) {
                    new AutoNumeric.multiple('#quota', {
                        currencySymbol: '',
                        decimalCharacter: '.',
                        digitGroupSeparator: ',',
                        decimalPlaces: 0,
                        eventIsCancelable: true,
                        minimumValue: 0
                    });
                } else {
                    new AutoNumeric.multiple('#quota', {
                        currencySymbol: '',
                        decimalCharacter: '.',
                        digitGroupSeparator: ',',
                        decimalPlaces: decimal_number,
                        eventIsCancelable: true,
                        minimumValue: 0
                    });
                }

                $('#promotion_type_discount_value_percent').ForceNumericOnly();

                $("#sortable").sortable();
                $("#sortable").disableSelection();

                $("#start_time, #end_time").timepicker({
                    minuteStep: 1,
                    defaultTime: "",
                    showMeridian: !1,
                    snapToStep: !0,
                });

                $('#branch_apply').select2().on('select2:select', function (event) {
                    if (event.params.data.id == 'all') {
                        $('#branch_apply').val('all').trigger('change');
                    } else {
                        var arrayChoose = [];

                        $.map($('#branch_apply').val(), function (val) {
                            if (val != 'all') {
                                arrayChoose.push(val);
                            }
                        });
                        $('#branch_apply').val(arrayChoose).trigger('change');
                    }
                }).on('select2:unselect', function (event) {
                    if ($('#branch_apply').val() == '') {
                        $('#branch_apply').val('all').trigger('change');
                    }
                });

                // $('#promotion_type_discount_value_same').css('display', 'none');

                $('#order_source').select2();

                $('#promotion_apply_to').select2({
                    placeholder: json['Chọn đối tượng áp dụng']
                });

                $('#member_level_id').select2({
                    placeholder: json['Chọn hạng thành viên']
                });

                $('#customer_group_id').select2({
                    placeholder: json['Chọn nhóm khách hàng']
                });

                $('#customer_id').select2({
                    placeholder: json['Chọn khách hàng']
                });

                $('#type_display_app').select2();

                $("#description_detail").summernote({
                    height: 208,
                    width: 1000,
                    placeholder: json['Nhập nội dung'],
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['fontname', ['fontname']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture', 'video']],
                        ['view', ['fullscreen', 'codeview', 'help']],
                    ],
                    callbacks: {
                        onImageUpload: function(files) {
                            for(let i=0; i < files.length; i++) {
                                uploadImg(files[i]);
                            }
                        }
                    },
                });

                if ($('#is_display').is(':checked')) {
                    $('.is_feature').css('display', 'flex');
                    $('.div_feature').css('display', 'block');
                } else {
                    $('.is_feature').css('display', 'none');
                    $('.div_feature').css('display', 'none');
                }
            });
        });
    },
    changePercent: function (obj) {
        if ($(obj).val().replace(new RegExp('\\,', 'g'), '') > 100) {
            $(obj).val(100);
        }

        $('#discount_value_percent').val($(obj).val().replace(new RegExp('\\,', 'g')));

        if ($('#table-discount > tbody').find('tr').length > 0) {
            $('.btn-search').trigger('click');
        }
    },
    changeSamePrice: function (obj) {
        $('#discount_value_same').val($(obj).val().replace(new RegExp('\\,', 'g'), ''));

        if ($('#table-discount > tbody').find('tr').length > 0) {
            $('.btn-search').trigger('click');
        }
    },
    changeType: function (val) {
        var tpl = $('#tpl-quota').html();
        $('.div_quota').html(tpl);

        if (val == 1) {
            $('.discount_value').css('display', 'block');

            new AutoNumeric.multiple('#quota', {
                currencySymbol: '',
                decimalCharacter: '.',
                digitGroupSeparator: ',',
                decimalPlaces: decimal_number,
                eventIsCancelable: true,
                minimumValue: 0
            });
        } else if (val == 2) {
            $('.discount_value').css('display', 'none');

            new AutoNumeric.multiple('#quota', {
                currencySymbol: '',
                decimalCharacter: '.',
                digitGroupSeparator: ',',
                decimalPlaces: 0,
                eventIsCancelable: true,
                minimumValue: 0
            });
        } else {
            $('.discount_value').css('display', 'none');
        }

        $('#autotable-discount').find('.div_table_discount').empty();
        $('#autotable-gift').find('.div_table_gift').empty();

        $.ajax({
            url: laroute.route('promotion.clear-list-all'),
            method: 'POST',
            dataType: 'JSON'
        });
    },
    changeTypeValue: function (val) {
        if (val == 'percent') {
            $('#promotion_type_discount_value_percent').css('display', 'block');
            $('#promotion_type_discount_value_same').css('display', 'none');
            $('#discount_type').val('percent');
        } else if (val == 'same') {
            $('#promotion_type_discount_value_percent').css('display', 'none');
            $('#promotion_type_discount_value_same').css('display', 'block');
            $('#discount_type').val('same');
        } else if (val == 'custom') {
            $('#promotion_type_discount_value_percent').css('display', 'none');
            $('#promotion_type_discount_value_same').css('display', 'none');
            $('#discount_type').val('custom');
        }

        if ($('#table-discount > tbody').find('tr').length > 0) {
            $('.btn-search').trigger('click');
        }
    },
    changeIsTime: function (obj) {
        if ($(obj).is(':checked')) {
            var tpl = $('#tpl-time').html();
            $('.div_time').html(tpl);

            $("#start_time, #end_time").timepicker({
                minuteStep: 1,
                defaultTime: "",
                showMeridian: !1,
                snapToStep: !0,
            });
        } else {
            $('.div_time').empty();
        }
    },
    changeTime: function (obj) {
        if (obj == 'D') {
            $('.weekly').empty();
            $('.monthly').empty();
            $('.form_to').empty();

            var tpl = $('#tpl-daily').html();
            $('.daily').html(tpl);

            $("#start_time, #end_time").timepicker({
                minuteStep: 1,
                defaultTime: "",
                showMeridian: !1,
                snapToStep: !0,
            });
        } else if (obj == 'W') {
            $('.daily').empty();
            $('.monthly').empty();
            $('.form_to').empty();

            var tpl = $('#tpl-weekly').html();
            $('.weekly').html(tpl);

            $("#default_start_time, " +
                "#default_end_time, " +
                "#is_other_monday_start_time, " +
                "#is_other_monday_end_time, " +
                "#is_other_tuesday_start_time, " +
                "#is_other_tuesday_end_time, " +
                "#is_other_wednesday_start_time, " +
                "#is_other_wednesday_end_time, " +
                "#is_other_thursday_start_time, " +
                "#is_other_thursday_end_time, " +
                "#is_other_friday_start_time, " +
                "#is_other_friday_end_time, " +
                "#is_other_saturday_start_time, " +
                "#is_other_saturday_end_time, " +
                "#is_other_sunday_start_time, " +
                "#is_other_sunday_end_time").timepicker({
                minuteStep: 1,
                defaultTime: "",
                showMeridian: !1,
                snapToStep: !0,
            });

        } else if (obj == 'M') {
            $('.daily').empty();
            $('.weekly').empty();
            $('.form_to').empty();

            var tpl = $('#tpl-monthly').html();
            $('.monthly').html(tpl);
        } else if (obj == 'R') {
            $('.daily').empty();
            $('.weekly').empty();
            $('.monthly').empty();

            var tpl = $('#tpl-from-to').html();
            $('.form_to').html(tpl);

            $('#form_date, #to_date').datepicker({
                // startDate: '0d',
                language: 'en',
                orientation: "bottom left", todayHighlight: !0,
                format: 'dd/mm/yyyy'
            });

            $("#start_time, #end_time").timepicker({
                minuteStep: 1,
                defaultTime: "",
                showMeridian: !1,
                snapToStep: !0,
            });
        }
    },
    checkAllWeek: function (obj) {
        if ($(obj).is(':checked')) {
            //Bật cờ thứ 2
            onDay('Monday');
            //Bật cờ thứ 3
            onDay('Tuesday');
            //Bật cờ thứ 4
            onDay('Wednesday');
            //Bật cờ thứ 5
            onDay('Thursday');
            //Bật cờ thứ 6
            onDay('Friday');
            //Bật cờ thứ 7
            onDay('Saturday');
            //Bật cờ chủ nhật
            onDay('Sunday');
        } else {
            //Tắt cờ thứ 2
            offDay('Monday');
            //Tắt cờ thứ 3
            offDay('Tuesday');
            //Tắt cờ thứ 4
            offDay('Wednesday');
            //Tắt cờ thứ 5
            offDay('Thursday');
            //Tắt cờ thứ 6
            offDay('Friday');
            //Tắt cờ thứ 7
            offDay('Saturday');
            //Tắt cờ chủ nhật
            offDay('Sunday');
        }
    },
    checkDay: function (obj, day) {
        if ($(obj).is(':checked')) {
            onDay(day);
        } else {
            offDay(day);
        }
    },
    checkOther: function (obj, day) {
        if ($(obj).is(':checked')) {
            onOther(day);
        } else {
            offOther(day);
        }
    },
    addTimeMonthly: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var continute = true;

            //check các trường dữ liệu rỗng thì báo lỗi
            $.each($('#table-monthly').find(".tr_monthly"), function () {
                var check_run_date = $(this).find($('.run_date')).val();
                var check_start_time = $(this).find($('.start_time')).val();
                var check_end_time = $(this).find($('.end_time')).val();
                var number = $(this).find($('.number')).val();

                if (check_start_time.length == 4) {
                    check_start_time = '0' + check_start_time;
                }
                if (check_end_time.length == 4) {
                    check_end_time = '0' + check_end_time;
                }

                if (check_run_date == '') {
                    $('.error_run_date_' + number + '').text(json['Hãy chọn ngày chạy']);
                    continute = false;
                } else {
                    $('.error_run_date_' + number + '').text('');
                }

                if (check_start_time == '') {
                    $('.error_start_time_' + number + '').text(json['Hãy chọn giờ bắt đầu']);
                    continute = false;
                } else {
                    $('.error_start_time_' + number + '').text('');
                }

                if (check_end_time == '') {
                    $('.error_end_time_' + number + '').text(json['Hãy chọn giờ kết thúc']);
                    continute = false;
                } else {
                    $('.error_end_time_' + number + '').text('');
                }

                if (check_start_time != '' && check_end_time != '' && check_start_time >= check_end_time) {
                    $('.error_start_time_' + number + '').text(json['Giờ bắt đầu phải lớn hơn giờ kết thúc']);
                    continute = false;
                }
            });

            if (continute == true) {
                stt++;
                //append tr table
                var tpl = $('#tpl-tr-monthly').html();
                tpl = tpl.replace(/{stt}/g, stt);
                $('#table-monthly > tbody').append(tpl);

                $('.run_date').datepicker({
                    startDate: '0d',
                    language: 'en',
                    orientation: "bottom left", todayHighlight: !0,
                    format: 'dd/mm/yyyy',
                });

                $(".start_time, .end_time").timepicker({
                    minuteStep: 1,
                    defaultTime: "",
                    showMeridian: !1,
                    snapToStep: !0,
                });
            }
        });
    },
    changeIsDisplay: function (obj) {
        if ($(obj).is(':checked')) {
            $('.is_feature').css('display', 'flex');
            $('.div_feature').css('display', 'block');
        } else {
            $('.is_feature').css('display', 'none');
            $('.div_feature').css('display', 'none');
        }
    },
    changeIsFeature: function (obj) {
        if ($(obj).is(':checked')) {
            $('.div_feature').css('display', 'block');
        } else {
            $('.div_feature').css('display', 'none');
        }
    },
    changeObjectApply: function (obj) {
        $.getJSON(laroute.route('translate'), function (json) {
            if (obj.value == 2) {
                var tpl = $('#tpl-member-level').html();
                $('.div_object_apply').html(tpl);


                $('#member_level_id').select2({
                    placeholder: json['Chọn hạng thành viên']
                });

            } else if (obj.value == 3) {
                var tpl = $('#tpl-customer-group').html();
                $('.div_object_apply').html(tpl);

                $('#customer_group_id').select2({
                    placeholder: json['Chọn nhóm khách hàng']
                });

            } else if (obj.value == 4) {
                var tpl = $('#tpl-customer').html();
                $('.div_object_apply').html(tpl);

                $('#customer_id').select2({
                    placeholder: json['Chọn khách hàng']
                });
            } else {
                $('.div_object_apply').empty();
            }
        });
    },
    showModal: function (type) {
        $.ajax({
            url: laroute.route('promotion.popup'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                type: type
            },
            success: function (res) {
                $('#my-modal').html(res.html);
                $('#modal-product').modal('show');

                if (type == 'product') {
                    $('#autotable').PioTable({
                        baseUrl: laroute.route('promotion.list-product')
                    });
                } else if (type == 'service') {
                    $('#autotable').PioTable({
                        baseUrl: laroute.route('promotion.list-service')
                    });
                } else if (type == 'service_card') {
                    $('#autotable').PioTable({
                        baseUrl: laroute.route('promotion.list-service-card')
                    });
                }

            }
        });
    },
    chooseAll: function (obj, type) {
        if ($(obj).is(':checked')) {
            $('.check_one').prop('checked', true);

            var arrCheck = [];
            $('.check_one').each(function () {
                if (type == 'product') {
                    arrCheck.push({
                        object_id: $(this).parents('label').find('.product_id').val(),
                        object_code: $(this).parents('label').find('.product_code').val(),
                        object_name: $(this).parents('label').find('.product_name').val(),
                        base_price: $(this).parents('label').find('.base_price').val()
                    });
                } else if (type == 'service') {
                    arrCheck.push({
                        object_id: $(this).parents('label').find('.service_id').val(),
                        object_code: $(this).parents('label').find('.service_code').val(),
                        object_name: $(this).parents('label').find('.service_name').val(),
                        base_price: $(this).parents('label').find('.base_price').val()
                    });
                } else if (type == 'service_card') {
                    arrCheck.push({
                        object_id: $(this).parents('label').find('.service_card_id').val(),
                        object_code: $(this).parents('label').find('.service_card_code').val(),
                        object_name: $(this).parents('label').find('.service_card_name').val(),
                        base_price: $(this).parents('label').find('.base_price').val()
                    });
                }
            });

            $.ajax({
                url: laroute.route('promotion.choose-all'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    arr_check: arrCheck,
                    type: type
                }
            });
        } else {
            $('.check_one').prop('checked', false);

            var arrUnCheck = [];
            $('.check_one').each(function () {
                if (type == 'product') {
                    arrUnCheck.push({
                        object_id: $(this).parents('label').find('.product_id').val(),
                        object_code: $(this).parents('label').find('.product_code').val(),
                        object_name: $(this).parents('label').find('.product_name').val(),
                        base_price: $(this).parents('label').find('.base_price').val()
                    });
                } else if (type == 'service') {
                    arrUnCheck.push({
                        object_id: $(this).parents('label').find('.service_id').val(),
                        object_code: $(this).parents('label').find('.service_code').val(),
                        object_name: $(this).parents('label').find('.service_name').val(),
                        base_price: $(this).parents('label').find('.base_price').val()
                    });
                } else if (type == 'service_card') {
                    arrUnCheck.push({
                        object_id: $(this).parents('label').find('.service_card_id').val(),
                        object_code: $(this).parents('label').find('.service_card_code').val(),
                        object_name: $(this).parents('label').find('.service_card_name').val(),
                        base_price: $(this).parents('label').find('.base_price').val()
                    });
                }
            });

            $.ajax({
                url: laroute.route('promotion.un-choose-all'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    arr_un_check: arrUnCheck,
                    type: type
                }
            });
        }
    },
    choose: function (obj, type) {
        if ($(obj).is(":checked")) {
            var objectId = '';
            var objectCode = '';
            var objectName = '';
            var basePrice = '';

            if (type == 'product') {
                objectId = $(obj).parents('label').find('.product_id').val();
                objectCode = $(obj).parents('label').find('.product_code').val();
                objectName = $(obj).parents('label').find('.product_name').val();
                basePrice = $(obj).parents('label').find('.base_price').val();
            } else if (type == 'service') {
                objectId = $(obj).parents('label').find('.service_id').val();
                objectCode = $(obj).parents('label').find('.service_code').val();
                objectName = $(obj).parents('label').find('.service_name').val();
                basePrice = $(obj).parents('label').find('.base_price').val();
            } else if (type == 'service_card') {
                objectId = $(obj).parents('label').find('.service_card_id').val();
                objectCode = $(obj).parents('label').find('.service_card_code').val();
                objectName = $(obj).parents('label').find('.service_card_name').val();
                basePrice = $(obj).parents('label').find('.base_price').val();
            }

            $.ajax({
                url: laroute.route('promotion.choose'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    object_id: objectId,
                    object_code: objectCode,
                    object_name: objectName,
                    base_price: basePrice,
                    type: type
                }
            });
        } else {
            var objectId = '';
            var objectCode = '';
            var objectName = '';
            var basePrice = '';

            if (type == 'product') {
                objectId = $(obj).parents('label').find('.product_id').val();
                objectCode = $(obj).parents('label').find('.product_code').val();
                objectName = $(obj).parents('label').find('.product_name').val();
                basePrice = $(obj).parents('label').find('.base_price').val();
            } else if (type == 'service') {
                objectId = $(obj).parents('label').find('.service_id').val();
                objectCode = $(obj).parents('label').find('.service_code').val();
                objectName = $(obj).parents('label').find('.service_name').val();
                basePrice = $(obj).parents('label').find('.base_price').val();
            } else if (type == 'service_card') {
                objectId = $(obj).parents('label').find('.service_card_id').val();
                objectCode = $(obj).parents('label').find('.service_card_code').val();
                objectName = $(obj).parents('label').find('.service_card_name').val();
                basePrice = $(obj).parents('label').find('.base_price').val();
            }

            $.ajax({
                url: laroute.route('promotion.un-choose'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    object_id: objectId,
                    object_code: objectCode,
                    object_name: objectName,
                    base_price: basePrice,
                    type: type
                }
            });
        }
    },
    submitChoose: function (type) {
        var promotion_type = $('input[name="promotion_type"]:checked').val();

        $.ajax({
            url: laroute.route('promotion.submit-choose'),
            dataType: 'JSON',
            method: 'POST',
            data: {
                promotion_type: promotion_type,
                type: type,
                discount_type: $('#discount_type').val(),
                discount_value_percent: $('#discount_value_percent').val(),
                discount_value_same: $('#discount_value_same').val()
            },
            success: function (res) {
                if (promotion_type == 1) {
                    $('.div_table_gift').empty();

                    $('.div_table_discount').html(res.html);
                    $('#modal-product').modal('hide');

                    $('#autotable-discount').PioTable({
                        baseUrl: laroute.route('promotion.list-discount')
                    });

                    $('.btn-search').trigger('click');
                } else if (promotion_type == 2) {
                    $('.div_table_discount').empty();

                    $('.div_table_gift').html(res.html);
                    $('#modal-product').modal('hide');

                    $('#autotable-gift').PioTable({
                        baseUrl: laroute.route('promotion.list-gift')
                    });

                    $('#autotable-gift').PioTable('refresh');
                }
            }
        });
    },
    changePromotionPrice: function (obj, object_code) {
        $.ajax({
            url: laroute.route('promotion.change-price'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                promotion_price: $(obj).val(),
                object_code: object_code
            }
        });
    },
    removeTr: function (obj, object_code, type_table, object_type) {
        $.ajax({
            url: laroute.route('promotion.remove-tr'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                object_code: object_code,
                object_type: object_type
            },
            success: function (res) {
                $(obj).closest('tr').remove();

                if (type_table == 'discount') {
                    $('#autotable-discount').PioTable('refresh');
                } else {
                    $('#autotable-gift').PioTable('refresh');
                }

            }
        });
    },
    changeStatus: function (obj, object_code) {
        var is_actived = 0;
        if ($(obj).is(':checked')) {
            is_actived = 1;
        }

        $.ajax({
            url: laroute.route('promotion.change-status-tr'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                object_code: object_code,
                is_actived: is_actived
            }
        });
    },
    changeGiftType: function (obj, object_code) {
        $.ajax({
            url: laroute.route('promotion.change-gift-type'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                object_code: object_code,
                gift_object_type: $(obj).val()
            },
            success: function (res) {
                $(obj).closest('tr').find('.gift_object_id').prop('disabled', false);
                $(obj).closest('tr').find('.gift_object_id').val('').trigger('change');

                $.getJSON(laroute.route('translate'), function (json) {
                    $(obj).closest('tr').find('.gift_object_id').select2({
                        width: '100%',
                        placeholder: json["Chọn quà tặng"],
                        ajax: {
                            url: laroute.route('promotion.list-option'),
                            data: function (params) {
                                return {
                                    search: params.term,
                                    page: params.page || 1,
                                    type: $(obj).val()
                                };
                            },
                            dataType: 'json',
                            method: 'POST',
                            processResults: function (data) {
                                data.page = data.page || 1;
                                return {
                                    results: data.items.map(function (item) {
                                        if ($(obj).val() == 'product') {
                                            return {
                                                id: item.product_child_id,
                                                text: item.product_child_name,
                                                code: item.product_code
                                            };
                                        } else if ($(obj).val() == 'service') {
                                            return {
                                                id: item.service_id,
                                                text: item.service_name,
                                                code: item.service_code
                                            };
                                        } else if ($(obj).val() == 'service_card') {
                                            return {
                                                id: item.service_card_id,
                                                text: item.card_name,
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
                    });
                });
            }
        });
    },
    changeGift: function (obj, object_code) {
        $.ajax({
            url: laroute.route('promotion.change-gift'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                gift_object_type: $(obj).closest('tr').find('.gift_object_type').val(),
                gift_object_id: $(obj).val(),
                object_code: object_code
            },
            success: function (res) {

            }
        });
    },
    changeQuantityBuy: function (obj, object_code) {
        $.ajax({
            url: laroute.route('promotion.change-quantity-buy'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                quantity_buy: $(obj).val(),
                object_code: object_code
            }
        });
    },
    changeNumberGift: function (obj, object_code) {
        $.ajax({
            url: laroute.route('promotion.change-number-gift'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                quantity_gift: $(obj).val(),
                object_code: object_code
            }
        });
    },
    submitCreate: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-register');

            form.validate({
                rules: {
                    promotion_name: {
                        required: true,
                        maxlength: 250
                    },
                    start_date: {
                        required: true,
                    },
                    end_date: {
                        required: true
                    },
                    start_time: {
                        required: true
                    },
                    end_time: {
                        required: true
                    },
                    default_start_time: {
                        required: true
                    },
                    default_end_time: {
                        required: true
                    },
                    form_date: {
                        required: true
                    },
                    to_date: {
                        required: true
                    },
                    member_level_id: {
                        required: true
                    },
                    customer_group_id: {
                        required: true
                    },
                    customer_id: {
                        required: true
                    },
                    // description: {
                    //     maxlength: 250
                    // }
                },
                messages: {
                    promotion_name: {
                        required: json['Hãy nhập tên chương trình'],
                        maxlength: json['Tên chương trình tối đa 250 kí tự']
                    },
                    start_date: {
                        required: json['Hãy chọn ngày bắt đầu'],
                    },
                    end_date: {
                        required: json['Hãy chọn ngày kết thúc']
                    },
                    start_time: {
                        required: json['Hãy chọn thời gian bắt đầu']
                    },
                    end_time: {
                        required: json['Hãy chọn thời gian kết thúc']
                    },
                    default_start_time: {
                        required: json['Hãy chọn thời gian bắt đầu mặc định']
                    },
                    default_end_time: {
                        required: json['Hãy chọn thời gian kết thúc mặc định']
                    },
                    form_date: {
                        required: json['Hãy chọn ngày bắt đầu']
                    },
                    to_date: {
                        required: json['Hãy chọn ngày kết thúc']
                    },
                    member_level_id: {
                        required: json['Hãy chọn hàng thành viên']
                    },
                    customer_group_id: {
                        required: json['Hãy chọn nhóm khách hàng']
                    },
                    customer_id: {
                        required: json['Hãy chọn khách hàng']
                    },
                    // description: {
                    //     maxlength: json['Mô tả ngắn tối đa 250 kí tự']
                    // }
                },
            });

            if (!form.valid()) {
                return false;
            }

            // kiem tra quota
            if ($('#quota').val().replace(new RegExp('\\,', 'g'), '') < 0) {
                swal(json['Vui lòng nhập số lượng là số dương'], '', 'error')
                return false;
            }

            var next = true;

            var is_time_campaign = 0;
            if ($('#is_time_campaign').is(':checked')) {
                is_time_campaign = 1;
            }

            var arrDataDaily = [];
            var arrDataWeekly = [];
            var arrDataMonthly = [];
            var arrDataFromTo = [];

            //Validate time khuyến mãi
            if ($('input[name="time_type"]:checked').val() == 'W') {
                var is_monday = 0;
                var is_tuesday = 0;
                var is_wednesday = 0;
                var is_thursday = 0;
                var is_friday = 0;
                var is_saturday = 0;
                var is_sunday = 0;
                var is_other_monday = 0;
                var is_other_tuesday = 0;
                var is_other_wednesday = 0;
                var is_other_thursday = 0;
                var is_other_friday = 0;
                var is_other_saturday = 0;
                var is_other_sunday = 0;
                var default_start_time = $('#default_start_time').val();
                var default_end_time = $('#default_end_time').val();
                var is_other_monday_start_time = $('#is_other_monday_start_time').val();
                var is_other_monday_end_time = $('#is_other_monday_end_time').val();
                var is_other_tuesday_start_time = $('#is_other_tuesday_start_time').val();
                var is_other_tuesday_end_time = $('#is_other_tuesday_end_time').val();
                var is_other_wednesday_start_time = $('#is_other_wednesday_start_time').val();
                var is_other_wednesday_end_time = $('#is_other_wednesday_end_time').val();
                var is_other_thursday_start_time = $('#is_other_thursday_start_time').val();
                var is_other_thursday_end_time = $('#is_other_thursday_end_time').val();
                var is_other_friday_start_time = $('#is_other_friday_start_time').val();
                var is_other_friday_end_time = $('#is_other_friday_end_time').val();
                var is_other_saturday_start_time = $('#is_other_saturday_start_time').val();
                var is_other_saturday_end_time = $('#is_other_saturday_end_time').val();
                var is_other_sunday_start_time = $('#is_other_sunday_start_time').val();
                var is_other_sunday_end_time = $('#is_other_sunday_end_time').val();

                if (default_start_time.length == 4) {
                    default_start_time = '0' + default_start_time;
                }
                if (default_end_time.length == 4) {
                    default_end_time = '0' + default_end_time;
                }

                if (default_start_time == '') {
                    $('.error_default_start_time').text(json['Hãy chọn giờ bắt đầu']);
                    next = false;
                } else {
                    $('.error_default_start_time').text('');
                }

                if (default_end_time == '') {
                    $('.error_default_end_time').text(json['Hãy chọn giờ kết thúc']);
                    next = false;
                } else {
                    $('.error_default_end_time').text('');
                }

                if (default_start_time != '' && default_end_time != '' && default_start_time >= default_end_time) {
                    $('.error_default_start_time').text(json['Giờ bắt đầu phải nhỏ hơn giờ kết thúc']);
                    next = false;
                }

                if ($('#is_monday').is(':checked')) {
                    is_monday = 1;
                }

                if ($('#is_tuesday').is(':checked')) {
                    is_tuesday = 1;
                }

                if ($('#is_wednesday').is(':checked')) {
                    is_wednesday = 1;
                }

                if ($('#is_thursday').is(':checked')) {
                    is_thursday = 1;
                }

                if ($('#is_friday').is(':checked')) {
                    is_friday = 1;
                }

                if ($('#is_saturday').is(':checked')) {
                    is_saturday = 1;
                }

                if ($('#is_sunday').is(':checked')) {
                    is_sunday = 1;
                }

                if ($('#is_other_monday').is(':checked')) {
                    is_other_monday = 1;

                    if (is_other_monday_start_time.length == 4) {
                        is_other_monday_start_time = '0' + is_other_monday_start_time;
                    }
                    if (is_other_monday_end_time.length == 4) {
                        is_other_monday_end_time = '0' + is_other_monday_end_time;
                    }

                    if (is_other_monday_start_time == '') {
                        $('.error_start_time_monday').text(json['Hãy chọn ngày bắt đầu']);
                        next = false;
                    } else {
                        $('.error_start_time_monday').text('');
                    }

                    if (is_other_monday_end_time == '') {
                        $('.error_end_time_monday').text(json['Hãy chọn ngày kết thúc']);
                        next = false;
                    } else {
                        $('.error_end_time_monday').text('');
                    }

                    if (is_other_monday_start_time != '' && is_other_monday_end_time != '' && is_other_monday_start_time >= is_other_monday_end_time) {
                        $('.error_start_time_monday').text(json['Giờ bắt đầu phải nhỏ hơn giờ kết thúc']);
                        next = false;
                    }
                }

                if ($('#is_other_tuesday').is(':checked')) {
                    is_other_tuesday = 1;

                    if (is_other_tuesday_start_time.length == 4) {
                        is_other_tuesday_start_time = '0' + is_other_tuesday_start_time;
                    }
                    if (is_other_tuesday_end_time.length == 4) {
                        is_other_tuesday_end_time = '0' + is_other_tuesday_end_time;
                    }

                    if (is_other_tuesday_start_time == '') {
                        $('.error_start_time_tuesday').text(json['Hãy chọn ngày bắt đầu']);
                        next = false;
                    } else {
                        $('.error_start_time_tuesday').text('');
                    }

                    if (is_other_tuesday_end_time == '') {
                        $('.error_end_time_tuesday').text(json['Hãy chọn ngày kết thúc']);
                        next = false;
                    } else {
                        $('.error_end_time_tuesday').text('');
                    }

                    if (is_other_tuesday_start_time != '' && is_other_tuesday_end_time != '' && is_other_tuesday_start_time >= is_other_tuesday_end_time) {
                        $('.error_start_time_tuesday').text(json['Giờ bắt đầu phải nhỏ hơn giờ kết thúc']);
                        next = false;
                    }
                }

                if ($('#is_other_wednesday').is(':checked')) {
                    is_other_wednesday = 1;

                    if (is_other_wednesday_start_time.length == 4) {
                        is_other_wednesday_start_time = '0' + is_other_wednesday_start_time;
                    }
                    if (is_other_wednesday_end_time.length == 4) {
                        is_other_wednesday_end_time = '0' + is_other_wednesday_end_time;
                    }

                    if (is_other_wednesday_start_time == '') {
                        $('.error_start_time_wednesday').text(json['Hãy chọn ngày bắt đầu']);
                        next = false;
                    } else {
                        $('.error_start_time_wednesday').text('');
                    }

                    if (is_other_wednesday_end_time == '') {
                        $('.error_end_time_wednesday').text(json['Hãy chọn ngày kết thúc']);
                        next = false;
                    } else {
                        $('.error_end_time_wednesday').text('');
                    }

                    if (is_other_wednesday_start_time != '' && is_other_wednesday_end_time != '' && is_other_wednesday_start_time >= is_other_wednesday_end_time) {
                        $('.error_start_time_wednesday').text(json['Giờ bắt đầu phải nhỏ hơn giờ kết thúc']);
                        next = false;
                    }
                }

                if ($('#is_other_thursday').is(':checked')) {
                    is_other_thursday = 1;

                    if (is_other_thursday_start_time.length == 4) {
                        is_other_wednesday_start_time = '0' + is_other_wednesday_start_time;
                    }
                    if (is_other_thursday_end_time.length == 4) {
                        is_other_wednesday_end_time = '0' + is_other_wednesday_end_time;
                    }

                    if (is_other_thursday_start_time == '') {
                        $('.error_start_time_thursday').text(json['Hãy chọn ngày bắt đầu']);
                        next = false;
                    } else {
                        $('.error_start_time_thursday').text('');
                    }

                    if (is_other_thursday_end_time == '') {
                        $('.error_end_time_thursday').text(json['Hãy chọn ngày kết thúc']);
                        next = false;
                    } else {
                        $('.error_end_time_thursday').text('');
                    }

                    if (is_other_thursday_start_time != '' && is_other_thursday_end_time != '' && is_other_thursday_start_time >= is_other_thursday_end_time) {
                        $('.error_start_time_thursday').text(json['Giờ bắt đầu phải nhỏ hơn giờ kết thúc']);
                        next = false;
                    }
                }

                if ($('#is_other_friday').is(':checked')) {
                    is_other_friday = 1;

                    if (is_other_friday_start_time.length == 4) {
                        is_other_friday_start_time = '0' + is_other_friday_start_time;
                    }
                    if (is_other_friday_end_time.length == 4) {
                        is_other_friday_end_time = '0' + is_other_friday_end_time;
                    }

                    if (is_other_friday_start_time == '') {
                        $('.error_start_time_friday').text(json['Hãy chọn ngày bắt đầu']);
                        next = false;
                    } else {
                        $('.error_start_time_friday').text('');
                    }

                    if (is_other_friday_end_time == '') {
                        $('.error_end_time_friday').text(json['Hãy chọn ngày kết thúc']);
                        next = false;
                    } else {
                        $('.error_end_time_friday').text('');
                    }

                    if (is_other_friday_start_time != '' && is_other_friday_end_time != '' && is_other_friday_start_time >= is_other_friday_end_time) {
                        $('.error_start_time_friday').text(json['Giờ bắt đầu phải nhỏ hơn giờ kết thúc']);
                        next = false;
                    }
                }

                if ($('#is_other_saturday').is(':checked')) {
                    is_other_saturday = 1;

                    if (is_other_saturday_start_time.length == 4) {
                        is_other_saturday_start_time = '0' + is_other_saturday_start_time;
                    }
                    if (is_other_saturday_end_time.length == 4) {
                        is_other_saturday_end_time = '0' + is_other_saturday_end_time;
                    }

                    if (is_other_saturday_start_time == '') {
                        $('.error_start_time_saturday').text(json['Hãy chọn ngày bắt đầu']);
                        next = false;
                    } else {
                        $('.error_start_time_saturday').text('');
                    }

                    if (is_other_saturday_end_time == '') {
                        $('.error_end_time_saturday').text(json['Hãy chọn ngày kết thúc']);
                        next = false;
                    } else {
                        $('.error_end_time_saturday').text('');
                    }

                    if (is_other_saturday_start_time != '' && is_other_saturday_end_time != '' && is_other_saturday_start_time >= is_other_saturday_end_time) {
                        $('.error_start_time_saturday').text(json['Giờ bắt đầu phải nhỏ hơn giờ kết thúc']);
                        next = false;
                    }
                }

                if ($('#is_other_sunday').is(':checked')) {
                    is_other_sunday = 1;

                    if (is_other_sunday_start_time.length == 4) {
                        is_other_sunday_start_time = '0' + is_other_sunday_start_time;
                    }
                    if (is_other_sunday_end_time.length == 4) {
                        is_other_sunday_end_time = '0' + is_other_sunday_end_time;
                    }

                    if (is_other_sunday_start_time == '') {
                        $('.error_start_time_sunday').text(json['Hãy chọn ngày bắt đầu']);
                        next = false;
                    } else {
                        $('.error_start_time_sunday').text('');
                    }

                    if (is_other_sunday_end_time == '') {
                        $('.error_end_time_sunday').text(json['Hãy chọn ngày kết thúc']);
                        next = false;
                    } else {
                        $('.error_end_time_sunday').text('');
                    }

                    if (is_other_sunday_start_time != '' && is_other_sunday_end_time != '' && is_other_sunday_start_time >= is_other_sunday_end_time) {
                        $('.error_start_time_sunday').text(json['Giờ bắt đầu phải nhỏ hơn giờ kết thúc']);
                        next = false;
                    }
                }

                arrDataWeekly = {
                    default_start_time: default_start_time,
                    default_end_time: default_end_time,
                    is_monday: is_monday,
                    is_other_monday: is_other_monday,
                    is_other_monday_start_time: is_other_monday_start_time,
                    is_other_monday_end_time: is_other_monday_end_time,
                    is_tuesday: is_tuesday,
                    is_other_tuesday: is_other_tuesday,
                    is_other_tuesday_start_time: is_other_tuesday_start_time,
                    is_other_tuesday_end_time: is_other_tuesday_end_time,
                    is_wednesday: is_wednesday,
                    is_other_wednesday: is_other_wednesday,
                    is_other_wednesday_start_time: is_other_wednesday_start_time,
                    is_other_wednesday_end_time: is_other_wednesday_end_time,
                    is_thursday: is_thursday,
                    is_other_thursday: is_other_thursday,
                    is_other_thursday_start_time: is_other_thursday_start_time,
                    is_other_thursday_end_time: is_other_thursday_end_time,
                    is_friday: is_friday,
                    is_other_friday: is_other_friday,
                    is_other_friday_start_time: is_other_friday_start_time,
                    is_other_friday_end_time: is_other_friday_end_time,
                    is_saturday: is_saturday,
                    is_other_saturday: is_other_saturday,
                    is_other_saturday_start_time: is_other_saturday_start_time,
                    is_other_saturday_end_time: is_other_saturday_end_time,
                    is_sunday: is_sunday,
                    is_other_sunday: is_other_sunday,
                    is_other_sunday_start_time: is_other_sunday_start_time,
                    is_other_sunday_end_time: is_other_sunday_end_time
                };
            } else if ($('input[name="time_type"]:checked').val() == 'M') {
                $.each($('#table-monthly').find(".tr_monthly"), function () {
                    var check_run_date = $(this).find($('.run_date')).val();
                    var check_start_time = $(this).find($('.start_time')).val();
                    var check_end_time = $(this).find($('.end_time')).val();
                    var number = $(this).find($('.number')).val();

                    if (check_start_time.length == 4) {
                        check_start_time = '0' + check_start_time;
                    }
                    if (check_end_time.length == 4) {
                        check_end_time = '0' + check_end_time;
                    }

                    if (check_run_date == '') {
                        $('.error_run_date_' + number + '').text(json['Hãy chọn ngày chạy']);
                        next = false;
                    } else {
                        $('.error_run_date_' + number + '').text('');
                    }

                    if (check_start_time == '') {
                        $('.error_start_time_' + number + '').text(json['Hãy chọn giờ bắt đầu']);
                        next = false;
                    } else {
                        $('.error_start_time_' + number + '').text('');
                    }

                    if (check_end_time == '') {
                        $('.error_end_time_' + number + '').text(json['Hãy chọn giờ kết thúc']);
                        next = false;
                    } else {
                        $('.error_end_time_' + number + '').text('');
                    }

                    if (check_start_time != '' && check_end_time != '' && check_start_time >= check_end_time) {
                        $('.error_start_time_' + number + '').text(json['Giờ bắt đầu phải nhỏ hơn giờ kết thúc']);
                        next = false;
                    }

                    arrDataMonthly.push({
                        run_date: check_run_date,
                        start_time: check_start_time,
                        end_time: check_end_time
                    });
                });
            } else if ($('input[name="time_type"]:checked').val() == 'R') {
                var form_date = $('#form_date').val();
                var start_time = $('#start_time').val();
                var to_date = $('#to_date').val();
                var end_time = $('#end_time').val();

                if (start_time.length == 4) {
                    start_time = '0' + start_time;
                }
                if (end_time.length == 4) {
                    end_time = '0' + end_time;
                }

                //Format form_date
                var formDateParse = form_date.split("/");
                var formDateObject = new Date(+formDateParse[2], formDateParse[1] - 1, +formDateParse[0]);
                //Format to_date
                var toDateParse = to_date.split("/");
                var toDateObject = new Date(+toDateParse[2], toDateParse[1] - 1, +toDateParse[0]);

                if (formDateObject.toString() + ' ' + start_time >= toDateObject.toString() + ' ' + end_time) {
                    $('.error_from_date').text(json['Ngày giờ bắt đầu phải lớn hơn ngày giờ kết thúc']);
                    next = false;
                } else {
                    $('.error_from_date').text('');
                }

                arrDataFromTo = {
                    form_date: form_date,
                    start_time: start_time,
                    to_date: to_date,
                    end_time: end_time
                };
            } else if ($('input[name="time_type"]:checked').val() == 'D') {
                var start_time = $('#start_time').val();
                var end_time = $('#end_time').val();

                if (start_time.length == 4) {
                    start_time = '0' + start_time;
                }
                if (end_time.length == 4) {
                    end_time = '0' + end_time;
                }

                if (start_time >= end_time) {
                    $('.error_start_time_daily').text(json['Giờ bắt đầu phải nhỏ hơn giờ kết thúc']);
                    next = false;
                } else {
                    $('.error_start_time_daily').text('');
                }

                arrDataDaily = {
                    start_time: start_time,
                    end_time: end_time
                };
            }

            var is_display = 0;
            var is_feature = 0;
            if ($('#is_display').is(':checked')) {
                is_display = 1;
                if ($('#is_feature').is(':checked')) {
                    is_feature = 1;
                }
            }

            var positionId = [];
            $.each($('#sortable').find("li"), function () {
                var id = $(this).find("input[name='promotion_id']");
                positionId.push(id.val());
            });
            // check mo ta ngan
            var description = $('#description').val();
            if (description.length > 250) {
                $('.error_description').text(json['Mô tả ngắn tối đa 250 kí tự']);
                next = false;
            } else {
                $('.error_description').text('');
            }

            if (next == true) {
                $.ajax({
                    url: laroute.route('promotion.store'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        promotion_name: $('#promotion_name').val(),
                        promotion_type: $('input[name="promotion_type"]:checked').val(),
                        promotion_type_discount: $('input[name="promotion_type_value"]:checked').val(),
                        promotion_type_discount_percent: $('#promotion_type_discount_value_percent').val(),
                        promotion_type_discount_same: $('#promotion_type_discount_value_same').val(),
                        quota: $('#quota').val(),
                        start_date: $('#start_date').val(),
                        end_date: $('#end_date').val(),
                        is_time_campaign: is_time_campaign,
                        time_type: $('input[name="time_type"]:checked').val(),
                        arrDataDaily: arrDataDaily,
                        arrDataWeekly: arrDataWeekly,
                        arrDataMonthly: arrDataMonthly,
                        arrDataFromTo: arrDataFromTo,
                        branch_apply: $('#branch_apply').val(),
                        order_source: $('#order_source').val(),
                        promotion_apply_to: $('#promotion_apply_to').val(),
                        member_level_id: $('#member_level_id').val(),
                        customer_group_id: $('#customer_group_id').val(),
                        customer_id: $('#customer_id').val(),
                        is_display: is_display,
                        is_feature: is_feature,
                        image: $('#image').val(),
                        description: $('#description').val(),
                        description_detail: $('#description_detail').val(),
                        positionId: positionId,
                        type_display_app: $('#type_display_app').val()
                    },
                    success: function (res) {
                        if (res.error == false) {
                            swal(res.message, "", "success").then(function (result) {
                                if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                    window.location.href = laroute.route('promotion');
                                }
                                if (result.value == true) {
                                    window.location.href = laroute.route('promotion');
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
            }
        });
    },
    submitEdit: function (promotionId, promotionCode) {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-edit');

            form.validate({
                rules: {
                    promotion_name: {
                        required: true,
                        maxlength: 250
                    },
                    start_date: {
                        required: true,
                    },
                    end_date: {
                        required: true
                    },
                    start_time: {
                        required: true
                    },
                    end_time: {
                        required: true
                    },
                    default_start_time: {
                        required: true
                    },
                    default_end_time: {
                        required: true
                    },
                    form_date: {
                        required: true
                    },
                    to_date: {
                        required: true
                    },
                    member_level_id: {
                        required: true
                    },
                    customer_group_id: {
                        required: true
                    },
                    customer_id: {
                        required: true
                    },
                    // description: {
                    //     maxlength: 250
                    // }
                },
                messages: {
                    promotion_name: {
                        required: json['Hãy nhập tên chương trình'],
                        maxlength: json['Tên chương trình tối đa 250 kí tự']
                    },
                    start_date: {
                        required: json['Hãy chọn ngày bắt đầu'],
                    },
                    end_date: {
                        required: json['Hãy chọn ngày kết thúc']
                    },
                    start_time: {
                        required: json['Hãy chọn thời gian bắt đầu']
                    },
                    end_time: {
                        required: json['Hãy chọn thời gian kết thúc']
                    },
                    default_start_time: {
                        required: json['Hãy chọn thời gian bắt đầu mặc định']
                    },
                    default_end_time: {
                        required: json['Hãy chọn thời gian kết thúc mặc định']
                    },
                    form_date: {
                        required: json['Hãy chọn ngày bắt đầu']
                    },
                    to_date: {
                        required: json['Hãy chọn ngày kết thúc']
                    },
                    member_level_id: {
                        required: json['Hãy chọn hàng thành viên']
                    },
                    customer_group_id: {
                        required: json['Hãy chọn nhóm khách hàng']
                    },
                    customer_id: {
                        required: json['Hãy chọn khách hàng']
                    },
                    // description: {
                    //     maxlength: json['Mô tả ngắn tối đa 250 kí tự']
                    // }
                },
            });

            if (!form.valid()) {
                return false;
            }
            // kiem tra quota
            if ($('#quota').val().replace(new RegExp('\\,', 'g'), '') < 0) {
                swal(json['Vui lòng nhập số lượng là số dương'], '', 'error')
                return false;
            }

            var next = true;

            var is_time_campaign = 0;
            if ($('#is_time_campaign').is(':checked')) {
                is_time_campaign = 1;
            }

            var arrDataDaily = [];
            var arrDataWeekly = [];
            var arrDataMonthly = [];
            var arrDataFromTo = [];

            //Validate time khuyến mãi
            if ($('input[name="time_type"]:checked').val() == 'W') {
                var is_monday = 0;
                var is_tuesday = 0;
                var is_wednesday = 0;
                var is_thursday = 0;
                var is_friday = 0;
                var is_saturday = 0;
                var is_sunday = 0;
                var is_other_monday = 0;
                var is_other_tuesday = 0;
                var is_other_wednesday = 0;
                var is_other_thursday = 0;
                var is_other_friday = 0;
                var is_other_saturday = 0;
                var is_other_sunday = 0;
                var default_start_time = $('#default_start_time').val();
                var default_end_time = $('#default_end_time').val();
                var is_other_monday_start_time = $('#is_other_monday_start_time').val();
                var is_other_monday_end_time = $('#is_other_monday_end_time').val();
                var is_other_tuesday_start_time = $('#is_other_tuesday_start_time').val();
                var is_other_tuesday_end_time = $('#is_other_tuesday_end_time').val();
                var is_other_wednesday_start_time = $('#is_other_wednesday_start_time').val();
                var is_other_wednesday_end_time = $('#is_other_wednesday_end_time').val();
                var is_other_thursday_start_time = $('#is_other_thursday_start_time').val();
                var is_other_thursday_end_time = $('#is_other_thursday_end_time').val();
                var is_other_friday_start_time = $('#is_other_friday_start_time').val();
                var is_other_friday_end_time = $('#is_other_friday_end_time').val();
                var is_other_saturday_start_time = $('#is_other_saturday_start_time').val();
                var is_other_saturday_end_time = $('#is_other_saturday_end_time').val();
                var is_other_sunday_start_time = $('#is_other_sunday_start_time').val();
                var is_other_sunday_end_time = $('#is_other_sunday_end_time').val();

                if (default_start_time.length == 4) {
                    default_start_time = '0' + default_start_time;
                }
                if (default_end_time.length == 4) {
                    default_end_time = '0' + default_end_time;
                }

                if (default_start_time == '') {
                    $('.error_default_start_time').text(json['Hãy chọn giờ bắt đầu']);
                    next = false;
                } else {
                    $('.error_default_start_time').text('');
                }

                if (default_end_time == '') {
                    $('.error_default_end_time').text(json['Hãy chọn giờ kết thúc']);
                    next = false;
                } else {
                    $('.error_default_end_time').text('');
                }

                if (default_start_time != '' && default_end_time != '' && default_start_time >= default_end_time) {
                    $('.error_default_start_time').text(json['Giờ bắt đầu phải nhỏ hơn giờ kết thúc']);
                    next = false;
                }

                if ($('#is_monday').is(':checked')) {
                    is_monday = 1;
                }

                if ($('#is_tuesday').is(':checked')) {
                    is_tuesday = 1;
                }

                if ($('#is_wednesday').is(':checked')) {
                    is_wednesday = 1;
                }

                if ($('#is_thursday').is(':checked')) {
                    is_thursday = 1;
                }

                if ($('#is_friday').is(':checked')) {
                    is_friday = 1;
                }

                if ($('#is_saturday').is(':checked')) {
                    is_saturday = 1;
                }

                if ($('#is_sunday').is(':checked')) {
                    is_sunday = 1;
                }

                if ($('#is_other_monday').is(':checked')) {
                    is_other_monday = 1;

                    if (is_other_monday_start_time.length == 4) {
                        is_other_monday_start_time = '0' + is_other_monday_start_time;
                    }
                    if (is_other_monday_end_time.length == 4) {
                        is_other_monday_end_time = '0' + is_other_monday_end_time;
                    }

                    if (is_other_monday_start_time == '') {
                        $('.error_start_time_monday').text(json['Hãy chọn ngày bắt đầu']);
                        next = false;
                    } else {
                        $('.error_start_time_monday').text('');
                    }

                    if (is_other_monday_end_time == '') {
                        $('.error_end_time_monday').text(json['Hãy chọn ngày kết thúc']);
                        next = false;
                    } else {
                        $('.error_end_time_monday').text('');
                    }

                    if (is_other_monday_start_time != '' && is_other_monday_end_time != '' && is_other_monday_start_time >= is_other_monday_end_time) {
                        $('.error_start_time_monday').text(json['Giờ bắt đầu phải nhỏ hơn giờ kết thúc']);
                        next = false;
                    }
                }

                if ($('#is_other_tuesday').is(':checked')) {
                    is_other_tuesday = 1;

                    if (is_other_tuesday_start_time.length == 4) {
                        is_other_tuesday_start_time = '0' + is_other_tuesday_start_time;
                    }
                    if (is_other_tuesday_end_time.length == 4) {
                        is_other_tuesday_end_time = '0' + is_other_tuesday_end_time;
                    }

                    if (is_other_tuesday_start_time == '') {
                        $('.error_start_time_tuesday').text(json['Hãy chọn ngày bắt đầu']);
                        next = false;
                    } else {
                        $('.error_start_time_tuesday').text('');
                    }

                    if (is_other_tuesday_end_time == '') {
                        $('.error_end_time_tuesday').text(json['Hãy chọn ngày kết thúc']);
                        next = false;
                    } else {
                        $('.error_end_time_tuesday').text('');
                    }

                    if (is_other_tuesday_start_time != '' && is_other_tuesday_end_time != '' && is_other_tuesday_start_time >= is_other_tuesday_end_time) {
                        $('.error_start_time_tuesday').text(json['Giờ bắt đầu phải nhỏ hơn giờ kết thúc']);
                        next = false;
                    }
                }

                if ($('#is_other_wednesday').is(':checked')) {
                    is_other_wednesday = 1;

                    if (is_other_wednesday_start_time.length == 4) {
                        is_other_wednesday_start_time = '0' + is_other_wednesday_start_time;
                    }
                    if (is_other_wednesday_end_time.length == 4) {
                        is_other_wednesday_end_time = '0' + is_other_wednesday_end_time;
                    }

                    if (is_other_wednesday_start_time == '') {
                        $('.error_start_time_wednesday').text(json['Hãy chọn ngày bắt đầu']);
                        next = false;
                    } else {
                        $('.error_start_time_wednesday').text('');
                    }

                    if (is_other_wednesday_end_time == '') {
                        $('.error_end_time_wednesday').text(json['Hãy chọn ngày kết thúc']);
                        next = false;
                    } else {
                        $('.error_end_time_wednesday').text('');
                    }

                    if (is_other_wednesday_start_time != '' && is_other_wednesday_end_time != '' && is_other_wednesday_start_time >= is_other_wednesday_end_time) {
                        $('.error_start_time_wednesday').text(json['Giờ bắt đầu phải nhỏ hơn giờ kết thúc']);
                        next = false;
                    }
                }

                if ($('#is_other_thursday').is(':checked')) {
                    is_other_thursday = 1;

                    if (is_other_thursday_start_time.length == 4) {
                        is_other_wednesday_start_time = '0' + is_other_wednesday_start_time;
                    }
                    if (is_other_thursday_end_time.length == 4) {
                        is_other_wednesday_end_time = '0' + is_other_wednesday_end_time;
                    }

                    if (is_other_thursday_start_time == '') {
                        $('.error_start_time_thursday').text(json['Hãy chọn ngày bắt đầu']);
                        next = false;
                    } else {
                        $('.error_start_time_thursday').text('');
                    }

                    if (is_other_thursday_end_time == '') {
                        $('.error_end_time_thursday').text(json['Hãy chọn ngày kết thúc']);
                        next = false;
                    } else {
                        $('.error_end_time_thursday').text('');
                    }

                    if (is_other_thursday_start_time != '' && is_other_thursday_end_time != '' && is_other_thursday_start_time >= is_other_thursday_end_time) {
                        $('.error_start_time_thursday').text(json['Giờ bắt đầu phải nhỏ hơn giờ kết thúc']);
                        next = false;
                    }
                }

                if ($('#is_other_friday').is(':checked')) {
                    is_other_friday = 1;

                    if (is_other_friday_start_time.length == 4) {
                        is_other_friday_start_time = '0' + is_other_friday_start_time;
                    }
                    if (is_other_friday_end_time.length == 4) {
                        is_other_friday_end_time = '0' + is_other_friday_end_time;
                    }

                    if (is_other_friday_start_time == '') {
                        $('.error_start_time_friday').text(json['Hãy chọn ngày bắt đầu']);
                        next = false;
                    } else {
                        $('.error_start_time_friday').text('');
                    }

                    if (is_other_friday_end_time == '') {
                        $('.error_end_time_friday').text(json['Hãy chọn ngày kết thúc']);
                        next = false;
                    } else {
                        $('.error_end_time_friday').text('');
                    }

                    if (is_other_friday_start_time != '' && is_other_friday_end_time != '' && is_other_friday_start_time >= is_other_friday_end_time) {
                        $('.error_start_time_friday').text(json['Giờ bắt đầu phải nhỏ hơn giờ kết thúc']);
                        next = false;
                    }
                }

                if ($('#is_other_saturday').is(':checked')) {
                    is_other_saturday = 1;

                    if (is_other_saturday_start_time.length == 4) {
                        is_other_saturday_start_time = '0' + is_other_saturday_start_time;
                    }
                    if (is_other_saturday_end_time.length == 4) {
                        is_other_saturday_end_time = '0' + is_other_saturday_end_time;
                    }

                    if (is_other_saturday_start_time == '') {
                        $('.error_start_time_saturday').text(json['Hãy chọn ngày bắt đầu']);
                        next = false;
                    } else {
                        $('.error_start_time_saturday').text('');
                    }

                    if (is_other_saturday_end_time == '') {
                        $('.error_end_time_saturday').text(json['Hãy chọn ngày kết thúc']);
                        next = false;
                    } else {
                        $('.error_end_time_saturday').text('');
                    }

                    if (is_other_saturday_start_time != '' && is_other_saturday_end_time != '' && is_other_saturday_start_time >= is_other_saturday_end_time) {
                        $('.error_start_time_saturday').text(json['Giờ bắt đầu phải nhỏ hơn giờ kết thúc']);
                        next = false;
                    }
                }

                if ($('#is_other_sunday').is(':checked')) {
                    is_other_sunday = 1;

                    if (is_other_sunday_start_time.length == 4) {
                        is_other_sunday_start_time = '0' + is_other_sunday_start_time;
                    }
                    if (is_other_sunday_end_time.length == 4) {
                        is_other_sunday_end_time = '0' + is_other_sunday_end_time;
                    }

                    if (is_other_sunday_start_time == '') {
                        $('.error_start_time_sunday').text(json['Hãy chọn ngày bắt đầu']);
                        next = false;
                    } else {
                        $('.error_start_time_sunday').text('');
                    }

                    if (is_other_sunday_end_time == '') {
                        $('.error_end_time_sunday').text(json['Hãy chọn ngày kết thúc']);
                        next = false;
                    } else {
                        $('.error_end_time_sunday').text('');
                    }

                    if (is_other_sunday_start_time != '' && is_other_sunday_end_time != '' && is_other_sunday_start_time >= is_other_sunday_end_time) {
                        $('.error_start_time_sunday').text(json['Giờ bắt đầu phải nhỏ hơn giờ kết thúc']);
                        next = false;
                    }
                }

                arrDataWeekly = {
                    default_start_time: default_start_time,
                    default_end_time: default_end_time,
                    is_monday: is_monday,
                    is_other_monday: is_other_monday,
                    is_other_monday_start_time: is_other_monday_start_time,
                    is_other_monday_end_time: is_other_monday_end_time,
                    is_tuesday: is_tuesday,
                    is_other_tuesday: is_other_tuesday,
                    is_other_tuesday_start_time: is_other_tuesday_start_time,
                    is_other_tuesday_end_time: is_other_tuesday_end_time,
                    is_wednesday: is_wednesday,
                    is_other_wednesday: is_other_wednesday,
                    is_other_wednesday_start_time: is_other_wednesday_start_time,
                    is_other_wednesday_end_time: is_other_wednesday_end_time,
                    is_thursday: is_thursday,
                    is_other_thursday: is_other_thursday,
                    is_other_thursday_start_time: is_other_thursday_start_time,
                    is_other_thursday_end_time: is_other_thursday_end_time,
                    is_friday: is_friday,
                    is_other_friday: is_other_friday,
                    is_other_friday_start_time: is_other_friday_start_time,
                    is_other_friday_end_time: is_other_friday_end_time,
                    is_saturday: is_saturday,
                    is_other_saturday: is_other_saturday,
                    is_other_saturday_start_time: is_other_saturday_start_time,
                    is_other_saturday_end_time: is_other_saturday_end_time,
                    is_sunday: is_sunday,
                    is_other_sunday: is_other_sunday,
                    is_other_sunday_start_time: is_other_sunday_start_time,
                    is_other_sunday_end_time: is_other_sunday_end_time
                };
            } else if ($('input[name="time_type"]:checked').val() == 'M') {
                $.each($('#table-monthly').find(".tr_monthly"), function () {
                    var check_run_date = $(this).find($('.run_date')).val();
                    var check_start_time = $(this).find($('.start_time')).val();
                    var check_end_time = $(this).find($('.end_time')).val();
                    var number = $(this).find($('.number')).val();

                    if (check_start_time.length == 4) {
                        check_start_time = '0' + check_start_time;
                    }
                    if (check_end_time.length == 4) {
                        check_end_time = '0' + check_end_time;
                    }

                    if (check_run_date == '') {
                        $('.error_run_date_' + number + '').text(json['Hãy chọn ngày chạy']);
                        next = false;
                    } else {
                        $('.error_run_date_' + number + '').text('');
                    }

                    if (check_start_time == '') {
                        $('.error_start_time_' + number + '').text(json['Hãy chọn giờ bắt đầu']);
                        next = false;
                    } else {
                        $('.error_start_time_' + number + '').text('');
                    }

                    if (check_end_time == '') {
                        $('.error_end_time_' + number + '').text(json['Hãy chọn giờ kết thúc']);
                        next = false;
                    } else {
                        $('.error_end_time_' + number + '').text('');
                    }

                    if (check_start_time != '' && check_end_time != '' && check_start_time >= check_end_time) {
                        $('.error_start_time_' + number + '').text(json['Giờ bắt đầu phải nhở hơn giờ kết thúc']);
                        next = false;
                    }

                    arrDataMonthly.push({
                        run_date: check_run_date,
                        start_time: check_start_time,
                        end_time: check_end_time
                    });
                });
            } else if ($('input[name="time_type"]:checked').val() == 'R') {
                var form_date = $('#form_date').val();
                var start_time = $('#start_time').val();
                var to_date = $('#to_date').val();
                var end_time = $('#end_time').val();

                if (start_time.length == 4) {
                    start_time = '0' + start_time;
                }
                if (end_time.length == 4) {
                    end_time = '0' + end_time;
                }

                //Format form_date
                var formDateParse = form_date.split("/");
                var formDateObject = new Date(+formDateParse[2], formDateParse[1] - 1, +formDateParse[0]);
                //Format to_date
                var toDateParse = to_date.split("/");
                var toDateObject = new Date(+toDateParse[2], toDateParse[1] - 1, +toDateParse[0]);

                if (formDateObject.toString() + ' ' + start_time >= toDateObject.toString() + ' ' + end_time) {
                    $('.error_from_date').text(json['Ngày giờ bắt đầu phải lớn hơn ngày giờ kết thúc']);
                    next = false;
                } else {
                    $('.error_from_date').text('');
                }

                arrDataFromTo = {
                    form_date: form_date,
                    start_time: start_time,
                    to_date: to_date,
                    end_time: end_time
                };
            } else if ($('input[name="time_type"]:checked').val() == 'D') {
                var start_time = $('#start_time').val();
                var end_time = $('#end_time').val();

                if (start_time.length == 4) {
                    start_time = '0' + start_time;
                }
                if (end_time.length == 4) {
                    end_time = '0' + end_time;
                }

                if (start_time >= end_time) {
                    $('.error_start_time_daily').text(json['Giờ bắt đầu phải nhỏ hơn giờ kết thúc']);
                    next = false;
                } else {
                    $('.error_start_time_daily').text('');
                }

                arrDataDaily = {
                    start_time: start_time,
                    end_time: end_time
                };
            }

            var is_display = 0;
            var is_feature = 0;
            if ($('#is_display').is(':checked')) {
                is_display = 1;
                if ($('#is_feature').is(':checked')) {
                    is_feature = 1;
                }
            }

            var is_actived = 0;
            if ($('#is_actived').is(':checked')) {
                is_actived = 1;
            }

            var positionId = [];
            $.each($('#sortable').find("li"), function () {
                var id = $(this).find("input[name='promotion_id']");
                positionId.push(id.val());
            });
            // check mo ta ngan
            var description = $('#description').val();
            if (description.length > 250) {
                $('.error_description').text(json['Mô tả ngắn tối đa 250 kí tự']);
                next = false;
            } else {
                $('.error_description').text('');
            }

            if (next == true) {
                $.ajax({
                    url: laroute.route('promotion.update'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        promotion_id: promotionId,
                        promotion_code: promotionCode,
                        promotion_name: $('#promotion_name').val(),
                        promotion_type: $('input[name="promotion_type"]:checked').val(),
                        promotion_type_discount: $('input[name="promotion_type_value"]:checked').val(),
                        promotion_type_discount_percent: $('#promotion_type_discount_value_percent').val(),
                        promotion_type_discount_same: $('#promotion_type_discount_value_same').val(),
                        quota: $('#quota').val(),
                        start_date: $('#start_date').val(),
                        end_date: $('#end_date').val(),
                        is_time_campaign: is_time_campaign,
                        time_type: $('input[name="time_type"]:checked').val(),
                        arrDataDaily: arrDataDaily,
                        arrDataWeekly: arrDataWeekly,
                        arrDataMonthly: arrDataMonthly,
                        arrDataFromTo: arrDataFromTo,
                        branch_apply: $('#branch_apply').val(),
                        order_source: $('#order_source').val(),
                        promotion_apply_to: $('#promotion_apply_to').val(),
                        member_level_id: $('#member_level_id').val(),
                        customer_group_id: $('#customer_group_id').val(),
                        customer_id: $('#customer_id').val(),
                        is_display: is_display,
                        is_feature: is_feature,
                        image: $('#image').val(),
                        description: $('#description').val(),
                        description_detail: $('#description_detail').val(),
                        positionId: positionId,
                        is_actived: is_actived,
                        type_display_app: $('#type_display_app').val()
                    },
                    success: function (res) {
                        if (res.error == false) {
                            swal(res.message, "", "success").then(function (result) {
                                if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                    window.location.href = laroute.route('promotion');
                                }
                                if (result.value == true) {
                                    window.location.href = laroute.route('promotion');
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
                        swal(json['Chỉnh sửa thất bại'], mess_error, "error");
                    }
                });
            }
        });
    }
};

function onDay(day) {
    switch (day) {
        case 'Monday':
            $('#is_monday').prop("checked", true);
            $('#is_other_monday').prop('disabled', false);
            break;
        case 'Tuesday':
            $('#is_tuesday').prop("checked", true);
            $('#is_other_tuesday').prop('disabled', false);
            break;
        case 'Wednesday':
            $('#is_wednesday').prop("checked", true);
            $('#is_other_wednesday').prop('disabled', false);
            break;
        case 'Thursday':
            $('#is_thursday').prop("checked", true);
            $('#is_other_thursday').prop('disabled', false);
            break;
        case 'Friday':
            $('#is_friday').prop("checked", true);
            $('#is_other_friday').prop('disabled', false);
            break;
        case 'Saturday':
            $('#is_saturday').prop("checked", true);
            $('#is_other_saturday').prop('disabled', false);
            break;
        case 'Sunday':
            $('#is_sunday').prop("checked", true);
            $('#is_other_sunday').prop('disabled', false);
            break;
    }
}

function offDay(day) {
    switch (day) {
        case 'Monday':
            $('#is_monday').prop("checked", false);
            $('#is_other_monday').prop('disabled', true);
            $('#is_other_monday').prop("checked", false);
            $('#is_other_monday_start_time').prop("disabled", true);
            $('#is_other_monday_end_time').prop("disabled", true);
            break;
        case 'Tuesday':
            $('#is_tuesday').prop("checked", false);
            $('#is_other_tuesday').prop('disabled', true);
            $('#is_other_tuesday').prop("checked", false);
            $('#is_other_tuesday_start_time').prop("disabled", true);
            $('#is_other_tuesday_end_time').prop("disabled", true);
            break;
        case 'Wednesday':
            $('#is_wednesday').prop("checked", false);
            $('#is_other_wednesday').prop('disabled', true);
            $('#is_other_wednesday').prop("checked", false);
            $('#is_other_wednesday_start_time').prop("disabled", true);
            $('#is_other_wednesday_end_time').prop("disabled", true);
            break;
        case 'Thursday':
            $('#is_thursday').prop("checked", false);
            $('#is_other_thursday').prop('disabled', true);
            $('#is_other_thursday').prop("checked", false);
            $('#is_other_thursday_start_time').prop("disabled", true);
            $('#is_other_thursday_end_time').prop("disabled", true);
            break;
        case 'Friday':
            $('#is_friday').prop("checked", false);
            $('#is_other_friday').prop('disabled', true);
            $('#is_other_friday').prop("checked", false);
            $('#is_other_friday_start_time').prop("disabled", true);
            $('#is_other_friday_end_time').prop("disabled", true);
            break;
        case 'Saturday':
            $('#is_saturday').prop("checked", false);
            $('#is_other_saturday').prop('disabled', true);
            $('#is_other_saturday').prop("checked", false);
            $('#is_other_saturday_start_time').prop("disabled", true);
            $('#is_other_saturday_end_time').prop("disabled", true);
            break;
        case 'Sunday':
            $('#is_sunday').prop("checked", false);
            $('#is_other_sunday').prop('disabled', true);
            $('#is_other_sunday').prop("checked", false);
            $('#is_other_sunday_start_time').prop("disabled", true);
            $('#is_other_sunday_end_time').prop("disabled", true);
            break;
    }
}

function onOther(day) {
    switch (day) {
        case 'Monday':
            $('#is_other_monday').prop("checked", true);
            $('#is_other_monday_start_time').prop("disabled", false);
            $('#is_other_monday_end_time').prop("disabled", false);
            break;
        case 'Tuesday':
            $('#is_other_tuesday').prop("checked", true);
            $('#is_other_tuesday_start_time').prop("disabled", false);
            $('#is_other_tuesday_end_time').prop("disabled", false);
            break;
        case 'Wednesday':
            $('#is_other_wednesday').prop("checked", true);
            $('#is_other_wednesday_start_time').prop("disabled", false);
            $('#is_other_wednesday_end_time').prop("disabled", false);
            break;
        case 'Thursday':
            $('#is_other_thursday').prop("checked", true);
            $('#is_other_thursday_start_time').prop("disabled", false);
            $('#is_other_thursday_end_time').prop("disabled", false);
            break;
        case 'Friday':
            $('#is_other_friday').prop("checked", true);
            $('#is_other_friday_start_time').prop("disabled", false);
            $('#is_other_friday_end_time').prop("disabled", false);
            break;
        case 'Saturday':
            $('#is_other_saturday').prop("checked", true);
            $('#is_other_saturday_start_time').prop("disabled", false);
            $('#is_other_saturday_end_time').prop("disabled", false);
            break;
        case 'Sunday':
            $('#is_other_sunday').prop("checked", true);
            $('#is_other_sunday_start_time').prop("disabled", false);
            $('#is_other_sunday_end_time').prop("disabled", false);
            break;
    }
}

function offOther(day) {
    switch (day) {
        case 'Monday':
            $('#is_other_monday').prop("checked", false);
            $('#is_other_monday_start_time').prop("disabled", true);
            $('#is_other_monday_end_time').prop("disabled", true);
            break;
        case 'Tuesday':
            $('#is_other_tuesday').prop("checked", false);
            $('#is_other_tuesday_start_time').prop("disabled", true);
            $('#is_other_tuesday_end_time').prop("disabled", true);
            break;
        case 'Wednesday':
            $('#is_other_wednesday').prop("checked", false);
            $('#is_other_wednesday_start_time').prop("disabled", true);
            $('#is_other_wednesday_end_time').prop("disabled", true);
            break;
        case 'Thursday':
            $('#is_other_thursday').prop("checked", false);
            $('#is_other_thursday_start_time').prop("disabled", true);
            $('#is_other_thursday_end_time').prop("disabled", true);
            break;
        case 'Friday':
            $('#is_other_friday').prop("checked", false);
            $('#is_other_friday_start_time').prop("disabled", true);
            $('#is_other_friday_end_time').prop("disabled", true);
            break;
        case 'Saturday':
            $('#is_other_saturday').prop("checked", false);
            $('#is_other_saturday_start_time').prop("disabled", true);
            $('#is_other_saturday_end_time').prop("disabled", true);
            break;
        case 'Sunday':
            $('#is_other_sunday').prop("checked", false);
            $('#is_other_sunday_start_time').prop("disabled", true);
            $('#is_other_sunday_end_time').prop("disabled", true);
            break;
    }
}

jQuery.fn.ForceNumericOnly =
    function () {
        return this.each(function () {
            $(this).keydown(function (e) {
                var key = e.charCode || e.keyCode || 0;
                // allow backspace, tab, delete, enter, arrows, numbers and keypad numbers ONLY
                // home, end, period, and numpad decimal
                return (
                    key == 8 ||
                    key == 9 ||
                    key == 13 ||
                    key == 46 ||
                    key == 110 ||
                    key == 190 ||
                    (key >= 35 && key <= 40) ||
                    (key >= 48 && key <= 57) ||
                    (key >= 96 && key <= 105));
            });
        });
    };

function uploadAvatar(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        var imageAvatar = $('#image');
        reader.onload = function (e) {
            $('#blah')
                .attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
        var file_data = $('#getFile').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('link', '_promotion.');
        var fsize = input.files[0].size;
        var fileInput = input,
            file = fileInput.files && fileInput.files[0];
        var img = new Image();

        img.src = window.URL.createObjectURL(file);

        img.onload = function () {
            var imageWidth = img.naturalWidth;
            var imageHeight = img.naturalHeight;

            window.URL.revokeObjectURL(img.src);

            $('.image-size').text(imageWidth + "x" + imageHeight + "px");

        };
        $('.image-capacity').text(Math.round(fsize / 1024) + 'kb');

        $('.image-format').text(input.files[0].name.split('.').pop().toUpperCase());

        if (Math.round(fsize / 1024) <= 10240) {
            $.ajax({
                url: laroute.route("admin.upload-image"),
                method: "POST",
                data: form_data,
                contentType: false,
                cache: false,
                processData: false,
                success: function (res) {
                    if (res.error == 0) {
                        $('#image').val(res.file);
                    }
                }
            });
        } else {
            swal("Hình ảnh vượt quá dung lượng cho phép", "", "error");
        }
    }
}

function uploadAvatar2(input,lang = 'vi') {
    $.getJSON(laroute.route('translate'), function (json) {
        var arr = ['.jpg', '.png', '.jpeg', '.JPG', '.PNG', '.JPEG'];
        var check = 0;
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            if (lang == 'en'){
                var file_data = $('#getFileEn').prop('files')[0];
            } else {
                var file_data = $('#getFile').prop('files')[0];
            }

            var form_data = new FormData();
            form_data.append('file', file_data);
            form_data.append('link', '_promotion.');
            var fsize = input.files[0].size;
            var fileInput = input,
                file = fileInput.files && fileInput.files[0];
            var img = new Image();
            $.map(arr, function (item) {
                if (file_data.name.indexOf(item) != -1) {
                    check = 1;
                }
            })
            if (check == 1) {
                if (Math.round(fsize / 1024) <= 10240) {
                    reader.onload = function (e) {
                        if (lang == 'en'){
                            $('#blah_en')
                                .attr('src', e.target.result);
                        } else {
                            $('#blah')
                                .attr('src', e.target.result);
                        }

                    };
                    reader.readAsDataURL(input.files[0]);
                    $.ajax({
                        url: laroute.route("admin.upload-image"),
                        method: "POST",
                        data: form_data,
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function (res) {
                            if (lang == 'en'){
                                $('#image_en').val(res.file);
                            } else {
                                $('#image').val(res.file);
                            }
                        },
                        error: function (res) {
                            swal.fire(json["Hình ảnh không đúng định dạng"], "", "error");
                        }
                    });
                } else {
                    swal.fire(json["Hình ảnh vượt quá dung lượng cho phép"], "", "error");
                }
            } else {
                swal.fire(json["Hình ảnh không đúng định dạng"], "", "error");
            }
        }
    });
}

uploadImg = function (file) {
    let out = new FormData();
    out.append('file', file, file.name);
    out.append('link', '_promotion.');

    $.ajax({
        method: 'POST',
        url: laroute.route('admin.upload-image'),
        contentType: false,
        cache: false,
        processData: false,
        data: out,
        success: function (img) {
            $("#description_detail").summernote('insertImage', img['file'] , function (image){
                image.css('width', '100%');
            });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error(textStatus + " " + errorThrown);
        }
    });
};