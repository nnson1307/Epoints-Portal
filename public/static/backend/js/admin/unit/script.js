$(document).ready(function () {


    $('#btnLuu').click(function () {
        $('#formEdit').validate({
            rules:{
                name:{
                    required:true
                }
            },
            messages:{
                name:{
                    required:'Hãy nhập đơn vị tính'
                }
            },
            submitHandler:function () {
                var is_standard = 0;
                if($('#h_is_standard').is(':checked'))
                {
                    is_standard=1;
                }
                var id=$('#hhidden').val();
                var name=$('#h_name').val();
                var is_actived = 0;
                if($('#h_is_actived').is(':checked'))
                {
                    is_actived=1;
                }

                $.ajax({
                    url: laroute.route('admin.unit.submitedit'),
                    data: {
                        id:id ,
                        name:name,
                        is_standard:is_standard,
                        is_actived:is_actived
                    },
                    type: "POST",
                    dataType: 'JSON',
                    success: function (response) {
                        if(response.status=='')
                        {
                            $('.error-name').text('');
                            $("#editForm").modal("hide");
                            swal("Cập nhật đơn vị tính thành công", "", "success");
                            $('#autotable').PioTable('refresh');
                        }else{
                            $('.error-name').text(response.status);
                            $('.error-name').css('color','red');
                        }
                    },

                });
            }
        });

    });
});
var unit = {

    remove: function (obj, id) {
        // hightlight row
        $(obj).closest('tr').addClass('m-table__row--danger');
        $.getJSON(laroute.route('translate'), function (json) {
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
                $.post(laroute.route('admin.unit.remove', {id: id}), function () {
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
            url: laroute.route('admin.unit.change-status'),
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
                }
            },
            messages: {
                name: {
                    required: 'Hãy nhập đơn vị tính'
                }
            },
            submitHandler: function () {
                var input=$('#type_add');
                var is_actived = 0;
                if($('#is_actived').is(':checked'))
                {
                    is_actived=1;
                }
                var is_standard = 0;
                if($('#is_standard').is(':checked'))
                {
                    is_standard=1;
                }

                $.ajax({
                    type: 'post',
                    url: laroute.route('admin.unit.submitadd'),
                    data: {
                        name: $('#name').val(),
                        is_standard: is_standard,
                        is_actived: is_actived,
                        close:input.val()
                    },
                    dataType: "JSON",
                    success: function (response) {
                        $.getJSON(laroute.route('translate'), function (json) {
                        if(response.status=='')
                        {
                            if(response.close!=0)
                            {
                                $("#add").modal("hide");
                            }
                            $('#name').val('');
                            $('.error-name').text('');
                            swal(json["Thêm đơn vị tính thành công"], "", "success");
                            $('#autotable').PioTable('refresh');
                        }else{
                            $('.error-name').text(response.status);
                            $('.error-name').css('color','red');
                        }
                    });
                    }

                })
            }
        });

    }
    ,
    edit: function (id) {
        $.ajax({
            type: 'POST',
            url: laroute.route('admin.unit.edit'),
            data: {
                id: id
            },
            dataType: 'JSON',
            success: function (response) {
                if(response['is_actived']==1)
                {
                    $('#h_is_actived').prop('checked',true);
                }else{
                    $('#h_is_actived').prop('checked',false);
                }
                if(response['is_standard']==1)
                {
                    $('#h_is_standard').prop('checked',true);
                }else{
                    $('#h_is_standard').prop('checked',false);
                }
                $('#editForm').modal("show");
                $('#hhidden').val(response['unit_id']);
                $('#h_name').val(response["name"]);
                $('#h_is_actived').val(response["is_actived"]);
                $('.error-name').text('');
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
    baseUrl: laroute.route('admin.unit.list')
});
$('.m_selectpicker').selectpicker();