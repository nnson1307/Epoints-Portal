$('#autotable').PioTable({
    baseUrl: laroute.route('ticket.list-ticket-created')
});
$('#autotables').PioTable({
    baseUrl: laroute.route('ticket.list-my-ticket')
});

$('.m_selectpicker').selectpicker();
$('select[name="is_actived"]').select2();

$(".date-timepicker").datetimepicker({
    todayHighlight: !0,
    autoclose: !0,
    pickerPosition: "bottom-left",
    format: "dd/mm/yyyy hh:ii",
    // minDate: new Date(),
    // locale: 'vi'
});

$("#form-edit #date_issue").datetimepicker({
    todayHighlight: !0,
    autoclose: !0,
    pickerPosition: "bottom-left",
    format: "dd/mm/yyyy hh:ii",
    endDate: new Date(),
});
$("#form-edit #date_request").datetimepicker({
    todayHighlight: !0,
    autoclose: !0,
    pickerPosition: "bottom-left",
    format: "dd/mm/yyyy hh:ii",
    startDate: new Date(),
});

// $("#date_expected").datetimepicker({
//     todayHighlight: !0,
//     autoclose: !0,
//     pickerPosition: "bottom-left",
//     format: "dd/mm/yyyy hh:ii",
// }).on('changeDate', function(selected) {
//     var maxDate = new Date(selected.date.valueOf());
//     $('#date_issue').datepicker('setStartDateTime', maxDate);
// });
var arrRange = {};
arrRange[lang['Hôm nay']] = [moment(), moment()],
    arrRange[lang['Hôm qua']] = [moment().subtract(1, "days"), moment().subtract(1, "days")],
    arrRange[lang["7 ngày trước"]] = [moment().subtract(6, "days"), moment()],
    arrRange[lang["30 ngày trước"]] = [moment().subtract(29, "days"), moment()],
    arrRange[lang["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")],
    arrRange[lang["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
$(".daterange-picker").daterangepicker({
    autoUpdateInput: false,
    autoApply: true,
    buttonClasses: "m-btn btn",
    applyClass: "btn-primary",
    cancelClass: "btn-danger",
    maxDate: moment().endOf("day"),
    startDate: moment().startOf("day"),
    endDate: moment().add(1, 'days'),
    locale: {
        format: 'DD/MM/YYYY',
        "applyLabel": lang["Đồng ý"],
        "cancelLabel": lang["Thoát"],
        "customRangeLabel": lang["Tùy chọn ngày"],
        daysOfWeek: [
            lang["CN"],
            lang["T2"],
            lang["T3"],
            lang["T4"],
            lang["T5"],
            lang["T6"],
            lang["T7"]
        ],
        "monthNames": [
            lang["Tháng 1 năm"],
            lang["Tháng 2 năm"],
            lang["Tháng 3 năm"],
            lang["Tháng 4 năm"],
            lang["Tháng 5 năm"],
            lang["Tháng 6 năm"],
            lang["Tháng 7 năm"],
            lang["Tháng 8 năm"],
            lang["Tháng 9 năm"],
            lang["Tháng 10 năm"],
            lang["Tháng 11 năm"],
            lang["Tháng 12 năm"]
        ],
        "firstDay": 1
    },
    ranges: arrRange
}).on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'))
});

var ticket = {
    dropzoneFile: function() {
        Dropzone.options.dropzoneFile = {
            paramName: 'file',
            maxFilesize: 10, // MB
            maxFiles: 10,
            acceptedFiles: ".pdf,.doc,.docx,.pdf,.csv,.xls,.xlsx",
            addRemoveLinks: true,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
            },
            renameFile: function(file) {
                var dt = new Date();
                var time = dt.getTime().toString() + dt.getDate().toString() + (dt.getMonth() + 1).toString() + dt.getFullYear().toString();
                var random = "";
                var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                for (let z = 0; z < 10; z++) {
                    random += possible.charAt(Math.floor(Math.random() * possible.length));
                }
                return time + "_" + random + "." + file.name.substr((file.name.lastIndexOf('.') + 1));
            },
            init: function() {
                this.on("sending", function(file, xhr, data) {
                    data.append("link", "_ticket.");
                });

                this.on("success", function(file, response) {
                    var a = document.createElement('span');
                    a.className = "thumb-url btn btn-primary";
                    a.setAttribute('data-clipboard-text', laroute.route('ticket.upload-file') + response);
                    if (response.error == false) {
                        $("#up-file-temp").append("<input type='hidden' class='" + file.upload.filename + "'  name='fileName' value='" + response.file + "'>");
                    }
                });

                this.on('removedfile', function(file, response) {
                    var name = file.upload.filename;
                    $.ajax({
                        url: laroute.route('admin.service.delete-image'),
                        method: "POST",
                        data: {

                            filename: name
                        },
                        success: function() {
                            $("input[class='file_Name']").each(function() {
                                var $this = $(this);
                                if ($this.val() === name) {
                                    $this.remove();
                                }
                            });

                        }
                    });
                });
            }
        };
    },
    // dropzoneImage: function() {
    //     new Dropzone.options.dropzoneFile = {
    //         paramName: 'file',
    //         maxFilesize: 10, // MB
    //         maxFiles: 10,
    //         acceptedFiles: "image/jpeg,image/png,image/gif,image/jpg",
    //         addRemoveLinks: true,
    //         headers: {
    //             "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
    //         },
    //         renameFile: function(file) {
    //             var dt = new Date();
    //             var time = dt.getTime().toString() + dt.getDate().toString() + (dt.getMonth() + 1).toString() + dt.getFullYear().toString();
    //             var random = "";
    //             var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    //             for (let z = 0; z < 10; z++) {
    //                 random += possible.charAt(Math.floor(Math.random() * possible.length));
    //             }
    //             return time + "_" + random + "." + file.name.substr((file.name.lastIndexOf('.') + 1));
    //         },
    //         init: function() {
    //             this.on("sending", function(file, xhr, data) {
    //                 data.append("link", "_ticket_image.");
    //             });

    //             this.on("success", function(file, response) {
    //                 var a = document.createElement('span');
    //                 a.className = "thumb-url btn btn-primary";
    //                 a.setAttribute('data-clipboard-text', laroute.route('ticket.upload-file') + response);
    //                 if (response.error == false) {
    //                     $("#up-file-temp").append("<input type='hidden' class='" + file.upload.filename + "'  name='image[]' value='" + response.file + "'>");
    //                 }
    //             });

    //             this.on('removedfile', function(file, response) {
    //                 var name = file.upload.filename;
    //                 $.ajax({
    //                     url: laroute.route('admin.service.delete-image'),
    //                     method: "POST",
    //                     data: {
    //                         filename: name
    //                     },
    //                     success: function() {
    //                         $("input[class='image[]']").each(function() {
    //                             var $this = $(this);
    //                             if ($this.val() === name) {
    //                                 $this.remove();
    //                             }
    //                         });

    //                     }
    //                 });
    //             });
    //         }
    //     };
    // },
    modalFile: function() {
        $('#up-file-temp').empty();
        $('#dropzoneFile')[0].dropzone.files.forEach(function(file) {
            file.previewElement.remove();
        });
        $('#dropzoneFile').removeClass('dz-started');

        $('#modal-file-ticket').modal({
            backdrop: 'static',
            keyboard: false
        });
    },
    // modalImage: function() {
    //     $('#up-image-temp').empty();
    //     $('#dropzoneImage')[0].dropzone.files.forEach(function(file) {
    //         file.previewElement.remove();
    //     });
    //     console.log(1)
    //     $('#dropzoneImage').removeClass('dz-started');
    //     $('#modal-image-ticket').modal({
    //         backdrop: 'static',
    //         keyboard: false
    //     });
    //     console.log(2)
    // },
    submitFileticket: function() {
        var checkFile = $('#up-file-temp').find('input[name="fileName"]');

        $.each(checkFile, function() {
            let tpl = $('#tpl-file').html();
            tpl = tpl.replace(/{fileName}/g, $(this).val());
            $('.div_file_ticket').append(tpl);
        });

        $('#modal-file-ticket').modal('hide');
    },
    // submitImageticket: function() {
    //     var checkFile = $('#up-image-temp').find('input[name="image[]"]');

    //     $.each(checkFile, function() {
    //         let tpl = $('#tpl-image').html();
    //         tpl = tpl.replace(/{link_image}/g, $(this).val());
    //         $('.div_image_ticket').append(tpl);
    //     });

    //     $('#modal-image-ticket').modal('hide');
    // },
    removeFile: function(obj) {
        $(obj).closest('.div_file').remove();
        $(obj).closest('.div_file').find('[name=file_ticket]').remove();
    },
    changeBoolean: function(obj) {
        if ($(obj).is(":checked")) {
            $(obj).val(1);
        } else {
            $(obj).val(0);
        }
    },
    configSearch: function() {
        $('#modal-config').modal();
    },
    saveConfig: function() {
        let search = $('.config_search [name="search[]"]:checked').map(function() {
            return this.value;
        }).get();
        let column = $('.config_column [name="column[]"]:checked').map(function() {
            return this.value;
        }).get();
        $.ajax({
            url: laroute.route('ticket.save-config'),
            data: {
                search: search,
                column: column,
            },
            method: "POST",
            dataType: "JSON",
            success: function(data) {
                if (data.status == 1) {
                    swal(
                        lang['Cấu hình thành công'],
                        '',
                        'success'
                    );
                    location.reload();
                } else {
                    swal(
                        lang['Cấu hình thất bại'],
                        '',
                        'warning'
                    );
                }
            }
        });
    }

};

function uploadImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        var imageAvatar = $('#ticket_img');
        reader.onload = function(e) {
            $('#blah').attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
        var file_data = $('#getFile').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('link', '_ticket.');

        var fsize = input.files[0].size;
        var fileInput = input,
            file = fileInput.files && fileInput.files[0];
        var img = new Image();

        img.src = window.URL.createObjectURL(file);

        img.onload = function() {
            var imageWidth = img.naturalWidth;
            var imageHeight = img.naturalHeight;

            window.URL.revokeObjectURL(img.src);

            $('.image-size').text(imageWidth + "x" + imageHeight + "px");

        };
        $('.image-capacity').text(Math.round(fsize / 1024) + 'kb');

        $('.image-format').text(input.files[0].name.split('.').pop().toUpperCase());

        if (Math.round(fsize / 1024) <= 10240) {
            $('.error_img').text('');
            $.ajax({
                url: laroute.route("ticket.upload-file"),
                method: "POST",
                data: form_data,
                contentType: false,
                cache: false,
                processData: false,
                success: function(res) {
                    if (res.error == false) {
                        let tpl = $('#tpl-image').html();
                        tpl = tpl.replace(/{link_image}/g, res.file);
                        $('.div_avatar').append(tpl);
                        // imageAvatar.val(res.file);
                        // $('.delete-img').css('display', 'block');
                    }
                }
            });
        } else {
            $('.error_img').text(lang['Hình ảnh vượt quá dung lượng cho phép']);
        }

    }
}
$('#add-rating').click(function() {
    $('#modal-rating-ticket').modal();
});

var edit = {
    _init: function() {},
    save: function(ticketId) {
        $.getJSON(laroute.route('translate'), function(json) {
            var form = $('#form-edit');
            form.find('select:selected').prop('disabled', false);
            form.validate({
                rules: {
                    localtion_id: {
                        required: true
                    },
                    ticket_issue_group_id: {
                        required: true
                    },
                    ticket_issue_id: {
                        required: true,
                    },
                    ticket_type: {
                        required: true
                    },
                    issule_level: {
                        required: true
                    },
                    priority: {
                        required: true
                    },
                    title: {
                        required: true,
                        maxlength: 255
                    },
                    customer_id: {
                        required: true,
                    },
                    // customer_address: {
                    //     required: true,
                    //     maxlength: 255
                    // },
                    // description: {
                    //     required: true,
                    //     maxlength: 255
                    // },
                    date_issue: {
                        required: true,
                    },
                    // date_estimated: {
                    //     required: true,
                    // },
                    // date_expected: {
                    //     required: true,
                    // },
                    queue_process_id: {
                        required: true,
                    },
                    // operate_by: {
                    //     required: true,
                    // },
                    // processor: {
                    //     required: true,
                    // },
                },
                messages: {
                    localtion_id: {
                        required: lang['Vui lòng chọn thành phố'],
                    },
                    ticket_issue_group_id: {
                        required: lang['Vui lòng chọn loại yêu cầu'],
                    },
                    ticket_issue_id: {
                        required: lang['Vui lòng chọn yêu cầu'],
                    },
                    ticket_type: {
                        required: lang['Vui lòng chọn loại ticket'],
                    },
                    issule_level: {
                        required: lang['Vui lòng chọn cấp độ sự cố'],
                    },
                    priority: {
                        required: lang['Vui lòng chọn mức độ ưu tiên'],
                    },
                    date_issue: {
                        required: lang['Vui lòng chọn thời gian phát sinh'],
                    },
                    date_expected: {
                        required: lang['Vui lòng chọn thời gian khách hàng yêu cầu'],
                    },
                    date_estimated: {
                        required: lang['Vui lòng chọn thời gian bắt buộc hoàn thành'],
                    },
                    queue_process_id: {
                        required: lang['Vui lòng chọn queue xử lý'],
                    },
                    title: {
                        required: lang['Vui lòng nhập tiêu đề'],
                        maxlength: lang['Tiêu đề tối đa 255 kí tự'],
                    },
                    customer_id: {
                        required: lang['Vui lòng chọn khách hàng'],
                    },
                    customer_address: {
                        required: lang['Vui lòng chọn địa chỉ khách hàng'],
                        maxlength: lang['Địa chỉ khách hàng tối đa 255 kí tự'],
                    },
                    description: {
                        required: lang['Vui lòng nhập mô tả'],
                        maxlength: lang['Mô tả tối đa 255 kí tự'],
                    },
                    operate_by: {
                        required: lang['Vui lòng chọn nhân viên chủ trì'],
                    },
                    // processor: {
                    //     required: 'Vui lòng chọn nhân viên xử lý',
                    // },
                },
            });

            if (!form.valid()) {
                return false;
            }
            var fileTicket = [];
            $.each($('.div_file_ticket').find('input[name="file_ticket"]'), function() {
                fileTicket.push($(this).val());
            });
            var image = [];
            $.each($('#form-edit').find('[name="image[]"]'), function() {
                image.push($(this).val());
            });
            // var image = $('#form-edit').find('[name="image[]"]').map(function(i, el) {
            //     return el.value;
            // });
            console.log(image)
            $.ajax({
                url: laroute.route('ticket.submit'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    ticket_id: ticketId,
                    localtion_id: $('#localtion_id').val(),
                    ticket_issue_group_id: $('#ticket_issue_group_id').val(),
                    ticket_issue_id: $('#ticket_issue_id').val(),
                    ticket_type: $('#ticket_type').val(),
                    issule_level: $('#issule_level').val(),
                    priority: $('#priority').val(),
                    title: $('#title').val(),
                    description: $('#description').val(),
                    customer_id: $('#customer_id').val(),
                    customer_address: $('#customer_address').val(),
                    staff_notification_id: $('#staff_notification_id').val(),
                    date_issue: $('#date_issue').val(),
                    date_estimated: $('#date_estimated').val(),
                    date_expected: $('#date_expected').val(),
                    date_request: $('#date_request').val(),
                    queue_process_id: $('#queue_process_id').val(),
                    operate_by: $('#operate_by').val(),
                    processor: $('#processor').val(),
                    fileTicket: fileTicket,
                    image: $('#ticket_img').val(),
                    ticket_status_id: $('#ticket_status_id').val(),
                    contract_id: $('#contract_id').val(),
                    image: image,
                },
                success: function(res) {
                    if (res.status == 1) {
                        if (ticketId) {
                            swal(lang["Chỉnh sửa ticket thành công"], "", "success").then(function(result) {
                                window.location.href = laroute.route('ticket');
                            });
                        } else {
                            swal(lang["Thêm ticket thành công"], "", "success").then(function(result) {
                                window.location.href = laroute.route('ticket');
                            });
                        }
                    } else {
                        swal(lang["Thêm ticket thất bại"], '', "error");
                    }
                },
                error: function(res) {
                    swal(lang['Chỉnh sửa thất bại'], '', "error");
                }
            });
        });
    },
    submitrate: function(ticketId) {
        var point = $('#modal-rating-ticket [name="rate"]:checked').val();
        var description = $('#modal-rating-ticket [name="description"]').val();
        $.ajax({
            url: laroute.route('ticket.rating'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                ticket_id: ticketId,
                point: point,
                description: description,
            },
            success: function(res) {
                console.log(res)
                if (res.status == 1) {
                    swal(lang["Đánh giá thành công"], "", "success").then(function(result) {
                        // window.location.href = laroute.route('ticket');
                        $('#modal-rating-ticket').modal('hide');
                    });
                } else {
                    swal(lang["Đánh giá thất bại"], '', "error");
                }
            },
            error: function(res) {
                swal(lang['Đánh giá thất bại'], '', "error");
            }
        });
    }
};
var Material = {
    remove: function(obj, id) {
        $(obj).closest('tr').addClass('m-table__row--danger');
        $.getJSON(laroute.route('translate'), function(json) {
            swal({
                title: json['Thông báo'],
                text: json["Bạn có muốn xóa không?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json['Xóa'],
                cancelButtonText: json['Hủy'],
                onClose: function() {
                    $(obj).closest('tr').removeClass('m-table__row--danger');
                }
            }).then(function(result) {
                if (result.value) {
                    $.post(laroute.route('ticket.material.remove', { id: id }), function() {
                        swal(
                            json['Xóa thành công.'],
                            '',
                            'success'
                        );
                        clear();
                        loadMaterial();
                    });
                }
            });
        });
    },
    changeStatus: function(obj, id, action) {
        $.post(laroute.route('ticket.material.change-status'), { id: id, action: action }, function(data) {
            $('#autotable').PioTable('refresh');
        }, 'JSON');
    },
    addClose: function() {
        $('#form-add-material').validate({
            rules: {
                ticket_code: {
                    required: true,
                },
                import_file: {
                    required: false,
                    accept: false
                },
                description: {
                    required: true,
                    maxlength: 255,
                    minlength: 1
                },
            },
            messages: {
                ticket_code: {
                    required: lang['Mã ticket là trường bắt buộc nhập'],
                },
                description: {
                    required: lang['Nội dung đề xuất là trường bắt buộc nhập'],
                    maxlength: lang['Nội dung đề xuất không quá 255 ký tự'],
                }
            },
            submitHandler: function() {
                $.ajax({
                    url: laroute.route('ticket.material.add'),
                    data: $("#form-add-material").serialize(),
                    method: "POST",
                    dataType: "JSON",
                    success: function(data) {
                        if (data.status == 1) {
                            swal(
                                lang['Thêm vật tư thành công'],
                                '',
                                'success'
                            );
                            loadMaterial();
                            $('#modalAdd').modal('hide');
                            $('#form-add-material [name=description]').val('');
                            $('#form-add-material .table-add-material tbody').html('');
                        }
                    }
                });
                return false;
            }
        });

    },
    edit: function(id) {
        $.ajax({
            url: laroute.route('ticket.material.edit'),
            data: {
                ticket_request_material_id: id
            },
            method: "POST",
            dataType: 'JSON',
            success: function(data) {
                $('#modalEdit').modal('show');
                $('#modalEdit [name="ticket_request_material_id"]').val(data.ticket_request_material_id);
                // $('#modalEdit [name="ticket_code"]').val(data.ticket_request_material_code).trigger('change');
                $('#modalEdit [name="description"]').val(data.description)
                $('#modalEdit [name="proposer_by"]').val(data.proposer_by);
                $('#modalEdit [name="proposer_date"]').val(data.proposer_date);
                $('#modalEdit [name="status_material"]').val(data.status).trigger('change');
                $('#form-edit-material .table-add-material tbody').html('');
                if (data.material_detail.length) {
                    $.each(data.material_detail, function(index, val) {
                        var remove_btn = '<button class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill remove-btn"><i class="la la-trash"></i></button>';
                        var tr_id = $('#form-edit-material .table-add-material tbody tr').length;
                        tr_id += 1;
                        var edit_btn = '<button type="button" class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill replace-material-btn"><i class="la la-edit"></i><input type="hidden" name="ticket_request_material_detail_id[' + val.product_id + ']" value="' + val.ticket_request_material_detail_id + '"> </button>';
                        var input_counter = $('#input-counter').html();
                        input_counter = input_counter.replace(/{max}/g, val.quantity_max);
                        input_counter = input_counter.replace(/{value}/g, val.quantity);
                        input_counter = input_counter.replace(/{product_id}/g, val.product_id);
                        var select_status = $('#select-status').html();
                        var name_status = 'status[' + val.product_id + ']';
                        select_status = select_status.replaceAll(/{product_id}/g, name_status);
                        var tr = [tr_id, val.product_code, val.product_name, val.quantity_approve, val.quantity_max, input_counter, val.unitName, remove_btn + select_status]; //, select_status
                        $('#form-edit-material .table-add-material tbody').append(generate_row_material(tr));
                    });
                    $('#modalEdit select[name^="status"]').addClass('d-none');
                    // $('#form-edit-material input.number-input').attr('disabled', true);
                    // $('#form-edit-material .number input').attr('disabled', true);
                    // $('#form-edit-material .number span').remove();
                }

            }
        })
    },
    submitEdit: function() {
        $('#form-edit-material').validate({
            rules: {
                ticket_code: {
                    required: true,
                },
                import_file: {
                    required: false,
                    accept: false
                },
                description: {
                    required: true,
                    maxlength: 255,
                    minlength: 1
                },
            },
            messages: {
                ticket_code: {
                    required: lang['Mã ticket là trường bắt buộc nhập'],
                },
                description: {
                    required: lang['Mô tả là trường bắt buộc nhập'],
                    maxlength: lang['Mô tả không quá 255 ký tự'],
                }
            },
            submitHandler: function() {
                $('#form-edit-material [name="ticket_code"],#form-edit-material [name="ticket_code"],#form-edit-material select[name^="status["]').attr('disabled', false);
                $.ajax({
                    url: laroute.route('ticket.material.submit-edit'),
                    data: $("#form-edit-material").serialize(),
                    method: "POST",
                    dataType: "JSON",
                    success: function(data) {
                        if (data.status == 1) {
                            swal(
                                lang['Cập nhật vật tư thành công'],
                                '',
                                'success'
                            );
                            loadMaterial();
                            $('#modalEdit').modal('hide');
                        }
                    }
                });
                $('#form-edit-material [name="ticket_code"],#form-edit-material [name="ticket_code"],#form-edit-material select[name^="status["]').attr('disabled', true);
                return false;
            }
        });
    },
    view: function(id) {
        $.ajax({
            url: laroute.route('ticket.material.edit'),
            data: {
                ticket_request_material_id: id
            },
            method: "POST",
            dataType: 'JSON',
            success: function(data) {
                $('#modalView').modal('show');
                $('#modalView [name="ticket_request_material_id"]').val(data.ticket_request_material_id);
                $('#modalView [name="ticket_code"]').val(data.ticket_id).trigger('change');
                $('#modalView [name="description"]').val(data.description);
                $('#modalView [name="proposer_by"]').val(data.proposer_by);
                $('#modalView [name="proposer_date"]').val(data.proposer_date);
                $('#modalView [name="status_material"]').val(data.status).trigger('change');
                if (data['material_detail'].length) {
                    $.each(data.material_detail, function(index, val) {
                        var tr_id = $('#form-view-material .table-add-material tbody tr').length;
                        tr_id += 1;
                        var edit_btn = '<button type="button" class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill replace-material-btn"><i class="la la-edit"></i><input type="hidden" name="ticket_request_material_detail_id[' + val.product_id + ']" value="' + val.ticket_request_material_detail_id + '"> </button>';
                        var input_counter = $('#input-counter').html();
                        input_counter = input_counter.replace(/{max}/g, val.quantity_max);
                        input_counter = input_counter.replace(/{value}/g, val.quantity);
                        input_counter = input_counter.replace(/{product_id}/g, val.product_id);
                        var select_status = $('#select-status').html();
                        var name_status = 'status[' + val.product_id + ']';
                        select_status = select_status.replaceAll(/{product_id}/g, name_status);
                        if (data.status == 'new' && val.status !== 'new') {
                            $('.button-action').removeClass('d-none');
                        }
                        // , select_status, edit_btn
                        var tr = [tr_id, val.product_code, val.product_name, val.quantity, val.quantity_max, input_counter, val.unitName + select_status];
                        $('#form-view-material .table-add-material tbody').append(generate_row_material(tr));
                    });
                    $('#form-view-material input', '#form-view-material select', '#form-view-material textarea').attr('disabled', true);
                    $('#form-view-material .number input').attr('disabled', true);
                    $('#form-view-material .number span').remove();
                    $('#form-view-material select[name^="status["]').addClass('d-none');

                }

            }
        })
    },
    clear: function() {
        clear();
    },
    refresh: function() {
        $('input[name="search_keyword"]').val('');
        $('.m_selectpicker').val('');
        $('.m_selectpicker').selectpicker('refresh');
        $(".btn-search").trigger("click");
    },
    search: function() {
        $(".btn-search").trigger("click");
    },
    configSearch: function() {
        $('#modal-config').modal();
    },
    saveConfig: function() {
        let search = $('.config_search [name="search[]"]:checked').map(function() {
            return this.value;
        }).get();
        let column = $('.config_column [name="column[]"]:checked').map(function() {
            return this.value;
        }).get();
        $.ajax({
            url: laroute.route('ticket.material.save-config'),
            data: {
                search: search,
                column: column,
            },
            method: "POST",
            dataType: "JSON",
            success: function(data) {
                console.log(data.data);
                if (data.status == 1) {
                    swal(
                        lang['Cấu hình thành công'],
                        '',
                        'success'
                    );
                    location.reload();
                } else {
                    swal(
                        lang['Cấu hình thất bại'],
                        '',
                        'warning'
                    );
                }
            }
        });
    },
    upload: function() {
        var formData = new FormData();
        formData.append('import_file', $('#file-import')[0].files[0]);
        $.ajax({
            url: laroute.route('ticket.material.parserExcel'),
            data: formData,
            method: "POST",
            dataType: "JSON",
            contentType: false,
            processData: false,
            success: function(data) {
                console.log(data.data);
                if (data.status == 1) {
                    swal(
                        lang['Import thành công'],
                        '',
                        'success'
                    );
                    $.each(data.data, function(index, val) {
                        var tr_id = $('#form-add-material .table-add-material tbody tr').length;
                        tr_id += 1;
                        var remove_btn = '<button class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill remove-btn"><i class="la la-trash"></i></button>';
                        var input_counter = $('#input-counter').html();
                        input_counter = input_counter.replace(/{max}/g, val.quantity);
                        input_counter = input_counter.replace(/{value}/g, val.quantity_current);
                        input_counter = input_counter.replace(/{product_id}/g, val.product_id);
                        var tr = [tr_id, val.product_code, val.product_name, input_counter, val.unitName, remove_btn];
                        $('#form-add-material .table-add-material tbody').append(generate_row_material(tr));
                        $('#form-edit-material .table-add-material tbody').append(generate_row_material(tr));
                    });
                } else {
                    swal(
                        lang['Import thất bại'],
                        '',
                        'warning'
                    );
                }
            }
        });
        // }

    }
};
// xử lý nút thêm vật tư
$('#form-add-material [name=material]').change(function() {
    var product_id = $(this).val();
    if (!product_id) {
        return;
    }
    if ($(this).closest('body').find('.table-add-material tbody tr [name="' + product_id + '"]').length) {
        swal(
            lang['Đã có vật tư này'],
            '',
            'warning'
        );
        return;
    }
    var form_id = '#' + $(this).closest('form').attr('id') + ' ';
    var tr_id = $(form_id + '.table-add-material tbody tr').length;
    tr_id += 1;
    $.ajax({
        url: laroute.route('ticket.material.get-item-material'),
        data: {
            product_id: product_id,
        },
        method: "POST",
        dataType: "JSON",
        success: function(response) {
            if (response.status == 1) {
                var remove_btn = '<button class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill remove-btn"><i class="la la-trash"></i></button>';
                var input_counter = $('#input-counter').html(); //response.data.quantity
                input_counter = input_counter.replace(/{max}/g, response.data.quantity);
                input_counter = input_counter.replace(/{value}/g, 1);
                input_counter = input_counter.replace(/{product_id}/g, response.data.product_id);
                var tr = [tr_id, response.data.product_code, response.data.product_name, input_counter, response.data.unitName, remove_btn];
                $(form_id + '.table-add-material tbody').append(generate_row_material(tr));
                $(form_id + '[name=material]').val('').trigger('change');
            }
        }
    });
});
// chỉnh sửa
$('#form-edit-material [name=material]').change(function() {
    var product_id = $(this).val();
    if (!product_id) {
        return;
    }
    if ($(this).closest('body').find('.table-add-material tbody tr [name="' + product_id + '"]').length) {
        swal(
            lang['Đã có vật tư này'],
            '',
            'warning'
        );
        return;
    }
    var form_id = '#' + $(this).closest('form').attr('id') + ' ';
    var tr_id = $(form_id + '.table-add-material tbody tr').length;
    tr_id += 1;
    $.ajax({
        url: laroute.route('ticket.material.get-item-material'),
        data: {
            product_id: product_id,
        },
        method: "POST",
        dataType: "JSON",
        success: function(response) {
            if (response.status == 1) {
                var remove_btn = '<button class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill remove-btn"><i class="la la-trash"></i></button>';
                var input_counter = $('#input-counter').html(); //response.data.quantity
                input_counter = input_counter.replace(/{max}/g, response.data.quantity);
                input_counter = input_counter.replace(/{value}/g, 1);
                input_counter = input_counter.replace(/{product_id}/g, response.data.product_id);
                var name_status = 'status[' + response.data.product_id + ']';
                var select_status = $('#select-status').html();
                select_status = select_status.replaceAll(/{product_id}/g, name_status);
                if (response.data.status == 'new' && response.data.status !== 'new') {
                    $('.button-action').removeClass('d-none');
                }
                var tr = [tr_id, response.data.product_code, response.data.product_name, 0, response.data.quantity, input_counter, response.data.unitName, remove_btn + select_status];
                $(form_id + '.table-add-material tbody').append(generate_row_material(tr));
                $(form_id + '[name=material]').val('').trigger('change');
            }
            $(form_id + '.table-add-material tbody select[name^="status["]').addClass('d-none');
        }
    });
});

// xu ly thay the vat tu
$('#form-replace-material [name=material_replace]').change(function() {
    var product_id = $(this).val();
    if (!product_id) {
        return;
    }
    if ($(this).closest('body').find('.table-add-material tbody tr [name="' + product_id + '"]').length) {
        swal(
            lang['Đã có vật tư này'],
            '',
            'warning'
        );
        return;
    }
    var form_id = '#' + $(this).closest('form').attr('id') + ' ';
    var tr_id = $(form_id + '.table-add-material tbody tr').length;
    tr_id += 1;
    $.ajax({
        url: laroute.route('ticket.material.get-item-material'),
        data: {
            product_id: product_id,
        },
        method: "POST",
        dataType: "JSON",
        success: function(response) {
            if (response.status == 1) {
                var remove_btn = '<button class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill remove-btn"><i class="la la-trash"></i></button>';
                var input_counter = $('#input-counter').html(); //response.data.quantity
                input_counter = input_counter.replace(/{max}/g, response.data.quantity);
                input_counter = input_counter.replace(/{value}/g, 1);
                input_counter = input_counter.replace(/{product_id}/g, response.data.product_id);
                var name_status = 'status[' + response.data.product_id + ']';
                var select_status = $('#select-status').html();
                select_status = select_status.replaceAll(/{product_id}/g, name_status);
                var quantity_accept = '<span class="quantity_accept">1</span>';
                var tr = [tr_id, response.data.product_code, response.data.product_name, input_counter, response.data.quantity, quantity_accept, response.data.unitName, select_status, remove_btn];
                $(form_id + '.table-add-material tbody').append(generate_row_material(tr));
                $(form_id + '[name=material_replace]').val('').trigger('change');
                $(form_id + '.table-add-material tbody tr select[name^="status["]').val("approve").change().prop('disabled', 'disabled');
            }
        }
    });
});
$(document).on('change', '#form-replace-material .table-add-material tbody tr .number-input', function() {
    $(this).closest('tr').find('td .quantity_accept').text($(this).val());
});
$(document).on('click', '.table-list-material .remove-btn', function() {
    $(this).closest('tr').remove();
});
$(document).on('click', '.table-add-material .remove-btn', function() {
    $(this).closest('tr').remove();
    loadMaterial();
});
// 
$(document).on('change', '.table-add-material select[name^="status["]', function() {
    $status_val = $(this).val();
    if ($status_val != 'new') {
        $(this).closest('tr').find('.replace-material-btn').addClass('d-none');
    } else {
        $(this).closest('tr').find('.replace-material-btn').removeClass('d-none');
    }
});
$(document).on('change', '.table-propose select[name^="status["]', function() {
    $status_val = $(this).val();
    if ($status_val != 'new') {
        $(this).closest('tr').find('.replace-material-btn').addClass('d-none');
        $(this).closest('tr').find('.number-input').prop('disabled', 'disabled');
        $(this).closest('tr').find('.minus,.plus').addClass('d-none');
    } else {
        $(this).closest('tr').find('.replace-material-btn').removeClass('d-none');
        $(this).closest('tr').find('.number-input').prop('disabled', false);
        $(this).closest('tr').find('.minus,.plus').removeClass('d-none');
    }
});
$(document).on('click', '.replace-material-btn', function() {
    var tr = $(this).closest('tr').clone();
    tr.find('td:last-child').remove();
    $('#form-replace-material .table-propose tbody').html(tr);
    $('#modalReplace').modal('show');
})
$('#form-replace-material').submit(function() {
    $('#form-replace-material .table-add-material tbody tr td .remove-btn').remove();
    $('#form-replace-material .table-add-material tbody tr td .number').each(function() {
        $(this).addClass('d-none');
        $(this).closest('tr').find('select[name^="status["] option[value=approve]').attr('selected', 'selected');
    });
    $('#form-edit-material .table-add-material tbody tr').find('td:first-child').each(function(index, value) {
        $(this).html(index);
    })
    var append_mateial = $('#form-replace-material .table-add-material tbody').html();
    $('#form-edit-material .table-add-material tbody').append(append_mateial);
    var product_replace = $('#form-replace-material .table-propose tbody tr .number-input').attr('name'); // option[value=approve]
    $('#form-edit-material .table-add-material tbody tr input[name="' + product_replace + '"]').closest('tr').find('select[name^="status["]').val('approve').change();
    console.log(product_replace, $('#form-edit-material .table-add-material tbody tr input[name="' + product_replace + '"]'))

    $('#form-replace-material .table-propose tbody').html('');
    $('#form-replace-material .table-add-material tbody').html('');
    $('#modalReplace').modal('hide');
    return false;
});
$('#modalReplace').on('hidden.bs.modal', function(e) {
    $('#form-replace-material .table-propose tbody').html('');
    $('#form-replace-material .table-add-material tbody').html('');
});
$(document).on('click', '.view-material a', function() {
    var id_request_material = $(this).attr('data-id');
    Material.view(id_request_material);
});

function generate_row_material(arr) {
    var tr_append = '<tr>';
    $.each(arr, function(index, val) {
        tr_append += '<td>';
        tr_append += val;
        tr_append += '</td>';
    });
    tr_append += '</tr>';
    return tr_append;
}

function clear() {
    $('[name="ticket_code"]').val('').trigger('change');
    // $('[name="description"]').val('');
    $('.table-add-material tbody').find('tr').remove();
    $('#file-import').val('');
}

function loadMaterial() {
    $('#form-edit .table-list-material tbody').find('tr').remove();
    var ticket_id = $('#form-edit [name="ticket_id"]').val();
    if (!ticket_id) {
        return;
    }
    $.ajax({
        url: laroute.route('ticket.material.get-ticket-material'),
        data: {
            ticket_id: ticket_id
        },
        method: "POST",
        dataType: 'JSON',
        success: function(data) {
            if (data.success > 0) {
                if (data.material_list) {
                    if (!data.material_list.length) {
                        return;
                    }
                    $('.ticket-material-table').removeClass('d-none');
                    $('#form-edit .table-list-material tbody').html('');
                    $('#form-edit .table-list-material-detail tbody').html('');
                    $.each(data.material_list, function(index, val) {
                        var tr_id = parseInt($('#form-edit .table-list-material tbody tr').length);
                        tr_id += 1;
                        var edit_btn = '<button type="button" class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill" onclick="Material.edit(' + val.ticket_request_material_id + ')"><i class="la la-edit"></i><input type="hidden" name="ticket_request_material_detail_id[' + val.product_id + ']" value="' + val.ticket_request_material_detail_id + '"> </button>';
                        var remove_btn = '<button type="button" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" onclick=" Material.remove(this,' + val.ticket_request_material_id + ')"><i class="la la-trash"></i></button>';
                        var request_material_code = '<a href="javascript:void(0);" data-id="' + val.ticket_request_material_id + '" class="view-material-child">' + val.ticket_request_material_code + '</a>';
                        // if (typeof detail != 'undefined') {
                        // edit_btn = "";
                        // remove_btn = "";
                        // }
                        if (val.approved_by == '') {
                            val.approved_date = '';
                        }
                        if (val.status_id != 'new') {
                            edit_btn = '';
                            remove_btn = '';
                        }
                        var tr = [tr_id, request_material_code, val.description, val.proposer_by, val.proposer_date, val.approved_by, val.approved_date, val.status, (edit_btn + remove_btn)];
                        $('#form-edit .table-list-material tbody').append(generate_row_material(tr));
                        $('#form-edit .view-material-child').closest('td').addClass('view-material');
                    });
                    $.each(data.material_list_detail, function(index, val) {
                        var tr_id_detail = parseInt($('#form-edit .table-list-material-detail tbody tr').length);
                        tr_id_detail += 1;
                        // var edit_btn = '<button type="button" class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill" onclick="Material.edit(' + val.ticket_request_material_id + ')"><i class="la la-edit"></i><input type="hidden" name="ticket_request_material_detail_id[' + val.product_id + ']" value="' + val.ticket_request_material_detail_id + '"> </button>';
                        // var remove_btn = '<button type="button" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" onclick=" Material.remove(this,' + val.ticket_request_material_id + ')"><i class="la la-trash"></i></button>';
                        var tr = [tr_id_detail, val.product_code, val.product_name, val.quantity, val.quantity_approve, val.quantity_reality, val.quantity_return]; //, val.status
                        $('#form-edit .table-list-material-detail tbody').append(generate_row_material(tr));
                    });
                }

            }

        }
    });
}
$(document).ready(function() {
    // clear();
    loadMaterial();
});

$(document).on('click', '.minus', function() {
    var $input = $(this).parent().find('input');
    var count = parseInt($input.val()) - 1;
    count = count < 0 ? 0 : count;
    $input.val(count);
    $input.change();
    return false;
});
$(document).on('click', '.plus', function() {
    var $input = $(this).parent().find('input');
    // if ($(this).parent().find('input').val() >= $input.attr('max')) {
    //     return false;
    // }
    $input.val(parseInt($input.val()) + 1);
    $input.change();
    return false;
});

$('#form-edit #ticket_type').change(function() {
    let ticket_type_id = $(this).find('option:selected').val();
    if (!ticket_type_id) {
        // $('#ticket_issue_id').prop('disabled', true);
        // $('#issule_level').prop('disabled', true);
    } else {
        $.ajax({
            url: laroute.route('ticket.get-request-by-issue-group-id'),
            data: {
                ticket_type_id: ticket_type_id,
            },
            method: "POST",
            dataType: "JSON",
            success: function(response) {
                if (response.status == 1) {
                    $('#ticket_issue_id').html(response.html);
                    $('#ticket_issue_id').prop('disabled', false);
                }
            }
        });
    }
});
$('#form-edit #ticket_issue_id').change(function(e) {
    let ticket_issue_id = $(this).find('option:selected').val();
    if (!ticket_issue_id) {
        return;
    }
    $.ajax({
        url: laroute.route('ticket.get-request-by-issue-group-id'),
        data: {
            ticket_issue_id: ticket_issue_id,
        },
        method: "POST",
        dataType: "JSON",
        success: function(response) {
            if (response.status == 1) {
                $('#issule_level').val(response.level).change();
            }
        }
    });
});
$('#form-edit #queue_process_id').change(function(e) {
    let queue_process_id = $(this).find('option:selected').val();
    if (!queue_process_id) {
        return;
    }
    $.ajax({
        url: laroute.route('ticket.get-request-by-issue-group-id'),
        data: {
            queue_process_id: queue_process_id,
        },
        method: "POST",
        dataType: "JSON",
        success: function(response) {
            if (response.status == 1) {
                if (response.data.operate_by) {
                    $('#operate_by').html(response.data.operate_by);
                } else {
                    $('#operate_by').html('<option>' + lang['Queue hiện tại chưa có nhân viên chủ trì'] + '</option>');
                }
                if (response.data.processor) {
                    $('#processor').html(response.data.processor);
                } else {
                    $('#processor').html('<option>' + lang['Queue hiện tại chưa có nhân viên xử lý'] + '</option>');
                }
            }
        }
    });
});
$('#form-edit [name="customer_id"]').change(function(e) {
    let customer_id = $(this).val();
    let address = $('#form-edit select[name="customer_address_select"] option[value="' + customer_id + '"]').text();
    $('#form-edit #customer_address').val(address);
});