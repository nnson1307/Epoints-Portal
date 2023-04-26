var ServiceCardList = {
    init:function () {
        $("#search-name-field").select2();
        $("#branch").select2();
        $("#template").select2();

        $.ajax({
            url:laroute.route("admin.service-card-list.getprice"),
            method:"POST",
            data:{
                service_card:$("#search-name-field").val()
            },
            success:function (resp) {
                $("#card-price").val(resp);
            }
        });

        $("#search-name-field").change(function () {
            var value= $(this).val();
           $.ajax({
              url:laroute.route("admin.service-card-list.getprice"),
              method:"POST",
              data:{
                  service_card:value
              },
              success:function (resp) {
                  $("#card-price").val(resp);
              }
           });
        });

        $(document).on("change",".ckb-all",function () {
            if($(this).prop("checked")) {
                $(".ckb-item").prop("checked", "on").trigger("change");
            }
            else{
                $(".ckb-item").prop("checked", "").trigger("change");
            }

        });

        $(document).on("change",".ckb-item",function () {
            if($(this).prop("checked")){
                var code = $(this).parents("tr").find(".c_code").html();
                console.log(code);
                $(this).parent("label").append("<input type='hidden' name='code[]' value='"+code+"'>");
            }else{
                $(this).parent("label").find("input[name='code[]']").remove();
            }
        });
    },
    getCardCode:function () {
        var num = $("#quantity").val();
        if(jQuery.isNumeric(num)){
            if(num != 0){
                $.ajax({
                    url:laroute.route("admin.service-card-list.getcode"),
                    method:"POST",
                    data:{
                        quantity:num
                    },
                    success:function (resp) {
                        $("table.table tbody").html(resp.html);
                        $('.ckb-all').prop('checked',true);
                    }
                });
            }
        }else{
            $.getJSON(laroute.route('translate'), function (json) {
                $.notify({
                    // options
                    message: json['Xin hãy nhập lại Số lượng']
                },{
                    // settings
                    type: 'danger'
                });
            });
        }
    },

    saveCard:function () {
        $("#form-create input[name=action]").val("save");

        $("#form-create").submit();
    },
    saveCardAndPrint:function () {

    }
};

ServiceCardList.init();