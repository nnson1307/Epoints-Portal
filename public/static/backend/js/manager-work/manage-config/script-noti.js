var Notification = {
    editMessage : function (id) {
        $.ajax({
            url: laroute.route('manager-work.manage-config.notification.show-popup'),
            data: {
                manage_config_notification_id : id
            },
            method: "POST",
            dataType: "JSON",
            success: function(data) {
                if (data.error == false) {
                    $('.append-popup').empty();
                    $('.append-popup').append(data.view);
                    $('#edit_message').modal('show');
                } else {
                    swal(data.message,'','error');
                }
            }
        });
    },

    append_para_txa: function (e) {
        var text = e;
        var txtarea = document.getElementById('manage_config_notification_message_popup');
        var scrollPos = txtarea.scrollTop;
        var caretPos = txtarea.selectionStart;

        var front = (txtarea.value).substring(0, caretPos);
        var back = (txtarea.value).substring(txtarea.selectionEnd, txtarea.value.length);
        txtarea.value = front + text + back;
        caretPos = caretPos + text.length;
        txtarea.selectionStart = caretPos;
        txtarea.selectionEnd = caretPos;
        txtarea.focus();
        txtarea.scrollTop = scrollPos;
    },

    changeMessage : function () {
        id = $('#manage_config_notification_id_popup').val();
        text = $('#manage_config_notification_message_popup').val();
        title = $('#manage_config_notification_title_popup').val();

        if (text.length == 0){
            swal($('#text_error_popup').val(),'','error');
        } else if (title.length == 0){
            swal($('#title_error_popup').val(),'','error');
        }else if (title.length > 191) {
            swal($('#title_191_error_popup').val(),'','error');
        } else {
            $('.block_'+id+' #manage_config_notification_message').val(text);
            $('.block_'+id+' #manage_config_notification_title').val(title);
            $('.block_'+id+' .message').text(text);
            $('.block_'+id+' .title').text(title);
            $('#edit_message').modal('hide');
        }
    },

    updateConfigNoti : function () {
        $.ajax({
            url: laroute.route('manager-work.manage-config.notification.update-notification'),
            data: $('#form-config-noti').serialize(),
            method: "POST",
            dataType: "JSON",
            success: function(data) {
                if (data.error == false) {
                    swal(data.message,'','success').then(function () {
                        window.location.href = laroute.route('manager-work.manage-config.notification');
                    });
                } else {
                    swal(data.message,'','error');
                }
            }
        });
    }
}