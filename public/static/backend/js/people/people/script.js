var index = {
  importExcel: function () {
    $("#modal-excel").modal("show");
    $("#show").val("");
    $("input[type=file]").val("");
  },
  importSubmit: function () {
    var file_data = $("#file_excel").prop("files")[0];
    var form_data = new FormData();
    form_data.append("file", file_data);

    $.ajax({
      url: laroute.route("people.people.import-excel"),
      method: "POST",
      data: form_data,
      contentType: false,
      cache: false,
      processData: false,
      success: function (res) {
        if (res.success == 1) {
          swal(res.message, "", "success");
          $(".submit_list_people").trigger("click");

          if (res.number_error > 0) {
            $(".export_error").css("display", "block");
            $("#data_error").empty();

            $.map(res.data_error, function (val) {
              var tpl = $("#tpl-data-error").html();
              tpl = tpl.replace(/{code}/g, val.code);
              tpl = tpl.replace(/{full_name}/g, val.full_name);
              tpl = tpl.replace(/{gender}/g, val.gender);
              tpl = tpl.replace(/{id_number}/g, val.id_number);
              tpl = tpl.replace(/{id_license_date}/g, val.id_license_date);
              tpl = tpl.replace(
                /{people_id_license_place}/g,
                val.people_id_license_place
              );
              tpl = tpl.replace(/{birth_day}/g, val.birth_day);
              tpl = tpl.replace(/{birth_month}/g, val.birth_month);
              tpl = tpl.replace(/{birth_year}/g, val.birth_year);
              tpl = tpl.replace(/{permanent_address}/g, val.permanent_address);
              tpl = tpl.replace(/{temporary_address}/g, val.temporary_address);
              tpl = tpl.replace(/{birthplace}/g, val.birthplace);
              tpl = tpl.replace(/{hometown}/g, val.hometown);
              tpl = tpl.replace(/{people_group}/g, val.people_group);
              tpl = tpl.replace(/{people_quarter}/g, val.people_quarter);
              tpl = tpl.replace(/{ethnic}/g, val.ethnic);
              tpl = tpl.replace(/{religion}/g, val.religion);
              tpl = tpl.replace(/{people_family}/g, val.people_family);
              tpl = tpl.replace(/{educational_level}/g, val.educational_level);

              tpl = tpl.replace(/{graduation_year}/g, val.graduation_year);
              tpl = tpl.replace(/{specialized}/g, val.specialized);
              tpl = tpl.replace(/{foreign_language}/g, val.foreign_language);
              tpl = tpl.replace(/{union_join_date}/g, val.union_join_date);
              tpl = tpl.replace(/{group_join_date}/g, val.group_join_date);

              tpl = tpl.replace(/{people_job}/g, val.people_job);
              tpl = tpl.replace(/{elementary_school}/g, val.elementary_school);
              tpl = tpl.replace(/{middle_school}/g, val.middle_school);
              tpl = tpl.replace(/{high_school}/g, val.high_school);
              tpl = tpl.replace(/{from_18_to_21}/g, val.from_18_to_21);
              tpl = tpl.replace(/{from_21_to_now}/g, val.from_21_to_now);
              tpl = tpl.replace(/{full_name_dad}/g, val.full_name_dad);
              tpl = tpl.replace(/{birth_year_dad}/g, val.birth_year_dad);
              tpl = tpl.replace(/{job_dad}/g, val.job_dad);
              tpl = tpl.replace(/{before_30_04_dad}/g, val.before_30_04_dad);
              tpl = tpl.replace(/{after_30_04_dad}/g, val.after_30_04_dad);
              tpl = tpl.replace(/{current_dad}/g, val.current_dad);
              tpl = tpl.replace(/{full_name_mom}/g, val.full_name_mom);
              tpl = tpl.replace(/{birth_year_mom}/g, val.birth_year_mom);
              tpl = tpl.replace(/{job_mom}/g, val.job_mom);
              tpl = tpl.replace(/{before_30_04_mom}/g, val.before_30_04_mom);
              tpl = tpl.replace(/{full_name_mom}/g, val.full_name_mom);
              tpl = tpl.replace(/{birth_year_mom}/g, val.birth_year_mom);
              tpl = tpl.replace(/{job_mom}/g, val.job_mom);
              tpl = tpl.replace(/{before_30_04_mom}/g, val.before_30_04_mom);
              tpl = tpl.replace(/{after_30_04_mom}/g, val.after_30_04_mom);
              tpl = tpl.replace(/{current_mom}/g, val.current_mom);
              tpl = tpl.replace(/{info_brother_1}/g, val.info_brother_1);
              tpl = tpl.replace(/{info_brother_2}/g, val.info_brother_2);
              tpl = tpl.replace(/{info_brother_3}/g, val.info_brother_3);
              tpl = tpl.replace(/{info_brother_4}/g, val.info_brother_4);
              tpl = tpl.replace(/{info_brother_5}/g, val.info_brother_5);
              tpl = tpl.replace(/{info_brother_6}/g, val.info_brother_6);
              tpl = tpl.replace(/{full_name_couple}/g, val.full_name_couple);
              tpl = tpl.replace(/{birth_year_couple}/g, val.birth_year_couple);
              tpl = tpl.replace(/{job_couple}/g, val.job_couple);
              tpl = tpl.replace(/{info_child_1}/g, val.info_child_1);
              tpl = tpl.replace(/{info_child_2}/g, val.info_child_2);
              tpl = tpl.replace(/{error}/g, val.error);

              $("#data_error").append(tpl);
            });

            //Download file lỗi sẵn
            $("#form-error").submit();
            // get the segments
            pathArray = window.location.pathname.split("/");
            // find where the segment is located
            indexOfSegment = pathArray.indexOf("");
            // make base_url be the origin plus the path to the segment
            //var base_url = window.location.origin + pathArray.slice(0,indexOfSegment).join('/') + '/';
            //$('#link_download_excel').attr('href',res.file_url);
            //window.location.href = res.file_url;

            //alert(window.location.origin+"uploads/export-error-people.xlsx");
          } else {
            $(".export_error").css("display", "none");
            $("#data_error").empty();
          }
        } else {
          swal(res.message, "", "error");
        }
      },
    });
  },
  fileName: function () {
    var fileNamess = $("input[type=file]").val();
    $("#show").val(fileNamess);
  },
  closeModalImport: function () {
    $("#modal-excel").modal("hide");

    $(".submit_list_people").trigger("click");
  },
  choosePeople: function (obj) {
    var arrayPeopleId = [];

    arrayPeopleId.push(
      $(obj).closest(".tr_people").find($(".people_id")).val()
    );

    if ($(obj).is(":checked")) {
      $.ajax({
        url: laroute.route("people.people.choose-people"),
        method: "POST",
        dataType: "JSON",
        data: {
          arrayPeopleId: arrayPeopleId,
        },
        success: function (res) {
          $(".total_choose_people").text("(" + res.count_choose + ")");
        },
      });
    } else {
      $.ajax({
        url: laroute.route("people.people.un-choose-people"),
        method: "POST",
        dataType: "JSON",
        data: {
          arrayPeopleId: arrayPeopleId,
        },
        success: function (res) {
          $(".total_choose_people").text("(" + res.count_choose + ")");
        },
      });
    }
  },
  chooseAll: function (obj) {
    var arrayPeopleId = [];

    if ($(obj).is(":checked")) {
      $(".check_one").prop("checked", true);

      $(".check_one").each(function () {
        arrayPeopleId.push($(this).parents("label").find(".people_id").val());
      });

      $.ajax({
        url: laroute.route("people.people.choose-people"),
        method: "POST",
        dataType: "JSON",
        data: {
          arrayPeopleId: arrayPeopleId,
        },
        success: function (res) {
          $(".total_choose_people").text("(" + res.count_choose + ")");
        },
      });
    } else {
      $(".check_one").prop("checked", false);

      $(".check_one").each(function () {
        arrayPeopleId.push($(this).parents("label").find(".people_id").val());
      });

      $.ajax({
        url: laroute.route("people.people.un-choose-people"),
        method: "POST",
        dataType: "JSON",
        data: {
          arrayPeopleId: arrayPeopleId,
        },
        success: function (res) {
          $(".total_choose_people").text("(" + res.count_choose + ")");
        },
      });
    }
  },

  showPopCamera: function () {
    $.ajax({
      url: laroute.route("people.people.show-pop-camera"),
      method: "POST",
      dataType: "JSON",
      data: {},
      success: function (res) {
        $("#div-camera").html(res.html);
        $("#pop-camera").modal("show");
      },
    });
  },

  takeSnapshot: function () {
    // take snapshot and get image data
    Webcam.snap(function (data_uri) {
      document.getElementById("results").innerHTML =
        '<img src="' + data_uri + '"/>';
    });
  },

  saveSnapshot: function () {
    var form_data = new FormData();
    form_data.append("file", $("#results img").attr("src"));
    form_data.append("link", "_people.");
    form_data.append("is_base_64", true);

    $.ajax({
      url: laroute.route("admin.upload-image"),
      method: "POST",
      data: form_data,
      contentType: false,
      cache: false,
      processData: false,
      success: function (res) {
        if (res.error == 0) {
          $("#avatar").val(res.file);
          $("#avatar").attr("src", res.file);

          $(".img-sd").attr("src", res.file);

          $("#pop-camera").modal("hide");
        }
      },
    });
  },

  quickUpdateDetail: function (peopleId) {
    $.ajax({
      url: laroute.route("people.people.quick-update"),
      method: "PATCH",
      dataType: "JSON",
      data: {
        people_id: peopleId,
        register_nvqs: $("#register_nvqs").val(),
        date_register_nvqs: $("#date_register_nvqs").val(),
        issuer_register_nvqs: $("#issuer_register_nvqs").val(),
      },
      success: function (res) {
        if (res.error == false) {
          swal(res.message, "", "success");
        } else {
          swal(res.message, "", "error");
        }
      },
    });
  },
};
