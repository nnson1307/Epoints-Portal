var jsonLang = JSON.parse(localStorage.getItem("tranlate"));

var view = {
  jsontranslate: null,
  _init: function () {
    $(document).ready(function () {
      $("#staff_salary_pay_period_code").select2({
        placeholder: jsonLang["Chọn kỳ hạn lương"],
        width: "100%",
      });

      $("#payment_type").select2({
        placeholder: jsonLang["Chọn hình thức trả lương"],
        width: "100%",
      });

      $("#staff_salary_type_code").select2({
        placeholder: jsonLang["Chọn loại lương"],
        width: "100%",
      });

      $("#staff_salary_unit_code").select2({
        width: "100%",
      });

      $("[name=staff_salary_template_id]").select2({
        placeholder: jsonLang["Chọn mẫu áp dụng"],
        width: "calc(100% - 30px)",
      });



      view.chooseUnitAndType(true);
    });
  },
  chooseUnitAndType: function (loadDefault = false) {

    var salaryTypeName = $("#staff_salary_type_code  option:selected").text();
    var salaryUnitName = $("#staff_salary_unit_code  option:selected").text();

    $(".salary-unit-name").text(salaryUnitName);
    $(".salary-type-name").text(salaryTypeName);

    $(".text_type_default").text(salaryUnitName + "/ " + salaryTypeName);
    $(".text_type_overtime").text(salaryUnitName + "/ " + jsonLang["Giờ"]);

    //Lấy value của loại lương
    var salaryTypeCode = $("#staff_salary_type_code").val();

    if (loadDefault == false) {
      $(".salary_not_month").remove();
      if (salaryTypeCode == "monthly") {
        //Theo tháng
        $("#staff_salary_pay_period_code")
          .val("pay_month")
          .trigger("change")
          .attr("disabled", true);

     
        var tpl = $("#head-table-overtime-tpl").html();
        $("#table_overtime > thead > tr").append(tpl);

        var tpl = $("#body-table-overtime-tpl").html();
        $("#table_overtime > tbody > tr").append(tpl);
      } else {
        console.log(2);
        $("#staff_salary_pay_period_code")
          .val("")
          .trigger("change")
          .attr("disabled", false);

        //Class này không tồn tại thì mới append zo
        var tpl = $("#head-table-default-tpl").html();
        $("#table_default > thead > tr").append(tpl);

        var tpl = $("#body-table-default-tpl").html();
        $("#table_default > tbody > tr").append(tpl);

        var tpl = $("#head-table-overtime-tpl").html();
        $("#table_overtime > thead > tr").append(tpl);

        var tpl = $("#body-table-overtime-tpl").html();
        $("#table_overtime > tbody > tr").append(tpl);
      }
    }
    new AutoNumeric.multiple(".numeric_child", {
      currencySymbol: "",
      decimalCharacter: ".",
      digitGroupSeparator: ",",
      decimalPlaces: decimal_number,
      eventIsCancelable: true,
      minimumValue: 0,
    });
  },
  chooseUnitAndType2: function (loadDefault = false) {
    var salaryTypeName = $(
      ".popup-create #staff_salary_type_code  option:selected"
    ).text();
    var salaryUnitName = $(
      ".popup-create #staff_salary_unit_code  option:selected"
    ).text();

    // $(".popup-create .salary-unit-name").text(salaryUnitName);
    $(".popup-create .salary-type-name").text(salaryTypeName);

    $(".popup-create .text_type_default").text(
      (salaryUnitName + "/ " + salaryTypeName).replace("Lương ", "")
    );
    $(".popup-create .text_type_overtime").text(
      (salaryUnitName + "/ " + salaryTypeName).replace("Lương ", "")
    );

    //Lấy value của loại lương
    var salaryTypeCode = $(".popup-create #staff_salary_type_code").val();

    if (loadDefault == false) {
      if (salaryTypeCode == "monthly") {
        //Theo tháng
        $(".popup-create #staff_salary_pay_period_code")
          .val("pay_month")
          .trigger("change")
          .attr("disabled", true);

        $(".popup-create .salary_not_month").remove();
      } else {
        $(".popup-create .salary_not_month").remove();
        $(".popup-create #staff_salary_pay_period_code")
          .val("")
          .trigger("change")
          .attr("disabled", false);

        if (!$(".popup-create .salary_not_month")[0]) {
          console.log(3);
          //Class này không tồn tại thì mới append zo
          var tpl = $("#head-table-default-tpl").html();
          $(".popup-create #table_default > thead > tr").append(tpl);

          var tpl = $("#body-table-default-tpl").html();
          $(".popup-create #table_default > tbody > tr").append(tpl);

          var tpl = $("#head-table-overtime-tpl").html();
          $(".popup-create #table_overtime > thead > tr").append(tpl);

          var tpl = $("#body-table-overtime-tpl").html();
          $(".popup-create #table_overtime > tbody > tr").append(tpl);

        }
      }
    }
  },
  checkIsOvertime: function (obj) {
    if ($(obj).is(":checked")) {
      $(".div_overtime").css("display", "block");
    } else {
      $(".div_overtime").css("display", "none");
    }
  },
  checkIsAllowance: function (obj) {
    if ($(obj).is(":checked")) {
      $(".div_allowance").css("display", "block");
    } else {
      $(".div_allowance").css("display", "none");
    }
  },
  showPopCreateAllowance: function () {
    $.ajax({
      url: laroute.route("staff-salary.template.pop-create-allowance"),
      method: "POST",
      dataType: "JSON",
      data: {},
      success: function (res) {
        $("#modal-allowance").html(res.html);
        $("#modal-create-allowance").modal("show");

        // $('#salary_allowance_id').select2({
        //     placeholder: jsonLang['Chọn loại phụ cấp'],
        //     width: '100%'
        // });
        $("[name=salary_allowance_id]").select2({
          placeholder: jsonLang["Chọn loại phụ cấp"],
          width: "100%",
        });
        new AutoNumeric.multiple("#staff_salary_allowance_num", {
          currencySymbol: "",
          decimalCharacter: ".",
          digitGroupSeparator: ",",
          decimalPlaces: decimal_number,
          eventIsCancelable: true,
          minimumValue: 0,
        });
      },
    });
  },
  submitCreateAllowance: function () {
    var form = $("#form-create-allowance");

    form.validate({
      rules: {
        salary_allowance_id: {
          required: true,
        },
        staff_salary_allowance_num: {
          required: true,
        },
      },
      messages: {
        salary_allowance_id: {
          required: jsonLang["Hãy chọn loại phụ cấp"],
        },
        staff_salary_allowance_num: {
          required: jsonLang["Hãy nhập mức áp dụng"],
          min: jsonLang["Mức áp dụng tối thiểu 1"],
        },
      },
    });

    if (!form.valid()) {
      return false;
    }

    var next = true;

    //Validate phụ cấp đã tồn tại chưa
    $.each($("#table_allowance").find(".tr_allowance"), function () {
      var salaryAllowanceId = $(this).find($(".salary_allowance_id")).val();
      console.log(salaryAllowanceId);
      console.log($("[name=salary_allowance_id]").val());
      if (salaryAllowanceId == $("[name=salary_allowance_id]").val()) {
        $(".error-salaryAllowance").text(
          jsonLang["Loại phụ cấp này đã tồn tại"]
        );

        next = false;
      }
    });

    if (next == false) {
      return false;
    }

    swal(jsonLang["Thêm thành công"], "", "success").then(function (result) {
      if (result.dismiss == "esc" || result.dismiss == "backdrop") {
        var tpl = $("#tr-allowance-tpl").html();
        tpl = tpl.replace(
          /{salary_allowance_id}/g,
          $("[name=salary_allowance_id]").val()
        );
        tpl = tpl.replace(
          /{salary_allowance_name}/g,
          $("[name=salary_allowance_id] option:selected").text()
        );
        tpl = tpl.replace(
          /{staff_salary_allowance_num}/g,
          "#staff_salary_allowance_num".val()
        );
        $("#table_allowance > tbody").append(tpl);

        $("#modal-create-allowance").modal("hide");
      }
      if (result.value == true) {
        var tpl = $("#tr-allowance-tpl").html();
        tpl = tpl.replace(
          /{salary_allowance_id}/g,
          $("[name=salary_allowance_id]").val()
        );
        tpl = tpl.replace(
          /{salary_allowance_name}/g,
          $("[name=salary_allowance_id] option:selected").text()
        );
        tpl = tpl.replace(
          /{staff_salary_allowance_num}/g,
          $("#staff_salary_allowance_num").val()
        );
        $("#table_allowance > tbody").append(tpl);

        $("#modal-create-allowance").modal("hide");
      }
    });
  },
  removeAllowance: function (obj) {
    $(obj).closest(".tr_allowance").remove();
  },
  store: function () {
    var form = $("#form-register");

    form.validate({
      rules: {
        staff_salary_template_name: {
          required: true,
          maxlength: 190,
        },
        staff_salary_pay_period_code: {
          required: true,
        },
        payment_type: {
          required: true,
        },
        staff_salary_type_code: {
          required: true,
        },
        staff_salary_unit_code: {
          required: true,
        },
        salary_default: {
          required: true,
        },
      },
      messages: {
        staff_salary_template_name: {
          required: jsonLang["Hãy nhập tên mẫu lương"],
          maxlength: jsonLang["Tên mẫu lương tối đa 190 kí tự"],
        },
        staff_salary_pay_period_code: {
          required: jsonLang["Hãy chọn kỳ hạn trả lương"],
        },
        payment_type: {
          required: jsonLang["Hãy chọn hình thức trả lương"],
        },
        staff_salary_type_code: {
          required: jsonLang["Hãy chọn loại lương"],
        },
        staff_salary_unit_code: {
          required: jsonLang["Hãy chọn đơn vị tiền tệ"],
        },
        salary_default: {
          required: jsonLang["Hãy nhập mức lương"],
        },
      },
    });

    if (!form.valid()) {
      return false;
    }

    var isOverTime = 0;

    if ($("#is_overtime").is(":checked")) {
      isOverTime = 1;
    }

    var isAllowance = 0;

    if ($("#is_allowance").is(":checked")) {
      isAllowance = 1;
    }

    var arrayAllowance = [];

    //Lấy data phụ cấp
    $.each($("#table_allowance").find(".tr_allowance"), function () {
      var salaryAllowanceId = $(this).find($(".salary_allowance_id")).val();
      var staffSalaryAllowanceNum = $(this)
        .find($(".staff_salary_allowance_num"))
        .val()
        .replace(new RegExp("\\,", "g"), "");

      arrayAllowance.push({
        salary_allowance_id: salaryAllowanceId,
        staff_salary_allowance_num: staffSalaryAllowanceNum,
      });
    });
    var salary_default = $("#salary_default")
      .val()
      .replace(new RegExp("\\,", "g"), "");
    var salary_saturday_default = "";
    var salary_saturday_default_type = "";
    var salary_sunday_default = "";
    var salary_sunday_default_type = "";
    var salary_holiday_default = "";
    var salary_holiday_default_type = "";
    var salary_overtime = "";
    var salary_saturday_overtime = "";
    var salary_saturday_overtime_type = "";
    var salary_sunday_overtime = "";
    var salary_sunday_overtime_type = "";
    var salary_holiday_overtime = "";
    var salary_holiday_overtime_type = "";
    var isValid = true;
    if ($("#staff_salary_type_code").val() != "monthly") {
      salary_saturday_default = $(".salary_not_month")[0]
        ? $("#salary_saturday_default")
            .val()
            .replace(new RegExp("\\,", "g"), "")
        : null;
      salary_saturday_default_type = $(".salary_not_month")[0]
        ? $("input[name=salary_saturday_default_type]:checked").val()
        : null;
      salary_sunday_default = $(".salary_not_month")[0]
        ? $("#salary_sunday_default").val().replace(new RegExp("\\,", "g"), "")
        : null;
      salary_sunday_default_type = $(".salary_not_month")[0]
        ? $("input[name=salary_sunday_default_type]:checked").val()
        : null;
      salary_holiday_default = $(".salary_not_month")[0]
        ? $("#salary_holiday_default").val().replace(new RegExp("\\,", "g"), "")
        : null;
      salary_holiday_default_type = $(".salary_not_month")[0]
        ? $("input[name=salary_holiday_default_type]:checked").val()
        : null;
      if (salary_default != "") {
        if (parseInt(salary_default) <= 0) {
          $("#salary_default-error").css("color", "red");
          $("#salary_default-error").text(jsonLang["Mức lương phải lớn hơn 0"]);
          isValid = false;
        }
      } else {
        $("#salary_default-error").css("color", "red");
        $("#salary_default-error").text(jsonLang["Chưa điền mức lương"]);
        isValid = false;
      }

      //Validate mức lương thứ 7
      if (salary_saturday_default != "") {
        if (salary_saturday_default_type == "money") {
          if (parseInt(salary_saturday_default) <= 0) {
            $("#salary_saturday_default-error").css("color", "red");
            $("#salary_saturday_default-error").text(
              jsonLang["Mức lương phải lớn hơn 0"]
            );
            isValid = false;
          }
        } else {
          if (
            parseInt(salary_saturday_default) < 100 ||
            parseInt(salary_saturday_default) > 900
          ) {
            $("#salary_saturday_default-error").css("color", "red");
            $("#salary_saturday_default-error").text(
              jsonLang["Mức lương chỉ từ 100 - 900 %"]
            );
            isValid = false;
          }
        }
      } else {
        $("#salary_saturday_default-error").css("color", "red");
        $("#salary_saturday_default-error").text(
          jsonLang["Chưa điền mức lương"]
        );
        isValid = false;
      }

      //Validate mức lương cn
      if (salary_sunday_default != "") {
        if (salary_sunday_default_type == "money") {
          if (parseInt(salary_sunday_default) <= 0) {
            $("#salary_sunday_default-error").css("color", "red");
            $("#salary_sunday_default-error").text(
              jsonLang["Mức lương phải lớn hơn 0"]
            );
            isValid = false;
          }
        } else {
          if (
            parseInt(salary_sunday_default) < 100 ||
            parseInt(salary_sunday_default) > 900
          ) {
            $("#salary_sunday_default-error").css("color", "red");
            $("#salary_sunday_default-error").text(
              jsonLang["Mức lương chỉ từ 100 - 900 %"]
            );
            isValid = false;
          }
        }
      } else {
        $("#salary_sunday_default-error").css("color", "red");
        $("#salary_sunday_default-error").text(jsonLang["Chưa điền mức lương"]);
        isValid = false;
      }
      //Validate mức lương cn
      if (salary_holiday_default != "") {
        if (salary_holiday_default_type == "money") {
          if (parseInt(salary_holiday_default) <= 0) {
            $("#salary_holiday_default-error").css("color", "red");
            $("#salary_holiday_default-error").text(
              jsonLang["Mức lương phải lớn hơn 0"]
            );
            isValid = false;
          }
        } else {
          if (
            parseInt(salary_holiday_default) < 100 ||
            parseInt(salary_holiday_default) > 900
          ) {
            $("#salary_holiday_default-error").css("color", "red");
            $("#salary_holiday_default-error").text(
              jsonLang["Mức lương chỉ từ 100 - 900 %"]
            );
            isValid = false;
          }
        }
      } else {
        $("#salary_holiday_default-error").css("color", "red");
        $("#salary_holiday_default-error").text(
          jsonLang["Chưa điền mức lương"]
        );
        isValid = false;
      }
    }
    if (isOverTime == 1) {
      salary_saturday_overtime = $(".salary_not_month")[0]
        ? $("#salary_saturday_overtime")
            .val()
            .replace(new RegExp("\\,", "g"), "")
        : null;
      salary_saturday_overtime_type = $(".salary_not_month")[0]
        ? $("input[name=salary_saturday_overtime_type]:checked").val()
        : null;
      salary_sunday_overtime = $(".salary_not_month")[0]
        ? $("#salary_sunday_overtime").val().replace(new RegExp("\\,", "g"), "")
        : null;
      salary_sunday_overtime_type = $(".salary_not_month")[0]
        ? $("input[name=salary_sunday_overtime_type]:checked").val()
        : null;
      salary_holiday_overtime = $(".salary_not_month")[0]
        ? $("#salary_holiday_overtime")
            .val()
            .replace(new RegExp("\\,", "g"), "")
        : null;
      salary_holiday_overtime_type = $(".salary_not_month")[0]
        ? $("input[name=salary_holiday_overtime_type]:checked").val()
        : null;
      salary_overtime = $("#salary_overtime")
        .val()
        .replace(new RegExp("\\,", "g"), "");
      if (salary_overtime != "") {
        if (parseInt(salary_overtime) <= 0) {
          $("#salary_overtime-error").css("color", "red");
          $("#salary_overtime-error").text(
            view.jsontranslate["Mức lương phải lớn hơn 0"]
          );
          isValid = false;
        }
      } else {
        $("#salary_overtime-error").css("color", "red");
        $("#salary_overtime-error").text(
          view.jsontranslate["Chưa điền mức lương"]
        );
        isValid = false;
      }
      //Validate mức lương thứ 7
      if (salary_saturday_overtime != "") {
        if (salary_saturday_overtime_type == "money") {
          if (parseInt(salary_saturday_overtime) <= 0) {
            $("#salary_saturday_overtime-error").css("color", "red");
            $("#salary_saturday_overtime-error").text(
              view.jsontranslate["Mức lương phải lớn hơn 0"]
            );
            isValid = false;
          }
        } else {
          if (
            parseInt(salary_saturday_overtime) < 100 ||
            parseInt(salary_saturday_overtime) > 900
          ) {
            $("#salary_saturday_overtime-error").css("color", "red");
            $("#salary_saturday_overtime-error").text(
              view.jsontranslate["Mức lương chỉ từ 100 - 900 %"]
            );
            isValid = false;
          }
        }
      } else {
        $("#salary_saturday_overtime-error").css("color", "red");
        $("#salary_saturday_overtime-error").text(
          view.jsontranslate["Chưa điền mức lương"]
        );
        isValid = false;
      }
      //Validate mức lương thứ cn
      if (salary_sunday_overtime != "") {
        if (salary_sunday_overtime_type == "money") {
          if (parseInt(salary_sunday_overtime) <= 0) {
            $("#salary_sunday_overtime-error").css("color", "red");
            $("#salary_sunday_overtime-error").text(
              view.jsontranslate["Mức lương phải lớn hơn 0"]
            );
            isValid = false;
          }
        } else {
          if (
            parseInt(salary_sunday_overtime) < 100 ||
            parseInt(salary_sunday_overtime) > 900
          ) {
            $("#salary_sunday_overtime-error").css("color", "red");
            $("#salary_sunday_overtime-error").text(
              view.jsontranslate["Mức lương chỉ từ 100 - 900 %"]
            );
            isValid = false;
          }
        }
      } else {
        $("#salary_sunday_overtime-error").css("color", "red");
        $("#salary_sunday_overtime-error").text(
          view.jsontranslate["Chưa điền mức lương"]
        );
        isValid = false;
      }
      //Validate mức lương ngày lễ
      if (salary_holiday_overtime != "") {
        if (salary_holiday_overtime_type == "money") {
          if (parseInt(salary_holiday_overtime) <= 0) {
            $("#salary_holiday_overtime-error").css("color", "red");
            $("#salary_holiday_overtime-error").text(
              view.jsontranslate["Mức lương phải lớn hơn 0"]
            );
            isValid = false;
          }
        } else {
          if (
            parseInt(salary_sunday_overtime) < 100 ||
            parseInt(salary_sunday_overtime) > 900
          ) {
            $("#salary_holiday_overtime-error").css("color", "red");
            $("#salary_holiday_overtime-error").text(
              view.jsontranslate["Mức lương chỉ từ 100 - 900 %"]
            );
            isValid = false;
          }
        }
      } else {
        $("#salary_holiday_overtime-error").css("color", "red");
        $("#salary_holiday_overtime-error").text(
          view.jsontranslate["Chưa điền mức lương"]
        );
        isValid = false;
      }

      //Validate staff_salary_unit_code
      if (staff_salary_unit_code != "") {
      } else {
        $(".error-staff_salary_unit_code").css("color", "red");
        $(".error-staff_salary_unit_code").text(
          view.jsontranslate["Chưa chọn đơn vị tiền tệ"]
        );
        $(".error-staff_salary_unit_code").text("Chưa chọn đơn vị tiền tệ");
        isValid = false;
      }
    }
    if (!isValid) {
      return false;
    }
    $.ajax({
      url: laroute.route("staff-salary.template.store"),
      method: "POST",
      dataType: "JSON",
      data: {
        staff_salary_template_name: $("#staff_salary_template_name").val(),
        staff_salary_pay_period_code: $("#staff_salary_pay_period_code").val(),
        payment_type: $("#payment_type").val(),
        staff_salary_type_code: $("#staff_salary_type_code").val(),
        staff_salary_unit_code: $("#staff_salary_unit_code").val(),
        salary_default: salary_default,
        salary_saturday_default: salary_saturday_default,
        salary_saturday_default_type: salary_saturday_default_type,
        salary_sunday_default: salary_sunday_default,
        salary_sunday_default_type: salary_sunday_default_type,
        salary_holiday_default: salary_holiday_default,
        salary_holiday_default_type: salary_holiday_default_type,
        is_overtime: isOverTime,
        salary_overtime: salary_overtime,
        salary_saturday_overtime: salary_saturday_overtime,
        salary_saturday_overtime_type: salary_saturday_overtime_type,
        salary_sunday_overtime: salary_sunday_overtime,
        salary_sunday_overtime_type: salary_sunday_overtime_type,
        salary_holiday_overtime: salary_holiday_overtime,
        salary_holiday_overtime_type: salary_holiday_overtime_type,
        is_allowance: isAllowance,
        arrayAllowance: arrayAllowance,
      },
      success: function (res) {
        if (res.error == false) {
          swal(res.message, "", "success").then(function (result) {
            if (result.dismiss == "esc" || result.dismiss == "backdrop") {
              window.location.href = laroute.route("staff-salary.template");
            }
            if (result.value == true) {
              window.location.href = laroute.route("staff-salary.template");
            }
          });
        } else {
          swal(res.message, "", "error");
        }
      },
      error: function (res) {
        var mess_error = "";
        $.map(res.responseJSON.errors, function (a) {
          mess_error = mess_error.concat(a + "<br/>");
        });
        swal(jsonLang["Thêm thất bại"], mess_error, "error");
      },
    });
  },
  ajaxCreate: function () {
    var form = $("#form-register");

    form.validate({
      rules: {
        modal_staff_salary_template_name: {
          required: true,
          maxlength: 190,
        },
        modal_staff_salary_pay_period_code: {
          required: true,
        },
        modal_payment_type: {
          required: true,
        },
        modal_staff_salary_type_code: {
          required: true,
        },
        modal_staff_salary_unit_code: {
          required: true,
        },
        modal_salary_default: {
          required: true,
        },
      },
      messages: {
        modal_staff_salary_template_name: {
          required: jsonLang["Hãy nhập tên mẫu lương"],
          maxlength: jsonLang["Tên mẫu lương tối đa 190 kí tự"],
        },
        modal_staff_salary_pay_period_code: {
          required: jsonLang["Hãy chọn kỳ hạn trả lương"],
        },
        modal_payment_type: {
          required: jsonLang["Hãy chọn hình thức trả lương"],
        },
        modal_staff_salary_type_code: {
          required: jsonLang["Hãy chọn loại lương"],
        },
        modal_staff_salary_unit_code: {
          required: jsonLang["Hãy chọn đơn vị tiền tệ"],
        },
        modal_salary_default: {
          required: jsonLang["Hãy nhập mức lương"],
        },
      },
    });

    if (!form.valid()) {
      return false;
    }

    var isOverTime = 0;

    if ($("#is_overtime").is(":checked")) {
      isOverTime = 1;
    }

    var isAllowance = 0;

    if ($("#is_allowance").is(":checked")) {
      isAllowance = 1;
    }

    var arrayAllowance = [];

    //Lấy data phụ cấp
    $.each($("#table_allowance").find(".tr_allowance"), function () {
      var salaryAllowanceId = $(this).find($(".salary_allowance_id")).val();
      var staffSalaryAllowanceNum = $(this).find($(".staff_salary_allowance_num")).val().replace(new RegExp("\\,", "g"), "");

      arrayAllowance.push({
        salary_allowance_id: salaryAllowanceId,
        staff_salary_allowance_num: staffSalaryAllowanceNum,
      });
    });
    var salary_default = $("#salary_default")
      .val()
      .replace(new RegExp("\\,", "g"), "");
    var salary_saturday_default = "";
    var salary_saturday_default_type = "";
    var salary_sunday_default = "";
    var salary_sunday_default_type = "";
    var salary_holiday_default = "";
    var salary_holiday_default_type = "";
    var salary_overtime = "";
    var salary_saturday_overtime = "";
    var salary_saturday_overtime_type = "";
    var salary_sunday_overtime = "";
    var salary_sunday_overtime_type = "";
    var salary_holiday_overtime = "";
    var salary_holiday_overtime_type = "";
    var isValid = true;
    if ($("#modal_staff_salary_type_code").val() != "monthly") {
      salary_saturday_default = $(".salary_not_month")[0] ? $("#salary_saturday_default").val().replace(new RegExp("\\,", "g"), ""): null;
      salary_saturday_default_type = $(".salary_not_month")[0]? $("input[name=salary_saturday_default_type]:checked").val(): null;
      salary_sunday_default = $(".salary_not_month")[0]? $("#salary_sunday_default").val().replace(new RegExp("\\,", "g"), ""): null;
      salary_sunday_default_type = $(".salary_not_month")[0] ? $("input[name=salary_sunday_default_type]:checked").val() : null;
      salary_holiday_default = $(".salary_not_month")[0] ? $("#salary_holiday_default").val().replace(new RegExp("\\,", "g"), "") : null;
      salary_holiday_default_type = $(".salary_not_month")[0] ? $("input[name=salary_holiday_default_type]:checked").val() : null;
      if (salary_default != "") {
        if (parseInt(salary_default) <= 0) {
          $("#salary_default-error").css("color", "red");
          $("#salary_default-error").text(jsonLang["Mức lương phải lớn hơn 0"]);
          isValid = false;
        }
      } else {
        $("#salary_default-error").css("color", "red");
        $("#salary_default-error").text(jsonLang["Chưa điền mức lương"]);
        isValid = false;
      }

      //Validate mức lương thứ 7
      if (salary_saturday_default != "") {
        if (salary_saturday_default_type == "money") {
          if (parseInt(salary_saturday_default) <= 0) {
            $("#salary_saturday_default-error").css("color", "red");
            $("#salary_saturday_default-error").text(
              jsonLang["Mức lương phải lớn hơn 0"]
            );
            isValid = false;
          }
        } else {
          if (
            parseInt(salary_saturday_default) < 100 ||
            parseInt(salary_saturday_default) > 900
          ) {
            $("#salary_saturday_default-error").css("color", "red");
            $("#salary_saturday_default-error").text(
              jsonLang["Mức lương chỉ từ 100 - 900 %"]
            );
            isValid = false;
          }
        }
      } else {
        $("#salary_saturday_default-error").css("color", "red");
        $("#salary_saturday_default-error").text(
          jsonLang["Chưa điền mức lương"]
        );
        isValid = false;
      }

      //Validate mức lương cn
      if (salary_sunday_default != "") {
        if (salary_sunday_default_type == "money") {
          if (parseInt(salary_sunday_default) <= 0) {
            $("#salary_sunday_default-error").css("color", "red");
            $("#salary_sunday_default-error").text(
              jsonLang["Mức lương phải lớn hơn 0"]
            );
            isValid = false;
          }
        } else {
          if (
            parseInt(salary_sunday_default) < 100 ||
            parseInt(salary_sunday_default) > 900
          ) {
            $("#salary_sunday_default-error").css("color", "red");
            $("#salary_sunday_default-error").text(
              jsonLang["Mức lương chỉ từ 100 - 900 %"]
            );
            isValid = false;
          }
        }
      } else {
        $("#salary_sunday_default-error").css("color", "red");
        $("#salary_sunday_default-error").text(jsonLang["Chưa điền mức lương"]);
        isValid = false;
      }
      //Validate mức lương cn
      if (salary_holiday_default != "") {
        if (salary_holiday_default_type == "money") {
          if (parseInt(salary_holiday_default) <= 0) {
            $("#salary_holiday_default-error").css("color", "red");
            $("#salary_holiday_default-error").text(
              jsonLang["Mức lương phải lớn hơn 0"]
            );
            isValid = false;
          }
        } else {
          if (
            parseInt(salary_holiday_default) < 100 ||
            parseInt(salary_holiday_default) > 900
          ) {
            $("#salary_holiday_default-error").css("color", "red");
            $("#salary_holiday_default-error").text(
              jsonLang["Mức lương chỉ từ 100 - 900 %"]
            );
            isValid = false;
          }
        }
      } else {
        $("#salary_holiday_default-error").css("color", "red");
        $("#salary_holiday_default-error").text(
          jsonLang["Chưa điền mức lương"]
        );
        isValid = false;
      }
    }
    if (isOverTime == 1) {
      salary_saturday_overtime = $(".salary_not_month")[0]? $("#salary_saturday_overtime").val().replace(new RegExp("\\,", "g"), ""): null;
      salary_saturday_overtime_type = $(".salary_not_month")[0] ? $("input[name=salary_saturday_overtime_type]:checked").val(): null;
      salary_sunday_overtime = $(".salary_not_month")[0] ? $("#salary_sunday_overtime").val().replace(new RegExp("\\,", "g"), "") : null;
      salary_sunday_overtime_type = $(".salary_not_month")[0]  ? $("input[name=salary_sunday_overtime_type]:checked").val() : null;
      salary_holiday_overtime = $(".salary_not_month")[0] ? $("#salary_holiday_overtime").val().replace(new RegExp("\\,", "g"), "") : null;
      salary_holiday_overtime_type = $(".salary_not_month")[0] ? $("input[name=salary_holiday_overtime_type]:checked").val() : null;
      salary_overtime = salary_overtime = $("#salary_overtime").val().replace(new RegExp("\\,", "g"), "");
      if (salary_overtime != "") {
        if (parseInt(salary_overtime) <= 0) {
          $("#salary_overtime-error").css("color", "red");
          $("#salary_overtime-error").text(
            view.jsontranslate["Mức lương phải lớn hơn 0"]
          );
          isValid = false;
        }
      } else {
        $("#salary_overtime-error").css("color", "red");
        $("#salary_overtime-error").text(
          view.jsontranslate["Chưa điền mức lương"]
        );
        isValid = false;
      }
      //Validate mức lương thứ 7
      if (salary_saturday_overtime != "") {
        if (salary_saturday_overtime_type == "money") {
          if (parseInt(salary_saturday_overtime) <= 0) {
            $("#salary_saturday_overtime-error").css("color", "red");
            $("#salary_saturday_overtime-error").text(
              view.jsontranslate["Mức lương phải lớn hơn 0"]
            );
            isValid = false;
          }
        } else {
          if (
            parseInt(salary_saturday_overtime) < 100 ||
            parseInt(salary_saturday_overtime) > 900
          ) {
            $("#salary_saturday_overtime-error").css("color", "red");
            $("#salary_saturday_overtime-error").text(
              view.jsontranslate["Mức lương chỉ từ 100 - 900 %"]
            );
            isValid = false;
          }
        }
      } else {
        $("#salary_saturday_overtime-error").css("color", "red");
        $("#salary_saturday_overtime-error").text(
          view.jsontranslate["Chưa điền mức lương"]
        );
        isValid = false;
      }
      //Validate mức lương thứ cn
      if (salary_sunday_overtime != "") {
        if (salary_sunday_overtime_type == "money") {
          if (parseInt(salary_sunday_overtime) <= 0) {
            $("#salary_sunday_overtime-error").css("color", "red");
            $("#salary_sunday_overtime-error").text(
              view.jsontranslate["Mức lương phải lớn hơn 0"]
            );
            isValid = false;
          }
        } else {
          if (
            parseInt(salary_sunday_overtime) < 100 ||
            parseInt(salary_sunday_overtime) > 900
          ) {
            $("#salary_sunday_overtime-error").css("color", "red");
            $("#salary_sunday_overtime-error").text(
              view.jsontranslate["Mức lương chỉ từ 100 - 900 %"]
            );
            isValid = false;
          }
        }
      } else {
        $("#salary_sunday_overtime-error").css("color", "red");
        $("#salary_sunday_overtime-error").text(
          view.jsontranslate["Chưa điền mức lương"]
        );
        isValid = false;
      }
      //Validate mức lương ngày lễ
      if (salary_holiday_overtime != "") {
        if (salary_holiday_overtime_type == "money") {
          if (parseInt(salary_holiday_overtime) <= 0) {
            $("#salary_holiday_overtime-error").css("color", "red");
            $("#salary_holiday_overtime-error").text(
              view.jsontranslate["Mức lương phải lớn hơn 0"]
            );
            isValid = false;
          }
        } else {
          if (parseInt(salary_sunday_overtime) < 100 || parseInt(salary_sunday_overtime) > 900) {
            $("#salary_holiday_overtime-error").css("color", "red");
            $("#salary_holiday_overtime-error").text(
              view.jsontranslate["Mức lương chỉ từ 100 - 900 %"]
            );
            isValid = false;
          }
        }
      } else {
        $("#salary_holiday_overtime-error").css("color", "red");
        $("#salary_holiday_overtime-error").text(
          view.jsontranslate["Chưa điền mức lương"]
        );
        isValid = false;
      }

      //Validate staff_salary_unit_code
      if (staff_salary_unit_code != "") {
      } else {
        $(".error-staff_salary_unit_code").css("color", "red");
        $(".error-staff_salary_unit_code").text(
          view.jsontranslate["Chưa chọn đơn vị tiền tệ"]
        );
        $(".error-staff_salary_unit_code").text("Chưa chọn đơn vị tiền tệ");
        isValid = false;
      }
    }
    if (!isValid) {
      return false;
    }
    $.ajax({
      url: laroute.route("staff-salary.template.ajax-create"),
      method: "POST",
      dataType: "JSON",
      data: {
        staff_salary_template_name: $("#modal_staff_salary_template_name").val(),
        staff_salary_pay_period_code: $("#modal_staff_salary_pay_period_code").val(),
        payment_type: $("#modal_payment_type").val(),
        staff_salary_type_code: $("#modal_staff_salary_type_code").val(),
        staff_salary_unit_code: $("#modal_staff_salary_unit_code").val(),
        salary_default: salary_default,
        salary_saturday_default: salary_saturday_default,
        salary_saturday_default_type: salary_saturday_default_type,
        salary_sunday_default: salary_sunday_default,
        salary_sunday_default_type: salary_sunday_default_type,
        salary_holiday_default: salary_holiday_default,
        salary_holiday_default_type: salary_holiday_default_type,
        is_overtime: isOverTime,
        salary_overtime: salary_overtime,
        salary_saturday_overtime: salary_saturday_overtime,
        salary_saturday_overtime_type: salary_saturday_overtime_type,
        salary_sunday_overtime: salary_sunday_overtime,
        salary_sunday_overtime_type: salary_sunday_overtime_type,
        salary_holiday_overtime: salary_holiday_overtime,
        salary_holiday_overtime_type: salary_holiday_overtime_type,
        is_allowance: isAllowance,
        arrayAllowance: arrayAllowance,
      },
      success: function (res) {
          if (res.error == false) {
              swal(res.message, "", "success").then(function (result) {
                  if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                      $('.popup-create').modal('hide');
                  }
                  if (result.value == true) {
                      $('.popup-create').modal('hide');

                  }
                  $('.staff_salary_template_id_input').replaceWith(res.staff_salary_template_id_input);
                  $('[name=staff_salary_template_id]').val(res.staff_salary_template_id);
                  $('[name=staff_salary_template_id]').trigger('change');
                  $('[name=staff_salary_template_id]').select2({
                      placeholder:jsonLang['Chọn mẫu áp dụng'],
                      width: 'calc(100% - 30px)'
                  });
               
              });
          } else {
              swal(res.message, '', "error");
          }
      },
      error: function (res) {
        var mess_error = "";
        $.map(res.responseJSON.errors, function (a) {
          mess_error = mess_error.concat(a + "<br/>");
        });
        swal(jsonLang["Thêm thất bại"], mess_error, "error");
      },
    });
  },
  // ajaxCreate: function () {
  //     var form = $('#form-register-2');

  //     form.validate({
  //         rules: {
  //             staff_salary_template_name: {
  //                 required: true,
  //                 maxlength: 190
  //             },
  //             staff_salary_pay_period_code: {
  //                 required: true
  //             },
  //             payment_type: {
  //                 required: true
  //             },
  //             staff_salary_type_code: {
  //                 required: true
  //             },
  //             staff_salary_unit_code: {
  //                 required: true
  //             },
  //             salary_default: {
  //                 required: true
  //             }
  //         },
  //         messages: {
  //             staff_salary_template_name: {
  //                 required: jsonLang['Hãy nhập tên mẫu lương'],
  //                 maxlength: jsonLang['Tên mẫu lương tối đa 190 kí tự']
  //             },
  //             staff_salary_pay_period_code: {
  //                 required: jsonLang['Hãy chọn kỳ hạn trả lương']
  //             },
  //             payment_type: {
  //                 required: jsonLang['Hãy chọn hình thức trả lương']
  //             },
  //             staff_salary_type_code: {
  //                 required: jsonLang['Hãy chọn loại lương']
  //             },
  //             staff_salary_unit_code: {
  //                 required: jsonLang['Hãy chọn đơn vị tiền tệ']
  //             },
  //             salary_default: {
  //                 required: jsonLang['Hãy nhập mức lương']
  //             }
  //         },
  //     });

  //     if (!form.valid()) {
  //         return false;
  //     }

  //     var isOverTime = 0;

  //     if ($(form).find('#is_overtime').is(':checked')) {
  //         isOverTime = 1;
  //     }

  //     var isAllowance = 0;

  //     if ($(form).find('#is_allowance').is(':checked')) {
  //         isAllowance = 1;
  //     }

  //     var arrayAllowance = [];

  //     //Lấy data phụ cấp
  //     $.each($(form).find('#table_allowance').find(".tr_allowance"), function () {
  //         var salaryAllowanceId = $(this).find($('.salary_allowance_id')).val();
  //         var staffSalaryAllowanceNum = $(this).find($('.staff_salary_allowance_num')).val().replace(new RegExp('\\,', 'g'), '');

  //         arrayAllowance.push({
  //             'salary_allowance_id': salaryAllowanceId,
  //             'staff_salary_allowance_num': staffSalaryAllowanceNum
  //         });
  //     });

  // $.ajax({
  //     url: laroute.route('staff-salary.template.ajax-create'),
  //     method: 'POST',
  //     dataType: 'JSON',
  //     data: {
  //         staff_salary_template_name: $(form).find('#staff_salary_template_name').val(),
  //         staff_salary_pay_period_code: $(form).find('#staff_salary_pay_period_code').val(),
  //         payment_type: $(form).find('[name=payment_type]').val(),
  //         staff_salary_type_code: $(form).find('#staff_salary_type_code').val(),
  //         staff_salary_unit_code: $(form).find('#staff_salary_unit_code').val(),
  //         salary_default: $(form).find('#salary_default').val().replace(new RegExp('\\,', 'g'), ''),
  //         salary_saturday_default: $(form).find(".salary_not_month")[0] ? $(form).find('#salary_saturday_default').val().replace(new RegExp('\\,', 'g'), '') : null,
  //         salary_saturday_default_type: $(form).find(".salary_not_month")[0] ? $(form).find('input[name=salary_saturday_default_type]:checked').val() : null,
  //         salary_sunday_default: $(form).find(".salary_not_month")[0] ? $(form).find('#salary_sunday_default').val().replace(new RegExp('\\,', 'g'), '') : null,
  //         salary_sunday_default_type: $(form).find(".salary_not_month")[0] ? $(form).find('input[name=salary_sunday_default_type]:checked').val() : null,
  //         salary_holiday_default: $(form).find(".salary_not_month")[0] ? $(form).find('#salary_holiday_default').val().replace(new RegExp('\\,', 'g'), '') : null,
  //         salary_holiday_default_type: $(form).find(".salary_not_month")[0] ? $(form).find('input[name=salary_holiday_default_type]:checked').val() : null

  //     },
  //     success: function (res) {
  //         if (res.error == false) {
  //             swal(res.message, "", "success").then(function (result) {
  //                 if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
  //                     $('.popup-create').modal('hide');
  //                 }
  //                 if (result.value == true) {
  //                     $('.popup-create').modal('hide');

  //                 }
  //                 $('.staff_salary_template_id_input').replaceWith(res.staff_salary_template_id_input);
  //                 $('[name=staff_salary_template_id]').val(res.staff_salary_template_id);
  //                 $('[name=staff_salary_template_id]').trigger('change');
  //                 $('[name=staff_salary_template_id]').select2({
  //                     placeholder:jsonLang['Chọn mẫu áp dụng'],
  //                     width: 'calc(100% - 30px)'
  //                 });

  //             });
  //         } else {
  //             swal(res.message, '', "error");
  //         }
  //     },
  //     error: function (res) {
  //         var mess_error = '';
  //         $.map(res.responseJSON.errors, function (a) {
  //             mess_error = mess_error.concat(a + '<br/>');
  //         });
  //         swal(jsonLang['Thêm thất bại'], mess_error, "error");
  //     }
  // });
  // },
  update: function (templateId) {
    var form = $("#form-edit");

    form.validate({
      rules: {
        staff_salary_template_name: {
          required: true,
          maxlength: 190,
        },
        staff_salary_pay_period_code: {
          required: true,
        },
        payment_type: {
          required: true,
        },
        staff_salary_type_code: {
          required: true,
        },
        staff_salary_unit_code: {
          required: true,
        },
      },
      messages: {
        staff_salary_template_name: {
          required: jsonLang["Hãy nhập tên mẫu lương"],
          maxlength: jsonLang["Tên mẫu lương tối đa 190 kí tự"],
        },
        staff_salary_pay_period_code: {
          required: jsonLang["Hãy chọn kỳ hạn trả lương"],
        },
        payment_type: {
          required: jsonLang["Hãy chọn hình thức trả lương"],
        },
        staff_salary_type_code: {
          required: jsonLang["Hãy chọn loại lương"],
        },
        staff_salary_unit_code: {
          required: jsonLang["Hãy chọn đơn vị tiền tệ"],
        },
      },
    });

    if (!form.valid()) {
      return false;
    }

    var isActive = 0;

    if ($("#is_actived").is(":checked")) {
      isActive = 1;
    }

    var isOverTime = 0;

    if ($("#is_overtime").is(":checked")) {
      isOverTime = 1;
    }

    var isAllowance = 0;

    if ($("#is_allowance").is(":checked")) {
      isAllowance = 1;
    }

    var arrayAllowance = [];

    //Lấy data phụ cấp
    $.each($("#table_allowance").find(".tr_allowance"), function () {
      var salaryAllowanceId = $(this).find($(".salary_allowance_id")).val();
      var staffSalaryAllowanceNum = $(this)
        .find($(".staff_salary_allowance_num"))
        .val()
        .replace(new RegExp("\\,", "g"), "");

      arrayAllowance.push({
        salary_allowance_id: salaryAllowanceId,
        staff_salary_allowance_num: staffSalaryAllowanceNum,
      });
    });
    var salary_default = $("#salary_default")
      .val()
      .replace(new RegExp("\\,", "g"), "");
    var salary_saturday_default = "";
    var salary_saturday_default_type = "";
    var salary_sunday_default = "";
    var salary_sunday_default_type = "";
    var salary_holiday_default = "";
    var salary_holiday_default_type = "";
    var salary_overtime = "";
    var salary_saturday_overtime = "";
    var salary_saturday_overtime_type = "";
    var salary_sunday_overtime = "";
    var salary_sunday_overtime_type = "";
    var salary_holiday_overtime = "";
    var salary_holiday_overtime_type = "";
    var isValid = true;
    if ($("#staff_salary_type_code").val() != "monthly") {
      salary_saturday_default = $(".salary_not_month")[0]
        ? $("#salary_saturday_default")
            .val()
            .replace(new RegExp("\\,", "g"), "")
        : null;
      salary_saturday_default_type = $(".salary_not_month")[0]
        ? $("input[name=salary_saturday_default_type]:checked").val()
        : null;
      salary_sunday_default = $(".salary_not_month")[0]
        ? $("#salary_sunday_default").val().replace(new RegExp("\\,", "g"), "")
        : null;
      salary_sunday_default_type = $(".salary_not_month")[0]
        ? $("input[name=salary_sunday_default_type]:checked").val()
        : null;
      salary_holiday_default = $(".salary_not_month")[0]
        ? $("#salary_holiday_default").val().replace(new RegExp("\\,", "g"), "")
        : null;
      salary_holiday_default_type = $(".salary_not_month")[0]
        ? $("input[name=salary_holiday_default_type]:checked").val()
        : null;
      if (salary_default != "") {
        if (parseInt(salary_default) <= 0) {
          $("#salary_default-error").css("color", "red");
          $("#salary_default-error").text(jsonLang["Mức lương phải lớn hơn 0"]);
          isValid = false;
        }
      } else {
        $("#salary_default-error").css("color", "red");
        $("#salary_default-error").text(jsonLang["Chưa điền mức lương"]);
        isValid = false;
      }
      //Validate mức lương thứ 7
      if (salary_saturday_default != "") {
        if (salary_saturday_default_type == "money") {
          if (parseInt(salary_saturday_default) <= 0) {
            $("#salary_saturday_default-error").css("color", "red");
            $("#salary_saturday_default-error").text(
              jsonLang["Mức lương phải lớn hơn 0"]
            );
            isValid = false;
          }
        } else {
          if (
            parseInt(salary_saturday_default) < 100 ||
            parseInt(salary_saturday_default) > 900
          ) {
            $("#salary_saturday_default-error").css("color", "red");
            $("#salary_saturday_default-error").text(
              jsonLang["Mức lương chỉ từ 100 - 900 %"]
            );
            isValid = false;
          }
        }
      } else {
        $("#salary_saturday_default-error").css("color", "red");
        $("#salary_saturday_default-error").text(
          jsonLang["Chưa điền mức lương"]
        );
        isValid = false;
      }

      //Validate mức lương cn
      if (salary_sunday_default != "") {
        if (salary_sunday_default_type == "money") {
          if (parseInt(salary_sunday_default) <= 0) {
            $("#salary_sunday_default-error").css("color", "red");
            $("#salary_sunday_default-error").text(
              jsonLang["Mức lương phải lớn hơn 0"]
            );
            isValid = false;
          }
        } else {
          if (
            parseInt(salary_sunday_default) < 100 ||
            parseInt(salary_sunday_default) > 900
          ) {
            $("#salary_sunday_default-error").css("color", "red");
            $("#salary_sunday_default-error").text(
              jsonLang["Mức lương chỉ từ 100 - 900 %"]
            );
            isValid = false;
          }
        }
      } else {
        $("#salary_sunday_default-error").css("color", "red");
        $("#salary_sunday_default-error").text(jsonLang["Chưa điền mức lương"]);
        isValid = false;
      }
      //Validate mức lương cn
      if (salary_holiday_default != "") {
        if (salary_holiday_default_type == "money") {
          if (parseInt(salary_holiday_default) <= 0) {
            $("#salary_holiday_default-error").css("color", "red");
            $("#salary_holiday_default-error").text(
              jsonLang["Mức lương phải lớn hơn 0"]
            );
            isValid = false;
          }
        } else {
          if (
            parseInt(salary_holiday_default) < 100 ||
            parseInt(salary_holiday_default) > 900
          ) {
            $("#salary_holiday_default-error").css("color", "red");
            $("#salary_holiday_default-error").text(
              jsonLang["Mức lương chỉ từ 100 - 900 %"]
            );
            isValid = false;
          }
        }
      } else {
        $("#salary_holiday_default-error").css("color", "red");
        $("#salary_holiday_default-error").text(
          jsonLang["Chưa điền mức lương"]
        );
        isValid = false;
      }
    }
    if (isOverTime == 1) {
      $('#table_overtime tbody > tr').each(function () {
          salary_overtime = $(this).find("td:eq(1) input[type='text']").val().replace(',', '');
          salary_saturday_overtime = $(this).find("td:eq(2) input[type='text']").val().replace(',', '');
          staff_salary_overtime_saturday = $(this).find("td:eq(3) input[type='text']").val().replace(',', '');
          salary_sunday_overtime = $(this).find("td:eq(4) input[type='text']").val().replace(',', '');
          salary_holiday_overtime =$(this).find("#salary_sunday_overtime").val().replace(',', '');
          salary_holiday_overtime_type = $(this).find("input[name=salary_holiday_overtime_type]:checked").val();
          salary_saturday_overtime_type = $(this).find("input[name=salary_saturday_overtime_type]:checked").val();
          salary_sunday_overtime_type = $(this).find("input[name=salary_sunday_overtime_type]:checked").val();
      });
      console.log(salary_saturday_overtime_type);
      if (salary_overtime != "") {
        if (parseInt(salary_overtime) <= 0) {
          $("#salary_overtime-error").css("color", "red");
          $("#salary_overtime-error").text(
            view.jsontranslate["Mức lương phải lớn hơn 0"]
          );
          isValid = false;
        }
      } else {
        $("#salary_overtime-error").css("color", "red");
        $("#salary_overtime-error").text(
          view.jsontranslate["Chưa điền mức lương"]
        );
        isValid = false;
      }
      //Validate mức lương thứ 7
      if (salary_saturday_overtime != "") {
        if (salary_saturday_overtime_type == "money") {
          if (parseInt(salary_saturday_overtime) <= 0) {
            $("#salary_saturday_overtime-error").css("color", "red");
            $("#salary_saturday_overtime-error").text(
              view.jsontranslate["Mức lương phải lớn hơn 0"]
            );
            isValid = false;
          }
        } else {
          if (
            parseInt(salary_saturday_overtime) < 100 ||
            parseInt(salary_saturday_overtime) > 900
          ) {
            $("#salary_saturday_overtime-error").css("color", "red");
            $("#salary_saturday_overtime-error").text(
              view.jsontranslate["Mức lương chỉ từ 100 - 900 %"]
            );
            isValid = false;
          }
        }
      } else {
        $("#salary_saturday_overtime-error").css("color", "red");
        $("#salary_saturday_overtime-error").text(
          view.jsontranslate["Chưa điền mức lương"]
        );
        isValid = false;
      }
      console.log('salary_sunday_overtime: ' + salary_sunday_overtime);
      console.log('salary_sunday_overtime_type: ' + salary_sunday_overtime_type);
      //Validate mức lương thứ cn
      if (salary_sunday_overtime != "") {
        if (salary_sunday_overtime_type == "money") {
          if (parseInt(salary_sunday_overtime) <= 0) {
            $("#salary_sunday_overtime-error").css("color", "red");
            $("#salary_sunday_overtime-error").text(
              view.jsontranslate["Mức lương phải lớn hơn 0"]
            );
            isValid = false;
          }
        } else {
          if (
            parseInt(salary_sunday_overtime) < 100 ||
            parseInt(salary_sunday_overtime) > 900
          ) {
            $("#salary_sunday_overtime-error").css("color", "red");
            $("#salary_sunday_overtime-error").text(
              view.jsontranslate["Mức lương chỉ từ 100 - 900 %"]
            );
            isValid = false;
          }
        }
      } else {
        $("#salary_sunday_overtime-error").css("color", "red");
        $("#salary_sunday_overtime-error").text(
          view.jsontranslate["Chưa điền mức lương"]
        );
        isValid = false;
      }
      //Validate mức lương ngày lễ
      if (salary_holiday_overtime != "") {
        if (salary_holiday_overtime_type == "money") {
          if (parseInt(salary_holiday_overtime) <= 0) {
            $("#salary_holiday_overtime-error").css("color", "red");
            $("#salary_holiday_overtime-error").text(
              view.jsontranslate["Mức lương phải lớn hơn 0"]
            );
            isValid = false;
          }
        } else {
          if (
            parseInt(salary_sunday_overtime) < 100 ||
            parseInt(salary_sunday_overtime) > 900
          ) {
            $("#salary_holiday_overtime-error").css("color", "red");
            $("#salary_holiday_overtime-error").text(
              view.jsontranslate["Mức lương chỉ từ 100 - 900 %"]
            );
            isValid = false;
          }
        }
      } else {
        $("#salary_holiday_overtime-error").css("color", "red");
        $("#salary_holiday_overtime-error").text(
          view.jsontranslate["Chưa điền mức lương"]
        );
        isValid = false;
      }

      //Validate staff_salary_unit_code
      if (staff_salary_unit_code != "") {
      } else {
        $(".error-staff_salary_unit_code").css("color", "red");
        $(".error-staff_salary_unit_code").text(
          view.jsontranslate["Chưa chọn đơn vị tiền tệ"]
        );
        $(".error-staff_salary_unit_code").text("Chưa chọn đơn vị tiền tệ");
        isValid = false;
      }
    }
    if (!isValid) {
      return false;
    }
    $.ajax({
      url: laroute.route("staff-salary.template.update"),
      method: "POST",
      dataType: "JSON",
      data: {
        staff_salary_template_id: templateId,
        staff_salary_template_name: $("#staff_salary_template_name").val(),
        staff_salary_pay_period_code: $("#staff_salary_pay_period_code").val(),
        payment_type: $("#payment_type").val(),
        staff_salary_type_code: $("#staff_salary_type_code").val(),
        staff_salary_unit_code: $("#staff_salary_unit_code").val(),
        salary_default: salary_default,
        salary_saturday_default: salary_saturday_default,
        salary_saturday_default_type: salary_saturday_default_type,
        salary_sunday_default: salary_sunday_default,
        salary_sunday_default_type: salary_sunday_default_type,
        salary_holiday_default: salary_holiday_default,
        salary_holiday_default_type: salary_holiday_default_type,
        is_overtime: isOverTime,
        salary_overtime: salary_overtime,
        salary_saturday_overtime: salary_saturday_overtime,
        salary_saturday_overtime_type: salary_saturday_overtime_type,
        salary_sunday_overtime: salary_sunday_overtime,
        salary_sunday_overtime_type: salary_sunday_overtime_type,
        salary_holiday_overtime: salary_holiday_overtime,
        salary_holiday_overtime_type: salary_holiday_overtime_type,
        is_allowance: isAllowance,
        arrayAllowance: arrayAllowance,
        is_actived: isActive,
      },
      success: function (res) {
        if (res.error == false) {
          swal(res.message, "", "success").then(function (result) {
            if (result.dismiss == "esc" || result.dismiss == "backdrop") {
              window.location.href = laroute.route("staff-salary.template");
            }
            if (result.value == true) {
              window.location.href = laroute.route("staff-salary.template");
            }
          });
        } else {
          swal(res.message, "", "error");
        }
      },
      error: function (res) {
        var mess_error = "";
        $.map(res.responseJSON.errors, function (a) {
          mess_error = mess_error.concat(a + "<br/>");
        });
        swal(jsonLang["Chỉnh sửa thất bại"], mess_error, "error");
      },
    });
  },
  showModalAddTemplate: function () {

    $.ajax({
      url: laroute.route("staff-salary.template.pop-create-modal"),
      method: "POST",
      dataType: "JSON",
      data: {},
      success: function (res) {
        $("#modal-template-add").html(res.html);
        $("#popup-create").modal("show");
        new AutoNumeric.multiple('.numeric, .numeric_child', {
          currencySymbol: '',
          decimalCharacter: '.',
          digitGroupSeparator: ',',
          decimalPlaces: decimal_number,
          eventIsCancelable: true,
          minimumValue: 0
      });
        $('[name=modal_staff_salary_pay_period_code]').select2({
            placeholder:jsonLang['Chọn kỳ hạn trả lương'],
            width: '100%'
        });
        $('[name=modal_payment_type]').select2({
            placeholder:jsonLang['Chọn hình thức trả lương'],
            width: '100%'
        });
        $('[name=modal_staff_salary_template_id]').select2({
            placeholder:jsonLang['Chọn mẫu áp dụng'],
            width: 'calc(100% - 30px)'
        });
        $('[name=modal_staff_salary_type_code]').select2({
            placeholder:jsonLang['Chọn loại lương'],
            width: '100%'
        });
        $('[name=modal_staff_salary_unit_code]').select2({
            placeholder:jsonLang['Chọn đơn vị tiền tệ'],
            width: '100%'
        });
        var salaryTypeName = $("#modal_staff_salary_type_code  option:selected").text();
        var salaryUnitName = $("#modal_staff_salary_unit_code  option:selected").text();
        $(".text_type_default").text(salaryUnitName + "/ " + salaryTypeName); 
        $(".text_type_overtime").text(salaryUnitName + "/ " + jsonLang['Giờ']); 
      },
    });
  },
  chooseUnitAndTypeModal: function (e) {
  
    var salaryTypeName = $("#modal_staff_salary_type_code  option:selected").text();
    var salaryUnitName = $("#modal_staff_salary_unit_code  option:selected").text();
    
    // $(".salary-unit-name").text(salaryUnitName);
    $(".salary-type-name").text(salaryTypeName);

    $(".text_type_default").text(salaryUnitName + "/ " + salaryTypeName);
    $(".text_type_overtime").text(salaryUnitName + "/ " + jsonLang["Giờ"]);

    //Lấy value của loại lương
    var salaryTypeCode =  $('[name=modal_staff_salary_type_code]').val();
   
    $(".salary_not_month").remove();
      if (salaryTypeCode == "monthly") {
        //Theo tháng
        $("#modal_staff_salary_pay_period_code")
          .val("pay_month")
          .trigger("change")
          .attr("disabled", true);

     
        var tpl = $("#head-table-overtime-tpl").html();
        $("#table_overtime > thead > tr").append(tpl);

        var tpl = $("#body-table-overtime-tpl").html();
        $("#table_overtime > tbody > tr").append(tpl);
      } else {
        $("#modal_staff_salary_pay_period_code")
          .val("")
          .trigger("change")
          .attr("disabled", false);

        //Class này không tồn tại thì mới append zo
        var tpl = $("#head-table-default-tpl").html();
        $("#table_default > thead > tr").append(tpl);

        var tpl = $("#body-table-default-tpl").html();
        $("#table_default > tbody > tr").append(tpl);

        var tpl = $("#head-table-overtime-tpl").html();
        $("#table_overtime > thead > tr").append(tpl);

        var tpl = $("#body-table-overtime-tpl").html();
        $("#table_overtime > tbody > tr").append(tpl);

      }
      new AutoNumeric.multiple(".numeric_child", {
        currencySymbol: "",
        decimalCharacter: ".",
        digitGroupSeparator: ",",
        decimalPlaces: decimal_number,
        eventIsCancelable: true,
        minimumValue: 0,
      });
  },
};

$(document).ready(function () {
  view.jsontranslate = JSON.parse(localStorage.getItem("tranlate"));
  new AutoNumeric.multiple(".numeric", {
    currencySymbol: "",
    decimalCharacter: ".",
    digitGroupSeparator: ",",
    decimalPlaces: decimal_number,
    eventIsCancelable: true,
    minimumValue: 0,
  });
});
