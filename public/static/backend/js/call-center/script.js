var callCenter = {
    jsontranslate : JSON.parse(localStorage.getItem('tranlate')),

    showModalSearchCustomer: function () {
        $.ajax({
            url: laroute.route('call-center.show-modal-search-customer'),
            method: 'POST',
            dataType: 'JSON',
            data: {},
            success: function (res) {
                if (res.html != null) {
                    $('#modal-call-center-search').html(res.html);
                    $('#modal-search').modal('show');
                } else {
                    Swal.fire(
                        callCenter.jsontranslate['Thông Báo'],
                        callCenter.jsontranslate['Có lỗi xảy ra, hãy thử lại sau!'],
                        'error'
                    )
                }

            }
        });
    },
    SearchCustomerCallCenter: function () {

        $.ajax({
            url: laroute.route('call-center.search-customer'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                keyWord: $('#keyWordCustomer').val()
            },
            success: function (res) {
                if (res.html != null) {
                    $('#lstCustomerSearch').html(res.html);
                }else {
                    Swal.fire(
                        callCenter.jsontranslate['Thông Báo'],
                        callCenter.jsontranslate['Có lỗi xảy ra, hãy thử lại sau!'],
                        'error'
                    )
                }

            }
        });
    },

    showModalCustomerInfo: function (object_id, object_type, keyWord = '') {
        $.ajax({
            url: laroute.route('call-center.customer-info'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                object_id : object_id,
                object_type : object_type,
                phone : keyWord
            },
            success: function (res) {
                if (res.html != null) {
                    $('#modal-search').modal('hide');
                    $('#modal-call-center-search').empty();
                    $('.modal-backdrop').remove();
                    $('#modal-call-center-search').html(res.html);
                    $('#modal-info').modal('show');
                } else {
                    Swal.fire(
                        callCenter.jsontranslate['Thông Báo'],
                        callCenter.jsontranslate['Có lỗi xảy ra, hãy thử lại sau!'],
                        'error'
                    )
                }

            }
        });
    },

    showModalCustomerInfoSuccess: function (id) {
        
        $.ajax({
            url: laroute.route('call-center.customer-info-success'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                customer_request_id : id
            },
            success: function (res) {
                if (res.html != null) {
                    $('#modal-search').modal('hide');
                    $('#modal-call-center-search').empty();
                    $('.modal-backdrop').remove();
                    $('#modal-call-center-search').html(res.html);
                    $('#modal-info').modal('show');
                } else {
                    Swal.fire(
                        callCenter.jsontranslate['Thông Báo'],
                        callCenter.jsontranslate['Có lỗi xảy ra, hãy thử lại sau!'],
                        'error'
                    )
                }

            }
        });
    },

    showDetailRequest: function(object){
        var data = {
            call_center_phone : object['customer_request_phone'],
            call_center_full_name : object['customer_request_name'],
            call_center_note : object['customer_request_note'],
            call_center_customer_request_type : object['customer_request_type'],
        };
        
        callCenter.showModalCustomerInfoSuccess(id)
    },


    saveCustomerRequest: function(){
      
        var form = $('#formCustomerRequest');
        form.validate({
            rules: {
                call_center_phone: {
                    required: true,
                    integer: true,
                },
                call_center_full_name: {
                    required: true,
                },
                call_center_customer_source: {
                    required: true,
                },
                call_center_pipeline: {
                    required: true,
                },
                call_center_journey: {
                    required: true,
                },
            },
            messages: {
                call_center_phone: {
                    required: "Nhập số điện thoại liên hệ",
                    integer: 'Số điện thoại không hợp lệ',
                },
                call_center_full_name: {
                    required: "Nhập tên người liên hệ"
                },
                call_center_customer_source: {
                    required: "Vui lòng nguồn khách hàng"
                },
                call_center_pipeline: {
                    required: "Vui lòng chọn hành trình"
                },
                call_center_journey: {
                    required: "Vui lòng chọn trạng thái"
                },
            },
        });
        if (!form.valid()) {
            return false;
        }
        var data = {
            call_center_phone : $('#call_center_phone').val(),
            call_center_full_name : $('#call_center_full_name').val(),
            call_center_gender : $('input[name="call_center_gender"]:checked').val(),
            call_center_customer_type : $('input[name="call_center_customer_type"]:checked').val(),
            call_center_pipeline : $('#call_center_pipeline').val(),
            call_center_journey : $('#call_center_journey').val(),
            call_center_staff : $('#call_center_staff').val(),
            call_center_province : $('#call_center_province').val(),
            call_center_district : $('#call_center_district').val(),
            call_center_ward : $('#call_center_ward').val(),
            call_center_address : $('#call_center_address').val(),
            call_center_customer_source : $('#call_center_customer_source').val(),
            call_center_customer_request_type : $('input[name="call_center_customer_request_type"]:checked').val(),
            call_center_note : $('#call_center_note').val(),
         
        };
        form.serializeArray().forEach(element => {
            switch (element['name']) {
                // case 'column_request_type':
                //     data['call_center_customer_request_type'] = $('input[name="call_center_customer_request_type"]:checked').val();
                //     break; 
                case 'custom_column_value_1':
                    data['custom_column_value_1'] = element['value'];
                    break;
                case 'custom_column_name_1':
                    data['custom_column_name_1'] = element['value'];
                    break;
                case 'object_data_type_1':
                    data['object_data_type_1'] = element['value'];
                    break;
                case 'custom_column_value_2':
                    data['custom_column_value_2'] = element['value'];
                    break;
                case 'custom_column_name_2':
                    data['custom_column_name_2'] = element['value'];
                    break;
                case 'object_data_type_2':
                    data['object_data_type_2'] = element['value'];
                    break;
                case 'custom_column_value_3':
                    data['custom_column_value_3'] = element['value'];
                    break;
                case 'custom_column_name_3':
                    data['custom_column_name_3'] = element['value'];
                    break;
                case 'object_data_type_3':
                    data['object_data_type_3'] = element['value'];
                    break;
                case 'custom_column_value_4':
                    data['custom_column_value_4'] = element['value'];
                    break;
                case 'custom_column_name_4':
                    data['custom_column_name_4'] = element['value'];
                    break;
                case 'object_data_type_4':
                    data['object_data_type_4'] = element['value'];
                    break;
                case 'custom_column_value_5':
                    data['custom_column_value_5'] = element['value'];
                    break;
                case 'custom_column_name_5':
                    data['custom_column_name_5'] = element['value'];
                    break;
                case 'object_data_type_5':
                    data['object_data_type_5'] = element['value'];
                    break;
                case 'custom_column_value_6':
                    data['custom_column_value_6'] = element['value'];
                    break;
                case 'custom_column_name_6':
                    data['custom_column_name_6'] = element['value'];
                    break;
                case 'object_data_type_6':
                    data['object_data_type_6'] = element['value'];
                    break;
                case 'custom_column_value_7':
                    data['custom_column_value_7'] = element['value'];
                    break;
                case 'custom_column_name_7':
                    data['custom_column_name_7'] = element['value'];
                    break;
                case 'object_data_type_7':
                    data['object_data_type_7'] = element['value'];
                    break;
                case 'custom_column_value_8':
                    data['custom_column_value_8'] = element['value'];
                    break;
                case 'custom_column_name_8':
                    data['custom_column_name_8'] = element['value'];
                    break;
                case 'object_data_type_8':
                    data['object_data_type_8'] = element['value'];
                    break;
                case 'custom_column_value_9':
                    data['custom_column_value_9'] = element['value'];
                    break;
                case 'custom_column_name_9':
                    data['custom_column_name_9'] = element['value'];
                    break;
                case 'object_data_type_9':
                    data['object_data_type_1'] = element['value'];
                    break;
                case 'custom_column_value_10':
                    data['custom_column_value_10'] = element['value'];
                    break;
                case 'custom_column_name_10':
                    data['custom_column_name_10'] = element['value'];
                    break;
                case 'object_data_type_10':
                    data['object_data_type_10'] = element['value'];
                    break;
              }
        });
        $.ajax({
            url: laroute.route('call-center.create-not-info'),
            method: 'POST',
            dataType: 'JSON',
            data: data,
            success: function (res) {
            
                if(!res.error){
                    swal({
                        title:  res.message,
                        text: 'Redirecting...',
                        type: 'success',
                        timer: 1500,
                        showConfirmButton: false,
                    })
                    .then(() => {
                       
                        $('#modal-info').modal('hide');
                        $('.modal-backdrop').remove();
                        callCenter.showModalCustomerInfoSuccess(res.id);
                        var url = window.location.href;
                        if(url.includes("dashbroad")){
                            statisticCustomer.getCustomerRequestToday();
                        }else if(url.includes("call-center")){
                            $('#autotable').PioTable('refresh');
                        }
                    });
                }else{
                    Swal.fire(
                        callCenter.jsontranslate['Thông Báo'],
                        res.message,
                        'error'
                    )
                }
            }
        });
    },

    addCustomerRequest: function(){
        var form = $('#formCustomerRequest');
        form.validate({
            rules: {
                call_center_phone: {
                    required: true,
                    integer: true,
                },
                call_center_full_name: {
                    required: true,
                },
               
            },
            messages: {
                call_center_phone: {
                    required: "Nhập số điện thoại liên hệ",
                    integer: 'Số điện thoại không hợp lệ',
                },
                call_center_full_name: {
                    required: "Nhập tên người liên hệ"
                },
            },
        });
        if (!form.valid()) {
            return false;
        }
        var object_id = $('#call_center_object_id').val();
        var object_type = $('#call_center_object_type').val();
        var data = {
            call_center_note : $('#call_center_note').val(),
            call_center_customer_request_type : $('input[name="call_center_customer_request_type"]:checked').val(),
            object_id: object_id,
            object_type: object_type,
            call_center_phone: $('#call_center_phone').val(),
            call_center_full_name: $('#call_center_full_name').val(),
        };
        form.serializeArray().forEach(element => {
            switch (element['name']) {
                // case 'column_request_type':
                //     data['call_center_customer_request_type'] = $('input[name="call_center_customer_request_type"]:checked').val();
                //     break; 
                case 'custom_column_value_1':
                    data['custom_column_value_1'] = element['value'];
                    break;
                case 'custom_column_name_1':
                    data['custom_column_name_1'] = element['value'];
                    break;
                case 'object_data_type_1':
                    data['object_data_type_1'] = element['value'];
                    break;
                case 'custom_column_value_2':
                    data['custom_column_value_2'] = element['value'];
                    break;
                case 'custom_column_name_2':
                    data['custom_column_name_2'] = element['value'];
                    break;
                case 'object_data_type_2':
                    data['object_data_type_2'] = element['value'];
                    break;
                case 'custom_column_value_3':
                    data['custom_column_value_3'] = element['value'];
                    break;
                case 'custom_column_name_3':
                    data['custom_column_name_3'] = element['value'];
                    break;
                case 'object_data_type_3':
                    data['object_data_type_3'] = element['value'];
                    break;
                case 'custom_column_value_4':
                    data['custom_column_value_4'] = element['value'];
                    break;
                case 'custom_column_name_4':
                    data['custom_column_name_4'] = element['value'];
                    break;
                case 'object_data_type_4':
                    data['object_data_type_4'] = element['value'];
                    break;
                case 'custom_column_value_5':
                    data['custom_column_value_5'] = element['value'];
                    break;
                case 'custom_column_name_5':
                    data['custom_column_name_5'] = element['value'];
                    break;
                case 'object_data_type_5':
                    data['object_data_type_5'] = element['value'];
                    break;
                case 'custom_column_value_6':
                    data['custom_column_value_6'] = element['value'];
                    break;
                case 'custom_column_name_6':
                    data['custom_column_name_6'] = element['value'];
                    break;
                case 'object_data_type_6':
                    data['object_data_type_6'] = element['value'];
                    break;
                case 'custom_column_value_7':
                    data['custom_column_value_7'] = element['value'];
                    break;
                case 'custom_column_name_7':
                    data['custom_column_name_7'] = element['value'];
                    break;
                case 'object_data_type_7':
                    data['object_data_type_7'] = element['value'];
                    break;
                case 'custom_column_value_8':
                    data['custom_column_value_8'] = element['value'];
                    break;
                case 'custom_column_name_8':
                    data['custom_column_name_8'] = element['value'];
                    break;
                case 'object_data_type_8':
                    data['object_data_type_8'] = element['value'];
                    break;
                case 'custom_column_value_9':
                    data['custom_column_value_9'] = element['value'];
                    break;
                case 'custom_column_name_9':
                    data['custom_column_name_9'] = element['value'];
                    break;
                case 'object_data_type_9':
                    data['object_data_type_1'] = element['value'];
                    break;
                case 'custom_column_value_10':
                    data['custom_column_value_10'] = element['value'];
                    break;
                case 'custom_column_name_10':
                    data['custom_column_name_10'] = element['value'];
                    break;
                case 'object_data_type_10':
                    data['object_data_type_10'] = element['value'];
                    break;
              }
        });
        $.ajax({
            url: laroute.route('call-center.create-customer-request'),
            method: 'POST',
            dataType: 'JSON',
            data: data,
            success: function (res) {
                if(!res.error){
                    swal({
                        title:  res.message,
                        text: 'Redirecting...',
                        type: 'success',
                        timer: 1500,
                        showConfirmButton: false,
                    })
                    .then(() => {
                        $('#modal-info').modal('hide');
                        $('.modal-backdrop').remove();
                        callCenter.showModalCustomerInfoSuccess(res.id);
                        var url = window.location.href;
                        if(url.includes("dashbroad")){
                            statisticCustomer.getCustomerRequestToday();
                        }else if(url.includes("call-center")){
                            $('#autotable').PioTable('refresh');
                        }
                    });
                }else{
                    Swal.fire(
                        callCenter.jsontranslate['Thông Báo'],
                        res.message,
                        'error'
                    )
                }
            }
        });
    },

    loadJourney: function(e){
        $.ajax({
            url: laroute.route("call-center.load-option-journey"),
            dataType: "JSON",
            data: {
              pipeline_code: $(e).val(),
            },
            method: "POST",
            success: function (res) {
              $("#call_center_journey").empty();
              $.map(res.optionJourney, function (a) {
                $("#call_center_journey").append(
                  '<option value="' +
                    a.journey_code +
                    '">' +
                    a.journey_name +
                    "</option>"
                );
              });
            },
          });
    },

    changeProvince: function (e) {
        $.ajax({
            url: laroute.route("admin.customer.load-district"),
            dataType: "JSON",
            data: {
              id_province: $(e).val(),
            },
            method: "POST",
            success: function (res) {
              $("#call_center_district").empty();
              $("#call_center_district").append(
                "<option value=''" + callCenter.jsontranslate['Chọn Tỉnh/Thành phố'] + "</option>"
              );
              $.map(res.optionDistrict, function (a) {
                $("#call_center_district").append(
                  '<option value="' +
                    a.id +
                    '">' +
                    a.type +
                    " " +
                    a.name +
                    "</option>"
                );
              });
            },
        });
    },

    changeDistrict: function(e){
        $.ajax({
            url: laroute.route('admin.customer.load-ward'),
            dataType: 'JSON',
            data: {
                id_district: $(e).val(),
            },
            method: 'POST',
            success: function (res) {
                $('#call_center_ward').empty();
                $("#call_center_ward").append(
                    "<option value=''" + callCenter.jsontranslate['Chọn Quận/Huyện'] + "</option>"
                  );
                $.map(res.optionWard, function (a) {
                    $('#call_center_ward').append('<option value="' + a.id + '">' + a.type + ' ' + a.name + '</option>');
                });
            }
        });
    },

    showModalCreateLead: function (object_id, object_type) {
        var data = {
            'object_id' : object_id,
            'object_type' : object_type
        }
        $('#modal-call-center-search').empty();
        $('#modal-info').modal('hide');
        $('.modal-backdrop').remove();
        var type = 'lead';
        if(object_type == 'customer'){
            type = 'customer';
        }
        create.popupCreate(false,type,object_id,'call-center', data);
    },
}
function phonenumber(inputtxt)
{
    var phoneno = /^\d{10}$/;
    if(inputtxt.value.match(phoneno))
    {
        return true;
    }
    else
    {
        return false;
    }
}