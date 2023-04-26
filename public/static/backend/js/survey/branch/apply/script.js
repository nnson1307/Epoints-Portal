$(document).ready(function () {
    $.getJSON(laroute.route('translate'), function (json) {
        var jsonLang = JSON.parse(localStorage.getItem('tranlate'));
        $.ajax({
            url: laroute.route('admin.customer.load-district'),
            dataType: 'JSON',
            data: {
                id_province: $('#province_id').val()
            },
            method: 'POST',
            success: function (res) {
                $.map(res.optionDistrict, function (a) {
                    $('.district').append('<option value="' + a.id + '">' + a.type + ' ' + a.name + '</option>');
                });
            }
        });

        $('#province_id').select2({
            placeholder: jsonLang["Chọn tỉnh/thành"],
        });

        $('#ward_id').select2({
            placeholder: jsonLang["Chọn phường/xã"],
        });

        $('#province_id').change(function () {
            $.ajax({
                url: laroute.route('admin.customer.load-district'),
                dataType: 'JSON',
                data: {
                    id_province: $('#province_id').val(),
                },
                method: 'POST',
                success: function (res) {
                    $('.district').empty();
                    $.map(res.optionDistrict, function (a) {
                        $('.district').append('<option value="' + a.id + '">' + a.type + ' ' + a.name + '</option>');
                    });
                }
            });
        });

        $('#district_id').select2({
            placeholder: jsonLang["Chọn quận/huyện"],
            ajax: {
                url: laroute.route('admin.customer.load-district'),
                data: function (params) {
                    return {
                        id_province: $('#province_id').val(),
                        search: params.term,
                        page: params.page || 1
                    };
                },
                dataType: 'JSON',
                method: 'POST',
                processResults: function (res) {
                    res.page = res.page || 1;
                    return {
                        results: res.optionDistrict.map(function (item) {
                            return {
                                id: item.id,
                                text: item.name
                            };
                        }),
                        pagination: {
                            more: res.pagination
                        }
                    };
                },
            }
        });

        $('#district_id').change(function () {
            var district = $("#district_id").val();
            $.ajax({
                url: laroute.route('admin.customer.load-ward'),
                dataType: 'JSON',
                data: {
                    id_district: district,
                },
                method: 'POST',
                success: function (res) {
                    $("#ward_id").empty();
                    $.map(res.optionWard, function (a) {
                        $("#ward_id").append('<option value="' + a.id + '">' + a.type + ' ' + a.name + '</option>');
                    });
                }
            });
        })
    });

});
var branch = {
    init: function () {
        $(document).ajaxStart(function () {
            $.ajaxSetup({
                async: false,
            });
            $('#fade').hide();

        });
        if (TYPE_SHOW_CUSTOMER_GROUP != '') {
            branch.renderModalCustomerAuto();
        }
        let typeApply = $("input[name='type_apply']:checked");
        if (typeApply.val() == 'customers') {
            branch.loadItemSelectedCustomer();
        } else if (typeApply.val() == 'staffs') {
            branch.loadItemSelectedStaff();
        }

        branch.checkedApplyUser();
        $('.ss-select2').select2();
        $('.condition_selected').select2();
        $('#district_id_customer').select2({
            placeholder: "Chọn quận/huyện",
            ajax: {
                url: laroute.route('admin.customer.load-district'),
                data: function (params) {
                    return {
                        id_province: $('#province_id').val(),
                        search: params.term,
                        page: params.page || 1
                    };
                },
                dataType: 'JSON',
                method: 'POST',
                processResults: function (res) {
                    res.page = res.page || 1;
                    return {
                        results: res.optionDistrict.map(function (item) {
                            return {
                                id: item.id,
                                text: item.name
                            };
                        }),
                        pagination: {
                            more: res.pagination
                        }
                    };
                },
            }
        });
    },
    getListProvinces: function () {
        $.ajax({
            url: laroute.route('admin.customer.load-district'),
            dataType: 'JSON',
            data: {
                id_province: $('#province_id_customer').val(),
            },
            method: 'POST',
            success: function (res) {
                $('.district_customer').empty();
                $.map(res.optionDistrict, function (a) {
                    $('.district_customer').append('<option value="' + a.id + '">' + a.type + ' ' + a.name + '</option>');
                });
            }
        });
    },
    getWard: function () {
        var district = $("#district_id_customer").val();
        $.ajax({
            url: laroute.route('admin.customer.load-ward'),
            dataType: 'JSON',
            data: {
                id_district: district,
            },
            method: 'POST',
            success: function (res) {
                $("#ward_id_customer").empty();
                $.map(res.optionWard, function (a) {
                    $("#ward_id_customer").append('<option value="' + a.id + '">' + a.type + ' ' + a.name + '</option>');
                });
            },
            error: function (res) {

            }
        });
    },
    forgetSessionItemSelected: function () {
        $.ajax({
            url: laroute.route('survey.branch.apply.forget-session-item-selected'),
            method: "POST",
            data: { unique: UNIQUE },
            success: function (res) {
                $('#table_list_outlet_selected').html(res.view);
            }
        });
    },
    onChangeAllowAllOutlet: function (type) {
        branch.forgetSessionItemSelected();
        branch.resetSearchItemSelected();
        if (type == 0) {
            $('.btn-store').prop('disabled', false);
        } else {
            $('.btn-store').prop('disabled', true);
        }
    },
    renderPopupBranch: function (isGroup = 0) {
        $.ajax({
            url: laroute.route('survey.branch.apply.render-popup-branch'),
            method: "POST",
            data: {
                unique: UNIQUE,
                isGroup: isGroup,
            },
            success: function (res) {
                $('#div_modal').html(res);
                if (isGroup == 0) {
                    $('#modal_outlet').modal('show');
                    branch.resetSearchBranch();
                } else {
                    $('#modal_outlet_group').modal('show');
                    branch.resetSearchAllOutletGroup();
                }
            }
        });
    },
    searchBranch: function (page = 1) {
        var branchName = $('#modal_outlet' + ' .branch_name').val();
        var representativeCode = $('#modal_outlet' + ' .representative_code').val();
        var branchCode = $('#modal_outlet' + ' .branch_code').val();
        $.ajax({
            url: laroute.route('survey.branch.apply.search-branch'),
            method: "POST",
            data: {
                unique: UNIQUE,
                branchName: branchName,
                representativeCode: representativeCode,
                branchCode: branchCode,
                page: page,
            },
            success: function (res) {
                $('#modal_outlet' + ' .table-list-outlet').html(res.view);
            }
        });
    },
    resetSearchBranch: function () {
        $('#modal_outlet' + ' .branch_name').val('');
        $('#modal_outlet' + ' .representative_code').val('');
        $('#modal_outlet' + ' .branch_code').val('');
        branch.searchBranch();
    },
    searchAllOutletGroup: function (page = 1) {
        var name = $('#modal_outlet_group' + ' .name').val();
        var filter_group_type = $('#modal_outlet_group' + ' .filter_group_type').val();
        $.ajax({
            url: laroute.route('survey.branch.apply.search-all-branch-group'),
            method: "POST",
            data: {
                unique: UNIQUE,
                name: name,
                filter_group_type: filter_group_type,
                page: page,
            },
            success: function (res) {
                $('#modal_outlet_group' + ' .table-list-outlet-group').html(res);

            }
        });
    },
    resetSearchAllOutletGroup: function () {
        $('#modal_outlet_group' + ' .name').val('');
        $('#modal_outlet_group' + ' .filter_group_type').val('');
        branch.searchAllOutletGroup();
    },
    checkedAllItem: function (o, isGroup = 0) {
        var type = 'unchecked';
        var arrItemId = [];
        if (isGroup == 0) {
            if ($(o).is(':checked')) {
                type = 'checked';
                $('#modal_outlet' + ' .checkbox_item').prop('checked', true);
            } else {
                $('#modal_outlet' + ' .checkbox_item').prop('checked', false);
            }
            $('#modal_outlet' + ' .item_id').each(function () {
                var itemId = $(this).val();
                arrItemId.push(itemId);
            });
        } else {
            if ($(o).is(':checked')) {
                type = 'checked';
                $('#modal_outlet_group' + ' .checkbox_item').prop('checked', true);
            } else {
                $('#modal_outlet_group' + ' .checkbox_item').prop('checked', false);
            }
            $('#modal_outlet_group' + ' .item_id').each(function () {
                var itemId = $(this).val();
                arrItemId.push(itemId);
            });
        }
        branch.checkedItemTemp(type, arrItemId, isGroup);
    },
    checkedOneItem: function (o, id, isGroup = 0) {
        var type = 'unchecked';
        var arrItemId = [];
        if (isGroup == 0) {
            if ($(o).is(':checked')) {
                type = 'checked';
            }
            arrItemId.push(id);
        } else {
            if ($(o).is(':checked')) {
                type = 'checked';
            }
            arrItemId.push(id);
        }
        branch.checkedItemTemp(type, arrItemId, isGroup);
    },
    checkedItemTemp: function (type, array_item, isGroup) {
        $.ajax({
            url: laroute.route('survey.branch.apply.checked-item-temp'),
            method: "POST",
            data: {
                unique: UNIQUE,
                array_item: array_item,
                type: type,
                isGroup: isGroup,
            },
            success: function (res) {
            }
        });
    },
    submitAddItemTemp: function (isGroup = 0) {
        $.ajax({
            url: laroute.route('survey.branch.apply.submit-add-item-temp'),
            method: "POST",
            data: {
                unique: UNIQUE,
                isGroup: isGroup,
            },
            success: function (res) {
                if (isGroup == 0) {
                    $('#modal_outlet').modal('hide');
                } else {
                    $('#modal_outlet_group').modal('hide');
                }
                branch.loadItemSelect();
            }
        });
    },
    loadItemSelect: function (page = 1) {
        var branchName = $('#branch_name').val();
        var branchCode = $('#branch_code').val();
        var representativeCode = $('#representative_code').val();
        var phone = $('#phone').val();
        var address = $('#address').val();
        var provinceId = $('#province_id').val();
        var districtId = $('#district_id').val();
        var wardId = $('#ward_id').val();
        var apply_all = $('input[name=apply_all]:checked').val();
        var data = {
            branchName: branchName,
            branchCode: branchCode,
            representativeCode: representativeCode,
            phone: phone,
            address: address,
            provinceId: provinceId,
            districtId: districtId,
            wardId: wardId,
            unique: UNIQUE,
            page: page,
            apply_all: apply_all,
            is_show: IS_SHOW,
        };
        $.ajax({
            url: laroute.route('survey.branch.apply.load-item-selected'),
            method: "POST",
            data: data,
            success: function (res) {
                $('#table_list_outlet_selected').html(res.view);
            }
        });
    },
    resetSearchItemSelected: function () {
        $('#branch_name').val('');
        $('#branch_code').val('');
        $('#representative_code').val('');
        $('#phone').val('');
        $('#address').val('');
        $('#province_id').val('');
        $('#district_id').val('');
        $('#ward_id').val('');
        branch.loadItemSelect();
    },
    removeItemSelected: function (o, id, page) {
        $.ajax({
            url: laroute.route('survey.branch.apply.remove-item-selected'),
            method: "POST",
            data: {
                unique: UNIQUE,
                id: id,
                page: page,
            },
            success: function (res) {
                branch.loadItemSelect(page);
            }
        });
    },
    showModalImport: function () {
        $.ajax({
            url: laroute.route('survey.branch.apply.show-modal-import'),
            method: "POST",
            data: {
                unique: UNIQUE,
            },
            success: function (res) {
                $('#div_modal').html(res);
                $('#modal_import_excel').modal('show');
            }
        });
    },
    showNameFile: function () {
        var fileName = $('#file_excel').val();
        $('#show').val(fileName);
        outlet.importExcel();
    },
    importExcel: function () {
        var file_data = $('#file_excel').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        $.ajax({
            url: laroute.route('survey.branch.apply.import-excel'),
            method: "POST",
            data: form_data,
            contentType: false,
            cache: false,
            processData: false,
            success: function (res) {
                if (res.error == false) {
                    $('#modal_import_excel' + ' .tbody_preview').html(res.view);
                    $('.btn-submit-excel').prop('disabled', false);
                }
                $('.btn-upload-excel').hide();
            },
            error: function (res) {
                swal.fire(res.message, "", "error");
            }
        });
    },
    submitImportExcel: function (type) {
        var file_data = $('#file_excel').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('unique', UNIQUE);
        $.ajax({
            url: laroute.route('survey.branch.apply.submit-import-excel'),
            method: "POST",
            data: form_data,
            contentType: false,
            cache: false,
            processData: false,
            success: function (res) {
                if (res.error == false) {
                    if (res.error_file == false) {
                        $('#modal_import_excel').modal('hide');
                    } else {
                        $('.btn-submit-excel').hide();
                        $('.href_file_example').hide();
                        $('#modal_import_excel' + ' .tbody_preview').html(res.view);
                    }
                } else {
                    swal.fire(res.message, "", "error");
                }
            },
            error: function (res) {
                swal.fire(res.message, "", "error");
            }
        });
        branch.loadItemSelect();
    },
    save: function (id) {
        var jsonLang = JSON.parse(localStorage.getItem('tranlate'));
        var data = {};
        var typeApply = $('input[name=type_apply]:checked').val();
        if (typeApply == 'staffs') {
            data = branch.getAllDataStaffApply(id, typeApply);
        } else if (typeApply == 'customers') {
            data = branch.getAllDataCustomerApply(id, typeApply);
        } else if (typeApply == null) {
            data.typeApply = null;
            data.unique = UNIQUE;
            data.survey_id = id;

        } else {
            data.typeApply = typeApply;
            data.unique = UNIQUE;
            data.survey_id = id;
        }
        $.ajax({
            url: laroute.route('survey.branch.apply.update'),
            method: "POST",
            data: data,
            success: function (res) {
                if (res.error === false) {
                    swal.fire(res.message, '', "success").then(function () {
                        window.location.href = laroute.route('survey.show-branch', { id: id });
                    });
                } else {
                    var mess_error = '';
                    $.map(res.array_error, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal.fire(res.message, mess_error, "error");
                }
            },
            error: function (res) {
                if (res.responseJSON != undefined) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal.fire(jsonLang['Chỉnh sửa thất bại'], mess_error, "error");
                }
            }
        });
    },
    getAllDataStaffApply: function (id, typeApply) {
        var departmentSeleted = $("#department_seleted").val();
        var titleSeleted = $("#title_seleted").val();
        var branchSeleted = $("#branch_seleted").val();
        var typeCondition = $("#type_condition_seleted").val();
        var countListStaff = $("#tbody-add-staff-seleted > tr").length;
        const data = {};
        data.survey_id = id;
        data.condition_branch = branchSeleted;
        data.condition_department = departmentSeleted;
        data.condition_titile = titleSeleted;
        data.type_condition = typeCondition;
        data.typeApply = typeApply;
        data.unique = UNIQUE;
        data.countListStaff = countListStaff;
        return data;
    },
    getAllDataCustomerApply: function (id, typeApply) {
        var idGroupAutoCustomer = $("#itemGroupChecked").val();
        var countListCustomer = $("#tbody-add-customer-seleted > tr").length;
        const data = {};
        data.survey_id = id;
        data.idGroupAutoCustomer = idGroupAutoCustomer;
        data.typeApply = typeApply;
        data.unique = UNIQUE;
        data.countListCustomer = countListCustomer;
        return data;
    },
    checkedApplyUser: function () {
        typeApply = $("input[name=type_apply]:checked").val();
        if (typeApply == 'staffs') {
            $(".list_staff-apply").show();
            $(".list_staff-button").show();
        } else if (typeApply == 'customers') {
            $(".list_customer-apply").show();
            $(".list_customer-button").show();

        }
    },
    toggleApplyUser: function (o) {

        if (o.value == 'staffs') {
            $(".list_customer-apply").hide();
            $(".list_customer-button").hide();
            $(".list_staff-apply").show();
            $(".list_staff-button").show();
        } else if (o.value == 'customers') {
            $(".list_customer-apply").show();
            $(".list_customer-button").show();
            $(".list_staff-apply").hide();
            $(".list_staff-button").hide();
        } else if (o.value == 'all_staff' || o.value == 'all_customer') {
            $(".list_customer-apply").hide();
            $(".list_staff-apply").hide();
            $(".list_staff-button").hide();
            $(".list_customer-button").hide();
        }
    },
    // customer survey //
    renderModalCustomer: function () {
        $.ajax({
            url: laroute.route('survey.branch.apply.render-popup-customer'),
            method: "POST",
            data: {
                unique: UNIQUE
            },
            success: function (res) {
                $('#div_modal').html(res);
                $('#modal_customer').modal('show');
                $('#province_id_customer').select2({
                    placeholder: "Chọn tỉnh/thành",
                });

                $('#ward_id_customer').select2({
                    placeholder: "Chọn phường/xã",
                });
                $('#district_id_customer').select2({
                    placeholder: "Chọn quận/huyện",
                })
                $('#customer_type_modal').select2();
                $('#customer_group_modal').select2();
                $('#customer_source_modal').select2();

                branch.resetSearchCustomer();
            }
        });
    },
    resetSearchCustomer: function () {

        var jsonLang = JSON.parse(localStorage.getItem('tranlate'));

        $('#modal_customer' + ' #customer_type_modal').val("");
        $('#modal_customer' + ' #customer_group_modal').val("");
        $('#modal_customer' + ' #customer_source_modal').val("");
        $('#modal_customer' + ' #province_id_customer').val("");
        $('#modal_customer' + ' #district_id_customer').val("");
        $('#modal_customer' + ' #ward_id_customer').val("");
        $('#modal_customer' + ' #code_or_name_customer_modal').val("");

        $('#province_id_customer').select2({
            placeholder: jsonLang["Chọn tỉnh/thành"],
            allowClear: true
        });

        $('#ward_id_customer').select2({
            placeholder: jsonLang["Chọn phường/xã"],
            allowClear: true
        });
        $('#district_id_customer').select2({
            placeholder: jsonLang["Chọn quận/huyện"],
            allowClear: true
        }),
            $('#customer_type_modal').select2({
                placeholder: jsonLang["Loại khách hàng"],
                allowClear: true
            })
        $('#customer_group_modal').select2({
            placeholder: jsonLang["Nhóm khách hàng"],
            allowClear: true
        })
        $('#customer_source_modal').select2({
            placeholder: jsonLang["Nguồn khách hàng"],
            allowClear: true
        })
        branch.searchCustomer();
    },
    searchCustomer: function (page = 1) {
        var customerType = $('#modal_customer' + ' #customer_type_modal option:selected').val();
        var customerGroup = $('#modal_customer' + ' #customer_group_modal option:selected').val();
        var customerSource = $('#modal_customer' + ' #customer_source_modal option:selected').val();
        var customerProvince = $('#modal_customer' + ' #province_id_customer option:selected').val();
        var customerDistrict = $('#modal_customer' + ' #district_id_customer option:selected').val();
        var customerWard = $('#modal_customer' + ' #ward_id_customer option:selected').val();
        var nameOrCode = $('#modal_customer' + ' #code_or_name_customer_modal').val();

        $.ajax({
            url: laroute.route('survey.branch.apply.search-customer'),
            method: "POST",
            data: {
                unique: UNIQUE,
                customerType: customerType,
                customerGroup: customerGroup,
                customerSource: customerSource,
                customerProvince: customerProvince,
                customerDistrict: customerDistrict,
                customerWard: customerWard,
                nameOrCode: nameOrCode,
                page: page,
            },
            success: function (res) {
                $('#modal_customer' + ' .table-list-customer').html(res.view);

            }
        });
    },
    checkedAllItemCustomer: function (o) {
        var type = 'unchecked';
        var arrItemId = [];

        if ($(o).is(':checked')) {
            type = 'checked';
            $('#modal_customer' + ' .checkbox_item').prop('checked', true);
        } else {
            $('#modal_customer' + ' .checkbox_item').prop('checked', false);
        }
        $('#modal_customer' + ' .item_id').each(function () {
            var itemId = $(this).val();
            arrItemId.push(itemId);
        });

        branch.checkedItemTempCustomer(type, arrItemId);
    },
    checkedOneItemCustomer: function (o, id) {
        var type = 'unchecked';
        var arrItemId = [];
        if ($(o).is(':checked')) {
            type = 'checked';
        }
        arrItemId.push(id);
        branch.checkedItemTempCustomer(type, arrItemId);
    },
    checkedItemTempCustomer: function (type, array_item) {
        $.ajax({
            url: laroute.route('survey.branch.apply.checked-item-temp-customer'),
            method: "POST",
            data: {
                unique: UNIQUE,
                array_item: array_item,
                type: type
            },
            success: function (res) {
            }
        });
    },
    submitAddItemTempCustomer: function () {
        $.ajax({
            url: laroute.route('survey.branch.apply.submit-add-item-temp'),
            method: "POST",
            data: {
                unique: UNIQUE,
            },
            success: function (res) {
                $('#modal_customer').modal('hide');
                branch.loadItemSelectedCustomer();
            }
        });
    },
    loadItemSelectedCustomer: function (page = 1) {
        var nameOrCode = $('#code_or_name_customer').val();
        var customerType = $('#customer_type option:selected').val();
        var customerSource = $('#customer_source option:selected').val();
        var customerGroup = $('#customer_group option:selected').val();
        var provinceId = $('#province_id').val();
        var districtId = $('#district_id').val();
        var wardId = $('#ward_id').val();
        var data = {
            provinceId: provinceId,
            districtId: districtId,
            customerGroup: customerGroup,
            wardId: wardId,
            unique: UNIQUE,
            page: page,
            nameOrCode: nameOrCode,
            customerType: customerType,
            customerSource: customerSource,
            is_show: IS_SHOW,
        };
        $.ajax({
            url: laroute.route('survey.branch.apply.load-item-selected-customer'),
            method: "POST",
            data: data,
            success: function (res) {
                $('.table_customer_selected').html(res.view);
            }
        });
    },
    resetSearchItemSelectedCustomer: function () {
        var jsonLang = JSON.parse(localStorage.getItem('tranlate'));

        $('.list_customer-apply' + ' #customer_type').val("");
        $('.list_customer-apply' + ' #customer_group').val("");
        $('.list_customer-apply' + ' #customer_source').val("");
        $('.list_customer-apply' + ' #province_id option:selected').prop("selected", false);
        $('#district_id').val("");
        $('.list_customer-apply' + ' #ward_id').val("");
        $('.list_customer-apply' + ' #code_or_name_customer').val('');
        $('#province_id').select2({
            placeholder: jsonLang["Chọn tỉnh/thành"],
            allowClear: true
        });

        $('#ward_id').select2({
            placeholder: jsonLang["Chọn phường/xã"],
            allowClear: true
        });
        $('#district_id').select2({
            placeholder: jsonLang["Chọn quận/huyện"],
            allowClear: true
        }),
            $('#customer_type').select2({
                placeholder: jsonLang["Loại khách hàng"],
                allowClear: true
            }),
            $('#customer_group').select2({
                placeholder: jsonLang["Nhóm khách hàng"],
                allowClear: true
            }),
            $('#customer_source').select2({
                placeholder: jsonLang["Nguồn khách hàng"],
                allowClear: true
            })

        branch.loadItemSelectedCustomer();
    },

    removeItemSelectedCustomer: function (o, id, page) {
        $.ajax({
            url: laroute.route('survey.branch.apply.remove-item-selected-customer'),
            method: "POST",
            data: {
                unique: UNIQUE,
                id: id,
                page: page,
            },
            success: function (res) {
                branch.loadItemSelectedCustomer(page);
            }
        });
    },

    // customer survey auto //
    renderModalCustomerAuto: function (id) {
        $.ajax({
            url: laroute.route('survey.branch.apply.render-popup-customer-auto'),
            method: "POST",
            data: {
                unique: UNIQUE,
                id: id
            },
            success: function (res) {
                $('#div_modal').html(res);
                $('#modal_customer_auto').modal('show');
                branch.resetSearchCustomerAuto();
                $("#type_group").select2();
            }
        });
    },
    resetSearchCustomerAuto: function () {
        $('#modal_customer_auto' + ' #name_group_customer').val('');
        $('#modal_customer_auto' + ' #type_group option:selected').prop("selected", false);
        branch.searchCustomerAuto();
    },
    searchCustomerAuto: function (page = 1) {
        var nameGroupCustomer = $('#modal_customer_auto' + ' #name_group_customer').val();
        var typeGroupCustomer = $('#modal_customer_auto' + ' #type_group option:selected').val();

        $.ajax({
            url: laroute.route('survey.branch.apply.search-customer-auto'),
            method: "POST",
            data: {
                unique: UNIQUE,
                nameGroupCustomer: nameGroupCustomer,
                typeGroupCustomer: typeGroupCustomer,
                page: page,
            },
            success: function (res) {
                $('#modal_customer_auto' + ' .table-list-customer_auto').html(res.view);
            }
        });
    },

    submitAddItemTempCustomerAuto: function () {
        var idGroupCustomer = $("input[name=checked_group]:checked").val();
        $.ajax({
            url: laroute.route('survey.branch.apply.submit-add-item-temp-auto'),
            method: "POST",
            data: {
                id: idGroupCustomer
            },
            success: function (res) {
                $('#modal_customer_auto').modal('hide');
                $('.show-group_customer-selected').html(res.view);
            }
        });
    },
    // staffs // 
    renderModalStaff: function () {
        var jsonLang = JSON.parse(localStorage.getItem('tranlate'));
        $.ajax({
            url: laroute.route('survey.branch.apply.render-popup-staff'),
            method: "POST",
            data: {
                unique: UNIQUE
            },
            success: function (res) {
                $('#div_modal').html(res);
                $('#modal_staff').modal('show');
                $('#province_id_customer').select2({
                    placeholder: jsonLang["Chọn tỉnh/thành"],
                });

                $('#ward_id_customer').select2({
                    placeholder: jsonLang["Chọn phường/xã"],
                });
                $('#district_id_customer').select2({
                    placeholder: jsonLang["Chọn quận/huyện"],
                })
                branch.resetSearchStaff();
            }
        });
    },
    resetSearchStaff: function () {
        $('#modal_staff' + ' #staff_department_modal').val('');
        $('#modal_staff' + ' #staff_position_modal').val('');
        $('#modal_staff' + ' #staff_branch_modal').val('');
        $('#modal_staff' + ' #name_or_code_staff_modal').val("");
        $("#staff_branch_modal").select2();
        $("#staff_department_modal").select2();
        $("#staff_position_modal").select2();
        $('#modal_staff' + ' #address_staff_modal').val('');
        branch.searchStaff();
    },
    searchStaff: function (page = 1) {
        var staffDepartment = $('#modal_staff' + ' #staff_department_modal option:selected').val();
        var staffPosition = $('#modal_staff' + ' #staff_position_modal option:selected').val();
        var staffBranch = $('#modal_staff' + ' #staff_branch_modal option:selected').val();
        var address = $('#modal_staff' + ' #address_staff_modal').val();
        console.log(address);
        var nameOrCodeStaff = $('#modal_staff' + ' #name_or_code_staff_modal').val();
        $.ajax({
            url: laroute.route('survey.branch.apply.search-staff'),
            method: "POST",
            data: {
                unique: UNIQUE,
                staffDepartment: staffDepartment,
                staffPosition: staffPosition,
                staffBranch: staffBranch,
                address: address,
                nameOrCodeStaff: nameOrCodeStaff,
                page: page,
            },
            success: function (res) {
                $('#modal_staff' + ' .table-list-staff').html(res.view);
            }
        });
    },
    checkedAllItemStaff: function (o) {
        var type = 'unchecked';
        var arrItemId = [];

        if ($(o).is(':checked')) {
            type = 'checked';
            $('#modal_staff' + ' .checkbox_item').prop('checked', true);
        } else {
            $('#modal_staff' + ' .checkbox_item').prop('checked', false);
        }
        $('#modal_staff' + ' .item_id').each(function () {
            var itemId = $(this).val();
            arrItemId.push(itemId);
        });

        branch.checkedItemTempStaff(type, arrItemId);
    },
    checkedOneItemStaff: function (o, id) {
        var type = 'unchecked';
        var arrItemId = [];
        if ($(o).is(':checked')) {
            type = 'checked';
        }
        arrItemId.push(id);
        branch.checkedItemTempStaff(type, arrItemId);
    },
    checkedItemTempStaff: function (type, array_item) {
        $.ajax({
            url: laroute.route('survey.branch.apply.checked-item-temp-staff'),
            method: "POST",
            data: {
                unique: UNIQUE,
                array_item: array_item,
                type: type
            },
            success: function (res) {
            }
        });
    },

    submitAddItemTempStaff: function () {
        $.ajax({
            url: laroute.route('survey.branch.apply.submit-add-item-temp-staff'),
            method: "POST",
            data: {
                unique: UNIQUE,
            },
            success: function (res) {
                $('#modal_staff').modal('hide');
                branch.loadItemSelectedStaff();
            }
        });
    },

    loadItemSelectedStaff: function (page = 1) {
        var nameOrCode = $('#name_or_code_staff').val();
        var staffDepartment = $('#staff_department option:selected').val();
        var staffPosition = $('#staff_position option:selected').val();
        var staffBranch = $('#staff_branch option:selected').val();
        var address = $("#address_staff").val();
        var data = {
            address: address,
            unique: UNIQUE,
            page: page,
            nameOrCode: nameOrCode,
            staffDepartment: staffDepartment,
            staffPosition: staffPosition,
            staffBranch: staffBranch,
            is_show: IS_SHOW,
        };
        $.ajax({
            url: laroute.route('survey.branch.apply.load-item-selected-staff'),
            method: "POST",
            data: data,
            success: function (res) {
                $('.table_staff_selected').html(res.view);
            }
        });
    },

    resetSearchItemSelectedStaff: function () {
        $('.list_staff-apply' + ' #staff_department').val('');
        $('.list_staff-apply' + ' #staff_position').val('');
        $('.list_staff-apply' + ' #staff_branch').val('');
        $('.list_staff-apply' + ' #name_or_code_staff').val('');
        $('.list_staff-apply' + ' #address_staff').val('');
        $("#staff_department").select2();
        $("#staff_position").select2();
        $("#staff_branch").select2();
        branch.loadItemSelectedStaff();
    },

    removeItemSelectedStaff: function (o, id, page) {
        $.ajax({
            url: laroute.route('survey.branch.apply.remove-item-selected-staff'),
            method: "POST",
            data: {
                unique: UNIQUE,
                id: id,
                page: page,
            },
            success: function (res) {
                branch.loadItemSelectedStaff(page);
            }
        });
    },

    showModalStaffAuto: function () {
        $("#modal_staff_auto").modal('show');
        $('.chooses-condition').select2();

    },
    addCondition: function () {
        let flag = true;
        var countCondition = 0;
        $('.condition').each(function () {
            var val = $(this).val();
            if (val == '') {
                flag = false;
            } else {
                if (flag == true) {
                    $(this).prop('disabled', true)
                }
            }
        });
        $('.chooses-condition').each(function () {
            var val = $(this).val();
            if (val == '') {
                flag = false;
            }
        });

        $('.div-condition .chooses-condition').each(function () {
            countCondition++;
        });
        if (countCondition >= 3) {
            $('.btn-add-condition').hide();
            flag = false;
        }
        if (flag == true) {
            let tpl = $('#choose-condition').html();
            tpl = tpl.replace(/{option}/g, branch.loadCondition());
            $('.div-condition').append(tpl);
            $(".ss--select-2").select2();
        }
    },
    chooseCondition: function (t) {
        var idCondition = $(t).val();
        var divContentCondition = $(t).parents('.A-condition-1').find('.div-content-condition');
        var tpl;
        divContentCondition.empty();
        if (idCondition == '') {
            divContentCondition.empty();
        } else if (idCondition == 'condition_branch') {

            tpl = $('#tpl-branch-define').html();

        } else if (idCondition == 'condition_department') {

            tpl = $('#tpl-department-define').html();
        } else if (idCondition == 'condition_title') {

            tpl = $('#tpl-title-define').html();
        }

        divContentCondition.append(tpl);
        $('.chooses-condition').select2();
    },
    loadCondition: function () {
        var jsonLang = JSON.parse(localStorage.getItem('tranlate'));
        var arrayCondition = [0];
        $('.condition').each(function () {
            var val = $(this).val();
            arrayCondition.push(val);
        });
        var option = '';
        $.ajax({
            url: laroute.route('survey.branch.apply.get-condition'),
            method: "POST",
            data: { arrayCondition: arrayCondition },
            async: false,
            success: function (res) {

                $.each(res, function (key, value) {
                    option += "<option value='" + key + "'>" + jsonLang[value] + "</option>";
                })
            }
        });
        return option;
    },
    removeCondition: function (t) {
        $(t).closest('.A-condition-1').remove();
        $('.btn-add-condition').show();
    },
    removeConditionSelected: function (t, type) {
        $(t).closest('.A-condition-1').remove();
        $(`#${type}`).closest('.A-condition-1').remove();
        $('.btn-add-condition').show();
    },
    submitAddItemTempStaffAuto: function () {
        var listConditionDepartment = $('#condition_department').val();
        var listConditionTitle = $('#condition_titile').val();
        var listConditionBranch = $('#condition_branch').val();
        var typeCondition = $('#type_condition').val();
        var jsonLang = JSON.parse(localStorage.getItem('tranlate'));
        $.ajax({
            url: laroute.route('survey.branch.apply.get-condition-selected'),
            method: "POST",
            data: {
                listConditionDepartment: listConditionDepartment,
                listConditionTitle: listConditionTitle,
                listConditionBranch: listConditionBranch,
                typeCondition: typeCondition,
            },
            success: function (res) {
                $("#modal_staff_auto").modal("hide");
                $("#staff_condition_seleted").empty();
                $("#staff_condition_seleted").html(res.view);
                $('.condition_selected').select2();

            }, error: function (error) {
                var mess_error = '';
                $.map(error.responseJSON.errors, function (a) {
                    mess_error = mess_error.concat(a + '<br/>');
                });
                swal.fire(jsonLang['Thêm nhân viên động thất bại !'], mess_error, "error");
            }
        });
    },

    removeCustomerAuto: function (o) {
        $(o).closest('#itemGroupCustomerSeleted').remove();
    }


};

branch.init();
