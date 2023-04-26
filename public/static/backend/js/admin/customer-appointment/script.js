$(document).ready(function () {
  customer_appointment.jsonLang = JSON.parse(localStorage.getItem("tranlate"));

  $("#month").click(function () {
    $(".list-calendar").css("display", "block");
    $(".list").css("display", "none");
  });
  calendar();

  $("#search").click(function () {
    $(".table-list").find("tbody tr").empty();
  });
  $("#customer_id")
    .select2({
      placeholder: customer_appointment.jsonLang["Nhập thông tin khách hàng"],
      ajax: {
        url: laroute.route("admin.customer_appointment.search"),
        dataType: "json",
        delay: 250,
        type: "POST",
        data: function (params) {
          var query = {
            search: params.term,
            page: params.page || 1,
          };
          return query;
        },
      },
      minimumInputLength: 1,
      allowClear: true,
    })
    .on("select2:unselect", function (e) {
      $("#customer_hidden").val("");
      $("#full_name").val("");
      $("#phone1").val("");
    });
  $("#search_refer").select2({
    placeholder:
      customer_appointment.jsonLang["Nhập thông tin người giới thiệu"],
    ajax: {
      url: laroute.route("admin.customer_appointment.search"),
      dataType: "json",
      delay: 250,
      type: "POST",
      data: function (params) {
        var query = {
          search: params.term,
          page: params.page || 1,
        };
        return query;
      },
      processResults: function (response) {
        console.log(response);
        response.page = response.page || 1;
        return {
          results: response.search.results,
          pagination: {
            more: response.pagination,
          },
        };
      },
      cache: true,
      delay: 250,
    },
    // minimumInputLength: 1,
    // minimumResultsForSearch:0,
    allowClear: true,
    language: "vi",
  });
  $("#service_id").select2({
    placeholder: customer_appointment.jsonLang["Chọn dịch vụ"],
    // ajax: {
    //     url: laroute.route('admin.customer_appointment.search-service'),
    //     dataType: 'json',
    //     delay: 250,
    //     type: 'POST',
    //     data: function (params) {
    //         var query = {
    //             search: params.term,
    //             page: params.page || 1
    //         };
    //         return query;
    //     }
    // },
    // minimumInputLength: 1,
  });
  $("#service_id").on("select2:select", function (event) {
    $("#service_id").val("").trigger("change");
    $("#table").css("display", "block");
    var check = true;
    $.each($("#table_service tbody tr"), function () {
      let codeHidden = $(this).find("td input[name='service_name_hidden']");
      let codeExists = codeHidden.val();
      var code = event.params.data.id;
      if (codeExists == code) {
        check = false;
        let quantitySv = codeHidden
          .parents("tr")
          .find('input[name="quantity"]')
          .val();
        // console.log(quantitySv);
        let numbers = parseInt(quantitySv) + 1;
        codeHidden.parents("tr").find('input[name="quantity"]').val(numbers);
      }
    });
    if (check == true) {
      $.ajax({
        url: laroute.route("admin.customer_appointment.load-time"),
        dataType: "JSON",
        data: {
          id: event.params.data.id,
        },
        method: "POST",
        success: function (res) {
          var tpl = $("#service-tpl").html();
          var stts = $("#table_service tr").length;
          tpl = tpl.replace(/{stt}/g, stts);
          tpl = tpl.replace(/{service_name}/g, event.params.data.text);
          tpl = tpl.replace(/{service_name_id}/g, event.params.data.id);
          tpl = tpl.replace(/{time}/g, res.time);
          $("#table_service > tbody").append(tpl);
          $(".quantity").TouchSpin({
            initval: 1,
            min: 1,
          });
          $(".remove_service").click(function () {
            $(this).closest(".service_tb").remove();
            //alert('ok');
          });
        },
      });
    }
  });
  $(".btn_add").click(function () {
    $("#form-add").validate({
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
      },
      submitHandler: function () {
        // mApp.block("#m_blockui_1_content", {
        //     overlayColor: "#000000",
        //     type: "loader",
        //     state: "success",
        //     message: customer_appointment.jsonLang["Đang tải..."]
        // });
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
        for (let i = 0; i < customer_quantity; i++) {
          var stt = i + 1;
          var input = $("#customer_order_" + stt + "").val();
          var sv = "";
          if ($("#service_id_" + stt + "").val() != "") {
            sv = $("#service_id_" + stt + "").val();
          }
          var room = $("#room_id_" + stt + "").val();
          var staff = $("#staff_id_" + stt + "").val();
          var arr = {
            stt: input,
            sv: sv,
            staff: staff,
            room: room,
          };
          table_quantity.push(arr);
        }
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
            },
            success: function (res) {
              mApp.unblock("#m_blockui_1_content");
              console.log(res);
              if (res.time_error == 1) {
                $(".error_time").text(
                  customer_appointment.jsonLang[
                    "Ngày hẹn, giờ hẹn không hợp lệ"
                  ]
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
                  status
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
                    confirmButtonText:
                      customer_appointment.jsonLang["THÊM MỚI"],
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
                      status
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
                      table_quantity
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
                      table_quantity
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
            status
          );
        }
      },
    });
  });
  $(".btn_new").click(function () {
    $("#form-add").validate({
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
      },
      submitHandler: function () {
        mApp.block("#m_blockui_1_content", {
          overlayColor: "#000000",
          type: "loader",
          state: "success",
          message: customer_appointment.jsonLang["Đang tải..."],
        });
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
        for (let i = 0; i < customer_quantity; i++) {
          var stt = i + 1;
          var input = $("#customer_order_" + stt + "").val();
          var sv = "";
          if ($("#service_id_" + stt + "").val() != "") {
            sv = $("#service_id_" + stt + "").val();
          }
          var room = $("#room_id_" + stt + "").val();
          var staff = $("#staff_id_" + stt + "").val();
          var arr = {
            stt: input,
            sv: sv,
            staff: staff,
            room: room,
          };
          table_quantity.push(arr);
        }
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
            },
            success: function (res) {
              mApp.unblock("#m_blockui_1_content");
              if (res.time_error == 1) {
                $(".error_time").text(
                  customer_appointment.jsonLang[
                    "Ngày hẹn, giờ hẹn không hợp lệ"
                  ]
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
                  status
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
                    confirmButtonText:
                      customer_appointment.jsonLang["THÊM MỚI"],
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
                      status
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
                      table_quantity
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
                      table_quantity
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
            status
          );
        }
      },
    });
  });

  $("#appointment_source_id").selectpicker();
  let $element = $("#appointment_source_id");
  let val = $element
    .find(
      "option:contains('" + customer_appointment.jsonLang["gọi điện"] + "')"
    )
    .val();
  $("#appointment_source_id").val(val).trigger("change");
});

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

  click_modal: function () {
    $.ajax({
      url: laroute.route("admin.customer_appointment.modalAddTimeline"),
      dataType: "JSON",
      method: "POST",
      data: {
        date_now: $("#search").val(),
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

        $("#appointment_source_id, #time_type, #branch_id_modal").select2();

        $(".room_id").select2({
          placeholder: customer_appointment.jsonLang["Chọn phòng"],
        });

        $(".staff_id").select2({
          placeholder: customer_appointment.jsonLang["Chọn nhân viên"],
        });

        $(".service_id")
          .select2({
            placeholder: customer_appointment.jsonLang["Chọn dịch vụ"],
          })
          .on("select2:select", function (event) {
            $("#room_id_1").enable(true);
            $("#staff_id_1").enable(true);
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

        if (
          typeof $("#view_mode") != "undefined" &&
          $("#view_mode").val() == "chathub_popup"
        ) {
          $(".btn-addNewContinued").remove();
          $("#phone1").val($("#fr_phone").val());
          // $('#phone1').attr('readonly', 'readonly');
          customer_appointment.showCusPhone($("#fr_phone").val());
        }
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

        $("#start_week, #end_week").select2();
        $("#start_month, #end_month").select2();
        $("#start_year, #end_year").select2();
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

  processFunctionCancelAppointment: function (data) {
    $("#show-modal").modal("hide");
    window.postMessage(
      {
        func: "cancelAppointment",
        message: data,
      },
      "*"
    );
  },

  out_modal: function () {
    $.ajax({
      url: laroute.route(
        "admin.customer_appointment.remove-session-customer_id"
      ),
      dataType: "JSON",
      method: "GET",
      success: function (res) {
        if (
          typeof $("#view_mode") != "undefined" &&
          $("#view_mode").val() == "chathub_popup"
        ) {
          customer_appointment.processFunctionCancelAppointment({});
        } else {
          window.location.reload();
        }
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
                  $("#group_name").val(res.cus.group_name);
                  $("#group_name").attr("disabled", true);
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
                  $("#group_name").val("");
                  $("#group_name").attr("disabled", true);
                  $(".lstHistoryAppointment").html(
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
            customer_appointment.showCusPhone(value);
          },
        });
      },
    });
  },

  showCusPhone: function (value) {
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
          $("#group_name").val(res.cus.group_name);
          $("#group_name").attr("disabled", true);
          $("#HistoryAppointment").PioTable({
            baseUrl: laroute.route("admin.customer_appointment.list-history"),
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
              placeholder: customer_appointment.jsonLang["Chọn thẻ liệu trình"],
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
            placeholder: customer_appointment.jsonLang["Chọn nhân viên"],
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

  processFunctionAddAppointmentSchedule: function (data) {
    $("#modal-add").modal("hide");
    window.postMessage(
      {
        func: "addSuccessAppointmentSchedule",
        message: data,
      },
      "*"
    );
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
  changeNumberTime: function () {
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
};

$("#autotable").PioTable({
  baseUrl: laroute.route("admin.customer_appointment.list"),
});

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

function onmouseoverAddNew() {
  $(".dropdow-add-new").show();
}

function onmouseoutAddNew() {
  $(".dropdow-add-new").hide();
}

// function onmouseoverAddNew() {
//     $('.dropdow-add-new').show();
// }
function onKeyDownInput(o) {
  if ($(o).val().charAt(0) != "0" && $(o).val().length > 0) {
    $(".error-phone1").text(
      customer_appointment.jsonLang["Cập nhật lịch hẹn thất bại"]
    );
  } else {
    $(".error-phone1").text("");
    $(o).on("keydown", function (e) {
      -1 !== $.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) ||
        (/65|67|86|88/.test(e.keyCode) &&
          (e.ctrlKey === true || e.metaKey === true) &&
          (!0 === e.ctrlKey || !0 === e.metaKey)) ||
        (35 <= e.keyCode && 40 >= e.keyCode) ||
        ((e.shiftKey || 48 > e.keyCode || 57 < e.keyCode) &&
          (96 > e.keyCode || 105 < e.keyCode) &&
          e.preventDefault());
    });
  }
}

function formatDate(date) {
  var d = new Date(date),
    month = "" + (d.getMonth() + 1),
    day = "" + d.getDate(),
    year = d.getFullYear();

  if (month.length < 2) month = "0" + month;
  if (day.length < 2) day = "0" + day;

  return [year, month, day].join("-");
}

function calendar() {
  $("#m_calendar").fullCalendar({
    header: {
      left: "prev,next today",
      center: "title",
      right: "month,agendaWeek,agendaDay",
    },
    buttonText: {
      month: customer_appointment.jsonLang["THÁNG"],
      day: customer_appointment.jsonLang["NGÀY"],
      week: customer_appointment.jsonLang["TUẦN"],
      today: customer_appointment.jsonLang["HÔM NAY"],
    },
    views: {
      month: {
        titleFormat: "DD/MM/YYYY",
      },
      agendaWeek: {
        titleFormat: "DD/MM/YYYY",
      },
      agendaDay: {
        titleFormat: "DD/MM/YYYY",
        // 'dddd, MMMM Do YYYY'
      },
    },
    slotLabelFormat: ["MMMM YYYY", "H:mm"],
    locale: "vi",
    height: "auto",
    validRange: {
      start:
        new Date().getFullYear() +
        "-" +
        (new Date().getMonth() + 1) +
        "-" +
        new Date().getDate(),
    },
    minTime: "07:00:00",
    maxTime: "24:00:00",
    events: function (start, end, timezone, callback) {
      $.ajax({
        url: laroute.route("admin.customer_appointment.calendar"),
        data: {},
        method: "POST",
        dataType: "JSON",
        success: function (response) {
          var events = [];
          if (response.data) {
            $.map(response.data, function (r) {
              var class_sta = "";
              if (r.status == "new") {
                class_sta = "m-fc-event--success";
              } else if (r.status == "confirm") {
                class_sta = "m-fc-event--accent";
              } else if (r.status == "wait") {
                class_sta = "m-fc-event--warning";
              } else if (r.status == "finish") {
                class_sta = "m-fc-event--primary";
              } else if (r.status == "processing") {
                class_sta = "m-fc-event--info";
              }
              if (r.status != "cancel") {
                events.push({
                  title: r.full_name_cus,
                  start: r.date_appointment + "T" + r.time,
                  day: r.date_appointment,
                  time: r.time,
                  id: r.customer_appointment_id,
                  status: r.status,
                  allDay: false,
                  className: class_sta,
                  phone: r.phone1,
                  customer_quantity: r.customer_quantity,
                });
              }
            });
          }
          callback(events);
        },
      });
    },
    eventLimit: true,
    timeFormat: "H:mm",
    selectable: true,
    eventClick: function (calEvent, jsEvent, view) {
      if ($("#role-edit-appointments").val() == 1) {
        $.getJSON(laroute.route("translate"), function (json) {
          $.ajax({
            type: "POST",
            url: laroute.route("admin.customer_appointment.detail"),
            data: {
              id: calEvent.id,
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

              $("#time_type, #branch_id_modal").select2();

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
                baseUrl: laroute.route(
                  "admin.customer_appointment.list-history"
                ),
              });
              $("#HistoryAppointmentEdit").PioTable("refresh");
            },
          });
        });
      }
    },
    eventMouseover: function (calEvent, jsEvent, view) {
      var sta = "";
      var class_status = "";
      var class_status_text = "";
      if (calEvent.status == "new") {
        sta = customer_appointment.jsonLang["Mới"];
        class_status = "m-badge--success";
        class_status_text = "m--font-success";
      } else if (calEvent.status == "confirm") {
        sta = customer_appointment.jsonLang["Đã xác nhận"];
        class_status = "m-badge--accent";
        class_status_text = "m--font-accent";
      } else if (calEvent.status == "wait") {
        sta = customer_appointment.jsonLang["Chờ phục vụ"];
        class_status = "m-badge--warning";
        class_status_text = "m--font-warning";
      } else if (calEvent.status == "finish") {
        sta = customer_appointment.jsonLang["Hoàn thành"];
        class_status = "m-badge--primary";
        class_status_text = "m--font-primary";
      } else if (calEvent.status == "processing") {
        sta = customer_appointment.jsonLang["Đang thực hiện"];
        class_status = "m-badge--info";
        class_status_text = "m--font-info";
      }
      var tpl = $("#mouse-over-tpl").html();
      tpl = tpl.replace(/{full_name}/g, calEvent.title);
      tpl = tpl.replace(/{phone}/g, calEvent.phone);
      tpl = tpl.replace(/{class_status}/g, class_status);
      tpl = tpl.replace(/{class_status_text}/g, class_status_text);
      tpl = tpl.replace(/{status}/g, sta);
      tpl = tpl.replace(/{customer_quantity}/g, 1);
      $("body").append(tpl);
      $(this)
        .mouseover(function (e) {
          $(this).css("z-index", 10000);
          $(".tooltipevent").fadeIn("500");
          $(".tooltipevent").fadeTo("10", 1.9);
        })
        .mousemove(function (e) {
          $(".tooltipevent").css("top", e.pageY + 10);
          $(".tooltipevent").css("left", e.pageX + 20);
        });
    },
    eventMouseout: function (calEvent, jsEvent) {
      $(this).css("z-index", 8);
      $(".tooltipevent").remove();
    },
    dayClick: function (date, jsEvent, view) {
      if ($("#role-add-appointments").val() == 1) {
        var toDateParse = formatDate(date).split("-");

        $.getJSON(laroute.route("translate"), function (json) {
          $.ajax({
            url: laroute.route("admin.customer_appointment.modalAddTimeline"),
            dataType: "JSON",
            method: "POST",
            data: {
              date_now:
                toDateParse[2] + "/" + toDateParse[1] + "/" + toDateParse[0],
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
              $(
                "#appointment_source_id, #time_type, #branch_id_modal"
              ).select2();

              $(".room_id").select2({
                placeholder: customer_appointment.jsonLang["Chọn phòng"],
              });

              $(".staff_id").select2({
                placeholder: customer_appointment.jsonLang["Chọn nhân viên"],
              });

              $(".service_id")
                .select2({
                  placeholder: customer_appointment.jsonLang["Chọn dịch vụ"],
                })
                .on("select2:select", function (event) {
                  $("#room_id_1").enable(true);
                  $("#staff_id_1").enable(true);
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
                placeholder:
                  customer_appointment.jsonLang["Chọn nhóm khách hàng"],
              });
              $("#HistoryAppointment").PioTable({
                baseUrl: laroute.route(
                  "admin.customer_appointment.list-history"
                ),
              });
              $("#HistoryAppointment").PioTable("refresh");
            },
          });
        });
      }
    },
  });
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
      branch_id: $("#branch_id_modal").val(),
    },
    method: "POST",
    dataType: "JSON",
    success: function (res) {
      if (res.error == false) {
        if (
          typeof $("#view_mode") != "undefined" &&
          $("#view_mode").val() == "chathub_popup"
        ) {
          customer_appointment.processFunctionAddAppointmentSchedule(res.data);
        } else {
          window.location.reload();
          swal(
            customer_appointment.jsonLang["Thêm lịch hẹn thành công"],
            "",
            "success"
          );
        }
      } else {
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
        if (
          typeof $("#view_mode") != "undefined" &&
          $("#view_mode").val() == "chathub_popup"
          ) {
            customer_appointment.processFunctionAddAppointmentSchedule(res.data);
          } else {
            window.location.reload();
            swal(
              customer_appointment.jsonLang["Cập nhật lịch hẹn thành công"],
              "",
              "success"
            );
          }
        // window.location.reload();
        // swal(
        //   customer_appointment.jsonLang["Cập nhật lịch hẹn thành công"],
        //   "",
        //   "success"
        // );
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
