var addVersion = {
  jsonLang: JSON.parse(localStorage.getItem("tranlate")),
};
var allProductAttributeGroup = [];
$('select[name="selectAttrGr[]"] option').each(function () {
  allProductAttributeGroup.push($(this).val());
});
for (let z = 0; z < allProductAttributeGroup.length; z++) {
  if (allProductAttributeGroup[z] == "") {
    allProductAttributeGroup.splice(z, 1);
  }
}

$('select[name="sProducAttribute[]"]')
  .select2({
    placeholder: addVersion.jsonLang["Chọn thuộc tính"],
    allowClear: true,
    tags: true,
    maximumSelectionLength: 5,
    createTag: function (tag) {
      return {
        id: tag.term,
        text: tag.term,
        isNew: true,
      };
    },
  })
  .on("select2:select", function (e) {
    // BEGIN INSERT TAG (product attribute)
    var dataSelect = e.params.data;
    let sku = $("#product_sku");
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
    $(".error-product-sku").text("");
    let isSkuExist = false;
    $.each(
      $('#add-product-version tr input[name="sku"]').parentsUntil("tbody"),
      function () {
        var $tds = $(this).find('td input[name="sku"]');
        $.each($tds, function () {
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
    $("#check-all").prop("checked", false);
    $("#hide-price").val($("#price").val());
    $("#hide-cost").val($("#cost").val());
    $("#hide-name").val($("#product-name").val());
    let productName = $("#product-name");
    let cost = $("#cost");

    let price = $("#price");
    let attribute = e.params.data.text;
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
        console.log(arrAttribute);
      });
    if (arrAttribute != "") {
      $("#adGroupAttribute").prop("disabled", false);
    }
    if (arrayAttributeGroup.length > 7) {
      $("#adGroupAttribute").prop("disabled", true);
    }
    for (var i = 0; i < arrAttribute.length; i++) {
      if (arrAttribute[i] == "") {
        arrAttribute.splice(i, 1);
      }
    }
    if (arrAttribute.length == 1) {
      let arrtextIdAttribute = [];
      $.each(
        $("select[name='sProducAttribute[]'] option:selected"),
        function () {
          arrtextIdAttribute.push($(this).val());
        }
      );
      let $_tpl = $("#product-childs").html();
      for (let i = 0; i < arrAttribute.length; i++) {
        $("tbody").empty();
        let xxx = 0;
        for (let j = 0; j < arrAttribute[i].length; j++) {
          xxx = j + 1;
          let tpl = $_tpl;
          tpl = tpl.replace(/{stt}/g, xxx);
          tpl = tpl.replace(
            /{name}/g,
            productName.val() + "/" + arrAttribute[i][j]
          );
          tpl = tpl.replace(/{cost}/g, cost.val());
          tpl = tpl.replace(/{sku}/g, sku.val());
          tpl = tpl.replace(/{price}/g, price.val());
          $("#add-product-version > tbody").append(tpl);

          $("#check-all").prop("checked", true);



          new AutoNumeric.multiple(".price_" + xxx + "", {
            currencySymbol: "",
            decimalCharacter: ".",
            digitGroupSeparator: ",",
            decimalPlaces: decimal_number,
            minimumValue: 0,
          });
        }
      }
    } else {
      $("tbody").empty();
      var count = 0;
      for (let i = 0; i < arrAttribute.length; i++) {
        if (arrAttribute[i].length != 0) {
          count++;
        }
      }

      if (count == 1) {
        let arrtextIdAttribute = [];
        $.each(
          $("select[name='sProducAttribute[]'] option:selected"),
          function () {
            arrtextIdAttribute.push($(this).val());
          }
        );
        let xxx = 0;
        let $_tpl = $("#product-childs").html();
        for (let i = 0; i < arrAttribute.length; i++) {
          for (let j = 0; j < arrAttribute[i].length; j++) {
            xxx = j + 1;
            //code version.
            let tpl = $_tpl;
            tpl = tpl.replace(/{stt}/g, xxx);
            tpl = tpl.replace(
              /{name}/g,
              productName.val() + "/" + arrAttribute[i][j]
            );
            tpl = tpl.replace(/{cost}/g, cost.val());
            tpl = tpl.replace(/{price}/g, price.val());
            $("#add-product-version > tbody").append(tpl);
            $("#check-all").prop("checked", true);

            new AutoNumeric.multiple(".price_" + xxx + "", {
              currencySymbol: "",
              decimalCharacter: ".",
              digitGroupSeparator: ",",
              decimalPlaces: decimal_number,
              minimumValue: 0,
            });
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
            $("tbody").empty();
            let $_tpl = $("#product-childs").html();
            for (let j = 0; j < data.length; j++) {
              let t = j + 1;
              //code version.
              let tpl = $_tpl;
              tpl = tpl.replace(/{stt}/g, t);
              tpl = tpl.replace(/{name}/g, productName.val() + "/" + data[j]);
              tpl = tpl.replace(/{cost}/g, cost.val());
              tpl = tpl.replace(/{price}/g, price.val());
              $("#add-product-version > tbody").append(tpl);
              $("#check-all").prop("checked", true);

              new AutoNumeric.multiple(".price_" + xxx + "", {
                currencySymbol: "",
                decimalCharacter: ".",
                digitGroupSeparator: ",",
                decimalPlaces: decimal_number,
                minimumValue: 0,
              });
            }
          },
        });
      }
    }

    if($('input[name="is_master[]"]').is(':checked') == false){
      $('input[name="is_master[]"]:first').prop("checked",true);
    }
  })
  .on("select2:unselect", function (e) {
    $("#check-all").prop("checked", false);
    let productName = $("#product-name");
    let cost = $("#cost");
    let price = $("#price");
    let textvalues = [];
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
    for (var i = 0; i < arrAttribute.length; i++) {
      if (arrAttribute[i] == "") {
        arrAttribute.splice(i, 1);
      }
    }
    if (arrAttribute != "") {
      $("#adGroupAttribute").prop("disabled", false);
    }
    if (arrayAttributeGroup.length > 7) {
      $("#adGroupAttribute").prop("disabled", true);
    }
    if (arrAttribute.length == 1) {
      let arrtextIdAttribute = [];
      $.each(
        $("select[name='sProducAttribute[]'] option:selected"),
        function () {
          arrtextIdAttribute.push($(this).val());
        }
      );
      let $_tpl = $("#product-childs").html();
      let xxx = 0;
      for (let i = 0; i < arrAttribute.length; i++) {
        $("tbody").empty();
        for (let j = 0; j < arrAttribute[i].length; j++) {
          xxx = j + 1;
          let tpl = $_tpl;
          tpl = tpl.replace(/{stt}/g, xxx);
          tpl = tpl.replace(
            /{name}/g,
            productName.val() + "/" + arrAttribute[i][j]
          );
          tpl = tpl.replace(/{cost}/g, cost.val());
          tpl = tpl.replace(/{price}/g, price.val());
          $("#add-product-version > tbody").append(tpl);
          $("#check-all").prop("checked", true);

          new AutoNumeric.multiple(".price_" + xxx + "", {
            currencySymbol: "",
            decimalCharacter: ".",
            digitGroupSeparator: ",",
            decimalPlaces: decimal_number,
            minimumValue: 0,
          });
        }
      }
    } else {
      $("tbody").empty();
      let $_tpl = $("#product-childs").html();
      var count = 0;
      for (let i = 0; i < arrAttribute.length; i++) {
        if (arrAttribute[i].length != 0) {
          count++;
        }
      }
      if (count == 1) {
        let arrtextIdAttribute = [];
        $.each(
          $("select[name='sProducAttribute[]'] option:selected"),
          function () {
            arrtextIdAttribute.push($(this).val());
          }
        );
        let xxx = 0;
        for (let i = 0; i < arrAttribute.length; i++) {
          for (let j = 0; j < arrAttribute[i].length; j++) {
            textvalues.toString();
            arrtextIdAttribute.toString();
            let nameversion = textvalues.join("/");
            //code version.
            xxx = j + 1;

            let tpl = $_tpl;
            tpl = tpl.replace(/{stt}/g, xxx);
            tpl = tpl.replace(
              /{name}/g,
              productName.val() + "/" + arrAttribute[i][j]
            );
            tpl = tpl.replace(/{cost}/g, cost.val());
            tpl = tpl.replace(/{price}/g, price.val());
            $("#add-product-version > tbody").append(tpl);
            $("#check-all").prop("checked", true);

            new AutoNumeric.multiple(".price_" + xxx + "", {
              currencySymbol: "",
              decimalCharacter: ".",
              digitGroupSeparator: ",",
              decimalPlaces: decimal_number,
              minimumValue: 0,
            });
          }
        }
      } else {
        let xxx = 0;
        let $_tpl = $("#product-childs").html();
        $.ajax({
          url: laroute.route("create-name-product-child"),
          method: "POST",
          dataType: "JSON",
          data: { arrAttribute: arrAttribute },
          success: function (data) {
            //code version.
            $("tbody").empty();
            for (let j = 0; j < data.length; j++) {
              let t = j + 1;
              let tpl = $_tpl;
              tpl = tpl.replace(/{stt}/g, t);
              tpl = tpl.replace(/{name}/g, productName.val() + "/" + data[j]);
              tpl = tpl.replace(/{cost}/g, cost.val());
              tpl = tpl.replace(/{price}/g, price.val());
              $("#add-product-version > tbody").append(tpl);
              $("#check-all").prop("checked", true);

              new AutoNumeric.multiple(".price_" + xxx + "", {
                currencySymbol: "",
                decimalCharacter: ".",
                digitGroupSeparator: ",",
                decimalPlaces: decimal_number,
                minimumValue: 0,
              });
            }
          },
        });
      }
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

function maskNumberPriceProductChild($this) {
  $($this).mask("000,000,000", { reverse: true });
}
$('select[name="selectAttrGr[]"]').select2({
  placeholder: addVersion.jsonLang["Nhóm thuộc tính"],
});
