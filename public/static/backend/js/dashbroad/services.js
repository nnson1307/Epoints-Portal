var Services = {
    status:null,
    queue:null,
    pioTable:null,
    jsonLang : JSON.parse(localStorage.getItem('tranlate')),
    start:function () {

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
    init:function () {
        var options = {
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        method: 'POST',
                        headers: {},
                        url: laroute.route('dashbroad.list-services'),
                        global: false,
                        map: function(raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                        complete:function () {
                            alert('ok');
                        }

                    },
                },
                pageSize: 5,
                serverPaging:!0,
                serverFiltering:!0,
                serverSorting:0
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
            sortable:!0,
            toolbar: {
                placement:["bottom"], items: {
                    pagination: {
                        pageSizeSelect: [5, 10, 20, 30, 50]
                    }
                }
            },
            search: {
                input: $("#search_service")
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
                        return (index + 1 + (datatable.getCurrentPage()) * datatable.getPageSize())-datatable.getPageSize();
                    }
                },
                {
                    field: 'service_name',
                    title: Services.jsonLang['Dịch vụ'],
                    width: 250,
                    filterable: false, // disable or enablePOP filtering,
                    // template: function (row) {
                    //     // return '<a href="'+laroute.route('ticket.edit',{id:row.customer_services_id})+'" class="m-link m--font-bolder">'+row.code+'</a>';
                    //     return '<span class="m-link m--font-bolder">'+row.code+'</span>';
                    //
                    // }

                },{
                    field: 'service_code',
                    title: Services.jsonLang['Mã dịch vụ'],
                    filterable: false, // disable or enablePOP filtering
                    // width: 250,
                    // template: function (row) {
                    //     return '<a href="javascript:void(0)" onclick="ListDevice.detail('+row.pop_device_id+')" class="m-link m--font-bolder">'+row.pop_device_name+'</a>';
                    // }
                },
                {
                    field: 'price_standard',
                    title: Services.jsonLang['Giá dịch vụ'],
                    // sortable: 'asc',
                    sortable: true,
                    filterable: false, // disable or enablePOP filtering
                    template: function (row) {
                        return '<span class="m--font-bolder">' + formatNumber(Number(row.price_standard).toFixed(decimal_number)) +' ' + Services.jsonLang["đ"] +'</span>';
                    }
                },
                {
                    field: 'new_price',
                    title: Services.jsonLang['Giá khuyến mãi'],
                    filterable: false, // disable or enablePOP filtering
                    template: function (row) {
                        return '<span class="m--font-bolder">' + formatNumber(Number(row.new_price).toFixed(decimal_number)) +' ' + Services.jsonLang["đ"] +'</span>';
                    }
                }
                ],
        };
        Services.pioTable = $('.m_datatable_services').mDatatable(options);
    },
    tab_services:function () {
        $('.m_datatable_services').mDatatable().search('');
    }
};

Services.start();
Services.init();

function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}
