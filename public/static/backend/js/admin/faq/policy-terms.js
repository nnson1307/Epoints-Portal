var policyTerms = {
    init: function () {
        $('.select2').select2({
            'width': '100%'
        });

        $('#faq_content').summernote({
            placeholder: '',
            tabsize: 2,
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontname', ['fontname', 'fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']],
            ]
        });
    },
    filter: function () {
        $('#form-filter').submit();
    },
    sort: function (o, col) {
        var sort = $(o).data('sort');
        switch (col) {
            case 'faq_type':
                $("#sort_faq_type").val(sort);
                $("#sort_faq_title").val(null);
                break;
            case 'faq_title':
                $("#sort_faq_type").val(null);
                $("#sort_faq_title").val(sort);
                break;
        }
        policyTerms.filter();
    },
    inputFiler: function () {
        $('.input-filter').show();
    },
    save: function (is_quit = 0) {
        let form = $('#form-submit');
        $.getJSON(laroute.route('admin.validation'), function (json) {
            form.validate({
                rules: {
                    faq_title: {
                        required: true,
                        maxlength: 250
                    }
                },
                messages: {
                    faq_title: {
                        required: json.faq.faq_title_required,
                        maxlength: json.faq.faq_title_max
                    }
                }
            });

            if (form.valid()) {
                let url = ($('#faq_id').length) ? laroute.route('admin.policy-terms.update') : laroute.route('admin.policy-terms.store');
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
                                        window.location.href = laroute.route('admin.policy-terms.create');
                                    }
                                } else {
                                    window.location.href = laroute.route('admin.policy-terms.index');
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
                title: json.policy_terms.TITLE_POPUP,
                html: json.policy_terms.HTML_POPUP,
                buttonsStyling: false,

                confirmButtonText: json.policy_terms.YES_BUTTON,
                confirmButtonClass: "btn btn-sm btn-default btn-bold",

                showCancelButton: true,
                cancelButtonText: json.policy_terms.CANCEL_BUTTON,
                cancelButtonClass: "btn btn-sm btn-bold btn-brand"
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: laroute.route('admin.policy-terms.destroy'),
                        method: 'POST',
                        data: {
                            faq_id: id
                        },
                        success: function (data) {
                            if (data.error === 0) {
                                location.reload();
                            } else {
                                Swal.fire(data.message, "", "error");
                            }
                        }
                    });
                }
            });
        });
    }
};
policyTerms.init();
