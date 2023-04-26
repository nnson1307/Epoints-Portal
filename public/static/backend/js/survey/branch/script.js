var survey = {
    back: function (id) {
        $.getJSON(laroute.route('survey.validation'), function (json) {
            swal.fire({
                title: json.cancel_back_title,
                html: json.cancel_back_content_outlet_apply,
                buttonsStyling: false,

                confirmButtonText: json.btn_yes,
                confirmButtonClass: "btn btn-sm btn-default btn-bold btn_yes",

                showCancelButton: true,
                cancelButtonText: json.btn_no,
                cancelButtonClass: "btn btn-sm btn-bold btn-brand btn_cancel"
            }).then(function (result) {
                if (result.value) {
                    window.location.href = laroute.route('survey.show-branch', {id: id});
                }
            });
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
                        data: {id: id},
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
};