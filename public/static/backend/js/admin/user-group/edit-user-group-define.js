$('.select-2').select2({minimumResultsForSearch: -1});

var arrayAccount = [];
var arrayAccount2 = [];
var arrayCustomerId = [];
var arrayCustomerId2 = [];
var arrayAccountRemove = [];

var userGroupDefine = {
    removeRow: function (t) {
        $(t).parentsUntil('tbody').remove();
    },
    removeRowTr: function (t, id, page) {
        if (jQuery.inArray(id, arrayCustomerId) !== -1) {
            arrayCustomerId = jQuery.grep(arrayCustomerId, function (value) {
                return value != id;
            });
        }
        pageClick3(page);
        $(t).closest('tr').remove();
    },
    showModalAddUser: function () {
        $('#modal-add-user').modal('show');
        $('#search-trigger').trigger('click');
        $('.select-2').select2({minimumResultsForSearch: -1});
    },
    searchAddUser: function () {
        var fullName = $('#define_full_name_3').val();
        var phone = $('#define_phone_3').val();
        var isActive = $('#define_is_actived_3').val();
        $.ajax({
            url: laroute.route('admin.customer-group-filter.search-all-customer'),
            method: "POST",
            async: false,
            data: {
                fullName: fullName,
                phone: phone,
                isActive: isActive,
            },
            success: function (res) {

                $('#modal-add-user-2 #table-list-user').empty();
                $('#modal-add-user-2 #table-list-user').append(res.view);
                $('.check-box-choose-user').each(function () {
                    // var phone = $(this).parents('label').find('.phone-2').val();
                    // if (jQuery.inArray(phone, arrayAccount2) !== -1) {
                    //     $(this).prop('checked', true);
                    // } else {
                    //     $(this).prop('checked', false);
                    // }
                    var id2 = $(this).parents('label').find('.customer-id-2').val();
                    if (jQuery.inArray(id2, arrayCustomerId2) !== -1) {
                        $(this).prop('checked', true);
                    } else {
                        $(this).prop('checked', false);
                    }
                });
            }
        });
    },
    selectAll1: function (t) {
        if ($(t).is(":checked")) {
            $('.check-box-choose-user1').prop('checked', true);
            $('.check-box-choose-user1').each(function () {
                // var phone = $(this).parents('label').find('.phone-1').val();
                // if (jQuery.inArray(phone, arrayAccount) === -1) {
                //     arrayAccount.push(phone)
                // }
                var id2 = $(this).parents('label').find('.customer-id-1').val();
                if (jQuery.inArray(id2, arrayCustomerId) === -1) {
                    arrayAccount.push(id2)
                }
            });
        } else {
            $('.check-box-choose-user1').prop('checked', false);

            $('.check-box-choose-user1').each(function () {
                // var phone = $(this).parents('label').find('.phone-1').val();
                // if (jQuery.inArray(phone, arrayAccount) !== -1) {
                //     arrayAccount = jQuery.grep(arrayAccount, function (value) {
                //         return value != phone;
                //     });
                // }
                var id2 = $(this).parents('label').find('.customer-id-1').val();
                if (jQuery.inArray(id2, arrayCustomerId) !== -1) {
                    arrayCustomerId = jQuery.grep(arrayCustomerId, function (value) {
                        return value != id2;
                    });
                }
            });

        }
    },
    selectAll2: function (t) {
        if ($(t).is(":checked")) {
            $('.check-box-choose-user').prop('checked', true);
            $('.check-box-choose-user').each(function () {
                // var phone = $(this).parents('label').find('.phone-2').val();
                // if (jQuery.inArray(phone, arrayAccount2) === -1) {
                //     arrayAccount2.push(phone)
                // }
                var id2 = $(this).parents('label').find('.customer-id-2').val();
                if (jQuery.inArray(id2, arrayCustomerId2) === -1) {
                    arrayCustomerId2.push(id2)
                }
            });
        } else {
            $('.check-box-choose-user').prop('checked', false);

            $('.check-box-choose-user').each(function () {
                // var phone = $(this).parents('label').find('.phone-2').val();
                // if (jQuery.inArray(phone, arrayAccount2) !== -1) {
                //     arrayAccount2 = jQuery.grep(arrayAccount2, function (value) {
                //         return value != phone;
                //     });
                // }
                var id2 = $(this).parents('label').find('.customer-id-2').val();
                if (jQuery.inArray(id2, arrayCustomerId2) !== -1) {
                    arrayCustomerId2 = jQuery.grep(arrayCustomerId2, function (value) {
                        return value != id2;
                    });
                }
            });

        }
    },
    showModalImportExcel: function () {
        $('#import-excel').modal('show');
        $('#modal-add-user').modal('hide');
    },
    showNameFile: function () {
        var fileNamess = $('input[type=file]').val();
        $('#show').val(fileNamess);
    },
    import: function () {
        if ($('#show').val() == '') {
            $('.error-input-excel').text('Vui lòng chọn file');
        } else {
            $('.error-input-excel').text('');
            var file_data = $('#file_excel').prop('files')[0];
            var form_data = new FormData();
            form_data.append('file', file_data);
            $.ajax({
                url: laroute.route("admin.customer-group-filter.read-excel"),
                method: "POST",
                data: form_data,
                contentType: false,
                cache: false,
                processData: false,
                async: false,
                success: function (res) {
                    if (res.success == 10) {
                        swal.fire('Thêm thất bại', '', "error");
                    } else {
                        var success = 0;
                        $.map(res.arrayPhone, function (value, key) {
                            if (jQuery.inArray(value, arrayCustomerId) === -1) {
                                success += 1;
                                arrayCustomerId.push(value);
                            }
                        });
                        userGroupDefine.searchWhereInUser(arrayCustomerId);
                        let mess = '';
                        mess = mess.concat('Số lượng khách hàng thành công ' + (success) + '<br/>');
                        mess = mess.concat('Số lượng khách hàng thất bại ' + (res.total - success) + '<br/>');
                        swal.fire('Thêm thành công', mess, "success");
                    }
                }
            });
        }
    },
    searchWhereInUser: function (arrayUser, phone, fullName, isActive) {
        console.log(arrayUser, phone, fullName, isActive)
        $.ajax({
            url: laroute.route('admin.customer-group-filter.search-where-in-customer'),
            method: "POST",
            async: false,
            data: {
                arrayUser: arrayUser,
                phone: phone,
                fullName: fullName,
                isActive: isActive,

            },
            success: function (res) {
                $('#modal-add-user #table-list-user').empty();
                $('#modal-add-user #table-list-user').append(res);
                $('#import-excel').modal('hide');
                $('.check-box-choose-user1').each(function () {
                    var p = $(this).parents('label').find('.phone-1').val();
                    if (jQuery.inArray(p, arrayAccountRemove) !== -1) {
                        $(this).prop('checked', false);
                    }
                });
            }
        });
    },
    showModalAddUser2: function () {
        //Show modal thêm user 2 (Thêm user từ db)
        // arrayAccount2 = [];
        arrayCustomerId2 = [];

        userGroupDefine.searchAddUser();

        $('#modal-add-user-2').modal('show');
        $('#modal-add-user').modal('hide');
    },
    chooseUser1: function (t) {
        if ($(t).is(":checked")) {
            // var phone = $(t).parents('label').find('.phone-1').val();
            // if (jQuery.inArray(phone, arrayAccount) === -1) {
            //     arrayAccount.push(phone)
            // }
            var id2 = $(t).parents('label').find('.customer-id-1').val();
            if (jQuery.inArray(id2, arrayCustomerId) === -1) {
                arrayCustomerId.push(id2)
            }

            // if (jQuery.inArray(phone, arrayAccountRemove) !== -1) {
            //     arrayAccountRemove = jQuery.grep(arrayAccountRemove, function (value) {
            //         return value != phone;
            //     });
            // }
            if (jQuery.inArray(id2, arrayAccountRemove) !== -1) {
                arrayAccountRemove = jQuery.grep(arrayAccountRemove, function (value) {
                    return value != id2;
                });
            }
        } else {
            // var phone = $(t).parents('label').find('.phone-1').val();
            // if (jQuery.inArray(phone, arrayAccount) !== -1) {
            //     arrayAccount = jQuery.grep(arrayAccount, function (value) {
            //         return value != phone;
            //     });
            // }
            // if (jQuery.inArray(phone, arrayAccountRemove) === -1) {
            //     arrayAccountRemove.push(phone)
            // }
            var id2 = $(t).parents('label').find('.customer-id-1').val();
            if (jQuery.inArray(id2, arrayCustomerId) !== -1) {
                arrayCustomerId = jQuery.grep(arrayCustomerId, function (value) {
                    return value != id2;
                });
            }
            if (jQuery.inArray(id2, arrayAccountRemove) === -1) {
                arrayAccountRemove.push(id2)
            }
        }

    },
    chooseUser2: function (t) {
        if ($(t).is(":checked")) {
            // var phone = $(t).parents('label').find('.phone-2').val();
            // if (jQuery.inArray(phone, arrayAccount2) === -1) {
            //     arrayAccount2.push(phone)
            // }
            var id2 = $(t).parents('label').find('.customer-id-2').val();
            if (jQuery.inArray(id2, arrayCustomerId2) === -1) {
                arrayCustomerId2.push(id2)
            }
        } else {
            // var phone = $(t).parents('label').find('.phone-2').val();
            // if (jQuery.inArray(phone, arrayAccount2) !== -1) {
            //     arrayAccount2 = jQuery.grep(arrayAccount2, function (value) {
            //         return value != phone;
            //     });
            // }
            var id2 = $(t).parents('label').find('.customer-id-2').val();
            if (jQuery.inArray(phone, arrayCustomerId2) !== -1) {
                arrayCustomerId2 = jQuery.grep(arrayCustomerId2, function (value) {
                    return value != id2;
                });
            }
        }
    },
    addUser2: function () {
        // $.map(arrayAccount2, function (value, key) {
        //     if (jQuery.inArray(value, arrayAccount) === -1) {
        //         arrayAccount.push(value);
        //     }
        // });
        $.map(arrayCustomerId2, function (value, key) {
            if (jQuery.inArray(value, arrayCustomerId) === -1) {
                arrayCustomerId.push(value);
            }
        });
        userGroupDefine.searchWhereInUser(arrayCustomerId, '', '', '');
        // userGroupDefine.searchWhereInUser(arrayAccount, '', '', '');
        $('#modal-add-user-2').modal('hide');
    },
    searchPopup1: function () {
        var phone = $('#modal-add-user #define_phone_2').val();
        var fullName = $('#modal-add-user #define_full_name_2').val();
        var isActive = $('#modal-add-user #define_is_actived_2').val();

        userGroupDefine.searchWhereInUser(arrayCustomerId, phone, fullName, isActive);
        // userGroupDefine.searchWhereInUser(arrayAccount, phone, fullName, isActive);
    },
    addUserGroupDefine: function () {
        $.map(arrayAccount, function (value, key) {
            if (jQuery.inArray(value, arrayAccountRemove) !== -1) {
                arrayAccount = jQuery.grep(arrayAccount, function (a) {
                    return a != value;
                });
            }
        });
        $.map(arrayCustomerId, function (value, key) {
            if (jQuery.inArray(value, arrayAccountRemove) !== -1) {
                arrayCustomerId = jQuery.grep(arrayCustomerId, function (a) {
                    return a != value;
                });
            }
        });

        $.ajax({
            url: laroute.route('admin.customer-group-filter.add-customer-group-define'),
            method: "POST",
            data: {arrayAccount: arrayCustomerId},
            success: function (res) {
                $('.table-list-user-group-define').empty();
                $('.table-list-user-group-define').append(res);
                $('#modal-add-user').modal('hide');
            }
        });
    },
    searchUserDefine: function () {
        var phone = $('#define_phone_1').val();
        var fullName = $('#define_full_name_1').val();
        var isActive = $('#define_is_actived_1').val();
        $.ajax({
            url: laroute.route('admin.customer-group-filter.add-customer-group-define'),
            method: "POST",
            data: {
                phone: phone,
                fullName: fullName,
                isActive: isActive,
                arrayAccount: arrayCustomerId,
            },
            success: function (res) {
                $('.table-list-user-group-define').empty();
                $('.table-list-user-group-define').append(res);
                $('#modal-add-user').modal('hide');
            }
        });
    },
    save: function (type) {
        var name = $('#name').val();
        var error = $('.error-name');
        $.getJSON(laroute.route('translate'), function (json) {
            if (name == '') {
                error.text(json['Vui lòng nhập tên nhóm khách hàng']);
            } else {
                if (name.length > 255) {
                    error.text(json['Độ dài tối đa 255 ký tự']);
                } else {
                    error.text('');
                    if (arrayCustomerId.length == 0) {
                        swal.fire(json['Thông báo'], json['Vui lòng thêm khách hàng'], "error");
                    } else {
                        $.ajax({
                            url: laroute.route('admin.customer-group-filter.update-user-define'),
                            method: "POST",
                            data: {
                                id: $('#customer_group_id').val(),
                                name: name,
                                arrayAccount: arrayCustomerId
                            },
                            success: function (res) {
                                if (res.error == false) {
                                    swal.fire(json['Thêm thành công!'], '', "success").then(function (result) {
                                        if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                            if (type == 0) {
                                                window.location.href = laroute.route('admin.customer-group-filter');
                                            } else {
                                                location.reload();
                                            }
                                        }
                                        if (result.value == true) {
                                            if (type == 0) {
                                                window.location.href = laroute.route('admin.customer-group-filter');
                                            } else {
                                                location.reload();
                                            }
                                        }
                                    });
                                }
                            },
                            error: function (res) {
                                let mess_error = '';
                                $.map(res.responseJSON.errors, function (a) {
                                    mess_error = mess_error.concat(a + '<br/>');
                                });
                                console.log(mess_error);
                                swal.fire(json['Thêm thất bại!'], mess_error, "error");
                            }
                        });
                    }
                }
            }
        });
    },
    init: function () {
        $.ajax({
            url: laroute.route('admin.customer-group-filter.get-customer-by-group-define'),
            method: "POST",
            data: {
                id: $('#customer_group_id').val()
            },
            success: function (res) {
                // $.map(res, function (value, key) {
                //     if (jQuery.inArray(value, arrayAccount) === -1) {
                //         arrayAccount.push(value.phone);
                //     }
                // });
                $.map(res, function (value, key) {
                    if (jQuery.inArray(value, arrayCustomerId) === -1) {
                        arrayCustomerId.push(value.customer_id.toString());
                    }
                });
                userGroupDefine.searchUserDefine();
            }
        });
    }
};
userGroupDefine.init();
$('#import-excel').on('hidden.bs.modal', function () {
    $('#modal-add-user').modal('show');
    $('.select-2').select2({minimumResultsForSearch: -1});
});
$('#modal-add-user-2').on('hidden.bs.modal', function () {
    $('#modal-add-user').modal('show');
    $('.select-2').select2({minimumResultsForSearch: -1});
});


function pageClick2(page) {
    var fullName = $('#define_full_name_3').val();
    var phone = $('#define_phone_3').val();
    var isActive = $('#define_is_actived_3').val();
    $.ajax({
        url: laroute.route('admin.customer-group-filter.search-all-customer'),
        method: "POST",
        async: false,
        data: {
            phone: phone,
            fullName: fullName,
            isActive: isActive,
            page: page
        },
        success: function (res) {
            $('#modal-add-user-2 #table-list-user').empty();
            $('#modal-add-user-2 #table-list-user').append(res.view);
            $('.check-box-choose-user').each(function () {
                // var phone = $(this).parents('label').find('.phone-2').val();
                // if (jQuery.inArray(phone, arrayAccount2) !== -1) {
                //     $(this).prop('checked', true);
                // } else {
                //     $(this).prop('checked', false);
                // }
                var id2 = $(this).parents('label').find('.customer-id-2').val();
                if (jQuery.inArray(id2, arrayCustomerId2) !== -1) {
                    $(this).prop('checked', true);
                } else {
                    $(this).prop('checked', false);
                }
            });

        }
    });
}

function pageClick1(page) {
    var phone = $('#modal-add-user #define_phone_2').val();
    var fullName = $('#modal-add-user #define_full_name_2').val();
    var isActive = $('#modal-add-user #define_is_actived_2').val();
    $.map(arrayAccountRemove, function (value, key) {
        arrayCustomerId.push(value);
    });
    $.ajax({
        url: laroute.route('admin.customer-group-filter.search-where-in-customer'),
        method: "POST",
        async: false,
        data: {
            phone: phone,
            fullName: fullName,
            isActive: isActive,
            page: page,
            arrayUser: arrayCustomerId
        },
        success: function (res) {
            $('#modal-add-user #table-list-user').empty();
            $('#modal-add-user #table-list-user').append(res);
            $('.check-box-choose-user1').each(function () {
                // var phone = $(this).parents('label').find('.phone-1').val();
                // if (jQuery.inArray(phone, arrayAccount) !== -1) {
                //     $(this).prop('checked', true);
                // } else {
                //     $(this).prop('checked', false);
                // }
                var id2 = $(this).parents('label').find('.customer-id-1').val();
                if (jQuery.inArray(id2, arrayCustomerId) !== -1) {
                    $(this).prop('checked', true);
                } else {
                    $(this).prop('checked', false);
                }
            });
            $('.check-box-choose-user1').each(function () {
                // var p = $(this).parents('label').find('.phone-1').val();
                // if (jQuery.inArray(p, arrayAccountRemove) !== -1) {
                //     $(this).prop('checked', false);
                // }
                var id2 = $(this).parents('label').find('.customer-id-1').val();
                if (jQuery.inArray(id2, arrayAccountRemove) !== -1) {
                    $(this).prop('checked', false);
                }
            });

        }
    });
}

function pageClick3(page) {
    var phone = $('#define_phone_1').val();
    var fullName = $('#define_full_name_1').val();
    var isActive = $('#define_is_actived_1').val();
    $.ajax({
        url: laroute.route('admin.customer-group-filter.add-customer-group-define'),
        method: "POST",
        data: {
            phone: phone,
            fullName: fullName,
            isActive: isActive,
            arrayAccount: arrayCustomerId,
            page: page
        },
        success: function (res) {
            $('.table-list-user-group-define').empty();
            $('.table-list-user-group-define').append(res);
            $('#modal-add-user').modal('hide');
        }
    });
}

$.ajax({
    url: laroute.route('admin.customer-group-filter.get-customer-in-group-define'),
    method: "POST",
    data:{id: $('#customer_group_id').val()},
    success: function (res) {

    }
});
