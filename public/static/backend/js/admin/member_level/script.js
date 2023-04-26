
$(document).ready(function () {
    $.getJSON(laroute.route('translate'), function (json) {
        $('#description').summernote({
            height: 150,
            placeholder: json['Nhập quyền lợi...'],
            toolbar: [
                ['style', ['bold', 'italic', 'underline']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
            ]
        });

        $('#btnLuu').click(function () {
            var form = $('#formEdit');
            form.validate({
                rules: {
                    name: {
                        required: true
                    },
                    point: {
                        required: true,
                        maxlength: 9,
                    },
                    discount: {
                        required: true,
                        max: 100,
                        min: 0,
                    }
                },
                messages: {
                    name: {
                        required: json['Hãy nhập cấp độ']
                    },
                    point: {
                        required: json['Hãy nhập số điểm quy đổi'],
                        maxlength: json['Số điểm không hợp lệ vui lòng kiểm tra lại'],
                    },
                    discount: {
                        required: json['Hãy nhập % giảm giá'],
                        max: json['% giảm giá không hợp lệ ( 0% - 100% )'],
                        min: json['% giảm giá không hợp lệ ( 0% - 100% )'],
                    }
                },
            });
            if (!form.valid()) {
                return false;
            }
            var id = $('#hhidden').val();
            var name = $('#h_name').val();
            var point = $('#h_point').val();
            var discount = $('#discount').val();
            var is_actived = 0;
            if ($('#h_is_actived').is(':checked')) {
                is_actived = 1;
            }
            $.ajax({
                url: laroute.route('admin.member-level.submitedit'),
                data: {
                    id: id,
                    name: name,
                    point: point,
                    discount: discount,
                    is_actived: is_actived,
                    description: $('#description').val()
                },
                type: "POST",
                dataType: 'JSON',
                success: function (response) {
                    if (response.status == '') {
                        $("#editForm").modal("hide");
                        swal(json["Cập nhật cấp độ thành viên thành công"], "", "success");
                        $('.error-name').text('');
                        $('#autotable').PioTable('refresh');
                    } else {
                        swal(json["Cập nhật cấp độ thành viên thất bại"], "", "success");
                    }
                },
            });
        });
    });


    $('#h_point').mask('000,000,000,000', {reverse: true});
    $('#discount').mask('000', {reverse: true});
});
var member_level = {

    remove: function (obj, id) {
        // hightlight row
        $(obj).closest('tr').addClass('m-table__row--danger');
        $.getJSON(laroute.route('translate'), function (json) {
            swal({
                title: json['Thông báo'],
                text: json["Bạn có muốn xóa không"],
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
                    $.post(laroute.route('admin.member-level.remove', {id: id}), function () {
                        swal(
                            json['Xóa thành công'],
                            '',
                            'success'
                        );
                        // window.location.reload();
                        $('#autotable').PioTable('refresh');
                    });
                }
            });
        });

    },
    changeStatus: function (obj, id, action) {
        $.ajax({
            url: laroute.route('admin.member-level.change-status'),
            method: "POST",
            data: {
                id: id, action: action
            },
            dataType: "JSON"
        }).done(function (data) {
            $('#autotable').PioTable('refresh');
        });
    },
    add:function (close) {
        $('#type_add').val(close);
        $.getJSON(laroute.route('translate'), function (json) {
            $('#form').validate({
                rules: {
                    name: {
                        required: true
                    },
                    point:{
                        required:true,
                        maxlength:11,
                        min:1,
                        number:true
                    }
                },
                messages: {
                    name: {
                        required: json['Hãy nhập cấp độ']
                    },
                    point:{
                        required:json['Hãy nhập số điểm quy đổi'],
                        maxlength:json['Số điểm không hợp lệ vui lòng kiểm tra lại'],
                        min:json['Điểm quy đổi tối thiểu 1'],
                        number:json['Điểm quy đổi không hợp lệ']
                    }
                },
                submitHandler: function () {
                    var input=$('#type_add');
                    var is_actived = 0;
                    if($('#is_actived').is(':checked'))
                    {
                        is_actived=1;
                    }

                    $.ajax({
                        type: 'post',
                        url: laroute.route('admin.member-level.submitadd'),
                        data: {
                            name: $('#name').val(),
                            point:$('#point').val(),
                            is_actived: is_actived,
                            close:input.val()
                        },
                        dataType: "JSON",
                        success: function (response) {
                            if(response.status=='')
                            {
                                if(response.close!=0)
                                {
                                    $("#add1").modal("hide");
                                }
                                swal(json["Thêm cấp độ thành công"], "", "success");
                                $('#autotable').PioTable('refresh');
                                $('#name').val('');
                                $('#point').val('');
                                $('.error-name').text('');
                            }
                            else{
                                $('.error-name').text(response.status);
                                $('.error-name').css('color','red');
                            }
                        }
                    })
                }
            });
        });
    },
    edit: function (id) {
        $.ajax({
            type: 'POST',
            url: laroute.route('admin.member-level.edit'),
            data: {
                id: id
            },
            dataType: 'JSON',
            success: function (response) {
                $('#editForm').modal("show");
                $('#hhidden').val(response['member_level_id']);
                $('#h_name').val(response["name"]);
                $('#h_point').val(response["point"]);
                $('#discount').val(response["discount"]);
                $('#h_is_actived').val(response["is_actived"]);
                if( $('#h_is_actived').val()==1)
                {
                    $('#h_is_actived').prop('checked',true);
                }else{
                    $('#h_is_actived').prop('checked',false);
                }
                $('.error-name').text('');
                $("#description").summernote("code", response["description"]);
            }
        });

    },
    refresh: function () {
        $('input[name="search_keyword"]').val('');
        $('.m_selectpicker').val('');
        $('.m_selectpicker').selectpicker('refresh');
        $(".btn-search").trigger("click");

    }
};
$(".m_selectpicker").selectpicker();
$('#autotable').PioTable({
    baseUrl: laroute.route('admin.member-level.list')
});

function onKeyDownInput(o) {
    $(o).on('keydown', function (e) {
        -1 !== $.inArray(e.keyCode, [46, 8, 9, 27, 13, 110])
        || (/65|67|86|88/.test(e.keyCode) && (e.ctrlKey === true || e.metaKey === true))
        && (!0 === e.ctrlKey || !0 === e.metaKey)
        || 35 <= e.keyCode && 40 >= e.keyCode
        || (e.shiftKey || 48 > e.keyCode || 57 < e.keyCode) && (96 > e.keyCode || 105 < e.keyCode)
        && e.preventDefault()
    });
}
