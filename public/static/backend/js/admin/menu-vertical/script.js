var listMenu = {
    _init: function () {
        $('#autotable').PioTable({
            baseUrl: laroute.route('admin.menu-vertical.list')
        });
    },
    popupAdd: function () {
        var jsonLang = JSON.parse(localStorage.getItem('tranlate'));
        $.ajax({
            url: laroute.route('admin.menu-vertical.popup-add'),
            method: 'POST',
            dataType: 'JSON',
            success: function (res) {
                $('#my-modal').html(res.html);
                $('#modal-add').modal('show');
                $('.admin_menu').select2({
                    maximumSelectionLength: 4,
                    placeholder: jsonLang["Chọn chức năng"]
                });
                $.ajax({
                    url: laroute.route('admin.menu-vertical.menu-by-menu-category'),
                    dataType: 'JSON',
                    data: {
                        menu_category_id: $('#admin_menu_category').val(),
                        type: 'vertical'
                    },
                    method: 'POST',
                    success: function (res) {
                        $('.admin_menu').empty();
                        $.map(res.optionMenu, function (a) {
                            $('.admin_menu').append('<option value="' + a.admin_menu_id + '">' + a.admin_menu_name + '</option>');
                        });
                    }
                });

                $('#admin_menu_category').select2({
                    placeholder: 'Select menu group'
                }).on('select2:select', function (e) {
                    $('.admin_menu').empty();

                    $.ajax({
                        url: laroute.route('admin.menu-vertical.menu-by-menu-category'),
                        dataType: 'JSON',
                        data: {
                            menu_category_id: $('#admin_menu_category').val(),
                            type: 'vertical'
                        },
                        method: 'POST',
                        success: function (res) {
                            $('.admin_menu').empty();
                            $.map(res.optionMenu, function (a) {
                                $('.admin_menu').append('<option value="' + a.admin_menu_id + '">' + a.admin_menu_name + '</option>');
                            });
                        }
                    });
                });
            }
        });
    },
    changeStatus: function (id, is_actived) {
        $.ajax({
            url: laroute.route('admin.menu-vertical.update-status'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                admin_menu_function_id: id,
                is_actived: is_actived
            },
            success: function (res) {
                if (res.error == false) {
                    swal.fire(res.message, "", "success");
                    window.location = laroute.route('admin.menu-vertical');
                } else {
                    swal.fire(res.message, '', "error");
                }
            }
        });
    },
    remove: function (id) {
        $.getJSON(laroute.route('translate'), function (json) {
            swal({
                title: json['Thông báo'],
                text: json["Bạn có muốn xóa không?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json['Xóa'],
                cancelButtonText: json['Hủy'],
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: laroute.route('admin.menu-vertical.remove'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            admin_menu_function_id: id,
                        },
                        success: function (res) {
                            if (res.error == false) {
                                swal.fire(res.message, "", "success");
                                window.location = laroute.route('admin.menu-vertical');
                            } else {
                                swal.fire(res.message, '', "error");
                            }
                        }
                    });
                }
            });
        });
    }
}

var add = {
    save: function() {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-add');
            form.validate({
                rules: {
                    admin_menu_category: {
                        required: true
                    },
                    admin_menu: {
                        required: true
                    },
                },
                messages: {
                    admin_menu_category: {
                        required: json['Vui lòng chọn nhóm chức năng.'],
                    },
                    admin_menu: {
                        required: json['Vui lòng chọn chức năng.'],
                    },
                },
            });

            if (!form.valid()) {
                return false;
            }

            $.ajax({
                url: laroute.route('admin.menu-vertical.save-menu-vertical'),
                data: {
                    admin_menu: $('#admin_menu').val(),
                    admin_menu_category: $('#admin_menu_category').val(),
                },
                method: 'POST',
                dataType: "JSON",
                success: function (response) {
                    if (response.error == false) {
                        swal(response.message, "", "success");
                        window.location = laroute.route('admin.menu-vertical');
                    } else {
                        swal(response.message, "", "error")
                    }
                },
                error: function (res) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal(json['Thêm thất bại'], mess_error, "error");
                }
            });

        });
    }
}