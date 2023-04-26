var create = {
    modal_address_contact: function () {
        let customer_id = $('#customer_id').val();

        $.ajax({
            url: laroute.route('admin.order-app.get-list-contact-customer'),
            dataType: 'JSON',
            data: {
                id: customer_id
            },
            method: 'POST',
            success: function (result) {
                $('#popup-customer-contact').empty();
                $('#popup-customer-contact').html(result.url);
                $('#popup-customer-contact').find('#modal-address-contact').modal('show');

                $('#autotable-contact').PioTable({
                    baseUrl: laroute.route('admin.order-app.contact-list')
                });
                $('.btn-search').trigger('click');
            }
        });
    },
    add_contact: function (customer_id) {
        $.ajax({
            url: laroute.route('admin.order-app.add-contact'),
            dataType: 'JSON',
            // data: {
            //     id: customer_id
            // },
            method: 'POST',
            success: function (result) {
                $('.append_address_contact').html(result.url);
                $('#province_id').select2();
                $('#flag_default').val(1);
                $('#province_id').change(function () {
                    $.ajax({
                        url: laroute.route('admin.customer.load-district'),
                        dataType: 'JSON',
                        data: {
                            id_province: $('#province_id').val(),
                        },
                        method: 'POST',
                        success: function (res) {
                            $('.district').empty();
                            $.map(res.optionDistrict, function (a) {
                                $('.district').append('<option value="' + a.id + '">' + a.type + ' ' + a.name + '</option>');
                            });
                        }
                    });
                });
                $('#district_id').select2({
                    ajax: {
                        url: laroute.route('admin.customer.load-district'),
                        data: function (params) {
                            return {
                                id_province: $('#province_id').val(),
                                search: params.term,
                                page: params.page || 1
                            };
                        },
                        dataType: 'JSON',
                        method: 'POST',
                        processResults: function (res) {
                            res.page = res.page || 1;
                            var district_id = $('#district_id_hide').val();
                            return {
                                results: res.optionDistrict.map(function (item) {
                                    return {
                                        id: item.id,
                                        text: item.name
                                    };
                                }),
                                pagination: {
                                    more: res.pagination
                                }
                            };
                        },
                    }
                });
            }
        });
    },
    edit_contact: function (customer_contact_id) {
        $.ajax({
            url: laroute.route('admin.order-app.show-detail-contact'),
            dataType: 'JSON',
            data: {
                id: customer_contact_id
            },
            method: 'POST',
            success: function (result) {
                $('.append_address_contact').html(result.url);
                $('#province_id').select2();
                $('#flag_default').val(1);
                $('#province_id').change(function () {
                    $.ajax({
                        url: laroute.route('admin.customer.load-district'),
                        dataType: 'JSON',
                        data: {
                            id_province: $('#province_id').val(),
                        },
                        method: 'POST',
                        success: function (res) {
                            $('.district').empty();
                            $.map(res.optionDistrict, function (a) {
                                $('.district').append('<option value="' + a.id + '">' + a.type + ' ' + a.name + '</option>');
                            });
                        }
                    });
                })
                $('#district_id').select2({
                    ajax: {
                        url: laroute.route('admin.customer.load-district'),
                        data: function (params) {
                            return {
                                id_province: $('#province_id').val(),
                                search: params.term,
                                page: params.page || 1
                            };
                        },
                        dataType: 'JSON',
                        method: 'POST',
                        processResults: function (res) {
                            res.page = res.page || 1;
                            var district_id = $('#district_id_hide').val();
                            return {
                                results: res.optionDistrict.map(function (item) {
                                    return {
                                        id: item.id,
                                        text: item.name
                                    };
                                }),
                                pagination: {
                                    more: res.pagination
                                }
                            };
                        },
                    }
                });
                // $.ajax({
                //     url: laroute.route('admin.customer.load-district'),
                //     dataType: 'JSON',
                //     data: {
                //         id_province: $('#province_id').val()
                //     },
                //     method: 'POST',
                //     success: function (res) {
                //         var district_id = $('#district_id_hide').val();
                //         $.map(res.optionDistrict, function (a) {
                //             if (a.id == district_id) {
                //                 $('.district').append('<option value="' + a.id + '" selected>' + a.type + ' ' + a.name + '</option>');
                //             } else {
                //                 $('.district').append('<option value="' + a.id + '">' + a.type + ' ' + a.name + '</option>');
                //             }
                //         });
                //     }
                // });
            }
        });
    },
    remove_contact: function (customer_contact_id, address_default) {
        // delete in database
        $.getJSON(laroute.route('translate'), function (json) {
            if(address_default == 1) {
                swal(json['Bạn không thể xoá liên hệ mặc định'], "", "error");
                return false;
            }
            swal({
                title: json['Bạn chắc chắn chứ?'],
                text: json['Bạn sẽ không thể hoàn nguyên điều này!'],  //Bạn sẽ không thể hoàn nguyên điều này!
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json['Đồng ý'],
            }).then(function (result) {
                if (result.value) {
                    $.post(laroute.route('admin.order-app.submit-delete-contact',
                        {id: customer_contact_id}), function () {
                        swal(
                            json['Đã xoá'],
                            json['Liên hệ của bạn đã được xoá'],
                            'success'
                        );
                        $('#autotable-contact').PioTable('refresh');
                    });
                }
            });
        });
    },
    save: function (customer_id) {
        let customer_contact_id_default = $('#customer_contact_id_default').val(); // Sử dụng nếu có click thay đổi default
        let change_default = $('#flag_default').val();
        // let is_checked_contact = $('input[name=address_default]:checked').val();

        $.getJSON(laroute.route('translate'), function (json) {
            // if (is_checked_contact != 'on') {
            //     swal(json['Vui lòng chọn liên hệ mặc định'], "", "warning");
            //     return false;
            // }
            // change_default
            // IF: Không click add hoặc edit -> Chỉ thay đổi địa chỉ mặc định
            // ELSE: Click add hoặc edit
            if (change_default == '') {
                if (customer_contact_id_default != '') {
                    $.ajax({
                        url: laroute.route('admin.order-app.set-default-contact'),
                        data: {
                            idCustomer: customer_id,
                            idContact: customer_contact_id_default,
                        },
                        method: 'POST',
                        dataType: "JSON",
                        success: function (response) {
                            if (response.success == 1) {
                                swal(json["Thiết lập mặc định thành công"], "", "success");
                                $('#popup-customer-contact').find('#modal-address-contact').modal('hide');
                            } else {
                                swal(response.message, "", "error")
                            }
                            $('#autotable-contact').PioTable('refresh');
                        }
                    });
                } else {
                    $('#popup-customer-contact').find('#modal-address-contact').modal('hide');
                    return false;
                }
            } else {
                $.ajax({
                    url: laroute.route('admin.order-app.set-default-contact'),
                    data: {
                        idCustomer: customer_id,
                        // idContact: customer_contact_id_default,
                        idContact: $("input[name='address_default']:checked").val(),
                    },
                    method: 'POST',
                    dataType: "JSON",
                    success: function (response) {
                        $('#autotable-contact').PioTable('refresh');
                    }
                });
            }
            // get addresss contact
            // if (customer_contact_id_default != '') {
            // console.log($("input[name='address_default']:checked").val());
                $.ajax({
                    url: laroute.route('admin.order-app.get-full-address'),
                    dataType: 'JSON',
                    data: {
                        id: $("input[name='address_default']:checked").val(),
                    },
                    method: 'POST',
                    success: function (result) {
                        let province = result['province_name'];
                        let district = result['district_name'];
                        let address = result['full_address'];
                        let postcode = result['postcode'];
                        if (province == null || province == '') {
                            $('.contact-text').text('');
                        } else {
                            if (postcode == '' || postcode == null) {
                                $('.contact-text').text(result['full_address'] + ', '
                                    + result['district_name'] + ', ' + result['province_name'])
                            } else {
                                $('.contact-text').text(result['full_address'] + ', '
                                    + result['district_name'] + ', ' + result['province_name'] + ', ' + result['postcode'])
                            };
                        }
                        // $('#contact_name').val(result.contact_name);
                        // $('#contact_phone').val(result.contact_phone);
                        // $('#customer_contact_code').val(result.customer_contact_code);
                    }
                });
            // }

            let customer_contact_id = $('#get_id_contact').val(); // use for edit
            // Neu -1 -> add
            // neu ton tai customer_contact_id -> update
            if (customer_contact_id == '-1') {
                $.getJSON(laroute.route('translate'), function (json) {
                    var form = $('#form-address-contact');
                    form.validate({
                        rules: {
                            province_id: {
                                required: true
                            },
                            district_id: {
                                required: true
                            },
                            postcode: {
                                required: true
                            },
                            full_address: {
                                required: true
                            }

                        },
                        messages: {
                            province_id: {
                                required: json['Hãy chọn tỉnh/ thành phố'],
                            },
                            district_id: {
                                required: json['Hãy chọn quận/ huyện'],
                            },
                            postcode: {
                                required: json['Nhập post code'],
                            },
                            full_address: {
                                required: json['Hãy nhập địa chỉ'],
                            }
                        },
                    });

                    if (!form.valid()) {
                        return false;
                    }
                    // check email
                    let email = $('#contact_email').val();
                    if (email != '') {
                        if (!isValidEmailAddress(email)) {
                            $('.error_email').text(json['Email không hợp lệ']);
                            return false;
                        }
                    }

                    $.ajax({
                        url: laroute.route('admin.order-app.submit-add-contact'),
                        data: {
                            customer_id: customer_id,
                            province_id: $('#province_id').val(),
                            district_id: $('#district_id').val(),
                            postcode: $('#post_code').val(),
                            full_address: $('#full_address').val(),
                            contact_name: $('#contact_name').val(),
                            contact_phone: $('#contact_phone').val(),
                            contact_email: $('#contact_email').val(),
                        },
                        method: 'POST',
                        dataType: "JSON",
                        success: function (response) {
                            if (response.success == 1) {
                                swal(json["Thêm liên hệ thành công"], "", "success");
                                // window.location = laroute.route('admin.order-app.create');
                                // $('#popup-customer-contact').find('#modal-address-contact').modal('hide');

                            } else {
                                swal(response.message, "", "error")
                            }
                            $('#autotable-contact').PioTable('refresh');
                            form.resetForm();
                        }
                    });
                });
            } else {
                $.getJSON(laroute.route('translate'), function (json) {
                    var form = $('#form-address-contact');
                    form.validate({
                        rules: {
                            province_id: {
                                required: true
                            },
                            district_id: {
                                required: true
                            },
                            postcode: {
                                required: true
                            },
                            full_address: {
                                required: true
                            }

                        },
                        messages: {
                            province_id: {
                                required: json['Hãy chọn tỉnh/ thành phố'],
                            },
                            district_id: {
                                required: json['Hãy chọn quận/ huyện'],
                            },
                            postcode: {
                                required: json['Nhập post code'],
                            },
                            full_address: {
                                required: json['Hãy nhập địa chỉ'],
                            }
                        },
                    });

                    if (!form.valid()) {
                        return false;
                    }
                    // check email
                    let email = $('#contact_email').val();
                    if (email != '') {
                        if (!isValidEmailAddress(email)) {
                            $('.error_email').text(json['Email không hợp lệ']);
                            return false;
                        }
                    }

                    $.ajax({
                        url: laroute.route('admin.order-app.submit-edit-contact'),
                        data: {
                            customer_id: customer_id,
                            customer_contact_id: $('#get_id_contact').val(),
                            province_id: $('#province_id').val(),
                            district_id: $('#district_id').val(),
                            postcode: $('#post_code').val(),
                            full_address: $('#full_address').val(),
                            contact_name: $('#contact_name').val(),
                            contact_phone: $('#contact_phone').val(),
                            contact_email: $('#contact_email').val(),
                        },
                        method: 'POST',
                        dataType: "JSON",
                        success: function (response) {
                            if (response.success == 1) {
                                swal(json["Cập nhật liên hệ thành công"], "", "success");
                                // Sua lai shipping address view order-app
                                $.ajax({
                                    url: laroute.route('admin.order-app.get-full-address'),
                                    dataType: 'JSON',
                                    data: {
                                        id: $("input[name='address_default']:checked").val(),
                                    },
                                    method: 'POST',
                                    success: function (result) {
                                        let province = result['province_name'];
                                        let district = result['district_name'];
                                        let address = result['full_address'];
                                        let postcode = result['postcode'];
                                        if (province == null || province == '') {
                                            $('.contact-text').text('');
                                        } else {
                                            if (postcode == '' || postcode == null) {
                                                $('.contact-text').text(result['full_address'] + ', '
                                                    + result['district_name'] + ', ' + result['province_name'])
                                            } else {
                                                $('.contact-text').text(result['full_address'] + ', '
                                                    + result['district_name'] + ', ' + result['province_name'] + ', ' + result['postcode'])
                                            };
                                        }
                                        // $('#contact_name').val(result.contact_name);
                                        // $('#contact_phone').val(result.contact_phone);
                                        // $('#customer_contact_code').val(result.customer_contact_code);
                                    }
                                });
                                // window.location = laroute.route('admin.order-app.create');
                                $('#popup-customer-contact').find('#modal-address-contact').modal('hide');
                            } else {
                                swal(response.message, "", "error")
                            }
                            $('#autotable-contact').PioTable('refresh');
                        }
                    });
                });
            }
        });
    },
    default: function (idCus, idContact) {
        $('#customer_contact_id_default').val(idContact);
    }
};

function isValidEmailAddress(emailAddress) {
    let pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
    return pattern.test(emailAddress);
}

$(document).ready(function () {
    // create.show_contact();
    // find detail contact default of customer
    $('#province_id').select2();
    $('#district_id').select2({
        placeholder: 'Choose town',
    });
});
