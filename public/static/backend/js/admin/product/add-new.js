var product = {
  jsonLang: JSON.parse(localStorage.getItem("tranlate")),
  addProductModel: function () {
    var productModelName = $("#modalAddModel #product-model-name");
    var productModelNote = $("#modalAddModel #product-model-note");
    let errName = $("#modalAddModel .error-product-model-name");
    errName.css("color", "red");

    if (productModelName.val().trim() != "") {
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
            swal(
              product.jsonLang["Thêm nhãn hiệu sản phẩm thành công"],
              "",
              "success"
            );
            $("#autotable").PioTable("refresh");
            clearModalAddProductModel();
            $("#productModel > option").empty();
            $.each(data, function (index, element) {
              $("#productModel").append(
                '<option value="' + index + '">' + element + "</option>"
              );
            });
          }
          if (data.status == 0) {
            errName.text(product.jsonLang["Nhãn sản phẩm đã tồn tại"]);
          }
        },
      });
    } else {
      errName.text(product.jsonLang["Vui lòng nhập tên nhãn hiệu sản phẩm."]);
    }
  },
  addProductModelClose: function () {
    var productModelName = $("#modalAddModel #product-model-name");
    var productModelNote = $("#modalAddModel #product-model-note");
    let errName = $("#modalAddModel .error-product-model-name");
    errName.css("color", "red");

    if (productModelName.val().trim() != "") {
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
            swal(
              product.jsonLang["Thêm nhãn hiệu sản phẩm thành công"],
              "",
              "success"
            );
            $("#autotable").PioTable("refresh");
            clearModalAddProductModel();
            $(".model-new > option").empty();
            $.each(data.model, function (index, element) {
              $(".model-new").append(
                '<option value="' + index + '">' + element + "</option>"
              );
            });
            $("#modalAddModel").modal("hide");
          }
          if (data.status == 0) {
            errName.text(product.jsonLang["Nhãn sản phẩm đã tồn tại"]);
          }
        },
      });
    } else {
      errName.text(product.jsonLang["Vui lòng nhập tên nhãn hiệu sản phẩm."]);
    }
  },
  clearModalAddProductModel: function () {
    clearModalAddProductModel();
  },
  addCategory: function () {
    var categoryName = $("#modalAddCategory #category-name");
    var description = $("#modalAddCategory #description");
    var isActived = $("#modalAddCategory #is_actived");
    var errorCategoryName = $("#modalAddCategory .error-category-name");

    if (categoryName.val().trim() != "") {
      $.ajax({
        url: laroute.route("admin.product-category.add"),
        data: {
          categoryName: categoryName.val(),
          description: description.val(),
          isActived: "1",
        },
        method: "POST",
        dataType: "JSON",
        success: function (data) {
          if (data.status == 1) {
            $(".categoryssss").empty();
            swal(product.jsonLang["Thêm danh mục thành công"], "", "success");
            clearModalAddCategory();
            $.each(data.category, function (index, element) {
              $("#category").append(
                '<option value="' + index + '">' + element + "</option>"
              );
            });
          } else {
            errorCategoryName.css("color", "red");
            errorCategoryName.text(product.jsonLang["Tên danh mục đã tồn tại"]);
          }
        },
      });
    } else {
      errorCategoryName.css("color", "red");
      errorCategoryName.text(product.jsonLang["Vui lòng nhập tên danh mục."]);
    }
  },
  addCategoryClose: function () {
    var categoryName = $("#modalAddCategory #category-name");
    var description = $("#modalAddCategory #description");
    var isActived = $("#modalAddCategory #is_actived");
    var errorCategoryName = $("#modalAddCategory .error-category-name");

    if (categoryName.val().trim() != "") {
      $.ajax({
        url: laroute.route("admin.product-category.add"),
        data: {
          categoryName: categoryName.val(),
          description: description.val(),
          isActived: "1",
        },
        method: "POST",
        success: function (data) {
          if (data.status == 1) {
            $(".categoryssss").empty();
            swal(product.jsonLang["Thêm danh mục thành công"], "", "success");
            $("#modalAddCategory").modal("hide");
            clearModalAddCategory();
            $.each(data.category, function (index, element) {
              $("#category").append(
                '<option value="' + index + '">' + element + "</option>"
              );
            });
          } else {
            errorCategoryName.css("color", "red");
            errorCategoryName.text(product.jsonLang["Tên danh mục đã tồn tại"]);
          }
        },
      });
    } else {
      errorCategoryName.css("color", "red");
      errorCategoryName.text(product.jsonLang["Vui lòng nhập tên danh mục."]);
    }
  },
  clearModalAddCategory: function () {
    clearModalAddCategory();
  },
  addUnit: function (close) {
    $("#type_add").val(close);

    var validation = $("#form").validate({
      rules: {
        name: {
          required: true,
        },
      },
      messages: {
        name: {
          required: product.jsonLang["Hãy nhập đơn vị tính"],
        },
      },
      submitHandler: function () {
        var input = $("#type_add");
        var check = document.getElementById("is_standard");
        $.ajax({
          type: "post",
          url: laroute.route("admin.unit.submitadd"),
          data: {
            name: $("#name").val(),
            is_standard: check.value,
            is_actived: "1",
            close: input.val(),
          },
          dataType: "JSON",
          success: function (response) {
            if (response.status == "") {
              if (response.close != 0) {
                $("#modalAddUnit").modal("hide");
              }
              $("#name").val("");
              $(".error-name").text("");
              swal(
                product.jsonLang["Thêm đơn vị tính thành công"],
                "",
                "success"
              );
              $(".unit-new > option").empty();
              $.each(response.unitOption, function (index, element) {
                $("#unit").append(
                  '<option value="' + index + '">' + element + "</option>"
                );
              });
              $(".error-name-unit").text("");
            } else {
              $(".error-name-unit").css("color", "red");
              $(".error-name-unit").text(response.status);
            }
          },
        });
      },
    });
  },
  addBranch: function (close) {
    $("#type_add_branch").val(close);

    $("#form-add-branch").validate({
      rules: {
        branch_name: {
          required: true,
        },
        address: {
          required: true,
        },
        phone: {
          required: true,
          minlength: 10,
          number: true,
        },
      },
      messages: {
        branch_name: {
          required: product.jsonLang["Vui lòng nhập tên chi nhánh."],
        },
        address: {
          required: product.jsonLang["Vui lòng nhập địa chỉ."],
        },
        phone: {
          required: product.jsonLang["Vui lòng nhập số điện thoại."],
          minlength: product.jsonLang["SĐT tối thiểu 10 số"],
          number: product.jsonLang["SĐT không hợp lệ"],
        },
      },
      submitHandler: function () {
        var branch_name = $("#modalAddBranch #branch_name");
        var address = $("#modalAddBranch #address");
        var phone = $("#modalAddBranch #phone");
        var des = $("#modalAddBranch #description");
        var is_actived = $("#modalAddBranch #is_actived");
        var input = $("#modalAddBranch #type_add_branch");
        $.ajax({
          url: laroute.route("admin.branch.submitAdd"),
          data: {
            branch_name: branch_name.val(),
            address: address.val(),
            phone: phone.val(),
            description: des.val(),
            is_actived: "1",
            close: input.val(),
          },
          method: "POST",
          dataType: "JSON",
          success: function (response) {
            if (response.status == 1) {
              if (response.close != 0) {
                $("#modalAddBranch").modal("hide");
              }
              $("#name").val("");
              $("#address").val("");
              $("#phone").val("");
              $("#description").val("");
              $(".error-name").text("");
              swal(
                product.jsonLang["Thêm chi nhánh thành công"],
                "",
                "success"
              );
              $(".branch-new > option").empty();
              $.each(response.branchOption, function (index, element) {
                $(".branch-new").append(
                  '<option value="' + index + '">' + element + "</option>"
                );
              });
              branch_name.val("");
              address.val("");
              phone.val("");
              des.val("");
              $(".error-name-branch").text("");
            } else {
              $(".error-name-branch").text(
                product.jsonLang["Tên chi nhánh đã tồn tại"]
              );
              $(".error-name-branch").css("color", "red");
            }
          },
        });
      },
    });
  },
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
            swal(product.jsonLang["Thêm danh mục thành công"], "", "success");
            $("#category").empty();
            $("#category").append('<option value="">Chọn danh mục</option>');
            $.map(data.category, function (value, key) {
              $("#category").append(
                '<option value="' + key + '">' + value + "</option>"
              );
            });
            categoryName.val("");
            description.val("");
            errorCategoryName.text("");
            $("#modal-add-product-category").modal("hide");
          } else {
            errorCategoryName.text(product.jsonLang["Danh mục đã tồn tại"]);
          }
        },
      });
    } else {
      errorCategoryName.text(product.jsonLang["Vui lòng nhập tên danh mục."]);
    }
  },
  addModalUnit: function () {
    var name = $("#modal-add-unit #name").val();
    if (name != "") {
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
            swal(
              product.jsonLang["Thêm đơn vị tính thành công"],
              "",
              "success"
            );
            $("#unit").empty();
            $("#unit").append('<option value="">Chọn đơn vị tính</option>');
            $.map(response.unitOption, function (value, key) {
              $("#unit").append(
                '<option value="' + key + '">' + value + "</option>"
              );
            });
          } else {
            $("#modal-add-unit .error-name").text(response.status);
            $("#modal-add-unit .error-name").css("color", "red");
          }
        },
      });
    } else {
      $("#modal-add-unit .error-name").text(
        product.jsonLang["Hãy nhập đơn vị tính"]
      );
    }
  },
  addModalProductModel: function () {
    $("#modal-add-product-model .error-product-model-name").css("color", "red");
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
            swal(
              product.jsonLang["Thêm nhãn hiệu sản phẩm thành công"],
              "",
              "success"
            );
            productModelName.val("");
            productModelNote.val("");
            $("#modal-add-product-model .error-product-model-name").text("");
            $("#modal-add-product-model").modal("hide");
            $("#productModel").empty();
            $("#productModel").append(
              '<option value="">Chọn nhãn hiệu</option>'
            );
            $.map(data.model, function (value, key) {
              $("#productModel").append(
                '<option value="' + key + '">' + value + "</option>"
              );
            });
          }
          if (data.status == 0) {
            $(".error-product-model-name").text(
              product.jsonLang["Nhãn sản phẩm đã tồn tại"]
            );
          }
        },
      });
    } else {
      $(".error-product-model-name").text(
        product.jsonLang["Vui lòng nhập tên nhãn hiệu sản phẩm."]
      );
    }
  },
};

function clearModalAddProductModel() {
  $("#product-model-name").val("");
  $("#product-model-note").val("");
  $(".error-product-model-name").text("");
}

function clearModalAddCategory() {
  $("#modalAddCategory #category-name").val("");
  $("#modalAddCategory #description").val("");
  $("#modalAddCategory #is_actived").val("1");
  $("#modalAddCategory .error-category-name").text("");
}

function clearModalAddSupplier() {
  $("#modalAddSupplier #supplierName").val("");
  $("#modalAddSupplier #description").val("");
  $("#modalAddSupplier #address").val("");
  $("#modalAddSupplier #contact_name").val("");
  $("#modalAddSupplier #contact_title").val("");
  $("#modalAddSupplier #contact_phone").val("");
  $("#modalAddSupplier .error-supplier-name").text("");
  $("#modalAddSupplier .error-contact-name").text("");
  $("#modalAddSupplier .error-contact-phone").text("");
}
