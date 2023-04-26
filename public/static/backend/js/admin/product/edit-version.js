var editVersion = {
  jsonLang: JSON.parse(localStorage.getItem("tranlate")),
};
var arrNameChild = new Array();
// $.each($('#edit-product-version tr td'), function () {
//     var $tds = $(this).find("input[name='hiddennameversion[]']");
//     $.each($tds, function () {
//         arrNameChild.push($(this).val());
//     });
// });
$(".product-child1").each(function () {
  arrNameChild.push($(this).val());
});
var arrayCodeVersionDelete = [];

// Select product attribute.

$('select[name="sProducAttribute[]"]')
  .select2({
    placeholder: editVersion.jsonLang["Chọn thuộc tính"],
    allowClear: true,
    tags: true,
    createTag: function (tag) {
      return {
        id: tag.term,
        text: tag.term,
        isNew: true,
      };
    },
  })
  .on("select2:select", function (e) {
    let sku = $("#product_sku");
    // BEGIN INSERT TAG (product attribute)
    if (e.params.data.isNew) {
      // store the new tag:
      $.ajax({
        type: "POST",
        url: laroute.route("admin.product-attribute.add"),
        data: {
          productAttributeGroup_id: $('select[name="selectAttrGr[]"]').val(),
          productAttributeLabel: e.params.data.text,
        },
        success: function (res) {
          // append the new option element end replace id
          $('select[name="sProducAttribute[]"]')
            .find('[value="' + e.params.data.id + '"]')
            .replaceWith(
              '<option selected value="' +
                res.productAttributeId +
                '">' +
                e.params.data.text +
                "</option>"
            );
        },
      });
    }
    let isSkuExist = false;

    $.each(
      $('#edit-product-version tr input[name="product_sku"]').parentsUntil(
        "tbody"
      ),
      function () {
        var $tds = $(this).find('td input[name="product_sku"]');
        $.each($tds, function () {
          console.log($(this).val());
          if (sku.val() != "") {
            if (sku.val() == $(this).val()) {
              $(".error-product-sku").text("Mã sku đã tồn tại");
              isSkuExist = true;
              return;
            }
          }
        });
      }
    );
    if (isSkuExist) {
      $("#selectAttrGr option[value='" + dataSelect.id + "']").remove();
      return;
    }
    // END
    let productName = $("#product-name");
    let cost = $("#cost");
    let price = $("#price");
    // let attribute = e.params.data.text;
    let realvalues = [];
    let textvalues = [];
    $('select[name="sProducAttribute[]"] :selected').each(function (
      i,
      selected
    ) {
      realvalues[i] = $(selected).val();
      textvalues[i] = $(selected).text();
    });
    var arrAttribute = [];
    $("div")
      .find(".class-procuct-attibute")
      .each(function (rowIndex, r) {
        var cols = [];
        $(this)
          .find('select[name="sProducAttribute[]"] :selected')
          .each(function (colIndex, c) {
            cols.push(c.textContent);
          });
        arrAttribute.push(cols);
      });
    if (arrAttribute != "") {
      $("#addGroupAttribute").prop("disabled", false);
    }
    if (arrayAttributeGroup.length > 7) {
      $("#addGroupAttribute").prop("disabled", true);
    }
    for (var j = 0; j < arrAttribute.length; j++) {
      if (arrAttribute[j] == "") {
        arrAttribute.splice(j, 1);
      }
    }
    var aaaaaaaa = [];
    for (var i = 0; i < arrAttribute.length; i++) {
      if (arrAttribute[i].length > 0) {
        aaaaaaaa.push(arrAttribute[i]);
      }
    }
    var arrNameChild1 = new Array();
    $(".product-child1").each(function () {
      arrNameChild1.push($(this).val());
    });
    if (aaaaaaaa.length == 1) {
      $(".product-child-appen").each(function () {
        this.remove();
      });
      // $('tbody').empty();
      let xxx = 0;
      for (let i = 0; i < aaaaaaaa.length; i++) {
        for (let j = 0; j < aaaaaaaa[i].length; j++) {
          if (
            arrNameChild1.indexOf(productName.val() + "/" + aaaaaaaa[i][j]) ==
            -1
          ) {
            xxx = 1 + $("#edit-product-version > tbody tr").length++;
            var $_tpl = $("#product-childs").html();
            var tpl = $_tpl;
            tpl = tpl.replace(/{stt}/g, xxx);
            tpl = tpl.replace(
              /{name}/g,
              productName.val() + "/" + aaaaaaaa[i][j]
            );
            tpl = tpl.replace(/{id}/g, "");
            tpl = tpl.replace(/{cost}/g, cost.val());
            tpl = tpl.replace(/{price}/g, price.val());
            tpl = tpl.replace(/{sku}/g, sku.val());
            $("#edit-product-version > tbody").append(tpl);

            new AutoNumeric.multiple(".price_" + xxx + "", {
              currencySymbol: "",
              decimalCharacter: ".",
              digitGroupSeparator: ",",
              decimalPlaces: decimal_number,
              minimumValue: 0,
            });
          }
        }
      }
    } else {
      let xxx = 0;
      $.ajax({
        url: laroute.route("create-name-product-child"),
        method: "POST",
        dataType: "JSON",
        data: { arrAttribute: arrAttribute },
        success: function (data) {
          $("#edit-product-version tbody tr.product-child-appen").remove();
          for (let j = 0; j < data.length; j++) {
            let versionName = productName.val() + "/" + data[j];
            if (arrNameChild1.indexOf(versionName) == -1) {
              let t = 1 + $("#edit-product-version > tbody tr").length++;
              var $_tpl = $("#product-childs").html();
              var tpl = $_tpl;
              tpl = tpl.replace(/{stt}/g, t);
              tpl = tpl.replace(/{name}/g, versionName);
              tpl = tpl.replace(/{id}/g, "");
              tpl = tpl.replace(/{cost}/g, cost.val());
              tpl = tpl.replace(/{price}/g, price.val());
              $("#edit-product-version > tbody").append(tpl);

              new AutoNumeric.multiple(".price_" + xxx + "", {
                currencySymbol: "",
                decimalCharacter: ".",
                digitGroupSeparator: ",",
                decimalPlaces: decimal_number,
                minimumValue: 0,
              });
            }
          }
        },
      });
    }
    if ($("select[name='sProducAttribute[]']").last().val() == "") {
      $("#addGroupAttribute").prop("disabled", true);
    }
  })
  .on("select2:unselect", function (e) {
    //Unselect option select2
    let attributeText = e.params.data.text;
    let productName = $("#product-name");
    let cost = $("#cost");
    let price = $("#price");
    let textvalues = [];
    let arrayProductChildExistIsDelete = new Array();
    var arrNameChild2 = new Array();
    var arrAttribute = [];
    $("#edit-product-version tr td:contains('" + attributeText + "')").each(
      function () {
        arrayCodeVersionDelete.push(
          $(this).find('input[name="hiddennameversion[]"]').val()
        );
        $(this).parent().remove();
      }
    );
    $("div")
      .find(".class-procuct-attibute")
      .each(function (rowIndex, r) {
        var cols = [];
        $(this)
          .find('select[name="sProducAttribute[]"] :selected')
          .each(function (colIndex, c) {
            cols.push(c.textContent);
          });
        arrAttribute.push(cols);
      });
    for (var i = 0; i < arrAttribute.length; i++) {
      if (arrAttribute[i] == "") {
        arrAttribute.splice(i, 1);
      }
    }
    if (arrAttribute != "") {
      $("#addGroupAttribute").prop("disabled", false);
    }
    if (arrayAttributeGroup.length > 7) {
      $("#addGroupAttribute").prop("disabled", true);
    }

    for (var xx = 0; xx < arrAttribute.length; xx++) {
      if (arrAttribute[xx] == "") {
        arrAttribute.splice(xx, 1);
      }
    }
    $(".product-child1").each(function () {
      var $this = $(this);
      if (arrayCodeVersionDelete.indexOf($this.val()) != -1) {
        $this.remove();
      }
    });

    $(".product-child1").each(function () {
      arrNameChild2.push($(this).val());
    });

    if (arrAttribute.length == 1) {
      let xxx = 0;
      $(".product-child-appen").remove();

      for (let i = 0; i < arrAttribute.length; i++) {
        for (let j = 0; j < arrAttribute[i].length; j++) {
          let versionName = productName.val() + "/" + arrAttribute[i][j];
          if (arrNameChild2.indexOf(versionName) == -1) {
            let versionName = productName.val() + "/" + arrAttribute[i][j];
            var $_tpl = $("#product-childs").html();

            let t = 1 + $("#edit-product-version > tbody tr").length++;
            var tpl = $_tpl;
            tpl = tpl.replace(/{stt}/g, t);
            tpl = tpl.replace(/{name}/g, versionName);
            tpl = tpl.replace(/{id}/g, "");
            tpl = tpl.replace(/{cost}/g, cost.val());
            tpl = tpl.replace(/{price}/g, price.val());
            $("#edit-product-version > tbody").append(tpl);

            new AutoNumeric.multiple(".price_" + xxx + "", {
              currencySymbol: "",
              decimalCharacter: ".",
              digitGroupSeparator: ",",
              decimalPlaces: decimal_number,
              minimumValue: 0,
            });
          }
        }
      }
      let $stt = 0;
      $(".stt").each(function () {
        $(this).text(($stt += 1));
      });
    } else {
      let xxx = 0;
      for (let i = 0; i < arrAttribute.length; i++) {
        if (arrAttribute[i] == "") {
          arrAttribute.splice(i, 1);
        }
      }
      $(".product-child-appen").remove();

      let $stt = 0;
      $(".stt").each(function () {
        $(this).text(($stt += 1));
      });

      $.ajax({
        url: laroute.route("create-name-product-child"),
        method: "POST",
        dataType: "JSON",
        data: { arrAttribute: arrAttribute },
        success: function (data) {
          $("#edit-product-version tbody tr.product-child-appen").remove();

          for (let j = 0; j < data.length; j++) {
            let versionName = productName.val() + "/" + data[j];
            if (arrNameChild2.indexOf(versionName) == -1) {
              let t = 1 + $("#edit-product-version > tbody tr").length++;
              var $_tpl = $("#product-childs").html();
              var tpl = $_tpl;
              tpl = tpl.replace(/{stt}/g, t);
              tpl = tpl.replace(/{name}/g, versionName);
              tpl = tpl.replace(/{id}/g, "");
              tpl = tpl.replace(/{cost}/g, cost.val());
              tpl = tpl.replace(/{price}/g, price.val());
              $("#edit-product-version > tbody").append(tpl);

              new AutoNumeric.multiple(".price_" + xxx + "", {
                currencySymbol: "",
                decimalCharacter: ".",
                digitGroupSeparator: ",",
                decimalPlaces: decimal_number,
                minimumValue: 0,
              });
            }
          }
        },
      });
    }
  });

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
$('select[name="selectAttrGr[]"]').select2({
  placeholder: editVersion.jsonLang["Nhóm thuộc tính"],
});

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