$(document).ready(function () {
    $('#time').timepicker({
        minuteStep: 15,
        defaultTime: "12:00:00",
        showMeridian: !1,
        snapToStep: !0,
    });
    // $('#btnLuu').click(function () {
    //     $('#formEdit').validate({
    //         rules:{
    //             name: {
    //                 required: true
    //             },
    //             seat:{
    //                 required:true,
    //                 maxlength:11,
    //                 number:true,
    //                 range:[1,1000]
    //             }
    //         },
    //         messages:{
    //             name: {
    //                 required: 'Hãy nhập tên phòng'
    //             },
    //             seat:{
    //                 required:'Hãy nhập số ghế',
    //                 maxlength:'Số ghế không hợp lệ bạn vui lòng kiểm tra lại',
    //                 number:'Số ghế không hợp lệ',
    //                 range:'Số ghế sử dụng phải lớn hơn 0'
    //             }
    //         },
    //         submitHandler:function () {
    //             var id=$('#hhidden').val();
    //             var name=$('#h_name').val();
    //             var seat=$('#h_seat').val();
    //             // var is_actived=$('#h_is_actived').val();
    //             var is_actived = 0;
    //             if($('#h_is_actived').is(':checked'))
    //             {
    //                 is_actived=1;
    //             }
    //
    //
    //             $.ajax({
    //                 url: laroute.route('admin.room.submitedit'),
    //                 data: {
    //                     id:id ,
    //                     name:name,
    //                     seat:seat,
    //                     is_actived:is_actived
    //                 },
    //                 type: "POST",
    //                 dataType: 'JSON',
    //                 success: function (response ) {
    //                     if(response.status=='')
    //                     {
    //                         $("#editForm").modal("hide");
    //                         swal("Cập nhật phòng thành công", "", "success");
    //                         $('#autotable').PioTable('refresh');
    //                     }else{
    //                         $('#baoloi').text(response.status);
    //                         $('#baoloi').css('color','red');
    //                         // $('#autotable').PioTable('refresh');
    //                     }
    //                 },
    //             });
    //         }
    //     });
    //
    // });
});
var customer_appointment_time = {

    remove: function (obj, id) {
        // hightlight row
        $(obj).closest('tr').addClass('m-table__row--danger');

        swal({
            title: 'Thông báo',
            text: "Bạn có muốn xóa không",
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
                        'Xóa thành công',
                        '',
                        'success'
                    );
                    // window.location.reload();
                    $('#autotable').PioTable('refresh');
                });
            }
        });

    },
    add:function (close) {
        $('#type_add').val(close);
        $('#form').validate({
            rules: {
                time: {
                    required: true
                }
            },
            messages: {
                time: {
                    required: 'Hãy nhập khung giờ hẹn'
                }
            },
            submitHandler: function () {
                var input=$('#type_add');
                $.ajax({
                    type: 'post',
                    url: laroute.route('admin.customer_appointment_time.submitAdd'),
                    data: {
                        time:$('#time').val(),
                        close:input.val()
                    },
                    dataType: "JSON",
                    success: function (response) {
                        if(response.status==1)
                        {
                            if (response.close != 0) {
                                $("#add").modal("hide");
                            }
                            $('.error-time').text('');
                            $('#time').val('');
                            swal("Thêm khung giờ thành công", "", "success");
                            $('#autotable').PioTable('refresh');
                        }else{
                            $('.error-time').text("Khung giờ đã tồn tại");
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
    baseUrl: laroute.route('admin.customer_appointment_time.list')
});
$('.m_selectpicker').selectpicker();
