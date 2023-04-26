var product = {
  jsonLang: JSON.parse(localStorage.getItem("tranlate")),
  remove: function (obj, id) {
    $(obj).closest("tr").addClass("m-table__row--danger");

    swal({
      title: product.jsonLang["Thông báo"],
      text: product.jsonLang["Bạn có muốn xóa không?"],
      type: "warning",
      showCancelButton: true,
      confirmButtonText: product.jsonLang["Xóa"],
      cancelButtonText: product.jsonLang["Hủy"],
      onClose: function () {
        $(obj).closest("tr").removeClass("m-table__row--danger");
      },
    }).then(function (result) {
      if (result.value) {
        $.post(
          laroute.route("admin.product.remove", { id: id }),
          function (data) {
            if (data.error == 0) {
              swal(product.jsonLang["Xóa thành công"], "", "success");
              $("#autotable").PioTable("refresh");
            } else {
              swal({
                title: product.jsonLang["Sản phẩm có tồn kho"],
                text: product.jsonLang["Bạn có muốn xóa không?"],
                type: "warning",
                showCancelButton: true,
                confirmButtonText: product.jsonLang["Có"],
                cancelButtonText: product.jsonLang["Không"],
              }).then(function (willDelete) {
                if (willDelete.value == true) {
                  $.ajax({
                    url: laroute.route(
                      "admin.product.remove-product-inventorys"
                    ),
                    data: {
                      id: id,
                    },
                    method: "POST",
                    dataType: "JSON",
                    success: function (data) {
                      if ((data.status = 1)) {
                        swal(product.jsonLang["Xóa thành công"], "", "success");
                        $("#autotable").PioTable("refresh");
                      }
                    },
                  });
                }
              });
            }
          }
        );
      }
    });
  },
  clearModalAdd: function () {
    clearAdd();
  },
  add: function (close) {
    $("#close").val(close);
    $("#formAdd").validate({
      rules: {
        product_name: { required: true },
        cost: { required: true, min: 0 },
      },
      messages: {
        product_name: "Vui lòng nhập tên sản phẩm",
        cost: {
          required: "Vui lòng nhập giá sản phẩm",
          min: "Giá phải lớn hơn 0",
        },
      },
      submitHandler: function () {
        var input = $("#close");
        $.ajax({
          url: laroute.route("admin.product.add"),
          data: {
            productCategoryId: $("#modalAdd #product_category_id").val(),
            productModelId: $("#modalAdd #product_model_id").val(),
            productName: $("#modalAdd #product_name").val(),
            productShortName: $("#modalAdd #product_short_name").val(),
            cost: $("#modalAdd #cost").val(),
            avatarApp: $("#avatar_app").val(),
            priceStandard: $("#modalAdd #price_standard").val(),
            unitId: $("#modalAdd #unit_id").val(),
            supplierId: $("#modalAdd #supplier_id").val(),
            isSales: $("#modalAdd #is_sales").val(),
            isPromo: $("#modalAdd #is_promo").val(),
            isActived: $("#modalAdd #is_actived").val(),
            type: $("#modalAdd #type").val(),
            description: $("#modalAdd #description").val(),
            close: input.val(),
          },
          method: "POST",
          dataType: "JSON",
          success: function (data) {
            if (data.message == "") {
              swal("Thêm sản phẩm thành công", "", "success");
              if (data.close != 0) {
                $("#modalAdd").modal("hide");
              }
              $("#autotable").PioTable("refresh");
              $("#err-code").text(data.message);
              clearAdd();
            } else {
              $("#err-code").css("color", "red");
              $("#err-code").text(data.message);
            }
          },
        });
      },
    });
  },
  changeStatus: function (obj, id, action) {
    $.ajax({
      url: laroute.route("admin.product.change-status"),
      method: "POST",
      data: {
        id: id,
        action: action,
      },
      dataType: "JSON",
    }).done(function (data) {
      $("#autotable").PioTable("refresh");
    });
  },
  edit: function (id) {
    $.ajax({
      url: laroute.route("admin.product.edit"),
      method: "POST",
      data: { id: id },
      dataType: "JSON",
      success: function (data) {
        $("#modalEdit").modal("show");
        $("#modalEdit #product_model_id").val(data.product_model_id);
        $("#modalEdit #product_id").val(data.product_id);
        $("#modalEdit #product_category_id").val(data.product_category_id);
        $("#modalEdit #product_name").val(data.product_name);
        $("#modalEdit #product_short_name").val(data.product_short_name);
        $("#modalEdit #unit_id").val(data.unit_id);
        $("#modalEdit #cost").val(data.cost);
        $("#modalEdit #price_standard").val(data.price_standard);
        $("#modalEdit #is_sales").val(data.is_sales);
        $("#modalEdit #is_promo").val(data.is_promo);
        $("#modalEdit #type").val(data.type);
        $("#modalEdit #description").val(data.description);
        $("#modalEdit #supplier_id").val(data.supplier_id);
        $("#modalEdit #is_actived").val(data.is_actived);
      },
    });
  },
  submitEdit: function () {
    $("#formEdit").validate({
      rules: {
        product_name: { required: true },
        cost: { required: true, min: 0 },
      },
      messages: {
        product_name: "Vui lòng nhập tên sản phẩm",
        cost: {
          required: "Vui lòng nhập giá sản phẩm",
          min: "Giá phải lớn hơn 0",
        },
      },
      submitHandler: function () {
        $.ajax({
          url: laroute.route("admin.product.submit-edit"),
          method: "POST",
          dataType: "JSON",
          data: {
            product_id: $("#modalEdit #product_id").val(),
            product_category_id: $("#modalEdit #product_category_id").val(),
            product_name: $("#modalEdit #product_name").val(),
            product_short_name: $("#modalEdit #product_short_name").val(),
            unit_id: $("#modalEdit #unit_id").val(),
            cost: $("#modalEdit #cost").val(),
            price_standard: $("#modalEdit #price_standard").val(),
            is_sales: $("#modalEdit #is_sales").val(),
            is_promo: $("#modalEdit #is_promo").val(),
            type: $("#modalEdit #type").val(),
            description: $("#modalEdit #description").val(),
            supplier_id: $("#modalEdit #supplier_id").val(),
            is_actived: $("#modalEdit #is_actived").val(),
          },
          success: function (data) {
            swal("Cập nhật sản phẩm thành công", "", "success");
            $("#modalEdit").modal("hide");
            $("#autotable").PioTable("refresh");
          },
        });
      },
    });
  },
  refresh: function () {
    $('input[name="search_keyword"]').val("");
    $(".m_selectpicker").val("").trigger("change");
    $("#created_at").val("");
    $(".btn-search").trigger("click");
  },
  search: function () {
    $(".btn-search").trigger("click");
  },
  notEnterInput: function (thi) {
    $(thi).val("");
  },
  modal_file: function () {
    $("#modal-excel").modal("show");
    $("#show").val("");
    $("input[type=file]").val("");
  },
  showNameFile: function () {
    var fileNamess = $("input[type=file]").val();
    $("#show").val(fileNamess);
  },
  import: function () {
    // mApp.block(".modal-body", {
    //     overlayColor: "#000000",
    //     type: "loader",
    //     state: "success",
    //     message: "Xin vui lòng chờ..."
    // });

    var file_data = $("#file_excel").prop("files")[0];
    var form_data = new FormData();
    form_data.append("file", file_data);
    $.ajax({
      url: laroute.route("admin.product.import-file-image"),
      method: "POST",
      data: form_data,
      contentType: false,
      cache: false,
      processData: false,
      success: function (res) {
        mApp.unblock(".modal-body");
        if (res.error == false) {
          swal(res.message, "", "success").then(function (result) {
            if (result.dismiss == "esc" || result.dismiss == "backdrop") {
              $("#modal-excel").modal("hide");
              window.location.reload();
            }
            if (result.value == true) {
              $("#modal-excel").modal("hide");
              window.location.reload();
            }
          });
        } else {
          swal(res.message, "", "error");
        }
      },
    });
  },
  modalImportProduct:function () {
    $('#modal-import-excel').modal('show');
    $('#show-import-excel').val('');
    $('#file-import-excel').val('');
  },
  showNameFileImportExcel: function () {
    var fileNamess = $("#file-import-excel").val();
    $("#show-import-excel").val(fileNamess);
  },
  importExcelProduct: function () {
    var file_data = $("#file-import-excel").prop("files")[0];
    console.log(file_data);
    var form_data = new FormData();
    form_data.append("file", file_data);
    $.ajax({
      url: laroute.route("admin.product.import-template"),
      method: "POST",
      data: form_data,
      contentType: false,
      cache: false,
      processData: false,
      success: function (res) {
        console.log(res);
        mApp.unblock(".modal-body");
        if (res.error == false) {
          swal(res.message, "", "success").then(function (result) {
            if (result.dismiss == "esc" || result.dismiss == "backdrop") {
              $("#modal-excel").modal("hide");
              window.location.reload();
            }
            if (result.value == true) {
              $("#modal-excel").modal("hide");
              window.location.reload();
            }
          });
        } else {
          swal(res.message, "", "error");
        }
      },
    });
  },
};

function clearAdd() {
  $("#product_name-error").text("");
  $("#product_code-error").text("");
  $("#cost-error").text("");
  $("#product_code").val("");
  $("#product_name").val("");
  $("#product_short_name").val("");
  $("#cost").val("");
  $("#price_standard").val("");
  $("#is_sales").val("0");
  $("#is_promo").val("0");
  $("#type").val("normal");
  $("#is_actived").val("1");
  $("#description").val("");
  $("#err-code").val("");
  $("#product_model_id option:first").prop("selected", true);
  $("#product_category_id option:first").prop("selected", true);
  $("#unit_id option:first").prop("selected", true);
  $("#supplier_id option:first").prop("selected", true);
}

$("#autotable").PioTable({
  baseUrl: laroute.route("admin.product.list"),
});
$(".create-at").datepicker({
  format: "YYYY-MM-DD",
  locale: {
    format: "DD/MM/YYYY",
    daysOfWeek: ["CN", "T2", "T3", "T4", "T5", "T6", "T7"],
    monthNames: [
      "Tháng 1 năm",
      "Tháng 2 năm",
      "Tháng 3 năm",
      "Tháng 4 năm",
      "Tháng 5 năm",
      "Tháng 6 năm",
      "Tháng 7 năm",
      "Tháng 8 năm",
      "Tháng 9 năm",
      "Tháng 10 năm",
      "Tháng 11 năm",
      "Tháng 12 năm",
    ],
    firstDay: 1,
  },
});
$("#product_category_id").change(function () {
  $.ajax({
    url: laroute.route("admin.product.search-category"),
    method: "POST",
    data: { categoryId: $("#product_category_id").val() },
  });
});

$(".check-inventory-warning").on("change", function () {
  // From the other examples
  if (this.checked) {
    $("#inventory-warning").prop("readonly", false);
    $("#inventory-warning").focus();
  } else {
    $("#inventory-warning").prop("readonly", true);
    $("#inventory-warning").val("");
  }
});
$(".js-example-data-ajax").select2({
  placeholder: product.jsonLang["Chọn chi nhánh"],
});
//Check all branch.
$("#check-all-branch").click(function () {
  if ($("#check-all-branch").is(":checked")) {
    $('select[name="branch[]"] > option').prop("selected", "selected");
    $('select[name="branch[]"]').trigger("change");
  } else {
    $('select[name="branch[]"] > option').removeAttr("selected");
    $('select[name="branch[]"]').val(null).trigger("change");
  }
});
// create array attribute group.
var arrayAttributeGroup = [0];
$(".errs").css("color", "red");
//click button add attribute group.
$("#adGroupAttribute").click(function (e) {
  $("#adGroupAttribute").prop("disabled", true);
  $("#hide-price").val($("#price").val());
  $("#hide-cost").val($("#cost").val());
  $("#hide-name").val($("#product-name").val());
  if ($('select[name="sProducAttribute[]"]').val() != null) {
    $(this)
      .parents(".col-lg-12")
      .find('select[name="selectAttrGr[]"]')
      .attr("disabled", "disabled");
  }
  var productName = $("#product-name");
  var productCode = $("#in-product-code");
  var cost = $("#cost");
  var price = $("#price");
  var branch = $('select[name="branch[]"]');
  var isInventoryWarning = $("#is-inventory-warning");
  var inventoryWarning = $("#inventory-warning");
  var errProductName = $(".error-product-name");
  var errProductCode = $(".error-product-code");
  var errCost = $(".error-cost");
  var errPrice = $(".error-price");
  var errBranch = $(".error-branch");
  var errInventoryWarning = $(".error-inventory-warning");
  if (testInputAddProduct() == true) {
    $.each($('select[name="selectAttrGr[]"] option:selected'), function () {
      arrayAttributeGroup.push($(this).val());
    });
    if (arrayAttributeGroup.length > 7) {
      $("#adGroupAttribute").prop("disabled", true);
    } else {
      $("#adGroupAttribute").prop("disabled", false);
    }
    if (productName.val().trim() != "") {
      errProductName.text("");
    }
    if (cost.val().trim() != "") {
      if (cost.val() < 0) {
        errCost.text("Vui lòng nhập lại giá nhập");
      } else {
        errCost.text("");
      }
    }
    if (price.val().trim() != "") {
      errPrice.text("");
    }
    if (branch.val() != "") {
      errBranch.text("");
    }
    if (isInventoryWarning.is(":checked")) {
      if (inventoryWarning.val().trim() == "") {
        errInventoryWarning.text("Vui lòng nhập số lượng cảnh báo tồn kho");
      }
    } else {
      errInventoryWarning.text("");
    }
    $.ajax({
      url: laroute.route("admin.product.check-name"),
      method: "POST",
      data: {
        productName: $("#product-name").val().trim(),
      },
      dataType: "JSON",
      success: function (data) {
        if (data.error == 1) {
          $(".error-product-name").text("Sản phẩm đã tồn tại");
        } else {
          $(".error-product-name").empty();
          $.ajax({
            url: laroute.route("admin.product.get-product-attribute"),
            method: "POST",
            data: {
              attributeGroupId: arrayAttributeGroup,
              cateId: $("#category").val(),
            },
            success: function (data) {
              $(".select-group-attribute").append(data);
            },
          });
          productName.prop("disabled", true);
          $("#adGroupAttribute").prop("disabled", true);
        }
      },
    });
  }
});
var general = true;
$("#in-product-code").keyup(function () {
  $.ajax({
    url: laroute.route("admin.product.test-product-code"),
    method: "POST",
    dataType: "JSON",
    data: { productCode: $(this).val() },
    success: function (data) {
      $(".error-product-code").text(data);
    },
  });
});
//save add product and child product
$(".btn-save").click(function () {
  if (testInputAddProduct() == true) {
    var flag = true;
    var category = $("#category");
    var productName = $("#product-name");
    var productCode = $("#in-product-code");
    var promo = $("#promo");
    // var isActive = $('#isActive');
    var productModel = $("#productModel");
    var supplier = $("#supplier");
    var unit = $("#unit");
    var cost = $("#cost");
    var price = $("#price");
    var nameVersion = $(".name-version");
    var branch = $('select[name="branch[]"]');
    var isInventoryWarning = $("#is-inventory-warning");
    var inventoryWarning = $("#inventory-warning");
    var errProductName = $(".error-product-name");
    var errProductCode = $(".error-product-code");
    var errCost = $(".error-cost");
    var errInventoryWarning = $(".error-inventory-warning");
    var productChilds = new Array();
    var promoCheck = 0;
    var isInventoryWarningCheck = 0;
    var costFormat = cost.val().replace(new RegExp("\\,", "g"), "");
    var priceFormat = price.val().replace(new RegExp("\\,", "g"), "");
    var isAllBranch = 0;
    var arrayAttrAndAttrGroup = new Array();
    var arrImage = new Array();
    let sale = 0;
    var description = $(".summernote").summernote("code");
    $("#temp")
      .find('input[name="fileName[]"]')
      .each(function () {
        arrImage.push($(this).val());
      });
    if ($("#check-all-branch").is(":checked")) {
      isAllBranch = 1;
    }
    if (promo.is(":checked")) {
      promoCheck = 1;
    }
    if (isInventoryWarning.is(":checked")) {
      isInventoryWarningCheck = 1;
    }
    if ($("#product-sale").is(":checked")) {
      sale = 1;
    }
    if (productName.val().trim() != "") {
      errProductName.text("");
    }
    if ($("tbody tr").length > 0) {
      $.each(
        $(
          '#add-product-version tr input[name="check-all-branch[]"]:checked'
        ).parentsUntil("tbody"),
        function () {
          var $tds = $(this).find("td input");
          $.each($tds, function () {
            productChilds.push($(this).val());
          });
        }
      );
      if (productChilds == "") {
        $(".errs-product-childs").text(
          product.jsonLang["Vui lòng chọn phiên bản để lưu lại"]
        );
        flag = false;
      }
    } else {
      $(".errs-product-childs").text("");
    }
    var arrayProductAttributeGroup = [];
    var arrayProductAttribute = [];
    $.each($('select[name="selectAttrGr[]"] option:selected'), function () {
      arrayProductAttributeGroup.push($(this).val());
    });
    errInventoryWarning.text("");
    if (flag == true && general == true) {
      $.each(
        $('select[name="sProducAttribute[]"] option:selected'),
        function () {
          let valAttr = $(this).text();
          console.log(valAttr);
          arrayProductAttribute.push(valAttr);
          let valAttrGroup = $(this)
            .parents(".new-attribute-version")
            .find('select[name="selectAttrGr[]"]')
            .val();
          let aa = valAttrGroup + "=>" + valAttr;
          arrayAttrAndAttrGroup.push(aa);
        }
      );

      $.ajax({
        url: laroute.route("admin.product.check-name"),
        method: "POST",
        data: {
          productName: $("#product-name").val().trim(),
        },
        dataType: "JSON",
        success: function (data) {
          if (data.error == 1) {
            $(".error-product-name").text(
              product.jsonLang["Sản phẩm đã tồn tại"]
            );
          } else {
            $(".error-product-name").empty();
            $.ajax({
              url: laroute.route("admin.product.submit-add"),
              method: "POST",
              data: {
                category: category.val(),
                productName: productName.val(),
                productCode: productCode.val(),
                promo: promoCheck,
                isActive: 1,
                productModel: productModel.val(),
                supplier: supplier.val(),
                unit: unit.val(),
                cost: costFormat,
                price: priceFormat,
                branch: branch.val(),
                isInventoryWarning: isInventoryWarningCheck,
                inventoryWarning: inventoryWarning.val(),
                nameVersion: nameVersion.val(),
                productAttributeGroup: arrayProductAttributeGroup,
                productChilds: productChilds,
                arrayProductAttribute: arrayProductAttribute,
                isAllBranch: isAllBranch,
                arrayAttrAndAttrGroup: arrayAttrAndAttrGroup,
                arrImage: arrImage,
                description: description,
                avatar: $("#file_name_avatar").val(),
                avatarApp: $("#avatar_app").val(),
                sale: sale,
              },
              dataType: "JSON",
              success: function (data) {
                if (data.status == true) {
                  swal("Thêm sản phẩm thành công", "", "success");
                  window.location = laroute.route("admin.product");
                } else {
                  swal("Thêm sản phẩm thất bại", "", "success");
                }
              },
            });
          }
        },
      });
    }
  }
});
$("#check-all").click(function (e) {
  $(this)
    .closest("table")
    .find("td input:checkbox")
    .prop("checked", this.checked);
});

function clearAddVersion() {
  $("#product-name").val("");
  // $("#product-name-en").val("");
  $("#in-product-code").val("");
  $("#cost").val("");
  $("#price").val("");
  $('select[name="branch[]"]').val(null).trigger("change");
}

// $('#productModel').select2();
// $('#supplier').select2();
// $('#unit').select2();

new AutoNumeric.multiple(
  "#refer_commission_value, #refer_commission_percent, #staff_commission_value, #staff_commission_percent, #deal_commission_value, #deal_commission_percent, #cost, #price",
  {
    currencySymbol: "",
    decimalCharacter: ".",
    digitGroupSeparator: ",",
    decimalPlaces: decimal_number,
    minimumValue: 0,
  }
);

function testInputAddProduct() {
  var category = $("#category");
  var productName = $("#product-name");
  var productModel = $("#productModel");
  var supplier = $("#supplier");
  var unit = $("#unit");
  var cost = $("#cost");
  var price = $("#price");
  var branch = $('select[name="branch[]"]');
  var flag = true;
  var errorCategory = $(".error-category");
  var errorProductName = $(".error-product-name");
  var errorProductModel = $(".error-product-model");
  var errorSupplier = $(".error-supplier");
  var errorUnit = $(".error-unit");
  var errorCost = $(".error-cost");
  var errorPrice = $(".error-price");
  var errorBranch = $(".error-branch");
  var costFormat = cost.val().replace(new RegExp("\\,", "g"), "");
  var priceFormat = price.val().replace(new RegExp("\\,", "g"), "");
  $(".errs").css("color", "red");

  if (category.val().trim() == "") {
    errorCategory.text(product.jsonLang["Vui lòng chọn danh mục."]);
    flag = false;
  } else {
    errorCategory.empty();
  }
  if (productName.val().trim() == "") {
    errorProductName.text(product.jsonLang["Vui lòng nhập tên sản phẩm."]);
    flag = false;
  } else {
    errorProductName.empty();
  }
  if (unit.val().trim() == "") {
    errorUnit.text(product.jsonLang["Vui lòng chọn đơn vị tính của sản phẩm."]);
    flag = false;
  } else {
    errorUnit.empty();
  }
  if (cost.val().trim() == "") {
    errorCost.text(product.jsonLang["Vui lòng điền giá nhập của sản phẩm."]);
    flag = false;
  } else {
    errorCost.empty();
  }
  if (price.val().trim() == "") {
    $(".error-price").text(
      product.jsonLang["Vui lòng điền giá bán của sản phẩm."]
    );
    flag = false;
  } else {
    if (parseInt(costFormat) > parseInt(priceFormat)) {
      $(".error-price").text(product.jsonLang["Giá bán phải lớn hơn giá nhập"]);
      flag = false;
    } else {
      $(".error-price").text("");
    }
    // errorPrice.empty();
  }
  if (branch.val() == "") {
    errorBranch.text(product.jsonLang["Vui lòng chọn chi nhánh."]);
    flag = false;
  } else {
    errorBranch.empty();
  }

  $.ajax({
    url: laroute.route("admin.product.check-name"),
    method: "POST",
    data: {
      productName: $("#product-name").val().trim(),
    },
    dataType: "JSON",
    async: false,
    success: function (data) {
      if (data.error == 1) {
        flag = false;
        $(".error-product-name").text(product.jsonLang["Sản phẩm đã tồn tại"]);
      }
    },
  });
  return flag;
}

$("#category").change(function () {
  if ($("#category").val() != "") {
    $(".error-category").empty();
  }
});
$("#product-name").change(function () {
  if ($("#product-name").val() != "") {
    $(".error-product-name").empty();
  }
});
// $("#product-name-en").change(function () {
//   if ($("#product-name-en").val() != "") {
//     $(".error-product-name-en").empty();
//   }
// });
$("#productModel").change(function () {
  if ($("#productModel").val() != "") {
    $(".error-product-model").empty();
  }
});
$("#supplier").change(function () {
  if ($("#supplier").val() != "") {
    $(".error-supplier").empty();
  }
});
$("#unit").change(function () {
  if ($("#unit").val() != "") {
    $(".error-unit").empty();
  }
});
$("#cost").change(function () {
  if ($("#cost").val() != "") {
    $(".error-cost").empty();
  }
});
$("#price").change(function () {
  if ($("#price").val() != "") {
    $(".error-price").empty();
  }
});
$('select[name="branch[]"]').change(function () {
  if ($('select[name="branch[]"]').val() != "") {
    $(".error-branch").empty();
  }
});

function testCodeVersion(o) {
  let flag = false;
  let code = $(o).val();
  var arrCodeVersion = new Array();
  $.each($("#add-product-version tr td").parentsUntil("tbody"), function () {
    var $t = $(this).find("input.code-version");
    $.each($t, function () {
      arrCodeVersion.push($(this).val());
    });
  });
  let count = 0;
  for (let i = 0; i < arrCodeVersion.length; i++) {
    if (arrCodeVersion[i] == code) {
      count += 1;
    }
  }
  if (count > 1) {
    $(o).parents("td").find("span").text("Mã phiên bản đã tồn tại");
    flag = false;
  } else {
    $.ajax({
      url: laroute.route("test-product-child-code"),
      method: "POST",
      data: { productCode: code },
      dataType: "JSON",
      success: function (data) {
        if (data == "") {
          flag = true;
          $(o).parents("td").find("span").text("");
        } else {
          flag = false;
          $(o).parents("td").find("span").text(data);
        }
      },
    });
  }
  if (flag == false) {
    general = false;
  }
}

$(".manager-btn").click(function () {
  if (testInputAddProduct() == true) {
    if ($(".manager-btn").is(":checked")) {
      $("#add-product-attr").empty();
      $(".save-attribute").hide();
      $("#add-product-version tbody").empty();
      $("#attribute-manager").show();
      $("#product-name").prop("disabled", true);
      // $("#product-name-en").prop("disabled", true);
      $("#promo").prop("disabled", true);
      $("#product-sale").prop("disabled", true);
      $("#cost").prop("disabled", true);
      $("#price").prop("disabled", true);
    } else {
      if ($("#add-product-version tbody").length > 0) {
        swal({
          title: product.jsonLang["Thông báo"],
          text: product.jsonLang["Bạn có muốn xóa phiên bản không?"],
          type: "warning",
          showCancelButton: true,
          confirmButtonText: product.jsonLang["Xóa"],
          cancelButtonText: product.jsonLang["Hủy"],
        }).then(function (willDelete) {
          if (willDelete.value == true) {
            swal(product.jsonLang["Xóa phiên bản thành công"], "", "success");
            $("#attribute-manager").hide();
            $(".save-attribute").show();
            $("#adGroupAttribute").prop("disabled", false);
            $("#product-name").prop("disabled", false);
            // $("#product-name-en").prop("disabled", false);
            $("#promo").prop("disabled", false);
            $("#product-sale").prop("disabled", false);
            $("#cost").prop("disabled", false);
            $("#price").prop("disabled", false);
          } else {
            $(".manager-btn").prop("checked", true);
          }
        });
      }
    }
  } else {
    $(".manager-btn").prop("checked", false);
  }
});
$(".error-contact-phone").css("color", "red");

function onKeyDownInput(o) {
  if ($(o).val().charAt(0) != "0" && $(o).val().length > 0) {
    $(".error-contact-phone").text("Bắt đầu bằng số 0");
  } else {
    $(".error-contact-phone").text("");
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

$("#promo").click(function () {
  $("#product-sale").prop("checked", false);
  if ($("#promo").is(":checked")) {
    $("#cost").val(0);
    $("#cost").prop("disabled", true);
    $("#price").val(0);
    $("#price").prop("disabled", true);
    $(".error-cost").empty();
    $(".error-price").empty();

    $("#percent_sale").prop("disabled", true);
    $("#percent_sale").val("");
  } else {
    $("#cost").val("");
    $("#cost").prop("disabled", false);
    $("#price").val("");
    $("#price").prop("disabled", false);
  }
});
$("#product-sale").click(function () {
  $("#promo").prop("checked", false);
  $("#cost").val("");
  $("#cost").prop("disabled", false);
  $("#price").val("");
  $("#price").prop("disabled", false);
  $("#percent_sale").prop("disabled", false);
  $("#percent_sale").val(0);
});

function editor() {
  var a = CKEDITOR.replace("editor1");

  return a.getData();
}

function checkName() {
  let flag;

  if ($("#product-name").val().trim() != "") {
    $.ajax({
      url: laroute.route("admin.product.check-name"),
      method: "POST",
      data: {
        productName: $("#product-name").val().trim(),
      },
      dataType: "JSON",
      success: function (data) {
        if (data.error == 1) {
          $(".error-product-name").text(
            product.jsonLang["Sản phẩm đã tồn tại"]
          );
          flag = false;
        } else {
          $(".error-product-name").empty();
          flag = true;
        }
      },
    });
  }

  return flag;
}

// $("#created_at").daterangepicker({
//     autoUpdateInput: false,
//     autoApply: true,
//     locale: {
//         format: 'DD/MM/YYYY',
//         daysOfWeek: [
//             "CN",
//             "T2",
//             "T3",
//             "T4",
//             "T5",
//             "T6",
//             "T7"
//         ],
//         "monthNames": [
//             "Tháng 1 năm",
//             "Tháng 2 năm",
//             "Tháng 3 năm",
//             "Tháng 4 năm",
//             "Tháng 5 năm",
//             "Tháng 6 năm",
//             "Tháng 7 năm",
//             "Tháng 8 năm",
//             "Tháng 9 năm",
//             "Tháng 10 năm",
//             "Tháng 11 năm",
//             "Tháng 12 năm"
//         ],
//         "firstDay": 1
//     }
// });

var arrRange = {};
(arrRange[product.jsonLang["Hôm nay"]] = [moment(), moment()]),
  (arrRange[product.jsonLang["Hôm qua"]] = [
    moment().subtract(1, "days"),
    moment().subtract(1, "days"),
  ]),
  (arrRange[product.jsonLang["7 ngày trước"]] = [
    moment().subtract(6, "days"),
    moment(),
  ]),
  (arrRange[product.jsonLang["30 ngày trước"]] = [
    moment().subtract(29, "days"),
    moment(),
  ]),
  (arrRange[product.jsonLang["Trong tháng"]] = [
    moment().startOf("month"),
    moment().endOf("month"),
  ]),
  (arrRange[product.jsonLang["Tháng trước"]] = [
    moment().subtract(1, "month").startOf("month"),
    moment().subtract(1, "month").endOf("month"),
  ]);
$("#created_at")
  .daterangepicker({
    autoUpdateInput: false,
    autoApply: true,
    buttonClasses: "m-btn btn",
    applyClass: "btn-primary",
    cancelClass: "btn-danger",
    maxDate: moment().endOf("day"),
    startDate: moment().startOf("day"),
    endDate: moment().add(1, "days"),
    locale: {
      format: "DD/MM/YYYY",
      applyLabel: product.jsonLang["Đồng ý"],
      cancelLabel: product.jsonLang["Thoát"],
      customRangeLabel: product.jsonLang["Tùy chọn ngày"],
      daysOfWeek: [
        product.jsonLang["CN"],
        product.jsonLang["T2"],
        product.jsonLang["T3"],
        product.jsonLang["T4"],
        product.jsonLang["T5"],
        product.jsonLang["T6"],
        product.jsonLang["T7"],
      ],
      monthNames: [
        product.jsonLang["Tháng 1 năm"],
        product.jsonLang["Tháng 2 năm"],
        product.jsonLang["Tháng 3 năm"],
        product.jsonLang["Tháng 4 năm"],
        product.jsonLang["Tháng 5 năm"],
        product.jsonLang["Tháng 6 năm"],
        product.jsonLang["Tháng 7 năm"],
        product.jsonLang["Tháng 8 năm"],
        product.jsonLang["Tháng 9 năm"],
        product.jsonLang["Tháng 10 năm"],
        product.jsonLang["Tháng 11 năm"],
        product.jsonLang["Tháng 12 năm"],
      ],
      firstDay: 1,
    },
    ranges: arrRange,
  })
  .on("apply.daterangepicker", function (ev) {
    product.search();
  });

function onmouseoverAddNew() {
  $(".dropdow-add-new").show();
}

function onmouseoutAddNew() {
  $(".dropdow-add-new").hide();
}

function onKeyDownInputNumber(o) {
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

$(".m_selectpicker").select2();
$("#inventory-warning").on("keyup", function () {
  var n = parseInt($(this).val().replace(/\D/g, ""), 10);
  if (typeof n == "number" && Number.isInteger(n))
    $(this).val(n.toLocaleString());
  else {
    $(this).val("");
  }
});
// $("#cost").on('keyup', function () {
//     var n = parseInt($(this).val().replace(/\D/g, ''), 10);
//     if (typeof n == 'number' && Number.isInteger(n))
//         $(this).val(n.toLocaleString());
//     else {
//         $(this).val("");
//     }
// });
// $("#price").on('keyup', function () {
//     var n = parseInt($(this).val().replace(/\D/g, ''), 10);
//     if (typeof n == 'number' && Number.isInteger(n))
//         $(this).val(n.toLocaleString());
//     else {
//         $(this).val("");
//     }
// });
var pppp = {
  addProduct: function (parameter) {
    // var form = $('#add-product');
    //
    // form.validate({
    //     rules: {
    //         category: {
    //             required: true
    //         },
    //         product_name:{
    //             required: true,
    //             maxlength: 255
    //         },
    //         unit: {
    //             required: true,
    //         },
    //         price: {
    //             required: true
    //         },
    //         integer_default: {
    //             required: true
    //         },
    //         'branch[]' : {
    //             required: true
    //         }
    //     },
    //     messages: {
    //         category: {
    //             required: product.jsonLang['Hãy chọn danh mục'],
    //         },
    //         product_name:{
    //             required: product.jsonLang['Hãy nhập tên sản phẩm'],
    //             maxlength: product.jsonLang['Tên sản phẩm vượt quá 255 ký tự']
    //         },
    //         unit: {
    //             required: product.jsonLang['Hãy chọn đơn vị tính'],
    //         },
    //         price: {
    //             required: product.jsonLang['Hãy nhập giá bán']
    //         },
    //         integer_default: {
    //             required: product.jsonLang['Hãy nhập giá nhập']
    //         },
    //         'branch[]' : {
    //             required: product.jsonLang['Hãy chọn chi nhánh']
    //         }
    //     }
    // });
    //
    // if (!form.valid()) {
    //     return false;
    // }
    var cont = true;
    if ($("#category").val().trim() == "") {
      $(".error-category").text(product.jsonLang["Vui lòng chọn danh mục."]);
      cont = false;
    } else {
      $(".error-category").empty();
    }
    if ($("#product-name").val().trim() == "") {
      $(".error-product-name").text(
        product.jsonLang["Vui lòng nhập tên sản phẩm."]
      );
      cont = false;
    } else {
      if ($("#product-name").val().length > 250) {
        $(".error-product-name").text(
          product.jsonLang["Tên sản phẩm tối đa 250 ký tự."]
        );
      } else {
        $(".error-product-name").empty();
      }
    }

    // if ($("#product-name-en").val().trim() == "") {
    //   $(".error-product-name-en").text(
    //       product.jsonLang["Vui lòng nhập tên sản phẩm (EN)."]
    //   );
    //   cont = false;
    // } else {
    //   if ($("#product-name-en").val().length > 250) {
    //     $(".error-product-name-en").text(
    //         product.jsonLang["Tên sản phẩm (EN) tối đa 250 ký tự."]
    //     );
    //   } else {
    //     $(".error-product-name-en").empty();
    //   }
    // }

    if ($("#unit").val().trim() == "") {
      $(".error-unit").text(
        product.jsonLang["Vui lòng chọn đơn vị tính của sản phẩm."]
      );
      cont = false;
    } else {
      $(".error-unit").empty();
    }
    if ($("#price").val().trim() == "") {
      $(".error-price").text(
        product.jsonLang["Vui lòng điền giá bán của sản phẩm."]
      );
      cont = false;
    } else {
      $(".error-price").empty();
    }
    if ($("#cost").val().trim() == "") {
      $(".error-cost").text(
        product.jsonLang["Vui lòng điền giá nhập của sản phẩm."]
      );
      cont = false;
    } else {
      $(".error-cost").empty();
    }
    if ($('select[name="branch[]"]').val() == "") {
      $(".error-branch").text(product.jsonLang["Vui lòng chọn chi nhánh."]);
      cont = false;
    } else {
      $(".error-branch").empty();
    }
    if (cont == false) {
      return false;
    }

    if (testInputAddProduct() == true) {
      var flag = true;
      var category = $("#category");
      var productName = $("#product-name");
      // var productNameEN = $("#product-name-en");
      var productCode = $("#in-product-code");
      var promo = $("#promo");
      // var isActive = $('#isActive');
      var productModel = $("#productModel");
      var supplier = $("#supplier");
      var unit = $("#unit");
      var cost = $("#cost");
      var price = $("#price");
      var nameVersion = $(".name-version");
      var branch = $('select[name="branch[]"]');
      var isInventoryWarning = $("#is-inventory-warning");
      var inventoryWarning = $("#inventory-warning");
      var errProductName = $(".error-product-name");
      var errProductCode = $(".error-product-code");
      var errCost = $(".error-cost");
      var errInventoryWarning = $(".error-inventory-warning");
      var is_topping = $(".is_topping");
      var productChilds = new Array();
      var promoCheck = 0;
      var isInventoryWarningCheck = 0;
      var costFormat = cost.val().replace(new RegExp("\\,", "g"), "");
      var priceFormat = price.val().replace(new RegExp("\\,", "g"), "");
      var isAllBranch = 0;
      var arrayAttrAndAttrGroup = new Array();
      var arrImage = new Array();
      let sale = 0;
      var description = $(".summernote").summernote("code");
      var type_app = [];

      var inventory_management = "basic";
      $('input[name="inventory_management"]:checked').each(function () {
        if (inventory_management == "packet" && this.value == "serial") {
          inventory_management = "all";
        } else {
          inventory_management = this.value;
        }
      });

      $(".type_app:checked").each(function (i) {
        type_app[i] = $(this).val();
      });

      // var description = $('#description').val();
      $("#temp")
        .find('input[name="fileName[]"]')
        .each(function () {
          arrImage.push($(this).val());
        });
      if ($("#check-all-branch").is(":checked")) {
        isAllBranch = 1;
      }
      if (promo.is(":checked")) {
        promoCheck = 1;
      }
      if (isInventoryWarning.is(":checked")) {
        isInventoryWarningCheck = 1;
      }
      is_topping_check = 0;

      if (is_topping.is(":checked")) {
        is_topping_check = 1;
      }
      if ($("#product-sale").is(":checked")) {
        sale = 1;
      }
      if (productName.val().trim() != "") {
        errProductName.text("");
      }

      if (parseInt(costFormat) > parseInt(priceFormat)) {
        $(".error-price").text(
          product.jsonLang["Giá bán phải lớn hơn giá nhập"]
        );
        flag = false;
      } else {
        $(".error-price").text("");
      }

      if ($("tbody tr").length > 0) {
        $.each(
          $(
            '#add-product-version tr input[name="check-all-branch[]"]:checked'
          ).parentsUntil("tbody"),
          function () {
            var $tds = $(this).find("td input");
            var key = 0;
            $.each($tds, function () {
              if (!$(this).hasClass('is_master')){
                if (key == 4) {
                  if ($(this).is(":checked") == true) {
                    productChilds.push(1);
                  } else {
                    productChilds.push(0);
                  }
                } else {
                  productChilds.push($(this).val());
                }
                key++;
              }
            });
          }
        );
        if (productChilds == "") {
          $(".errs-product-childs").text(
            product.jsonLang["Vui lòng chọn phiên bản để lưu lại"]
          );
          flag = false;
        }
      } else {
        $(".errs-product-childs").text("");
      }
      var arrayProductAttributeGroup = [];
      var arrayProductAttribute = [];
      var arrayProductAttribute2 = [];
      var arrayProductAttributeByGroup = [];
      $.each($('select[name="selectAttrGr[]"] option:selected'), function () {
        arrayProductAttributeGroup.push($(this).val());
      });
      errInventoryWarning.text("");
      if (flag == true && general == true) {
        $.each(
          $('select[name="sProducAttribute[]"] option:selected'),
          function () {
            let valAttr = $(this).text();

            arrayProductAttribute.push(valAttr);
            let valAttrGroup = $(this)
              .parents(".new-attribute-version")
              .find('select[name="selectAttrGr[]"]')
              .val();
            let aa = valAttrGroup + "=>" + valAttr;
            arrayAttrAndAttrGroup.push(aa);
            arrayProductAttribute2.push($(this).val());
            arrayProductAttributeByGroup.push({
              attrGroupId : valAttrGroup,
              attributeId : $(this).val()
            });
          }
        );

        var is_master_name = $('input[name="is_master[]"]:checked').data('name');

        $.ajax({
          url: laroute.route("admin.product.check-name"),
          method: "POST",
          data: {
            productName: $("#product-name").val().trim(),
            // productNameEN: $("#product-name-en").val().trim(),
          },
          dataType: "JSON",
          success: function (data) {
            if (data.error == 1) {
              if (data.message != ''){
                $(".error-product-name").text(
                    // product.jsonLang["Sản phẩm đã tồn tại"]
                    data.message
                );
              }

              // if (data.message_en != ''){
              //   $(".error-product-name-en").text(
              //       // product.jsonLang["Sản phẩm đã tồn tại"]
              //       data.message_en
              //   );
              // }

            } else {
              $(".error-product-name").empty();
              $.ajax({
                url: laroute.route("admin.product.submit-add"),
                method: "POST",
                data: {
                  category: category.val(),
                  productName: productName.val(),
                  // productNameEN: productNameEN.val(),
                  productCode: productCode.val(),
                  productSku: $("#product_sku").val(),
                  promo: promoCheck,
                  isActive: 1,
                  productModel: productModel.val(),
                  supplier: supplier.val(),
                  unit: unit.val(),
                  cost: costFormat,
                  price: priceFormat,
                  branch: branch.val(),
                  isInventoryWarning: isInventoryWarningCheck,
                  inventoryWarning: inventoryWarning.val(),
                  nameVersion: nameVersion.val(),
                  productAttributeGroup: arrayProductAttributeGroup,
                  productChilds: productChilds,
                  arrayProductAttribute: arrayProductAttribute2,
                  isAllBranch: isAllBranch,
                  arrayAttrAndAttrGroup: [],
                  arrImage: arrImage,
                  description: $("#description").val(),
                  description_detail: description,
                  type_app: type_app,
                  avatar: $("#file_name_avatar").val(),
                  avatarApp: $("#avatar_app").val(),
                  sale: sale,
                  is_master_name: is_master_name,
                  is_topping: is_topping_check,
                  inventory_management: inventory_management,
                  arrayProductAttributeByGroup : arrayProductAttributeByGroup,
                  type_refer_commission: $(".refer")
                    .find('.active input[name="type_refer_commission"]')
                    .val(),
                  refer_commission_value: $("#refer_commission_value")
                    .val()
                    .replace(new RegExp("\\,", "g"), ""),
                  refer_commission_percent: $("#refer_commission_percent")
                    .val()
                    .replace(new RegExp("\\,", "g"), ""),
                  type_staff_commission: $(".staff")
                    .find('.active input[name="type_staff_commission"]')
                    .val(),
                  staff_commission_value: $("#staff_commission_value")
                    .val()
                    .replace(new RegExp("\\,", "g"), ""),
                  staff_commission_percent: $("#staff_commission_percent")
                    .val()
                    .replace(new RegExp("\\,", "g"), ""),
                  percent_sale: $("#percent_sale").val(),
                  type_deal_commission: $(".deal")
                    .find('.active input[name="type_deal_commission"]')
                    .val(),
                  deal_commission_value: $("#deal_commission_value")
                    .val()
                    .replace(new RegExp("\\,", "g"), ""),
                  deal_commission_percent: $("#deal_commission_percent")
                    .val()
                    .replace(new RegExp("\\,", "g"), ""),
                },
                dataType: "JSON",
                success: function (data) {
                  if (data.status == true) {
                    swal(
                      product.jsonLang["Thêm sản phẩm thành công"],
                      "",
                      "success"
                    );
                    if (parameter == 1) {
                      window.location = laroute.route("admin.product");
                    } else {
                      location.reload();
                    }
                  } else {
                    swal(
                      product.jsonLang["Thêm sản phẩm thất bại"],
                      "",
                      "error"
                    );
                  }

                  if (data.error_check == 1) {
                    swal(data.message, "", "error");
                  }
                },
              });
            }
          },
        });
        // $.ajax({
        //     url: laroute.route('admin.product.check-name'),
        //     method: "POST",
        //     data: {
        //         productName: $('#product-name').val().trim()
        //     },
        //     dataType: "JSON",
        //     success: function (data) {
        //         if (data.error == 1) {
        //             $('.error-product-name').text(product.jsonLang['Sản phẩm đã tồn tại']);
        //         } else {
        //             $('.error-product-name').empty();
        //             $.ajax({
        //                 url: laroute.route('admin.product.submit-add'),
        //                 method: "POST",
        //                 data: {
        //                     category: category.val(),
        //                     productName: productName.val(),
        //                     productCode: productCode.val(),
        //                     productSku: $('#product_sku').val(),
        //                     promo: promoCheck,
        //                     isActive: 1,
        //                     productModel: productModel.val(),
        //                     supplier: supplier.val(),
        //                     unit: unit.val(),
        //                     cost: costFormat,
        //                     price: priceFormat,
        //                     branch: branch.val(),
        //                     isInventoryWarning: isInventoryWarningCheck,
        //                     inventoryWarning: inventoryWarning.val(),
        //                     nameVersion: nameVersion.val(),
        //                     productAttributeGroup: arrayProductAttributeGroup,
        //                     productChilds: productChilds,
        //                     arrayProductAttribute: arrayProductAttribute2,
        //                     isAllBranch: isAllBranch,
        //                     arrayAttrAndAttrGroup: [],
        //                     arrImage: arrImage,
        //                     description: $('#description').val(),
        //                     description_detail: description,
        //                     type_app: type_app,
        //                     avatar: $('#file_name_avatar').val(),
        //                     sale: sale,
        //                     inventory_management : inventory_management,
        //                     type_refer_commission: $('.refer').find('.active input[name="type_refer_commission"]').val(),
        //                     refer_commission_value: $('#refer_commission_value').val().replace(new RegExp('\\,', 'g'), ''),
        //                     refer_commission_percent: $('#refer_commission_percent').val().replace(new RegExp('\\,', 'g'), ''),
        //                     type_staff_commission: $('.staff').find('.active input[name="type_staff_commission"]').val(),
        //                     staff_commission_value: $('#staff_commission_value').val().replace(new RegExp('\\,', 'g'), ''),
        //                     staff_commission_percent: $('#staff_commission_percent').val().replace(new RegExp('\\,', 'g'), ''),
        //                     percent_sale: $('#percent_sale').val(),
        //                     type_deal_commission: $('.deal').find('.active input[name="type_deal_commission"]').val(),
        //                     deal_commission_value: $('#deal_commission_value').val().replace(new RegExp('\\,', 'g'), ''),
        //                     deal_commission_percent: $('#deal_commission_percent').val().replace(new RegExp('\\,', 'g'), '')
        //                 },
        //                 dataType: "JSON",
        //                 success: function (data) {
        //                     if (data.status == true) {
        //                         swal(product.jsonLang["Thêm sản phẩm thành công"], "", "success");
        //                         if (parameter == 1) {
        //                             window.location = laroute.route('admin.product');
        //                         } else {
        //                             location.reload();
        //                         }
        //                     } else {
        //                         swal(product.jsonLang["Thêm sản phẩm thất bại"], "", "error");
        //                     }

        //                     if (data.error_check == 1) {
        //                         swal(data.message, "", "error");
        //                     }
        //                 }
        //             });
        //         }
        //     }
        // });
      }
    }
  },
  refer_commission: function (obj) {
    if (obj == "money") {
      $("#refer_money").attr("class", "btn btn-info color_button active");
      $("#refer_percent").attr("class", "btn btn-default");
      $("#refer_commission_value").removeClass("d-none");
      $("#refer_commission_percent").addClass("d-none");
    } else {
      $("#refer_percent").attr("class", "btn btn-info color_button active");
      $("#refer_money").attr("class", "btn btn-default");
      $("#refer_commission_percent").removeClass("d-none");
      $("#refer_commission_value").addClass("d-none");
    }
  },
  staff_commission: function (obj) {
    if (obj == "money") {
      $("#staff_money").attr("class", "btn btn-info color_button active");
      $("#staff_percent").attr("class", "btn btn-default");
      $("#staff_commission_value").removeClass("d-none");
      $("#staff_commission_percent").addClass("d-none");
    } else {
      $("#staff_percent").attr("class", "btn btn-info color_button active");
      $("#staff_money").attr("class", "btn btn-default");
      $("#staff_commission_percent").removeClass("d-none");
      $("#staff_commission_value").addClass("d-none");
    }
  },
  // Hoa hồng cho deal
  deal_commission: function (obj) {
    if (obj == "money") {
      $("#deal_money").attr("class", "btn btn-info color_button active");
      $("#deal_percent").attr("class", "btn btn-default");
      $("#deal_commission_value").removeClass("d-none");
      $("#deal_commission_percent").addClass("d-none");
    } else {
      $("#deal_percent").attr("class", "btn btn-info color_button active");
      $("#deal_money").attr("class", "btn btn-default");
      $("#deal_commission_percent").removeClass("d-none");
      $("#deal_commission_value").addClass("d-none");
    }
  },

  getSku: function (e) {
    $.ajax({
      url: laroute.route("admin.product.get-sku-product"),
      method: "POST",
      data: {
        cateId: $(e).val(),
      },
      dataType: "JSON",
      success: function (data) {
        $("#product_sku").val(data.sku);
      },
    });
  },
  checkSku: function (e) {
    if ($("#product_sku").val().trim() != "") {
      $.ajax({
        url: laroute.route("admin.product.check-sku"),
        method: "POST",
        data: {
          productSku: $("#product_sku").val().trim(),
        },
        dataType: "JSON",
        success: function (data) {
          if (data.error == 1) {
            $(".error-product-sku").text(
              product.jsonLang["Sku sản phẩm đã tồn tại"]
            );
            return (flag = false);
          } else {
            $(".error-product-sku").empty();
            return (flag = true);
          }
        },
      });
    }
  },
};

uploadImg = function (file) {
  let out = new FormData();
  out.append('file', file, file.name);
  out.append('link', '_product.');

  $.ajax({
    method: 'POST',
    url: laroute.route('admin.upload-image'),
    contentType: false,
    cache: false,
    processData: false,
    data: out,
    success: function (img) {
      $(".summernote").summernote('insertImage', img['file'] , function (image){
        image.css('width', '100%');
      });
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error(textStatus + " " + errorThrown);
    }
  });
};

//
