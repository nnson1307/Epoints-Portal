
var configDisplay = {
    loadAll: (page = 1) => {
        let namePage = $("#namePage").val();
        let position = $("#position").val();
        let typeTemplate = $("#typeTemplate").val();
        const data = {
            namePage: namePage,
            position: position,
            typeTemplate: typeTemplate,
            page: page
        };
        $.ajax({
            url: laroute.route('config-display.configDisplay.load-all'),
            method: 'POST',
            dataType: 'JSON',
            data: data,
            success: function (res) {
                if (res.error == false) {
                    $(".table-content").html(res.view);
                }
            },
            error: function (res) {
                console.log(res);
            }
        });
    },
    resetResearchDisplay: () => {
        $("#namePage").val("");
        $("#position").val("");
        $("#typeTemplate").val("");
        $("#typeTemplate").select2({
            placeholder: "Loại template"
        });

        configDisplay.loadAll();

    }
}

var configDisplayDetail = {
    init: () => {
        $(document).ready(function () {
            let destination = $("#destination").val();
            if (destination == 'survey') {
                configDisplayDetail.destinationSurvey()
            } else if (destination == 'promotion') {
                configDisplayDetail.destinationPromotion()

            } else if (destination == 'product_detail') {
                configDisplayDetail.destinationProduct()
            } else if (destination == 'post_detail') {
                configDisplayDetail.destinationPost()
            }
            if ($("#status").is(':checked')) {
                $("#block_position").show();
            } else {
                $("#block_position").hide();
            }

        })
    },
    loadAll: (page = 1) => {
        let mainTitle = $("#mainTitle").val();
        let createdAt = $("#created_at").val();
        let status = $("#status").val();
        const data = {
            mainTitle: mainTitle,
            status: status,
            dateStart: createdAt,
            id: ID_CONFIG_DISPLAY,
            site: SITE,
            page: page
        };
        $.ajax({
            url: laroute.route('config-display-detail.configDisplay.load-all'),
            method: 'POST',
            dataType: 'JSON',
            data: data,
            success: function (res) {
                if (res.error == false) {
                    $(".table__content--detail").html(res.view);
                }
            },
            error: function (res) {
                console.log(res);
            }
        });
    },
    resetResearchDisplayDetail: () => {
        $("#mainTitle").val("");
        $("#created_at").val("");
        $("#status").val("");
        $("#status").select2({
            placeholder: "Chọn trạng thái",
            data: [
                { id: '1', text: "Hoạt động" },
                { id: '0', text: "Ngưng hoạt động" },
            ]
        });

        configDisplayDetail.loadAll();

    },
    increment: () => {

        $('#position').val(function (i, oldval) {
            return ++oldval;
        });
        let position = $('#position').val();
        $("#text_position").text(position);

    },
    decrement: () => {

        $('#position').val(function (i, oldval) {
            if (oldval > 1) {
                return --oldval;
            } else {
                return oldval;
            }
        });
        let position = $('#position').val();
        $("#text_position").text(position);

    },
    upload: (input) => {
        var jsonLang = JSON.parse(localStorage.getItem('tranlate'));

        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#image-config-banner').empty();
                var tpl = $('#image-tpl').html();
                tpl = tpl.replace(/{link}/g, e.target.result);
                $('#image-config-banner').append(tpl);

            };
            reader.readAsDataURL(input.files[0]);
            var file_data = $('#getFileConfigBanner').prop('files')[0];
            var form_data = new FormData();
            form_data.append('file', file_data);
            form_data.append('link', '_config_display.');
            var fsize = input.files[0].size;
            const maxSize = Math.round(1048576 * 2);
            if (Math.round(fsize) < maxSize) {
                $.ajax({
                    url: laroute.route("admin.upload-image"),
                    method: "POST",
                    data: form_data,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (res) {
                        $('#config_banner').val(res.file);
                    }
                });
            } else {
                swal.fire(jsonLang['Upload fail'], jsonLang['Dung lượng file tối đa 2MB'], "error");
            }

        }
    },
    loadDestination: (o) => {
        let valueCategoryDestination = $(o).val();
        if (valueCategoryDestination == 'survey') {
            configDisplayDetail.destinationSurvey()
        } else if (valueCategoryDestination == 'promotion') {
            configDisplayDetail.destinationPromotion()
        } else if (valueCategoryDestination == 'product_detail') {
            configDisplayDetail.destinationProduct()
        } else if (valueCategoryDestination == 'post_detail') {
            configDisplayDetail.destinationPost()
        }
    },
    destinationSurvey: () => {
        var option = '';
        let itemSurveySeleted = typeof SURVEY_ITEM === "undefined" ? '' : SURVEY_ITEM;
        $.ajax({
            url: laroute.route('config-display-category-destination.configDisplay.survey'),
            method: 'POST',
            dataType: 'JSON',
            data: {},
            success: function (res) {
                if (res.erorr == false) {
                    let destinationDetail = $("#destination_detail");
                    $.each(res.data, function (key, value) {
                        option += `<option ${itemSurveySeleted == value.survey_id ? 'selected' : ''} value="${value.survey_id}">${value.survey_name}</option>`;

                    })
                    destinationDetail.empty();
                    destinationDetail.append(option);
                }
            },
            error: function (res) {
                console.log(res);
            }
        });
    },
    destinationPromotion: () => {
        var option = '';
        let itemPromotionSeleted = typeof PROMOTION_ITEM === "undefined" ? '' : PROMOTION_ITEM;
        $.ajax({
            url: laroute.route('config-display-category-destination.configDisplay.promotion'),
            method: 'POST',
            dataType: 'JSON',
            data: {},
            success: function (res) {
                if (res.erorr == false) {
                    let destinationDetail = $("#destination_detail");
                    $.each(res.data, function (key, value) {
                        option += `<option ${itemPromotionSeleted == value.promotion_id ? 'selected' : ''} value="${value.promotion_id}">${value.promotion_name}</option>`;

                    })
                    destinationDetail.empty();
                    destinationDetail.append(option);
                }
            },
            error: function (res) {
                console.log(res);
            }
        });
    },
    destinationProduct: () => {
        var option = '';
        let itemProductSeleted = typeof PRODUCT_ITEM === "undefined" ? '' : PRODUCT_ITEM;
        $.ajax({
            url: laroute.route('config-display-category-destination.configDisplay.product'),
            method: 'POST',
            dataType: 'JSON',
            data: {},
            success: function (res) {
                if (res.erorr == false) {
                    let destinationDetail = $("#destination_detail");
                    $.each(res.data, function (key, value) {
                        option += `<option ${itemProductSeleted == value.product_id ? 'selected' : ''} value="${value.product_id}">${value.product_name}</option>`;

                    })
                    destinationDetail.empty();
                    destinationDetail.append(option);
                }
            },
            error: function (res) {
                console.log(res);
            }
        });
    },
    destinationPost: () => {
        var option = '';
        let itemPostSeleted = typeof POST_ITEM === "undefined" ? '' : POST_ITEM;
        $.ajax({
            url: laroute.route('config-display-category-destination.configDisplay.post'),
            method: 'POST',
            dataType: 'JSON',
            data: {},
            success: function (res) {
                if (res.erorr == false) {
                    let destinationDetail = $("#destination_detail");
                    $.each(res.data, function (key, value) {
                        option += `<option ${itemPostSeleted == value.new_id ? 'selected' : ''} value="${value.new_id}">${value.title_vi}</option>`;

                    })
                    destinationDetail.empty();
                    destinationDetail.append(option);
                }
            },
            error: function (res) {
                console.log(res);
            }
        });
    },
    back: (id) => {
        window.location.href = laroute.route('config-display.configDisplay.edit', { id: id });
    },
    store: () => {
        var jsonLang = JSON.parse(localStorage.getItem('tranlate'));
        var form = $('#form-data');
        form.validate({
            rules: {
                main_title: {
                    required: true,
                    maxlength: 50,
                },
                sub_title: {
                    maxlength: 30,
                },
                acction_name: {
                    required: true,
                    maxlength: 30,
                },
                destination: {
                    required: true,
                }
            },
            messages: {
                main_title: {
                    required: jsonLang['Tiêu đề chính không được bỏ trống'],
                    maxlength: jsonLang['Tối đa 50 kí tự'],
                },
                sub_title: {
                    maxlength: jsonLang['Tối đa 30 kí tự'],
                },
                acction_name: {
                    required: jsonLang['Tên hành động không được bỏ trống'],
                    maxlength: jsonLang['Tối đa 30 kí tự'],
                },
                destination: {
                    required: jsonLang['Chọn đích đến'],
                }
            },
        });
        if (!form.valid()) {
            return false;
        }

        let image = $("#config_banner").val();
        let main_title = $("#main_title").val();
        let sub_title = $("#sub_title").val();
        let action_name = $("#action_name").val();
        let destination = $("#destination").val();
        let destination_detail = $("#destination_detail").val();
        let position = $("#position").val();
        let status = 0;
        if ($("#status").is(":checked")) {
            status = 1;
        }
        let id_config_display = ID_CONFIG_DISPLAY;
        const data = {
            image,
            main_title,
            sub_title,
            action_name,
            destination,
            destination_detail,
            position,
            status,
            id_config_display
        }
        $.ajax({
            url: laroute.route('config-display-detail.configDisplay.store'),
            method: 'POST',
            dataType: 'JSON',
            data: data,
            success: function (res) {
                if (res.error == false) {
                    swal.fire(jsonLang['Tạo banner thành công'], "", "success").then(function () {
                        window.location.href = laroute.url('config-display/config-display-detail/show', [id_config_display, res.id]);
                    });
                } else {
                    var mess_error = '';
                    $.map(res.array_error, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal.fire(jsonLang['Tạo banner thất bại'], mess_error, "error");
                }
            },
            error: function (res) {
                if (res.responseJSON != undefined) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal.fire(jsonLang['Tạo banner thất bại'], mess_error, "error");
                }
            }
        });

    },
    update: () => {
        var jsonLang = JSON.parse(localStorage.getItem('tranlate'));
        var form = $('#form-data');
        form.validate({
            rules: {
                main_title: {
                    required: true,
                    maxlength: 50,
                },
                sub_title: {
                    maxlength: 30,
                },
                acction_name: {
                    required: true,
                    maxlength: 30,
                },
                destination: {
                    required: true,
                }
            },
            messages: {
                main_title: {
                    required: jsonLang['Tiêu đề chính không được bỏ trống'],
                    maxlength: jsonLang['Tối đa 50 kí tự'],
                },
                sub_title: {
                    maxlength: jsonLang['Tối đa 30 kí tự'],
                },
                acction_name: {
                    required: jsonLang['Tên hành động không được bỏ trống'],
                    maxlength: jsonLang['Tối đa 30 kí tự'],
                },
                destination: {
                    required: jsonLang['Chọn đích đến'],
                }
            },
        });
        if (!form.valid()) {
            return false;
        }

        let image = $("#config_banner").val();
        let main_title = $("#main_title").val();
        let sub_title = $("#sub_title").val();
        let action_name = $("#action_name").val();
        let destination = $("#destination").val();
        let destination_detail = $("#destination_detail").val();
        let position = $("#position").val();
        let status = 0;
        if ($("#status").is(":checked")) {
            status = 1;
        }
        let id_config_display = ID_CONFIG_DISPLAY;
        let id_config_display_detail = ID_CONFIG_DISPLAY_DETAIL;
        const data = {
            image,
            main_title,
            sub_title,
            action_name,
            destination,
            destination_detail,
            position,
            status,
            id_config_display,
            id_config_display_detail
        }

        $.ajax({
            url: laroute.route('config-display-detail.configDisplay.update'),
            method: 'POST',
            dataType: 'JSON',
            data: data,
            success: function (res) {
                if (res.error == false) {
                    swal.fire(jsonLang['Cập nhật banner thành công'], "", "success").then(function () {
                        window.location.href = laroute.url('config-display/config-display-detail/show', [id_config_display, res.id]);
                    });
                } else {
                    var mess_error = '';
                    $.map(res.array_error, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal.fire(jsonLang['Cập nhật banner thất bại'], mess_error, "error");
                }
            },
            error: function (res) {
                if (res.responseJSON != undefined) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal.fire(jsonLang['Cập nhật banner thất bại'], mess_error, "error");
                }
            }
        });
    },
    togglePosition: (o) => {
        if ($(o).is(':checked')) {
            $(".block_position").show();
        } else {
            $(".block_position").hide();
        }
    },
    showModalDestroy: (idConfigDisplay, idConfigDetail) => {
        let data = {
            idConfigDetail,
            idConfigDisplay
        };
        $.ajax({
            url: laroute.route('config-display-detail.configDisplay.modal-show-destroy'),
            method: 'POST',
            dataType: 'JSON',
            data: data,
            success: function (res) {
                $("#modal__destroy--detail").html(res.view);
                $("#modal__remove--config").modal('show');
            },
            error: function (res) {
                console.log(res);
            }
        });
    },
    destroy: (idConfigDisplay, idConfigDetail) => {
        var jsonLang = JSON.parse(localStorage.getItem('tranlate'));
        let data = {
            idConfigDetail,
            idConfigDisplay
        };
        $.ajax({
            url: laroute.route('config-display-detail.configDisplay.destroy'),
            method: 'POST',
            dataType: 'JSON',
            data: data,
            success: function (res) {
                if (res.error == false) {
                    swal.fire(jsonLang['Xoá banner thành công'], "", "success").then(function () {
                        window.location.reload()
                    });
                } else {
                    swal.fire(jsonLang['Xoá banner thất bại'], "", "error").then(function () {
                        window.location.reload()
                    });
                }
            },
            error: function (res) {
                swal.fire(jsonLang['Xoá banner thất bại'], "", "error").then(function () {
                    window.location.reload()
                });
            }
        });
    }
}
