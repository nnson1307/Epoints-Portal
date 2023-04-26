var Document = {
    jsonLang: JSON.parse(localStorage.getItem('tranlate')),
    showPopup: function(manage_project_document_id = null) {
        $.getJSON(laroute.route('translate'), function (json) {
            $.ajax({
                // url: laroute.route('manager-project.work.detail.show-popup-upload-file'),
                url: laroute.route('manager-work.detail.show-popup-upload-file'),
                data: {
                    manage_project_document_id: manage_project_document_id
                },
                method: "POST",
                // dataType: "JSON",
                success: function(res) {
                    if (res.error == false) {
                        $('#block_append').empty();
                        $('#block_append').append(res.view);
                        $('#popup_upload_file').modal('show');
                        $('#dropzoneImage').dropzone({
                            paramName: 'file',
                            timeout: 180000,
                            maxFilesize: 1024, // MB
                            maxFiles: 1000,
                            // acceptedFiles: ".jpeg,.jpg,.png,.gif",
                            addRemoveLinks: true,
                            // parallelUploads: 1,
                            // headers: {
                            //     "X-CSRF-TOKEN": $('input[name=_token]').val()
                            // },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            dictRemoveFile: 'Xóa',
                            dictMaxFilesExceeded: json['Bạn tải quá nhiều file'],
                            dictInvalidFileType: json['Tệp không hợp lệ'],
                            dictCancelUpload: json['Hủy'],
                            dictFileTooBig : Document.jsonLang['Bạn tải file có dung lượng lớn ({{filesize}}MiB). Dung lượng tối đa: {{maxFilesize}}MiB.'],
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
                                this.on("success", function(file, response) {
                                    const typeImage = ["image/bmp", "image/gif", "image/vnd.microsoft.icon", "image/jpeg", "image/png", "image/svg+xml", "image/tiff", "image/webp"];
                                    let fileName = file.upload.filename;
                                    // var a = document.createElement('span');
                                    // a.className = "thumb-url btn btn-primary";
                                    // a.setAttribute('data-clipboard-text', laroute.route('manager-project.work.detail.upload-file') + response);
                                    if (file.status === "success") {
                                        //Xóa image trong dropzone
                                        $('#dropzoneImage')[0].dropzone.files.forEach(function(file) {
                                            file.previewElement.remove();
                                        });
                                        $('#dropzoneImage').removeClass('dz-started');
                                        //Append vào div image
                                        if (typeImage.indexOf(file.type) != -1) {
                                            let tpl = $('#imageShow').html();
                                            tpl = tpl.replace(/{link}/g, response.file);
                                            tpl = tpl.replace(/{link_hidden}/g, response.file);
                                            // tpl = tpl.replace(/{file_name}/g, file.upload.filename);
                                            tpl = tpl.replace(/{file_name}/g, file.name);
                                            // $('#file_name').val(file.upload.filename);
                                            // $('#upload-image').empty();
                                            if ($('.manage_document_file_id').length){
                                                $('#upload-image').empty();
                                            }
                                            $('#upload-image').append(tpl);
                                            $('#path').val(response.file);
                                            // $('#file_type').val('image');
                                        } else {
                                            let tpl = $('#imageShowFile').html();
                                            tpl = tpl.replace(/{link}/g, response.file);
                                            tpl = tpl.replace(/{link_hidden}/g, response.file);
                                            // tpl = tpl.replace(/{file_name}/g, file.upload.filename);
                                            tpl = tpl.replace(/{file_name}/g, file.name);
                                            // $('#file_name').val(file.upload.filename);
                                            // $('#upload-image').empty();
                                            if ($('.manage_document_file_id').length){
                                                $('#upload-image').empty();
                                            }
                                            $('#upload-image').append(tpl);
                                            $('#path').val(response.file);
                                            // $('#file_type').val('file');
                                        }
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

                                this.on('sending', function(file, xhr, formData) {
                                    /*Called just before each file is sent*/
                                    xhr.ontimeout = (() => {
                                        /*Execute on case of timeout only*/
                                        swal(Document.jsonLang['Quá thời gian upload'],'','error');
                                    });
                                });
                            }
                        });
                    } else {
                        swal('', res.message, 'error');
                    }
                },
                error: function(res) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function(a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal('', mess_error, "error");
                }
            });
        });
    },

    clear: function() {
        $('#frm-search-document')[0].reset();
        $("[name=created_by]").val('').trigger('change');
        $("[name=type]").val('').trigger('change');
        Document.search(1);
    },

    search: function(page = 1) {
        $.ajax({
            url: laroute.route('manager-project.work.detail.search-document'),
            method: "POST",
            data: $('#frm-search-document').serialize() + "&page=" + page,
            success: function(res) {
                if (res.error == false) {
                    $('.append-list-document').empty();
                    $('.append-list-document').append(res.view);
                } else {
                    swal.fire(res.message, '', 'error');
                }
            }
        });
    },

    removeImage: function(obj) {
        $(obj).closest('.image-show').remove();
        $('#path').val('');
    },

    addDocument: function(check) {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-file');
            form.validate({
                rules: {
                    // file_name: {
                    //     required: true,
                    //     maxlength: 255
                    // },
                    note: {
                        maxlength: 255
                    },
                    // path: {
                    //     required: true,
                    // }
                },
                messages: {
                    // file_name: {
                    //     required: json["Vui lòng nhập tên hồ sơ"],
                    //     maxlethis.on('sending', function(file, xhr, formData) {
                    //                                                 /*Called just before each file is sent*/
                    //                                                 xhr.ontimeout = (() => {
                    //                                                     /*Execute on case of timeout only*/
                    //                                                     swal(Document.jsonLang['Quá thời gian upload'],'','error');
                    //                                                 });
                    //                                             });ngth: json["Tên hồ sơ vượt quá 255 ký tự"],
                    // },
                    note: {
                        maxlength: json["Ghi chú vượt quá 255 ký tự"],
                    },
                    // path: {
                    //     required: json["Vui lòng chọn hồ sơ đính kèm"],
                    // }
                },
            });

            if (!form.valid()) {
                return false;
            }

            var document = [];
            $.each($('#upload-image').find(".image-show"), function () {
                var image = $(this).find($('img')).attr('src');
                var path = $(this).find($('.path')).val();
                var file_name = $(this).find($('.file_name')).val();
                var file_type = $(this).find($('.file_type')).val();

                document.push({
                    path:path,
                    image:image,
                    file_name : file_name,
                    file_type : file_type
                });
            });

            $.ajax({
                method: 'POST',
                url: laroute.route('manager-project.work.detail.add-file-document'),
                // data: form.serialize(),
                data: {
                    type_upload : $('input[name="type_upload"]:checked').val(),
                    name_upload : $('input[name="name_upload"]').val(),
                    link_upload : $('input[name="link_upload"]').val(),
                    document: document,
                    // note : $('.note').val(),
                    // manage_project_id : $('#manage_project_id').val(),
                    manage_work_id : $('#manage_work_id').val(),
                    manage_project_id : $('#manage_project').val(),
                    manage_project_document_id : $('.manage_project_document_id').val(),
                    manage_document_file_id : $('.manage_document_file_id').val()
                },
                dataType: "JSON",
                success: function(res) {
                    if (res.error == false) {
                        swal.fire(
                            res.message,
                            '',
                            'success'
                        ).then(function() {
                            Document.search(1);
                            if (check == 0) {
                                // location.reload();
                                $('#popup_upload_file').modal('hide');
                            } else {
                                $('#popup_upload_file').modal('hide');
                                $('.modal-backdrop').remove();
                                $('#block_append').empty();
                                $('#block_append').append(res.view);
                                $(document).ready(function () {
                                    $('#dropzoneImage').dropzone({
                                        paramName: 'file',
                                        timeout: 180000,
                                        maxFilesize: 1024, // MB
                                        maxFiles: 1000,
                                        // acceptedFiles: ".jpeg,.jpg,.png,.gif",
                                        addRemoveLinks: true,
                                        // parallelUploads: 1,
                                        // headers: {
                                        //     "X-CSRF-TOKEN": $('input[name=_token]').val()
                                        // },
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        },
                                        dictRemoveFile: 'Xóa',
                                        dictMaxFilesExceeded: json['Bạn tải quá nhiều file'],
                                        dictInvalidFileType: json['Tệp không hợp lệ'],
                                        dictCancelUpload: json['Hủy'],
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
                                            this.on("success", function(file, response) {
                                                const typeImage = ["image/bmp", "image/gif", "image/vnd.microsoft.icon", "image/jpeg", "image/png", "image/svg+xml", "image/tiff", "image/webp"];
                                                let fileName = file.upload.filename;
                                                // var a = document.createElement('span');
                                                // a.className = "thumb-url btn btn-primary";
                                                // a.setAttribute('data-clipboard-text', laroute.route('manager-project.work.detail.upload-file') + response);
                                                if (file.status === "success") {
                                                    //Xóa image trong dropzone
                                                    $('#dropzoneImage')[0].dropzone.files.forEach(function(file) {
                                                        file.previewElement.remove();
                                                    });
                                                    $('#dropzoneImage').removeClass('dz-started');
                                                    //Append vào div image
                                                    if (typeImage.indexOf(file.type) != -1) {
                                                        let tpl = $('#imageShow').html();
                                                        tpl = tpl.replace(/{link}/g, response.file);
                                                        tpl = tpl.replace(/{link_hidden}/g, response.file);
                                                        tpl = tpl.replace(/{file_name}/g, file.upload.filename);
                                                        // $('#file_name').val(file.upload.filename);
                                                        // $('#upload-image').empty();
                                                        if ($('.manage_document_file_id').length){
                                                            $('#upload-image').empty();
                                                        }
                                                        $('#upload-image').append(tpl);
                                                        $('#path').val(response.file);
                                                        // $('#file_type').val('image');
                                                    } else {
                                                        let tpl = $('#imageShowFile').html();
                                                        tpl = tpl.replace(/{link}/g, response.file);
                                                        tpl = tpl.replace(/{link_hidden}/g, response.file);
                                                        tpl = tpl.replace(/{file_name}/g, file.upload.filename);
                                                        // $('#file_name').val(file.upload.filename);
                                                        // $('#upload-image').empty();
                                                        if ($('.manage_document_file_id').length){
                                                            $('#upload-image').empty();
                                                        }
                                                        $('#upload-image').append(tpl);
                                                        $('#path').val(response.file);
                                                        // $('#file_type').val('file');
                                                    }
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

                                            this.on('sending', function(file, xhr, formData) {
                                                /*Called just before each file is sent*/
                                                xhr.ontimeout = (() => {
                                                    /*Execute on case of timeout only*/
                                                    swal(Document.jsonLang['Quá thời gian upload'],'','error');
                                                });
                                            });
                                        }
                                    });
                                })

                                $('#popup_upload_file').modal('show');

                            }
                        })
                    } else {
                        swal(res.message, "", "error");
                    }
                },
            })
        })
    },
    removeDocument: function(id) {
        $.getJSON(laroute.route('translate'), function (json) {
            swal({
                title: json['Xoá hồ sơ'],
                text: json["Bạn có muốn xóa không?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json['Xóa'],
                cancelButtonText: json['Hủy'],

            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        url: laroute.route('manager-project.work.detail.remove-file-document'),
                        method: "POST",
                        data: {
                            manage_document_file_id: id
                        },
                        success: function(res) {
                            if (res.error == false) {
                                swal.fire(res.message, '', 'success').then(function() {
                                    location.reload();
                                });
                            } else {
                                swal.fire(res.message, '', 'error');
                            }
                        }
                    });
                }
            });
        });
    }
}

var Image = {
    changeFolder : function (manage_project_document_id = null){
        $.ajax({
            url: laroute.route('manager-project.work.detail.show-popup-change-folder'),
            data: {
                manage_project_document_id: manage_project_document_id
            },
            method: "POST",
            // dataType: "JSON",
            success: function(res) {
                if (res.error == false) {
                    $('.append_popup_show').empty();
                    $('.append_popup_show').append(res.view);
                    $('#popup_show_file_change_folder').modal('show');
                } else {
                    swal('', res.message, 'error');
                }
            },
            error: function(res) {
                var mess_error = '';
                $.map(res.responseJSON.errors, function(a) {
                    mess_error = mess_error.concat(a + '<br/>');
                });
                swal('', mess_error, "error");
            }
        });
    },

    submitChangeFolder : function (){
        $.ajax({
            url: laroute.route('manager-project.work.detail.submit-change-folder'),
            data: $('#form-change-folder').serialize(),
            method: "POST",
            // dataType: "JSON",
            success: function(res) {
                if (res.error == false) {
                    swal('', res.message, 'success').then(function (){
                        $('#popup_show_file_change_folder').modal('hide');
                    });
                } else {
                    swal('', res.message, 'error');
                }
            },
            error: function(res) {
                var mess_error = '';
                $.map(res.responseJSON.errors, function(a) {
                    mess_error = mess_error.concat(a + '<br/>');
                });
                swal('', mess_error, "error");
            }
        });
    }
}

Document.search();

var arrRange = {};
arrRange[Document.jsonLang['Hôm nay']] = [moment(), moment()],
arrRange[Document.jsonLang['Hôm qua']] = [moment().subtract(1, "days"), moment().subtract(1, "days")],
arrRange[Document.jsonLang["7 ngày trước"]] = [moment().subtract(6, "days"), moment()],
arrRange[Document.jsonLang["30 ngày trước"]] = [moment().subtract(29, "days"), moment()],
arrRange[Document.jsonLang["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")],
arrRange[Document.jsonLang["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]

$(".daterange-picker-list").daterangepicker({
    autoUpdateInput: false,
    autoApply: true,
    buttonClasses: "m-btn btn",
    applyClass: "btn-primary",
    cancelClass: "btn-danger",
    // maxDate: moment().endOf("day"),
    startDate: moment().startOf("day"),
    // endDate: moment().add(1, 'days'),
    locale: {
        format: 'DD/MM/YYYY',
        "applyLabel": Document.jsonLang["Đồng ý"],
        "cancelLabel": Document.jsonLang["Thoát"],
        "customRangeLabel": Document.jsonLang["Tùy chọn ngày"],
        daysOfWeek: [
            Document.jsonLang["CN"],
            Document.jsonLang["T2"],
            Document.jsonLang["T3"],
            Document.jsonLang["T4"],
            Document.jsonLang["T5"],
            Document.jsonLang["T6"],
            Document.jsonLang["T7"]
        ],
        "monthNames": [
            Document.jsonLang["Tháng 1 năm"],
            Document.jsonLang["Tháng 2 năm"],
            Document.jsonLang["Tháng 3 năm"],
            Document.jsonLang["Tháng 4 năm"],
            Document.jsonLang["Tháng 5 năm"],
            Document.jsonLang["Tháng 6 năm"],
            Document.jsonLang["Tháng 7 năm"],
            Document.jsonLang["Tháng 8 năm"],
            Document.jsonLang["Tháng 9 năm"],
            Document.jsonLang["Tháng 10 năm"],
            Document.jsonLang["Tháng 11 năm"],
            Document.jsonLang["Tháng 12 năm"]
        ],
        "firstDay": 1
    },
    ranges: arrRange
}).on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'))
});