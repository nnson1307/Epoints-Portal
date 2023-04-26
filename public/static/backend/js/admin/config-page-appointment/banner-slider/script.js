var banner = {
    modal_add: function () {
        var row=document.getElementById('table_banner').getElementsByTagName("tbody")[0].getElementsByTagName("tr").length;
        if(row>=5)
        {
            $('.alert_banner').css('display','block');
            return false;
        }else{
            $('.alert_banner').css('display','none');
            $('.avatar_add').empty();
            var tpl = $('#img-tpl').html();
            $('.avatar_add').append(tpl);
            $('#modal-add-banner').modal('show');
        }
    },
    remove_img: function () {
        $('.avatar_add').empty();
        var tpl = $('#img-tpl').html();
        $('.avatar_add').append(tpl);

    },
    submit_add: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-add-banner');
            form.validate({
                rules: {
                    link: {
                        required: true
                    },
                    position: {
                        required: true,
                        number: true
                    },
                },
                messages: {
                    link: {
                        required: json['Hãy nhập link liên kết']
                    },
                    position: {
                        required: json['Hãy nhập vị trí hiển thị'],
                        number: json['Vị trí không hợp lệ']

                    },
                },
            });
            if (!form.valid()) {
                return false;
            }
            $.ajax({
                url: laroute.route('admin.config-page-appointment.submit-add-banner'),
                dataType: 'JSON',
                method: 'POST',
                data: {
                    link: $('#link').val(),
                    position: $('#position').val(),
                    banner_img: $('#banner_img').val()
                },
                success: function (res) {
                    if (res.success == 1) {
                        $('.avatar_add').empty();
                        var tpl = $('#img-tpl').html();
                        $('.avatar_add').append(tpl);
                        $('#link').val('');
                        $('#position').val('');
                        swal(json["Thêm banner thành công"], "", "success");
                        $('#modal-add-banner').modal('hide');
                        $('#autotable1').PioTable('refresh');
                    }
                }
            });
        });
    },
    modal_edit:function (id) {
       $.ajax({
           url:laroute.route('admin.config-page-appointment.edit-banner'),
           method:'POST',
           dataType:'JSON',
           data:{
               id:id
           },
           success:function (res) {
               console.log($('#img_default').val());
               $('.avatar_edit').empty();
               var tpl = $('#img-edit-tpl').html();
               if(res.item.name!=null){
                   tpl = tpl.replace(/{name}/g, res.item.name);
                   tpl = tpl.replace(/{display}/g, 'block');
               } else{
                   tpl = tpl.replace(/{name}/g, $('#img_default').val());
                   tpl = tpl.replace(/{display}/g, 'none');
               }

               $('.avatar_edit').append(tpl);

               $('#banner_img_hidden').val(res.item.name);
               $('#banner_id').val(res.item.id);
               $('#link_edit').val(res.item.link);
               $('#position_edit').val(res.item.position);
               $('#modal-edit-banner').modal('show');
           }
       });
    },
    remove_img_edit:function () {
        $('.avatar_edit').empty();
        var tpl = $('#img-edit-tpl').html();
        tpl = tpl.replace(/{name}/g, '/static/backend/images/default-placeholder.png');
        $('.avatar_edit').append(tpl);
        $('#banner_img_hidden').val('');
    },
    submit_edit:function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-edit-banner');
            form.validate({
                rules: {
                    link_edit: {
                        required: true
                    },
                    position_edit: {
                        required: true,
                        number: true
                    },
                },
                messages: {
                    link_edit: {
                        required: json['Hãy nhập link liên kết']
                    },
                    position_edit: {
                        required: json['Hãy nhập vị trí hiển thị'],
                        number: json['Vị trí không hợp lệ']
                    },
                },
            });
            if (!form.valid()) {
                return false;
            }
            $.ajax({
                url: laroute.route('admin.config-page-appointment.submit-edit-banner'),
                dataType: 'JSON',
                method: 'POST',
                data: {
                    link: $('#link_edit').val(),
                    position: $('#position_edit').val(),
                    banner_edit_hidden: $('#banner_img_hidden').val(),
                    banner_edit_new: $('#banner_img_edit').val(),
                    id: $('#banner_id').val()
                },
                success: function (res) {
                    if (res.success == 1) {
                        swal(json["Cập nhật banner thành công"], "", "success");
                        $('#modal-edit-banner').modal('hide');
                        $('#autotable1').PioTable('refresh');
                    }
                }
            });
        });
    },
    remove: function (obj, id) {
        // hightlight row
        $.getJSON(laroute.route('translate'), function (json) {
            $(obj).closest('tr').addClass('m-table__row--danger');

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
                    $.post(laroute.route('admin.config-page-appointment.remove-banner', {id: id}), function () {
                        swal(
                            json['Xóa thành công'],
                            '',
                            'success'
                        );
                        // window.location.reload();
                        $('#autotable1').PioTable('refresh');
                    });
                }
            });
        });
    },
}
$('#autotable1').PioTable({
    baseUrl: laroute.route('admin.config-page-appointment.list-banner')
});

function uploadBanner(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        var imageAvatar = $('#banner_img');
        reader.onload = function (e) {
            $('#blah_banner')
                .attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
        var file_data = $('#getFileBanner').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('link', '_config.');

        $.ajax({
            url: laroute.route("admin.upload-image"),
            method: "POST",
            data: form_data,
            contentType: false,
            cache: false,
            processData: false,
            success: function (res) {
                if (res.error == 0) {
                    $('#banner_img').val(res.file);
                    $('.delete-img').css('display', 'block');

                }

            }
        });
    }
}
function uploadBannerEdit(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        var imageAvatar = $('#banner_img_edit');
        reader.onload = function (e) {
            $('.blah1')
                .attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
        var file_data = $('#getFileEdit').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('link', '_config.');

        $.ajax({
            url: laroute.route("admin.upload-image"),
            method: "POST",
            data: form_data,
            contentType: false,
            cache: false,
            processData: false,
            success: function (res) {
                if (res.error == 0) {
                    $('.banner_img_edit').val(res.file);
                    $('.delete-img').css('display', 'block');
                }

            }
        });
    }
}