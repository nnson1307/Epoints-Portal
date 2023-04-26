var message = {
    tenant_id : null,
    channel_id :  null,
    channel_social_id :  null,
    customer_id : null,
    customer_social_id : null,
    last_message_id : null,
    socket : null,
    image_message_default :  null,


    init: function(){

        message.scrollToBottom();
        //scroll message
        $('#chatBody').scroll(function() {
            if ($('#chatBody').scrollTop() == 0 ) {

                if(!message.last_message_id || !message.channel_id || !message.customer_id){
                    return;
                }

                $.ajax({
                    url: laroute.route('message.add-message'),
                    method: 'POST',
                    async: false,
                    data: { message_id : message.last_message_id, channel_id : message.channel_id, customer_id : message.customer_id },
                    success: function(res) {
                        var height = $('#scroll').height();
                        if (res.length) {
                            message.last_message_id = res[res.length - 1]['message_id'];
                        }
                        res.forEach(function(element) {

                            // if(element['content_type']=='template'){
                            //     $.ajax({
                            //         url: laroute.route('chathub.response_element.get-element'),
                            //         method: 'post',
                            //         async: false,
                            //         data: { response_element_id: element['content']},
                            //         success: function(res) {
                            //             var tpl = $('#right').html();
                            //             tpl = tpl.replace(/{time}/g, element['time']);
                            //             tpl = tpl.replace(/{full_name}/g, element['channel_name']);
                            //             tpl = tpl.replace(/{avatar}/g, element['ava']);
                            //             tpl = tpl.replace(/{content}/g, res);
                            //             $('#scroll').prepend(tpl);
                            //             // $("#chatBody").animate({
                            //             //     scrollTop: $('#scroll').height() + 200000
                            //             // }, 1);
                            //         }
                            //     });
                            // }else{
                            //     if (element['type'] == 'send') {
                            //         var tpl = $('#right').html();
                            //         tpl = tpl.replace(/{avatar}/g, element['ava']);
                            //         tpl = tpl.replace(/{full_name}/g, element['channel_name']);
                            //     } else {
                            //         var tpl = $('#left').html();
                            //         tpl = tpl.replace(/{avatar}/g, element['avatar']);
                            //         tpl = tpl.replace(/{full_name}/g, element['full_name']);
                            //     }
                            //     tpl = tpl.replace(/{time}/g, element['time']);
                            //     tpl = tpl.replace(/{message_id}/g, element['message_id']);
                            //
                            //
                            //     if (element['content_type'] == 'text') {
                            //         tpl = tpl.replace(/{bg}/g, 'kt-bg-light-success');
                            //         tpl = tpl.replace(/{content}/g, element['content']);
                            //     } else if (element['content_type'] == 'image') {
                            //         tpl = tpl.replace(/{content}/g, `<img class="message-image"  src="` + element['content'] + `">`);
                            //     } else if (element['content_type'] == 'file') {
                            //         tpl = tpl.replace(/{content}/g, `<a href="` + element['content'] + `">file</a>`);
                            //     } else {
                            //         tpl = tpl.replace(/{content}/g, element['content']);
                            //     }
                            //     $('#scroll').prepend(tpl);
                            // }
                            message.addMessage(element);
                        });
                        $("#chatBody").animate({
                            scrollTop: $('#scroll').height() - height
                        }, 1)

                    },
                });
            }
        });

        message.socket = io.connect('https://chathub-stag.epoints.vn', {
            transports: ['websocket'],
            path : '/fb/socket.io',
            query : 'tenant_id='+tenant_id + '&channel_social_id=' + message.channel_social_id,
        });

        message.socket.on('connect', function(msg) {
            message.getConversation();
        });
        message.socket.on('response-conversation', function(data) {
        });

        message.socket.on('receive-message-admin', function (data) {
            message.appendMessage(data, 'right');
        });

        message.socket.on('receive-message-customer', function (data) {
            message.appendMessage(data, 'left');
        })
    },

    selectChannel: function() {
        channel_id = $("#selectChannel").val();
        type_reading = $("#type_reading").val();
        $.ajax({
            url: laroute.route('channel.choose'),
            method: 'POST',
            data: {
                type_reading: type_reading,
                channel_id: channel_id
            },
            success: function(res) {
                message.resetParams(channel_id);
                $('#selectChannel').val(channel_id);
                $('#channelSelect').val(channel_id);
                $('#type_reading').val(type_reading);
                $('#reading_type').val(type_reading);
                $('#search_message').val('');
                $('.kt-chat__title').html('');
                $('#detail-button').addClass('hidden');
                $('#conversation-list').empty();
                $('#message-image').removeClass('hidden');
                $('#scroll').empty();
                $('#list-customer').html(res.length);
                $('#sent-message').addClass('hidden');
                $('#submit-sent').addClass('hidden');
                $('#tool').addClass('hidden');
                if(res.length){
                    var isLoadMessage = true;
                    res.forEach(function(element) {
                        var tpl = $('#customer-add').html();
                        tpl = tpl.replace(/{register_object_id}/g, element['customer_register_id']);
                        tpl = tpl.replace(/{channel_id}/g, element['channel_id']);
                        tpl = tpl.replace(/{avatar}/g, element['avatar']);
                        tpl = tpl.replace(/{full_name_con}/g, element['full_name']);
                        tpl = tpl.replace(/{full_name}/g, element['full_name']);
                        tpl = tpl.replace(/{last_message}/g, element['last_message']);
                        tpl = tpl.replace(/{last_time}/g, message.timeAgo(element['last_time']));
                        if (element['is_read'] > 0) {
                            tpl = tpl.replace(/{bg}/g, "bg-secondary");
                            tpl = tpl.replace(/{is_read}/g, element['is_read']);
                        } else {
                            tpl = tpl.replace(/{is_read}/g, '');
                            tpl = tpl.replace(/{bg}/g, '');
                        }
                        tpl = tpl.replace(/{id}/g, element['customer_register_id'] + '_' + element['channel_id']);
                        tpl = tpl.replace(/{customer_id}/g, element['customer_id']);
                        tpl = tpl.replace(/{channel_name}/g, element['channel_name']);

                        if(element['is_read'] > 0){
                            tpl = tpl.replace(/{icon_message}/g, '<i class="fas fa-circle" style="color:blue"></i>');
                            tpl = tpl.replace(/{text-color}/g, 'blue');
                        }
                        else if(element['last_message_send'] == element['last_message']){
                            tpl = tpl.replace(/{icon_message}/g, '');
                            tpl = tpl.replace(/{text-color}/g, '');
                        }
                        else{
                            tpl = tpl.replace(/{icon_message}/g, '<i class="fas fa-check-circle"></i>');
                            tpl = tpl.replace(/{text-color}/g, '');
                        }
                        $('#conversation-list').append(tpl);

                        if(isLoadMessage){
                            isLoadMessage = false;
                            message.handleClickConversation(element['customer_register_id'], element['channel_id'])
                        }
                    });
                }

            }
        })
    },

    resetParams : function(channel_id){
        message.channel_id = channel_id;
        message.customer_id = null;
        message.customer_social_id = null;
        message.last_message_id = null;
        message.image_message_default =  null;
    },

    getConversation: function(page = 1){
        message.socket.emit('get-conversation', {
            tenant_id : tenant_id ,
            channel_id : message.channel_id,
            channel_social_id : message.channel_social_id,
            page : page
        });
    },

    handleClickConversation: function(customer_id, channel_id = null) {
        $('#conversation-list').find('.kt-widget__item').removeClass('bg-secondary')
        $('#customer_' + customer_id).addClass("bg-secondary").removeClass("new-message");
        message.customer_id = customer_id;
        message.channel_id = channel_id;
        $.ajax({
            url: laroute.route('message.get-message'),
            method: 'POST',
            async:true,
            data: { customer_id : customer_id, channel_id : channel_id},
            success: function(res) {
                $('.kt-chat__title').html(res[0]['full_name']);
                var htmlReplaceChannel = $('.replace-channel').html();
                htmlReplaceChannel = htmlReplaceChannel.replace(/{channel_id}/g, channel_id);
                $('.replace-channel').html(htmlReplaceChannel);
                $('#detail-button').removeClass('hidden');
                $('#scroll').removeClass('hidden');
                $('#sent-message').removeClass('hidden');
                $('#submit-sent').removeClass('hidden');
                $('#tool').removeClass('hidden');
                $('#scroll').empty();
                $('#message-image').addClass('hidden');
                message.last_message_id = res[res.length - 1]['message_id'];
                pass = customer_id + '' + message.channel_id;
                var lastTimeCustomerSent = '';
                res.forEach(function(element) {
                    message.addMessage(element);
                    if(element['type'] == 'receive'){
                        lastTimeCustomerSent = element['time']
                    }
                });

                $('.hidden-send-button').attr('hidden', false);
                var a = moment(new Date(lastTimeCustomerSent),'YYYY-mm-dd H:i:s');
                var b = moment(new Date(),'YYYY-mm-dd H:i:s');
                var diffMinutes = b.diff(a, 'minutes');
                if(diffMinutes >= 60*24){
                    $('.hidden-send-button').attr('hidden', true);
                }
                $('#sent-message').val('').empty();
                $('#array-image-hidden').empty();
                $('#array-file-hidden').empty();
                $('#image-file').empty();
                $('#' + customer_id + '_' + message.channel_id).html('');
                setTimeout(function(){
                    message.scrollToBottom();
                }, 1000)

            },
        });
    },

    addMessage: function(element){
       // console.log(element);
        if(element['content_type']=='template'){
            if(element['content'] == '') return;
            var tpl = $('#right').html();
            tpl = tpl.replace(/{avatar}/g, element['ava']);
            tpl = tpl.replace(/{full_name}/g, element['channel_name']);
            tpl = tpl.replace(/{time}/g, message.dmyTime(element['time']));
            tpl = tpl.replace(/{message_id}/g, element['message_id']);
            $('#scroll').prepend(tpl);

            var listTemplate = JSON.parse(element['content']);
            $('.chat__message_id_'+element['message_id']).find('.kt-chat__text').html('');
            listTemplate.forEach(function(value) {
                console.log(value);
                var tpl_ = $('#template_right').html();
                tpl_ = tpl_.replace(/{title}/g, value['title']);
                tpl_ = tpl_.replace(/{subtitle}/g, value['subtitle']);
                tpl_ = tpl_.replace(/{image_url}/g, value['image_url']);
                tpl_ = tpl_.replace(/{buttons_title}/g, value['buttons'][0]['title']);
                if(value['buttons'][0]['type'] == 'web_url'){
                    tpl_ = tpl_.replace(/{buttons_url}/g, value['buttons'][0]['url']);
                } else {
                    tpl_ = tpl_.replace(/{buttons_url}/g, 'javascript:void(0)');
                }

                console.log($('.chat__message_id_'+element['message_id']).find('.kt-chat__text'));
                $('.chat__message_id_'+element['message_id']).find('.kt-chat__text').removeClass('kt-bg-light-brand');
                $('.chat__message_id_'+element['message_id']).find('.kt-chat__text').append(tpl_);
            });

        }else{
            if (element['type'] == 'send') {
                var tpl = $('#right').html();
                tpl = tpl.replace(/{avatar}/g, element['ava']);
                tpl = tpl.replace(/{full_name}/g, element['channel_name']);
            } else {
                var tpl = $('#left').html();
                tpl = tpl.replace(/{avatar}/g, element['avatar']);
                tpl = tpl.replace(/{full_name}/g, element['full_name']);
            }
            tpl = tpl.replace(/{time}/g, message.dmyTime(element['time']));
            tpl = tpl.replace(/{message_id}/g, element['message_id']);


            if (element['content_type'] == 'text') {
                tpl = tpl.replace(/{bg}/g, 'kt-bg-light-success');
                tpl = tpl.replace(/{content}/g, element['content']);
            } else if (element['content_type'] == 'image') {
                var arrImg = JSON.parse(element['content']);
                var htmlImg = '';
                arrImg.forEach(function(img){
                    htmlImg +=  '<div class="col-md-3"><div onclick="window.open(\''+img+'\')" class="message-image" style="background-image: url(' + img + ')"></div></div>';
                });
                tpl = tpl.replace(/{content}/g, `<div class="row">`+htmlImg+`</div>`);
                tpl = tpl.replace(/{col12}/g, `col-12`);
            } else if (element['content_type'] == 'file') {
                tpl = tpl.replace(/{content}/g, `<a href="` + element['content'] + `">file</a>`);
            } else {
                tpl = tpl.replace(/{content}/g, element['content']);
            }
            $('#scroll').prepend(tpl);
        }
    },

    sentMessage: function() {
        var mess = $('#sent-message').val();
        var arrayImage = new Array();
        var arrayFile = new Array();
        $('.image-hide').each(function() {
            arrayImage.push($(this).val());
        });
        $('.file-hide').each(function() {
            arrayFile.push($(this).val());
        });
        var height = $('#scroll').height();
        if (mess || arrayImage || arrayFile) {
            $.ajax({
                url: laroute.route('message.sent-message'),
                method: 'post',
                data: {
                    channel_id : message.channel_id,
                    customer_id : message.customer_id,
                    customer_social_id : message.customer_social_id,
                    mess, arrayImage, arrayFile
                },
                success: function(res) {
                    $('#sent-message').val('').empty();
                    $('#array-image-hidden').empty();
                    $('#array-file-hidden').empty();
                    $('#image-file').empty();
                    $("#chatBody").animate({
                        scrollTop: $('#scroll').height()
                    }, 1);

                    // message.appendMessage(mess, res, now(), 'text');
                }
            });
        }
    },

    getEditForm: function(channel_id = null) {
        $.ajax({
            url: laroute.route('message.get-edit-form'),
            method: 'POST',
            data: { customer_id : message.customer_id, channel_id : channel_id },
            success: function(res) {
                $('#show-form').empty();
                $('#show-form').append(res);
                $('#kt_modal_card').modal('show');
            },
        });
    },
    getFormLead: function() {
        $.getJSON(laroute.route('translate'), function (json) {
            $.ajax({
                url: laroute.route('message.get-form-lead'),
                method: 'POST',
                data: {customer_id: message.customer_id},
                success: function (res) {
                    $('#show-form').empty();
                    $('#show-form').append(res.html);
                    $("#tag_id").select2({
                        placeholder: json['Chọn tag'],
                        tags: true,
                        tokenSeparators: [",", " "],
                        createTag: function (newTag) {
                            return {
                                id: 'new:' + newTag.term,
                                text: newTag.term,
                                isNew: true
                            };
                        }
                    }).on("select2:select", function (e) {
                        if (e.params.data.isNew) {
                            // store the new tag:
                            $.ajax({
                                type: "POST",
                                url: laroute.route('customer-lead.tag.store'),
                                data: {
                                    tag_name: e.params.data.text
                                },
                                success: function (res) {
                                    // append the new option element end replace id
                                    $('#tag_id').find('[value="' + e.params.data.id + '"]').replaceWith('<option selected value="' + res.tag_id + '">' + e.params.data.text + '</option>');
                                }
                            });
                        }
                    });


                    $('#pipeline_code').select2({
                        placeholder: json['Chọn pipeline']
                    });

                    $('#journey_code').select2({
                        placeholder: json['Chọn hành trình']
                    });

                    $('#customer_type_create').select2({
                        placeholder: json['Chọn loại khách hàng']
                    });

                    $('#customer_source').select2({
                        placeholder: json['Chọn nguồn khách hàng']
                    });

                    $('#business_clue').select2({
                        placeholder: json['Chọn đầu mối doanh nghiệp']
                    });

                    // $('.phone').ForceNumericOnly();

                    $('#pipeline_code').change(function () {
                        $.ajax({
                            url: laroute.route('customer-lead.load-option-journey'),
                            dataType: 'JSON',
                            data: {
                                pipeline_code: $('#pipeline_code').val(),
                            },
                            method: 'POST',
                            success: function (res) {
                                $('.journey').empty();
                                $.map(res.optionJourney, function (a) {
                                    $('.journey').append('<option value="' + a.journey_code + '">' + a.journey_name + '</option>');
                                });
                            }
                        });
                    });

                    $('#sale_id').select2({
                        placeholder: json['Chọn nhân viên được phân bổ']
                    });

                    $('#province_id').select2({
                        placeholder: json['Chọn tỉnh/thành']
                    });

                    $('#district_id').select2({
                        placeholder: json['Chọn quận/huyện']
                    });
                    $('#pipeline_code').trigger('change');
                    $('#customer_type_create').trigger('change');
                    $('#modal-message-create-lead').modal('show');
                },
            });
        });
    },
    getFormDeal: function() {
        $.ajax({
            url: laroute.route('message.get-form-deal'),
            method: 'POST',
            data: { customer_id : message.customer_id},
            success: function(res) {
                $('#show-form').empty();
                $('#show-form').append(res);
                $('#pipeline_code').select2({
                    placeholder: 'Chọn pipeline'
                });

                $('#journey_code').select2({
                    placeholder: 'Chọn hành trình'
                });


                $("#end_date_expected").datepicker({
                    todayHighlight: !0,
                    autoclose: !0,
                    format: "dd/mm/yyyy",
                    // minDate: new Date(),
                });
                $('#pipeline_code').change(function () {
                    $.ajax({
                        url: laroute.route('customer-lead.load-option-journey'),
                        dataType: 'JSON',
                        data: {
                            pipeline_code: $('#pipeline_code').val(),
                        },
                        method: 'POST',
                        success: function (res) {
                            $('.journey').empty();
                            $.map(res.optionJourney, function (a) {
                                $('.journey').append('<option value="' + a.journey_code + '">' + a.journey_name + '</option>');
                            });
                        }
                    });
                });
                new AutoNumeric.multiple('#amount' ,{
                    currencySymbol : '',
                    decimalCharacter : '.',
                    digitGroupSeparator : ',',
                    decimalPlaces: decimal_number,
                    minimumValue: 0
                });
                $('#modal-message-create-deal').modal('show');
            },
        });
    },

    popupImage: function() {
        $('.dropzone')[0].dropzone.files.forEach(function(file) {
            file.previewElement.remove();
        });

        $('.dropzone').removeClass('dz-started');
        $('#editImage').modal('show');
    },
    popupFile: function() {
        $('.dropzone')[0].dropzone.files.forEach(function(file) {
            file.previewElement.remove();
        });

        $('.dropzone').removeClass('dz-started');
        $('#editFile').modal('show');
    },


    updateCustomer: function() {
        $.ajax({
            url: laroute.route('customer.update'),
            method: 'post',
            data: {
                id: message.customer_id,
                // address: $('.cus-address').val(),
                email: $('.cus-email').val(),
                phone: $('.cus-phone').val(),
                gender: $('.cus-gender').val()
            },
            success: function(res) {
                if (res.error) {
                    swal.fire(res.message, "", "error");
                } else {
                    $('#kt_modal_card').modal('hide');
                    swal.fire(res.message, "", "success");
                }
            },
            error: function (res) {
                var mess_error = '';
                $.map(res.responseJSON.errors, function (a) {
                    mess_error = mess_error.concat(a + '<br/>');
                });
                swal.fire(mess_error, '', "error");
            }
        });
    },

    appendMessage : function(data, position) {
        console.log(data);
        today = new Date(data.time);
        var time = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate() + ' ' + today.getHours() + ':' + today.getMinutes() + ':' + today.getSeconds();
        if(data.content_type ==='template'){
            console.log(382);
            if(data.content === '') return;
            var tpl = $('#right').html();
            tpl = tpl.replace(/{full_name}/g, data.channel.name);
            tpl = tpl.replace(/{avatar}/g, data.channel.avatar);
            console.log(387);
            tpl = tpl.replace(/{time}/g, message.dmyTime(time));
            tpl = tpl.replace(/{message_id}/g, data.message_id);
            $('#scroll').append(tpl);
            console.log(391);
            var listTemplate = JSON.parse(data.content);
            $('.chat__message_id_'+ data.message_id).find('.kt-chat__text').html('');
            listTemplate.forEach(function(value) {
                console.log(value);
                var tpl_ = $('#template_right').html();
                tpl_ = tpl_.replace(/{title}/g, value['title']);
                tpl_ = tpl_.replace(/{subtitle}/g, value['subtitle']);
                tpl_ = tpl_.replace(/{image_url}/g, value['image_url']);
                tpl_ = tpl_.replace(/{buttons_title}/g, value['buttons'][0]['title']);
                if(value['buttons'][0]['type'] === 'web_url'){
                    tpl_ = tpl_.replace(/{buttons_url}/g, value['buttons'][0]['url']);
                } else {
                    tpl_ = tpl_.replace(/{buttons_url}/g, 'javascript:void(0)');
                }
                console.log(406);
                console.log($('.chat__message_id_'+ data.message_id).find('.kt-chat__text'));
                $('.chat__message_id_'+ data.message_id).find('.kt-chat__text').removeClass('kt-bg-light-brand');
                $('.chat__message_id_'+ data.message_id).find('.kt-chat__text').append(tpl_);
            });

        } else {



            var tpl = $('#' + position).html();

            var customer_id = 0;
            if(position == 'right'){
                tpl = tpl.replace(/{full_name}/g, data.channel.name);
                tpl = tpl.replace(/{avatar}/g, data.channel.avatar);
                customer_id = data.receiver_id;
            } else {
                tpl = tpl.replace(/{full_name}/g, data.customer.name);
                tpl = tpl.replace(/{avatar}/g, data.customer.avatar);
                customer_id = data.sender_id;
            }
            data.customer_id = customer_id;
            data.time = time;

            // check nếu đang mở message mà không phải user cần nhận
            if(customer_id != message.customer_id){
                message.changeValueLeft(data);
                return;
            }
            tpl = tpl.replace(/{time}/g, message.dmyTime(time));

            tpl = tpl.replace(/{message_id}/g, data.message_id);
            tpl = tpl.replace(/{bg}/g, 'kt-bg-light-success');
            if (data.content_type == 'text') {
                tpl = tpl.replace(/{content}/g, data.content);
            } else if (data.content_type == 'image') {
                tpl = tpl.replace(/{content}/g, `<img class="message-image" src="`+ data.content + `"></img>`);
            } else if (data.content_type == 'file') {
                tpl = tpl.replace(/{content}/g, `file`);
            } else {
                tpl = tpl.replace(/{content}/g, data.content);
            }

            $('#scroll').append(tpl);

            // thay đổi text left

            message.changeValueLeft(data);
        }


    },

    changeValueLeft(data){
        var elmCustomer = $('#conversation-list').find('#customer_' + data.customer_id)
        if(elmCustomer.length){
            $(elmCustomer).find('.kt-widget__desc').html(data.content);
            $(elmCustomer).find('.kt-widget__date').html(data.time);
            $(elmCustomer).find('.ml-3').html('(' + data.conversation.is_read + ')');
            $(elmCustomer).prependTo("#conversation-list");
            $(elmCustomer).addClass("new-message");
        } else {
            // append vào
        }
    },

    scrollToBottom  : function(){
        var div = document.getElementById('chatBody');
        $('#chatBody').animate({
            scrollTop: div.scrollHeight - div.clientHeight
        }, 500);
    },

    timeAgo : function (time){
        var $time_ago = new Date(time).getTime()
        var $current_time = new Date().getTime();
        var $seconds = ($current_time - $time_ago)/1000;

        var $minutes = Math.round($seconds / 60); // value 60 is seconds
        var $hours   = Math.round($seconds / 3600); //value 3600 is 60 minutes * 60 sec
        var $days    = Math.round($seconds / 86400); //86400 = 24 * 60 * 60;
        var $weeks   = Math.round($seconds / 604800); // 7*24*60*60;
        var $months  = Math.round($seconds / 2629440); //((365+365+365+365+366)/5/12)*24*60*60
        var $years   = Math.round($seconds / 31553280); //(365+365+365+365+366)/5 * 24 * 60 * 60

        if ($seconds <= 60){
            return "vừa nhận";
        } else if ($minutes <= 60){
            if ($minutes == 1){
                return "1 phút trước";
            } else {
                return $minutes + " phút";
            }
        } else if ($hours <= 24){
            if ($hours == 1){
                return "1 giờ trước";
            } else {
                return $hours + " giờ";
            }
        } else if ($days <= 7){
            if ($days == 1){
                return "hôm qua";
            } else {
                return $days + " ngày";
            }
        } else if ($weeks <= 4.3){
            if ($weeks == 1){
                return "1 tuần trước";
            } else {
                return $weeks + " tuần";
            }
        } else if ($months <= 12){
            if ($months == 1){
                return "1 tháng trước";
            } else {
                return $months + " tháng";
            }
        } else {
            if ($years == 1){
                return "1 năm trước";
            } else {
                return $years + " năm";
            }
        }

        // return;
        //
        // var date = new Date((time || "").replace(/-/g,"/").replace(/[TZ]/g," ")),
        //     diff = (((new Date()).getTime() - date.getTime()) / 1000),
        //     day_diff = Math.floor(diff / 86400);
        //
        // if ( isNaN(day_diff) || day_diff < 0 || day_diff >= 31 )
        //     return;
        //
        // return day_diff == 0 && (
        //     diff < 60 && "vừa nhận" ||
        //     diff < 120 && "1 phút trước" ||
        //     diff < 3600 && Math.floor( diff / 60 ) + " phút" ||
        //     diff < 7200 && "1 giờ trước" ||
        //     diff < 86400 && Math.floor( diff / 3600 ) + " giờ") ||
        //     day_diff == 1 && "Hôm qua" ||
        //     day_diff < 7 && day_diff + " ngày" ||
        //     day_diff < 31 && Math.ceil( day_diff / 7 ) + " tuần";
    },

    dmyTime : function (date){
        if(date == null || date == '' || typeof date == "undefined") return  '';
        var newDate = new Date(date.replace(/-/g, "/"));
        return message.formatDateForSafari(newDate);
    },

    formatDateForSafari : function (date) {
        var year = date.getFullYear() + '',
            month = (date.getMonth() + 1).toString(),
            formatedMonth = (month.length === 1) ? ("0" + month) : month,
            day = date.getDate().toString(),
            formatedDay = (day.length === 1) ? ("0" + day) : day,
            hour = date.getHours().toString(),
            formatedHour = (hour.length === 1) ? ("0" + hour) : hour,
            minute = date.getMinutes().toString(),
            formatedMinute = (minute.length === 1) ? ("0" + minute) : minute,
            second = date.getSeconds().toString(),
            formatedSecond = (second.length === 1) ? ("0" + second) : second;
        return formatedDay + "/" + formatedMonth + "/" + year + " " + formatedHour + ':' + formatedMinute + ':' + formatedSecond;
    }
}
// $(function() {
//     // var socket = io.connect('http://localhost:3000');
//     // io.set('origins', '*:*');
//
//     socket.on('chat', function(msg) {
//         var customer_id = $('#customer_id').html();
//         var channel_id = $('#channel_id').html()
//         pass = $('#customer_id').html() + $('#channel_id').html();
//
//         pass1 = msg['customer_id'] + ""+msg['channel_id'];
//         console.log(msg['channel_id']);
//         console.log($('#channel_id').html());
//
//         //đếu đang nhắn tin vs customer vừa gửi tin nhắn
//         if(msg['channel_id']==$('#channel_id').html()){
//             if (pass1 == pass) {
//                 if(msg['type'] == 'receive'){
//                     console.log('lètf');
//                     var tpl = $('#left').html();
//                     tpl = tpl.replace(/{time}/g, msg['time']);
//                     tpl = tpl.replace(/{full_name}/g, msg['full_name']);
//                     tpl = tpl.replace(/{avatar}/g, msg['avatar']);
//                     if (msg['content_type'] == 'text') {
//                         tpl = tpl.replace(/{bg}/g, 'kt-bg-light-success');
//                         tpl = tpl.replace(/{content}/g, msg['content']);
//                     } else if (msg['content_type'] == 'image') {
//                         tpl = tpl.replace(/{content}/g, `<img class="message-image"  src="` + msg['content'] + `">`);
//                     } else if (msg['content_type'] == 'file') {
//                         tpl = tpl.replace(/{content}/g, `<a href="` + msg['content'] + `">file</a>`);
//                     } else {
//                         tpl = tpl.replace(/{content}/g, msg['content']);
//                     }
//                     $('#scroll').append(tpl);
//                     $('#sent-message').val('').empty();
//                     $("#chatBody").animate({
//                         scrollTop: $('#scroll').height() + 200000
//                     }, 1);
//                     $("#chatBody").animate({
//                         scrollTop: $('#scroll').height() + 200000
//                     }, 1);
//                     $.ajax({
//                         url: laroute.route('message.seen-message'),
//                         method: 'post',
//                         data: { channel_id, customer_id }
//                     });
//                     preCustomer(msg, customer_id);
//                 }else{
//                     console.log('right');
//                     var tpl = $('#right').html();
//                     tpl = tpl.replace(/{time}/g, msg['time']);
//                     tpl = tpl.replace(/{full_name}/g, msg['full_name']);
//                     tpl = tpl.replace(/{avatar}/g, msg['avatar']);
//                     if (msg['content_type'] == 'text') {
//                         tpl = tpl.replace(/{bg}/g, 'kt-bg-light-success');
//                         tpl = tpl.replace(/{content}/g, msg['content']);
//                         $('#scroll').append(tpl);
//                     } else if (msg['content_type'] == 'image') {
//                         tpl = tpl.replace(/{content}/g, `<img class="message-image"  src="` + msg['content'] + `">`);
//                         $('#scroll').append(tpl);
//                     } else if (msg['content_type'] == 'file') {
//                         tpl = tpl.replace(/{content}/g, `<a href="` + msg['content'] + `">file</a>`);
//                         $('#scroll').append(tpl);
//                     }else if(msg['content_type'] == 'template'){
//                         $.ajax({
//                             url: laroute.route('chathub.response_element.get-element'),
//                             method: 'post',
//                             async: false,
//                             data: { response_element_id: msg['content']},
//                             success: function(res) {
//                                 console.log(res);
//                                 tpl = tpl.replace(/{time}/g, msg['time']);
//                                 tpl = tpl.replace(/{full_name}/g, msg['full_name']);
//                                 tpl = tpl.replace(/{avatar}/g, msg['avatar']);
//                                 tpl = tpl.replace(/{content}/g, res);
//                                 $('#scroll').append(tpl);
//                                 $("#chatBody").animate({
//                                     scrollTop: $('#scroll').height() + 200000
//                                 }, 1);
//                             }
//                         });
//                     } else {
//                         tpl = tpl.replace(/{content}/g, msg['content']);
//                         $('#scroll').append(tpl);
//                     }
//                     $('#sent-message').val('').empty();
//                     $("#chatBody").animate({
//                         scrollTop: $('#scroll').height() + 200000
//                     }, 1);
//                     // preCustomer(msg, customer_id);
//                 }
//
//             }
//             //đang ko ở trang nhắn tin vs customer
//             else {
//                 preCustomer(msg, msg['customer_id']);
//             }
//         }
//     });
// });
//
//
//
// function preCustomer(msg, customer_id) {
//     console.log(customer_id);
//     $('#customer_' + msg['customer_id']).remove();
//     var tpl = $('#customer-add').html();
//     tpl = tpl.replace(/{register_object_id}/g, msg['customer_id']);
//     tpl = tpl.replace(/{channel_id}/g, msg['channel_id']);
//     tpl = tpl.replace(/{avatar}/g, msg['avatar']);
//     tpl = tpl.replace(/{full_name_con}/g, msg['full_name']);
//     tpl = tpl.replace(/{full_name}/g, msg['full_name']);
//     tpl = tpl.replace(/{last_message}/g, msg['content']);
//     tpl = tpl.replace(/{last_time}/g, msg['time']);
//     if (msg['is_read']) {
//         tpl = tpl.replace(/{bg}/g, "bg-secondary");
//         tpl = tpl.replace(/{is_read}/g, '(' + msg['is_read'] + ')');
//     } else {
//         tpl = tpl.replace(/{is_read}/g, '');
//         tpl = tpl.replace(/{bg}/g, '');
//     }
//     tpl = tpl.replace(/{id}/g, msg['customer_id'] + '_' + msg['channel_id']);
//     tpl = tpl.replace(/{customer_id}/g, msg['customer_id']);
//     $('#conversation-list').prepend(tpl);
// }
