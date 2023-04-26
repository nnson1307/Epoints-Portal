$('#autotable').PioTable({
    baseUrl: laroute.route('kpi.marketing.budget.month.list')
});

var jsonLang = [];
$.getJSON(laroute.route('translate'), function (json) {
    jsonLang = json;
});


var BudgetMarketing = {
    remove: function (obj, id) {
        // hightlight row
        $(obj).closest('tr').addClass('m-table__row--danger');
        swal({
            title: jsonLang['Thông báo'],
            text: jsonLang['Bạn xác nhận muốn xóa ngân sách tháng này?'],
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: jsonLang['Xóa'],
            cancelButtonText: jsonLang['Hủy'],
            onClose: function () {
                // remove hightlight row
                $(obj).closest('tr').removeClass('m-table__row--danger');
            }
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: laroute.route('kpi.marketing.budget.remove', { id: id }),
                    method: 'POST',
                    dataType: "JSON",
                    success: function (response) {
                        console.log(response);
                        if (response.error == 0) {
                            swal(response.message, "", "success");
                            window.location = laroute.route('kpi.marketing.budget.month');
                        } else {
                            swal(response.message, "", "error")
                        }
                    }
                });
            }
        });
    },
}

$(document).ready(function () {
    // Thêm ngân sách theo tháng
    $(document).on('click', '.btn-add-budget', function (e) {
        e.preventDefault();
        
        $("#department_add_allocate").prop("checked", true);
        $('.team-row').hide();
        $("#add_team_id").prop('disabled', true);

        $('#popup-add').modal('show');
    });

    // Trigger chọn thêm ngân sách theo phòng ban
    $("#department_add_allocate").on('change', function(){
        $(".team-row").hide();
        $("#add_team_id").prop('disabled', true);
    });

    // Trigger chọn thêm ngân sách theo nhóm
    $("#team_add_allocate").on('change', function(){
        $(".team-row").show();
        $("#add_team_id").prop('disabled', false);
        $("#add_team_id").val('');
    });

    // Trigger chọn phòng ban để load nhóm tương ứng
    $("#add_department_id").on('change', function(){
        var data = { 
            department_id: (this).value
        };
        var teamOptionHtml = '<option value="">' + jsonLang['Chọn nhóm'] + '</option>';
        $.ajax({
            url: laroute.route('kpi.note.team'),
            data: data,
            method: 'POST',
            dataType: "JSON",
            success: function (response) {
                response.forEach(element => {
                    teamOptionHtml += '<option value="' + element.team_id + '">' + element.team_name + '</option>'
                })
                $('#add_team_id').html(teamOptionHtml);
            }
        });
    });

    $("#add_budget, #budget").on('change', function() {
        $(this).val(Number(parseFloat($(this).val())).toLocaleString('en'));
    });

    $('#frm-add-budget').submit(function (e) {
        e.preventDefault();
        let url = $(this).data("route");
        formData = jQuery("#frm-add-budget").serializeArray();

        var form = $('#frm-add-budget');
        form.validate({
            rules: {
                department_id: {
                    required: true,
                },
                team_id: {
                    required: true,
                },
                budget: {
                    required: true,
                    maxlength: 11
                },
                effect_time: {
                    required: true,
                }
            },
            messages: {
                department_id: {
                    required: jsonLang['Phòng ban là trường bắt buộc phải chọn']
                },
                team_id: {
                    required: jsonLang['Nhóm là trường bắt buộc phải chọn'],
                },
                budget: {
                    required: jsonLang['Ngân sách tháng là trường bắt buộc phải nhập'],
                    maxlength: jsonLang['Ngân sách tháng chỉ cho phép tối đa là 999,999,999 VND']
                },
                effect_time: {
                    required: jsonLang['Hãy chọn tháng áp dụng'],
                }
            }
        });
    
        if (!form.valid()) {
            return false;
        }

        $.post(url, formData, function (resp) {
            if (resp.error == 0) {
                swal(resp.message, "", "success");
                window.location.reload();
            }
            else {
                swal(resp.message, "", "error");
            }
        });
    });

    $('#popup-add').on('hidden.bs.modal', function(e) {
        $(this).find('#frm-add-budget').validate().resetForm();
    });

    // Chỉnh sửa ngân sách theo tháng
    $(document).on('click', '.btn-edit-budget', function (e) {
        e.preventDefault();
        $('#frm-edit-criteria')[0].reset();
        let budget_id = $(this).data('id');
        let team_id = $(this).data('team');
        let department_id = $(this).data('department');
        let budget = $(this).data('budget');
        let time = $(this).data('time');
        
        if (team_id) {
            $("#team_allocate").prop("checked", true);
            $(".team-row").show();
            $("#team_id").prop('disabled', true);
        } else {
            $("#department_allocate").prop("checked", true);
            $('.team-row').hide();
            $("#team_id").prop('disabled', true);
        }

        $('#frm-edit-criteria').attr('data-id', budget_id);
        $('#department_id').val(department_id);
        $('#team_id').val(team_id);
        $('#budget').val(Number(parseFloat(budget)).toLocaleString('en')); 
        document.getElementById("effect_time").value = time;

        $('#popup-edit').modal('show');
    });

    $("#department_allocate").on('change', function(){
        $(".team-row").hide();
        $("#team_id").prop('disabled', true);
    });

    $("#team_allocate").on('change', function(){
        $(".team-row").show();
        $("#team_id").prop('disabled', false);
        $("#team_id").val('');
    });

    $('#frm-edit-criteria').submit(function (e) {
        e.preventDefault();
        let url = $(this).data("route");

        budgetId = [{
            'name': 'budget_marketing_id',
            'value': $(this).data("id")
        }]

        formData = jQuery("#frm-edit-criteria").serializeArray();
        formData = formData.concat(budgetId);

        var form = $('#frm-edit-criteria');
        form.validate({
            rules: {
                department_id: {
                    required: true,
                },
                team_id: {
                    required: true,
                },
                budget: {
                    required: true,
                    maxlength: 11
                },
                effect_time: {
                    required: true,
                }
            },
            messages: {
                department_id: {
                    required: jsonLang['Phòng ban là trường bắt buộc phải chọn']
                },
                team_id: {
                    required: jsonLang['Nhóm là trường bắt buộc phải chọn'],
                },
                budget: {
                    required: jsonLang['Ngân sách tháng là trường bắt buộc phải nhập'],
                    maxlength: jsonLang['Ngân sách tháng chỉ cho phép tối đa là 999,999,999 VND']
                },
                effect_time: {
                    required: jsonLang['Hãy chọn tháng áp dụng'],
                }
            }
        });
    
        if (!form.valid()) {
            return false;
        }

        $.post(url, formData, function (resp) {
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