$.widget('dai.PioTable', {
    // default options
    options: {
        baseUrl: '',
        frm_filter: '.frmFilter'
    },

    // Filter data
    _filter_data: '',
    _cur_page: 1,
    _per_page: 10,
    _column:"search",

    _init: function()
    {
        let o = this;

        o.element.find(o.options.frm_filter).submit(function() {
            o._search();
            return false;
        });
        o._blindEvent();

        o.element.find(o.options.frm_filter).find('select.m-input').change(function() {
            o._search();
        });
    },

    _blindEvent: function()
    {
        let o = this;
        // o.element.find('ul.m-datatable__pager-nav > li > a[data-page]').off('click').click(function(){
        // 	o._loadPage($(this).data('page'));
        // });

        // o.element.find('.m-datatable__pager-info .selectpicker').change(function() {
        // 	o._display($(this).val());
        // });
        // o.element.find('.m-datatable__pager-info .selectpicker').selectpicker();
    },

    _search: function()
    {
        let o = this;
        is_active = o.element.find(o.options.frm_filter).find('select.m-input').val();
        search_type = o.element.find(o.options.frm_filter).find(".search-type").val();
        text_value = o.element.find(o.options.frm_filter).find("#generalSearch").val();
        o._filter_data = {
            search_type:search_type,
            search_keyword:text_value,
            is_active:is_active
        };
        // o._filter_data = o.element.find(o.options.frm_filter).serialize();
        o._loadPage(1);
    },

    _loadPage: function(p)
    {
        this._cur_page = p;
        this._loadData();
    },

    _display: function(num)
    {
        this._cur_page = 1;
        this._per_page = num;
        this._loadData();
    },

    _loadData: function()
    {
        let o = this;
        // $.post(o.options.baseUrl, o._mergeData(o._filter_data, 'page=' + o._cur_page + '&display=' + o._per_page), function(resp) {
        //
        // 	// o.element.find('.table-content').html(resp);
        // 	datatable.reload();
        // 	o._blindEvent();
        // });
        datatable.search(o._filter_data,o._column);
        o._blindEvent();
    },

    // merge filter data and page
    _mergeData: function(str1, str2)
    {
        if (str1 == '') {
            return str2;
        }

        return str1 + '&' + str2;
    },

    /** refresh table **/
    refresh: function()
    {
        this._loadData();
    },

    reset: function()
    {
        this._loadPage(1);
    }
});
//
// $('#autotable').PioTable({
// 	baseUrl: laroute.route('user.list')
//    // baseUrl: laroute.route('user.list')
// });