
var order = {
    //biến dùng cho load more sản phẩm
    is_busy : false,
    page : 1,
    stopped : false,
    //End
    jsonLang: null,

    chooseType: function (objectType) {
        $('#search').val('')
        $.ajax({
            url: laroute.route('fnb.orders.choose-type'),
            data: {
                object_type: objectType
            },
            method: 'POST',
            dataType: 'JSON',
            success: function (res) {
                $('.ul_category').empty()

                var tpl = $('#tab-category-tpl').html()
                tpl = tpl.replace(/{category_id}/g, 'all')
                tpl = tpl.replace(/{category_name}/g,  order.jsonLang['Tất cả'])
                tpl = tpl.replace(/{active}/g, 'active show')
                $('.ul_category').append(tpl)

                $.map(res.data, function (v) {

                    var tpl = $('#tab-category-tpl').html()
                    tpl = tpl.replace(/{category_id}/g, v.category_id)
                    tpl = tpl.replace(/{category_name}/g, v.category_name.toLowerCase())
                    tpl = tpl.replace(/{active}/g, '')
                    $('.ul_category').append(tpl)
                })
                //Chỉnh lại chiều cao của table item order
                $(window).on('resize', function(){
                    var height = $(this).height();
                    var heightItemProduct = height - 320 - $('#tab_category').height();
                    // $('.demo-index').height(heightItemProduct);
                }).trigger('resize'); //on page load
                order.loadProduct('all')
            }
        })
    },
    loadProduct: function (categoryId = null) {
        var object_type = $('.ul_type').find('.active').attr('data-name');

        order.is_busy = false;
        order.page = 1;
        if (categoryId != null) {
            $('#category_id_hidden').val(categoryId);
        }

        var search = $('#search').val()

        $.ajax({
            url: laroute.route('fnb.orders.list-add'),
            data: {
                object_type: object_type,
                category_id: $('#category_id_hidden').val(),
                search: search,
                customer_id: $('#customer_id').val(),
                page: order.page
            },
            method: 'POST',
            dataType: 'JSON',
            success: function (res) {
                $('.demo-index').empty()
                $('.demo-index').append(res)
            }
        })
    },
    click: function (param) {
        mApp.block("#m_blockui_1_content", {
            overlayColor: "#000000",
            type: "loader",
            state: "success",
            message:  order.jsonLang["Đang tải..."]
        });
        if (param != 'member_card') {
            $.ajax({
                url: laroute.route('admin.order.list-add'),
                data: {
                    object_type: param
                },
                method: 'POST',
                dataType: "JSON",
                success: function (res) {
                    $('#list-product').empty();
                    $('#list-product').append(res);
                }
            });
        } else {
            var id = $('#customer_id').val();
            $.ajax({
                url: laroute.route('admin.order.check-card-customer'),
                dataType: 'JSON',
                method: 'POST',
                data: {
                    id: id
                },
                success: function (res) {
                    mApp.unblock("#m_blockui_1_content");
                    $('.append').empty();
                    $.map(res.data, function (a) {
                        var tpl = $('#list-card-tpl').html();
                        tpl = tpl.replace(/{card_name}/g, a.card_name);
                        tpl = tpl.replace(/{card_code}/g, a.card_code);
                        tpl = tpl.replace(/{id_card}/g, a.customer_service_card_id);
                        if (a.image != null) {
                            tpl = tpl.replace(/{img}/g, a.image);
                        } else {
                            tpl = tpl.replace(/{img}/g, '/static/backend/images/default-placeholder.png');
                        }

                        if (a.count_using != 0) {
                            if (a.count_using !=  order.jsonLang['Không giới hạn']) {
                                tpl = tpl.replace(/{quantity}/g,  order.jsonLang['Còn '] + a.count_using);
                                tpl = tpl.replace(/{quantity_app}/g, a.count_using);
                            } else {
                                tpl = tpl.replace(/{quantity}/g, 'KGH');
                                tpl = tpl.replace(/{quantity_app}/g, a.count_using);
                            }
                        } else {
                            tpl = tpl.replace(/{quantity}/g, 'KGH');
                            tpl = tpl.replace(/{quantity_app}/g,  order.jsonLang['Không giới hạn']);
                        }

                        $('.append').append(tpl);
                        $.each($('#table_add tbody tr'), function () {
                            var codeHidden = $(this).find("input[name='object_code']");
                            var value_code = codeHidden.val();
                            var code = a.card_code;
                            if (value_code == code) {
                                var quantity = $(this).find("input[name='quantity']").val();
                                var quantity_card = $('.card_check_' + a.customer_service_card_id + '').find('.quantity_card').val();
                                if (quantity_card !=  order.jsonLang['Không giới hạn']) {
                                    $('.card_check_' + a.customer_service_card_id + '').find('.quantity_card').val(quantity_card - quantity);
                                    $('.card_check_' + a.customer_service_card_id + '').find('.quantity').empty();
                                    $('.card_check_' + a.customer_service_card_id + '').find('.quantity').append( order.jsonLang['Còn '] + $('.card_check_' + a.customer_service_card_id + '').find('.quantity_card').val() + ' (lần)');
                                }
                            }
                        });
                    });


                }
            });
        }

    },
    append_table: function (product_id,id, price, type, name, code, isSurcharge, inventoryManagement) {

        var check = true;
        if (check == true) {
            stt_tr++;
            var loc = price.replace(new RegExp('\\,', 'g'), '');
            if (inventoryManagement == 'serial') {
                var tpl = $('#table-tpl-serial').html();
            } else {
                var tpl = $('#table-tpl').html();
            }

            tpl = tpl.replace(/{numberRow}/g, numberRow);
            tpl = tpl.replace(/{stt}/g, stt_tr);
            tpl = tpl.replace(/{name}/g, name);
            tpl = tpl.replace(/{id}/g, id);
            tpl = tpl.replace(/{product_id}/g, product_id);
            if (type == 'service') {
                tpl = tpl.replace(/{type}/g, order.jsonLang['Dịch vụ']);
                tpl = tpl.replace(/{id_type}/g, '1');
            }
            if (type == 'service_card') {
                tpl = tpl.replace(/{type}/g, order.jsonLang['Thẻ dịch']);
                tpl = tpl.replace(/{id_type}/g, '2');
            }
            if (type == 'product') {
                tpl = tpl.replace(/{type}/g, order.jsonLang['Sản phẩm']);
                tpl = tpl.replace(/{id_type}/g, '3');
            }
            tpl = tpl.replace(/{code}/g, code);
            tpl = tpl.replace(/{type_hidden}/g, type);
            tpl = tpl.replace(/{price}/g, (price));

            if (parseInt(isSurcharge) == 1) {
                //DV phụ thu
                tpl = tpl.replace(/{isSurcharge}/g, 'disabled');
                tpl = tpl.replace(/{is_change_price}/g, 1);
                tpl = tpl.replace(/{is_check_promotion}/g, 0);
                tpl = tpl.replace(/{price_hidden}/g, price);
            } else {
                tpl = tpl.replace(/{isSurcharge}/g, '');
                tpl = tpl.replace(/{is_change_price}/g, 0);
                tpl = tpl.replace(/{is_check_promotion}/g, 1);
                tpl = tpl.replace(/{price_hidden}/g, loc);
            }

            tpl = tpl.replace(/{amount}/g, (price));
            tpl = tpl.replace(/{amount_hidden}/g, loc);
            tpl = tpl.replace(/{quantity_hid}/g, 0);
            if (type != 'member_card') {
                tpl = tpl.replace(/{class}/g, 'abc');
            } else {
                tpl = tpl.replace(/{class}/g, 'abc_member_card');
            }
            $('#table_add > tbody').append(tpl);

            if (parseInt(isSurcharge) == 1) {
                //Dịch vụ phụ thu
                $('#discount_' + stt_tr + '').remove();
                $('#amount_not_surcharge_' + stt_tr + '').remove();
            } else {
                //Ko phải dv phụ thu
                $('#amount_surcharge_' + stt_tr + '').remove();
            }

            $('.staff').select2({
                placeholder: order.jsonLang['Chọn nhân viên']
            });

            if (inventoryManagement == 'serial') {
                order.changeSelectSearch(id, code, numberRow);
            }

            new AutoNumeric.multiple('#amount_' + stt_tr + '', {
                currencySymbol: '',
                decimalCharacter: '.',
                digitGroupSeparator: ',',
                decimalPlaces: decimal_number,
                minimumValue: 0
            });


            $('.none').css('display', 'block');
            $('.append_bill').empty();
            var tpl_bill = $('#bill-tpl').html();
            var sum = 0;
            $.each($('#table_add > tbody').find('input[name="amount"]'), function () {
                sum += Number($(this).val().replace(new RegExp('\\,', 'g'), ''));
            });

            tpl_bill = tpl_bill.replace(/{total_bill_label}/g, formatNumber(sum.toFixed(decimal_number)) + order.jsonLang['đ']);
            tpl_bill = tpl_bill.replace(/{total_bill}/g, sum.toFixed(decimal_number));
            $('.append_bill').prepend(tpl_bill);
            $('.amount_bill').empty();
            $('.tag_a').remove();
            //$('.discount_bill').append('<a href="javascript:void(0)" onclick="order.modal_discount_bill(' + sum + ')" class="tag_a m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary m--margin-left-50"><i class="la la-plus"></i></a>');
            var discount_bill = $('#discount_bill').val();
            $('.close').remove();
            if (discount_bill != 0) {
                $('.discount_bill').prepend('<a class="tag_a" href="javascript:void(0)" onclick="order.close_discount_bill(' + sum.toFixed(decimal_number) + ')"><i class="la la-close cl_amount_bill"></i></a>');
            } else {
                $('.discount_bill').prepend('<a href="javascript:void(0)" onclick="order.modal_discount_bill(' + sum.toFixed(decimal_number) + ')" class="tag_a"><i class="fa fa-plus-circle icon-sz m--margin-right-5" style="color: #4fc4cb;"></i></a>');
            }

            var delivery_fee = $('#delivery_fee').val();

            var amount_bill = (sum - discount_bill) + parseInt(delivery_fee);
            if (amount_bill < 0) {
                amount_bill = 0;
            }
            $('.amount_bill').append(formatNumber(amount_bill.toFixed(decimal_number)) + ' ' + order.jsonLang['đ']);
            $('.amount_bill').append('<input type="hidden" name="amount_bill_input" class="form-control amount_bill_input" value="' + amount_bill.toFixed(decimal_number) + '">');
            numberRow++;
            order.getPromotionGift();
        }
        if (inventoryManagement == 'serial') {
            if (decimalsQuantity == 0){
                $(".quantity").TouchSpin({
                    initval: 1,
                    min: 0,
                    buttondown_class: "btn btn-metal btn-sm",
                    buttonup_class: "btn btn-metal btn-sm"
                });
            } else {
                $(".quantity").TouchSpin({
                    initval: 1,
                    min: 0,
                    decimals: decimalsQuantity,
                    forcestepdivisibility: 'none',
                    buttondown_class: "btn btn-metal btn-sm",
                    buttonup_class: "btn btn-metal btn-sm"
                });
            }

        } else {
            if (decimalsQuantity == 0){
                $(".quantity").TouchSpin({
                    initval: 1,
                    min: 1,
                    buttondown_class: "btn btn-metal btn-sm",
                    buttonup_class: "btn btn-metal btn-sm"
                });
            } else {
                $(".quantity").TouchSpin({
                    initval: 1,
                    min: 1,
                    decimals: decimalsQuantity,
                    forcestepdivisibility: 'none',
                    buttondown_class: "btn btn-metal btn-sm",
                    buttonup_class: "btn btn-metal btn-sm"
                });
            }

        }

        // $('.discount').mask('000,000,000', {reverse: true});
        $('.quantity').change(function () {
            $(this).closest('.tr_table').find('.amount-tr').empty();
            var id = $(this).closest('.tr_table').find('input[name="id"]').val();
            var type = $(this).closest('.tr_table').find('input[name="object_type"]').val();
            var stt = $(this).attr('data-id');
            var id_type = "";
            if (type === "service") {
                id_type = 1;
            } else if (type === "service_card") {
                id_type = 2;
            } else {
                id_type = 3;
            }
            var price = $(this).closest('.tr_table').find('input[name="price"]').val();
            var discount = 0;
            var quantity = $(this).val();
            //Tính lại thành tiền
            var amount = ((price * quantity) - discount);
            $(this).closest('.tr_table').find('.amount-tr').append(formatNumber(amount.toFixed(decimal_number)) + order.jsonLang['đ']);
            $(this).closest('.tr_table').find('.amount-tr').append('<input type="hidden" name="amount" class="form-control amount" value="' + amount.toFixed(decimal_number) + '">');
            //Update lại discount trên từng dòng
            $(".discount-tr-" + type + "-" + stt + "").empty();
            $(".discount-tr-" + type + "-" + stt + "").append('<input type="hidden" name="discount" value="0">');
            $(".discount-tr-" + type + "-" + stt + "").append('<input type="hidden" name="voucher_code" value="">');
            $(".discount-tr-" + type + "-" + stt + "").append('<a class="abc m-btn m-btn--pill m-btn--hover-brand-od btn btn-sm btn-secondary btn-sm-cus" href="javascript:void(0)" onclick="order.modal_discount(' + amount.toFixed(decimal_number) + ',' + id + ',' + id_type + ',' + stt + ')"><i class="la la-plus icon-sz"></i></a>');
            //End update

            //Tổng bill
            $('.total_bill').empty();
            var sum = 0;
            $.each($('#table_add > tbody').find('input[name="amount"]'), function () {
                sum += Number($(this).val().replace(new RegExp('\\,', 'g'), ''));
            });
            $('.total_bill').append(formatNumber(sum.toFixed(decimal_number)) + order.jsonLang['đ']);
            $('.total_bill').append(' <input type="hidden" name="total_bill" class="form-control total_bill" value="' + sum.toFixed(decimal_number) + '">');
            $('.tag_a').remove();
            //Thay đổi số lượng update lại giảm giá (tổng bill)
            $('.discount_bill').empty();
            $('.discount_bill').append(0 + order.jsonLang['đ']);
            $('.discount_bill').append('<input type="hidden" id="discount_bill" name="discount_bill" value="' + 0 + '">');
            $('.discount_bill').append('<input type="hidden" id="voucher_code_bill" name="voucher_code_bill" value="">');
            $('.discount_bill').prepend('<a href="javascript:void(0)" onclick="order.modal_discount_bill(' + sum.toFixed(decimal_number) + ')" class="tag_a"><i class="fa fa-plus-circle icon-sz m--margin-right-5" style="color: #4fc4cb;"></i></a>');
            //end update
            $('.amount_bill').append();
            $('.amount_bill').empty();

            var discount_bill = $('input[name="discount_bill"]').val();

            var delivery_fee = $('#delivery_fee').val();

            var amount_bill = (sum - discount_bill) + parseInt(delivery_fee);
            if (amount_bill < 0) {
                amount_bill = 0;
            }
            $('.amount_bill').append(formatNumber(amount_bill.toFixed(decimal_number)) + order.jsonLang['đ']);
            $('.amount_bill').append('<input type="hidden" name="amount_bill_input" class="form-control amount_bill_input" value="' + amount_bill.toFixed(decimal_number) + '">');
            $(this).closest('.tr_table').find('.abc').remove();
            if (discount != 0) {
                $(this).closest('.tr_table').find('.discount-tr-' + type + '-' + stt + '').prepend('<a class="abc" href="javascript:void(0)" onclick="order.close_amount(' + id + ',' + id_type + ',' + stt + ')"><i class="la la-close cl_amount" ></i></a>');
            } else {
                $(this).closest('.tr_table').find('.discount-tr-' + type + '-' + stt + '').append('<a class="abc btn ss--button-cms-piospa m-btn m-btn--icon m-btn--icon-only" href="javascript:void(0)" onclick="order.modal_discount(' + amount.toFixed(decimal_number) + ',' + id + ',' + id_type + ',' + stt + ')"><i class="la la-plus icon-sz"></i></a>');
            }

            order.getPromotionGift();
            discountCustomerInput();
        });
        $('.remove').click(function () {
            // var totalAfterDiscountMember = $('.amount_bill_input').val() - $('#member_level_discount').val();
            $(this).closest('.tr_table').remove();
            var product_id = $(this).closest('.tr_table').find('input[name="product_id"]').val();
            var numberRowRemove = $(this).closest('.tr_table').find('#numberRow').val();

            $.ajax({
                url: laroute.route('fnb.orders.remove-session-product'),
                method: 'POST',
                data: {
                    product_id: product_id,
                    numberRowRemove : numberRowRemove
                },
                success: function (res) {

                }
            });

            $('.tr_table_child_' + numberRowRemove).remove();
            $('.total_bill').empty();
            var sum = 0;
            $.each($('#table_add > tbody').find('input[name="amount"]'), function () {
                sum += Number($(this).val().replace(new RegExp('\\,', 'g'), ''));
            });
            $('.total_bill').append(formatNumber(sum.toFixed(decimal_number)) + order.jsonLang['đ']);
            $('.total_bill').append(' <input type="hidden" name="total_bill" class="form-control total_bill" value="' + sum.toFixed(decimal_number) + '">');
            $('.discount_bill').empty();
            $('.discount_bill').append(0 + order.jsonLang['đ']);
            $('.discount_bill').append('<input type="hidden" id="discount_bill" name="discount_bill" value="' + 0 + '">');
            $('.discount_bill').append('<input type="hidden" id="voucher_code_bill" name="voucher_code_bill" value="">');
            $('.discount_bill').prepend('<a href="javascript:void(0)" onclick="order.modal_discount_bill(' + sum.toFixed(decimal_number) + ')" class="tag_a"><i class="fa fa-plus-circle icon-sz m--margin-right-5" style="color: #4fc4cb;"></i></a>');
            $('.amount_bill').empty();
            // var discount_bill = $('#discount_bill').val();
            var amount_bill = sum.toFixed(decimal_number);

            var delivery_fee = $('#delivery_fee').val();

            var totalAfterDiscountMember = amount_bill - $('#member_level_discount').val() + parseInt(delivery_fee);
            $('.amount_bill').append(formatNumber(totalAfterDiscountMember.toFixed(decimal_number)) + order.jsonLang['đ']);
            $('.amount_bill').append('<input type="hidden" name="amount_bill_input" class="form-control amount_bill_input" value="' + totalAfterDiscountMember.toFixed(decimal_number) + '">');

            order.getPromotionGift();
            discountCustomerInput();
        });

        $('.amount').change(function () {
            $('.total_bill').empty();
            var sum = 0;
            $.each($('#table_add > tbody').find('input[name="amount"]'), function () {
                sum += Number($(this).val().replace(new RegExp('\\,', 'g'), ''));
            });
            $('.total_bill').append(formatNumber(sum.toFixed(decimal_number)) + order.jsonLang['đ']);
            $('.total_bill').append(' <input type="hidden" name="total_bill" class="form-control total_bill" value="' + sum.toFixed(decimal_number) + '">');
            $('.tag_a').remove();
            //$('.discount_bill').append('<a href="javascript:void(0)" onclick="order.modal_discount_bill(' + sum + ')" class="tag_a m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary m--margin-left-50"><i class="la la-plus"></i></a>');
            $('.amount_bill').append();
            $('.amount_bill').empty();
            var discount_bill = $('#discount_bill').val();
            $('.close').remove();
            if (discount_bill != 0) {
                $('.discount_bill').prepend('<a  class="tag_a" href="javascript:void(0)" onclick="order.close_discount_bill(' + sum.toFixed(decimal_number) + ')"><i class="la la-close cl_amount_bill"></i></a>');
            } else {
                $('.discount_bill').prepend('<a href="javascript:void(0)" onclick="order.modal_discount_bill(' + sum.toFixed(decimal_number) + ')" class="tag_a"><i class="fa fa-plus-circle icon-sz m--margin-right-5" style="color: #4fc4cb;"></i></a>');
            }

            var delivery_fee = $('#delivery_fee').val();

            var amount_bill = (sum - discount_bill) + parseInt(delivery_fee);
            if (amount_bill < 0) {
                amount_bill = 0;
            }
            $('.amount_bill').append(formatNumber(amount_bill.toFixed(decimal_number)) + order.jsonLang['đ']);
            $('.amount_bill').append('<input type="hidden" name="amount_bill_input" class="form-control amount_bill_input" value="' + amount_bill.toFixed(decimal_number) + '">');
            $(this).closest('.tr_table').find('.abc').remove();

            order.getPromotionGift();
        });

        discountCustomerInput();

        order.selectTopping(id,$('.tr_table').length - 1);
    },
    append_table_card: function (id, price, type, name, quantity_using, code, e) {
        if (quantity_using !=  order.jsonLang['Không giới hạn']) {
            var check = true;
            $.each($('#table_add tbody tr'), function () {
                let codeHidden = $(this).find("input[name='object_code']");
                let value_id = codeHidden.val();

                let code_card = code;
                if (value_id == code_card) {
                    check = false;
                    var count_using = $(e).find('.card_check_' + id + '').find('.quantity_card').val();
                    if (count_using > 0) {
                        $(e).find('.card_check_' + id + '').find('.quantity_card').val(count_using - 1);
                        $(e).find('.card_check_' + id + '').find('.quantity').empty();
                        $(e).find('.card_check_' + id + '').find('.quantity').append( order.jsonLang['Còn '] + $(e).find('.card_check_' + id + '').find('.quantity_card').val() + ' (lần)');
                        let quantitySv = codeHidden.parents('tr').find('input[name="quantity"]').val();
                        let numbers = parseInt(quantitySv) + 1;
                        codeHidden.parents('tr').find('input[name="quantity"]').val(numbers);
                    }
                }
            });
            if (check == true) {
                var stt = $('#table_add tr').length;
                var loc = price.replace(/\D+/g, '');
                var tpl = $('#table-card-tpl').html();
                // tpl = tpl.replace(/{stt}/g, stt);
                tpl = tpl.replace(/{name}/g,  order.jsonLang['Sử dụng thẻ '] + name);
                tpl = tpl.replace(/{id}/g, id);
                tpl = tpl.replace(/{type_hidden}/g, type);
                tpl = tpl.replace(/{price}/g, price);
                tpl = tpl.replace(/{price_hidden}/g, loc);
                tpl = tpl.replace(/{amount}/g, price);
                tpl = tpl.replace(/{amount_hidden}/g, loc);
                tpl = tpl.replace(/{amount_hidden}/g, loc);
                tpl = tpl.replace(/{quantity_hid}/g, quantity_using);
                tpl = tpl.replace(/{code}/g, code);
                if (type != 'member_card') {
                    tpl = tpl.replace(/{class}/g, 'abc');
                } else {
                    tpl = tpl.replace(/{class}/g, 'abc_member_card');
                }
                $('#table_add > tbody').append(tpl);
                $('.staff').select2({
                    placeholder:  order.jsonLang['Chọn nhân viên']
                });
                if (type == 'member_card') {
                    $('.abc_member_card ').remove();
                }
                var count_using = $(e).find('.card_check_' + id + '').find('.quantity_card').val();
                $(e).find('.card_check_' + id + '').find('.quantity_card').val(count_using - 1);
                $(e).find('.card_check_' + id + '').find('.quantity').empty();
                $(e).find('.card_check_' + id + '').find('.quantity').append( order.jsonLang['Còn '] + $(e).find('.card_check_' + id + '').find('.quantity_card').val() + ' (lần)');

            }

            $(".quantity_c").TouchSpin({
                initval: 1,
                min: 1,
                max: quantity_using,
                buttondown_class: "btn btn-metal btn-sm",
                buttonup_class: "btn btn-metal btn-sm"
            });
            $('.quantity_c').change(function () {
                var quan_val = $(this).val();
                var quan_db = $(this).closest('.table_add').find("input[name='quantity_hid']").val();
                var id = $(this).closest('.table_add').find("input[name='id']").val();
                $('.card_check_' + id + '').find('.quantity_card').val(quan_db - quan_val);
                $('.card_check_' + id + '').find('.quantity').empty();
                $('.card_check_' + id + '').find('.quantity').append( order.jsonLang['Còn '] + $('.card_check_' + id + '').find('.quantity_card').val() + ' (lần)');
            });
            $('.remove_card_new').click(function () {
                var quan_db = $(this).closest('.table_add').find("input[name='quantity_hid']").val();
                var id = $(this).closest('.table_add').find("input[name='id']").val();
                $('.card_check_' + id + '').find('.quantity_card').val(quan_db);
                $('.card_check_' + id + '').find('.quantity').empty();
                $('.card_check_' + id + '').find('.quantity').append( order.jsonLang['Còn '] + $('.card_check_' + id + '').find('.quantity_card').val() + ' (lần)');
                $(this).closest('.table_add').remove();
            });
        } else {
            var check = true;
            $.each($('#table_add tbody tr'), function () {
                let codeHidden = $(this).find("input[name='object_code']");
                let value_id = codeHidden.val();
                let code_card = code;
                if (value_id == code_card) {
                    check = false;
                    let quantitySv = codeHidden.parents('tr').find('input[name="quantity"]').val();
                    let numbers = parseInt(quantitySv) + 1;
                    codeHidden.parents('tr').find('input[name="quantity"]').val(numbers);
                }
            });
            if (check == true) {
                var stt = $('#table_add tr').length;
                var loc = price.replace(/\D+/g, '');
                var tpl = $('#table-card-tpl').html();
                // tpl = tpl.replace(/{stt}/g, stt);
                tpl = tpl.replace(/{name}/g,  order.jsonLang['Sử dụng thẻ '] + name);
                tpl = tpl.replace(/{id}/g, id);
                tpl = tpl.replace(/{type_hidden}/g, type);
                tpl = tpl.replace(/{price}/g, price);
                tpl = tpl.replace(/{price_hidden}/g, loc);
                tpl = tpl.replace(/{amount}/g, price);
                tpl = tpl.replace(/{amount_hidden}/g, loc);
                tpl = tpl.replace(/{amount_hidden}/g, loc);
                tpl = tpl.replace(/{quantity_hid}/g, 0);
                tpl = tpl.replace(/{code}/g, code);
                if (type != 'member_card') {
                    tpl = tpl.replace(/{class}/g, 'abc');
                } else {
                    tpl = tpl.replace(/{class}/g, 'abc_member_card');
                }
                $('#table_add > tbody').append(tpl);
                if (type == 'member_card') {
                    $('.abc_member_card ').remove();
                }

            }

            $(".quantity_c").TouchSpin({
                initval: 1,
                min: 1,
                buttondown_class: "btn btn-metal btn-sm",
                buttonup_class: "btn btn-metal btn-sm"
            });
            $('.remove_card_new').click(function () {
                $(this).closest('.table_add').remove();
            });
        }

    },
    print: function (code) {
        var stt_image = $('.toimg.' + code + '').find('input[name="stt"]').val();
        var base = $('.canvas').find('#' + stt_image + '').attr('src');
        $.ajax({
            url: laroute.route('admin.order.print-card-one'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                base: base
            }, success: function (res) {
                $('.content-print-card').empty();
                $('.content-print-card').append(res);
                jQuery('#print-card').print()
            }
        });
    },
    print_all: function () {
        var list_image = [];
        $.each($('.canvas'), function () {
            var $tds = $(this).find(".img-canvas");
            $.each($tds, function () {
                list_image.push($(this).attr('src'));
            });
        });
        $.ajax({
            url: laroute.route('admin.order.print-card-all'),
            method: "POST",
            data: {
                list_image: list_image
            },
            success: function (res) {
                $('.content-print-card').empty();
                $('.content-print-card').append(res);
                jQuery('#print-card').print();
            }

        });
    },
    send_mail: function () {
        var customer_id = $('#customer_id').val();
        if (customer_id == 1) {
            $('#modal-enter-email').modal('show');
        } else {
            $.ajax({
                url: laroute.route('admin.order.check-email-customer'),
                dataTye: 'JSON',
                method: 'POST',
                data: {
                    customer_id: customer_id
                }, success: function (res) {
                    if (res.email_null == 1) {
                        $('#modal-enter-email').modal('show');
                    }
                    if (res.email_success == 1) {
                        $('#enter_email').val(res.email).attr('disabled', true);
                        $('#modal-enter-email').modal('show');
                    }
                }
            });
        }
    },
    submit_send_email: function () {

        $('#submit_email').validate({
            rules: {
                enter_email: {
                    required: true,
                    email: true
                },
            },
            messages: {
                enter_email: {
                    required: order.jsonLang['Hãy nhập email'],
                    email: order.jsonLang['Email không hợp lệ']
                },
            },
            submitHandler: function () {
                ///list image
                var list_image = [];
                $.each($('.canvas'), function () {
                    var $tds = $(this).find(".img-canvas");
                    $.each($tds, function () {
                        list_image.push($(this).attr('src'));
                    });
                });
                $.ajax({
                    url: laroute.route('admin.order.submit-send-email'),
                    dataType: 'JSON',
                    method: 'POST',
                    data: {
                        email: $('#enter_email').val(),
                        customer_id: $('#customer_id').val(),
                        list_image: list_image
                    }, success: function (res) {
                        if (res.success == 1) {
                            swal(order.jsonLang["Gửi email thành công"], "", "success");
                            $('#modal-enter-email').modal('hide');
                        }
                    }
                });
            }
        });

    },
    changeAmountReceipt: function (obj) {
        // UPDATE 15/03/2021
        // tính tổng tiền trả
        let total = 0
        $.each($('.payment_method').find('.method'), function () {
            let moneyEachMethod = $(this).find("input[name='payment_method']").val().replace(new RegExp('\\,', 'g'), '');
            total += Number(moneyEachMethod);
        });
        // END UPDATE 15/03/2021
        var amount_all = total;

        $('#amount_all').val(formatNumber(amount_all.toFixed(decimal_number)));
        $('.cl_amount_all').text(formatNumber(amount_all.toFixed(decimal_number)));

        var rest = $('#receipt_amount').val().replace(new RegExp('\\,', 'g'), '');
        if (rest - amount_all > 0) {
            $('#amount_rest').val(formatNumber((rest - amount_all).toFixed(decimal_number)));
            $('.cl_amount_rest').text(formatNumber((rest - amount_all).toFixed(decimal_number)));
            if ($(obj).val() == '') {
                if (rest - amount_all < 0) {
                    $('#amount_return').val(formatNumber((amount_all - rest).toFixed(decimal_number)));
                    $('.cl_amount_return').text(formatNumber((amount_all - rest).toFixed(decimal_number)));
                } else {
                    $('#amount_return').val(0);
                    $('.cl_amount_return').text(0);
                }
            }
        } else {
            $('#amount_rest').val(0);
            $('#amount_return').val(formatNumber((amount_all - rest).toFixed(decimal_number)));
            $('.cl_amount_rest').text(0);
            $('.cl_amount_return').text(formatNumber((amount_all - rest).toFixed(decimal_number)));
        }
        discountCustomerInput();
    },
    getPromotionGift: function () {
        //Lấy total quantity sp, dv, thẻ dv
        var arrParam = [];
        $.each($('#table_add').find('.tr_table, .table_add'), function () {
            var objectType = $(this).find("input[name='object_type']").val();
            var objectCode = $(this).find("input[name='object_code']").val();
            var quantity = $(this).find("input[name='quantity']").val();

            arrParam.push({
                objectType: objectType,
                objectCode: objectCode,
                quantity: quantity
            });
        });

        //Check promotion gift
        $.ajax({
            url: laroute.route('admin.order.check-gift'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                customer_id: $('#customer_id').val(),
                arrParam: arrParam
            },
            async: false,
            success: function (res) {
                $('.promotion_gift').remove();
                if (res.gift > 0) {
                    $.map(res.arr_gift, function (a) {
                        stt_tr++;
                        var zero = 0;
                        var tpl = $('#table-gift-tpl').html();
                        tpl = tpl.replace(/{stt}/g, stt_tr);
                        tpl = tpl.replace(/{name}/g, a.gift_object_name + ' (' +  order.jsonLang['quà tặng'] + ')');
                        tpl = tpl.replace(/{id}/g, a.gift_object_id);
                        tpl = tpl.replace(/{code}/g, a.gift_object_code);
                        tpl = tpl.replace(/{type_hidden}/g, a.gift_object_type + '_gift');
                        tpl = tpl.replace(/{price}/g, zero.toFixed(decimal_number));
                        tpl = tpl.replace(/{price_hidden}/g, zero.toFixed(decimal_number));
                        tpl = tpl.replace(/{amount}/g, zero.toFixed(decimal_number));
                        tpl = tpl.replace(/{amount_hidden}/g, zero.toFixed(decimal_number));
                        tpl = tpl.replace(/{quantity}/g, a.quantity_gift);
                        tpl = tpl.replace(/{quantity_hid}/g, zero.toFixed(decimal_number));
                        $('#table_add > tbody').append(tpl);
                        $('.staff').select2({
                            placeholder:  order.jsonLang['Chọn nhân viên']
                        });
                    });
                }
            }
        });
    },
    removeGift: function (obj) {
        $(obj).closest('.tr_table').remove();
    },

    changeSelectSearch : function(id,productCode,numberRow){
        $('.input_child_'+numberRow).select2({
            placeholder:  order.jsonLang["Nhập số serial và enter"],
            ajax: {
                url:laroute.route('admin.order.getListSerial'),
                data: function (params) {
                    return {
                        productCode: productCode,
                        numberRow: numberRow,
                        session : $('#session').val(),
                        page: params.page || 1,
                    };
                },
                method: "POST",
                dataType: 'json',
                processResults: function (data) {
                    data.page = data.current_page || 1;
                    return {
                        results: data.data.map(function (item) {
                            return {
                                id: item.serial,
                                text: item.serial
                            };
                        }),
                        pagination: {
                            more: data.current_page + 1
                        }
                    };
                },
            }
        }).on("select2:select", function (e) {
            order.enterSerial(id,numberRow);
        })
    },
    enterSerial : function(id,numberRow){
        // if(e.keyCode == 13){
        $.ajax({
            url: laroute.route('admin.order.checkSerialEnter'),
            data: {
                product_code : $('.object_code_'+id).val(),
                serial : $('.input_child_'+numberRow).val(),
                session : $('#session').val(),
                id : id,
                numberRow : numberRow
            },
            method: 'POST',
            dataType: "JSON",
            success: function (res) {
                if(res.error == false){
                    $('.td_vtc_'+numberRow+' .quantity').trigger("touchspin.uponce");
                    $('.tr_table_child_'+numberRow).html(res.view);
                    if(res.total_serial != 0){
                        $('.td_vtc_'+numberRow+' .quantity').val(res.total_serial).trigger('change');
                    }
                    order.changeSelectSearch(id,$('.object_code_'+id).val(),numberRow);
                } else {
                    swal(res.message, "", "error");
                }
            }
        })
        // }
    },

    showPopupSerial : function(session,id,productCode,position){
        $.ajax({
            url: laroute.route('admin.order.showPopupSerial'),
            data: {
                product_code : productCode,
                session : session,
                id : id,
                numberRow : position
            },
            method: 'POST',
            dataType: "JSON",
            success: function (res) {
                if(res.error == false){
                    $('#showPopup').html(res.view);
                    $('#popup-list-serial').modal('show');
                } else {
                    swal(res.message, "", "error");
                }
            }
        })
    },

    searchItem : function(){
        order.loadProduct();
    },

    searchSerial : function(){
        $.ajax({
            url: laroute.route('admin.order.searchSerial'),
            data: $('#form-list-serial').serialize(),
            method: 'POST',
            dataType: "JSON",
            success: function (res) {
                if(res.error == false){
                    $('.block-list-serial').html(res.view);
                } else {
                    swal(res.message, "", "error");
                }
            }
        })
    },

    changePageSerial: function(page){
        $('#page_serial').val(page);
        order.searchSerial();
    },

    removeSearchSerial : function(){
        $('.page_serial').val(page);
        $('#serial_search').val('');
        order.searchSerial();
    },

    removeSerial: function(session,id,productCode,position,serial,popup = false){

        swal({
            title:  order.jsonLang['Xoá serial'],
            text:  order.jsonLang["Bạn có muốn xóa không?"],
            type: 'warning',
            showCancelButton: true,
            confirmButtonText:  order.jsonLang['Xóa'],
            cancelButtonText:  order.jsonLang['Hủy'],

        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: laroute.route('admin.order.removeSerial'),
                    data: {
                        product_code : productCode,
                        serial : serial,
                        session : session,
                        id : id,
                        numberRow : position
                    },
                    method: 'POST',
                    dataType: "JSON",
                    success: function (res) {
                        if(res.error == false){
                            if(popup == true){
                                order.searchSerial();
                            }
                            $('.td_vtc_'+position+' .quantity').trigger("touchspin.downonce");
                            $('.tr_table_child_'+position).html(res.view);
                            if (res.total_serial != 0){
                                $('.td_vtc_'+position+' .quantity').val(res.total_serial).trigger('change');
                            }
                        } else {
                            swal(res.message, "", "error");
                        }
                    }
                })
            }
        })
    },
};

var list = {
    remove: function (obj, id) {
        $(obj).closest('tr').addClass('m-table__row--danger');

        swal({
            title:  order.jsonLang['Thông báo'],
            text:  order.jsonLang["Bạn có muốn xóa không?"],
            type: 'warning',
            showCancelButton: true,
            confirmButtonText:  order.jsonLang['Xóa'],
            cancelButtonText:  order.jsonLang['Hủy'],
            onClose: function () {
                // remove hightlight row
                $(obj).closest('tr').removeClass('m-table__row--danger');
            }
        }).then(function (result) {
            if (result.value) {
                $.post(laroute.route('admin.order.remove', {id: id}), function () {
                    swal(
                        order.jsonLang['Xóa thành công'],
                        '',
                        'success'
                    );
                    // window.location.reload();
                    $('#autotable').PioTable('refresh');
                });
            }
        });
    },
    refresh: function () {
        $('input[name="search"]').val('');
        $(".btn-search").trigger("click");
    },
    search: function () {

        var id = $('#customer_id').val();
        var type = $('.type').find('.active').attr('data-name');
        // var type_load = $('.type').find('.active').attr('data-name');

        var search = $('#search').val();
        mApp.block("#m_blockui_1_content", {
            overlayColor: "#000000",
            type: "loader",
            state: "success",
            message:  order.jsonLang["Đang tải..."]
        });
        $.ajax({
            url: laroute.route('admin.order.search'),
            data: {
                type: type,
                search: search,
                id: id
            },
            method: 'POST',
            dataType: "JSON",
            success: function (response) {
                if (response.type != 'member_card') {
                    mApp.unblock("#m_blockui_1_content");
                    $('.append').empty();
                    $.map(response.list, function (a) {
                        var tpl = $('#list-tpl').html();
                        tpl = tpl.replace(/{name}/g, a.name);
                        tpl = tpl.replace(/{price}/g, a.price);
                        tpl = tpl.replace(/{id}/g, a.id);
                        if (a.avatar !== null && a.avatar !== '') {
                            tpl = tpl.replace(/{img}/g, a.avatar);
                        } else {
                            tpl = tpl.replace(/{img}/g, '/static/backend/images/default-placeholder.png');
                        }
                        tpl = tpl.replace(/{price_hidden}/g, a.price);
                        tpl = tpl.replace(/{type}/g, a.type);
                        tpl = tpl.replace(/{is_surcharge}/g, a.is_surcharge);
                        $('.append').append(tpl);
                    });
                } else {
                    mApp.unblock("#m_blockui_1_content");
                    $('.append').empty();
                    $.map(response.list, function (a) {
                        var tpl = $('#list-card-tpl').html();
                        tpl = tpl.replace(/{card_name}/g, a.card_name);
                        tpl = tpl.replace(/{card_code}/g, a.card_code);
                        tpl = tpl.replace(/{id_card}/g, a.customer_service_card_id);
                        if (a.image !== null && a.image !== '') {
                            tpl = tpl.replace(/{img}/g, a.image);
                        } else {
                            tpl = tpl.replace(/{img}/g, '/static/backend/images/default-placeholder.png');
                        }
                        if (a.count_using != 0) {
                            tpl = tpl.replace(/{quantity}/g,  order.jsonLang['Còn '] + a.count_using);
                            tpl = tpl.replace(/{quantity_app}/g, a.count_using);
                        } else {
                            tpl = tpl.replace(/{quantity}/g,  order.jsonLang['Không giới hạn']);
                            tpl = tpl.replace(/{quantity_app}/g,  order.jsonLang['Không giới hạn']);
                        }
                        $('.append').append(tpl);
                        $.each($('#table_add tbody tr'), function () {
                            var codeHidden = $(this).find("input[name='object_code']");
                            var value_code = codeHidden.val();
                            var code = a.card_code;
                            if (value_code == code) {
                                var quantity = $(this).find("input[name='quantity']").val();
                                var quantity_card = $('.card_check_' + a.customer_service_card_id + '').find('.quantity_card').val();
                                if (quantity_card !=  order.jsonLang['Không giới hạn']) {
                                    $('.card_check_' + a.customer_service_card_id + '').find('.quantity_card').val(quantity_card - quantity);
                                    $('.card_check_' + a.customer_service_card_id + '').find('.quantity').empty();
                                    $('.card_check_' + a.customer_service_card_id + '').find('.quantity').append( order.jsonLang['Còn '] + $('.card_check_' + a.customer_service_card_id + '').find('.quantity_card').val() + ' (lần)');
                                }
                            }
                        });
                    });
                }

            }
        })
    },
    close_amount: function (id, id_type, stt) {

        var type_class = "";
        if (id_type == 1) {
            type_class = "service";
        } else if (id_type == 2) {
            type_class = "service_card";
        } else {
            type_class = "product";
        }
        $(".discount-tr-" + type_class + "-" + stt + "").empty();
        var price = $(".discount-tr-" + type_class + "-" + stt + "").closest('.tr_table').find('input[name="price"]').val();
        var quantity = $(".discount-tr-" + type_class + "-" + stt + "").closest('.tr_table').find('input[name="quantity"]').val();
        var discount_new = 0;
        var amount_new = (price * quantity - discount_new);
        $(".discount-tr-" + type_class + "-" + stt + "").closest('.tr_table').find('.amount-tr').empty();
        $(".discount-tr-" + type_class + "-" + stt + "").closest('.tr_table').find('.amount-tr').append(formatNumber(amount_new.toFixed(decimal_number)) +  order.jsonLang['đ']);
        $(".discount-tr-" + type_class + "-" + stt + "").closest('.tr_table').find('.amount-tr').append('<input type="hidden" name="amount" class="form-control amount" value="' + amount_new.toFixed(decimal_number) + '">');
        $(".discount-tr-" + type_class + "-" + stt + "").append('<input type="hidden" name="discount" value="0">');
        $(".discount-tr-" + type_class + "-" + stt + "").append('<input type="hidden" name="voucher_code" value="">');
        $(".discount-tr-" + type_class + "-" + stt + "").append('<a class="abc btn ss--button-cms-piospa m-btn m-btn--icon m-btn--icon-only" href="javascript:void(0)" onclick="list.modal_discount(' + amount_new.toFixed(decimal_number) + ',' + id + ',' + id_type + ',' + stt + ')"><i class="la la-plus icon-sz"></i></a>');
        $('.append_bill').empty();
        var sum = 0;
        $.each($('#table_add > tbody').find('input[name="amount"]'), function () {
            sum += Number($(this).val());
        });
        $('.append_bill').append(formatNumber(sum.toFixed(decimal_number)) +  order.jsonLang['đ']);
        $('.append_bill').append(' <input type="hidden" name="total_bill" class="form-control total_bill" value="' + sum.toFixed(decimal_number) + '">');
        $('.tag_a').remove();
        //$('.discount_bill').append('<a href="javascript:void(0)" onclick="order.modal_discount_bill(' + sum + ')" class="tag_a m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary m--margin-left-50"><i class="la la-plus"></i></a>');
        $('.amount_bill').append();
        $('.amount_bill').empty();
        var discount_bill = $('#discount_bill').val();
        $('.close').remove();
        if (discount_bill != 0) {
            $('.discount_bill').prepend('<a  class="tag_a" href="javascript:void(0)" onclick="list.close_discount_bill(' + sum.toFixed(decimal_number) + ')"><i class="la la-close cl_amount_bill m--margin-right-5"></i></a>');
        } else {
            $('.discount_bill').prepend('<a href="javascript:void(0)" onclick="list.modal_discount_bill(' + sum.toFixed(decimal_number) + ')" class="tag_a"><i class="fa fa-plus-circle icon-sz m--margin-right-5" style="color: #4fc4cb;"></i></a>');
        }
        var delivery_fee = $('#delivery_fee').val();

        var amount_bill = (sum - discount_bill) + parseInt(delivery_fee);
        $('.amount_bill').append(formatNumber(amount_bill.toFixed(decimal_number)) +  order.jsonLang['đ']);
        $('.amount_bill').append('<input type="hidden" name="amount_bill_input" class="form-control amount_bill_input" value="' + amount_bill.toFixed(decimal_number) + '">');

        discountCustomerInput();
    },
    modal_discount: function (amount, id, id_type, stt) {
        $('#modal-discount').modal('show');
        $('#amount-tb').val(amount);
        $('#discount-modal').val(0);
        $('#discount-code-modal').val('');
        $('.error-discount1').text('');
        $('.error-discount').text('');
        $('.error_discount_code').text('');
        $('.error_discount_expired').text('');
        $('.error_discount_not_using').text('');
        $('.error_discount_amount_error').text('');
        $('.error_discount_null').text('');
        $('.branch_not').text('');
        $('.btn-click').empty();
        // $('.btn-click').append('<button type="button" onclick="list.discount(' + id + ',' + id_type + ')" class="btn btn-primary">Áp dụng</button>');
        // $('.btn-click').append('<input type="button" data-dismiss="modal"  class="btn btn-default" value="Hủy">');
        var tpl = $('#button-discount-tpl').html();
        tpl = tpl.replace(/{id}/g, id);
        tpl = tpl.replace(/{id_type}/g, id_type);
        tpl = tpl.replace(/{stt}/g, stt);
        $('.btn-click').append(tpl);
        $('#live-tag').click(function () {
            $('#discount-code-modal').val('');
            $('.error_discount_code').text('');
            $('.error_discount_expired').text('');
            $('.error_discount_not_using').text('');
            $('.error_discount_amount_error').text('');
            $('.error_discount_null').text('');
        });
        $('#code-tag').click(function () {
            $('#discount-modal').val(0);
            $('.error-discount1').text('');
            $('.error-discount').text('');
        });
    },
    discount: function (id, id_type, stt) {

        var amount = $('#amount-tb').val();
        var discount = $('#discount-modal').val().replace(new RegExp('\\,', 'g'), '');
        var type_discount = $("input[name='type-discount']:checked").val();
        var voucher_code = $('#discount-code-modal').val();
        var type_class = "";
        var amount_bill = $('input[name="total_bill"]').val();
        var total_using_voucher = 0;

        $("input[name='voucher_code']").each(function (val) {
            var value = $(this).val();
            if (value === voucher_code.trim()) {
                total_using_voucher++;
            }
        });

        if (id_type == 1) {
            type_class = "service";
        } else if (id_type == 2) {
            type_class = "service_card";
        } else {
            type_class = "product";
        }
        $.ajax({
            url: laroute.route('admin.order.add-discount'),
            data: {
                amount: amount,
                discount: discount,
                type: type_discount,
                voucher_code: voucher_code,
                type_order: type_class,
                id_order: id,
                amount_bill: amount_bill,
                total_using_voucher: total_using_voucher,
                customer_id: $('#customer_id').val()
            },
            async: false,
            method: 'POST',
            dataType: "JSON",
            success: function (response) {
                if (response.error_money === 1) {
                    $('.error-discount1').text( order.jsonLang['Số tiền giảm giá không hợp lệ']);
                } else {
                    $('.error-discount1').text('');
                }
                if (response.error_money === 0) {
                    $('#modal-discount').modal('hide');
                    $(".discount-tr-" + type_class + "-" + stt + "").empty();
                    $(".discount-tr-" + type_class + "-" + stt + "").prepend(formatNumber(discount) +  order.jsonLang['đ']);
                    $(".discount-tr-" + type_class + "-" + stt + "").append('<input type="hidden" name="discount" class="form-control discount" value="' + discount + '">');
                    $(".discount-tr-" + type_class + "-" + stt + "").append('<input type="hidden" name="voucher_code" value="" >');
                    var price = $(".discount-tr-" + type_class + "-" + stt + "").closest('.tr_table').find('input[name="price"]').val();
                    var quantity = $(".discount-tr-" + type_class + "-" + stt + "").closest('.tr_table').find('input[name="quantity"]').val();
                    var discount_new = $(".discount-tr-" + type_class + "-" + stt + "").closest('.tr_table').find('input[name="discount"]').val();
                    var amount_new = (price * quantity - discount_new);

                    $(".discount-tr-" + type_class + "-" + stt + "").closest('.tr_table').find('.amount-tr').empty();
                    $(".discount-tr-" + type_class + "-" + stt + "").closest('.tr_table').find('.amount-tr').append(formatNumber(amount_new.toFixed(decimal_number)) +  order.jsonLang['đ']);
                    $(".discount-tr-" + type_class + "-" + stt + "").closest('.tr_table').find('.amount-tr').append('<input type="hidden" name="amount" class="form-control amount" value="' + amount_new.toFixed(decimal_number) + '">');
                    $(".discount-tr-" + type_class + "-" + stt + "").prepend('<a class="abc"  href="javascript:void(0)" onclick="list.close_amount(' + id + ',' + id_type + ',' + stt + ')"><i class="la la-close cl_amount"></i></a>');
                    $('.total_bill').empty();
                    var sum = 0;
                    $.each($('#table_add > tbody').find('input[name="amount"]'), function () {
                        sum += Number($(this).val().replace(new RegExp('\\,', 'g'), ''));
                    });

                    $('.total_bill').append(formatNumber(sum.toFixed(decimal_number)) +  order.jsonLang['đ']);
                    $('.total_bill').append(' <input type="hidden" name="total_bill" class="form-control total_bill" value="' + sum.toFixed(decimal_number) + '">');
                    $('.tag_a').remove();
                    //$('.discount_bill').append('<a href="javascript:void(0)" onclick="order.modal_discount_bill(' + sum + ')" class="tag_a m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary m--margin-left-50"><i class="la la-plus"></i></a>');
                    $('.amount_bill').append();
                    $('.amount_bill').empty();
                    var discount_bill = $('#discount_bill').val();
                    $('.close').remove();
                    if (discount_bill != 0) {
                        $('.discount_bill').prepend('<a class="tag_a" href="javascript:void(0)" onclick="list.close_discount_bill(' + sum.toFixed(decimal_number) + ')"><i class="la la-close cl_amount_bill"></i></a>');
                    } else {
                        $('.discount_bill').prepend('<a href="javascript:void(0)" onclick="list.modal_discount_bill(' + sum.toFixed(decimal_number) + ')" class="tag_a"><i class="fa fa-plus-circle icon-sz m--margin-right-5" style="color: #4fc4cb;"></i></a>');
                    }
                    var delivery_fee = $('#delivery_fee').val();

                    var amount_bill = (sum - discount_bill) + parseInt(delivery_fee);
                    $('.amount_bill').append(formatNumber(amount_bill.toFixed(decimal_number)) +  order.jsonLang['đ']);
                    $('.amount_bill').append('<input type="hidden" name="amount_bill_input" class="form-control amount_bill_input" value="' + amount_bill.toFixed(decimal_number) + '">');
                    //Xóa giảm giá tổng bill
                    ////Thêm giảm giá từng dòng thì xóa giảm giá tổng bill.
                    list.close_discount_bill($('input[name=total_bill]').val());
                }
                if (response.error_percent === 1) {
                    $('.error-discount').text( order.jsonLang['Số tiền giảm giá không hợp lệ']);
                } else {
                    $('.error-discount').text('');
                }
                if (response.error_percent === 0) {
                    $('#modal-discount').modal('hide');
                    $(".discount-tr-" + type_class + "-" + stt + "").empty();
                    $(".discount-tr-" + type_class + "-" + stt + "").prepend(formatNumber(response.discount_percent) +  order.jsonLang['đ']);
                    $(".discount-tr-" + type_class + "-" + stt + "").append('<input type="hidden" name="discount" class="form-control discount" value="' + response.discount_percent + '">');
                    $(".discount-tr-" + type_class + "-" + stt + "").append('<input type="hidden" name="voucher_code" value="" >');
                    var price = $(".discount-tr-" + type_class + "-" + stt + "").closest('.tr_table').find('input[name="price"]').val();
                    var quantity = $(".discount-tr-" + type_class + "-" + stt + "").closest('.tr_table').find('input[name="quantity"]').val();
                    var discount_new = $(".discount-tr-" + type_class + "-" + stt + "").closest('.tr_table').find('input[name="discount"]').val();
                    var amount_new = (price * quantity - discount_new);

                    $(".discount-tr-" + type_class + "-" + stt + "").closest('.tr_table').find('.amount-tr').empty();
                    $(".discount-tr-" + type_class + "-" + stt + "").closest('.tr_table').find('.amount-tr').append(formatNumber(amount_new.toFixed(decimal_number)) +  order.jsonLang['đ']);
                    $(".discount-tr-" + type_class + "-" + stt + "").closest('.tr_table').find('.amount-tr').append('<input type="hidden" name="amount" class="form-control amount" value="' + amount_new.toFixed(decimal_number) + '">');
                    $(".discount-tr-" + type_class + "-" + stt + "").prepend('<a class="abc" href="javascript:void(0)" style="color:red" onclick="list.close_amount(' + id + ',' + id_type + ',' + stt + ')"><i class="la la-close cl_amount"></i></a>');
                    $('.total_bill').empty();
                    var sum = 0;
                    $.each($('#table_add > tbody').find('input[name="amount"]'), function () {
                        sum += Number($(this).val().replace(new RegExp('\\,', 'g'), ''));
                    });
                    $('.total_bill').append(formatNumber(sum.toFixed(decimal_number)) +  order.jsonLang['đ']);
                    $('.total_bill').append(' <input type="hidden" name="total_bill" class="form-control total_bill" value="' + sum.toFixed(decimal_number) + '">');
                    $('.tag_a').remove();
                    //$('.discount_bill').append('<a href="javascript:void(0)" onclick="order.modal_discount_bill(' + sum + ')" class="tag_a m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary m--margin-left-50"><i class="la la-plus"></i></a>');
                    $('.amount_bill').append();
                    $('.amount_bill').empty();
                    var discount_bill = $('#discount_bill').val();
                    $('.close').remove();
                    if (discount_bill != 0) {
                        $('.discount_bill').prepend('<a  class="tag_a" href="javascript:void(0)" onclick="order.close_discount_bill(' + sum.toFixed(decimal_number) + ')"><i class="la la-close cl_amount_bill"></i></a>');
                    } else {
                        $('.discount_bill').prepend('<a href="javascript:void(0)" onclick="order.modal_discount_bill(' + sum.toFixed(decimal_number) + ')" class="tag_a"><i class="fa fa-plus-circle icon-sz m--margin-right-5" style="color: #4fc4cb;"></i></a>');
                    }
                    var delivery_fee = $('#delivery_fee').val();

                    var amount_bill = (sum - discount_bill) + parseInt(delivery_fee);
                    $('.amount_bill').append(formatNumber(amount_bill.toFixed(decimal_number)) +  order.jsonLang['đ']);
                    $('.amount_bill').append('<input type="hidden" name="amount_bill_input" class="form-control amount_bill_input" value="' + amount_bill.toFixed(decimal_number) + '">');
                    //Xóa giảm giá tổng bill
                    ////Thêm giảm giá từng dòng thì xóa giảm giá tổng bill.
                    list.close_discount_bill($('input[name=total_bill]').val());
                }
                if (response.voucher_null === 1) {
                    $('.error_discount_null').text( order.jsonLang['Mã giảm giá không tồn tại']);
                } else {
                    $('.error_discount_null').text('');
                }
                if (response.voucher_not_exist == 1) {
                    $('.error_discount_code').text( order.jsonLang['Mã giảm giá không tồn tại']);
                } else {
                    $('.error_discount_code').text('');
                }
                if (response.voucher_expired == 1) {
                    $('.error_discount_expired').text( order.jsonLang['Mã giảm giá hết hạn sử dụng']);
                } else {
                    $('.error_discount_expired').text('');
                }
                if (response.voucher_not_using == 1) {
                    $('.error_discount_not_using').text( order.jsonLang['Mã giảm giá đã hết số lần sử dụng']);
                } else {
                    $('.error_discount_not_using').text('');
                }
                if (response.voucher_amount_error == 1) {
                    $('.error_discount_amount_error').text( order.jsonLang['Tổng tiền không đủ sử dụng mã giảm giá']);
                } else {
                    $('.error_discount_amount_error').text('');
                }
                if (response.branch_not == 1) {
                    $('.branch_not').text( order.jsonLang['Mã giảm giá không sử dụng cho chi nhánh này']);
                } else {
                    $('.branch_not').text('');
                }
                if (response.voucher_doesnt_use_guest || response.voucher_max_using_by_customer) {
                    $('.branch_not').text(response.message);
                } else {
                    $('.branch_not').text('');
                }
                if (response.voucher_success == 1) {
                    if(response.number_using != -1){
                        if($(`[name="voucher_code"][value="${response.voucher_name}"]`).length >= response.number_using){
                            $('.branch_not').text( order.jsonLang['Mã giảm giá đã hết số lần sử dụng đối với khách hàng này']);
                            return;
                        }
                        else{
                            $('.branch_not').text('');
                        }
                    }
                    $('#modal-discount').modal('hide');
                    $(".discount-tr-" + type_class + "-" + stt + "").empty();
                    $(".discount-tr-" + type_class + "-" + stt + "").prepend(formatNumber(response.discount_voucher) +  order.jsonLang['đ']);
                    $(".discount-tr-" + type_class + "-" + stt + "").append('<input type="hidden" name="discount" class="form-control discount" value="' + response.discount_voucher + '">');
                    $(".discount-tr-" + type_class + "-" + stt + "").append('<input type="hidden" name="voucher_code" value="' + response.voucher_name + '" >')
                    var price = $(".discount-tr-" + type_class + "-" + stt + "").closest('.tr_table').find('input[name="price"]').val();
                    var quantity = $(".discount-tr-" + type_class + "-" + stt + "").closest('.tr_table').find('input[name="quantity"]').val();
                    var discount_new = $(".discount-tr-" + type_class + "-" + stt + "").closest('.tr_table').find('input[name="discount"]').val();
                    var amount_new = (price * quantity - discount_new);
                    if (amount_new < 0) {
                        amount_new = 0;
                    }
                    $(".discount-tr-" + type_class + "-" + stt + "").closest('.tr_table').find('.amount-tr').empty();
                    $(".discount-tr-" + type_class + "-" + stt + "").closest('.tr_table').find('.amount-tr').append(formatNumber(amount_new.toFixed(decimal_number)) +  order.jsonLang['đ']);
                    $(".discount-tr-" + type_class + "-" + stt + "").closest('.tr_table').find('.amount-tr').append('<input type="hidden" name="amount" class="form-control amount" value="' + amount_new.toFixed(decimal_number) + '">');
                    $(".discount-tr-" + type_class + "-" + stt + "").prepend('<a class="abc" style="color:red" href="javascript:void(0)" onclick="list.close_amount(' + id + ',' + id_type + ',' + stt + ')"><i class="la la-close cl_amount"></i></a>');
                    $('.total_bill').empty();
                    var sum = 0;
                    $.each($('#table_add > tbody').find('input[name="amount"]'), function () {
                        sum += Number($(this).val().replace(new RegExp('\\,', 'g'), ''));
                    });
                    $('.total_bill').append(formatNumber(sum.toFixed(decimal_number)) +  order.jsonLang['đ']);
                    $('.total_bill').append(' <input type="hidden" name="total_bill" class="form-control total_bill" value="' + sum.toFixed(decimal_number) + '">');
                    $('.tag_a').remove();
                    //$('.discount_bill').append('<a href="javascript:void(0)" onclick="order.modal_discount_bill(' + sum + ')" class="tag_a m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary m--margin-left-50"><i class="la la-plus"></i></a>');
                    $('.amount_bill').append();
                    $('.amount_bill').empty();
                    var discount_bill = $('#discount_bill').val();
                    $('.close').remove();
                    if (discount_bill != 0) {
                        $('.discount_bill').prepend('<a  class="close" href="javascript:void(0)" onclick="order.close_discount_bill(' + sum.toFixed(decimal_number) + ')"><i class="la la-close cl_amount_bill"></i></a>');
                    } else {
                        $('.discount_bill').prepend('<a href="javascript:void(0)" onclick="order.modal_discount_bill(' + sum.toFixed(decimal_number) + ')" class="tag_a"><i class="fa fa-plus-circle icon-sz m--margin-right-5" style="color: #4fc4cb;"></i></a>');
                    }
                    var delivery_fee = $('#delivery_fee').val();

                    var amount_bill = (sum - discount_bill) + parseInt(delivery_fee);
                    $('.amount_bill').append(formatNumber(amount_bill.toFixed(decimal_number)) +  order.jsonLang['đ']);
                    $('.amount_bill').append('<input type="hidden" name="amount_bill_input" class="form-control amount_bill_input" value="' + amount_bill.toFixed(decimal_number) + '">');
                    //Xóa giảm giá tổng bill
                    ////Thêm giảm giá từng dòng thì xóa giảm giá tổng bill.
                    order.close_discount_bill($('input[name=total_bill]').val());
                }
            }
        });
        discountCustomerInput();
    },
    modal_discount_add: function (amount, id, id_type, stt) {
        $('#modal-discount').modal('show');
        $('#amount-tb').val(amount);
        $('#discount-modal').val(0);
        $('#discount-code-modal').val('');
        $('.error-discount1').text('');
        $('.error-discount').text('');
        $('.error_discount_code').text('');
        $('.error_discount_expired').text('');
        $('.error_discount_not_using').text('');
        $('.error_discount_amount_error').text('');
        $('.error_discount_null').text('');
        $('.branch_not').text('');
        $('.btn-click').empty();
        // $('.btn-click').append('<button type="button" onclick="list.discount_add(' + id + ',' + id_type + ')" class="btn btn-primary">Áp dụng</button>');
        // $('.btn-click').append('<input type="button" data-dismiss="modal"  class="btn btn-default" value="Hủy">');
        var tpl = $('#button-discount-add-tpl').html();
        tpl = tpl.replace(/{id}/g, id);
        tpl = tpl.replace(/{id_type}/g, id_type);
        tpl = tpl.replace(/{stt}/g, stt);
        $('.btn-click').append(tpl);
        $('#live-tag').click(function () {
            $('#discount-code-modal').val('');
            $('.error_discount_code').text('');
            $('.error_discount_expired').text('');
            $('.error_discount_not_using').text('');
            $('.error_discount_amount_error').text('');
            $('.error_discount_null').text('');
        });
        $('#code-tag').click(function () {
            $('#discount-modal').val(0);
            $('.error-discount1').text('');
            $('.error-discount').text('');
        });
    },
    discount_add: function (id, id_type, stt) {

        var amount = $('#amount-tb').val();
        var discount = $('#discount-modal').val().replace(new RegExp('\\,', 'g'), '');
        var type_discount = $("input[name='type-discount']:checked").val();
        var voucher_code = $('#discount-code-modal').val();
        var amount_bill = $('input[name="total_bill"]').val();
        var total_using_voucher = 0;
        $("input[name='voucher_code']").each(function (val) {
            var value = $(this).val();
            if (value === voucher_code.trim()) {
                total_using_voucher++;
            }
        });
        var type_class = "";
        if (id_type == 1) {
            type_class = "service";
        } else if (id_type == 2) {
            type_class = "service_card";
        } else {
            type_class = "product";
        }
        $.ajax({
            url: laroute.route('admin.order.add-discount'),
            data: {
                amount: amount,
                discount: discount,
                type: type_discount,
                voucher_code: voucher_code,
                type_order: type_class,
                id_order: id,
                amount_bill: amount_bill,
                total_using_voucher: total_using_voucher,
                customer_id: $('#customer_id').val()
            },
            method: 'POST',
            dataType: "JSON",
            success: function (response) {
                if (response.error_money === 1) {
                    $('.error-discount1').text( order.jsonLang['Số tiền giảm giá không hợp lệ']);
                } else {
                    $('.error-discount1').text('');
                }
                if (response.error_money === 0) {
                    $('#modal-discount').modal('hide');
                    $(".discount-tr-" + type_class + "-" + stt + "").empty();
                    $(".discount-tr-" + type_class + "-" + stt + "").prepend(formatNumber(discount) +  order.jsonLang['đ']);
                    $(".discount-tr-" + type_class + "-" + stt + "").append('<input type="hidden" name="discount" class="form-control discount" value="' + discount + '">');
                    $(".discount-tr-" + type_class + "-" + stt + "").append('<input type="hidden" name="voucher_code" value="" >');
                    var price = $(".discount-tr-" + type_class + "-" + stt + "").closest('.table_add').find('input[name="price"]').val();
                    var quantity = $(".discount-tr-" + type_class + "-" + stt + "").closest('.table_add').find('input[name="quantity"]').val();
                    var discount_new = $(".discount-tr-" + type_class + "-" + stt + "").closest('.table_add').find('input[name="discount"]').val();
                    var amount_new = parseInt(price * quantity - discount_new);
                    $(".discount-tr-" + type_class + "-" + stt + "").closest('.table_add').find('.amount-tr').empty();
                    $(".discount-tr-" + type_class + "-" + stt + "").closest('.table_add').find('.amount-tr').append(formatNumber(amount_new.toFixed(decimal_number)));
                    $(".discount-tr-" + type_class + "-" + stt + "").closest('.table_add').find('.amount-tr').append('<input type="hidden" name="amount" class="form-control amount" value="' + amount_new.toFixed(decimal_number) + '">');
                    $(".discount-tr-" + type_class + "-" + stt + "").prepend('<a class="abc" href="javascript:void(0)" onclick="list.close_amount_add(' + id + ',' + id_type + ',' + stt + ')"><i class="la la-close cl_amount"></i></a>');
                    $('.append_bill').empty();
                    var sum = 0;
                    $.each($('#table_add > tbody').find('input[name="amount"]'), function () {
                        sum += Number($(this).val());
                    });
                    $('.append_bill').append(formatNumber(sum.toFixed(decimal_number)) +  order.jsonLang['đ']);
                    $('.append_bill').append(' <input type="hidden" name="total_bill" class="form-control total_bill" value="' + sum.toFixed(decimal_number) + '">');
                    $('.tag_a').remove();
                    //$('.discount_bill').append('<a href="javascript:void(0)" onclick="order.modal_discount_bill(' + sum + ')" class="tag_a m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary m--margin-left-50"><i class="la la-plus"></i></a>');
                    $('.amount_bill').append();
                    $('.amount_bill').empty();
                    var discount_bill = $('#discount_bill').val();
                    $('.close').remove();
                    if (discount_bill != 0) {
                        $('.discount_bill').prepend('<a  class="tag_a" href="javascript:void(0)" onclick="list.close_discount_bill(' + sum.toFixed(decimal_number) + ')"><i class="la la-close cl_amount_bill m--margin-right-5"></i></a>');
                    } else {
                        $('.discount_bill').prepend('<a href="javascript:void(0)" onclick="list.modal_discount_bill(' + sum.toFixed(decimal_number) + ')" class="tag_a"><i class="fa fa-plus-circle icon-sz m--margin-right-5" style="color: #4fc4cb;"></i></a>');
                    }
                    var delivery_fee = $('#delivery_fee').val();

                    var amount_bill = (sum - discount_bill) + parseInt(delivery_fee);
                    $('.amount_bill').append(formatNumber(amount_bill.toFixed(decimal_number)) +  order.jsonLang['đ']);
                    $('.amount_bill').append('<input type="hidden" name="amount_bill_input" class="form-control amount_bill_input" value="' + amount_bill.toFixed(decimal_number) + '">');
                }
                if (response.error_percent === 1) {
                    $('.error-discount').text( order.jsonLang['Số tiền giảm giá không hợp lệ']);
                } else {
                    $('.error-discount').text('');
                }
                if (response.error_percent === 0) {
                    $('#modal-discount').modal('hide');
                    $(".discount-tr-" + type_class + "-" + stt + "").empty();
                    $(".discount-tr-" + type_class + "-" + stt + "").prepend(formatNumber(response.discount_percent) +  order.jsonLang['đ']);
                    $(".discount-tr-" + type_class + "-" + stt + "").append('<input type="hidden" name="discount" class="form-control discount" value="' + response.discount_percent + '">');
                    $(".discount-tr-" + type_class + "-" + stt + "").append('<input type="hidden" name="voucher_code" value="" >');
                    var price = $(".discount-tr-" + type_class + "-" + stt + "").closest('.table_add').find('input[name="price"]').val();
                    var quantity = $(".discount-tr-" + type_class + "-" + stt + "").closest('.table_add').find('input[name="quantity"]').val();
                    var discount_new = $(".discount-tr-" + type_class + "-" + stt + "").closest('.table_add').find('input[name="discount"]').val();
                    var amount_new = (price * quantity - discount_new);
                    $(".discount-tr-" + type_class + "-" + stt + "").closest('.table_add').find('.amount-tr').empty();
                    $(".discount-tr-" + type_class + "-" + stt + "").closest('.table_add').find('.amount-tr').append(formatNumber(amount_new.toFixed(decimal_number)));
                    $(".discount-tr-" + type_class + "-" + stt + "").closest('.table_add').find('.amount-tr').append('<input type="hidden" name="amount" class="form-control amount" value="' + amount_new.toFixed(decimal_number) + '">');
                    $(".discount-tr-" + type_class + "-" + stt + "").prepend('<a class="abc" href="javascript:void(0)" onclick="list.close_amount_add(' + id + ',' + id_type + ',' + stt + ')"><i class="la la-close cl_amount"></i></span></a>');
                    $('.total_bill').empty();
                    var sum = 0;
                    $.each($('#table_add > tbody').find('input[name="amount"]'), function () {
                        sum += Number($(this).val());
                    });
                    $('.total_bill').append(formatNumber(sum.toFixed(decimal_number)) +  order.jsonLang['đ']);
                    $('.total_bill').append(' <input type="hidden" name="total_bill" class="form-control total_bill" value="' + sum.toFixed(decimal_number) + '">');
                    $('.tag_a').remove();
                    //$('.discount_bill').append('<a href="javascript:void(0)" onclick="order.modal_discount_bill(' + sum + ')" class="tag_a m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary m--margin-left-50"><i class="la la-plus"></i></a>');
                    $('.amount_bill').append();
                    $('.amount_bill').empty();
                    var discount_bill = $('#discount_bill').val();
                    $('.close').remove();
                    if (discount_bill != 0) {
                        $('.discount_bill').prepend('<a  class="tag_a" href="javascript:void(0)" onclick="list.close_discount_bill(' + sum.toFixed(decimal_number) + ')"><i class="la la-close cl_amount_bill m--margin-right-5"></i></a>');
                    } else {
                        $('.discount_bill').prepend('<a href="javascript:void(0)" onclick="list.modal_discount_bill(' + sum.toFixed(decimal_number) + ')" class="tag_a"><i class="fa fa-plus-circle icon-sz m--margin-right-5" style="color: #4fc4cb;"></i></a>');
                    }
                    var delivery_fee = $('#delivery_fee').val();

                    var amount_bill = (sum - discount_bill) + parseInt(delivery_fee);
                    $('.amount_bill').append(formatNumber(amount_bill.toFixed(decimal_number)) +  order.jsonLang['đ']);
                    $('.amount_bill').append('<input type="hidden" name="amount_bill_input" class="form-control amount_bill_input" value="' + amount_bill.toFixed(decimal_number) + '">');
                }
                if (response.voucher_null === 1) {
                    $('.error_discount_null').text( order.jsonLang['Mã giảm giá không tồn tại']);
                } else {
                    $('.error_discount_null').text('');
                }
                if (response.voucher_not_exist == 1) {
                    $('.error_discount_code').text( order.jsonLang['Mã giảm giá không tồn tại']);
                } else {
                    $('.error_discount_code').text('');
                }
                if (response.voucher_expired == 1) {
                    $('.error_discount_expired').text( order.jsonLang['Mã giảm giá hết hạn sử dụng']);
                } else {
                    $('.error_discount_expired').text('');
                }
                if (response.voucher_not_using == 1) {
                    $('.error_discount_not_using').text( order.jsonLang['Mã giảm giá đã hết số lần sử dụng']);
                } else {
                    $('.error_discount_not_using').text('');
                }
                if (response.voucher_amount_error == 1) {
                    $('.error_discount_amount_error').text( order.jsonLang['Tổng tiền không đủ sử dụng mã giảm giá']);
                } else {
                    $('.error_discount_amount_error').text('');
                }
                if (response.branch_not == 1) {
                    $('.branch_not').text( order.jsonLang['Mã giảm giá không sử dụng cho chi nhánh này']);
                } else {
                    $('.branch_not').text('');
                }

                if (response.voucher_doesnt_use_guest || response.voucher_max_using_by_customer) {
                    $('.branch_not').text(response.message);
                } else {
                    $('.branch_not').text('');
                }
                if (response.voucher_success == 1) {
                    if(response.number_using != -1){
                        if($(`[name="voucher_code"][value="${response.voucher_name}"]`).length >= response.number_using){
                            $('.branch_not').text( order.jsonLang['Mã giảm giá đã hết số lần sử dụng đối với khách hàng này']);
                            return;
                        }
                        else{
                            number_using_voucher++;
                            $('.branch_not').text('');
                        }
                    }
                    $('#modal-discount').modal('hide');
                    $(".discount-tr-" + type_class + "-" + stt + "").empty();
                    $(".discount-tr-" + type_class + "-" + stt + "").prepend(formatNumber(response.discount_voucher) +  order.jsonLang['đ']);
                    $(".discount-tr-" + type_class + "-" + stt + "").append('<input type="hidden" name="discount" class="form-control discount" value="' + response.discount_voucher + '">');
                    $(".discount-tr-" + type_class + "-" + stt + "").append('<input type="hidden" name="voucher_code" value="' + response.voucher_name + '" >')
                    var price = $(".discount-tr-" + type_class + "-" + stt + "").closest('.table_add').find('input[name="price"]').val();
                    var quantity = $(".discount-tr-" + type_class + "-" + stt + "").closest('.table_add').find('input[name="quantity"]').val();
                    var discount_new = $(".discount-tr-" + type_class + "-" + stt + "").closest('.table_add').find('input[name="discount"]').val();
                    var amount_new = (price * quantity - discount_new);
                    if (amount_new < 0) {
                        amount_new = 0;
                    }
                    $(".discount-tr-" + type_class + "-" + stt + "").closest('.table_add').find('.amount-tr').empty();
                    $(".discount-tr-" + type_class + "-" + stt + "").closest('.table_add').find('.amount-tr').append(formatNumber(amount_new.toFixed(decimal_number)) +  order.jsonLang['đ']);
                    $(".discount-tr-" + type_class + "-" + stt + "").closest('.table_add').find('.amount-tr').append('<input type="hidden" name="amount" class="form-control amount" value="' + amount_new.toFixed(decimal_number) + '">');
                    $(".discount-tr-" + type_class + "-" + stt + "").prepend('<a class="abc" href="javascript:void(0)" onclick="list.close_amount_add(' + id + ',' + id_type + ',' + stt + ')"><i class="la la-close cl_amount"></i></a>');
                    $('.total_bill').empty();
                    var sum = 0;
                    $.each($('#table_add > tbody').find('input[name="amount"]'), function () {
                        sum += Number($(this).val());
                    });
                    $('.total_bill').append(formatNumber(sum.toFixed(decimal_number)) +  order.jsonLang['đ']);
                    $('.total_bill').append(' <input type="hidden" name="total_bill" class="form-control total_bill" value="' + sum.toFixed(decimal_number) + '">');
                    $('.tag_a').remove();
                    //$('.discount_bill').append('<a href="javascript:void(0)" onclick="order.modal_discount_bill(' + sum + ')" class="tag_a m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary m--margin-left-50"><i class="la la-plus"></i></a>');
                    $('.amount_bill').append();
                    $('.amount_bill').empty();
                    var discount_bill = $('#discount_bill').val();
                    $('.close').remove();
                    if (discount_bill != 0) {
                        $('.discount_bill').prepend('<a  class="tag_a" href="javascript:void(0)" onclick="list.close_discount_bill(' + sum.toFixed(decimal_number) + ')"><i class="la la-close cl_amount_bill m--margin-right-5"></i></a>');
                    } else {
                        $('.discount_bill').prepend('<a href="javascript:void(0)" onclick="list.modal_discount_bill(' + sum.toFixed(decimal_number) + ')" class="tag_a"><i class="fa fa-plus-circle icon-sz m--margin-right-5" style="color: #4fc4cb;"></i></a>');
                    }
                    var delivery_fee = $('#delivery_fee').val();

                    var amount_bill = (sum - discount_bill) + parseInt(delivery_fee);
                    $('.amount_bill').append(formatNumber(amount_bill.toFixed(decimal_number)) +  order.jsonLang['đ']);
                    $('.amount_bill').append('<input type="hidden" name="amount_bill_input" class="form-control amount_bill_input" value="' + amount_bill.toFixed(decimal_number) + '">');
                }
            }
        })

    },
    close_amount_add: function (id, id_type, stt) {

        var type_class = "";
        if (id_type == 1) {
            type_class = "service";
        } else if (id_type == 2) {
            type_class = "service_card";
        } else {
            type_class = "product";
        }
        $(".discount-tr-" + type_class + "-" + stt + "").empty();
        var price = $(".discount-tr-" + type_class + "-" + stt + "").closest('.table_add').find('input[name="price"]').val();
        var quantity = $(".discount-tr-" + type_class + "-" + stt + "").closest('.table_add').find('input[name="quantity"]').val();
        var discount_new = 0;
        var amount_new = (price * quantity - discount_new);
        $(".discount-tr-" + type_class + "-" + stt + "").closest('.table_add').find('.amount-tr').empty();
        $(".discount-tr-" + type_class + "-" + stt + "").closest('.table_add').find('.amount-tr').append(formatNumber(amount_new.toFixed(decimal_number)) +  order.jsonLang['đ']);
        $(".discount-tr-" + type_class + "-" + stt + "").closest('.table_add').find('.amount-tr').append('<input type="hidden" name="amount" class="form-control amount" value="' + amount_new.toFixed(decimal_number) + '">');
        $(".discount-tr-" + type_class + "-" + stt + "").append('<input type="hidden" name="discount" value="0">');
        $(".discount-tr-" + type_class + "-" + stt + "").append('<input type="hidden" name="voucher_code" value="">');
        //HIEUPC1
        // $(".discount-tr-" + type_class + "-" + stt + "").append('<a class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--icon-only" href="javascript:void(0)" onclick="list.modal_discount_add(' + amount_new.toFixed(decimal_number) + ',' + id + ',' + id_type + ',' + stt + ')"><i class="la la-plus icon-sz"></i></a>');
        $('.append_bill').empty();
        var sum = 0;
        $.each($('#table_add > tbody').find('input[name="amount"]'), function () {
            sum += Number($(this).val());
        });
        $('.append_bill').append(formatNumber(sum.toFixed(decimal_number)) +  order.jsonLang['đ']);
        $('.append_bill').append(' <input type="hidden" name="total_bill" class="form-control total_bill" value="' + sum.toFixed(decimal_number) + '">');
        $('.tag_a').remove();
        //$('.discount_bill').append('<a href="javascript:void(0)" onclick="order.modal_discount_bill(' + sum + ')" class="tag_a m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary m--margin-left-50"><i class="la la-plus"></i></a>');
        $('.amount_bill').append();
        $('.amount_bill').empty();
        var discount_bill = $('#discount_bill').val();
        $('.close').remove();
        if (discount_bill != 0) {
            $('.discount_bill').prepend('<a  class="tag_a" href="javascript:void(0)" onclick="list.close_discount_bill(' + sum.toFixed(decimal_number) + ')"><i class="la la-close cl_amount_bill m--margin-right-5"></i></a>');
        } else {
            $('.discount_bill').prepend('<a href="javascript:void(0)" onclick="list.modal_discount_bill(' + sum.toFixed(decimal_number) + ')" class="tag_a"><i class="fa fa-plus-circle icon-sz m--margin-right-5" style="color: #4fc4cb;"></i></a>');
        }
        var delivery_fee = $('#delivery_fee').val();

        var amount_bill = (sum - discount_bill) + parseInt(delivery_fee);
        $('.amount_bill').append(formatNumber(amount_bill.toFixed(decimal_number)) +  order.jsonLang['đ']);
        $('.amount_bill').append('<input type="hidden" name="amount_bill_input" class="form-control amount_bill_input" value="' + amount_bill.toFixed(decimal_number) + '">');
    },
    modal_discount_bill: function (amount_bill) {
        $('#modal-discount-bill').modal('show');
        $('#amount-bill').val(amount_bill);
        $('#discount-bill').val(0);
        $('#discount-code-bill-modal').val('');
        $('.error-discount-bill').text('');
        $('.error-discount-bill-percent').text('');
        $('.error_bill_null').text('');
        $('.error_bill_expired').text('');
        $('.error_bill_amount').text('');
        $('.error_bill_not_using').text('');
        $('.branch_not').text('');
        $('#live-tag-bill').click(function () {
            $('#discount-code-bill-modal').val('');
            $('.error_bill_null').text('');
            $('.error_bill_expired').text('');
            $('.error_bill_amount').text('');
            $('.error_bill_not_using').text('');
        });
        $('#code-tag-bill').click(function () {
            $('#discount-bill').val(0);
            $('.error-discount-bill').text('');
            $('.error-discount-bill-percent').text('');
        });
        $('.btn-click-bill').empty();
        // $('.btn-click-bill').append('<button type="button" onclick="list.modal_discount_bill_click()" class="btn btn-primary">Áp dụng</button>');
        // $('.btn-click-bill').append('<button type="button" onclick="list.close_modal_discount_bill()" class="btn btn-default">Hủy</button>');
        var tpl = $('#button-discount-bill-tpl').html();
        $('.btn-click-bill').append(tpl);
        // $('#discount-bill').mask('000,000,000', {reverse: true});
    },
    close_modal_discount_bill: function () {
        $('#modal-discount-bill').modal('hide');
    },
    modal_discount_bill_click: function () {

        var total_bill = $("input[name=total_bill]").val() - $('#member_level_discount').val();
        var discount_bill = $('#discount-bill').val().replace(new RegExp('\\,', 'g'), '');
        var type_discount_bill = $("input[name='type-discount-bill']:checked").val();
        var voucher_code_bill = $('#discount-code-bill-modal').val();
        $.ajax({
            url: laroute.route('admin.order.add-discount-bill'),
            data: {
                total_bill: total_bill,
                discount_bill: discount_bill,
                type_discount_bill: type_discount_bill,
                voucher_code_bill: voucher_code_bill,
                customer_id: $('#customer_id').val()
            },
            method: 'POST',
            dataType: "JSON",
            success: function (response) {
                if (response.error_money_bill == 1) {
                    $('.error-discount-bill').text( order.jsonLang['Số tiền không hợp lệ']);
                } else {
                    $('.error-discount-bill').text('');
                }
                if (response.error_percent_bill == 1) {
                    $('.error-discount-bill-percent').text( order.jsonLang['Số tiền không hợp lệ']);
                } else {
                    $('.error-discount-bill-percent').text('');
                }
                if (response.error_money_bill == 0) {
                    $('#modal-discount-bill').modal('hide');
                    $('.discount_bill').empty();
                    $('.discount_bill').append(formatNumber(response.discount_bill) +  order.jsonLang['đ']);
                    $('.discount_bill').append('<input type="hidden" id="discount_bill" name="discount_bill" value=' + response.discount_bill + '>');
                    $('.discount_bill').append('<input type="hidden" id="voucher_code_bill" name="voucher_code_bill" value="">');
                    $('.discount_bill').prepend('<a class="tag_a" href="javascript:void(0)" onclick="list.close_discount_bill(' + total_bill + ')"><i class="la la-close cl_amount_bill m--margin-right-5"></i></a>');

                    var delivery_fee = $('#delivery_fee').val();

                    var amount_bill = (total_bill - response.discount_bill) + parseInt(delivery_fee);
                    $('.amount_bill').empty();
                    $('.amount_bill').append(formatNumber(amount_bill.toFixed(decimal_number)) +  order.jsonLang['đ']);
                    $('.amount_bill').append('<input type="hidden" name="amount_bill_input" class="form-control amount_bill_input" value=' + amount_bill.toFixed(decimal_number) + '>');
                    $('.amount_bill').append('<input type="hidden" id="voucher_code_bill" name="voucher_code_bill" value="">');
                }
                if (response.error_percent_bill == 0) {
                    $('#modal-discount-bill').modal('hide');
                    $('.discount_bill').empty();
                    $('.discount_bill').append(formatNumber(response.discount_bill) +  order.jsonLang['đ']);
                    $('.discount_bill').append('<input type="hidden" id="discount_bill" name="discount_bill" value=' + response.discount_bill + '>');
                    $('.discount_bill').append('<input type="hidden" id="voucher_code_bill" name="voucher_code_bill" value="">');
                    $('.discount_bill').prepend('<a  class="tag_a" href="javascript:void(0)" onclick="list.close_discount_bill(' + total_bill + ')"><i class="la la-close cl_amount_bill m--margin-right-5"></i></a>');

                    var delivery_fee = $('#delivery_fee').val();

                    var amount_bill = (total_bill - response.discount_bill) + parseInt(delivery_fee);
                    $('.amount_bill').empty();
                    $('.amount_bill').append(formatNumber(amount_bill.toFixed(decimal_number)) +  order.jsonLang['đ']);
                    $('.amount_bill').append('<input type="hidden" name="amount_bill_input" class="form-control amount_bill_input" value=' + amount_bill.toFixed(decimal_number) + '>');
                }

                if (response.voucher_bill_null == 1) {
                    $('.error_bill_null').text( order.jsonLang['Mã giảm giá không tồn tại']);
                } else {
                    $('.error_bill_null').text('');
                }
                if (response.voucher_bill_expired == 1) {
                    $('.error_bill_expired').text( order.jsonLang['Mã giảm giá hết hạn sử dụng']);
                } else {
                    $('.error_bill_expired').text('');
                }
                if (response.voucher_amount_bill_error == 1) {
                    $('.error_bill_amount').text( order.jsonLang['Tổng tiền không đủ để sử dụng mã giảm giá']);
                } else {
                    $('.error_bill_amount').text('');
                }
                if (response.voucher_bill_not_using == 1) {
                    $('.error_bill_not_using').text( order.jsonLang['Mã giảm giá đã hết số lần sử dụng']);
                } else {
                    $('.error_bill_not_using').text('');
                }
                if (response.branch_not == 1) {
                    $('.branch_not').text( order.jsonLang['Mã giảm giá không sử dụng cho chi nhánh này']);
                } else {
                    $('.branch_not').text('');
                }

                if (response.voucher_doesnt_use_guest || response.voucher_max_using_by_customer) {
                    $('.branch_not').text(response.message);
                } else {
                    $('.branch_not').text('');
                }

                if (response.voucher_success_bill == 1) {
                    if(response.number_using != -1){
                        if(number_using_voucher >= response.number_using){
                            $('.branch_not').text( order.jsonLang['Mã giảm giá đã hết số lần sử dụng đối với khách hàng này']);
                            return false;
                        }
                        else{
                            number_using_voucher++;
                            $('.branch_not').text('');
                        }
                    }
                    $('#modal-discount-bill').modal('hide');
                    $('.discount_bill').empty();
                    $('.discount_bill').append(formatNumber(response.discount_voucher_bill) +  order.jsonLang['đ']);
                    $('.discount_bill').append('<input type="hidden" id="discount_bill" name="discount_bill" value=' + response.discount_voucher_bill + '>');
                    $('.discount_bill').append('<input type="hidden" id="voucher_code_bill" name="voucher_code_bill" value="' + response.voucher_name_bill + '">');
                    $('.discount_bill').prepend('<a  class="tag_a" href="javascript:void(0)" onclick="list.close_discount_bill(' + total_bill + ')"><i class="la la-close cl_amount_bill m--margin-right-5"></i></a>');

                    var delivery_fee = $('#delivery_fee').val();

                    var amount_bill = (total_bill - response.discount_voucher_bill) + parseInt(delivery_fee);

                    if (amount_bill < 0) {
                        amount_bill = 0;
                    }
                    $('.amount_bill').empty();
                    $('.amount_bill').append(formatNumber(amount_bill.toFixed(decimal_number)) +  order.jsonLang['đ']);
                    $('.amount_bill').append('<input type="hidden" name="amount_bill_input" class="form-control amount_bill_input" value=' + amount_bill.toFixed(decimal_number) + '>');
                }
            }
        })
        discountCustomerInput();

    },
    close_discount_bill: function (total_bill) {
        $('#modal-discount-bill').modal('hide');
        // var total_bill = $('#amount-bill').val();
        $('.discount_bill').empty();
        $('.discount_bill').append(0 +  order.jsonLang['đ']);
        $('.discount_bill').append('<input type="hidden" name="discount_bill" id="discount_bill" value=' + 0 + '>')
        $('.discount_bill').append('<input type="hidden" id="voucher_code_bill" name="voucher_code_bill" value="">');
        $('.discount_bill').prepend('<a href="javascript:void(0)" onclick="list.modal_discount_bill(' + total_bill + ')" class="tag_a"><i class="fa fa-plus-circle icon-sz m--margin-right-5" style="color: #4fc4cb;"></i></a>');
        $('.amount_bill').empty();
        $('.amount_bill').append(formatNumber(total_bill) +  order.jsonLang['đ']);
        $('.amount_bill').append('<input type="hidden" name="amount_bill_input" class="form-control amount_bill_input" value=' + total_bill + '>');
        discountCustomerInput();
    },
    apply_branch: function (id) {
        $.ajax({
            url: laroute.route('admin.order.apply-branch'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                order_id: id
            },
            success: function (res) {
                $('#my-modal').html(res.url);
                $('#modal-apply-branch').modal('show');
                $('#branch_id').select2({
                    placeholder:  order.jsonLang['Chọn chi nhánh']
                });
            }
        });
    },
    submit_apply_branch: function (id) {

        var form = $('#form-apply-branch');

        form.validate({
            rules: {
                branch_id: {
                    required: true,
                }
            },
            messages: {
                branch_id: {
                    required:  order.jsonLang['Hãy chọn chi nhánh']
                }
            },
        });

        if (!form.valid()) {
            return false;
        }

        $.ajax({
            url: laroute.route('admin.order.submit-apply-branch'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                order_id: id,
                branch_id: $('#branch_id').val()
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                            window.location.reload();
                        }
                        if (result.value == true) {
                            window.location.reload();
                        }
                    });
                } else {
                    swal(res.message, "", "error");
                }
            },
            error: function (res) {
                var mess_error = '';
                $.map(res.responseJSON.errors, function (a) {
                    mess_error = mess_error.concat(a + '<br/>');
                });
                swal(mess_error, "", "error")
            }
        });
    }
};
var save = {
    submit_edit: function (id) {

        //Lưu thông tin cho đơn hàng mới
        var continute = true;
        var table_subbmit = [];
        $.each($('#table_add').find(".tr_table"), function () {
            // var $tds = $(this).find("input,select");
            // var $check_amount = $(this).find("input[name='amount']");
            var product_child_id = $(this).find('input[name="id"]').val();
            var product_id = $(this).find('input[name="product_id"]').val();
            var product_name = $(this).find('input[name="name"]').val();
            var object_type = $(this).find('input[name="object_type"]').val();
            var object_code = $(this).find('input[name="object_code"]').val();
            var price = $(this).find('input[name="price"]').val();
            var quantity = $(this).find('input[name="quantity"]').val();
            var discount = $(this).find('input[name="discount"]').val();
            var voucher_code = $(this).find('input[name="voucher_code"]').val();
            var amount = $(this).find('input[name="amount"]').val();
            var is_change_price = $(this).find('input[name="is_change_price"]').val();
            var is_check_promotion = $(this).find('input[name="is_check_promotion"]').val();
            var key_string = $(this).find('input[name="key_string"]').val();
            if (amount < 0) {
                $('.error-table').text( order.jsonLang['Tổng tiền không hợp lệ']);
                continute = false;
            }
            // $.each($tds, function () {
            //     table_subbmit.push($(this).val().length == 0 ? '' : $(this).val());
            // });
            table_subbmit.push({
                product_id : product_id,
                product_child_id : product_child_id,
                product_name : product_name,
                object_type : object_type,
                object_code : object_code,
                price : price,
                quantity : quantity,
                discount : discount,
                voucher_code : voucher_code,
                amount : amount,
                is_change_price : is_change_price,
                is_check_promotion : is_check_promotion,
                key_string : key_string
            })
        });

        var table_add = [];
        $.each($('#table_add').find(".table_add"), function () {
            // var $tds = $(this).find("input,select");
            // var $check_amount = $(this).find("input[name='amount']");
            // if ($check_amount.val() < 0) {
            //     $('.error-table').text( order.jsonLang['Tổng tiền không hợp lệ']);
            //     continute = false;
            // }
            // $.each($tds, function () {
            //     table_add.push($(this).val().length == 0 ? '' : $(this).val());
            // });

            var product_child_id = $(this).find('input[name="id"]').val();
            var product_id = $(this).find('input[name="product_id"]').val();
            var product_name = $(this).find('input[name="name"]').val();
            var object_type = $(this).find('input[name="object_type"]').val();
            var object_code = $(this).find('input[name="object_code"]').val();
            var price = $(this).find('input[name="price"]').val();
            var quantity = $(this).find('input[name="quantity"]').val();
            var discount = $(this).find('input[name="discount"]').val();
            var voucher_code = $(this).find('input[name="voucher_code"]').val();
            var amount = $(this).find('input[name="amount"]').val();
            var is_change_price = $(this).find('input[name="is_change_price"]').val();
            var is_check_promotion = $(this).find('input[name="is_check_promotion"]').val();
            var key_string = $(this).find('input[name="key_string"]').val();
            if (amount < 0) {
                $('.error-table').text( order.jsonLang['Tổng tiền không hợp lệ']);
                continute = false;
            }

            table_add.push({
                product_id : product_id,
                product_child_id : product_child_id,
                product_name : product_name,
                object_type : object_type,
                object_code : object_code,
                price : price,
                quantity : quantity,
                discount : discount,
                voucher_code : voucher_code,
                amount : amount,
                is_change_price : is_change_price,
                is_check_promotion : is_check_promotion,
                key_string : key_string
            })

        });
        var check_service_card = [];
        $.each($('#table_add').find("tbody tr"), function () {
            var $check_amount = $(this).find("input[name='object_type']");
            if ($check_amount.val() == 'service_card') {
                $.each($check_amount, function () {
                    check_service_card.push($(this).val());
                });
            }
        });

        if (table_subbmit == '' && table_add == '') {
            $('.error-table').text( order.jsonLang['Vui lòng chọn dịch vụ/thẻ dịch vụ/sản phẩm']);
        } else {
            $('.error-table').text('');
            var total_bill = $('input[name="total_bill"]').val();
            var discount_bill = $('input[name="discount_bill"]').val();
            var voucher_bill = $('#voucher_code_bill').val();
            var amount_bill = $('input[name="amount_bill_input"]').val();

            var delivery_active = 0;
            if ($('#delivery_active').is(':checked')) {
                delivery_active = 1;
            }

            var receipt_info_check = 0 ;
            if ($('.receipt_info_check').is(':checked')){
                receipt_info_check = 1;
            }

            $.ajax({
                url: laroute.route('fnb.orders.submit-edit'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    table_edit: table_subbmit,
                    table_add: table_add,
                    total_bill: total_bill,
                    staff_id: $('#staff_id').val(),
                    table_id: $('#table_id').val(),
                    discount_bill: discount_bill,
                    voucher_bill: voucher_bill,
                    amount_bill: amount_bill,
                    order_id: id,
                    order_code: $('#order_code').val(),
                    refer_id: $('#refer_id').val(),
                    delivery_active: delivery_active,
                    customer_id: $('#customer_id').val(),
                    custom_price: $('#custom_price').val(),
                    order_description: $('[name="order_description"]').val(),
                    sessionSerial : $('#session').val(),
                    type_time : $('#type_time_hidden').val(),
                    time_address : $('#time_address_hidden').val(),
                    customer_contact_id : $('#customer_contact_id_hidden').val(),
                    receipt_info_check: receipt_info_check,
                    tranport_charge : $('#delivery_fee').val(),
                    delivery_type : $('#delivery_type').val(),
                    delivery_cost_id : $('#delivery_cost_id').val(),
                    discount_member: $('#member_level_discount').val().replace(new RegExp('\\,', 'g'), '')
                },
                success: function (res) {
                    if (res.error == true) {
                        swal( order.jsonLang["Lưu đơn hàng thất bại"], "", "error");
                    } else {
                        swal( order.jsonLang["Lưu đơn hàng thành công"], "", "success");
                        // window.location.href = laroute.route('fnb.orders');
                        window.location.reload();
                    }
                }
            });
        }

    }
};

$('#autotable').PioTable({
    baseUrl: laroute.route('admin.order.list')
});

function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}

function loyalty(orderId) {
    var flag = true;
    $.ajax({
        url: laroute.route('admin.order.loyalty'),
        method: "POST",
        async: false,
        data: {order_id: orderId},
        success: function (res) {
        }
    });
    return flag;
}

/*
Giảm tiền theo hạng thành viên
 */
function discountCustomer(moneyTotal, pt) {
    var result = 0;
    result = moneyTotal * (pt / 100);
    return formatNumber((result).toFixed(decimal_number));
}

function discountCustomerInput() {
    //Tổng tiền
    var moneyTotal = $("input[name=total_bill]").val().replace(new RegExp('\\,', 'g'), '');
    $('#total-money-discount').val();
    //Phần trăm giảm.
    var pt = $('.pt-discount').val().replace(new RegExp('\\,', 'g'), '');
    var moneyDiscountCustomer = discountCustomer(moneyTotal, pt);
    $('.span_member_level_discount').text(formatNumber(moneyDiscountCustomer));

    $('#member_level_discount').val(moneyDiscountCustomer.replace(new RegExp('\\,', 'g'), ''));

    ////Thành tiền.
    //Tiền giảm theo m
    // Member level.
    var memberLevelDiscount = $('#member_level_discount').val().replace(new RegExp('\\,', 'g'), '');
    //Giảm giá
    var discountBill = $('#discount_bill').val().replace(new RegExp('\\,', 'g'), '');

    var delivery_fee = $('#delivery_fee').val().replace(new RegExp('\\,', 'g'), '');

    var amountBill = Number(moneyTotal) - Number(memberLevelDiscount) - Number(discountBill) + Number(delivery_fee);

    $('.amount_bill').empty();
    $('.amount_bill').append(formatNumber(amountBill.toFixed(decimal_number)) +  order.jsonLang['đ']);
    $('.amount_bill').append('<input type="hidden" name="amount_bill_input" class="form-control amount_bill_input" value="' + amountBill.toFixed(decimal_number) + '">');
}

var customerAppointment = {
    changeNumberTime: function (obj) {
        $.ajax({
            url: laroute.route('admin.customer_appointment.change-number-type'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                time_type: $('#time_type').val(),
                type_number: $('#type_number').val(),
                date: $('#date').val(),
                time: $('#time').val()
            },
            success: function (res) {
                $('#end_date').val(res.end_date);
                $('#end_time').val(res.end_time);
            }
        });
    }
}
$(document).ready(function () {
    order.jsonLang = JSON.parse(localStorage.getItem('tranlate'));;
    // $(window).on('resize', function(){
    //     var height = $(this).height();
    //     $('#lstItemOrder').height(height - 660);
    // }).trigger('resize'); //on page load
    if (decimalsQuantity == 0){
        $(".quantity").TouchSpin({
            initval: 1,
            min: 1,
            buttondown_class: "btn btn-metal btn-sm",
            buttonup_class: "btn btn-metal btn-sm"
        });
    } else {
        $(".quantity").TouchSpin({
            initval: 1,
            min: 1,
            decimals: decimalsQuantity,
            forcestepdivisibility: 'none',
            buttondown_class: "btn btn-metal btn-sm",
            buttonup_class: "btn btn-metal btn-sm"
        });
    }

    $(".quantity_card").TouchSpin({
        initval: 1,
        min: 1,
        buttondown_class: "btn btn-metal btn-sm",
        buttonup_class: "btn btn-metal btn-sm"
    });
    $('.quantity').change(function () {
        $(this).closest('.tr_table').find('.amount-tr').empty();
        var id = $(this).closest('.tr_table').find('input[name="id"]').val();
        var type = $(this).closest('.tr_table').find('input[name="object_type"]').val();
        var stt = $(this).attr('data-id');

        var id_type = "";
        if (type === "service") {
            id_type = 1;
        } else if (type === "service_card") {
            id_type = 2;
        } else {
            id_type = 3;
        }
        var price = $(this).closest('.tr_table').find('input[name="price"]').val();
        var discount = 0;
        var quantity = $(this).val();
        //Tính lại thành tiền
        var amount = ((price * quantity) - discount);
        $(this).closest('.tr_table').find('.amount-tr').append(formatNumber(amount.toFixed(decimal_number)) + order.jsonLang['đ']);
        $(this).closest('.tr_table').find('.amount-tr').append('<input type="hidden" name="amount" class="form-control amount" value="' + amount.toFixed(decimal_number) + '">');
        //Update lại discount trên từng dòng
        $(".discount-tr-" + type + "-" + stt + "").empty();
        $(".discount-tr-" + type + "-" + stt + "").append('<input type="hidden" name="discount" value="0">');
        $(".discount-tr-" + type + "-" + stt + "").append('<input type="hidden" name="voucher_code" value="">');
        //HIEUPC2
        // $(".discount-tr-" + type + "-" + stt + "").append('<a class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--icon-only" href="javascript:void(0)" onclick="list.modal_discount(' + amount.toFixed(decimal_number) + ',' + id + ',' + id_type + ',' + stt + ')"><i class="la la-plus icon-sz"></i></a>');
        //End update

        //Tính bill
        $('.append_bill').empty();
        var sum = 0;
        $.each($('#table_add > tbody').find('input[name="amount"]'), function () {
            sum += Number($(this).val().replace(new RegExp('\\,', 'g'), ''));
        });
        $('.append_bill').append(formatNumber(sum.toFixed(decimal_number)) + order.jsonLang['đ']);
        $('.append_bill').append(' <input type="hidden" name="total_bill" class="form-control total_bill" value="' + sum.toFixed(decimal_number) + '">');
        $('.tag_a').remove();
        //Thay đổi số lượng update lại giảm giá (tổng bill)
        $('.discount_bill').empty();
        $('.discount_bill').append(0 + order.jsonLang['đ']);
        $('.discount_bill').append('<input type="hidden" id="discount_bill" name="discount_bill" value="' + 0 + '">');
        $('.discount_bill').append('<input type="hidden" id="voucher_code_bill" name="voucher_code_bill" value="">');
        $('.discount_bill').prepend('<a href="javascript:void(0)" onclick="list.modal_discount_bill(' + sum.toFixed(decimal_number) + ')" class="tag_a"><i class="fa fa-plus-circle icon-sz m--margin-right-5" style="color: #4fc4cb;"></i></a>');
        //End Update
        $('.amount_bill').append();
        $('.amount_bill').empty();

        var discount_bill = $('input[name="discount_bill"]').val();

        var delivery_fee = $('#delivery_fee').val();

        var amount_bill = (sum - discount_bill) + parseInt(delivery_fee);

        if (amount_bill < 0) {
            amount_bill = 0;
        }
        $('.amount_bill').append(formatNumber(amount_bill.toFixed(decimal_number)) + order.jsonLang['đ']);
        $('.amount_bill').append('<input type="hidden" name="amount_bill_input" class="form-control amount_bill_input" value="' + amount_bill.toFixed(decimal_number) + '">');
        $(this).closest('.tr_table').find('.abc').remove();
        if (discount != 0) {
            $(this).closest('.tr_table').find('.discount-tr-' + type + '-' + stt + '').prepend('<a class="abc" href="javascript:void(0)" onclick="list.close_amount(' + id + ',' + id_type + ',' + stt + ')"><i class="la la-close cl_amount"></i></a>');
        } else {
            $(this).closest('.tr_table').find('.discount-tr-' + type + '-' + stt + '').prepend('<a class="abc btn ss--button-cms-piospa m-btn m-btn--icon m-btn--icon-only" href="javascript:void(0)" onclick="list.modal_discount(' + amount.toFixed(decimal_number) + ',' + id + ',' + id_type + ',' + stt + ')"><i class="la la-plus icon-sz"></i></a>');
        }
        discountCustomerInput();
        order.getPromotionGift();
    });
    $('.quantity_card').change(function () {
        var id = $(this).closest('.tr_table').find("input[name='id']").val();
        var max_quantity = $(this).closest('.tr_table').find("input[name='quantity_hidden']").val();
        var quan_this = $(this).val();
        var quan_hide = $('.card_check_' + id + '').find('.quantity_hide').val();
        if (quan_hide != order.jsonLang['Không giới hạn']) {
            $('.card_check_' + id + '').find('.quantity_card').val(quan_hide - quan_this);
            $('.card_check_' + id + '').find('.quantity').empty();
            $('.card_check_' + id + '').find('.quantity').append(order.jsonLang['Còn '] + $('.card_check_' + id + '').find('.quantity_card').val() + ' (lần)');
        }
        if (max_quantity > 0) {
            $(this).trigger("touchspin.updatesettings", {
                max: max_quantity
            });
        }
    });
    $('.remove').click(function () {
        var totalAfterDiscountMember = $('.amount_bill_input').val() - $('#member_level_discount').val();

        $(this).closest('.tr_table').remove();
        var numberRowRemove = $(this).closest('.tr_table').find('.numberRow').val();
        $('.tr_table_child_'+numberRowRemove).remove();
        $('.append_bill').empty();
        var sum = 0;
        $.each($('#table_add > tbody').find('input[name="amount"]'), function () {
            sum += Number($(this).val().replace(new RegExp('\\,', 'g'), ''));
        });
        $('.append_bill').append(formatNumber(sum.toFixed(decimal_number)) + order.jsonLang['đ']);
        $('.append_bill').append(' <input type="hidden" name="total_bill" class="form-control total_bill" value="' + sum.toFixed(decimal_number) + '">');
        $('.discount_bill').empty();
        $('.discount_bill').append(0 + order.jsonLang['đ']);
        $('.discount_bill').append('<input type="hidden" id="discount_bill" name="discount_bill" value="' + 0 + '">');
        $('.discount_bill').append('<input type="hidden" id="voucher_code_bill" name="voucher_code_bill" value="">');
        $('.discount_bill').prepend('<a href="javascript:void(0)" onclick="list.modal_discount_bill(' + sum.toFixed(decimal_number) + ')" class="tag_a"><i class="fa fa-plus-circle icon-sz m--margin-right-5" style="color: #4fc4cb;"></i></a>');
        $('.amount_bill').empty();
        // var discount_bill = $('#discount_bill').val();
        // var amount_bill = (sum.toFixed(decimal_number));

        $('.amount_bill').append(formatNumber(totalAfterDiscountMember.toFixed(decimal_number)) + order.jsonLang['đ']);
        $('.amount_bill').append('<input type="hidden" name="amount_bill_input" class="form-control amount_bill_input" value="' + totalAfterDiscountMember.toFixed(decimal_number) + '">');
        discountCustomerInput();
        order.getPromotionGift();
    });
    $('.remove_card').click(function () {
        var id = $(this).closest('.tr_table').find("input[name='id']").val();
        var quan_hide = $('.card_check_' + id + '').find('.quantity_hide').val();
        $('.card_check_' + id + '').find('.quantity_card').val(quan_hide);
        $('.card_check_' + id + '').find('.quantity').empty();
        $('.card_check_' + id + '').find('.quantity').append(order.jsonLang['Còn '] + $('.card_check_' + id + '').find('.quantity_card').val() + order.jsonLang['(lần)']);
        $(this).closest('.tr_table').remove();
    });

    //Chọn phương thức thanh toán
    $('#receipt_type').select2({
        placeholder: order.jsonLang['Chọn hình thức thanh toán']
    }).on('select2:select', function (event) {
        // Lấy id và tên của phương thức thanh toán
        let methodId = event.params.data.id;
        let methodName = event.params.data.text;
        let tpl = $('#payment_method_tpl').html();
        tpl = tpl.replace(/{label}/g, methodName);
        tpl = tpl.replace(/{id}/g, methodId);
        tpl = tpl.replace(/{id}/g, methodId);
        if(methodId == 'VNPAY'){
            tpl = tpl.replace(/{style-display}/g, 'block');
        } else {
            tpl = tpl.replace(/{style-display}/g, 'none');
        }
        if (methodId == 'MEMBER_MONEY') {
            let money = $('#money').val();
            tpl = tpl.replace(/{money}/g, order.jsonLang['(Còn '] + formatNumber(money) + ')');
        } else {
            tpl = tpl.replace(/{money}/g, '*');
        }

        $('.payment_method').append(tpl);

        new AutoNumeric.multiple('#payment_method_' + methodId, {
            currencySymbol: '',
            decimalCharacter: '.',
            digitGroupSeparator: ',',
            decimalPlaces: decimal_number,
            eventIsCancelable: true,
            minimumValue: 0
        });
    }).on('select2:unselect', function (event) {
        // UPDATE 15/03/2021
        let moneyTobePaid = $('#receipt_amount').val().replace(new RegExp('\\,', 'g'), ''); // tiền phải thanh toán
        let methodId = event.params.data.id;
        let amountThis = $('#payment_method_' + methodId).val().replace(new RegExp('\\,', 'g'), '');
        $('.payment_method_' + methodId).remove();
        // tính lại tổng tiền trả (tổng tiền trả ban đầu - tiền unselect)
        let amountAllOld = $('#amount_all').val().replace(new RegExp('\\,', 'g'), '');
        let amountAllNew = amountAllOld - amountThis;
        $('#amount_all').val(formatNumber(amountAllNew.toFixed(decimal_number)));
        $('.cl_amount_all').text(formatNumber(amountAllNew.toFixed(decimal_number)));
        // tính lại tiền nợ
        if (moneyTobePaid - amountAllNew > 0) {
            $('#amount_rest').val(formatNumber((moneyTobePaid - amountAllNew).toFixed(decimal_number)));
            $('.cl_amount_rest').text(formatNumber((moneyTobePaid - amountAllNew).toFixed(decimal_number)));
        } else {
            $('#amount_rest').val(0);
            $('.cl_amount_rest').text(0);
        }
        // tính lại tiền trả khách
        if (amountAllNew - moneyTobePaid > 0) {
            $('#amount_return').val(formatNumber((amountAllNew - moneyTobePaid).toFixed(decimal_number)));
            $('.cl_amount_return').text(formatNumber((amountAllNew - moneyTobePaid).toFixed(decimal_number)));
        } else {
            $('#amount_return').val(0);
            $('.cl_amount_return').text(0);
        }
        // END UPDATE 15/03/2021
    });

    // payment old (removed, update 20/01/2022)

    $('.staff').select2({
        placeholder: order.jsonLang['Chọn nhân viên']
    });
    $('#refer_id').select2({
        placeholder: order.jsonLang['Chọn người giới thiệu'],
        allowClear: true
    });


    new AutoNumeric.multiple('.amount', {
        currencySymbol: '',
        decimalCharacter: '.',
        digitGroupSeparator: ',',
        decimalPlaces: decimal_number,
        minimumValue: 0
    });

    $('.amount').change(function () {
        $('.append_bill').empty();
        var sum = 0;
        $.each($('#table_add > tbody').find('input[name="amount"]'), function () {
            sum += Number($(this).val().replace(new RegExp('\\,', 'g'), ''));
        });
        $('.append_bill').append(formatNumber(sum.toFixed(decimal_number)) + order.jsonLang['đ']);
        $('.append_bill').append(' <input type="hidden" name="total_bill" class="form-control total_bill" value="' + sum.toFixed(decimal_number) + '">');
        $('.tag_a').remove();
        //$('.discount_bill').append('<a href="javascript:void(0)" onclick="order.modal_discount_bill(' + sum + ')" class="tag_a m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary m--margin-left-50"><i class="la la-plus"></i></a>');
        $('.amount_bill').append();
        $('.amount_bill').empty();
        var discount_bill = $('#discount_bill').val();
        $('.close').remove();
        if (discount_bill != 0) {
            $('.discount_bill').prepend('<a  class="tag_a" href="javascript:void(0)" onclick="list.close_discount_bill(' + sum.toFixed(decimal_number) + ')"><i class="la la-close cl_amount_bill m--margin-right-5"></i></a>');
        } else {
            $('.discount_bill').prepend('<a href="javascript:void(0)" onclick="list.modal_discount_bill(' + sum.toFixed(decimal_number) + ')" class="tag_a"><i class="fa fa-plus-circle icon-sz m--margin-right-5" style="color: #4fc4cb;"></i></a>');
        }
        var delivery_fee = $('#delivery_fee').val();

        var amount_bill = (sum - discount_bill) + parseInt(delivery_fee);
        if (amount_bill < 0) {
            amount_bill = 0;
        }
        $('.amount_bill').append(formatNumber(amount_bill.toFixed(decimal_number)) + order.jsonLang['đ']);
        $('.amount_bill').append('<input type="hidden" name="amount_bill_input" class="form-control amount_bill_input" value="' + amount_bill.toFixed(decimal_number) + '">');
        $(this).closest('.tr_table').find('.abc').remove();

        discountCustomerInput();
        order.getPromotionGift();
    });
    order.chooseType('area');
    discountCustomerInput();

    // $('#delivery_active').select2({
    //    placeholder: 'Xác nhận đơn hàng'
    // });
    new AutoNumeric.multiple('#tranport_charge, #discount-modal, #discount-bill', {
        currencySymbol: '',
        decimalCharacter: '.',
        digitGroupSeparator: ',',
        decimalPlaces: decimal_number,
        minimumValue: 0
    });
    $(".demo-index").scroll(function(){

        if($("#list-product").scrollTop() + $("#list-product").height() >= $("#list-product").height()) {

            // Nếu đang gửi ajax thì ngưng
            if (order.is_busy == true){
                return false;
            }
            // Nếu hết dữ liệu thì ngưng
            if (order.stopped == true){
                return false;
            }
            // Thiết lập đang gửi ajax
            order.is_busy = true;
            order.page++;
            // Hiển thị loadding

            $.ajax({
                url: laroute.route('fnb.orders.list-add'),
                data: {
                    object_type: $('.ul_type').find('.active').attr('data-name'),
                    category_id: $('#category_id_hidden').val(),
                    search: $('#search').val(),
                    customer_id: $('#customer_id').val(),
                    page : order.page
                },
                method: 'POST',
                dataType: 'JSON',
                global: false,
                success: function (res) {
                    if(res != ""){
                        $(".demo-index").append(res)
                        order.isload = true;
                        order.is_busy = false;
                    }else {
                        order.isload = false;
                        order.is_busy = true;
                        console.log( order.page);
                    }

                }
            });
            return false;
        }
    });
});

// setInterval(function () {
//     discountCustomerInput();
// }, 800/* in milliseconds 1s */);

