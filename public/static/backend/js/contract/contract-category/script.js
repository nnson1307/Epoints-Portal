var sttGeneral = 1;
var sttPartner = 1;
var sttPayment = 1;
var sttRemind = 1;
var sttStatus = 1;
$(document).ready(function () {
    $('.select').select2();
    $(".date-picker").datepicker({
        todayHighlight: !0,
        autoclose: !0,
        format: "dd/mm/yyyy"
    });
    $.getJSON(laroute.route('translate'), function (json) {
        var arrRange = {};
        arrRange[json["Hôm nay"]] = [moment(), moment()];
        arrRange[json["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
        arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
        arrRange[json["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
        arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
        arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
        $("#created_at").daterangepicker({
            autoUpdateInput: false,
            autoApply: true,
            locale: {
                cancelLabel: 'Clear',
                format: 'DD/MM/YYYY',
                // "applyLabel": "Đồng ý",
                // "cancelLabel": "Thoát",
                "customRangeLabel": json['Tùy chọn ngày'],
                daysOfWeek: [
                    json["CN"],
                    json["T2"],
                    json["T3"],
                    json["T4"],
                    json["T5"],
                    json["T6"],
                    json["T7"]
                ],
                "monthNames": [
                    json["Tháng 1 năm"],
                    json["Tháng 2 năm"],
                    json["Tháng 3 năm"],
                    json["Tháng 4 năm"],
                    json["Tháng 5 năm"],
                    json["Tháng 6 năm"],
                    json["Tháng 7 năm"],
                    json["Tháng 8 năm"],
                    json["Tháng 9 năm"],
                    json["Tháng 10 năm"],
                    json["Tháng 11 năm"],
                    json["Tháng 12 năm"]
                ],
                "firstDay": 1
            },
            ranges: arrRange
        });
    });
    sttGeneral = parseInt($('#sttGeneral').val()) ? parseInt($('#sttGeneral').val()) + 1 : 1;
    sttPartner = parseInt($('#sttPartner').val()) ? parseInt($('#sttPartner').val()) + 1 : 1;
    sttPayment = parseInt($('#sttPayment').val()) ? parseInt($('#sttPayment').val()) + 1 : 1;
    sttRemind = parseInt($('#sttRemind').val()) ? parseInt($('#sttRemind').val()) + 1 : 1;
    contractCategories.refreshStatus();
});
var defaultContentNew = 'Hợp đồng {contract_code} đã được tạo mới thành công.';
var defaultContent = 'Hợp đồng {contract_code} đã được chuyển sang trạng thái {status_code}.';
var contractCategories = {

//     var element = document.querySelector(".status_name");
// element.scrollIntoView();
    disabledUnitValue: function (e) {
        if ($(e).val() == '=') {
            $('#pop_unit_value').attr('disabled', true);
        } else {
            $('#pop_unit_value').prop('disabled', false);
        }
    },
    submitChangeStatus: function (e, id, is_actived) {
        $.getJSON(laroute.route('translate'), function (json) {
            swal({
                title: json['Thông báo'],
                text: json["Bạn có muốn thay đổi trạng thái?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json['Xóa'],
                cancelButtonText: json['Hủy']
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: laroute.route('contract.contract-category.submit-change-status'),
                        data: {
                            is_actived: is_actived,
                            contract_category_id: id
                        },
                        method: 'POST',
                        dataType: "JSON",
                        success: function (res) {
                            swal(json['Thay đổi trạng thái thành công'], "", "success");
                            window.location.reload();
                        }
                    });
                } else {
                    window.location.reload();
                }
            });
        });
    },
    remove: function (obj, id) {
        // hightlight row
        $(obj).closest('tr').addClass('m-table__row--danger');
        $.getJSON(laroute.route('translate'), function (json) {
            swal({
                title: json['Thông báo'],
                text: json["Bạn có muốn xóa không?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json['Xóa'],
                cancelButtonText: json['Hủy'],
                onClose: function () {
                    // remove hightlight row
                    $(obj).closest('tr').removeClass('m-table__row--danger');
                }
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: laroute.route("contract.contract-category.delete"),
                        method: "POST",
                        data: {
                            contract_category_id: id
                        },
                        success: function (result) {
                            swal(
                                json['Xóa thành công'],
                                '',
                                'success'
                            ).then(function () {
                                $('#autotable').PioTable('refresh');
                            });
                        }
                    })
                }
            });
        });
    },
    uploadFileCc: function (input) {
        $.getJSON(laroute.route('translate'), function (json) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.readAsDataURL(input.files[0]);
                var file_data = $('#upload_file_cc').prop('files')[0];
                if (file_data.size > 41943040) {
                    swal(json['Tối đa 5MB'], "", "error");
                }
                var actFile = [".pdf", ".doc", ".docx", ".pdf", ".csv", ".xls", ".xlsx"];
                var ext = file_data.name.substring(file_data.name.lastIndexOf("."), file_data.name.length);
                if (jQuery.inArray(ext, actFile) == -1) {
                    swal(json['Vui lòng chọn file đúng định dạng'], "", "error");
                } else {
                    var form_data = new FormData();
                    form_data.append('file', file_data);
                    form_data.append('link', '_contract_category.');
                    $.ajax({
                        url: laroute.route("admin.upload-image"),
                        method: "POST",
                        data: form_data,
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function (res) {
                            if (res.error == 0) {
                                $('#contract_category_list_files').append(`
                                <div class="col-lg-12">
                                    <a href="${res.file}" value="${res.file}" name="contract_category_list_files[]" class="ss--text-black" download="${file_data.name}">${file_data.name}</a>
                                    <a href="javascript:void(0)" onclick="contractCategories.removeFileCc(this)"><i class="la la-trash"></i></a>
                                    <br>
                                </div>
                            `);
                            }

                        }
                    });
                }

            }
        });
    },
    removeFileCc: function (e) {
        $(e).parent('div').remove();
    },
    changeTab: function (e, tabName, type = 'add') {
        $.getJSON(laroute.route('translate'), function (json) {
            $('.tab_contract_category').attr('hidden', true);
            switch (tabName) {
                case 'general':
                    $('#div-general').removeAttr('hidden');
                    break;
                case 'partner':
                    $('#div-partner').removeAttr('hidden');
                    break;
                case 'status':
                    $('#div-status').removeAttr('hidden');
                    break;
                case 'payment':
                    $('#div-payment').removeAttr('hidden');
                    break;
                case 'remind':
                    if ($('#check_save_general_tab').val() == "0") {
                        swal(json['Vui lòng lưu tab thông tin hợp đồng'], "", "error");
                        $(e).parent('li').parent('ul').find('a.active').trigger('click');
                    } else {
                        $(e).attr("data-toggle", "tab");
                        $(e).closest('ul').find('.active').removeClass('active');
                        $(e).addClass("active");
                        $('#div-remind').removeAttr('hidden');
                    }
                    break;
                case 'notify':
                    if ($('#check_save_status_tab').val() == "0") {
                        swal(json['Vui lòng lưu tab trạng thái hợp đồng'], "", "error");
                        $(e).parent('li').parent('ul').find('a.active').trigger('click');
                    } else {
                        $(e).attr("data-toggle", "tab");
                        $(e).closest('ul').find('.active').removeClass('active');
                        $(e).addClass("active");
                        $('#div-notify').removeAttr('hidden');
                        contractCategories.loadStatusNotify();
                    }
                    break;
            }
        });
    },
    loadStatusNotify: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            $.ajax({
                url: laroute.route('contract.contract-category.load-status-notify'),
                data: {
                    contract_category_id: $('#contract_category_id').val(),
                },
                method: 'POST',
                dataType: "JSON",
                success: function (res) {
                    // append table
                    $('.table_tab_notify').html('');
                    res.forEach(e => {
                        var content = '';
                        if (e.default_system == 'draft') {
                            content = json[defaultContentNew];
                        } else {
                            content = json[defaultContent];
                        }
                        var tpl = $('#append-notify').html();
                        tpl = tpl.replace(/{status_code}/g, e.status_code);
                        tpl = tpl.replace(/{status_name}/g, e.status_name);
                        tpl = tpl.replace(/{content}/g, content);
                        $('.table_tab_notify').append(tpl);
                    });
                }
            });
        });
    },
    changeContentNotify: function (e, status_code) {
        $.getJSON(laroute.route('translate'), function (json) {
            $.ajax({
                url: laroute.route('contract.contract-category.modal-change-content-notify'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    'status_code': status_code,
                    'content': $(e).closest('tr').find('td.content>input').val()
                },
                success: function (res) {
                    $('#frm_change_content_notify').html(res.html);
                    $('#change_content_notify').modal('show');
                    $('#pop_parameter_for_content').select2({
                        placeholder: json['Chọn nội dụng mẫu']
                    });
                }
            });
        });
    },
    saveChangeContentNotify: function (status_code) {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#frm_change_content_notify');
            form.validate({
                rules: {
                    pop_content: {
                        required: true,
                    },
                },
                messages: {
                    pop_content: {
                        required: json['Hãy nhập nội dung'],
                    },
                }
            });
            if (!form.valid()) {
                return false;
            }
            var newContent = $('#frm_change_content_notify').find('#pop_content').val();
            $('.table_tab_notify>tr.tab-notify').find(`.${status_code}`).val(newContent);
            $('#change_content_notify').modal('hide');
        });
    },
    tabGeneralPrepend: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            $('.table_tab_general').append(`
                <tr class="tab-general-custom tab-general-custom_${sttGeneral}">
                    <td class="key-general" hidden>custom_${sttGeneral}</td>
                    <td class="key-name-general" style="width: 250px !important;">
                        <input aria-describedby="basic-addon1"
                           class="format-money form-control m-input btn-sm">
                    </td>
                    <td class="type-general">
                        <select class="form-control select" style="width: 100%">
                            <option value="int" selected>int</option>
                            <option value="float">float</option>
                            <option value="text">text</option>
                            <option value="date">date</option>
                            <option value="text area">text area</option>
                        </select>
                    </td>   
                    <td class="is-show-general">
                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                        <label style="margin: 0 0 0 10px">
                            <input type="checkbox"class="manager-btn">
                            <span></span>
                        </label>
                        </span>
                    </td>
                    <td class="is-validate-general">
                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                        <label style="margin: 0 0 0 10px">
                            <input type="checkbox" class="manager-btn">
                            <span></span>
                        </label>
                        </span>
                    </td>
                    <td class="general_custom_${sttGeneral}">
                        <button type="button" onclick="contractCategories.saveCustom(${sttGeneral},'custom_${sttGeneral}', 'general');"
                                class="btn btn-success color_button son-mb btn-sm  m-btn m-btn--icon">
                                <span>
                                    <i class="la la-check"></i>
                                    <span>${json['LƯU TRƯỜNG']}</span>
                                </span>
                        </button>
                    </td>
                    <td hidden>
                        <input type="text" hidden class="general_save_custom general_save_custom_${sttGeneral}" value="0">
                    </td>
                </tr>
            `);
            var element = document.querySelector(".is-validate-general");
            element.scrollIntoView();
            $('.select').select2();
            sttGeneral++;
        });
    },
    tabPartnerPrepend: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            $('.table_tab_partner').append(`
                <tr class="tab-partner-custom tab-partner-custom_${sttPartner}">
                    <td class="key-partner" hidden>custom_${sttPartner}</td>
                    <td class="key-name-partner" style="width: 250px !important;">
                        <input aria-describedby="basic-addon1"
                           class="format-money form-control m-input btn-sm">
                    </td>
                    <td class="type-partner">
                        <select class="form-control select" style="width: 100%">
                            <option value="int" selected>int</option>
                            <option value="float">float</option>
                            <option value="text">text</option>
                            <option value="date">date</option>
                            <option value="text area">text area</option>
                        </select>
                    </td>   
                    <td class="is-show-partner">
                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                        <label style="margin: 0 0 0 10px">
                            <input type="checkbox"class="manager-btn">
                            <span></span>
                        </label>
                        </span>
                    </td>
                    <td class="is-validate-partner">
                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                        <label style="margin: 0 0 0 10px">
                            <input type="checkbox" class="manager-btn">
                            <span></span>
                        </label>
                        </span>
                    </td>
                    <td class="partner_custom_${sttPartner}">
                        <button type="button" onclick="contractCategories.saveCustom(${sttPartner},'custom_${sttPartner}', 'partner');"
                                class="btn btn-success color_button son-mb btn-sm  m-btn m-btn--icon">
                                <span>
                                    <i class="la la-check"></i>
                                    <span>${json['LƯU TRƯỜNG']}</span>
                                </span>
                        </button>
                    </td>
                    <td hidden>
                        <input type="text" hidden class="partner_save_custom partner_save_custom_${sttPartner}" value="0">
                    </td>
                </tr>
            `);
            var element = document.querySelector(".key-name-partner");
            element.scrollIntoView();
            $('.select').select2();
            sttPartner++;
        });
    },
    tabPaymentPrepend: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            $('.table_tab_payment').append(`
                <tr class="tab-payment-custom tab-payment-custom_${sttPayment}">
                    <td class="key-payment" hidden>custom_${sttPayment}</td>
                    <td class="key-name-payment" style="width: 250px !important;">
                        <input aria-describedby="basic-addon1"
                           class="format-money form-control m-input btn-sm">
                    </td>
                    <td class="type-payment">
                        <select class="form-control select" style="width: 100%">
                            <option value="int" selected>int</option>
                            <option value="float">float</option>
                            <option value="text">text</option>
                            <option value="date">date</option>
                            <option value="text area">text area</option>
                        </select>
                    </td>   
                    <td class="is-show-payment">
                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                        <label style="margin: 0 0 0 10px">
                            <input type="checkbox"class="manager-btn">
                            <span></span>
                        </label>
                        </span>
                    </td>
                    <td class="is-validate-payment">
                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                        <label style="margin: 0 0 0 10px">
                            <input type="checkbox" class="manager-btn">
                            <span></span>
                        </label>
                        </span>
                    </td>
                    <td class="payment_custom_${sttPayment}">
                        <button type="button" onclick="contractCategories.saveCustom(${sttPayment},'custom_${sttPayment}', 'payment');"
                                class="btn btn-success color_button son-mb btn-sm  m-btn m-btn--icon">
                                <span>
                                    <i class="la la-check"></i>
                                    <span>${json['LƯU TRƯỜNG']}</span>
                                </span>
                        </button>
                    </td>
                    <td hidden>
                        <input type="text" hidden class="payment_save_custom payment_save_custom_${sttPayment}" value="0">
                    </td>
                </tr>
            `);
            var element = document.querySelector(".key-name-payment");
            element.scrollIntoView();
            $('.select').select2();
            sttPayment++;
        });
    },
    saveCustom: function (stt, key, tab, isEdit = 0) {
        $.getJSON(laroute.route('translate'), function (json) {
            var fieldName = $(`.tab-${tab}-custom_${stt}`).find(`.key-name-${tab} > input`).val();
            var checkUnique = true;
            $(`.table_tab_${tab}`).find(`.key-name-${tab}-default`).each(function (k, v) {
                if ($(v).text() == fieldName) {
                    checkUnique = false;
                }
            });
            let count = 0;
            $(`.table_tab_${tab}`).find(`.key-name-${tab} > input`).each(function (k, v) {
                if ($(v).val() == fieldName) {
                    count++;
                }
            });

            if (count > 1) {
                checkUnique = false;
            }
            //check text default
            if (fieldName == '') {
                swal("Vui lòng nhập tên trường", "", "error");
            } else if (fieldName.length > 191) {
                swal("Tên trường không quá 191 kí tự", "", "error");
            } else if (!checkUnique) {
                swal("Tên trường không được trùng", "", "error");
            } else {
                contractCategories.disabledTab(stt, tab);
                $(`.tab-${tab}-custom_${stt}`).find(`.is-validate-${tab} > span > label > input`).attr('disabled', true);

                if (isEdit == 1) {
                    $(`.${tab}_custom_${stt}`).html(`
                        <a href="javascript:void(0)" onclick="contractCategories.enabledTab(${stt}, '${tab}');"
                           title="${json['Cập nhật']}" style="color: #a1a1a1">
                            <i class="la la-edit"></i>
                        </a>                 
                    `);
                } else {
                    $(`.${tab}_custom_${stt}`).html(`
                        <a href="javascript:void(0)" onclick="contractCategories.enabledTab(${stt}, '${tab}');"
                           title="${json['Cập nhật']}" style="color: #a1a1a1">
                            <i class="la la-edit"></i>
                        </a>                 
                        <a href="javascript:void(0)" onclick="$(this).parent('td').parent('tr').remove();"
                           title="${json['Hủy']}" style="color: #a1a1a1"><i class="la la-trash"></i>
                        </a>
                    `);
                }
            }
        });
    },
    disabledTab: function (stt, tab) {
        $(`.tab-${tab}-custom_${stt}`).find(`.key-name-${tab} > input`).attr('disabled', true);
        $(`.tab-${tab}-custom_${stt}`).find(`.type-${tab} > select`).attr('disabled', true);
        $(`.tab-${tab}-custom_${stt}`).find(`.is-show-${tab} > span > label > input`).attr('disabled', true);
        $(`.tab-${tab}-custom_${stt}`).find(`.is-validate-${tab} > span > label > input`).attr('disabled', true);
        $(`.${tab}_save_custom_${stt}`).val('1');
    },
    enabledTab: function (stt, tab, isEdit = 0) {
        $.getJSON(laroute.route('translate'), function (json) {
            $(`.tab-${tab}-custom_${stt}`).find(`.key-name-${tab} > input`).removeAttr('disabled');

            if (isEdit == 0) {
                $(`.tab-${tab}-custom_${stt}`).find(`.type-${tab} > select`).removeAttr('disabled');
            }

            $(`.tab-${tab}-custom_${stt}`).find(`.is-show-${tab} > span > label > input`).removeAttr('disabled');
            $(`.tab-${tab}-custom_${stt}`).find(`.is-validate-${tab} > span > label > input`).removeAttr('disabled');
            $(`.${tab}_custom_${stt}`).html(`
               <button type="button" onclick="contractCategories.saveCustom(${stt},'custom_${stt}', '${tab}', '${isEdit}');"
                        class="btn btn-success color_button son-mb btn-sm  m-btn m-btn--icon">
                        <span>
                            <i class="la la-check"></i>
                            <span>${json['LƯU TRƯỜNG']}</span>
                        </span>
                </button>
            `);
            $(`.${tab}_save_custom_${stt}`).val('0');
        });
    },
    submitAdd: function (e) {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-create-cc');
            form.validate({
                rules: {
                    // contract_category_code: {
                    //     required: true,
                    //     maxlength: 191
                    // },
                    contract_category_name: {
                        required: true,
                        maxlength: 191
                    },
                    contract_code_format: {
                        required: true,
                        maxlength: 191
                    }
                },
                messages: {
                    // contract_category_code: {
                    //     required: json['Hãy nhập mã loại hợp đồng'],
                    //     maxlength: json['Tối đa 191 kí tự']
                    // },
                    contract_category_name: {
                        required: json['Hãy nhập tên loại hợp đông'],
                        maxlength: json['Tối đa 191 kí tự']
                    },
                    contract_code_format: {
                        required: json['Hãy nhập cấu hình mã hợp đồng'],
                        maxlength: json['Tối đa 191 kí tự']
                    }
                }
            });

            if (!form.valid()) {
                return false;
            }
            var is_actived = 0;
            if ($('#is_actived').is(":checked")) {
                is_actived = 1
            }
            var contract_category_list_files = [];
            var contract_category_list_name_files = [];
            var nFile = $('[name="contract_category_list_files[]"]').length;
            if (nFile > 0) {
                for (let i = 0; i < nFile; i++) {
                    contract_category_list_files.push($('[name="contract_category_list_files[]"]')[i].href);
                    contract_category_list_name_files.push($('[name="contract_category_list_files[]"]')[i].text);
                }
            }
            $.ajax({
                url: laroute.route('contract.contract-category.submit-add'),
                data: {
                    is_actived: is_actived,
                    // contract_category_code: $('#contract_category_code').val(),
                    contract_category_name: $('#contract_category_name').val(),
                    contract_code_format: $('#contract_code_format').val(),
                    type: $('#type_contract option:selected').val(),
                    contract_category_list_files: contract_category_list_files,
                    contract_category_list_name_files: contract_category_list_name_files,
                },
                method: 'POST',
                dataType: "JSON",
                success: function (res) {
                    if (!res.error) {
                        swal(res.message, "", "success");
                        $('#contract_category_id').val(res.contract_category_id);
                        $(e).remove();

                    } else {
                        swal(res.message, "", "error");
                    }
                },
                error: function (res) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal(json['Thêm mới thất bại'], mess_error, "error");
                }
            });
        });
    },
    submitEdit: function (e) {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-create-cc');
            form.validate({
                rules: {
                    // contract_category_code: {
                    //     required: true,
                    //     maxlength: 191
                    // },
                    contract_category_name: {
                        required: true,
                        maxlength: 191
                    },
                    contract_code_format: {
                        required: true,
                        maxlength: 191
                    }
                },
                messages: {
                    // contract_category_code: {
                    //     required: json['Hãy nhập mã loại hợp đồng'],
                    //     maxlength: json['Tối đa 191 kí tự']
                    // },
                    contract_category_name: {
                        required: json['Hãy nhập tên loại hợp đông'],
                        maxlength: json['Tối đa 191 kí tự']
                    },
                    contract_code_format: {
                        required: json['Hãy nhập cấu hình mã hợp đồng'],
                        maxlength: json['Tối đa 191 kí tự']
                    }
                }
            });

            if (!form.valid()) {
                return false;
            }
            var is_actived = 0;
            if ($('#is_actived').is(":checked")) {
                is_actived = 1
            }
            var contract_category_list_files = [];
            var contract_category_list_name_files = [];
            var nFile = $('[name="contract_category_list_files[]"]').length;
            if (nFile > 0) {
                for (let i = 0; i < nFile; i++) {
                    contract_category_list_files.push($('[name="contract_category_list_files[]"]')[i].href);
                    contract_category_list_name_files.push($('[name="contract_category_list_files[]"]')[i].text);
                }
            }
            $.ajax({
                url: laroute.route('contract.contract-category.submit-edit'),
                data: {
                    is_actived: is_actived,
                    contract_category_id: $('#contract_category_id').val(),
                    // contract_category_code: $('#contract_category_code').val(),
                    contract_category_name: $('#contract_category_name').val(),
                    contract_code_format: $('#contract_code_format').val(),
                    type: $('#type_contract option:selected').val(),
                    contract_category_list_files: contract_category_list_files,
                    contract_category_list_name_files: contract_category_list_name_files,
                },
                method: 'POST',
                dataType: "JSON",
                success: function (res) {
                    if (!res.error) {
                        swal(res.message, "", "success");
                    } else {
                        swal(res.message, "", "error");
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
    submitAddGeneralTab: function (type = 'add') {
        $.getJSON(laroute.route('translate'), function (json) {
            let nElement = $('.general_save_custom').length;
            for (var i = 0; i < nElement; i++) {
                if ($($('.general_save_custom')[i]).val() == 0) {
                    swal(json['Vui lòng lưu trường mới'], "", "error");
                    return;
                }
            }
            if (nElement > 19) { // tối đa 20 custom
                swal(json['Thêm mới tối đa 20 trường'], "", "error");
                return;
            }
            if ($('#contract_category_id').val() == "" || $('#contract_category_id').val() == "0") {
                swal(json['Vui lòng lưu thông tin chung của loại hợp đồng trước'], "", "error");
                return;
            }
            let numField = $('.table_tab_general>tr.tab-general-custom').length;
            var arrGeneralCustom = [];
            for (let i = 0; i < numField; i++) {
                let element = $('.table_tab_general>tr.tab-general-custom')[i];
                let key = $(element).find('.key-general').text();
                let name = $(element).find('.key-name-general>input').val();
                let type = $(element).find('.type-general>select option:selected').val();
                let is_show = 0;
                if ($(element).find('.is-show-general>span>label>input').is(":checked")) {
                    is_show = 1;
                }
                let is_validate = 0;
                if ($(element).find('.is-validate-general>span>label>input').is(":checked")) {
                    is_validate = 1;
                }
                var elementGeneralCustom = {
                    key: key,
                    name: name,
                    type: type,
                    is_show: is_show,
                    is_validate: is_validate
                };
                arrGeneralCustom.push(elementGeneralCustom);
            }
            $.ajax({
                url: laroute.route('contract.contract-category.submit-add-config-tab'),
                data: {
                    contract_category_id: $('#contract_category_id').val(),
                    arrCustom: arrGeneralCustom,
                    tab: 'general',
                    type: type
                },
                method: 'POST',
                dataType: "JSON",
                success: function (res) {
                    if (!res.error) {
                        swal(res.message, "", "success");
                        $('#check_save_general_tab').val('1');
                    } else {
                        swal(res.message, "", "error");
                    }
                }
            });
        });
    },
    submitAddPartnerTab: function (type = 'add') {
        $.getJSON(laroute.route('translate'), function (json) {
            let nElement = $('.partner_save_custom').length;
            for (var i = 0; i < nElement; i++) {
                if ($($('.partner_save_custom')[i]).val() == 0) {
                    swal(json['Vui lòng lưu trường mới'], "", "error");
                    return;
                }
            }
            if (nElement > 19) { // tối đa 20 custom
                swal(json['Thêm mới tối đa 20 trường'], "", "error");
                return;
            }
            if ($('#contract_category_id').val() == "" || $('#contract_category_id').val() == "0") {
                swal(json['Vui lòng lưu thông tin chung của loại hợp đồng trước'], "", "error");
                return;
            }
            let numField = $('.table_tab_partner>tr.tab-partner-custom').length;
            var arrPartnerCustom = [];
            for (let i = 0; i < numField; i++) {
                let element = $('.table_tab_partner>tr.tab-partner-custom')[i];
                let key = $(element).find('.key-partner').text();
                let name = $(element).find('.key-name-partner>input').val();
                let type = $(element).find('.type-partner>select option:selected').val();
                let is_show = 0;
                if ($(element).find('.is-show-partner>span>label>input').is(":checked")) {
                    is_show = 1;
                }
                let is_validate = 0;
                if ($(element).find('.is-validate-partner>span>label>input').is(":checked")) {
                    is_validate = 1;
                }
                var elementPartnerCustom = {
                    key: key,
                    name: name,
                    type: type,
                    is_show: is_show,
                    is_validate: is_validate
                };
                arrPartnerCustom.push(elementPartnerCustom);
            }

            $.ajax({
                url: laroute.route('contract.contract-category.submit-add-config-tab'),
                data: {
                    contract_category_id: $('#contract_category_id').val(),
                    arrCustom: arrPartnerCustom,
                    tab: 'partner',
                    type: type
                },
                method: 'POST',
                dataType: "JSON",
                success: function (res) {
                    if (!res.error) {
                        swal(res.message, "", "success");
                    } else {
                        swal(res.message, "", "error");
                    }
                }
            });
        });
    },
    submitAddPaymentTab: function (type = 'add') {
        $.getJSON(laroute.route('translate'), function (json) {
            let nElement = $('.payment_save_custom').length;
            for (var i = 0; i < nElement; i++) {
                if ($($('.payment_save_custom')[i]).val() == 0) {
                    swal(json['Vui lòng lưu trường mới'], "", "error");
                    return;
                }
            }
            if (nElement > 19) { // tối đa 20 custom
                swal(json['Thêm mới tối đa 20 trường'], "", "error");
                return;
            }
            if ($('#contract_category_id').val() == "" || $('#contract_category_id').val() == "0") {
                swal(json['Vui lòng lưu thông tin chung của loại hợp đồng trước'], "", "error");
                return;
            }
            let numField = $('.table_tab_payment>tr.tab-payment-custom').length;
            var arrPaymentCustom = [];
            for (let i = 0; i < numField; i++) {
                let element = $('.table_tab_payment>tr.tab-payment-custom')[i];
                let key = $(element).find('.key-payment').text();
                let name = $(element).find('.key-name-payment>input').val();
                let type = $(element).find('.type-payment>select option:selected').val();
                let is_show = 0;
                if ($(element).find('.is-show-payment>span>label>input').is(":checked")) {
                    is_show = 1;
                }
                let is_validate = 0;
                if ($(element).find('.is-validate-payment>span>label>input').is(":checked")) {
                    is_validate = 1;
                }
                var elementPaymentCustom = {
                    key: key,
                    name: name,
                    type: type,
                    is_show: is_show,
                    is_validate: is_validate
                };
                arrPaymentCustom.push(elementPaymentCustom);
            }

            $.ajax({
                url: laroute.route('contract.contract-category.submit-add-config-tab'),
                data: {
                    contract_category_id: $('#contract_category_id').val(),
                    arrCustom: arrPaymentCustom,
                    tab: 'payment',
                    type: type
                },
                method: 'POST',
                dataType: "JSON",
                success: function (res) {
                    if (!res.error) {
                        swal(res.message, "", "success");
                    } else {
                        swal(res.message, "", "error");
                    }
                }
            });
        });
    },
    submitAddStatusTab: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            let nElement = $('.save_change_action').length;
            for (var i = 0; i < nElement; i++) {
                if ($($('.save_change_action')[i]).val() == 0) {
                    swal(json['Vui lòng lưu trạng thái'], "", "error");
                    return;
                }
            }
            if ($('#contract_category_id').val() == "" || $('#contract_category_id').val() == "0") {
                swal(json['Vui lòng lưu thông tin chung của loại hợp đồng trước'], "", "error");
                return;
            }
            let numField = $('.table_tab_status>tr.tab-status').length;
            var arrStatusCustom = [];
            for (let i = 0; i < numField; i++) {
                let element = $('.table_tab_status>tr.tab-status')[i];
                let default_system = $(element).find('.default_system').text();
                let status_name = $(element).find('.status_name>input').val();
                let status_name_update = $(element).find('.status_name_update>select').val();
                let is_approve = 0;
                if ($(element).find('.is_approve>span>label>input').is(":checked")) {
                    is_approve = 1;
                }
                let approve_by = $(element).find('.approve_by>select').val();
                let is_edit_contract = 0;
                if ($(element).find('.is_edit_contract>span>label>input').is(":checked")) {
                    is_edit_contract = 1;
                }
                let is_deleted_contract = 0;
                if ($(element).find('.is_deleted_contract>span>label>input').is(":checked")) {
                    is_deleted_contract = 1;
                }
                let is_reason = 0;
                if ($(element).find('.is_reason>span>label>input').is(":checked")) {
                    is_reason = 1;
                }
                let is_show = 0;
                if ($(element).find('.is_show>span>label>input').is(":checked")) {
                    is_show = 1;
                }
                var elementStatusCustom = {
                    default_system: default_system,
                    status_name: status_name,
                    status_name_update: status_name_update,
                    is_approve: is_approve,
                    approve_by: approve_by,
                    is_edit_contract: is_edit_contract,
                    is_deleted_contract: is_deleted_contract,
                    is_reason: is_reason,
                    is_show: is_show,
                };
                arrStatusCustom.push(elementStatusCustom);
            }

            $.ajax({
                url: laroute.route('contract.contract-category.submit-add-status-tab'),
                data: {
                    contract_category_id: $('#contract_category_id').val(),
                    arrCustom: arrStatusCustom,
                },
                method: 'POST',
                dataType: "JSON",
                success: function (res) {
                    if (!res.error) {
                        swal(res.message, "", "success");
                        $('#check_save_status_tab').val('1')
                    } else {
                        swal(res.message, "", "error");
                    }
                }
            });
        });
    },
    submitEditStatusTab: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            let nElement = $('.save_change_action').length;
            for (var i = 0; i < nElement; i++) {
                if ($($('.save_change_action')[i]).val() == 0) {
                    swal(json['Vui lòng lưu trạng thái'], "", "error");
                    return;
                }
            }
            if ($('#contract_category_id').val() == "" || $('#contract_category_id').val() == "0") {
                swal(json['Vui lòng lưu thông tin chung của loại hợp đồng trước'], "", "error");
                return;
            }
            let numField = $('.table_tab_status>tr.tab-status').length;
            var arrStatusCustom = [];
            for (let i = 0; i < numField; i++) {
                let element = $('.table_tab_status>tr.tab-status')[i];
                let default_system = $(element).find('.default_system').text();
                let status_code = $(element).find('.status_code').text();
                let status_name = $(element).find('.status_name>input').val();
                let status_name_update = $(element).find('.status_name_update>select').val();
                let is_approve = 0;
                if ($(element).find('.is_approve>span>label>input').is(":checked")) {
                    is_approve = 1;
                }
                let approve_by = $(element).find('.approve_by>select').val();
                let is_edit_contract = 0;
                if ($(element).find('.is_edit_contract>span>label>input').is(":checked")) {
                    is_edit_contract = 1;
                }
                let is_deleted_contract = 0;
                if ($(element).find('.is_deleted_contract>span>label>input').is(":checked")) {
                    is_deleted_contract = 1;
                }
                let is_reason = 0;
                if ($(element).find('.is_reason>span>label>input').is(":checked")) {
                    is_reason = 1;
                }
                let is_show = 0;
                if ($(element).find('.is_show>span>label>input').is(":checked")) {
                    is_show = 1;
                }
                var elementStatusCustom = {
                    default_system: default_system,
                    status_code: status_code,
                    status_name: status_name,
                    status_name_update: status_name_update,
                    is_approve: is_approve,
                    approve_by: approve_by,
                    is_edit_contract: is_edit_contract,
                    is_deleted_contract: is_deleted_contract,
                    is_reason: is_reason,
                    is_show: is_show,
                };
                arrStatusCustom.push(elementStatusCustom);
            }

            $.ajax({
                url: laroute.route('contract.contract-category.submit-edit-status-tab'),
                data: {
                    contract_category_id: $('#contract_category_id').val(),
                    arrCustom: arrStatusCustom,
                },
                method: 'POST',
                dataType: "JSON",
                success: function (res) {
                    if (!res.error) {
                        swal(res.message, "", "success");
                        $('#check_save_status_tab').val('1')
                    } else {
                        swal(res.message, "", "error");
                    }
                }
            });
        });
    },
    submitAddNotifyTab: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            // let nElement = $('.save_change_action').length;
            // for (var i = 0; i < nElement; i++) {
            //     if ($($('.save_change_action')[i]).val() == 0) {
            //         swal(json['Vui lòng lưu trạng thái'], "", "error");
            //         return;
            //     }
            // }
            if ($('#contract_category_id').val() == "" || $('#contract_category_id').val() == "0") {
                swal(json['Vui lòng lưu thông tin chung của loại hợp đồng trước'], "", "error");
                return;
            }
            let numField = $('.table_tab_notify>tr.tab-notify').length;
            var arrNotifyCustom = [];
            for (let i = 0; i < numField; i++) {
                let element = $('.table_tab_notify>tr.tab-notify')[i];
                let status_code = $(element).find('.status_code').text();
                let content = $(element).find('.content>input').val();
                let is_created_by = 0;
                if ($(element).find('.is_created_by').is(":checked")) {
                    is_created_by = 1;
                }
                let is_performer_by = 0;
                if ($(element).find('.is_performer_by').is(":checked")) {
                    is_performer_by = 1;
                }
                let is_signer_by = 0;
                if ($(element).find('.is_signer_by').is(":checked")) {
                    is_signer_by = 1;
                }
                let is_follow_by = 0;
                if ($(element).find('.is_follow_by').is(":checked")) {
                    is_follow_by = 1;
                }
                var elementNotifyCustom = {
                    status_code: status_code,
                    content: content,
                    is_created_by: is_created_by,
                    is_performer_by: is_performer_by,
                    is_signer_by: is_signer_by,
                    is_follow_by: is_follow_by,
                };
                arrNotifyCustom.push(elementNotifyCustom);
            }

            $.ajax({
                url: laroute.route('contract.contract-category.submit-add-notify-tab'),
                data: {
                    contract_category_id: $('#contract_category_id').val(),
                    arrCustom: arrNotifyCustom,
                },
                method: 'POST',
                dataType: "JSON",
                success: function (res) {
                    if (!res.error) {
                        swal(res.message, "", "success");
                    } else {
                        swal(res.message, "", "error");
                    }
                }
            });
        });
    },
    refreshStatus: function () {
        var nElement = $('.table_tab_status').find(".tab-status").length;
        var arrStatusName = [];
        var arrStatusNameUpdate = [];
        $.each($('.table_tab_status').find(".tab-status"), function () {
            let check_status_name = $(this).find('.status_name>input').val();
            let check_status_name_update = $(this).find('.status_name_update>select').val();
            arrStatusName.push(check_status_name);
            arrStatusNameUpdate.push(check_status_name_update);
        });
        $('.table_tab_status')
            .find(".status_name_update>select").html('');
        $.map(arrStatusName, function (value, key) {
            if (value != '') {
                for (let i = 0; i < nElement; i++) {
                    if ($($('.table_tab_status').find(".status_name>input")[i]).val() != value) {
                        if (jQuery.inArray(value, arrStatusNameUpdate[i]) != -1) {
                            $($('.table_tab_status')
                                .find(".status_name_update>select")[i])
                                .append('<option value="' + value + '" selected>' + value + '</option>');
                        } else {
                            $($('.table_tab_status')
                                .find(".status_name_update>select")[i])
                                .append('<option value="' + value + '">' + value + '</option>');
                        }
                    }
                }
            }
        });
        $('.select').select2();
    },
    addStatus: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            let nElement = $('.save_change_action').length;
            for (var i = 0; i < nElement; i++) {
                if ($($('.save_change_action')[i]).val() == 0) {
                    swal(json['Vui lòng lưu trạng thái'], "", "error");
                    return;
                }
            }
            var tpl = $('#append-status').html();
            tpl = tpl.replace(/{number_status}/g, sttStatus++);
            $('.table_tab_status').append(tpl);
            contractCategories.refreshStatus();
        });

    },
    enabledApproveBy: function (e) {
        if ($(e).is(":checked")) {
            $(e).parent('label').parent('span').parent('td').parent('tr').find('.approve_by>select').removeAttr('disabled')
        } else {
            $(e).parent('label').parent('span').parent('td').parent('tr').find('.approve_by>select').val('');
            $(e).parent('label').parent('span').parent('td').parent('tr').find('.approve_by>select').select2();
            $(e).parent('label').parent('span').parent('td').parent('tr').find('.approve_by>select').attr('disabled', true)
        }
    },
    editStatus: function (e) {
        var element = $(e).parent('td').parent('tr');
        element.find('.status_name>input').removeAttr('disabled');
        element.find('.status_name_update>select').removeAttr('disabled');
        element.find('.is_approve>span>label>input').removeAttr('disabled');
        if (element.find('.is_approve>span>label>input').is(":checked")) {
            element.find('.approve_by>select').removeAttr('disabled');
        }
        element.find('.is_edit_contract>span>label>input').removeAttr('disabled');
        element.find('.is_deleted_contract>span>label>input').removeAttr('disabled');
        element.find('.is_reason>span>label>input').removeAttr('disabled');
        element.find('.is_show>span>label>input').removeAttr('disabled');
        element.find('.save_change_action').val('0');
        var default_status = $(e).attr('data-default');
        $(e).parent('td').html(`
            <a href="javascript:void(0)" data-default="${default_status}" onclick="contractCategories.saveStatus(this)" class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill save_journey">
                <i class="la la-check"></i>
            </a>
        `)
    },
    saveStatus: function (e) {
        $.getJSON(laroute.route('translate'), function (json) {
            var flag = true;
            var element = $(e).parent('td').parent('tr');
            var newStatusName = element.find('.status_name>input').val();
            if (newStatusName == '') {
                swal(json['Vui lòng nhập tên trạng thái'], "", "error");
                flag = false;
            }
            if (element.find('.is_approve>span>label>input').is(':checked')) {
                if (element.find('.approve_by>select').val().length == 0) {
                    swal(json['Vui lòng chọn người duyệt'], "", "error");
                    flag = false;
                }
                ;
            }
            var lstElement = $('.table_tab_status').children('tr.tab-status');
            var length = lstElement.length;
            for (var i = 0; i < length; i++) {
                if ($(lstElement[i]).find('.unique_status').text() != element.find('.unique_status').text()) {
                    if ($(lstElement[i]).find('.status_name>input').val() == newStatusName) {
                        swal(json['Tên trạng thái đã tồn tại'], "", "error");
                        flag = false;
                    }
                }
            }
            if (!flag) {
                return;
            }
            element.find('.status_name>input').attr('disabled', true);
            element.find('.status_name_update>select').attr('disabled', true);
            element.find('.is_approve>span>label>input').attr('disabled', true);
            element.find('.approve_by>select').attr('disabled', true);
            element.find('.is_edit_contract>span>label>input').attr('disabled', true);
            element.find('.is_deleted_contract>span>label>input').attr('disabled', true);
            element.find('.is_reason>span>label>input').attr('disabled', true);
            element.find('.is_show>span>label>input').attr('disabled', true);
            element.find('.save_change_action').val('1');
            var default_status = $(e).attr('data-default');
            if (default_status == 1) {
                $(e).parent('td').html(`
                <a href="javascript:void(0)"  data-default="${default_status}"  onclick="contractCategories.editStatus(this);"
                   title="{{__('Cập nhật')}}" style="color: #a1a1a1">
                    <i class="la la-edit"></i>
                </a>
            `)
            } else {
                $(e).parent('td').html(`
                <a href="javascript:void(0)"  data-default="${default_status}"  onclick="contractCategories.editStatus(this);"
                   title="{{__('Cập nhật')}}" style="color: #a1a1a1">
                    <i class="la la-edit"></i>
                </a>
                <a href="javascript:void(0)" onclick="contractCategories.removeStatus(this);"
                   title="{{__('Cập nhật')}}" style="color: #a1a1a1">
                    <i class="la la-trash"></i>
                </a>
            `)
            }
            contractCategories.refreshStatus();
        });
    },
    removeStatus: function (e) {
        $(e).parent('td').parent('tr').remove();
        contractCategories.refreshStatus();
    },
    addRemind: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            if ($('#contract_category_id').val() == "" || $('#contract_category_id').val() == "0") {
                swal(json['Vui lòng lưu thông tin chung của loại hợp đồng trước'], "", "error");
                return;
            }
            $.ajax({
                url: laroute.route('contract.contract-category.modal-add-remind-tab'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    'contract_category_id': $('#contract_category_id').val()
                },
                success: function (res) {
                    $('#frm_create_remind').html(res.html);
                    $('#create_remind').modal('show');

                    $('#pop_remind_type').select2({
                        placeholder: json['Chọn loại nhắc nhở']
                    });
                    $('#pop_recipe').select2();
                    $('#pop_unit').select2();
                    $('#pop_compare_unit').select2();
                    $('#pop_remind_method').select2();
                    $('#pop_receiver_by').select2({
                        placeholder: json['Chọn người nhận']
                    });
                    $('#pop_parameter_for_content').select2({
                        placeholder: json['Chọn nội dụng mẫu']
                    });
                }
            });
        });
    },
    submitAddRemind: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#frm_create_remind');
            form.validate({
                rules: {
                    pop_remind_type: {
                        required: true,
                    },
                    pop_title: {
                        required: true,
                        maxlength: 191
                    },
                    pop_content: {
                        required: true,
                    },
                    pop_receiver_by: {
                        required: true,
                    },
                    pop_remind_method: {
                        required: true,
                    },
                },
                messages: {
                    pop_remind_type: {
                        required: json['Hãy chọn loại nhắc nhở']
                    },
                    pop_title: {
                        required: json['Hãy nhập tiêu đề'],
                        maxlength: json['Tối đa 191 kí tự']
                    },
                    pop_content: {
                        required: json['Hãy nhập nội dung'],
                    },
                    pop_receiver_by: {
                        required: json['Hãy chọn người nhận'],
                    },
                    pop_remind_method: {
                        required: json['Hãy chọn phương thức nhắc nhở'],
                    },
                }
            });

            if (!form.valid()) {
                return false;
            }
            if ($('#pop_unit_value').val() == '' && $('#pop_recipe').val() != '=') {
                $('#pop_unit_value-error').removeAttr('hidden');
                $('#pop_unit_value-error').css('display', 'block');
                $('#pop_unit_value-error').text(json['Hãy nhập giá trị']);
                return;
            } else {
                $('#pop_unit_value-error').attr('hidden', true);
            }
            var is_actived = 0;
            if ($('#pop_is_actived').is(":checked")) {
                is_actived = 1
            }
            var remind_type = $('#pop_remind_type option:selected').val();
            var remind_type_text = $('#pop_remind_type option:selected').text();
            var title = $('#pop_title').val();
            var content = $('#pop_content').val();
            var recipe = $('#pop_recipe option:selected').val();
            var unit_value = $('#pop_unit_value').val();
            var unit = $('#pop_unit option:selected').val();
            var unit_text = $('#pop_unit option:selected').text();
            var compare_unit = $('#pop_compare_unit option:selected').val();
            var compare_unit_text = $('#pop_compare_unit option:selected').text();
            var receiver_by = $('#pop_receiver_by').val();
            var remind_method = $('#pop_remind_method').val();


            $.ajax({
                url: laroute.route('contract.contract-category.submit-add-remind-tab'),
                data: {
                    is_actived: is_actived,
                    contract_category_id: $('#contract_category_id').val(),
                    remind_type: remind_type,
                    title: title,
                    content: content,
                    recipe: recipe,
                    unit_value: unit_value,
                    unit: unit,
                    compare_unit: compare_unit,
                    receiver_by: receiver_by,
                    remind_method: remind_method,
                },
                method: 'POST',
                dataType: "JSON",
                success: function (res) {
                    if (!res.error) {
                        swal(res.message, "", "success");
                        // append table
                        var tpl = $('#append-remind').html();
                        tpl = tpl.replace(/{remind_id}/g, res.remind_id);
                        tpl = tpl.replace(/{number_remind}/g, sttRemind);
                        tpl = tpl.replace(/{remind_type}/g, remind_type_text);
                        tpl = tpl.replace(/{title}/g, title);
                        tpl = tpl.replace(/{content}/g, content);
                        var recipeText = recipe == '<' ? "Trước" : "";
                        var stringTimeSend = recipeText + ' ' + unit_value + ' ' + unit_text + ' ' + compare_unit_text;
                        tpl = tpl.replace(/{time_send}/g, stringTimeSend);
                        tpl = tpl.replace(/{receiver_by}/g, $('#pop_receiver_by option:selected').toArray().map(item => item.text).join());
                        tpl = tpl.replace(/{remind_method}/g, $('#pop_remind_method option:selected').toArray().map(item => item.text).join());
                        tpl = tpl.replace(/{is_actived}/g, is_actived == 1 ? 'checked' : '');
                        sttRemind++;
                        $('.table_tab_remind').prepend(tpl);

                        $('#create_remind').modal('hide');

                    } else {
                        swal(res.message, "", "error");
                    }
                }
            });
        });
    },
    editRemind: function (e, id) {
        $.getJSON(laroute.route('translate'), function (json) {
            $.ajax({
                url: laroute.route('contract.contract-category.modal-edit-remind'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    'contract_category_id': $('#contract_category_id').val(),
                    'contract_category_remind_id': id
                },
                success: function (res) {
                    $('#frm_create_remind').html(res.html);
                    $('#edit_remind').modal('show');
                    $('#number_remind').val($(e).closest('tr').find('.number_remind').text());
                    $('#pop_remind_type').select2({
                        placeholder: json['Chọn loại nhắc nhở']
                    });
                    $('#pop_recipe').select2();
                    $('#pop_unit').select2();
                    $('#pop_compare_unit').select2();
                    $('#pop_remind_method').select2();
                    $('#pop_receiver_by').select2({
                        placeholder: json['Chọn người nhận']
                    });
                    $('#pop_parameter_for_content').select2({
                        placeholder: json['Chọn nội dụng mẫu']
                    });
                }
            });
        });
    },
    submitEditRemind: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#frm_create_remind');
            form.validate({
                rules: {
                    pop_remind_type: {
                        required: true,
                    },
                    pop_title: {
                        required: true,
                        maxlength: 191
                    },
                    pop_content: {
                        required: true,
                    },
                    pop_receiver_by: {
                        required: true,
                    },
                    pop_remind_method: {
                        required: true,
                    },
                },
                messages: {
                    pop_remind_type: {
                        required: json['Hãy chọn loại nhắc nhở']
                    },
                    pop_title: {
                        required: json['Hãy nhập tiêu đề'],
                        maxlength: json['Tối đa 191 kí tự']
                    },
                    pop_content: {
                        required: json['Hãy nhập nội dung'],
                    },
                    pop_receiver_by: {
                        required: json['Hãy chọn người nhận'],
                    },
                    pop_remind_method: {
                        required: json['Hãy chọn phương thức nhắc nhở'],
                    },
                }
            });

            if (!form.valid()) {
                return false;
            }
            if ($('#pop_unit_value').val() == '' && $('#pop_recipe').val() != '=') {
                $('#pop_unit_value-error').removeAttr('hidden');
                $('#pop_unit_value-error').css('display', 'block');
                $('#pop_unit_value-error').text(json['Hãy nhập giá trị']);
                return;
            } else {
                $('#pop_unit_value-error').attr('hidden', true);
            }
            var is_actived = 0;
            if ($('#pop_is_actived').is(":checked")) {
                is_actived = 1
            }
            var remind_type = $('#pop_remind_type option:selected').val();
            var remind_type_text = $('#pop_remind_type option:selected').text();
            var title = $('#pop_title').val();
            var content = $('#pop_content').val();
            var recipe = $('#pop_recipe option:selected').val();
            var unit_value = $('#pop_unit_value').val();
            var unit = $('#pop_unit option:selected').val();
            var unit_text = $('#pop_unit option:selected').text();
            var compare_unit = $('#pop_compare_unit option:selected').val();
            var compare_unit_text = $('#pop_compare_unit option:selected').text();
            var receiver_by = $('#pop_receiver_by').val();
            var remind_method = $('#pop_remind_method').val();


            $.ajax({
                url: laroute.route('contract.contract-category.submit-edit-remind'),
                data: {
                    contract_category_remind_id: $('#pop_contract_category_remind_id').val(),
                    is_actived: is_actived,
                    contract_category_id: $('#contract_category_id').val(),
                    remind_type: remind_type,
                    title: title,
                    content: content,
                    recipe: recipe,
                    unit_value: unit_value,
                    unit: unit,
                    compare_unit: compare_unit,
                    receiver_by: receiver_by,
                    remind_method: remind_method,
                },
                method: 'POST',
                dataType: "JSON",
                success: function (res) {
                    if (!res.error) {
                        swal(json['Chỉnh sửa nhắc nhở thành công'], "", "success");
                        // edit table
                        var recipeText = recipe == '<' ? "Trước" : "";
                        var stringTimeSend = recipeText + ' ' + unit_value + ' ' + unit_text + ' ' + compare_unit_text;

                        var numberRemind = parseInt($('#number_remind').val());
                        var element = $(`tr.remind_${numberRemind}`);
                        element.find('td.remind_id').text($('#pop_contract_category_remind_id').val());
                        element.find('td.number_remind').text($('#number_remind').val());
                        element.find('td.remind_type').text(remind_type_text);
                        element.find('td.title').text(title);
                        element.find('td.content').text(content);
                        element.find('td.time_send').text(stringTimeSend);
                        element.find('td.receiver_by').text($('#pop_receiver_by option:selected').toArray().map(item => item.text).join());
                        element.find('td.remind_method').text($('#pop_remind_method option:selected').toArray().map(item => item.text).join());
                        if (is_actived == 1) {
                            element.find('td.is_actived>span>label>input').attr("checked", true);
                        } else {
                            element.find('td.is_actived>span>label>input').removeAttr("checked");
                        }

                        $('#edit_remind').modal('hide');

                    } else {
                        swal(res.message, "", "error");
                    }
                }
            });
        });
    },
    appendContent: function () {
        var currentContent = $('#pop_content').val();
        var newSelected = $('#pop_parameter_for_content').val();
        newSelected.forEach(e => {
            if (currentContent.indexOf(`{${e}}`) === -1) {
                currentContent = currentContent + ' ' + '{' + e + '}';
            }
        });
        $('#pop_content').val(currentContent);
    },
    removeRemind: function (e, id) {
        $.getJSON(laroute.route('translate'), function (json) {
            swal({
                title: json["Thông báo"],
                text: json["Bạn có muốn xóa không?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json["Xóa"],
                cancelButtonText: json["Hủy"],
                onClose: function () {
                    // remove hightlight row
                    $(e).closest('tr').removeClass('m-table__row--danger');
                }
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: laroute.route('contract.contract-category.remove-remind'),
                        data: {
                            contract_category_remind_id: id,
                            contract_category_id: $('#contract_category_id').val(),
                        },
                        method: 'POST',
                        dataType: "JSON",
                        success: function (res) {
                            if (!res.error) {
                                swal(res.message, "", "success");
                                $(e).closest('tr').remove();

                            } else {
                                swal(res.message, "", "error");
                            }
                        }
                    });
                }
            });
        });
    }
};
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$('#autotable').PioTable({
    baseUrl: laroute.route('contract.contract-category.list')
});