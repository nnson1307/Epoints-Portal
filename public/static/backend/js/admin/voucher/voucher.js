var Voucher = {
    init: function () {
        $('#autotable').PioTable({
            baseUrl: laroute.route('admin.voucher.list')
        });

        new AutoNumeric.multiple('[name="number_of_using"]', {
            currencySymbol: '',
            decimalCharacter: '.',
            digitGroupSeparator: ',',
            decimalPlaces: 0,
            minimumValue: 0
        });

        $(".select2").select2();
        $(".date-picker").datepicker({
            format: "dd/mm/yyyy"
        });
        $(".date-picker-expire").datepicker({
            format: "dd/mm/yyyy",
            startDate: '+1d',
            language: 'en',
            autoclose: true
        });

        $("#product-field select[name='product_id[]']").select2({
            // minimumInputLength: 1,
            ajax: {
                url: laroute.route("admin.voucher.filterObject"),
                dataType: 'json',
                type: "POST",
                delay: 1000,
                data: function (params) {
                    var query = {
                        keyword: params.term,
                        product_type: $(".product_type").val(),
                        type: "product"
                    };
                    return query;
                },
                processResults: function (data) {
                    console.log(data)
                    return {
                        results: $.map(data.list, function (obj) {
                            return {
                                id: (obj.proId),
                                text: (obj.proName)
                            };
                        })
                    };
                },
                // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
            }
        });

        $("#service-field select[name='service_id[]']").select2({
            minimumInputLength: 1,
            ajax: {
                url: laroute.route("admin.voucher.filterObject"),
                dataType: 'json',
                type: "POST",
                delay: 700,
                data: function (params) {
                    var query = {
                        keyword: params.term,
                        service_type: $(".service_type").val(),
                        type: "service"
                    };
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: $.map(data.list, function (obj) {
                            return {
                                id: (obj.service_id),
                                text: (obj.service_name)
                            };
                        })
                    };
                },
                // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
            }
        });

        $("#service-card-field select[name='service_card_id[]']").select2({
            minimumInputLength: 1,
            ajax: {
                url: laroute.route("admin.voucher.filterObject"),
                dataType: 'json',
                type: "POST",
                delay: 1000,
                data: function (params) {
                    var query = {
                        keyword: params.term,
                        service_card_type: $(".service_card_type").val(),
                        type: "service_card"
                    };
                    return query;
                },
                processResults: function (data) {
                    console.log($(".service_card_type").val());

                    return {
                        results: $.map(data.list, function (obj) {
                            return {
                                id: (obj.service_card_id),
                                text: (obj.card_name)
                            };
                        })
                    };
                },
                // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
            }
        });


        $('#member_level_apply').select2().on('select2:select', function (event) {
            if (event.params.data.id == 'all') {
                $('#member_level_apply').val('all').trigger('change');
            } else {
                var arrayChoose = [];

                $.map($('#member_level_apply').val(), function (val) {
                    if (val != 'all') {
                        arrayChoose.push(val);
                    }
                });
                $('#member_level_apply').val(arrayChoose).trigger('change');
            }
        }).on('select2:unselect', function (event) {
            if ($('#member_level_apply').val() == '') {
                $('#member_level_apply').val('all').trigger('change');
            }
        });

        $('#customer_group_apply').select2().on('select2:select', function (event) {
            if (event.params.data.id == 'all') {
                $('#customer_group_apply').val('all').trigger('change');
            } else {
                var arrayChoose = [];

                $.map($('#customer_group_apply').val(), function (val) {
                    if (val != 'all') {
                        arrayChoose.push(val);
                    }
                });
                $('#customer_group_apply').val(arrayChoose).trigger('change');
            }
        }).on('select2:unselect', function (event) {
            if ($('#customer_group_apply').val() == '') {
                $('#customer_group_apply').val('all').trigger('change');
            }
        });

        $(document).on('keyup', ".format-money", function () {
            var n = parseInt($(this).val().replace(/\D/g, ''), 10);
            if (typeof n == 'number' && Number.isInteger(n)) {
                // $(this).val(n.toLocaleString());
            } else {
                // $(this).val("");
            }
            //do something else as per updated question
            // myFunc(); //call another function too
        });

        $(document).on('keyup', ".format-percent", function () {

            var n = $(this).val().replace(new RegExp('\\,', 'g'), '');
            // if (typeof n == 'number' && Number.isInteger(n)) {
            if (n > 100) {
                $(this).val("");
            }
            // } else {
            //     $(this).val("");
            // }

        });

        $(document).on("click", ".btnObjectType", function () {
            $(".btnObjectType").removeClass("active-btn");
            $(this).addClass("active-btn");
            var type = $(this).attr("data-type");
            $("input[name=object_type]").val(type);
            $(".object-type-input").addClass("hide-input");
            $(".object-type-input select").attr("disabled", true);

            if (type == "product") {
                $("#product-field").removeClass("hide-input");
                $("#product-field select").attr("disabled", false);
            } else if (type == "service") {

                $("#service-field").removeClass("hide-input");
                $("#service-field select").attr("disabled", false);
            } else if (type == "service_card") {
                $("#service-card-field").removeClass("hide-input");
                $("#service-card-field select").attr("disabled", false);
            }
        });

        $(document).ready(function () {
            var type = $(".radio-sale input[name=type]:checked").val();
            // if (type == "sale_cash") {
            //     $("input[name=max_price]").prop("readonly", true);
            // } else if (type == "sale_percent") {
            //     $("input[name=max_price]").prop("readonly", false);
            // }

            if (type == "sale_percent") {
                new AutoNumeric.multiple('#max_price', {
                    currencySymbol: '',
                    decimalCharacter: '.',
                    digitGroupSeparator: ',',
                    decimalPlaces: decimal_number,
                    minimumValue: 0
                });
            } else {
                $("input[name=max_price]").prop("readonly", true);
            }

            $(".format-select").trigger("change");
        });

        $(document).on("change", ".radio-sale input[name=type]", function () {
            //Div giá trị giảm
            $('.div_voucher_money').empty();

            var tpl = $('#tpl-voucher-money').html();
            $('.div_voucher_money').append(tpl);

            //Div tiền giảm tối đa
            $('.div_max_price').empty();

            var tpl = $('#tpl-max-price').html();
            $('.div_max_price').append(tpl);

            var val = $(this).val();

            $("#voucher-money").val("");
            // console.log(val);
            if (val == "sale_percent") {
                $("#voucher-money").attr("name", "percent");
                $("#voucher-money").removeClass("format-money");
                $("#voucher-money").addClass("format-percent");

                $("input[name=max_price]").val("");
                $("input[name=max_price]").prop("readonly", false);

                new AutoNumeric.multiple('#max_price', {
                    currencySymbol: '',
                    decimalCharacter: '.',
                    digitGroupSeparator: ',',
                    decimalPlaces: decimal_number,
                    minimumValue: 0
                });
            } else if (val == "sale_cash") {
                $("#voucher-money").attr("name", "cash");
                $("#voucher-money").addClass("format-money");
                $("#voucher-money").removeClass("format-percent");

                $("input[name=max_price]").val("");
                $("input[name=max_price]").prop("readonly", true);
            }

            new AutoNumeric.multiple('#voucher-money', {
                currencySymbol: '',
                decimalCharacter: '.',
                digitGroupSeparator: ',',
                decimalPlaces: decimal_number,
                minimumValue: 0
            });
        });

        $(document).on("keyup", "#voucher-money", function () {

            var value = $(this).val();

            var type = $(".radio-sale input[name=type]:checked").val();
            if (type == "sale_cash") {
                $("input[name=max_price]").val(value);
            }
        });

        $(document).on("change", ".format-select", function () {
            var value = $(this).val();
            // console.log(value.includes(""));
            if (value.length > 1 && value.includes("")) {
                var index = value.indexOf("");
                // console.log(index);
                value.splice(index, 1);
                $(this).val(value).trigger("change");
            }
            if (value.length == 0) {
                $(this).val([""]).trigger("change");
            }
        });

        $(document).on("change", "#select_obj_type", function () {
            var value = $(this).val();
            $.ajax({
                url: laroute.route("admin.voucher.getObject"),
                method: "POST",
                data: {
                    type: value
                },
                success: function (resp) {
                    $(".table-content").html(resp);
                }
            });
        });

        $(document).on("change", ".ckb-all", function () {
            if ($(this).prop("checked")) {
                $(".ckb-item").prop("checked", "on").trigger("change");
            } else {
                $(".ckb-item").prop("checked", "").trigger("change");
            }

        });

        $(document).on("change", ".ckb-item", function () {
            if (!$(this).prop("checked")) {
                $(".ckb-all").prop("checked", "");
            }
        });

        $("#form").on('keyup keypress', function (e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {
                e.preventDefault();
                return false;
            }
        });
        $(document).on('keyup', "input[name='quota']", function () {
            var n = parseInt($(this).val().replace(/\D/g, ''), 10);
            if (typeof n == 'number' && Number.isInteger(n))
                if (n > 10000) {
                    $(this).val(1);
                } else {
                    $(this).val(n);
                }

            else {
                $(this).val("");
            }
            //do something else as per updated question
            // myFunc(); //call another function too
        });
        // $('button[type="submit"]').prop('disabled', true);
        if ($('#idVoucher').val() > 0) {
            $('button[type="submit"]').prop('disabled', false);
        }
        $('input[name="code"]').keyup(function () {
            var code = $('input[name="code"]').val();
            var id = $('#idVoucher').val();
            var idVoucher = 0;
            if (id > 0) {
                idVoucher = id;
            }
            $.ajax({
                url: laroute.route('admin.voucher.check-slug'),
                method: "POST",
                data: {
                    code: code,
                    id: idVoucher
                }, success: function (data) {
                    if (data.error == 1) {
                        $('input[name="code"]').parents('.form-group').find('span.form-control-feedback').text('')
                        $('.error-code').text('Mã giảm giá đã tồn tại');
                        $('button[type="submit"]').prop('disabled', true);
                        $('.class-submit-edit').attr('onClick', "");
                    } else {
                        $('.error-code').text('');
                        $('button[type="submit"]').prop('disabled', false);
                        $('.class-submit-edit').attr('onClick', "document.getElementById('form').submit()");
                    }
                }
            })
        });

        // $('#voucher-money').mask('000,000,000.00', {reverse: true});
        // $('.format-money').mask('000,000,000.00', {reverse: true});

        new AutoNumeric.multiple('#voucher-money, .format-money', {
            currencySymbol: '',
            decimalCharacter: '.',
            digitGroupSeparator: ',',
            decimalPlaces: decimal_number,
            minimumValue: 0
        });

        $.getJSON(laroute.route('translate'), function (json) {
            $('.summernote').summernote({
                height: 150,
                placeholder: json['Nhập thông tin chi tiết'],
                toolbar: [
                    ['style', ['bold', 'italic', 'underline']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                ]
            });
        });
    },

    FilterProduct: function (obj = null) {
        var w = $(".search_keyword").val();
        var t = $(".product_type").val();
        $.ajax({
            url: laroute.route("admin.voucher.filterObject"),
            method: "POST",
            data: {
                type: "product",
                keyword: w,
                product_type: t,
                object: obj
            },
            success: function (resp) {
                $(".table-content").html(resp);
            }
        })
    },
    FilterService: function (obj = null) {
        var w = $(".search_keyword").val();
        var t = $(".service_type").val();
        $.ajax({
            url: laroute.route("admin.voucher.filterObject"),
            method: "POST",
            data: {
                type: "service",
                keyword: w,
                service_type: t,
                object: obj
            },
            success: function (resp) {
                $(".table-content").html(resp);
            }
        });
    },
    FilterServiceCard: function (obj = null) {
        var w = $(".search_keyword").val();
        var t = $(".service_card_type").val();
        $.ajax({
            url: laroute.route("admin.voucher.filterObject"),
            method: "POST",
            data: {
                type: "service_card",
                keyword: w,
                service_card_type: t,
                object: obj
            },
            success: function (resp) {
                $(".table-content").html(resp);
            }
        })
    },
    remove: function (obj, sid) {
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
                    $.post(laroute.route('admin.voucher.delete', {id: sid}), function (resp) {
                        if (resp.error == 0) {
                            swal(
                                resp.message,
                                '',
                                'success'
                            );
                            // window.location.reload();
                            $('#autotable').PioTable('refresh');
                        } else {
                            $.notify({
                                // options
                                message: resp.message
                            }, {
                                // settings
                                type: 'danger'
                            });
                        }

                    });
                }
            });
        });
    },
    detail: function (sid) {
        $.ajax({
            url: laroute.route("admin.voucher.detail", {id: sid}),
            method: "POST",
            success: function (resp) {
                $("#detail").html(resp);
                $("#detail").modal();
            }
        })
    },
    changeStatus: function (obj, sid) {
        // $(obj).closest('tr').addClass('m-table__row--danger');
        // swal({
        //     title: 'Thông báo',
        //     text: "Bạn có muốn thay đổi trạng thái không?",
        //     type: 'warning',
        //     showCancelButton: true,
        //     confirmButtonText: 'Có',
        //     cancelButtonText: 'Không',
        //     onClose: function () {
        //         // remove hightlight row
        //         $(obj).closest('tr').removeClass('m-table__row--danger');
        //     }
        // }).then(function (result) {
        //     if (result.value) {
        //         $.post(laroute.route('admin.voucher.changeStatus', {id: sid}), function (resp) {
        //             if(resp.error == 0){
        //                 swal(
        //                     resp.message,
        //                     '',
        //                     'success'
        //                 );
        //                 if(resp.is_active==1){
        //                     $(obj).removeClass("btn-outline-danger");
        //                     $(obj).addClass("btn-danger");
        //                     $(obj).html("Hoạt động");
        //                 }else if(resp.is_active==0){
        //                     $(obj).removeClass("btn-danger");
        //                     $(obj).addClass("btn-outline-danger");
        //                     $(obj).html("Tạm ngưng");
        //                 }
        //                 // window.location.reload();
        //                 $('#autotable').PioTable('refresh');
        //             }else{
        //                 $.notify({
        //                     // options
        //                     message: resp.message
        //                 },{
        //                     // settings
        //                     type: 'danger'
        //                 });
        //             }
        //
        //         });
        //     }
        // });
        $.post(laroute.route('admin.voucher.changeStatus', {id: sid}), function (resp) {
            $('#autotable').PioTable('refresh');
        });
    },
    refresh: function () {
        $('input[name="search_keyword"]').val('');
        $('.m_selectpicker').val('');
        $('.m_selectpicker').selectpicker('refresh');
        $('.btn-search').trigger('click');
    },
    search: function () {
        $('.btn-search').trigger('click');
    },
    submitAddNew: function () {
        $('#type_add').val(1);
    },

    remove_avatar: function () {
        $('.avatar').empty();
        var tpl = $('#avatar-tpl').html();
        $('.avatar').append(tpl);
        $('.image-format').text('');
        $('.image-size').text('');
        $('.image-capacity').text('');
        $('#img_old').val('');
    },
};

Voucher.init();
$('.m_selectpicker').select2();

if ($('#errorssss').val() == 0) {
    $.getJSON(laroute.route('translate'), function (json) {
        swal(
            json['Thêm khuyến mãi thành công'],
            '',
            'success'
        );
    });
}

//

function uploadImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        var imageAvatar = $('#voucher_img');
        reader.onload = function (e) {
            $('#blah').attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
        var file_data = $('#getFile').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('link', '_voucher.');

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
                        $('#voucher_img').val(res.file);
                        $('.delete-img').css('display', 'block');

                    }

                }
            });
        } else {
            $.getJSON(laroute.route('translate'), function (json) {
                $('.error_img').text(json['Hình ảnh vượt quá dung lượng cho phép']);
            });
        }

    }
}
