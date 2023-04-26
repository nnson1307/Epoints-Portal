$(document).ready(function () {
    $.getJSON(laroute.route('translate'), function (json) {
        $('#btnLuu').click(function () {
            var formEdit = $('#formEdit');
            formEdit.validate({
                rules:{
                    transport_name: {
                        required: true
                    },
                    // charge: {
                    //     required: true,
                    //     maxlength: 11
                    // },
                    address: {
                        required: true
                    },
                    contact_name: {
                        required: true
                    },
                    contact_phone: {
                        required: true,
                        maxlength: 20,
                        minlength: 10,
                        number:true
                    },
                    token:{
                        required: function () {
                            if($('#transport_code').val() == 'ghn'){
                                return true;
                            }
                            return false;
                        },
                        maxlength: 191,
                    }
                },
                messages:{
                    transport_name: {
                        required: json['Hãy nhập đơn vị giao hàng']
                    },
                    // charge: {
                    //     required: json["Hãy nhập phí giao hàng"],
                    //     maxlength: json['Phí quá lớn hãy kiểm tra lại'],
                    // },
                    address: {
                        required: json['Hãy nhập địa chỉ giao hàng']
                    },
                    contact_name: {
                        required: json['Hãy nhập tên người đại diện']
                    },
                    contact_phone: {
                        required: json['Hãy nhập sđt người đại diện'],
                        minlength: json['Sđt ít nhất 10 số'],
                        maxlength: json['Sđt không hợp lệ'],
                        number:json['Sđt không hợp lệ']
                    },
                    token: {
                        required: 'Hãy nhập token đối tác giao hàng',
                        maxlength : 'Token đối tác giao hàng vượt quá 191 ký tự'
                    }
                },
                submitHandler:function () {
                    var id=$('#hhidden').val();
                    var transport_name=$('#h_transport_name').val();
                    // var charge=$('#h_charge').val().replace(new RegExp('\\,', 'g'), '');
                    var description=$('#h_description').val();
                    var address=$('#h_address').val();
                    var contact_name=$('#h_contact_name').val();
                    var contact_phone=$('#h_contact_phone').val();
                    var contact_title=$('#h_contact_title').val();
                    var token=$('#token').val();
                    var transport_code=$('#transport_code').val();
                    $.ajax({
                        url: laroute.route('admin.transport.submitedit'),
                        data: {
                            id:id ,
                            transport_name:transport_name,
                            // charge:charge,
                            description:description,
                            address:address,
                            contact_name:contact_name,
                            contact_phone:contact_phone,
                            contact_title:contact_title,
                            token:token,
                            transport_code:transport_code,
                        },
                        type: "POST",
                        dataType: 'JSON',
                        success: function (response) {
                            if(response.status=='')
                            {
                                $("#editForm").modal("hide");
                                swal(json["Cập nhật đơn vị giao hàng thành công"], "", "success");
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
});
var transport = {

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
                    $.post(laroute.route('admin.transport.remove', {id: id}), function () {
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
        $.getJSON(laroute.route('translate'), function (json) {

            var form = $('#form');

            form.validate({
                rules: {
                    transport_name: {
                        required: true
                    },
                    // charge: {
                    //     required: true,
                    //     maxlength: 11
                    // },
                    address: {
                        required: true
                    },
                    contact_name: {
                        required: true
                    },
                    contact_phone: {
                        required: true,
                        maxlength: 20,
                        minlength: 10,
                        number:true
                    },
                    contact_title:{
                        required:true
                    }
                },
                messages: {
                    transport_name: {
                        required: json['Hãy nhập đơn vị giao hàng']
                    },
                    // charge: {
                    //     required: json["Hãy nhập phí giao hàng"],
                    //     maxlength: json['Phí quá lớn hãy kiểm tra lại'],
                    // },
                    address: {
                        required: json['Hãy nhập địa chỉ giao hàng']
                    },
                    contact_name: {
                        required: json['Hãy nhập tên người đại diện']
                    },
                    contact_phone: {
                        required: json['Hãy nhập số điện thoại'],
                        minlength: json['Sđt ít nhất 10 số'],
                        maxlength: json['Sđt không hợp lệ'],
                        number: json['Sđt không hợp lệ']
                    },
                    contact_title:{
                        required: json['Hãy nhập chức danh']
                    }
                }
            });

            if (!form.valid()) {
                return false;
            }

            var input=$('#type_add');
            $.ajax({
                type: 'post',
                    url: laroute.route('admin.transport.submitadd'),
                    data: {
                        transport_name: $('#transport_name').val(),
                        // charge: $('#charge').val().replace(new RegExp('\\,', 'g'), ''),
                        address: $('#address').val(),
                        contact_name: $('#contact_name').val(),
                        contact_phone: $('#contact_phone').val(),
                        contact_title: $('#contact_title').val(),
                        description: $('#description').val(),
                        close:input.val()
                    },
                    dataType: "JSON",
                    success: function (response) {
                        if(response.status=='')
                        {
                            if(response.close!=0)
                            {
                                $("#add").modal("hide");
                            }
                            $('.error-name').text('');
                            $('#transport_name').val('');
                            $('#charge').val('');
                            $('#address').val('');
                            $('#contact_name').val('');
                            $('#contact_phone').val('');
                            $('#contact_title').val('');
                            $('#description').val('');
                            swal(json["Thêm đơn vị vận chuyển thành công"], "", "success");
                            $('#autotable').PioTable('refresh');
                        }else{
                            $('.error-name').text(response.status);
                            $('.error-name').css('color','red');
                        }
                    }
            });
        });
        // $('#form').validate({
        //     rules: {
        //         transport_name: {
        //             required: true
        //         },
        //         charge: {
        //             required: true,
        //             maxlength: 11
        //         },
        //         address: {
        //             required: true
        //         },
        //         contact_name: {
        //             required: true
        //         },
        //         contact_phone: {
        //             required: true,
        //             maxlength: 20,
        //             minlength: 10,
        //             number:true
        //         },
        //         contact_title:{
        //             required:true
        //         }
        //     },
        //     messages: {
        //         transport_name: {
        //             required: 'Hãy nhập đơn vị giao hàng'
        //         },
        //         charge: {
        //             required: "Hãy nhập phí giao hàng",
        //             maxlength: 'Phí quá lớn hãy kiểm tra lại',
        //         },
        //         address: {
        //             required: 'Hãy nhập địa chỉ giao hàng'
        //         },
        //         contact_name: {
        //             required: 'Hãy nhập tên người đại diện'
        //         },
        //         contact_phone: {
        //             required: 'Hãy nhập số điện thoại',
        //             minlength: 'Sđt ít nhất 10 số',
        //             maxlength: 'Sđt không hợp lệ',
        //             number:'Sđt không hợp lệ'
        //         },
        //         contact_title:{
        //             required:'Hãy nhập chức danh'
        //         }
        //     },
        //     submitHandler: function () {
        //         var input=$('#type_add');
        //         $.ajax({
        //             type: 'post',
        //             url: laroute.route('admin.transport.submitadd'),
        //             data: {
        //                 transport_name: $('#transport_name').val(),
        //                 charge: $('#charge').val().replace(new RegExp('\\,', 'g'), ''),
        //                 address: $('#address').val(),
        //                 contact_name: $('#contact_name').val(),
        //                 contact_phone: $('#contact_phone').val(),
        //                 contact_title: $('#contact_title').val(),
        //                 description: $('#description').val(),
        //                 close:input.val()
        //             },
        //             dataType: "JSON",
        //             success: function (response) {
        //                 if(response.status=='')
        //                 {
        //                     if(response.close!=0)
        //                     {
        //                         $("#add").modal("hide");
        //                     }
        //                     $('.error-name').text('');
        //                     $('#transport_name').val('');
        //                     $('#charge').val('');
        //                     $('#address').val('');
        //                     $('#contact_name').val('');
        //                     $('#contact_phone').val('');
        //                     $('#contact_title').val('');
        //                     $('#description').val('');
        //                     swal("Thêm đơn vị vận chuyển thành công", "", "success");
        //                     $('#autotable').PioTable('refresh');
        //                 }else{
        //                     $('.error-name').text(response.status);
        //                     $('.error-name').css('color','red');
        //                 }
        //             }
        //         })
        //     }
        // });

    },
    edit: function (id) {
        $.ajax({
            type: 'POST',
            url: laroute.route('admin.transport.edit'),
            data: {
                id: id
            },
            dataType: 'JSON',
            success: function (response) {
                $('#editForm').modal("show");
                $('#hhidden').val(response['transport_id']);
                $('#h_transport_name').val(response["transport_name"]);
                $('#h_charge').val(response["charge"]);
                $('#h_address').val(response["address"]);
                $('#h_contact_name').val(response["contact_name"]);
                $('#h_contact_phone').val(response["contact_phone"]);
                $('#h_contact_title').val(response["contact_title"]);
                $('#h_description').val(response["description"]);
                $('#transport_code').val(response["transport_code"]);
                $('#token').val(response["token"]);
                $('.error-name').text('');
                $('#h_charge').mask('000,000,000', {reverse: true});

                $('.block-token').hide();
                if (response["transport_code"] == 'ghn'){
                    $('.block-token').show();
                }
            }

        });

    },
    refresh: function () {
        $('input[name="search_keyword"]').val('');
        $(".btn-search").trigger("click");
    },
    modal_add:function () {
        $('#add').modal('show');
        $('#form')[0].reset();
    }

};
$('#autotable').PioTable({
    baseUrl: laroute.route('admin.transport.list')
});
function maskNumberPriceProductChild() {
    $('.charge-add').maskNumber({integer: true});

}