var jsonLang = JSON.parse(localStorage.getItem('tranlate'));

var view = {
    _init: function () {
        new AutoNumeric.multiple('.config_general_value', {
            currencySymbol: '',
            decimalCharacter: '.',
            digitGroupSeparator: ',',
            decimalPlaces: 0,
            eventIsCancelable: true,
            minimumValue: 0
        });
    },
    submitEdit: function () {
        var arrayData = [];

        $.each($('.data_view').find(".div_child"), function () {
            var isActive = 0;

            if ($(this).find($('.is_actived')).is(':checked')) {
                isActive = 1;
            }

            var configGeneralId = $(this).find($('.config_general_id')).val();
            var configGeneralCode = $(this).find($('.config_general_code')).val();
            var configGeneralValue = $(this).find($('.config_general_value')).val().replace(new RegExp('\\,', 'g'), '');

            arrayData.push({
                config_general_id: configGeneralId,
                config_general_code: configGeneralCode,
                is_actived: isActive,
                config_general_value: configGeneralValue,
            });
        });

        $.ajax({
            url: laroute.route('shift.config-general.update'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                arrayData: arrayData
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {

                        }

                        if (result.value == true) {

                        }
                    });
                } else {
                    swal(res.message, '', "error");
                }
            }
        })
    }
};