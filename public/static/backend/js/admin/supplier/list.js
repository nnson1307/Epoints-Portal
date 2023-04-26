function clear() {
    $('#modalAdd #supplierName').val('');
    $('#modalAdd #description').val('');
    $('#modalAdd #address').val('');
    $('#modalAdd #contact_name').val('');
    $('#modalAdd #contact_title').val('');
    $('#modalAdd #contact_phone').val('');
    $('#modalAdd .error-supplier-name').text('');
}

var Supplier = {
        remove: function (obj, id) {
            $.getJSON(laroute.route('translate'), function (json) {
                $(obj).closest('tr').addClass('m-table__row--danger');

                swal({
                    title: json['Thông báo'],
                    text: json["Bạn có muốn xóa không!"],
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: json['Xóa'],
                    cancelButtonText: json['Hủy'],
                    onClose: function () {
                        $(obj).closest('tr').removeClass('m-table__row--danger');
                    }
                }).then(function (result) {
                    if (result.value) {
                        $.post(laroute.route('admin.supplier.remove', {id: id}), function () {
                            swal(
                                json['Xóa thành công.'],
                                '',
                                'success'
                            );
                            $('#autotable').PioTable('refresh');
                        });
                    }
                });
            });
        },
        clearAdd: function () {
            clear();
        },
        add: function (parameters) {
            $('#parameters').val(parameters);
            $.getJSON(laroute.route('translate'), function (json) {
                var form = $('#form-add');
                form.validate({
                    rules: {
                        supplierName: {
                            required: true,
                            maxlength: 190
                        },
                        address: {
                            required: true,
                        },
                        contact_name: {
                            required: true,
                        },
                        contact_phone: {
                            required: true,
                            minlength: 10,
                            maxlength: 11,
                        },
    
                    },
                    messages: {
                        supplierName: {
                            required: json['Vui lòng nhập tên nhà cung cấp'],
                            maxlength: json['Tối đa 190 ký tự']
                        },
                        address: {
                            required: json['Vui lòng nhập địa chỉ'],
                        },
                        contact_name: {
                            required: json['Vui lòng nhập tên người đại diện']
                        },
                        contact_phone: {
                            required: json['Vui lòng nhập số điện thoại'],
                            minlength: json['SĐT tối thiểu 10 số'],
                            maxlength: json['Vui lòng nhập lại SĐT'],
                        }
                    },
                });
    
                if (!form.valid()) {
                    return false;
                }
    
                var input = $('#type_add').val();
                var group_name = $('#group_name').val();
                let parameters = $('#parameters');
                    let supplierName = $('#supplierName');
                    let description = $("#description");
                    let address = $("#address");
                    let contactName = $("#contact_name");
                    let contactTitle = $("#contact_title");
                    let contactPhone = $("#contact_phone");
                $.ajax({
                    url: laroute.route('admin.supplier.add'),
                        data: {
                            supplierName: supplierName.val(),
                            description: description.val(),
                            address: address.val(),
                            contactName: contactName.val(),
                            contactTitle: contactTitle.val(),
                            contactPhone: contactPhone.val(),
                            parameters: parameters.val()
                        },
                        method: "POST",
                        dataType: "JSON",
                        success: function (data) {
                            if (data.status == 1) {
                                if (data.parameters != 0) {
                                    $('#modalAdd').modal('hide');
                                }
                                swal(
                                    json['Thêm nhà cung cấp thành công'],
                                    '',
                                    'success'
                                );
                                $('#autotable').PioTable('refresh');
                                clear();
                            } else {
                                $('.error-supplier-name').css('color', 'red');
                                $('.error-supplier-name').text(json['Nhà cung cấp đã tồn tại']);
                            }
                        }
                });
            });
            // $('#form-add').validate({
            //     rules: {
            //         supplierName: {
            //             required: true,
            //             maxlength: 30
            //         },
            //         address: {
            //             required: true,
            //         },
            //         contact_name: {
            //             required: true,
            //         },
            //         contact_phone: {
            //             required: true,
            //             minlength: 10,
            //             maxlength: 11,
            //         },

            //     },
            //     messages: {
            //         supplierName: {
            //             required: 'Vui lòng nhập tên nhà cung cấp',
            //             maxlength: 'Tối đa 30 ký tự'
            //         },
            //         address: {
            //             required: 'Vui lòng nhập địa chỉ',
            //         },
            //         contact_name: {
            //             required: 'Vui lòng nhập tên người đại diện'
            //         },
            //         contact_phone: {
            //             required: 'Vui lòng nhập số điện thoại',
            //             minlength: 'SĐT tối thiểu 10 số',
            //             maxlength: 'Vui lòng nhập lại SĐT',
            //         }
            //     },
            //     submitHandler: function () {
            //         let parameters = $('#parameters');
            //         let supplierName = $('#supplierName');
            //         let description = $("#description");
            //         let address = $("#address");
            //         let contactName = $("#contact_name");
            //         let contactTitle = $("#contact_title");
            //         let contactPhone = $("#contact_phone");
            //         $.ajax({
            //             url: laroute.route('admin.supplier.add'),
            //             data: {
            //                 supplierName: supplierName.val(),
            //                 description: description.val(),
            //                 address: address.val(),
            //                 contactName: contactName.val(),
            //                 contactTitle: contactTitle.val(),
            //                 contactPhone: contactPhone.val(),
            //                 parameters: parameters.val()
            //             },
            //             method: "POST",
            //             dataType: "JSON",
            //             success: function (data) {
            //                 $.getJSON(laroute.route('translate'), function (json) {
            //                 if (data.status == 1) {
            //                     if (data.parameters != 0) {
            //                         $('#modalAdd').modal('hide');
            //                     }
            //                     swal(
            //                         json['Thêm nhà cung cấp thành công'],
            //                         '',
            //                         'success'
            //                     );
            //                     $('#autotable').PioTable('refresh');
            //                     clear();
            //                 } else {
            //                     $('.error-supplier-name').css('color', 'red');
            //                     $('.error-supplier-name').text(json['Nhà cung cấp đã tồn tại']);
            //                 }
            //             });
            //             }
            //         });
            //     }
            // });
        },
        edit: function (id) {
            let errSupName = $('#modalEditSupplier .error-supplier-name');
            let errContactName = $('#modalEditSupplier .error-contact-name');
            let errPhone = $('#modalEditSupplier .error-contact-phone');
            errPhone.text('');
            errContactName.text('');
            errSupName.text('');
            $.ajax({
                url: laroute.route('admin.supplier.edit'),
                data: {
                    supplierId: id
                },
                method: "POST",
                dataType: 'JSON',
                success: function (data) {
                    $('#supplier-id').val(data['id']);
                    $('#supplierName-edit').val(data['supplierName']);
                    $('#description-edit').val(data['description']);
                    $('#address-edit').val(data['address']);
                    $('#contact_name-edit').val(data['contact_name']);
                    $('#contact_title-edit').val(data['contact_title']);
                    $('#contact_phone-edit').val(data['contact_phone']);
                    $('#modalEditSupplier').modal('show');
                }
            })

        },
        submitEdit: function () {
            $.getJSON(laroute.route('translate'), function (json) {

                var form = $('#form-edit');
    
                form.validate({
                    rules: {
                        supplierName: {
                            required: true,
                            maxlength: 190
                        },
                        address: {
                            required: true,
                        },
                        contactName: {
                            required: true,
                        },
                        contactPhone: {
                            required: true,
                            minlength: 10,
                            maxlength: 11,
                        },
    
                    },
                    messages: {
                        supplierName: {
                            required: json['Vui lòng nhập tên nhà cung cấp'],
                            maxlength: json['Tối đa 190 ký tự']
                        },
                        address: {
                            required: json['Vui lòng nhập địa chỉ'],
                        },
                        contactName: {
                            required: json['Vui lòng nhập tên người đại diện'],
                            maxlength: json['Tối đa 30 ký tự']
                        },
                        contactPhone: {
                            required: json['Vui lòng nhập số điện thoại'],
                            minlength: json['SĐT tối thiểu 10 số'],
                            maxlength: json['Vui lòng nhập lại SĐT'],
                        }
                    },
                });
    
                if (!form.valid()) {
                    return false;
                }
    
                var input = $('#type_add').val();
                var group_name = $('#group_name').val();
                $.ajax({
                    url: laroute.route('admin.supplier.submit-edit'),
                        data: {
                            id: $('#supplier-id').val(),
                            supplierName: $('#supplierName-edit').val(),
                            description: $('#description-edit').val(),
                            address: $('#address-edit').val(),
                            contactName: $('#contact_name-edit').val(),
                            contactTitle: $('#contact_title-edit').val(),
                            contactPhone: $('#contact_phone-edit').val(),
                            parameter: 0
                        },
                        method: "POST",
                        dataType: "JSON",
                        success: function (data) {
                            if (data.status == 0) {
                                $('.error-supplier-name').text(json['Tên chi nhánh đã tồn tại']);
                            }
                            if (data.status == 1) {
                                $('#modalEditSupplier').modal('hide');
                                swal(
                                    json['Cập nhật nhà cung cấp thành công'],
                                    '',
                                    'success'
                                );
                                $('#autotable').PioTable('refresh');
                                clear();
                            } else if (data.status == 2) {
                                swal({
                                    title: json['Nhà cung cấp đã tồn tại'],
                                    text: json["Bạn có muốn kích hoạt lại không?"],
                                    type: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: json['Có'],
                                    cancelButtonText: json['Không'],
                                }).then(function (willDelete) {
                                    if (willDelete.value == true) {
                                        $.ajax({
                                            url: laroute.route('admin.supplier.submit-edit'),
                                            data: {
                                                id: $('#supplier-id').val(),
                                                supplierName: $('#supplierName-edit').val(),
                                                description: $('#description-edit').val(),
                                                address: $('#address-edit').val(),
                                                contactName: $('#contact_name-edit').val(),
                                                contactTitle: $('#contact_title-edit').val(),
                                                contactPhone: $('#contact_phone-edit').val(),
                                                parameter: 1
                                            },
                                            method: "POST",
                                            dataType: 'JSON',
                                            success: function (data) {
                                                if (data.status = 3) {
                                                    swal(
                                                        json['Kích hoạt nhà cung cấp thành công'],
                                                        '',
                                                        'success'
                                                    );
                                                    $('#modalEditSupplier').modal('hide');
                                                    $('#autotable').PioTable('refresh');
                                                }
                                            }
                                        });
                                    }
                                });
                            }
                        }
                });
            });

            // $('#form-edit').validate({
            //     rules: {
            //         supplierName: {
            //             required: true,
            //             maxlength: 30
            //         },
            //         address: {
            //             required: true,
            //         },
            //         contactName: {
            //             required: true,
            //         },
            //         contactPhone: {
            //             required: true,
            //             minlength: 10,
            //             maxlength: 11,
            //         },

            //     },
            //     messages: {
            //         supplierName: {
            //             required: 'Vui lòng nhập tên nhà cung cấp'
            //         },
            //         address: {
            //             required: 'Vui lòng nhập địa chỉ',
            //         },
            //         contactName: {
            //             required: 'Vui lòng nhập tên người đại diện',
            //             maxlength: 'Tối đa 30 ký tự'
            //         },
            //         contactPhone: {
            //             required: 'Vui lòng nhập số điện thoại',
            //             minlength: 'SĐT tối thiểu 10 số',
            //             maxlength: 'Vui lòng nhập lại SĐT',
            //         }
            //     },
            //     submitHandler: function () {
            //         $.ajax({
            //             url: laroute.route('admin.supplier.submit-edit'),
            //             data: {
            //                 id: $('#supplier-id').val(),
            //                 supplierName: $('#supplierName-edit').val(),
            //                 description: $('#description-edit').val(),
            //                 address: $('#address-edit').val(),
            //                 contactName: $('#contact_name-edit').val(),
            //                 contactTitle: $('#contact_title-edit').val(),
            //                 contactPhone: $('#contact_phone-edit').val(),
            //                 parameter: 0
            //             },
            //             method: "POST",
            //             dataType: "JSON",
            //             success: function (data) {
            //                 $.getJSON(laroute.route('translate'), function (json) {
            //                 if (data.status == 0) {
            //                     $('.error-supplier-name').text(json['Tên chi nhánh đã tồn tại']);
            //                 }
            //                 if (data.status == 1) {
            //                     $('#modalEditSupplier').modal('hide');
            //                     swal(
            //                         json['Cập nhật nhà cung cấp thành công'],
            //                         '',
            //                         'success'
            //                     );
            //                     $('#autotable').PioTable('refresh');
            //                     clear();
            //                 } else if (data.status == 2) {
            //                     swal({
            //                         title: json['Nhà cung cấp đã tồn tại'],
            //                         text: json["Bạn có muốn kích hoạt lại không?"],
            //                         type: 'warning',
            //                         showCancelButton: true,
            //                         confirmButtonText: json['Có'],
            //                         cancelButtonText: json['Không'],
            //                     }).then(function (willDelete) {
            //                         if (willDelete.value == true) {
            //                             $.ajax({
            //                                 url: laroute.route('admin.supplier.submit-edit'),
            //                                 data: {
            //                                     id: $('#supplier-id').val(),
            //                                     supplierName: $('#supplierName-edit').val(),
            //                                     description: $('#description-edit').val(),
            //                                     address: $('#address-edit').val(),
            //                                     contactName: $('#contact_name-edit').val(),
            //                                     contactTitle: $('#contact_title-edit').val(),
            //                                     contactPhone: $('#contact_phone-edit').val(),
            //                                     parameter: 1
            //                                 },
            //                                 method: "POST",
            //                                 dataType: 'JSON',
            //                                 success: function (data) {
            //                                     if (data.status = 3) {
            //                                         swal(
            //                                             json['Kích hoạt nhà cung cấp thành công'],
            //                                             '',
            //                                             'success'
            //                                         );
            //                                         $('#modalEditSupplier').modal('hide');
            //                                         $('#autotable').PioTable('refresh');
            //                                     }
            //                                 }
            //                             });
            //                         }
            //                     });
            //                 }
            //             });
            //             }
            //         });
            //     }
            // });
        },
        refresh: function () {
            $('input[name="search_keyword"]').val('');
            $(".btn-search").trigger("click");
        },
        search: function () {
            $(".btn-search").trigger("click");
        }
    }
;
$('#autotable').PioTable({
    baseUrl: laroute.route('admin.supplier.list')
});

function onKeyDownInput(o) {
    $(o).on('keydown', function (e) {
        -1 !== $.inArray(e.keyCode, [46, 8, 9, 27, 13, 110])
        || (/65|67|86|88/.test(e.keyCode) && (e.ctrlKey === true || e.metaKey === true))
        && (!0 === e.ctrlKey || !0 === e.metaKey)
        || 35 <= e.keyCode && 40 >= e.keyCode
        || (e.shiftKey || 48 > e.keyCode || 57 < e.keyCode) && (96 > e.keyCode || 105 < e.keyCode)
        && e.preventDefault()
    });
}
