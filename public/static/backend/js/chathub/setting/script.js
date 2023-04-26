var vali;
$.ajax({
    url: laroute.route('chathub.validation'),
    method: 'GET',
    async: false,
    success: function(json) {
        vali = json;
    }
});
var channel = {
    showPopupEdit: function(id){
        $.ajax({
            url: laroute.route('setting.popup-edit'),
            method: 'POST',
            data: {id:id},
            success: function(res) {
                $('#modal-setting').html(res);
                $(document).ready(function(){
                    $('#is_dialogflow').on('click',function(){
                        if ($('#is_dialogflow').is(":checked")) {
                            $('[name="project_id_dialogflow"]').prop('disabled',false);
                            $('[name="private_key_dialogflow"]').prop('disabled',false);
                            $('[name="client_email_dialogflow"]').prop('disabled',false);
                        } else {
                            $('[name="project_id_dialogflow"]').prop('disabled',true);
                            $('[name="private_key_dialogflow"]').prop('disabled',true);
                            $('[name="client_email_dialogflow"]').prop('disabled',true);
                        }
                    })
                });
                $('#modal-setting-edit').modal();
            },
        });
    },
    saveChannel: function(){
        $.getJSON(laroute.route('translate'), function (json) {
            let is_dialogflow = 0;
            if ($('#is_dialogflow').is(":checked")) {
                is_dialogflow = 1;
            }
            $.ajax({
                url: laroute.route('setting.save-channel'),
                method: 'POST',
                data: {
                    'channel_id' : $('[name="channel_id"]').val(),
                    'is_dialogflow' : is_dialogflow,
                    'project_id_dialogflow': $('[name="project_id_dialogflow"]').val(),
                    'private_key_dialogflow' : $('[name="private_key_dialogflow"]').val(),
                    'client_email_dialogflow' : $('[name="client_email_dialogflow"]').val(),
                },
                success: function (response) {
                    if (!response.error) {
                        swal.fire(
                            response.message,
                            '',
                            'success'
                        ).then(function (e) {
                            $('#modal-setting-edit').modal('hide');
                        })
                    } else {
                        swal(response.message, "", "error");
                    }
                },
                error: function (res) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal(json['Chỉnh sửa thất bại'], mess_error, "error");
                }
            });
        });
    },
    add: function() {
        $.ajax({
            url: laroute.route('setting.add-channel'),
            method: 'POST',
            data: {},
            success: function(res) {
                $('#add-channel').html(res);
                $('#kt_modal_card').modal();
            },
        });
    },
    subscribeChannel: function(id) {
        $.ajax({
            url: laroute.route('setting.subscribe-channel'),
            dataType : 'JSON',
            method: 'POST',
            data: { id },
            success: function(res) {
                if(!res.errors){
                    swal.fire(vali.setting.SUBSCRIBE_SUCCESS, "", "success").then(function() {
                        location.reload();
                    });
                } else {
                    swal.fire(vali.setting.SUBSCRIBE_ERROR, "", "error").then(function() {
                        location.reload();
                    });
                }

            }
        });
    },
    unsubscribeChannel: function(id) {
        $.ajax({
            url: laroute.route('setting.unsubscribe-channel'),
            method: 'POST',
            data: { id },
            success: function() {
                swal.fire(vali.setting.UNSUBSCRIBE_SUCCESS, "", "success").then(function() {
                    location.reload();
                });
            }
        });
    },
    showOption: function(id) {
        var check = $('#show_option').is(":checked");
        if (check == true) {
            check = 1;
        } else {
            check = 0;
        }
        $.ajax({
            url: laroute.route('setting.show-option'),
            method: 'POST',
            data: { id, check },
            success: function() {
                swal.fire(vali.setting.TITLE, "", "success");

            }
        });
    }
}
