var create = {
    _init: function () {

        $(document).on('keyup', ".format-money", function () {
            var n = parseInt($(this).val().replace(/\D/g, ''), 10);
            if (typeof n == 'number' && Number.isInteger(n)) {
                // $(this).val(n.toLocaleString());
            } else {
                // $(this).val("");
            }
        });
        $.getJSON(laroute.route('translate'), function (json) {
            $('#province_id').select2({
                placeholder: json['Chọn tỉnh/thành'],
            }).on('select2:unselect', function (e) {
                $('#district_id').empty();
            });

            $('#district_id').select2({
                placeholder: json['Chọn quận/ huyện'],
                ajax: {
                    url: laroute.route('delivery-cost.load-district-pagination'),
                    data: function (params) {
                        return {
                            arrProvince: $('#province_id').val(),
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
            })
        });
    },
    save: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-create');

            form.validate({
                rules: {
                    delivery_cost_name: { required: true },
                    delivery_cost: {required: true },
                    district_id: {required: true }
                },
                messages: {
                    delivery_cost_name: { required: json['Hãy nhập tên chi phí vận chuyển'] },
                    delivery_cost: {required: json['Hãy nhập chi phí vận chuyển'] },
                    district_id: {required: json['Hãy chọn quận/huyện'] }
                },
            });

            if (!form.valid()) {
                return false;
            }

            // check is_system
            let is_system = $('#is_system').is(":checked");
            if (is_system == true) {
                is_system = 1;
            } else {
                is_system = 0;
            }


            $.ajax({
                url: laroute.route('delivery-cost.store'),
                method: 'POST',
                dataType: 'JSON',
                // data: {
                //     delivery_cost_name: $('#delivery_cost_name').val(),
                //     delivery_cost: $('#delivery_cost').val(),
                //     district_id: $('#district_id').val(),
                //     is_system: is_system
                // },
                data: $('#form-create').serialize()+'&is_system='+is_system,

                success: function (res) {
                    if (res.error == false) {
                        swal(res.message, "", "success").then(function (result) {
                            if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                window.location.href = laroute.route('delivery-cost');
                            }
                            if (result.value == true) {
                                window.location.href = laroute.route('delivery-cost');
                            }
                        });
                    } else {
                        swal(res.message, '', "error");
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

    changeMethod : function(code){
        if($('.is_delivery_fast').is(':checked')){
            $('.block-fast-delivery').show();
        } else {
            $('.block-fast-delivery').hide();
        }
    }
}

var edit = {
    save: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-edit');

            form.validate({
                rules: {
                    delivery_cost_name: { required: true },
                    delivery_cost: {required: true },
                    district_id: {required: true }
                },
                messages: {
                    delivery_cost_name: { required: json['Hãy nhập tên chi phí vận chuyển'] },
                    delivery_cost: {required: json['Hãy nhập chi phí vận chuyển'] },
                    district_id: {required: json['Hãy chọn quận/huyện'] }
                },
            });

            if (!form.valid()) {
                return false;
            }
            // check is_system
            // let is_system = $('#is_system').val();
            let is_system = $('#is_system').is(":checked");
            if (is_system == true) {
                is_system = 1;
            } else {
                is_system = 0;
            }

            $.ajax({
                url: laroute.route('delivery-cost.update'),
                method: 'POST',
                dataType: 'JSON',
                // data: {
                //     delivery_cost_id: $('#delivery_cost_id').val(),
                //     delivery_cost_name: $('#delivery_cost_name').val(),
                //     delivery_cost_code: $('#delivery_cost_code').val(),
                //     delivery_cost: $('#delivery_cost').val(),
                //     district_id: $('#district_id').val(),
                //     is_system: is_system,
                // },
                data: $('#form-edit').serialize()+'&is_system='+is_system,
                success: function (res) {
                    if (res.error == false) {
                        swal(res.message, "", "success").then(function (result) {
                            if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                window.location.href = laroute.route('delivery-cost');
                            }
                            if (result.value == true) {
                                window.location.href = laroute.route('delivery-cost');
                            }
                        });
                    } else {
                        swal(res.message, '', "error");
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
    }
}

var list = {
    _init: function () {
        $('#autotable').PioTable({
            baseUrl: laroute.route('delivery-cost.list')
        });
    },

    delete: function (delivery_cost_id) {

        $.getJSON(laroute.route('translate'), function (json) {
            swal({
                title: json['Thông báo'],
                text: json["Bạn có muốn xóa không?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json['Xóa'],
                cancelButtonText: json['Hủy'],
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: laroute.route('delivery-cost.destroy'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            delivery_cost_id: delivery_cost_id
                        },
                        success: function (res) {
                            if (res.error == false) {
                                swal.fire(res.message, "", "success");
                                window.location = laroute.route('delivery-cost');
                            } else {
                                swal.fire(res.message, '', "error");
                            }
                        }
                    });
                }
            });
        });
    },
}

$(document).ready(function(){
    new AutoNumeric.multiple('#delivery_cost', {
        currencySymbol: '',
        decimalCharacter: '.',
        digitGroupSeparator: ',',
        decimalPlaces: decimal_number,
        minimumValue: 0
    });

    new AutoNumeric.multiple('.format-money', {
        currencySymbol: '',
        decimalCharacter: '.',
        digitGroupSeparator: ',',
        decimalPlaces: decimal_number,
        minimumValue: 0,
        maximumValue:10000000
    });
});