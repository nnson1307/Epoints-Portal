$(document).ready(function () {
    $('.select').select2();

    $('#payment_type').select2({
        placeholder: 'Chọn loại phiếu chi',
        tags: true,
        createTag: function(newTag) {
            return {
                id: newTag.term,
                text: newTag.term,
                isNew : true
            };
        }
    }).on("select2:select", function(e) {
        if(e.params.data.isNew){
            //Thêm loại phiếu thu nhanh
            $.ajax({
                type: "POST",
                url: laroute.route('payment-type.store-quickly'),
                data: {
                    payment_type_name: e.params.data.text
                },
                success: function (res) {
                    $('#payment_type').find('[value="'+e.params.data.text+'"]').replaceWith('<option selected value="'+ res.payment_type_id  +'">'+e.params.data.text+'</option>');
                }
            });
        }
    });

    $(".date-picker").datepicker({
        todayHighlight: !0,
        autoclose: !0,
        format: "dd/mm/yyyy"
    });

    new AutoNumeric.multiple('#total_amount', {
        currencySymbol: '',
        decimalCharacter: '.',
        digitGroupSeparator: ',',
        decimalPlaces: decimal_number,
        eventIsCancelable: true,
        minimumValue: 0
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

        //onchange object accounting type -> append corresponding input with object type
        $('[name="object_accounting_type_code"]').on('change',function(){
            var data = $('[name="object_accounting_type_code"] option:selected').val();
            if(data == ''){
                $('#object_render').children().remove();
                $('#object_render').append(`<input type="text" class="form-control m-input btn-sm"
                             name="accounting_name" id="accounting_name"  placeholder="Nhập tên người nhận">`);
                return;
            }
            $.ajax({
                url: laroute.route("payment.append-by-object-type"),
                method: "POST",
                data: {
                    code: data
                },
                success: function (result) {
                    $('#object_render').children().remove();
                    var stringAppend = `<select name="accounting_id" id="accounting_id" class="form-control m-input select">`;
                    if(data == 'OAT_CUSTOMER'){
                        result.forEach(e=>{
                            stringAppend+= `<option value="${e['customer_id']}">${e['full_name']}</option>`;
                        })
                        stringAppend+= `</select>`;
                        $('#object_render').append(stringAppend);
                    }
                    else if(data == 'OAT_SUPPLIER'){
                        result.forEach(e=>{
                            stringAppend+= `<option value="${e['supplier_id']}">${e['supplier_name']}</option>`;
                        })
                        stringAppend+= `</select>`;
                        $('#object_render').append(stringAppend);
                    }
                    else if(data == 'OAT_EMPLOYEE'){
                        result.forEach(e=>{
                            stringAppend+= `<option value="${e['staff_id']}">${e['full_name']}</option>`;
                        })
                        stringAppend+= `</select>`;
                        $('#object_render').append(stringAppend);
                    }
                    else{
                        $('#object_render').append(`<input type="text" class="form-control m-input btn-sm"
                             name="accounting_name" id="accounting_name"  placeholder="Nhập tên người nhận">`);
                    }
                    $('.select').select2();
                }
            });
        });
        // $('[name="edit_object_accounting_type_code"]').on('change',function(){
        //     var data = $('[name="edit_object_accounting_type_code"] option:selected').val();
        //     console.log(data);
        //     if(data == ''){
        //         $('#edit_object_render').children().remove();
        //         $('#edit_object_render').append(`<input type="text" class="form-control m-input btn-sm"
        //                      name="edit_accounting_name" id="edit_accounting_name"  placeholder="Nhập tên người nhận">`);
        //         return;
        //     }
        //     $.ajax({
        //         url: laroute.route("payment.append-by-object-type"),
        //         method: "POST",
        //         data: {
        //             code: data
        //         },
        //         success: function (result) {
        //             $('#edit_object_render').children().remove();
        //             var stringAppend = `<select name="edit_accounting_id" id="edit_accounting_id" class="form-control m-input select">`;
        //             if(data == 'OAT_CUSTOMER'){
        //                 result.forEach(e=>{
        //                     stringAppend+= `<option value="${e['customer_id']}">${e['full_name']}</option>`;
        //                 })
        //                 stringAppend+= `</select>`;
        //                 $('#edit_object_render').append(stringAppend);
        //             }
        //             else if(data == 'OAT_SUPPLIER'){
        //                 result.forEach(e=>{
        //                     stringAppend+= `<option value="${e['supplier_id']}">${e['supplier_name']}</option>`;
        //                 })
        //                 stringAppend+= `</select>`;
        //                 $('#edit_object_render').append(stringAppend);
        //             }
        //             else if(data == 'OAT_EMPLOYEE'){
        //                 result.forEach(e=>{
        //                     stringAppend+= `<option value="${e['staff_id']}">${e['full_name']}</option>`;
        //                 })
        //                 stringAppend+= `</select>`;
        //                 $('#edit_object_render').append(stringAppend);
        //             }
        //             else{
        //                 $('#edit_object_render').append(`<input type="text" class="form-control m-input btn-sm"
        //                      name="edit_accounting_name" id="edit_accounting_name"  placeholder="Nhập tên người nhận">`);
        //             }
        //             $('.select').select2();
        //         }
        //     });
        // });
    });

});

var payment = {
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
                        url: laroute.route("payment.delete-payment"),
                        method: "POST",
                        data: {
                            payment_id: id
                        },
                        success: function (result){
                            swal(
                                json['Xóa thành công'],
                                '',
                                'success'
                            ).then(function(){
                                $('#autotable').PioTable('refresh');
                            });
                        }
                    })
                }
            });
        });
    },
    add: function (close) {
        $('#type_add').val(close);
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form');
            form.validate({
                rules: {
                    object_accounting_type_code: {
                        required: true
                    },
                    accounting_id: {
                        required: true
                    },
                    accounting_name: {
                        required: true
                    },
                    payment_type: {
                        required: true
                    },
                    total_amount: {
                        required: true
                    },
                    payment_method: {
                        required: true
                    },
                    branch_code: {
                        required: true
                    },
                    note: {
                        required: true
                    }
                },
                messages: {
                    object_accounting_type_code: {
                        required: json['Hãy chọn nhóm người nhận']
                    },
                    accounting_id: {
                        required: json['Hãy chọn người nhận']
                    },
                    accounting_name: {
                        required: json['Hãy nhập tên người nhận']
                    },
                    payment_type: {
                        required: json['Hãy chọn loại chi']
                    },
                    total_amount: {
                        required: json['Hãy nhập số tiền']
                    },
                    payment_method: {
                        required: json['Hãy chọn phương thức chi']
                    },
                    branch_code: {
                        required: json['Hãy chọn chi nhánh']
                    },
                    note: {
                        required: json['Hãy nhập mô tả']
                    }
                },
            });

            if (!form.valid()) {
                return false;
            }
            $.ajax({
                method: 'POST',
                url: laroute.route('payment.create-payment'),
                data: {
                    object_accounting_type_code: $('#object_accounting_type_code option:selected').val(),
                    accounting_id: $('#accounting_id option:selected').val(),
                    accounting_name: $('#accounting_name').val(),
                    payment_type: $('#payment_type option:selected').val(),
                    document_code: $('#document_code').val(),
                    total_amount: $('#total_amount').val(),
                    payment_method: $('#payment_method option:selected').val(),
                    payment_date: $('#payment_date').val(),
                    branch_code: $('#branch_code option:selected').val(),
                    note: $('#note').val(),
                },
                dataType: "JSON",
                success: function (response) {
                    if (!response.error) {
                        $('#add').modal('hide');
                        swal.fire(
                            response.message,
                            '',
                            'success'
                        ).then(function(e){
                            location.reload(true);
                        })
                        // $('#autotable').PioTable('refresh');
                    } else {
                        swal(response.message, "", "error");
                    }
                },
                error: function (res) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal(json['Thêm mới thất bại'], mess_error, "error");
                }
            })
        });
    },
    save: function(){

        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#formEdit');
            form.validate({
                rules: {
                    edit_object_accounting_type_code: {
                        required: true
                    },
                    edit_accounting_id: {
                        required: true
                    },
                    edit_accounting_name: {
                        required: true
                    },
                    edit_payment_type: {
                        required: true
                    },
                    edit_total_amount: {
                        required: true
                    },
                    edit_payment_method: {
                        required: true
                    },
                    edit_branch_code: {
                        required: true
                    },
                    edit_note: {
                        required: true
                    }
                },
                messages: {
                    edit_object_accounting_type_code: {
                        required: json['Hãy chọn nhóm người nhận']
                    },
                    edit_accounting_id: {
                        required: json['Hãy chọn người nhận']
                    },
                    edit_accounting_name: {
                        required: json['Hãy nhập tên người nhận']
                    },
                    edit_payment_type: {
                        required: json['Hãy chọn loại chi']
                    },
                    edit_total_amount: {
                        required: json['Hãy nhập số tiền']
                    },
                    edit_payment_method: {
                        required: json['Hãy chọn phương thức chi']
                    },
                    edit_branch_code: {
                        required: json['Hãy chọn chi nhánh']
                    },
                    edit_note: {
                        required: json['Hãy nhập mô tả']
                    }
                },
            });

            if (!form.valid()) {
                return false;
            }
            let statusEdit = $('[name="edit_status"] option:selected').val();
            $.ajax({
                method: 'POST',
                url: laroute.route('payment.save-update'),
                data: {
                    payment_id: $('[name="edit_payment_id"]').val(),
                    object_accounting_type_code: $('[name="edit_object_accounting_type_code"] option:selected').val(),
                    accounting_id: $('[name="edit_accounting_id"] option:selected').val(),
                    accounting_name: $('[name="edit_accounting_name"]').val(),
                    payment_type: $('[name="edit_payment_type"] option:selected').val(),
                    document_code: $('[name="edit_document_code"]').val(),
                    total_amount: $('[name="edit_total_amount"]').val(),
                    payment_method: $('[name="edit_payment_method"] option:selected').val(),
                    payment_date: $('[name="edit_payment_date"]').val(),
                    branch_code: $('[name="edit_branch_code"] option:selected').val(),
                    note: $('[name="edit_note"]').val(),
                    status: statusEdit
                },
                dataType: "JSON",
                success: function (response) {
                    if (!response.error) {
                        swal.fire(
                            response.message,
                            '',
                            'success'
                        ).then(function(e){
                            $('#modal-edit').modal('hide');
                            $('#autotable').PioTable('refresh');
                        })
                        // $('#autotable').PioTable('refresh');
                    } else {
                        swal(response.message, "", "error");
                    }
                },
                error: function (res) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal(json['Chỉnh sửa thất bại'], mess_error, "error");
                }
            })
        });

    },
    refresh: function () {
        $('input[name="search"]').val('');
        $(".btn-search").trigger("click");
    },
    popupEdit: function (id, load, referral = 0) {
        $.getJSON(laroute.route('translate'), function (json) {
            $.ajax({
                url: laroute.route('payment.edit'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    payment_id: id,
                    load: load,
                    referral : referral
                },
                success: function (res) {
                    $('#modal-edit-payment').html(res.html);
                    $('.select').select2();
                    $('[name="edit_object_accounting_type_code"]').on('change',function(){
                        var data = $('[name="edit_object_accounting_type_code"] option:selected').val();
                        // console.log(data);
                        if(data == ''){
                            $('#edit_object_render').children().remove();
                            $('#edit_object_render').append(`<input type="text" class="form-control m-input btn-sm"
                             name="edit_accounting_name" id="edit_accounting_name"  placeholder="${json["Nhập tên người nhận"]}">`);
                            return;
                        }
                        $.ajax({
                            url: laroute.route("payment.append-by-object-type"),
                            method: "POST",
                            data: {
                                code: data
                            },
                            success: function (result) {
                                $('#edit_object_render').children().remove();
                                var stringAppend = `<select name="edit_accounting_id" id="edit_accounting_id" class="form-control m-input select">`;
                                if(data == 'OAT_CUSTOMER'){
                                    result.forEach(e=>{
                                        stringAppend+= `<option value="${e['customer_id']}">${e['full_name']}</option>`;
                                    })
                                    stringAppend+= `</select>`;
                                    $('#edit_object_render').append(stringAppend);
                                }
                                else if(data == 'OAT_SUPPLIER'){
                                    result.forEach(e=>{
                                        stringAppend+= `<option value="${e['supplier_id']}">${e['supplier_name']}</option>`;
                                    })
                                    stringAppend+= `</select>`;
                                    $('#edit_object_render').append(stringAppend);
                                }
                                else if(data == 'OAT_EMPLOYEE'){
                                    result.forEach(e=>{
                                        stringAppend+= `<option value="${e['staff_id']}">${e['full_name']}</option>`;
                                    })
                                    stringAppend+= `</select>`;
                                    $('#edit_object_render').append(stringAppend);
                                }
                                else{
                                    $('#edit_object_render').append(`<input type="text" class="form-control m-input btn-sm"
                             name="edit_accounting_name" id="edit_accounting_name"  placeholder="${json["Nhập tên người nhận"]}">`);
                                }
                                $('.select').select2();
                            }
                        });
                    });
                    $('#modal-edit').modal('show');
                    // format money
                    new AutoNumeric.multiple('#edit_total_amount', {
                        currencySymbol: '',
                        decimalCharacter: '.',
                        digitGroupSeparator: ',',
                        decimalPlaces: decimal_number,
                        eventIsCancelable: true,
                        minimumValue: 0
                    });

                    // format date
                    $(".date-picker").datepicker({
                        todayHighlight: !0,
                        autoclose: !0,
                        format: "dd/mm/yyyy",
                        // minDate: new Date(),
                    });

                    $('#edit_payment_type').select2({
                        placeholder: json['Chọn loại phiếu chi'],
                        tags: true,
                        createTag: function(newTag) {
                            return {
                                id: newTag.term,
                                text: newTag.term,
                                isNew : true
                            };
                        }
                    }).on("select2:select", function(e) {
                        if(e.params.data.isNew){
                            //Thêm loại phiếu thu nhanh
                            $.ajax({
                                type: "POST",
                                url: laroute.route('payment-type.store-quickly'),
                                data: {
                                    payment_type_name: e.params.data.text
                                },
                                success: function (res) {
                                    $('#edit_payment_type').find('[value="'+e.params.data.text+'"]').replaceWith('<option selected value="'+ res.payment_type_id  +'">'+e.params.data.text+'</option>');
                                }
                            });
                        }
                    });
                }
            });
        });
    },
    popupDetail: function (id, load) {
        $.getJSON(laroute.route('translate'), function (json) {
            $.ajax({
                url: laroute.route('payment.detail'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    payment_id: id,
                    load: load
                },
                success: function (res) {
                    $('#modal-detail-payment').html(res.html);
                    $('#modal-detail').modal('show');
                    // format money
                    new AutoNumeric.multiple('#detail_total_amount', {
                        currencySymbol: '',
                        decimalCharacter: '.',
                        digitGroupSeparator: ',',
                        decimalPlaces: decimal_number,
                        eventIsCancelable: true,
                        minimumValue: 0
                    });
                    // format date
                    $(".date-picker").datepicker({
                        todayHighlight: !0,
                        autoclose: !0,
                        format: "dd/mm/yyyy",
                        // minDate: new Date(),
                    });
                    $('.select2').select2();
                }
            });
        });
    },
    printBill: function (id) {
        $('#payment_id').val(id);
        $('#form-print-bill').submit();
    },
    searchList: function () {
        let search = $("input[name='search']").val();
        let branch_code = $("select[name='branch_code']").val();
        let status = $("select[name='status']").val();
        let created_at = $("input[name='created_at']").val();
        let created_by = $("input[name='created_by']").val();

        $('#search_export').val(search);
        $('#branch_code_export').val(branch_code);
        $('#status_export').val(status);
        $('#created_at_export').val(created_at);
        $('#created_by_export').val(created_by);
    }
};
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
var printBill = {
    printBill: function () {
        $.ajax({
            url: laroute.route('payment.save-log-print-bill'),
            method: "POST",
            data: {
                id: $('#payment_id').val()
            },
            async:false,
            success: function (res) {
                if (res.error == false) {
                    $('.error-print-bill').empty();
                    $("#PrintArea").print();
                    window.onafterprint = function(e){
                        $(window).off('mousemove', window.onafterprint);
                        location.reload();
                    };
                    setTimeout(function(){
                        $(window).one('mousemove', window.onafterprint);
                    }, 100);
                } else {
                    $('.error-print-bill').text(res.message);
                }
            }
        });
    },
    back:function () {
        window.top.close();
    }
};
$('#autotable').PioTable({
    baseUrl: laroute.route('payment.list')
});