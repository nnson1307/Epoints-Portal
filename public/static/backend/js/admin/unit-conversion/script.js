$(document).ready(function () {
    $.getJSON(laroute.route('translate'), function (json) {
    $('#h_unit_id').select2({
       placeholder:json['Chọn đơn vị tính'],
       allowClear:true
    });
    $('#h_unit_standard').select2({
       placeholder:json['Chọn đơn vị chuẩn'],
       allowClear:true
    });
    $('#b_unit_id').select2({
        placeholder:json['Chọn đơn vị tính'],
        allowClear:true
    });
    $('#unit_standard').select2({
        placeholder:json['Chọn đơn vị chuẩn'],
        allowClear:true
    });
});
    $('#btnLuu').click(function () {
        $.getJSON(laroute.route('translate'), function (json) {

            var form = $('#formEdit');

            form.validate({
                rules: {
                    conversion_rate: {
                        required: true,
                        min:1,
                        number:true
                    },
                    unit_id: {
                        required: true
                    },
                    unit_standard: {
                        required: true
                    }
                },
                messages: {
                    conversion_rate: {
                        required: json['Hãy nhập tỉ lệ chuyển đổi'],
                        min:json['Tỉ lệ chuyển đổi tối thiểu là 1'],
                        number:json['Tỉ lệ chuyển đổi không hợp lệ']
                    },
                    unit_id: {
                        required: json['Hãy chọn đơn vị tính']
                    },
                    unit_standard: {
                        required: json['Hãy chọn tiêu chuẩn']
                    }
                },
            });

            if (!form.valid()) {
                return false;
            }

            var id = $('#hhidden').val();
            var conversion_rate = $('#conversion_rate').val();
            var unit_id = $('#b_unit_id').val();
            var unit_standard = $('#unit_standard').val();
            $.ajax({
                url: laroute.route('admin.unit_conversion.submitedit'),
                data: {
                    id: id,
                    conversion_rate: conversion_rate,
                    unit_id: unit_id,
                    unit_standard: unit_standard
                },
                type: "POST",
                dataType: 'JSON',
                success: function (response) {
                    $("#editForm").modal("hide");
                    swal(json["Cập nhật tỉ lệ chuyển đổi thành công"], "", "success");
                    $('#autotable').PioTable('refresh');
                }
            });
        });
        // $('#formEdit').validate({
        //     rules: {
        //         conversion_rate: {
        //             required: true,
        //             min:1,
        //             number:true
        //         },
        //         unit_id: {
        //             required: true
        //         },
        //         unit_standard: {
        //             required: true
        //         }
        //     },
        //     messages: {
        //         conversion_rate: {
        //             required: 'Hãy nhập tỉ lệ chuyển đổi',
        //             min:'Tỉ lệ chuyển đổi tối thiểu là 1',
        //             number:'Tỉ lệ chuyển đổi không hợp lệ'
        //         },
        //         unit_id: {
        //             required: 'Hãy chọn đơn vị tính'
        //         },
        //         unit_standard: {
        //             required: 'Hãy chọn tiêu chuẩn'
        //         }
        //     },
        //     submitHandler: function () {
        //         var id = $('#hhidden').val();
        //         var conversion_rate = $('#conversion_rate').val();
        //         var unit_id = $('#b_unit_id').val();
        //         var unit_standard = $('#unit_standard').val();
        //         $.ajax({
        //             url: laroute.route('admin.unit_conversion.submitedit'),
        //             data: {
        //                 id: id,
        //                 conversion_rate: conversion_rate,
        //                 unit_id: unit_id,
        //                 unit_standard: unit_standard
        //             },
        //             type: "POST",
        //             dataType: 'JSON',
        //             success: function () {
        //                 $("#editForm").modal("hide");
        //                 swal("Cập nhật tỉ lệ chuyển đổi thành công", "", "success");
        //                 $('#autotable').PioTable('refresh');

        //             },

        //         });
        //     }
        // });

    });
});
var unit_conversion = {
    modal_add:function()
    {
        $('#add').modal('show');
        $('#h_unit_id').val('').trigger('change');
        $('#h_unit_standard').val('').trigger('change');
        $('#h_conversion_rate').val('');
    },
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
                $.post(laroute.route('admin.unit_conversion.remove', {id: id}), function () {
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
    add: function (close) {
        $('#type_add').val(close);
        $.getJSON(laroute.route('translate'), function (json) {

            var form = $('#form');

            form.validate({
                rules: {
                    conversion_rate: {
                        required: true,
                        min:1,
                        number:true
                    },
                    unit_id: {
                        required: true
                    },
                    unit_standard: {
                        required: true
                    }
                },
                messages: {
                    conversion_rate: {
                        required: json['Hãy nhập tỉ lệ chuyển đổi'],
                        min:json['Tỉ lệ chuyển đổi tối thiểu 1'],
                        number:json['Tỉ lệ chuyển đổi không hợp lệ']
                    },
                    unit_id: {
                        required: json['Hãy chọn đơn vị tính']
                    },
                    unit_standard: {
                        required: json['Hãy chọn tiêu chuẩn']
                    }
                },
            });

            if (!form.valid()) {
                return false;
            }

            var input = $('#type_add');
            var conversion_rate = $('#h_conversion_rate').val();
            var unit_id = $('#h_unit_id').val();
            var unit_standard = $('#h_unit_standard').val();
            $.ajax({
                type: 'post',
                url: laroute.route('admin.unit_conversion.submitadd'),
                data: {
                    conversion_rate: conversion_rate,
                    unit_id: unit_id,
                    unit_standard: unit_standard,
                    close: input.val()
                },
                dataType: "JSON",
                success: function (response) {
                    if (response.close != 0) {
                        $("#add").modal("hide");
                    }
                    $('#h_conversion_rate').val('');
                    $('#h_unit_id').val('').trigger('change');
                    $('#h_unit_standard').val('').trigger('change');
                    swal(json["Thêm công thức quy đổi thành công"], "", "success");
                    $('#autotable').PioTable('refresh');
                }
            });
        });
        // $('#form').validate({
        //     rules: {
        //         conversion_rate: {
        //             required: true,
        //             min:1,
        //             number:true
        //         },
        //         unit_id: {
        //             required: true
        //         },
        //         unit_standard: {
        //             required: true
        //         }
        //     },
        //     messages: {
        //         conversion_rate: {
        //             required: 'Hãy nhập tỉ lệ chuyển đổi',
        //             min:'Tỉ lệ chuyển đổi tối thiểu 1',
        //             number:'Tỉ lệ chuyển đổi không hợp lệ'
        //         },
        //         unit_id: {
        //             required: 'Hãy chọn đơn vị tính'
        //         },
        //         unit_standard: {
        //             required: 'Hãy chọn tiêu chuẩn'
        //         }
        //     },
        //     submitHandler: function () {
        //         var input = $('#type_add');
        //         var conversion_rate = $('#h_conversion_rate').val();
        //         var unit_id = $('#h_unit_id').val();
        //         var unit_standard = $('#h_unit_standard').val();
        //         $.ajax({
        //             type: 'post',
        //             url: laroute.route('admin.unit_conversion.submitadd'),
        //             data: {
        //                 conversion_rate: conversion_rate,
        //                 unit_id: unit_id,
        //                 unit_standard: unit_standard,
        //                 close: input.val()
        //             },
        //             dataType: "JSON",
        //             success: function (response) {
        //                 if (response.close != 0) {
        //                     $("#add").modal("hide");
        //                 }
        //                 $('#h_conversion_rate').val('');
        //                 $('#h_unit_id').val('').trigger('change');
        //                 $('#h_unit_standard').val('').trigger('change');
        //                 swal("Thêm công thức quy đổi thành công", "", "success");
        //                 $('#autotable').PioTable('refresh');
        //             },
        //         })
        //     }
        // });
    },
    edit: function (id) {
        $.ajax({
            type: 'POST',
            url: laroute.route('admin.unit_conversion.edit'),
            data: {
                id: id
            },
            dataType: 'JSON',
            success: function (response) {
                $('#editForm').modal("show");
                $('#hhidden').val(response['unit_conversion_id']);
                $('#conversion_rate').val(response["conversion_rate"]);
                $('#b_unit_id').val(response["unit_id"]).trigger('change');
                $('#unit_standard').val(response["unit_standard"]).trigger('change');
            }
        });

    },
    refresh: function () {
        $('input[name="search_keyword"]').val('');
        $(".btn-search").trigger("click");
    }

};
$('#autotable').PioTable({
    baseUrl: laroute.route('admin.unit_conversion.list')
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
$('.m_selectpicker').selectpicker();