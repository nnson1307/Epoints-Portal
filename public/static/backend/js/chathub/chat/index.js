var chatInternal = {

    domain : null,
    iFrame : null,
    _init : function (){
        chatInternal.iFrame = document.getElementById('if_chathub_inbox');

        if (window.addEventListener) {
            window.addEventListener('message', chatInternal.onMessage, false);
        } else if (window.attachEvent) {
            window.attachEvent('onmessage', chatInternal.onMessage, false);
        }
        const bcCusAddSuccess = new BroadcastChannel('customer_add_success');

        bcCusAddSuccess.onmessage = (event) => {
            chatInternal.pushAddCustomerSuccess();
        }


    },

    onMessage : function (event){
        // Check sender origin to be trusted
        // if (event.origin !== chatInternal.domain) return;

        var data = event.data;
        if (typeof(chatInternal[data.func]) === 'function') {
            chatInternal[data.func].call(null, data.message);
        }
    },

    showAddCustomerLead : function (data){
        customerDealCreate.popupCreateLead(false);
        console.log(data);

        var fillData = setInterval(function(){
            if($('#form-create-lead').length){
                $('#popup_full_name').val(data.full_name);
                $('<input>').attr('type','hidden').attr('name','ch_customer_id').attr('id','ch_customer_id')
                    .attr('value',data.ch_customer_id).appendTo('#form-create-lead');
                clearInterval(fillData);
            }
        }, 500);
    },

    addSuccessCustomerLead : function(){
        chatInternal.iFrame.contentWindow.postMessage({
            'func': 'addSuccessCustomerLead',
            'message': {full_name : 'vu ngo'}
        }, '*');
    },

    showAddCustomerDeal : function (data){
        customerDealCreate.popupCreate(false);
        setTimeout(function(){
            // $('#full_name').val(data.full_name)
        }, 500);
    },

    addSuccessCustomerDeal : function(){
        chatInternal.iFrame.contentWindow.postMessage({
            'func': 'addSuccessCustomerDeal',
            'message': {full_name : 'vu ngo'}
        }, '*');
    },

    showAddCustomer : function (data){
        var new_window = window.open(laroute.route('admin.customer.add', {view_mode : 'chathub_popup', full_name : 'vu'}), '_blank',"directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no");
    },

    addSuccessCustomer : function (){

    },

    showAddOrder : function (data){
        var new_window = window.open(laroute.route('admin.order.add', {view_mode : 'chathub_popup', customer_id : data.customer_id}), '_blank',"directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no");
    },

    addSuccessOrder : function (){

    },

    showAddManagerWork : function (data){
        $('#message_chat').val(data.content)
        WorkChild.showPopup();
    },


}
