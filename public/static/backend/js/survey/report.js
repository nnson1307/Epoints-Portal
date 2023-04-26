var survey = {
    init: function () {
        var jsonLang = JSON.parse(localStorage.getItem('tranlate'));
        $(document).ajaxStart(function () {
            $.ajaxSetup({
                async: false,
            });
            $('#fade').hide();
        });
        survey.charReportSignleChoice();
        survey.charReportMutipleChoice();
        $('.ss-select2').select2();
        $.getJSON(laroute.route('admin.validation'), function (json) {
            $("#created_at").daterangepicker({
                autoUpdateInput: false,
                //autoApply: false,
                buttonClasses: "m-btn btn",
                applyClass: "btn-primary",
                cancelClass: "btn-danger",
                locale: {
                    format: 'DD/MM/YYYY',
                    daysOfWeek: [
                        json.content.CN,
                        json.content.T2,
                        json.content.T3,
                        json.content.T4,
                        json.content.T5,
                        json.content.T6,
                        json.content.T7
                    ],
                    "monthNames": [
                        json.content.month_1,
                        json.content.month_2,
                        json.content.month_3,
                        json.content.month_4,
                        json.content.month_5,
                        json.content.month_6,
                        json.content.month_7,
                        json.content.month_8,
                        json.content.month_9,
                        json.content.month_10,
                        json.content.month_11,
                        json.content.month_12
                    ],
                    "firstDay": 1,
                    "applyLabel": json.content.confirm,
                    "cancelLabel": json.content.exit,
                }
            });
            $('#created_at').on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
            });
            $('#created_at').on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
            });
        });
        $('#created_at').val('');
        $('#province_id').select2({
            placeholder: jsonLang["Chọn tỉnh/thành"],
        });
        $('#ward_id').select2({
            placeholder: jsonLang["Chọn phường/xã"],
        });
        $('#district_id').select2({
            placeholder: jsonLang["Chọn quận/huyện"],
        });
    },

    getDistrict: function () {
        var id = $('#province_id').val();
        $.ajax({
            url: laroute.route('admin.customer.load-district'),
            dataType: 'JSON',
            data: {
                id_province: id,
            },
            method: 'POST',
            success: function (res) {
                $('.district').empty();
                $.map(res.optionDistrict, function (a) {
                    $('.district').append('<option value="' + a.id + '">' + a.type + ' ' + a.name + '</option>');
                });
            }
        });
    },
    getWard: function () {
        var district = $("#district_id").val();
        $.ajax({
            url: laroute.route('admin.customer.load-ward'),
            dataType: 'JSON',
            data: {
                id_district: district,
            },
            method: 'POST',
            success: function (res) {
                $("#ward_id").empty();
                $.map(res.optionWard, function (a) {
                    $("#ward_id").append('<option value="' + a.id + '">' + a.type + ' ' + a.name + '</option>');
                });
            }
        });
    },


    destroy: function (id) {
        $.getJSON(laroute.route('survey.validation'), function (json) {
            swal.fire({
                title: json.title_modal_destroy,
                html: json.content_modal_destroy,
                buttonsStyling: false,

                confirmButtonText: json.btn_yes,
                confirmButtonClass: "btn btn-sm btn-default btn-bold btn_yes",

                showCancelButton: true,
                cancelButtonText: json.btn_no,
                cancelButtonClass: "btn btn-sm btn-bold btn-brand btn_cancel"
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: laroute.route('survey.destroy'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: { id: id },
                        success: function (res) {
                            swal.fire(json.remove_success, "", "success").then(function () {
                                window.location.href = laroute.route('survey.index');
                            });
                        },
                        error: function (res) {
                        }
                    });
                }
            });
        });
    },
    changeStatus: function (id, status) {
        $.getJSON(laroute.route('survey.validation'), function (json) {
            let title = '';
            let html = '';
            if (status === 'R') {
                // Duyệt
                title = json.title_modal_change_status_R;
                html = json.content_modal_change_status_R;
            } else if (status === 'C') {
                // Kết thúc
                title = json.title_modal_change_status_C;
                html = json.content_modal_change_status_C;
            } else if (status === 'D') {
                // Từ chối
                title = json.title_modal_change_status_D;
                html = json.content_modal_change_status_D;
            }
            swal.fire({
                title: title,
                html: html,
                buttonsStyling: false,
                confirmButtonText: json.btn_yes,
                confirmButtonClass: "btn btn-sm btn-default btn-bold btn_yes",
                showCancelButton: true,
                cancelButtonText: json.btn_no,
                cancelButtonClass: "btn btn-sm btn-bold btn-brand btn_cancel"
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: laroute.route('survey.change-status'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            id: id,
                            status: status
                        },
                        success: function (res) {
                            if (res.error == false) {
                                location.reload();
                            } else {
                                var mess_error = '';
                                $.map(res.array_error, function (a) {
                                    mess_error = mess_error.concat(a + '<br/>');
                                });
                                swal.fire(json.tb_errors, mess_error, "error");
                            }
                        },
                        error: function (res) {
                        }
                    });
                }
            });
        });
    },

    loadListReportSurvey: function (page = 1) {
        let codeCustomerOrStaff = $("input[name='code_customer_or_staff']").val();
        let nameCustomerOrStaff = $("input[name='name_customer_or_staff']").val();
        let dateCreatedCustomer = $("#created_at_customer").val() ?? "";
        let dateCreatedStaff = $("#created_at_staff").val() ?? "";
        let province = $("#province_id option:selected").val() ?? "";
        let district = $("#district_id option:selected").val() ?? "";
        let ward = $("#ward_id option:selected").val() ?? "";
        let idSurvey = $('#id_survey').val();
        let address = $("input[name='address_staff").val() ?? "";
        let perpage = $('#perpage option:selected').val();
        $.ajax({
            url: laroute.route('survey.loadAllReport'),
            method: "POST",
            data: {
                codeCustomerOrStaff: codeCustomerOrStaff,
                nameCustomerOrStaff: nameCustomerOrStaff,
                dateCreatedCustomer: dateCreatedCustomer,
                dateCreatedStaff: dateCreatedStaff,
                province: province,
                district: district,
                ward: ward,
                page: page,
                perpage: perpage,
                idSurvey: idSurvey,
                address: address
            },
            success: function (res) {
                $('.table-content').html(res.view);
                $('.selectpicker').selectpicker('show');

            }
        });
    },
    resetSearchReportSurvey: function () {
        var jsonLang = JSON.parse(localStorage.getItem('tranlate'));
        $("input[name='code_customer_or_staff']").val('');
        $("input[name='name_customer_or_staff']").val('');
        $("input[name='created_at']").val('');
        $("#province_id").val('');
        $("#district_id").val('');
        $("#ward_id").val('');
        $("input[name='address_staff").val('');
        $("#created_at_customer").val('');
        $("#created_at_staff").val('');
        $("#province_id").select2({
            placeholder: jsonLang["Chọn tỉnh/thành"],
        });
        $("#district_id").select2({
            placeholder: jsonLang["Chọn quận/huyện"],
        });
        $("#ward_id").select2({
            placeholder: jsonLang["Chọn phường/xã"],
        });
        survey.loadListReportSurvey();
    },
    showReportDetailSurvey: function (page = 1) {
        $.ajax({
            url: laroute.route('survey.report.load-item-detail'),
            method: "POST",
            data: {
                page: page,
                survey_id: SURVEY_ID
            },
            success: function (res) {
                $('#report_answer_question_detail').html(res.view);
            }
        });
    },
    showModalExport: function () {
        $("#export_report").modal('show');
    },
    exportExcel: function (idSurvey) {
        $("#export_report").modal('hide');
        const url = laroute.route("survey.export-report", { idSurvey: idSurvey });
        window.location = url;

    },
    charReportSignleChoice: function () {

        let dataChart = Object.entries(DATA_CHART_SIGNCHOICE);
        dataChart.forEach((data, index) => {
            var keyBlock = data[0].replace(/\s/g, '');
            var listDataChart = data[1];

            listDataChart.forEach((data) => {
                itemChart = Object.entries(data);

                itemChart.forEach((item, index) => {
                    var indexChart = Number(item[0]) + 1;
                    var elementChart = `chartExportSingle${keyBlock}${indexChart}`;
                    Highcharts.chart(elementChart, {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Hình thức trả lời: Chỉ chọn được 1 đáp án'
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                        },
                        accessibility: {
                            point: {
                                valueSuffix: '%'
                            }
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: false
                                },
                                showInLegend: true
                            }
                        },
                        series: [{
                            name: 'Survey',
                            colorByPoint: true,
                            data: itemChart[index][1]
                        }]
                    });
                })

            })
        })
    },
    charReportMutipleChoice: function () {
        let dataChart = Object.entries(DATA_CHART_MUTIPLECHOICE);
        dataChart.forEach((data, index) => {
            var keyBlock = data[0].replace(/\s/g, '');
            var listDataChart = data[1];
            listDataChart.forEach((data) => {
                itemChart = Object.entries(data);
                itemChart.forEach((item, index) => {
                    var indexChart = Number(item[0]) + 1;
                    var elementChart = `chartExportMutiple${keyBlock}${indexChart}`;
                    Highcharts.chart(elementChart, {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Hình thức trả lời: Chọn được nhiều  đáp án'
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                        },
                        accessibility: {
                            point: {
                                valueSuffix: '%'
                            }
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: false
                                },
                                showInLegend: true
                            }
                        },
                        series: [{
                            name: 'Survey',
                            colorByPoint: true,
                            data: itemChart[index][1]
                        }]
                    });
                })

            })
        })
    }
};
survey.init();


