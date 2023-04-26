var loyalty = {
    init: function () {
        $(document).ready(function () {
            $('#time_start').datetimepicker({
                todayHighlight: true,
                autoclose: true,
                format: 'd/m/yyyy hh:ii',
                startDate: '+0d',
                minDate: new Date()
            });
            $('#time_end').datetimepicker({
                todayHighlight: true,
                autoclose: true,
                format: 'd/m/yyyy hh:ii',
                startDate: '+0d',
                minDate: new Date()
            });
        });
    },
    loadListLoyalty: function (page = 1) {
        let nameProgram = $("input[name='name_program']").val();
        let timeStart = $("input[name='time_start']").val();
        let timeEnd = $("input[name='time_end']").val();
        let perpage = $('#perpage option:selected').val();
        let status = $('#status option:selected').val();
        $.ajax({
            url: laroute.route('loyalty.accumulate-points.load-all'),
            method: "POST",
            data: {
                nameProgram: nameProgram,
                time_start: timeStart,
                time_end: timeEnd,
                page: page,
                perpage: perpage,
                status: status
            },
            success: function (res) {

                $('.table-content').html(res.view);
                $('.selectpicker').selectpicker('show');

            }
        });
    },
    resetSearchLoyalty: function () {
        $("input[name='name_program']").val('');
        $("input[name='time_start']").val('');
        $("input[name='time_end']").val('');
        $('#status option:selected').val('');
        loyalty.loadListLoyalty()
    },
    togglePeriodType: function (o) {
        let validity_period_type = $(o).val();
        if (validity_period_type != 'time_limit') {
            $("#date_start").val('');
            $("#date_end").val('');
            $("#date_start").prop('disabled', true);
            $("#date_start").prop('readonly', true);
            $("#date_end").prop('disabled', true);
            $("#date_end").prop('readonly', true);
        } else {
            $("#date_start").prop('disabled', false);
            $("#date_start").prop('readonly', false);
            $("#date_end").prop('disabled', false);
            $("#date_end").prop('readonly', false);
        }
    },
    toggleApplyPoint: function (o) {
        var typeApplyPoint = $(o).val();
        if (typeApplyPoint == 'all') {
            $("#accumulate_point_all").prop('disabled', false);
            $.each($('.item_rank'), function () {
                $(this).find($('.rank_point')).prop('disabled', true);
                $(this).find($('.rank_point')).val('');
            })
        } else {
            $("#accumulate_point_all").prop('disabled', true);
            $("#accumulate_point_all").val('');
            $.each($('.item_rank'), function () {
                $(this).find($('.rank_point')).prop('disabled', false);

            })
        }
    },
    save: function () {
        $.getJSON(laroute.route('loyalty.validation'), function (json) {
            var form = $("#form-data");
            let is_active = 0;
            if ($("#is_active").is(':checked')) {
                is_active = 1;
            }
            form.validate({
                rules: {
                    accumulation_program_name: {
                        required: true,
                        maxlength: 255,
                    },
                },
                messages: {
                    accumulation_program_name: {
                        required: json.accumulate_point.name_required,
                        maxlength: json.accumulate_point.name_max,
                    },
                }
            });
            if (!form.valid()) {
                return false;
            }
            var continute = true;
            let apply_type = $('input[name="apply_type"]:checked').val();
            let time_start = $('#date_start').val();
            let time_end = $('#date_end').val();
            let validity_period_type = $('input[name="validity_period_type"]:checked').val();
            let accumulate_point_all = $('#accumulate_point_all').val();
            var valuePoint = [];
            if (validity_period_type == 'time_limit') {
                if (time_start == '') {
                    $('.time_start_error').text(json.accumulate_point.time_start_required);
                    continute = false;
                } else {
                    $('.time_start_error').text('');
                }
                if (time_end == '') {
                    $('.time_end_error').text(json.accumulate_point.time_end_required);
                    continute = false;
                } else {
                    $('.time_end_error').text('');
                }
            } else {
                $('.time_start_error').text('');
                $('.time_end_error').text('');
            }

            if (apply_type == 'all') {
                if (accumulate_point_all == '') {
                    $('.accumulate_point_all_error').text(json.accumulate_point.accumulate_point);
                    continute = false;
                } else if (accumulate_point_all < 0) {
                    $('.accumulate_point_all_error').text(json.accumulate_point.accumulate_min);
                    continute = false;
                } else {
                    $('.accumulate_point_all_error').text('');
                }
            } else if (apply_type == 'rank') {
                $.each($('.item_rank'), function () {
                    var num = $(this).find($('.rank_point')).data('index');
                    var rankId = $(this).find($('.rank_point')).data('rank_id');
                    var accumulatePoint = $(this).find($('.rank_point')).val();
                    valuePoint.push({
                        rankId: rankId,
                        accumulatePoint: accumulatePoint
                    })
                    if (accumulatePoint == '') {
                        $('.accumulate_point_error_' + num).text(json.accumulate_point.diemphanhang);
                        continute = false;
                    } else if (accumulatePoint < 0) {
                        $('.accumulate_point_error_' + num).text(json.accumulate_point.diemphanhang_min);
                        continute = false;
                    } else {
                        $('.accumulate_point_error_' + num).text('');
                    }

                })

            }
            if (continute == false) return false;
            let data = {
                accumulation_program_name: $('#accumulation_program_name').val(),
                survey_id: $("#survey").val(),
                validity_period_type: validity_period_type,
                date_start: time_start,
                date_end: time_end,
                is_active: is_active,
                description: $("#loylaty_description").val(),
                apply_type: apply_type,
                accumulation_point: accumulate_point_all,
                valuePoint: valuePoint
            }

            $.ajax({
                url: laroute.route('loyalty.accumulate-points.store'),
                method: "POST",
                async: false,
                data: data,
                success: function (res) {
                    if (res.error == false) {
                        swal.fire(res.message, "", "success").then(function (result) {
                            window.location.href = laroute.route('loyalty.accumulate-points.show', { id: res.id });
                        });
                    } else {
                        swal.fire(res.message, '', "error");
                    }
                },
                error: function (res) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal.fire(json.accumulate_point.create_fail, mess_error, "error");
                }
            });
        })
    },
    update: function () {
        $.getJSON(laroute.route('loyalty.validation'), function (json) {
            var form = $("#form-data");
            let is_active = 0;
            if ($("#is_active").is(':checked')) {
                is_active = 1;
            }
            form.validate({
                rules: {
                    accumulation_program_name: {
                        required: true,
                        maxlength: 255,
                    },
                },
                messages: {
                    accumulation_program_name: {
                        required: json.accumulate_point.name_required,
                        maxlength: json.accumulate_point.name_max,
                    },
                }
            });
            if (!form.valid()) {
                return false;
            }
            var continute = true;
            let apply_type = $('input[name="apply_type"]:checked').val();
            let time_start = $('#date_start').val();
            let time_end = $('#date_end').val();
            let validity_period_type = $('input[name="validity_period_type"]:checked').val();
            let accumulate_point_all = $('#accumulate_point_all').val();
            var valuePoint = [];
            if (validity_period_type == 'time_limit') {
                if (time_start == '') {
                    $('.time_start_error').text(json.accumulate_point.time_start_required);
                    continute = false;
                } else {
                    $('.time_start_error').text('');
                }
                if (time_end == '') {
                    $('.time_end_error').text(json.accumulate_point.time_end_required);
                    continute = false;
                } else {
                    $('.time_end_error').text('');
                }
            } else {
                $('.time_start_error').text('');
                $('.time_end_error').text('');
            }

            if (apply_type == 'all') {
                if (accumulate_point_all == '') {
                    $('.accumulate_point_all_error').text(json.accumulate_point.accumulate_point);
                    continute = false;
                } else if (accumulate_point_all < 0) {
                    $('.accumulate_point_all_error').text(json.accumulate_point.accumulate_min);
                    continute = false;
                } else {
                    $('.accumulate_point_all_error').text('');
                }
            } else if (apply_type == 'rank') {
                $.each($('.item_rank'), function () {
                    var num = $(this).find($('.rank_point')).data('index');
                    var rankId = $(this).find($('.rank_point')).data('rank_id');
                    var accumulatePoint = $(this).find($('.rank_point')).val();
                    valuePoint.push({
                        rankId: rankId,
                        accumulatePoint: accumulatePoint
                    })
                    if (accumulatePoint == '') {
                        $('.accumulate_point_error_' + num).text(json.accumulate_point.diemphanhang);
                        continute = false;
                    } else if (accumulatePoint < 0) {
                        $('.accumulate_point_error_' + num).text(json.accumulate_point.diemphanhang_min);
                        continute = false;
                    } else {
                        $('.accumulate_point_error_' + num).text('');
                    }

                })

            }
            if (continute == false) return false;
            let data = {
                accumulation_program_name: $('#accumulation_program_name').val(),
                survey_id: $("#survey").val(),
                validity_period_type: validity_period_type,
                date_start: time_start,
                date_end: time_end,
                is_active: is_active,
                description: $("#loylaty_description").val(),
                apply_type: apply_type,
                accumulation_point: accumulate_point_all,
                valuePoint: valuePoint,
                id: $("#accumulation_program_id").val()
            }

            $.ajax({
                url: laroute.route('loyalty.accumulate-points.update'),
                method: "POST",
                async: false,
                data: data,
                success: function (res) {
                    if (res.error == false) {
                        swal.fire(res.message, "", "success").then(function (result) {
                            window.location.href = laroute.route('loyalty.accumulate-points.show', { id: res.id });
                        });
                    } else {
                        swal.fire(res.message, '', "error");
                    }
                },
                error: function (res) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal.fire(json.accumulate_point.create_fail, mess_error, "error");
                }
            });
        })
    },
    back: function () {
        window.location.href = laroute.route('loyalty.accumulate-points');
    },
    showModalConfig: function () {
        $.ajax({
            url: laroute.route('loyalty.accumulate-points.notification'),
            method: "POST",
            success: function (res) {
                $('#modal-show').html(res.view);
                $("#modal_notification").modal('show');
            }
        });
    },
    uploadBackground: function (input) {
        let arr = ['.jpg', '.png', '.jpeg', '.JPG', '.PNG', '.JPEG'];
        let check = 0;
        if (input.files && input.files[0]) {
            let file_data = $('#getFileLogo').prop('files')[0];
            let form_data = new FormData();
            form_data.append('file', file_data);
            form_data.append('link', '_survey_question_background.');
            let fileInput = input, file = fileInput.files && fileInput.files[0];
            $.map(arr, function (item) {
                if (file_data.name.indexOf(item) != -1) {
                    check = 1;
                }
            });
            let fileUpload = document.getElementById('getFileLogo');
            //Initiate the FileReader object.
            let reader = new FileReader();
            //Read the contents of Image File.
            reader.readAsDataURL(fileUpload.files[0]);
            reader.onload = function (e) {
                //Initiate the JavaScript Image object.
                let image = new Image();
                //Set the Base64 string return from FileReader as source.
                image.src = e.target.result;
                //Validate the File Height and Width.
                image.onload = function () {
                    if (check == 1) {
                        let reader = new FileReader();
                        reader.onload = function (e) {
                            $('#logo-image').empty();
                            let tpl = $('#image-tpl').html();
                            tpl = tpl.replace(/{link}/g, e.target.result);
                            $('#logo-image').append(tpl);
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
                                if (res.error == 0) {
                                    $('#detail_background').val(res.file);
                                }
                            }
                        });
                    } else {
                        swal.fire('', "", "error");
                    }
                };
            };
        }
    },

    copyCode: function (element, key) {
        var $temp = $("#code_here_" + key);
        // $("body").append($temp);
        $temp.val($(element).text()).select();
        document.execCommand("copy");
        // $temp.remove();

    },

    updateTemplate: function () {
        $.getJSON(laroute.route('loyalty.validation'), function (json) {
            var form = $('#form-submit-template');
            form.validate({
                rules: {
                    title_template: {
                        required: true,
                        maxlength: 255,
                    },
                    message_template: {
                        required: true
                    },
                    des_detail_template: {
                        required: true
                    }
                },
                messages: {
                    title_template: {
                        required: json.template_config_notifi.title_required,
                        maxlength: json.template_config_notifi.title_max_255,
                    },
                    message_template: {
                        required: json.template_config_notifi.description_required,
                    },
                    des_detail_template: {
                        required: json.template_config_notifi.description_detail_required,
                    }
                }
            });
            var arrPramShow = [];
            $.each($('.param-show-1'), function () {
                var params_show_template = $(this).find($('.params_show_template')).val();
                arrPramShow.push(params_show_template);
            });
            if (form.valid()) {

                $.ajax({
                    url: laroute.route('loyalty.accumulate-points.setting-notification'),
                    method: 'POST',
                    data: {
                        title_template: $('#title_template').val(),
                        message_template: $('#message_template').val(),
                        des_detail_template: $('#des_detail_template').val(),
                        arrPramShow: arrPramShow,
                        avatar: $("#detail_background").val()
                    },
                    success: function (res) {
                        if (!res.error) {
                            swal.fire(res.message, "", "success").then(function () {
                                window.location.reload();
                            });
                        } else {
                            swal.fire(res.message, "", "error")
                        }
                    },
                    error: function (res) {
                        var mess_error = '';
                        jQuery.each(res.responseJSON.errors, function (key, val) {
                            mess_error = mess_error.concat(val + '<br/>');
                        });
                        swal.fire(mess_error, "", "error");
                    }
                });
            }
        });
    },
    showModalDestroy: function (id) {
        $.ajax({
            url: laroute.route('loyalty.accumulate-points.show-modal-destroy'),
            method: 'POST',
            data: {
                id: id
            },
            success: function (res) {
                if (res.error == false) {
                    $("#modal__destroy--show").html(res.view);
                    $("#loyalty__modal--destroy").modal('show');
                }
            }
        });
    },
    destroy: function (id) {
        $.getJSON(laroute.route('loyalty.validation'), function (json) {
            $.ajax({
                url: laroute.route('loyalty.accumulate-points.destroy'),
                method: "POST",
                data: {
                    id: id
                },
                success: function (res) {
                    if (res.error == false) {
                        swal.fire(res.message, "", "success").then(function (result) {
                            window.location.reload();
                        });
                    } else {
                        swal.fire(res.message, '', "error");
                    }
                },
                error: function (res) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal.fire(json.accumulate_point.delete_fail, mess_error, "error");
                }
            });
        })
    }
}

loyalty.init();