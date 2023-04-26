$('select[name=created_by]').select2().on("select2:select", function (e) {
    SmsCampaign.filter();
});
$('select[name=sent_by]').select2().on("select2:select", function (e) {
    SmsCampaign.filter();
});
$('select[name=status]').select2().on("select2:select", function (e) {
    SmsCampaign.filter();
});
$('#type').selectpicker();
$('#branchName').selectpicker();
$('#optionss').selectpicker();
$('#day').selectpicker();
$('#hours').selectpicker();
$('#remindCalendarOption').selectpicker();

// $('#autotable').PioTable({
//     baseUrl: laroute.route('admin.sms.list')
// });

// //== Class definition
// var WizardDemo = function () {
//     //== Base elements
//     var wizardEl = $('#m_wizard');
//     var formEl = $('#m_form');
//     var validator;
//     var wizard;
//
//     //== Private functions
//     var initWizard = function () {
//         //== Initialize form wizard
//         wizard = wizardEl.mWizard({
//             startStep: 1
//         });
//
//         //== Validation before going to next page
//         wizard.on('beforeNext', function (wizard) {
//             if (validator.form() !== true) {
//                 return false;  // don't go to the next step
//             }
//         })
//
//         //== Change event
//         wizard.on('change', function (wizard) {
//             mApp.scrollTop();
//         });
//     }
//
//     var initValidation = function () {
//         validator = formEl.validate({
//             //== Validate only visible fields
//             ignore: ":hidden",
//             //
//             // //== Validation rules
//             rules: {
//                 //=== Client Information(step 1)
//                 //== Client details
//
//                 name: {
//                     required: true
//                 },
//                 type: {
//                     required: true
//                 },
//                 branchName: {
//                     required: true
//                 },
//                 remindCalendarValue: {
//                     required: true
//                 },
//
//
//                 //=== Client Information(step 2)
//                 //== Account Details
//                 content: {
//                     required: true,
//                     maxlength: 160
//                 },
//
//                 //== step 3
//                 optionss: {
//                     required: true,
//                 },
//                 time: {
//                     required: true,
//                 },
//             },
//
//             //== Validation messages
//             messages: {
//                 name: {
//                     required: "Vui lòng nhập tên chiến dịch."
//                 },
//                 type: {
//                     required: "Vui lòng chọn loại tin nhắn."
//                 },
//                 branchName: {
//                     required: "Vui lòng chọn loại đầu số."
//                 },
//                 content: {
//                     required: "Vui lòng nhập nội dung tin nhắn.",
//                     maxlength: "Tối đa 160 ký tự."
//                 },
//                 optionss: {
//                     required: "Vui lòng chọn hình thức gửi."
//                 },
//                 remindCalendarValue: {
//                     required: "Vui lòng nhập số ngày."
//                 },
//                 time: {
//                     required: "Vui lòng chọn ngày."
//                 }
//             },
//
//             //== Display error
//             invalidHandler: function (event, validator) {
//                 mApp.scrollTop();
//
//                 // swal({
//                 //     "title": "",
//                 //     "text": "There are some errors in your submission. Please correct them.",
//                 //     "type": "error",
//                 //     "confirmButtonClass": "btn btn-secondary m-btn m-btn--wide"
//                 // });
//             },
//
//             //== Submit valid form
//             submitHandler: function (form) {
//
//             }
//         });
//     }
//
//     var initSubmit = function () {
//         var btn = formEl.find('[data-wizard-action="submit"]');
//         btn.on('click', function (e) {
//             e.preventDefault();
//
//             if (validator.form()) {
//                 //== See: src\js\framework\base\app.js
//                 mApp.progress(btn);
//                 //mApp.block(formEl);
//
//                 //== See: http://malsup.com/jquery/form/#ajaxSubmit
//                 $.ajax({
//                     url: laroute.route('admin.sms.sms-campaign-add'),
//                     method: "POST",
//                     data: {
//                         name: $('#name').val(),
//                         contents: $('#content').val(),
//                         type: $('#type').val(),
//                         options: $('#optionss').val(),
//                         daySend: $('#time').val(),
//                         timeSend: $('#start-time').val(),
//                         remindCalendarValue: $('#remindCalendarValue').val(),
//                         brandname_id: $('#branchName').val(),
//                     },
//                     success: function (data) {
//                         if (data.error == 0) {
//                             if (data.type == 'customer_care') {
//                                 window.location = laroute.route('admin.sms.send-sms');
//                             } else if (data.type == 'other') {
//                                 window.location = laroute.route('admin.sms.sms-campaign');
//                             }
//
//                         }
//                     }
//                 })
//             }
//         });
//     }
//
//     return {
//         // public functions
//         init: function () {
//             wizardEl = $('#m_wizard');
//             formEl = $('#m_form');
//             initWizard();
//             initValidation();
//             initSubmit();
//         }
//     };
// }();
//
// jQuery(document).ready(function () {
//     WizardDemo.init();
// });
var character = '';
var SmsCampaign = {
    countCharacter: function (o) {
        let value = $(o).val().length;
        $('#amount-character').text(value);
    },
    valueParameter: function (o) {
        if (o == "customer-name") {
            SmsCampaign.insertAtCaret("{CUSTOMER_NAME}");
        }
        if (o == "customer-birthday") {
            SmsCampaign.insertAtCaret("{CUSTOMER_BIRTHDAY}");
        }
        if (o == "customer-gender") {
            SmsCampaign.insertAtCaret("{CUSTOMER_GENDER}");
        }
        if (o == "full-name") {
            SmsCampaign.insertAtCaret("{CUSTOMER_FULL_NAME}");
        }

        $('#amount-character').text($('#content').val().length);
    },
    remove: function (obj, id) {
        $.getJSON(laroute.route('translate'), function (json) {
        $(obj).closest('tr').addClass('m-table__row--danger');
            swal({
                title: json['Thông báo'],
                text: json["Bạn có muốn hủy chiến dịch?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json['Có'],
                cancelButtonText: json['Không'],
                onClose: function () {
                    $(obj).closest('tr').removeClass('m-table__row--danger');
                }
            }).then(function (result) {
                if (result.value) {
                    $.post(laroute.route('admin.sms.remove', {id: id}), function () {
                        swal(
                            json['Hủy thành công.'],
                            '',
                            'success'
                        );
                        SmsCampaign.filter();
                    });
                }
            });
        });
    },
    refresh: function () {
        $('input[name="search_keyword"]').val('');
        $('#day-sent').val('');
        $('#created_at').val('');
        $('select[name="created_by"]').val('').trigger('change');
        $('select[name="sent_by"]').val('').trigger('change');
        $('select[name="status"]').val('').trigger('change');
        SmsCampaign.filter();
    },
    filter: function () {
        $.ajax({
            url: laroute.route('admin.campaign.filter'),
            method: 'POST',
            data: {
                keyWord: $('input[name="search_keyword"]').val(),
                createdBy: $('select[name="created_by"]').val(),
                sentBy: $('select[name="sent_by"]').val(),
                status: $('select[name="status"]').val(),
                daySent: $('#day-sent').val(),
                createdAt: $('#created_at').val(),
            },
            success: function (data) {
                $('.list-campaign').empty();
                $('.list-campaign').append(data);
            }
        });
    },
    insertAtCaret: function (text) {
        var txtarea = document.getElementById('message-content');
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
    pageClick:function (page) {
        $.ajax({
            url: laroute.route('admin.campaign.detail-paging'),
            method: "POST",
            data: {
                page: page,
                id:$('#id').val()
            },
            success: function (data) {
                $('.list-log-detail-campaign').empty();
                $('.list-log-detail-campaign').append(data);
            }
        });
    }
};

$('#optionss').change(function () {
    let value = $(this).val();
    let type = $('#type').val();
    $('#setting-time-now').empty();
    if (type == 'remind_appointment') {
        let $_tpl = $('#amount-day').html();

        $('#setting-time-now').append($_tpl);
        $("#remindCalendarValue").on('keyup', function () {
                var n = parseInt($(this).val().replace(/,/g, ''));
                if (typeof n == 'number' && Number.isInteger(n)) {
                    // $(this).val(n.toLocaleString());
                    $(this).val().replace(/,/g, '');
                }
                else {
                    $(this).val("");
                }
            }
        );
    } else if (type == 'birthday') {
        $('#setting-time-now').append($('#choose-time').html());
        $("#start-time").timepicker({
            minuteStep: 15,
            defaultTime: "12:00:00",
            showMeridian: !1,
            snapToStep: !0,
        });
    } else if (type == 'customer_care') {
        $('#setting-time-now').append($('#choose-day-time').html());
        $("#start-time").timepicker({
            minuteStep: 15,
            defaultTime: "12:00:00",
            showMeridian: !1,
            snapToStep: !0,
        });
        $('#time').datepicker({
            format: "dd/mm/yyyy",
            startDate: '0d',
            language: 'vi',
        });
    }
    if (value == 'now' || value == '') {
        $('#setting-time-now').empty();
    }
})
;


$("#start-time").timepicker({
    minuteStep: 15,
    defaultTime: "12:00:00",
    showMeridian: !1,
    snapToStep: !0,
});

// $("#created_at").datepicker({
//     autoApply: true,
//     locale: {
//         daysOfWeek: [
//             "CN",
//             "T2",
//             "T3",
//             "T4",
//             "T5",
//             "T6",
//             "T7"
//         ],
//         "monthNames": [
//             "Tháng 1 năm",
//             "Tháng 2 năm",
//             "Tháng 3 năm",
//             "Tháng 4 năm",
//             "Tháng 5 năm",
//             "Tháng 6 năm",
//             "Tháng 7 năm",
//             "Tháng 8 năm",
//             "Tháng 9 năm",
//             "Tháng 10 năm",
//             "Tháng 11 năm",
//             "Tháng 12 năm"
//         ],
//         "firstDay": 1
//     }
// });
$("#created_at").datepicker({format: 'dd/mm/yyyy'});
$('#created_at').change(function () {

});
$('#type').change(function () {
    let value = $(this).val();
    if (value == 'remind_appointment') {

    }
});

//Trang đầu hoặc trang cuối của tất cả item.
function firstAndLastPage(o) {
    console.log(o);
    $.ajax({
        url: laroute.route('admin.campaign.paging'),
        method: "POST",
        data: {page: o},
        success: function (data) {
            $('.list-campaign').empty();
            $('.list-campaign').append(data);
        }
    });
}

function pageClick(page) {
    $.ajax({
        url: laroute.route('admin.campaign.paging'),
        method: "POST",
        data: {
            page: page
        },
        success: function (data) {
            $('.list-campaign').empty();
            $('.list-campaign').append(data);
        }
    })
}

$('#day-sent').datepicker({
    format: "dd/mm/yyyy",
    language: 'vi',
});
$('#day-sent').datepicker({
    format: "dd/mm/yyyy",
    language: 'vi',
});

$('input[name=search_keyword]').keyup(function (e) {
    if (e.keyCode == 13) {
        $(this).trigger("enterKey");
    }
});
$('input[name=search_keyword]').bind("enterKey", function (e) {
    SmsCampaign.filter();
});

function pageClickFilter(page) {
    $.ajax({
        url: laroute.route('admin.campaign.paging-filter'),
        method: "POST",
        data: {
            keyWord: $('input[name="search_keyword"]').val(),
            createdBy: $('select[name="created_by"]').val(),
            sentBy: $('select[name="sent_by"]').val(),
            status: $('select[name="status"]').val(),
            daySent: $('#day-sent').val(),
            createdAt: $('#created_at').val(),
            page: page
        },
        success: function (data) {
            $('.list-campaign').empty();
            $('.list-campaign').append(data);
        }
    })
}


// $('#autotable').PioTable({
//     baseUrl: laroute.route('admin.campaign.detail-list')
// });
//
// $('.clickPage').click(function () {
//     alert($("#id").val());
//     $.ajax({
//         url: laroute.url('admin.campaign.detail-list'),
//         method: "POST",
//         data: {id: $("#id").val()},
//     });
// })



