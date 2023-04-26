$.getJSON(laroute.route('translate'), function (json) {
$('#province').change(function () {
    var province_id= $(this).val();
    $.ajax({
        method: 'post',
        url: laroute.route('customer.change-province'),
        data: {id_province: province_id},
        dataType: 'JSON',
        success: function (data) {
            $('select[name=district_id]').empty().prepend('<option>Quận/Huyện</option>');
            $('select[name=ward_id]').empty().prepend('<option>Phường/ Xã</option>');
            $.each(data,function (index,element) {
                $('select[name=district_id]').append('<option value="' + index + '">' + element + '</option>')
            });
        }

    })
});
});
$.getJSON(laroute.route('translate'), function (json) {
$('#district').change(function () {

    var district_id= $(this).val();
    $.ajax({
        method: 'post',
        url: laroute.route('customer.change-district'),
        data: {id_district: district_id},
        dataType: 'JSON',
        success: function (data) {
            $('select[name=ward_id]').empty().prepend('<option>Phường/ Xã</option>');
            $.each(data,function (index,element) {
                $('select[name=ward_id]').append('<option value="' + index + '">' + element + '</option>')
            });
        }

    })
});
});