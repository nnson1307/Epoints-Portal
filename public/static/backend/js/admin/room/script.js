$(document).ready(function () {
    $.getJSON(laroute.route('translate'), function (json) {
        $('#btnLuu').click(function () {
            $('#formEdit').validate({
                rules:{
                    name: {
                        required: true
                    },
                    seat:{
                        required:true,
                        maxlength:11,
                        number:true,
                        min:1
                    }
                },
                messages:{
                    name: {
                        required: json['Hãy nhập tên phòng']
                    },
                    seat:{
                        required: json['Hãy nhập số ghế'],
                        maxlength: json['Số ghế không hợp lệ bạn vui lòng kiểm tra lại'],
                        number: json['Số ghế không hợp lệ'],
                        min: json['Số ghế phục vụ tối thiểu 1']
                    }
                },
                submitHandler:function () {
                    var id=$('#hhidden').val();
                    var name=$('#h_name').val();
                    var seat=$('#h_seat').val();
                    // var is_actived=$('#h_is_actived').val();
                    var is_actived = 0;
                    if($('#h_is_actived').is(':checked'))
                    {
                        is_actived=1;
                    }


                    $.ajax({
                        url: laroute.route('admin.room.submitedit'),
                        data: {
                            id:id ,
                            name:name,
                            seat:seat,
                            is_actived:is_actived
                        },
                        type: "POST",
                        dataType: 'JSON',
                        success: function (response ) {
                        if(response.status=='')
                        {
                            $("#editForm").modal("hide");
                            swal(json["Cập nhật phòng thành công"], "", "success");
                            $('#autotable').PioTable('refresh');
                        }else{
                            $('#baoloi').text(response.status);
                            $('#baoloi').css('color','red');
                            // $('#autotable').PioTable('refresh');
                        }
                        },
                    });
                }
            });

        });
    });
});
var room = {

    remove: function (obj, id) {
        // hightlight row
        $(obj).closest('tr').addClass('m-table__row--danger');
        $.getJSON(laroute.route('translate'), function (json) {
            swal({
                title: json['Thông báo'],
                text: json["Bạn có muốn xóa không"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy',
                onClose: function () {
                    // remove hightlight row
                    $(obj).closest('tr').removeClass('m-table__row--danger');
                }
            }).then(function (result) {
                if (result.value) {
                    $.post(laroute.route('admin.room.remove', {id: id}), function () {
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
            url: laroute.route('admin.room.change-status'),
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
        $('#form').validate({
            rules: {
                name: {
                    required: true
                },
                seat:{
                    required:true,
                    maxlength:11,
                    min:1,
                    number:true
                }
            },
            messages: {
                name: {
                    required: 'Hãy nhập tên phòng'
                },
                seat:{
                    required:'Hãy nhập số ghế',
                    maxlength:'Số ghế không hợp lệ bạn vui lòng kiểm tra lại',
                    min:'Số ghế phục vụ tối thiếu 1',
                    number:'Số ghế phục vụ không hợp lệ'
                }
            },
            submitHandler: function () {
                var input=$('#type_add');
                var is_actived=0;
                if($('#is_actived').is(':checked'))
                {
                    is_actived=1;
                }
                $.ajax({
                    type: 'post',
                    url: laroute.route('admin.room.submitadd'),
                    data: {
                        name: $('#name').val(),
                        seat: $('#seat').val(),
                        is_actived: is_actived,
                        close:input.val()
                    },
                    dataType: "JSON",
                    success: function (response) {
                        if(response.status=='')
                        {
                            if (response.close != 0) {
                                $("#add").modal("hide");
                            }

                            $('.error-name').text('');
                            $('#name').val('');
                            $('#seat').val('');
                            swal("Thêm phòng thành công", "", "success");
                            $('#autotable').PioTable('refresh');
                        }else{
                            $('.error-name').text(response.status);
                            $('.error-name').css('color','red');
                        }
                    }
                })
            }
        });

    },
    edit: function (id) {

        $.ajax({
            type: 'POST',
            url: laroute.route('admin.room.edit'),
            data: {
                id: id
            },
            dataType: 'JSON',
            success: function (response) {
                $('#editForm').modal("show");
                $('#hhidden').val(response['room_id']);
                $('#h_name').val(response["name"]);
                $('#h_seat').val(response["seat"]);
                if(response['is_actived']==1)
                {
                    $('#h_is_actived').prop('checked',true);
                }else{
                    $('#h_is_actived').prop('checked',false);
                }

                $('#baoloi').text('');
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
$('#autotable').PioTable({
    baseUrl: laroute.route('admin.room.list')
});
$('.m_selectpicker').selectpicker();

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