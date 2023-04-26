var sttBranch = 0;

$(document).ready(function () {
    $.getJSON(laroute.route('translate'), function (json) {
        // $(".quantity").TouchSpin({
        //     initval: 1,
        //     min: 1,
        //     buttondown_class: "btn btn-default down btn-ct",
        //     buttonup_class: "btn btn-default up btn-ct"
        //
        // });

        $('.summernote').summernote({
            height: 150,
            placeholder: json['Nhập mô tả chi tiết...'],
            toolbar: [
                ['style', ['bold', 'italic', 'underline']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
            ]
        });
        $('.note-btn').attr('title', '');

        $('#select2').select2({
            placeholder: json['Chọn đơn vị tính']
        });
        $('#branch_id').select2({
            placeholder: json["Chọn chi nhánh"],
        });

        $('#product_id').select2({
            ajax: {
                url: laroute.route('admin.search.product'),
                dataType: 'json',
                delay: 250,
                type: 'POST',
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    };
                    return query;
                }
            },
            placeholder: json['Chọn sản phẩm sử dụng'],
            minimumInputLength: 1,
        });

        $('#service_accompanied_id').select2({
            ajax: {
                url: laroute.route('admin.search.services'),
                dataType: 'json',
                delay: 250,
                type: 'POST',
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    return query
                }
            },
            placeholder: json['Chọn dịch vụ đi kèm'],
            minimumInputLength: 1
        })

        $('#service_category_id').select2({
            placeholder: json["Chọn nhóm dịch vụ"],
        });

        $('#price_standard').change(function () {
            $('.old_price').empty();
            $('.old_price ').append($(this).val());
            $('.old_price ').append('<input type="hidden"  id="old_tb" name="old_tb" value=' + $(this).val() + '>');
        });
        $('#new_price').change(function () {
            $.each($('#table_branch').find(".branch_tb"), function () {
                var stt = $(this).find($('.stt')).val();

                $('.new_price').empty();
                $('.price_week').empty();
                $('.price_month').empty();
                $('.price_year').empty();

                $('.new_price ').append('<input  class="form-control m-input btn-sm width-250 new_'+ stt +'" style="text-align: right;" id="new_tb" name="new_tb" value=' + $('#new_price').val() + '>');
                $('.price_week ').append('<input  class="form-control m-input btn-sm width-250 new_'+ stt +'" style="text-align: right;" id="price_week" name="price_week" value=' + parseInt($('#new_price').val().replace(new RegExp('\\,', 'g'), '')) * 7 + '>');
                $('.price_month ').append('<input  class="form-control m-input btn-sm width-250 new_'+ stt +'" style="text-align: right;" id="price_month" name="price_month" value=' + parseInt($('#new_price').val().replace(new RegExp('\\,', 'g'), '')) * 30 + '>');
                $('.price_year ').append('<input  class="form-control m-input btn-sm width-250 new_'+ stt +'" style="text-align: right;" id="price_year" name="price_year" value=' + parseInt($('#new_price').val().replace(new RegExp('\\,', 'g'), '')) * 365 + '>');


                new AutoNumeric.multiple('.new_' + stt + '', {
                    currencySymbol: '',
                    decimalCharacter: '.',
                    digitGroupSeparator: ',',
                    decimalPlaces: decimal_number,
                    minimumValue: 0
                });
            });
        });
        $('#checkAll').click(function () {
            $('#frm_branch_service').css('display', 'block');
            $('#table_branch > tbody').empty();
            //Check vào all sẽ disable select 2
            if ($('input[name="checkAll"]').is(':checked')) {
                $('select[name="branch_id[]"]').prop("disabled", true);
                $('#frm_branch_service').css('display', 'block');
            } else {
                $('select[name="branch_id[]"]').prop("disabled", false);
                $('#frm_branch_service').css('display', 'none');
            }
            if ($('#checkAll').is(':checked')) {
                $('#branch_id > option').prop("selected", "selected");
                $('#branch_id').trigger("change");
                $('select[name="branch_id"] > option').each(function () {
                    sttBranch++;
                    var old = $('#price_standard').val();
                    var new_p = $('#new_price').val();
                    var loc_old = old.replace(new RegExp('\\,', 'g'), '');
                    var loc_new = new_p.replace(new RegExp('\\,', 'g'), '');

                    // var stts = $('#table_branch tr').length;
                    var tpl = $('#branch-tpl').html();
                    tpl = tpl.replace(/{stt}/g, sttBranch);
                    tpl = tpl.replace(/{branch_name}/g, $(this).text());
                    tpl = tpl.replace(/{branch_id}/g, $(this).val());
                    tpl = tpl.replace(/{old_price}/g, old);
                    tpl = tpl.replace(/{new_price}/g, loc_new);
                    tpl = tpl.replace(/{price_week}/g, parseInt($('#new_price').val().replace(new RegExp('\\,', 'g'), '')) * 7);
                    tpl = tpl.replace(/{price_month}/g, parseInt($('#new_price').val().replace(new RegExp('\\,', 'g'), '')) * 30);
                    tpl = tpl.replace(/{price_year}/g, parseInt($('#new_price').val().replace(new RegExp('\\,', 'g'), '')) * 365);
                    $('#table_branch > tbody').append(tpl);

                    new AutoNumeric.multiple('.new_' + sttBranch + '', {
                        currencySymbol: '',
                        decimalCharacter: '.',
                        digitGroupSeparator: ',',
                        decimalPlaces: decimal_number,
                        minimumValue: 0
                    });

                    $('.remove_branch').click(function () {
                        $(this).closest('.branch_tb').remove();
                    });

                });
            } else {
                $('#table_branch > tbody').empty();
                $('select[name="branch_id"] > option').removeAttr('selected');
                $('select[name="branch_id"]').val(null).trigger('change');

            }
        });
        $('#branch_id').on('select2:select', function (event) {
            sttBranch++;
            $('#frm_branch_service').css('display', 'block');
            // var stts = $('#table_branch tr').length;
            var tpl = $('#branch-tpl').html();
            tpl = tpl.replace(/{stt}/g, sttBranch);
            tpl = tpl.replace(/{branch_name}/g, event.params.data.text);
            tpl = tpl.replace(/{branch_id}/g, event.params.data.id);
            tpl = tpl.replace(/{old_price}/g, $('#price_standard').val());
            tpl = tpl.replace(/{new_price}/g, $('#new_price').val());
            tpl = tpl.replace(/{price_week}/g, parseInt($('#new_price').val().replace(new RegExp('\\,', 'g'), '')) * 7);
            tpl = tpl.replace(/{price_month}/g, parseInt($('#new_price').val().replace(new RegExp('\\,', 'g'), '')) * 30);
            tpl = tpl.replace(/{price_year}/g, parseInt($('#new_price').val().replace(new RegExp('\\,', 'g'), '')) * 365);
            $('#table_branch > tbody').append(tpl);

            new AutoNumeric.multiple('.new_' + sttBranch + '', {
                currencySymbol: '',
                decimalCharacter: '.',
                digitGroupSeparator: ',',
                decimalPlaces: decimal_number,
                minimumValue: 0
            });

            $('.remove_branch').click(function () {
                $(this).closest('.branch_tb').remove();
            });

        });
        $('#branch_id').on('select2:unselect', function (event) {
            $('.branch_tb').remove(":contains(" + event.params.data.text + ")");
        });
        $('#check_all_branch').click(function () {
            $('.check:checkbox').prop('checked', this.checked);

        });
        //Sản phẩm đi kèm
        if ($('input[name="check_product"]').is(':checked')) {
            $('select[name="product_id[]"]').prop("disabled", false);
            $('#product_service').css('display', 'block');
        } else {
            $('select[name="product_id[]"]').prop("disabled", true);
            $('#product_service').css('display', 'none');
        }
        $('#check_product').click(function () {
            if ($('input[name="check_product"]').is(':checked')) {
                $('select[name="product_id[]"]').prop("disabled", false);
                $('#product_service').css('display', 'block');
            } else {
                $('select[name="product_id[]"]').prop("disabled", true);
                $('#product_service').css('display', 'none');
            }
        });

        $('#product_id').on('select2:select', function (event) {
            // console.log(event.params.data.id);
            $(this).val('').trigger('change')
            var check = true;
            $.each($('#table_product tbody tr'), function () {
                let codeHidden = $(this).find("input[name='product_hidden']");
                let value_id = codeHidden.val();
                let code = event.params.data.id;
                if (value_id == code) {
                    check = false;
                    let quantitySv = codeHidden.parents('tr').find('input[name="quantity"]').val();
                    let numbers = parseInt(quantitySv) + 1;
                    codeHidden.parents('tr').find('input[name="quantity"]').val(numbers);
                    // codeHidden.parents('tr').find('.quantity').empty();
                    //codeHidden.parents('tr').find('.discount-tr-'+type_check+'-'+code+'').append('<a class="abc m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary" href="javascript:void(0)" onclick="order.modal_discount('+amount+','+code+','+id_type+')"><i class="la la-plus"></i></a>');
                }
            });
            if (check == true) {
                var stts = $('#table_product tr').length;
                var tpl = $('#product-tpl').html();
                tpl = tpl.replace(/{stt}/g, stts);
                tpl = tpl.replace(/{product_name}/g, event.params.data.text);
                tpl = tpl.replace(/{product_id}/g, event.params.data.id);
                tpl = tpl.replace(/{id_unit}/g, event.params.data.id);
                $("#table_product > tbody").append(tpl);

                var id = $(this).val();

                $.ajax({
                    url: laroute.route('admin.service.getUnit'),
                    method: "POST",
                    data: {id: id},
                    dataType: "JSON",
                    success: function (data) {
                        // $('.unit').empty();
                        $.each(data, function (index, element) {
                            $('.unit').append('<option></option>');
                            $('.unit').append('<option value="' + index + '">' + element + '</option>');
                        });
                    }
                });
                $(".in_quantity").TouchSpin({
                    initval: 1,
                    min: 1,
                    buttondown_class: "btn btn-metal btn-sm",
                    buttonup_class: "btn btn-metal btn-sm"
                });
                $.getJSON(laroute.route('translate'), function (json) {
                    $('.unit').select2({
                        placeholder: json['Chọn đơn vị tính']
                    });
                });
            }


            $('.remove_product').click(function () {
                $(this).closest('.pro_tb').remove();
            });
        });
        $('#product_id').on('select2:unselect', function (event) {
            $('.pro_tb').remove(":contains(" + event.params.data.text + ")");
        });

        /**
         * Start
         * Dịch vụ đi kèm
         */
        if ($('input[name="check_service_accompanied"]').is(':checked')) {
            $('select[name="service_accompanied_id[]"]').prop('disabled', false)
            $('#service_accompanied').css('display', 'block')
        } else {
            $('select[name="service_accompanied_id[]"]').prop('disabled', true)
            $('#service_accompanied').css('display', 'none')
        }
        $('#check_service_accompanied').click(function () {
            if ($('input[name="check_service_accompanied"]').is(':checked')) {
                $('select[name="service_accompanied_id[]"]').prop('disabled', false)
                $('#service_accompanied').css('display', 'block')
            } else {
                $('select[name="service_accompanied_id[]"]').prop('disabled', true)
                $('#service_accompanied').css('display', 'none')
            }
        })
        $('#service_accompanied_id').on('select2:select', function (event) {
            // console.log(event.params.data.id);
            $(this).val('').trigger('change')
            var check = true
            $.each($('#table_service_accompanied tbody tr'), function () {
                let codeHidden = $(this).find("input[name='service_accompanied_hidden']");
                let value_id = codeHidden.val()
                let code = event.params.data.id
                if (value_id == code) {
                    check = false
                    let quantitySv = codeHidden.parents('tr').find('input[name="quantity"]').val()
                    let numbers = parseInt(quantitySv) + 1
                    codeHidden.parents('tr').find('input[name="quantity"]').val(numbers)
                    // codeHidden.parents('tr').find('.quantity').empty();
                    //codeHidden.parents('tr').find('.discount-tr-'+type_check+'-'+code+'').append('<a class="abc m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary" href="javascript:void(0)" onclick="order.modal_discount('+amount+','+code+','+id_type+')"><i class="la la-plus"></i></a>');
                }
            })
            if (check == true) {
                var stts = $('#table_service_accompanied tr').length
                var tpl = $('#service-accompanied-tpl').html()
                tpl = tpl.replace(/{stt}/g, stts)
                tpl = tpl.replace(/{service_accompanied_name}/g, event.params.data.text)
                tpl = tpl.replace(/{service_accompanied_id}/g, event.params.data.id)
                tpl = tpl.replace(/{service_accompanied_code}/g, event.params.data.code)
                $('#table_service_accompanied > tbody').append(tpl)

                var id = $(this).val()

                $('.in_quantity').TouchSpin({
                    initval: 1,
                    min: 1,
                    buttondown_class: 'btn btn-default down btn-ct',
                    buttonup_class: 'btn btn-default up btn-ct'
                })

            }

            $('.remove_service_accompanied').click(function () {
                $(this).closest('.accompanied_tb').remove()
            })
        })
        $('#service_accompanied_id').on('select2:unselect', function (event) {
            $('.accompanied_tb').remove(':contains(' + event.params.data.text + ')');
        })
        /**
         * end
         * Dịch vụ đi kèm
         */

        if ($('input[name="is_actived"]').is(':checked')) {
            $('#h_is_actived').val(1);
        } else {
            $('#h_is_actived').val(0);
        }
        $('#h_is_actived').click(function () {
            if ($('input[name="is_actived"]').is(':checked')) {
                $('#h_is_actived').val(1);
            } else {
                $('#h_is_actived').val(0);
            }
        });


        $('.btn3').click(function () {
            $.getJSON(laroute.route('translate'), function (json) {
                var form = $('#formAdd');

                form.validate({
                    rules: {
                        service_category_id: {
                            required: true
                        },
                        service_name: {
                            required: true
                        },
                        service_code: {
                            required: true
                        },
                        price_standard: {
                            required: true
                        },
                        // time: {
                        //     required: true
                        // },
                        branch_id: {
                            required: true
                        },
                        description1: {
                            maxlength: 250
                        },
                        remind_value: {
                            integer: true,
                            min: 1,
                            required: true
                        }
                    },
                    messages: {
                        service_category_id: {
                            required: json['Hãy chọn nhóm dịch vụ']
                        },
                        service_name: {
                            required: json['Hãy nhập tên dịch vụ']
                        },
                        service_code: {
                            required: json['Hãy nhập mã dịch vụ']
                        },
                        price_standard: {
                            required: json['Hãy nhập giá dịch vụ']
                        },
                        time: {
                            required: json['Hãy nhập thời gian sử dụng']
                        },
                        branch_id: {
                            required: json["Hãy chọn chi nhánh"]
                        },
                        description1: {
                            maxlength: json['Mô tả ngắn tối đa 250 kí tự']
                        },
                        remind_value: {
                            integer: json['Kiểu dữ liệu không hợp lệ'],
                            min: json['Số ngày tối thiếu phải lớn hơn 0'],
                            required: json['Hãy nhập số ngày nhắc lại']
                        }
                    },
                });

                if (!form.valid()) {
                    return false;
                }

                $('.black-title').css('color', 'black');
                var continues = true;
                var standard = $('#price_standard').val();
                var loc = standard.replace(new RegExp('\\,', 'g'), '');
                var active = $('#h_is_actived').val();
                // $('.new_hid').val($('.new').val().replace(/\D+/g, ''));
                var branch_table = [];
                $.each($('#table_branch').find(".branch_tb"), function () {
                    var $checkPrice = $(this).find("td input#new_tb");
                    var $checkWeek = $(this).find("td input#week_tb");
                    var $checkMonth = $(this).find("td input#month_tb");
                    var $checkYear = $(this).find("td input#year_tb");
                    $checkPrice.parents('td').find('.error_new_price').text('');
                    $checkWeek.parents('td').find('.error_price_week').text('');
                    $checkMonth.parents('td').find('.error_price_month').text('');
                    $checkYear.parents('td').find('.error_price_year').text('');
                    var $tds = $(this).find("td input");
                    if ($checkPrice.val() == "") {
                        $checkPrice.parents('td').find('.error_new_price').text('Hãy nhập giá chi nhánh');
                        continues = false;
                    }
                    // if ($checkWeek.val() == "") {
                    //     $checkWeek.parents('td').find('.error_price_week').text('Hãy nhập giá tuần');
                    //     continues = false;
                    // }
                    // if ($checkMonth.val() == "") {
                    //     $checkMonth.parents('td').find('.error_price_month').text('Hãy nhập giá tháng');
                    //     continues = false;
                    // }
                    // if ($checkYear.val() == "") {
                    //     $checkYear.parents('td').find('.error_price_year').text('Hãy nhập giá năm');
                    //     continues = false;
                    // }
                    $.each($tds, function () {
                        branch_table.push($(this).val());
                    });

                });
                var product_table = [];
                $.each($('#table_product tr input[name="product_hidden"]').parentsUntil("tbody"), function () {
                    var $tds = $(this).find("td input,td select");
                    var $check_quantity = $(this).find("td input.in_quantity ");
                    if ($check_quantity.val() == "") {
                        $check_quantity.parents('td').find('.error_quantity').text(json['Hãy nhập số lượng sản phẩm']);
                        continues = false;
                    }
                    $.each($tds, function () {
                        product_table.push($(this).val());
                    });
                });
                var services_table = []
                $.each(
                    $('#table_service_accompanied tr input[name="service_accompanied_hidden"]').parentsUntil('tbody'),
                    function () {
                        var $tds = $(this).find('td input')
                        $.each($tds, function () {
                            services_table.push($(this).val())
                        })
                    }
                )

                var check_image = $('.image-show').find('input[name="img-sv"]');
                var img = [];
                $.each(check_image, function () {
                    img.push($(this).val());
                });
                var service_avatar = $('#service_avatar').val();

                if ($('input[name="is_surcharge"]').is(':checked')) {
                    $('#is_surcharge').val(1);
                } else {
                    $('#is_surcharge').val(0);
                }

                var is_upload_image_ticket = 0;

                if ($('input[name="is_upload_image_ticket"]').is(':checked')) {
                    is_upload_image_ticket = 1;
                }

                var is_upload_image_sample = 0;

                if ($('input[name="is_upload_image_sample"]').is(':checked')) {
                    is_upload_image_sample = 1;
                }

                if (continues == true) {
                    $.ajax({
                        url: laroute.route('admin.service.submitAdd'),
                        data: {
                            service_category_id: $('#service_category_id').val(),
                            service_name: $('#service_name').val(),
                            service_code: $('#service_code').val(),
                            time: $('#time').val(),
                            price_standard: loc,
                            description: $('#description1').val(),
                            detail_description: $('.summernote').summernote('code'),
                            branch_id: $('#branch_hidden').val(),
                            product_id: $('#product_hidden').val(),
                            is_actived: active,
                            is_surcharge: $('#is_surcharge').val(),
                            branch_tb: branch_table,
                            product_tb: product_table,
                            services_tb: services_table,
                            image: img,
                            service_avatar: service_avatar,
                            type_refer_commission: $('.refer').find('.active input[name="type_refer_commission"]').val(),
                            refer_commission_value: $('#refer_commission_value').val().replace(new RegExp('\\,', 'g'), ''),
                            type_staff_commission: $('.staff').find('.active input[name="type_staff_commission"]').val(),
                            staff_commission_value: $('#staff_commission_value').val().replace(new RegExp('\\,', 'g'), ''),
                            type_deal_commission: $('.deal').find('.active input[name="type_deal_commission"]').val(),
                            deal_commission_value: $('#deal_commission_value').val().replace(new RegExp('\\,', 'g'), ''),
                            is_remind: $('#is_remind').val(),
                            remind_value: $('#remind_value').val(),
                            is_upload_image_ticket: is_upload_image_ticket,
                            is_upload_image_sample: is_upload_image_sample
                        },
                        method: 'POST',
                        dataType: "JSON",
                        success: function (response) {
                            if (response.error == false) {
                                $('#formAdd')[0].reset();
                                swal(response.message, "", "success");
                                window.location = laroute.route('admin.service');
                            }
                            if (response.error == true) {
                                swal(response.message, "", "error");
                                return false;
                            }
                            if (response.error_refer_commission == 1) {
                                swal(response.message, "", "error");
                            }
                            if (response.error_staff_commission == 1) {
                                swal(response.message, "", "error");
                            }
                            if (response.error_deal_commission == 1) {
                                swal(response.message, "", "error");
                            }
                            if (response.branch_null == 1) {
                                $('.error_branch_tb').text(json['Vui lòng chọn chi nhánh']);
                            } else {
                                $('.error_branch_tb').text('');
                            }

                        }
                    })
                }
            });
        });
        $('.btn_new').click(function () {
            $.getJSON(laroute.route('translate'), function (json) {
                var form = $('#formAdd');
                form.validate({
                    rules: {
                        service_category_id: {
                            required: true
                        },
                        service_name: {
                            required: true
                        },
                        service_code: {
                            required: true
                        },
                        price_standard: {
                            required: true
                        },
                        // time: {
                        //     required: true
                        // },
                        branch_id: {
                            required: true
                        },
                        description1: {
                            maxlength: 250
                        },
                        remind_value: {
                            integer: true,
                            min: 1,
                            required: true
                        }
                    },
                    messages: {
                        service_category_id: {
                            required: json['Hãy chọn nhóm dịch vụ']
                        },
                        service_name: {
                            required: json['Hãy nhập tên dịch vụ']
                        },
                        service_code: {
                            required: json['Hãy nhập mã dịch vụ']
                        },
                        price_standard: {
                            required: json['Hãy nhập giá dịch vụ']
                        },
                        time: {
                            required: json['Hãy nhập thời gian sử dụng']
                        },
                        branch_id: {
                            required: json["Hãy chọn chi nhánh"]
                        },
                        description1: {
                            maxlength: json['Mô tả ngắn tối đa 250 kí tự']
                        },
                        remind_value: {
                            integer: json['Kiểu dữ liệu không hợp lệ'],
                            min: json['Số ngày tối thiếu phải lớn hơn 0'],
                            required: json['Hãy nhập số ngày nhắc lại']
                        }
                    }
                });

                if (!form.valid()) {
                    return false;
                }

                $('.black-title').css('color', 'black');
                var continues = true;
                var standard = $('#price_standard').val();
                var loc = standard.replace(new RegExp('\\,', 'g'), '');
                var active = $('#h_is_actived').val();
                // $('.new_hid').val($('.new').val().replace(/\D+/g, ''));

                if ($('input[name="is_surcharge"]').is(':checked')) {
                    $('#is_surcharge').val(1);
                } else {
                    $('#is_surcharge').val(0);
                }
                var branch_table = [];
                $.each($('#table_branch').find(".branch_tb"), function () {
                    var $checkPrice = $(this).find("td input#new_tb");
                    var $checkWeek = $(this).find("td input#week_tb");
                    var $checkMonth = $(this).find("td input#month_tb");
                    var $checkYear = $(this).find("td input#year_tb");

                    $checkPrice.parents('td').find('.error_new_price').text('');
                    $checkWeek.parents('td').find('.error_price_week').text('');
                    $checkMonth.parents('td').find('.error_price_month').text('');
                    $checkYear.parents('td').find('.error_price_year').text('');
                    var $tds = $(this).find("td input");
                    if ($checkPrice.val() == "") {
                        $checkPrice.parents('td').find('.error_new_price').text('Hãy nhập giá chi nhánh');
                        continues = false;
                    }
                    if ($checkWeek.val() == "") {
                        $checkWeek.parents('td').find('.error_price_week').text('Hãy nhập giá tuần');
                        continues = false;
                    }
                    if ($checkMonth.val() == "") {
                        $checkMonth.parents('td').find('.error_price_month').text('Hãy nhập giá tháng');
                        continues = false;
                    }
                    if ($checkYear.val() == "") {
                        $checkYear.parents('td').find('.error_price_year').text('Hãy nhập giá năm');
                        continues = false;
                    }
                    $.each($tds, function () {
                        branch_table.push($(this).val());
                    });

                });
                var product_table = [];
                $.each($('#table_product tr input[name="product_hidden"]').parentsUntil("tbody"), function () {
                    var $tds = $(this).find("td input,td select");
                    var $check_quantity = $(this).find("td input.in_quantity ");
                    if ($check_quantity.val() == "") {
                        $check_quantity.parents('td').find('.error_quantity').text(json['Hãy nhập số lượng sản phẩm']);
                        continues = false;
                    }
                    $.each($tds, function () {
                        product_table.push($(this).val());
                    });
                });
                var services_table = []
                $.each(
                    $('#table_service_accompanied tr input[name="service_accompanied_hidden"]').parentsUntil('tbody'),
                    function () {
                        var $tds = $(this).find('td input')
                        // var $check_quantity = $(this).find('td input.in_quantity ')
                        // if ($check_quantity.val() == '') {
                        //   $check_quantity.parents('td').find('.error_quantity').text(json['Hãy nhập số lượng sản phẩm'])
                        //   continues = false
                        // }
                        $.each($tds, function () {
                            services_table.push($(this).val())
                        })
                    }
                )

                var check_image = $('.image-show').find('input[name="img-sv"]');
                var img = [];
                $.each(check_image, function () {
                    img.push($(this).val());
                });
                var service_avatar = $('#service_avatar').val();

                var is_upload_image_ticket = 0;

                if ($('input[name="is_upload_image_ticket"]').is(':checked')) {
                    is_upload_image_ticket = 1;
                }

                var is_upload_image_sample = 0;

                if ($('input[name="is_upload_image_sample"]').is(':checked')) {
                    is_upload_image_sample = 1;
                }

                if (continues == true) {
                    $.ajax({
                        url: laroute.route('admin.service.submitAdd'),
                        data: {
                            service_category_id: $('#service_category_id').val(),
                            service_name: $('#service_name').val(),
                            service_code: $('#service_code').val(),
                            time: $('#time').val(),
                            price_standard: loc,
                            description: $('#description1').val(),
                            detail_description: $('.summernote').summernote('code'),
                            branch_id: $('#branch_hidden').val(),
                            product_id: $('#product_hidden').val(),
                            is_actived: active,
                            is_surcharge: $('#is_surcharge').val(),
                            branch_tb: branch_table,
                            product_tb: product_table,
                            services_tb: services_table,
                            image: img,
                            service_avatar: service_avatar,
                            type_refer_commission: $('.refer').find('.active input[name="type_refer_commission"]').val(),
                            refer_commission_value: $('#refer_commission_value').val().replace(new RegExp('\\,', 'g'), ''),
                            refer_commission_percent: $('#refer_commission_percent').val().replace(new RegExp('\\,', 'g'), ''),
                            type_staff_commission: $('.staff').find('.active input[name="type_staff_commission"]').val(),
                            staff_commission_value: $('#staff_commission_value').val().replace(new RegExp('\\,', 'g'), ''),
                            staff_commission_percent: $('#staff_commission_percent').val().replace(new RegExp('\\,', 'g'), ''),
                            type_deal_commission: $('.deal').find('.active input[name="type_deal_commission"]').val(),
                            deal_commission_value: $('#deal_commission_value').val().replace(new RegExp('\\,', 'g'), ''),
                            deal_commission_percent: $('#deal_commission_percent').val().replace(new RegExp('\\,', 'g'), ''),
                            is_remind: $('#is_remind').val(),
                            remind_value: $('#remind_value').val(),
                            is_upload_image_ticket: is_upload_image_ticket,
                            is_upload_image_sample: is_upload_image_sample
                        },
                        method: 'POST',
                        dataType: "JSON",
                        success: function (response) {
                            if (response.error == false) {
                                $('#formAdd')[0].reset();
                                swal(response.message, "", "success");
                                window.location.reload();
                            }
                            if (response.error == true) {
                                swal(response.message, "", "error");
                                return false;
                            }
                            if (response.error_refer_commission == 1) {
                                swal(response.message, "", "error");
                            }
                            if (response.error_staff_commission == 1) {
                                swal(response.message, "", "error");
                            }
                            if (response.error_deal_commission == 1) {
                                swal(response.message, "", "error");
                            }
                            if (response.branch_null == 1) {
                                $('.error_branch_tb').text(json['Vui lòng chọn chi nhánh']);
                            } else {
                                $('.error_branch_tb').text('');
                            }
                        }
                    })
                }
            });
        });
    });
});
var service = {
    remove: function (obj, id) {
        $.getJSON(laroute.route('translate'), function (json) {
            // hightlight row
            $(obj).closest('tr').addClass('m-table__row--danger');

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
                    $.post(laroute.route('admin.service.remove', {id: id}), function () {
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
            url: laroute.route('admin.service.change-status'),
            method: "POST",
            data: {
                id: id, action: action
            },
            dataType: "JSON"
        }).done(function (data) {
            $('#autotable').PioTable('refresh');
        });
    },
    edit: function (id) {
        $.ajax({
            type: 'POST',
            url: laroute.route('admin.service_category.edit'),
            data: {
                id: id
            },
            dataType: 'JSON',
            success: function (response) {
                $('#editForm').modal("show");
                $('#hhidden').val(response["service_category_id"]);
                $('#h_name').val(response["name"]);
                $('#h_description').val(response["description"]);
                $('#h_is_actived').val(response["is_actived"]);
                $('.error-name').text('');
            }


        });
    },
    add_service_category: function (close) {
        $.getJSON(laroute.route('translate'), function (json) {
            $('#type_add').val(close);

            var form = $('#form');

            form.validate({
                rules: {
                    name: {
                        required: true,
                    }
                },
                messages: {
                    name: {
                        required: json['Hãy nhập nhóm dịch vụ']
                    }
                }
            });

            if (!form.valid()) {
                return false;
            }

            var name = $('#name');
            var des = $('#description');
            var is_actived = $('#is_actived');
            var input = $('#type_add');
            $.ajax({
                url: laroute.route('admin.service_category.submitAdd'),
                data: {
                    name: name.val(),
                    description: des.val(),
                    is_actived: is_actived.val(),
                    close: input.val()
                },
                method: 'POST',
                dataType: "JSON",
                success: function (response) {
                    if (response.status == 1) {
                        if (response.close != 0) {
                            $("#add").modal("hide");
                        }
                        $('#form')[0].reset();
                        $('.error-name').text('');
                        swal(json["Thêm nhóm dịch vụ thành công"], "", "success");
                        $('#service_category_id > option').remove();
                        $.each(response.optionCategory, function (index, element) {
                            $('#service_category_id').append('<option value="' + index + '">' + element + '</option>')
                        });
                        $('#autotable').PioTable('refresh');
                    } else {
                        $('.error-name').text(json['Nhóm dịch vụ đã tồn tại']);
                        $('.error-name').css('color', 'red');

                    }
                }
            });
        });
    },
    refresh: function () {
        $('input[name="search_keyword"]').val('');
        $('#created_at').val('');
        $('.m_selectpicker').val('');
        $('.m_selectpicker').selectpicker('refresh');
        $(".btn-search").trigger("click");
    },
    image_dropzone: function () {
        $('#addImage').modal('show');
        $('#up-ima').empty();
        $('.dropzone')[0].dropzone.files.forEach(function (file) {
            file.previewElement.remove();
        });
        $('.dropzone').removeClass('dz-started');
        // Dropzone.options.dropzoneone={
        //     init: function () {
        //         this.options.maxFiles = this.options.maxFiles + 5;
        //     }
        // }


    },
    remove_avatar: function () {
        $('.avatar').empty();
        var tpl = $('#avatar-tpl').html();
        $('.avatar').append(tpl);
        $('.image-format').text('');
        $('.image-size').text('');
        $('.image-capacity').text('');
    },
    remove_img: function (e) {
        $(e).closest('.image-show-child').remove();
    },
    description: function () {
        $('#modal-description').modal('show');
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
$('#autotable').PioTable({
    baseUrl: laroute.route('admin.service.list')
});

$.getJSON(laroute.route('translate'), function (json) {
    var arrRange = {};
    arrRange[json["Hôm nay"]] = [moment(), moment()];
    arrRange[json["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
    arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
    arrRange[json["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
    arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
    arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];

    $("#created_at").daterangepicker({
        autoUpdateInput: false,
        autoApply: true,
        // buttonClasses: "m-btn btn",
        // applyClass: "btn-primary",
        // cancelClass: "btn-danger",

        maxDate: moment().endOf("day"),
        startDate: moment().startOf("day"),
        endDate: moment().add(1, 'days'),
        locale: {
            cancelLabel: 'Clear',
            format: 'DD/MM/YYYY',
            // "applyLabel": "Đồng ý",
            // "cancelLabel": "Thoát",
            "customRangeLabel": json['Tùy chọn ngày'],
            daysOfWeek: [
                json["CN"],
                json["T2"],
                json["T3"],
                json["T4"],
                json["T5"],
                json["T6"],
                json["T7"]
            ],
            "monthNames": [
                json["Tháng 1 năm"],
                json["Tháng 2 năm"],
                json["Tháng 3 năm"],
                json["Tháng 4 năm"],
                json["Tháng 5 năm"],
                json["Tháng 6 năm"],
                json["Tháng 7 năm"],
                json["Tháng 8 năm"],
                json["Tháng 9 năm"],
                json["Tháng 10 năm"],
                json["Tháng 11 năm"],
                json["Tháng 12 năm"]
            ],
            "firstDay": 1
        },
        ranges: arrRange
    });
});

function onmouseoverAddNew() {
    $('.dropdow-add-new').show();
}

function onmouseoutAddNew() {
    $('.dropdow-add-new').hide();
}

// function readURL(input) {
//     if (input.files && input.files[0]) {
//         var reader = new FileReader();
//
//         reader.onload = function (e) {
//             $('#blah')
//                 .attr('src', e.target.result);
//         };
//
//         reader.readAsDataURL(input.files[0]);
//     }
// }
function uploadImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        var imageAvatar = $('#service_avatar');
        reader.onload = function (e) {
            $('#blah')
                .attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
        var file_data = $('#getFile').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('link', '_service.');

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
        $.getJSON(laroute.route('translate'), function (json) {
            if (Math.round(fsize / 1024) <= 10240) {
                $('.error_img').text('');
                $.ajax({
                    url: laroute.route("admin.upload-image"),
                    method: "POST",
                    data: form_data,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (res) {
                        if (res.error == 0) {
                            $('#service_avatar').val(res.file);
                            $('.delete-img').css('display', 'block');
                        }
                    }
                });
            } else {
                $('.error_img').text(json['Hình ảnh vượt quá dung lượng cho phép']);
            }
        });
    }
}

$('.btn-save-image').click(function () {
    var arrayImage = new Array();
    $('.file_Name').each(function () {
        arrayImage.push($(this).val());
    });
    // $('.image-show').empty();
    for (let i = 0; i < arrayImage.length; i++) {
        let $_tpl = $('#imgeShow').html();
        let tpl = $_tpl;
        tpl = tpl.replace(/{link}/g, arrayImage[i]);
        tpl = tpl.replace(/{link_hidden}/g, arrayImage[i]);
        $('.image-show').append(tpl);
        $('.delete-img-sv').css('display', 'block');
    }

    $('#addImage').modal('hide');

});
