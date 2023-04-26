var stt = 0;

var listLead = {
    jsonTranslate: null,
    is_busy: false,
    page: 1,
    stopped: false,
    jsonLang: JSON.parse(localStorage.getItem("tranlate")),
    processFunctioneEditCustomerLead: function (data) {
        window.close()
        window.postMessage({
            'func': 'editSuccessCustomerLead',
            'message': data
        }, "*");
    },
    _init: function () {
        $(document).ready(function () {
            $(".select").select2();


            $("input[name='search']").change(function () {
                $("#search_filter").val($(this).val());
            });

            $("select[name='tag_id']").change(function () {
                $("#tag_id_filter").val($(this).val());
            });
            $("select[name='is_convert']").change(function () {
                $("#is_convert_filter").val($(this).val());
            });

            $("select[name='customer_type']").change(function () {
                $("#customer_type_filter").val($(this).val());
            });

            $("select[name='assign']").change(function () {
                $("#assign_filter").val($(this).val());
            });

            $("select[name='customer_source']").change(function () {
                $("#customer_source_filter").val($(this).val());
            });

            $("select[name='sale_id']").change(function () {
                $("#sale_id_filter").val($(this).val());
            });

            $("select[name='pipeline_code']").change(function () {
                $.ajax({
                    url: laroute.route("customer-lead.load-option-journey"),
                    dataType: "JSON",
                    data: {
                        pipeline_code: $("select[name='pipeline_code']").val(),
                    },
                    method: "POST",
                    success: function (res) {
                        $(".journey").empty();

                        $(".journey").append(
                            '<option value="">' +
                            listLead.jsonLang["Chọn hành trình"] +
                            "</option>"
                        );
                        $.map(res.optionJourney, function (a) {
                            $(".journey").append(
                                '<option value="' +
                                a.journey_code +
                                '">' +
                                a.journey_name +
                                "</option>"
                            );
                        });
                    },
                });

                $("#pipeline_code_filter").val($(this).val());
            });

            $("select[name='journey_code']").change(function () {
                $("#journey_code_filter").val($(this).val());
            });

            var arrRange = {};
            arrRange[listLead.jsonLang["Hôm nay"]] = [moment(), moment()];
            arrRange[listLead.jsonLang["Hôm qua"]] = [
                moment().subtract(1, "days"),
                moment().subtract(1, "days"),
            ];
            arrRange[listLead.jsonLang["7 ngày trước"]] = [
                moment().subtract(6, "days"),
                moment(),
            ];
            arrRange[listLead.jsonLang["30 ngày trước"]] = [
                moment().subtract(29, "days"),
                moment(),
            ];
            arrRange[listLead.jsonLang["Trong tháng"]] = [
                moment().startOf("month"),
                moment().endOf("month"),
            ];
            arrRange[listLead.jsonLang["Tháng trước"]] = [
                moment().subtract(1, "month").startOf("month"),
                moment().subtract(1, "month").endOf("month"),
            ];

            $("#created_at")
                .daterangepicker(
                    {
                        autoUpdateInput: true,
                        autoApply: true,
                        // buttonClasses: "m-btn btn",
                        // applyClass: "btn-primary",
                        // cancelClass: "btn-danger",

                        // maxDate: moment().endOf("day"),
                        // startDate: moment().startOf("day"),
                        // endDate: moment().add(1, 'days'),
                        locale: {
                            cancelLabel: "Clear",
                            format: "DD/MM/YYYY",
                            // "applyLabel": "Đồng ý",
                            // "cancelLabel": "Thoát",
                            customRangeLabel: listLead.jsonLang["Tùy chọn ngày"],
                            daysOfWeek: [
                                listLead.jsonLang["CN"],
                                listLead.jsonLang["T2"],
                                listLead.jsonLang["T3"],
                                listLead.jsonLang["T4"],
                                listLead.jsonLang["T5"],
                                listLead.jsonLang["T6"],
                                listLead.jsonLang["T7"],
                            ],
                            monthNames: [
                                listLead.jsonLang["Tháng 1 năm"],
                                listLead.jsonLang["Tháng 2 năm"],
                                listLead.jsonLang["Tháng 3 năm"],
                                listLead.jsonLang["Tháng 4 năm"],
                                listLead.jsonLang["Tháng 5 năm"],
                                listLead.jsonLang["Tháng 6 năm"],
                                listLead.jsonLang["Tháng 7 năm"],
                                listLead.jsonLang["Tháng 8 năm"],
                                listLead.jsonLang["Tháng 9 năm"],
                                listLead.jsonLang["Tháng 10 năm"],
                                listLead.jsonLang["Tháng 11 năm"],
                                listLead.jsonLang["Tháng 12 năm"],
                            ],
                            firstDay: 1,
                        },
                        ranges: arrRange,
                    },
                    function (start, end, label) {
                        $("#created_at_filter").val(
                            start.format("DD/MM/YYYY") + " - " + end.format("DD/MM/YYYY")
                        );
                    }
                )
                .change(function () {
                    $("#created_at_filter").val($(this).val());
                });

            $("#allocation_date")
                .daterangepicker(
                    {
                        autoUpdateInput: true,
                        autoApply: true,
                        // buttonClasses: "m-btn btn",
                        // applyClass: "btn-primary",
                        // cancelClass: "btn-danger",

                        // maxDate: moment().endOf("day"),
                        // startDate: moment().startOf("day"),
                        // endDate: moment().add(1, 'days'),
                        locale: {
                            cancelLabel: "Clear",
                            format: "DD/MM/YYYY",
                            // "applyLabel": "Đồng ý",
                            // "cancelLabel": "Thoát",
                            customRangeLabel: listLead.jsonLang["Tùy chọn ngày"],
                            daysOfWeek: [
                                listLead.jsonLang["CN"],
                                listLead.jsonLang["T2"],
                                listLead.jsonLang["T3"],
                                listLead.jsonLang["T4"],
                                listLead.jsonLang["T5"],
                                listLead.jsonLang["T6"],
                                listLead.jsonLang["T7"],
                            ],
                            monthNames: [
                                listLead.jsonLang["Tháng 1 năm"],
                                listLead.jsonLang["Tháng 2 năm"],
                                listLead.jsonLang["Tháng 3 năm"],
                                listLead.jsonLang["Tháng 4 năm"],
                                listLead.jsonLang["Tháng 5 năm"],
                                listLead.jsonLang["Tháng 6 năm"],
                                listLead.jsonLang["Tháng 7 năm"],
                                listLead.jsonLang["Tháng 8 năm"],
                                listLead.jsonLang["Tháng 9 năm"],
                                listLead.jsonLang["Tháng 10 năm"],
                                listLead.jsonLang["Tháng 11 năm"],
                                listLead.jsonLang["Tháng 12 năm"],
                            ],
                            firstDay: 1,
                        },
                        ranges: arrRange,
                    },
                    function (start, end, label) {
                        $("#allocation_date_filter").val(
                            start.format("DD/MM/YYYY") + " - " + end.format("DD/MM/YYYY")
                        );
                    }
                )
                .change(function () {
                    $("#allocation_date_filter").val($(this).val());
                });

            $("#created_at, #allocation_date").val("");

            $("#autotable").PioTable({
                baseUrl: laroute.route("customer-lead.list"),
            });
            $(".btn-search").submit();
            // listLead.getList();
        });
    },
    remove: function (id, load) {
        swal({
            title: listLead.jsonLang["Thông báo"],
            text: listLead.jsonLang["Bạn có muốn xóa không?"],
            type: "warning",
            showCancelButton: true,
            confirmButtonText: listLead.jsonLang["Xóa"],
            cancelButtonText: listLead.jsonLang["Hủy"],
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: laroute.route("customer-lead.destroy"),
                    method: "POST",
                    dataType: "JSON",
                    data: {
                        customer_lead_id: id,
                    },
                    success: function (res) {
                        if (res.error == false) {
                            if (load == true) {
                                swal.fire(res.message, "", "success");

                                // $('#kanban').remove();
                                // $('.parent_kanban').append('<div id="kanban"></div>');
                                // kanBanView.loadKanban();

                                let curElement = $(`#kanban_${id}`)
                                    .parent("div")
                                    .children("div").length;
                                $(`#kanban_${id}`)
                                    .parent("div")
                                    .parent("div")
                                    .find(".jqx-kanban-column-header-status")
                                    .html(` (${curElement - 1})`);
                                $(`#kanban_${id}`).remove();
                            } else {
                                swal.fire(res.message, "", "success");
                                $("#autotable").PioTable("refresh");
                            }
                        } else {
                            swal.fire(res.message, "", "error");
                        }
                    },
                });
            }
        });
    },
    popupCustomerCare: function (id) {
        $.ajax({
            url: laroute.route("customer-lead.popup-customer-care"),
            method: "POST",
            dataType: "JSON",
            data: {
                customer_lead_id: id,
            },
            success: function (res) {
                $("#my-modal").html(res.html);
                $("#modal-customer-care").modal("show");
                // $('.block-hide-work').hide();
                $(".select2-active").select2();
                Work.changeBooking();
                $(".date-timepicker").datetimepicker({
                    todayHighlight: !0,
                    autoclose: !0,
                    pickerPosition: "bottom-left",
                    format: "dd/mm/yyyy hh:ii",
                    // format: "dd/mm/yyyy",
                    startDate: new Date(),
                    // locale: 'vi'
                });

                $(".time-input").timepicker({
                    todayHighlight: !0,
                    autoclose: !0,
                    pickerPosition: "bottom-left",
                    // format: "dd/mm/yyyy hh:ii",
                    format: "HH:ii",
                    defaultTime: "",
                    showMeridian: false,
                    minuteStep: 5,
                    snapToStep: !0,
                    // startDate : new Date()
                    // locale: 'vi'
                });

                $(".daterange-input").datepicker({
                    todayHighlight: !0,
                    autoclose: !0,
                    pickerPosition: "bottom-left",
                    // format: "dd/mm/yyyy hh:ii",
                    format: "dd/mm/yyyy",
                    // startDate : new Date()
                    // locale: 'vi'
                });

                AutoNumeric.multiple(".input-mask,.input-mask-remind", {
                    currencySymbol: "",
                    decimalCharacter: ".",
                    digitGroupSeparator: ",",
                    decimalPlaces: 0,
                    minimumValue: 0,
                });

                $("#care_type").select2({
                    placeholder: listLead.jsonLang["Chọn loại chăm sóc"],
                });

                $(".summernote").summernote({
                    placeholder: "",
                    tabsize: 2,
                    height: 200,
                    toolbar: [
                        ["style", ["style"]],
                        ["font", ["bold", "underline", "clear"]],
                        ["fontname", ["fontname", "fontsize"]],
                        ["color", ["color"]],
                        ["para", ["ul", "ol", "paragraph"]],
                        ["table", ["table"]],
                        ["insert", ["link", "picture"]],
                    ],
                    callbacks: {
                        onImageUpload: function (files) {
                            for (let i = 0; i < files.length; i++) {
                                uploadImgCk(files[i]);
                            }
                        },
                    },
                });
            },
        });
    },
    getList: function (params) {
        $.ajax({
            url: laroute.route("customer-lead.list"),
            method: "POST",
            global: false,
            data: {
                customer_lead_code: filter,
                page: page,
            },
            success: function (res) {
                $(".table-content").html(res);
            },
        });
    },
    popupCustomerCareEdit: function (id, manage_work_id) {
        $.ajax({
            url: laroute.route("customer-lead.popup-customer-care"),
            method: "POST",
            dataType: "JSON",
            data: {
                customer_lead_id: id,
                manage_work_id: manage_work_id,
            },
            success: function (res) {
                $("#modal-detail").css("opacity", 0);
                $("#modal-customer-care").css("opacity", 0);
                $("#popup-work-edit").html(res.html);
                $("#modal-customer-care-edit").modal("show");
                // if (res.is_booking == 0){
                //     $('.block-hide-work').hide();
                // }
                $(".select2-active").select2();

                $(".date-timepicker").datetimepicker({
                    todayHighlight: !0,
                    autoclose: !0,
                    pickerPosition: "bottom-left",
                    format: "dd/mm/yyyy hh:ii",
                    // format: "dd/mm/yyyy",
                    startDate: new Date(),
                    // locale: 'vi'
                });

                $(".time-input").timepicker({
                    todayHighlight: !0,
                    autoclose: !0,
                    pickerPosition: "bottom-left",
                    // format: "dd/mm/yyyy hh:ii",
                    format: "HH:ii",
                    defaultTime: "",
                    showMeridian: false,
                    minuteStep: 5,
                    snapToStep: !0,
                    // startDate : new Date()
                    // locale: 'vi'
                });

                $(".daterange-input").datepicker({
                    todayHighlight: !0,
                    autoclose: !0,
                    pickerPosition: "bottom-left",
                    // format: "dd/mm/yyyy hh:ii",
                    format: "dd/mm/yyyy",
                    // startDate : new Date()
                    // locale: 'vi'
                });

                AutoNumeric.multiple(".input-mask,.input-mask-remind", {
                    currencySymbol: "",
                    decimalCharacter: ".",
                    digitGroupSeparator: ",",
                    decimalPlaces: 0,
                    minimumValue: 0,
                });

                $("#care_type").select2({
                    placeholder: listLead.jsonLang["Chọn loại chăm sóc"],
                });

                $(".summernote").summernote({
                    placeholder: "",
                    tabsize: 2,
                    height: 200,
                    toolbar: [
                        ["style", ["style"]],
                        ["font", ["bold", "underline", "clear"]],
                        ["fontname", ["fontname", "fontsize"]],
                        ["color", ["color"]],
                        ["para", ["ul", "ol", "paragraph"]],
                        ["table", ["table"]],
                        ["insert", ["link", "picture"]],
                    ],
                    callbacks: {
                        onImageUpload: function (files) {
                            for (let i = 0; i < files.length; i++) {
                                uploadImgCk(files[i]);
                            }
                        },
                    },
                });
            },
        });
    },
    submitCustomerCare: function (id) {
        var form = $("#form-care");
        var is_booking = 0;
        if ($("#is_booking").is(":checked")) {
            is_booking = 1;
        }

        $.ajax({
            url: laroute.route("customer-lead.customer-care"),
            method: "POST",
            dataType: "JSON",
            data: $("#form-care").formSerialize() + "&is_booking=" + is_booking,
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        if (window.location.href.includes("kan-ban-view")) {
                            $("#modal-customer-care").modal("hide");
                            $("#modal-customer-care-edit").modal("hide");
                        } else {
                            location.reload();
                        }
                    });
                } else {
                    swal(res.message, "", "error");
                }
            },
            error: function (res) {
                var mess_error = "";
                $.map(res.responseJSON.errors, function (a) {
                    mess_error = mess_error.concat(a + "<br/>");
                });
                swal(listLead.jsonLang["Thêm mới thất bại"], mess_error, "error");
            },
        });
    },
    submitCustomerCareEdit: function (id) {
        var form = $("#form-care-edit");
        var is_booking = 0;
        if ($("#is_booking").is(":checked")) {
            is_booking = 1;
        }

        $.ajax({
            url: laroute.route("customer-lead.customer-care"),
            method: "POST",
            dataType: "JSON",
            data: $("#form-care-edit").formSerialize() + "&is_booking=" + is_booking,
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        Work.search();
                        $("#modal-detail").css("opacity", 1);
                        $("#modal-customer-care").css("opacity", 1);
                        $("#modal-customer-care-edit").modal("hide");
                    });
                } else {
                    swal(res.message, "", "error");
                }
            },
            error: function (res) {
                var mess_error = "";
                $.map(res.responseJSON.errors, function (a) {
                    mess_error = mess_error.concat(a + "<br/>");
                });
                swal(listLead.jsonLang["Thêm mới thất bại"], mess_error, "error");
            },
        });
    },
    closeModalCare: function () {
        $("#modal-customer-care").modal("hide");
        $(".modal-backdrop").remove();
    },
    closeModalCareEdit: function () {
        $("#modal-detail").css("opacity", 1);
        $("#modal-customer-care").css("opacity", 1);
        $("#modal-customer-care-edit").modal("hide");
        $(".modal-backdrop").remove();
    },
    detail: function (id) {
        $.ajax({
            url: laroute.route("customer-lead.show"),
            method: "POST",
            dataType: "JSON",
            data: {
                customer_lead_id: id,
                view: "detail",
            },
            success: function (res) {
                $("#my-modal").html(res.html);
                $("#modal-detail").modal("show");

                $("#tag_id_detail").select2({
                    placeholder: listLead.jsonLang["Chọn tag"],
                });

                $("#pipeline_code").select2({
                    placeholder: listLead.jsonLang["Chọn pipeline"],
                });

                $("#customer_type").select2({
                    placeholder: listLead.jsonLang["Chọn loại khách hàng"],
                });

                $("#journey_code").select2({
                    placeholder: listLead.jsonLang["Chọn hành trình"],
                });

                $("#business_clue").select2({
                    placeholder: listLead.jsonLang["Chọn đầu mối doanh nghiệp"],
                });

                var arrRange = {};
                arrRange[listLead.jsonLang["Hôm nay"]] = [moment(), moment()];
                arrRange[listLead.jsonLang["7 ngày trước"]] = [
                    moment().subtract(6, "days"),
                    moment(),
                ];
                arrRange[listLead.jsonLang["Trong tháng"]] = [
                    moment().startOf("month"),
                    moment().endOf("month"),
                ];
                arrRange[listLead.jsonLang["Tháng trước"]] = [
                    moment().subtract(1, "month").startOf("month"),
                    moment().subtract(1, "month").endOf("month"),
                ];

                $(".searchDateForm")
                    .daterangepicker({
                        autoUpdateInput: false,
                        autoApply: true,
                        // buttonClasses: "m-btn btn",
                        // applyClass: "btn-primary",
                        // cancelClass: "btn-danger",
                        // startDate: moment().subtract(6, "days"),
                        startDate: moment().startOf("month"),
                        endDate: moment().endOf("month"),
                        locale: {
                            cancelLabel: "Clear",
                            format: "DD/MM/YYYY",
                            applyLabel: listLead.jsonLang["Đồng ý"],
                            cancelLabel: listLead.jsonLang["Thoát"],
                            customRangeLabel: listLead.jsonLang["Tùy chọn ngày"],
                            daysOfWeek: [
                                listLead.jsonLang["CN"],
                                listLead.jsonLang["T2"],
                                listLead.jsonLang["T3"],
                                listLead.jsonLang["T4"],
                                listLead.jsonLang["T5"],
                                listLead.jsonLang["T6"],
                                listLead.jsonLang["T7"],
                            ],
                            monthNames: [
                                listLead.jsonLang["Tháng 1 năm"],
                                listLead.jsonLang["Tháng 2 năm"],
                                listLead.jsonLang["Tháng 3 năm"],
                                listLead.jsonLang["Tháng 4 năm"],
                                listLead.jsonLang["Tháng 5 năm"],
                                listLead.jsonLang["Tháng 6 năm"],
                                listLead.jsonLang["Tháng 7 năm"],
                                listLead.jsonLang["Tháng 8 năm"],
                                listLead.jsonLang["Tháng 9 năm"],
                                listLead.jsonLang["Tháng 10 năm"],
                                listLead.jsonLang["Tháng 11 năm"],
                                listLead.jsonLang["Tháng 12 năm"],
                            ],
                            firstDay: 1,
                        },
                        ranges: arrRange,
                    })
                    .on("apply.daterangepicker", function (ev, picker) {
                        var start = picker.startDate.format("DD/MM/YYYY");
                        var end = picker.endDate.format("DD/MM/YYYY");
                        $(this).val(start + " - " + end);
                    });
                $(".selectForm").select2();
                $(document).on(
                    "click",
                    "#autotable-care a.m-datatable__pager-link",
                    function (event) {
                        event.preventDefault();
                        var page = $(this).attr("data-page");
                        console.log(page);
                        if (page) {
                            var code = $("#customer_lead_code").val();
                            listLead.getDataCare(page, code);
                        }
                    }
                );
                $(document).on(
                    "click",
                    "#autotable-deal a.m-datatable__pager-link",
                    function (event) {
                        event.preventDefault();
                        var page = $(this).attr("data-page");
                        if (page) {
                            var code = $("#customer_lead_code").val();
                            listLead.getDataDeal(page, code);
                        }
                    }
                );
                $(".phone").ForceNumericOnly();
                if (
                    typeof $("#view_mode") != "undefined" &&
                    $("#view_mode").val() == "chathub_popup"
                ) {
                    $("#modal-detail").find(".btn-success").remove();
                }
            },
        });
    },
    getDataCare: function (page, filter = null) {
        $.ajax({
            url: laroute.route("customer-lead.show-list-care"),
            method: "POST",
            data: {
                customer_lead_code: filter,
                page: page,
            },
            success: function (res) {
                $("#div-care-list").html(res);
            },
        });
    },
    getDataDeal: function (page, filter = null) {
        $.ajax({
            url: laroute.route("customer-lead.show-list-deal"),
            method: "POST",
            data: {
                customer_lead_code: filter,
                page: page,
            },
            success: function (res) {
                $("#div-deal-list").html(res);
            },
        });
    },
    popupListStaff: function (customer_lead_id) {
        $.ajax({
            url: laroute.route("customer-lead.popup-list-staff"),
            method: "POST",
            dataType: "JSON",
            data: {
                customer_lead_id: customer_lead_id,
            },
            success: function (res) {
                $("#my-modal").html(res.html);
                $("#modal-list-staff").modal("show");
                $("#staff").select2({
                    placeholder: listLead.jsonLang["Chọn nhân viên"],
                });
            },
        });
    },
    submitAssignStaff: function () {
        var form = $("#form-assign");
        form.validate({
            rules: {
                staff: {
                    required: true,
                },
            },
            messages: {
                staff: {
                    required: listLead.jsonLang["Hãy chọn nhân viên đuọc phân công"],
                },
            },
        });

        if (!form.valid()) {
            return false;
        }

        let staff = $("#staff option:selected").val();

        $.ajax({
            url: laroute.route("customer-lead.save-assign-staff"),
            method: "POST",
            dataType: "JSON",
            data: {
                staff_id: staff,
                customer_lead_id: $("#customer_lead_id").val(),
            },
            success: function (res) {
                if (res.error == false) {
                    $("#modal-list-staff").modal("hide");
                    swal(res.message, "", "success");
                    $("#autotable").PioTable("refresh");
                } else {
                    swal(res.message, "", "error");
                }
            },
            error: function (res) {
                var mess_error = "";
                $.map(res.responseJSON.errors, function (a) {
                    mess_error = mess_error.concat(a + "<br/>");
                });
                swal(listLead.jsonLang["Thêm mới thất bại"], mess_error, "error");
            },
        });
    },
    // Thu hổi 1 lead
    revokeOne: function (id) {
        swal({
            title: listLead.jsonLang["Thông báo"],
            text: listLead.jsonLang["Bạn có muốn thu hồi không?"],
            type: "warning",
            showCancelButton: true,
            confirmButtonText: listLead.jsonLang["Thu hồi"],
            cancelButtonText: listLead.jsonLang["Hủy"],
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: laroute.route("customer-lead.revoke-one"),
                    method: "POST",
                    dataType: "JSON",
                    data: {
                        customer_lead_id: id,
                    },
                    success: function (res) {
                        if (res.error == false) {
                            swal.fire(res.message, "", "success");
                            $("#autotable").PioTable("refresh");
                        } else {
                            swal.fire(res.message, "", "error");
                        }
                    },
                });
            }
        });
    },
    // View thu hồi toàn bộ lead
    revoke: function () {
        $.ajax({
            url: laroute.route("customer-lead.revoke"),
            method: "POST",
            dataType: "JSON",
            success: function (res) {
                $("#my-modal").html(res.html);
                $("#modal-list-staff").modal("show");
                $("#staff").select2({
                    placeholder: listLead.jsonLang["Chọn nhân viên"],
                });
            },
        });
    },
    // Thu hồi toàn bộ lead theo sale id
    submitRevoke: function () {
        var form = $("#form-assign");
        form.validate({
            rules: {
                staff: {
                    required: true,
                },
            },
            messages: {
                staff: {
                    required: listLead.jsonLang["Hãy chọn nhân viên bị thu hồi"],
                },
            },
        });

        if (!form.valid()) {
            return false;
        }

        let staff = $("#staff option:selected").val();

        $.ajax({
            url: laroute.route("customer-lead.submit-revoke"),
            method: "POST",
            dataType: "JSON",
            data: {
                staff_id: staff,
            },
            success: function (res) {
                if (res.error == false) {
                    $("#modal-list-staff").modal("hide");
                    swal(res.message, "", "success");
                    $("#autotable").PioTable("refresh");
                } else {
                    swal(res.message, "", "error");
                }
            },
        });
    },
    modalCall: function (customerLeadId) {
        $.ajax({
            url: laroute.route("customer-lead.modal-call"),
            method: "POST",
            dataType: "JSON",
            data: {
                customer_lead_id: customerLeadId,
            },
            success: function (res) {
                $("#my-modal").html(res.html);
                $("#modal-call").modal("show");
            },
        });
    },
    call: function (customerLeadId, phone) {
        $.ajax({
            url: laroute.route("customer-lead.call"),
            method: "POST",
            dataType: "JSON",
            data: {
                customer_lead_id: customerLeadId,
                phone: phone,
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        if (result.dismiss == "esc" || result.dismiss == "backdrop") {
                            $("#modal-call").modal("hide");

                            $("#my-modal").html(res.html);
                            $("#modal-customer-care").modal("show");

                            $("#care_type").select2({
                                placeholder: listLead.jsonLang["Chọn loại chăm sóc"],
                            });
                        }
                        if (result.value == true) {
                            $("#modal-call").modal("hide");

                            $("#my-modal").html(res.html);
                            $("#modal-customer-care").modal("show");

                            $("#care_type").select2({
                                placeholder: listLead.jsonLang["Chọn loại chăm sóc"],
                            });
                        }
                    });
                } else {
                    swal(res.message, "", "error");
                }
            },
        });
    },
};

var customerLeadCreate = (create = {
    popupCreate: function (load) {
        $.ajax({
            url: laroute.route("customer-lead.create"),
            method: "POST",
            dataType: "JSON",
            data: {
                load: load,
            },
            success: function (res) {
                $("#my-modal").html(res.html);
                $("#modal-create").modal("show");

                $("#tag_id")
                    .select2({
                        placeholder: listLead.jsonLang["Chọn tag"],
                        tags: true,
                        tokenSeparators: [",", " "],
                        createTag: function (newTag) {
                            return {
                                id: "new:" + newTag.term,
                                text: newTag.term,
                                isNew: true,
                            };
                        },
                    })
                    .on("select2:select", function (e) {
                        if (e.params.data.isNew) {
                            // store the new tag:
                            $.ajax({
                                type: "POST",
                                url: laroute.route("customer-lead.tag.store"),
                                data: {
                                    tag_name: e.params.data.text,
                                },
                                success: function (res) {
                                    // append the new option element end replace id
                                    $("#tag_id")
                                        .find('[value="' + e.params.data.id + '"]')
                                        .replaceWith(
                                            '<option selected value="' +
                                            res.tag_id +
                                            '">' +
                                            e.params.data.text +
                                            "</option>"
                                        );
                                },
                            });
                        }
                    });

                $("#pipeline_code").select2({
                    placeholder: listLead.jsonLang["Chọn pipeline"],
                });

                $("#journey_code").select2({
                    placeholder: listLead.jsonLang["Chọn hành trình"],
                });

                $("#customer_type_create").select2({
                    placeholder: listLead.jsonLang["Chọn loại khách hàng"],
                });

                $("#customer_source").select2({
                    placeholder: listLead.jsonLang["Chọn nguồn khách hàng"],
                });

                $("#business_clue").select2({
                    placeholder: listLead.jsonLang["Chọn đầu mối doanh nghiệp"],
                });

                // $('.phone').ForceNumericOnly();

                $("#pipeline_code").change(function () {
                    $.ajax({
                        url: laroute.route("customer-lead.load-option-journey"),
                        dataType: "JSON",
                        data: {
                            pipeline_code: $("#pipeline_code").val(),
                        },
                        method: "POST",
                        success: function (res) {
                            $(".journey").empty();
                            $.map(res.optionJourney, function (a) {
                                $(".journey").append(
                                    '<option value="' +
                                    a.journey_code +
                                    '">' +
                                    a.journey_name +
                                    "</option>"
                                );
                            });
                        },
                    });
                });

                $("#sale_id").select2({
                    placeholder: listLead.jsonLang["Chọn nhân viên được phân bổ"],
                });

                $('#branch_code').select2({
                    placeholder: listLead.jsonLang["Chi nhánh trung tâm"],
                });

                $('#business_id').select2({
                    placeholder: listLead.jsonLang["Lĩnh vực kinh doanh"],
                });

                $("#province_id").select2({
                    placeholder: listLead.jsonLang["Chọn tỉnh/thành"],
                });

                $("#district_id").select2({
                    placeholder: listLead.jsonLang["Chọn quận/huyện"],
                });
            },
        });
    },

    save: function (load, view = false) {
        var form = $("#form-register");

        form.validate({
            rules: {
                full_name: {
                    required: true,
                    maxlength: 250,
                },
                phone: {
                    // required: true,
                    integer: true,
                    maxlength: 10,
                },
                address: {
                    maxlength: 250,
                },
                pipeline_code: {
                    required: true,
                },
                journey_code: {
                    required: true,
                },
                customer_type: {
                    required: true,
                },
                tax_code: {
                    // required: true,
                    maxlength: 50,
                },
                representative: {
                    // required: true,
                    maxlength: 250,
                },
                customer_source: {
                    required: true,
                },
                // hotline: {
                //   required: true,
                // },
            },
            messages: {
                full_name: {
                    required: listLead.jsonLang["Hãy nhập họ và tên"],
                    maxlength: listLead.jsonLang["Họ và tên tối đa 250 kí tự"],
                },
                phone: {
                    // required: listLead.jsonLang['Hãy nhập số điện thoại'],
                    integer: listLead.jsonLang["Số điện thoại không hợp lệ"],
                    maxlength: listLead.jsonLang["Số điện thoại tối đa 10 kí tự"],
                },
                address: {
                    maxlength: listLead.jsonLang["Địa chỉ tối đa 250 kí tự"],
                },
                pipeline_code: {
                    required: listLead.jsonLang["Hãy chọn pipeline"],
                },
                journey_code: {
                    required: listLead.jsonLang["Hãy chọn hành trình khách hàng"],
                },
                customer_type: {
                    required: listLead.jsonLang["Hãy chọn loại khách hàng"],
                },
                tax_code: {
                    // required: listLead.jsonLang["Hãy nhập mã số thuế"],
                    maxlength: listLead.jsonLang["Mã số thuế tối đa 50 kí tự"],
                },
                representative: {
                    // required: listLead.jsonLang["Hãy nhập người đại diện"],
                    maxlength: listLead.jsonLang["Người đại diện tối đa 250 kí tự"],
                },
                customer_source: {
                    required: listLead.jsonLang["Hãy chọn nguồn khách hàng"],
                },
                // hotline: {
                //   required: listLead.jsonLang["Hãy nhập hotline"],
                // },
            },
        });

        if (!form.valid()) {
            return false;
        }

        var continute = true;

        var arrPhoneAttack = [];
        var arrEmailAttack = [];
        var arrFanpageAttack = [];
        var arrContact = [];

        $.each($(".phone_append").find(".div_phone_attach"), function () {
            var phone = $(this).find($(".phone_attach")).val();
            var number = $(this).find($(".number_phone")).val();

            if (phone == "") {
                $(".error_phone_attach_" + number + "").text(
                    listLead.jsonLang["Hãy nhập số điện thoại"]
                );
                continute = false;
            } else {
                $(".error_phone_attach_" + number + "").text("");
            }

            arrPhoneAttack.push({
                phone: phone,
            });
        });

        $.each($(".email_append").find(".div_email_attach"), function () {
            var email = $(this).find($(".email_attach")).val();
            var number = $(this).find($(".number_email")).val();

            if (email == "") {
                $(".error_email_attach_" + number + "").text(
                    listLead.jsonLang["Hãy nhập email"]
                );
                continute = false;
            } else {
                $(".error_email_attach_" + number + "").text("");
            }

            arrEmailAttack.push({
                email: email,
            });
        });

        $.each($(".fanpage_append").find(".div_fanpage_attach"), function () {
            var fanpage = $(this).find($(".fanpage_attach")).val();
            var number = $(this).find($(".number_fanpage")).val();

            if (fanpage == "") {
                $(".error_fanpage_attach_" + number + "").text(
                    listLead.jsonLang["Hãy nhập fanpage"]
                );
                continute = false;
            } else {
                $(".error_fanpage_attach_" + number + "").text("");
            }

            arrFanpageAttack.push({
                fanpage: fanpage,
            });
        });

        $.each($("#table-contact").find(".tr_contact"), function () {
            var fullName = $(this).find($(".full_name_contact")).val();
            var phoneContact = $(this).find($(".phone_contact")).val();
            var emailContact = $(this).find($(".email_contact")).val();
            var addressContact = $(this).find($(".address_contact")).val();
            let staffTitleContact = $(this).find($(".staff-title-contact")).val();
            var number = $(this).find($(".number_contact")).val();

            if (fullName == "") {
                $(".error_full_name_contact_" + number + "").text(
                    listLead.jsonLang["Hãy nhập họ và tên"]
                );
                continute = false;
            } else {
                $(".error_full_name_contact_" + number + "").text("");
            }

            if (phoneContact == "") {
                $(".error_phone_contact_" + number + "").text(
                    listLead.jsonLang["Hãy nhập số điện thoại"]
                );
                continute = false;
            } else {
                $(".error_phone_contact_" + number + "").text("");
            }

            if (addressContact == "") {
                $(".error_address_contact_" + number + "").text(
                    listLead.jsonLang["Hãy nhập địa chỉ"]
                );
                continute = false;
            } else {
                $(".error_address_contact_" + number + "").text("");
            }

            if (staffTitleContact == "") {
                $(".error_staff_title_contact_" + number + "").text(
                    listLead.jsonLang["Hãy chọn chức vụ"]
                );
                continute = false;
            } else {
                $(".error_staff_title_contact_" + number + "").text("");
            }

            if (emailContact == "") {
                $(".error_email_contact_" + number + "").text(
                    listLead.jsonLang["Hãy nhập email"]
                );
                continute = false;
            } else {
                $(".error_email_contact_" + number + "").text("");

                if (isValidEmailAddress(emailContact) == false) {
                    $(".error_email_contact_" + number + "").text(
                        listLead.jsonLang["Email không hợp lệ"]
                    );
                    continute = false;
                } else {
                    $(".error_email_contact_" + number + "").text("");
                }
            }

            arrContact.push({
                full_name: fullName,
                phone: phoneContact,
                email: emailContact,
                staff_title_id: staffTitleContact,
            });
        });

        if (continute == true) {
            $.ajax({
                url: laroute.route("customer-lead.store"),
                method: "POST",
                dataType: "JSON",
                data: {
                    full_name: $("#full_name").val(),
                    phone: $("#phone").val(),
                    gender: $('input[name="gender"]:checked').val(),
                    address: $("#address").val(),
                    avatar: $("#avatar").val(),
                    email: $("#email").val(),
                    tag_id: $("#tag_id").val(),
                    pipeline_code: $("#pipeline_code").val(),
                    journey_code: $("#journey_code").val(),
                    customer_type: $("#customer_type_create").val(),
                    hotline: $("#hotline").val(),
                    fanpage: $("#fanpage").val(),
                    zalo: $("#zalo").val(),
                    arrPhoneAttack: arrPhoneAttack,
                    arrEmailAttack: arrEmailAttack,
                    arrFanpageAttack: arrFanpageAttack,
                    arrContact: arrContact,
                    tax_code: $("#tax_code").val(),
                    representative: $("#representative").val(),
                    customer_source: $("#customer_source").val(),
                    business_clue: $("#business_clue").val(),
                    sale_id: $("#sale_id").val(),
                    province_id: $("#province_id").val(),
                    district_id: $("#district_id").val(),
                    custom_1: $("#custom_1").val(),
                    custom_2: $("#custom_2").val(),
                    custom_3: $("#custom_3").val(),
                    custom_4: $("#custom_4").val(),
                    custom_5: $("#custom_5").val(),
                    custom_6: $("#custom_6").val(),
                    custom_7: $("#custom_7").val(),
                    custom_8: $("#custom_8").val(),
                    custom_9: $("#custom_9").val(),
                    custom_10: $("#custom_10").val(),

                    website: $("#website").val(),
                    business_id: $("#business_id").val(),
                    employ_qty: $("#employ_qty").val(),
                    birthday: $("#birthday").val(),
                    note: $("#note").val(),
                    branch_code: $("#branch_code").val(),
                },
                success: function (res) {
                    if (res.error == false) {
                        if (load == true) {
                            swal(res.message, "", "success").then(function (result) {
                                $("#modal-create").modal("hide");
                                $("#kanban").remove();
                                $(".parent_kanban").append('<div id="kanban"></div>');
                                kanBanView.loadKanban();
                            });
                            // $("#kanban").remove();
                            // $(".parent_kanban").append('<div id="kanban"></div>');
                            // kanBanView.loadKanban();
                            window.location.reload();
                        } else {

                            if (view) {
                                swal({
                                    title: res.message,
                                    text: 'Redirecting...',
                                    type: 'success',
                                    timer: 1500,
                                    showConfirmButton: false,
                                })
                                    .then(() => {
                                        window.location.href = '/customer-lead/customer-lead';
                                    });

                            } else {
                                swal(res.message, "", "success").then(function (result) {
                                    // if (result.dismiss == "esc" || result.dismiss == "backdrop") {
                                    //   $("#modal-create").modal("hide");
                                    // }
                                    // if (result.value == true) {
                                    //   $("#modal-create").modal("hide");
                                    // }
                                    $("#modal-create").modal("hide");
                                    $("#autotable").PioTable("refresh");
                                });

                            }

                        }
                    } else {
                        swal(res.message, "", "error");
                    }
                },
                error: function (res) {
                    var mess_error = "";
                    $.map(res.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + "<br/>");
                    });
                    swal(listLead.jsonLang["Thêm mới thất bại"], mess_error, "error");
                },
            });
        }
    },
});

var edit = {
    popupEdit: function (id, load, chatHubPopup = '') {
        $.ajax({
            url: laroute.route("customer-lead.edit"),
            method: "POST",
            dataType: "JSON",
            data: {
                customer_lead_id: id,
                load: load,
                view: "edit",
                chatHubPopup: chatHubPopup
            },
            success: function (res) {
                $("#my-modal").html(res.html);
                $("#modal-edit").modal("show");

                $("#tag_id")
                    .select2({
                        placeholder: listLead.jsonLang["Chọn tag"],
                        tags: true,
                        tokenSeparators: [",", " "],
                        createTag: function (newTag) {
                            return {
                                id: "new:" + newTag.term,
                                text: newTag.term,
                                isNew: true,
                            };
                        },
                    })
                    .on("select2:select", function (e) {
                        if (e.params.data.isNew) {
                            // store the new tag:
                            $.ajax({
                                type: "POST",
                                url: laroute.route("customer-lead.tag.store"),
                                data: {
                                    tag_name: e.params.data.text,
                                },
                                success: function (res) {
                                    // append the new option element end replace id
                                    $("#tag_id")
                                        .find('[value="' + e.params.data.id + '"]')
                                        .replaceWith(
                                            '<option selected value="' +
                                            res.tag_id +
                                            '">' +
                                            e.params.data.text +
                                            "</option>"
                                        );
                                },
                            });
                        }
                    });

                $("#pipeline_code").select2({
                    placeholder: listLead.jsonLang["Chọn pipeline"],
                });

                $("#customer_type").select2({
                    placeholder: listLead.jsonLang["Chọn loại khách hàng"],
                });

                $("#journey_code").select2({
                    placeholder: listLead.jsonLang["Chọn hành trình"],
                });

                $("#customer_source").select2({
                    placeholder: listLead.jsonLang["Chọn nguồn khách hàng"],
                });

                $("#business_clue").select2({
                    placeholder: listLead.jsonLang["Chọn đầu mối doanh nghiệp"],
                });

                $("#branch_code").select2({
                    placeholder: listLead.jsonLang["Chọn chi nhánh"],
                });

                // $('.phone').ForceNumericOnly();
                $("#province_id").select2({
                    placeholder: listLead.jsonLang["Chọn tỉnh/thành"],
                });

                $("#district_id").select2({
                    placeholder: listLead.jsonLang["Chọn quận/huyện"],
                });
            },
        });
    },
    save: function (id, load, view = false) {

        var form = $("#form-edit");
        form.validate({
            rules: {
                full_name: {
                    required: true,
                    maxlength: 250,
                },
                phone: {
                    // required: true,
                    integer: true,
                    maxlength: 10,
                },
                address: {
                    maxlength: 250,
                },
                pipeline_code: {
                    required: true,
                },
                journey_code: {
                    required: true,
                },
                customer_type: {
                    required: true,
                },
                tax_code: {
                    // required: true,
                    maxlength: 50,
                },
                representative: {
                    // required: true,
                    maxlength: 250,
                },
                // tax_code: {
                //   required: true,
                //   maxlength: 50,
                // },
                // representative: {
                //   required: true,
                //   maxlength: 250,
                // },
                customer_source: {
                    required: true,
                },
                // hotline: {
                //   required: true,
                // },
            },
            messages: {
                full_name: {
                    required: listLead.jsonLang["Hãy nhập họ và tên"],
                    maxlength: listLead.jsonLang["Họ và tên tối đa 250 kí tự"],
                },
                phone: {
                    // required: listLead.jsonLang['Hãy nhập số điện thoại'],
                    integer: listLead.jsonLang["Số điện thoại không hợp lệ"],
                    maxlength: listLead.jsonLang["Số điện thoại tối đa 10 kí tự"],
                },
                address: {
                    maxlength: listLead.jsonLang["Địa chỉ tối đa 250 kí tự"],
                },
                pipeline_code: {
                    required: listLead.jsonLang["Hãy chọn pipeline"],
                },
                journey_code: {
                    required: listLead.jsonLang["Hãy chọn hành trình khách hàng"],
                },
                customer_type: {
                    required: listLead.jsonLang["Hãy chọn loại khách hàng"],
                },
                tax_code: {
                    // required: listLead.jsonLang["Hãy nhập mã số thuế"],
                    maxlength: listLead.jsonLang["Mã số thuế tối đa 50 kí tự"],
                },
                representative: {
                    // required: listLead.jsonLang["Hãy nhập người đại diện"],
                    maxlength: listLead.jsonLang["Người đại diện tối đa 250 kí tự"],
                },
                customer_source: {
                    required: listLead.jsonLang["Hãy chọn nguồn khách hàng"],
                },
                // hotline: {
                //   required: listLead.jsonLang["Hãy nhập hotline"],
                // },
            },
        });


        if (!form.valid()) {

            return false;
        }

        var continute = true;

        var arrPhoneAttack = [];
        var arrEmailAttack = [];
        var arrFanpageAttack = [];
        var arrContact = [];

        $.each($(".phone_append").find(".div_phone_attach"), function () {
            var phone = $(this).find($(".phone_attach")).val();
            var number = $(this).find($(".number_phone")).val();

            if (phone == "") {
                $(".error_phone_attach_" + number + "").text(
                    listLead.jsonLang["Hãy nhập số điện thoại"]
                );
                continute = false;
            } else {
                $(".error_phone_attach_" + number + "").text("");
            }

            arrPhoneAttack.push({
                phone: phone,
            });
        });

        $.each($(".email_append").find(".div_email_attach"), function () {
            var email = $(this).find($(".email_attach")).val();
            var number = $(this).find($(".number_email")).val();

            if (email == "") {
                $(".error_email_attach_" + number + "").text(
                    listLead.jsonLang["Hãy nhập email"]
                );
                continute = false;
            } else {
                $(".error_email_attach_" + number + "").text("");
            }

            arrEmailAttack.push({
                email: email,
            });
        });

        $.each($(".fanpage_append").find(".div_fanpage_attach"), function () {
            var fanpage = $(this).find($(".fanpage_attach")).val();
            var number = $(this).find($(".number_fanpage")).val();

            if (fanpage == "") {
                $(".error_fanpage_attach_" + number + "").text(
                    listLead.jsonLang["Hãy nhập fanpage"]
                );
                continute = false;
            } else {
                $(".error_fanpage_attach_" + number + "").text("");
            }

            arrFanpageAttack.push({
                fanpage: fanpage,
            });
        });

        $.each($("#table-contact").find(".tr_contact"), function () {
            var fullName = $(this).find($(".full_name_contact")).val();
            var phoneContact = $(this).find($(".phone_contact")).val();
            var emailContact = $(this).find($(".email_contact")).val();
            let staffTitleContact = $(this).find($(".staff-title-contact")).val();
            var number = $(this).find($(".number_contact")).val();

            if (fullName == "") {
                $(".error_full_name_contact_" + number + "").text(
                    listLead.jsonLang["Hãy nhập họ và tên"]
                );
                continute = false;
            } else {
                $(".error_full_name_contact_" + number + "").text("");
            }

            if (phoneContact == "") {
                $(".error_phone_contact_" + number + "").text(
                    listLead.jsonLang["Hãy nhập số điện thoại"]
                );
                continute = false;
            } else {
                $(".error_phone_contact_" + number + "").text("");
            }

            if (emailContact == "") {
                $(".error_email_contact_" + number + "").text(
                    listLead.jsonLang["Hãy nhập email"]
                );
                continute = false;
            } else {
                $(".error_email_contact_" + number + "").text("");

                if (isValidEmailAddress(emailContact) == false) {
                    $(".error_email_contact_" + number + "").text(
                        listLead.jsonLang["Email không hợp lệ"]
                    );
                    continute = false;
                } else {
                    $(".error_email_contact_" + number + "").text("");
                }
            }

            arrContact.push({
                full_name: fullName,
                phone: phoneContact,
                email: emailContact,
                staff_title_id: staffTitleContact,
            });
        });

        if (continute == true) {
            $.ajax({
                url: laroute.route("customer-lead.update"),
                method: "POST",
                dataType: "JSON",
                data: {
                    full_name: $("#full_name").val(),
                    phone: $("#phone").val(),
                    gender: $('input[name="gender"]:checked').val(),
                    address: $("#address").val(),
                    avatar: $("#avatar").val(),
                    email: $("#email").val(),
                    tag_id: $("#tag_id").val(),
                    pipeline_code: $("#pipeline_code").val(),
                    journey_code: $("#journey_code").val(),
                    customer_type: $("#customer_type").val(),
                    hotline: $("#hotline").val(),
                    fanpage: $("#fanpage").val(),
                    zalo: $("#zalo").val(),
                    customer_lead_id: id,
                    customer_lead_code: $("#customer_lead_code").val(),
                    arrPhoneAttack: arrPhoneAttack,
                    arrEmailAttack: arrEmailAttack,
                    arrFanpageAttack: arrFanpageAttack,
                    arrContact: arrContact,
                    tax_code: $("#tax_code").val(),
                    representative: $("#representative").val(),
                    customer_source: $("#customer_source").val(),
                    business_clue: $("#business_clue").val(),
                    province_id: $("#province_id").val(),
                    district_id: $("#district_id").val(),
                    custom_1: $("#custom_1").val(),
                    custom_2: $("#custom_2").val(),
                    custom_3: $("#custom_3").val(),
                    custom_4: $("#custom_4").val(),
                    custom_5: $("#custom_5").val(),
                    custom_6: $("#custom_6").val(),
                    custom_7: $("#custom_7").val(),
                    custom_8: $("#custom_8").val(),
                    custom_9: $("#custom_9").val(),
                    custom_10: $("#custom_10").val(),

                    website: $("#website").val(),
                    business_id: $("#business_id").val(),
                    employ_qty: $("#employ_qty").val(),
                    birthday: $("#birthday").val(),
                    note: $("#note").val(),
                    branch_code: $("#branch_code").val(),
                },
                success: function (res) {
                    if (res.error == false) {
                        if (res.create_deal == 1) {
                            if ($('#chatHubPopup').val() == 'chathub_popup') {
                                swal({
                                    title: json["Chỉnh sửa khách hàng thành công"],
                                    text: 'Redirecting...',
                                    type: 'success',
                                    timer: 1500,
                                    showConfirmButton: false,
                                })
                                    .then(() => {
                                        listLead.processFunctioneEditCustomerLead(res);
                                    });
                            } else {
                                swal(res.message, "", "success").then(function (result) {
                                    if (
                                        result.dismiss == "esc" ||
                                        result.dismiss == "backdrop" ||
                                        result.dismiss == "overlay"
                                    ) {
                                        edit.showConfirmCreateDeal(res.lead_id);
                                    }
                                    if (result.value == true) {
                                        edit.showConfirmCreateDeal(res.lead_id);
                                    }
                                });
                            }

                        } else {
                            if ($('#chatHubPopup').val() == 'chathub_popup') {
                                swal({
                                    title: listLead.jsonLang["Chỉnh sửa khách hàng thành công"],
                                    text: 'Redirecting...',
                                    type: 'success',
                                    timer: 1500,
                                    showConfirmButton: false,
                                })
                                    .then(() => {
                                        listLead.processFunctioneEditCustomerLead(res);
                                    });
                            } else {
                                if (view) {
                                    swal({
                                        title: listLead.jsonLang["Chỉnh sửa khách hàng thành công"],
                                        text: 'Redirecting...',
                                        type: 'success',
                                        timer: 1500,
                                        showConfirmButton: false,
                                    })
                                        .then(() => {
                                            window.location.href = '/customer-lead/customer-lead/detail/' + id;
                                        });
                                } else {
                                    swal(res.message, "", "success").then(function (result) {
                                        if (result.dismiss == "esc" || result.dismiss == "backdrop") {
                                            $("#modal-edit").modal("hide");
                                        }
                                        if (result.value == true) {
                                            $("#modal-edit").modal("hide");
                                        }
                                    });

                                    if (load == true) {
                                        //Reload page
                                        setTimeout(() => {
                                            window.location.reload();
                                        }, 500);
                                    } else {
                                        $("#autotable").PioTable("refresh");
                                    }

                                }
                            }
                        }
                    } else {
                        swal(res.message, "", "error");
                    }
                },
                error: function (res) {
                    var mess_error = "";
                    $.map(res.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + "<br/>");
                    });
                    swal(listLead.jsonLang["Chỉnh sửa thất bại"], mess_error, "error");
                },
            });
        }
    },
    showConfirmCreateDeal: function (leadId) {
        swal({
            title: listLead.jsonLang["Thông báo"],
            text: listLead.jsonLang["Bạn có muốn tạo deal không?"],
            type: "warning",
            showCancelButton: true,
            confirmButtonText: listLead.jsonLang["Có"],
            cancelButtonText: listLead.jsonLang["Không"],
        }).then(function (result) {
            if (result.value) {
                edit.createDealAuto(leadId);
            } else {
                $("#modal-edit").modal("hide");
            }
        });
    },
    //Tạo deal tự động
    createDealAuto: function (leadId) {
        $.ajax({
            url: laroute.route("customer-lead.create-deal-auto"),
            method: "POST",
            dataType: "JSON",
            data: {
                customer_lead_id: leadId,
            },
            success: function (res) {
                $("#my-modal").html(res.html);
                $("#modal-create").modal("show");

                $("#end_date_expected").datepicker({
                    todayHighlight: !0,
                    autoclose: !0,
                    format: "dd/mm/yyyy",
                    startDate: "dateToday",
                });
                $("#staff").select2({
                    placeholder: listLead.jsonLang["Chọn người sở hữu"],
                });

                new AutoNumeric.multiple("#auto-deal-amount", {
                    currencySymbol: "",
                    decimalCharacter: ".",
                    digitGroupSeparator: ",",
                    decimalPlaces: decimal_number,
                    eventIsCancelable: true,
                    minimumValue: 0,
                });
                $("#customer_code").select2({
                    placeholder: listLead.jsonLang["Chọn khách hàng"],
                });

                $("#customer_contact_code").select2({
                    placeholder: listLead.jsonLang["Chọn liên hệ"],
                });

                $("#pipeline_code").select2({
                    placeholder: listLead.jsonLang["Chọn pipeline"],
                });

                $("#pipeline_code").change(function () {
                    $.ajax({
                        url: laroute.route("customer-lead.load-option-journey"),
                        dataType: "JSON",
                        data: {
                            pipeline_code: $("#pipeline_code").val(),
                        },
                        method: "POST",
                        success: function (res) {
                            $(".journey").empty();
                            $.map(res.optionJourney, function (a) {
                                $(".journey").append(
                                    '<option value="' +
                                    a.journey_code +
                                    '">' +
                                    a.journey_name +
                                    "</option>"
                                );
                            });
                        },
                    });
                });

                $("#journey_code").select2({
                    placeholder: listLead.jsonLang["Chọn hành trình"],
                });
                $("#customer_contact_code").select2();

                new AutoNumeric.multiple("#amount", {
                    currencySymbol: "",
                    decimalCharacter: ".",
                    digitGroupSeparator: ",",
                    decimalPlaces: decimal_number,
                    eventIsCancelable: true,
                    minimumValue: 0,
                });
                $("#tag_id")
                    .select2({
                        placeholder: listLead.jsonLang["Chọn tag"],
                        tags: true,
                        createTag: function (newTag) {
                            return {
                                id: newTag.term,
                                text: newTag.term,
                                isNew: true,
                            };
                        },
                    })
                    .on("select2:select", function (e) {
                        if (e.params.data.isNew) {
                            $.ajax({
                                type: "POST",
                                url: laroute.route(
                                    "customer-lead.customer-deal.store-quickly-tag"
                                ),
                                data: {
                                    tag_name: e.params.data.text,
                                },
                                success: function (res) {
                                    $("#tag_id")
                                        .find('[value="' + e.params.data.text + '"]')
                                        .replaceWith(
                                            '<option selected value="' +
                                            res.tag_id +
                                            '">' +
                                            e.params.data.text +
                                            "</option>"
                                        );
                                },
                            });
                        }
                    });
                $("#order_source").select2({
                    placeholder: listLead.jsonLang["Chọn nguồn đơn hàng"],
                });

                $("#probability").ForceNumericOnly();
            },
        });
    },
};

var index = {
    importExcel: function () {
        $("#modal-excel").modal("show");
        $("#show").val("");
        $("input[type=file]").val("");
    },
    importSubmit: function () {
        mApp.block(".modal-body", {
            overlayColor: "#000000",
            type: "loader",
            state: "success",
            message: "Xin vui lòng chờ...",
        });

        var file_data = $("#file_excel").prop("files")[0];
        var form_data = new FormData();
        form_data.append("file", file_data);
        console.log(file_data);
        console.log(form_data);
        $.ajax({
            url: laroute.route("customer-lead.import-excel"),
            method: "POST",
            data: form_data,
            contentType: false,
            cache: false,
            processData: false,
            success: function (res) {
                mApp.unblock(".modal-body");
                if (res.success == 1) {
                    swal(res.message, "", "success");
                    $("#autotable").PioTable("refresh");

                    if (res.number_error > 0) {
                        $(".export_error").css("display", "block");
                        $("#data_error").empty();

                        $.map(res.data_error, function (val) {
                            var tpl = $("#tpl-data-error").html();
                            tpl = tpl.replace(/{full_name}/g, val.full_name);
                            tpl = tpl.replace(/{phone}/g, val.phone);
                            tpl = tpl.replace(/{phone_attack}/g, val.phone_attack);
                            tpl = tpl.replace(/{birthday}/g, val.birthday);
                            tpl = tpl.replace(/{province_name}/g, val.province_name);
                            tpl = tpl.replace(/{district_name}/g, val.district_name);
                            tpl = tpl.replace(/{gender}/g, val.gender);
                            tpl = tpl.replace(/{email}/g, val.email);
                            tpl = tpl.replace(/{email_attach}/g, val.email_attach);
                            tpl = tpl.replace(/{address}/g, val.address);
                            tpl = tpl.replace(/{customer_type}/g, val.customer_type);
                            tpl = tpl.replace(/{pipeline}/g, val.pipeline);
                            tpl = tpl.replace(/{customer_source}/g, val.customer_source);
                            tpl = tpl.replace(/{business_clue}/g, val.business_clue);
                            tpl = tpl.replace(/{fanpage}/g, val.fanpage);
                            tpl = tpl.replace(/{fanpage_attack}/g, val.fanpage_attack);
                            tpl = tpl.replace(/{zalo}/g, val.zalo);
                            tpl = tpl.replace(/{tag}/g, val.tag);
                            tpl = tpl.replace(/{sale_id}/g, val.sale_id);
                            tpl = tpl.replace(/{tax_code}/g, val.tax_code);
                            tpl = tpl.replace(/{representative}/g, val.representative);
                            tpl = tpl.replace(/{hotline}/g, val.hotline);
                            tpl = tpl.replace(/{error}/g, val.error);
                            $("#data_error").append(tpl);
                        });

                        //Download file lỗi sẵn
                        $("#form-error").submit();
                    } else {
                        $(".export_error").css("display", "none");
                        $("#data_error").empty();
                    }
                } else {
                    swal(res.message, "", "error");
                }
            },
        });
    },
    fileName: function () {
        var fileNamess = $("input[type=file]").val();
        $("#show").val(fileNamess);
    },
    closeModalImport: function () {
        $("#modal-excel").modal("hide");
        $("#autotable").PioTable("refresh");
    },
};

var numberPhone = 0;
var numberEmail = 0;
var numberFanpage = 0;
var numberContact = 0;

var view = {
    addPhone: function () {
        var continute = true;

        //check các trường dữ liệu rỗng thì báo lỗi
        $.each($(".phone_append").find(".div_phone_attach"), function () {
            var phone = $(this).find($(".phone_attach")).val();
            var number = $(this).find($(".number_phone")).val();

            if (phone == "") {
                $(".error_phone_attach_" + number + "").text(
                    listLead.jsonLang["Hãy nhập số điện thoại"]
                );
                continute = false;
            } else {
                $(".error_phone_attach_" + number + "").text("");
            }
        });

        if (continute == true) {
            numberPhone++;
            //append tr table
            var tpl = $("#tpl-phone").html();
            tpl = tpl.replace(/{number}/g, numberPhone);
            $(".phone_append").append(tpl);

            $(".phone").ForceNumericOnly();
        }
    },
    removePhone: function (obj) {
        $(obj).closest(".div_phone_attach").remove();
    },
    addEmail: function () {
        var continute = true;

        //check các trường dữ liệu rỗng thì báo lỗi
        $.each($(".email_append").find(".div_email_attach"), function () {
            var email = $(this).find($(".email_attach")).val();
            var number = $(this).find($(".number_email")).val();

            if (email == "") {
                $(".error_email_attach_" + number + "").text(
                    listLead.jsonLang["Hãy nhập email"]
                );
                continute = false;
            } else {
                $(".error_email_attach_" + number + "").text("");
            }
        });

        if (continute == true) {
            numberEmail++;
            //append tr table
            var tpl = $("#tpl-email").html();
            tpl = tpl.replace(/{number}/g, numberEmail);
            $(".email_append").append(tpl);
        }
    },
    removeEmail: function (obj) {
        $(obj).closest(".div_email_attach").remove();
    },
    addFanpage: function () {
        var continute = true;

        //check các trường dữ liệu rỗng thì báo lỗi
        $.each($(".fanpage_append").find(".div_fanpage_attach"), function () {
            var fanpage = $(this).find($(".fanpage_attach")).val();
            var number = $(this).find($(".number_fanpage")).val();

            if (fanpage == "") {
                $(".error_fanpage_attach_" + number + "").text(
                    listLead.jsonLang["Hãy nhập fanpage"]
                );
                continute = false;
            } else {
                $(".error_fanpage_attach_" + number + "").text("");
            }
        });

        if (continute == true) {
            numberFanpage++;
            //append tr table
            var tpl = $("#tpl-fanpage").html();
            tpl = tpl.replace(/{number}/g, numberFanpage);
            $(".fanpage_append").append(tpl);
        }
    },
    removeFanpage: function (obj) {
        $(obj).closest(".div_fanpage_attach").remove();
    },
    changeType: function (obj) {
        if ($(obj).val() == "personal") {
            $(".append_type").empty();

            $(".append_contact").empty();
            $(".div_add_contact").css("display", "none");

            // $("#table-contact > tbody").empty();

            $(".div_business_clue").css("display", "block");

            $("#business_clue").select2({
                placeholder: listLead.jsonLang["Chọn đầu mối doanh nghiệp"],
            });

            $('.zone-business').css("display", "none");
            $('.zone-personal').css("display", "block");

        } else {
            var tpl = $("#tpl-type").html();
            $(".append_type").append(tpl);

            $(".div_add_contact").css("display", "block");

            $(".div_business_clue").css("display", "none");

            $('.zone-business').css("display", "block");
            $('.zone-personal').css("display", "none");
        }
    },
    addContact: function (staffTitles) {
        var continute = true;

        //check các trường dữ liệu rỗng thì báo lỗi
        $.each($("#table-contact").find(".tr_contact"), function () {
            var fullName = $(this).find($(".full_name_contact")).val();
            var phoneContact = $(this).find($(".phone_contact")).val();
            var emailContact = $(this).find($(".email_contact")).val();
            var addressContact = $(this).find($(".address_contact")).val();
            let staffTitleContact = $(this).find($(".staff-title-contact")).val();
            var number = $(this).find($(".number_contact")).val();

            if (fullName == "") {
                $(".error_full_name_contact_" + number + "").text(
                    listLead.jsonLang["Hãy nhập họ và tên"]
                );
                continute = false;
            } else {
                $(".error_full_name_contact_" + number + "").text("");
            }

            if (phoneContact == "") {
                $(".error_phone_contact_" + number + "").text(
                    listLead.jsonLang["Hãy nhập số điện thoại"]
                );
                continute = false;
            } else {
                $(".error_phone_contact_" + number + "").text("");
            }

            if (addressContact == "") {
                $(".error_address_contact_" + number + "").text(
                    listLead.jsonLang["Hãy nhập địa chỉ"]
                );
                continute = false;
            } else {
                $(".error_address_contact_" + number + "").text("");
            }

            if (staffTitleContact == "") {
                $(".error_staff_title_contact_" + number + "").text(
                    listLead.jsonLang["Hãy chọn chức vụ"]
                );
                continute = false;
            } else {
                $(".error_staff_title_contact_" + number + "").text("");
            }

            if (emailContact == "") {
                $(".error_email_contact_" + number + "").text(
                    listLead.jsonLang["Hãy nhập email"]
                );
                continute = false;
            } else {
                $(".error_email_contact_" + number + "").text("");

                if (isValidEmailAddress(emailContact) == false) {
                    $(".error_email_contact_" + number + "").text(
                        listLead.jsonLang["Email không hợp lệ"]
                    );
                    continute = false;
                } else {
                    $(".error_email_contact_" + number + "").text("");
                }
            }
        });

        if (continute == true) {
            numberContact++;
            //append tr table
            var tpl = this.renderStaffTitle(staffTitles);
            tpl = tpl.replace(/{number}/g, numberContact);
            $("#table-contact > tbody").append(tpl);

            $(".phone").ForceNumericOnly();
        }
    },

    renderStaffTitle: function (data) {
        let listTitle = '<select class="form-control staff-title-contact" name="staff_title_{number}"><option value="">Chọn chức vụ</option>';
        listTitle += data.map(item => `<option value="${item.staff_title_id}">${item.staff_title_name}</option>`);
        listTitle += '</select>';

        return `<tr class="tr_contact">
        <td>
            <input type="hidden" class="number_contact" value="{number}">
            <input type="text" class="form-control m-input full_name_contact" placeholder="${listLead.jsonLang["Họ và tên"]}">
            <span class="error_full_name_contact_{number} color_red"></span>
        </td>
        <td>
            <input type="text" class="form-control m-input phone phone_contact" placeholder="${listLead.jsonLang["Số điện thoại"]}">
            <span class="error_phone_contact_{number} color_red"></span>
        </td>
        <td>
            <input type="text" class="form-control email_contact" placeholder="${listLead.jsonLang["Email"]}">
            <span class="error_email_contact_{number} color_red"></span>
        </td>
        <td>
            ${listTitle}
            <span class="error_staff_title_contact_{number} color_red"></span>
        </td>
        <td>
            <a class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill" href="javascript:void(0)" onclick="view.removeContact(this)">
                <i class="la la-trash"></i>
            </a>
        </td>
    </tr>`;
    },
    removeContact: function (obj) {
        $(obj).closest(".tr_contact").remove();
    },
    changeProvince: function (obj) {
        $.ajax({
            url: laroute.route("admin.customer.load-district"),
            dataType: "JSON",
            data: {
                id_province: $(obj).val(),
            },
            method: "POST",
            success: function (res) {
                $(".district").empty();

                $.map(res.optionDistrict, function (a) {
                    $(".district").append(
                        '<option value="' +
                        a.id +
                        '">' +
                        a.type +
                        " " +
                        a.name +
                        "</option>"
                    );
                });
            },
        });
    },
    changeBoolean: function (obj) {
        if ($(obj).is(":checked")) {
            $(obj).val(1);
        } else {
            $(obj).val(0);
        }
    },
};

var detail = {
    processFunctionAddSuccessConvertCustomer: function (data) {
        $("#modal-detail").modal("hide");
        window.postMessage(
            {
                func: "addSuccessConvertCustomer",
                message: data,
            },
            "*"
        );
    },
    convertCustomer: function (lead_id, flag) {
        // flag = 0: chuyển đổi KH k tạo deal, flag = 1: chuyển đổi KH có tạo deal
        // update is_convert = 1
        if (flag == 0) {
            $.ajax({
                url: laroute.route("convert-customer-no-deal"),
                method: "POST",
                dataType: "JSON",
                data: {
                    customer_lead_id: lead_id,
                },
                success: function (res) {
                    if (res.error == false) {
                        swal(res.message, "", "success").then(function (result) {
                            if (
                                typeof $("#view_mode") != "undefined" &&
                                $("#view_mode").val() == "chathub_popup"
                            ) {
                                listLead.processFunctionAddSuccessConvertCustomer(res);
                            } else {
                                if (result.dismiss == "esc" || result.dismiss == "backdrop") {
                                    window.location.href = laroute.route("customer-lead");
                                }
                                if (result.value == true) {
                                    window.location.href = laroute.route("customer-lead");
                                }
                            }
                        });
                    } else {
                        swal(res.message, "", "error");
                    }
                },
                error: function (res) {
                    var mess_error = "";
                    $.map(res.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + "<br/>");
                    });
                    swal(listLead.jsonLang["Chuyển đổi thất bại"], mess_error, "error");
                },
            });
        } else if (flag == 1) {
            $.ajax({
                url: laroute.route("customer-lead.create-deal"),
                method: "POST",
                dataType: "JSON",
                data: {
                    customer_lead_id: lead_id,
                },
                success: function (res) {
                    $("#my-modal").html(res.html);
                    $("#modal-detail").modal("hide");
                    $("#modal-create").modal("show");

                    $("#end_date_expected").datepicker({
                        todayHighlight: !0,
                        autoclose: !0,
                        format: "dd/mm/yyyy",
                        startDate: "dateToday",
                    });
                    $("#staff").select2({
                        placeholder: listLead.jsonLang["Chọn người sở hữu"],
                    });

                    new AutoNumeric.multiple("#auto-deal-amount", {
                        currencySymbol: "",
                        decimalCharacter: ".",
                        digitGroupSeparator: ",",
                        decimalPlaces: decimal_number,
                        eventIsCancelable: true,
                        minimumValue: 0,
                    });
                    $("#customer_code").select2({
                        placeholder: listLead.jsonLang["Chọn khách hàng"],
                    });

                    $("#customer_contact_code").select2({
                        placeholder: listLead.jsonLang["Chọn liên hệ"],
                    });

                    $("#pipeline_code").select2({
                        placeholder: listLead.jsonLang["Chọn pipeline"],
                    });

                    $("#pipeline_code").change(function () {
                        $.ajax({
                            url: laroute.route("customer-lead.load-option-journey"),
                            dataType: "JSON",
                            data: {
                                pipeline_code: $("#pipeline_code").val(),
                            },
                            method: "POST",
                            success: function (res) {
                                $(".journey").empty();
                                var today = moment().format("DD/MM/YYYY");
                                var new_date = moment(today, "DD/MM/YYYY");
                                new_date.add(parseInt(res.time_revoke_lead), "days");
                                new_date = new_date.format("DD/MM/YYYY");
                                $("#end_date_expected").val(new_date);
                                $.map(res.optionJourney, function (a) {
                                    $(".journey").append(
                                        '<option value="' +
                                        a.journey_code +
                                        '">' +
                                        a.journey_name +
                                        "</option>"
                                    );
                                });
                            },
                        });
                    });

                    $("#journey_code").select2({
                        placeholder: listLead.jsonLang["Chọn hành trình"],
                    });
                    $("#customer_contact_code").select2();

                    new AutoNumeric.multiple("#amount", {
                        currencySymbol: "",
                        decimalCharacter: ".",
                        digitGroupSeparator: ",",
                        decimalPlaces: decimal_number,
                        eventIsCancelable: true,
                        minimumValue: 0,
                    });
                    $("#tag_id")
                        .select2({
                            placeholder: listLead.jsonLang["Chọn tag"],
                            tags: true,
                            createTag: function (newTag) {
                                return {
                                    id: newTag.term,
                                    text: newTag.term,
                                    isNew: true,
                                };
                            },
                        })
                        .on("select2:select", function (e) {
                            if (e.params.data.isNew) {
                                $.ajax({
                                    type: "POST",
                                    url: laroute.route(
                                        "customer-lead.customer-deal.store-quickly-tag"
                                    ),
                                    data: {
                                        tag_name: e.params.data.text,
                                    },
                                    success: function (res) {
                                        $("#tag_id")
                                            .find('[value="' + e.params.data.text + '"]')
                                            .replaceWith(
                                                '<option selected value="' +
                                                res.tag_id +
                                                '">' +
                                                e.params.data.text +
                                                "</option>"
                                            );
                                    },
                                });
                            }
                        });
                    $("#order_source").select2({
                        placeholder: listLead.jsonLang["Chọn nguồn đơn hàng"],
                    });

                    $("#probability").ForceNumericOnly();
                    $("#pipeline_code").trigger("change");
                    var fn = $("#deal_name").val();
                    var pipName = $("#pipeline_code option:selected").text();
                    $("#deal_name").val(pipName.trim() + "_" + fn);
                },
            });
        }
    },
    addObject: function () {
        stt++;
        var tpl = $("#tpl-object").html();
        tpl = tpl.replace(/{stt}/g, stt);
        $(".append-object").append(tpl);
        $(".object_type").select2({
            placeholder: listLead.jsonLang["Chọn loại"],
        });

        $(".object_code").select2({
            placeholder: listLead.jsonLang["Chọn đối tượng"],
        });

        $(".object_quantity").TouchSpin({
            initval: 1,
            min: 1,
            buttondown_class: "btn btn-metal btn-sm",
            buttonup_class: "btn btn-metal btn-sm",
        });

        // Tính lại giá khi thay đổi số lượng
        $(".object_quantity, .object_discount, .object_price").change(function () {
            $(this).closest("tr").find(".object_amount").empty();
            var type = $(this).closest("tr").find(".object_type").val();
            var id_type = 0;
            if (type === "product") {
                id_type = 1;
            } else if (type === "service") {
                id_type = 2;
            } else if (type === "service_card") {
                id_type = 3;
            }
            var price = $(this)
                .closest("tr")
                .find('input[name="object_price"]')
                .val()
                .replace(new RegExp("\\,", "g"), "");
            var discount = $(this)
                .closest("tr")
                .find('input[name="object_discount"]')
                .val();
            var loc = discount.replace(new RegExp("\\,", "g"), "");
            var quantity = $(this)
                .closest("tr")
                .find('input[name="object_quantity"]')
                .val();

            var amount = price * quantity - loc > 0 ? price * quantity - loc : 0;

            $(this)
                .closest("tr")
                .find(".object_amount")
                .val(formatNumber(amount.toFixed(decimal_number)));

            $("#amount").empty();
            $("#amount-remove").html("");
            $("#amount-remove").append(
                `<input type="text" class="form-control m-input" id="amount" name="amount">`
            );
            var sum = 0;
            $.each(
                $("#table_add > tbody").find('input[name="object_amount"]'),
                function () {
                    sum += Number($(this).val().replace(new RegExp("\\,", "g"), ""));
                }
            );
            $("#amount").val(formatNumber(sum.toFixed(decimal_number)));
            new AutoNumeric.multiple("#amount", {
                currencySymbol: "",
                decimalCharacter: ".",
                digitGroupSeparator: ",",
                decimalPlaces: decimal_number,
                eventIsCancelable: true,
                minimumValue: 0,
            });
        });

        new AutoNumeric.multiple('#object_discount_' + stt + ', #object_price_' + stt + '', {
            currencySymbol: '',
            decimalCharacter: '.',
            digitGroupSeparator: ',',
            decimalPlaces: decimal_number,
            eventIsCancelable: true,
            minimumValue: 0
        });
    },

    removeObject: function (obj) {
        $(obj).closest(".add-object").remove();
        // Tính lại tổng tiền
        $("#auto-deal-amount").empty();
        $("#auto-deal-amount-remove").html("");
        $("#auto-deal-amount-remove").append(
            `<input type="text" class="form-control m-input" id="auto-deal-amount" name="auto-deal-amount">`
        );
        var sum = 0;
        $.each(
            $("#table_add > tbody").find('input[name="object_amount"]'),
            function () {
                sum += Number($(this).val().replace(new RegExp("\\,", "g"), ""));
            }
        );
        $("#auto-deal-amount").val(formatNumber(sum.toFixed(decimal_number)));
        new AutoNumeric.multiple("#auto-deal-amount", {
            currencySymbol: "",
            decimalCharacter: ".",
            digitGroupSeparator: ",",
            decimalPlaces: decimal_number,
            eventIsCancelable: true,
            minimumValue: 0,
        });
    },

    changeObjectType: function (obj) {
        var object = $(obj).val();
        // product, service, service_card
        $(obj).closest("tr").find(".object_code").prop("disabled", false);
        $(obj).closest('tr').find('.object_code').empty();
        $(obj).closest("tr").find(".object_code").val("").trigger("change");

        $(obj)
            .closest("tr")
            .find(".object_code")
            .select2({
                placeholder: listLead.jsonLang["Chọn đối tượng"],
                ajax: {
                    url: laroute.route("customer-lead.customer-deal.load-object"),
                    data: function (params) {
                        return {
                            search: params.term,
                            page: params.page || 1,
                            type: $(obj).val(),
                        };
                    },
                    dataType: "json",
                    method: "POST",
                    processResults: function (data) {
                        data.page = data.page || 1;
                        return {
                            results: data.items.map(function (item) {
                                if ($(obj).val() == "product") {
                                    return {
                                        id: item.product_code,
                                        text: item.product_child_name,
                                        code: item.product_code,
                                    };
                                } else if ($(obj).val() == "service") {
                                    return {
                                        id: item.service_code,
                                        text: item.service_name,
                                        code: item.service_code,
                                    };
                                } else if ($(obj).val() == "service_card") {
                                    return {
                                        id: item.code,
                                        text: item.card_name,
                                        code: item.code,
                                    };
                                }
                            }),
                            pagination: {
                                more: data.pagination,
                            },
                        };
                    },
                },
            })
            .on("select2:open", function (e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
    },

    changeObject: function (obj) {
        var object_type = $(obj).closest("tr").find(".object_type").val();
        var object_code = $(obj).val();
        var stt_row = $(obj).closest('tr').find('.stt_row').val();

        //get price of object
        $.ajax({
            url: laroute.route("customer-lead.customer-deal.get-price-object"),
            dataType: "JSON",
            data: {
                object_type: object_type,
                object_code: object_code,
            },
            method: "POST",
            success: function (result) {
                //Remove giá trong td
                $('.td_object_price_' + stt_row + '').empty();

                if (Object.keys(result).length === 0) {
                    //Append lại input giá
                    var tplPrice = $('#tpl-object-price').html();
                    tplPrice = tplPrice.replace(/{stt}/g, stt_row);
                    tplPrice = tplPrice.replace(/{price}/g, 0);
                    $('.td_object_price_' + stt_row + '').append(tplPrice);

                    $(obj).closest("tr").find($(".object_amount")).val(formatNumber(Number(0).toFixed(decimal_number)));
                } else {
                    if (object_type == "product") {
                        //Append lại input giá
                        var tplPrice = $('#tpl-object-price').html();
                        tplPrice = tplPrice.replace(/{stt}/g, stt_row);
                        tplPrice = tplPrice.replace(/{price}/g, result.price);
                        $('.td_object_price_' + stt_row + '').append(tplPrice);

                        // Reset số lượng về 1, Tính lại tiền * số lượng
                        $(obj).closest("tr").find(".object_quantity").val(1);
                        var discount = $(obj).closest("tr").find(".object_discount").val().replace(new RegExp("\\,", "g"), "");
                        var amount = Number(result.price) - discount;
                        $(obj).closest("tr").find(".object_amount").val(formatNumber(Number(amount > 0 ? amount : 0).toFixed(decimal_number)));
                        $(obj).closest("tr").find(".object_id").val(result.product_child_id);
                    } else if (object_type == "service") {
                        //Append lại input giá
                        var tplPrice = $('#tpl-object-price').html();
                        tplPrice = tplPrice.replace(/{stt}/g, stt_row);
                        tplPrice = tplPrice.replace(/{price}/g, result.price_standard);
                        $('.td_object_price_' + stt_row + '').append(tplPrice);

                        $(obj).closest("tr").find(".object_quantity").val(1);

                        var discount = $(obj).closest("tr").find(".object_discount").val().replace(new RegExp("\\,", "g"), "");
                        var amount = Number(result.price_standard) - discount;
                        $(obj).closest("tr").find(".object_amount").val(formatNumber(Number(amount > 0 ? amount : 0).toFixed(decimal_number)));
                        $(obj).closest("tr").find(".object_id").val(result.service_id);
                    } else if (object_type == "service_card") {
                        //Append lại input giá
                        var tplPrice = $('#tpl-object-price').html();
                        tplPrice = tplPrice.replace(/{stt}/g, stt_row);
                        tplPrice = tplPrice.replace(/{price}/g, result.price);
                        $('.td_object_price_' + stt_row + '').append(tplPrice);

                        $(obj).closest("tr").find(".object_quantity").val(1);

                        var discount = $(obj).closest("tr").find(".object_discount").val().replace(new RegExp("\\,", "g"), "");
                        var amount = Number(result.price) - discount;
                        $(obj).closest("tr").find(".object_amount").val(formatNumber(Number(amount > 0 ? amount : 0).toFixed(decimal_number)));
                        $(obj).closest("tr").find(".object_id").val(result.service_card_id);
                    }
                }

                // Tính lại tổng tiền
                $("#amount").empty();
                $("#amount-remove").html("");
                $("#amount-remove").append(
                    `<input type="text" class="form-control m-input" id="amount" name="amount">`
                );
                var sum = 0;
                $.each(
                    $("#table_add > tbody").find('input[name="object_amount"]'),
                    function () {
                        sum += Number($(this).val().replace(new RegExp("\\,", "g"), ""));
                    }
                );
                $("#amount").val(formatNumber(sum.toFixed(decimal_number)));

                new AutoNumeric.multiple('#amount, #object_price_' + stt_row + '', {
                    currencySymbol: "",
                    decimalCharacter: ".",
                    digitGroupSeparator: ",",
                    decimalPlaces: decimal_number,
                    eventIsCancelable: true,
                    minimumValue: 0,
                });

                $('.object_price').change(function () {
                    $(this).closest('tr').find('.object_amount').empty();
                    var type = $(this).closest('tr').find('.object_type').val();
                    var id_type = 0;
                    if (type === "product") {
                        id_type = 1;
                    } else if (type === "service") {
                        id_type = 2;
                    } else if (type === "service_card") {
                        id_type = 3;
                    }
                    var price = $(this).closest('tr').find('input[name="object_price"]').val().replace(new RegExp('\\,', 'g'), '');
                    var discount = $(this).closest('tr').find('input[name="object_discount"]').val();
                    var loc = discount.replace(new RegExp('\\,', 'g'), '');
                    var quantity = $(this).closest('tr').find('input[name="object_quantity"]').val();

                    var amount = ((price * quantity) - loc) > 0 ? ((price * quantity) - loc) : 0;

                    $(this).closest('tr').find('.object_amount').val(formatNumber(amount.toFixed(decimal_number)));

                    $('#amount').empty();
                    $('#amount-remove').html('');
                    $('#amount-remove').append(`<input type="text" class="form-control m-input" id="amount" name="amount">`);
                    var sum = 0;
                    $.each($('#table_add > tbody').find('input[name="object_amount"]'), function () {
                        sum += Number($(this).val().replace(new RegExp('\\,', 'g'), ''));
                    });
                    $('#amount').val(formatNumber(sum.toFixed(decimal_number)));
                    new AutoNumeric.multiple('#amount', {
                        currencySymbol: '',
                        decimalCharacter: '.',
                        digitGroupSeparator: ',',
                        decimalPlaces: decimal_number,
                        eventIsCancelable: true,
                        minimumValue: 0
                    });

                });
            },
        });
    },

    createDeal: function () {
        var form = $("#form-create");

        form.validate({
            rules: {
                deal_name: {
                    required: true,
                },
                staff: {
                    required: true,
                },
                customer_code: {
                    required: true,
                },
                pipeline_code: {
                    required: true,
                },
                journey_code: {
                    required: true,
                },
                end_date_expected: {
                    required: true,
                },
                add_phone: {
                    required: true,
                    integer: true,
                    maxlength: 10,
                },
            },
            messages: {
                deal_name: {
                    required: listLead.jsonLang["Hãy nhập tên deal"],
                },
                staff: {
                    required: listLead.jsonLang["Hãy chọn người sở hữu deal"],
                },
                customer_code: {
                    required: listLead.jsonLang["Hãy chọn khách hàng"],
                },
                pipeline_code: {
                    required: listLead.jsonLang["Hãy chọn pipeline"],
                },
                journey_code: {
                    required: listLead.jsonLang["Hãy chọn hành trình khách hàng"],
                },
                end_date_expected: {
                    required: listLead.jsonLang["Hãy chọn ngày kết thúc dự kiến"],
                },
                add_phone: {
                    required: listLead.jsonLang["Hãy nhập số điện thoại"],
                    integer: listLead.jsonLang["Số điện thoại không hợp lệ"],
                    maxlength: listLead.jsonLang["Số điện thoại tối đa 10 kí tự"],
                },
            },
        });

        if (!form.valid()) {
            return false;
        }
        var flag = true;

        // check object
        $.each($("#table_add > tbody").find(".add-object"), function () {
            var object_type = $(this).find($(".object_type")).val();
            var object_code = $(this).find($(".object_id")).val();

            if (object_type == "") {
                $(this)
                    .find($(".error_object_type"))
                    .text(listLead.jsonLang["Vui lòng chọn loại sản phẩm"]);
                flag = false;
            } else {
                $(this).find($(".error_object_type")).text("");
            }
            if (object_code == "") {
                $(this)
                    .find($(".error_object"))
                    .text(listLead.jsonLang["Vui lòng chọn sản phẩm"]);
                flag = false;
            } else {
                $(this).find($(".error_object")).text("");
            }
        });

        // Lấy danh sách object (nếu có)
        var arrObject = [];
        $.each($("#table_add > tbody").find(".add-object"), function () {
            var object_type = $(this).find($(".object_type")).val();
            var object_name = $(this).find($(".object_code")).text();
            var object_code = $(this).find($(".object_code")).val();
            var object_id = $(this).find($(".object_id")).val();
            var price = $(this).find($(".object_price")).val();
            var quantity = $(this).find($(".object_quantity")).val();
            var discount = $(this).find($(".object_discount")).val();
            var amount = $(this).find($(".object_amount")).val();

            arrObject.push({
                object_type: object_type,
                object_name: object_name,
                object_code: object_code,
                object_id: object_id,
                price: price,
                quantity: quantity,
                discount: discount,
                amount: amount,
            });
        });

        if (flag == true) {
            $.ajax({
                url: laroute.route("customer-lead.customer-deal.store"),
                method: "POST",
                dataType: "JSON",
                data: {
                    deal_name: $("#deal_name").val(),
                    staff: $("#staff").val(),
                    customer_code: $("#customer_code").val(),
                    customer_contact_code: $("#customer_contact_code").val(),
                    pipeline_code: $("#pipeline_code").val(),
                    journey_code: $("#journey_code").val(),
                    tag_id: $("#tag_id").val(),
                    order_source_id: $("#order_source").val(),
                    phone: $("#add_phone").val(),
                    amount: $("#amount").val(),
                    probability: $("#probability").val(),
                    end_date_expected: $("#end_date_expected").val(),
                    deal_description: $("#deal_description").val(),
                    deal_type_code: $("#deal_type_code").val(),
                    type_customer: $("#type_customer").val(),
                    arrObject: arrObject,
                },
                success: function (res) {
                    if (res.error == false) {
                        swal(res.message, "", "success").then(function (result) {
                            if (result.dismiss == "esc" || result.dismiss == "backdrop") {
                                window.location.href = laroute.route(
                                    "customer-lead.customer-deal"
                                );
                            }
                            if (result.value == true) {
                                window.location.href = laroute.route(
                                    "customer-lead.customer-deal"
                                );
                            }
                        });
                    } else {
                        swal(res.message, "", "error");
                    }
                },
                error: function (res) {
                    var mess_error = "";
                    $.map(res.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + "<br/>");
                    });
                    swal(listLead.jsonLang["Thêm thất bại"], mess_error, "error");
                },
            });
        }
    },

    changeTab: function (tabName) {
        switch (tabName) {
            case "info":
                $("#div-info").css("display", "block");
                $("#div-care").css("display", "none");
                $("#div-deal").css("display", "none");
                $("#div-support").css("display", "none");
                $("#div-comment").css("display", "none");
                $("#div-file").css("display", "none");
                $("#div-contact").css("display", "none");
                break;

            case "care":
                $("#div-info").css("display", "none");
                $("#div-care").css("display", "block");
                $("#div-deal").css("display", "none");
                $("#div-support").css("display", "none");
                $("#div-comment").css("display", "none");
                $("#div-note").css("display", "none");
                $("#div-file").css("display", "none");
                $("#div-contact").css("display", "none");
                break;

            case "deal":
                $("#div-info").css("display", "none");
                $("#div-care").css("display", "none");
                $("#div-support").css("display", "none");
                $("#div-deal").css("display", "block");
                $("#div-comment").css("display", "none");
                $("#div-note").css("display", "none");
                $("#div-file").css("display", "none");
                $("#div-contact").css("display", "none");
                break;
            case "note":
                $("#div-info").css("display", "none");
                $("#div-care").css("display", "none");
                $("#div-deal").css("display", "none");
                $("#div-support").css("display", "none");
                $("#div-note").css("display", "block");
                $("#div-comment").css("display", "none");
                $("#div-file").css("display", "none");
                $("#div-contact").css("display", "none");
                break;
            case "file":
                $("#div-info").css("display", "none");
                $("#div-care").css("display", "none");
                $("#div-deal").css("display", "none");
                $("#div-support").css("display", "none");
                $("#div-note").css("display", "none");
                $("#div-comment").css("display", "none");
                $("#div-file").css("display", "block");
                $("#div-contact").css("display", "none");
                break;
            case "contact":
                $("#div-info").css("display", "none");
                $("#div-care").css("display", "none");
                $("#div-deal").css("display", "none");
                $("#div-support").css("display", "none");
                $("#div-note").css("display", "none");
                $("#div-comment").css("display", "none");
                $("#div-file").css("display", "none");
                $("#div-contact").css("display", "block");
                break;
            case "support":
                $("#div-info").css("display", "none");
                $("#div-care").css("display", "none");
                $("#div-deal").css("display", "none");
                $("#div-support").css("display", "block");
                $("#div-comment").css("display", "none");
                $("#div-note").css("display", "none");
                $("#div-file").css("display", "none");
                $("#div-contact").css("display", "none");
                break;
            case "comment":
                $("#div-info").css("display", "none");
                $("#div-care").css("display", "none");
                $("#div-deal").css("display", "none");
                $("#div-support").css("display", "none");
                $("#div-comment").css("display", "block");
                $("#div-note").css("display", "none");
                $("#div-file").css("display", "none");
                $("#div-contact").css("display", "none");
                CustomerComment.getListCustomerComment();
                break;
        }
    },
};

var arrOldSaleChecked = [];
var assign = {
    _init: function () {
        $("#autotable").PioTable({
            baseUrl: laroute.route("customer-lead.list-lead-not-assign-yet"),
        });
        $("#customer_source")
            .select2({
                placeholder: listLead.jsonLang["Chọn hành trình"],
            })
            .change(function () {
                $(".btn-search").submit();
            });
        $("#journey_code").select2({
            placeholder: listLead.jsonLang["Chọn hành trình"],
        });

        $("#pipeline_code")
            .select2()
            .change(function () {
                $(".journey").empty();
                if ($("#pipeline_code").val() == "") {
                    $(".journey").append(
                        '<option value="">' +
                        listLead.jsonLang["Chọn hành trình"] +
                        "</option>"
                    );
                    return;
                }
                $.ajax({
                    url: laroute.route("customer-lead.load-option-journey"),
                    dataType: "JSON",
                    data: {
                        pipeline_code: $("#pipeline_code").val(),
                    },
                    method: "POST",
                    success: function (res) {
                        $(".journey").append(
                            '<option value="">' +
                            listLead.jsonLang["Chọn hành trình"] +
                            "</option>"
                        );
                        $.map(res.optionJourney, function (a) {
                            $(".journey").append(
                                '<option value="' +
                                a.journey_code +
                                '">' +
                                a.journey_name +
                                "</option>"
                            );
                        });
                    },
                });

                $("#pipeline_code_filter").val($(this).val());
            });
        $("#department")
            .select2({
                placeholder: listLead.jsonLang["Hãy chọn phòng ban"],
            })
            .on("select2:select", function (e) {
                // Bỏ check all sale
                $("#checkAllSale").prop("checked", false);

                let arrDepartment = $("#department").val();
                // load option sales
                $.ajax({
                    url: laroute.route("customer-lead.load-option-sale"),
                    method: "POST",
                    dataType: "JSON",
                    data: {
                        arrayDepartment: arrDepartment,
                    },
                    success: function (res) {
                        $("#staff").empty();
                        $.map(res.optionStaff, function (a) {
                            // nếu đã tồn tại trong mảng arrOldSaleChecked thì checked
                            if (!arrOldSaleChecked.includes(a.staff_id)) {
                                console.log(true);
                                $("#staff").append(
                                    '<option value="' +
                                    a.staff_id +
                                    '">' +
                                    a.full_name +
                                    "</option>"
                                );
                            } else {
                                console.log(arrOldSaleChecked);
                                $("#staff").append(
                                    '<option value="' +
                                    a.staff_id +
                                    '" selected>' +
                                    a.full_name +
                                    "</option>"
                                );
                            }
                        });
                    },
                });
            })
            .on("select2:unselect", function (e) {
                // Bỏ check all sale
                $("#checkAllSale").prop("checked", false);

                let arrDepartment = $("#department").val();
                $.ajax({
                    url: laroute.route("customer-lead.load-option-sale"),
                    dataType: "JSON",
                    method: "POST",
                    data: {
                        arrayDepartment: arrDepartment,
                    },
                    success: function (res) {
                        $("#staff").empty();
                        $.map(res.optionStaff, function (a) {
                            $("#staff").append(
                                '<option value="' +
                                a.staff_id +
                                '">' +
                                a.full_name +
                                "</option>"
                            );
                        });
                    },
                });
            });

        $("#staff")
            .select2({
                placeholder: listLead.jsonLang["Chọn sale"],
            })
            .on("select2:unselect", function (e) {
                arrOldSaleChecked = $("#staff")
                    .val()
                    .map(function (i) {
                        return parseInt(i, 10);
                    });
            })
            .on("select2:select", function (e) {
                arrOldSaleChecked = $("#staff")
                    .val()
                    .map(function (i) {
                        return parseInt(i, 10);
                    });
            });
    },
    checkAllSale: function () {
        $("#staff").val("").trigger("change");
        if ($("#checkAllSale").is(":checked")) {
            $("#staff > option").prop("selected", "selected");
            $("#staff").trigger("change");
            arrOldSaleChecked = $("#staff")
                .val()
                .map(function (i) {
                    return parseInt(i, 10);
                });
            console.log(arrOldSaleChecked);
        } else {
            arrOldSaleChecked = [];
        }
    },
    chooseAll: function (obj) {
        if ($(obj).is(":checked")) {
            $(".check_one").prop("checked", true);
            let arrCheck = [];
            $(".check_one").each(function () {
                arrCheck.push({
                    customer_lead_id: $(this)
                        .parents("label")
                        .find(".customer_lead_id")
                        .val(),
                    customer_lead_code: $(this)
                        .parents("label")
                        .find(".customer_lead_code")
                        .val(),
                    time_revoke_lead: $(this)
                        .parents("label")
                        .find(".time_revoke_lead")
                        .val(),
                });
            });

            $.ajax({
                url: laroute.route("customer-lead.choose-all"),
                method: "POST",
                dataType: "JSON",
                data: {
                    arr_check: arrCheck,
                },
            });
        } else {
            $(".check_one").prop("checked", false);

            var arrUnCheck = [];
            $(".check_one").each(function () {
                arrUnCheck.push({
                    customer_lead_id: $(this)
                        .parents("label")
                        .find(".customer_lead_id")
                        .val(),
                    customer_lead_code: $(this)
                        .parents("label")
                        .find(".customer_lead_code")
                        .val(),
                    time_revoke_lead: $(this)
                        .parents("label")
                        .find(".time_revoke_lead")
                        .val(),
                });
            });

            $.ajax({
                url: laroute.route("customer-lead.un-choose-all"),
                method: "POST",
                dataType: "JSON",
                data: {
                    arr_un_check: arrUnCheck,
                },
            });
        }
    },
    choose: function (obj) {
        if ($(obj).is(":checked")) {
            let customerLeadId = "";
            let customerLeadCode = "";
            let timeRevokeLead = "";
            customerLeadId = $(obj).parents("label").find(".customer_lead_id").val();
            customerLeadCode = $(obj)
                .parents("label")
                .find(".customer_lead_code")
                .val();
            timeRevokeLead = $(obj).parents("label").find(".time_revoke_lead").val();

            $.ajax({
                url: laroute.route("customer-lead.choose"),
                method: "POST",
                dataType: "JSON",
                data: {
                    customer_lead_id: customerLeadId,
                    customer_lead_code: customerLeadCode,
                    time_revoke_lead: timeRevokeLead,
                },
            });
        } else {
            let customerLeadId = "";
            let customerLeadCode = "";
            let timeRevokeLead = "";
            customerLeadId = $(obj).parents("label").find(".customer_lead_id").val();
            customerLeadCode = $(obj)
                .parents("label")
                .find(".customer_lead_code")
                .val();
            timeRevokeLead = $(obj).parents("label").find(".time_revoke_lead").val();

            $.ajax({
                url: laroute.route("customer-lead.un-choose"),
                method: "POST",
                dataType: "JSON",
                data: {
                    customer_lead_id: customerLeadId,
                    customer_lead_code: customerLeadCode,
                    time_revoke_lead: timeRevokeLead,
                },
            });
        }
    },
    checkAllLead: function () {
        if ($("#checkAllLead").is(":checked")) {
            $(".check_one").prop("checked", true);
            $.ajax({
                url: laroute.route("customer-lead.check-all-lead"),
                method: "POST",
                dataType: "JSON",
                data: {
                    is_check_all: 1,
                    search: $("input[name=search]").val(),
                    customer_source: $("#customer_source option:selected").val(),
                },
                success: function (res) {
                    $("#autotable").PioTable("refresh");
                },
            });
        } else {
            $(".check_one").prop("checked", false);
            $.ajax({
                url: laroute.route("customer-lead.check-all-lead"),
                method: "POST",
                dataType: "JSON",
                data: {
                    is_check_all: 0,
                },
                success: function (res) {
                    $("#autotable").PioTable("refresh");
                },
            });
        }
    },

    submit: function () {
        var form = $("#form-assign");
        form.validate({
            rules: {
                department: {
                    required: true,
                },
                staff: {
                    required: true,
                },
            },
            messages: {
                department: {
                    required: listLead.jsonLang["Hãy chọn phòng ban"],
                },
                staff: {
                    required: listLead.jsonLang["Hãy chọn nhân viên bị thu hồi"],
                },
            },
        });

        if (!form.valid()) {
            return false;
        }

        let arrStaff = $("#staff").val();

        $.ajax({
            url: laroute.route("customer-lead.submit-assign"),
            method: "POST",
            dataType: "JSON",
            data: {
                arrStaff: arrStaff,
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success");
                    // $('#autotable').PioTable('refresh');
                    window.location.href = laroute.route("customer-lead");
                } else {
                    swal(res.message, "", "error");
                }
            },
        });
    },
};

var idClick = "";

var kanBanView = {
    _init: function () {
        $(document).ready(function () {
            $("#pipeline_id").select2({
                placeholder: listLead.jsonLang["Chọn pipeline"],
            });

            $("#customer_type_filter").select2({
                placeholder: listLead.jsonLang["Chọn loại khách hàng"],
            });

            var arrRange = {};
            arrRange[listLead.jsonLang["Hôm nay"]] = [moment(), moment()];
            arrRange[listLead.jsonLang["Hôm qua"]] = [
                moment().subtract(1, "days"),
                moment().subtract(1, "days"),
            ];
            arrRange[listLead.jsonLang["7 ngày trước"]] = [
                moment().subtract(6, "days"),
                moment(),
            ];
            arrRange[listLead.jsonLang["30 ngày trước"]] = [
                moment().subtract(29, "days"),
                moment(),
            ];
            arrRange[listLead.jsonLang["Trong tháng"]] = [
                moment().startOf("month"),
                moment().endOf("month"),
            ];
            arrRange[listLead.jsonLang["Tháng trước"]] = [
                moment().subtract(1, "month").startOf("month"),
                moment().subtract(1, "month").endOf("month"),
            ];
            $("#created_at")
                .daterangepicker({
                    autoUpdateInput: false,
                    autoApply: true,
                    buttonClasses: "m-btn btn",
                    applyClass: "btn-primary",
                    cancelClass: "btn-danger",
                    maxDate: moment().endOf("day"),
                    startDate: moment().subtract(29, "days"),
                    endDate: moment(),
                    locale: {
                        format: "DD/MM/YYYY",
                        applyLabel: listLead.jsonLang["Đồng ý"],
                        cancelLabel: listLead.jsonLang["Thoát"],
                        customRangeLabel: listLead.jsonLang["Tùy chọn ngày"],
                        daysOfWeek: [
                            listLead.jsonLang["CN"],
                            listLead.jsonLang["T2"],
                            listLead.jsonLang["T3"],
                            listLead.jsonLang["T4"],
                            listLead.jsonLang["T5"],
                            listLead.jsonLang["T6"],
                            listLead.jsonLang["T7"],
                        ],
                        monthNames: [
                            listLead.jsonLang["Tháng 1 năm"],
                            listLead.jsonLang["Tháng 2 năm"],
                            listLead.jsonLang["Tháng 3 năm"],
                            listLead.jsonLang["Tháng 4 năm"],
                            listLead.jsonLang["Tháng 5 năm"],
                            listLead.jsonLang["Tháng 6 năm"],
                            listLead.jsonLang["Tháng 7 năm"],
                            listLead.jsonLang["Tháng 8 năm"],
                            listLead.jsonLang["Tháng 9 năm"],
                            listLead.jsonLang["Tháng 10 năm"],
                            listLead.jsonLang["Tháng 11 năm"],
                            listLead.jsonLang["Tháng 12 năm"],
                        ],
                        firstDay: 1,
                    },
                    ranges: arrRange,
                })
                .on("apply.daterangepicker", function (ev, picker) {
                    $(this).val(
                        picker.startDate.format("DD/MM/YYYY") +
                        " - " +
                        picker.endDate.format("DD/MM/YYYY")
                    );
                });
            $("#created_at").val(
                moment().subtract(29, "days").format("DD/MM/YYYY") +
                " - " +
                moment().format("DD/MM/YYYY")
            );
        });
    },
    loadKanban: function () {
        $.ajax({
            url: laroute.route("customer-lead.load-kan-ban-view"),
            method: "POST",
            dataType: "JSON",
            data: {
                pipeline_id: $("#pipeline_id").val(),
                customer_type: $("#customer_type_filter").val(),
                search: $("#search").val(),
                select_manage_type_work_id: $("#select_manage_type_work_id").val(),
                dataField: $("#dataField").val(),
                search_manage_type_work_id: $("#search_manage_type_work_id").val(),
                created_at: $("#created_at").val(),
                page: 1,
            },
            success: function (res) {
                if (res.error == false) {
                    var columns = [];
                    var loadDataSource = [];
                    var loadDataResourceSource = [];
                    var listTotalWork = res.listTotalWork;
                    $.map(res.journey, function (val) {
                        columns.push({
                            text: val.journey_name,
                            dataField: val.journey_code,
                        });
                    });
                    $.map(res.customerLead, function (val) {
                        var hex = "";
                        if (val.default_system == "new") {
                            hex = "#34bfa3";
                        } else if (val.default_system == "fail") {
                            hex = "#f4516c";
                        } else if (val.default_system == "win") {
                            hex = "#5867dd";
                        } else {
                            hex = "#36a3f7";
                        }
                        var fullName = "";
                        var phone = "";
                        var email = "";
                        var sale_name = "";
                        var tag = "";
                        if (val.full_name != null) {
                            fullName = val.full_name;
                        }
                        if (val.phone != null) {
                            phone = val.phone;
                        }
                        if (val.email != null) {
                            email = val.email;
                        }
                        if (val.sale_name != null) {
                            sale_name =
                                listLead.jsonLang["Người được phân bổ"] + ": " + val.sale_name;
                        }
                        if (val.total_work > 0) {
                            var tag =
                                "<span class='badge badge_la-gratipay badge-fix badge-light float-right color-red-fix'>" +
                                val.total_work +
                                "</span><i class='la la-gratipay'></i>, <i class='la la-edit'></i>, <i class='la la-trash'></i>, <i class='la la-eye'></i>";
                        } else {
                            var tag =
                                "<i class='la la-gratipay'></i>, <i class='la la-edit'></i>, <i class='la la-trash'></i>, <i class='la la-eye'></i>";
                        }
                        if (res.isCall == 1) {
                            tag = tag + ", <i class='la la-phone'></i>";
                        }
                        var item = {
                            id: val.customer_lead_id,
                            state: val.journey_code,
                            label:
                                fullName +
                                "<br/><br/>" +
                                phone +
                                "<br/>" +
                                email +
                                "<br/>" +
                                sale_name,
                            tags: tag,
                            hex: hex,
                            resourceId: val.customer_lead_id,
                        };
                        loadDataSource.push(item);
                        loadDataResourceSource.push({
                            id: val.customer_lead_id,
                            name: val.full_name,
                            image: val.avatar,
                            total_work: val.total_work,
                        });
                    });

                    if (
                        loadDataSource.length == 0 &&
                        loadDataResourceSource.length == 0
                    ) {
                        loadDataSource.push({
                            id: 0,
                            state: "",
                            name: "",
                            tags: "<i class='la la-gratipay'></i>, <i class='la la-edit'></i>, <i class='la la-trash'></i>",
                            hex: "",
                            resourceId: "",
                        });

                        loadDataResourceSource.push({
                            id: 0,
                            name: "asd",
                            image: "",
                        });
                    }

                    kanBanView.loadView(
                        columns,
                        loadDataSource,
                        loadDataResourceSource,
                        listTotalWork
                    );
                    // $(".jqx-kanban-column-container").scroll(function(){
                    //     // Nếu đang gửi ajax thì ngưng
                    //     if (listLead.is_busy == true){
                    //         return false;
                    //     }
                    //     // Nếu hết dữ liệu thì ngưng
                    //     if (listLead.stopped == true){
                    //         return false;
                    //     }
                    //     // Thiết lập đang gửi ajax
                    //     listLead.is_busy = true;
                    //     listLead.page++;

                    //     $.ajax({
                    //         url: laroute.route('customer-lead.load-kan-ban-view'),
                    //         method: 'POST',
                    //         dataType: 'JSON',
                    //         global: false,
                    //         data: {
                    //             pipeline_code: $('#pipeline_id').val(),
                    //             customer_type: $('#customer_type_filter').val(),
                    //             search: $('#search').val(),
                    //             select_manage_type_work_id: $('#select_manage_type_work_id').val(),
                    //             dataField: $('#dataField').val(),
                    //             search_manage_type_work_id: $('#search_manage_type_work_id').val(),
                    //             created_at: $('#created_at').val(),
                    //             page : listLead.page,
                    //         },
                    //         success: function (res) {
                    //             if (res.error == false) {
                    //                 if(res.customerLead.length > 0){
                    //                     $.map(res.customerLead, function (val) {
                    //                         var hex = '';
                    //                         if (val.default_system == 'new') {
                    //                             hex = '#34bfa3';
                    //                         } else if (val.default_system == 'fail') {
                    //                             hex = '#f4516c';
                    //                         } else if (val.default_system == 'win') {
                    //                             hex = '#5867dd';
                    //                         } else {
                    //                             hex = '#36a3f7';
                    //                         }
                    //                         var fullName = '';
                    //                         var phone = '';
                    //                         var email = '';
                    //                         var sale_name = '';
                    //                         var tag = '';
                    //                         if (val.full_name != null) {
                    //                             fullName = val.full_name;
                    //                         }

                    //                         if (val.phone != null) {
                    //                             phone = val.phone;
                    //                         }

                    //                         if (val.email != null) {
                    //                             email = val.email;
                    //                         }

                    //                         if (val.sale_name != null) {
                    //                             sale_name = 'Người được phân bổ' + ': ' + val.sale_name
                    //                         }

                    //                         if (val.total_work > 0) {
                    //                             var tag = "<span class='badge badge_la-gratipay badge-fix badge-light float-right color-red-fix'>" + val.total_work + "</span><i class='la la-gratipay'></i>, <i class='la la-edit'></i>, <i class='la la-trash'></i>, <i class='la la-eye'></i>";
                    //                         } else {
                    //                             var tag = "<i class='la la-gratipay'></i>, <i class='la la-edit'></i>, <i class='la la-trash'></i>, <i class='la la-eye'></i>";
                    //                         }

                    //                         if (res.isCall == 1) {
                    //                             tag = tag + ", <i class='la la-phone'></i>";
                    //                         }
                    //                         var newItem = {
                    //                             id: val.customer_lead_id,
                    //                             resourceId: val.customer_lead_id,
                    //                             status:  val.journey_code,
                    //                             text: fullName + "<br/><br/>" + phone + "<br/>" + email + "<br/>" + sale_name,
                    //                             tags: tag,
                    //                             color: hex
                    //                         }
                    //                         listLead.loadDataSource.push(newItem);
                    //                         listLead.loadDataResourceSource.push({
                    //                             id: val.customer_lead_id,
                    //                             name: val.full_name,
                    //                             image: val.avatar,
                    //                             total_work: val.total_work,
                    //                         });

                    //                         // $('#kanban').jqxKanban('addItem',newItem);
                    //                     });

                    //                     kanBanView.loadView();
                    //                     listLead.isload = true;
                    //                     listLead.is_busy = false;
                    //                 }else {
                    //                     listLead.isload = false;
                    //                     listLead.is_busy = true;
                    //                 }

                    //             }
                    //         }
                    //     });
                    //     if($(".jqx-kanban-column-container").scrollTop() + $(".jqx-kanban-column-container").height() >= $(".jqx-kanban-column-container").height()) {

                    //     }
                    // });
                    // console.log("#kanban-column-container-" + index);
                    // index = index+1;
                    //
                    // kanBanView.loadMoreKanBanView(res.lastPage);
                }
            },
        });
    },
    loadMoreKanBanView: function (lastPage) {
        var page = 2;
        var isRun = true;
        do {
            isRun = kanBanView.loadKanBanViewPage(page);
            page++;
        } while (page <= lastPage);
    },
    loadKanBanViewPage: function (page) {
        $.ajax({
            url: laroute.route("customer-lead.load-kan-ban-view"),
            method: "POST",
            dataType: "JSON",
            global: false,
            data: {
                pipeline_code: $("#pipeline_id").val(),
                customer_type: $("#customer_type_filter").val(),
                search: $("#search").val(),
                select_manage_type_work_id: $("#select_manage_type_work_id").val(),
                dataField: $("#dataField").val(),
                search_manage_type_work_id: $("#search_manage_type_work_id").val(),
                created_at: $("#created_at").val(),
                page: page,
            },
            success: function (res) {
                if (res.error == false) {
                    if (res.customerLead.length > 0) {
                        $.map(res.customerLead, function (val) {
                            var hex = "";
                            if (val.default_system == "new") {
                                hex = "#34bfa3";
                            } else if (val.default_system == "fail") {
                                hex = "#f4516c";
                            } else if (val.default_system == "win") {
                                hex = "#5867dd";
                            } else {
                                hex = "#36a3f7";
                            }
                            var fullName = "";
                            var phone = "";
                            var email = "";
                            var sale_name = "";
                            var tag = "";
                            if (val.full_name != null) {
                                fullName = val.full_name;
                            }

                            if (val.phone != null) {
                                phone = val.phone;
                            }

                            if (val.email != null) {
                                email = val.email;
                            }

                            if (val.sale_name != null) {
                                sale_name = "Người được phân bổ" + ": " + val.sale_name;
                            }

                            if (val.total_work > 0) {
                                var tag =
                                    "<span class='badge badge_la-gratipay badge-fix badge-light float-right color-red-fix'>" +
                                    val.total_work +
                                    "</span><i class='la la-gratipay'></i>, <i class='la la-edit'></i>, <i class='la la-trash'></i>, <i class='la la-eye'></i>";
                            } else {
                                var tag =
                                    "<i class='la la-gratipay'></i>, <i class='la la-edit'></i>, <i class='la la-trash'></i>, <i class='la la-eye'></i>";
                            }

                            if (res.isCall == 1) {
                                tag = tag + ", <i class='la la-phone'></i>";
                            }
                            var newItem = {
                                id: val.customer_lead_id,
                                resourceId: val.customer_lead_id,
                                status: val.journey_code,
                                text:
                                    fullName +
                                    "<br/><br/>" +
                                    phone +
                                    "<br/>" +
                                    email +
                                    "<br/>" +
                                    sale_name,
                                tags: tag,
                                color: hex,
                            };
                            $("#kanban").jqxKanban("addItem", newItem);
                        });
                        return true;
                    } else {
                        return false;
                    }
                }
            },
        });
    },
    loadView: function (
        columns,
        loadDataSource,
        loadDataResourceSource,
        listTotalWork
    ) {
        console.log(loadDataSource);
        var fields = [
            {name: "id", type: "string"},
            {name: "status", map: "state", type: "string"},
            {name: "text", map: "label", type: "string"},
            {name: "tags", type: "string"},
            {name: "color", map: "hex", type: "string"},
            {name: "resourceId", type: "number"},
        ];
        var source = {
            localData: loadDataSource,
            dataType: "array",
            dataFields: fields,
        };

        var dataAdapter = new $.jqx.dataAdapter(source);

        var resourcesAdapterFunc = function () {
            var resourcesSource = {
                localData: loadDataResourceSource,
                dataType: "array",
                dataFields: [
                    {name: "id", type: "number"},
                    {name: "name", type: "string"},
                    {name: "image", type: "string"},
                    {name: "common", type: "boolean"},
                ],
            };
            var resourcesDataAdapter = new $.jqx.dataAdapter(resourcesSource);
            return resourcesDataAdapter;
        };

        $("#kanban").jqxKanban({
            width: "100%",
            height: "100%",
            rtl: true,
            resources: resourcesAdapterFunc(),
            source: dataAdapter,
            columns: columns,

            columnRenderer: function (element, collapsedElement, column) {
                var columnItems = $("#kanban").jqxKanban(
                    "getColumnItems",
                    column.dataField
                ).length;
                // update header's status.
                element
                    .find(".jqx-kanban-column-header-status")
                    .html(" (" + columnItems + ")");

                // update collapsed header's status.
                collapsedElement
                    .find(".jqx-kanban-column-header-status")
                    .html(" (" + columnItems + ")");
                element
                    .find(".img-fluid.icon-header-kanban")
                    .parent("div")
                    .parent("span")
                    .remove();
                element.children("br").remove();
                var html = "";
                element
                    .parent()
                    .closest(".jqx-kanban-column")
                    .addClass("jqx-kanban-column-show");
                // $.map(listLead.listTotalWork[column.dataField], function (val) {
                //     if (typeof val.total_work !== "undefined" && val.total_work != '0'){
                //         if (column.dataField == $('#dataField').val() && val.manage_type_work_id == $('#search_manage_type_work_id').val()){
                //             html += ("<span class='jqx-kanban-column-header-icon jqx-kanban-column-header-status-light'><div class='float-right img-fluid mr-4 position-relative base-div background-img "+val.manage_type_work_key+"'><img class='img-fluid icon-header-kanban "+val.manage_type_work_key+"' src='"+val.manage_type_work_icon+"' data-field='"+column.dataField+"' data-type-work-id='"+val.manage_type_work_id+"'><span class='badge badge-fix badge-light color-red-fix' style='background:transparent'>"+(val.total_work)+"</span></div></span>");
                //         } else {
                //             html += ("<span class='jqx-kanban-column-header-icon jqx-kanban-column-header-status-light'><div class='float-right img-fluid mr-4 position-relative base-div "+val.manage_type_work_key+"'><img class='img-fluid icon-header-kanban "+val.manage_type_work_key+"' src='"+val.manage_type_work_icon+"' data-field='"+column.dataField+"' data-type-work-id='"+val.manage_type_work_id+"'><span class='badge badge-fix badge-light color-red-fix' style='background:transparent'>"+(val.total_work)+"</span></div></span>");
                //         }
                //     } else {
                //         if (column.dataField == $('#dataField').val() && val.manage_type_work_id == $('#search_manage_type_work_id').val()){
                //             html += ("<span class='jqx-kanban-column-header-icon jqx-kanban-column-header-status-light'><div class='float-right img-fluid mr-4 position-relative base-div background-img "+val.manage_type_work_key+"'><img class='img-fluid icon-header-kanban "+val.manage_type_work_key+"' src='"+val.manage_type_work_icon+"' data-field='"+column.dataField+"' data-type-work-id='"+val.manage_type_work_id+"'><span class='badge badge-fix badge-light color-red-fix' style='background:transparent'></span></div></span>");
                //         } else {
                //             html += ("<span class='jqx-kanban-column-header-icon jqx-kanban-column-header-status-light'><div class='float-right img-fluid mr-4 position-relative base-div "+val.manage_type_work_key+"'><img class='img-fluid icon-header-kanban "+val.manage_type_work_key+"' src='"+val.manage_type_work_icon+"' data-field='"+column.dataField+"' data-type-work-id='"+val.manage_type_work_id+"'><span class='badge badge-fix badge-light color-red-fix' style='background:transparent'></span></div></span>");
                //         }
                //     }
                // });
                // element.prepend(html + '<br>');
            },
            template:
                "<div class='jqx-kanban-item' id=''>" +
                "<div class='jqx-kanban-item-color-status'></div>" +
                "<div style='display: block;' class='jqx-kanban-item-avatar'></div>" +
                "<div class='jqx-icon jqx-icon-close-white jqx-kanban-item-template-content jqx-kanban-template-icon'></div>" +
                "<div class='jqx-kanban-item-text'></div>" +
                "<div style='display: block;' class='jqx-kanban-item-footer'></div>" +
                "</div>",
        });

        // custom kanbanview by nhandt 13/12/2021
        // off event click, itemMoved, itemAttrClicked of #kanban
        // because when loadKanban, this function will make 3 events click, itemMoved, itemAttrClicked
        $("#kanban").off("itemMoved");
        $("#kanban").off("itemAttrClicked");
        $("#kanban").off("click");
        // end custom 13/12/2021

        //Event kéo thả
        $("#kanban").on("itemMoved", function (event) {
            var args = event.args;
            var itemId = args.itemId;
            var oldParentId = args.oldParentId;
            var newParentId = args.newParentId;
            var itemData = args.itemData;
            var journey_old = args.oldColumn.dataField;
            var journey_new = args.newColumn.dataField;
            console.log(journey_old);
            console.log(journey_new);
            $.ajax({
                url: laroute.route("customer-lead.update-journey"),
                method: "POST",
                dataType: "JSON",
                data: {
                    customer_lead_id: itemId,
                    journey_old: journey_old,
                    journey_new: journey_new,
                    pipeline_id: $("#pipeline_id").val(),
                },
                success: function (res) {
                    if (res.error == false) {
                        if (res.create_deal == 1) {
                            swal(res.message, "", "success").then(function (result) {
                                if (
                                    result.dismiss == "esc" ||
                                    result.dismiss == "backdrop" ||
                                    result.dismiss == "overlay"
                                ) {
                                    edit.showConfirmCreateDeal(res.lead_id);
                                }
                                if (result.value == true) {
                                    edit.showConfirmCreateDeal(res.lead_id);
                                }
                            });
                        }

                        setTimeout(function () {
                            toastr.success(res.message);
                        }, 60);
                    } else {
                        setTimeout(function () {
                            toastr.error(res.message);
                        }, 60);

                        $("#kanban").remove();
                        $(".parent_kanban").append('<div id="kanban"></div>');
                        kanBanView.loadKanban();
                    }
                },
            });
        });

        //Lấy id button được nhấp
        $("#kanban").on("itemAttrClicked", function (event1) {
            var args = event1.args;
            var itemId = args.itemId;
            var attribute = args.attribute; // template, colorStatus, content, keyword, text, avatar

            idClick = itemId;
        });

        //Event click
        $("#kanban").click(function (event) {
            $.each(
                $("#kanban").find(
                    ".jqx-kanban-column .jqx-kanban-column-header-collapsed"
                ),
                function () {
                    // let moneyEachMethod = $(this).find("input[name='payment_method']").val().replace(new RegExp('\\,', 'g'), '');

                    if (
                        $(this).hasClass("jqx-kanban-column-header-collapsed-show-light")
                    ) {
                        $(this)
                            .parent()
                            .closest(".jqx-kanban-column")
                            .removeClass("jqx-kanban-column-show");
                        $(this)
                            .parent()
                            .closest(".jqx-kanban-column")
                            .addClass("jqx-kanban-column-hidden");
                    } else {
                        $(this)
                            .parent()
                            .closest(".jqx-kanban-column")
                            .removeClass("jqx-kanban-column-hidden");
                        $(this)
                            .parent()
                            .closest(".jqx-kanban-column")
                            .addClass("jqx-kanban-column-show");
                    }
                }
            );
            //Get button nào được nhấp
            // event.target.innerText
            if (
                event.target.className == "la la-gratipay" ||
                event.target.className ==
                "badge badge_la-gratipay badge-fix badge-light float-right color-red-fix"
            ) {
                listLead.popupCustomerCare(idClick);
            } else if (event.target.className == "la la-edit") {
                edit.popupEdit(idClick, true);
            } else if (event.target.className == "la la-trash") {
                listLead.remove(idClick, true);
            } else if (event.target.className == "la la-eye") {
                listLead.detail(idClick);
            } else if (event.target.className == "la la-phone") {
                listLead.modalCall(idClick);
            } else if (
                event.target.className == "img-fluid icon-header-kanban call" ||
                event.target.className == "img-fluid icon-header-kanban email" ||
                event.target.className == "img-fluid icon-header-kanban message" ||
                event.target.className == "img-fluid icon-header-kanban meeting" ||
                event.target.className == "img-fluid icon-header-kanban other"
            ) {
                if (typeof event.target.dataset.field !== "undefined") {
                    var dataField = $("#dataField").val();
                    var search_manage_type_work_id = $(
                        "#search_manage_type_work_id"
                    ).val();
                    if (
                        dataField == event.target.dataset.field &&
                        search_manage_type_work_id == event.target.dataset.typeWorkId
                    ) {
                        $("#dataField").val("");
                        $("#search_manage_type_work_id").val("");
                    } else {
                        $("#dataField").val(event.target.dataset.field);
                        $("#search_manage_type_work_id").val(
                            event.target.dataset.typeWorkId
                        );
                    }

                    $("#select_manage_type_work_id").val("").trigger("change");

                    // kanBanView.loadKanban();
                }
            }
        });
    },
    changePipeline: function () {
        if ($("#select_manage_type_work_id").val() != "") {
            $("#dataField").val("");
            $("#search_manage_type_work_id").val("");
        }

        $("#kanban").remove();
        $(".parent_kanban").append('<div id="kanban"></div>');

        kanBanView.loadKanban();
    },
    closeModalDeal: function () {
        $("#modal-detail").modal("hide");
        window.location.reload();
    },
};

function uploadAvatar(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        var imageAvatar = $("#image");
        reader.onload = function (e) {
            $("#blah").attr("src", e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
        var file_data = $("#getFile").prop("files")[0];
        var form_data = new FormData();
        form_data.append("file", file_data);
        form_data.append("link", "_customer-lead.");
        var fsize = input.files[0].size;
        var fileInput = input,
            file = fileInput.files && fileInput.files[0];
        var img = new Image();

        img.src = window.URL.createObjectURL(file);

        img.onload = function () {
            var imageWidth = img.naturalWidth;
            var imageHeight = img.naturalHeight;

            window.URL.revokeObjectURL(img.src);

            $(".image-size").text(imageWidth + "x" + imageHeight + "px");
        };
        $(".image-capacity").text(Math.round(fsize / 1024) + "kb");

        $(".image-format").text(input.files[0].name.split(".").pop().toUpperCase());

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
                        $("#avatar").val(res.file);
                    }
                },
            });
        } else {
            swal("Hình ảnh vượt quá dung lượng cho phép", "", "error");
        }
    }
}

jQuery.fn.ForceNumericOnly = function () {
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
                (key >= 96 && key <= 105)
            );
        });
    });
};

function isValidEmailAddress(emailAddress) {
    var pattern =
        /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
    return pattern.test(emailAddress);
}

function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
}

uploadImgCk = function (file, parent_comment = null) {
    let out = new FormData();
    out.append("file", file, file.name);

    $.ajax({
        method: "POST",
        url: laroute.route("customer-lead.upload-file"),
        contentType: false,
        cache: false,
        processData: false,
        data: out,
        success: function (img) {
            if (parent_comment != null) {
                $(".summernote").summernote("insertImage", img["file"]);
            } else {
                $(".summernote").summernote("insertImage", img["file"]);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error(textStatus + " " + errorThrown);
        },
    });
};
