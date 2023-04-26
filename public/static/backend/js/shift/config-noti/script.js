var Config = {
    editMessage : function (sf_timekeeping_notification_id){
        $.ajax({
            url: laroute.route('config-noti.show-popup'),
            data: {
                sf_timekeeping_notification_id : sf_timekeeping_notification_id
            },
            method: "POST",
            dataType: "JSON",
            success: function(data) {
                if (data.error == false) {
                    $('.append-popup').empty();
                    $('.append-popup').append(data.view);
                    new AutoNumeric.multiple('.time_send', {
                        currencySymbol: '',
                        decimalCharacter: '.',
                        digitGroupSeparator: ',',
                        decimalPlaces: 0,
                        eventIsCancelable: true,
                        minimumValue: 0
                    });

                    $('.type_send').change(function (){
                        if ($('.type_send option:selected').val() == 0){
                            $('.time_send').val(0);
                            $('.time_send').prop('disabled',true);
                        } else {
                            $('.time_send').prop('disabled',false);
                        }
                    });
                    $('#edit_message').modal('show');
                } else {
                    swal(data.message,'','error');
                }
            }
        });
    },

    changeMessage : function (){
        $.ajax({
            url: laroute.route('config-noti.update-message'),
            data: $('#form-update').serialize(),
            method: "POST",
            dataType: "JSON",
            success: function(data) {
                if (data.error == false) {
                    swal(data.message,'','success').then(function (){
                        location.reload();
                    });
                } else {
                    swal(data.message,'','error');
                }
            }
        });
    },

    updateConfigNoti : function (){
        $.ajax({
            url: laroute.route('config-noti.update-noti'),
            data: $('#form-config-noti').serialize(),
            method: "POST",
            dataType: "JSON",
            success: function(data) {
                if (data.error == false) {
                    swal(data.message,'','success').then(function () {
                        window.location.href = laroute.route('config-noti');
                    });
                } else {
                    swal(data.message,'','error');
                }
            }
        });
    }
};