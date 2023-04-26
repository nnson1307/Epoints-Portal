var arrayAttributeGroup = [0];
var category = $("#category");
var productName = $("#product-name");
var productCode = $("#product-code");
var avatarApp = $("input[name=avatar_app]");
var isActive = $("#isActive");
var isPromo = $("#promo");
var productModel = $("#productModel");
var supplier = $("#supplier");
var unit = $("#unit");
var cost = $("#cost");
var price = $("#price");
var branch = $('select[name="branch[]"]');
var isAllBranch = $("#check-all-branch");
var isInventoryWarning = $("#is-inventory-warning");
var inventoryWarning = $("#inventory-warning");
var errorCategory = $(".error-category");
var errorProductName = $(".error-product-name");
var errorProductCode = $(".error-product-code");
var errorProductModel = $(".error-product-model");
var errorSupplier = $(".error-supplier");
var errorUnit = $(".error-unit");
var errorCost = $(".error-cost");
var errorPrice = $(".error-price");
var errorBranch = $(".error-branch");
var errorInventoryWarning = $(".error-inventory-warning");

branch.select2();
category.select2();
productModel.select2();
supplier.select2();
unit.select2();

AutoNumeric.multiple(
  "#refer_commission_value, #refer_commission_percent, #staff_commission_value, #staff_commission_percent, #deal_commission_value, #deal_commission_percent, #cost, #price, .price",
  {
    currencySymbol: "",
    decimalCharacter: ".",
    digitGroupSeparator: ",",
    decimalPlaces: decimal_number,
    minimumValue: 0,
  }
);

//Check all branch.
isAllBranch.click(function () {
  if (isAllBranch.is(":checked")) {
    $('select[name="branch[]"] > option').prop("selected", "selected");
    branch.trigger("change");
  } else {
    branch.val(null).trigger("change");
  }
});

//Check all product child.
$("#check-all-product-child").click(function (e) {
  $(this)
    .closest("table")
    .find("td input:checkbox")
    .prop("checked", this.checked);
});

// Select product attribute.

$('select[name="sProducAttribute[]"]').select2({
  placeholder: "Chọn hoặc nhập thuộc tính",
  allowClear: true,
  tags: true,
  createTag: function (tag) {
    return {
      id: tag.term,
      text: tag.term,
      isNew: true,
    };
  },
});

// function test input info product.

function testInput() {
  let flag = true;
  $.getJSON(laroute.route("translate"), function (json) {
    if (category.val() == "") {
      errorCategory.text(json["Vui lòng chọn danh mục."]);
      flag = false;
    } else {
      errorCategory.empty();
    }
    if (productName.val().trim() == "") {
      errorProductName.text(json["Vui lòng nhập tên sản phẩm."]);
      flag = false;
    } else {
      errorProductName.empty();
    }
    if (unit.val() == "") {
      errorUnit.text(json["Vui lòng chọn đơn vị tính của sản phẩm."]);
      flag = false;
    } else {
      errorUnit.empty();
    }
    if (cost.val().trim() == "") {
      errorCost.text(json["Vui lòng điền giá nhập của sản phẩm."]);
      flag = false;
    } else {
      errorCost.empty();
    }
    if (branch.val() == "") {
      errorBranch.text(json["Vui lòng chọn chi nhánh."]);
      flag = false;
    } else {
      errorBranch.empty();
    }
    let mesCodeVersion = [];
    $.each($("#edit-product-version tr").parentsUntil("tbody"), function () {
      var $td = $(this).find("td .mesCode");
      $.each($td, function () {
        if ($(this).text() != "") {
          mesCodeVersion.push($(this).text());
        }
      });
    });
    if (mesCodeVersion.length > 0) {
      flag = false;
    }
    var costFormat = cost.val().replace(new RegExp("\\,", "g"), "");
    var priceFormat = price.val().replace(new RegExp("\\,", "g"), "");

    if (price.val() == "") {
      errorPrice.text(json["Vui lòng nhập giá bán"]);
      flag = false;
    } else {
      if (parseInt(costFormat) > parseInt(priceFormat)) {
        errorPrice.text(json["Giá bán phải lớn hơn giá nhập"]);
        flag = false;
      } else {
        errorPrice.text("");
      }
    }

    $.ajax({
      url: laroute.route("admin.product.check-name"),
      method: "POST",
      data: {
        productName: productName.val().trim(),
        id: $("#idHidden").val(),
      },
      dataType: "JSON",
      async: false,
      success: function (data) {
        if (data.error == 1) {
          flag = false;
          $(".error-product-name").text(json["Sản phẩm đã tồn tại"]);
        }
      },
    });
  });
  return flag;
}

$(".errs").css("color", "red");

productName.change(function () {
  if (productName.val() != "") {
    errorProductName.empty();
  }
});

cost.change(function () {
  if (cost.val() != "") {
    errorCost.empty();
  }
});
price.change(function () {
  if (price.val() != "") {
    errorPrice.empty();
  }
});
branch.change(function () {
  if (branch.val() != "") {
    errorBranch.empty();
  }
});
//Button save edit product.
$(".save-change").click(function () {
  var inventory_management = "basic";
  $('input[name="inventory_management"]:checked').each(function () {
    if (inventory_management == "packet" && this.value == "serial") {
      inventory_management = "all";
    } else {
      inventory_management = this.value;
    }
  });
  var continueStep = 1;
  var inventory_management_hidden = $("#inventory_management_hidden").val();
  if (
    inventory_management_hidden == "basic" &&
    inventory_management_hidden != inventory_management
  ) {
    $.ajax({
      async: false,
      url: laroute.route("admin.product.check-basic-edit"),
      method: "POST",
      data: {
        id: $("#idHidden").val(),
        productCode: productCode.val(),
      },
      success: function (res) {
        console.log(res);
        if (res.total != 0) {
          $.getJSON(laroute.route("translate"), function (json) {
            swal({
              title: json["Sản phẩm còn tồn kho"],
              text: json[
                "Bạn không thể thay đổi cách quản lý sản phẩm do sản phẩn còn tồn kho. Vui lòng xuất kho số lượng còn lại để có thể thay đổi cách quản lý kho"
              ],
              type: "warning",
              // showCancelButton: true,
              confirmButtonText: json["Xác nhận"],
              // cancelButtonText: json['Hủy'],
            }).then(function (result) {});
          });
          $('input[name="inventory_management"]').prop("checked", false);
          continueStep = 0;
        }
      },
    });
  }

  if (continueStep == 1) {
    if (
      (inventory_management_hidden == "serial" ||
        inventory_management_hidden == "all") &&
      inventory_management != "all" &&
      inventory_management != "serial"
    ) {
      $.ajax({
        url: laroute.route("admin.product.check-serial-edit"),
        method: "POST",
        data: {
          id: $("#idHidden").val(),
          productCode: productCode.val(),
        },
        success: function (res) {
          if (res.total != 0) {
            $.getJSON(laroute.route("translate"), function (json) {
              swal({
                title: json["Sản phẩm đã có tồn kho theo số seri"],
                text: json[
                  "Nếu bạn thay đổi các seri của sản phẩm này sẽ được xóa hết"
                ],
                type: "warning",
                showCancelButton: true,
                confirmButtonText: json["Xác nhận"],
                cancelButtonText: json["Hủy"],
              }).then(function (result) {
                if (result.value) {
                  editProduct("delete");
                } else {
                  location.reload();
                }
              });
            });
          } else {
            editProduct();
          }
        },
      });
    } else {
      editProduct();
    }
  }
});

function editProduct(deleteSerial = null) {
  $.getJSON(laroute.route("translate"), function (json) {
    var form = $("#edit-product");

    form.validate({
      rules: {
        category: {
          required: true,
        },
        product_name: {
          required: true,
          maxlength: 255,
        },
        unit: {
          required: true,
        },
        price: {
          required: true,
        },
        integer_default: {
          required: true,
        },
        "branch[]": {
          required: true,
        },
      },
      messages: {
        category: {
          required: json["Hãy chọn danh mục"],
        },
        product_name: {
          required: json["Hãy nhập tên sản phẩm"],
          maxlength: json["Tên sản phẩm vượt quá 255 ký tự"],
        },

        product_name_en: {
          required: json["Hãy nhập tên sản phẩm (EN)"],
          maxlength: json["Tên sản phẩm (EN) vượt quá 255 ký tự"],
        },
        unit: {
          required: json["Hãy chọn đơn vị tính"],
        },
        price: {
          required: json["Hãy nhập giá bán"],
        },
        integer_default: {
          required: json["Hãy nhập giá nhập"],
        },
        "branch[]": {
          required: json["Hãy chọn chi nhánh"],
        },
      },
    });

    if (!form.valid()) {
      return false;
    }
    if (testInput() == true) {
      var flag = true;
      let isInventoryWarningCheck = 0;
      let isActiveCheck = 0;
      let isAllBranchCheck = 0;
      let is_topping = 0;
      let isPromoCheck = 0;
      let isSale = 0;
      var arrayProductAttribute = [];
      var productChilds = new Array();
      var arrayAttrAndAttrGroup = new Array();
      var arrImage = new Array();
      var arrayProductAttribute2 = new Array();
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
      $(".dropzone-append-image").each(function () {
        arrImage.push($(this).val());
      });
      var arrayProductAttributeByGroup = [];
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
      if (isInventoryWarning.is(":checked")) {
        isInventoryWarningCheck = 1;
      }
      if (isActive.is(":checked")) {
        isActiveCheck = 1;
      }
      if (isAllBranch.is(":checked")) {
        isAllBranchCheck = 1;
      }
      if (isPromo.is(":checked")) {
        isPromoCheck = 1;
      }
      if ($('.is_topping').is(':checked')){
        is_topping = 1;
      }
      if ($("#product-sale").is(":checked")) {
        isSale = 1;
      }
      var costFormat = cost.val().replace(new RegExp("\\,", "g"), "");
      var priceFormat = price.val().replace(new RegExp("\\,", "g"), "");
      if (parseInt(costFormat) > parseInt(priceFormat)) {
        $(".error-price").text(json["Giá bán phải lớn hơn giá nhập"]);
        flag = false;
      } else {
        $(".error-price").text("");
        12169;
      }

      if ($("tbody tr").length > 0) {
        $.each(
          $(
            '#edit-product-version tr input[name="check-product-child[]"]:checked'
          ).parentsUntil("tbody"),
          function () {
            var $tds = $(this).find("td input");
            var key = 0;
            $.each($tds, function () {
              if (!$(this).hasClass('is_master')) {
                if (key == 5) {
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
            json["Vui lòng chọn phiên bản để lưu lại."]
          );
          flag = false;
        }
      } else {
        $(".errs-product-childs").text("");
      }

      if ($("#file_name_avatar").val() != "") {
        $("#avatar-exist").val("");
      }

      var is_master_name = $('input[name="is_master[]"]:checked').data('name');

      if (flag == true) {
        $.ajax({
          url: laroute.route("admin.product.submit-edit"),
          method: "POST",
          data: {
            id: $("#idHidden").val(),
            branch: branch.val(),
            productCode: productCode.val(),
            productCategory: category.val(),
            productModel: productModel.val(),
            productName: productName.val(),
            unit: unit.val(),
            cost: costFormat,
            price: priceFormat,
            isPromo: isPromoCheck,
            isInventoryWarning: isInventoryWarningCheck,
            inventoryWarning: inventoryWarning.val(),
            supplier: supplier.val(),
            isActive: isActiveCheck,
            isAllBranch: isAllBranchCheck,
            is_topping: is_topping,
            arrayAttrAndAttrGroup: arrayAttrAndAttrGroup,
            productChilds: productChilds,
            arrayProductAttribute: arrayProductAttribute2,
            arrImage: arrImage,
            description: $("#description").val(),
            description_detail: description,
            type_app: type_app,
            avatar: $("#file_name_avatar").val(),
            avatarApp: $("input[name=avatar_app]").val(),
            isSale: isSale,
            is_master_name:is_master_name,
            avatarExist: $("#avatar-exist").val(),
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
            inventory_management: inventory_management,
            deleteSerial: deleteSerial,
          },
          success: function (response) {
            if (response.error_check == 1) {
              swal(response.message, "", "error");
            }

            if (response.editProduct == 1) {
              swal(json["Cập nhật sản phẩm thành công"], "", "success");
              $.ajax({
                url: laroute.route("admin.product.edit-product-branch-price"),
                method: "POST",
                data: {
                  id: $("#idHidden").val(),
                  branch: branch.val(),
                },
                success: function (data) {
                  location.reload();
                },
              });
            }
          },
        });
      }
    }
  });
}

$('select[name="selectAttrGr[]"]').change(function () {
  if ($('select[name="selectAttrGr[]"]').val() != "") {
    $('select[name="sProducAttribute[]"]').prop("disabled", false);
  } else {
    $('select[name="sProducAttribute[]"]').prop("disabled", true);
  }
  var id = $(this).val();
  var tt = $(this)
    .parents(".new-attribute-version")
    .find('select[name="sProducAttribute[]"]');
  $.ajax({
    url: laroute.route("get-product-attribute-by-group"),
    method: "POST",
    data: { id: id },
    dataType: "JSON",
    success: function (data) {
      tt.empty();
      $.each(data, function (index, element) {
        tt.append('<option value="' + index + '">' + element + "</option>");
      });
    },
  });
});

function testCodeVersion(o) {
  let flag = true;
  let code = $(o).val();
  var arrCodeVersion = new Array();
  $.getJSON(laroute.route("translate"), function (json) {
    $.each($("#edit-product-version tr td").parentsUntil("tbody"), function () {
      var $t = $(this).find("input.code-version");
      $.each($t, function () {
        arrCodeVersion.push($(this).val());
      });
    });
    if (code == "") {
      $(o).parents("td").find("span").text(json["Vui lòng nhập mã phiên bản."]);
      flag = false;
    }
    let count = 0;
    for (let i = 0; i < arrCodeVersion.length; i++) {
      if (arrCodeVersion[i] == code) {
        count += 1;
      }
    }
    if (count > 1) {
      $(o).parents("td").find("span").text(json["Mã phiên bản đã tồn tại"]);
      flag = false;
    } else if (count == 1) {
      $(o).parents("td").find("span").text("");
    } else {
      let idHide = $(o).parents("tr").find(".hiddenCode").val();
      if (code == idHide) {
        $(o).parents("td").find("span").text("");
      } else {
        $(o).parents("td").find("span").text(json["Mã phiên bản đã tồn tại"]);
        flag = false;
      }
      //     $.ajax({
      //         url: laroute.route('test-product-child-code'),
      //         method: "POST",
      //         data: {productCode: code},
      //         dataType: "JSON",
      //         success: function (data) {
      //             console.log(data);
      //             if (data == '') {
      //                 flag = true;
      //                 $(o).parents('td').find('span').text('');
      //             } else {
      //                 flag = false;
      //                 $(o).parents('td').find('span').text(data);
      //             }
      //
      //         }
      //     });
    }
  });
}

$("#check-all").click(function (e) {
  $(this)
    .closest("table")
    .find("td input:checkbox")
    .prop("checked", this.checked);
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

function maskNumberPriceProductChild() {
  new AutoNumeric(".price", {
    currencySymbol: "",
    decimalCharacter: ".",
    digitGroupSeparator: ",",
    decimalPlaces: decimal_number,
  });
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
  $("#cost").prop("disabled", false);
  $("#price").prop("disabled", false);

  $("#percent_sale").prop("disabled", false);
  $("#percent_sale").val(0);
});
$(".manager-btn").click(function () {
  $.getJSON(laroute.route("translate"), function (json) {
    if ($(".manager-btn").is(":checked")) {
      $("#add-product-attr").empty();
      $(".save-attribute").hide();
      $("#add-product-version tbody").empty();
      $("#attribute-manager").show();
      $("#adGroupAttribute").prop("disabled", true);
    } else {
      if ($("#edit-product-version tbody").length > 0) {
        swal({
          title: json["Thông báo"],
          text: json["Bạn có muốn xóa phiên bản không?"],
          type: "warning",
          showCancelButton: true,
          confirmButtonText: "Xóa",
          cancelButtonText: "Hủy",
        }).then(function (willDelete) {
          if (willDelete.value == true) {
            swal(json["Xóa phiên bản thành công"], "", "success");
            $("#attribute-manager").hide();
            $(".exist-product-child").remove();
            $(".save-attribute").show();
          } else {
            $(".manager-btn").prop("checked", true);
          }
        });
      }
    }
  });
});

function uploadImage(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    var imageAvatar = $("#file_name_avatar");
    reader.onload = function (e) {
      $("#blah-edit").attr("src", e.target.result);
      $(".delete-img").addClass("delete-img-show");
      $(".delete-img").removeClass("delete-img");
    };
    reader.readAsDataURL(input.files[0]);
    var file_data = $("#getFile").prop("files")[0];
    var form_data = new FormData();
    form_data.append("file", file_data);
    form_data.append("link", "_product.");

    var fsize = input.files[0].size;

    var fileInput = input,
      file = fileInput.files && fileInput.files[0];
    var img = new Image();

    img.src = window.URL.createObjectURL(file);

    img.onload = function () {
      var imageWidth = img.naturalWidth;
      var imageHeight = img.naturalHeight;

      window.URL.revokeObjectURL(img.src);

      $(".image-size").text(imageWidth + "x" + imageHeight + "px");
    };
    $(".image-capacity").text(Math.round(fsize / 1024) + "kb");

    $(".image-format").text(input.files[0].name.split(".").pop().toUpperCase());
    if (Math.round(fsize / 1024) < 10241) {
      $.ajax({
        url: laroute.route("admin.upload-image"),
        method: "POST",
        data: form_data,
        contentType: false,
        cache: false,
        processData: false,
        success: function (data) {
          imageAvatar.val(data.file);
        },
      });
    } else {
      $(".max-size").addClass("text-danger");
    }
  }
}

function removeImage(image, $t) {
  $.ajax({
    url: laroute.route("product.delete-image-by-productId-link"),
    method: "POST",
    data: {
      productId: $("#idHidden").val(),
      link: image,
    },
    dataType: "JSON",
    success: function (data) {
      if (data.error == 0) {
        $(".image-hide").each(function () {
          if ($(this).val() == image) {
            $(this).remove();
          }
        });
        $(".dropzone-append-image").each(function () {
          if ("temp_upload/" + $(this).val() == image) {
            $(this).remove();
          }
        });
      }
    },
  });
  $($t).parents(".class-for-delete").find(".div-image-show").remove();
}

$(".btn-save-image").click(function () {
  $(".image-temp").remove();
  // $('.image-append-this').empty();
  // $('.oooooo').empty();
  let linkImageAvatar = $("#file_name_avatar").val();
  if (linkImageAvatar != "") {
    $(".oooooo").empty();
  }
  var arrayImage = new Array();
  $(".dropzone-append-image").each(function () {
    arrayImage.push($(this).val());
  });
  for (let i = 0; i < arrayImage.length; i++) {
    let $_tpl = $("#js-template-append-image").html();
    let tpl = $_tpl;
    tpl = tpl.replace(/{link}/g, arrayImage[i]);
    $(".image-append-this").append(tpl);
  }

  if (linkImageAvatar != "") {
    let $_tpl = $("#JS-template-avatar").html();
    let tpl = $_tpl;
    tpl = tpl.replace(/{link1}/g, linkImageAvatar);
    $(".oooooo").append(tpl);
    $("#avatar-exist").val("");
  }
  $("#editImage").modal("hide");
});

function deleteAvatar(image, $this) {
  $($this).parents(".class-for-delete").find(".div-image-show").remove();
  $("#blah-edit").attr("src", $("#link-image-fault").val());
  $("#avatar-exist").val("");
}

function cancelAddImage() {
  $(".dropzone-append-image").remove();
}

$("*").dblclick(function (e) {
  e.preventDefault();
});
$("#addGroupAttribute").on("click", function (e) {
  $.getJSON(laroute.route("translate"), function (json) {
    $("#addGroupAttribute").prop("disabled", true);
    // event.preventDefault();
    $.each($('select[name="selectAttrGr[]"]'), function () {
      arrayAttributeGroup.push($(this).val());
    });
    if ($('select[name="sProducAttribute[]"]').val() != null) {
      $(this)
        .parents(".col-lg-12")
        .find('select[name="selectAttrGr[]"]')
        .attr("disabled", "disabled");
    }
    if (productName.val() != "") {
      errorProductName.text("");
    }


    if (productCode.val() != "") {
      errorProductCode.text("");
    }
    if (cost.val() != "") {
      if (cost.val() < 0) {
        errorCost.text(json["Vui lòng nhập lại giá nhập."]);
      } else {
        errorCost.text("");
      }
    }
    if (price.val() != "") {
      errorPrice.text("");
    }
    if (branch.val() != "") {
      errorBranch.text("");
    }
    if (
      productName.val() == "" ||
      cost.val() == "" ||
      price.val() == "" ||
      branch.val() == ""
    ) {
      if (productName.val() == "") {
        errorProductName.text(json["Vui lòng nhập tên sản phẩm."]);
      }

      if (cost.val() == "") {
        errorCost.text(json["Vui lòng nhập giá nhập."]);
      }
      if (price.val() == "") {
        errorPrice.text(json["Vui lòng nhập giá bán."]);
      }
      if (branch.val() == "") {
        errorBranch.text(json["Vui lòng chọn chi nhánh."]);
      }
    } else {
      errorInventoryWarning.text("");
      $.ajax({
        url: laroute.route("admin.product.get-product-attribute-edit"),
        method: "POST",
        data: { attributeGroupId: arrayAttributeGroup },
        success: function (data) {
          $(".select-group-attribute").append(data);
          productName.prop("disabled", true);
          $("#addGroupAttribute").prop("disabled", true);
        },
      });
    }
    if ($("select[name='sProducAttribute[]']").last().val() == "") {
      $("#addGroupAttribute").prop("disabled", true);
    }
  });
});

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

var product = {
  onMouseOverAddNew: function () {
    $(".dropdow-add-new").show();
  },
  onMouseOutAddNew: function () {
    $(".dropdow-add-new").hide();
  },
  addProductCategory: function () {
    let categoryName = $("#modal-add-product-category #category-name");
    let description = $("#modal-add-product-category #description");
    let errorCategoryName = $(
      "#modal-add-product-category .error-category-name"
    );
    let check = 0;
    $.getJSON(laroute.route("translate"), function (json) {
      if ($(".is_actived").is(":checked")) {
        check = 1;
      }
      errorCategoryName.css("color", "red");
      if (categoryName.val() != "") {
        $.ajax({
          url: laroute.route("admin.product-category.add"),
          data: {
            categoryName: categoryName.val(),
            description: description.val(),
            isActived: check,
          },
          method: "POST",
          success: function (data) {
            if (data.status == 1) {
              swal(json["Thêm danh mục thành công"], "", "success");
              $.map(data.category, function (value, key) {
                if (key == data.id) {
                  $("#category").append(
                    '<option value="' + key + '">' + value + "</option>"
                  );
                }
              });
              categoryName.val("");
              description.val("");
              errorCategoryName.text("");
              $("#modal-add-product-category").modal("hide");
            } else {
              errorCategoryName.text(json["Danh mục đã tồn tại"]);
            }
          },
        });
      } else {
        errorCategoryName.text(json["Vui lòng nhập tên danh mục."]);
      }
    });
  },
  addModalUnit: function () {
    var name = $("#modal-add-unit #name").val();
    $.getJSON(laroute.route("translate"), function (json) {
      if (name != "") {
        console.log(1231);
        var is_actived = 0;
        if ($("#modal-add-unit #is_actived").is(":checked")) {
          is_actived = 1;
        }
        var is_standard = 0;
        if ($("#modal-add-unit #is_standard").is(":checked")) {
          is_standard = 1;
        }

        $.ajax({
          type: "post",
          url: laroute.route("admin.unit.submitadd"),
          data: {
            name: $("#modal-add-unit #name").val(),
            is_standard: is_standard,
            is_actived: is_actived,
            close: 1,
          },
          dataType: "JSON",
          success: function (response) {
            if (response.status == "") {
              if (response.close != 0) {
                $("#modal-add-unit").modal("hide");
              }
              $("#modal-add-unit #name").val("");
              $("#modal-add-unit .error-name").text("");
              swal(json["Thêm đơn vị tính thành công"], "", "success");
              $.map(response.unitOption, function (value, key) {
                if (key == response.id) {
                  $("#unit").append(
                    '<option value="' + key + '">' + value + "</option>"
                  );
                }
              });
            } else {
              $("#modal-add-unit .error-name").text(response.status);
              $("#modal-add-unit .error-name").css("color", "red");
            }
          },
        });
      } else {
        $("#modal-add-unit .error-name").text(json["Hãy nhập đơn vị tính"]);
      }
    });
  },
  addModalProductModel: function () {
    $.getJSON(laroute.route("translate"), function (json) {
      $("#modal-add-product-model .error-product-model-name").css(
        "color",
        "red"
      );
      let productModelName = $("#modal-add-product-model #product-model-name");
      let productModelNote = $("#modal-add-product-model #product-model-note");
      if (productModelName.val() != "") {
        $.ajax({
          url: laroute.route("admin.product-model.add"),
          data: {
            productModelName: productModelName.val(),
            productModelNote: productModelNote.val(),
          },
          method: "POST",
          dataType: "JSON",
          success: function (data) {
            if (data.status == 1) {
              swal(json["Thêm nhãn hiệu sản phẩm thành công"], "", "success");
              productModelName.val("");
              productModelNote.val("");
              $("#modal-add-product-model .error-product-model-name").text("");
              $("#modal-add-product-model").modal("hide");
              $.map(data.model, function (value, key) {
                if (key == data.id) {
                  $("#productModel").append(
                    '<option value="' + key + '">' + value + "</option>"
                  );
                }
              });
            }
            if (data.status == 0) {
              $(".error-product-model-name").text(
                json["Nhãn sản phẩm đã tồn tại"]
              );
            }
          },
        });
      } else {
        $(".error-product-model-name").text(
          json["Vui lòng nhập tên nhãn hiệu sản phẩm."]
        );
      }
    });
  },
};
var pppp = {
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
};
