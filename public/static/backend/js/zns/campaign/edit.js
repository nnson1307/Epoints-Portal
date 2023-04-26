var stt = 0;
// $("#time-send").timepicker({
//     minuteStep: 15,
//     defaultTime: "08:00:00",
//     showMeridian: !1,
//     snapToStep: !0,
// });
$('#day-send').datepicker({
    format: "dd/mm/yyyy",
    startDate: '0d',
    language: 'en',
}).datepicker("setDate", new Date());
$("#created_at").datetimepicker({
    todayHighlight: !0,
    autoclose: !0,
    pickerPosition: "bottom-left",
    format: "dd/mm/yyyy hh:ii",
    startDate: new Date(),
});
$("#time_send").datetimepicker({
    todayHighlight: !0,
    autoclose: !0,
    pickerPosition: "bottom-left",
    format: "dd/mm/yyyy hh:ii",
    startDate: new Date(),
});
$('[name="zns_template_id"]').select2();

var AddCampaign = {
    showListCustomer: function (type, filter = 0) {
        var params = "";
        var val_lead_journey_code = $('#show-list-customer form #lead_journey_code').val();
        if ($('#show-list-customer form').length) {
            params = $('#show-list-customer form').serialize() + '&type=' + type + '&filter=' + filter;
        } else {
            params = 'type=' + type;
        }
        $.ajax({
            url: laroute.route('zns.campaign.show-list-customer'),
            data: params,
            method: "POST",
            dataType: "JSON",
            success: function (data) {
                $('#show-list-customer').html(data.html);
                if (type == 'add-group-potential') {
                    $('#show-list-customer #lead_type_customer').select2();
                    $('#show-list-customer #lead_customer_source').select2();
                    $('#show-list-customer #lead_sale_status').select2();
                    $('#show-list-customer #lead_pipeline_code').select2();
                    if ($('#lead_pipeline_code').val()) {
                        $.ajax({
                            url: laroute.route('customer-lead.load-option-journey'),
                            dataType: 'JSON',
                            data: {
                                pipeline_code: $('#lead_pipeline_code').val(),
                            },
                            method: 'POST',
                            success: function (res) {
                                $('#lead_journey_code').empty();
                                $('#lead_journey_code').append('<option value=""></option>');
                                $.map(res.optionJourney, function (a) {
                                    let selected = '';
                                    if (val_lead_journey_code == a.journey_code) {
                                        selected = ' selected';
                                    }
                                    $('#lead_journey_code').append('<option value="' + a.journey_code + '"' + selected + '>' + a.journey_name + '</option>');
                                });
                            }
                        });
                    }
                    $('#lead_journey_code').select2({
                        placeholder: 'Chọn hành trình',
                    });
                }
                if (type == 'add-group-define') {
                    $('#filter_type_group').select2();
                    $('#customer_group_filter').select2();
                    console.log($('#filter_type_group').val())
                    if ($('#filter_type_group').val()) {
                        $.ajax({
                            url: laroute.route('admin.sms.search-customer-group-filter'),
                            method: "POST",
                            data: {
                                filter_type_group: $('#filter_type_group').val(),
                            },
                            success: function (data) {
                                var stringHtml = '';
                                if (data.length == 0) {
                                    stringHtml += `<option value="">Chọn nhóm khách hàng</option>`;
                                } else {
                                    data.forEach(e => {
                                        stringHtml += `<option value="${e.id}">${e.name}</option>`;
                                    });
                                }
                                $('#customer_group_filter').html('');
                                $('#customer_group_filter').append(stringHtml);
                            }
                        });
                    }
                }
                if (type == 'add-customer') {
                    $('#birthday').datepicker({
                        format: 'dd/mm/yyyy',
                        language: 'vi',
                    });
                    $('#branch').select2();
                    $('#gender').select2();
                }
                if (data.is_filter == 0) {
                    $('#show-list-customer').modal('show');
                }
            }
        });
    },
    chooseCustomer: function () {
        $('#show-list-customer .check_lead:checked').each(function (index, value) {
            let customer_id = $(this).closest('tr').find('.customer_id_class').val();
            let type_customer = $(this).closest('tr').find('[name^="type_customer"]').val();
            if ($('#list-customer-get-notification-table .customer_id_' + type_customer + customer_id).length == 0) {
                let id = parseInt($('#list-customer-get-notification-table tr').length);
                let td_id = generate_input('hidden', 'customer_id[' + type_customer + '][]', 'customer_id_' + type_customer + customer_id, customer_id);
                let customer_name = $(this).closest('tr').find('td').eq(1).text();
                let customer_phone = $(this).closest('tr').find('td').eq(2).text();
                td_id += generate_input('hidden', 'name_customer[' + type_customer + '][' + customer_id + ']', '', customer_name);
                td_id += generate_input('hidden', 'phone_customer[' + type_customer + '][' + customer_id + ']', '', customer_phone);
                td_id += generate_input('hidden', 'type_customer[' + type_customer + '][' + customer_id + ']', '', type_customer);
                var preview = $('#eye-link-preview').html();
                preview = preview.replace(/{link}/g, $('#content_zns').attr('src'));
                tr = [id, customer_name + td_id, customer_phone, preview, $('#button-delete-customer').html()];
                $('#list-customer-get-notification-table tbody').append(generate_row(tr));
            }
        });
        $('#show-list-customer').modal('hide');
    },
    confirmPopup: function () {
        var data = $('#form-add').serializeArray();
        var params = {
            // oa_name: $('[name=oa] option:selected').text(),
            oa_name: "",
            type: "ZNS Template API",
            time_send: $('[name=time_send]').val(),
            name_campaign: $('[name=name]').val(),
            number_get: $('.table-list-customer tr').length,
            number_send: $('.table-list-customer tr').length,
        };
        $.each(params, function (key, val) {
            data.push({name: key, value: val});
        });
        $.ajax({
            url: laroute.route('zns.campaign.confirm-popup'),
            data: data,
            method: "POST",
            dataType: "JSON",
            success: function (data) {
                $('#confirm').html(data.html);
                $('#confirm').modal('show');
            }
        }).fail(function (error) {
            $('.error').remove();
            $.map(error.responseJSON.errors, function (mess, index) {
                $('[name=' + index + ']').parent().append('<div class="mt-3 error">' + mess[0] + '</div>');
            });
        });
    },
    save: function () {
        $.ajax({
            url: laroute.route('zns.campaign.edit-action'),
            method: "POST",
            data: $('#form-add').serialize(),
            success: function (res) {
                if (res.status == 1) {
                    swal(
                        'Chỉnh sửa dịch thành công',
                        '',
                        'success'
                    );
                    location.href = laroute.route('zns.campaign');
                } else {
                    swal(
                        'Chỉnh sửa chiến dịch thất bại',
                        '',
                        'warning'
                    );
                }
            }
        }).fail(function (error) {
            $('.error').remove();
            $.map(error.responseJSON.errors, function (mess, index) {
                $('[name=' + index + ']').parent().append('<div class="mt-3 error">' + mess[0] + '</div>');

            });
        });
    }
};

function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}

$('#check-all').click(function () {
    if ($('#check-all').is(":checked")) {
        $('.check').prop("checked", true);
    } else {
        $('.check').prop("checked", false);

    }
});

function chunkArray(myArray, chunk_size) {
    var index = 0;
    var arrayLength = myArray.length;
    var tempArray = [];

    for (index = 0; index < arrayLength; index += chunk_size) {
        myChunk = myArray.slice(index, index + chunk_size);
        // Do something if you want with the group
        tempArray.push(myChunk);
    }

    return tempArray;
}

$('#gender').select2();
$('#branch').select2();
$('#branchOption').select2();
$('#birthday').datepicker({
    format: 'dd/mm/yyyy',
    language: 'vi',
});

$('[name="check_type"]').click(function () {
    if ($('#is_now').is(':checked')) {
        $('#day-send').prop('disabled', true);
        $('#time-send').prop('disabled', true);
    } else {
        $('#day-send').prop('disabled', false);
        $('#time-send').prop('disabled', false);
    }
});
$(document).on('change', '.check_all_lead', function () {
    if ($(this).is(":checked")) {
        $('#show-list-customer table input[name="check-lead"]').prop("checked", true);
    } else {
        $('#show-list-customer table input[name="check-lead"]').prop("checked", false);
    }
});
$(document).on('change', '#lead_pipeline_code', function () {
    $.ajax({
        url: laroute.route('customer-lead.load-option-journey'),
        dataType: 'JSON',
        data: {
            pipeline_code: $('#lead_pipeline_code').val(),
        },
        method: 'POST',
        success: function (res) {
            $('#lead_journey_code').empty();
            $('#lead_journey_code').append('<option value="">Chọn hành trình</option>');
            $.map(res.optionJourney, function (a) {
                $('#lead_journey_code').append('<option value="' + a.journey_code + '">' + a.journey_name + '</option>');
            });
        }
    });
});
$(document).on('change', '#show-list-customer [name="filter_type_group"]', function () {
    $.ajax({
        url: laroute.route('admin.sms.search-customer-group-filter'),
        method: "POST",
        data: {
            filter_type_group: $(this).val(),
        },
        success: function (data) {
            var stringHtml = '';
            if (data.length == 0) {
                stringHtml += `<option value="">Chọn nhóm khách hàng</option>`;
            } else {
                data.forEach(e => {
                    stringHtml += `<option value="${e.id}">${e.name}</option>`;
                });
            }
            $('#customer_group_filter').html('');
            $('#customer_group_filter').append(stringHtml);
        }
    })
});

function generate_row(arr) {
    var tr_append = '<tr>';
    $.each(arr, function (index, val) {
        tr_append += '<td>';
        tr_append += val;
        tr_append += '</td>';
    });
    tr_append += '</tr>';
    return tr_append;
};

function generate_input($type = 'hiddden', $name = "", $class = "", $value = "") {
    let input = '<input type="' + $type + '" name="' + $name + '" class="' + $class + '"value="' + $value + '">';
    return input;
}

$(document).on('click', '#list-customer-get-notification-table .remove-customer', function () {
    $(this).closest('tr').remove();
    $('#list-customer-get-notification-table tbody tr').each(function (index, value) {
        $(this).find('td').eq(0).text(index + 1);
    });
});

$('[name="zns_template_id"]').change(function () {
    let val = $(this).val();
    if (!val) {
        return;
    }
    $.ajax({
        url: laroute.route('zns.template.get-template'),
        method: "POST",
        data: {
            zns_template_id: val,
            type: 'item'
        },
        success: function (res) {
            $('#content_zns').attr('src', res.detail.preview);
            $('#template_id').val(res.detail.template_id);
            $('#template_price').val(res.detail.price);
            $('#list-customer-get-notification-table tr').find('td').eq(3).find('a').attr('href', res.detail.preview);
            $('.param_list').html(res.html_params);
            $(".date-time").datepicker({
                todayHighlight: !0,
                autoclose: !0,
                pickerPosition: "bottom-left",
                format: "dd/mm/yyyy",
            });
        }
    });
});
$(".date-time").datepicker({
    todayHighlight: !0,
    autoclose: !0,
    pickerPosition: "bottom-left",
    format: "dd/mm/yyyy",
});

$('.m-radio [name="check_type"]').change(function () {
    if ($(this).val() == 1) {
        $('#time_send').val('');
        $('#time_send').prop('disabled', true);
    } else {
        $('#time_send').prop('disabled', false);
    }
});