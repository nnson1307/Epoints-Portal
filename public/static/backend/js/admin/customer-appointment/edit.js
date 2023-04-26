$(document).ready(function () {
    $.getJSON(laroute.route('translate'), function (json) {
    // $('#customer_id').select2();
    $('#staff_id').select2({
        placeholder: json['Chọn nhân viên']
    });
    $('#room_id').select2({
        placeholder: json['Chọn phòng']
    });
    $('#customer_appointment_time_id').select2();
    $('#date').datepicker({
        format: 'dd/mm/yyyy',
        startDate: '0d',
        language: 'vi',
        orientation: "bottom left", todayHighlight: !0,
    });
    $('#service_id').select2({
        placeholder: json['Chọn dịch vụ'],
        // ajax: {
        //     url: laroute.route('admin.customer_appointment.search-service'),
        //     dataType: 'json',
        //     delay: 250,
        //     type: 'POST',
        //     data: function (params) {
        //         var query = {
        //             search: params.term,
        //             page: params.page || 1
        //         };
        //         return query;
        //     }
        // },
        // minimumInputLength: 1
    }).on('select2:select', function (event) {
        // var arr = $("input[name='service_id']").map(function () {
        //     return $(this).val();
        // }).get();
        // var arr1 = $("input[name='service_name_hidden']").map(function () {
        //     return $(this).val();
        // }).get();
        $('#service_id').val('').trigger('change');
        var check1 = true;
        var check2 = true;
        $('#table').css('display', 'block');
        $.each($('#table_service tbody tr'), function () {
            let codeHidden = $(this).find("td input[name='service_id']");
            let codeHidden_add = $(this).find("td input[name='service_name_hidden']");
            let codeExists = codeHidden.val();
            let codeExists_add = codeHidden_add.val();
            var code = event.params.data.id;
            if (codeExists == code) {
                check1 = false;
                let quantitySv = codeHidden.parents('tr').find('input[name="quantity"]').val();
                let numbers = parseInt(quantitySv) + 1;
                codeHidden.parents('tr').find('input[name="quantity"]').val(numbers);
            }
            if (codeExists_add == code) {
                check2 = false;
                let quantitySv_add = codeHidden_add.parents('tr').find('input[name="quantity"]').val();
                let numbers_add = parseInt(quantitySv_add) + 1;
                codeHidden_add.parents('tr').find('input[name="quantity"]').val(numbers_add);
            }
        });
        if (check1 == true && check2 == true) {

            $.ajax({
                url: laroute.route('admin.customer_appointment.load-time'),
                dataType: 'JSON',
                data: {
                    id: event.params.data.id
                },
                method: 'POST',
                success: function (res) {
                    var tpl = $('#service-tpl').html();
                    var stts = $('#table_service tbody tr').length;
                    tpl = tpl.replace(/{stt}/g, stts + 1);
                    tpl = tpl.replace(/{service_name}/g, event.params.data.text);
                    tpl = tpl.replace(/{service_name_id}/g, event.params.data.id);
                    tpl = tpl.replace(/{time}/g, res.time);
                    $('#table_service > tbody').append(tpl);
                    $(".quantity").TouchSpin({
                        initval: 1,
                        min: 1
                    });
                    $('.remove_service').click(function () {
                        $(this).closest('.service_tb_add').remove();
                        //alert('ok');
                    });
                }
            });
        }

        // $(".quantity").TouchSpin({
        //     initval: 1,
        //     min: 1
        // });

});
$(".quantity").TouchSpin({
    initval: 1,
    min: 1
});

$('.remove_service').click(function () {
    $(this).closest('.service_tb').remove();
    //alert('ok');
});
$('.btn-status').click(function () {
    $('.btn-status').attr('class', 'btn-status btn btn-default');
    $(this).attr('class', 'btn-status btn btn-primary active');

});

$.ajax({
    url: laroute.route('admin.customer_appointment.load-time-edit'),
    dataType:'JSON',
    method:'POST',
    data:{
        id_appointment:$('#customer_appointment_id').val()
    },
    success:function (res) {
        $('#time').val(res.time);
        $('#time').select2({
            placeholder: json['Hãy chọn giờ hẹn']
        });
    }
});

var sv_table_load = [];
$.each($('#table_service').find('.service_tb'), function () {
    var $tds = $(this).find("input[name='appointment_service_id']");
    $.each($tds, function () {
        sv_table_load.push($(this).val());
    });
});
$('#btn_edit').click(function () {
    $.getJSON(laroute.route('translate'), function (json) {      
    var id = $('#customer_appointment_id').val();
    var staff_id = $('#staff_id').val();
    var room_id = $('#room_id').val();
    var time = $('#time').val();
    var date = $('#date').val();
    var status = $('.active').find(' input[name="status"]').val();
    var description = $('#description').val();
    //edit sv
    var sv_table = [];
    $.each($('#table_service').find('.service_tb'), function () {
        var $tds = $(this).find("input");
        $.each($tds, function () {
            sv_table.push($(this).val());
        });
    });
    //add sv
    var sv_table_add = [];
    $.each($('#table_service').find('.service_tb_add'), function () {
        var $tds = $(this).find("input");
        $.each($tds, function () {
            sv_table_add.push($(this).val());
        });
    });
    //remove sv
    var sv_table_submit = [];
    $.each($('#table_service').find('.service_tb'), function () {
        var $tds = $(this).find("input[name='appointment_service_id']");
        $.each($tds, function () {
            sv_table_submit.push($(this).val());
        });
    });
    var remove_sv = arr_diff(sv_table_load, sv_table_submit);

    $.ajax({
        url: laroute.route('admin.customer_appointment.submitEditForm'),
        method: 'POST',
        dataType: 'JSON',
        data: {
            id: id,
            staff_id: staff_id,
            room_id: room_id,
            time: time,
            date: date,
            status: status,
            description: description,
            sv_table: sv_table,
            sv_table_add: sv_table_add,
            remove_sv: remove_sv
        },
        success: function (response) {
            if (response.error == 0) {
                window.location.reload();
                swal(json["Cập nhật thành công"], "", "success");
            }
        }
    })
});
});


$('#huy').click(function () {
    var sv_table_submit = [];
    $.each($('#table_service').find('.service_tb'), function () {
        var $tds = $(this).find("input[name='appointment_service_id']");
        $.each($tds, function () {
            sv_table_submit.push($(this).val());
        });
    });
    var cut = arr_diff(sv_table_load, sv_table_submit);
    console.log(cut);
});
$('.m_selectpicker').val('default').selectpicker("refresh");
    });
    
});

function arr_diff(a1, a2) {

    var a = [], diff = [];

    for (var i = 0; i < a1.length; i++) {
        a[a1[i]] = true;
    }

    for (var i = 0; i < a2.length; i++) {
        if (a[a2[i]]) {
            delete a[a2[i]];
        } else {
            a[a2[i]] = true;
        }
    }

    for (var k in a) {
        diff.push(k);
    }

    return diff;
}