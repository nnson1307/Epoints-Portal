var survey = {
    init: function () {
        survey.loadListSurvey()
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
                                survey.loadListSurvey();
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
    loadListSurvey: function (page = 1) {
        let nameOrCodeSurvey = $("input[name='name_or_code']").val();
        let dateCreated = $("input[name='created_at']").val();
        let status = $("#status option:selected").val();
        let perpage = $('#perpage option:selected').val();
        $.ajax({
            url: laroute.route('survey.loadAll'),
            method: "POST",
            data: {
                nameOrCodeSurvey: nameOrCodeSurvey,
                dateCreated: dateCreated,
                status: status,
                page: page,
                perpage: perpage
            },
            success: function (res) {
                $('.table-content').html(res.view);
                $('.selectpicker').selectpicker('show');

            }
        });
    },
    resetSearchSurvey: function () {
        $("input[name='name_or_code']").val('');
        $("input[name='created_at']").val('');
        $("#status").val('');
        $("#status").select2();
        survey.loadListSurvey();
    },
    showModalCoppy: (idSurvey) => {
        $.ajax({
            url: laroute.route('survey.show-modal-coppy'),
            method: "POST",
            data: {
                idSurvey
            },
            success: function (res) {
                $("#modal").html(res.view);
                $("#coppy_survey").modal('show');
            }
        });
    },
    Coppy: (idSurvey) => {
        $.ajax({
            url: laroute.route('survey.coppy'),
            method: "POST",
            data: {
                idSurvey
            },
            success: function (res) {
                if (res.error == false) {
                    swal.fire(res.message, "", "success").then(function () {
                        window.location.reload();
                    });
                }
            }, error: function (res) {
                swal.fire(res.message, "", "error").then(function () {
                    window.location.reload();
                });
            }
        });
    },
    showModalCoppyUrl: (idSurvey) => {
        $.ajax({
            url: laroute.route('survey.show-modal-coppy-url'),
            method: "POST",
            data: {
                idSurvey
            },
            success: function (res) {
                $("#modal").html(res.view);
                $("#coppyUrl_survey").modal('show');
            }
        });
    },
    CoppyURL: () => {
        let coppyUrl = $("#coppy_url");
        let coppyText = $("#text_url");
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($("text_url").val()).select();
        document.execCommand("copy");
        $temp.remove();
        if (coppyUrl.is(':checked')) {
        }
    }

};

survey.init();

