// Index
$('#autotable').PioTable({
    baseUrl: laroute.route('notification.list')
});
var stt = 0;

$('#kt_daterangepicker_4').daterangepicker({
    buttonClasses: ' btn',
    applyClass: 'btn-primary',
    cancelClass: 'btn-secondary',

    timePicker: true,
    timePickerIncrement: 30,
    locale: {
        format: 'MM/DD/YYYY h:mm A'
    }
}, function (start, end, label) {
    $('#kt_daterangepicker_4 .form-control').val(start.format('MM/DD/YYYY h:mm A') + ' / ' + end.format('MM/DD/YYYY h:mm A'));
});

$(".is_actived").change(function () {
    var notiId = $(this).attr('data-id');
    var check;
    this.checked ? check = 1 : check = 0;
    var time = $(this).attr("data-non-specific-value");
    var type = $(this).attr("data-non-specific-type");
    // dùng để hiển thị lại giờ tương đối
    var oldTime = $("#time-"+notiId).text();
    $.ajax({
        method: 'POST',
        url: laroute.route('admin.notification.updateIsActived', {id:notiId}),
        data: {
            check: check,
            non_specific_value: time,
            non_specific_type: type,
            old_time: oldTime
        },
        success: function (result) {
            if (result.success == 1) {
                toastr.success('Cập nhật thành công!');
                if (check == 1) {
                    $("#status-"+notiId).html(statusPending);
                } else if (check == 0) {
                    $("#status-"+notiId).html(statusNot);
                }

                if (time != '' && type != '') {
                    $("#time-"+notiId).html(result.send_at);
                }
            } else {
                swal.fire("", result.message, "error").then(function (result) {
                    location.reload();
                });
                // $(this).attr('checked', false);
            }
        }
    });
});

function removeItem(id) {
    $.getJSON(laroute.route('admin.validation'), function (json) {
        swal.fire({
            title: json.notification.TITLE_POPUP,
            html: json.notification.HTML_POPUP,
            buttonsStyling: false,

            confirmButtonText: json.notification.YES_BUTTON,
            confirmButtonClass: "btn btn-sm btn-default btn-bold",

            showCancelButton: true,
            cancelButtonText: json.notification.CANCEL_BUTTON,
            cancelButtonClass: "btn btn-sm btn-bold btn-brand"
        }).then(function (result) {
            if (result.value) {
                $.post(laroute.route("admin.notification.destroy", {id:id}), function (res) {
                    if (res.error == false) {
                        location.reload();
                    } else {
                        Swal.fire(res.message, "", "error");
                    }
                })
            }
        });
    });
}

// Xử lý thao tác
$("select[name=action]").change(function () {
    var value = $(this).children(":selected").val();
    if (value == 0) {
        return false;
    } else if (value != 'delete') {
        window.location.href = value;
    } else {
        var route = $(this).children(":selected").attr("data-route");
        var id = $(this).children(":selected").attr("data-id");
        removeItem(route, id);
    }
});
// End Index


var detailType = 0;
var isDetail = 0;
var activeDelete = 0; // lần đầu load trang sẽ ko xóa params nếu có
$("#end_point").change(function () {
    // xóa detail nếu có
    if (activeDelete == 1) {
        $("input[name=end_point_detail]").val('');
        $("input[name=end_point_detail_click]").val('');
    }
    activeDelete = 1;
    // $("input[name=end_point_detail]").val('');
    $("#end-point-detail").css("display", "none");
    $("input[name=end_point_detail]").attr('disabled', 'disabled');
    $("#end-point-modal").html('');

    var detailType1 = $(this).children(":selected").attr("data-type");
    var isDetail1 = $(this).children(":selected").attr("is-detail");
    if (detailType1 != '') {
        $("#end-point-detail").css("display", "flex");
        $("input[name=end_point_detail]").removeAttr('disabled');
        detailType = detailType1;
        isDetail = isDetail1;
    }
    if(isDetail == 0){
        $("input[name=end_point_detail_click]").attr('disabled',true);
    }
    else{
        $("input[name=end_point_detail_click]").attr('disabled',false);
    }
    $("input[name=is_detail]").val($(this).children(":selected").attr("is-detail"));
    $("input[name=notification_type_id]").val($(this).children(":selected").attr("data-id"));
});

function handleClick() {
    $.getJSON('/admin/validation', function (json) {
        $.ajax({
            url: laroute.route("admin.notification.detailEndPoint"),
            method: "GET",
            data: {
                view: 'modal',
                detail_type: detailType
            },
            success: function (res) {
                end_point_value = $("input[name=end_point_detail]").val();
                $("#end-point-modal").html(res);
                $("#end-point-modal").find("#modal-end-point").modal();
                $('#autotable-product').PioTable({
                    baseUrl: laroute.route('admin.notification.listDetailEndPoint')
                });
                $('.btn-search').trigger('click');
            }
        });
    });
}
function clickRadioEndPoint(id = 0,name = ''){
    $('#end_point_detail_click').val(name);
    $('#end_point_detail').val(id);
    $("input[name=end_point_detail]").val(id);
}
$('#specific_time').datetimepicker({
    todayHighlight: true,
    autoclose: true,
    pickerPosition: 'top-left',
    format: 'yyyy-mm-dd hh:ii'
});

$("input[name=send_time_radio]").change(function () {
    radio = $("input[name=send_time_radio]:checked").val();
    if (radio == 1) {
        $("#schedule-time").css("display", "flex");
    } else {
        $("#schedule-time").css("display", "none");
    }
});

$("select[name=schedule_time]").change(function () {
    value = $(this).val();
    if (value == 'non_specific_time') {
        $("#non_specific_time_display").css("display", "block");
        $("#specific_time_display").css("display", "none");
        $("#specific_time").removeClass("is-invalid");
        $("#specific_time-error").remove();
        $(".invalid-feedback").remove();
    }
    if (value == 'specific_time') {
        $("#specific_time_display").css("display", "flex");
        $("#non_specific_time_display").css("display", "none");
        $("#non_specific_time").removeClass("is-invalid");
        $("#non_specific_time-error").remove();
        $(".invalid-feedback").remove();
    }
});

$("select[name=action_group]").change(function () {
    value = $(this).val();
    if (value == 1) {
        $("#cover-action").removeAttr('style');
        $("input[name=action_name]").removeAttr('disabled');
    } else {
        $("#cover-action").css("display", "none");
        $("input[name=action_name]").attr('disabled', 'disabled');
    }
});

// trigger change cho view edit
$("select[name=action_group]").change();
$("#end_point").change();
$("input[name=send_time_radio]").change();

var script = {
    closeModalAddDeal: function () {
        $('#load-modal-create').val(0);
        $('#modal-create').modal('hide');
    },
    saveModalDeal: function(){
        $('#load-modal-create').val(1);
        $('#modal-create').modal('hide');
    },
    changeCreateDeal: function(){
        if($('#is_deal_created').is(":checked")){
            $('#popup_create_deal').removeAttr('hidden');
        }
        else{
            $('#popup_create_deal').attr('hidden', true);
            $('#load-modal-create').val(0);
        }
    },
    popupCreateDeal: function(){
        $.getJSON(laroute.route('translate'), function (json) {
            let load = $('#load-modal-create').val();
            if(load == 1){
                $('#modal-create').modal('show');
            }
            else{
                $.ajax({
                    url: laroute.route('admin.notification.noti-popup-created-deal'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                    },
                    success: function (res) {
                        $('#my-modal-create').html(res.html);
                        $('#modal-create').modal('show');

                        $('#pipeline_code').select2({
                            placeholder: json['Chọn pipeline']
                        });

                        $('#journey_code').select2({
                            placeholder: json['Chọn hành trình']
                        });


                        $("#end_date_expected").datepicker({
                            todayHighlight: !0,
                            autoclose: !0,
                            format: "dd/mm/yyyy",
                            startDate: "dateToday"
                        });
                        $('#pipeline_code').change(function () {
                            $.ajax({
                                url: laroute.route('customer-lead.load-option-journey'),
                                dataType: 'JSON',
                                data: {
                                    pipeline_code: $('#pipeline_code').val(),
                                },
                                method: 'POST',
                                success: function (res) {
                                    $('.journey').empty();
                                    $.map(res.optionJourney, function (a) {
                                        $('.journey').append('<option value="' + a.journey_code + '">' + a.journey_name + '</option>');
                                    });
                                }
                            });
                        });

                        new AutoNumeric.multiple('#amount', {
                            currencySymbol: '',
                            decimalCharacter: '.',
                            digitGroupSeparator: ',',
                            decimalPlaces: decimal_number,
                            eventIsCancelable: true,
                            minimumValue: 0
                        });
                    }
                });
            }
        });
    },
    uploadAvatar: function (input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            var imageAvatar = $('#image');
            reader.onload = function (e) {
                $('#blah')
                    .attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);

            var file_data = $('#getFile').prop('files')[0];
            var form_data = new FormData();
            form_data.append('file', file_data);
            form_data.append('link', '_notification.');

                $.ajax({
                    url: laroute.route("config.upload"),
                    method: "POST",
                    data: form_data,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (res) {
                        if (res.success == 1) {
                            $('#background').val(res.file);
                        }
                    }
                });
        }
    },
    submit_add: function (is_quit) {
        $(".invalid-feedback").remove();

        $.getJSON(laroute.route('translate'), function (json1) {
            $.getJSON('/admin/validation', function (json) {
                var form = $('#form-add');
                rules = {
                    title: {required: true, maxlength: 255},
                    short_title: {required: true, maxlength: 255},
                    feature: {required: true, maxlength: 255},
                    cost:{
                        required:true
                    }
                };

                messages = {
                    title: {
                        required: json.notification.title_required,
                        maxlength: json.notification.max_255
                    },
                    short_title: {
                        required: json.notification.short_title_required,
                        maxlength: json.notification.max_255
                    },
                    feature: {
                        required: json.notification.feature_required,
                        maxlength: json.notification.max_255
                    },
                    cost: {
                        required: json.notification.cost,
                    }
                };

                form.validate({
                    rules: rules,
                    messages: messages
                });

                if (!form.valid()) {
                    return false;
                }

                var is_deal_created = 0;
                var arrObject = [];
                    if($('#is_deal_created').is(":checked")){
                        is_deal_created = 1;
                    }
                    if(is_deal_created == 1){
                        if($('#pipeline_code').val() == ''){
                            swal(json1['Hãy chọn pipeline'], '', "error");
                            return;
                        }
                        if($('#end_date_expected').val() == ''){
                            swal(json1['Hãy chọn ngày kết thúc dự kiến'], '', "error");
                            return;
                        }
                    }
                    if(is_deal_created == 1){

                        // check object
                        $.each($('#table_add > tbody').find('.add-object'), function () {

                            var object_type = $(this).find($('.object_type')).val();
                            var object_code = $(this).find($('.object_code')).val();

                            if (object_type == "") {
                                swal(json1['Vui lòng chọn loại'], '', "error");
                                $(this).find($('.error_object_type')).text(json1['Vui lòng chọn loại sản phẩm']);
                                return;
                            } else {
                                $(this).find($('.error_object_type')).text('');
                            }
                            if (object_code == "") {
                                swal(json1['Vui lòng chọn sản phẩm'], '', "error");
                                $(this).find($('.error_object')).text(json1['Vui lòng chọn sản phẩm']);
                                return;
                            } else {
                                $(this).find($('.error_object')).text('');
                            }
                        });

                        // Lấy danh sách object (nếu có)
                        var arrObject = [];
                        $.each($('#table_add > tbody').find('.add-object'), function () {

                            var object_type = $(this).find($('.object_type')).val();
                            var object_name = $(this).find($('.object_code')).text();
                            var object_code = $(this).find($('.object_code')).val();
                            var object_id = $(this).find($('.object_id')).val();
                            var price = $(this).find($('.object_price')).val();
                            var quantity = $(this).find($('.object_quantity')).val();
                            var discount = $(this).find($('.object_discount')).val();
                            var amount = $(this).find($('.object_amount')).val();

                            arrObject.push({
                                object_type: object_type,
                                object_name: object_name,
                                object_code: object_code,
                                object_id: object_id,
                                price: price,
                                quantity: quantity,
                                discount: discount,
                                amount: amount
                            });
                        });

                    }

                var endPoint = $('#end_point').val()

                if(endPoint == 'product_detail' || endPoint == 'service_detail' || endPoint == 'promotion_detail' || endPoint == 'news_detail'){
                    if($('[name="action_group"] option:selected').val() == 1 && $('#end_point_detail_click').val() == ""){

                        $.getJSON(laroute.route('translate'), function (json) {
                            swal(json["Vui lòng chọn đích đến chi tiết"], "", "error").then(function (result) {
                                return;
                            });
                        });
                        return;
                    }
                    else{
                        $.ajax({
                            url: laroute.route('admin.notification.store'),
                            method: 'POST',
                            dataType: 'JSON',
                            data: $('#form-add').serialize() + "&is_deal_created="+is_deal_created+"&arrObject=" +  JSON.stringify(arrObject),
                            success: function (res) {
                                if (res.error) {
                                    var iconMessage = "error";
                                } else {
                                    var iconMessage = "success";
                                }
                                setTimeout(function () {
                                    swal(res.message, "", iconMessage).then(function (result) {
                                        if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                            window.location.href = laroute.route('admin.notification');
                                        }
                                        if (result.value == true) {
                                            if (is_quit === 0) {
                                                window.location.reload();
                                            } else {
                                                window.location.href = laroute.route('admin.notification');
                                            }
                                        }
                                    });
                                }, 1500);
                            },
                            error: function (res) {
                                var mess_error = '';
                                jQuery.each(res.responseJSON.errors, function (key, val) {
                                    mess_error = mess_error.concat(val + '<br/>');
                                });
                                swal("", mess_error, "error");
                            }
                        });
                    }
                }
                else{

                    $.ajax({
                        url: laroute.route('admin.notification.store'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: $('#form-add').serialize() + "&is_deal_created="+is_deal_created+"&arrObject=" +  JSON.stringify(arrObject),
                        success: function (res) {
                            if (res.error) {
                                var iconMessage = "error";
                            } else {
                                var iconMessage = "success";
                            }
                            setTimeout(function () {
                                swal(res.message, "", iconMessage).then(function (result) {
                                    if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                        window.location.href = laroute.route('admin.notification');
                                    }
                                    if (result.value == true) {
                                        if (is_quit === 0) {
                                            window.location.reload();
                                        } else {
                                            window.location.href = laroute.route('admin.notification');
                                        }
                                    }
                                });
                            }, 1500);
                        },
                        error: function (res) {
                            var mess_error = '';
                            jQuery.each(res.responseJSON.errors, function (key, val) {
                                mess_error = mess_error.concat(val + '<br/>');
                            });
                            swal("", mess_error, "error");
                        }
                    });
                }
                // $.post(laroute.route('admin.notification.store'), $('#form-add').serialize(), function (res) {
                //     if (res.error == true) {
                //         swal.fire(res.message, "", "error")
                //             .then(function (result) {
                //                 if (result.value) {
                //                     window.location.href = laroute.route('admin.notification');
                //                 }
                //             });
                //     } else {
                //         swal.fire(res.message, "", "success").then(function (result) {
                //             if (result.value == true) {
                //                 window.location.href = laroute.route('admin.notification');
                //             }
                //         });
                //     }
                // });
            });
        });
    },
    submit_edit: function () {
        $(".invalid-feedback").remove();
        $.getJSON(laroute.route('translate'), function (json1) {
            $.getJSON('/admin/validation', function (json) {
                var form = $('#form-edit');
                rules = {
                    title: {required: true, maxlength: 255},
                    short_title: {required: true, maxlength: 255},
                    feature: {required: true, maxlength: 255},
                    cost:{
                        required:true
                    }
                };

                messages = {
                    title: {
                        required: json.notification.title_required,
                        maxlength: json.notification.max_255
                    },
                    short_title: {
                        required: json.notification.short_title_required,
                        maxlength: json.notification.max_255
                    },
                    feature: {
                        required: json.notification.feature_required,
                        maxlength: json.notification.max_255
                    },
                    cost: {
                        required: json.notification.cost,
                    }
                };

                form.validate({
                    rules: rules,
                    messages: messages
                });

                if (!form.valid()) {
                    return false;
                }
                var is_deal_created = 0;
                var arrObject = [];
                if($('#is_deal_created').is(":checked")){
                    is_deal_created = 1;
                }
                if(is_deal_created == 1){
                    if($('#pipeline_code').val() == ''){
                        swal(json1['Hãy chọn pipeline'], '', "error");
                        return;
                    }
                    if($('#end_date_expected').val() == ''){
                        swal(json1['Hãy chọn ngày kết thúc dự kiến'], '', "error");
                        return;
                    }
                }
                if(is_deal_created == 1){

                    // check object
                    $.each($('#table_add > tbody').find('.add-object'), function () {

                        var object_type = $(this).find($('.object_type')).val();
                        var object_code = $(this).find($('.object_code')).val();

                        if (object_type == "") {
                            swal(json1['Vui lòng chọn loại'], '', "error");
                            $(this).find($('.error_object_type')).text(json1['Vui lòng chọn loại sản phẩm']);
                            return;
                        } else {
                            $(this).find($('.error_object_type')).text('');
                        }
                        if (object_code == "") {
                            swal(json1['Vui lòng chọn sản phẩm'], '', "error");
                            $(this).find($('.error_object')).text(json1['Vui lòng chọn sản phẩm']);
                            return;
                        } else {
                            $(this).find($('.error_object')).text('');
                        }
                    });

                    // Lấy danh sách object (nếu có)
                    $.each($('#table_add > tbody').find('.add-object'), function () {

                        var object_type = $(this).find($('.object_type')).val();
                        var object_name = $(this).find($('.object_code')).text();
                        var object_code = $(this).find($('.object_code')).val();
                        var object_id = $(this).find($('.object_id')).val();
                        var price = $(this).find($('.object_price')).val();
                        var quantity = $(this).find($('.object_quantity')).val();
                        var discount = $(this).find($('.object_discount')).val();
                        var amount = $(this).find($('.object_amount')).val();

                        arrObject.push({
                            object_type: object_type,
                            object_name: object_name,
                            object_code: object_code,
                            object_id: object_id,
                            price: price,
                            quantity: quantity,
                            discount: discount,
                            amount: amount
                        });
                    });
                }
                console.log(is_deal_created, JSON.stringify(arrObject));
                $.ajax({
                    url: laroute.route('admin.notification.update', {id:$("#noti-id").val()}),
                    method: 'POST',
                    dataType: 'JSON',
                    data: $('#form-edit').serialize() + "&is_deal_created="+is_deal_created+"&arrObject=" +  JSON.stringify(arrObject),
                    success: function (res) {
                        if (res.error) {
                            var iconMessage = "error";
                        } else {
                            var iconMessage = "success";
                        }
                        setTimeout(function () {
                            swal.fire(res.message, "", iconMessage)
                                .then(function (result) {
                                    if (result.value) {
                                        window.location.href = laroute.route('admin.notification');
                                    }
                                });
                        }, 1500);
                    },
                    error: function (res) {
                        var mess_error = '';
                        jQuery.each(res.responseJSON.errors, function (key, val) {
                            mess_error = mess_error.concat(val + '<br/>');
                        });
                        swal.fire(mess_error, "", "error");
                    }
                });
            });
        });
    },
    popupSuccess: function(){
        $.getJSON(laroute.route('translate'), function (json) {
            if($('#end_point_detail_click').val() != ""){
                swal.fire(json['Thêm đích đến chi tiết thành công'], "", "success");
            }
        });
        $('#modal-end-point').modal('hide');
    },

    closeModalDeal: function () {
        $('#modal-detail').modal('hide');
    },

    addObject: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            stt++;
            var tpl = $('#tpl-object').html();
            tpl = tpl.replace(/{stt}/g, stt);
            $('.append-object').append(tpl);
            $('.object_type').select2({
                placeholder: json['Chọn loại']
            });

            $('.object_code').select2({
                placeholder: json['Chọn đối tượng']
            });

            $(".object_quantity").TouchSpin({
                initval: 1,
                min: 1,
                buttondown_class: "btn btn-default down btn-ct",
                buttonup_class: "btn btn-default up btn-ct"

            });

            // Tính lại giá khi thay đổi số lượng
            $('.object_quantity, .object_discount').change(function () {
                $(this).closest('tr').find('.object_amount').empty();
                var type = $(this).closest('tr').find('.object_type').val();
                var id_type = 0;
                if (type === "product") {
                    id_type = 1;
                } else if (type === "service") {
                    id_type = 2;
                } else if (type === "service_card") {
                    id_type = 3;
                }
                var price = $(this).closest('tr').find('input[name="object_price"]').val().replace(new RegExp('\\,', 'g'), '');
                var discount = $(this).closest('tr').find('input[name="object_discount"]').val();
                var loc = discount.replace(new RegExp('\\,', 'g'), '');
                var quantity = $(this).closest('tr').find('input[name="object_quantity"]').val();

                var amount = ((price * quantity) - loc);

                $(this).closest('tr').find('.object_amount').val(formatNumber(amount.toFixed(decimal_number)));


                $('#amount').empty();
                $('#amount-remove').html('');
                $('#amount-remove').append(`<input type="text" class="form-control m-input" id="amount" name="amount">`);
                var sum = 0;
                $.each($('#table_add > tbody').find('input[name="object_amount"]'), function () {
                    sum += Number($(this).val().replace(new RegExp('\\,', 'g'), ''));
                });
                $('#amount').val(formatNumber(sum.toFixed(decimal_number)));
                new AutoNumeric.multiple('#amount', {
                    currencySymbol: '',
                    decimalCharacter: '.',
                    digitGroupSeparator: ',',
                    decimalPlaces: decimal_number,
                    eventIsCancelable: true,
                    minimumValue: 0
                });

            });

            new AutoNumeric.multiple('#object_discount_' + stt + '', {
                currencySymbol: '',
                decimalCharacter: '.',
                digitGroupSeparator: ',',
                decimalPlaces: decimal_number,
                eventIsCancelable: true,
                minimumValue: 0
            });
        });
    },

    removeObject: function (obj) {
        $(obj).closest('.add-object').remove();
        $('#amount').empty();
        $('#amount-remove').html('');
        $('#amount-remove').append(`<input type="text" class="form-control m-input" id="amount" name="amount">`);
        var sum = 0;
        $.each($('#table_add > tbody').find('input[name="object_amount"]'), function () {
            sum += Number($(this).val().replace(new RegExp('\\,', 'g'), ''));
        });
        $('#amount').val(formatNumber(sum.toFixed(decimal_number)));
        new AutoNumeric.multiple('#amount', {
            currencySymbol: '',
            decimalCharacter: '.',
            digitGroupSeparator: ',',
            decimalPlaces: decimal_number,
            eventIsCancelable: true,
            minimumValue: 0
        });

    },

    changeObjectType: function (obj) {
        $.getJSON(laroute.route('translate'), function (json) {
            var object = $(obj).val();
            // product, service, service_card
            $(obj).closest('tr').find('.object_code').prop('disabled', false);
            $(obj).closest('tr').find('.object_code').val('').trigger('change');

            $(obj).closest('tr').find('.object_code').select2({
                width: '100%',
                placeholder: json['Chọn đối tượng'],
                ajax: {
                    url: laroute.route('customer-lead.customer-deal.load-object'),
                    data: function (params) {
                        return {
                            search: params.term,
                            page: params.page || 1,
                            type: $(obj).val()
                        };
                    },
                    dataType: 'json',
                    method: 'POST',
                    processResults: function (data) {
                        data.page = data.page || 1;
                        return {
                            results: data.items.map(function (item) {
                                if ($(obj).val() == 'product') {
                                    return {
                                        id: item.product_code,
                                        text: item.product_child_name,
                                        code: item.product_code
                                    };
                                } else if ($(obj).val() == 'service') {
                                    return {
                                        id: item.service_code,
                                        text: item.service_name,
                                        code: item.service_code
                                    };
                                } else if ($(obj).val() == 'service_card') {
                                    return {
                                        id: item.code,
                                        text: item.card_name,
                                        code: item.code
                                    };
                                }
                            }),
                            pagination: {
                                more: data.pagination
                            }
                        };
                    },
                }
            });
        });
    },

    changeObject: function (obj) {
        var object_type = $(obj).closest('tr').find('.object_type').val();
        var object_code = $(obj).val();

        //get price of object
        $.ajax({
            url: laroute.route('customer-lead.customer-deal.get-price-object'),
            dataType: 'JSON',
            data: {
                object_type: object_type,
                object_code: object_code,
            },
            method: 'POST',
            success: function (result) {
                if (Object.keys(result).length === 0) {
                    $(obj).closest('tr').find($('.object_price')).val(formatNumber(Number(0).toFixed(decimal_number)));
                    $(obj).closest('tr').find($('.object_amount')).val(formatNumber(Number(0).toFixed(decimal_number)));
                } else {
                    if (object_type == 'product') {
                        $(obj).closest('tr').find($('.object_price')).val(formatNumber(Number(result.price).toFixed(decimal_number)));
                        // Reset số lượng về 1, Tính lại tiền * số lượng
                        $(obj).closest('tr').find('.object_quantity').val(1);
                        $(obj).closest('tr').find('.object_amount').val(formatNumber(Number(result.price).toFixed(decimal_number)));
                        $(obj).closest('tr').find('.object_id').val(result.product_child_id);
                    } else if (object_type == 'service') {
                        $(obj).closest('tr').find($('.object_price')).val(formatNumber(Number(result.price_standard).toFixed(decimal_number)));
                        $(obj).closest('tr').find('.object_quantity').val(1);
                        $(obj).closest('tr').find('.object_amount').val(formatNumber(Number(result.price_standard).toFixed(decimal_number)));
                        $(obj).closest('tr').find('.object_id').val(result.service_id);
                    } else if (object_type == 'service_card') {
                        $(obj).closest('tr').find($('.object_price')).val(formatNumber(Number(result.price).toFixed(decimal_number)));
                        $(obj).closest('tr').find('.object_quantity').val(1);
                        $(obj).closest('tr').find('.object_amount').val(formatNumber(Number(result.price).toFixed(decimal_number)));
                        $(obj).closest('tr').find('.object_id').val(result.service_card_id);
                    }
                }

                // Tính lại tổng tiền
                $('#amount').empty();
                $('#amount-remove').html('');
                $('#amount-remove').append(`<input type="text" class="form-control m-input" id="amount" name="amount">`);
                var sum = 0;
                $.each($('#table_add > tbody').find('input[name="object_amount"]'), function () {
                    sum += Number($(this).val().replace(new RegExp('\\,', 'g'), ''));
                });
                $('#amount').val(formatNumber(sum.toFixed(decimal_number)));
                new AutoNumeric.multiple('#amount', {
                    currencySymbol: '',
                    decimalCharacter: '.',
                    digitGroupSeparator: ',',
                    decimalPlaces: decimal_number,
                    eventIsCancelable: true,
                    minimumValue: 0
                });
            }
        });


    },
};
var edit = {
    closeModalDeal: function () {
        $('#load-modal-edit').val(0);
        $('#modal-edit').modal('hide');
    },
    saveModalDeal: function(){
        $('#load-modal-edit').val(1);
        $('#modal-edit').modal('hide');
    },
    changeCreateDeal: function(){
        $('#switch_deal_created').val(1); // check có tắt chỗ tạo deal
        if($('#is_deal_created').is(":checked")){
            $('#popup_create_deal').removeAttr('hidden');
        }
        else{
            $('#popup_create_deal').attr('hidden', true);
            $('#load-modal-edit').val(0);
            $('#load-modal-create').val(0);
        }
    },
    popupEditLead: function(notification_template_id){
        $.getJSON(laroute.route('translate'), function (json) {
            if($('#switch_deal_created').val() == '1'){ // có bật/tắt => mở popup mới hoàn toàn
                $('#my-modal-edit').html('');
                let load = $('#load-modal-create').val();
                if(load == 1){
                    $('#modal-create').modal('show');
                }
                else{
                    $.ajax({
                        url: laroute.route('admin.notification.noti-popup-created-deal'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                        },
                        success: function (res) {
                            $('#my-modal-create').html(res.html);
                            $('#modal-create').modal('show');

                            $('#pipeline_code').select2({
                                placeholder: json['Chọn pipeline']
                            });

                            $('#journey_code').select2({
                                placeholder: json['Chọn hành trình']
                            });


                            $("#end_date_expected").datepicker({
                                todayHighlight: !0,
                                autoclose: !0,
                                format: "dd/mm/yyyy",
                                startDate: "dateToday"
                            });
                            $('#pipeline_code').change(function () {
                                $.ajax({
                                    url: laroute.route('customer-lead.load-option-journey'),
                                    dataType: 'JSON',
                                    data: {
                                        pipeline_code: $('#pipeline_code').val(),
                                    },
                                    method: 'POST',
                                    success: function (res) {
                                        $('.journey').empty();
                                        $.map(res.optionJourney, function (a) {
                                            $('.journey').append('<option value="' + a.journey_code + '">' + a.journey_name + '</option>');
                                        });
                                    }
                                });
                            });

                            new AutoNumeric.multiple('#amount', {
                                currencySymbol: '',
                                decimalCharacter: '.',
                                digitGroupSeparator: ',',
                                decimalPlaces: decimal_number,
                                eventIsCancelable: true,
                                minimumValue: 0
                            });
                        }
                    });
                }
            }
            else{
                $('#my-modal-create').html('');
                let load = $('#load-modal-edit').val();
                if(load == 1){
                    $('#modal-edit').modal('show');
                }
                else{
                    $.ajax({
                        url: laroute.route('admin.notification.noti-popup-edit-deal'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            'notification_template_id' : notification_template_id
                        },
                        success: function (res) {
                            $('#my-modal-edit').html(res.html);
                            $('#modal-edit').modal('show');

                            $('#pipeline_code').select2({
                                placeholder: json['Chọn pipeline']
                            });

                            $('#journey_code').select2({
                                placeholder: json['Chọn hành trình']
                            });


                            $(".object_quantity").TouchSpin({
                                initval: 1,
                                min: 1,
                                buttondown_class: "btn btn-default down btn-ct",
                                buttonup_class: "btn btn-default up btn-ct"

                            });
                            $("#end_date_expected").datepicker({
                                todayHighlight: !0,
                                autoclose: !0,
                                format: "dd/mm/yyyy",
                                startDate: "dateToday"
                            });
                            $('#pipeline_code').change(function () {
                                $.ajax({
                                    url: laroute.route('customer-lead.load-option-journey'),
                                    dataType: 'JSON',
                                    data: {
                                        pipeline_code: $('#pipeline_code').val(),
                                    },
                                    method: 'POST',
                                    success: function (res) {
                                        $('.journey').empty();
                                        $.map(res.optionJourney, function (a) {
                                            $('.journey').append('<option value="' + a.journey_code + '">' + a.journey_name + '</option>');
                                        });
                                    }
                                });
                            });

                            new AutoNumeric.multiple('#amount', {
                                currencySymbol: '',
                                decimalCharacter: '.',
                                digitGroupSeparator: ',',
                                decimalPlaces: decimal_number,
                                eventIsCancelable: true,
                                minimumValue: 0
                            });
                        }
                    });
                }
            }
        });
    },
};
var dealNoti = {
    addObject: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            stt++;
            var tpl = $('#tpl-object').html();
            tpl = tpl.replace(/{stt}/g, stt);
            $('.append-object').append(tpl);
            $('.object_type').select2({
                placeholder: json['Chọn loại']
            });

            $('.object_code').select2({
                placeholder: json['Chọn đối tượng']
            });

            $(".object_quantity").TouchSpin({
                initval: 1,
                min: 1,
                buttondown_class: "btn btn-default down btn-ct",
                buttonup_class: "btn btn-default up btn-ct"

            });

            // Tính lại giá khi thay đổi số lượng
            $('.object_quantity, .object_discount').change(function () {
                $(this).closest('tr').find('.object_amount').empty();
                var type = $(this).closest('tr').find('.object_type').val();
                var id_type = 0;
                if (type === "product") {
                    id_type = 1;
                } else if (type === "service") {
                    id_type = 2;
                } else if (type === "service_card") {
                    id_type = 3;
                }
                var price = $(this).closest('tr').find('input[name="object_price"]').val().replace(new RegExp('\\,', 'g'), '');
                var discount = $(this).closest('tr').find('input[name="object_discount"]').val();
                var loc = discount.replace(new RegExp('\\,', 'g'), '');
                var quantity = $(this).closest('tr').find('input[name="object_quantity"]').val();

                var amount = ((price * quantity) - loc);

                $(this).closest('tr').find('.object_amount').val(formatNumber(amount.toFixed(decimal_number)));


                $('#amount').empty();
                $('#amount-remove').html('');
                $('#amount-remove').append(`<input type="text" class="form-control m-input" id="amount" name="amount">`);
                var sum = 0;
                $.each($('#table_add > tbody').find('input[name="object_amount"]'), function () {
                    sum += Number($(this).val().replace(new RegExp('\\,', 'g'), ''));
                });
                $('#amount').val(formatNumber(sum.toFixed(decimal_number)));
                new AutoNumeric.multiple('#amount', {
                    currencySymbol: '',
                    decimalCharacter: '.',
                    digitGroupSeparator: ',',
                    decimalPlaces: decimal_number,
                    eventIsCancelable: true,
                    minimumValue: 0
                });

            });

            new AutoNumeric.multiple('#object_discount_' + stt + '', {
                currencySymbol: '',
                decimalCharacter: '.',
                digitGroupSeparator: ',',
                decimalPlaces: decimal_number,
                eventIsCancelable: true,
                minimumValue: 0
            });
        });
    },

    removeObject: function (obj) {
        $(obj).closest('.add-object').remove();
        $('#amount').empty();
        $('#amount-remove').html('');
        $('#amount-remove').append(`<input type="text" class="form-control m-input" id="amount" name="amount">`);
        var sum = 0;
        $.each($('#table_add > tbody').find('input[name="object_amount"]'), function () {
            sum += Number($(this).val().replace(new RegExp('\\,', 'g'), ''));
        });
        $('#amount').val(formatNumber(sum.toFixed(decimal_number)));
        new AutoNumeric.multiple('#amount', {
            currencySymbol: '',
            decimalCharacter: '.',
            digitGroupSeparator: ',',
            decimalPlaces: decimal_number,
            eventIsCancelable: true,
            minimumValue: 0
        });

    },

    changeObjectType: function (obj) {
        $.getJSON(laroute.route('translate'), function (json) {
            var object = $(obj).val();
            // product, service, service_card
            $(obj).closest('tr').find('.object_code').prop('disabled', false);
            $(obj).closest('tr').find('.object_code').val('').trigger('change');

            $(obj).closest('tr').find('.object_code').select2({
                width: '100%',
                placeholder: json['Chọn đối tượng'],
                ajax: {
                    url: laroute.route('customer-lead.customer-deal.load-object'),
                    data: function (params) {
                        return {
                            search: params.term,
                            page: params.page || 1,
                            type: $(obj).val()
                        };
                    },
                    dataType: 'json',
                    method: 'POST',
                    processResults: function (data) {
                        data.page = data.page || 1;
                        return {
                            results: data.items.map(function (item) {
                                if ($(obj).val() == 'product') {
                                    return {
                                        id: item.product_code,
                                        text: item.product_child_name,
                                        code: item.product_code
                                    };
                                } else if ($(obj).val() == 'service') {
                                    return {
                                        id: item.service_code,
                                        text: item.service_name,
                                        code: item.service_code
                                    };
                                } else if ($(obj).val() == 'service_card') {
                                    return {
                                        id: item.code,
                                        text: item.card_name,
                                        code: item.code
                                    };
                                }
                            }),
                            pagination: {
                                more: data.pagination
                            }
                        };
                    },
                }
            });
        });
    },

    changeObject: function (obj) {
        var object_type = $(obj).closest('tr').find('.object_type').val();
        var object_code = $(obj).val();

        //get price of object
        $.ajax({
            url: laroute.route('customer-lead.customer-deal.get-price-object'),
            dataType: 'JSON',
            data: {
                object_type: object_type,
                object_code: object_code,
            },
            method: 'POST',
            success: function (result) {
                if (Object.keys(result).length === 0) {
                    $(obj).closest('tr').find($('.object_price')).val(formatNumber(Number(0).toFixed(decimal_number)));
                    $(obj).closest('tr').find($('.object_amount')).val(formatNumber(Number(0).toFixed(decimal_number)));
                } else {
                    if (object_type == 'product') {
                        $(obj).closest('tr').find($('.object_price')).val(formatNumber(Number(result.price).toFixed(decimal_number)));
                        // Reset số lượng về 1, Tính lại tiền * số lượng
                        $(obj).closest('tr').find('.object_quantity').val(1);
                        $(obj).closest('tr').find('.object_amount').val(formatNumber(Number(result.price).toFixed(decimal_number)));
                        $(obj).closest('tr').find('.object_id').val(result.product_child_id);
                    } else if (object_type == 'service') {
                        $(obj).closest('tr').find($('.object_price')).val(formatNumber(Number(result.price_standard).toFixed(decimal_number)));
                        $(obj).closest('tr').find('.object_quantity').val(1);
                        $(obj).closest('tr').find('.object_amount').val(formatNumber(Number(result.price_standard).toFixed(decimal_number)));
                        $(obj).closest('tr').find('.object_id').val(result.service_id);
                    } else if (object_type == 'service_card') {
                        $(obj).closest('tr').find($('.object_price')).val(formatNumber(Number(result.price).toFixed(decimal_number)));
                        $(obj).closest('tr').find('.object_quantity').val(1);
                        $(obj).closest('tr').find('.object_amount').val(formatNumber(Number(result.price).toFixed(decimal_number)));
                        $(obj).closest('tr').find('.object_id').val(result.service_card_id);
                    }
                }

                // Tính lại tổng tiền
                $('#amount').empty();
                $('#amount-remove').html('');
                $('#amount-remove').append(`<input type="text" class="form-control m-input" id="amount" name="amount">`);
                var sum = 0;
                $.each($('#table_add > tbody').find('input[name="object_amount"]'), function () {
                    sum += Number($(this).val().replace(new RegExp('\\,', 'g'), ''));
                });
                $('#amount').val(formatNumber(sum.toFixed(decimal_number)));
                new AutoNumeric.multiple('#amount', {
                    currencySymbol: '',
                    decimalCharacter: '.',
                    digitGroupSeparator: ',',
                    decimalPlaces: decimal_number,
                    eventIsCancelable: true,
                    minimumValue: 0
                });
            }
        });


    }
};
function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}
