var jsonLang = [];
$.getJSON(laroute.route('translate'), function (json) {
    jsonLang = json;
});

$('#autotable').PioTable({
    baseUrl: laroute.route('kpi.criteria.list')
});

$.validator.addMethod("onlyVietnamese", function(value, element) {
    return this.optional(element) || /^[a-zA-Z_ÀÁÂÃÈÉÊẾÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêếìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂưăạảấầẩẫậắằẳẵặẹẻẽềềểỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ\ ]+$/i.test( value );
}, "Only Vietnamese Charater");

var KpiCriteria = {
    remove: function (obj, id) {
        // hightlight row
        $(obj).closest('tr').addClass('m-table__row--danger');
        swal({
            title: jsonLang["Thông báo"],
            text: jsonLang["Bạn xác nhận muốn xóa tiêu chí này?"],
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: jsonLang["Xóa"],
            cancelButtonText: jsonLang["Hủy"],
            onClose: function () {
                // remove hightlight row
                $(obj).closest('tr').removeClass('m-table__row--danger');
            }
        }).then(function (result) {
            if (result.value) {
                $.post(laroute.route('kpi.criteria.remove', { id: id }), function () {
                    swal(
                        jsonLang["Xóa thành công"],
                        '',
                        'success'
                    );
                    // window.location.reload();
                    $('#autotable').PioTable('refresh');
                });
            }
        });
    },
};

$(document).ready(function () {
    $(document).on('click', '.btn-add-criteria', function(e) {
        e.preventDefault();
        $('#popup-add').modal('show');
    })

    $('#frm-add-criteria').submit(function (e) {
        e.preventDefault();
        let url = $(this).data("route");

        formData = jQuery("#frm-add-criteria").serializeArray();

        var form = $('#frm-add-criteria');
        form.validate({
            rules: {
                kpi_criteria_name: {
                    required: true,
                    onlyVietnamese: true,
                    maxlength: 100
                },
                kpi_criteria_unit_id: {
                    required: true,
                }
            },
            messages: {
                kpi_criteria_name: {
                    required: jsonLang["Tên tiêu chí là trường bắt buộc phải nhập"],
                    onlyVietnamese: jsonLang["Không được nhập chữ số, kí tự đặc biệt"],
                    maxlength: jsonLang["Tên tiêu chí có tối đa 100 kí tự"]
                },
                kpi_criteria_unit_id: {
                    required: jsonLang["Đơn vị là trường bắt buộc phải chọn"],
                }
            }
        });
    
        if (!form.valid()) {
            return false;
        }

        $.post(url, formData, function (resp) {
            console.log(resp);
            if (resp.error == 0) {
                swal(resp.message, "", "success");
                window.location.reload();
            }
            else {
                swal(resp.message, "", "error");
            }
        });
    });

    $(document).on('click', '.btn-edit-criteria', function (e) {
        e.preventDefault();
        $('#frm-edit-criteria')[0].reset();
        let kpi_criteria_id    = $(this).data('id');
        let kpi_criteria_name  = $(this).data('name');
        let unit               = $(this).data('unit');
        let description        = $(this).data('description');
        let kpi_criteria_trend = $(this).data('trend');
        let customize          = $(this).data('customize');
        let is_blocked         = $(this).data('blocked');
        let status             = $(this).data('status');
        let lead               = $(this).data('lead');

        $('#frm-edit-criteria').attr('data-id', kpi_criteria_id);
        console.log(customize);
        if (customize == 0) {
            $('#kpi_criteria_name').val(jsonLang[kpi_criteria_name]);
        } else {
            $('#kpi_criteria_name').val(kpi_criteria_name);
        }
        $('#kpi_criteria_unit_id').val(unit); 
        $('#description').val(description);

        if (parseFloat(kpi_criteria_trend) == 0) {
            $('#kpi_criteria_trend_down').prop("checked", true);
        } else {
            $('#kpi_criteria_trend_up').prop("checked", true);
        }

        if (is_blocked === 1) {
            $('#is_blocked').attr('checked', true);
        }

        if (status === 1) {
            $('#status').attr('checked', true);
        }

        if (lead === 1) {
            url = laroute.route('kpi.criteria.lead-option');
            var pipelineOptionHtml = '';
            var journeyOptionHtml = '';
            var pipelineId = $(this).data('pipeline');
            var journeyId = $(this).data('journey');

            if (pipelineId) {
                var data = {pipelineId: pipelineId};
            }

            $.ajax({
                url: url,
                type: "POST",
                data: data,
                async: false,
                success: function(resp){
                    if (resp) {
                        $.each(resp.pipeline, function(id, val) {
                            pipelineOptionHtml += '<option value="'+id+'">'+val+'</option>';
                        });

                        $.each(resp.journey, function(id, val) {
                            journeyOptionHtml += '<option value="'+id+'">'+val+'</option>';
                        });
                    }
                }
            });

            $('#lead-row').show();
            $('#lead-row').html(
                '<div class="col-6">'+
                    '<label class="font-weight-bold">Pipeline: </label>'+
                    '<div class="input-group">'+
                        '<select class="form-control" name="pipeline_id" id="pipeline_id">'+
                            '<option value="">Chọn pipeline</option>'+
                            "'"+pipelineOptionHtml+"'"+
                        '</select>'+
                    '</div>'+
                '</div>'+

                '<div class="col-6">'+
                    '<label class="font-weight-bold">Hành trình: </label>'+
                    '<div class="input-group">'+
                        '<select class="form-control" name="journey_id" id="journey_id">'+
                            '<option value="">Chọn hành trình</option>'+
                            journeyOptionHtml+
                        '</select>'+    
                    '</div>'+
                '</div>'
            );
            $('#pipeline_id').val(pipelineId);
            $('#journey_id').val(journeyId);
        } else {
            $('#lead-row').html('');
            $('#lead-row').hide();
        }

        $('#popup-edit').modal('show');
    });

    $(document).on('change', '#pipeline_id', function () {
        var url               = laroute.route('kpi.criteria.lead-option');
        var data              = {pipelineId: (this.value)};
        var journeyOptionHtml = '';

        $.ajax({
            url: url,
            type: "POST",
            data: data,
            async: false,
            success: function(resp){
                if (resp) {
                    $.each(resp.journey, function(id, val) {
                        journeyOptionHtml += '<option value="'+id+'">'+val+'</option>';
                    });
                    $('#journey_id').html(journeyOptionHtml);
                    var journeyId = $(this).data('journey');
                    $('#journey_id').val(journeyId); 
                }
            }
        });
    });

    $('#frm-edit-criteria').submit(function (e) {
        e.preventDefault();
        let url = $(this).data("route");

        criteriaId = [{
            'name': 'kpi_criteria_id',
            'value': $(this).data("id")
        }]

        formData = jQuery("#frm-edit-criteria").serializeArray();
        formData = formData.concat(
            jQuery('#frm-edit-criteria input[type=checkbox]:not(:checked)').map(
                function () {
                    return { "name": this.name, "value": 0 }
                }).get()
        );
        formData = formData.concat(criteriaId);

        var form = $('#frm-edit-criteria');
        form.validate({
            rules: {
                kpi_criteria_name: {
                    required: true,
                }
            },
            messages: {
                kpi_criteria_name: {
                    required: jsonLang["Hãy nhập tên tiêu chí"]
                }
            }
        });
    
        if (!form.valid()) {
            return false;
        }

        $.post(url, formData, function (resp) {
            console.log(resp);
            if (resp.error == 0) {
                swal(resp.message, "", "success");
                window.location.reload();
            }
            else {
                swal(resp.message, "", "error");
            }
        });
    });

    $('#popup-edit').on('hidden.bs.modal', function(e) {
        $(this).find('#frm-edit-criteria').validate().resetForm();
    });
});