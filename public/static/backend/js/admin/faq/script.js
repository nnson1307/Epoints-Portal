var faq = {
    init: function () {
        $('.select2').select2({
            'width': '100%'
        });
        // faq.updateStatus();
    },
    filter: function () {
        $('#form-filter').submit();
    },
    sort: function (o, col) {
        var sort = $(o).data('sort');
        switch (col) {
            case 'faq_group_title_vi':
                $("#sort_faq_group_title_vi").val(sort);
                $("#sort_faq_group_title_en").val(null);
                $("#sort_faq_title_vi").val(null);
                $("#sort_faq_title_en").val(null);
                $("#sort_faq_position").val(null);
                $("#sort_is_actived").val(null);
                break;
            case 'faq_group_title_en':
                $("#sort_faq_group_title_vi").val(null);
                $("#sort_faq_group_title_en").val(sort);
                $("#sort_faq_title_vi").val(null);
                $("#sort_faq_title_en").val(null);
                $("#sort_faq_position").val(null);
                $("#sort_is_actived").val(null);
                break;
            case 'faq_title_vi':
                $("#sort_faq_group_title_vi").val(null);
                $("#sort_faq_group_title_en").val(null);
                $("#sort_faq_title_vi").val(sort);
                $("#sort_faq_title_en").val(null);
                $("#sort_faq_position").val(null);
                $("#sort_is_actived").val(null);
                break;
            case 'faq_title_en':
                $("#sort_faq_group_title_vi").val(null);
                $("#sort_faq_group_title_en").val(null);
                $("#sort_faq_title_vi").val(null);
                $("#sort_faq_title_en").val(sort);
                $("#sort_faq_position").val(null);
                $("#sort_is_actived").val(null);
                break;
            case 'faq_position':
                $("#sort_faq_group_title_vi").val(null);
                $("#sort_faq_group_title_en").val(null);
                $("#sort_faq_title_vi").val(null);
                $("#sort_faq_title_en").val(null);
                $("#sort_faq_position").val(sort);
                $("#sort_is_actived").val(null);
                break;
            case 'is_actived':
                $("#sort_faq_group_title_vi").val(null);
                $("#sort_faq_group_title_en").val(null);
                $("#sort_faq_title_vi").val(null);
                $("#sort_faq_title_en").val(null);
                $("#sort_faq_position").val(null);
                $("#sort_is_actived").val(sort);
                break;
        }
        faq.filter();
    },
    inputFiler: function () {
        $('.input-filter').show();
    },
    save: function (is_quit = 0) {
        let form = $('#form-submit');
        $.getJSON(laroute.route('admin.validation'), function (json) {
            form.validate({
                rules: {
                    faq_title_vi: {
                        required: true,
                        maxlength: 250
                    },
                    faq_title_en: {
                        required: true,
                        maxlength: 250
                    },
                    faq_position: {
                        required: true,
                        number: true,
                        max: 1000000
                    },
                    faq_group: {
                        required: true,
                    }
                },
                messages: {
                    faq_title_vi: {
                        required: json.faq.faq_title_required_vi,
                        maxlength: json.faq.faq_title_max_vi
                    },
                    faq_title_en: {
                        required: json.faq.faq_title_required_en,
                        maxlength: json.faq.faq_title_max_en
                    },
                    faq_position: {
                        required: json.faq.faq_position_required,
                        number: json.faq.faq_position_number,
                        max: json.faq.faq_group_position_max
                    },
                    faq_group: {
                        required: json.faq.faq_group_required,
                    }
                }
            });

            if (form.valid()) {
                let url = ($('#faq_id').length) ? laroute.route('admin.faq.update') : laroute.route('admin.faq.store');
                $.ajax({
                    url: url,
                    method: 'POST',
                    dataType: 'JSON',
                    data: form.serialize(),
                    success: function (res) {
                        if (!res.error) {
                            Swal.fire(res.message, "", "success").then(function () {
                                if (is_quit === 0) {
                                    if ($('#faq_id').length) {
                                        window.location.reload();
                                    } else {
                                        window.location.href = laroute.route('admin.faq.create');
                                    }
                                } else {
                                    window.location.href = laroute.route('admin.faq.index');
                                }
                            });
                        } else {
                            Swal.fire(res.message, "", "error");
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
    remove: function (id) {
        $.getJSON(laroute.route('admin.validation'), function (json) {
            Swal.fire({
                title: json.faq.TITLE_POPUP,
                html: json.faq.HTML_POPUP,
                buttonsStyling: false,

                confirmButtonText: json.faq.YES_BUTTON,
                confirmButtonClass: "btn btn-sm btn-default btn-bold",

                showCancelButton: true,
                cancelButtonText: json.faq.CANCEL_BUTTON,
                cancelButtonClass: "btn btn-sm btn-bold btn-brand"
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: laroute.route('admin.faq.destroy'),
                        method: 'POST',
                        data: {
                            faq_id: id
                        },
                        success: function (data) {
                            if (data.error === 0) {
                                // location.reload();
                                $('#autotable').PioTable('refresh');
                            } else {
                                Swal.fire(data.message, "", "error");
                            }
                        }
                    });
                }
            });
        });
    },
    updateStatus: function (id, obj) {
        var is_actived = 0;

        if ($(obj).is(':checked')) {
            is_actived = 1;
        }
        $.ajax({
            url: laroute.route('admin.faq.update-status'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                faq_id: id,
                is_actived: is_actived
            },
            success: function (res) {
                if (!res.error) {
                    swal(res.message, '', 'success');
                    $('#autotable').PioTable('refresh');
                } else {
                    swal(res.message, '', 'error');
                }
            }
        });
    }
};
faq.init();

$('#autotable').PioTable({
    baseUrl: laroute.route('admin.faq.list')
});