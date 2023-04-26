$.widget('dai.PioTable', {
	// default options
	options: {
		baseUrl: '',
		frm_filter: '.frmFilter',
		perPage:10
	},
	
	// Filter data
	_filter_data: '',
	_cur_page: 1,
	_per_page: 10,
	
	_init: function()
	{
		let o = this;
		
		o.element.find(o.options.frm_filter).submit(function() {
			o._search();
			return false;
		});
		o._blindEvent();

		if(o.options.perPage){
			this._per_page = o.options.perPage;
		}
		
		o.element.find(o.options.frm_filter).find('select.m-input').change(function() {
			// o._search();
		});

        o.element.find(o.options.frm_filter).find('input.daterange-picker').on("apply.daterangepicker",function(ev, picker) {
            var start = picker.startDate.format("DD/MM/YYYY");
            var end = picker.endDate.format("DD/MM/YYYY");

            $(this).val(start+ " - "+end);
        	// o._search();
        });
        o.element.find(o.options.frm_filter).find('input.datepicker').on("change",function(e) {
            // console.log($(this).val());
            // o._search();
        });
        o.element.find(o.options.frm_filter).find('input.timepicker').on("change",function(e) {
            // console.log($(this).val());
            // o._search();
        });
        o.element.find(o.options.frm_filter).find('input.datepicker-filter').on("change",function(e) {
            // console.log($(this).val());
            // o._search();
        });
	},
	
	_blindEvent: function()
	{
		let o = this;
		o.element.find('ul.m-datatable__pager-nav > li > a[data-page]').off('click').click(function(){
			o._loadPage($(this).data('page'));
		});
		
		o.element.find('.m-datatable__pager-info .selectpicker').change(function() {
			o._display($(this).val());
		});
		o.element.find('.m-datatable__pager-info .selectpicker').selectpicker();
	},
	
	_search: function()
	{
		let o = this;
		o._filter_data = o.element.find(o.options.frm_filter).serialize();
		// console.log(o._filter_data)
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
		$.post(o.options.baseUrl, o._mergeData(o._filter_data, 'page=' + o._cur_page + '&display=' + o._per_page), function(resp) {

			o.element.find('.table-content').html(resp);
			o._blindEvent();
		});
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
