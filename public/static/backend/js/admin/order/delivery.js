var delivery = {
    showPopup:function(customerAddressId = null){
        if($('#customer_id').val() != ''){
            $.ajax({
                url: laroute.route('admin.order.showPopupAddress'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    customer_id : $('#customer_id').val(),
                    customerAddressId : customerAddressId,
                    type_time : $('#type_time_hidden').val(),
                    time_address : $('#time_address_hidden').val()
                },
                success: function (res) {
                    $('.popupShow').html(res.view);
                    $('.select-fix').select2();
                    $('#time_address').datepicker({
                        pickerPosition: 'bottom-left',
                        todayHighlight: true,
                        autoclose: true,
                        format: 'dd/mm/yyyy',
                        setDate:  '+0d',
                        startDate: '0d',
                        language:'vi'
                    });
                    $('#popup-address').modal('show');
                }
            });
        }
    },

    showPopupAddAddress : function(customer_contact_id = null){
        $.ajax({
            url: laroute.route('admin.order.showPopupAddAddress'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                customer_id : $('#customer_id').val(),
                customer_contact_id : customer_contact_id
            },
            success: function (res) {
                $('.modal-backdrop').hide();
                $('.popupShow').html(res.view);
                $('.select-fix').select2();
                $('#popup-add-address').modal('show');
                $('select:not(.normal)').each(function () {
                    $(this).select2({
                        dropdownParent: $(this).parent()
                    });
                });
            }
        });
    },

    changeProvince: function(){
        $.ajax({
            url: laroute.route('admin.order.changeProvince'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                province_id : $('#province_id').val(),
            },
            success: function (res) {
                if (res.error == false){
                    $('#district_id').html(res.view);
                    $('#ward_id').html(res.view1);
                    $('#district_id').select2();
                    $('#ward_id').select2();
                    $('select:not(.normal)').each(function () {
                        $(this).select2({
                            dropdownParent: $(this).parent()
                        });
                    });
                } else {
                    swal(res.message, "", "error");
                }
            }
        });
    },

    changeDistrict: function(){
        $.ajax({
            url: laroute.route('admin.order.changeDistrict'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                district_id : $('#district_id').val(),
            },
            success: function (res) {
                if (res.error == false){
                    $('#ward_id').html(res.view);
                    $('#ward_id').select2();
                    $('select:not(.normal)').each(function () {
                        $(this).select2({
                            dropdownParent: $(this).parent()
                        });
                    });
                } else {
                    swal(res.message, "", "error");
                }
            }
        });
    },

    submitAddress : function(){
        $.ajax({
            url: laroute.route('admin.order.submitAddress'),
            method: 'POST',
            dataType: 'JSON',
            data: $('#form-add-address').serialize(),
            success: function (res) {
                if (res.error == false){
                    swal(res.message, "", "success").then(function(){
                        $('.modal-backdrop').hide();
                        delivery.showPopup(res.idAddress);
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
                swal(mess_error,'', "error");
            }
        });
    },

    removeAddress : function (customer_contact_id) {
        swal({
            title: 'Xoá địa chỉ giao hàng',
            text: "Bạn có muốn xóa không?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy',

        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url:laroute.route('admin.order.removeAddressCustomer'),
                    method:"POST",
                    data:{
                        customer_contact_id : customer_contact_id,
                        customer_id : $('#customer_id').val()
                    },
                    success:function (data) {
                        if(data.error == false){
                            swal(data.message,'', "success");
                            $('.block-list-address').html(data.view);
                        } else {
                            swal(data.message,'', "error");
                        }
                    }
                });
            }
        });
    },

    changeInfoAddress: function () {
        $.ajax({
            url:laroute.route('admin.order.changeInfoAddress'),
            method:"POST",
            data:{
                customer_contact_id : $('input[name="customer_contact_id"]:checked').val(),
                type_time : $('#type_time').val(),
                time_address : $('#time_address').val()
            },
            success:function (data) {
                if(data.error == false){
                    swal(data.message,'', "success");
                    $('.block-address').html(data.view);
                    $('#popup-address').modal('hide');
                    $('#type_time_hidden').val($('#type_time').val()),
                    $('#time_address_hidden').val($('#time_address').val());
                    $('#customer_contact_id_hidden').val(data.customer_contact_id);

                    $('.delivery_fee_text').text(0);
                    $('#delivery_fee').val(0);
                    $('#delivery_type').val('');
                    $('#delivery_obj_id').val('');

                    discountCustomerInput();
                } else {
                    swal(data.message,'', "error");
                }
            }
        });
    },

    changeInfoAddressCustomer: function () {
        $.ajax({
            url:laroute.route('admin.order.changeInfoAddress'),
            method:"POST",
            data:{
                customer_id : $('#customer_id').val()
            },
            success:function (data) {
                if(data.error == false){
                    $('.block-address').html(data.view);
                    $('#popup-address').modal('hide');
                    $('#type_time_hidden').val(''),
                    $('#time_address_hidden').val('');
                    $('#customer_contact_id_hidden').val(data.customer_contact_id);

                    $('.delivery_fee_text').text(0);
                    $('#delivery_fee').val(0);
                    $('#delivery_type').val('');
                    $('#delivery_obj_id').val('');

                    discountCustomerInput();
                } else {
                    swal(data.message,'', "error");
                }
            }
        });
    },

    removeSelectCustomer : function (){
        $.ajax({
            url:laroute.route('admin.order.changeInfoAddress'),
            method:"POST",
            data:{
            },
            success:function (data) {
                if(data.error == false){
                    $('.block-address').html(data.view);
                    $('#popup-address').modal('hide');
                    $('#type_time_hidden').val(''),
                        $('#time_address_hidden').val('');
                    $('#customer_contact_id_hidden').val(data.customer_contact_id);

                    $('.delivery_fee_text').text(0);
                    $('#delivery_fee').val(0);
                    $('#delivery_type').val('');
                    $('#delivery_obj_id').val('');

                    discountCustomerInput();
                } else {
                    swal(data.message,'', "error");
                }
            }
        });
    },

    changeDeliveryStyle : function(obj){
        $('.delivery_type').removeClass('active');
        $(obj).addClass('active');
        var fee = $('.delivery_type.active').attr('data-fee');
        var delivery_cost_id = $('.delivery_type.active').attr('data-delivery-cost-id');
        var type_shipping = $('.delivery_type.active').attr('data-type-shipping');
        $('.delivery_fee_text').text(formatNumber(fee));
        $('#delivery_fee').val(fee);
        $('#tranport_charge').val(formatNumber(fee));
        new AutoNumeric.multiple('#tranport_charge', {
            currencySymbol: '',
            decimalCharacter: '.',
            digitGroupSeparator: ',',
            decimalPlaces: decimal_number,
            eventIsCancelable: true,
            minimumValue: 0
        });
        $('#delivery_type').val(type_shipping);
        $('#delivery_cost_id').val(delivery_cost_id);
        discountCustomerInput();

    }
}


$(document).ready(function () {
    $('.receipt_info_check').change(function (){
        if ($('.receipt_info_check').is(":checked")){
            $('.receipt_info_check_block').show();
            $('.icon-edit-delivery').show();
        } else {
            $('.receipt_info_check_block').hide();
            $('.icon-edit-delivery').hide();
            $('.delivery_fee_text').text(formatNumber(0));
            $('#delivery_fee').val(0);
            $('#tranport_charge').val(0);
            $('#delivery_type').val('');
            $('#delivery_cost_id').val('');
            $('.delivery_type').removeClass('active');
            $('input[name="type_shipping"]').prop('checked',false);
            discountCustomerInput();
        }
    })
})

function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}