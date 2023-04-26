var ServiceCard = {
    start: function () {
        // console.log($('input[name=_token]').val());
        Dropzone.options.imageservicecard = {
            acceptedFile: 'image/*',
            headers: {
                "X-CSRF-TOKEN": $('input[name=_token]').val()
            },
            method: "POST",
            url: laroute.route("admin.service-card.uploads-image"),
            paramName: "image_file",
            uploadMultiple: false,
            maxFilesize: 10,
            addRemoveLinks: true,
            maxFiles: 1,
            init: function () {
                this.on("success", function (file, response) {
                    console.log(file, response);
                    ServiceCard.createInputFileName(response.value);
                    if (response.status == "error") {
                        $(file.previewElement).find(".dz-error-message").html(response.value);
                    }

                    if ($(".image-preview").length != 0) {
                        $(".image-preview").html('');
                    }
                });

                this.on('removedfile', function (file) {

                    $.ajax({
                        url: laroute.route("admin.service-card.delete-uploads-image"),
                        method: "POST",
                        data: {
                            file: $("input[name=image]").val()
                        },
                        success: function (data) {
                            $("input[name=image]").val("");
                        }
                    });
                });

                // this.on("error",function () {
                //     alert("Sssss")
                // })
            }
        };

        $(document).on("click", ".btnServiceType", function () {
            var that = this;
            var type = $(this).attr("data-type");
            var id = $(this).attr("data-id");
            $.ajax({
                url: laroute.route("admin.service-card.type-template"),
                method: "GET",
                data: {
                    service_type: type,
                    service_card_id: (id != undefined) ? id : null
                },
                success: function (resp) {
                    $(".service-type-section").html(resp.html);
                    $('select[name=service_id]').select2()
                    $('input[name=money]').focus();

                    new AutoNumeric.multiple('input[name=money]', {
                        currencySymbol: '',
                        decimalCharacter: '.',
                        digitGroupSeparator: ',',
                        decimalPlaces: decimal_number,
                        minimumValue: 0
                    });
                }
            });

        });

        $(document).on("click", ".btnDateType", function () {
            $(".btnDateType").removeClass("active-btn");
            $(this).addClass("active-btn");
            var type = $(this).attr("data-type");
            $("input[name=date_type]").val(type);
        });


        $(document).on("click", ".btn-delete-img", function () {
            $.getJSON(laroute.route('translate'), function (json) {
                $(".image-preview").html('<h3 class="m-dropzone__msg-title">\n' +
                    json['Không có hình ảnh'] + '\n' +
                    '                                        </h3>');

                $("input[name=image]").val("");
            });
        });

        $(document).on("change", "input[name=date_using_limit]", function () {
            var vl = $(this).prop("checked");
            if (vl == true) {
                $("input[name=date_using]").attr("disabled", true);
            } else
                $("input[name=date_using]").attr("disabled", false);
        });

        $(document).on("change", "input[name=number_using_limit]", function () {
            var vl = $(this).prop("checked");
            if (vl == true) {
                $("input[name=number_using]").attr("disabled", true);
            } else
                $("input[name=number_using]").attr("disabled", false);
        });

        // $('input[name=price]').mask('000,000,000', {reverse: true});
        // $('input[name=money]').mask('000,000,000', {reverse: true});

        new AutoNumeric.multiple('input[name=price], input[name=money]', {
            currencySymbol: '',
            decimalCharacter: '.',
            digitGroupSeparator: ',',
            decimalPlaces: decimal_number,
            minimumValue: 0
        });
        // $("input[name=price]").on('keyup', function () {
        //     var n = parseInt($(this).val().replace(/\D/g, ''), 10);
        //     if (typeof n == 'number' && Number.isInteger(n))
        //         $(this).val(n.toLocaleString());
        //     else {
        //         $(this).val("");
        //     }
        // });
        $("input[name=date_using]").on('keyup', function () {
            var n = parseInt($(this).val().replace(/\D/g, ''), 10);
            if (typeof n == 'number' && Number.isInteger(n))
                $(this).val(n.toLocaleString());
            else {
                $(this).val("");
            }
        });
        $("input[name=number_using]").on('keyup', function () {
            var n = parseInt($(this).val().replace(/\D/g, ''), 10);
            if (typeof n == 'number' && Number.isInteger(n))
                $(this).val(n.toLocaleString());
            else {
                $(this).val("");
            }
        });

        // $(document).on('keyup', "input[name=money]", function () {
        //     var n = parseInt($(this).val().replace(/\D/g, ''), 10);
        //     if (typeof n == 'number' && Number.isInteger(n))
        //         $(this).val(n.toLocaleString());
        //     else {
        //         $(this).val("");
        //     }
        //     //do something else as per updated question
        //     // myFunc(); //call another function too
        // });
    },
    createInputFileName: function (inputname) {
        $("input[name=image]").val(inputname);
    },
    addGroupService: function (element) {
        $.getJSON(laroute.route('translate'), function (json) {
            if ($('#name-group').val().trim() != '') {
                $.ajax({
                    url: laroute.route("admin.service-card.create-group"),
                    method: "POST",
                    data: {
                        nameGroup: $('#name-group').val(),
                        description: $('#description-group').val()
                    },
                    success: function (resp) {

                        if (resp.error == 0) {
                            swal(json["Thêm nhóm thẻ dịch vụ thành công"], "", "success");
                            $("#add-group").modal("hide");
                            $("#card_group").append("<option selected value='" + resp.id + "'>" + resp.name + "</option>")
                            $('.error-name').text('');
                            $('#name-group').val('');
                            $('#description-group').val('')
                        } else {
                            $('.error-name').text(json['Tên nhóm đã tồn tại.'])
                        }

                    }
                });
            } else {
                $('.error-name').text(json['Vui lòng nhập tên nhóm thẻ.'])
            }
        });
    },
    uploadImage: function (input) {
        $('.image-info').text('');
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#blah').attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);

            var imageAvatar = $('#file_name_avatar');
            var file_data = $('#getFile').prop('files')[0];
            var form_data = new FormData();
            form_data.append('file', file_data);
            form_data.append('link', '_service-card.');

            var fsize = input.files[0].size;
            var fileInput = input,
                file = fileInput.files && fileInput.files[0];
            var img = new Image();

            img.src = window.URL.createObjectURL(file);

            img.onload = function () {
                var imageWidth = img.naturalWidth;
                var imageHeight = img.naturalHeight;

                window.URL.revokeObjectURL(img.src);

                $('.image-size').text(imageWidth + "x" + imageHeight + "px");

            };
            $('.image-capacity').text(Math.round(fsize / 1024) + 'kb');

            $('.image-format').text(input.files[0].name.split('.').pop().toUpperCase());
            if (Math.round(fsize / 1024) < 10241) {
                $.ajax({
                    url: laroute.route("admin.upload-image"),
                    method: "POST",
                    data: form_data,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        imageAvatar.val(data.file);
                    }
                });
            } else {
                $('.max-size').addClass('text-danger');
            }
        }
    }
    ,
    onmouseoverAddNew: function () {
        $('.dropdow-add-new').show();
    }
    ,
    onmouseoutAddNew: function () {
        $('.dropdow-add-new').hide();
    }
    ,
    add: function (o) {
        var cardGroup = $('#card_group').val();
        var name = $('#service_card_name').val();
        var price = $('input[name=price]').val();
        var serviceId = $('select[name=service_id]').val();
        var money = $('input[name=money]').val();
        var typeDateUsing = $('.active').find('input[name="date-use"]').val();
        var dateUsing = $('input[name=date_using]').val();
        var dateUsingNotLimit = $('#date-using-not-limit').val();
        var numberUsing = $('#number_using').val();
        var numberUsingNotLimit = $('#number-using-not-limit').val();
        var description = $('.summernote').summernote('code');
        // var description = $('#description').val();
        var image = $('#file_name_avatar').val();
        var typeService = $('#type').val();

        if ($('input[name="is_surcharge"]').is(':checked')) {
            $('#is_surcharge').val(1);
        } else {
            $('#is_surcharge').val(0);
        }

        if (testInput() == true) {
            $.ajax({
                url: laroute.route('admin.service-card.submitCreate'),
                method: "POST",
                data: {
                    cardGroup: cardGroup,
                    name: name,
                    price: price,
                    serviceId: serviceId,
                    money: money,
                    typeDateUsing: typeDateUsing,
                    dateUsing: dateUsing,
                    dateUsingNotLimit: dateUsingNotLimit,
                    numberUsing: numberUsing,
                    numberUsingNotLimit: numberUsingNotLimit,
                    description: description,
                    image: image,
                    typeService: typeService,
                    type_refer_commission: $('.refer').find('.active input[name="type_refer_commission"]').val(),
                    refer_commission_value: $('#refer_commission_value').val().replace(new RegExp('\\,', 'g'), ''),
                    refer_commission_percent: $('#refer_commission_percent').val().replace(new RegExp('\\,', 'g'), ''),
                    type_staff_commission: $('.staff').find('.active input[name="type_staff_commission"]').val(),
                    staff_commission_value: $('#staff_commission_value').val().replace(new RegExp('\\,', 'g'), ''),
                    staff_commission_percent: $('#staff_commission_percent').val().replace(new RegExp('\\,', 'g'), ''),
                    type_deal_commission: $('.deal').find('.active input[name="type_deal_commission"]').val(),
                    deal_commission_value: $('#deal_commission_value').val().replace(new RegExp('\\,', 'g'), ''),
                    deal_commission_percent: $('#deal_commission_percent').val().replace(new RegExp('\\,', 'g'), ''),
                    is_surcharge: $('#is_surcharge').val(),
                    is_remind: $('#is_remind').val(),
                    remind_value: $('#remind_value').val()
                },
                success: function (data) {
                    $.getJSON(laroute.route('translate'), function (json) {
                        if (data.error == 0) {
                            if (o == 'close') {
                                swal(json["Thêm thẻ dịch vụ thành công"], "", "success");
                                window.location = laroute.route('admin.service-card');
                            } else {
                                swal(json["Thêm thẻ dịch vụ thành công"], "", "success");
                                location.reload();
                            }
                        }
                        if (data.error_refer_commission == 1) {
                            swal(data.message, "", "error");
                        }
                        if (data.error_staff_commission == 1) {
                            swal(data.message, "", "error");
                        }
                        if (data.error_deal_commission == 1) {
                            swal(data.message, "", "error");
                        }
                    });
                }
            });
        }
    }
    ,
    edit: function () {
        var cardGroup = $('#card_group').val();
        var name = $('#service_card_name').val();
        var price = $('input[name=price]').val();
        var serviceId = $('select[name=service_id]').val();
        var money = $('input[name=money]').val();
        var typeDateUsing = $('.active').find('input[name="date-use"]').val();
        var dateUsing = $('input[name=date_using]').val();
        var numberUsing = $('#number_using').val();
        var description = $('.summernote').summernote('code');
        // var description = $('#description').val();
        var image = $('#file_name_avatar').val();
        var typeService = $('#type').val();
        var id = $('#id').val();
        var oldImage = $('#oldImage').val();
        if ($('input[name="is_surcharge"]').is(':checked')) {
            $('#is_surcharge').val(1);
        } else {
            $('#is_surcharge').val(0);
        }
        if (testInput() == true) {
            $.ajax({
                url: laroute.route('admin.service-card.submitEdit'),
                method: "POST",
                data: {
                    cardGroup: cardGroup,
                    name: name,
                    price: price,
                    serviceId: serviceId,
                    money: money,
                    typeDateUsing: typeDateUsing,
                    dateUsing: dateUsing,
                    numberUsing: numberUsing,
                    description: description,
                    image: image,
                    typeService: typeService,
                    id: id,
                    oldImage: oldImage,
                    type_refer_commission: $('.refer').find('.active input[name="type_refer_commission"]').val(),
                    refer_commission_value: $('#refer_commission_value').val().replace(new RegExp('\\,', 'g'), ''),
                    refer_commission_percent: $('#refer_commission_percent').val().replace(new RegExp('\\,', 'g'), ''),
                    type_staff_commission: $('.staff').find('.active input[name="type_staff_commission"]').val(),
                    staff_commission_value: $('#staff_commission_value').val().replace(new RegExp('\\,', 'g'), ''),
                    staff_commission_percent: $('#staff_commission_percent').val().replace(new RegExp('\\,', 'g'), ''),
                    type_deal_commission: $('.deal').find('.active input[name="type_deal_commission"]').val(),
                    deal_commission_value: $('#deal_commission_value').val().replace(new RegExp('\\,', 'g'), ''),
                    deal_commission_percent: $('#deal_commission_percent').val().replace(new RegExp('\\,', 'g'), ''),
                    is_surcharge: $('#is_surcharge').val(),
                    is_remind: $('#is_remind').val(),
                    remind_value: $('#remind_value').val()
                },
                success: function (data) {
                    $.getJSON(laroute.route('translate'), function (json) {
                        if (data.error == 0) {
                            swal(json["Cập nhật thẻ dịch vụ thành công"], "", "success");
                            setTimeout(function () {
                                location.reload();
                            }, 1500);
                        } else {
                            swal(json["Cập nhật thẻ dịch vụ thất bại"], "", "success");
                        }
                        if (data.error_refer_commission == 1) {
                            swal(data.message, "", "error");
                        }
                        if (data.error_staff_commission == 1) {
                            swal(data.message, "", "error");
                        }
                        if (data.error_deal_commission == 1) {
                            swal(data.message, "", "error");
                        }
                    });
                }
            })
        }
    },
    refer_commission: function (obj) {
        if (obj == 'money') {
            $('#refer_money').attr('class', 'btn btn-info color_button active');
            $('#refer_percent').attr('class', 'btn btn-default');
            $('#refer_commission_value').removeClass('d-none');
            $('#refer_commission_percent').addClass('d-none');
        } else {
            $('#refer_percent').attr('class', 'btn btn-info color_button active');
            $('#refer_money').attr('class', 'btn btn-default');
            $('#refer_commission_percent').removeClass('d-none');
            $('#refer_commission_value').addClass('d-none');
        }
    },
    staff_commission: function (obj) {
        if (obj == 'money') {
            $('#staff_money').attr('class', 'btn btn-info color_button active');
            $('#staff_percent').attr('class', 'btn btn-default');
            $('#staff_commission_value').removeClass('d-none');
            $('#staff_commission_percent').addClass('d-none');
        } else {
            $('#staff_percent').attr('class', 'btn btn-info color_button active');
            $('#staff_money').attr('class', 'btn btn-default');
            $('#staff_commission_percent').removeClass('d-none');
            $('#staff_commission_value').addClass('d-none');
        }
    },
    // Hoa hồng cho deal
    deal_commission: function (obj) {
        if (obj == 'money') {
            $('#deal_money').attr('class', 'btn btn-info color_button active');
            $('#deal_percent').attr('class', 'btn btn-default');
            $('#deal_commission_value').removeClass('d-none');
            $('#deal_commission_percent').addClass('d-none');
        } else {
            $('#deal_percent').attr('class', 'btn btn-info color_button active');
            $('#deal_money').attr('class', 'btn btn-default');
            $('#deal_commission_percent').removeClass('d-none');
            $('#deal_commission_value').addClass('d-none');
        }
    },
    changeRemind: function (obj) {
        if ($(obj).is(':checked')) {
            $('.div_remind_value').css('display', 'block');
            $(obj).val(1);
        } else {
            $('.div_remind_value').css('display', 'none');
            $(obj).val(0);
        }
        //Bật/ tắt giá trị back về 1
        $('#remind_value').val(1);
    }
};

ServiceCard.start();
var Summernote = {
    init: function () {
        $(".summernote").summernote({height: 160})
    }
};
jQuery(document).ready(function () {
    Summernote.init()
});
$('#card_group').select2();
$('select[name="service_id"]').select2();
$('select[name="date_type"]').select2();
$('select[name="number_type"]').select2();

$(document).ready(
    function () {
        $('select[name=service_id]').select2();
    }
)
$('.rdo').click(function () {
    $('.rdo').attr('class', 'btn btn-default rdo');
    $(this).attr('class', 'btn ss--button-cms-piospa active rdo');
});
//Lấy kiểu của thẻ.
$(document).on("click", ".btnServiceType", function () {
    $('.btnServiceType').removeClass('btn-secondary');
    $(this).addClass('ss--font-weight');
    var that = this;
    var type = $(this).attr("data-type");
    $('#date-using-not-limit').prop('checked', false);
    $('#number-using-not-limit').prop('checked', false);
    if (type == 'money') {
        $('input[name=date_using]').val(0);
        $('#number_using').val(1);
        $('input[name=date_using]').prop('disabled', true);
        $('#date-using-not-limit').prop('disabled', true);
        $('#number_using').prop('disabled', true);
        $('#number-using-not-limit').prop('disabled', true);
    } else {
        $('input[name=date_using]').val('');
        $('#number_using').val('');
        $('input[name=date_using]').prop('disabled', false);
        $('#date-using-not-limit').prop('disabled', false);
        $('#number_using').prop('disabled', false);
        $('#number-using-not-limit').prop('disabled', false);
    }
    $('#type').val(type);
});

var serviceCardGroup = {
    clearAdd: function () {
        $('#name').val('');
        $('#description').val('');
    },
    add: function (param) {
        $.getJSON(laroute.route('translate'), function (json) {
            var name = $('#name');
            var description = $('#description');
            var error = $('.error-name');
            if (name.val() != '') {
                error.text('');
                $.ajax({
                    url: laroute.route('admin.service-card-group.submit-add'),
                    method: 'POST',
                    data: {
                        name: name.val(),
                        description: description.val()
                    },
                    success: function (response) {
                        if (response.error == 1) {
                            error.text(json['Nhóm thẻ dịch vụ đã tồn tại']);
                        } else {
                            swal(
                                json['Thêm nhãn hiệu sản phẩm thành công'],
                                '',
                                'success'
                            );
                            if (param == 0) {
                                $('#modalAdd').modal('hide');
                            } else {
                                name.val('');
                                description.val('');
                                error.text('');
                            }
                            $('#autotable').PioTable('refresh');

                            $('#card_group > option').remove();
                            $('#card_group').append('<option>' + json['Chọn nhóm thẻ'] + '</option>');
                            $.each(response.optionCardGroup, function (index, element) {
                                $('#card_group').append('<option value="' + index + '">' + element + '</option>')
                            });
                        }
                    }
                });
            } else {
                error.text(json['Vui lòng nhập tên nhóm thẻ'])
            }
        });
    },
};

function testInput() {
    let flag = true;
    let group = $('select[name=service_card_group_id]');
    let name = $('#service_card_name');
    let type = $('#type').val();
    let service = $('#service_id');
    let money = $('input[name=money]');
    let dateUsing = $('input[name=date_using]');
    let numberUsing = $('#number_using');
    let price = $('input[name=price]');

    let errServiceCardGroup = $('.error-service-card-group');
    let errServiceCardName = $('.error-service-card-name');
    let errService = $('.error-service');
    let errMoney = $('.error-money');
    let errDateUsing = $('.error-date-using');
    let errNumberUsing = $('.error-number-using');
    let errPrice = $('.error-price');
    if (group.val() == '') {
        flag = false;
        $.getJSON(laroute.route('translate'), function (json) {
            errServiceCardGroup.text(json['Vui lòng chọn nhóm thẻ.']);
        });
    } else {
        errServiceCardGroup.text('');
    }
    if (name.val() == '') {
        flag = false;
        $.getJSON(laroute.route('translate'), function (json) {
            errServiceCardName.text(json['Vui lòng nhập tên thẻ.']);
        });
    } else {
        errServiceCardName.text('');
    }
    if (group.val() != '' && name.val().trim() != '') {
        $.ajax({
            url: laroute.route('admin.service-card.check-name'),
            method: "POST",
            async: false,
            data: {
                name: name.val(),
                groupId: group.val(),
                id: $('#id').val()
            },
            success: function (data) {
                if (data.error == 1) {
                    flag = false;
                    $.getJSON(laroute.route('translate'), function (json) {
                        errServiceCardName.text(json['Tên thẻ đã tồn tại.']);
                    });
                } else {
                    errServiceCardName.text('');
                }
            }
        });
    }
    if (type == 'service') {
        // if (service.val() == '') {
        //     flag = false;
        //     errService.text('Vui lòng chọn dịch vụ.')
        // } else {
        //     errService.text('');
        // }
    } else {
        if (money.val() == '') {
            flag = false;
            $.getJSON(laroute.route('translate'), function (json) {
                errMoney.text(json['Vui lòng nhập số tiền.'])
            });
        } else {
            errMoney.text('');
        }
    }
    if (dateUsing.val().trim() == '') {
        flag = false;
        $.getJSON(laroute.route('translate'), function (json) {
            errDateUsing.text(json['Vui lòng nhập hạn sử dụng.'])
        });
    } else {
        errDateUsing.text('');
    }
    if (numberUsing.val().trim() == '') {
        flag = false;
        $.getJSON(laroute.route('translate'), function (json) {
            errNumberUsing.text(json['Vui lòng nhập số lần sử dụng.'])
        });
    } else {
        errNumberUsing.text('');
    }

    if (price.val().trim() == '') {
        flag = false;
        $.getJSON(laroute.route('translate'), function (json) {
            errPrice.text(json['Vui lòng nhập giá thẻ.'])
        });
    } else {
        errPrice.text('');
    }

    if ($('#remind_value').val() != '') {
        if (isInteger(Number($('#remind_value').val())) == false) {
            flag = false;
            $.getJSON(laroute.route('translate'), function (json) {
                $('.error_remind_value').text(json['Kiểu dữ liệu không hợp lệ']);
            });
        } else if (Number($('#remind_value').val()) < 1) {
            flag = false;
            $.getJSON(laroute.route('translate'), function (json) {
                $('.error_remind_value').text(json['Số ngày tối thiếu phải lớn hơn 0']);
            });
        } else {
            $('.error_remind_value').text('');
        }
    } else {
        flag = false;
        $.getJSON(laroute.route('translate'), function (json) {
            $('.error_remind_value').text(json['Hãy nhập số ngày nhắc lại']);
        });
    }

    return flag;
}

//Function check số
function isInteger(argument) {
    return argument == argument + 0 && argument == ~~argument;
}


$.getJSON(laroute.route('translate'), function (json) {
    $('#date-using-not-limit').click(function () {
        let dateUsing = $('input[name=date_using]');
        let errDateUsing = $('.error-date-using');
        if ($(this).is(':checked')) {
            dateUsing.val(0);
            dateUsing.prop('disabled', true);
            errDateUsing.text('');
        } else {
            dateUsing.prop('disabled', false);
            dateUsing.val('');
            errDateUsing.text(json['Vui lòng nhập hạn sử dụng.']);
        }

    });
    $('#number-using-not-limit').click(function () {
        let numberUsing = $('#number_using');
        let errNumberUsing = $('.error-number-using');
        if ($(this).is(':checked')) {
            numberUsing.val(0);
            numberUsing.prop('disabled', true);
            errNumberUsing.text('');
        } else {
            numberUsing.prop('disabled', false);
            numberUsing.val('');
            errNumberUsing.text(json['Vui lòng nhập số lần sử dụng.']);
        }

    });
});

function onmouseoverAddNew() {
    $('.dropdow-add-new').show();
}

function onmouseoutAddNew() {
    $('.dropdow-add-new').hide();
}

new AutoNumeric.multiple('#refer_commission_value, #refer_commission_percent, #staff_commission_value, #staff_commission_percent, #deal_commission_value, #deal_commission_percent', {
    currencySymbol: '',
    decimalCharacter: '.',
    digitGroupSeparator: ',',
    decimalPlaces: decimal_number,
    minimumValue: 0
});
