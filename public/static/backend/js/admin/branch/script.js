$(document).ready(function () {
    $.getJSON(laroute.route('translate'), function (json) {
        $('#description').summernote({
            height: 150,
            placeholder: json['Nhập nội dung...'],
            toolbar: [
                ['style', ['bold', 'italic', 'underline']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
            ]

        });

        $('#provinceid').select2({
            placeholder: json["Chọn tỉnh/thành"],
        });

        $('#provinceid').change(function () {
            $.ajax({
                url: laroute.route('admin.customer.load-district'),
                dataType: 'JSON',
                data: {
                    id_province: $('#provinceid').val(),
                },
                method: 'POST',
                success: function (res) {
                    $('.district').empty();
                    $.map(res.optionDistrict, function (a) {
                        $('#districtid').append('<option value="' + a.id + '">' + a.type + ' ' + a.name + '</option>');
                    });
                }
            });
        });

        $('#districtid').select2({
            placeholder: json["Chọn quận/huyện"],
            ajax: {
                url: laroute.route('admin.customer.load-district'),
                data: function (params) {
                    return {
                        id_province: $('#provinceid').val(),
                        search: params.term,
                        page: params.page || 1
                    };
                },
                dataType: 'JSON',
                method: 'POST',
                processResults: function (res) {
                    res.page = res.page || 1;
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

        $('.note-btn').attr('title', '');
        $('.delete-image').click(function () {
            $(this).parents('.image-edit').remove();
            var name = $(this).val();
            $(".branch_image").each(function () {
                var $this = $(this);
                if ($this.val() === name) {
                    $this.remove();
                }
            });

        });
    });
});

var branch = {
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
                    $.post(laroute.route('admin.branch.delete', {id: id}), function () {
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
    add: function () {
        $.getJSON(laroute.route('translate'), function (json) {

            var form = $('#form');

            form.validate({
                rules: {
                    branch_name: {
                        required: true,
                    },
                    address: {
                        required: true
                    },
                    phone: {
                        required: true,
                        minlength: 10,
                        maxlength: 11,
                        number: true
                    },
                    provinceid: {
                        required: true
                    },
                    districtid: {
                        required: true
                    },
                    // hot_line: {
                    //     number: true
                    // },
                    representative_code: {
                        required: true
                    },


                },
                messages: {
                    branch_name: {
                        required: json['Hãy nhập tên chi nhánh']
                    },
                    address: {
                        required: json['Hãy nhập địa chỉ']
                    },
                    phone: {
                        required: json['Hãy nhập số điện thoại'],
                        minlength: json['Tối thiểu 10 số'],
                        number: json['Số điện thoại không hợp lệ'],
                        maxlength: json['Số điện thoại không hợp lệ']
                    },
                    provinceid: {
                        required: json['Hãy chọn tỉnh/thành phố']
                    },
                    districtid: {
                        required: json['Hãy chọn quận/huyện']
                    },
                    // hot_line: {
                    //     number: json['Số hot line không hợp lệ']
                    // },
                    representative_code: {
                        required: json['Hãy nhập mã đại diện']
                    }
                },
            });

            if (!form.valid()) {
                return false;
            }

            var is_representative = 0;
            if ($('#is_representative').is(':checked')) {
                is_representative = 1;
            }
            var check_image = $('.image-show').find('input[name="img"]');
            var img = [];
            $.each(check_image, function () {
                img.push($(this).val());
            });

            var email = $('#email').val();
            if (email != '') {
                if (!isValidEmailAddress(email)) {
                    $('.error_email').text('Email không hợp lệ');
                    return false;
                } else {
                    $.ajax({
                        url: laroute.route('admin.branch.submitAdd'),
                        data: {
                            branch_name: $('#branch_name').val(),
                            representative_code: $('#representative_code').val(),
                            address: $('#address').val(),
                            phone: $('#phone').val(),
                            email: $('#email').val(),
                            hot_line: $('#hot_line').val(),
                            provinceid: $('#provinceid').val(),
                            districtid: $('#districtid').val(),
                            description: $('#description').val(),
                            is_representative: is_representative,
                            img: img,
                            latitude: $('#latitude').val(),
                            longitude: $('#longitude').val()
                        },
                        method: 'POST',
                        dataType: "JSON",
                        success: function (response) {
                            console.log(response);
                            if (response.success == 1) {
                                swal(json["Thêm chi nhánh thành công"], "", "success");
                                window.location = laroute.route('admin.branch');
                            } else {
                                swal(response.message, "", "error")
                            }
                        }
                    });
                }
            } else {
                $.ajax({
                    url: laroute.route('admin.branch.submitAdd'),
                    data: {
                        branch_name: $('#branch_name').val(),
                        representative_code: $('#representative_code').val(),
                        address: $('#address').val(),
                        phone: $('#phone').val(),
                        email: $('#email').val(),
                        hot_line: $('#hot_line').val(),
                        provinceid: $('#provinceid').val(),
                        districtid: $('#districtid').val(),
                        description: $('#description').val(),
                        is_representative: is_representative,
                        img: img,
                        latitude: $('#latitude').val(),
                        longitude: $('#longitude').val()
                    },
                    method: 'POST',
                    dataType: "JSON",
                    success: function (response) {
                        if (response.success == 1) {
                            swal(json["Thêm chi nhánh thành công"], "", "success");
                            window.location = laroute.route('admin.branch');
                        } else {
                            swal(response.message, "", "error")
                        }
                    }
                });
            }
        });
    },
    edit: function () {
        $.getJSON(laroute.route('translate'), function (json) {

            var form = $('#edit');

            form.validate({
                rules: {
                    branch_name: {
                        required: true,
                    },
                    address: {
                        required: true
                    },
                    phone: {
                        required: true,
                        minlength: 10,
                        maxlength: 11,
                        number: true
                    },
                    provinceid: {
                        required: true
                    },
                    districtid: {
                        required: true
                    },
                    // hot_line: {
                    //     number: true
                    // },
                    representative_code: {
                        required: true
                    }

                },
                messages: {
                    branch_name: {
                        required: json['Hãy nhập tên chi nhánh']
                    },
                    address: {
                        required: json['Hãy nhập địa chỉ']
                    },
                    phone: {
                        required: json['Hãy nhập số điện thoại'],
                        minlength: json['Tối thiểu 10 số'],
                        number: json['Số điện thoại không hợp lệ'],
                        maxlength: json['Số điện thoại không hợp lệ']
                    },
                    provinceid: {
                        required: json['Hãy chọn tỉnh/thành phố']
                    },
                    districtid: {
                        required: json['Hãy chọn quận/huyện']
                    },
                    // hot_line: {
                    //     number: json['Số hot line không hợp lệ']
                    // },
                    representative_code: {
                        required: json['Hãy nhập mã đại diện']
                    }
                },
            });

            if (!form.valid()) {
                return false;
            }

            var branch_id = $('#branch_id').val();
            var is_representative = 0;
            if ($('#is_representative').is(':checked')) {
                is_representative = 1;
            }
            var is_actived = 0;
            if ($('#is_actived').is(':checked')) {
                is_actived = 1;
            }
            //thêm image mới
            var check_image = $('.image-show').find('input[name="img"]');
            var img = [];
            $.each(check_image, function () {
                img.push($(this).val());
            });
            //lấy danh sách image cũ để xóa
            var clickImg = $('.branch_image');
            var img_delete = [];
            $.each(clickImg, function () {
                img_delete.push($(this).val());
            });
            var email = $('#email').val();
            if (email != '') {
                if (!isValidEmailAddress(email)) {
                    $('.error_email').text(json['Email không hợp lệ']);
                    return false;
                } else {
                    $.ajax({
                        type: 'POST',
                        url: laroute.route('admin.branch.submit-edit'),
                        data: {
                            branch_id: branch_id,
                            branch_name: $('#branch_name').val(),
                            address: $('#address').val(),
                            phone: $('#phone').val(),
                            description: $('#description').val(),
                            representative_code: $('#representative_code').val(),
                            is_representative: is_representative,
                            is_actived: is_actived,
                            hot_line: $('#hot_line').val(),
                            provinceid: $('#provinceid').val(),
                            districtid: $('#districtid').val(),
                            email: $('#email').val(),
                            img: img,
                            img_delete: img_delete,
                            latitude: $('#latitude').val(),
                            longitude: $('#longitude').val()
                        },
                        dataType: "JSON",
                        success: function (response) {
                            if (response.success == 1) {
                                $('.error-name').text('');
                                swal(json["Cập nhật chi nhánh thành công"], "", "success");
                                window.location = laroute.route('admin.branch');
                            }
                            if (response.success == 0) {
                                swal(response.message, "", "error")
                            }
                        }
                    })
                }
            } else {
                $.ajax({
                    type: 'POST',
                    url: laroute.route('admin.branch.submit-edit'),
                    data: {
                        branch_id: branch_id,
                        branch_name: $('#branch_name').val(),
                        address: $('#address').val(),
                        phone: $('#phone').val(),
                        description: $('#description').val(),
                        representative_code: $('#representative_code').val(),
                        is_representative: is_representative,
                        is_actived: is_actived,
                        hot_line: $('#hot_line').val(),
                        provinceid: $('#provinceid').val(),
                        districtid: $('#districtid').val(),
                        email: $('#email').val(),
                        img: img,
                        img_delete: img_delete,
                        latitude: $('#latitude').val(),
                        longitude: $('#longitude').val()
                    },
                    dataType: "JSON",
                    success: function (response) {
                        if (response.success == 1) {
                            $('.error-name').text('');
                            swal(json["Cập nhật chi nhánh thành công"], "", "success");
                            window.location = laroute.route('admin.branch');
                        }
                        if (response.success == 0) {
                            swal(response.message, "", "error")
                        }
                    }
                })
            }

        });
        // $('#edit').validate({
        //     rules: {
        //         branch_name: {
        //             required: true,
        //         },
        //         address: {
        //             required: true
        //         },
        //         phone: {
        //             required: true,
        //             minlength: 10,
        //             maxlength: 11,
        //             number: true
        //         },
        //         provinceid: {
        //             required: true
        //         },
        //         districtid: {
        //             required: true
        //         },
        //         hot_line: {
        //             number: true
        //         },
        //         representative_code: {
        //             required: true
        //         }

        //     },
        //     messages: {
        //         branch_name: {
        //             required: 'Hãy nhập tên chi nhánh'
        //         },
        //         address: {
        //             required: 'Hãy nhập địa chỉ'
        //         },
        //         phone: {
        //             required: 'Hãy nhập số điện thoại',
        //             minlength: 'Tối thiểu 10 số',
        //             number: 'Số điện thoại không hợp lệ',
        //             maxlength: 'Số điện thoại không hợp lệ'
        //         },
        //         provinceid: {
        //             required: 'Hãy chọn tỉnh/thành phố'
        //         },
        //         districtid: {
        //             required: 'Hãy chọn quận/huyện'
        //         },
        //         hot_line: {
        //             number: 'Số hot line không hợp lệ'
        //         },
        //         representative_code: {
        //             required: 'Hãy nhập mã đại diện'
        //         }
        //     },
        //     submitHandler: function () {
        //         var branch_id = $('#branch_id').val();
        //         var is_representative = 0;
        //         if ($('#is_representative').is(':checked')) {
        //             is_representative = 1;
        //         }
        //         var is_actived = 0;
        //         if ($('#is_actived').is(':checked')) {
        //             is_actived = 1;
        //         }
        //         //thêm image mới
        //         var check_image = $('.image-show').find('input[name="img"]');
        //         var img = [];
        //         $.each(check_image, function () {
        //             img.push($(this).val());
        //         });
        //         //lấy danh sách image cũ để xóa
        //         var clickImg = $('.branch_image');
        //         var img_delete = [];
        //         $.each(clickImg, function () {
        //             img_delete.push($(this).val());
        //         });
        //         var email = $('#email').val();
        //         if (email != '') {
        //             if (!isValidEmailAddress(email)) {
        //                 $('.error_email').text('Email không hợp lệ');
        //                 return false;
        //             } else {
        //                 $.ajax({
        //                     type: 'POST',
        //                     url: laroute.route('admin.branch.submit-edit'),
        //                     data: {
        //                         branch_id: branch_id,
        //                         branch_name: $('#branch_name').val(),
        //                         address: $('#address').val(),
        //                         phone: $('#phone').val(),
        //                         description: $('#description').val(),
        //                         representative_code: $('#representative_code').val(),
        //                         is_representative: is_representative,
        //                         is_actived: is_actived,
        //                         hot_line: $('#hot_line').val(),
        //                         provinceid: $('#provinceid').val(),
        //                         districtid: $('#districtid').val(),
        //                         email: $('#email').val(),
        //                         img: img,
        //                         img_delete: img_delete,
        //                         latitude: $('#latitude').val(),
        //                         longitude: $('#longitude').val()
        //                     },
        //                     dataType: "JSON",
        //                     success: function (response) {
        //                         if (response.success == 1) {
        //                             $('.error-name').text('');
        //                             swal("Cập nhật chi nhánh thành công", "", "success");
        //                             window.location = laroute.route('admin.branch');
        //                         }
        //                         if (response.success == 0) {
        //                             swal(response.message, "", "error")
        //                         }
        //                     }
        //                 })
        //             }
        //         } else {
        //             $.ajax({
        //                 type: 'POST',
        //                 url: laroute.route('admin.branch.submit-edit'),
        //                 data: {
        //                     branch_id: branch_id,
        //                     branch_name: $('#branch_name').val(),
        //                     address: $('#address').val(),
        //                     phone: $('#phone').val(),
        //                     description: $('#description').val(),
        //                     representative_code: $('#representative_code').val(),
        //                     is_representative: is_representative,
        //                     is_actived: is_actived,
        //                     hot_line: $('#hot_line').val(),
        //                     provinceid: $('#provinceid').val(),
        //                     districtid: $('#districtid').val(),
        //                     email: $('#email').val(),
        //                     img: img,
        //                     img_delete: img_delete,
        //                     latitude: $('#latitude').val(),
        //                     longitude: $('#longitude').val()
        //                 },
        //                 dataType: "JSON",
        //                 success: function (response) {
        //                     if (response.success == 1) {
        //                         $('.error-name').text('');
        //                         swal("Cập nhật chi nhánh thành công", "", "success");
        //                         window.location = laroute.route('admin.branch');
        //                     }
        //                     if (response.success == 0) {
        //                         swal(response.message, "", "error")
        //                     }
        //                 }
        //             })
        //         }


        //     }
        // });
    },
    refresh: function () {
        $('input[name="search_branch"]').val('');
        $('.m_selectpicker').val('');
        $('.m_selectpicker').selectpicker('refresh');
        $(".btn-search").trigger("click");
    },
    modal_image: function () {
        $('#add-img').modal('show');
        $('#up-ima').empty();
        $('.dropzone')[0].dropzone.files.forEach(function (file) {
            file.previewElement.remove();
        });
        $('.dropzone').removeClass('dz-started');
    },
    save_image: function () {
        var arrayImage = new Array();
        $('.file_Name').each(function () {
            arrayImage.push($(this).val());
        });
        // $('.image-show').empty();
        for (let i = 0; i < arrayImage.length; i++) {
            let $_tpl = $('#imgeShow').html();
            let tpl = $_tpl;
            tpl = tpl.replace(/{link}/g, arrayImage[i]);
            tpl = tpl.replace(/{link_hidden}/g, arrayImage[i]);
            $('.image-show').append(tpl);
            $('#lb-avatar').css('display', 'block');
            $('#lb-img').css('display', 'block');
        }
        $('#add-img').modal('hide');
    },
    remove_img: function (e) {
        $(e).closest('.image-show-child').remove();
    },
};
$('#autotable').PioTable({
    baseUrl: laroute.route('admin.branch.list')
});
$('.m_selectpicker').selectpicker();

function isValidEmailAddress(emailAddress) {
    var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
    return pattern.test(emailAddress);
}
