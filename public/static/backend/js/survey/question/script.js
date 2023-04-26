
var question = {
    init: function () {
        $(document).ready(function () {
            $.ajaxSetup({
                async: false,
            });
            setTimeout(function () {
                question.loadBlock();
            }, 800);
            $('.numeric').mask('00000000000', { reverse: true });
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
        });
    },
    showBlockCollapse: function (o, number, block_number = null) {
        let countQuestion = $('.div_question_item_' + block_number).length;
        $('.span_count_question_' + number).text('(' + countQuestion + ' câu hỏi)');
        let is_show = 1;
        $('#contentblock_' + number).on('shown.bs.collapse', function () {
            $(".icon_drop_" + number).removeClass('fa fa-caret-right').addClass('fa fa-caret-down');
            $('.span_count_question_' + number).addClass('text-white');
            is_show = 1;
        });
        $('#contentblock_' + number).on('hidden.bs.collapse', function () {
            $(".icon_drop_" + number).removeClass('fa fa-caret-down').addClass('fa fa-caret-right');
            $('.span_count_question_' + number).removeClass('text-white');
            is_show = 0;
        });
        $('#div_config_question').html('');
        $('.div_question_item').removeClass('background-color-fff').addClass('background-color-fff').removeClass('background-color-e3f5ff');
        $('.btn-action-list').addClass('div-hidden');
        setTimeout(function () {
            question.onChangeBlock(is_show, block_number, 'is_show', 'collapse');
        }, 1200);
    },
    addBlock: function (number) {
        $.ajax({
            url: laroute.route('survey.add-block'),
            method: "POST",
            data: {
                unique: UNIQUE,
                number: number,
                id : ID
            },
            success: function (res) {
                question.loadBlock();
            }
        });
    },
    loadBlock: function () {
        $.ajax({
            url: laroute.route('survey.load-block'),
            method: "POST",
            data: {
                unique: UNIQUE,
                action_page: ACTION,
            },
            success: function (res) {
                $('#div_list_block').html(res.html);
                $('#div_config_question').html('');
                res.data.map((index, key) => {
                    question.loadQuestionInBlock(key);
                });
            }
        });
    },
    loadTeample: (template, key, keeding = null) => {
        var jsonLang = JSON.parse(localStorage.getItem('tranlate'));
        let data = {
            template,
            key,
            unique: UNIQUE,
            keeding: keeding,
            id: ID
        };
        $.ajax({
            url: laroute.route('survey.template-question'),
            method: "POST",
            data: data,
            success: function (res) {

                if (res.error == true) {
                    swal.fire(jsonLang['Thêm template câu hỏi thất bại'], res.message, "error")
                } else if (res.is_modal == true && res.error == false) {
                    $("#div-modal__template").html(res.view)
                    $("#modal-template__question").modal('show')
                } else {
                    question.loadBlock()
                    $("#modal-template__question").modal('hide')
                }
            }
        });

    },
    // modal remove //
    showModalRemove: function (key) {
        $.ajax({
            url: laroute.route('survey.show-modal-remove-block'),
            method: "POST",
            data: {
                key: key
            },
            success: function (res) {
                $('#div-modal-block-remove').html(res.view);
                $('#destroy_block_question').modal('show');
            }
        });
    },

    onChangeBlock: function (o, number = null, element = null, action = 'change') {
        if (action == 'remove') {
            $.ajax({
                url: laroute.route('survey.on-change-block'),
                global: false,
                method: "POST",
                data: {
                    unique: UNIQUE,
                    number: number,
                    action: action,
                    action_page: ACTION,
                    id: ID
                },
                success: function (res) {
                    $('#destroy_block_question').modal('hide');
                    question.loadBlock();
                }
            });
        } else {
            let value = 0;
            if (action == 'change_position' || action == 'collapse') {
                value = o;
            } else {
                value = $(o).val();
            }
            $.ajax({
                url: laroute.route('survey.on-change-block'),
                global: false,
                method: "POST",
                data: {
                    unique: UNIQUE,
                    number: number,
                    element: element,
                    value: value,
                    action: action,
                    action_page: ACTION,
                    id: ID
                },
                success: function (res) {
                    if (action == 'change_position') {
                        question.loadBlock();
                    }
                }
            });
        }
    },
    /**
     * Thêm câu hỏi
     * survey_question_type == null: Show modal chọn loại câu hỏi
     * survey_question_type != null: Thêm câu hỏi
     * @param block_number - Thêm ở block nào
     * @param question_number - Thêm ở phía DƯỚI câu hỏi nào (Chỉ xảy ra khi add_custom = 1, nếu = 0 thì thêm cuối cùng)
     * @param survey_question_type - Loại câu hỏi
     * @param add_custom - Thêm khi click vào (+)
     * @param position - Đang click (+) ở câu hỏi có vị trí nào
     * @param change_question - Thay đổi loại câu hỏi
     */
    addQuestion: function (block_number, question_number = 0, survey_question_type = null, add_custom = 0, position = null, change_question = 0, countPoint = null) {
        if (survey_question_type) {
            $.ajax({
                url: laroute.route('survey.add-question'),
                method: "POST",
                data: {
                    unique: UNIQUE,
                    block_number: block_number,
                    question_number: question_number,
                    survey_question_type: survey_question_type,
                    add_custom: add_custom,
                    position: position,
                    change_question: change_question,
                    countPoint: countPoint,
                    id: ID
                },
                success: function (res) {
                    if (res) {
                        question.loadQuestionInBlock(block_number);
                        if (change_question == 1) {
                            $('.div_question_item_' + block_number + '_' + question_number).trigger('click');
                        } else {
                            $('#div_config_question').html('');
                        }
                    }
                }
            });
            $('#modal_question_type').modal('hide');
            $('.numeric').mask('00000000000', { reverse: true });
        } else {
            $.ajax({
                url: laroute.route('survey.render-modal-question-type'),
                method: "POST",
                data: {
                    unique: UNIQUE,
                    block_number: block_number,
                    question_number: question_number,
                    add_custom: add_custom,
                    position: position,
                    idSurvey: ID,
                    change_question: change_question,
                },
                success: function (res) {
                    $('#div_modal').html(res.html);
                    $('#modal_question_type').modal('show');
                    $('.numeric').mask('00000000000', { reverse: true });
                }
            });
        }
    },
    /**
     * Load ra các câu hỏi của block
     * @param block_number
     */
    loadQuestionInBlock: function (block_number) {
        $.ajax({
            url: laroute.route('survey.load-question-in-block'),
            method: "POST",
            data: {
                unique: UNIQUE,
                block_number: block_number,
                action_page: ACTION,
            },
            success: function (res) {
                $('#sortable_' + block_number).html(res.html);
                if (res.data['question'].length >= 20) {
                    $('.button-add-list-' + block_number).prop('disabled', true);
                } else {
                    $('.button-add-list-' + block_number).prop('disabled', false);
                }
                question.changeQuestionPosition(block_number);
            }
        });
    },
    changeQuestionPosition: function (block_number) {
        $(".icon-move").on('mousedown', function () {
            let blockNumber = $(this).attr("data-block-number");
            let position = $(this).attr("data-position");
            $('#sortable_' + block_number).sortable({
                stop: function (event, ui) {
                    $(".sortable").sortable("cancel");
                    let arrayPosition = [];
                    $('#sortable_' + block_number).find(".icon-move").each(function () {
                        arrayPosition.push($(this).attr("data-position"));
                    });
                    $.ajax({
                        method: 'POST',
                        url: laroute.route('survey.change-question-position'),
                        data: {
                            unique: UNIQUE,
                            arrayPosition: arrayPosition,
                            block_number: blockNumber,
                            position: position,
                        },
                        success: function (res) {
                            question.loadQuestionInBlock(blockNumber);
                        }
                    });
                }
            });
            if (ACTION == 'show') {
                $(".div_sortable").sortable({
                    disabled: true
                }).enableSelection();
            }
        });
    },
    /**
     * Click vào chọn box câu hỏi
     * @param block_number
     * @param question_number
     * @param change_question
     */
    selectedQuestion: function (block_number, question_number, change_question = 0) {
        $('.div_question_item').removeClass('background-color-fff').addClass('background-color-fff').removeClass('background-color-e3f5ff');
        $('.div_question_item_' + block_number + '_' + question_number).addClass('background-color-e3f5ff');
        $('.btn-action-list').addClass('div-hidden');
        $('.btn-action-list-' + block_number + '-' + question_number).removeClass('div-hidden');
        question.showConfigQuestion(block_number, question_number, change_question);
    },
    /**
     * Chi tiết câu hỏi
     * @param block_number
     * @param question_number
     * @param change_question
     */
    showConfigQuestion: function (block_number, question_number, change_question = 0) {
        $.ajax({
            method: 'POST',
            url: laroute.route('survey.show-config-question'),
            data: {
                unique: UNIQUE,
                block_number: block_number,
                question_number: question_number,
                change_question: change_question,
                action_page: ACTION,
                id: ID
            },
            success: function (res) {
                $('#div_config_question').html(res.html);
                $('.numeric').mask('00000000000', { reverse: true });
            }
        });
    },
    /**
     * Xóa câu hỏi
     * @param block_number
     * @param question_number
     */
    removeQuestion: function (block_number, question_number) {
        $.ajax({
            url: laroute.route('survey.remove-question'),
            method: "POST",
            data: {
                unique: UNIQUE,
                block_number: block_number,
                question_number: question_number,
            },
            success: function (res) {
                question.loadQuestionInBlock(block_number);
                $('#div_config_question').html('');
            }
        });
    },
    /**
     * chọn đáp án đúng cho câu hỏi loại tính điểm 
     * @param o: this element
     * @param block_number: Block nào
     * @param question_number: Câu hỏi nào
     * @param element: Phần tử nào
     * @param answer_number: Câu trả lời nào
     * @param survey_question_type: Loại câu hỏi
     */
    oncheckedAnswerQuestion: function (o, block_number, question_number, element, answer_number = null, survey_question_type = null, typeChecked) {
        if (typeChecked == 'multi_choice') {
            let checked = 0;
            if ($(o).is(':checked')) {
                checked = 1;
            }

            if (checked == 1) {
                $(o).parents('.multiple-radio').find(".icon-check__answer--success").removeClass("icon-check__answer--hiden");
                $(o).parents('.multiple-radio').find(".question-text__answer").addClass("background-checked__answer--success");
            } else {
                $(o).parents('.multiple-radio').find(".icon-check__answer--success").addClass("icon-check__answer--hiden");
                $(o).parents('.multiple-radio').find(".question-text__answer").removeClass("background-checked__answer--success");
            }

            $.ajax({
                url: laroute.route('survey.on-change-question'),
                method: "POST",
                global: false,
                data: {
                    unique: UNIQUE,
                    block_number: block_number,
                    question_number: question_number,
                    element: element,
                    answer_number: answer_number,
                    checked: checked
                },
                success: function (res) {

                }
            });

        } else {
            $(o).parents('.question-field').find(".icon-check__answer--success").addClass("icon-check__answer--hiden");
            $(o).parents('.question-field').find(".question-text__answer").removeClass("background-checked__answer--success");
            $(o).parents('.multiple-radio').find(".icon-check__answer--success").removeClass("icon-check__answer--hiden");
            $(o).parents('.multiple-radio').find(".question-text__answer").addClass("background-checked__answer--success");

            $.ajax({
                url: laroute.route('survey.on-change-question'),
                method: "POST",
                global: false,
                data: {
                    unique: UNIQUE,
                    block_number: block_number,
                    question_number: question_number,
                    element: element,
                    answer_number: answer_number,
                },
                success: function (res) {

                }
            });
        }
    },

    /**
     * Thay đổi gì đó của câu hỏi
     * @param o: this element
     * @param block_number: Block nào
     * @param question_number: Câu hỏi nào
     * @param element: Phần tử nào
     * @param answer_number: Câu trả lời nào
     * @param survey_question_type: Loại câu hỏi
     */
    onChangeQuestion: function (o, block_number, question_number, element, answer_number = null, survey_question_type = null, checked = null) {
        let value = $(o).val();
        if (element == 'up' || element == 'down') {
            value = parseInt($('.quantity-' + block_number + '-' + question_number).attr('data-quantity'));
            if (element == 'up') {
                value += 1;
                $('.btn-custom-answer').prop('disabled', false);
            } else {
                if (value <= 2) {
                    if (survey_question_type != 'page_picture') {
                        $(o).prop('disabled', true);
                        return false;
                    } else {
                        if (value <= 1) {
                            $(o).prop('disabled', true);
                            return false;
                        }
                    }
                } else {
                    $(o).prop('disabled', false);
                }
                value -= 1;
            }
            $('.quantity-' + block_number + '-' + question_number).text(value);
            $('.quantity-' + block_number + '-' + question_number).attr('data-quantity', value);
        } else if (element == 'confirm_type') {
            value = $('input[name=confirm_type]:checked').val();
            question.initConfigText(value);
        }

        $.ajax({
            url: laroute.route('survey.on-change-question'),
            method: "POST",
            global: false,
            data: {
                unique: UNIQUE,
                block_number: block_number,
                question_number: question_number,
                element: element,
                value: value,
                answer_number: answer_number,
            },
            success: function (res) {
                if (element == 'up' || element == 'down' || element == 'survey_question_type') {
                    question.loadQuestionInBlock(block_number);
                    $('.div_question_item_' + block_number + '_' + question_number).trigger('click');
                }
            }
        });
    },

    initConfigText: function (value) {
        $('.numeric').val('');
        $('.div_config_hidden').addClass('div-hidden');
        if (value == 'none') {
            $('input[name=is_confirm_content]').prop('checked', false);
        } else if (value == 'min') {
            $('.div_min').removeClass('div-hidden');
            $('input[name=is_confirm_content]').prop('checked', false);
        } else if (value == 'max') {
            $('.div_max').removeClass('div-hidden');
            $('input[name=is_confirm_content]').prop('checked', false);
        } else if (value == 'digits_between') {
            $('.div_digits_between').removeClass('div-hidden');
            $('input[name=is_confirm_content]').prop('checked', false);
        } else if (value == 'numeric') {
            $('.div_numeric').removeClass('div-hidden');
        }
        if (!$('input[name=is_confirm_content]').is(':checked')) {
            $('input:radio[name=confirm_type][value=email]').prop("disabled", true);
            $('input:radio[name=confirm_type][value=phone]').prop("disabled", true);
            $('input:radio[name=confirm_type][value=date_format]').prop("disabled", true);
            $('input:radio[name=confirm_type][value=numeric]').prop("disabled", true);
        }
    },
    onChangeIsConfirmContent: function (o) {
        $('input[name=confirm_type]').prop('disabled', false);
        let confirmType = $('input[name=confirm_type]:checked').val();
        if (confirmType == 'none' || confirmType == 'min' || confirmType == 'max' || confirmType == 'digits_between') {
            $('input:radio[name=confirm_type][value=email]').prop("checked", true).trigger("change");
        }
    },
    uploadPicture: function (input, block_number, question_number, image_number) {
        let arr = ['.jpg', '.png', '.jpeg', '.JPG', '.PNG', '.JPEG'];
        let check = 0;
        if (input.files && input.files[0]) {
            let uniqueByParam = block_number + '_' + question_number + '_' + image_number;
            let idInputGetFile = 'getFile_' + uniqueByParam;
            let file_data = $('#' + idInputGetFile).prop('files')[0];
            let form_data = new FormData();
            form_data.append('file', file_data);
            form_data.append('link', '_survey_question.');
            let fileInput = input, file = fileInput.files && fileInput.files[0];
            $.map(arr, function (item) {
                if (file_data.name.indexOf(item) != -1) {
                    check = 1;
                }
            });
            let fileUpload = document.getElementById(idInputGetFile);
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
                            $('#page_picture_' + uniqueByParam).empty();
                            let tpl = $('#logo-tpl').html();
                            tpl = tpl.replace(/{link}/g, e.target.result);
                            $('#page_picture_' + uniqueByParam).append(tpl);
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
                                    $('#image_' + uniqueByParam).val(res.file);
                                    question.onChangeImage(block_number, question_number, image_number, res.file)
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
    onChangeImage: function (block_number, question_number, image_number, path) {
        $.ajax({
            url: laroute.route('survey.on-change-question'),
            method: "POST",
            data: {
                unique: UNIQUE,
                block_number: block_number,
                question_number: question_number,
                element: 'image',
                image_number: image_number,
                value: path,
            },
            success: function (res) {
            }
        });
    },
    save: function () {
        $.getJSON(laroute.route('survey.validation'), function (json) {
            $.ajax({
                url: laroute.route('survey.update-survey-question'),
                method: 'POST',
                data: {
                    unique: UNIQUE,
                    id: ID,
                },
                success: function (res) {
                    if (res.error == false) {
                        if (res.warning != '') {
                            Swal.fire({
                                title: res.warning,
                                type: 'warning',
                                showCancelButton: true,
                                // confirmButtonColor: '#3085d6',
                                // cancelButtonColor: '#d33',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.value) {
                                    $.ajax({
                                        url: laroute.route('survey.update-survey-question'),
                                        method: 'POST',
                                        data: {
                                            unique: UNIQUE,
                                            id: ID,
                                            warning: true
                                        },
                                        success: function (res) {

                                            swal.fire(json.edit_success, "", "success").then(function () {
                                                window.location.href = laroute.route('survey.show-question', { id: res.id });
                                            });
                                        }
                                    });
                                }
                            })
                        } else {
                            swal.fire(json.edit_success, "", "success").then(function () {
                                window.location.href = laroute.route('survey.show-question', { id: res.id });
                            });
                        }
                    } else {
                        var mess_error = '';
                        $.map(res.array_error, function (a) {
                            mess_error = mess_error.concat(a + '<br/>');
                        });
                        swal.fire(json.edit_fail, mess_error, "error");
                    }
                },
                error: function (res) {
                    if (res.responseJSON != undefined) {
                        var mess_error = '';
                        $.map(res.responseJSON.errors, function (a) {
                            mess_error = mess_error.concat(a + '<br/>');
                        });
                        swal.fire(json.edit_fail, mess_error, "error");
                    }
                }
            });
        });
    },
    back: function () {
        window.location.href = laroute.route('survey.show-question', { id: ID })
    },
    showModalNotification: function () {
        $.ajax({
            url: laroute.route('survey.show-modal-notification'),
            method: "POST",
            data: { survey_id: ID },
            success: function (res) {
                $('#div_modal').html(res.html);
                $('#modal_notification').modal('show');

            }
        });
    },
    showModalConfigPoint: function () {
        $.ajax({
            url: laroute.route('survey.show-modal-config-point'),
            method: "POST",
            data: { survey_id: ID },
            success: function (res) {
                $('#div_modal').html(res.html);
                $('#modal_point').modal('show');
                $('#start_date').datetimepicker({
                    todayHighlight: true,
                    autoclose: true,
                    format: 'hh:ii:00 dd/mm/yyyy'

                });
                $('#end_date').datetimepicker({
                    todayHighlight: true,
                    autoclose: true,
                    format: 'hh:ii:00 dd/mm/yyyy'
                });
                if (res.time_start) {

                    $('#start_date').val(res.time_start)
                }

                if (res.time_end) {
                    $('#end_date').val(res.time_end)
                }


                if ($("input[name='show_answer']:checked").val() == 'C') {
                    $("#start_date").prop('disabled', false);
                    $("#end_date").prop('disabled', false);
                }

                $('.numeric').mask('00000000000', { reverse: true })

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
                            let tpl = $('#logo-tpl').html();
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
    updateTemplate: function () {
        $.getJSON(laroute.route('survey.validation'), function (json) {
            let form = $('#form-submit-template');
            form.validate({
                rules: {
                    title: {
                        required: true,
                        maxlength: 255,
                    },
                    message: {
                        maxlength: 255,
                    }
                },
                messages: {
                    title: {
                        required: json.title_required,
                        maxlength: json.maxlength_255,
                    },
                    message: {
                        maxlength: json.maxlength_255,
                    },
                }
            });
            if (form.valid()) {
                let data = form.serializeArray();
                let showPoint = 0;
                if ($("#show_point").is(':checked')) {
                    showPoint = 1;
                }
                data[data.length] = { name: "survey_id", value: ID };
                data[4] = { name: 'show_point', value: showPoint };
                $.ajax({
                    url: laroute.route('survey.update-template'),
                    method: 'POST',
                    data: data,
                    success: function (res) {
                        if (res.error == false) {
                            swal.fire(json.edit_success, "", "success").then(function () {
                                location.reload();
                            });
                        }
                    },
                    error: function (res) {

                    }
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

    toggleConfigPoint: (o) => {
        if ($(o).val() == 'C') {
            $("#start_date").prop('disabled', false);
            $("#end_date").prop('disabled', false);
        } else {
            $("#start_date").prop('disabled', true);
            $("#end_date").prop('disabled', true);
            $("#end_date").val("");
            $("#start_date").val("");
        }
    },

    updateConfigPoint: (idConfig) => {
        let jsonLang = JSON.parse(localStorage.getItem('tranlate'));
        let show_answer = $("input[name='show_answer']:checked").val();
        let time_start = $("#start_date").val();
        let time_end = $("#end_date").val();
        let show_answer_wrong = 0;
        if ($("#show_answer_wrong").is(':checked')) {
            show_answer_wrong = 1;
        }
        let show_answer_success = 0;
        if ($("#show_answer_success").is(':checked')) {
            show_answer_success = 1;
        }
        let show_point = 0;
        if ($("#show_point").is(':checked')) {
            show_point = 1;
        }
        let count_point_text = 0;
        if ($("#count_point_text").is(':checked')) {
            count_point_text = 1;
        }
        let point_default = $("#point_default").val();
        data = {
            show_answer,
            time_start, time_end,
            show_answer_wrong,
            show_answer_success,
            show_point,
            count_point_text,
            point_default,
            idSurvey: ID,
            id: idConfig
        }


        $.ajax({
            url: laroute.route('survey.update-config-point'),
            method: 'POST',
            dataType: 'JSON',
            data: data,
            success: function (res) {
                if (res.error == true) {
                    var mess_error = '';
                    $.map(res.message, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal.fire(jsonLang['Cập nhật cài đặt khảo sát tính điểm thất bại'], mess_error, "error");
                } else {
                    swal.fire(jsonLang['Cập nhật cài đặt khảo sát tính điểm thành công'], "", "success").then(function () {
                        setTimeout(function () {
                            $('#modal_point').modal('hide');
                            window.location.reload();
                        }, 1000)

                    });;
                }
            },
            error: function (res) {
                if (res.responseJSON != undefined) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal.fire(jsonLang['Cập nhật cài đặt khảo sát tính điểm thất bại'], mess_error, "error");
                }
            }
        });

    }

};
question.init();
