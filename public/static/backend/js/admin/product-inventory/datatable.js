options={
    data: {
        type: 'remote',
        source: {
            read: {
                url: laroute.route("service-example.list"),
                method: 'POST',
                headers: { },
                params: {
                    // custom parameters
                    // generalSearch: '',
                    // EmployeeID: 1,
                    // someParam: 'someValue',
                    // token: 'token-value'
                }
            }
        },
        pageSize: 10,
        saveState:false,

        serverPaging: true,
        serverFiltering: true,
        serverSorting: false,
        autoColumns: false
    },

    layout: {
        theme: 'default',
        class: 'm-datatable--brand',
        scroll: false,
        height: null,
        footer: true,
        header: true,

        smoothScroll: {
            scrollbarShown: true
        },

        spinner: {
            overlayColor: '#000000',
            opacity: 0,
            type: 'loader',
            state: 'brand',
            message: true
        },

        icons: {
            sort: {asc: 'la la-arrow-up', desc: 'la la-arrow-down'},
            pagination: {
                next: 'la la-angle-right',
                prev: 'la la-angle-left',
                first: 'la la-angle-double-left',
                last: 'la la-angle-double-right',
                more: 'la la-ellipsis-h'
            },
            rowDetail: {expand: 'fa fa-caret-down', collapse: 'fa fa-caret-right'}
        }
    },

    sortable: true,

    pagination: true,

    rows: {
        callback: function() {},
        // auto hide columns, if rows overflow. work on non locked columns
        autoHide: false,
    },

    // columns definition
    columns: [{
        field: "service_id",
        title: "ID",
        sortable: 'asc',
        filterable: false,
        width: 30,
        responsive: {visible: 'md'},
        // locked: {left: 'xl'},
        template: '{{service_id}}',
    },
        {
            field: "service_code",
            title: "Mã Dịch vụ",
            sortable: 'asc',
            filterable: false,
            width: 50,
            responsive: {visible: 'md'},
            // locked: {left: 'xl'},
            template: '{{service_code}}',
        },
        {
            field: "service_name",
            title: "Tên dịch vụ",
            sortable: 'asc',
            filterable: false,
            width: 150,
            responsive: {visible: 'md'},
            // locked: {left: 'xl'},
            template: '{{service_name}}',
        },
        {
            field: "description",
            title: "Mô tả",
            sortable: 'asc',
            filterable: false,
            width: 150,
            responsive: {visible: 'md'},
            // locked: {left: 'xl'},
            template: '{{description}}',
        },
        {
            field: "services_image",
            title: "Hình ảnh",
            sortable: 'asc',
            filterable: false,
            width: 100,
            responsive: {visible: 'md'},
            // locked: {left: 'xl'},
            // template: '{{services_image}}'
            template:function (row) {
                if(row.services_image != null)
                    return '<a target="_blank" href="'+laroute.route("uploads")+row.services_image+'"><img src="'+laroute.route("uploads")+row.services_image+'" alt="Hình ảnh" width="95" height="70"/></a>';
                else
                    return '<img src="'+laroute.route("uploads")+row.services_image+'" alt="Hình ảnh" width="95" height="70"/>';
            }
        },
        {
            field: "detail",
            title: "Chi tiết",
            sortable: 'asc',
            filterable: false,
            width: 200,
            responsive: {visible: 'md'},
            // locked: {left: 'xl'},
            template: '{{detail}}',
        },
        {
            field: "is_active",
            title: "Trạng thái",
            sortable: 'asc',
            filterable: false,
            width: 150,
            responsive: {visible: 'md'},
            // locked: {left: 'xl'},
            template: function (row) {

                if(row.is_active==1)
                    return '<div class="m-switch m-switch--success m-switch--sm">\n' +
                        '<label class="btn-is-active" style="margin: .5rem 0 0 0">\n' +
                        '<input type="checkbox" checked="checked" name="" data-id="'+row.service_id+'" act="publish">\n' +
                        '<span></span>\n' +
                        '<label style="margin: 0 0 0 10px; padding-top: 4px">Hoạt động</label>'+
                        '</label>\n' +
                        '</div>\n';
                else{
                    return '<div class="m-switch m-switch--success m-switch--sm">\n' +
                        '<label class="btn-is-active" style="margin: .5rem 0 0 0">\n' +
                        '<input type="checkbox" name="" data-id="'+row.service_id+'" act="unPublish">\n' +
                        '<span></span>\n' +
                        '<label style="margin: 0 0 0 10px; padding-top: 4px">Tạm ngưng</label>'+
                        '</label>\n' +
                        '</div>\n';
                }
            }
        },
        {
            field: "",
            title: "Hành động",
            width: 50,
            sortable: false,
            responsive: {visible: 'md'},
            // locked: {left: 'xl'},
            template: function (row) {
                return "<a href='"+laroute.route('service-example.edit',{id:row.service_id})+"'\n" +
                    "class=\"m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill\"\n" +
                    "title=\"Sửa dữ liệu\"><i class=\"la la-edit\"></i></a>\n" +
                    "<button onclick=\"service.remove(this, '"+row.service_id+"')\"\n" +
                    "class=\"m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill\"\n" +
                    "title=\"Xóa dữ liệu\"><i class=\"la la-trash\"></i></button>";
            }
        },],

    toolbar: {
        layout: ['pagination', 'info'],
        //
        placement: ['bottom'],  //'top', 'bottom'
        //
        items: {
            pagination: {
                //
                pageSizeSelect: [10, 25, 50, 100]
            },
            //
            info: true
        }
    }
};
var datatable = $('#m_datatable').mDatatable(options);

$(document).ready(function () {
    $(document).on("change", ".btn-is-active input", function () {
        // alert("ssss");
        $(this).prop("disabled", true);
        service.changeStatus($(this), $(this).attr("data-id"), $(this).attr("act"));
    });

    var page_size=10;

    $(document).on("mouseover","#autotable .m-datatable__pager-info ul li",function () {
        page_size = $(this).find("span").html();
    });

    $("#m_datatable").on("m-datatable--on-update-perpage", function (e, args) {
        // alert(args.perpage);
        args.perpage = page_size;
    });
});