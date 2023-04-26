$(document).ready(function () {
    $('#check_all_branch').click(function () {
        $('.check:checkbox').prop('checked', this.checked);

    });

    $('#price_standard').keyup(function () {
        var newPrice = $(this).val();

        if (newPrice == '') {
            $.ajax({
                url: laroute.route('admin.service-branch-price.list-branch-price'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    id: $('#service_id').val()
                },
                success: function (data) {
                    jQuery.each(data, function (key, val) {
                        $('#' + val.service_branch_price_id).val(val.new_price);
                    });
                }
            });
        } else {
            $('#table_branch .branch_tb').each(function () {
                $(this).find('input:text').each(function () {
                    $(this).val(newPrice);
                });
            });
        }
    });

    $('#btn').click(function () {
        var idService = $('#service_id').val();
        var serviceName = $('#service_name').val();
        var priceStandard = $('#price_standard').val();
        var listBranch = [];
        var listPriceWeek = [];
        var listPriceMonth = [];
        var listPriceYear = [];

        $('#table_branch .branch_tb').each(function () {
            let values = []; let week = []; let month = []; let year = [];

            $(this).find('input:hidden').each(function () {
                values.push($(this).val());
                week.push($(this).val());
                month.push($(this).val());
                year.push($(this).val());
            });
            var index = 0;
            $(this).find('input:text').each(function () {
                switch(index%4){
                    case 0:
                        values.push($(this).val().replace(new RegExp('\\,', 'g'), ''));
                        break;
                    case 1:
                        week.push($(this).val().replace(new RegExp('\\,', 'g'), ''));
                        break;
                    case 2:
                        month.push($(this).val().replace(new RegExp('\\,', 'g'), ''));
                        break;
                    case 3:
                        year.push($(this).val().replace(new RegExp('\\,', 'g'), ''));
                        break;
                }
                index = index + 1
            });
            $(this).find('input:checkbox').each(function () {

                values.push($(this).prop("checked"));
                week.push($(this).prop("checked"));
                month.push($(this).prop("checked"));
                year.push($(this).prop("checked"));
            });

            listBranch.push(values);
            listPriceWeek.push(week);
            listPriceMonth.push(month);
            listPriceYear.push(year);
        });
        // console.log(listBranch);
        $.ajax({
            url: laroute.route('admin.service-branch-price.submit-edit'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                idService: idService,
                serviceName: serviceName,
                priceStandard: priceStandard,
                listBranch: listBranch,
                listPriceWeek: listPriceWeek,
                listPriceMonth: listPriceMonth,
                listPriceYear: listPriceYear
            },
            success: function (data) {
                // window.location = laroute.route('admin.service-branch-price');
                $.getJSON(laroute.route('translate'), function (json) {
                    swal(
                        json['Cập nhật giá dịch vụ thành công'],
                        '',
                        'success'
                    );
                });
                setTimeout(function () {
                    location.reload();
                }, 1500);
                // location.reload();
            }
        });

    });

    $('#branch_id').on('select2:select',function (e) {
        e.preventDefault();
        var branchId = $(this).val();
        if (branchId != 0) {
            loadPrice(branchId);
            $('#price').removeAttr('disabled');
        }
        else{
            $('#price').val('0');
            $('#price').select2();
            loadPrice(branchId);
        }
    });

    $('#price').change(function (e) {
        e.preventDefault()

        var branchId = $(this).val();
        if (branchId != 0) {
            $('#autotableconfig').PioTable({
                baseUrl: laroute.route('admin.service-branch-price.list-config')
            });
            new AutoNumeric.multiple('.new', {
                currencySymbol : '',
                decimalCharacter : '.',
                digitGroupSeparator : ',',
                decimalPlaces: decimal_number,
                minimumValue: 0
            });
        } else {
            $('#price').val('0');
            $('#price').select2();
            loadPrice(branchId);
        }
    });

    $('#btnSubmitChange').click(function () {
        var branchId = $('#branch_id').val();
        if (branchId != '' && branchId != 0) {
            $('.error-choose-branch').text('');
            var listService = [];
            var listPriceWeek = [];
            var listPriceMonth = [];
            var listPriceYear = [];
            $('#table_branch .branch_tb').each(function () {

                let values = []; let week = []; let month = []; let year = [];
                $(this).find('input:hidden').each(function () {
                    values.push($(this).val());
                    week.push($(this).val());
                    month.push($(this).val());
                    year.push($(this).val());
                });
                let index = 0;
                $(this).find('input:text').each(function(){
                    switch(index%4){
                        case 0:
                            values.push($(this).val().replace(new RegExp('\\,', 'g'), ''));
                            break;
                        case 1:
                            week.push($(this).val().replace(new RegExp('\\,', 'g'), ''));
                            break;
                        case 2:
                            month.push($(this).val().replace(new RegExp('\\,', 'g'), ''));
                            break;
                        case 3:
                            year.push($(this).val().replace(new RegExp('\\,', 'g'), ''));
                            break;
                    }
                    index = index + 1
                });
                $(this).find('input:checkbox').each(function () {
                    values.push($(this).prop("checked"));
                    week.push($(this).prop("checked"));
                    month.push($(this).prop("checked"));
                    year.push($(this).prop("checked"));
                });

                listService.push(values);
                listPriceWeek.push(week);
                listPriceMonth.push(month);
                listPriceYear.push(year);
            });
            $.ajax({
                url: laroute.route('admin.service-branch-price.submit-config'),
                type: 'POST',
                dataType: 'json',
                data: {
                    branchId: branchId,
                    listService: listService,
                    listPriceWeek: listPriceWeek,
                    listPriceMonth: listPriceMonth,
                    listPriceYear: listPriceYear
                },
                success: function (data) {
                    $.getJSON(laroute.route('translate'), function (json) {
                        // window.location = laroute.route('admin.service-branch-price');
                        swal(
                            json['Cấu hình giá dịch vụ thành công'],
                            '',
                            'success'
                        );
                    });
                    setTimeout(function () {
                        location.reload();
                    }, 1500);
                }
            });
        } else {
            $.getJSON(laroute.route('translate'), function (json) {
                $('.error-choose-branch').text(json['Vui lòng chọn chi nhánh']);
            });
        }

    });

    new AutoNumeric.multiple('.new', {
        currencySymbol : '',
        decimalCharacter : '.',
        digitGroupSeparator : ',',
        decimalPlaces: decimal_number,
        minimumValue: 0
    });
});
$('#autotable').PioTable({
    baseUrl: laroute.route('admin.service-branch-price.list')
});
$('#autotableconfig').PioTable({
    baseUrl: laroute.route('admin.service-branch-price.list-config')
});
// Load thông tin giá
function loadPrice(branchId) {
    $.getJSON(laroute.route('translate'), function (json) {
        // $('#table_branch .branch_tb').each(function () {
        //     $(this).find('input:text').each(function () {
        //         $(this).val('0');
        //     });
        // });
        $('#autotableconfig').PioTable({
            baseUrl: laroute.route('admin.service-branch-price.list-config')
        });

        $.ajax({
            url: laroute.route('admin.service-branch-price.list-config-branch-price'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                branchId: branchId
            },
            success: function (data) {

                $('#price option').remove();
                $('#price').append($('<option>', {
                    value: 0,
                    text: json['Chọn chi nhánh']
                }));
                jQuery.each(data, function (index, val) {
                    $('#price').append($('<option>', {
                        value: index,
                        text: val
                    }));
                });
            }
        });
        new AutoNumeric.multiple('.new', {
            currencySymbol : '',
            decimalCharacter : '.',
            digitGroupSeparator : ',',
            decimalPlaces: decimal_number,
            minimumValue: 0
        });
    });
}

$('select[name="services$service_category_id"]').select2().on('select2:select', function () {
    filter();
});

// $("#tb-branch-price").tableHeadFixer({"head": false, "left": 3});

$('input[name="search_keyword"]').keyup(function (e) {
    if (e.keyCode == 13) {
        $(this).trigger("enterKey");
    }
});
$('input[name="search_keyword"]').bind("enterKey", function (e) {
    filter();
});

function filter() {
    $.ajax({
        url: laroute.route('admin.service-branch-price.filter'),
        method: "POST",
        data: {
            keyword: $('input[name="search_keyword"]').val(),
            serviceCategory: $('select[name="services$service_category_id"]').val()
        },
        success: function (data) {
            $('#list-data').empty();
            $('#list-data').append(data);
        }
    });
}

// $(document).ready(function () {
//     $("#tb-branch-price").tableHeadFixer({"head": false, "left": 3});
// })

function pageClickFilter(page) {
    $.ajax({
        url: laroute.route('admin.service-branch-price.paging-filter'),
        method: "POST",
        data: {
            keyword: $('input[name="search_keyword"]').val(),
            serviceCategory: $('select[name="services$service_category_id"]').val(),
            page: page
        },
        success: function (data) {
            $('#list-data').empty();
            $('#list-data').append(data);
        }
    })
}

function refresh() {
    $('input[name="search_keyword"]').val('');
    $('select[name="services$service_category_id"]').val('').trigger('change');
    filter();
}

$(document).on('keyup', "input[name='new_price']", function (obj) {
    // var n = $(this).val().replace(new RegExp('\\,', 'g'), '');

    // if (typeof n == 'number' && Number.isInteger(n))
    //     $(this).val(n.toLocaleString());
    // else {
    //     $(this).val('');
    // }

    //do something else as per updated question
    // myFunc(); //call another function too
});

function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
}
