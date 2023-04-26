number = 0;
var create = {
    save: function () {
        var arrJourney = [];
        $.getJSON(laroute.route('translate'), function (json) {
            // check button save journey
            let checkSaveJourney = true;
            $.each($('#journey').find(".count-journey"), function () {
                let temp = $(this).find($('.edit_journey')).val();
                if(typeof temp === 'undefined') {
                    swal(json['Vui lòng hoàn tất hành trình'], "", "error");
                    checkSaveJourney = false;
                }
            });
            if (checkSaveJourney == false) {
                return false;
            }
            var form = $('#form-create-pipeline');
            form.validate({
                rules: {
                    pipeline_name: {
                        required: true
                    },
                    pipeline_cat: {
                        required: true
                    },
                    time_revoke_lead: {
                        required: true
                    },
                    owner_id: {
                        required: true
                    },
                },
                messages: {
                    pipeline_name: {
                        required: json['Hãy nhập tên pipeline'],
                    },
                    pipeline_cat: {
                        required: json['Vui lòng chọn danh mục pipeline'],
                    },
                    time_revoke_lead: {
                        required: json['Hãy nhập số ngày'],
                    },
                    owner_id: {
                        required: json['Hãy chọn chủ sở hữu'],
                    }
                },
            });

            if (!form.valid()) {
                return false;
            }
            // check default
            let is_default = $('#is_default').is(":checked");
            if(is_default == true) {
                is_default = 1;
            } else {
                is_default = 0;
            }

            // check each row journey customer
            var flag = true;
            var count = 0;
            $.each($('#journey').find(".count-journey"), function () {
                count ++;
                let check_journey_name = $(this).find($('.journey_name')).val();
                if (check_journey_name == '') {
                    flag = false;
                }
            });
            if (count <= 0) {
                swal(json['Chưa có hành trình nào'], "", "error");
                return false;
            }

            if (flag == true) {
                $.each($('#journey').find(".count-journey"), function () {
                    let journey_name = $(this).find($('.journey_name')).val();
                    let journey_code = $(this).find($('.journey_default_code')).val();
                    let status = $(this).find($('.status')).val();
                    let is_deal_created = $(this).find($('.is_deal_created')).is(":checked");
                    if(is_deal_created == true) {
                        is_deal_created = 1;
                    } else {
                        is_deal_created = 0;
                    }
                    let is_contract_created = $(this).find($('.is_contract_created')).is(":checked");
                    if(is_contract_created == true) {
                        is_contract_created = 1;
                    } else {
                        is_contract_created = 0;
                    }
                    arrJourney.push({
                        journey_name: journey_name,
                        journey_code: journey_code,
                        status: status,
                        is_deal_created: is_deal_created,
                        is_contract_created: is_contract_created,
                    });
                });
                $.ajax({
                    url: laroute.route('customer-lead.pipeline.store'),
                    data: {
                        pipeline_name: $('#pipeline_name').val(),
                        pipeline_cat: $('#pipeline_cat').val(),
                        time_revoke_lead: $('#time_revoke_lead').val(),
                        owner_id: $('#owner_id').val(),
                        is_default: is_default,
                        arrJourney: arrJourney
                    },
                    method: 'POST',
                    dataType: "JSON",
                    success: function (response) {
                        if (response.error == false) {
                            swal(response.message, "", "success");
                            window.location = laroute.route('customer-lead.pipeline');
                        } else {
                            swal(response.message, "", "error")
                        }
                    },
                    error: function (response) {
                        var mess_error = '';
                        $.map(response.responseJSON.errors, function (a) {
                            mess_error = mess_error.concat(a + '<br/>');
                        });
                        swal(json['Thêm mới thất bại'], mess_error, "error");
                    }
                });
            } else {
                swal(json['Hãy nhập tên hành trình'], "", "error");
                return false;
            }
        });
    },
    addJourney: function () {
        number++;
        var tpl = $('#append-input').html();
        tpl = tpl.replace(/{number}/g, number);
        if($('#pipeline_cat').val() != 'CUSTOMER'){
            tpl = tpl.replace(/{hidden}/g, 'hidden');
            tpl = tpl.replace(/{hidden_deal}/g, '');
        }
        else{
            tpl = tpl.replace(/{hidden}/g, '');
            tpl = tpl.replace(/{hidden_deal}/g, 'hidden');
        }
        $('.append-journey').append(tpl);

        var countDiv = $('.count-journey').length;

        $('#position_default_2').val(countDiv - 1);
        $('#position_default_3').val(countDiv);

        //Lay value option journey status
        var arrJourneyName = [];
        $.each($('#journey').find(".count-journey"), function () {
            let check_journey_name = $(this).find($('.journey_name')).val();
            var numberRow = $(this).find($('.number')).val();

            $(this).find($('.journey_name')).prop('disabled', true);
            $(this).find($('.status')).prop('disabled', true);
            $(this).find('.save_journey').remove();
            $(this).find('.edit_journey').remove();

            var tpl1 = $('#edit-row-tpl').html();
            $(this).find('.row_icon').prepend(tpl1);

            if (number == numberRow) {
                $('.error_journey_name_' + number + '').closest('.mt-2').find('.journey_name').prop('disabled', false);
                $('.error_journey_name_' + number + '').closest('.mt-2').find('.status').prop('disabled', false);
                $('.error_journey_name_' + number + '').closest('.mt-2').find('.edit_journey').remove();

                var tpl2 = $('#save-row-tpl').html();
                $('.error_journey_name_' + number + '').closest('.mt-2').find('.row_icon').prepend(tpl2);
            }

            if (check_journey_name != '') {
                arrJourneyName.push(check_journey_name);
            }
        });

        var statusObj = $('.error_journey_name_' + number + '').closest('.mt-2').find('.status').val();
        $('.error_journey_name_' + number + '').closest('.mt-2').find('.status').empty();

        $.map(arrJourneyName, function (value) {
            if (jQuery.inArray(value, statusObj) == -1) {
                $('.error_journey_name_' + number + '')
                    .closest('.mt-2').find('.status').append('<option value="' + value + '">' + value + '</option>');
            } else {
                $('.error_journey_name_' + number + '')
                    .closest('.mt-2').find('.status').append('<option value="' + value + '" selected>' + value + '</option>');
            }
        });
        $.getJSON(laroute.route('translate'), function (json) {
            $('.status').select2({
                placeholder: json['Chọn trạng thái chuyển đổi'],
            });
        });

    },
    removeJourney: function (obj) {
        $(obj).closest('.add-input').remove();
        var countDiv = $('.mt-2').length;

        $('#position_default_2').val(countDiv - 1);
        $('#position_default_3').val(countDiv);

        //Lay value option journey status
        var arrJourneyName = $('.journey_name').map(function (idx, elem) {
            return $(elem).val();
        }).get()

        $.each($('#journey').find(".count-journey"), function () {
            let status = $(this).find($('.status')).val();
            let statusObject = $(this).find($('.status'));
            var journeyName = $(this).find($('.journey_name')).val();

            $(this).find($('.status')).empty();

            $.map(arrJourneyName, function (value) {
                if (journeyName != value) {
                    if (jQuery.inArray(value, status) == -1) {
                        statusObject.append('<option value="' + value + '">' + value + '</option>');
                    } else {
                        statusObject.append('<option value="' + value + '" selected>' + value + '</option>');
                    }
                }
            });
        });
    },
    editJourney: function (obj) {
        $(obj).closest('.mt-2').find('.journey_name, .status').prop('disabled', false);

        var tpl = $('#save-row-tpl').html();
        $(obj).closest('.mt-2').find('.row_icon').prepend(tpl);

        $(obj).remove();
    },
    saveJourney: function (obj) {
        $(obj).closest('.mt-2').find('.journey_name, .status').prop('disabled', true);

        //Lay value option journey status
        var arrJourneyName = $('.journey_name').map(function (idx, elem) {
            return $(elem).val();
        }).get()

        $.each($('#journey').find(".count-journey"), function () {
            let status = $(this).find($('.status')).val();
            let statusObject = $(this).find($('.status'));
            var journeyName = $(this).find($('.journey_name')).val();

            $(this).find($('.status')).empty();

            $.map(arrJourneyName, function (value) {
                if (journeyName != value) {
                    if (jQuery.inArray(value, status) == -1) {
                        statusObject.append('<option value="' + value + '">' + value + '</option>');
                    } else {
                        statusObject.append('<option value="' + value + '" selected>' + value + '</option>');
                    }
                }
            });
        });

        var tpl = $('#edit-row-tpl').html();
        $(obj).closest('.mt-2').find('.row_icon').prepend(tpl);

        $(obj).remove();
    },
}
var listRemove = [];
var edit = {
    save: function (pipeline_id) {
        $.getJSON(laroute.route('translate'), function (json) {
            // check button save journey
            let checkSaveJourney = true;
            $.each($('#journey').find(".count-journey"), function () {
                let temp = $(this).find($('.edit_journey')).val();
                if(typeof temp === 'undefined') {
                    swal(json['Vui lòng hoàn tất hành trình'], "", "error");
                    checkSaveJourney = false;
                }
            });
            if (checkSaveJourney == false) {
                return false;
            }
            var arrJourney = [];
            var form = $('#form-create-pipeline');
            form.validate({
                rules: {
                    pipeline_name: {
                        required: true
                    },
                    pipeline_cat: {
                        required: true
                    },
                    time_revoke_lead: {
                        required: true
                    },
                    owner_id: {
                        required: true
                    }
                },
                messages: {
                    pipeline_name: {
                        required: json['Hãy nhập tên pipeline'],
                    },
                    pipeline_cat: {
                        required: json['Vui lòng chọn danh mục pipeline'],
                    },
                    time_revoke_lead: {
                        required: json['Hãy nhập số ngày'],
                    },
                    owner_id: {
                        required: json['Hãy chọn chủ sở hữu'],
                    }
                },
            });

            if (!form.valid()) {
                return false;
            }
            // check default
            let is_default = $('#is_default').is(":checked");
            if(is_default == true) {
                is_default = 1;
            } else {
                is_default = 0;
            }
            // check each row journey customer
            var flag = true;
            $.each($('#journey').find(".count-journey"), function () {
                let check_journey_name = $(this).find($('.journey_name')).val();
                let stt = $(this).find($('.number')).val();
                if (check_journey_name == '') {
                    flag = false;
                } else {
                }
            });

            if (flag == true) {
                $.each($('#journey').find(".count-journey"), function () {
                    let journey_name = $(this).find($('.journey_name')).val();
                    let status = $(this).find($('.status')).val();
                    let journey_code = $(this).find($('.journey_code')).val();
                    let is_deal_created = $(this).find($('.is_deal_created')).is(":checked");
                    if(is_deal_created == true) {
                        is_deal_created = 1;
                    } else {
                        is_deal_created = 0;
                    }
                    let is_contract_created = $(this).find($('.is_contract_created')).is(":checked");
                    if(is_contract_created == true) {
                        is_contract_created = 1;
                    } else {
                        is_contract_created = 0;
                    }
                    console.log(journey_code);
                    arrJourney.push({
                        journey_name: journey_name,
                        status: status,
                        journey_code: journey_code,
                        is_deal_created: is_deal_created,
                        is_contract_created: is_contract_created,
                    });
                });
                $.ajax({
                    url: laroute.route('customer-lead.pipeline.update'),
                    data: {
                        pipeline_id: pipeline_id,
                        pipeline_code:  $('#pipeline_code').val(),
                        pipeline_name: $('#pipeline_name').val(),
                        pipeline_cat: $('#pipeline_cat').val(),
                        time_revoke_lead: $('#time_revoke_lead').val(),
                        owner_id: $('#owner_id').val(),
                        is_default: is_default,
                        arrJourney: arrJourney,
                        listRemove: listRemove
                    },
                    method: 'POST',
                    dataType: "JSON",
                    success: function (response) {
                        if (response.error == false) {
                            swal(response.message, "", "success");
                            window.location = laroute.route('customer-lead.pipeline');
                        } else {
                            swal(response.message, "", "error")
                        }
                    },
                    error: function (response) {
                        var mess_error = '';
                        $.map(response.responseJSON.errors, function (a) {
                            mess_error = mess_error.concat(a + '<br/>');
                        });
                        swal(json['Chỉnh sửa thất bại'], mess_error, "error");
                    }
                });
            } else {
                swal(json['Hãy nhập tên hành trình'], "", "error");
                return false;
            }
        });
    },
    removeJourneyOld : function (obj, journey_code) {
        $.ajax({
            url: laroute.route('customer-lead.pipeline.check-journey-used'),
            data: {
                pipeline_code: $('#pipeline_code').val(),
            },
            method: 'POST',
            dataType: "JSON",
            success: function (response) {
                if (response.error == false) {
                    // push in array listRemove
                    listRemove.push(journey_code);
                    $(obj).closest('.add-input').remove();
                    var countDiv = $('.mt-2').length;

                    $('#position_default_2').val(countDiv - 1);
                    $('#position_default_3').val(countDiv);

                    //Lay value option journey status
                    var arrJourneyName = $('.journey_name').map(function (idx, elem) {
                        return $(elem).val();
                    }).get()

                    $.each($('#journey').find(".count-journey"), function () {
                        let status = $(this).find($('.status')).val();
                        let statusObject = $(this).find($('.status'));
                        var journeyName = $(this).find($('.journey_name')).val();

                        $(this).find($('.status')).empty();

                        $.map(arrJourneyName, function (value) {
                            if (journeyName != value) {
                                if (jQuery.inArray(value, status) == -1) {
                                    statusObject.append('<option value="' + value + '">' + value + '</option>');
                                } else {
                                    statusObject.append('<option value="' + value + '" selected>' + value + '</option>');
                                }
                            }
                        });
                    });
                } else {
                    swal(response.message, "", "error")
                }
            },
        });
        return false;
    }
}

var list = {
    _init: function () {
        $('#autotable').PioTable({
            baseUrl: laroute.route('customer-lead.pipeline.list')
        });
    },
    remove:function (pipelineId, is_default) {
        $.getJSON(laroute.route('translate'), function (json) {
            // check pipeline default
            if(is_default == 1) {
                Swal.fire({
                    type: 'error',
                    text: json['Bạn không thể xoá pipeline mặc định!'],
                });
                return false;
            }
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
                        url: laroute.route('customer-lead.pipeline.destroy'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            pipeline_id: pipelineId
                        },
                        success: function (res) {
                            if (res.error == false) {
                                swal.fire(res.message, "", "success");
                                window.location = laroute.route('customer-lead.pipeline');
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
function setDefault(pipeline_id,  pipeline_category) {
    $.ajax({
        url: laroute.route('customer-lead.pipeline.setDefaultPipeline'),
        data: {
            pipeline_id: pipeline_id,
            pipeline_category_code: pipeline_category,
        },
        method: 'POST',
        dataType: "JSON",
        success: function (response) {
            if (response.error == false) {
                swal(response.message, "", "success");
                window.location = laroute.route('customer-lead.pipeline');
            } else {
                swal(response.message, "", "error")
            }
        },
        error: function (response) {
            swal(json['Chỉnh sửa thất bại'], mess_error, "error");
        }
    });
}

function listDefaultJourney() {
    $.getJSON(laroute.route('translate'), function (json) {
        // get pipeline category code thong qua selected
        var pipeline_category_code = $('#pipeline_cat option:selected').val();
        // Xoa html cu
        $('#journey').empty();
        $('.deal-created').removeAttr('hidden');
        if(pipeline_category_code != 'CUSTOMER'){
            $('.deal-created').attr('hidden','hidden');
        }
        $('.contract-created').removeAttr('hidden');
        if(pipeline_category_code == 'CUSTOMER'){
            $('.contract-created').attr('hidden','hidden');
        }

        // Ajax load list default journey by pipeline category code
        $.ajax({
            url: laroute.route('customer-lead.pipeline.list-journey-default'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                pipeline_category_code: pipeline_category_code
            },
            success: function (res) {
                $('#journey').append(res.url);
                $('.status').select2({
                    placeholder: json['Chọn trạng thái chuyển đổi'],
                });
                $(".sortable").sortable({
                    connectWith: ".connectedSortable"
                }).disableSelection();
            }
        });
    });
}

$(document).ready(function () {
    $.getJSON(laroute.route('translate'), function (json) {
        $('#pipeline_cat').select2({
            placeholder: json['Chọn danh mục'],
        });
        $('.status').select2({
            placeholder: json['Chọn trạng thái chuyển đổi'],
        });
        $('#owner_id').select2({
            placeholder: json['Chọn chủ sở hữu'],
        });
    });
});

function isUINT(v) {
    var r = RegExp(/(^[^\-]{0,1})?(^[\d]*)$/);
    return r.test(v) && v.length > 0;
}

$(function () {
    $(".sortable").sortable({
        connectWith: ".connectedSortable"
    }).disableSelection();
});