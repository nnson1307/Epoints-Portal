$('#smsCampaign').selectpicker();
$('#gender').select2();
$('#branch').select2();

$('#birthday').datepicker({
    format: "dd/mm/yyyy",
    startDate: '0d',
    language: 'vi',
});
$('#smsCampaign').change(function () {
    let id = $(this).val();
    let brandname_id=$('#brandname_id');
    let value=$('#value');
    let status=$('.status');
    if (id == '') {
        brandname_id.val('');
        value.val('');
        status.empty();
    } else {
        $.ajax({
            url: laroute.route('admin.sms.get-info-sms-campaign'),
            method: "POST",
            data: {id: id},
            dataType:"JSON",
            success: function (data) {
                 if (data!=''){
                     brandname_id.val(data.brandname_id);
                     value.val(data.value);
                     status.empty();
                     if (data.status=='draft'){
                         status.append('<span class="m-badge m-badge--warning m-badge--wide ">Nháp</span>')
                     } else{
                         status.append('<span class="m-badge m-badge--success m-badge--wide ">Đang sử dụng</span>')
                     }
                 }
            }
        })
    }
});

var sendSms={

}


