var jsonLang = JSON.parse(localStorage.getItem('tranlate'));
var ProjectAdd = {
    getCustomerDynamic: (o) => {
        let typeCustomer = $(o).val();
        var option = '';
        let listCustomer = $("#project_customer");
        listCustomer.empty();
        $.ajax({
            url: laroute.route('manager-project.project.type'),
            data: {
                type: typeCustomer
            },
            method: "POST",
            dataType: "JSON",
            success: function (res) {
                if (res.error == false) {
                    $.each(res.data, function (key, value) {
                        option += `<option  value="${value.customer_id}">${value.full_name}</option>`;
                    })
                    listCustomer.append(option);
                }
            }
        });
    },
    save: () => {
        // validate form //
        var form = $('#form-data');
        form.validate({
            rules: {
                project_name: {
                    required: true,
                    minlength: 2,
                    maxlength: 50,
                },
                project_manager: {
                    required: true
                },
                project_department: {
                    required: true
                },
                project_prefix: {
                    required: true,
                    minlength: 4,
                    maxlength: 4,
                },
                project_status: {
                    required: true
                }
                // progress: {
                //     required: true
                // }
            },
            messages: {
                project_name: {
                    required: jsonLang['Vui lòng nhập tên dự án'],
                    maxlength: jsonLang['Nhập tối 2 - 50 kí tự'],
                    minlength: jsonLang['Nhập tối 2 - 50 kí tự'],
                },
                project_manager: {
                    required: jsonLang['Vui lòng chọn người quản trị'],
                },
                project_department: {
                    required: jsonLang['Vui lòng chọn phòng ban trực thuộc'],
                },
                project_status: {
                    required: jsonLang['Vui lòng chọn trạng thái dự án'],
                },
                project_prefix: {
                    required: jsonLang['Vui lòng nhập tiền tố công việc'],
                    maxlength: jsonLang['Nhập tối đa 4 kí tự'],
                    minlength: jsonLang['Nhập tối đa 4 kí tự'],
                }
                // progress: {
                //     required: jsonLang['Vui lòng nhập tiến độ dự án'],
                // }
            },
        });
        if (!form.valid()) {
            return false;
        }
        // danh sách data //
        let manage_project_name = $("#project_name").val();
        let manager_id = $("#project_manager").val();
        let department_id = $("#project_department").val();
        let date_start = $("#date_start").val() != '' ? moment($("#date_start").val(), 'DD/MM/YYYY').format('YYYY-MM-DD') : '';
        let date_end = $("#date_end").val() != '' ? moment($("#date_end").val(), 'DD/MM/YYYY').format('YYYY-MM-DD') : '';
        let customer_type = $("#project_type_customer").val();
        let customer_id = $("#project_customer").val();
        let color_code = $("#color_code").val();
        let permission = $("input[name='permission']:checked").val();
        let prefix_code = $("#project_prefix").val();
        let manage_project_describe = $("#description").val();
        let manage_project_status_id = $("#project_status").val();
        let is_active = 1;
        let tags = $("#manage_tags").val();
        let budget = $("#budget").val();
        let resource = $("#resource").val();
        let contract_id = $("#contract_id").val();
        let progress = $("#progress").val();
        let contract_code = $("#contract_id option:selected").data('code');

        var is_important = 0 ;
        if ($('#is_important').is(':checked')) {
            is_important = 1;
        }

        var document = [];
        $.each($('#upload-image').find(".image-show"), function () {
            var image = $(this).find($('img')).attr('src');
            var path = $(this).find($('.path')).val();
            var file_name = $(this).find($('.file_name')).val();
            var file_type = $(this).find($('.file_type')).val();

            document.push({
                path:path,
                image:image,
                file_name : file_name,
                file_type : file_type
            });
        });

        var contact = [];

        var check = 1;

        $.each($('.add-customer-contact').find('.block-user-contact'),function (){
            var phone = $(this).find($('.user_contact_phone')).val();
            var name = $(this).find($('.user_contact_name')).val();

            if (name == '' || phone == ''){
                check = 0;
            }

            contact.push({
                name:name,
                phone:phone
            });


        })

        if (check == 0){
            swal.fire(jsonLang['Vui lòng nhập đủ thông tin người liên hệ còn trống'], "", "warning");
            return ;
        }

        data = {
            manage_project_name,
            manager_id,
            department_id,
            date_start,
            date_end,
            customer_type,
            customer_id,
            color_code,
            permission,
            prefix_code,
            manage_project_describe,
            manage_project_status_id,
            is_active,
            tags,
            document,
            budget,
            resource,
            is_important,
            contract_id,
            contract_code,
            contact,
            progress
        }
        // gửi data
        $.ajax({
            url: laroute.route('manager-project.project.store'),
            data: data,
            method: "POST",
            dataType: "JSON",
            success: function (res) {
                if (res.error == false) {
                    swal.fire(jsonLang['Thêm dự án thành công'], "", "success").then(function () {
                        window.location.href = laroute.route('manager-project.project');
                    });
                } else {
                    var mess_error = '';
                    $.map(res.array_error, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal.fire(jsonLang['Thêm dự án thất bại'], mess_error, "error");
                }
            },
            error: function (res) {
                if (res.responseJSON != undefined) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal.fire(jsonLang['Thêm dự án thất bại'], mess_error, "error");
                }
            }
        });


    },
    namePrefixProject: (o) => {
        let value = $(o).val();
        let prefixName = $("#project_prefix").val();
        let arrName = value.split(' ');
        if (arrName.length < 2){
            if (value.trim().length >= 2 && prefixName == '') {
                let nameDefault = value.slice(0, 2);
                $.ajax({
                    url: laroute.route('manager-project.project.name.prefix'),
                    data: {
                        nameDefault
                    },
                    method: "POST",
                    dataType: "JSON",
                    success: function (res) {
                        $("#project_prefix").val(res);
                    }
                });

            }
        } else {
            let nameDefault1 = arrName[0].slice(0, 1);
            let nameDefault2 = arrName[1].slice(0, 1);
            nameDefault = nameDefault1 + nameDefault2;
            $.ajax({
                url: laroute.route('manager-project.project.name.prefix'),
                data: {
                    nameDefault
                },
                method: "POST",
                dataType: "JSON",
                success: function (res) {
                    $("#project_prefix").val(res);
                }
            });
        }

    }
}
