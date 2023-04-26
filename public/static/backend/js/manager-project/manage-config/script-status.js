var ManageConfig = {
    addStatus : function (groupId) {
        $.getJSON(laroute.route('translate'), function (json) {
            Swal.fire({
                title: json['Tạo trạng thái mới'],
                buttonsStyling: false,

                confirmButtonText: json['Tạo'],
                confirmButtonClass: "btn btn-primary btn-hover-brand mt-0",
                reverseButtons: true,
                showCancelButton: true,
                cancelButtonText: json['Huỷ'],
                cancelButtonClass: "btn btn-secondary btn-hover-brand mt-0",
                html: json['Tên trạng thái mới'] +
                    '<input id="name" class="swal2-input" placeholder="'+json['Tên trạng thái mới']+'">',
                onOpen: function() {
                    $('#name').focus();
                }
            }).then(function (result) {
                if (result.value) {
                    if($('#name').val() == ''){
                        Swal.fire(json["Lỗi!"], json['Vui lòng nhập tên trạng thái mới'] , "error");
                    } else if($('#name').val().length > 255){
                        Swal.fire(json["Lỗi!"], json['Tên trạng thái mới vượt quá 255 ký tự'] , "error");
                    } else {
                        $.ajax({
                            url: laroute.route('manager-project.manage-config.add-status-config'),
                            method: 'POST',
                            data: {
                                groupId : groupId,
                                count : count,
                                status_name : $('#name').val()
                            },
                            success: function (res) {
                                if (res.error == false) {
                                    location.reload();
                                    // count = count + 1;
                                    // $('.groupId'+groupId+' tbody').append(res.view);
                                    // $('.select2Full').select2();
                                    // scrollBlock();
                                    // $.each($('.ui-sortable').find("tr"), function () {
                                    //     var num = $(this).find($('select')).val();
                                    //     $(this).find($('select')).append($('<option>', {
                                    //         value: res.detailStatus.manage_project_status_id,
                                    //         text: res.detailStatus.manage_project_status_name
                                    //     }));
                                    //     $(this).find($('select')).select2();
                                    //
                                    // });


                                } else {
                                    swal(res.message,'','error');
                                }
                            }
                        });
                    }

                }
            });
        });
    },

    updateConfigStatus : function () {
        $.ajax({
            url: laroute.route('manager-project.manage-config.update-config-status'),
            data: $('#form-config-status').serialize(),
            method: "POST",
            dataType: "JSON",
            success: function(data) {
                if (data.error == false) {
                    swal(data.message,'','success').then(function () {
                        window.location.href = laroute.route('manager-project.manage-config.status');
                    });
                } else {
                    swal(data.message,'','error');
                }
            }
        });
    },

    removeBlock: function (groupId,count,status_id) {
        $.getJSON(laroute.route('translate'), function (json) {
            Swal.fire({
                title: json['Xoá trạng thái'],
                buttonsStyling: false,

                confirmButtonText: json['Xoá'],
                confirmButtonClass: "btn btn-primary btn-hover-brand mt-0",
                reverseButtons: true,
                showCancelButton: true,
                cancelButtonText: json['Huỷ'],
                cancelButtonClass: "btn btn-secondary btn-hover-brand mt-0",
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: laroute.route('manager-project.manage-config.remove-status-config'),
                        method: 'POST',
                        data: {
                            manage_project_status_id : status_id
                        },
                        success: function (res) {
                            if (res.error == false) {
                                $('.block_'+groupId+'_'+count).remove();
                                $("select option[value='"+status_id+"']").remove();
                                $("select").select2();
                                location.reload();
                            } else {
                                swal(res.message,'','error');
                            }
                        }
                    });
                }
            });
        });
    },
    changeActive:function (idConfig){
        is_active = 0;
        if ($('#active_'+idConfig).is(':checked')) {
            is_active = 1;
        }

        $.ajax({
            url: laroute.route('manager-project.manage-config.notification.update-active'),
            data: {
                idConfig : idConfig,
                is_active : is_active
            },
            method: "POST",
            dataType: "JSON",
            success: function(data) {
                if (data.error == false) {
                    swal(data.message,'','success').then(function (){
                        location.reload();
                    });
                } else {
                    swal(data.message,'','error');
                    if (is_active == 0){
                        $('#active_'+idConfig).prop('checked',true);
                    } else {
                        $('#active_'+idConfig).prop('checked',false);
                    }
                }
            }
        });
    },

    changeColor: function (manage_project_status_group_config_id,keyValue){
        var color = $('#btn_'+manage_project_status_group_config_id+'_'+keyValue).attr('data-current-color');
        $('#input_'+manage_project_status_group_config_id+'_'+keyValue).val(color);
    }
}