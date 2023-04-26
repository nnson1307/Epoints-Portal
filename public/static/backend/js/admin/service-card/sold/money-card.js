function refresh() {
    $('#branch').val('').trigger('change');
    $('#status').val('').trigger('change');
    $('#staff').val('').trigger('change');
    $('input[name=search_keyword]').val('');
    $('#time').val('');
    filter();
}

$('#branch').select2().on('select2:select', function () {
    filter();
});
$('#status').select2().on('select2:select', function () {
    filter();
});
$('#staff').select2().on('select2:select', function () {
    filter();
});
$('input[name=search_keyword]').keyup(function (e) {
    if (e.keyCode == 13) {
        $(this).trigger("enterKey");
    }
});
$('input[name=search_keyword]').bind("enterKey", function (e) {
    filter();
});
// Chọn ngày.
$('#time').on('apply.daterangepicker', function (ev, picker) {
    var start = picker.startDate.format("DD/MM/YYYY");
    var end = picker.endDate.format("DD/MM/YYYY");
    $(this).val(start + " - " + end);
    filter();
});
$.getJSON(laroute.route('translate'), function (json) {
var arrRange = {};
arrRange[json["Hôm nay"]] = [moment(), moment()];
arrRange[json["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
arrRange[json["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
$("#time").daterangepicker({
    autoUpdateInput: false,
    autoApply: true,
    buttonClasses: "m-btn btn",
    applyClass: "btn-primary",
    cancelClass: "btn-danger",

    maxDate: moment().endOf("day"),
    startDate: moment().startOf("day"),
    endDate: moment().add(1, 'days'),
    locale: {
        format: 'DD/MM/YYYY',
        "applyLabel": json["Đồng ý"],
        "cancelLabel": json["Thoát"],
        "customRangeLabel": json["Tùy chọn ngày"],
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
}).on('apply.daterangepicker', function (ev) {
});
});
function filter() {
    let keyWord = $('input[name=search_keyword]').val();
    let status = $('#status').val();
    let branch = $('#branch').val();
    let staff = $('#staff').val();
    let time = $('#time').val();

    $.ajax({
        url: laroute.route('admin.service-card.sold.filter'),
        method: "POST",
        data: {
            cardType: 'money',
            keyWord: keyWord,
            status: status,
            branch: branch,
            staff: staff,
            time: time
        },
        success: function (data) {
            $('.list-card').empty();
            $('.list-card').append(data);
        }
    })
}
function pageClick(page) {
    $.ajax({
        url: laroute.route('admin.service-card.sold.paginate'),
        method: "POST",
        data: {
            page: page,
            cardType:'money'
        },
        success: function (data) {
            $('.list-card').empty();
            $('.list-card').append(data);

        }
    })
}
function pageClickFilter(page) {
    let keyWord = $('input[name=search_keyword]').val();
    let status = $('#status').val();
    let branch = $('#branch').val();
    let staff = $('#staff').val();
    let time = $('#time').val();

    $.ajax({
        url: laroute.route('admin.service-card.sold.paging-search'),
        method: "POST",
        data: {
            cardType: 'money',
            keyWord: keyWord,
            status: status,
            branch: branch,
            staff: staff,
            time: time,
            page:page
        },
        success: function (data) {
            $('.list-card').empty();
            $('.list-card').append(data);
        }
    })
}
//