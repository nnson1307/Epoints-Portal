$('#autotable').PioTable({
    baseUrl: laroute.route('admin.order.list')
});
var order_index = {
    detail: function (id) {
        $.ajax({
            url: laroute.route('admin.order.detail'),
            data: {
                id: id,
            },
            method: 'POST',
            dataType: "JSON",
            success: function (response) {
                $('.display-detail').css('display', 'block');
                $('.append-detail').empty();
                var tpl = $('#detail-tpl').html();
                tpl = tpl.replace(/{name}/g, response.list['full_name']);
                tpl = tpl.replace(/{id}/g, response.list['order_id']);
                tpl = tpl.replace(/{phone}/g, response.list['phone']);
                if (response.list['customer_avatar'] != null) {
                    tpl = tpl.replace(/{img}/g, '/' + response.list['customer_avatar']);
                } else {
                    tpl = tpl.replace(/{img}/g, '');
                }
                if (response.list['address'] != null) {
                    tpl = tpl.replace(/{address}/g, response.list['address']);
                } else {
                    tpl = tpl.replace(/{address}/g, '');
                }
                console.log(response);
                if (response.list['process_status'] == 'not_call') {
                    tpl = tpl.replace(/{class_not_call}/g, 'btn btn-info status active');
                    tpl = tpl.replace(/{class_confirm}/g, 'btn btn-default status');
                    tpl = tpl.replace(/{class_packing}/g, 'btn btn-default status');
                    tpl = tpl.replace(/{class_deliverd}/g, 'btn btn-default status');
                }
                if (response.list['process_status'] == 'confirm') {
                    tpl = tpl.replace(/{class_not_call}/g, 'btn btn-default status');
                    tpl = tpl.replace(/{class_confirm}/g, 'btn btn-info status active');
                    tpl = tpl.replace(/{class_packing}/g, 'btn btn-default status');
                    tpl = tpl.replace(/{class_deliverd}/g, 'btn btn-default status');
                }
                if (response.list['process_status'] == 'packing') {
                    tpl = tpl.replace(/{class_not_call}/g, 'btn btn-default status');
                    tpl = tpl.replace(/{class_confirm}/g, 'btn btn-default status');
                    tpl = tpl.replace(/{class_packing}/g, 'btn btn-info status active');
                    tpl = tpl.replace(/{class_deliverd}/g, 'btn btn-default status');
                }
                if (response.list['process_status'] == 'class_deliverd') {
                    tpl = tpl.replace(/{class_not_call}/g, 'btn btn-default status');
                    tpl = tpl.replace(/{class_confirm}/g, 'btn btn-default status');
                    tpl = tpl.replace(/{class_packing}/g, 'btn btn-default status');
                    tpl = tpl.replace(/{class_deliverd}/g, 'btn btn-info status active');
                }
                tpl = tpl.replace(/{total_bill}/g, formatNumber(response.list['total']));
                tpl = tpl.replace(/{total_bill_hidden}/g, response.list['total']);
                tpl = tpl.replace(/{discount_bill}/g, response.list['discount']);
                tpl = tpl.replace(/{tranport_charge}/g, response.list['discount']);
                tpl = tpl.replace(/{amount_bill}/g, formatNumber(response.list['amount']));
                tpl = tpl.replace(/{amount_bill_hidden}/g, response.list['amount']);
                $('.append-detail').append(tpl);
                $('.discount_bill').mask('000,000,000', {reverse: true});
                $('.tranport_charge').mask('000,000,000', {reverse: true});
                $.map(response.list_detail, function (a) {
                    var tpl = $('#detail-table-tpl').html();
                    var stt = $('#table_detail tr').length;
                    tpl = tpl.replace(/{stt}/g, stt);
                    tpl = tpl.replace(/{order_detail_id}/g, a.order_detail_id);
                    tpl = tpl.replace(/{name}/g, a.object_name);
                    if (a.object_type == 'service') {
                        tpl = tpl.replace(/{type}/g, json['Dịch vụ']);
                    } else if (a.object_type == 'service_card') {
                        tpl = tpl.replace(/{type}/g, json['Thẻ dịch vụ']);
                    } else {
                        tpl = tpl.replace(/{type}/g, json['Sản phẩm']);
                    }
                    tpl = tpl.replace(/{type_hidden}/g, a.object_type);
                    tpl = tpl.replace(/{price}/g, formatNumber(a.price));
                    tpl = tpl.replace(/{price_hidden}/g, a.price);
                    tpl = tpl.replace(/{quantity}/g, a.quantity);
                    tpl = tpl.replace(/{discount}/g, a.discount);
                    tpl = tpl.replace(/{amount}/g, formatNumber(a.amount));
                    tpl = tpl.replace(/{amount_hidden}/g, a.amount);
                    $('#table_detail > tbody').append(tpl);
                });
                $(".quantity").TouchSpin({
                    initval: 1,
                    min: 1
                });
                $('.discount').mask('000,000,000', {reverse: true});
                $('.status').click(function () {
                    $('.status').attr('class','btn btn-default status');
                    $(this).attr('class','btn btn-info status active');
                });
                $('.quantity').change(function () {
                    $(this).closest('.tr_detail').find('.amount-tr').empty();
                    var price = $(this).closest('.tr_detail').find('input[name="price"]').val();
                    var discount = $(this).closest('.tr_detail').find('input[name="discount"]').val();
                    var loc = discount.replace(/\D+/g, '');
                    var quantity = $(this).val();
                    var amount = parseInt((price * quantity) - loc);
                    $(this).closest('.tr_detail').find('.amount-tr').append(formatNumber(amount));
                    $(this).closest('.tr_detail').find('.amount-tr').append('<input type="hidden" name="amount" class="form-control amount" value="' + amount + '">');

                    $('.sp_total_bill').empty();
                    var sum = 0;
                    $.each($('#table_detail > tbody').find('input[name="amount"]'), function () {
                        sum += Number($(this).val());
                    });
                    $('.sp_total_bill').append(formatNumber(sum));
                    $('.sp_total_bill').append(' <input type="hidden" name="total_bill" class="form-control total_bill" value="' + sum + '">');
                    $('.amount_bill').append();
                    $('.amount_bill').empty();
                    var discount_bill = $('input[name="discount_bill"]').val();
                    var tranport_bill = $('input[name="tranport_charge"]').val();
                    var loc_discount = discount_bill.replace(/\D+/g, '');
                    var loc_transport = tranport_bill.replace(/\D+/g, '');
                    var amount_bill = parseInt(sum - loc_discount - loc_transport);
                    $('.amount_bill').append('<strong>' + formatNumber(amount_bill) + '</strong>');
                    $('.amount_bill').append('<input type="hidden" name="amount_bill_input" class="form-control amount_bill_input" value="' + amount_bill + '">');
                });
                $('.discount').change(function () {
                    $(this).closest('.tr_detail').find('.amount-tr').empty();
                    var price = $(this).closest('.tr_detail').find('input[name="price"]').val();
                    var discount = $(this).val();
                    var loc = discount.replace(/\D+/g, '');
                    var quantity = $(this).closest('.tr_detail').find('input[name="quantity"]').val();
                    var amount = parseInt((price * quantity) - loc);
                    $(this).closest('.tr_detail').find('.amount-tr').append(formatNumber(amount));
                    $(this).closest('.tr_detail').find('.amount-tr').append('<input type="hidden" name="amount" class="form-control amount" value="' + amount + '">');

                    $('.sp_total_bill').empty();
                    var sum = 0;
                    $.each($('#table_detail > tbody').find('input[name="amount"]'), function () {
                        sum += Number($(this).val());
                    });
                    $('.sp_total_bill').append(formatNumber(sum));
                    $('.sp_total_bill').append(' <input type="hidden" name="total_bill" class="form-control total_bill" value="' + sum + '">');
                    $('.amount_bill').append();
                    $('.amount_bill').empty();
                    var discount_bill = $('input[name="discount_bill"]').val();
                    var tranport_bill = $('input[name="tranport_charge"]').val();
                    var loc_discount = discount_bill.replace(/\D+/g, '');
                    var loc_transport = tranport_bill.replace(/\D+/g, '');
                    var amount_bill = parseInt(sum - loc_discount - loc_transport);
                    $('.amount_bill').append('<strong>' + formatNumber(amount_bill) + '</strong>');
                    $('.amount_bill').append('<input type="hidden" name="amount_bill_input" class="form-control amount_bill_input" value="' + amount_bill + '">');
                });


            }
        });
    },
    add_product_new: function () {
        $('#new').css('display', 'block');
        $('.type').click(function () {
            $('.type').prop('checked', false);
            $(this).prop('checked', true);
        });
        $('#search_new').select2({
            placeholder: 'Tìm kiếm',
            ajax: {
                url: laroute.route('admin.order.search-detail'),
                dataType: 'json',
                delay: 250,
                type: 'POST',
                data: function (params) {
                    var type = $('.type:checked').val();
                    var query = {
                        search: params.term,
                        page: params.page || 1,
                        type: type
                    };
                    return query;
                }
            },
            minimumInputLength: 3
        });
    },
    close_product_new: function () {
        $('.display-detail').css('display', 'none');
    },
    save: function () {
        var type = $('.type:checked').val();
        console.log(type);
    },
    refresh: function () {
        $('input[name="search_keyword"]').val('');
        $(".btn-search").trigger("click");
    }
};

function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
}