var Event = function () {
  var filterYear = "";
  var branch = "";

  var listWeekEstimate = function (p) {
    let url = $("#week-estimate-btn").attr("value");
    let branch_id = $("#week-estimate-btn").data("id");
    $.post(url, { year: filterYear, branch_id: branch_id }, function (resp) {
      $("#estimate-list").html(resp);
    });
  };

  return {
    // public functions
    init: function () {
      listWeekEstimate();
    },
  };
};

$(document).ready(function () {
   
  
  var eventManager = new Event();
  eventManager.init();

  $("#popup-quota").on("hidden.bs.modal", function () {
    $("#frm-add-estimate")[0].reset();
    $(`#selectWeekFrom`).removeClass("pointer-events");
    $(`#selectWeekTo`).removeClass("pointer-events");
    $(`#selectMonthFrom`).removeClass("pointer-events");
    $(`#selectMonthTo`).removeClass("pointer-events");
    $("#is_approve_week").parent().show().removeClass("pointer-events");
    $("#is_approve_month").parent().show().removeClass("pointer-events");
    $(".week-content").show();
    $(".month-content").hide();
  });

  $("#week-estimate-btn, #month-estimate-btn").on("click", function () {
    $(this).addClass("active");
    $("#week-estimate-btn, #month-estimate-btn")
      .not(this)
      .removeClass("active");
  });

  $("#week-estimate-btn").click(function () {
    let url = $("#week-estimate-btn").attr("value");
    let branch_id = $("#week-estimate-btn").data("id");
    var filterYear = $("#filter-year option:selected").val();
    $.post(url, { year: filterYear, branch_id: branch_id }, function (resp) {
      $("#estimate-list").html(resp);
    });
  });

  $("#month-estimate-btn").click(function () {
    let url = $("#month-estimate-btn").attr("value");
    let branch_id = $("#week-estimate-btn").data("id");
    var filterYear = $("#filter-year option:selected").val();
    $.post(url, { year: filterYear, branch_id: branch_id }, function (resp) {
      $("#estimate-list").html(resp);
    });
  });

  // $("#frm-add-estimate").submit(function (e) {
  //   e.preventDefault();
  //   var ckbApproveWeek = $('input[name="is_approve_week"]:checked').val();
  //   var ckbApproveMonth = $('input[name="is_approve_month"]:checked').val();
  //   var isValid = true;

  //   if (ckbApproveWeek == 1) {
  //     $(".error-week-estimate-time").text("");
  //     $(".error-week-estimate-money").text("");
  //     if ($("#week-estimate-time").val() != "") {
  //       var week_estimate_time = $("#week-estimate-time")
  //         .val()
  //         .replace(",", "");
  //       if (parseInt(week_estimate_time) < 1) {
  //         isValid = false;
  //         $(".error-week-estimate-time").text(
  //           translate.lang["Số giờ dự kiến phải lớn hơn 0"]
  //         );
  //       }
  //     } else {
  //       isValid = false;
  //       $(".error-week-estimate-time").text(translate.lang["Chưa điền số giờ dự kiến"]);
  //     }
  //     if ($("#week-estimate-money").val() != "") {
  //       var week_estimate_money = $("#week-estimate-money")
  //         .val()
  //         .replace(",", "");
  //       if (parseInt(week_estimate_money) < 1) {
  //         isValid = false;
  //         $(".error-week-estimate-money").text(
  //           translate.lang["Tổng ngân sách lương dự kiến phải lớn hơn 0"]
  //         );
  //       }
  //     } else {
  //       isValid = false;
  //       $(".error-week-estimate-money").text(
  //         translate.lang["Chưa điền tổng ngân sách lương dự kiến"]
  //       );
  //     }
  //   }
  //   if (ckbApproveMonth == 1) {
  //     $(".error-month-estimate-time").text("");
  //     $(".error-month-estimate-money").text("");
  //     if ($("#month-estimate-time").val() != "") {
  //       var month_estimate_time = $("#month-estimate-time")
  //         .val()
  //         .replace(",", "");
  //       if (parseInt(month_estimate_time) < 1) {
  //         isValid = false;
  //         $(".error-month-estimate-time").text(
  //           translate.lang["Số giờ dự kiến phải lớn hơn 0"]
  //         );
  //       }
  //     } else {
  //       isValid = false;
  //       $(".error-month-estimate-time").text(
  //         translate.lang["Chưa điền số giờ dự kiến"]
  //       );
  //     }
  //     if ($("#month-estimate-money").val() != "") {
  //       var month_estimate_money = $("#month-estimate-money")
  //         .val()
  //         .replace(",", "");
  //       if (parseInt(month_estimate_money) < 1) {
  //         isValid = false;
  //         $(".error-month-estimate-money").text(
  //           translate.lang["Tổng ngân sách lương dự kiến phải lớn hơn 0"]
  //         );
  //       }
  //     } else {
  //       isValid = false;
  //       $(".error-month-estimate-money").text(
  //         translate.lang["Chưa điền tổng ngân sách lương dự kiến"]
  //       );
  //     }
  //   }
  //   if (!isValid) {
  //     return;
  //   }
  //   let url = $(this).data("route");
  //   let branch_id = $(this).data("id");
  //   let data = $(this).serialize() + "&branch_id=" + branch_id;
  //   $.post(url, data, function (resp) {
  //     if (resp.status) {
  //       swal(resp.message, "", "success");
  //       window.location.reload();
  //     } else {
  //       swal(resp.message, "", "error");
  //     }
  //   });
  // });
  $("#filter-year").change(function () {
    let filterYear = $(this).val();
    let branch_id = $("#week-estimate-btn").data("id");

    let url = "";
    if ($("#week-estimate-btn").hasClass("active")) {
      url = $("#week-estimate-btn").attr("value");
    } else {
      url = $("#month-estimate-btn").attr("value");
    }

    $.post(url, { year: filterYear, branch_id: branch_id }, function (resp) {
      $("#estimate-list").html(resp);
    });
  });

});

$(document).ready(function () {
  $("#filter-year").select2();
});

var estimate = {
  showModalEdit: function (e) {
    let id = $(e).data("id");
    let type = $(e).data("type");
    let time = $(e).data("time");
    let money = $(e).data("money");
    let content = $(e).data("content");
    let branch = $(e).data("branch");
    $.ajax({
      url: laroute.route("estimate.modal-edit"),
      method: "POST",
      dataType: "JSON",
      data: {
        id: id,
        type: type,
        time: time,
        money: money,
        content: content,
        branch: branch,
      },
      success: function (res) {
        if (res.html != null) {
          $("#modal-estimate-edit").html(res.html);
          $("#popup-quota-edit").modal("show");
          $("select:not(.normal)").each(function () {
            $(this).select2({
              dropdownParent: $(this).parent(),
            });
          });
          new AutoNumeric.multiple(
            "#week-estimate-money, #week-estimate-time, #month-estimate-money, #month-estimate-time",
            {
              currencySymbol: "",
              decimalCharacter: ".",
              digitGroupSeparator: ",",
              decimalPlaces: decimal_number,
              minimumValue: 0,
            }
          );
        } else {
          Swal.fire(
            "Thông Báo",
            "Không có lịch làm việc trong thời gian này",
            "error"
          );
        }
      },
    });
  },
  showModalAdd: function (e) {
    let branch = $(e).data("branch");
    $.ajax({
      url: laroute.route("estimate.modal-add"),
      method: "POST",
      dataType: "JSON",
      data: {
        branch: branch,
      },
      success: function (res) {
        if (res.html != null) {
          $("#modal-estimate-add").html(res.html);
          $("#popup-quota-add").modal("show");
          $("select:not(.normal)").each(function () {
            $(this).select2({
              dropdownParent: $(this).parent(),
            });
          });
          new AutoNumeric.multiple(
            "#week-estimate-money, #week-estimate-time, #month-estimate-money, #month-estimate-time",
            {
              currencySymbol: "",
              decimalCharacter: ".",
              digitGroupSeparator: ",",
              decimalPlaces: decimal_number,
              minimumValue: 0,
            }
          );
        } else {
          Swal.fire(
            "Thông Báo",
            "Không có lịch làm việc trong thời gian này",
            "error"
          );
        }
      },
    });
  },
  checkWeek: function (e) {
    if ($(e).is(":checked")) {
      $(".week-content").show();
    } else {
      $(".week-content").hide();
    }
  },
  checkMonth: function (e) {
    if ($(e).is(":checked")) {
      $(".month-content").show();
    } else {
      $(".month-content").hide();
    }
  },
  edit: function () {
    
    var ckbApproveWeek = $('input[name="is_approve_week"]:checked').val();
    var ckbApproveMonth = $('input[name="is_approve_month"]:checked').val();
    var isValid = true;
    if (ckbApproveWeek == 1) {
      $(".error-week-estimate-time").text("");
      $(".error-week-estimate-money").text("");
      if ($("#week-estimate-time").val() != "") {
        var week_estimate_time = $("#week-estimate-time")
          .val()
          .replace(",", "");
        if (parseInt(week_estimate_time) < 1) {
          isValid = false;
          $(".error-week-estimate-time").text(
            translate.lang["Số giờ dự kiến phải lớn hơn 0"]
          );
        }
      } else {
        isValid = false;
        $(".error-week-estimate-time").text(translate.lang["Chưa điền số giờ dự kiến"]);
      }
      if ($("#week-estimate-money").val() != "") {
        var week_estimate_money = $("#week-estimate-money")
          .val()
          .replace(",", "");
        if (parseInt(week_estimate_money) < 1) {
          isValid = false;
          $(".error-week-estimate-money").text(
            translate.lang["Tổng ngân sách lương dự kiến phải lớn hơn 0"]
          );
        }
      } else {
        isValid = false;
        $(".error-week-estimate-money").text(
          translate.lang["Chưa điền tổng ngân sách lương dự kiến"]
        );
      }
    }
    if (ckbApproveMonth == 1) {
      $(".error-month-estimate-time").text("");
      $(".error-month-estimate-money").text("");
      if ($("#month-estimate-time").val() != "") {
        var month_estimate_time = $("#month-estimate-time")
          .val()
          .replace(",", "");
        if (parseInt(month_estimate_time) < 1) {
          isValid = false;
          $(".error-month-estimate-time").text(
            translate.lang["Số giờ dự kiến phải lớn hơn 0"]
          );
        }
      } else {
        isValid = false;
        $(".error-month-estimate-time").text(
          translate.lang["Chưa điền số giờ dự kiến"]
        );
      }
      if ($("#month-estimate-money").val() != "") {
        var month_estimate_money = $("#month-estimate-money")
          .val()
          .replace(",", "");
        if (parseInt(month_estimate_money) < 1) {
          isValid = false;
          $(".error-month-estimate-money").text(
            translate.lang["Tổng ngân sách lương dự kiến phải lớn hơn 0"]
          );
        }
      } else {
        isValid = false;
        $(".error-month-estimate-money").text(
          translate.lang["Chưa điền tổng ngân sách lương dự kiến"]
        );
      }
    }
    if (!isValid) {
      return;
    }
    var disabled = $("#frm-edit-estimate")
      .find(":input:disabled")
      .removeAttr("disabled");
    var data = $("#frm-edit-estimate").serialize();
    disabled.attr("disabled", "disabled");
    $.ajax({
      url: laroute.route("estimate.quota.quota-estimate.edit"),
      method: "POST",
      dataType: "JSON",
      data: data,
      success: function (res) {
        if (res.status) {
          swal({
              title:  res.message,
              text: 'Đang chuyển hướng ...',
              type: 'success',
              timer: 1500,
              showConfirmButton: false,
          })
          .then(() => {
              window.location.reload();
          });
        } else {
          swal(res.message, "", "error");
        }
      },
    });
  
  },

  add: function () {
    
    var ckbApproveWeek = $('input[name="is_approve_week"]:checked').val();
    var ckbApproveMonth = $('input[name="is_approve_month"]:checked').val();
    var isValid = true;
    if (ckbApproveWeek == 1) {
      $(".error-week-estimate-time").text("");
      $(".error-week-estimate-money").text("");
      if ($("#week-estimate-time").val() != "") {
        var week_estimate_time = $("#week-estimate-time")
          .val()
          .replace(",", "");
        if (parseInt(week_estimate_time) < 1) {
          isValid = false;
          $(".error-week-estimate-time").text(
            translate.lang["Số giờ dự kiến phải lớn hơn 0"]
          );
        }
      } else {
        isValid = false;
        $(".error-week-estimate-time").text(translate.lang["Chưa điền số giờ dự kiến"]);
      }
      if ($("#week-estimate-money").val() != "") {
        var week_estimate_money = $("#week-estimate-money")
          .val()
          .replace(",", "");
        if (parseInt(week_estimate_money) < 1) {
          isValid = false;
          $(".error-week-estimate-money").text(
            translate.lang["Tổng ngân sách lương dự kiến phải lớn hơn 0"]
          );
        }
      } else {
        isValid = false;
        $(".error-week-estimate-money").text(
          translate.lang["Chưa điền tổng ngân sách lương dự kiến"]
        );
      }
    }
    if (ckbApproveMonth == 1) {
      $(".error-month-estimate-time").text("");
      $(".error-month-estimate-money").text("");
      if ($("#month-estimate-time").val() != "") {
        var month_estimate_time = $("#month-estimate-time")
          .val()
          .replace(",", "");
        if (parseInt(month_estimate_time) < 1) {
          isValid = false;
          $(".error-month-estimate-time").text(
            translate.lang["Số giờ dự kiến phải lớn hơn 0"]
          );
        }
      } else {
        isValid = false;
        $(".error-month-estimate-time").text(
          translate.lang["Chưa điền số giờ dự kiến"]
        );
      }
      if ($("#month-estimate-money").val() != "") {
        var month_estimate_money = $("#month-estimate-money")
          .val()
          .replace(",", "");
        if (parseInt(month_estimate_money) < 1) {
          isValid = false;
          $(".error-month-estimate-money").text(
            translate.lang["Tổng ngân sách lương dự kiến phải lớn hơn 0"]
          );
        }
      } else {
        isValid = false;
        $(".error-month-estimate-money").text(
          translate.lang["Chưa điền tổng ngân sách lương dự kiến"]
        );
      }
    }
    if (!isValid) {
      return;
    }
    var disabled = $("#frm-add-estimate")
      .find(":input:disabled")
      .removeAttr("disabled");
    var data = $("#frm-add-estimate").serialize();
    disabled.attr("disabled", "disabled");
    $.ajax({
      url: laroute.route("estimate.quota.quota-estimate.add"),
      method: "POST",
      dataType: "JSON",
      data: data,
      success: function (res) {
        if (res.status) {
          swal(res.message, "", "success");
          window.location.reload();
        } else {
          swal(res.message, "", "error");
        }
      },
    });
  
  },
};
