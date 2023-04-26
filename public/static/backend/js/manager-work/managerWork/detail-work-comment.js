uploadImgCk = function (file,parent_comment = null) {
    let out = new FormData();
    out.append('file', file, file.name);

    $.ajax({
        method: 'POST',
        url: laroute.route('manager-work.detail.upload-file'),
        contentType: false,
        cache: false,
        processData: false,
        data: out,
        success: function (img) {
            if (parent_comment != null){
                $(".description_"+parent_comment).summernote('insertImage', img['file']);
                var markupStr = '<h2>hello world</h2>';
                $(".description_"+parent_comment).summernote('code', markupStr);
            } else {
                $(".description").summernote('insertImage', img['file']);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error(textStatus + " " + errorThrown);
        }
    });
};

var Comment = {
    addComment: function () {
        var code = $('.description').summernote('code');
        var manage_work_id = $('#manage_work_id').val();
        $.ajax({
            url: laroute.route('manager-work.detail.add-comment'),
            data: {
                manage_work_id : manage_work_id,
                description : code
            },
            method: "POST",
            dataType: "JSON",
            success: function(res) {
                if (res.error == false) {
                    $('.table-message-main > tbody').prepend(res.view);
                    $('.description').summernote('code', '');
                } else {
                    swal('',res.message,'error');
                }
            },
            error: function (res) {
                var mess_error = '';
                $.map(res.responseJSON.errors, function (a) {
                    mess_error = mess_error.concat(a + '<br/>');
                });
                swal('', mess_error, "error");
            }
        });
    },

    addCommentChild: function (manage_comment_id) {
        var code = $('.description_'+manage_comment_id).summernote('code');
        var manage_work_id = $('#manage_work_id').val();
        $.ajax({
            url: laroute.route('manager-work.detail.add-comment'),
            data: {
                manage_parent_comment_id : manage_comment_id,
                manage_work_id : manage_work_id,
                description : code
            },
            method: "POST",
            dataType: "JSON",
            success: function(res) {
                if (res.error == false) {
                    $('.tr_child_'+manage_comment_id).append(res.view);
                    $('.description_'+manage_comment_id).summernote('code', '');
                } else {
                    swal('',res.message,'error');
                }
            },
            error: function (res) {
                var mess_error = '';
                $.map(res.responseJSON.errors, function (a) {
                    mess_error = mess_error.concat(a + '<br/>');
                });
                swal('', mess_error, "error");
            }
        });
    },

    showFormChat : function (parent_comment) {
        $.ajax({
            url: laroute.route('manager-work.detail.show-form-comment'),
            data: {
                manage_comment_id : parent_comment,
            },
            method: "POST",
            dataType: "JSON",
            success: function(res) {
                if (res.error == false) {
                    $('.form-chat-message').remove();
                    $(res.view).insertAfter($('.tr_'+parent_comment));
                    $('.description_'+parent_comment).summernote({
                        placeholder: '',
                        tabsize: 2,
                        height: 100,
                        toolbar: [
                            ['style', ['style']],
                            ['font', ['bold', 'underline', 'clear']],
                            ['fontname', ['fontname', 'fontsize']],
                            ['color', ['color']],
                            ['para', ['ul', 'ol', 'paragraph']],
                            ['table', ['table']],
                            ['insert', ['link', 'picture']],
                        ],
                        callbacks: {
                            onImageUpload: function(files) {
                                for(let i=0; i < files.length; i++) {
                                    uploadImgCk(files[i],parent_comment);
                                }
                            }
                        },
                    });
                } else {
                    swal('',res.message,'error');
                }
            },
            error: function (res) {
                var mess_error = '';
                $.map(res.responseJSON.errors, function (a) {
                    mess_error = mess_error.concat(a + '<br/>');
                });
                swal('', mess_error, "error");
            }
        });
    }
}