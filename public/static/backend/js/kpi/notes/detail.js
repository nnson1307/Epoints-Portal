$(document).ready(function () {
    // Trigger click nút chỉnh sửa kpi thực tế cho tiêu chí người dùng tự thêm
    $(document).on('click', '#btn-edit-custom-kpi', function () {
        $(this).closest('td').find('input').attr('disabled', false);
        $(this).closest('td').find('input').css("text-align", "left");
        $(this).closest('td').find('input').focus();
    });

    // Trigger khi người dùng click ngoài input chỉnh sửa kpi thực tế
    $('.kpi_calculate_value').attr('size', $('#kpi_calculate_value').val().length);
    $('.kpi_calculate_value').css("text-align", "right");
    $(".kpi_calculate_value").on("focusout", function(e) {
        $(this).attr('disabled', true);

        // Tính % hoàn thành kpi cho tiêu chí người dùng tự thêm
        var percent;
        var percentWithPriority;

        var kpiTarget = $(this).closest('td').find('#kpi_target').val();
        var priority  = $(this).closest('td').find('#priority').val();
        var trend     = $(this).closest('td').find('#kpi_criteria_trend').val();
        var block     = $(this).closest('td').find('#is_blocked').val();

        var kpiValue  = $(this).closest('td').find('#kpi_calculate_value').val();

        if (trend == 1) {
            percent = (kpiValue * 100) / kpiTarget; // Tính % hoàn thành KPI 
            if (block == 1 && kpiValue > kpiTarget) {
                percentWithPriority = priority;
            } else {
                percentWithPriority = (kpiValue / kpiTarget) * priority; // Tính % hoàn thành KPI kèm trọng số
            }
        } else {
            percent = (kpiTarget - kpiValue) * (100 / kpiTarget) + 100; // Tính % hoàn thành KPI 
            if (kpiValue == 0) {
                percentWithPriority = $priority;
            } else {
                if (block == 1 && kpiValue < $kpiTarget) {
                    percentWithPriority = priority;
                } else {
                    percentWithPriority = (kpiTarget - kpiValue) * (priority / kpiTarget) + priority; // Tính % hoàn thành KPI kèm trọng số
                }
            }
        }

        // Param bảng tính tiêu chí kpi do người dùng tự thêm
        var params = {
            kpi_note_detail_id:     $(this).closest('td').find('#kpi_note_detail_id').val(),
            kpi_criteria_id:        $(this).closest('td').find('#kpi_criteria_id').val(),
            branch_id:              $(this).closest('td').find('#branch_id').val(),
            department_id:          $(this).closest('td').find('#department_id').val(),
            team_id:                $(this).closest('td').find('#team_id').val(),
            staff_id:               $(this).closest('td').find('#staff_id').val(),
            day:                    $(this).closest('td').find('#day').val(),
            week:                   $(this).closest('td').find('#week').val(),
            month:                  $(this).closest('td').find('#month').val(),
            year:                   $(this).closest('td').find('#year').val(),
            total:                  $(this).closest('td').find('#kpi_calculate_value').val(),
            kpi_criteria_unit_id:   $(this).closest('td').find('#kpi_criteria_unit_id').val(),
            percent:                percent,
            percentWithPriority:    percentWithPriority,
        };

        // Tạo mới hoặc update bảng tính tiêu chí kpi người dùng tự thêm
        $.ajax({
            url: laroute.route('kpi.note.calculate'),
            data: params,
            method: 'POST',
            dataType: "JSON",
            success: function (response) {
                window.location.reload();
            }
        });
    });      
});