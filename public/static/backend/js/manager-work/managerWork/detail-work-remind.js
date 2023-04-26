$(document).ready(function () {
    $('.selectForm').select2();
    $.getJSON(laroute.route('translate'), function (json) {
        var arrRange = {};
        arrRange[json["Hôm nay"]] = [moment(), moment()];
        arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
        arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
        arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
        $(".searchDate").daterangepicker({
            // autoUpdateInput: false,
            autoApply: true,
            // buttonClasses: "m-btn btn",
            // applyClass: "btn-primary",
            // cancelClass: "btn-danger",
            // startDate: moment().subtract(6, "days"),
            startDate: moment().startOf("month"),
            endDate: moment().endOf("month"),
            locale: {
                cancelLabel: 'Clear',
                format: 'DD/MM/YYYY',
                "applyLabel": json["Đồng ý"],
                "cancelLabel": json["Thoát"],
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
        }).on('apply.daterangepicker', function (ev, picker) {
            var start = picker.startDate.format("DD/MM/YYYY");
            var end = picker.endDate.format("DD/MM/YYYY");
            $(this).val(start + " - " + end);
        });
    });
    Remind.search($('#manage_work_id').val());
});
var Remind = {

    showPopup : function(id){
        $.ajax({
            url: laroute.route('manager-work.detail.show-popup-remind-popup'),
            method: "POST",
            data: {
                manage_work_id : $('#manage_work_id').val(),
                manage_remind_id : id
            },
            success: function (res) {
                if (res.error == false){
                    $('#block_append').empty();
                    $('#block_append').append(res.view);
                    // $('.selectForm').select2({
                    //     dropdownParent: $(".modal")
                    // });
                    $('select:not(.normal)').each(function () {
                        $(this).select2({
                            dropdownParent: $(this).parent()
                        });
                    });

                    $(".date-timepicker").datetimepicker({
                        todayHighlight: !0,
                        autoclose: !0,
                        pickerPosition: "bottom-left",
                        format: "dd/mm/yyyy hh:ii",
                        startDate : new Date()
                        // locale: 'vi'
                    });

                    AutoNumeric.multiple('.input-mask',{
                        currencySymbol : '',
                        decimalCharacter : '.',
                        digitGroupSeparator : ',',
                        decimalPlaces: 0,
                        minimumValue: 0
                    });

                    AutoNumeric.multiple('.progress_input',{
                        currencySymbol : '',
                        decimalCharacter : '.',
                        digitGroupSeparator : ',',
                        decimalPlaces: 0,
                        minimumValue: 0,
                        maximumValue: 100,
                    });


                    $('#popup-remind-work').modal('show');
                    // $('#popup-remind-work').on('hidden.bs.modal', function (e) {
                    //     location.reload();
                    // });
                } else {
                    swal.fire(res.message, '', 'error');
                }
            }
        });
    },

    search: function (manage_work_id) {
        $.ajax({
            url: laroute.route('manager-work.detail.search-remind'),
            method: "POST",
            data: $('#form-search').serialize()+'&manage_work_id='+manage_work_id,
            success: function (res) {
                if (res.error == false){
                    $('.append-list-remind').empty();
                    $('.append-list-remind').append(res.view);
                } else {
                    swal.fire(res.message, '', 'error');
                }
            }
        });
    },

    addCloseRemind : function (check) {
        $.ajax({
            url: laroute.route('manager-work.staff-overview.add-remind-work'),
            data: $('#form-remind-staff-work').serialize(),
            method: "POST",
            dataType: "JSON",
            success: function(res) {
                if (res.error == false) {
                    Remind.search($('#manage_work_id').val());
                    if (check == 0){
                        swal(res.message,'','success').then(function () {
                            // location.reload();
                            $('#popup-remind-work').modal('hide');
                        });
                    } else {
                        $('#popup-remind-work').modal('hide');
                        $('.modal-backdrop').remove();
                        $('#block_append').empty();
                        $('#block_append').append(res.view);
                        $('.selectForm').select2({
                            dropdownParent: $(".modal")
                        });
                        $(".date-timepicker").datetimepicker({
                            todayHighlight: !0,
                            autoclose: !0,
                            pickerPosition: "bottom-left",
                            format: "dd/mm/yyyy hh:ii",
                            startDate : new Date()
                            // locale: 'vi'
                        });

                        AutoNumeric.multiple('.input-mask',{
                            currencySymbol : '',
                            decimalCharacter : '.',
                            digitGroupSeparator : ',',
                            decimalPlaces: 0,
                            minimumValue: 0
                        });
                        $('#popup-remind-work').modal('show');
                        // $('#popup-remind-work').on('hidden.bs.modal', function (e) {
                        //     location.reload();
                        // });
                    }

                } else {
                    swal('',res.message,'error');
                }
            },
            error: function (res) {
                var mess_error = '';
                $.map(res.responseJSON.errors, function (a) {
                    mess_error = mess_error.concat(a + '<br/>');
                });
                swal('', mess_error, "error");
            }
        });
    },

    removeRemind:function (id) {
        $.getJSON(laroute.route('translate'), function (json) {
            swal({
                title: json['Xoá nhắc nhở'],
                text: json["Bạn có muốn xóa không?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json['Xóa'],
                cancelButtonText: json['Hủy'],

            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        url: laroute.route('manager-work.detail.remove-remind'),
                        method: "POST",
                        data: {
                            manage_remind_id: id
                        },
                        success: function (res) {
                            if (res.error == false){
                                swal.fire(res.message, '', 'success').then(function () {
                                    // location.reload();
                                    Remind.search($('#manage_work_id').val());
                                });
                            } else {
                                swal.fire(res.message, '', 'error');
                            }
                        }
                    });
                }
            });
        });
    },

    changeActive : function (id) {

        is_active = 0;
        if ($('#is_active_'+id).is(':checked')) {
            is_active = 1;
        }

        $.ajax({
            url: laroute.route('manager-work.detail.change-status-remind'),
            method: "POST",
            data: {
                manage_remind_id: id,
                is_active:is_active
            },
            success: function (res) {
                if (res.error == false){
                    swal.fire(res.message, '', 'success').then(function () {
                        // location.reload();
                    });
                } else {
                    swal.fire(res.message, '', 'error');
                }
            }
        });
    },

    removeSearchRemind : function () {
        $('#description').val('');
        $('.selectForm').val('');
        $('#sort_date_remind').val('DESC');
        $.getJSON(laroute.route('translate'), function (json) {
            var arrRange = {};
            arrRange[json["Hôm nay"]] = [moment(), moment()];
            arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
            arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
            arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
            $(".searchDate").daterangepicker({
                // autoUpdateInput: false,
                autoApply: true,
                // buttonClasses: "m-btn btn",
                // applyClass: "btn-primary",
                // cancelClass: "btn-danger",
                // startDate: moment().subtract(6, "days"),
                startDate: moment().startOf("month"),
                endDate: moment().endOf("month"),
                locale: {
                    cancelLabel: 'Clear',
                    format: 'DD/MM/YYYY',
                    "applyLabel": json["Đồng ý"],
                    "cancelLabel": json["Thoát"],
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
            }).on('apply.daterangepicker', function (ev, picker) {
                var start = picker.startDate.format("DD/MM/YYYY");
                var end = picker.endDate.format("DD/MM/YYYY");
                $(this).val(start + " - " + end);
            });
        });
        Remind.search($('#manage_work_id').val());
    },
    sortListRemind : function () {
        var sort = $('#sort_date_remind').val();

        if (sort == 'DESC'){
            $('#sort_date_remind').val('ASC');
        } else {
            $('#sort_date_remind').val('DESC');
        }
        Remind.search($('#manage_work_id').val());
    }
}