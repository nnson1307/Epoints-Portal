var AjaxHandle = {
  startListen: function (param={}) {
    var form = param.form;
    var button = param.button;
    var callback = param.callback;
    var submitOnChange = param.submitOnChange;

    jQuery(document).on('click',form+button,function () {
      var objectData = jQuery(this).data();
      var data = '';
      jQuery.each(objectData,function (key,value) {
        data = data+'&'+key+'='+value;
      });
      AjaxHandle.ajax({
        action:jQuery(this).attr('action'),
        method:jQuery(this).attr('method'),
        data:data,
        callback:callback
      });
      return;
    });

    if(typeof submitOnChange != 'undefined' && typeof form != 'undefined'){

      submitOnChange = submitOnChange.split(",");
      jQuery.each(submitOnChange,function (key,value) {

        jQuery(document).on('change',form+' [name="'+value+'"]',function () {

          var newForm=jQuery(this).parents(form).first();
          if(newForm.length>0){
            var action = jQuery(newForm).attr('action');
            AjaxHandle.submitForm(newForm,callback);
          }else{
            var action = jQuery(form).attr('action');
            AjaxHandle.submitForm(form,callback);
          }

        });
      });
    }


    jQuery(document).on('click', form+' '+button+':not('+form+')' ,function () {
      // form
      var thisForm = jQuery(this).parents(form).first();

      //detect action
      var action = window.location.href;
      if( typeof jQuery(thisForm).attr('action') != 'undefined' ) action = jQuery(thisForm).attr('action');
      if( typeof jQuery(this).attr('action') != 'undefined' ) action = jQuery(this).attr('action');
      
      //detect method
      var method = 'GET';
      if( typeof jQuery(thisForm).attr('method') != 'undefined' ) method = jQuery(thisForm).attr('method');
      if( typeof jQuery(this).attr('method') != 'undefined' ) method = jQuery(this).attr('method');


      // get data
      var data = thisForm.find(':input').serialize();
      jQuery.each(jQuery(thisForm).data(),function (key,value) {
        data = data+'&'+key+'='+value;
      });
      jQuery.each(jQuery(this).data(),function (key,value) {
        data = data+'&'+key+'='+value;
      });
          
      AjaxHandle.ajax({
        action:action,
        method:method,
        data:data,
        callback:callback
      });

      return false;
    });


    jQuery(document).on('keypress',form,function(e) {
      if(e.which == 13) {
        AjaxHandle.submitForm(form);
      }
    });

  },
  startListenButton: function (button,callback = function(){}) {
    jQuery(document).on('click', button ,function () {
      var action = jQuery(this).attr('action');
      var method = jQuery(this).attr('method');

      var buttonData = jQuery(this).data();
      var data = '';
      jQuery.each(buttonData,function (key,value) {
        data = data+'&'+key+'='+value;
      });


      AjaxHandle.ajax({
        method:method,
        action:action,
        data:data
      });
      return false;
    });
  },
  ajaxFormSubmit: function (listForm) {
    jQuery.each(listForm,function (key,val) {
      AjaxHandle.submitForm(val);
    });
  },
  submitForm: function (form,callback = function(){}) {
    if( jQuery(form).length == 0 ) return false;
    var action = jQuery(form).attr('action');
    if(typeof action === 'undefined') action = window.location.href;
    var method = jQuery(form).attr('method');
    if(typeof method === 'undefined') method = 'GET';

    var data = jQuery(form).find(':input').serialize();
    var formData = jQuery(form).attr('data');
    data = data +'&'+ formData;
    data = AjaxHandle.handleData(data);

    jQuery.ajax({
      url: action,
      method: method,
      dataType: "JSON",
      data: data,
      success: function (res) {
        AjaxHandle.handleResponse(res,callback);
      },error: function(res){
        var mess_error = '';
        jQuery.map(res.responseJSON.errors, function (a) {
          mess_error = mess_error.concat(a + '<br/>');
        });
        swal.fire(mess_error,'', "error");
      }
    });
  },
  formSubmit: function (form,callback = function(){}) {
    AjaxHandle.submitForm(form,callback);
  },
  ajax: function (param={}) {
    var action = param.action;
    if(typeof action === 'undefined') action = window.location.href;

    var method = param.method;
    if(typeof method === 'undefined') method = 'GET';

    var data = param.data;
    if(typeof data === 'undefined') data = '';

    data = AjaxHandle.handleData(data);

    var callback = param.callback;

    jQuery.ajax({
      url: action,
      method: method,
      dataType: "JSON",
      data: data,
      success: function (res) {
        AjaxHandle.handleResponse(res);
        if(typeof callback != 'undefined') callback(res);
      },error: function(res){
        var mess_error = '';
        jQuery.map(res.responseJSON.errors, function (a) {
          mess_error = mess_error.concat(a + '<br/>');
        });
        swal.fire(mess_error,'', "error");
      }
    });
  },
  handleData: function (data) {
    const regex = /(^|&)(undefined)(=[^&]*|$)/ig;
    data = data.replaceAll(regex,'');
    return data;
  },
  submit: function (element) {
    var action = jQuery(element).attr('action');
    var method = jQuery(element).attr('method');
    var data = jQuery(element).find(':input').serialize();
    jQuery.each(jQuery(element).data(),function (key,value) {
      data = data+'&'+key+'='+value;
    });

    jQuery.ajax({
      url: action,
      method: method,
      dataType: "JSON",
      data: data,
      success: function (res) {
        AjaxHandle.handleResponse(res);
        if(typeof callback != 'undefined') callback(res);
      },error: function(res){
        var mess_error = '';
        jQuery.map(res.responseJSON.errors, function (a) {
          mess_error = mess_error.concat(a + '<br/>');
        });
        swal.fire(mess_error,'', "error");
      }
    });
  },
  handleResponse: function (response,callback=function(){}) {
    jQuery.each( response.action, function( key, value ) {
      if( (typeof response[value] != 'undefined' ) && (typeof AjaxHandle[value] == 'function') ){
        AjaxHandle[value](response[value]);
      }
    });
    callback(response);
  },
  appendOrReplace: function (obj) {
    AjaxHandle.replaceOrAppend(obj);
  },
  replaceOrAppend: function (obj) {
    jQuery.each( obj, function( key, value ) {
      if( jQuery(key).length>0 ){
        jQuery(key).replaceWith(value);
        AjaxHandle.onLoad(key);
      }else{
        jQuery('body').append(value);
        AjaxHandle.onLoad(key);
      }
    });
  },
  html: function (obj) {
    jQuery.each( obj, function( key, value ) {
      jQuery(key).html(value);
      AjaxHandle.onLoad(key);
    });
  },
  swal: function (obj) {
    swal.fire(obj).then(function() {
      if( typeof obj.redirect !== 'undefined' ){
        window.location.href=obj.redirect;
      }
    });
  },
  modal: function (obj) {
    jQuery.each( obj, function( key, value ) {
      jQuery(key).modal(value);
    });
  },
  input: function (obj) {
    AjaxHandle.value(obj);
  },
  value: function (obj) {
    jQuery.each( obj, function( key, value ) {
      jQuery(key).val(value);
      AjaxHandle.onChange(key);
    });
  },
  remove: function (obj) {
    jQuery.each( obj, function( key, value ) {
      jQuery(value).remove();
    });
  },
  replace: function (obj) {
    jQuery.each( obj, function( key, value ) {
      jQuery(key).replaceWith(value);
      AjaxHandle.onLoad(key);
    });
  },
  eval: function (data) {
    switch (typeof data) {
      case 'string':
        eval(data);
        break;
      case 'object':
        jQuery.each( data, function( key, value ) {
          eval(value);
        });
        break;
      default:
        console.log('warning function AjaxHandle.eval execute with '+(typeof data)+' param');
    }
  },
  onLoad: function (element) {
    jQuery(element).each(function(){
      var onLoad = jQuery(this).attr('onLoad');
      eval(onLoad);
    });
    jQuery(element).find('[onLoad]').each(function(){
      var onLoad = jQuery(this).attr('onLoad');
      eval(onLoad);
    });
  },
  onChange: function (element) {
    jQuery(element).each(function(){
      var onChange = jQuery(this).attr('onChange');
      window[onChange];
    });
    jQuery(element).find('[onChange]').each(function(){
      var onChange = jQuery(this).attr('onChange');
      window[onChange];
    });
  }
};

var AjaxLaravelPagination = {
  startListen: function (paginator= document) {
    jQuery(document).on('change', paginator+' [name="perpage"]' ,function () {
      var perPage = AjaxLaravelPagination.getPerPage(paginator);
      var currentPage = AjaxLaravelPagination.getCurrentPage();
      AjaxLaravelPagination.goToPage(currentPage,perPage,paginator);
    });

    jQuery(document).on('click', paginator+' a' ,function () {
      var perPage = AjaxLaravelPagination.getPerPage(paginator);
      // var page = jQuery(this).attr('href').match("[?&]page=([^&]+).*jQuery")[1];
      var page = jQuery(this).data('page');
      AjaxLaravelPagination.goToPage(page,perPage,paginator);

      return false;
    });

  },
  getPerPage: function (selector= 'html') {
    return jQuery(selector).find('[name="perpage"]').first().val();
  },
  setPerPage: function (value=10,selector='html') {
    // set per_page to paginator
    jQuery(selector).find('[name="perpage"]').first().val(value);
    // detect target form
    var target = jQuery(selector).attr('target');
    if(typeof target === 'undefined') target = 'html';
    // check per_page input
    var input = jQuery(selector).find('[name="per_page"]');

    if(input.length == 0){
      // add per_page input and set value
      jQuery(target).append('<input type="hidden" name="per_page" value="'+value+'">');
    }else{
      input.val(value);
    }
  },
  setCurrentPage: function (value=1,selector='html') {
    // detect target form
    var target = jQuery(selector).attr('target');
    if(typeof target === 'undefined') target = 'html';
    // check current_page input
    var input = jQuery(selector+ '[name="current_page"]');
    if(input.length == 0){
      // add current_page input and set value
      jQuery(target).append('<input type="hidden" name="current_page" value="'+value+'">');
    }else{
      input.val(value);
    }
  },
  getCurrentPage: function (page=1,selector=document) {
    return jQuery(selector).find('.kt-pagination__link--active a').first().html();
  },
  goToPage: function (page,perPage=0,selector) {

    if(perPage == 0) perPage = AjaxLaravelPagination.getPerPage(selector);

    var target = jQuery(selector).first().attr('target');
    if(typeof target === 'undefined') target = 'html';

    AjaxLaravelPagination.setPerPage(perPage,selector);
    AjaxLaravelPagination.setCurrentPage(page,selector);

    AjaxHandle.submitForm(target);
  }
};


var TableInput = {
  startListen: function (param={}) {
    var table = param.table;
    var addButton = param.addButton;
    var callback = param.callback;

    jQuery(document).on('click', addButton ,function () {
      var newItem = TableInput.addItem({table:table})
      callback({
        newItem:newItem
      });
    });

  },
  addItem: function (param={}) {
    var table = param.table;
    var item = jQuery(table).find('tbody>tr:nth-child(1)').prop('outerHTML');
    jQuery(table).find('tbody').append(item);
    var newItem = jQuery(table).find('tbody>tr:last-child');
    TableInput.onShow(newItem);
    console.log(newItem);
  },
  onShow: function (element) {
    jQuery(element).each(function(){
      var onShow = jQuery(this).attr('onShow');
      eval(onShow);
    });
    jQuery(element).find('[onShow]').each(function(){
      var onShow = jQuery(this).attr('onShow');
      eval(onShow);
    });
  }
};