var customer_appointment = {
    _init: function () {
        $('#status').select2({
            placeholder: 'Chọn trạng thái xe'
        });
        $('#date_filter').datepicker({
            format: "dd/mm/yyyy",
            autoclose: true
        });
    },
    hideAddNewModal: function(){
        $('#modal-add').modal('hide');
        $.ajax({
            url: laroute.route('admin.customer_appointment.remove-session-customer_id'),
            dataType: 'JSON',
            method: 'GET',
            success: function (res) {
                console.log(res);
            }
        });
    },
    clickDay: function (date, objectId) {
        $.getJSON(laroute.route('translate'), function (json) {
            $.ajax({
                url: laroute.route('booking-calendar.modal-add'),
                dataType: 'JSON',
                method: 'POST',
                data: {
                    date_now: date,
                    service_id: objectId
                },
                success: function (res) {
                    $('#show-modal').html(res.html);
                    $('#show-modal').find('#modal-add').modal({
                        backdrop: 'static', keyboard: false
                    });

                    var type = $('.source').find('.active input[name="customer_appointment_type"]').val();
                    if (type == 'appointment') {
                        var tpl = $('#append-status-other-tpl').html();
                        $('.append_status').append(tpl);
                        var tpl_date = $('#date-tpl').html();
                        $('.date_app').append(tpl_date);
                        var tpl_time = $('#time-tpl').html();
                        $('.time_app').append(tpl_time);
                    } else {
                        var tpl = $('#append-status-live-tpl').html();
                        $('.append_status').append(tpl);
                    }

                    if (res.is_booking_past == 1) {
                        $('#date, #end_date').datepicker({
                            language: 'vi',
                            orientation: "bottom left", todayHighlight: !0,
                        }).on('changeDate', function (ev) {
                            $(this).datepicker('hide');
                        });
                    } else {
                        $('#date, #end_date').datepicker({
                            startDate: '0d',
                            language: 'vi',
                            orientation: "bottom left", todayHighlight: !0,
                        }).on('changeDate', function (ev) {
                            $(this).datepicker('hide');
                        });
                    }

                    $('#date').val(res.date_now);
                    $('#time, #end_time').timepicker({
                        minuteStep: 1,
                        defaultTime: "",
                        showMeridian: !1,
                        snapToStep: !0,
                    });
                    $('#appointment_source_id, #time_type').select2();

                    $('.room_id').select2({
                        placeholder: json['Chọn phòng'],
                    });

                    $('.staff_id').select2({
                        placeholder: json['Chọn nhân viên'],
                    });

                    $('.service_id').select2({
                        placeholder: json['Chọn dịch vụ'],
                    }).on('select2:select', function (event) {
                        $('#room_id_1').enable(true);
                        $('#staff_id_1').enable(true);
                    }).on('select2:unselect', function (event) {
                        if ($(this).val() == '') {
                            var id = $(this).closest('.tr_quantity').find('input[name="customer_order"]').val();
                            $('#room_id_' + id + '').val('').trigger('change').enable(false);
                            $('#staff_id_' + id + '').val('').trigger('change').enable(false);
                        }
                    });

                    $('#customer_group_id').select2({
                        placeholder: json['Chọn nhóm khách hàng'],
                    });
                }
            });
        });
    },
    //Update Gk ver 3
    new_click: function () {
        $('#new').attr('class', 'btn btn-info color_button active');
        $('#confirm').attr('class', 'btn btn-default');
        $('#processing').attr('class', 'btn btn-default');
    },
    confirm_click: function () {
        $('#confirm').attr('class', 'btn btn-info color_button active');
        $('#new').attr('class', 'btn btn-default');
        $('#processing').attr('class', 'btn btn-default');
    },
    processing_click: function () {
        $('#processing').attr('class', 'btn btn-info  color_button active');
        $('#confirm').attr('class', 'btn btn-default');
        $('#new').attr('class', 'btn btn-default');
    },
    //End update Gk ver 3
    appointment: function (e) {
        $.getJSON(laroute.route('translate'), function (json) {
            $(e).attr('class', 'btn btn-info color_button active');
            $('#direct').attr('class', 'btn btn-default ');
            let name = json['gọi điện'];
            let $element = $('#appointment_source_id')
            let val = $element.find("option:contains('" + name + "')").val()
            $("#appointment_source_id").val(val).trigger('change');
            //Trạng thái lịch hẹn
            $('.append_status').empty();
            var tpl = $('#append-status-other-tpl').html();
            $('.append_status').append(tpl);
        });
    },
    direct: function (e) {
        $(e).attr('class', 'btn btn-info color_button active');
        $('#appointment').attr('class', 'btn btn-default');
        $("#appointment_source_id").val('1').trigger('change');
        //Trạng thái lịch hẹn
        $('.append_status').empty();
        var tpl = $('#append-status-live-tpl').html();
        $('.append_status').append(tpl);
    },
    chooseCustomer: function (obj) {
        $.getJSON(laroute.route('translate'), function (json) {
            $.ajax({
                url: laroute.route('admin.customer_appointment.search-phone'),
                dataType: 'JSON',
                method: 'POST',
                data: {
                    phone: $(obj).val()
                },
                success: function (res) {
                    var arr = [];
                    $.map(res.list_phone, function (a) {
                        arr.push(a.phone);
                    });
                    $('#phone1').autocomplete({
                        source: arr,
                        change: function () {
                            var phone = $(this).val();
                            $.ajax({
                                url: laroute.route('admin.customer_appointment.cus-phone'),
                                dataType: 'JSON',
                                method: 'post',
                                data: {
                                    phone: phone
                                },
                                success: function (res) {
                                    if (res.success == 1) {
                                        $('#customer_hidden').val(res.cus.customer_id);
                                        $('#full_name').val(res.cus.full_name);
                                        $('#full_name').attr('disabled', true);
                                        $('#customer_group_id').val(res.cus.customer_group_id).trigger('change').attr('disabled', true);
                                        $('#HistoryAppointment').PioTable({
                                            baseUrl: laroute.route('admin.customer_appointment.list-history'),
                                        });
                                        $('#HistoryAppointment').PioTable('refresh');
                                    }
                                    if (res.phone_new == 1) {
                                        $('#customer_hidden').val('');
                                        $('#full_name').val('');
                                        $('#full_name').attr('disabled', false);
                                        $('#customer_group_id').val('').trigger('change').attr('disabled', false);
                                        $('.lstHistoryAppointment').html(json['Không có lịch hẹn nào'])
                                    }

                                    $("#table_quantity > tbody").find('.tr_card').remove();
                                    //Lấy list thẻ liệu trình
                                    if (res.numberMemberCard > 0) {
                                        var tpl = $('#table-card-tpl').html();
                                        tpl = tpl.replace(/{stt}/g, 2);
                                        tpl = tpl.replace(/{name}/g, json['Thẻ liệu trình']);
                                        tpl = tpl.replace(/{type}/g, 'member_card');
                                        $("#table_quantity > tbody").append(tpl);

                                        $('#service_id_2').select2({
                                            placeholder: json['Chọn thẻ liệu trình']
                                        }).on('select2:select', function (event) {
                                            $('#room_id_2').enable(true);
                                            $('#staff_id_2').enable(true);
                                        }).on('select2:unselect', function (event) {
                                            if ($(this).val() == '') {
                                                var id = $(this).closest('.tr_quantity').find('input[name="customer_order"]').val();
                                                $('#room_id_' + id + '').val('').trigger('change').enable(false);
                                                $('#staff_id_' + id + '').val('').trigger('change').enable(false);
                                            }
                                        });

                                        $('.room_id').select2({
                                            placeholder: json['Chọn phòng'],
                                        });

                                        $('.staff_id').select2({
                                            placeholder: json['Chọn nhân viên'],
                                        });

                                        $.map(res.listCard, function (v) {
                                            $('#service_id_2').append('<option value="' + v.customer_service_card_id + '">' + v.card_name + '</option>');
                                        });

                                        $.map(res.optionStaff, function (v, k) {
                                            $('#staff_id_2').append('<option value="' + k + '">' + v + '</option>');
                                        });

                                        $.map(res.optionRoom, function (v, k) {
                                            $('#room_id_2').append('<option value="' + k + '">' + v + '</option>');
                                        });
                                    }
                                }
                            })
                        },
                        select: function (event, ui) {
                            var value = ui.item.value;
                            $.ajax({
                                url: laroute.route('admin.customer_appointment.cus-phone'),
                                dataType: 'JSON',
                                method: 'post',
                                data: {
                                    phone: value
                                },
                                success: function (res) {
                                    if (res.success == 1) {
                                        $('#customer_hidden').val(res.cus.customer_id);
                                        $('#full_name').val(res.cus.full_name);
                                        $('#full_name').attr('disabled', true);
                                        $('#customer_group_id').val(res.cus.customer_group_id).trigger('change').attr('disabled', true);
                                        $('#HistoryAppointment').PioTable({
                                            baseUrl: laroute.route('admin.customer_appointment.list-history'),
                                        });
                                        $('#HistoryAppointment').PioTable('refresh');
                                    }

                                    $("#table_quantity > tbody").find('.tr_card').remove();
                                    //Lấy list thẻ liệu trình
                                    if (res.numberMemberCard > 0) {
                                        var tpl = $('#table-card-tpl').html();
                                        tpl = tpl.replace(/{stt}/g, 2);
                                        tpl = tpl.replace(/{name}/g, json['Thẻ liệu trình']);
                                        tpl = tpl.replace(/{type}/g, 'member_card');
                                        $("#table_quantity > tbody").append(tpl);

                                        $('#service_id_2').select2({
                                            placeholder: json['Chọn thẻ liệu trình']
                                        }).on('select2:select', function (event) {
                                            $('#room_id_2').enable(true);
                                            $('#staff_id_2').enable(true);
                                        }).on('select2:unselect', function (event) {
                                            if ($(this).val() == '') {
                                                var id = $(this).closest('.tr_quantity').find('input[name="customer_order"]').val();
                                                $('#room_id_' + id + '').val('').trigger('change').enable(false);
                                                $('#staff_id_' + id + '').val('').trigger('change').enable(false);
                                            }
                                        });

                                        $('.room_id').select2({
                                            placeholder: json['Chọn phòng'],
                                        });

                                        $('.staff_id').select2({
                                            placeholder: json['Chọn nhân viên'],
                                        });

                                        $.map(res.listCard, function (v) {
                                            $('#service_id_2').append('<option value="' + v.customer_service_card_id + '">' + v.card_name + '</option>');
                                        });

                                        $.map(res.optionStaff, function (v, k) {
                                            $('#staff_id_2').append('<option value="' + k + '">' + v + '</option>');
                                        });

                                        $.map(res.optionRoom, function (v, k) {
                                            $('#room_id_2').append('<option value="' + k + '">' + v + '</option>');
                                        });
                                    }
                                }
                            })
                        },
                    });
                }
            })
        });
    },
    addNew: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-add');
            form.validate({
                rules: {
                    full_name: {
                        required: true
                    },
                    phone1: {
                        required: true,
                        minlength: 10,
                        maxlength: 11,
                        number: true
                    },
                    time: {
                        required: true
                    },
                    date: {
                        required: true
                    },
                    quantity_customer: {
                        min: 1,
                        required: true,
                        number: true,
                        max: 10,
                    },
                    end_date: {
                        required: true
                    },
                    end_time: {
                        required: true
                    }
                },
                messages: {
                    full_name: {
                        required: json['Hãy nhập tên khách hàng']
                    },
                    phone1: {
                        required: json['Hãy nhập số điện thoại'],
                        minlength: json['Số điện thoại tối thiểu 10 số'],
                        maxlength: json['Số điện thoại tối đa 11 số'],
                        number: json['Số điện thoại không hợp lệ']
                    },
                    time: {
                        required: json['Hãy chọn giờ hẹn']
                    },
                    date: {
                        required: json['Hãy chọn ngày hẹn']
                    },
                    quantity_customer: {
                        min: json['Số lượng khách hàng tối thiểu 1'],
                        required: json['Hãy nhập số lượng khách hàng'],
                        number: json['Số lượng khách hàng không hợp lệ'],
                        max: json['Số lượng khách hàng tối đa 10'],
                    },
                    end_date: {
                        required: json['Hãy chọn ngày kết thúc']
                    },
                    end_time: {
                        required: json['Hãy chọn giờ kết thúc']
                    }
                },
            });

            if (!form.valid()) {
                return false;
            }

            var full_name = $('#full_name').val();
            var phone1 = $('#phone1').val();
            var type = $('.source').find('.active input[name="customer_appointment_type"]').val();
            var date = $('#date').val();
            var time = $('#time').val();
            var customer_hidden = $('#customer_hidden').val();
            var description = $('#description').val();
            var customer_quantity = $('#quantity_customer').val();
            var appointment_source_id = $('#appointment_source_id').val();
            // var customer_refer = $('#search_refer').val();
            var status = $('.active').find(' input[name="status"]').val();
            var table_quantity = [];
            $.each($('#table_quantity').find(".tr_quantity"), function () {
                var stt = $(this).find("input[name='customer_order']").val();
                var sv = '';
                if ($('#service_id_' + stt + '').val() != '') {
                    sv = $('#service_id_' + stt + '').val();
                }
                var arr = {
                    stt: stt,
                    sv: sv,
                    staff: $('#staff_id_' + stt + '').val(),
                    room: $('#room_id_' + stt + '').val(),
                    object_type: $(this).find("input[name='object_type']").val()
                };
                table_quantity.push(arr);
            });
            //end_date, end_time
            var endDate = $('#end_date').val();
            var endTime = $('#end_time').val();
            //kiểm tra khách hàng đã có lịch hẹn ngày hôm nay chưa
            if (customer_hidden != '') {
                $.ajax({
                    url: laroute.route('admin.customer_appointment.check-number-appointment'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        customer_id: customer_hidden,
                        date: date,
                        time: time,
                        endDate: endDate,
                        endTime: endTime,
                        time_type: $('#time_type').val(),
                        type_number: $('#type_number').val()
                    },
                    success: function (res) {
                        mApp.unblock("#m_blockui_1_content");
                        if(res.status == -1){
                            swal.fire(res.message, '', "error");
                        }
                        if (res.time_error == 1) {
                            $('.error_time').text(json['Ngày hẹn, giờ hẹn không hợp lệ']);
                        }
                        if (res.status == 0) {
                            addLoad(full_name, phone1, type, appointment_source_id, customer_quantity, date, time,
                                customer_hidden, description, table_quantity, status, endDate, endTime);
                        }
                        if (res.status == 1) {
                            if (res.number < 3) {
                                swal({
                                    title: json["Khách đã có lịch hẹn hôm nay lúc"] + " " + res.time,
                                    text: "",
                                    type: "warning",
                                    showCancelButton: !0,
                                    confirmButtonText: json["THÊM MỚI"],
                                    cancelButtonText: json["CẬP NHẬT"]
                                });
                                $('.swal2-confirm').click(function () {
                                    addLoad(full_name, phone1, type, appointment_source_id, customer_quantity, date, time,
                                        customer_hidden, description, table_quantity, status, endDate, endTime);
                                });
                                $('.swal2-cancel').click(function () {
                                    updateLoad(customer_hidden, date, time, type, status, appointment_source_id, description, customer_quantity, table_quantity, endDate, endTime);
                                });
                            } else {
                                swal({
                                    title: json["Khách hàng đã đặt tối đa 3 lịch hẹn trong hôm nay"],
                                    text: "",
                                    type: "warning",
                                    confirmButtonText: json["Cập nhật lịch gần nhất"],
                                    confirmButtonClass: "btn btn-focus m-btn m-btn--pill m-btn--air"
                                });
                                $('.swal2-confirm').click(function () {
                                    updateLoad(customer_hidden, date, time, type, status, appointment_source_id, description, customer_quantity, table_quantity, endDate, endTime);
                                })
                            }
                        }
                    }
                });
            } else {
                addLoad(full_name, phone1, type, appointment_source_id, customer_quantity, date, time,
                    customer_hidden, description, table_quantity, status, endDate, endTime);
            }
        });
    },
    clickDetailObject: function (objectId, startDate, endDate) {
        $.ajax({
            url: laroute.route('booking-calendar.modal-detail'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                service_id: objectId,
                start_date: startDate,
                end_date: endDate
            },
            success: function (res) {
                $('#show-modal').html(res.html);
                $('#show-modal').find('#modal-detail').modal({
                    backdrop: 'static', keyboard: false
                });
            }
        });
    },
    changeTimeType: function (obj) {
        $('.time_type').empty();

        if ($(obj).val() == 'R') {
            //Theo ngày
            var tpl = $('#to-date-tpl').html();
            $('.time_type').append(tpl);

            if ($('#is_booking_past').val() == 1) {
                $('#date, #end_date').datepicker({
                    language: 'vi',
                    orientation: "bottom left", todayHighlight: !0,
                }).on('changeDate', function (ev) {
                    $(this).datepicker('hide');
                });
            } else {
                $('#date, #end_date').datepicker({
                    startDate: '0d',
                    language: 'vi',
                    orientation: "bottom left", todayHighlight: !0,
                }).on('changeDate', function (ev) {
                    $(this).datepicker('hide');
                });
            }

            $('#time, #end_time').timepicker({
                minuteStep: 1,
                defaultTime: "",
                showMeridian: !1,
                snapToStep: !0,
            });
        } else {
            //Theo tuần, tháng, năm
            var tpl = $('#w-m-y-tpl').html();
            $('.time_type').append(tpl);

            if ($('#is_booking_past').val() == 1) {
                $('#end_date').datepicker({
                    language: 'vi',
                    orientation: "bottom left", todayHighlight: !0,
                }).on('changeDate', function (ev) {
                    $(this).datepicker('hide');
                });
            } else {
                $('#end_date').datepicker({
                    startDate: '0d',
                    language: 'vi',
                    orientation: "bottom left", todayHighlight: !0,
                }).on('changeDate', function (ev) {
                    $(this).datepicker('hide');
                });
            }

            $('#end_time').timepicker({
                minuteStep: 1,
                defaultTime: "",
                showMeridian: !1,
                snapToStep: !0,
            });

            new AutoNumeric.multiple('#type_number', {
                currencySymbol: '',
                decimalCharacter: '.',
                digitGroupSeparator: ',',
                decimalPlaces: decimal_number,
                eventIsCancelable: true,
                minimumValue: 0
            });

            customer_appointment.changeNumberTime();
        }
    },
    changeNumberTime: function (obj) {
        $.ajax({
            url: laroute.route('admin.customer_appointment.change-number-type'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                time_type: $('#time_type').val(),
                type_number: $('#type_number').val(),
                date: $('#date').val(),
                time: $('#time').val()
            },
            success: function (res) {
                $('#end_date').val(res.end_date);
                $('#end_time').val(res.end_time);
            }
        });
    },
    loadPageReceipt: function (id) {
        $.getJSON(laroute.route('translate'), function (json) {
            swal.fire({
                title: json['Bạn có muốn thanh toán trước hạn?'],
                html: '',
                confirmButtonText: json['Xác nhận'],
                confirmButtonClass: "btn btn-success m-btn--wide m-btn--md",
                showCancelButton: true,
                cancelButtonText: json['Hủy'],
                cancelButtonClass: "btn btn-default m-btn--wide m-btn--md"
            }).then(function (result) {
                if (result.value) {
                    window.location.href = laroute.route('admin.customer_appointment.receipt', {id: id});
                }
            });
        });
    },
    click_modal_edit: function (id) {
        $('#modal-detail').modal('hide');

        $.getJSON(laroute.route('translate'), function (json) {
            $.ajax({
                type: 'POST',
                url: laroute.route('admin.customer_appointment.detail'),
                data: {
                    id: id
                },
                dataType: 'JSON',
                success: function (res) {
                    $('#show-modal').html(res.html);
                    $('#show-modal').find('#modal-edit').modal({
                        backdrop: 'static', keyboard: false
                    });

                    if (res.is_booking_past == 1) {
                        $('#date, #end_date').datepicker({
                            language: 'vi',
                            orientation: "bottom left", todayHighlight: !0,
                        }).on('changeDate', function (ev) {
                            $(this).datepicker('hide');
                        });
                    } else {
                        $('#date, #end_date').datepicker({
                            startDate: '0d',
                            language: 'vi',
                            orientation: "bottom left", todayHighlight: !0,
                        }).on('changeDate', function (ev) {
                            $(this).datepicker('hide');
                        });
                    }

                    $('#time, #end_time').timepicker({
                        minuteStep: 1,
                        defaultTime: "",
                        showMeridian: !1,
                        snapToStep: !0,
                    });

                    $('.room_id').select2({
                        placeholder: json['Chọn phòng'],
                    });

                    $('.staff_id').select2({
                        placeholder: json['Chọn nhân viên'],
                    });

                    $('#time_type').select2();

                    $('.service_id').select2({
                        placeholder: json['Chọn dịch vụ'],
                    }).on('select2:select', function (event) {
                        var id = $(this).closest('.tr_quantity').find('input[name="customer_order"]').val();
                        $('#room_id_' + id + '').val('').enable(true);
                        $('#staff_id_' + id + '').enable(true);
                    }).on('select2:unselect', function (event) {
                        if ($(this).val() == '') {
                            var id = $(this).closest('.tr_quantity').find('input[name="customer_order"]').val();
                            $('#room_id_' + id + '').val('').trigger('change').enable(false);
                            $('#staff_id_' + id + '').val('').trigger('change').enable(false);
                        }
                    });

                    new AutoNumeric.multiple('#type_number', {
                        currencySymbol: '',
                        decimalCharacter: '.',
                        digitGroupSeparator: ',',
                        decimalPlaces: decimal_number,
                        eventIsCancelable: true,
                        minimumValue: 0
                    });
                    $('#HistoryAppointmentEdit').PioTable({
                        baseUrl: laroute.route('admin.customer_appointment.list-history'),
                    });
                    $('#HistoryAppointmentEdit').PioTable('refresh');
                }
            });
        });
    },
    //Update GK ver 3
    status_edit: function (e) {
        var $this = $(e).find('input[name="status"]').val();
        if ($this == 'wait') {
            $('#new_stt').attr('class', 'btn btn-default ');
            $('#confirm_stt').attr('class', 'btn btn-default');
            $('#wait_stt').attr('class', 'btn btn-info  active_edit active color_button');
            $('#cancel_stt').attr('class', 'btn btn-default');
            $('#processing_stt').attr('class', 'btn btn-default');
        } else if ($this == 'cancel') {
            $('#new_stt').attr('class', 'btn btn-default');
            $('#confirm_stt').attr('class', 'btn btn-default');
            $('#wait_stt').attr('class', 'btn btn-default ');
            $('#cancel_stt').attr('class', 'btn btn-info active_edit active color_button');
            $('#processing_stt').attr('class', 'btn btn-default');
        } else if ($this == 'new') {
            $('#new_stt').attr('class', 'btn btn-info  active_edit active color_button');
            $('#confirm_stt').attr('class', 'btn btn-default ');
            $('#wait_stt').attr('class', 'btn btn-default');
            $('#cancel_stt').attr('class', 'btn btn-default');
            $('#processing_stt').attr('class', 'btn btn-default');
        } else if ($this == 'confirm') {
            $('#new_stt').attr('class', 'btn btn-default');
            $('#confirm_stt').attr('class', 'btn btn-info active_edit active color_button');
            $('#wait_stt').attr('class', 'btn btn-default ');
            $('#cancel_stt').attr('class', 'btn btn-default');
            $('#processing_stt').attr('class', 'btn btn-default');
        } else if ($this == 'processing') {
            $('#new_stt').attr('class', 'btn btn-default');
            $('#confirm_stt').attr('class', 'btn btn-default');
            $('#wait_stt').attr('class', 'btn btn-default ');
            $('#cancel_stt').attr('class', 'btn btn-default');
            $('#processing_stt').attr('class', 'btn btn-info active_edit active color_button');
        }
    },
    //End Update
    submit_edit: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-edit');

            form.validate({
                rules: {
                    time_edit: {
                        required: true
                    },
                    date_edit: {
                        required: true
                    },
                    quantity_customer_edit: {
                        min: 1,
                        required: true,
                        number: true,
                        max: 10
                    },
                    end_date: {
                        required: true
                    },
                    end_time: {
                        required: true
                    }
                },
                messages: {
                    time_edit: {
                        required: json['Hãy chọn giờ hẹn']
                    },
                    date_edit: {
                        required: json['Hãy chọn ngày hẹn']
                    },
                    quantity_customer_edit: {
                        min: json['Số lượng khách hàng tối thiểu 1'],
                        required: json['Hãy nhập số lượng khách hàng'],
                        number: json['Số lượng khách hàng không hợp lệ'],
                        max: json['Số lượng khách hàng tối đa 10']
                    },
                    end_date: {
                        required: json['Hãy chọn ngày kết thúc']
                    },
                    end_time: {
                        required: json['Hãy chọn giờ kết thúc']
                    }
                },
            });

            if (!form.valid()) {
                return false;
            }

            var time_edit = $('#time').val();
            var date_edit = $('#date').val();
            var customer_appointment_id = $('#customer_appointment_id').val();
            var customer_appointment_type = $('#customer_appointment_type').val();
            var description = $('#description_edit').val();
            var customer_quantity = $('#quantity_customer_edit').val();
            var status = $('.active_edit ').find('input[name="status"]').val();
            var table_quantity = [];
            $.each($('#table_quantity_edit').find(".tr_quantity"), function () {
                var stt = $(this).find("input[name='customer_order']").val();
                var sv = '';
                if ($('#service_id_' + stt + '').val() != '') {
                    sv = $('#service_id_' + stt + '').val();
                }
                var arr = {
                    stt: stt,
                    sv: sv,
                    staff: $('#staff_id_' + stt + '').val(),
                    room: $('#room_id_' + stt + '').val(),
                    object_type: $(this).find("input[name='object_type']").val()
                };
                table_quantity.push(arr);
            });
            $.ajax({
                url: laroute.route('admin.customer_appointment.submitModalEdit'),
                dataType: 'JSON',
                method: 'POST',
                data: {
                    date: date_edit,
                    time: time_edit,
                    customer_appointment_id: customer_appointment_id,
                    customer_appointment_type: customer_appointment_type,
                    description: description,
                    customer_quantity: customer_quantity,
                    status: status,
                    table_quantity: table_quantity,
                    time_edit_new: $('#time_edit_new').val(),
                    customer_id: $('#customer_id').val(),
                    discount: $('#discount').val(),
                    endDate: $('#end_date').val(),
                    endTime: $('#end_time').val(),
                    time_type: $('#time_type').val(),
                    type_number: $('#type_number').val()
                },
                success: function (res) {
                    if (res.error == false) {
                        swal(json["Cập nhật lịch hẹn thành công"], "", "success");
                        $('#modal-edit').modal('hide');
                        window.location.reload();
                    } else {
                        swal(res.message, '', "error");
                    }
                }
            })
        });
    },
    out_modal: function () {
        $.ajax({
            url: laroute.route('admin.customer_appointment.remove-session-customer_id'),
            dataType: 'JSON',
            method: 'GET',
            success: function (res) {
                $('#modal-edit').modal('hide');
            }
        });
    },
};

function addLoad(full_name, phone1, type, appointment_source_id, customer_quantity, date, time,
                 customer_hidden, description, table_quantity, status, endDate, endTime) {
    $.getJSON(laroute.route('translate'), function (json) {
        $.ajax({
            url: laroute.route('admin.customer_appointment.submitModalAdd'),
            data: {
                full_name: full_name,
                phone1: phone1,
                customer_appointment_type: type,
                appointment_source_id: appointment_source_id,
                customer_quantity: customer_quantity,
                date: date,
                time: time,
                customer_hidden: customer_hidden,
                description: description,
                table_quantity: table_quantity,
                status: status,
                endDate: endDate,
                endTime: endTime,
                time_type: $('#time_type').val(),
                type_number: $('#type_number').val(),
                customer_group_id: $('#customer_group_id').val()
            },
            method: 'POST',
            dataType: "JSON",
            success: function (res) {
                if (res.error == false) {
                    window.location.reload();
                    swal(json["Thêm lịch hẹn thành công"], "", "success");
                } else {
                    swal(res.message, '', "error");
                }
            }
        });
    });

}

function updateLoad(customer_hidden, date, time, type, status, appointment_source_id, description, customer_quantity, table_quantity, endDate, endTime) {
    $.getJSON(laroute.route('translate'), function (json) {
        $.ajax({
            url: laroute.route('admin.customer_appointment.update-number-appointment'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                customer_id: customer_hidden,
                date: date,
                time: time,
                type: type,
                status: status,
                appointment_source_id: appointment_source_id,
                description: description,
                customer_quantity: customer_quantity,
                table_quantity: table_quantity,
                endDate: endDate,
                endTime: endTime,
                time_type: $('#time_type').val(),
                type_number: $('#type_number').val()
            },
            success: function (res) {
                if (res.error == false) {
                    window.location.reload();
                    swal(json["Cập nhật lịch hẹn thành công"], "", "success");
                } else {
                    swal(res.message, '', "error");
                }
            }
        })
    });
}
$(window).scroll(function (event) {
    scrollFilterFix();
});
$(document).ready(function () {
    scrollFilterFix();
    $('.btn-fillter i').click(function () {
        if ($('.block-position-fix .form-inline').hasClass('filter-show')){
            $('.block-position-fix .form-inline').removeClass('filter-show');
        } else {
            $('.block-position-fix .form-inline').addClass('filter-show');
        }
    });

});


function scrollFilterFix() {
    $('.checkScrollFix .form-inline').removeClass('filter-show');
    var scroll = $(window).scrollTop();
    // Do something
    if (scroll > 80){
        if (!$('.checkScrollFix').hasClass('block-position-fix')){
            $('.checkScrollFix').addClass('block-position-fix');
            $('.checkScrollFix .form-inline').removeClass('filter-show');
        }
    } else {
        $('.checkScrollFix').removeClass('block-position-fix');
    }
}
