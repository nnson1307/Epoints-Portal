var x = "";
var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
for (let z = 0; z < 10; z++) {
    x += possible.charAt(Math.floor(Math.random() * possible.length));
}
var d = new Date()
var code = x + d.getFullYear() + d.getMonth() + d.getHours() + d.getMinutes();

var parent_class;
var fileName = 'fileName';

$(document).ready(function() {
    Refund.init();
});

var Refund = {
    init: function() {
        /* init seting */
        $("#checkAll").click(function() {
            $('[name^="ticket_id["]').not(this).prop('checked', this.checked);
            $('[name^="ticket_id["]').each(function() {
                let ticket_id = $(this).val();
                if ($(this).is(":checked") && ($('#ticket_refund_' + ticket_id).length == 0)) {
                    loadTicketRefundetail(ticket_id, true);
                }
                if (!$(this).is(":checked")) {
                    $('#ticket_refund_' + ticket_id).remove();
                }
            });
            changeTicketRefund();
            countValue();
        });
        $('[name^="ticket_id["]').change(function() {
            console.log(1)
            let ticket_id = $(this).val();
            if ($(this).is(":checked")) {
                loadTicketRefundetail(ticket_id, true);
            } else {
                $('#ticket_refund_' + ticket_id).remove();
                countValue();
            }
            changeTicketRefund();
        })


        $('.m_selectpicker').selectpicker();
        $('select[name="is_actived"]').select2();
        var arrRange = {};
        arrRange[lang['Hôm nay']] = [moment(), moment()],
            arrRange[lang['Hôm qua']] = [moment().subtract(1, "days"), moment().subtract(1, "days")],
            arrRange[lang["7 ngày trước"]] = [moment().subtract(6, "days"), moment()],
            arrRange[lang["30 ngày trước"]] = [moment().subtract(29, "days"), moment()],
            arrRange[lang["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")],
            arrRange[lang["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
        $(".daterange-picker").daterangepicker({
            autoUpdateInput: false,
            autoApply: true,
            buttonClasses: "m-btn btn",
            applyClass: "btn-primary",
            cancelClass: "btn-danger",
            maxDate: moment().endOf("day"),
            startDate: moment().startOf("day"),
            endDate: moment().add(1, 'days'),
            locale: {
                format: 'DD/MM/YYYY',
                "applyLabel": lang["Đồng ý"],
                "cancelLabel": lang["Thoát"],
                "customRangeLabel": lang["Tùy chọn ngày"],
                daysOfWeek: [
                    lang["CN"],
                    lang["T2"],
                    lang["T3"],
                    lang["T4"],
                    lang["T5"],
                    lang["T6"],
                    lang["T7"]
                ],
                "monthNames": [
                    lang["Tháng 1 năm"],
                    lang["Tháng 2 năm"],
                    lang["Tháng 3 năm"],
                    lang["Tháng 4 năm"],
                    lang["Tháng 5 năm"],
                    lang["Tháng 6 năm"],
                    lang["Tháng 7 năm"],
                    lang["Tháng 8 năm"],
                    lang["Tháng 9 năm"],
                    lang["Tháng 10 năm"],
                    lang["Tháng 11 năm"],
                    lang["Tháng 12 năm"]
                ],
                "firstDay": 1
            },
            ranges: arrRange
        }).on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'))
        });
        /* end init seting */
    },
    dropzoneFile: function() {
        Dropzone.options.dropzoneFile = {
            paramName: 'file',
            maxFilesize: 10, // MB
            maxFiles: 10,
            acceptedFiles: ".pdf,.doc,.docx,.pdf,.csv,.xls,.xlsx,image/jpeg,image/jpg,image/png,image/gif",
            addRemoveLinks: true,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
            },
            renameFile: function(file) {
                var dt = new Date();
                var time = dt.getTime().toString() + dt.getDate().toString() + (dt.getMonth() + 1).toString() + dt.getFullYear().toString();
                var random = "";
                var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                for (let z = 0; z < 10; z++) {
                    random += possible.charAt(Math.floor(Math.random() * possible.length));
                }
                // return time + "_" + random + "." + file.name.substr((file.name.lastIndexOf('.') + 1));
                return file.name;
            },
            init: function() {
                this.on("sending", function(file, xhr, data) {
                    data.append("link", "_ticket.");
                });

                this.on("success", function(file, response) {
                    var a = document.createElement('span');
                    a.className = "thumb-url btn btn-primary";
                    a.setAttribute('data-clipboard-text', laroute.route('ticket.refund.upload-file') + response);
                    if (response.error == false) {
                        $("#up-file-temp").append("<input type='hidden' class='" + file.upload.filename + "'  name='" + fileName + "' value='" + response.file + "'>");
                        $("#up-file-temp").append("<input type='hidden' class='" + file.upload.filename + "'  name='file_name_custom' value='" + response.file_name_custom + "'>");
                    }
                });

                this.on('removedfile', function(file, response) {
                    var name = file.upload.filename;
                    $.ajax({
                        url: laroute.route('admin.service.delete-image'),
                        method: "POST",
                        data: {
                            filename: name
                        },
                        success: function() {
                            $("input[class='" + name + "']").each(function() {
                                var $this = $(this);
                                if ($(this).attr('class') === name) {
                                    $this.remove();
                                }
                            });

                        }
                    });
                });
            }
        };
    },
    modalFile: function(current) {
        $('#up-file-temp').empty();
        $('#dropzoneFile')[0].dropzone.files.forEach(function(file) {
            file.previewElement.remove();
        });
        $('#dropzoneFile').removeClass('dz-started');

        $('#modal-file-ticket').modal({
            backdrop: 'static',
            keyboard: false
        });
    },
    submitFileticket: function() {
        var checkFile = $('#up-file-temp').find('input[name="fileName"]');
        $.each(checkFile, function() {
            let value_custom = $(this).attr('class');
            let tpl = $('#tpl-file').html();
            tpl = tpl.replace(/{fileName}/g, $(this).val());
            tpl = tpl.replace(/{fileNameCustom}/g, (value_custom));
            // tpl = tpl.replace(/{ticket_id}/g, $('#' + parent_class).attr('data-id'));
            // $('#' + parent_class).find('.div_file_ticket').append(tpl);
            tpl = tpl.replace(/{ticket_id}/g, parent_class.attr('data-id') + '[refund]');
            parent_class.append(tpl);
        });
        var checkFilefileNameAcceptance = $('#up-file-temp').find('input[name="fileNameAcceptance"]');
        $.each(checkFilefileNameAcceptance, function() {
            let value_custom = $(this).attr('class');
            let tpl = $('#tpl-file').html();
            tpl = tpl.replace(/{fileName}/g, $(this).val());
            tpl = tpl.replace(/{fileNameCustom}/g, (value_custom));
            // tpl = tpl.replace(/{ticket_id}/g, $('#' + parent_class).attr('data-id'));
            // $('#' + parent_class).find('.div_file_ticket').append(tpl);
            tpl = tpl.replace(/{ticket_id}/g, parent_class.attr('data-id') + '[acceptance]');
            parent_class.append(tpl);
        });

        $('#modal-file-ticket').modal('hide');
    },
    removeFile: function(obj) {
        $(obj).closest('.div_file').remove();
        $(obj).closest('.div_file').find('[name=' + fileName + ']').remove();
    },
    save: function(id) {
        // LƯU VỚI TRẠNG THÁI LÀ BẢN NHÁP
        let route_in = laroute.route('ticket.refund.submit-edit', { id: id });
        let route_out = laroute.route('ticket.refund');
        let status = 'D';
        Refund.changeStatus(route_in, status, route_out);
    },
    create: function(id) {
        // LƯU VỚI TRẠNG THÁI LÀ CHỜ DUYỆT
        setTimeout(function() {
            let route_in = laroute.route('ticket.refund.submit-edit', { id: id });
            let route_out = laroute.route('ticket.refund.detail-view', { id: id });
            let status = 'W&check_null_item=1';
            Refund.changeStatus(route_in, status, route_out, route_in);
        }, 2000);


    },
    reupload: function(id) {
        // LƯU VỚI TRẠNG THÁI LÀ CHỜ HỒ SƠ
        let route_in = laroute.route('ticket.refund.submit-edit', { id: id });
        let route_out = laroute.route('ticket.refund');
        let status = 'WF';
        Refund.changeStatus(route_in, status, route_out);
    },
    approve: function(id) {
        // LƯU VỚI TRẠNG THÁI LÀ DUYỆT
        let route_in = laroute.route('ticket.refund.submit-edit', { id: id });
        let route_out = laroute.route('ticket.refund');
        let status = 'A';
        Refund.changeStatus(route_in, status, route_out);
    },
    approve_success: function(id) {
        // LƯU VỚI TRẠNG THÁI LÀ HOÀN TẤT
        let route_in = laroute.route('ticket.refund.submit-edit', { id: id });
        let route_out = laroute.route('ticket.refund');
        let status = 'C';
        Refund.changeStatus(route_in, status, route_out);
    },
    cancle: function(id) {
        // LƯU VỚI TRẠNG THÁI LÀ HỦY
        let route_in = laroute.route('ticket.refund.submit-edit', { id: id });
        let route_out = laroute.route('ticket.refund');
        let status = 'R';
        Refund.changeStatus(route_in, status, route_out);
    },
    update_approve_item: function(id, type = false) {
        // type = true là số lượng, false là tiền
        let form = $('#approve-popup-item');
        if (type == true) {
            let total_amount = $('#approve-popup-item #total_amount').val();
            let number_compare = $('#refund_id_' + id).attr('data-value');
            let mess_type = lang["Số lượng duyệt phải bé hơn hoặc bằng số lượng hoàn ứng"];
            // mess_type = "Số tiền duyệt phải bé hơn hoặc bằng thành tiền";
            if (number_compare < total_amount) {
                swal(mess_type, '', "error");
                return false;
            }
        }
        $.ajax({
            url: laroute.route('ticket.refund.update-approve-item'),
            data: form.serialize(),
            method: "POST",
            dataType: "JSON",
            success: function(res) {
                if (res.error == 0) {
                    $('#approve-popup-item').modal('hide');
                    location.reload();
                }
            }
        });
    },
    show_approve_item: function(id, check = false) {
        if (!id) {
            return;
        }
        $.ajax({
            url: laroute.route('ticket.refund.show-approve-item'),
            data: {
                id: id,
                check: check
            },
            method: "POST",
            dataType: "JSON",
            success: function(res) {
                if (res.error == 0) {
                    $('#approve-popup-item').html(res.html);
                    new AutoNumeric.multiple('#total_amount', {
                        currencySymbol: '',
                        decimalCharacter: '.',
                        digitGroupSeparator: ',',
                        decimalPlaces: decimal_number,
                        eventIsCancelable: true,
                        minimumValue: 0,
                    });
                    $('#approve-popup-item').modal('show');

                }
            }
        });
    },
    changeStatus: function(route_in, status, route_out) {
        let form = $('#form-refund');
        $.ajax({
            url: route_in,
            data: form.serialize() + '&status=' + status,
            method: "POST",
            dataType: "JSON",
            success: function(res) {
                if (res.error == 0) {
                    swal(res.message, "", "success").then(function(result) {
                        window.location.href = route_out;
                    });
                } else {
                    swal(res.message, '', "error");
                }
            }
        });
    }
};
$(document).on('click', '.modalFile-click', function() {
    // parent_class = $(this).closest('[id^="ticket_refund_"]').attr('id');
    parent_class = $(this).closest('.col-lg-6').find('.div_file_ticket');
    fileName = 'fileName';
    Refund.modalFile();
});
$(document).on('click', '.modalFile-acceptance-click', function() {
    // parent_class = $(this).closest('[id^="ticket_refund_"]').attr('id');
    parent_class = $(this).closest('.col-lg-6').find('.div_file_ticket');
    fileName = 'fileNameAcceptance';
    Refund.modalFile();
});
/* function */
/* thay đổi chọn ticket cần hoàn ứng */
function changeTicketRefund() {
    let count = $('[name^="ticket_id["]:checked').length;
    $('.count-ticket-choose').text(count);
}

function countValue() {
    let total_quantity = 0;
    $('#ticket_refund_list .total_quantity').each(function() {
        total_quantity += parseInt($(this).attr('data-value'));
    });
    let total_money = 0;
    $('#ticket_refund_list .total_money').each(function() {
        total_money += parseInt($(this).attr('data-value'));
    });
    total_money = total_money.toLocaleString('it-IT', { style: 'currency', currency: 'VND' });
    $('.total_quantity_all').text(total_quantity);
    $('.total_money_all').text(total_money);
}

function loadTicketRefundetail(ticket_id, check_edit = false) {
    $.ajax({
        url: laroute.route('ticket.refund.load-ticket-refund-detail', { id: ticket_id }),
        data: { check_edit: check_edit },
        method: "POST",
        dataType: "JSON",
        success: function(res) {
            if (res.error == 0) {
                $('#ticket_refund_list').append(res.html)
                countValue();
            }
        }
    });
};