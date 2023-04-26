var customer_appointment = {
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
    click_modal_edit: function (id) {
        $.getJSON(laroute.route('translate'), function (json) {
            $('#modal-detail').modal('hide');

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
                window.location.reload();
            }
        });
    }
};