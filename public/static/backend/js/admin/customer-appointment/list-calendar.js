function removeDuplicateUsingSet(arr) {
  // let unique_array = Array.from(new Set(arr));
  // return unique_array;
  var uniqueNames = [];
  $.each(arr, function (i, el) {
    if ($.inArray(el, uniqueNames) === -1) uniqueNames.push(el);
  });
  return uniqueNames;
}

$(document).ready(function () {
  customer_appointment.jsonLang = JSON.parse(localStorage.getItem("tranlate"));
  $("#search").datepicker({
    language: "vi",
    orientation: "bottom left",
    todayHighlight: !0,
  });
});

var click_detail = {
  close: function () {
    $(".detail").css("display", "none");
  },
  save: function (id) {
    var time = $("#time_detail").val();
    var day = $("#search").val();
    var search_name = $("#search_name").val();
    $.ajax({
      url: laroute.route("admin.customer_appointment.submit-comfirm"),
      data: {
        id: id,
        time: time,
        day: day,
        search: search_name,
      },
      method: "POST",
      dataType: "JSON",
      success: function (res) {
        swal(
          customer_appointment.jsonLang["Xác nhận lịch hẹn thành công"],
          "",
          "success"
        );
        $("#timeline-list").empty();
        $("#timeline-list").append(res);
        $(".detail_btn_" + id + "").trigger("click");
      },
    });
  },
  detail_click: function (id) {
    $.ajax({
      url: laroute.route("admin.customer_appointment.detail-click"),
      data: {
        id: id,
      },
      method: "POST",
      dataType: "JSON",
      success: function (res) {
        $("#m-info-cus").empty();
        $("#m-info-cus").append(res);

        $("#time_detail").val($("#time_hide").val()).trigger("change");
        $("#staff_id").select2();
      },
    });
  },
  out_modal: function () {
    $.ajax({
      url: laroute.route(
        "admin.customer_appointment.remove-session-customer_id"
      ),
      dataType: "JSON",
      method: "GET",
      success: function (res) {},
    });
  },
};

var customer_appointment = {
  jsonLang: null,
  search_time: function (e) {
    $("#search_name").val("");
    $(e).datepicker("hide");
    var daySearch = $("#day_hidden").val($(e).val());
    $("#day_head").empty();
    $("#today").attr("class", "btn btn-info");
    $.ajax({
      url: laroute.route("admin.customer_appointment.search-time"),
      data: {
        day_search: daySearch.val(),
      },
      method: "POST",
      dataType: "JSON",
      success: function (res) {
        $("#timeline-list").empty();
        $("#timeline-list").append(res);
        $(".detail_btn_" + $(".id_appointment").val() + "").trigger("click");
        if ($("#null_day").text() != "") {
          $("#m-info-cus").empty();
        }
      },
    });
  },
  click_modal: function (date = null, customerId = null) {
    if (date == null) {
      date = $("#search").val();
    }

    $.ajax({
      url: laroute.route("admin.customer_appointment.modalAddTimeline"),
      dataType: "JSON",
      method: "POST",
      data: {
        date_now: date,
        customer_id: customerId,
      },
      success: function (res) {
        $("#show-modal").html(res.html);
        $("#show-modal").find("#modal-add").modal({
          backdrop: "static",
          keyboard: false,
        });

        var type = $(".source")
          .find('.active input[name="customer_appointment_type"]')
          .val();
        if (type == "appointment") {
          var tpl = $("#append-status-other-tpl").html();
          $(".append_status").append(tpl);
          var tpl_date = $("#date-tpl").html();
          $(".date_app").append(tpl_date);
          var tpl_time = $("#time-tpl").html();
          $(".time_app").append(tpl_time);
        } else {
          var tpl = $("#append-status-live-tpl").html();
          $(".append_status").append(tpl);
        }

        if (res.is_booking_past == 1) {
          $("#date, #end_date")
            .datepicker({
              language: "vi",
              orientation: "bottom left",
              todayHighlight: !0,
            })
            .on("changeDate", function (ev) {
              $(this).datepicker("hide");
            });
        } else {
          $("#date, #end_date")
            .datepicker({
              startDate: "0d",
              language: "vi",
              orientation: "bottom left",
              todayHighlight: !0,
            })
            .on("changeDate", function (ev) {
              $(this).datepicker("hide");
            });
        }

        $("#date").val(res.date_now);

        $("#time, #end_time").timepicker({
          minuteStep: 1,
          defaultTime: "",
          showMeridian: !1,
          snapToStep: !0,
        });
        $("#appointment_source_id, #time_type").select2();

        $(".room_id").select2({
          placeholder: customer_appointment.jsonLang["Chọn phòng"],
        });

        $(".staff_id").select2({
          placeholder: customer_appointment.jsonLang["Chọn nhân viên"],
        });
        $("#branch_id_modal")
        .select2({
          placeholder: customer_appointment.jsonLang["Chọn chi nhánh"],
        })
        .on("select2:select", function (event) {
          customer_appointment.getStaff(null);
        });
        $(".service_id")
          .select2({
            placeholder: customer_appointment.jsonLang["Chọn dịch vụ"],
          })
          .on("select2:select", function (event) {
            var id = $(this)
              .closest(".tr_quantity")
              .find('input[name="customer_order"]')
              .val();
            $("#room_id_" + id + "")
              .val("")
              .enable(true);
            $("#staff_id_" + id + "").enable(true);
          })
          .on("select2:unselect", function (event) {
            if ($(this).val() == "") {
              var id = $(this)
                .closest(".tr_quantity")
                .find('input[name="customer_order"]')
                .val();
              $("#room_id_" + id + "")
                .val("")
                .trigger("change")
                .enable(false);
              $("#staff_id_" + id + "")
                .val("")
                .trigger("change")
                .enable(false);
            }
          });

        $("#customer_group_id").select2({
          placeholder: customer_appointment.jsonLang["Chọn nhóm khách hàng"],
        });
        $("#HistoryAppointment").PioTable({
          baseUrl: laroute.route("admin.customer_appointment.list-history"),
        });
        $("#HistoryAppointment").PioTable("refresh");
        customer_appointment.getStaff();
      },
    });
  },
  //Update Gk ver 3
  new_click: function () {
    $("#new").attr("class", "btn btn-info color_button active");
    $("#confirm").attr("class", "btn btn-default");
    $("#processing").attr("class", "btn btn-default");
  },
  confirm_click: function () {
    $("#confirm").attr("class", "btn btn-info color_button active");
    $("#new").attr("class", "btn btn-default");
    $("#processing").attr("class", "btn btn-default");
  },
  processing_click: function () {
    $("#processing").attr("class", "btn btn-info  color_button active");
    $("#confirm").attr("class", "btn btn-default");
    $("#new").attr("class", "btn btn-default");
  },
  //End update Gk ver 3
  appointment: function (e) {
    $(e).attr("class", "btn btn-info color_button active");
    $("#direct").attr("class", "btn btn-default ");
    let name = customer_appointment.jsonLang["gọi điện"];
    let $element = $("#appointment_source_id");
    let val = $element.find("option:contains('" + name + "')").val();
    $("#appointment_source_id").val(val).trigger("change");
    //Trạng thái lịch hẹn
    $(".append_status").empty();
    var tpl = $("#append-status-other-tpl").html();
    $(".append_status").append(tpl);
  },
  direct: function (e) {
    $(e).attr("class", "btn btn-info color_button active");
    $("#appointment").attr("class", "btn btn-default");
    $("#appointment_source_id").val("1").trigger("change");
    //Trạng thái lịch hẹn
    $(".append_status").empty();
    var tpl = $("#append-status-live-tpl").html();
    $(".append_status").append(tpl);
  },
  add_customer: function () {
    var quantity = $("#quantity_customer").val();
    $("#quantity_customer").val(parseInt(quantity) + 1);
    var quan_hide = $("#quantity_hide").val();
    if ($("#quantity_customer").val() > quan_hide) {
      $.ajax({
        url: laroute.route("admin.customer_appointment.option"),
        dataType: "JSON",
        method: "POST",
        data: {},
        success: function (res) {
          // for (let i = 0; i < quantity - quan_hide; i++) {
          var stts = $("#table_quantity tr").length;
          var tpl = $("#table-quantity-tpl").html();
          tpl = tpl.replace(/{stt}/g, stts);
          tpl = tpl.replace(
            /{name}/g,
            customer_appointment.jsonLang["Khách "] + stts
          );
          $("#table_quantity > tbody").append(tpl);
          // }
          $.each(res.optionService, function (index, element) {
            $(".service_id").append(
              '<option value="' + index + '">' + element + "</option>"
            );
          });
          $.each(res.optionRoom, function (index, element) {
            $(".room_id").append(
              '<option value="' + index + '">' + element + "</option>"
            );
          });
          $.each(res.optionStaff, function (index, element) {
            $(".staff_id").append(
              '<option value="' + index + '">' + element + "</option>"
            );
          });
          $(".service_id")
            .select2({
              placeholder: customer_appointment.jsonLang["Chọn dịch vụ"],
            })
            .on("select2:select", function (event) {
              var id = $(this)
                .closest(".tr_quantity")
                .find('input[name="customer_order"]')
                .val();
              $("#room_id_" + id + "").enable(true);
              $("#staff_id_" + id + "").enable(true);
            })
            .on("select2:unselect", function (event) {
              if ($(this).val() == "") {
                var id = $(this)
                  .closest(".tr_quantity")
                  .find('input[name="customer_order"]')
                  .val();
                $("#room_id_" + id + "").enable(false);
                $("#staff_id_" + id + "").enable(false);
                $("#room_id_" + id + "")
                  .val("")
                  .trigger("change");
                $("#staff_id_" + id + "")
                  .val("")
                  .trigger("change");
              }
            });
          $(".staff_id").select2({
            placeholder: customer_appointment.jsonLang["Chọn nhân viên"],
          });
          $(".room_id").select2({
            placeholder: customer_appointment.jsonLang["Chọn phòng"],
          });
        },
      });
      $("#quantity_hide").val(quantity);
    }
    // $('#table_quantity > tbody').empty();
  },
  change_quantity: function (e) {
    $("#table_quantity > tbody").empty();
    if ($("#quantity_customer").val() > 10) {
      return false;
    }
    $.ajax({
      url: laroute.route("admin.customer_appointment.option"),
      dataType: "JSON",
      method: "POST",
      data: {},
      success: function (res) {
        for (let i = 0; i < $(e).val(); i++) {
          var stts = $("#table_quantity tr").length;

          var tpl = $("#table-quantity-tpl").html();
          tpl = tpl.replace(/{stt}/g, i + 1);
          tpl = tpl.replace(
            /{name}/g,
            customer_appointment.jsonLang["Khách "] + stts
          );
          $("#table_quantity > tbody").append(tpl);
        }
        $.each(res.optionService, function (index, element) {
          $(".service_id").append(
            '<option value="' + index + '">' + element + "</option>"
          );
        });
        $.each(res.optionRoom, function (index, element) {
          $(".room_id").append(
            '<option value="' + index + '">' + element + "</option>"
          );
        });
        $.each(res.optionStaff, function (index, element) {
          $(".staff_id").append(
            '<option value="' + index + '">' + element + "</option>"
          );
        });
        $(".service_id")
          .select2({
            placeholder: customer_appointment.jsonLang["Chọn dịch vụ"],
          })
          .on("select2:select", function (event) {
            var id = $(this)
              .closest(".tr_quantity")
              .find('input[name="customer_order"]')
              .val();
            $("#room_id_" + id + "").enable(true);
            $("#staff_id_" + id + "").enable(true);
          })
          .on("select2:unselect", function (event) {
            if ($(this).val() == "") {
              var id = $(this)
                .closest(".tr_quantity")
                .find('input[name="customer_order"]')
                .val();
              $("#room_id_" + id + "").enable(false);
              $("#staff_id_" + id + "").enable(false);
              $("#room_id_" + id + "")
                .val("")
                .trigger("change");
              $("#staff_id_" + id + "")
                .val("")
                .trigger("change");
            }
          });
        $(".staff_id").select2({
          placeholder: customer_appointment.jsonLang["Chọn nhân viên"],
        });
        $(".room_id").select2({
          placeholder: customer_appointment.jsonLang["Chọn phòng"],
        });
      },
    });
    $("#quantity_hide").val($(e).val() - 1);
  },
  click_modal_edit: function (id) {
    $.ajax({
      type: "POST",
      url: laroute.route("admin.customer_appointment.detail"),
      data: {
        id: id,
      },
      dataType: "JSON",
      success: function (res) {
        $("#show-modal").html(res.html);
        $("#show-modal").find("#modal-edit").modal({
          backdrop: "static",
          keyboard: false,
        });

        if (res.is_booking_past == 1) {
          $("#date, #end_date")
            .datepicker({
              language: "vi",
              orientation: "bottom left",
              todayHighlight: !0,
            })
            .on("changeDate", function (ev) {
              $(this).datepicker("hide");
            });
        } else {
          $("#date, #end_date")
            .datepicker({
              startDate: "0d",
              language: "vi",
              orientation: "bottom left",
              todayHighlight: !0,
            })
            .on("changeDate", function (ev) {
              $(this).datepicker("hide");
            });
        }

        $("#time, #end_time").timepicker({
          minuteStep: 1,
          defaultTime: "",
          showMeridian: !1,
          snapToStep: !0,
        });

        $(".room_id").select2({
          placeholder: customer_appointment.jsonLang["Chọn phòng"],
        });

        $(".staff_id").select2({
          placeholder: customer_appointment.jsonLang["Chọn nhân viên"],
        });

        $("#time_type").select2();
        $("#branch_id_modal")
        .select2({
          placeholder: customer_appointment.jsonLang["Chọn chi nhánh"],
        })
        .on("select2:select", function (event) {
          customer_appointment.getStaff($('#staff_id_old_1').val());
        });
        $(".service_id")
          .select2({
            placeholder: customer_appointment.jsonLang["Chọn dịch vụ"],
          })
          .on("select2:select", function (event) {
            var id = $(this)
              .closest(".tr_quantity")
              .find('input[name="customer_order"]')
              .val();
            $("#room_id_" + id + "")
              .val("")
              .enable(true);
            $("#staff_id_" + id + "").enable(true);
          })
          .on("select2:unselect", function (event) {
            if ($(this).val() == "") {
              var id = $(this)
                .closest(".tr_quantity")
                .find('input[name="customer_order"]')
                .val();
              $("#room_id_" + id + "")
                .val("")
                .trigger("change")
                .enable(false);
              $("#staff_id_" + id + "")
                .val("")
                .trigger("change")
                .enable(false);
            }
          });

        new AutoNumeric.multiple("#type_number", {
          currencySymbol: "",
          decimalCharacter: ".",
          digitGroupSeparator: ",",
          decimalPlaces: decimal_number,
          eventIsCancelable: true,
          minimumValue: 0,
        });
        $("#HistoryAppointmentEdit").PioTable({
          baseUrl: laroute.route("admin.customer_appointment.list-history"),
        });
        $("#HistoryAppointmentEdit").PioTable("refresh");
        customer_appointment.getStaff($('#staff_id_old_1').val());
      },
    });
  },
  //Update GK ver 3
  status_edit: function (e) {
    var $this = $(e).find('input[name="status"]').val();
    if ($this == "wait") {
      $("#new_stt").attr("class", "btn btn-default ");
      $("#confirm_stt").attr("class", "btn btn-default");
      $("#wait_stt").attr(
        "class",
        "btn btn-info  active_edit active color_button"
      );
      $("#cancel_stt").attr("class", "btn btn-default");
      $("#processing_stt").attr("class", "btn btn-default");
    } else if ($this == "cancel") {
      $("#new_stt").attr("class", "btn btn-default");
      $("#confirm_stt").attr("class", "btn btn-default");
      $("#wait_stt").attr("class", "btn btn-default ");
      $("#cancel_stt").attr(
        "class",
        "btn btn-info active_edit active color_button"
      );
      $("#processing_stt").attr("class", "btn btn-default");
    } else if ($this == "new") {
      $("#new_stt").attr(
        "class",
        "btn btn-info  active_edit active color_button"
      );
      $("#confirm_stt").attr("class", "btn btn-default ");
      $("#wait_stt").attr("class", "btn btn-default");
      $("#cancel_stt").attr("class", "btn btn-default");
      $("#processing_stt").attr("class", "btn btn-default");
    } else if ($this == "confirm") {
      $("#new_stt").attr("class", "btn btn-default");
      $("#confirm_stt").attr(
        "class",
        "btn btn-info active_edit active color_button"
      );
      $("#wait_stt").attr("class", "btn btn-default ");
      $("#cancel_stt").attr("class", "btn btn-default");
      $("#processing_stt").attr("class", "btn btn-default");
    } else if ($this == "processing") {
      $("#new_stt").attr("class", "btn btn-default");
      $("#confirm_stt").attr("class", "btn btn-default");
      $("#wait_stt").attr("class", "btn btn-default ");
      $("#cancel_stt").attr("class", "btn btn-default");
      $("#processing_stt").attr(
        "class",
        "btn btn-info active_edit active color_button"
      );
    }
  },
  //End Update
  add_customer_edit: function () {
    var quantity = $("#quantity_customer_edit").val();
    $("#quantity_customer_edit").val(parseInt(quantity) + 1);
    var quan_hide = $("#quantity_hide_edit").val();
    $("#quantity_hide_edit").val($("#quantity_customer_edit").val());
    $.ajax({
      url: laroute.route("admin.customer_appointment.option"),
      dataType: "JSON",
      method: "POST",
      data: {},
      success: function (res) {
        // for (let i = 0; i < $('#quantity_customer_edit').val() - quan_hide; i++) {
        var stts = $("#table_quantity_edit tr").length;
        var tpl = $("#table-quantity-edit-tpl").html();
        tpl = tpl.replace(/{stt}/g, stts);
        tpl = tpl.replace(
          /{name}/g,
          customer_appointment.jsonLang["Khách "] + stts
        );
        $("#table_quantity_edit > tbody").append(tpl);
        // }
        $.each(res.optionService, function (index, element) {
          $(".service_id_edit").append(
            '<option value="' + index + '">' + element + "</option>"
          );
        });
        $.each(res.optionRoom, function (index, element) {
          $(".room_id_edit").append(
            '<option value="' + index + '">' + element + "</option>"
          );
        });
        $.each(res.optionStaff, function (index, element) {
          $(".staff_id_edit").append(
            '<option value="' + index + '">' + element + "</option>"
          );
        });
        $(".service_id_edit")
          .select2({
            placeholder: customer_appointment.jsonLang["Chọn dịch vụ"],
          })
          .on("select2:select", function (event) {
            var id = $(this)
              .closest(".tr_quantity")
              .find('input[name="customer_order_edit"]')
              .val();
            $("#room_id_edit_" + id + "").enable(true);
            $("#staff_id_edit_" + id + "").enable(true);
          })
          .on("select2:unselect", function (event) {
            if ($(this).val() == "") {
              var id = $(this)
                .closest(".tr_quantity")
                .find('input[name="customer_order_edit"]')
                .val();
              $("#room_id_edit_" + id + "").enable(false);
              $("#staff_id_edit_" + id + "").enable(false);
              $("#room_id_edit_" + id + "")
                .val("")
                .trigger("change");
              $("#staff_id_edit_" + id + "")
                .val("")
                .trigger("change");
            }
          });
        $(".staff_id_edit").select2({
          placeholder: customer_appointment.jsonLang["Chọn nhân viên"],
          allowClear: true,
        });
        $(".room_id_edit").select2({
          placeholder: customer_appointment.jsonLang["Chọn phòng"],
          allowClear: true,
        });
      },
    });
  },
  change_quantity_edit: function (e) {
    if ($(e).val() > $("#quantity_hide_edit").val()) {
      // $('#table_quantity > tbody').empty();
      $.ajax({
        url: laroute.route("admin.customer_appointment.option"),
        dataType: "JSON",
        method: "POST",
        data: {},
        success: function (res) {
          for (
            let i = 0;
            i < $(e).val() - $("#quantity_hide_edit").val();
            i++
          ) {
            var stts = $("#table_quantity_edit tr").length;
            var tpl = $("#table-quantity-edit-tpl").html();
            tpl = tpl.replace(/{stt}/g, stts);
            tpl = tpl.replace(
              /{name}/g,
              customer_appointment.jsonLang["Khách "] + stts
            );
            $("#table_quantity_edit > tbody").append(tpl);
          }
          $.each(res.optionService, function (index, element) {
            $(".service_id_edit").append(
              '<option value="' + index + '">' + element + "</option>"
            );
          });
          $.each(res.optionRoom, function (index, element) {
            $(".room_id_edit").append(
              '<option value="' + index + '">' + element + "</option>"
            );
          });
          $.each(res.optionStaff, function (index, element) {
            $(".staff_id_edit").append(
              '<option value="' + index + '">' + element + "</option>"
            );
          });
          $(".service_id_edit")
            .select2({
              placeholder: customer_appointment.jsonLang["Chọn dịch vụ"],
            })
            .on("select2:select", function (event) {
              var id = $(this)
                .closest(".tr_quantity")
                .find('input[name="customer_order_edit"]')
                .val();
              $("#room_id_edit_" + id + "").enable(true);
              $("#staff_id_edit_" + id + "").enable(true);
            })
            .on("select2:unselect", function (event) {
              if ($(this).val() == "") {
                var id = $(this)
                  .closest(".tr_quantity")
                  .find('input[name="customer_order_edit"]')
                  .val();
                $("#room_id_edit_" + id + "").enable(false);
                $("#staff_id_edit_" + id + "").enable(false);
                $("#room_id_edit_" + id + "")
                  .val("")
                  .trigger("change");
                $("#staff_id_edit_" + id + "")
                  .val("")
                  .trigger("change");
              }
            });
          $(".staff_id_edit").select2({
            placeholder: customer_appointment.jsonLang["Chọn nhân viên"],
          });
          $(".room_id_edit").select2({
            placeholder: customer_appointment.jsonLang["Chọn phòng"],
          });
          $("#quantity_hide_edit").val($(e).val());
        },
      });
    } else {
      $("#quantity_hide_edit").val($(e).val());
      $("#table_quantity_edit > tbody").empty();
      $.ajax({
        url: laroute.route("admin.customer_appointment.option"),
        dataType: "JSON",
        method: "POST",
        data: {},
        success: function (res) {
          for (let i = 0; i < $(e).val(); i++) {
            var stts = $("#table_quantity_edit tr").length;
            var tpl = $("#table-quantity-edit-tpl").html();
            tpl = tpl.replace(/{stt}/g, stts);
            tpl = tpl.replace(
              /{name}/g,
              customer_appointment.jsonLang["Khách "] + stts
            );
            $("#table_quantity_edit > tbody").append(tpl);
          }
          $.each(res.optionService, function (index, element) {
            $(".service_id_edit").append(
              '<option value="' + index + '">' + element + "</option>"
            );
          });
          $.each(res.optionRoom, function (index, element) {
            $(".room_id_edit").append(
              '<option value="' + index + '">' + element + "</option>"
            );
          });
          $.each(res.optionStaff, function (index, element) {
            $(".staff_id_edit").append(
              '<option value="' + index + '">' + element + "</option>"
            );
          });
          $(".service_id_edit")
            .select2({
              placeholder: customer_appointment.jsonLang["Chọn dịch vụ"],
            })
            .on("select2:select", function (event) {
              var id = $(this)
                .closest(".tr_quantity")
                .find('input[name="customer_order_edit"]')
                .val();
              $("#room_id_edit_" + id + "").enable(true);
              $("#staff_id_edit_" + id + "").enable(true);
            })
            .on("select2:unselect", function (event) {
              if ($(this).val() == "") {
                var id = $(this)
                  .closest(".tr_quantity")
                  .find('input[name="customer_order_edit"]')
                  .val();
                $("#room_id_edit_" + id + "").enable(false);
                $("#staff_id_edit_" + id + "").enable(false);
                $("#room_id_edit_" + id + "")
                  .val("")
                  .trigger("change");
                $("#staff_id_edit_" + id + "")
                  .val("")
                  .trigger("change");
              }
            });
          $(".staff_id_edit").select2({
            placeholder: customer_appointment.jsonLang["Chọn nhân viên"],
          });
          $(".room_id_edit").select2({
            placeholder: customer_appointment.jsonLang["Chọn phòng"],
          });
        },
      });
    }
  },
  submit_edit: function () {
    var form = $("#form-edit");

    form.validate({
      rules: {
        time_edit: {
          required: true,
        },
        date_edit: {
          required: true,
        },
        quantity_customer_edit: {
          min: 1,
          required: true,
          number: true,
          max: 10,
        },
        end_date: {
          required: true,
        },
        end_time: {
          required: true,
        },
      },
      messages: {
        time_edit: {
          required: customer_appointment.jsonLang["Hãy chọn giờ hẹn"],
        },
        date_edit: {
          required: customer_appointment.jsonLang["Hãy chọn ngày hẹn"],
        },
        quantity_customer_edit: {
          min: customer_appointment.jsonLang["Số lượng khách hàng tối thiểu 1"],
          required:
            customer_appointment.jsonLang["Hãy nhập số lượng khách hàng"],
          number:
            customer_appointment.jsonLang["Số lượng khách hàng không hợp lệ"],
          max: customer_appointment.jsonLang["Số lượng khách hàng tối đa 10"],
        },
        end_date: {
          required: customer_appointment.jsonLang["Hãy chọn ngày kết thúc"],
        },
        end_time: {
          required: customer_appointment.jsonLang["Hãy chọn giờ kết thúc"],
        },
      },
    });

    if (!form.valid()) {
      return false;
    }

    var time_edit = $("#time").val();
    var date_edit = $("#date").val();
    var customer_appointment_id = $("#customer_appointment_id").val();
    var customer_appointment_type = $("#customer_appointment_type").val();
    var description = $("#description_edit").val();
    var customer_quantity = $("#quantity_customer_edit").val();
    var status = $(".active_edit ").find('input[name="status"]').val();
    var table_quantity = [];
    $.each($("#table_quantity_edit").find(".tr_quantity"), function () {
      var stt = $(this).find("input[name='customer_order']").val();
      var sv = "";
      if ($("#service_id_" + stt + "").val() != "") {
        sv = $("#service_id_" + stt + "").val();
      }
      var arr = {
        stt: stt,
        sv: sv,
        staff: $("#staff_id_" + stt + "").val(),
        room: $("#room_id_" + stt + "").val(),
        object_type: $(this).find("input[name='object_type']").val(),
        staff_old: $("#staff_id_old_" + stt + "").val(),
      };
      table_quantity.push(arr);
    });
    $.ajax({
      url: laroute.route("admin.customer_appointment.submitModalEdit"),
      dataType: "JSON",
      method: "POST",
      data: {
        date: date_edit,
        time: time_edit,
        customer_appointment_id: customer_appointment_id,
        customer_appointment_type: customer_appointment_type,
        customer_quantity: customer_quantity,
        status: status,
        table_quantity: table_quantity,
        time_edit_new: $("#time_edit_new").val(),
        customer_id: $("#customer_id").val(),
        discount: $("#discount").val(),
        endDate: $("#end_date").val(),
        endTime: $("#end_time").val(),
        time_type: $("#time_type").val(),
        type_number: $("#type_number").val(),
        branch_id: $("#branch_id_modal").val(),
        description: $("#description").val(),
      },
      success: function (res) {
        if (res.error == false) {
          swal(
            customer_appointment.jsonLang["Cập nhật lịch hẹn thành công"],
            "",
            "success"
          );
          $("#modal-edit").modal("hide");
          window.location.reload();
        } else {
          swal(res.message, "", "error");
        }
      },
    });
  },
  out_modal: function () {
    $.ajax({
      url: laroute.route(
        "admin.customer_appointment.remove-session-customer_id"
      ),
      dataType: "JSON",
      method: "GET",
      success: function (res) {
        window.location.reload();
      },
    });
  },
  chooseCustomer: function (obj) {
    $.ajax({
      url: laroute.route("admin.customer_appointment.search-phone"),
      dataType: "JSON",
      method: "POST",
      data: {
        phone: $(obj).val(),
      },
      success: function (res) {
        var arr = [];
        $.map(res.list_phone, function (a) {
          arr.push(a.phone);
        });
        $("#phone1").autocomplete({
          source: arr,
          change: function () {
            var phone = $(this).val();
            $.ajax({
              url: laroute.route("admin.customer_appointment.cus-phone"),
              dataType: "JSON",
              method: "post",
              data: {
                phone: phone,
              },
              success: function (res) {
                if (res.success == 1) {
                  $("#customer_hidden").val(res.cus.customer_id);
                  $("#full_name").val(res.cus.full_name);
                  $("#full_name").attr("disabled", true);
                  $("#customer_group_id")
                    .val(res.cus.customer_group_id)
                    .trigger("change")
                    .attr("disabled", true);
                  $("#HistoryAppointment").PioTable({
                    baseUrl: laroute.route(
                      "admin.customer_appointment.list-history"
                    ),
                  });
                  $("#HistoryAppointment").PioTable("refresh");
                }
                if (res.phone_new == 1) {
                  $("#customer_hidden").val("");
                  $("#full_name").val("");
                  $("#full_name").attr("disabled", false);
                  $("#customer_group_id")
                    .val("")
                    .trigger("change")
                    .attr("disabled", false);
                  $("#lstHistoryAppointment").html(
                    customer_appointment.jsonLang["Không có lịch hẹn nào"]
                  );
                }

                $("#table_quantity > tbody").find(".tr_card").remove();
                //Lấy list thẻ liệu trình
                if (res.numberMemberCard > 0) {
                  var tpl = $("#table-card-tpl").html();
                  tpl = tpl.replace(/{stt}/g, 2);
                  tpl = tpl.replace(
                    /{name}/g,
                    customer_appointment.jsonLang["Thẻ liệu trình"]
                  );
                  tpl = tpl.replace(/{type}/g, "member_card");
                  $("#table_quantity > tbody").append(tpl);

                  $("#service_id_2")
                    .select2({
                      placeholder:
                        customer_appointment.jsonLang["Chọn thẻ liệu trình"],
                    })
                    .on("select2:select", function (event) {
                      $("#room_id_2").enable(true);
                      $("#staff_id_2").enable(true);
                    })
                    .on("select2:unselect", function (event) {
                      if ($(this).val() == "") {
                        var id = $(this)
                          .closest(".tr_quantity")
                          .find('input[name="customer_order"]')
                          .val();
                        $("#room_id_" + id + "")
                          .val("")
                          .trigger("change")
                          .enable(false);
                        $("#staff_id_" + id + "")
                          .val("")
                          .trigger("change")
                          .enable(false);
                      }
                    });

                  $(".room_id").select2({
                    placeholder: customer_appointment.jsonLang["Chọn phòng"],
                  });

                  $(".staff_id").select2({
                    placeholder:
                      customer_appointment.jsonLang["Chọn nhân viên"],
                  });

                  $.map(res.listCard, function (v) {
                    $("#service_id_2").append(
                      '<option value="' +
                        v.customer_service_card_id +
                        '">' +
                        v.card_name +
                        "</option>"
                    );
                  });

                  $.map(res.optionStaff, function (v, k) {
                    $("#staff_id_2").append(
                      '<option value="' + k + '">' + v + "</option>"
                    );
                  });

                  $.map(res.optionRoom, function (v, k) {
                    $("#room_id_2").append(
                      '<option value="' + k + '">' + v + "</option>"
                    );
                  });
                }
              },
            });
          },
          select: function (event, ui) {
            var value = ui.item.value;
            $.ajax({
              url: laroute.route("admin.customer_appointment.cus-phone"),
              dataType: "JSON",
              method: "post",
              data: {
                phone: value,
              },
              success: function (res) {
                if (res.success == 1) {
                  $("#customer_hidden").val(res.cus.customer_id);
                  $("#full_name").val(res.cus.full_name);
                  $("#full_name").attr("disabled", true);
                  $("#customer_group_id")
                    .val(res.cus.customer_group_id)
                    .trigger("change")
                    .attr("disabled", true);
                  $("#HistoryAppointmentEdit").PioTable({
                    baseUrl: laroute.route(
                      "admin.customer_appointment.list-history"
                    ),
                  });
                  $("#HistoryAppointmentEdit").PioTable("refresh");
                  $("#HistoryAppointment").PioTable({
                    baseUrl: laroute.route(
                      "admin.customer_appointment.list-history"
                    ),
                  });
                  $("#HistoryAppointment").PioTable("refresh");
                }

                $("#table_quantity > tbody").find(".tr_card").remove();
                //Lấy list thẻ liệu trình
                if (res.numberMemberCard > 0) {
                  var tpl = $("#table-card-tpl").html();
                  tpl = tpl.replace(/{stt}/g, 2);
                  tpl = tpl.replace(
                    /{name}/g,
                    customer_appointment.jsonLang["Thẻ liệu trình"]
                  );
                  tpl = tpl.replace(/{type}/g, "member_card");
                  $("#table_quantity > tbody").append(tpl);

                  $("#service_id_2")
                    .select2({
                      placeholder:
                        customer_appointment.jsonLang["Chọn thẻ liệu trình"],
                    })
                    .on("select2:select", function (event) {
                      $("#room_id_2").enable(true);
                      $("#staff_id_2").enable(true);
                    })
                    .on("select2:unselect", function (event) {
                      if ($(this).val() == "") {
                        var id = $(this)
                          .closest(".tr_quantity")
                          .find('input[name="customer_order"]')
                          .val();
                        $("#room_id_" + id + "")
                          .val("")
                          .trigger("change")
                          .enable(false);
                        $("#staff_id_" + id + "")
                          .val("")
                          .trigger("change")
                          .enable(false);
                      }
                    });

                  $(".room_id").select2({
                    placeholder: customer_appointment.jsonLang["Chọn phòng"],
                  });

                  $(".staff_id").select2({
                    placeholder:
                      customer_appointment.jsonLang["Chọn nhân viên"],
                  });

                  $.map(res.listCard, function (v) {
                    $("#service_id_2").append(
                      '<option value="' +
                        v.customer_service_card_id +
                        '">' +
                        v.card_name +
                        "</option>"
                    );
                  });

                  $.map(res.optionStaff, function (v, k) {
                    $("#staff_id_2").append(
                      '<option value="' + k + '">' + v + "</option>"
                    );
                  });

                  $.map(res.optionRoom, function (v, k) {
                    $("#room_id_2").append(
                      '<option value="' + k + '">' + v + "</option>"
                    );
                  });
                }
              },
            });
          },
        });
      },
    });
  },
  addNew: function () {
    var form = $("#form-add");
    form.validate({
      rules: {
        full_name: {
          required: true,
        },
        phone1: {
          required: true,
          minlength: 10,
          maxlength: 11,
          number: true,
        },
        customer_group_id: {
          required: true,
        },
        time: {
          required: true,
        },
        date: {
          required: true,
        },
        quantity_customer: {
          min: 1,
          required: true,
          number: true,
          max: 10,
        },
        end_date: {
          required: true,
        },
        end_time: {
          required: true,
        },
      },
      messages: {
        full_name: {
          required: customer_appointment.jsonLang["Hãy nhập tên khách hàng"],
        },
        phone1: {
          required: customer_appointment.jsonLang["Hãy nhập số điện thoại"],
          minlength:
            customer_appointment.jsonLang["Số điện thoại tối thiểu 10 số"],
          maxlength:
            customer_appointment.jsonLang["Số điện thoại tối đa 11 số"],
          number: customer_appointment.jsonLang["Số điện thoại không hợp lệ"],
        },
        customer_group_id: {
          required: customer_appointment.jsonLang["Chọn nhóm khách hàng"],
        },
        time: {
          required: customer_appointment.jsonLang["Hãy chọn giờ hẹn"],
        },
        date: {
          required: customer_appointment.jsonLang["Hãy chọn ngày hẹn"],
        },
        quantity_customer: {
          min: customer_appointment.jsonLang["Số lượng khách hàng tối thiểu 1"],
          required:
            customer_appointment.jsonLang["Hãy nhập số lượng khách hàng"],
          number:
            customer_appointment.jsonLang["Số lượng khách hàng không hợp lệ"],
          max: customer_appointment.jsonLang["Số lượng khách hàng tối đa 10"],
        },
        end_date: {
          required: customer_appointment.jsonLang["Hãy chọn ngày kết thúc"],
        },
        end_time: {
          required: customer_appointment.jsonLang["Hãy chọn giờ kết thúc"],
        },
      },
    });

    if (!form.valid()) {
      return false;
    }

    var full_name = $("#full_name").val();
    var phone1 = $("#phone1").val();
    var type = $(".source")
      .find('.active input[name="customer_appointment_type"]')
      .val();
    var date = $("#date").val();
    var time = $("#time").val();
    var customer_hidden = $("#customer_hidden").val();
    var description = $("#description").val();
    var customer_quantity = $("#quantity_customer").val();
    var appointment_source_id = $("#appointment_source_id").val();
    // var customer_refer = $('#search_refer').val();
    var status = $(".active").find(' input[name="status"]').val();
    var table_quantity = [];
    $.each($("#table_quantity").find(".tr_quantity"), function () {
      var stt = $(this).find("input[name='customer_order']").val();
      var sv = "";
      if ($("#service_id_" + stt + "").val() != "") {
        sv = $("#service_id_" + stt + "").val();
      }
      var arr = {
        stt: stt,
        sv: sv,
        staff: $("#staff_id_" + stt + "").val(),
        room: $("#room_id_" + stt + "").val(),
        object_type: $(this).find("input[name='object_type']").val(),
      };
      table_quantity.push(arr);
    });
    //end_date, end_time
    var endDate = $("#end_date").val();
    var endTime = $("#end_time").val();
    //kiểm tra khách hàng đã có lịch hẹn ngày hôm nay chưa
    if (customer_hidden != "") {
      $.ajax({
        url: laroute.route(
          "admin.customer_appointment.check-number-appointment"
        ),
        method: "POST",
        dataType: "JSON",
        data: {
          customer_id: customer_hidden,
          date: date,
          time: time,
          endDate: endDate,
          endTime: endTime,
          time_type: $("#time_type").val(),
          type_number: $("#type_number").val(),
        },
        success: function (res) {
          mApp.unblock("#m_blockui_1_content");
          if (res.status == -1) {
            swal.fire(res.message, "", "error");
          }
          if (res.time_error == 1) {
            $(".error_time").text(
              customer_appointment.jsonLang["Ngày hẹn, giờ hẹn không hợp lệ"]
            );
          }
          if (res.status == 0) {
            addLoad(
              full_name,
              phone1,
              type,
              appointment_source_id,
              customer_quantity,
              date,
              time,
              customer_hidden,
              description,
              table_quantity,
              status,
              endDate,
              endTime
            );
          }
          if (res.status == 1) {
            if (res.number < 3) {
              swal({
                title:
                  customer_appointment.jsonLang[
                    "Khách đã có lịch hẹn hôm nay lúc"
                  ] +
                  " " +
                  res.time,
                text: "",
                type: "warning",
                showCancelButton: !0,
                confirmButtonText: customer_appointment.jsonLang["THÊM MỚI"],
                cancelButtonText: customer_appointment.jsonLang["CẬP NHẬT"],
              });
              $(".swal2-confirm").click(function () {
                addLoad(
                  full_name,
                  phone1,
                  type,
                  appointment_source_id,
                  customer_quantity,
                  date,
                  time,
                  customer_hidden,
                  description,
                  table_quantity,
                  status,
                  endDate,
                  endTime
                );
              });
              $(".swal2-cancel").click(function () {
                updateLoad(
                  customer_hidden,
                  date,
                  time,
                  type,
                  status,
                  appointment_source_id,
                  description,
                  customer_quantity,
                  table_quantity,
                  endDate,
                  endTime
                );
              });
            } else {
              swal({
                title:
                  customer_appointment.jsonLang[
                    "Khách hàng đã đặt tối đa 3 lịch hẹn trong hôm nay"
                  ],
                text: "",
                type: "warning",
                confirmButtonText:
                  customer_appointment.jsonLang["Cập nhật lịch gần nhất"],
                confirmButtonClass:
                  "btn btn-focus m-btn m-btn--pill m-btn--air",
              });
              $(".swal2-confirm").click(function () {
                updateLoad(
                  customer_hidden,
                  date,
                  time,
                  type,
                  status,
                  appointment_source_id,
                  description,
                  customer_quantity,
                  table_quantity,
                  endDate,
                  endTime
                );
              });
            }
          }
        },
      });
    } else {
      addLoad(
        full_name,
        phone1,
        type,
        appointment_source_id,
        customer_quantity,
        date,
        time,
        customer_hidden,
        description,
        table_quantity,
        status,
        endDate,
        endTime
      );
    }
  },
  addNewContinued: function () {
    var form = $("#form-add");

    form.validate({
      rules: {
        full_name: {
          required: true,
        },
        phone1: {
          required: true,
          minlength: 10,
          maxlength: 11,
          number: true,
        },
        customer_group_id: {
          required: true,
        },
        time: {
          required: true,
        },
        date: {
          required: true,
        },
        quantity_customer: {
          min: 1,
          required: true,
          number: true,
          max: 10,
        },
        end_date: {
          required: true,
        },
        end_time: {
          required: true,
        },
      },
      messages: {
        full_name: {
          required: customer_appointment.jsonLang["Hãy nhập tên khách hàng"],
        },
        phone1: {
          required: customer_appointment.jsonLang["Hãy nhập số điện thoại"],
          minlength:
            customer_appointment.jsonLang["Số điện thoại tối thiểu 10 số"],
          maxlength:
            customer_appointment.jsonLang["Số điện thoại tối đa 11 số"],
          number: customer_appointment.jsonLang["Số điện thoại không hợp lệ"],
        },
        customer_group_id: {
          required: customer_appointment.jsonLang["Chọn nhóm khách hàng"],
        },
        time: {
          required: customer_appointment.jsonLang["Hãy chọn giờ hẹn"],
        },
        date: {
          required: customer_appointment.jsonLang["Hãy chọn ngày hẹn"],
        },
        quantity_customer: {
          min: customer_appointment.jsonLang["Số lượng khách hàng tối thiểu 1"],
          required:
            customer_appointment.jsonLang["Hãy nhập số lượng khách hàng"],
          number:
            customer_appointment.jsonLang["Số lượng khách hàng không hợp lệ"],
          max: customer_appointment.jsonLang["Số lượng khách hàng tối đa 10"],
        },
        end_date: {
          required: customer_appointment.jsonLang["Hãy chọn ngày kết thúc"],
        },
        end_time: {
          required: customer_appointment.jsonLang["Hãy chọn giờ kết thúc"],
        },
      },
    });

    if (!form.valid()) {
      return false;
    }

    var full_name = $("#full_name").val();
    var phone1 = $("#phone1").val();
    var type = $(".source")
      .find('.active input[name="customer_appointment_type"]')
      .val();
    var date = $("#date").val();
    var time = $("#time").val();
    var customer_hidden = $("#customer_hidden").val();
    var description = $("#description").val();
    var customer_quantity = $("#quantity_customer").val();
    var appointment_source_id = $("#appointment_source_id").val();
    // var customer_refer = $('#search_refer').val();
    var status = $(".active").find(' input[name="status"]').val();
    var table_quantity = [];
    $.each($("#table_quantity").find(".tr_quantity"), function () {
      var stt = $(this).find("input[name='customer_order']").val();
      var sv = "";
      if ($("#service_id_" + stt + "").val() != "") {
        sv = $("#service_id_" + stt + "").val();
      }
      var arr = {
        stt: stt,
        sv: sv,
        staff: $("#staff_id_" + stt + "").val(),
        room: $("#room_id_" + stt + "").val(),
        object_type: $(this).find("input[name='object_type']").val(),
      };
      table_quantity.push(arr);
    });
    //end_date, end_time
    var endDate = $("#end_date").val();
    var endTime = $("#end_time").val();
    //kiểm tra khách hàng đã có lịch hẹn ngày hôm nay chưa
    if (customer_hidden != "") {
      $.ajax({
        url: laroute.route(
          "admin.customer_appointment.check-number-appointment"
        ),
        method: "POST",
        dataType: "JSON",
        data: {
          customer_id: customer_hidden,
          date: date,
          time: time,
          endDate: endDate,
          endTime: endTime,
          time_type: $("#time_type").val(),
          type_number: $("#type_number").val(),
        },
        success: function (res) {
          // mApp.unblock("#m_blockui_1_content");
          if (res.status == -1) {
            swal.fire(res.message, "", "error");
          }
          if (res.time_error == 1) {
            $(".error_time").text(
              customer_appointment.jsonLang["Ngày hẹn, giờ hẹn không hợp lệ"]
            );
          }
          if (res.status == 0) {
            addReset(
              full_name,
              phone1,
              type,
              appointment_source_id,
              customer_quantity,
              date,
              time,
              customer_hidden,
              description,
              table_quantity,
              status,
              endDate,
              endTime
            );
          }
          if (res.status == 1) {
            if (res.number < 3) {
              swal({
                title:
                  customer_appointment.jsonLang[
                    "Khách đã có lịch hẹn hôm nay lúc"
                  ] +
                  " " +
                  res.time,
                text: "",
                type: "warning",
                showCancelButton: !0,
                confirmButtonText: customer_appointment.jsonLang["THÊM MỚI"],
                cancelButtonText: customer_appointment.jsonLang["CẬP NHẬT"],
              });
              $(".swal2-confirm").click(function () {
                addReset(
                  full_name,
                  phone1,
                  type,
                  appointment_source_id,
                  customer_quantity,
                  date,
                  time,
                  customer_hidden,
                  description,
                  table_quantity,
                  status,
                  endDate,
                  endTime
                );
              });
              $(".swal2-cancel").click(function () {
                updateReset(
                  customer_hidden,
                  date,
                  time,
                  type,
                  status,
                  appointment_source_id,
                  description,
                  customer_quantity,
                  table_quantity,
                  endDate,
                  endTime
                );
              });
            } else {
              swal({
                title:
                  customer_appointment.jsonLang[
                    "Khách hàng đã đặt tối đa 3 lịch hẹn trong hôm nay"
                  ],
                text: "",
                type: "warning",
                confirmButtonText:
                  customer_appointment.jsonLang["Cập nhật lịch gần nhất"],
                confirmButtonClass:
                  "btn btn-focus m-btn m-btn--pill m-btn--air",
              });
              $(".swal2-confirm").click(function () {
                updateReset(
                  customer_hidden,
                  date,
                  time,
                  type,
                  status,
                  appointment_source_id,
                  description,
                  customer_quantity,
                  table_quantity,
                  endDate,
                  endTime
                );
              });
            }
          }
        },
      });
    } else {
      addReset(
        full_name,
        phone1,
        type,
        appointment_source_id,
        customer_quantity,
        date,
        time,
        customer_hidden,
        description,
        table_quantity,
        status,
        endDate,
        endTime
      );
    }
  },
  changeTimeType: function (obj) {
    $(".time_type").empty();

    if ($(obj).val() == "R") {
      //Theo ngày
      var tpl = $("#to-date-tpl").html();
      $(".time_type").append(tpl);

      if ($("#is_booking_past").val() == 1) {
        $("#date, #end_date")
          .datepicker({
            language: "vi",
            orientation: "bottom left",
            todayHighlight: !0,
          })
          .on("changeDate", function (ev) {
            $(this).datepicker("hide");
          });
      } else {
        $("#date, #end_date")
          .datepicker({
            startDate: "0d",
            language: "vi",
            orientation: "bottom left",
            todayHighlight: !0,
          })
          .on("changeDate", function (ev) {
            $(this).datepicker("hide");
          });
      }

      $("#time, #end_time").timepicker({
        minuteStep: 1,
        defaultTime: "",
        showMeridian: !1,
        snapToStep: !0,
      });
    } else {
      //Theo tuần, tháng, năm
      var tpl = $("#w-m-y-tpl").html();
      $(".time_type").append(tpl);

      if ($("#is_booking_past").val() == 1) {
        $("#end_date")
          .datepicker({
            language: "vi",
            orientation: "bottom left",
            todayHighlight: !0,
          })
          .on("changeDate", function (ev) {
            $(this).datepicker("hide");
          });
      } else {
        $("#end_date")
          .datepicker({
            startDate: "0d",
            language: "vi",
            orientation: "bottom left",
            todayHighlight: !0,
          })
          .on("changeDate", function (ev) {
            $(this).datepicker("hide");
          });
      }

      $("#end_time").timepicker({
        minuteStep: 1,
        defaultTime: "",
        showMeridian: !1,
        snapToStep: !0,
      });

      new AutoNumeric.multiple("#type_number", {
        currencySymbol: "",
        decimalCharacter: ".",
        digitGroupSeparator: ",",
        decimalPlaces: decimal_number,
        eventIsCancelable: true,
        minimumValue: 0,
      });

      customer_appointment.changeNumberTime();
    }
  },
  changeNumberTime: function (obj) {
    $.ajax({
      url: laroute.route("admin.customer_appointment.change-number-type"),
      method: "POST",
      dataType: "JSON",
      data: {
        time_type: $("#time_type").val(),
        type_number: $("#type_number").val(),
        date: $("#date").val(),
        time: $("#time").val(),
      },
      success: function (res) {
        $("#end_date").val(res.end_date);
        $("#end_time").val(res.end_time);
      },
    });
  },

  getStaff: function(staffId) {
    $.ajax({
      url: laroute.route("admin.customer_appointment.get-staff-by-branch"),
      method: "POST",
      dataType: "JSON",
      data: {
        branch: $('#branch_id_modal').val()
      },
      success: function (res) {
        var html = '<option></option>';
        for (var i = 0; i < res.data.length; i++) {
           var item = res.data[i];
            if(staffId == item['staff_id']){
              html += '<option value="'+ item['staff_id'] +'" selected>' + item['full_name'] + '</option>'
            }else {
              html += '<option value="'+ item['staff_id'] +'">' + item['full_name'] + '</option>'
            }
            
        }
        $('.staff_id').html(html);
        $(".staff_id").select2({
          placeholder: customer_appointment.jsonLang["Chọn nhân viên"],
        });
      },
    });
  }
};

function arr_diff(a1, a2) {
  var a = [],
    diff = [];

  for (var i = 0; i < a1.length; i++) {
    a[a1[i]] = true;
  }

  for (var i = 0; i < a2.length; i++) {
    if (a[a2[i]]) {
      delete a[a2[i]];
    } else {
      a[a2[i]] = true;
    }
  }

  for (var k in a) {
    diff.push(k);
  }

  return diff;
}

function addLoad(
  full_name,
  phone1,
  type,
  appointment_source_id,
  customer_quantity,
  date,
  time,
  customer_hidden,
  description,
  table_quantity,
  status,
  endDate,
  endTime
) {
  $.ajax({
    url: laroute.route("admin.customer_appointment.submitModalAdd"),
    data: {
      full_name: full_name,
      phone1: phone1,
      customer_appointment_type: type,
      appointment_source_id: appointment_source_id,
      customer_quantity: customer_quantity,
      date: date,
      time: time,
      customer_hidden: customer_hidden,
      description: description,
      table_quantity: table_quantity,
      status: status,
      endDate: endDate,
      endTime: endTime,
      time_type: $("#time_type").val(),
      type_number: $("#type_number").val(),
      customer_group_id: $("#customer_group_id").val(),
      branch_id: $("#branch_id_modal").val(),
    },
    method: "POST",
    dataType: "JSON",
    success: function (res) {
      if (res.error == false) {
        // window.location.reload();
        console.log("Thêm lịch thành công");
        Swal.fire({
          type: "success",
          title: customer_appointment.jsonLang["Thêm lịch hẹn thành công"],
          showConfirmButton: false,
          timer: 2000,
        }).then(() => {
          location.reload(true);
        });
      } else {
        console.log("Thêm lịch thất bại");
        swal(res.message, "", "error");
      }
    },
  });
}

function updateLoad(
  customer_hidden,
  date,
  time,
  type,
  status,
  appointment_source_id,
  description,
  customer_quantity,
  table_quantity,
  endDate,
  endTime
) {
  $.ajax({
    url: laroute.route("admin.customer_appointment.update-number-appointment"),
    method: "POST",
    dataType: "JSON",
    data: {
      customer_id: customer_hidden,
      date: date,
      time: time,
      type: type,
      status: status,
      appointment_source_id: appointment_source_id,
      description: description,
      customer_quantity: customer_quantity,
      table_quantity: table_quantity,
      endDate: endDate,
      endTime: endTime,
      time_type: $("#time_type").val(),
      type_number: $("#type_number").val(),
      branch_id: $("#branch_id_modal").val(),
    },
    success: function (res) {
      if (res.error == false) {
        window.location.reload();
        swal(
          customer_appointment.jsonLang["Cập nhật lịch hẹn thành công"],
          "",
          "success"
        );
      } else {
        swal(res.message, "", "error");
      }
    },
  });
}

function addReset(
  full_name,
  phone1,
  type,
  appointment_source_id,
  customer_quantity,
  date,
  time,
  customer_hidden,
  description,
  table_quantity,
  status,
  endDate,
  endTime
) {
  $.ajax({
    url: laroute.route("admin.customer_appointment.submitModalAdd"),
    data: {
      full_name: full_name,
      phone1: phone1,
      customer_appointment_type: type,
      appointment_source_id: appointment_source_id,
      customer_quantity: customer_quantity,
      date: date,
      time: time,
      customer_hidden: customer_hidden,
      description: description,
      table_quantity: table_quantity,
      status: status,
      endDate: endDate,
      endTime: endTime,
      time_type: $("#time_type").val(),
      type_number: $("#type_number").val(),
      customer_group_id: $("#customer_group_id").val(),
      branch_id: $("#branch_id_modal").val(),
    },
    method: "POST",
    dataType: "JSON",
    success: function (res) {
      console.log(res);
      if (res.error == false) {
        swal(
          customer_appointment.jsonLang["Thêm lịch hẹn thành công"],
          "",
          "success"
        );

        $("#customer_hidden").val("");
        $("#full_name").val("").attr("disabled", false);
        $("#customer_group_id")
          .val("")
          .trigger("change")
          .attr("disabled", false);
        $("#phone1").val("");
        $("#time").val("").trigger("change");
        $("#quantity_customer").val(1);
        $("#description").val("");
        $(".error_time").text("");
        $("#end_date").val("");
        $("#end_time").val("").trigger("change");

        $("#table_quantity > tbody .tr_service")
          .find('select[name="service_id"]')
          .val("")
          .trigger("change");
        $("#table_quantity > tbody .tr_service")
          .find('select[name="staff_id"]')
          .val("")
          .trigger("change")
          .enable(false);
        $("#table_quantity > tbody .tr_service")
          .find('select[name="room_id"]')
          .val("")
          .trigger("change")
          .enable(false);
        $("#table_quantity > tbody .tr_card").empty();
      } else {
        swal(res.message, "", "error");
      }
    },
  });
}

function updateReset(
  customer_hidden,
  date,
  time,
  type,
  status,
  appointment_source_id,
  description,
  customer_quantity,
  table_quantity,
  endDate,
  endTime
) {
  $.ajax({
    url: laroute.route("admin.customer_appointment.update-number-appointment"),
    method: "POST",
    dataType: "JSON",
    data: {
      customer_id: customer_hidden,
      date: date,
      time: time,
      type: type,
      status: status,
      appointment_source_id: appointment_source_id,
      description: description,
      customer_quantity: customer_quantity,
      table_quantity: table_quantity,
      endDate: endDate,
      endTime: endTime,
      time_type: $("#time_type").val(),
      type_number: $("#type_number").val(),
      branch_id: $("#branch_id_modal").val(),
    },
    success: function (res) {
      if (res.error == false) {
        swal(
          customer_appointment.jsonLang["Cập nhật lịch hẹn thành công"],
          "",
          "success"
        );

        $("#customer_hidden").val("");
        $("#full_name").val("").attr("disabled", false);
        $("#phone1").val("");
        $("#time").val("").trigger("change");
        $("#quantity_customer").val(1);
        $("#description").val("");
        $(".error_time").text("");
        $("#end_date").val("");
        $("#end_time").val("").trigger("change");

        $("#table_quantity > tbody .tr_service")
          .find('select[name="service_id"]')
          .val("")
          .trigger("change");
        $("#table_quantity > tbody .tr_service")
          .find('select[name="staff_id"]')
          .val("")
          .trigger("change")
          .enable(false);
        $("#table_quantity > tbody .tr_service")
          .find('select[name="room_id"]')
          .val("")
          .trigger("change")
          .enable(false);
        $("#table_quantity > tbody .tr_card").empty();
      } else {
        swal(res.message, "", "error");
      }
    },
  });
}
