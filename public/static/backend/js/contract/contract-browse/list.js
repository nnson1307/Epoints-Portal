$('#autotable').PioTable({
    baseUrl: laroute.route('contract.contract-browse.list')
});

var listBrowse = {
    confirm: function (id) {
        $.getJSON(laroute.route('translate'), function (json) {
            swal({
                title: json['Thông báo'],
                text: json["Bạn có muốn duyệt không?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json['Đồng ý'],
                cancelButtonText: json['Hủy'],
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: laroute.route('contract.contract-browse.confirm'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            contract_browse_id: id
                        },
                        success: function (res) {
                            if (res.error == false) {
                                swal.fire(res.message, "", "success");
                                // $('#autotable').PioTable('refresh');

                                window.location.reload();
                            } else {
                                swal.fire(res.message, '', "error");
                            }
                        }
                    });
                }
            });
        });
    },
    showModalRefuse: function (id) {
        $.ajax({
            url: laroute.route('contract.contract-browse.modal-refuse'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                contract_browse_id: id
            },
            success: function (res) {
                $('#my-modal').html(res.html);
                //Show modal chọn loại HĐ
                $('#modal-reason-refuse').modal('show');
            }
        });
    },
    refuse: function (id) {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-refuse');

            form.validate({
                rules: {
                    reason_refuse: {
                        required: true,
                        maxlength: 190
                    }
                },
                messages: {
                    reason_refuse: {
                        required: json['Lý do từ chối không được trống'],
                        maxlength: json['Lý do từ chối tối đa 190 kí tự']
                    }
                },
            });

            if (!form.valid()) {
                return false;
            }

            $.ajax({
                url: laroute.route('contract.contract-browse.refuse'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    contract_browse_id: id,
                    reason_refuse: $('#reason_refuse').val()
                },
                success: function (res) {
                    if (res.error == false) {
                        swal(res.message, "", "success").then(function (result) {
                            if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                $('#modal-reason-refuse').modal('hide');
                                //
                                // $('#autotable').PioTable('refresh');
                                window.location.reload();
                            }
                            if (result.value == true) {
                                $('#modal-reason-refuse').modal('hide');

                                // $('#autotable').PioTable('refresh');
                                window.location.reload();
                            }
                        });
                    } else {
                        swal(res.message, '', "error");
                    }
                }
            });
        });
    }
};