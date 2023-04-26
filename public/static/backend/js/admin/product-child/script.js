var productChild = {
    init: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var arrRange = {};
            arrRange[json['Hôm nay']] = [moment(), moment()],
                arrRange[json['Hôm qua']] = [moment().subtract(1, "days"), moment().subtract(1, "days")],
                arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()],
                arrRange[json["30 ngày trước"]] = [moment().subtract(29, "days"), moment()],
                arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")],
                arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
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
                    "applyLabel": json["Đồng ý"],
                    "cancelLabel": json["Thoát"],
                    "customRangeLabel": json["Tùy chọn ngày"],
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
            }).on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'))
            });
        });
        productChild.tab('new');
        productChild.tab('sale');
        productChild.tab('best_seller');
        $('#autotable_new').PioTable({
            baseUrl: laroute.route('admin.product-child.list-tab')
        });
        $('#autotable_sale').PioTable({
            baseUrl: laroute.route('admin.product-child.list-tab')
        });
        $('#autotable_best_seller').PioTable({
            baseUrl: laroute.route('admin.product-child.list-tab')
        });
        $(document).on('keyup', ".input-percent-sale", function () {
            var n = parseInt($(this).val().replace(/\D/g, ''), 10);
            if (typeof n == 'number' && Number.isInteger(n)) {
                if (n > 100) {
                    $(this).val(0);
                }
            } else {
                $(this).val(0);
            }
            $(this).val(parseInt($(this).val()));
        });

    },
    notEnterInput: function (thi) {
        $(thi).val('');
    },
    tab: function (type_tab) {
        $.ajax({
            url: laroute.route('admin.product-child.list-tab'),
            method: "POST",
            async: false,
            data: {type_tab: type_tab},
            success: function (res) {
                if (type_tab === 'new'){
                    $('.table-content-new').empty();
                    $('.table-content-new').prepend(res);
                } else if (type_tab === 'sale') {
                    $('.table-content-sale').empty();
                    $('.table-content-sale').prepend(res);
                } else if (type_tab === 'best_seller') {
                    $('.table-content-best-seller').empty();
                    $('.table-content-best-seller').prepend(res);
                }
            }
        });
    },
    popAdd: function (type_tab) {
        $.ajax({
            url: laroute.route('admin.product-child.get-option-add-tab'),
            method: "POST",
            data: {type_tab: type_tab},
            success: function (res) {
                $('#append-popup').empty();
                $('#append-popup').prepend(res);
                $('#modal_add').modal('show');
                $('.ss--select-2').select2();
            }
        });
    },
    selectedProductChild: function (type_tab, th) {
        $.ajax({
            url: laroute.route('admin.product-child.selected-product-child'),
            method: "POST",
            data: {
                id: $(th).val(),
                type_tab: type_tab
            },
            success: function (res) {
                var flag = true;
                $.each($('.product_child_id'), function () {
                    if ($(this).val() == res.product_child_id) {
                        flag = false;
                        return false;
                    }
                });
                if (res.product_child_id !== undefined && flag === true) {
                    if (type_tab != 'sale') {
                        let tpl = $('#product-childs').html();
                        tpl = tpl.replace(/{product_child_id}/g, res.product_child_id);
                        tpl = tpl.replace(/{product_child_name}/g, res.product_child_name);
                        tpl = tpl.replace(/{price}/g, productChild.formatNumber(res.price));
                        tpl = tpl.replace(/{unit}/g, res.unit_name);
                        tpl = tpl.replace(/{cost}/g, productChild.formatNumber(res.cost));
                        $('.tbody-table-product').append(tpl);
                    } else {
                        let tpl = $('#product-childs-sale').html();
                        tpl = tpl.replace(/{product_child_id}/g, res.product_child_id);
                        tpl = tpl.replace(/{product_child_name}/g, res.product_child_name);
                        tpl = tpl.replace(/{price}/g, productChild.formatNumber(res.price));
                        tpl = tpl.replace(/{unit}/g, res.unit_name);
                        tpl = tpl.replace(/{cost}/g, productChild.formatNumber(res.cost));
                        $('.tbody-table-product').append(tpl);
                    }
                }
                $('.input-percent-sale').mask('000', {reverse: true});
                productChild.resetStt();
            }
        });
    },
    removeTr: function (o) {
        $(o).closest('tr').remove();
        let table = $('#table-product > tbody tr').length;
        let a = 1;
        $.each($('.stt'), function () {
            $(this).text(a++);
        });
    },
    resetStt: function () {
        var stt = 1;
        $.each($('.stt'), function () {
            $(this).text(stt++);
        });
    },
    submitAdd: function (type_tab) {
        $.ajax({
            url: laroute.route('admin.product-child.submit-add-product-child'),
            method: "POST",
            data: {
                type_tab: type_tab,
                productChildId: productChild.eachGetProductChildId(type_tab)
            },
            success: function (res) {

                if (res.error === false) {
                    swal(
                        res.message,
                        '',
                        'success'
                    );
                    productChild.tab(type_tab);
                    $('#modal_add').modal('hide');
                } else {
                    swal(
                        'Thêm thất bại',
                        '',
                        'error'
                    );
                }
            }
        });
    },
    eachGetProductChildId: function (type_tab) {
        var result = [];
        if (type_tab != 'sale') {
            $.each($('.product_child_id'), function () {
                result.push($(this).val());
            });
        } else {
            $.each($('.product_child_id'), function () {
                let id = $(this).val();
                let percentSale = $(this).closest('tr').find('.input-percent-sale').val();
                var temp = {id: id, percentSale: percentSale};
                result.push(temp);
            });
        }
        return result;
    },
    tabCurrent: function (type_tab) {
        $.ajax({
            url: laroute.route('admin.product-child.tab-current'),
            method: "POST",
            data: {
                type_tab: type_tab,
            },
            success: function (res) {
            }
        });
    },
    removeList: function (obj, type_tab, product_child_id) {
        $.getJSON(laroute.route('translate'), function (json) {

            $(obj).closest('tr').addClass('m-table__row--danger');
            swal({
                title: json['Thông báo'],
                text: json["Bạn có muốn xóa không?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json['Xóa'],
                cancelButtonText: json['Hủy'],
                onClose: function () {
                    $(obj).closest('tr').removeClass('m-table__row--danger');
                }
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: laroute.route('admin.product-child.remove-list'),
                        method: "POST",
                        data: {
                            type_tab: type_tab,
                            product_child_id: product_child_id,
                        },
                        success: function (res) {
                            if (res.error === false) {
                                swal(
                                    res.message,
                                    '',
                                    'success'
                                );
                                productChild.tab(type_tab);
                            } else {
                                swal(
                                    json['Xóa không thành công'],
                                    '',
                                    'error'
                                );
                            }
                        }
                    });
                }
            });
        });
    },
    formatNumber: function (num) {
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
    },

    // Thêm điều kiện mới
    addConditionSuggest:function () {
        countSuggest = countSuggest+1;
        $.ajax({
            url: laroute.route('admin.product-child.add-condition-suggest'),
            method: "POST",
            data: {
                number : countSuggest
            },
            success: function (res) {
                if (res.error == false){
                    $('.product_suggest_condition').append(res.view);
                    $('.select2-suggest').select2();
                    $(".suggest_tags").select2({
                        placeholder: 'Chọn tags sản phẩm',
                        language: {
                            noResults: function() {
                                return 'Không tìm thấy tags sản phẩm ';
                            },
                        },
                    });
                    new AutoNumeric.multiple('#suggest_quantity_'+countSuggest, {
                        currencySymbol: '',
                        decimalCharacter: '.',
                        digitGroupSeparator: ',',
                        decimalPlaces: 0,
                        eventIsCancelable: true,
                        minimumValue: 0
                    });
                    $.getJSON(laroute.route('translate'), function (json) {
                        var arrRange = {};
                        arrRange[json['Hôm nay']] = [moment(), moment()],
                            arrRange[json['Hôm qua']] = [moment().subtract(1, "days"), moment().subtract(1, "days")],
                            arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()],
                            arrRange[json["30 ngày trước"]] = [moment().subtract(29, "days"), moment()],
                            arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")],
                            arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
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
                                "applyLabel": json["Đồng ý"],
                                "cancelLabel": json["Thoát"],
                                "customRangeLabel": json["Tùy chọn ngày"],
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
                        }).on('apply.daterangepicker', function (ev, picker) {
                            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'))
                        });
                    });

                } else {
                    swal(
                        res.message, '', 'error'
                    );
                }
            }
        });
    },

    // Xoá điều kiện
    removeCondition: function (number) {
        $('.block-suggest-'+number).remove();
    },

    // Lưu cấu hình
    addConditionSuggestConfig: function () {
        var obj = {};
        var messageError = '';
        var n = 0;
        $.getJSON(laroute.route('admin.validation'), function (json) {
            $.each($('.product_suggest_condition').find('.block-suggest'), function () {
                n = n + 1;
                var suggest_id = $(this).find($('.suggest_id')).val();
                var suggest_type = $(this).find($('.suggest_type')).val();
                var suggest_is_condition = $(this).find($('.suggest_is_condition')).val();
                var suggest_product_condition_id = $(this).find($('.suggest_product_condition_id')).val();
                var suggest_type_condition = $(this).find('.suggest_product_condition_id').find(':selected').attr('data-option');
                var suggest_key = $(this).find('.suggest_product_condition_id').find(':selected').attr('data-key');
                var suggest_quantity = $(this).find($('.suggest_quantity')).val();
                var suggest_date_range = $(this).find($('.suggest_date_range')).val();
                var suggest_tags = $(this).find($('.suggest_tags')).val();

                var keyCheck = suggest_type+'_'+suggest_product_condition_id;

                if (keyCheck in obj){
                    if (suggest_type == 'product'){
                        messageError = messageError + json.product_suggest.goi_y + n + json.product_suggest.product_using + '<br>';
                    } else if(suggest_type == 'service'){
                        messageError = messageError + json.product_suggest.goi_y + n + json.product_suggest.service_using + '<br>';
                    }
                } else {
                    if(suggest_type_condition == 'number_date'){
                        if (suggest_quantity == ''){
                            messageError = messageError + json.product_suggest.input_quantity + n + '<br>';
                        }

                        if (suggest_date_range == ''){
                            messageError = messageError + json.product_suggest.input_time + n + '<br>';
                        }

                    } else if (suggest_type_condition == 'number') {
                        if (suggest_quantity == ''){
                            messageError = messageError + json.product_suggest.input_quantity + n + '<br>';
                        }
                    } else if (suggest_type_condition == 'tags'){
                        if (suggest_tags.length == 0) {
                            messageError = messageError + json.product_suggest.input_tags + n + '<br>';
                        }
                    }
                }

                obj[suggest_type+'_'+suggest_product_condition_id] = {
                    suggest_type : suggest_type,
                    suggest_key : suggest_key,
                    suggest_is_condition : suggest_is_condition,
                    suggest_product_condition_id : suggest_product_condition_id,
                    suggest_type_condition : suggest_type_condition,
                    suggest_quantity : suggest_quantity,
                    suggest_date_range : suggest_date_range,
                    suggest_tags : suggest_tags,
                };

            });
        if (messageError != ''){
            swal(messageError, '', 'error');
        } else {
            if (obj.length != 0 && typeof obj !== "undefined" && n != 0){

                $.ajax({
                    url: laroute.route('admin.product-child.insert-condition-suggest'),
                    method: "POST",
                    data: obj,
                    success: function (res) {
                        if(res.error == false){
                            swal(res.message, '', 'success');
                        } else {
                            swal(res.message, '', 'error');
                        }

                    }
                });
            } else {
                swal(json.product_suggest.no_choose_config, '', 'error');
            }
        }

        });

    },

    changeCondition : function (key) {
        var val = $('#suggest_product_condition_id_'+key).find(':selected').attr('data-option');
        console.log(val);
        if (val == 'number'){
            $('.block-suggest-'+key+' .block-number').show();
            $('.block-suggest-'+key+' .block-number').removeClass('d-none');
            $('.block-suggest-'+key+' .block-date').hide();
            $('.block-suggest-'+key+' .block-tags').hide();
        } else if (val == 'date'){
            $('.block-suggest-'+key+' .block-number').hide();
            $('.block-suggest-'+key+' .block-date').show();
            $('.block-suggest-'+key+' .block-date').removeClass('d-none');
            $('.block-suggest-'+key+' .block-tags').hide();
        } else if (val == 'number_date'){
            $('.block-suggest-'+key+' .block-number').show();
            $('.block-suggest-'+key+' .block-number').removeClass('d-none');
            $('.block-suggest-'+key+' .block-date').show();
            $('.block-suggest-'+key+' .block-date').removeClass('d-none');
            $('.block-suggest-'+key+' .block-tags').hide();
        } else if (val == 'tags'){
            $('.block-suggest-'+key+' .block-number').hide();
            $('.block-suggest-'+key+' .block-date').hide();
            $('.block-suggest-'+key+' .block-tags').show();
            $('.block-suggest-'+key+' .block-tags').removeClass('d-none');
        } else {
            $('.block-suggest-'+key+' .block-number').hide();
            $('.block-suggest-'+key+' .block-date').hide();
            $('.block-suggest-'+key+' .block-tags').hide();
        }
    }
};
productChild.init();
