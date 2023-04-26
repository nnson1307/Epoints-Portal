var productInventory = {
    _init: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var arrRange = {};
            arrRange[json["Hôm nay"]] = [moment(), moment()];
            arrRange[json["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
            arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
            arrRange[json["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
            arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
            arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
            $("#time").daterangepicker({
                // autoUpdateInput: false,
                autoApply: true,
                // buttonClasses: "m-btn btn",
                // applyClass: "btn-primary",
                // cancelClass: "btn-danger",
                maxDate: moment().endOf("day"),
                startDate: moment().subtract(6, "days"),
                endDate: moment(),
                locale: {
                    cancelLabel: 'Clear',
                    format: 'DD/MM/YYYY',
                    "applyLabel": json["Đồng ý"],
                    "cancelLabel": json["Thoát"],
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
            }).on('apply.daterangepicker', function (event) {
                productInventory.loadDetail();
            });

            $('#warehouse_id').select2().on('select2:select', function (event) {
                productInventory.loadDetail();
            });

            $('#product_id').select2({
                width: '100%',
                placeholder: json["Chọn sản phẩm"],
                allowClear: true,
                ajax: {
                    url: laroute.route('report.product-inventory.list-child'),
                    data: function (params) {
                        return {
                            search: params.term,
                            page: params.page || 1,
                        };
                    },
                    dataType: 'json',
                    method: 'POST',
                    processResults: function (data) {
                        data.page = data.page || 1;
                        return {
                            results: data.items.map(function (item) {
                                return {
                                    id: item.product_child_id,
                                    text: item.product_child_name,
                                    code: item.product_code
                                };
                            }),
                            pagination: {
                                more: data.pagination
                            }
                        };
                    },
                }
            }).on('change', function (event) {
                productInventory.loadDetail();
            });

            //Load list chi tiết khi load view
            productInventory.loadDetail();
        });
    },
    loadDetail: function () {
        //Gán data filter list detail
        $('#created_at_filter').val($('#time').val());
        $('#warehouse_id_filter').val($('#warehouse_id').val());
        $('#product_id_filter').val($('#product_id').val());
        //Gán data filter export
        $('#created_at_export').val($('#time').val());
        $('#warehouse_id_export').val($('#warehouse_id').val());
        $('#product_id_export').val($('#product_id').val());

        $('#autotable').PioTable({
            baseUrl: laroute.route('report.product-inventory.paginate')
        });

        $('.btn-search').trigger('click');
    }
};