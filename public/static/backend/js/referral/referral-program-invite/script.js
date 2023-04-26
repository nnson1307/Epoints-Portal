var referralProgramInvite = {

    program_invite_id : 0,

    _initCommissionOrder: function () {
        $('#autotable').PioTable({
            baseUrl: laroute.route('referral.commission-order.listCommissionOrder'),
            perPage: 25
        });

        $.getJSON(laroute.route('translate'), function (json) {
            var arrRange = {};
            arrRange[json["Hôm nay"]] = [moment(), moment()];
            arrRange[json["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
            arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
            arrRange[json["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
            arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
            arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
            $("#created_at").daterangepicker({
                autoUpdateInput: true,
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

            $("#created_at").val("");
        });
    },

    _initCommissionOrderDetail: function () {
        $('#autotable').PioTable({
            baseUrl: laroute.route('referral.commission-order.commissionOrderDetailList', {id : referralProgramInvite.program_invite_id}),
            perPage: 25
        });

        $.getJSON(laroute.route('translate'), function (json) {
            var arrRange = {};
            arrRange[json["Hôm nay"]] = [moment(), moment()];
            arrRange[json["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
            arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
            arrRange[json["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
            arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
            arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
            $("#created_at").daterangepicker({
                autoUpdateInput: true,
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

            $("#created_at").val("");
        });
    },

    _init: function () {
        $('#autotable').PioTable({
            baseUrl: laroute.route('referral.referral-program-invite.list'),
            perPage: 25
        });

        $.getJSON(laroute.route('translate'), function (json) {
            var arrRange = {};
            arrRange[json["Hôm nay"]] = [moment(), moment()];
            arrRange[json["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
            arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
            arrRange[json["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
            arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
            arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
            $("#created_at").daterangepicker({
                autoUpdateInput: true,
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

            $("#created_at").val("");
        });
    },

    reject : function (referral_program_invite_id){
        jsonLang = JSON.parse(localStorage.getItem('tranlate'));
        Swal.fire({
            title: jsonLang['Từ chối hoa hồng'],
            buttonsStyling: false,

            confirmButtonText: jsonLang['Xác nhận'],
            confirmButtonClass: "btn btn-primary btn-hover-brand mt-0",
            reverseButtons: true,
            showCancelButton: true,
            cancelButtonText: jsonLang['Hủy'],
            cancelButtonClass: "btn btn-secondary btn-hover-brand mt-0",
            html: jsonLang['Bạn xác nhận từ chối hoa hồng của người giới thiệu này, thao tác này sẽ không hoàn lại được sau khi thực hiện']+
                '<textarea id="reason" class="swal2-input" placeholder="'+jsonLang['Vui lòng nhập lý do']+'"></textarea>',
            onOpen: function() {
                $('#reason').focus();
            }
        }).then(function (result) {
            if (result.value) {
                if($('#reason').val() == ''){
                    Swal.fire(jsonLang["Lỗi!"], jsonLang['Vui lòng nhập ly do'] , "error");
                } else {
                    $.ajax({
                        url: laroute.route('referral.referral-program-invite.updateProgramInvite'),
                        method: 'POST',
                        data: {
                            referral_program_invite_id: referral_program_invite_id,
                            reason: $('#reason').val(),
                        },
                        success: function (res) {
                            if (res.error == false) {
                                Swal.fire(res.message,'', "success").then(function () {
                                    location.reload();
                                });
                            } else {
                                Swal.fire(res.message, '', "error");
                            }
                        }
                    });
                }

            }
        })
    },

    rejectCommission : function (referral_program_invite_id){
        jsonLang = JSON.parse(localStorage.getItem('tranlate'));
        Swal.fire({
            title: jsonLang['Từ chối hoa hồng'],
            buttonsStyling: false,

            confirmButtonText: jsonLang['Xác nhận'],
            confirmButtonClass: "btn btn-primary btn-hover-brand mt-0",
            reverseButtons: true,
            showCancelButton: true,
            cancelButtonText: jsonLang['Hủy'],
            cancelButtonClass: "btn btn-secondary btn-hover-brand mt-0",
            html: jsonLang['Bạn xác nhận từ chối hoa hồng của người giới thiệu này, thao tác này sẽ không hoàn lại được sau khi thực hiện']+
                '<textarea id="reason" class="swal2-input" placeholder="'+jsonLang['Vui lòng nhập lý do']+'"></textarea>',
            onOpen: function() {
                $('#reason').focus();
            }
        }).then(function (result) {
            if (result.value) {
                if($('#reason').val() == ''){
                    Swal.fire(jsonLang["Lỗi!"],'Vui lòng nhập lý do' , "error");
                } else {
                    $.ajax({
                        url: laroute.route('referral.referral-program-invite.rejectCommission'),
                        method: 'POST',
                        data: {
                            referral_program_commission_id: referral_program_invite_id,
                            reason: $('#reason').val(),
                        },
                        success: function (res) {
                            if (res.error == false) {
                                Swal.fire(res.message,'', "success").then(function () {
                                    location.reload();
                                });
                            } else {
                                Swal.fire(res.message, '', "error");
                            }
                        }
                    });
                }

            }
        })
    },

    showRejectCommission : function (referral_program_invite_id){
        $.ajax({
            url: laroute.route('referral.referral-program-invite.showRejectCommission'),
            method: 'POST',
            data: {
                referral_program_commission_id: referral_program_invite_id,
            },
            success: function (res) {
                if (res.error == false) {
                    Swal.fire({
                        title: res.title,
                        text: res.reason,
                        icon: 'warning',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Hủy'
                    });
                } else {
                    Swal.fire(res.message, '', "error");
                }
            }
        });
    },

    showReject : function (referral_program_invite_id){
        $.ajax({
            url: laroute.route('referral.referral-program-invite.showReject'),
            method: 'POST',
            data: {
                referral_program_invite_id: referral_program_invite_id,
            },
            success: function (res) {
                if (res.error == false) {
                    Swal.fire({
                        title: res.title,
                        text: res.reason,
                        icon: 'warning',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Hủy'
                    });
                } else {
                    Swal.fire(res.message, '', "error");
                }
            }
        });
    }
}
