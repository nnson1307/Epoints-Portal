$('#staff_title_id').select2();
$('#modal-add-note').on('hidden.bs.modal', function(){
    $('#content-note').val('');
});

$("button[data-dismiss=modal]").click(function(){
    $(this).closest(".modal").modal('hide');
    $('.modal-backdrop').remove();
});

$(document).on('click', '#btn-customer-care', function(){
    let id = $(this).data('id');
    listLead.popupCustomerCare(id);
});

$(document).on('click', '#btn-show-add-file', function(){
    let id = $(this).data('id');
    $.ajax({
        method: 'POST',
        url: $(this).data('action'),
        data: {customer_lead_id: id},
        success: function (response) {
            if (response.error == 0) {
                $('#zone-popup-show').html(response.data);
                $('#modal-add-file').modal('show');
                loadingCreate = false;
            }
        }
    });
});

$(document).on('click', '.edit-file', function(){
    let fileId = $(this).data('id');
    let id = $(this).closest('table').data('id');

    $.ajax({
        method: 'POST',
        url: $('.list-table-file').data('action'),
        data: {fileId: fileId, customer_lead_id: id},
        success: function (response) {
            if (response.error == 0) {
                $('#zone-popup-show').html(response.data);
                $('#modal-edit-file').modal('show');
                loadingCreate = false;
            }
        }
    });
});

$(document).on('change', '#files', function() {
    $('.error-file-name').html('');
    let self = $(this);
    let file = this.files[0];
    let filename = file.name;
    console.log({filename});

    var formData = new FormData();
    formData.append('file', file);
    formData.append('link', '_customerlead.');

    if(loadingCreate == false){
        loadingCreate = true;
        $.ajax({
            method: 'POST',
            url: $('.frm-add-file').data('action'),
            data: formData,
            contentType: false,
            processData: false,
            cache: false,
            success: function (response) {
                console.log({response});
                if (response.error == 0) {
                    setTimeout(() => {
                        self.closest('.form-group').find('.upload-file-name').html(filename);
                        self.closest('.frm-add-file').find('.full-path').val(response.file);
                    }, 1000);
                    loadingCreate = false;
                }
            }
        });
    }
});

$(document).on('click', '#btn-add-file', function(e){
    e.preventDefault();
    $('.error-file-name').html('');
    let content = $('#file-note').val();
    let submit_type = $('.submit_type').val();
    let customer_lead_file_id = $('.customer_lead_file_id').val();
    let id = $(this).data('id');
    let fullPath = $('.full-path').val();

    if(fullPath == '' && submit_type != 'update'){
        $('.error-file-name').html('Bạn chưa chọn file');
    }else{

        let data = {
            content,
            file_name: $('.upload-file-name').text(),
            submit_type: submit_type,
            customer_lead_id: id,
            full_path: fullPath
        }

        if(customer_lead_file_id){
            data.customer_lead_file_id = customer_lead_file_id;
        }

        if(loadingCreate == false){
            loadingCreate = true;
            $.ajax({
                method: 'POST',
                url: $(this).data('action'),
                data: data,
                success: function (response) {
                    if (response.error == 0) {
                        swal(response.message, "", "success").then(function (result) {
                            $('#modal-add-file').modal('hide');
                            $('#modal-edit-file').modal('hide');
                            listLead.detail(id);
                        });

                        loadingCreate = false;
                    }
                }
            });
        }
    }
});

$(document).on('click', '#btn-add-note', function(e){
    e.preventDefault();
    $('.error-note').html('');
    let content = $('#content-note').val();
    let id = $(this).data('id');

    if(!content){
        $('.error-note').html('Bạn chưa nhập ghi chú');
    }
    else{
        if(loadingCreate == false){
            loadingCreate = true;
            $.ajax({
                method: 'POST',
                url: $(this).data('action'),
                data: $('#frm-add-note').serialize(),
                success: function (response) {
                    if (response.error == 0) {
                        swal(response.message, "", "success").then(function (result) {
                            $('#modal-add-note').modal('hide');
                            listLead.detail(id);
                        });

                        loadingCreate = false;
                    }
                }
            });
        }
    }
});

$(document).on('click', '#btn-add-contact', function(e){
    e.preventDefault();
    $('.error-full-name').html('');
    $('.error-phone').html('');
    let full_name = $('#full_name').val();
    let phone = $('#phone').val();
    let id = $(this).data('id');
    let validate = true;

    if(!full_name){
        $('.error-full-name').html('Bạn chưa nhập họ tên');
        validate = false;
    }

    if(!phone){
        $('.error-phone').html('Bạn chưa nhập số điện thoại');
        validate = false;
    }

    if(validate){
        if(loadingCreate == false){
            loadingCreate = true;
            $.ajax({
                method: 'POST',
                url: $(this).data('action'),
                data: $('#frm-add-contact').serialize(),
                success: function (response) {
                    if (response.error == 0) {
                        swal(response.message, "", "success").then(function (result) {
                            $('#modal-add-contact').modal('hide');
                            listLead.detail(id);
                        });

                        loadingCreate = false;
                    }
                }
            });
        }
    }
});

function registerSummernote(element, placeholder, max, callbackMax) {
    $('.description').summernote({
        placeholder: '',
        tabsize: 2,
        height: 100,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['fontname', ['fontname', 'fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture']],
        ],
        callbacks: {
            onImageUpload: function (files) {
                for (let i = 0; i < files.length; i++) {
                    uploadImgCk(files[i]);
                }
            },
            onKeydown: function (e) {
                var t = e.currentTarget.innerText;
                if (t.length >= max) {
                    //delete key
                    if (e.keyCode != 8)
                        e.preventDefault();
                    // add other keys ...
                }
            },
            onKeyup: function (e) {
                var t = e.currentTarget.innerText;
                if (typeof callbackMax == 'function') {
                    callbackMax(max - t.length);
                }
            },
            onPaste: function (e) {
                var t = e.currentTarget.innerText;
                var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                e.preventDefault();
                // var all = t + bufferText;
                var all = bufferText;
                document.execCommand('insertText', false, all.trim().substring(0, max - t.length));
                // document.execCommand('insertText', false, bufferText);
                if (typeof callbackMax == 'function') {
                    callbackMax(max - t.length);
                }
            }
        },
    });
}