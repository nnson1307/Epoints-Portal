var CardDetail = {
    start:function () {

        $.ajax({
            url:laroute.route("admin.service-card-list.detail-list-unuse"),
            method:"POST",
            data:{
                service_card_id:$("input[name=service_card_id]").val(),
                branch_id:$("input[name=branch_id]").val()
            },
            success:function (resp) {
                $(".table-content").html(resp.html);
            }
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

        $(document).on("click",".btnCardType",function () {
            $(".btnCardType").removeClass("active-btn");
            $(this).addClass("active-btn");

            var type = $(this).attr("data-type");
            if(type=="Inuse"){
                $.ajax({
                    url:laroute.route("admin.service-card-list.detail-list-inuse"),
                    method:"POST",
                    data:{
                        service_card_id:$("input[name=service_card_id]").val(),
                        branch_id:$("input[name=branch_id]").val()
                    },
                    success:function (resp) {
                        $(".table-content").html(resp);
                        $(".btn-print").addClass("disabled");
                    }
                });
            }else if(type=="Unuse"){
                $.ajax({
                    url:laroute.route("admin.service-card-list.detail-list-unuse"),
                    method:"POST",
                    data:{
                        service_card_id:$("input[name=service_card_id]").val(),
                        branch_id:$("input[name=branch_id]").val()
                    },
                    success:function (resp) {
                        $(".table-content").html(resp.html);
                        $(".btn-print").removeClass("disabled");
                    }
                });
            }
        });
    }
};

CardDetail.start();