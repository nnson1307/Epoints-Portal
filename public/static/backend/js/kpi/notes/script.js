$('#autotable').PioTable({
    baseUrl: laroute.route('kpi.note.list')
});

var jsonLang = [];
$.getJSON(laroute.route('translate'), function (json) {
    jsonLang = json;
});


var availablePriority = 100;
var KpiNote = {
    // Xóa phiếu giao
    remove: function (obj, id) {
        // hightlight row
        $(obj).closest('tr').addClass('m-table__row--danger');
        swal({
            title: jsonLang['Thông báo'],
            text: jsonLang['Bạn xác nhận muốn xóa tiêu chí này?'],
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
                    url: laroute.route('kpi.note.remove', { id: id }),
                    method: 'POST',
                    dataType: "JSON",
                    success: function (response) {
                        console.log(response);
                        if (response.error == 0) {
                            swal(response.message, "", "success");
                            window.location = laroute.route('kpi.note');
                        } else {
                            swal(response.message, "", "error")
                        }
                    }
                });
            }
        });
    },

    // Thêm phiếu giao
    add: function (obj, id) {
        var form = $('#form-banner');
        form.validate({
            ignore: '',
            rules: {
                kpi_note_name: {
                    required: true,
                },
                is_loop: {
                    required: true,
                },
                branch_id: {
                    required: true,
                },
                department_id: {
                    required: true,
                },
                team_id: {
                    required: true,
                },
                "staff_id[]" : {
                    required: true,
                },
            },
            messages: {
                kpi_note_name: {
                    required: jsonLang['Hãy nhập tên phiếu giao']
                },
                is_loop: {
                    required: jsonLang['Hãy chọn tần suất lặp lại']
                },
                branch_id: {
                    required: jsonLang['Hãy chọn chi nhánh']
                },
                department_id: {
                    required: jsonLang['Hãy chọn phòng ban']
                },
                team_id: {
                    required: jsonLang['Hãy chọn nhóm']
                },
                "staff_id[]" : {
                    required: jsonLang['Hãy chọn nhân viên']
                },
            },
        });

        if (!form.valid()) {
            return false;
        }

        var tableTr = $('#criteria-table tr');
        if (tableTr.length == 0) {
            $('.criteria-list-err').text(jsonLang['Hãy chọn ít nhất 1 tiêu chí']);
            return false;
        }

        var formData = form.serializeArray();
        formData = formData.concat(
            jQuery('#form-banner input[type=checkbox]:not(:checked)').map(
                function () {
                    return { "name": this.name, "value": 1 }
                }).get()
        );
        $.ajax({
            url: laroute.route('kpi.note.submit'),
            data: formData,
            method: 'POST',
            dataType: "JSON",
            success: function (response) {
                if (response.error == 0) {
                    swal(response.message, "", "success");
                    window.location = laroute.route('kpi.note');
                } else {
                    swal(response.message, "", "error")
                }
            }
        });
    },

    // Đóng popup thêm tiêu chí tính kpi
    closeModal: function () {
        // Ẩn text báo lỗi chưa chọn tiêu chí nếu có
        $('.criteria-list-err').text('');

        // Validate popup thêm tiêu chí 
        var formAdd = $('#frm-add-criteria');
        formAdd.validate({
            rules: {
                priority: {
                    required: true,
                    min: 1,
                },
                kpi_value: {
                    required: true,
                }
            },
            messages: {
                priority: {
                    required: jsonLang['Hãy nhập độ quan trọng'],
                    min: jsonLang['Độ quan trọng phải lớn hơn 0'],
                },
                kpi_value: {
                    required: jsonLang['Hãy nhập chỉ tiêu']
                }
            },
        });
        if (!formAdd.valid()) {
            return false;
        }

        /**
         * LẤY THÔNG TIN TIÊU CHÍ TỪ POPUP
         */
        var criteriaId        = $("#kpi_criteria_id").find(':selected').val();
        var criteriaName      = $("#kpi_criteria_id").find(':selected').data('name');
        var criteriaCustomize = $("#kpi_criteria_id").find(':selected').data('customize');

        // Lấy giá trị đơn vị
        unit = $("#kpi_criteria_id").find(':selected').data('unit')

        // Lấy giá trị độ quan trọng
        priority = $("#priority").val();

        // Lấy giá trị chỉ tiêu
        kpi_value = $("#kpi_value").val();

        // Lấy mảng id nhân viên
        staffId = $("#staff_id").val();

        // Chuyển thông tin select2 danh sách nhân viên thành array object 
        text = $("#staff_id").select2('data');
        var arr = [];
        text.forEach(function (k, v) {
            arr.push(k.text);
        });
        var data = [];
        staffId.forEach((element, index) => {
            data.push({ staff_id: element, full_name: arr[index] });
        });

        $('#popup-add').modal('hide');

        /**
         * LƯU DATA BẢNG HIỆN TẠI VÔ LOCAL STORAGE
         */
        // Nếu chưa có tiêu chí nào trong danh sách
        if (localStorage.getItem("criteria") === null) {
            var criteriaData = [];
            criteriaData.push({
                criteria_id: criteriaId,
                criteria_name: criteriaName,
                criteria_customize: criteriaCustomize 

            });
            localStorage.setItem("criteria", JSON.stringify(criteriaData));

            if (localStorage.getItem("unit") === null) {
                var unitArr = [];
                unitArr.push(unit);
                localStorage.setItem("unit", JSON.stringify(unitArr));
            }

            if (localStorage.getItem("priority") === null) {
                var priorityArr = [];
                priorityArr.push(priority);
                availablePriority -= priority;
                localStorage.setItem("priority", JSON.stringify(priorityArr));
            }

            if (localStorage.getItem("kpi_value") === null) {
                var kpiValueArr = [];
                kpiValueArr.push(kpi_value);
                localStorage.setItem("kpi_value", JSON.stringify(kpiValueArr));
            }
        }
        // Nếu đã có tiêu chí trong danh sách
        else {
            criteriaData = JSON.parse(localStorage.getItem("criteria"));
            // Kiểm tra tiêu chí đã có trong danh sách chưa
            // Nếu chưa thì thêm data vào mảng
            var indexCriteria = criteriaData.map(function (el) { return el.criteria_id; }).indexOf(criteriaId);
            if ( indexCriteria === -1 ) {
                criteriaData.push({
                    criteria_id: criteriaId,
                    criteria_name: criteriaName,
                    criteria_customize: criteriaCustomize 
    
                });
                localStorage.setItem("criteria", JSON.stringify(criteriaData));
    
                var unitArr = JSON.parse(localStorage.getItem("unit"));
                unitArr.push(unit);
                localStorage.setItem("unit", JSON.stringify(unitArr));
    
                var priorityArr = JSON.parse(localStorage.getItem("priority"));
                priorityArr.push(priority);
                availablePriority -= priority;
                localStorage.setItem("priority", JSON.stringify(priorityArr));
    
                var kpiValueArr = JSON.parse(localStorage.getItem("kpi_value"));
                kpiValueArr.push(kpi_value);
                localStorage.setItem("kpi_value", JSON.stringify(kpiValueArr));
            } 
            // Nếu rồi thì remove tiêu chí ra khỏi mảng
            else {
                criteriaData.splice(indexCriteria, 1)
                localStorage.setItem("criteria", JSON.stringify(criteriaData));

                var unitArr = JSON.parse(localStorage.getItem("unit"));
                unitArr.splice(indexCriteria, 1);
                localStorage.setItem("unit", JSON.stringify(unitArr));
    
                var priorityArr = JSON.parse(localStorage.getItem("priority"));
                availablePriority += parseInt(priorityArr[indexCriteria]);
                priorityArr.splice(indexCriteria, 1);
                localStorage.setItem("priority", JSON.stringify(priorityArr));
    
                var kpiValueArr = JSON.parse(localStorage.getItem("kpi_value"));
                kpiValueArr.splice(indexCriteria, 1);
                localStorage.setItem("kpi_value", JSON.stringify(kpiValueArr));
            }
        }

        // Staff
        if (localStorage.getItem("staff") === null) {
            localStorage.setItem("staff", JSON.stringify(data));
        }

        /**
         * XỬ LÝ GENERATE BẢNG DANH SÁCH TIÊU CHÍ
         */
        if ($('#kpi_note_type').val() === 'S') {
            generateCriteriaTableHtml();
        } else {
            generateCriteriaTableForGroup();
        }
    }
};

$(document).ready(function () {
    if (locale == 'vi') {
        $.extend($.validator.messages, {
            min: 'Giá trị nhập vào phải lớn hơn hoặc bằng {0}',
            max: 'Giá trị nhập vào phải nhỏ hơn hoặc bằng {0}'
        });
    } else {
        $.extend($.validator.messages, {
            min: 'The input value must be greater than or equal to {0}',
            max: 'The input value must be less than or equal to {0}'
        });
    }
    localStorage.clear();

    // Check phiếu giao là cho nhóm hay nhân viên để hiện field phù hợp
    $('#kpi_note_type').val("B");
    $('#department_id').parent().parent().addClass('d-none');
    $('#department_id').prop('disabled', true);
    $('#team_id').parent().parent().addClass('d-none');
    $('#team_id').prop('disabled', true);
    $('#staff_id').parent().parent().addClass('d-none');
    $('#staff_id').prop('disabled', true);

    $(document).on('change', '#kpi_note_type', function () {
        if ($(this).val() === 'B') {
            selectBranch();
        } else if ($(this).val() === 'D') {
            selectDepartment();
        } else if ($(this).val() === 'T') {
            selectTeam();
        } else {
            selectStaff();
        }
    });

    // Hiển thị modal thêm tiêu chí tính kpi
    $(document).on('click', '.btn-add-criteria', function () {
        var data = { kpi_note_type: $('#kpi_note_type').val() };
        var criteriaOptionHtml = '';
        $.ajax({
            url: laroute.route('kpi.note.criteria'),
            data: data,
            method: 'POST',
            dataType: "JSON",
            success: function (response) {
                response.forEach(element => {
                    if (element.is_customize == 1) {
                        criteriaOptionHtml += '<option value="' + element.kpi_criteria_id + '" data-unit="'+element.unit_name+'" data-name="'+element.kpi_criteria_name+'" data-customize="'+ element.is_customize +'">' + element.kpi_criteria_name + '</option>'
                    } else {
                        criteriaOptionHtml += '<option value="' + element.kpi_criteria_id + '" data-unit="'+element.unit_name+'" data-name="'+element.kpi_criteria_name+'" data-customize="'+ element.is_customize +'">' + jsonLang[element.kpi_criteria_name] + '</option>'
                    }
                })
                $('#kpi_criteria_id').html(criteriaOptionHtml);
            }
        });

        $('#priority').val('');
        $('#kpi_value').val('');
        $('.priority-msg').text(jsonLang['Độ quan trọng khả dụng'] + ': ' + availablePriority);
        $('#priority').attr({ 'max': availablePriority });
        $('.form-control-feedback').text('');
        $('#popup-add').modal('show');
    });

    // Trigger chọn năm áp dụng để load tháng
    $(document).on('change', '#effect_year', function () {
        var currentMonth = parseInt(moment().format('M'));
        var currentYear  = parseInt(moment().format('YYYY'));      

        var monthHtml = '';
        if ( parseInt($(this).val()) ==  currentYear) {
            var attr = '';  
            [1,2,3,4,5,6,7,8,9,10,11,12].forEach(function(i) {
                console.log(currentMonth);
                console.log(i);
                if (i < currentMonth) {
                    attr = 'disabled';
                    console.log('If < currentmonth: '+attr);
                }
                else if (i == currentMonth) {
                    attr = 'selected';
                    console.log('If = currentmonth: '+attr);
                } else {
                    attr = '';
                    console.log('If > currentmonth: '+attr);
                }
                console.log(i);
                console.log('#########');
                monthHtml += '<option value='+i+' '+attr+'>'+jsonLang['Tháng ' +i]+'</option>';
                console.log('HTML: ' +monthHtml);
            });
            $('#effect_month').html(monthHtml);
        } else if (parseInt($(this).val()) > currentYear) {
            [1,2,3,4,5,6,7,8,9,10,11,12].forEach(function(i) {
                monthHtml += '<option value='+i+'>'+jsonLang['Tháng ' +i]+'</option>';
            });
            console.log(monthHtml);
            $('#effect_month').html(monthHtml);
        }
    });

    // Trigger chọn chi nhánh để hiện phòng ban tương ứng
    $(document).on('change', '#branch_id', function () {
        $('#staff_id').select2("val", "");
        var data = { 
            branch_id: (this).value
        };
        var departmentOptionHtml = '<option value="">' + jsonLang['Chọn phòng ban'] + '</option>';
        $.ajax({
            url: laroute.route('kpi.note.department'),
            data: data,
            method: 'POST',
            dataType: "JSON",
            success: function (response) {
                response.forEach(element => {
                    departmentOptionHtml += '<option value="' + element.department_id + '">' + element.department_name + '</option>'
                })
                $('#department_id').html(departmentOptionHtml);
            }
        });

        var staffOptionHtml = '';
        $.ajax({
            url: laroute.route('kpi.note.staff'),
            data: data,
            method: 'POST',
            dataType: "JSON",
            success: function (response) {
                if ($('#kpi_note_type').val() === 'S') {
                    localStorage.setItem("staff", JSON.stringify(response));
                    if (response.length < 1) {
                        $('#staff_id').html('');
                        $('#criteria-table').empty();
                    }
                    else {
                        response.forEach(element => {
                            staffOptionHtml += '<option value="' + element.staff_id + '" selected>' + element.full_name + '</option>'
                        })
                        $('#staff_id').html(staffOptionHtml);
                        generateCriteriaTableHtml();
                    }
                }
            }
        });
    });

    // Trigger chọn phòng ban để hiện nhóm tương ứng
    $(document).on('change', '#department_id', function () {
        branchId = $('#branch_id').val();
        var data = { 
            branch_id: branchId,
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
                $('#team_id').html(teamOptionHtml);
            }
        });

        var staffOptionHtml = '';
        $.ajax({
            url: laroute.route('kpi.note.staff'),
            data: data,
            method: 'POST',
            dataType: "JSON",
            success: function (response) {
                if ($('#kpi_note_type').val() === 'S') {
                    localStorage.setItem("staff", JSON.stringify(response));
                    if (response.length < 1) {
                        $('#staff_id').html('');
                        $('#criteria-table').empty();
                    }
                    else {
                        response.forEach(element => {
                            staffOptionHtml += '<option value="' + element.staff_id + '" selected>' + element.full_name + '</option>'
                        })
                        $('#staff_id').html(staffOptionHtml);
                        generateCriteriaTableHtml();
                    }
                }
            }
        });
    });

    // Trigger chọn nhóm để hiện nhân viên tương ứng
    $(document).on('change', '#team_id', function () {
        branchId     = $('#branch_id').val();
        departmentId = $('#department_id').val();
        var data = { 
            branch_id: branchId,
            department_id: departmentId,
            team_id: (this).value
        };
        var staffOptionHtml = '';
        $.ajax({
            url: laroute.route('kpi.note.staff'),
            data: data,
            method: 'POST',
            dataType: "JSON",
            success: function (response) {
                if ($('#kpi_note_type').val() === 'S') {
                    localStorage.setItem("staff", JSON.stringify(response));
                    if (response.length < 1) {
                        $('#staff_id').html('');
                        $('#criteria-table').empty();
                    }
                    else {
                        response.forEach(element => {
                            staffOptionHtml += '<option value="' + element.staff_id + '" selected>' + element.full_name + '</option>'
                        })
                        $('#staff_id').html(staffOptionHtml);
                        generateCriteriaTableHtml();
                    }
                }
            }
        });
    });

    // Trigger thay đổi danh sách nhân viên trong phiếu giao
    $(document).on('change', '#staff_id', function () {
        // Lấy mảng id nhân viên
        staffId = $("#staff_id").val();
        if (staffId.length < 1) {
            $('#staff_id').empty();
        }
        // Chuyển thông tin select2 danh sách nhân viên thành array object 
        text = $("#staff_id").select2('data');
        var arr = [];
        text.forEach(function (k, v) {
            arr.push(k.text);
        });
        var data = [];
        staffId.forEach((element, index) => {
            data.push({ staff_id: element, full_name: arr[index] });
        });

        localStorage.setItem("staff", JSON.stringify(data));
        generateCriteriaTableHtml();
    });

    // Format field giá trị số trong bảng tiêu chí
    $(document).on('change', '.num_currency', function () {
        $(this).val(Number(parseFloat($(this).val())).toLocaleString('en'));
    });    
});

// Generate bảng danh sách tiêu chí cho phiếu giao nhân viên
function generateCriteriaTableHtml() {
    if (localStorage.getItem('staff') != null && localStorage.getItem('criteria') != null) {
        var staffArr = JSON.parse(localStorage.getItem('staff'));
        var criteriaArr = JSON.parse(localStorage.getItem('criteria'));
        var priorityArr = JSON.parse(localStorage.getItem('priority'));
        var kpiValueArr = JSON.parse(localStorage.getItem('kpi_value'));
        var unitArr = JSON.parse(localStorage.getItem('unit'));

        if (staffArr.length < 1 ) {
            $('#criteria-table').empty();
            return false;
        }

        var currentCriteria = '';
        var currentHeader = '';
        var newCriteriaId = [];

        // Generate dòng header tương ứng với các tiêu chí được thêm vào
        criteriaArr.forEach(element => {
            if (element.criteria_customize == 0) {
                currentCriteria += '<th colspan="3" class="tr_thead_list text-center" style="min-width: 500px;">' + jsonLang[element.criteria_name] + '</th>';
            } else {
                currentCriteria += '<th colspan="3" class="tr_thead_list text-center" style="min-width: 500px;">' + element.criteria_name + '</th>';
            }
            
            currentHeader += '<th class="text-center">'+jsonLang['Độ Quan Trọng']+'</th>' +
                '<th class="text-center">'+jsonLang['Chỉ tiêu']+'</th>' +
                '<th class="text-center">'+jsonLang['Đơn Vị']+'</th>';
            newCriteriaId.push(element.criteria_id);
        })

        var currentRow = '';
        staffArr.forEach(function callback(staffVal, staffKey) {
            // Generate cột "độ quan trọng", "chỉ tiêu", "đơn vị"
            var currentData = '';
            priorityArr.forEach(function callback(element, key) {

                currentData += '<td class="text-center">' +
                                    '<div class="input-group percent-label-input" style="padding-left: 0px;">' +
                                        '<input type="number" class="form-control text-center" name="priority_id_row[' + staffVal.staff_id + '][' + newCriteriaId[key] + ']" value="' + element + '" min="1" max="100" required>' +
                                    '</div>' +
                                '</td>';
                if (unitArr[key] == '%') {
                    currentData += '<td class="text-center">' +
                                        '<input type="number" class="form-control text-center" name="kpi_value_row[' + staffVal.staff_id + '][' + newCriteriaId[key] + ']" value="' + kpiValueArr[key] + '" min="0" max="100" required>' +
                                    '</td>';
                } else {
                    currentData += '<td class="text-center">' +
                                        '<input type="text" class="form-control text-center num_currency" name="kpi_value_row[' + staffVal.staff_id + '][' + newCriteriaId[key] + ']" value="' + Number(parseFloat(kpiValueArr[key])).toLocaleString('en') + '" required>' +
                                    '</td>';
                }
                    
                currentData += '<td class="text-center">' +
                                   jsonLang[unitArr[key]] +
                               '</td>';
            });

            // Generate dòng nhân viên + 3 cột bên trên
            currentRow += '<tr class="tr_template">' +
                '<td class="text-center">' +
                ++staffKey +
                '</td>' +
                '<td class="text-center">' +
                staffVal.full_name +
                '</td>' +
                currentData +
                '</tr>';
        });

        // Generate bảng danh sách tiêu chí
        var tableTpl = '<thead class="bg">' +
            '<tr>' +
            '<th rowspan="2" class="tr_thead_list text-center align-middle">#</th>' +
            '<th rowspan="2" class="tr_thead_list text-center align-middle">'+jsonLang['Nhân viên']+'</th>' +
            currentCriteria +
            '</tr>' +
            '<tr>' +
            currentHeader +
            '</tr>' +
            '</thead>' +
            '<tbody>' +
            currentRow +
            '</tbody>';

        // Gắn vào khung table trong view
        $('#criteria-table').html(tableTpl);
    }
}

// Generate bảng danh sách tiêu chí cho phiếu giao nhóm
function generateCriteriaTableForGroup() {
    if (localStorage.getItem('staff') != null && localStorage.getItem('criteria') != null) {
        var criteriaArr = JSON.parse(localStorage.getItem('criteria'));
        var priorityArr = JSON.parse(localStorage.getItem('priority'));
        var kpiValueArr = JSON.parse(localStorage.getItem('kpi_value'));
        var unitArr = JSON.parse(localStorage.getItem('unit'));

        var currentRow = '';
        criteriaArr.forEach(function callback(criteriaValue, criteriaKey) {
            var index = criteriaKey + 1;
            var criteriaName;
            if (criteriaValue.criteria_customize == 0) {
                criteriaName = jsonLang[criteriaValue.criteria_name];
            } else {
                criteriaName = criteriaValue.criteria_name;
            }

            // Generate dòng nhân viên + 3 cột bên trên
            currentRow  += '<tr class="tr_template">' +
                                '<td class="text-center">' +
                                    index +
                                '</td>' +
                                '<td class="text-center">' +
                                    criteriaName +
                                '</td>' +
                                '<td class="text-center">' +
                                    '<div class="input-group percent-label-input" style="padding-left: 0px;">' +
                                        '<input type="number" class="form-control text-center" name="priority_id_row[' + criteriaValue.criteria_id + ']" value="' + priorityArr[criteriaKey] + '" min="1" max="100" required>' +
                                    '</div>' +
                                '</td>';

            if (unitArr[criteriaKey] == '%') {
                currentRow  += '<td class="text-center">' +
                                    '<input type="number" class="form-control text-center" name="kpi_value_row[' + criteriaValue.criteria_id + ']" value="' + kpiValueArr[criteriaKey] + '" min="0" max="100" required>' +
                                '</td>';
            } else {
                currentRow  += '<td class="text-center">' +
                                    '<input type="text" class="form-control text-center num_currency" name="kpi_value_row[' + criteriaValue.criteria_id + ']" value="' + Number(parseFloat(kpiValueArr[criteriaKey])).toLocaleString('en') + '" required>' +
                                '</td>';
            }
            currentRow  += '<td class="text-center">' +
                                jsonLang[unitArr[criteriaKey]] +
                            '</td>' +
                            '</tr>';
        });

        // Generate bảng danh sách tiêu chí
        var tableTpl = '<thead class="bg">' +
                            '<tr>' +
                                '<th class="tr_thead_list text-center align-middle">#</th>' +
                                '<th class="tr_thead_list text-center align-middle">'+jsonLang['Tiêu chí']+'</th>' +
                                '<th class="tr_thead_list text-center align-middle">'+jsonLang['Độ Quan Trọng']+'</th>' +
                                '<th class="tr_thead_list text-center align-middle">'+jsonLang['Chỉ tiêu']+'</th>' +
                                '<th class="tr_thead_list text-center align-middle">'+jsonLang['Đơn Vị']+'</th>' +
                            '</tr>' +
                        '</thead>' +

                        '<tbody>' +
                            currentRow +
                        '</tbody>';

        // Gắn vào khung table trong view
        $('#criteria-table').html(tableTpl);
    }
}

// Chọn loại phiếu giao cho chi nhánh
function selectBranch()
{  
    $('#department_id').parent().parent().addClass('d-none');
    $('#department_id').prop('disabled', true);
    $('#team_id').parent().parent().addClass('d-none');
    $('#team_id').prop('disabled', true);
    $('#staff_id').parent().parent().addClass('d-none');
    $('#staff_id').prop('disabled', true);
    $('#criteria-table').empty();
    localStorage.clear();
    availablePriority = 100;
    $('#branch_id').val('');
}

// Chọn loại phiếu giao cho phòng ban
function selectDepartment()
{
    $('#department_id').parent().parent().removeClass('d-none');
    $('#department_id').prop('disabled', false);
    $('#team_id').parent().parent().addClass('d-none');
    $('#team_id').prop('disabled', true);
    $('#staff_id').parent().parent().addClass('d-none');
    $('#staff_id').prop('disabled', true);
    $('#criteria-table').empty();
    localStorage.clear();
    availablePriority = 100;
    $('#branch_id').val('');
    $('#department_id').val('');
}

// Chọn loại phiếu giao cho nhóm
function selectTeam()
{
    $('#department_id').parent().parent().removeClass('d-none');
    $('#department_id').prop('disabled', false);
    $('#team_id').parent().parent().removeClass('d-none');
    $('#team_id').prop('disabled', false);
    $('#staff_id').parent().parent().addClass('d-none');
    $('#staff_id').prop('disabled', true);
    $('#criteria-table').empty();
    localStorage.clear();
    availablePriority = 100;
    $('#branch_id').val('');
    $('#department_id').val('');
    $('#team_id').val('');
}

// Chọn loại phiếu giao cho nhân viên
function selectStaff()
{
    $('#department_id').parent().parent().removeClass('d-none');
    $('#department_id').prop('disabled', false);
    $('#team_id').parent().parent().removeClass('d-none');
    $('#team_id').prop('disabled', false);
    $('#staff_id').parent().parent().removeClass('d-none');
    $('#staff_id').prop('disabled', false);
    $('#criteria-table').empty();
    localStorage.clear();
    availablePriority = 100;
    $('#branch_id').val('');
    $('#department_id').val('');
    $('#team_id').val('');
}
