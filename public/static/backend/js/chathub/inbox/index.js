var chathubInbox = {
  domain: null,
  iFrame: null,
  jsonLang: null,
  _init: function () {
    chathubInbox.jsonLang = JSON.parse(localStorage.getItem("tranlate"));
    chathubInbox.iFrame = document.getElementById("if_chathub_inbox");

    if (window.addEventListener) {
      window.addEventListener("message", chathubInbox.onMessage, false);
    } else if (window.attachEvent) {
      window.attachEvent("onmessage", chathubInbox.onMessage, false);
    }

    const bcCancelOrder = new BroadcastChannel("cancelOrder");
    bcCancelOrder.onmessage = (event) => {
      chathubInbox.cancelOrder(event.data);
    };

    const bc = new BroadcastChannel("addSuccessOrder");
    bc.onmessage = (event) => {
      chathubInbox.addSuccessOrder(event.data);
    };

    const bcCancelCustomer = new BroadcastChannel("cancelCustomer");
    bcCancelCustomer.onmessage = (event) => {
      chathubInbox.cancelCustomer(event.data);
    };

    const bcAddSuccessCustomer = new BroadcastChannel("addSuccessCustomer");
    bcAddSuccessCustomer.onmessage = (event) => {
      chathubInbox.addSuccessCustomer(event.data);
    };

    const bcEditSuccessCustomer = new BroadcastChannel("editSuccessCustomer");
    bcEditSuccessCustomer.onmessage = (event) => {
      chathubInbox.bcEditSuccessCustomer(event.data);
    };

    const bcEditSuccessCustomerLead = new BroadcastChannel("editSuccessCustomerLead");
    bcEditSuccessCustomerLead.onmessage = (event) => {
      chathubInbox.bcEditSuccessCustomerLead(event.data);
    };
  },

  onMessage: function (event) {
    // Check sender origin to be trusted
    // if (event.origin !== chathubInbox.domain) return;

    var data = event.data;
    if (typeof chathubInbox[data.func] === "function") {
      chathubInbox[data.func].call(null, data.message);
    }
  },

  cancelAppointment: function (data) {
    console.log("cancelAppointment");
    console.log(data);
    chathubInbox.iFrame.contentWindow.postMessage(
      {
        func: "cancelAppointment",
        message: {
          ch_customer_id: $("#fr_ch_customer_id").val(),
        },
      },
      "*"
    );
  },

  showAppointmentSchedule: function (data) {
    console.log("showAppointmentSchedule");
    console.log(data);

    $("#fr_phone").val(data.phone);
    $("#fr_ch_customer_id").val(data.ch_customer_id);

    customer_appointment.click_modal();
  },

  addSuccessAppointmentSchedule: function (data) {
    console.log("addSuccessAppointmentSchedule");
    console.log(data);
    Swal.fire({
      type: "success",
      title: chathubInbox.jsonLang["Thêm lịch hẹn thành công"],
      showConfirmButton: false,
      timer: 2000,
    }).then(() => {
      chathubInbox.iFrame.contentWindow.postMessage(
        {
          func: "addSuccessAppointmentSchedule",
          message: {
            ch_customer_id: $("#fr_ch_customer_id").val(),
            customer_appointment_id: data.customer_appointment_id,
            customer_appointment_code: data.customer_appointment_code,
            customer_phone: data.customer_phone,
            customer_name: data.customer_name,
            service_name: data.service_name,
            datetime_appointment: data.datetime_appointment,
            hour_appointment: data.hour_appointment,
            note: data.note,
          },
        },
        "*"
      );
    });
  },

  showAddCustomerLead: function (data) {
    console.log("showAddCustomerLead");
    console.log(data);

    $("#fr_full_name").val(data.full_name);
    $("#fr_ch_customer_id").val(data.ch_customer_id);

    customerDealCreate.popupCreateLead(false);
  },

  cancelCustomerLead: function (data) {
    console.log("cancelCustomerLead");
    console.log(data);

    chathubInbox.iFrame.contentWindow.postMessage(
      {
        func: "cancelCustomerLead",
        message: {
          ch_customer_id: $("#fr_ch_customer_id").val(),
        },
      },
      "*"
    );
  },

  addSuccessCustomerLead: function (data) {
    console.log("addSuccessCustomerLead");
    console.log(data);
    let finalData = {
      ch_customer_id: $("#fr_ch_customer_id").val(),
      customer_lead_id: data.customer_lead_id,
      customer_lead_code: data.customer_lead_code,
      customer_lead_full_name: data.full_name,
    };

    chathubInbox.iFrame.contentWindow.postMessage(
      {
        func: "addSuccessCustomerLead",
        message: finalData,
      },
      "*"
    );

    if ($("#show_pop_cus_lead").val() == 1) {
      $("#show_pop_cus_lead").val(0);
      chathubInbox.showAddCustomerDeal(finalData);
    }
  },

  cancelDeal: function (data) {
    console.log("cancelDeal");
    console.log(data);

    chathubInbox.iFrame.contentWindow.postMessage(
      {
        func: "cancelDeal",
        message: {
          ch_customer_id: $("#fr_ch_customer_id").val(),
        },
      },
      "*"
    );
  },

  showAddCustomerDeal: function (data) {
    // kiem tra neu chua tao lead thi show popup tao lead
    console.log("showAddCustomerDeal");
    console.log(data);
    $("#fr_customer_lead_id").val(data.customer_lead_id);
    $("#fr_customer_lead_code").val(data.customer_lead_code);
    $("#fr_customer_lead_full_name").val(data.customer_lead_full_name);
    $("#fr_customer_id").val(data.customer_id);
    $("#fr_customer_code").val(data.customer_code);
    $("#fr_customer_full_name").val(data.customer_full_name);
    $("#fr_ch_customer_id").val(data.ch_customer_id);
    $("#fr_full_name").val(data.full_name);

    if (!data.customer_lead_id && !data.customer_id) {
      $("#show_pop_cus_lead").val(1);
      chathubInbox.showAddCustomerLead(data);
      return;
    }

    customerDealCreate.popupCreate(false);
  },

  addSuccessCustomerDeal: function (data) {
    console.log("addSuccessCustomerDeal");
    console.log(data);

    chathubInbox.iFrame.contentWindow.postMessage(
      {
        func: "addSuccessCustomerDeal",
        message: {
          ch_customer_id: $("#fr_ch_customer_id").val(),
          dead_id: data.dead_id,
          dead_code: data.dead_code,
        },
      },
      "*"
    );
  },

  showAddCustomer: function (data) {
    console.log("showAddCustomer");
    console.log(data);
    var new_window = window.open(
      laroute.route("admin.customer.add", {
        view_mode: "chathub_popup",
        full_name: data.full_name,
        ch_customer_id: data.ch_customer_id,
      }),
      "_blank",
      "directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no"
    );
  },

  cancelCustomer: function (data) {
    console.log("cancelCustomer");
    console.log(data);

    chathubInbox.iFrame.contentWindow.postMessage(
      {
        func: "cancelCustomer",
        message: {
          ch_customer_id: $("#fr_ch_customer_id").val(),
        },
      },
      "*"
    );
  },

  addSuccessCustomer: function (data) {
    console.log("addSuccessCustomer");
    console.log(data);

    chathubInbox.iFrame.contentWindow.postMessage(
      {
        func: "addSuccessCustomer",
        message: {
          ch_customer_id: $("#fr_ch_customer_id").val(),
          customer_id: data.customer_id,
          customer_code: data.customer_code,
        },
      },
      "*"
    );
  },

  showAddOrder: function (data) {
    console.log("showAddOrder");
    console.log(data);

    $("#fr_customer_id").val(data.customer_id);
    $("#fr_ch_customer_id").val(data.ch_customer_id);

    let params = {
      view_mode: "chathub_popup",
      ch_customer_id: data.ch_customer_id,
      ch_new_cus: 0,
    };
    if (typeof data.customer_id != "undefined") {
      params.customer_id = data.customer_id;
    } else {
      params.ch_full_name = data.full_name;
      params.ch_new_cus = 1;
    }
    var new_window = window.open(
      laroute.route("admin.order.add", params),
      "_blank",
      "directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no"
    );
  },

  cancelOrder: function (data) {
    console.log("cancelOrder");
    console.log(data);

    chathubInbox.iFrame.contentWindow.postMessage(
      {
        func: "cancelOrder",
        message: {
          ch_customer_id: $("#fr_ch_customer_id").val(),
        },
      },
      "*"
    );
  },

  addSuccessOrder: function (data) {
    console.log("addSuccessOrder");
    console.log(data);

    chathubInbox.iFrame.contentWindow.postMessage(
      {
        func: "addSuccessOrder",
        message: {
          ch_customer_id: $("#fr_ch_customer_id").val(),
          order_id: data.order_id,
          order_code: data.order_code,
        },
      },
      "*"
    );
  },

  cancelWork: function (data) {
    chathubInbox.iFrame.contentWindow.postMessage(
      {
        func: "cancelWork",
        message: {
          ch_customer_id: $("#fr_ch_customer_id").val(),
        },
      },
      "*"
    );

    $(".note-children-container").remove();
  },

  showAddManagerWork: function (data) {
    console.log("showAddManagerWork");
    console.log(data);

    $("#fr_customer_id").val(data.customer_id);
    $("#fr_customer_lead_id").val(data.customer_lead_id);
    $("#fr_ch_customer_id").val(data.ch_customer_id);
    $("#fr_message_chat").val(data.content);

    WorkChild.showPopup();
  },

  addSuccessManagerWork: function (data) {
    console.log("addSuccessManagerWork");
    console.log(data);

    chathubInbox.iFrame.contentWindow.postMessage(
      {
        func: "addSuccessManagerWork",
        message: {
          ch_customer_id: $("#fr_ch_customer_id").val(),
          manage_work_id: data.manage_work_id,
          manage_work_code: data.manage_work_code,
        },
      },
      "*"
    );
  },

  showDetailCustomer: function (data) {
    console.log("showDetailCustomer");
    console.log(data);
    listLead._init();

    if (data.customer_id) {
      var new_window = window.open(
        laroute.route("admin.customer.detail", {
          view_mode: "chathub_popup",
          id: data.customer_id,
          ch_customer_id: data.ch_customer_id,
        }),
        "_blank",
        "directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no"
      );
    } else {
      listLead.detail(data.customer_lead_id);
    }
  },

  addSuccessConvertCustomer: function (data) {
    console.log("addSuccessConvertCustomer");
    console.log(data);

    chathubInbox.iFrame.contentWindow.postMessage(
      {
        func: "addSuccessOrder",
        message: {
          ch_customer_id: $("#fr_ch_customer_id").val(),
          order_id: data.order_id,
          order_code: data.order_code,
        },
      },
      "*"
    );
  },

  unlinkChCustomer: function (data) {
    $("#fr_chathub")
      .find("input")
      .each(function (key, obj) {
        $(obj).val("");
      });
    setTimeout(function () {
      $("#fr_ch_customer_id").val(data.ch_customer_id);
    }, 500);
  },

  showNumberNotiChatHub: function (data) {
    console.log("showNumberNotiChatHub:", data.number);
    // if (data.number > 0) {
    //   $(".chathub_inbox")
    //     .find(".noti-chathub")
    //     .html(data.number)
    //     .css("display", "block");
    // } else {
    //   $(".chathub_inbox")
    //     .find(".noti-chathub")
    //     .html("0")
    //     .css("display", "none");
    // }
  },

  showEditCustomerLead: function (data) {
    console.log("showEditCustomerLead");
    console.log(data);
    edit.popupEdit(data.customer_lead_id, false, 'chathub_popup');
  },

  showEditCustomer: function (data) {
    console.log("showEditCustomer");
    console.log(data);
    var new_window = window.open(
      laroute.route("admin.customer.edit", {
        view_mode: "chathub_popup",
        id: data.customer_id,
      }),
      "_blank",
      "directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no"
    );
  },

  editSuccessCustomer: function (data) {
    console.log("editSuccessCustomer");
    console.log(data);
    chathubInbox.iFrame.contentWindow.postMessage(
      {
        func: "editSuccessCustomer",
        message: {
          data : data
        },
      },
      "*"
    );
  },
  editSuccessCustomerLead: function (data) {
    console.log("editSuccessCustomerLead");
    console.log(data);
    chathubInbox.iFrame.contentWindow.postMessage(
      {
        func: "editSuccessCustomerLead",
        message: {
          data : data
        },
      },
      "*"
    );
  },

  showPopupRemind: function (data) {
    console.log("showPopupRemind");
    console.log(data);
    MyWork.showPopupRemind();
  },
};
