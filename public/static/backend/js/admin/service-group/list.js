var serviceGroup = {

    loadFormAdd: function(obj, action,id)
    {
        if(action == 'add'){
            $('#formServiceGroup').load(laroute.route('service-group.add') ,function (data) {
                $('#formServiceGroup').modal('show');
            }) ;
        }else{
            $("table tr").removeClass('m-table__row--success');
            $(obj).closest('tr').addClass('m-table__row--success');
            $.get(laroute.route('service-group.edit',{id:id}),  function (data) {
                $('#formServiceGroup').html(data.html);
                $('#formServiceGroup').modal('show');
            },'json');
        }
    },
    doAddAndEditServiceGroup : function (action) {
        if(action == 'add'){
            $.post(laroute.route('service-group.do-add'),{service_group_name: $('input[name=service_group_name]').val(), is_active:$('select[name=is_active] option:selected').val()}, function (data) {
                $('div input').removeClass('has-error');
                $('div.form-group span.service_group_name').empty();
                if(data.error){
                    $.each(data.error, function (k, v) {
                        $('div input[name='+k+']').removeClass('has-error').addClass('has-error') ;
                        $('div span.'+k+'').removeClass('error').addClass('error').html(v[0]) ;
                    });
                }else {
                    if(data.status == false){
                        $('div span.service_group_name').removeClass('error').addClass('error').html(data.messages) ;
                    }else {
                        $('div.modal-body').prepend('<div class="messages-info alert alert-success">\n' +
                            ' <strong>Success!</strong> '+data.messages+'.\n' +
                            '</div>');
                        setTimeout($('div.modal-body div.messages-info').fadeOut(), 112000);
                        $('#formAddServiceGroup')[0].reset();
                        var status  = '<a style="color: #ffffff" class="m-badge  m-badge--wide m-badge--'+((data.object.is_active ==1) ? "success" : "danger")+'">'+((data.object.is_active ==1) ? "Đang hoạt động" : "Tạm ngưng")+'</a>' ;
                        var tr = ' <tr>' +
                            '<td>'+data.object.service_group_id+'</td>' +
                            '<td>'+data.object.service_group_name+'</td>' +
                            '<td>'+status+'</td>' +
                            '<td>'+data.object.created_at+'</td>' +
                            '<td>'+data.object.updated_at+'</td>' +
                            '<td>' +
                            '<a href="#" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="View"><i class="la la-edit"></i></a>' +
                            '<button onclick="serviceGroup.remove(this, '+data.object.service_group_id+')" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Delete"><i class="la la-trash"></i></button>'+
                            '</td>' +
                            '</tr>';
                        $("table>tbody").prepend(tr);
                        $("table>tbody>tr:first").addClass('m-table__row--success')
                    }
                }
            },'JSON')
        }else{
            $.post(laroute.route('service-group.do-edit'),{service_group_name: $('input[name=service_group_name]').val(), service_group_id: $('input[name=service_group_name]').val(),is_active:$('select[name=is_active] option:selected').val()}, function (data) {
                $('div input').removeClass('has-error');
                $('div.form-group span.service_group_name').empty();
                if(data.error){
                    $.each(data.error, function (k, v) {
                        $('div input[name='+k+']').removeClass('has-error').addClass('has-error') ;
                        $('div span.'+k+'').removeClass('error').addClass('error').html(v[0]) ;
                    });
                }else {
                    if(data.status == false){
                        $('div span.service_group_name').removeClass('error').addClass('error').html(data.messages) ;
                    }else {
                        $('div.modal-body').prepend('<div class="messages-info alert alert-success">\n' +
                            ' <strong>Success!</strong> '+data.messages+'.\n' +
                            '</div>');
                        var status  = '<a style="color: #ffffff" class="m-badge  m-badge--wide m-badge--'+((data.object.is_active ==1) ? "success" : "danger")+'">'+((data.object.is_active ==1) ? "Đang hoạt động" : "Tạm ngưng")+'</a>' ;
                        var tr = ' <tr>' +
                            '<td>'+data.object.service_group_id+'</td>' +
                            '<td>'+data.object.service_group_name+'</td>' +
                            '<td>'+status+'</td>' +
                            '<td>'+data.object.created_at+'</td>' +
                            '<td>'+data.object.updated_at+'</td>' +
                            '<td>' +
                            '<a href="#" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="View"><i class="la la-edit"></i></a>' +
                            '<button onclick="serviceGroup.remove(this, '+data.object.service_group_id+')" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Delete"><i class="la la-trash"></i></button>'+
                            '</td>' +
                            '</tr>';
                        $("table>tbody").prepend(tr);
                        $("table>tbody>tr:first").addClass('m-table__row--success')
                    }
                    $('#autotable').PioTable('refresh');
                }
            },'JSON')
        }
    },
    remove:function (obj , id) {
        $(obj).closest('tr').addClass('m-table__row--danger');

        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            onClose: function()
            {
                $(obj).closest('tr').removeClass('m-table__row--danger');
            }
        }).then(function(result) {
            if (result.value)
            {
                $.post(laroute.route('service-group.remove', {id:id}), function() {
                    swal(
                        'Deleted!',
                        'Your selected Item has been deleted.',
                        'success'
                    );
                    $('#autotable').PioTable('refresh');
                });
            }
        });
    },
    changeStatus:function (obj , id , action) {
        $.post(laroute.route('service-group.change-status'), {id: id, action: action}, function (data) {
            $('#autotable').PioTable('refresh');
        }, 'JSON');
    }
};

$('#autotable').PioTable({
    baseUrl: laroute.route('service-group.list')
});