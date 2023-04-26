var Orders = {
    status: null,
    queue: null,
    pioTable: null,
    jsonLang : null,
    start: function () {

        if ($('#m_dashboard_daterangepicker').length == 0) {
            return;
        }

        var picker = $('#m_dashboard_daterangepicker');
        var start = moment();
        var end = moment();

        function cb(start, end, label) {
            var title = '';
            var range = '';

            if ((end - start) < 100 || label == 'Today') {
                title = 'Today:';
                range = start.format('MMM D');
            } else if (label == 'Yesterday') {
                title = 'Yesterday:';
                range = start.format('MMM D');
            } else {
                range = start.format('MMM D') + ' - ' + end.format('MMM D');
            }

            picker.find('.m-subheader__daterange-date').html(range);
            picker.find('.m-subheader__daterange-title').html(title);
        }

        picker.daterangepicker({
            // direction: mUtil.isRTL(),
            startDate: start,
            endDate: end,
            opens: 'left',
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);

        cb(start, end, '');
    },
    init: function () {
       Orders.jsonLang = JSON.parse(localStorage.getItem('tranlate'));
        var options = {
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        method: 'POST',
                        headers: {},
                        url: laroute.route('dashbroad.list-order'),
                        map: function (raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 5,
                serverPaging: !0,
                serverFiltering: !0,
                serverSorting: 0
            },

            // layout definition
            layout: {
                theme: "default",
                class: "",
                scroll: !0,
                height: "auto",
                footer: 0
            },
            // column sorting
            sortable: !0,
            toolbar: {
                placement: ["bottom"], items: {
                    pagination: {
                        pageSizeSelect: [5, 10, 20, 30, 50]
                    }
                }
            },
            search: {
                input: $("#generalSearch")
            },

            // columns definition
            columns: [
                {
                    field: '',
                    title: '#',
                    sortable: false, // disable sort for this column
                    width: 40,
                    selector: false,
                    textAlign: 'center',
                    template: function (row, index, datatable) {
                        return (index + 1 + (datatable.getCurrentPage()) * datatable.getPageSize()) - datatable.getPageSize();
                    }
                }, {
                    field: 'order_code',
                    title: Orders.jsonLang['Mã đơn hàng'],
                    width: 150,
                    filterable: true, // disable or enablePOP filtering,
                    template: function (row) {
                        console.log(row.order_id);
                        if (row.order_source_id == 1) {
                            //Đơn hàng trực tiếp
                            return '<a href="' + laroute.route('admin.order.detail', { id : row.order_id}) + '" class="m-link m--font-bolder">' + row.order_code + '</a>';
                        } else {
                            //Đơn hàng online
                            return '<a href="' + laroute.route('admin.order-app.detail', { id : row.order_id}) + '" class="m-link m--font-bolder">' + row.order_code + '</a>';
                        }
                    }

                },
                {
                    field: 'full_name',
                    title: Orders.jsonLang['Khách hàng'],
                    filterable: false, // disable or enablePOP filtering
                },
                {
                    field: 'total',
                    title: Orders.jsonLang['Tổng tiền'],
                    filterable: true, // disable or enablePOP filtering
                    template: function (row) {
                        return '<span class="m--font-bolder">' + formatNumber(Number(row.amount).toFixed(decimal_number)) +' ' + Orders.jsonLang["đ"] +'</span>';
                    }

                },
                {
                    field: 'amount_paid',
                    title: Orders.jsonLang['Đã thanh toán'],
                    filterable: true, // disable or enablePOP filtering
                    template: function (row) {
                        var amountPaid = 0;

                        if (row.amount_paid != null) {
                            amountPaid = row.amount_paid;
                        }
                        console.log(amountPaid);
                        return '<span class="m--font-bolder">' + formatNumber(Number(amountPaid).toFixed(decimal_number)) + ' ' + Orders.jsonLang["đ"] +'</span>';
                    }

                },
                {
                    field: 'staffs',
                    title: Orders.jsonLang['Người tạo'],
                    filterable: false, // disable or enablePOP filtering
                },
                {
                    field: 'branch_name',
                    title: Orders.jsonLang['Chi nhánh'],
                    filterable: false, // disable or enablePOP filtering
                },
                {
                    field: 'process_status',
                    title: Orders.jsonLang['Trạng thái'],
                    filterable: false, // disable or enablePOP filtering
                    textAlign: 'center',
                    template: function (t) {
                        var a = {
                                "not_call": {
                                    title: Orders.jsonLang["Chưa xác nhận"], class: "m-badge--brand"
                                },
                                "packing": {
                                    title: Orders.jsonLang["Đang đóng gói"], class: " m-badge--metal"
                                }
                                ,
                                "delivered": {
                                    title: Orders.jsonLang["Đã giao"], class: " m-badge--primary"
                                }
                                ,
                                "confirmed": {
                                    title: Orders.jsonLang["Đã xác nhận"], class: " m-badge--warning"
                                }
                                ,
                                "preparing": {
                                    title: Orders.jsonLang["Đang chuẩn bị"], class: " m-badge--info"
                                }
                                ,
                                "delivering": {
                                    title: Orders.jsonLang["Đang giao"], class: " m-badge--danger"
                                },
                                "ordercomplete": {
                                    title: Orders.jsonLang["Hoàn thành"], class: " m-badge--warning"
                                },
                                "ordercancle": {
                                    title: Orders.jsonLang["Đã hủy"], class: "m-badge--danger"
                                },
                                "paysuccess": {
                                    title: Orders.jsonLang["Đã thanh toán"], class: " m-badge--primary"
                                },
                                "payfail": {
                                    title: Orders.jsonLang["Không thanh toán"], class: " m-badge--danger"
                                },
                                "new": {
                                    title: Orders.jsonLang["Mới"], class: "m-badge--success"
                                },
                                "pay-half": {
                                    title: Orders.jsonLang["Thanh toán còn thiếu"], class: "m-badge--info"
                                },
                            }
                        ;
                        return '<span class="m-badge ' + a[t.process_status].class + ' m-badge--wide">' + a[t.process_status].title + "</span>"
                    }
                }
            ],
        };

        Orders.pioTable = $('.m_datatable').mDatatable(options);
    
    }
};

Orders.start();
Orders.init();
