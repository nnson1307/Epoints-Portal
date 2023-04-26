var jsonLang = [];
$.getJSON(laroute.route('translate'), function (json) {
    jsonLang = json;
});

$.extend($.validator.messages, {
    min: 'Giá trị nhập vào phải lớn hơn hoặc bằng {0}',
    max: 'Giá trị nhập vào phải nhỏ hơn hoặc bằng {0}'
});

var availablePriority = 100;

var EditKpiNote = {
    update: function (obj, id) {
        var form = $('#form-banner');
        form.validate({
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

        $.ajax({
            url: laroute.route('kpi.note.update'),
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

    closeModal: function () {
        // Ẩn text báo lỗi chưa chọn tiêu chí nếu có
        $('.criteria-list-err').text('');
        var kpiNoteType = $('#kpi_note_type').val();

        // Validate popup thêm tiêu chí 
        var formAdd = $('#frm-add-criteria');
        formAdd.validate({
            rules: {
                priority: {
                    required: true,
                    max: function () {
                        return availablePriority;
                    }
                },
                kpi_value: {
                    required: true,
                }
            },
            messages: {
                priority: {
                    required: jsonLang['Hãy nhập độ quan trọng'],
                    max: jsonLang['Giá trị phải nhỏ hơn hoặc bằng độ quan trọng khả dụng']
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
         * LẤY DATA TỪ POPUP THÊM TIÊU CHÍ
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

        if (kpiNoteType === 'S') {
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
                data.push({ staff_id: element, staff_name: arr[index] });
            });
        }
        
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
            var indexCriteria = criteriaData.map(function (el) { return el.criteria_id; }).indexOf(parseInt(criteriaId));
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
                availablePriority += priorityArr[indexCriteria];
                priorityArr.splice(indexCriteria, 1);
                localStorage.setItem("priority", JSON.stringify(priorityArr));
    
                var kpiValueArr = JSON.parse(localStorage.getItem("kpi_value"));
                kpiValueArr.splice(indexCriteria, 1);
                localStorage.setItem("kpi_value", JSON.stringify(kpiValueArr));
            }
        }

        // Staff
        if (kpiNoteType !== 'S') {
            if (localStorage.getItem("staff") === null) {
                localStorage.setItem("staff", JSON.stringify(data));
            }
        }

        /**
         * XỬ LÝ GENERATE BẢNG DANH SÁCH TIÊU CHÍ
         */
        if ($('#kpi_note_type').val() === 'S') {
            generateCriteriaTableHtml(jsonLang)
        } else {
            generateCriteriaTableForGroup(jsonLang);
        }
    }
};

$(document).ready(function () {
    localStorage.clear();
    var kpiNoteType = $('#kpi_note_type').val();
    switch (kpiNoteType) {
        case 'B':
            $('#department_id').parent().parent().addClass('d-none');
            $('#department_id').prop('disabled', true);
            $('#team_id').parent().parent().addClass('d-none');
            $('#team_id').prop('disabled', true);
            $('#staff_id').parent().parent().addClass('d-none');
            $('#staff_id').prop('disabled', true);
            break;
        case 'D':
            $('#department_id').parent().parent().removeClass('d-none');
            $('#department_id').prop('disabled', false);
            $('#team_id').parent().parent().addClass('d-none');
            $('#team_id').prop('disabled', true);
            $('#staff_id').parent().parent().addClass('d-none');
            $('#staff_id').prop('disabled', true);
            break;
        case 'T':
            $('#department_id').parent().parent().removeClass('d-none');
            $('#department_id').prop('disabled', false);
            $('#team_id').parent().parent().removeClass('d-none');
            $('#team_id').prop('disabled', false);
            $('#staff_id').parent().parent().addClass('d-none');
            $('#staff_id').prop('disabled', true);
            break;
    }
    // Gọi lấy data bảng hiện tại
    var data = {
        id: $('#kpi_note_id').val()
    };

    $.ajax({
        url: laroute.route('kpi.note.list-current-criteria'),
        data: data,
        method: 'POST',
        async: false,
        dataType: "JSON",
        success: function (response) {
            availablePriority -= (response.priority).reduce((a, b) => a + b, 0);
            saveToLocalStorage(response);
        }
    });

    $.getJSON(laroute.route('translate'), function (json) {
        if (kpiNoteType !== 'S') {
            generateCriteriaTableForGroup(json);
        } else {
            generateCriteriaTableHtml(json)
        }
    });
    
    // Show modal thêm tiêu chí
    $(document).on('click', '.btn-add-criteria', function (e) {
        var data = { kpi_note_type: kpiNoteType };
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
        $('#popup-add').modal('show');
    });

    // Trigger chọn năm áp dụng để load tháng
    $(document).on('change', '#effect_year', function () {
        var currentMonth = parseInt(moment().format('M'));
        var currentYear  = parseInt(moment().format('YYYY'));      
        var settingMonth = $('#effect_month').data('month');

        var monthHtml = '';
        if ( parseInt($(this).val()) ==  currentYear) {
            var attr = '';  
            [1,2,3,4,5,6,7,8,9,10,11,12].forEach(function(i) {
                console.log(currentMonth);
                console.log(i);
                if (i == settingMonth) {
                    attr = 'selected';
                    console.log('If < currentmonth: '+attr);
                }
                else if (i < currentMonth) {
                    attr = 'disabled';
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

    // Trigger chọn chi nhánh
    $(document).on('change', '#branch_id', function () {
        $('#staff_id').select2("val", "");
        var data = { branch_id: (this).value };
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
                if (response.length < 1) {
                    $('#staff_id').html('');
                }
                else {
                    response.forEach(element => {
                        staffOptionHtml += '<option value="' + element.staff_id + '" selected>' + element.full_name + '</option>'
                    })
                    $('#staff_id').html(staffOptionHtml);
                }
            }
        });
    });

    // Trigger chọn phòng ban
    $(document).on('change', '#department_id', function () {
        var data = { department_id: (this).value };
        var teamOptionHtml = '<option value="">' + jsonLang['Chọn nhóm'] + '</option>';
        $.ajax({
            url: laroute.route('kpi.note.team'),
            data: data,
            method: 'POST',
            dataType: "JSON",
            success: function (response) {
                console.log(response);
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
                if (response.length < 1) {
                    $('#staff_id').html('');
                }
                else {
                    response.forEach(element => {
                        staffOptionHtml += '<option value="' + element.staff_id + '" selected>' + element.full_name + '</option>'
                    })
                    $('#staff_id').html(staffOptionHtml);
                }
            }
        });
    });

    // Trigger chọn nhóm
    $(document).on('change', '#team_id', function () {
        var data = { team_id: (this).value };
        var staffOptionHtml = '';
        $.ajax({
            url: laroute.route('kpi.note.staff'),
            data: data,
            method: 'POST',
            dataType: "JSON",
            success: function (response) {
                if (response.length < 1) {
                    $('#staff_id').html('');
                }
                else {
                    response.forEach(element => {
                        staffOptionHtml += '<option value="' + element.staff_id + '" selected>' + element.full_name + '</option>'
                    })
                    $('#staff_id').html(staffOptionHtml);
                }
            }
        });
    });

    // Trigger thay đổi danh sách nhân viên
    $(document).on('change', '#staff_id', function () {
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
            data.push({ staff_id: element, staff_name: arr[index] });
        });

        localStorage.setItem("staff", JSON.stringify(data));
        generateCriteriaTableHtml(jsonLang);
    });

    // Format number 000,000d
    $(document).on('change', '.num_currency', function () {
        $(this).val(Number(parseFloat($(this).val())).toLocaleString('en'));
    });
});

// Lưu data vào local storage
function saveToLocalStorage(response) {
    // Criteria
    var criteriaData = response.criteria;
    localStorage.setItem("criteria", JSON.stringify(criteriaData));

    // Staff
    var staffData = response.staff;
    localStorage.setItem("staff", JSON.stringify(staffData));

    // Unit
    var unitData = response.unit;
    localStorage.setItem("unit", JSON.stringify(unitData));

    // Priority
    var priorityData = response.priority;
    localStorage.setItem("priority", JSON.stringify(priorityData));

    // Kpi value
    var kpiValueData = response.kpi_value;
    localStorage.setItem("kpi_value", JSON.stringify(kpiValueData));
}

// Generate bảng danh sách tiêu chí từ localStorage
function generateCriteriaTableHtml(jsonLang) {
    if (localStorage.getItem('staff') != null && localStorage.getItem('criteria') != null) {
        var staffArr = JSON.parse(localStorage.getItem('staff'));
        var criteriaArr = JSON.parse(localStorage.getItem('criteria'));
        var priorityArr = JSON.parse(localStorage.getItem('priority'));
        var kpiValueArr = JSON.parse(localStorage.getItem('kpi_value'));
        var unitArr = JSON.parse(localStorage.getItem('unit'));

        var currentCriteria = '';
        var currentHeader = '';
        var newCriteriaId = [];

        // Generate dòng header tương ứng với các tiêu chí được thêm vào
        criteriaArr.forEach(element => {
            if (element.is_customize == 0) {
                currentCriteria += '<th colspan="3" class="tr_thead_list text-center" style="min-width: 500px;">' + jsonLang[element.criteria_name] + '</th>';
            } else {
                currentCriteria += '<th colspan="3" class="tr_thead_list text-center" style="min-width: 500px;">' + element.criteria_name + '</th>';
            }
            currentHeader += '<th class="text-center">' + jsonLang['Độ Quan Trọng'] + '</th>' +
                '<th class="text-center">' + jsonLang['Chỉ tiêu'] + '</th>' +
                '<th class="text-center">' + jsonLang['Đơn Vị'] + '</th>';
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
                                   jsonLang[unitArr[key]]+
                               '</td>';
            });

            // Generate dòng nhân viên + 3 cột bên trên
            currentRow += '<tr class="tr_template">' +
                '<td class="text-center">' +
                ++staffKey +
                '</td>' +
                '<td class="text-center">' +
                staffVal.staff_name +
                '</td>' +
                currentData +
                '</tr>';
        });

        // Generate bảng danh sách tiêu chí
        var tableTpl = '<thead class="bg">' +
            '<tr>' +
            '<th rowspan="2" class="tr_thead_list text-center align-middle">#</th>' +
            '<th rowspan="2" class="tr_thead_list text-center align-middle">' + jsonLang['Nhân viên'] + '</th>' +
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
function generateCriteriaTableForGroup(jsonLang) {
    if (localStorage.getItem('staff') != null && localStorage.getItem('criteria') != null) {
        var criteriaArr = JSON.parse(localStorage.getItem('criteria'));
        var priorityArr = JSON.parse(localStorage.getItem('priority'));
        var kpiValueArr = JSON.parse(localStorage.getItem('kpi_value'));
        var unitArr = JSON.parse(localStorage.getItem('unit'));

        var currentRow = '';
        criteriaArr.forEach(function callback(criteriaValue, criteriaKey) {
            var index = criteriaKey + 1;
            var criteriaName;
            console.log(criteriaValue.is_customize);
            if (criteriaValue.is_customize == 0) {
                console.log(1);
                criteriaName = jsonLang[criteriaValue.criteria_name];
            } else {
                criteriaName = criteriaValue.criteria_name;
            }
            console.log(criteriaName);
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




