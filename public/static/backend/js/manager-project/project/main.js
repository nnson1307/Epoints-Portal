var jsonLang = JSON.parse(localStorage.getItem('tranlate'));
var Project = {
    int: () => {
        $(".summernote").summernote({
            height: 208,
            lang: 'vi-VN',
            placeholder: '',
            toolbar: [
                ['style', ['bold', 'italic', 'underline']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']]
            ]
        })
        $('#date_start').datepicker({
            todayHighlight: true,
            autoclose: true,
            format: 'dd/mm/yyyy',
        });
        $('#date_end').datepicker({
            todayHighlight: true,
            autoclose: true,
            format: 'dd/mm/yyyy',
        });

        AutoNumeric.multiple('.budget,.resource',{
            currencySymbol : '',
            decimalCharacter : '.',
            digitGroupSeparator : ',',
            decimalPlaces: 0,
            minimumValue: 0
        });

        AutoNumeric.multiple('.progress-input',{
            currencySymbol : '',
            decimalCharacter : '.',
            digitGroupSeparator : ',',
            decimalPlaces: 0,
            minimumValue: 0,
            maximumValue: 100,
        });
        
    },

    addContact : function (){
        nCustomer++;
        let tpl = $('#userContactAdd').html();
        tpl = tpl.replace(/{key}/g, nCustomer);
        $('.add-customer-contact').append(tpl);
    },

    removeUserContact : function (key){
        $('.group_'+key).remove();
    },

    addWork : function (){

    }
}

var Document = {
    jsonLang: JSON.parse(localStorage.getItem('tranlate')),
    showPopup: function(manage_document_file_id = null) {
        $.getJSON(laroute.route('translate'), function (json) {
            $.ajax({
                url: laroute.route('manager-project.work.detail.show-popup-upload-file'),
                data: {
                    manage_document_file_id: manage_document_file_id
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
                            maxFilesize: 1024, // MB
                            maxFiles: 1000,
                            timeout:180000,
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
                            dictFileTooBig : json['Bạn tải file có dung lượng lớn ({{filesize}}MiB). Dung lượng tối đa: {{maxFilesize}}MiB.'],

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
                                    // a.setAttribute('data-clipboard-text', laroute.route('manager-work.detail.upload-file') + response);
                                    if (file.status === "success") {
                                        //Xóa image trong dropzone
                                        $('#dropzoneImage')[0].dropzone.files.forEach(function(file) {
                                            file.previewElement.remove();
                                        });
                                        $('#dropzoneImage').removeClass('dz-started');
                                        n++;
                                        //Append vào div image
                                        if (typeImage.indexOf(file.type) != -1) {
                                            let tpl = $('#imageShow').html();
                                            tpl = tpl.replace(/{link}/g, response.file);
                                            tpl = tpl.replace(/{link_hidden}/g, response.file);
                                            // tpl = tpl.replace(/{file_name}/g, file.upload.filename);
                                            tpl = tpl.replace(/{file_name}/g, file.name);
                                            tpl = tpl.replace(/{path}/g, response.file);
                                            tpl = tpl.replace(/{n}/g, n);
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
                                            tpl = tpl.replace(/{path}/g, response.file);
                                            tpl = tpl.replace(/{n}/g, n);
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
                    //     maxlength: json["Tên hồ sơ vượt quá 255 ký tự"],
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

            if ($('#manage_work_id').val() == undefined){
                $.each($('#upload-image').find(".image-show"), function () {
                    var image = $(this).find($('img')).attr('src');
                    var path = $(this).find($('.path')).val();
                    var file_name = $(this).find($('.file_name')).val();
                    var file_type = $(this).find($('.file_type')).val();
                    nDocument++;
                    if (file_type == 'image'){
                        let tpl = $('#imageShow').html();
                        tpl = tpl.replace(/{link}/g, image);
                        tpl = tpl.replace(/{link_hidden}/g, image);
                        tpl = tpl.replace(/{file_name}/g, file_name);
                        tpl = tpl.replace(/{path}/g, path);
                        tpl = tpl.replace(/{n}/g, nDocument);
                        $('.list-document-append').append(tpl);
                    } else {
                        console.log(path);
                        let tpl = $('#imageShowFile').html();
                        tpl = tpl.replace(/{link}/g, image);
                        tpl = tpl.replace(/{link_hidden}/g, image);
                        tpl = tpl.replace(/{file_name}/g, file_name);
                        tpl = tpl.replace(/{path}/g, path);
                        tpl = tpl.replace(/{n}/g, nDocument);
                        $('.list-document-append').append(tpl);
                    }

                });
                $('#popup_upload_file').modal('hide');
            } else {
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
                        document : document,
                        // note : $('.note').val(),
                        manage_work_id : $('#manage_work_id').val(),
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
                                Document.searchDocumentWork(1);
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
                                            timeout:180000,
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
                                            dictFileTooBig : json['Bạn tải file có dung lượng lớn ({{filesize}}MiB). Dung lượng tối đa: {{maxFilesize}}MiB.'],
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
                                                    // a.setAttribute('data-clipboard-text', laroute.route('manager-work.detail.upload-file') + response);
                                                    if (file.status === "success") {
                                                        n++;
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
                                                            tpl = tpl.replace(/{path}/g, response.file);
                                                            tpl = tpl.replace(/{n}/g, n);
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
                                                            tpl = tpl.replace(/{path}/g, response.file);
                                                            tpl = tpl.replace(/{n}/g, n);
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
            }

        })
    },

    removeImage: function(obj) {
        $(obj).closest('.image-show').remove();
        // $('#path_work').val('');
    },
}

Project.int();

const characters ='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

function generateString(length) {
    let result = ' ';
    const charactersLength = characters.length;
    for ( let i = 0; i < length; i++ ) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }

    return result;
}

