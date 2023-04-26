var vali;
$.ajax({
    url: laroute.route('chathub.validation'),
    method: 'GET',
    async: false,
    success: function(json) {
        vali = json;
    }
});
var response_content_submit= {
    Edit: function(){
        var end = 0;
        if($('[name="response_end"]').is(':checked')){
            end = 1;
        }
        var target = 0;
        if($('[name="response_target"]').is(':checked')){
            target = 1;
        }
        var personalized = 0;
        if($('[name="is_personalized"]').is(':checked')){
            personalized = 1;
        }
        var forward = 0;
        if($('[name="response_forward"]').is(':checked')){
            forward = 1;
        }
        let response_element_id = [];
        $('[name="response_element_id"]').each(function(e){
            response_element_id.push($(this).attr('data-value'));
        })
        let template_type = $('[name="template_type"] option:selected').val();
        if(template_type != "generic"){
            if(response_element_id.length < 2 || response_element_id.length > 4){
                swal.fire("Loại mẫu List cần 2-4 mẫu", "", "error");
                return;
            }
        }
        $.ajax({
            url: laroute.route('chathub.response-content.update'),
            method: 'POST',
            data: {
                response_content_id: $('[name="response_content_id"]').val(),
                title: $('[name="title"]').val(),
                response_content: $('[name="response_content"]').val(),
                response_end: end,
                response_target: target,
                is_personalized:personalized,
                response_forward: forward,
                brand_entities: $('[name="brand_entities"] option:selected').val(),
                template_type: $('[name="template_type"] option:selected').val(),
                response_element_id: response_element_id
            },
            success: function(res) {
                if (res.error) {
                    swal.fire(res.message, res.ex_message, "error");
                } else {
                    swal.fire(res.message, "", "success").then(function(res) {
                        window.location.href = laroute.route('chathub.response-content');
                    });

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

    Save: function(){
        var end = 0;
        if($('[name="response_end"]').is(':checked')){
            end = 1;
        }
        var target = 0;
        if($('[name="response_target"]').is(':checked')){
            target = 1;
        }
        var personalized = 0;
        if($('[name="is_personalized"]').is(':checked')){
            personalized = 1;
        }
        var forward = 0;
        if($('[name="response_forward"]').is(':checked')){
            forward = 1;
        }
        let response_element_id = [];
        $('[name="response_element_id"]').each(function(e){
            response_element_id.push($(this).attr('data-value'));
        })
        let template_type = $('[name="template_type"] option:selected').val();
        if(template_type != "generic"){
            if(response_element_id.length < 2 || response_element_id.length > 4){
                swal.fire("Loại mẫu List cần 2-4 mẫu", "", "error");
                return;
            }
        }
        $.ajax({
            url: laroute.route('chathub.response-content.insert'),
            method: 'POST',
            data: {
                title: $('[name="title"]').val(),
                response_content: $('[name="response_content"]').val(),
                response_end: end,
                response_target: target,
                is_personalized:personalized,
                response_forward: forward,
                brand_entities: $('[name="brand_entities"] option:selected').val(),
                template_type: $('[name="template_type"] option:selected').val(),
                response_element_id: response_element_id
            },
            success: function(res) {
                if (res.error) {
                    swal.fire(res.message, res.ex_message, "error");
                } else {
                    swal.fire(res.message, "", "success").then(function(res) {
                        window.location.href = laroute.route('chathub.response-content');
                    });

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
    }
}