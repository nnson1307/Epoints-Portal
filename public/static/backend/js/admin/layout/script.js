//== Class definition
$('#sm-form').click(function () {
    let keyword = $('#m_typeahead_2').val();
    if (keyword != '') {
        $('#form-search').submit();
    }
});
var $input = $("#m_typeahead_2");
$input.typeahead({minLength: 1});
var Layout = {
    searchTypeahead: function (o) {
        var $this = $(o).val();
        $.ajax({
            url: laroute.route('admin.layout.search-dashboard'),
            dataType: 'JSON',
            method: 'POST',
            data: {
                keyword: $this
            },
            success: function (data) {
                $input.typeahead({
                    hint: true,
                    highlight: true,
                    source: data,
                    autoSelect: false,
                    items: 15,
                    minLength: 1
                });
                $('.dropdown-item').click(
                    function () {
                        var current = $input.typeahead("getActive");
                        if (current) {
                            $('#idSearchDashboard').val(current.id);
                            $('#nameSearchDashboard').val(current.name);
                            if ($('#idSearchDashboard').val() != '' && $('#nameSearchDashboard').val() != '') {
                                $('#form-search-hhidden').submit();
                            }
                        }
                    }
                );
            }
        });
    },
    clickDetail: function (id,name) {
        console.log(id)
        console.log(name)
        $('#idSearchDashboard').val(id);
        $('#nameSearchDashboard').val(name);
        if ($('#idSearchDashboard').val() != '' && $('#nameSearchDashboard').val() != '') {
            $('#form-search-hhidden').submit();
        }
    }
};

// $input.change(function () {
//     var current = $input.typeahead("getActive");
//     console.log(current.id);
// if (current) {
//     $('#idSearchDashboard').val(current.id);
//     $('#nameSearchDashboard').val(current.name);
//     if ($('#idSearchDashboard').val() != '' && $('#nameSearchDashboard').val() != '') {
//         $('#form-search-hhidden').submit();
//     }
// }
// });
//


var Paginate = {
    pageClickCustomer: function (page) {
        $.ajax({
            url: laroute.route('admin.layout.search.paging-customer'),
            method: "POST",
            data: {
                page: page,
                keyword: $('#keyword').val()
            },
            success: function (data) {
                $('.table-content-customer').empty();
                $('.table-content-customer').append(data);
            }
        });
    },
    pageClickCustomerAppointment: function (page) {
        $.ajax({
            url: laroute.route('admin.layout.search.paging-customer-appointment'),
            method: "POST",
            data: {
                page: page,
                keyword: $('#keyword').val()
            },
            success: function (data) {
                $('.table-content-customer-appointment').empty();
                $('.table-content-customer-appointment').append(data);
            }
        });
    },
    pageClickOrder: function (page) {
        $.ajax({
            url: laroute.route('admin.layout.search.paging-order'),
            method: "POST",
            data: {
                page: page,
                keyword: $('#keyword').val()
            },
            success: function (data) {
                $('.table-content-order').empty();
                $('.table-content-order').append(data);
            }
        });
    }
};

$('#m_quicksearch_input').keyup(function (e) {
    if (e.keyCode == 13) {
        $(this).trigger("enterKey");
    }
});

$('#m_quicksearch_input').bind("enterKey", function (e) {
    if ($('#m_quicksearch_input').val() != '') {
        $('#form-search').submit();
    }
});

const TIME_REQUEST = 60 * 1000;     // 1 minute
var page = 1;
setInterval(function() {
    // mApp.unblock("#div-loading");
    // call api get notification new
    $.ajax({
        url: laroute.route('staff-notification.number-of-noti'),
        method: "POST",
        global: false,
        success: function (result) {
            // cập nhật lại số thông báo mới
            if (result > 0) {
                $('.notification').css('display', 'block')
                $('#number-noti-new').text(result);
                $('#number-noti-new_hidden').val(result);
            }
        }
    });

    notification.chatNoti();
}, TIME_REQUEST);
$('#scroll-notify').scroll(function() {
    let scrollHeight = $('#list-notify').height();
    let scrollPosition = $('#scroll-notify').height() + $('#scroll-notify').scrollTop();
    if (scrollHeight == scrollPosition) {
        page++;
        $.ajax({
            url: laroute.route('staff-notification.get-all'),
            method: "POST",
            data: {
                page: page
            },
            success: function (result) {
                let arrayNotify = result.getAllNotification;
                let dotNoti = '<span class="m-nav__link-badge m-badge m-badge--dot m-badge--dot-small m-badge--danger"></span>';
                if (arrayNotify != null) {
                    $.map(arrayNotify, function(item) {
                        let tpl = $('#tpl-notification').html();
                        tpl = tpl.replace(/{id_noti}/g, item.staff_notification_id);
                        tpl = tpl.replace(/{txt_noti}/g, item.notification_title);
                        tpl = tpl.replace(/{txt_content_noti}/g, item.notification_message);
                        tpl = tpl.replace(/{time_noti}/g, item.time_ago);
                        if (item.is_read == 0) {
                            tpl = tpl.replace(/{is_read}/g, 'unread');
                        } else {
                            tpl = tpl.replace(/{is_read}/g, '');
                        }
                        $('#list-notify').append(tpl);
                    });
                }
                // Lấy số lượng thông báo mới ban đầu
                let number_noti_old = parseInt($('#number-noti-new_hidden').val());
                let number_noti_new = 0;
                if (number_noti_old > arrayNotify.length) {
                    number_noti_new = number_noti_old - arrayNotify.length;
                } else {
                    $('.notification').css('display', 'none')
                }
                $('#number-noti-new').text(number_noti_new);
                $('#number-noti-new_hidden').val(number_noti_new);
            }
        });
    }
});
var notification = {
    _init: function () {
        $.ajax({
            url: laroute.route('staff-notification.number-of-noti'),
            method: "POST",
            global: false,
            success: function (result) {
                // cập nhật lại số thông báo mới
                if (result > 0) {
                    // notification.playSound();

                    $('.notification').css('display', 'block');
                    $('#number-noti-new').text(result);
                    $('#number-noti-new_hidden').val(result);
                }
            }
        });

        notification.chatNoti();
    },

    chatNoti : function(){
        $.ajax({
            url: laroute.route('chathub.chat-notification-count'),
            method: "POST",
            global: false,
            success: function (result) {
                if(result.total > 0) {
                    $('.chathub_chat').find('.noti-chat').html(result.total).css('display', 'block')
                } else {
                    $('.chathub_chat').find('.noti-chat').html('0').css('display', 'none')
                }
            }
        }, 'JSON');
    },

    loadNotification: function () {
        //Clear thông báo mới khi click vào chuông
        $.ajax({
            url: laroute.route('staff-notification.clear-new'),
            method: 'POST',
            dataType: 'JSON'
        });

        page = 1;
        $('#list-notify').empty();
        // load all notification
        $.ajax({
            url: laroute.route('staff-notification.get-all'),
            method: "POST",
            data: {
                page: page
            },
            success: function (result) {
                let arrayNotify = result.getAllNotification;
                let dotNoti = '<span class="m-nav__link-badge m-badge m-badge--dot m-badge--dot-small m-badge--danger"></span>';
                if (arrayNotify != null) {
                    $.map(arrayNotify, function(item) {
                        let tpl = $('#tpl-notification').html();
                        tpl = tpl.replace(/{id_noti}/g, item.staff_notification_id);
                        tpl = tpl.replace(/{txt_noti}/g, item.notification_title);
                        tpl = tpl.replace(/{txt_content_noti}/g, item.notification_message);
                        tpl = tpl.replace(/{time_noti}/g, item.time_ago);
                        if (item.is_read == 0) {
                            tpl = tpl.replace(/{dot_noti}/g, dotNoti);
                            tpl = tpl.replace(/{is_read}/g, 'unread');
                        } else {
                            tpl = tpl.replace(/{dot_noti}/g, '');
                            tpl = tpl.replace(/{is_read}/g, '');
                        }
                        $('#list-notify').append(tpl);
                    });
                }
                // Lấy số lượng thông báo mới ban đầu
                // let number_noti_old = parseInt($('#number-noti-new_hidden').val());
                // let number_noti_new = 0;
                // if (number_noti_old > arrayNotify.length) {
                //     number_noti_new = number_noti_old - arrayNotify.length;
                // } else {
                //     $('.notification').css('display', 'none')
                // }
                // $('#number-noti-new').text(0);
                $('.notification').css('display', 'none');
                $('#number-noti-new_hidden').val(0);
            }
        });
    },
    updateStatus: function (obj, id) {
        // Update trạng thái
        $.ajax({
            url: laroute.route('staff-notification.update-status'),
            method: "POST",
            data: {
                staff_notification_id: id
            },
            success: function (result) {
                if (result.error == false) {
                    // Update lại css
                    $(obj).find('.unread').attr('class', 'm-list-timeline__text txt_noti');
                    $(obj).find('.m-badge--dot').remove();

                    if(result.object_id != null) {
                        window.location.href = result.url;
                    }
                } else {
                    $(obj).find('.unread').attr('class', 'm-list-timeline__text txt_noti');
                    $(obj).find('.m-badge--dot').remove();
                    swal(
                        result.message,
                        '',
                        'warning'
                    );
                }
            }
        });
    },
    playSound: function () {
        var url = '/static/backend/mp3/notify.mp3';
        window.AudioContext = window.AudioContext||window.webkitAudioContext; //fix up prefixing
        var context = new AudioContext(); //context
        var source = context.createBufferSource(); //source node
        source.connect(context.destination); //connect source to speakers so we can hear it
        var request = new XMLHttpRequest();
        request.open('GET', url, true);
        request.responseType = 'arraybuffer'; //the response is an array of bits
        request.onload = function() {
            context.decodeAudioData(request.response, function(response) {
                source.buffer = response;
                source.start(0); //play audio immediately
                source.loop = true;
            }, function () { console.error('The request failed.'); } );
        };
        request.send();
    }
};

function showNumberNotiChatHub(number) {
    if(number > 0) {
        $('.chathub_inbox').find('.noti-chathub').html(number).css('display', 'block')
    } else {
        $('.chathub_inbox').find('.noti-chathub').html('0').css('display', 'none')
    }
}