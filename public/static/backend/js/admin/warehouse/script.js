$(document).ready(function () {
    $.getJSON(laroute.route('translate'), function (json) {
        $('#house_branch_id').select2({
            placeholder: json['Chọn chi nhánh']
        });
        $('#h_branch_id').select2({
            placeholder: json['Chọn chi nhánh']
        });
        $('#btnLuu').click(function () {
            $('#formEdit').validate({
                rules: {
                    name: {
                        required: true
                    },
                    branch_id: {
                        required: true
                    },
                    address: {
                        required: true
                    },
                    province: {
                        required: true
                    },
                    district: {
                        required: true
                    },
                    ward: {
                        required: true
                    },
                    phone: {
                        required: true,
                        checkPhone: true,
                    }
                },
                messages: {
                    name: {
                        required: json['Hãy nhập tên kho']
                    },
                    branch_id: {
                        required: json['Hãy chọn chi nhánh']
                    },
                    address: {
                        required: json['Hãy nhập địa chỉ']
                    },
                    province: {
                        required: 'Hãy chọn Tỉnh/thành'
                    },
                    district: {
                        required: 'Hãy chọn Quận/huyện'
                    },
                    ward: {
                        required: 'Hãy chọn Phường/xã'
                    },
                    phone: {
                        required: 'Hãy nhập số điện thoại',
                        checkPhone : 'Số điện thoại không đúng định dạng'
                    }
                },
                submitHandler: function () {
                    var id = $('#hhidden').val();
                    var name = $('#name').val();
                    var branch_id = $('#h_branch_id').val();
                    var address = $('#address').val();
                    var description = $('#description').val();
                    var isRetail = 0;
                    if ($('#h_is_retail').is(":checked")) {
                        isRetail = 1;
                    }
                    $.ajax({
                        url: laroute.route('admin.warehouse.check-is-retail'),
                        method: "POST",
                        data: {
                            branch: $('#h_branch_id').val(),
                            isRetail: isRetail,
                            id: $('#hhidden').val()
                        },
                        success: function (data) {
                            if (data.error == 1) {
                                swal({
                                    title: json['Chi nhánh đã có kho bán lẻ'],
                                    text: json["Bạn có muốn đổi lại không?"],
                                    type: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: json['Có'],
                                    cancelButtonText: json['Không'],
                                }).then(function (willDelete) {
                                    if (willDelete.value == true) {
                                        $.ajax({
                                            url: laroute.route('admin.warehouse.submitedit'),
                                            data: {
                                                id: id,
                                                name: name,
                                                branch_id: branch_id,
                                                address: address,
                                                description: description,
                                                province: $('#province_edit').val(),
                                                district: $('#district_edit').val(),
                                                ward: $('#ward_edit').val(),
                                                phone: $('#phone_edit').val(),
                                                isRetail: isRetail,
                                                param: 1
                                            },
                                            method: "POST",
                                            dataType: 'JSON',
                                            success: function (response) {
                                                if (response.status == '') {
                                                    $("#editForm").modal("hide");
                                                    $('#house_name').val('');
                                                    $('#house_branch_id').val('').trigger('change');
                                                    $('#house_address').val('');
                                                    $('#house_description').val('');
                                                    $('.error-name').text('');
                                                    swal(json["Cập nhật nhà kho thành công"], "", "success");
                                                    $('#autotable').PioTable('refresh');
                                                } else {
                                                    $('.error-name').text(response.status);
                                                    $('.error-name').css('color', 'red');
                                                }
                                            }
                                        });
                                    }
                                });
                            } else {
                                $.ajax({
                                    url: laroute.route('admin.warehouse.submitedit'),
                                    data: {
                                        id: id,
                                        name: name,
                                        branch_id: branch_id,
                                        address: address,
                                        description: description,
                                        province: $('#province_edit').val(),
                                        district: $('#district_edit').val(),
                                        isRetail: isRetail,
                                        ward: $('#ward_edit').val(),
                                        phone: $('#phone_edit').val(),
                                        param: 0
                                    },
                                    type: "POST",
                                    dataType: 'JSON',
                                    success: function (response) {
                                        if (response.status == '') {
                                            $('.error-name').text('');
                                            $("#editForm").modal("hide");
                                            swal(json["Cập nhật nhà kho thành công"], "", "success");
                                            $('#autotable').PioTable('refresh');
                                        } else {
                                            $('.error-name').text(response.status);
                                            $('.error-name').css('color', 'red');
                                        }
                                    },

                                });
                            }
                        }
                    });
                }
            });
        });
    });
});
var warehouse = {
    remove: function (obj, id) {
        // hightlight row
        $(obj).closest('tr').addClass('m-table__row--danger');
        $.getJSON(laroute.route('translate'), function (json) {
            swal({
                title: json['Thông báo'],
                text: json["Bạn có muốn xóa không?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json['Xóa'],
                cancelButtonText: json['Hủy'],
                onClose: function () {
                    // remove hightlight row
                    $(obj).closest('tr').removeClass('m-table__row--danger');
                }
            }).then(function (result) {
                if (result.value) {
                    $.post(laroute.route('admin.warehouse.delete', {id: id}), function () {
                        swal(
                            json['Xóa thành công'],
                            '',
                            'success'
                        );
                        // window.location.reload();
                        $('#autotable').PioTable('refresh');
                    });
                }
            });
        });
    },
    changeStatus: function (obj, id, action) {
        $.ajax({
            url: laroute.route('admin.branch.change-status'),
            method: "POST",
            data: {
                id: id, action: action
            },
            dataType: "JSON"
        }).done(function (data) {
            $('#autotable').PioTable('refresh');
        });
    },
    add: function (close) {
        $('#type_add').val(close);
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form');
            form.validate({
                rules: {
                    name: {
                        required: true
                    },
                    branch_id: {
                        required: true
                    },
                    address: {
                        required: true
                    },
                    province: {
                        required: true
                    },
                    district: {
                        required: true
                    },
                    ward: {
                        required: true
                    },
                    phone: {
                        required: true,
                        checkPhone: true,
                    }
                },
                messages: {
                    name: {
                        required: json['Hãy nhập tên kho']
                    },
                    branch_id: {
                        required: json['Hãy chọn chi nhánh']
                    },
                    address: {
                        required: json['Hãy nhập địa chỉ']
                    },
                    province: {
                        required: 'Hãy chọn Tỉnh/thành'
                    },
                    district: {
                        required: 'Hãy chọn Quận/huyện'
                    },
                    ward: {
                        required: 'Hãy chọn Phường/xã'
                    },
                    phone: {
                        required: 'Hãy nhập số điện thoại',
                        checkPhone : 'Số điện thoại không đúng định dạng'
                    }
                },
            });

            if (!form.valid()) {
                return false;
            }

            var input = $('#type_add');
            var isRetail = 0;
            if ($('#is_retail').is(":checked")) {
                isRetail = 1;
            }
            $.ajax({
                url: laroute.route('admin.warehouse.check-is-retail'),
                method: "POST",
                data: {
                    branch: $('#house_branch_id').val(),
                    isRetail: isRetail,
                    id: 0
                },
                success: function (data) {
                    if (data.error == 0) {
                        $.ajax({
                            type: 'post',
                            url: laroute.route('admin.warehouse.submitAdd'),
                            data: {
                                name: $('#house_name').val(),
                                branch_id: $('#house_branch_id').val(),
                                address: $('#house_address').val(),
                                description: $('#house_description').val(),
                                close: input.val(),
                                province: $('#province').val(),
                                district: $('#district').val(),
                                ward: $('#ward').val(),
                                phone: $('#phone').val(),
                                isRetail: isRetail
                            },
                            dataType: "JSON",
                            success: function (response) {
                                if (response.status == '') {
                                    if (response.close != 0) {
                                        $("#add").modal("hide");
                                    }
                                    $('#house_name').val('');
                                    $('#house_branch_id').val('').trigger('change');
                                    $('#house_address').val('');
                                    $('#house_description').val('');
                                    $('.error-name').text('');
                                    swal(json["Thêm nhà kho thành công"], "", "success");
                                    $('#autotable').PioTable('refresh');
                                } else {
                                    $('.error-name').text(response.status);
                                    $('.error-name').css('color', 'red');
                                }
                            }
                        })
                    } else {
                        swal({
                            title: json['Chi nhánh đã có kho bán lẻ'],
                            text: json["Bạn có muốn đổi lại không?"],
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonText: json['Có'],
                            cancelButtonText: json['Không'],
                        }).then(function (willDelete) {
                            if (willDelete.value == true) {
                                $.ajax({
                                    url: laroute.route('admin.warehouse.change-is-retail'),
                                    data: {
                                        name: $('#house_name').val(),
                                        branch_id: $('#house_branch_id').val(),
                                        address: $('#house_address').val(),
                                        description: $('#house_description').val(),
                                        close: input.val(),
                                        province: $('#province').val(),
                                        district: $('#district').val(),
                                        ward: $('#ward').val(),
                                        phone: $('#phone').val(),
                                        isRetail: isRetail
                                    },
                                    method: "POST",
                                    dataType: 'JSON',
                                    success: function (response) {
                                        if (response.error == 0) {
                                            if (response.close != 0) {
                                                $("#add").modal("hide");
                                            }
                                            $('#house_name').val('');
                                            $('#house_branch_id').val('').trigger('change');
                                            $('#house_address').val('');
                                            $('#house_description').val('');
                                            $('.error-name').text('');
                                            swal(json["Thêm nhà kho thành công"], "", "success");
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
    },
    edit: function (id) {
        $('.error-h-is-retail').text('')
        $.ajax({
            type: 'POST',
            url: laroute.route('admin.warehouse.edit'),
            data: {
                id: id
            },
            dataType: 'JSON',
            success: function (response) {
                console.log(response);
                $('#editForm').modal("show");
                $('#hhidden').val(response['warehouse_id']);
                $('#name').val(response["name"]);
                $('#h_branch_id').val(response["branch_id"]).trigger('change');
                $('#address').val(response["address"]);
                $('#description').val(response["description"]);
                $('#phone').val(response["phone"]);

                $('.error-name').text('');


                $('#province_edit').empty();

                $.each(response.provinceOption, function (key, value) {
                    if (key == response["province_id"]) {
                        $('#province_edit').append('<option selected value="' + key + '">' + value + '</option>');
                    } else {
                        $('#province_edit').append('<option value="' + key + '">' + value + '</option>');
                    }
                });

                $('#district_edit').empty();
                $('#district_edit').append('<option value="' + response.district_id + '">' + response.district_name + '</option>');

                $('#ward_edit').empty();
                if (response.ward_id !== null){
                    $('#ward_edit').append('<option value="' + response.ward_id + '">' + response.ward_name + '</option>');
                } else {
                    $('#ward_edit').append('<option value="">Chọn Phường/xã</option>');
                }

                if (response['is_retail'] == 1) {
                    $('#h_is_retail').prop('checked', true);
                } else {
                    $('#h_is_retail').prop('checked', false);
                }
            }


        });
    },
    refresh: function () {
        $('input[name="search"]').val('');
        $(".btn-search").trigger("click");
    },

    createStore: function(){
        swal({
            title: 'Tạo cửa hàng trên GHN',
            text: 'Bạn muốn tạo cửa hàng trên GHN',
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Tạo',
            cancelButtonText: 'Hủy',

        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: laroute.route('admin.warehouse.create-store-ghn'),
                    dataType: 'JSON',
                    data: {},
                    method: 'POST',
                    success: function (res) {
                        if (res.error == false){
                            swal(res.message, '', 'success');
                        } else {
                            swal(res.message, '', 'error');
                        }

                    }
                });
            }
        });
    }
};
$('#autotable').PioTable({
    baseUrl: laroute.route('admin.warehouse.list')
});

$.getJSON(laroute.route('translate'), function (json) {
    $('#province').select2({
        placeholder: json['Chọn tỉnh/thành']
    }).on('select2:select', function (event) {
        $.ajax({
            url: laroute.route('admin.customer.load-district'),
            dataType: 'JSON',
            data: {
                id_province: event.params.data.id
            },
            method: 'POST',
            success: function (res) {
                $('#district').empty();
                $('#ward').empty();
                $('#ward').append('<option value="">Chọn Phường/xã</option>');
                $.map(res.optionDistrict, function (a) {
                    $('#district').append('<option value="' + a.id + '">' + a.type + ' ' + a.name + '</option>');
                });
            }
        });
    });


    $('#district').select2({
        placeholder: json['Chọn quận/huyện'],
        ajax: {
            url: laroute.route('admin.customer.load-district'),
            data: function (params) {
                return {
                    id_province: $('#province').val(),
                    search: params.term,
                    page: params.page || 1
                };
            },
            dataType: 'JSON',
            method: 'POST',
            processResults: function (res) {
                res.page = res.page || 1;
                $('#ward').empty();
                $('#ward').append('<option value="">Chọn Phường/xã</option>');
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

    $('#ward').select2({
        placeholder: json['Chọn Phường/Xã'],
        ajax: {
            url: laroute.route('admin.customer.load-ward'),
            data: function (params) {
                return {
                    id_district: $('#district').val(),
                    search: params.term,
                    page: params.page || 1
                };
            },
            dataType: 'JSON',
            method: 'POST',
            processResults: function (res) {
                res.page = res.page || 1;
                return {
                    results: res.optionWard.map(function (item) {
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

    $('#province_edit').select2({
        placeholder: json['Chọn quận/huyện']
    }).on('select2:select', function (event) {
        $.ajax({
            url: laroute.route('admin.customer.load-district'),
            dataType: 'JSON',
            data: {
                id_province: event.params.data.id
            },
            method: 'POST',
            success: function (res) {
                $('#district_edit').empty();
                $('#ward_edit').empty();
                $('#ward_edit').append('<option value="">Chọn Phường/xã</option>');
                $.map(res.optionDistrict, function (a) {
                    $('#district_edit').append('<option value="' + a.id + '">' + a.type + ' ' + a.name + '</option>');
                });
            }
        });
    });

    $('#district_edit').select2({
        placeholder: json['Chọn quận/huyện'],
        ajax: {
            url: laroute.route('admin.customer.load-district'),
            data: function (params) {
                return {
                    id_province: $('#province_edit').val(),
                    search: params.term,
                    page: params.page || 1
                };
            },
            dataType: 'JSON',
            method: 'POST',
            processResults: function (res) {
                res.page = res.page || 1;
                $('#ward_edit').empty();
                $('#ward_edit').append('<option value="">Chọn Phường/xã</option>');
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


    $('#ward_edit').select2({
        placeholder: json['Chọn phường/xã'],
        ajax: {
            url: laroute.route('admin.customer.load-ward'),
            data: function (params) {
                return {
                    id_district: $('#district_edit').val(),
                    search: params.term,
                    page: params.page || 1
                };
            },
            dataType: 'JSON',
            method: 'POST',
            processResults: function (res) {
                res.page = res.page || 1;
                return {
                    results: res.optionWard.map(function (item) {
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
});

//Check kho bán lẻ (form add).
// $('#is_retail').prop('disabled', true);
// $('#house_branch_id').change(function () {
//     if ($('#house_branch_id').val() != '') {
//         $('#is_retail').prop('disabled', false);
//     } else {
//         $('#is_retail').prop('checked', false);
//         $('#is_retail').prop('disabled', true);
//     }
// });
//
// $('#is_retail').click(function () {
//     if ($('#is_retail').is(":checked")) {
//         $.ajax({
//             url: laroute.route('admin.warehouse.check-is-retail'),
//             method: "POST",
//             data: {
//                 branch: $('#house_branch_id').val(),
//                 isRetail: 1,
//                 id: 0
//             },
//             success: function (data) {
//                 if (data.error == 1) {
//                     $('.error-is-retail').text('Chi nhánh đã có kho bán lẻ');
//                 } else {
//                     $('.error-is-retail').text('');
//                 }
//             }
//         });
//     } else {
//         $('.error-is-retail').text('');
//     }
// });
//
// //Check kho bán lẻ (form edit).
// $('#h_is_retail').click(function () {
//     if ($('#h_is_retail').is(":checked")) {
//         $.ajax({
//             url: laroute.route('admin.warehouse.check-is-retail'),
//             method: "POST",
//             data: {
//                 branch: $('#h_branch_id').val(),
//                 isRetail: 1,
//                 id: $('#hhidden').val()
//             },
//             success: function (data) {
//                 if (data.error == 1) {
//                     $('.error-h-is-retail').text('Chi nhánh đã có kho bán lẻ');
//                 } else {
//                     $('.error-h-is-retail').text('');
//                 }
//             }
//         });
//     } else {
//         $('.error-h-is-retail').text('');
//     }
// });

jQuery.validator.addMethod("checkPhone", function (value, element) {
    var patt = new RegExp("((0|84)+(87|89|90|93|70|79|77|76|78)+([0-9]{7})\\b)|((0|84)+(86|96|97|98|32|33|34|35|36|37|38|39)+([0-9]{7})\\b)|((0|84)+(88|91|94|83|84|85|81|82)+([0-9]{7})\\b)|((0|84)+(99|59)+([0-9]{7})\\b)|((0|84)+(92|56|58)+([0-9]{7})\\b)");
    return patt.test(value);
}, "Vui lòng nhập đúng định dạng số điện thoại");