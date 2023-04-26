function arr_diff(a1, a2) {

    var a = [], diff = [];

    for (var i = 0; i < a1.length; i++) {
        a[a1[i]] = true;
    }

    for (var i = 0; i < a2.length; i++) {
        if (a[a2[i]]) {
            delete a[a2[i]];
        } else {
            a[a2[i]] = true;
        }
    }

    for (var k in a) {
        diff.push(k);
    }

    return diff;
}

var sttBranch = 0;
$(document).ready(function () {
    $.getJSON(laroute.route('translate'), function (json) {
        $('#price_standard').change(function () {
            $('.old_price').empty();
            $('.old_price ').append($(this).val());
            $('.old_price ').append('<input type="hidden"  value=' + $(this).val().replace(/\D+/g, '') + '>');
            $('#new_price').val($(this).val());
        });
        $('#branch_id').select2({
            placeholder: json["Chọn chi nhánh"],
        });
        $('.unit_load').select2({
            placeholder: json['Chọn đơn vị tính']
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
                        service_id: $('#service_id_hidden').val(),
                        search: params.term,
                        page: params.page || 1
                    }
                    return query
                }
            },
            placeholder: json['Chọn dịch vụ đi kèm'],
            minimumInputLength: 1
        });
        $('#service_category_id').select2();

        new AutoNumeric.multiple('#price_standard, #new_price, #refer_commission_value, #refer_commission_percent, #staff_commission_value, #staff_commission_percent, #deal_commission_value, #deal_commission_percent, .new', {
            currencySymbol: '',
            decimalCharacter: '.',
            digitGroupSeparator: ',',
            decimalPlaces: decimal_number,
            minimumValue: 0
        });

        $('#check_all_branch').click(function () {
            $('.check:checkbox').prop('checked', this.checked);
        });

        var branch_load = $('#branch_id').val();

        $('#checkAll').click(function () {
            //Check vào all sẽ disable select 2
            $('#frm_branch_service').css('display', 'block');
            $('#table_branch > tbody').empty();
            $('#branch_service').css('display', 'block');
            if ($('input[name="checkAll"]').is(':checked')) {
                $('select[name="branch_id[]"]').prop("disabled", true);
                $('#frm_branch_service').css('display', 'block')
            } else {
                $('select[name="branch_id[]"]').prop("disabled", false);
                $('#frm_branch_service').css('display', 'none');
            }
            if ($('#checkAll').is(':checked')) {
                $('#branch_id > option').prop("selected", "selected");
                $('#branch_id').trigger("change");
                var arr = $("input[name='id_branch[]']").map(function () {
                    return $(this).val();
                }).get();
                var arr2 = $("input[name='branch_hidden']").map(function () {
                    return $(this).val();
                }).get();
                arrAll = arr.concat(arr2);

                var $_tpl = $('#branch-tpl').html();
                $('#branch_id >option').each(function () {
                    if (arrAll.includes($(this).val()) === false) {
                        var tpl = $_tpl;
                        // var stts = $('#table_branch tr').length;
                        tpl = tpl.replace(/{stt}/g, sttBranch);
                        tpl = tpl.replace(/{branch_name}/g, $(this).text());
                        tpl = tpl.replace(/{branch_id}/g, $(this).val());
                        tpl = tpl.replace(/{old_price}/g, $('#price_standard').val());
                        tpl = tpl.replace(/{old_price_hide}/g, $('#price_standard').val().replace(/\D+/g, ''));
                        tpl = tpl.replace(/{new_price}/g, $('#new_price').val());
                        $('#table_branch > tbody').append(tpl);

                        new AutoNumeric.multiple('.new_' + sttBranch + '', {
                            currencySymbol: '',
                            decimalCharacter: '.',
                            digitGroupSeparator: ',',
                            decimalPlaces: decimal_number,
                            minimumValue: 0
                        });
                    }

                });
            } else {
                $('#table_branch > tbody tr').empty();
                $('select[name="branch_id"] > option').removeAttr('selected');
                $('select[name="branch_id"]').val(null).trigger('change');

            }
        });

        $('#branch_id').on('select2:select', function (event) {
            sttBranch++;

            var tpl = $('#branch-tpl').html();

            tpl = tpl.replace(/{stt}/g, sttBranch);
            tpl = tpl.replace(/{branch_name}/g, event.params.data.text);
            tpl = tpl.replace(/{branch_id}/g, event.params.data.id);
            tpl = tpl.replace(/{old_price}/g, $('#price_standard').val());
            tpl = tpl.replace(/{old_price_hide}/g, $('#price_standard').val().replace(/\D+/g, ''));
            tpl = tpl.replace(/{new_price}/g, $('#new_price').val());
            $('#table_branch > tbody').append(tpl);

            new AutoNumeric.multiple('.new_' + sttBranch + '', {
                currencySymbol: '',
                decimalCharacter: '.',
                digitGroupSeparator: ',',
                decimalPlaces: decimal_number,
                minimumValue: 0
            });
        });

        $('#branch_id').on('select2:unselect', function (event) {
            $('.branch_tb').remove(":contains(" + event.params.data.text + ")");
        });

        $('#check_all_branch').click(function () {
            $('.check:checkbox').prop('checked', this.checked);

        });

        if ($('input[name="check_product"]').is(':checked')) {
            $('select[name="product_id[]"]').prop("disabled", false);
            $('#product_service').css('display', 'block')
        } else {
            $('select[name="product_id[]"]').prop("disabled", true);
            $('#product_service').css('display', 'none');
        }
        $('#check_product').click(function () {
            if ($('input[name="check_product"]').is(':checked')) {
                $('select[name="product_id[]"]').prop("disabled", false);
                $('#product_service').css('display', 'block')
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
                let code_add = $(this).find("input[name='product_id']");
                let value_id = codeHidden.val();
                let value_add = code_add.val();
                let code = event.params.data.id;
                if (value_id == code) {
                    check = false;
                    let quantitySv = codeHidden.parents('tr').find('input[name="quantity"]').val();
                    let numbers = parseInt(quantitySv) + 1;
                    codeHidden.parents('tr').find('input[name="quantity"]').val(numbers);

                    // codeHidden.parents('tr').find('.quantity').empty();
                    //codeHidden.parents('tr').find('.discount-tr-'+type_check+'-'+code+'').append('<a class="abc m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary" href="javascript:void(0)" onclick="order.modal_discount('+amount+','+code+','+id_type+')"><i class="la la-plus"></i></a>');
                }
                if (value_add == code) {
                    check = false;
                    let quantityAdd = code_add.parents('tr').find('input[name="quantity"]').val();
                    console.log(quantityAdd);
                    let numbers_add = parseInt(quantityAdd) + 1;
                    code_add.parents('tr').find('input[name="quantity"]').val(numbers_add);
                }
            });
            if (check == true) {
                var random = "";
                var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                for (var i = 0; i < 10; i++) {
                    random += possible.charAt(Math.floor(Math.random() * possible.length));
                }
                var stts = $('#table_product tr').length;
                var pro = event.params.data.text;
                // var stt = "<td class='stt'>" + stts + "</td>";
                // // var product_code = "<td class='code'>" + random + "<input type='hidden' id='product_code_hidden' name='product_code_hidden' value=" + random + ">" + "</td>";
                // var product = "<td class='product'>" + pro + "<input type='hidden' id='product_hidden' name='product_id' value=" + event.params.data.id + ">" + "</td>";
                // var quantity = "<td >" + "<div>" + "<input type='number' id='quantity' name='quantity' class='form-control m-input m-input-group--solid quantity' value=" + 1 + " >"
                //     + "</td>";
                // var unit_id = "<td class='unit_id'>" + "<div>"
                //     + "<select id='unit_id_"+event.params.data.id+" name='unit_id' class='unit form-control m-input m-input-group--solid' " +
                //     +"</select>"
                //     + "</td>";
                // var del = "<td class='del'>" + "<a class='remove_product m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill'><i class='la la-trash'></i></a>" + "</td>";
                // var row = "<tr class='pro_tb_add'>" + stt  + product + quantity + unit_id + del + "</tr>";
                var tpl = $('#product-tpl').html();
                tpl = tpl.replace(/{stt}/g, stts);
                tpl = tpl.replace(/{product_name}/g, event.params.data.text);
                tpl = tpl.replace(/{product_id}/g, event.params.data.id);
                tpl = tpl.replace(/{id_unit}/g, event.params.data.id);
                $("#table_product > tbody").append(tpl);
                $(".quantity").TouchSpin({
                    initval: 1,
                    min: 1,
                    buttondown_class: "btn btn-metal btn-sm",
                    buttonup_class: "btn btn-metal btn-sm"
                });
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
                $('.unit').select2({
                    placeholder: 'Chọn đơn vị'
                });
                $('.remove_product').click(function () {
                    $(this).closest('.pro_tb_add').remove();
                });
            }
        });
        $(".quantity").TouchSpin({
            initval: 1,
            min: 1,
            buttondown_class: "btn btn-metal btn-sm",
            buttonup_class: "btn btn-metal btn-sm"
        });
        $('.remove_product').click(function () {
            $(this).closest('.pro_tb').remove();
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
                let codeHidden = $(this).find("input[name='service_accompanied_hidden']")
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
        $('.remove_service_accompanied').click(function () {

            $(this).closest('.accompanied_tb').remove()
        })
        $('#service_accompanied_id').on('select2:unselect', function (event) {
            $('.accompanied_tb').remove(':contains(' + event.params.data.text + ')')
        })
        /**
         * end
         * Dịch vụ đi kèm
         */

        //load du lieu branch lên khi chuyen form edit
        var load = $("input[name='service_branch_price_id']").map(function () {
            return $(this).val();
        }).get();
        //load du lieu material lên khi chuyển form edit
        var load_material = $("input[name='mate_service_id']").map(function () {
            return $(this).val();
        }).get();

        $('.btn_save').click(function () {
            var form = $('#formEdit');
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

            var branch_table = [];
            $.each($('#table_branch').find(".branch_tb"), function () {
                var $tds = $(this).find("td input");
                $.each($tds, function () {
                    branch_table.push($(this).val());
                });
            });
            // var branch_table_add = [];
            // $.each($('#table_branch').find(".branch_tb_add"), function () {
            //     var $tds = $(this).find("td input");
            //     $.each($tds, function () {
            //         branch_table_add.push($(this).val());
            //     });
            // });
            var branch_edit_active = [];
            $.each($('#table_branch tr input[name="check_branch[]"]:checked').parentsUntil("tbody"), function () {
                var $tds = $(this).find("input[name='service_branch_price_id']");
                $.each($tds, function () {
                    branch_edit_active.push($(this).val());
                });
            });

            var edit_active = arr_diff(load, branch_edit_active);

            var product_table = [];
            // $.each($('#table_product tr input[name="mate_service_id"]').parentsUntil("tbody"), function () {
            //     var $tds = $(this).find("td input,td select");
            //
            //     $.each($tds, function () {
            //         product_table.push($(this).val());
            //     });
            // });
            // var product_table_add = [];
            // $.each($('#table_product tr input[name="product_id"]').parentsUntil("tbody"), function () {
            //     var $tds = $(this).find("td input,td select");
            //     $.each($tds, function () {
            //         product_table_add.push($(this).val());
            //     });
            // });
            $.each($('#table_product tr input[name="product_hidden"]').parentsUntil('tbody'), function () {
                var $tds = $(this).find('td input,td select');

                var $check_quantity = $(this).find('td input.in_quantity ')
                if ($check_quantity.val() == '') {
                    $check_quantity.parents('td').find('.error_quantity').text(json['Hãy nhập số lượng sản phẩm'])
                    // continues = false
                }
                $.each($tds, function () {
                    product_table.push($(this).val());
                })
            })

            if ($('input[name="is_actived"]').is(':checked')) {
                $('#h_is_actived').val(1);
            } else {
                $('#h_is_actived').val(0);
            }
            if ($('input[name="is_surcharge"]').is(':checked')) {
                $('#is_surcharge').val(1);
            } else {
                $('#is_surcharge').val(0);
            }

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
            //Lay value xoa branch
            var arr = $("input[name='service_branch_price_id']").map(function () {
                return $(this).val();
            }).get();
            var cut = arr_diff(load, arr);
            //Lay value xoa material
            var arr_mate = $("input[name='mate_service_id']").map(function () {
                return $(this).val();
            }).get();
            var cut_mate = arr_diff(load_material, arr_mate);
            //Get value array image
            //Lay value khi add image
            var check_image = $('.image-show').find('input[name="img-sv"]');
            var img = [];
            $.each(check_image, function () {
                img.push($(this).val());
            });
            var service_avatar = $('#service_avatar').val();
            var clickImg = $('.service_image');
            var arrClick = [];
            $.each(clickImg, function () {
                arrClick.push($(this).val());
            });

            var is_upload_image_ticket = 0;

            if ($('input[name="is_upload_image_ticket"]').is(':checked')) {
                is_upload_image_ticket = 1;
            }

            var is_upload_image_sample = 0;

            if ($('input[name="is_upload_image_sample"]').is(':checked')) {
                is_upload_image_sample = 1;
            }

            $.ajax({
                url: laroute.route('admin.service.submitEdit'),
                data: {
                    is_actived: $('#h_is_actived').val(),
                    is_surcharge: $('#is_surcharge').val(),
                    service_name: $('#service_name').val(),
                    service_code: $('#service_code').val(),
                    price_standard: $('#price_standard').val().replace(new RegExp('\\,', 'g'), ''),
                    time: $('#time').val(),
                    description: $('#description1').val(),
                    service_category_id: $('#service_category_id').val(),
                    // branch_tb_detail: branch_table,
                    // edit_active_branch: edit_active,
                    service_id: $('#service_id_hidden').val(),
                    branch_tb: branch_table,
                    product_tb: product_table,
                    // product_tb_add: product_table_add,
                    services_tb : services_table,
                    // branch_tb_add: branch_table_add,
                    remove_branch: cut,
                    remove_product: cut_mate,
                    remove_image: arrClick,
                    add_image: img,
                    service_avatar: service_avatar,
                    service_avatar_edit: $('#service_avatar_edit').val(),
                    detail_description: $('.summernote').summernote('code'),
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
                    $.getJSON(laroute.route('translate'), function (json) {
                        if (response.error == false) {
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
                    });
                }
            });

        });
        $('.delete-avatar').click(function () {
            $(this).closest('.avatar').empty();
            $('#service_avatar_edit').val('');
        });
        $('.delete-image').click(function () {
            $(this).parents('.image-edit').remove();
            var name = $(this).val();
            $(".service_image").each(function () {
                var $this = $(this);
                if ($this.val() === name) {
                    $this.remove();
                }
            });

        });
        $('#btnTest').click(function () {
            var branch_edit_active = [];
            $.each($('#table_branch tr input[name="check_branch[]"]:checked').parentsUntil("tbody"), function () {
                var $tds = $(this).find("input[name='service_branch_price_id']");
                $.each($tds, function () {
                    branch_edit_active.push($(this).val());
                });
            });
            var edit_active = arr_diff(load, branch_edit_active);


        });
    });
});

var service = {
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
    remove_avatar: function () {
        $('.avatar').empty();
        var tpl = $('#avatar-tpl').html();
        $('.avatar').append(tpl);
        $('#service_avatar_edit').val('');
        $('.image-format').text('');
        $('.image-size').text('');
        $('.image-capacity').text('');
    },
    remove_img: function (e) {
        $(e).closest('.image-show-child').remove();
    },
    image_dropzone: function () {
        $('#addImage').modal('show');
        $('#up-ima').empty();
        $('.dropzone')[0].dropzone.files.forEach(function (file) {
            file.previewElement.remove();
        });
        $('.dropzone').removeClass('dz-started');
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


function onmouseoverAddNew() {
    $('.dropdow-add-new').show();
}

function onmouseoutAddNew() {
    $('.dropdow-add-new').hide();
}

function uploadImage(input) {
    $.getJSON(laroute.route('translate'), function (json) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            var imageAvatar = $('#service_avatar');
            reader.onload = function (e) {
                $('#blah').attr('src', e.target.result);
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

        }
    });
}

$('#btn-save-image').click(function () {
    var arrayImage = new Array();
    $('.file_Name').each(function () {
        arrayImage.push($(this).val());
    });
    // $('.append_image').empty();
    for (let i = 0; i < arrayImage.length; i++) {
        let $_tpl = $('#imgeShow').html();
        let tpl = $_tpl;
        tpl = tpl.replace(/{link}/g, arrayImage[i]);
        tpl = tpl.replace(/{link_hidden}/g, arrayImage[i]);
        $('.image-show').append(tpl);
    }
    $('#addImage').modal('hide');
});
