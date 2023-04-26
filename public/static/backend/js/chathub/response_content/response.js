var response = {
    uploadImage: function(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#image').empty();
                var tpl = $('#icon-tpl').html();
                tpl = tpl.replace(/{link}/g, e.target.result);
                $('#image').append(tpl);
            };
            reader.readAsDataURL(input.files[0]);
            var file_data = $('#getFileLogo').prop('files')[0];
            var form_data = new FormData();
            form_data.append('file', file_data);
            $.ajax({
                url: laroute.route("chathub.response-content.upload-image"),
                method: "POST",
                data: form_data,
                contentType: false,
                cache: false,
                processData: false,
                success: function(res) {
                    $('#image_url').val(res);
                }
            });
        }
    },
    popupAddTemplate:function(){
        $.ajax({
            url: laroute.route('chathub.response-content.popup-add-template'),
            method: 'POST',
            success: function(res) {
                $('#modal').html(res);
                $('#modal-template').modal('show');
                
            },
        });
    },
    addTemplate:function(){
        $.ajax({
            url: laroute.route('chathub.template.create'),
            method: 'POST',
            data:{
                title: $('#title').val(),
                subtitle: $('#subtitle').val(),
                image_url: $('#image_url').val()
            },
            success: function(res) {
                res= res.trim();
                var opt =`<option value="`+res+`">`+$('#title').val()+`</option>`;
                $('#modal-template').modal('hide');
                $('#response_element_id').append(opt);
                if($('#image_url').val()){
                    $('#append-template').append(
                        `
                        <div class="form-group col-sm-12 d-flex row" style="margin-top: 25px" id="element`+res+`" data-value="`+res+`" name="response_element_id">
                            <div class="col-sm-12">
                                <img src="`+$('#image_url').val()+`" height="83px" width="159px">
                                <h3>`+$('#title').val()+`</h3>
                                <p>`+$('#subtitle').val()+`</p>
                                <div class="d-flex mb-2">
                                    <button type="button" class="btn btn-primary m-btn m-btn--icon" onclick="response.popupAddButton(`+res+`)">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                    </button>
                                    <div id='button_`+res+`' class="d-flex">
                                    </div>
                                </div>
                            </div>
                            <div  class="col-sm-12">
                                <button type="button" onclick="response.popupEditTemplate(`+res+`)" class="btn btn-success m-btn m-btn--icon" id="m_search">
                                    <span>
                                        <span>Sửa mẫu</span>
                                    </span>
                                </button>
                                <button type="button" onclick="response.removeTemplate(`+res+`)" class="btn btn-warning m-btn m-btn--icon" id="m_search">
                                    <span>
                                        <span>Xoá mẫu</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                        `
                    );
                }else{
                    $('#append-template').append(
                        `
                        <div class="form-group col-sm-12 d-flex row" style="margin-top: 25px" id="element`+res+`" data-value="`+res+`"  name="response_element_id">
                            <div class="col-sm-12">
                                <h3>`+$('#title').val()+`</h3>
                                <p>`+$('#subtitle').val()+`</p>
                                <div class="d-flex mb-2">
                                    <button type="button" class="btn btn-primary m-btn m-btn--icon" onclick="response.popupAddButton(`+res+`)">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                    </button>
                                    <div id='button_`+res+`' class="d-flex">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <button type="button" onclick="response.popupEditTemplate(`+res+`)" class="btn btn-success m-btn m-btn--icon" id="m_search">
                                    <span>
                                        <span>Sửa mẫu</span>
                                    </span>
                                </button>
                                <button type="button" onclick="response.removeTemplate(`+res+`)" class="btn btn-warning m-btn m-btn--icon" id="m_search">
                                    <span>
                                        <span>Xoá mẫu</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                        `
                    );
                }
            },
            error: function (res) {
                var mess_error = '';
                $.map(res.responseJSON.errors, function (a) {
                    mess_error = mess_error.concat(a + '<br/>');
                });
                swal('Thêm thất bại', mess_error, "error");
            }
        });
    },
    updateTemplate:function(id){
        $.ajax({
            url: laroute.route('chathub.template.update'),
            method: 'POST',
            data:{
                response_element_id: id,
                title: $('#title').val(),
                subtitle: $('#subtitle').val(),
                image_url: $('#image_url').val()
            },
            success: function(res) {
                // $('#'+res).remove();
                $('#modal-template').modal('hide');
                if($('#image_url').val()){
                    $('#element'+id).html(
                        `
                            <div class="col-sm-12">
                                <img src="`+$('#image_url').val()+`" height="83px" width="159px">
                                <h3>`+$('#title').val()+`</h3>
                                <p>`+$('#subtitle').val()+`</p>
                                <div class="d-flex mb-2">
                                    <div id='button_`+res+`' class="d-flex">
                                    </div>
                                    <button type="button" class="btn btn-primary m-btn m-btn--icon" onclick="response.popupAddButton(`+res+`)">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                            <div  class="col-sm-12">
                                <button type="button" onclick="response.popupEditTemplate(`+res+`)" class="btn btn-success m-btn m-btn--icon" id="m_search">
                                    <span>
                                        <span>Sửa mẫu</span>
                                    </span>
                                </button>
                                <button type="button" onclick="response.removeTemplate(`+res+`)" class="btn btn-warning m-btn m-btn--icon" id="m_search">
                                    <span>
                                        <span>Xoá mẫu</span>
                                    </span>
                                </button>
                            </div>
                        `
                    );
                }else{
                    $('#element'+id).html(
                        `
                            <div  class="col-sm-12">
                                <h3>`+$('#title').val()+`</h3>
                                <p>`+$('#subtitle').val()+`</p>
                                <div class="d-flex mb-2">
                                    <div id='button_`+res+`' class="d-flex">
                                    </div>
                                    <button type="button" class="btn btn-primary m-btn m-btn--icon" onclick="response.popupAddButton(`+res+`)">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                            <div  class="col-sm-12">
                                <button type="button" onclick="response.popupEditTemplate(`+res+`)" class="btn btn-success m-btn m-btn--icon" id="m_search">
                                    <span>
                                        <span>Sửa mẫu</span>
                                    </span>
                                </button>
                                <button type="button" onclick="response.removeTemplate(`+res+`)" class="btn btn-warning m-btn m-btn--icon" id="m_search">
                                    <span>
                                        <span>Xoá mẫu</span>
                                    </span>
                                </button>
                            </div>
                        `
                    );
                }
            },
            error: function (res) {
                var mess_error = '';
                $.map(res.responseJSON.errors, function (a) {
                    mess_error = mess_error.concat(a + '<br/>');
                });
                swal('Chỉnh sửa thất bại', mess_error, "error");
            }
        });
    },
    popupEditTemplate:function(id){
        $.ajax({
            url: laroute.route('chathub.response-content.popup-edit-template'),
            method: 'POST',
            data:{
                response_element_id: id
            },
            success: function(res) {
                $('#modal').html(res);
                $('#modal-template').modal();
                
            },
        });
    },
    removeTemplate:function(id){
        $('#element'+id).remove();
    },


    popupAddButton:function(response_element_id){
        $.ajax({
            url: laroute.route('chathub.response-content.popup-add-button'),
            method: 'POST',
            data: {
                response_element_id: response_element_id
            },
            success: function(res) {
                $('#modal').html(res);
                $('#modal-template').modal('show');
                
            },
        });
    },
    addButton:function(id){
        $.ajax({
            url: laroute.route('chathub.button.create'),
            method: 'POST',
            data:{
                title: $('[name=btn_title]').val(),
                type: $('[name=btn_type]').val(),
                url: $('[name=btn_url]').val(),
                payload: $('[name=btn_payload]').val(),
                response_element_id: id
            },
            success: function(res) {
                res= res.trim();
                $('#modal-template').modal('hide');
                $('#button_'+id).append(`
                <div class="btn-group" id='btn_`+res+`'>
                    <button style="margin-right: 1px;" type="button" onclick='response.popupEditButton(`+res+`)' class="btn btn-primary">`+$('[name=btn_title]').val()+`</button>
                    <button style="margin-right: 1px;" type="button" onclick='response.removeButton(`+res+`)' class="btn btn-primary">X</button>
                </div>
                `);
            },
            error: function (res) {
                var mess_error = '';
                $.map(res.responseJSON.errors, function (a) {
                    mess_error = mess_error.concat(a + '<br/>');
                });
                swal('Thêm thất bại', mess_error, "error");
            }
        });
    },
    updateButton:function(id){
        $.ajax({
            url: laroute.route('chathub.button.update'),
            method: 'POST',
            data:{
                title: $('[name=btn_title]').val(),
                type: $('[name=btn_type]').val(),
                url: $('[name=btn_url]').val(),
                payload: $('[name=btn_payload]').val(),
                response_button_id: id
            },
            success: function(res) {
                $('#modal-template').modal('hide');
                $('#btn_'+id).html(`
                    <button style="margin-right: 1px;" type="button" onclick='response.popupEditButton(`+id+`)' class="btn btn-primary">`+$('[name=btn_title]').val()+`</button>
                    <button style="margin-right: 1px;" type="button" onclick='response.removeButton(`+id+`)' class="btn btn-primary">X</button>
                `);
            },
            error: function (res) {
                var mess_error = '';
                $.map(res.responseJSON.errors, function (a) {
                    mess_error = mess_error.concat(a + '<br/>');
                });
                swal('Chỉnh sửa thất bại', mess_error, "error");
            }
        });
    },
    popupEditButton:function(response_button_id){
        $.ajax({
            url: laroute.route('chathub.response-content.popup-edit-button'),
            method: 'POST',
            data:{
                response_button_id: response_button_id
            },
            success: function(res) {
                $('#modal').html(res);
                $('#modal-template').modal();
                
            },
        });
    },
    removeButton:function(id){
        $.ajax({
            url: laroute.route('chathub.button.remove'),
            method: 'POST',
            data:{
                response_button_id: id
            },
            success: function(res) {
                $('#btn_'+id).remove();
            },
            error: function(res) {
                if (res.responseJSON != undefined) {
                    console.log(res);
                    var mess_error = '';
                    $.map(res.responseJSON, function(a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                }
            }
        });
        
    },
}


