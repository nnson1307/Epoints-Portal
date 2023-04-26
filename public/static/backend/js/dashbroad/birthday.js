var Birthday = {
    status: null,
    queue: null,
    pioTable: null,
    jsonLang : JSON.parse(localStorage.getItem('tranlate')),
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
        
        var options = {
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        method: 'POST',
                        headers: {},
                        url: laroute.route('dashbroad.list-birthday'),
                        global: false,
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
                input: $("#search_customer")
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
                    field: 'name',
                    title: Birthday.jsonLang['Khách hàng'],
                    width: 200,
                    template: function (data) {
                        if (data.group == null) {
                            data.group = '';
                        }
                        if (data.avatar != null) {
                            output = '<div class="m-card-user m-card-user--sm">\
                            <div class="m-card-user__pic">\
                                <img width="40px" height="40px" src="' + data.avatar + '" class="m--img-rounded m--marginless" alt="photo">\
                            </div>\
                            <div class="m-card-user__details">\
                                <a href="" class="m-card-user__name">' + data.name + '</a>\
                                <span class="m-card-user__email">' +
                                data.group + '</span>\
                            </div>\
                        </div>';
                        } else {
                            var stateNo = mUtil.getRandomInt(0, 7);
                            var states = [
                                'success',
                                'brand',
                                'danger',
                                'accent',
                                'warning',
                                'metal',
                                'primary',
                                'info'];
                            var state = states[stateNo];
                            output = '<div class="m-card-user m-card-user--sm">\
                            <div class="m-card-user__pic">\
                                <div class="m-card-user__no-photo m--bg-fill-' + state +
                                '"><span>' + data.name.substring(0, 1) + '</span></div>\
                            </div>\
                            <div class="m-card-user__details">\
                                <a href="" class="m-card-user__name">' + data.name + '</a>\
                                <span class="m-card-user__email">' +
                                data.group + '</span>\
                            </div>\
                        </div>';

                        }

                        return output;
                    },
                },
                {
                    field: 'code',
                    title: Birthday.jsonLang['Mã khách hàng'],
                    filterable: false, // disable or enablePOP filtering
                },
                {
                    field: 'birthday',
                    title: Birthday.jsonLang['Ngày sinh'],
                    filterable: false, // disable or enablePOP filtering
                    template: function (data) {
                        // var string = 'Trống';
                        // if (data.birthday != null){
                        //     var date = new Date(data.birthday);
                        //
                        //     string = (date.getDate()) + '/' + date.getMonth()  + '/' +  date.getFullYear()
                        // }
                        var date = new Date(data.birthday).toLocaleDateString('vi-VN');

                        return date;
                    },

                },
                {
                    field: 'gender',
                    title: Birthday.jsonLang['Giới tính'],
                    filterable: true, // disable or enablePOP filtering
                    template: function (t) {
                        var a = {
                                "male": {
                                    title: Birthday.jsonLang["Nam"], class: "m-badge--brand", state: 'primary'
                                },
                                "female": {
                                    title: Birthday.jsonLang["Nữ"], class: " m-badge--metal", state: 'danger'
                                }
                                ,
                                "other": {
                                    title: Birthday.jsonLang["Khác"], class: " m-badge--primary", state: 'accent'
                                },
                                "null": {
                                    title: Birthday.jsonLang["Khác"], class: " m-badge--primary", state: 'accent'
                                }
                            }
                        ;

                        return '<span class="m-badge m-badge--' + a[t.gender].class +
                            ' m-badge--dot"></span>&nbsp;<span class="m--font-bold m--font-' +
                            a[t.gender].state + '">' + a[t.gender].title +
                            '</span>';
                    }

                },
                {
                    field: 'phone',
                    title: Birthday.jsonLang['Số điện thoại'],
                    filterable: true, // disable or enablePOP filtering

                },
                {
                    field: 'address',
                    title: Birthday.jsonLang['Địa chỉ'],
                    filterable: true, // disable or enablePOP filtering
                    template: function (a) {
                        let fullAddress = '';
                        if (a.address != null) {
                            fullAddress = a.address;
                        }
                        if (a.district_name != null) {
                            fullAddress = fullAddress + ', ' + a.district_name;
                        }
                        if (a.province_name != null) {
                            fullAddress = fullAddress + ', ' + a.province_name;
                        }
                        if (a.postcode != null) {
                            fullAddress = fullAddress + ', ' + a.postcode;
                        }
                        return fullAddress;
                    }
                }
            ],
        };

        Orders.pioTable = $('.m_datatable_birthday').mDatatable(options);
    
    }
};

Birthday.start();
Birthday.init();
