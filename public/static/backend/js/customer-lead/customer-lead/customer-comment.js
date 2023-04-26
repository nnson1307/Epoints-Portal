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
            } else {
                $(".description").summernote('insertImage', img['file']);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error(textStatus + " " + errorThrown);
        }
    });
};

var CustomerComment = {
    jsontranslate : JSON.parse(localStorage.getItem('tranlate')),
    
    addComment: function () {
      
        var code = $('.description').summernote('code');
        var customer_lead_id = $('#customer_lead_id').val();
        console.log(customer_lead_id);
        $.ajax({
            url: laroute.route('customer-lead.detail.add-comment'),
            data: {
                customer_lead_id : customer_lead_id,
                description : code
            },
            method: "POST",
            dataType: "JSON",
            success: function(res) {
                if (res.error == false) {
                    swal({
                        title:  CustomerComment.jsontranslate['Thêm bình luận thành công'],
                        text: 'Loading...',
                        type: 'success',
                        timer: 1500,
                        showConfirmButton: false,
                    })
                    .then(() => {
                        CustomerComment.getListCustomerComment();
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
    },

    addCommentChild: function (customer_lead_comment_id) {
        var code = $('.description_'+ customer_lead_comment_id).summernote('code');
        var customer_lead_id = $('#customer_lead_id').val();
        $.ajax({
            url: laroute.route('customer-lead.detail.add-comment'),
            data: {
                customer_lead_comment_id : customer_lead_comment_id,
                customer_lead_id : customer_lead_id,
                description : code
            },
            method: "POST",
            dataType: "JSON",
            success: function(res) {
                if (res.error == false) {
                    swal({
                        title:  CustomerComment.jsontranslate['Thêm bình luận thành công'],
                        text: 'Loading...',
                        type: 'success',
                        timer: 1500,
                        showConfirmButton: false,
                    })
                    .then(() => {
                        CustomerComment.getListCustomerComment();
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
    },

    showFormChat : function (parent_comment) {
        $.ajax({
            url: laroute.route('customer-lead.detail.show-form-comment'),
            data: {
                customer_lead_comment_id : parent_comment,
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
    },

    getListCustomerComment: function(){
        $.ajax({
            url: laroute.route('customer-lead.detail.get-list-comment'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                customer_lead_id : $('#customer_lead_id').val()
            },
            success: function (res) {
                $('#div-comment').html('');
                if (res.html != null) {
                    $('#div-comment').append(res.html);
                    registerSummernote('.description', 'Leave a comment', 1000, function(max) {
                        $('.description').text(max)
                    });
                }
            }
        });
    }
}