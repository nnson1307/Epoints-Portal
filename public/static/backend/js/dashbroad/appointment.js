var Appointments = {
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
                        url: laroute.route('dashbroad.list-appointment'),
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
                input: $("#search")
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
                    field: 'code',
                    title: Appointments.jsonLang['Mã lịch hẹn'],
                    width: 150,
                    filterable: false, // disable or enablePOP filtering,
                    template: function (row) {
                        // return '<a href="'+laroute.route('ticket.edit',{id:row.customer_appointment_id})+'" class="m-link m--font-bolder">'+row.code+'</a>';
                        return '<a class="m-link m--font-bolder" href="'+ laroute.route('admin.customer_appointment.detail-booking', {id: row.customer_appointment_id}) +'">'+row.code+'</a>';

                    }

                },{
                    field: 'full_name',
                    title: Appointments.jsonLang['Khách hàng'],
                    filterable: false, // disable or enablePOP filtering
                    // width: 250,
                    // template: function (row) {
                    //     return '<a href="javascript:void(0)" onclick="ListDevice.detail('+row.pop_device_id+')" class="m-link m--font-bolder">'+row.pop_device_name+'</a>';
                    // }
                },
                {
                    field: 'time_appointment',
                    title: Appointments.jsonLang['Giờ hẹn'],
                    // sortable: 'asc',
                    sortable: true,
                    filterable: false, // disable or enablePOP filtering

                },
                {
                    field: 'quantity',
                    title: Appointments.jsonLang['Số khách'],
                    filterable: false, // disable or enablePOP filtering
                    textAlign: 'center',
                    // width: 150,
                },
                {
                    field: 'branch_name',
                    title: Appointments.jsonLang['Chi nhánh'],
                    filterable: false, // disable or enablePOP filtering
                    textAlign: 'center',
                    // width: 150,
                },
                {
                    field: 'staff',
                    title: Appointments.jsonLang['Người tạo'],
                    filterable: false, // disable or enablePOP filtering
                    textAlign: 'center',
                    // width: 150,
                },
                {
                    field: 'status',
                    title: Appointments.jsonLang['Trạng thái'],
                    filterable: false, // disable or enablePOP filtering
                    textAlign: 'center',
                    template:function(t) {
                        var a= {
                                "new": {
                                    title: Appointments.jsonLang["Mới"], class: "m-badge--brand"
                                }
                                ,
                                "confirm": {
                                    title: Appointments.jsonLang["Đã xác nhận"], class: " m-badge--success"
                                }
                                ,
                                "wait": {
                                    title: Appointments.jsonLang["Chờ phục vụ"], class: " m-badge--warning"
                                }

                            }
                        ;
                        return'<span class="m-badge '+a[t.status].class+' m-badge--wide">'+a[t.status].title+"</span>"
                    }
                }],
        };
        Appointments.pioTable = $('.m_datatable_appointment').mDatatable(options);
    
    },
    tab_appointment:function () {
        $('.m_datatable_appointment').mDatatable().search('');
    }
};

Appointments.start();
Appointments.init();

