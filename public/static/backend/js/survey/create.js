var survey = {
    init: function () {
        $(document).ready(function () {
            $('#start_date').datetimepicker({
                todayHighlight: true,
                autoclose: true,
                format: 'hh:ii:00 dd/mm/yyyy',
                startDate: '+0d',
                minDate: new Date()
            });
            $('#end_date').datetimepicker({
                todayHighlight: true,
                autoclose: true,
                format: 'hh:ii:00 dd/mm/yyyy',
                startDate: '+0d',
                minDate: new Date()
            });
            $('#exec_time_from').timepicker({
                minuteStep: 1,
                defaultTime: '',
                showSeconds: true,
                showMeridian: false,
                snapToStep: true
            });
            $('#exec_time_to').timepicker({
                minuteStep: 1,
                defaultTime: '',
                showSeconds: true,
                showMeridian: false,
                snapToStep: true
            });
            // Thời gian hiệu lực
            $('input[name=is_exec_time]').change(function () {
                $('#start_date').val('');
                $('#end_date').val('');
                $('#close_date').val('');
                if ($('input[name=is_exec_time]:checked').val() == '0') {
                    $('#start_date').prop("disabled", true);
                    $('#end_date').prop("disabled", true);
                } else {
                    $('#start_date').prop("disabled", false);
                    $('#end_date').prop("disabled", false);
                }
            });

            $('.frequency_weekly').hide();
            $('.frequency_monthly').hide();
            // Tần suất khảo sát
            $('.frequency').change(function () {
                if (this.value == 'daily') {
                    $('.frequency_weekly').hide();
                    $('.frequency_monthly').hide();
                    // Hàng tuần
                    $('.frequency_value_weekly').prop("disabled", true);
                    $('.frequency_value_weekly').prop("checked", false);

                    // Hàng tháng
                    // Tháng
                    $('.frequency_value_monthly').prop("disabled", true);
                    $('.frequency_value_monthly').prop("checked", false);

                    // Tick ngày trong tháng + Ngày trong tuần
                    $('.frequency_monthly_type').prop("disabled", true);
                    $('.frequency_monthly_type').prop("checked", false);
                    // Ngày
                    $('.day_in_monthly').prop("disabled", true);
                    $('.day_in_monthly').prop("checked", false);
                    // Lặp lại vào tuần
                    $('.day_in_week').prop("disabled", true);
                    $('.day_in_week').prop("checked", false);
                    // Lặp lại vào thứ
                    $('.day_in_week_repeat').prop("disabled", true);
                    $('.day_in_week_repeat').prop("checked", false);
                    // Thời gian thực hiện trong ngày
                    // Thời gian thực hiện trong ngày
                } else if (this.value == 'weekly') {
                    $('.frequency_monthly').hide();
                    $('.frequency_weekly').show();
                    // Hàng tuần
                    $('.frequency_value_weekly').prop("disabled", false);
                    // Hàng tháng
                    // Tháng
                    $('.frequency_value_monthly').prop("disabled", true);
                    $('.frequency_value_monthly').prop("checked", false);

                    // Tick ngày trong tháng + Ngày trong tuần
                    $('.frequency_monthly_type').prop("disabled", true);
                    $('.frequency_monthly_type').prop("checked", false);
                    // Ngày
                    $('.day_in_monthly').prop("disabled", true);
                    $('.day_in_monthly').prop("checked", false);
                    // Lặp lại vào tuần
                    $('.day_in_week').prop("disabled", true);
                    $('.day_in_week').prop("checked", false);
                    // Lặp lại vào thứ
                    $('.day_in_week_repeat').prop("disabled", true);
                    $('.day_in_week_repeat').prop("checked", false);
                    // Thời gian thực hiện trong ngày
                    // Thời gian thực hiện trong ngày
                } else if (this.value == 'monthly') {
                    $('.frequency_monthly').show();
                    $('.frequency_weekly').hide();
                    // Hàng tuần
                    $('.frequency_value_weekly').prop("disabled", true);
                    $('.frequency_value_weekly').prop("checked", false);
                    // Hàng tháng
                    // Tháng
                    $('.frequency_value_monthly').prop("disabled", false);

                    // Tick ngày trong tháng + Ngày trong tuần
                    $('.frequency_monthly_type').prop("disabled", false);
                    // Ngày
                    $('.day_in_monthly').prop("disabled", true);
                    $('.day_in_monthly').prop("checked", false);
                    // Lặp lại vào tuần
                    $('.day_in_week').prop("disabled", true);
                    $('.day_in_week').prop("checked", false);
                    // Lặp lại vào thứ
                    $('.day_in_week_repeat').prop("disabled", true);
                    $('.day_in_week_repeat').prop("checked", false);
                    // Thời gian thực hiện trong ngày
                    // Thời gian thực hiện trong ngày
                    // Ngày trong tháng + Ngày trong tuần
                    if ($('.frequency_monthly_type:checked').val() == 'day_in_month') {
                        $('.day_in_monthly').prop("disabled", false);
                        $('.day_in_week').prop("disabled", true);
                        $('.day_in_week').prop("checked", false);
                        $('.day_in_week_repeat').prop("disabled", true);
                        $('.day_in_week_repeat').prop("checked", false);
                    } else if ($('.frequency_monthly_type:checked').val() == 'day_in_week') {
                        $('.day_in_monthly').prop("disabled", true);
                        $('.day_in_monthly').prop("checked", false);
                        $('.day_in_week').prop("disabled", false);
                        $('.day_in_week_repeat').prop("disabled", false);
                    }
                }
            });

            // loại type checked hàng tuần 
            $('.weekly_type').change(function () {
                if (this.value == 'all_frequency_weekly') {
                    $('.frequency_value_weekly').prop("disabled", true);
                } else {
                    $('.frequency_value_weekly').prop("disabled", false);
                }
            })

            // Ngày trong tháng + Ngày trong tuần
            $('.frequency_monthly_type').change(function () {
                if ($('.frequency_monthly_type:checked').val() == 'day_in_month') {
                    $('.day_in_monthly').prop("disabled", false);
                    $('.day_in_week').prop("disabled", true);
                    $('.day_in_week').prop("checked", false);
                    $('.day_in_week_repeat').prop("disabled", true);
                    $('.day_in_week_repeat').prop("checked", false);
                } else if ($('.frequency_monthly_type:checked').val() == 'day_in_week') {
                    $('.day_in_monthly').prop("disabled", true);
                    $('.day_in_monthly').prop("checked", false);
                    $('.day_in_week').prop("disabled", false);
                    $('.day_in_week_repeat').prop("disabled", false);
                }
            });
            $('#end_date').change(function () {
                $('#close_date').val('');
                let endDate = $('#end_date').val();
                if ($('input[name=is_exec_time]:checked').val() == 1 && endDate) {
                    $.ajax({
                        url: laroute.route("survey.format-close-date"),
                        method: "POST",
                        data: { date: endDate },
                        success: function (res) {
                            $('#close_date').val(res.date);
                        }
                    });
                }
            });
            $('input[name=is_limit_exec_time]').change(function () {
                $('#exec_time_from').val('');
                $('#exec_time_to').val('');
                if ($('input[name=is_limit_exec_time]:checked').val() == '0') {
                    $('#exec_time_from').prop("disabled", true);
                    $('#exec_time_to').prop("disabled", true);
                } else {
                    $('#exec_time_from').prop("disabled", false);
                    $('#exec_time_to').prop("disabled", false);
                }
            });
            $('input[name=config_turn]').change(function () {
                $('#max_times').val('');
                if ($('input[name=config_turn]:checked').val() == '0') {
                    $('#max_times').prop("disabled", true);
                } else {
                    $('#max_times').prop("disabled", false);
                }
            });
            $('.numeric').mask('00000000000', { reverse: true });
        });
    },
    upload: function (input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                // $('.kt-avatar__holder')
                //     .css('background-image', e.target.result);
                $('#image-survey-banner').empty();
                var tpl = $('#image-tpl').html();
                tpl = tpl.replace(/{link}/g, e.target.result);
                $('#image-survey-banner').append(tpl);

            };
            reader.readAsDataURL(input.files[0]);
            var file_data = $('#getFileSurveyBanner').prop('files')[0];
            var form_data = new FormData();
            form_data.append('file', file_data);
            form_data.append('link', '_survey.');
            $.ajax({
                url: laroute.route("admin.upload-image"),
                method: "POST",
                data: form_data,
                contentType: false,
                cache: false,
                processData: false,
                success: function (res) {
                    $('#survey_banner').val(res.file);
                }
            });
        }
    },
    store: function () {
        $.getJSON(laroute.route('survey.validation'), function (json) {
            var form = $('#form-data');
            form.validate({
                rules: {
                    survey_name: {
                        required: true,
                        maxlength: 255,
                    },
                    survey_code: {
                        required: true,
                        maxlength: 100,
                    },
                    survey_description: {
                        maxlength: 400,
                    },
                },
                messages: {
                    survey_name: {
                        required: json.survey_name_required,
                        maxlength: json.maxlength_255,
                    },
                    survey_code: {
                        required: json.survey_code_required,
                        maxlength: json.maxlength_100,
                    },
                    survey_description: {
                        maxlength: json.maxlength_400,
                    },
                },
            });
            if (!form.valid()) {
                return false;
            }
            var data = {};
            data['survey_name'] = $('#survey_name').val();
            data['survey_code'] = $('#survey_code').val();
            data['survey_description'] = $('#survey_description').val();
            data['survey_banner'] = $('#survey_banner').val();
            data['game_code'] = $('#game_code').val();
            data['game_name'] = $('#game_name').val();
            data['cate_id'] = $('.cate_id').val();
            data['period_type'] = $('.period_type:checked').val();
            data['frequency'] = $('.frequency:checked').val();
            data['start_date'] = $('#start_date').val();
            data['end_date'] = $('#end_date').val();
            data['frequency_value_weekly'] = $('.frequency_value_weekly:checked').map(function () {
                return this.value;
            }).get().join(',');
            if ($('.weekly_type:checked').val() == 'all_frequency_weekly') {
                data['frequency_value_weekly'] = $('.frequency_value_weekly').map(function () {
                    return this.value;
                }).get().join(',');
            }
            data['frequency_value_monthly'] = $('.frequency_value_monthly:checked').map(function () {
                return this.value;
            }).get().join(',');
            if ($('.frequency_monthly_type:checked').val() === undefined) {
                data['frequency_monthly_type'] = null;
            } else {
                data['frequency_monthly_type'] = $('.frequency_monthly_type:checked').val();
            }
            data['day_in_monthly'] = $('.day_in_monthly:checked').map(function () {
                return this.value;
            }).get().join(',');
            data['day_in_week'] = $('.day_in_week:checked').map(function () {
                return this.value;
            }).get().join(',');
            data['day_in_week_repeat'] = $('.day_in_week_repeat:checked').map(function () {
                return this.value;
            }).get().join(',');

            if ($('.period_in_date_type:checked').val() === undefined) {
                data['period_in_date_type'] = null;
            } else {
                data['period_in_date_type'] = $('.period_in_date_type:checked').val();
            }
            data['period_in_date_end'] = $('#period_in_date_end').val();
            data['config_turn'] = $('.config_turn:checked').val();
            data['is_limit_exec_time'] = $('input[name=is_limit_exec_time]:checked').val();
            data['is_exec_time'] = $('input[name=is_exec_time]:checked').val();
            data['exec_time_from'] = $('#exec_time_from').val();
            data['exec_time_to'] = $('#exec_time_to').val();
            data['max_times'] = $('#max_times').val();
            let publicLink = 0;
            if ($('#public_link').is(':checked')) {
                publicLink = 1;
            }
            let countPoint = 0;
            if ($('#count_point').is(':checked')) {
                countPoint = 1;
            }
            console.log(
                {
                    countPoint,
                    publicLink
                }
            );
            data['public_link'] = publicLink;
            data['count_point'] = countPoint;
            
            var data = JSON.parse(JSON.stringify(data));
            $.ajax({
                url: laroute.route('survey.store'),
                method: 'POST',
                dataType: 'JSON',
                data: data,
                success: function (res) {
                    if (res.error == false) {
                        swal.fire(json.add_success, "", "success").then(function () {
                            window.location.href = laroute.route('survey.show', { id: res.id });
                        });
                    } else {
                        var mess_error = '';
                        $.map(res.array_error, function (a) {
                            mess_error = mess_error.concat(a + '<br/>');
                        });
                        swal.fire(json.add_fail, mess_error, "error");
                    }
                },
                error: function (res) {
                    if (res.responseJSON != undefined) {
                        var mess_error = '';
                        $.map(res.responseJSON.errors, function (a) {
                            mess_error = mess_error.concat(a + '<br/>');
                        });
                        swal.fire(json.add_fail, mess_error, "error");
                    }
                }
            });
        });
    },
    back: function () {
        $.getJSON(laroute.route('survey.validation'), function (json) {
            swal.fire({
                title: json.cancel_back_title,
                html: json.cancel_back_content,
                buttonsStyling: false,

                confirmButtonText: json.btn_yes,
                confirmButtonClass: "btn btn-sm btn-default btn-bold btn_yes",

                showCancelButton: true,
                cancelButtonText: json.btn_no,
                cancelButtonClass: "btn btn-sm btn-bold btn-brand btn_cancel"
            }).then(function (result) {
                if (result.value) {
                    window.location.href = laroute.route('survey.index');
                }
            });
        });
    },
};
survey.init();

function onmouseoverAddNew() {
    $('.dropdow-add-new').show();
}


function onmouseoutAddNew() {
    $('.dropdow-add-new').hide();
}
