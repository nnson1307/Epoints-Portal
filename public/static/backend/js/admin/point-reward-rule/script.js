// 
$(".m_selectpicker").select2();
$(".ss-select-2").select2();
// $('.input-mask').mask('000,000,000', {reverse: true});
// $(".numeric").numeric({ decimal : ".",  negative : false, scale: 2 });
var pointRewardRule = {
    savePurchase: function (t) {
        
        var data = [];
        var flag = true;
        $(t).parents('.div-purchase').find('.record_rule').each(function () {
            
            var temp = {};
            var point_reward_rule_id = $(this).find('.point_reward_rule_id').val();
            var isActived = $(this).find('.is_actived');
            var point_maths = $(this).find('.point_maths').val();
            var point_value = $(this).find('.point_value');
            var hagtag_id = $(this).find('.hagtag_id').val();
            var is_actived = 0;
            if (isActived.is(':checked')) {
                is_actived = 1;
            } else {
                is_actived = 0;
            }
            if (point_value.val() == '') {
                flag = false;
                $.getJSON(laroute.route('translate'), function (json) {
                point_value.parents('.col-lg-2').find('.error_point_value').text(json['Vui lòng nhập giá trị'])
                });
            } else {
                point_value.parents('.col-lg-2').find('.error_point_value').text('')
            }
            temp = {
                point_reward_rule_id: point_reward_rule_id,
                is_actived: is_actived,
                point_maths: point_maths,
                point_value: (point_value.val()),
                hagtag_id: hagtag_id,
            };
            data.push(temp);
        });
        if (flag == true) {
            $.ajax({
                url: laroute.route('admin.point-reward-rule.save'),
                method: "POST",
                data: {data: data},
                success: function (res) {
                    $.getJSON(laroute.route('translate'), function (json) {
                    if (res.error == false) {
                        swal.fire(json['Chỉnh sửa thành công'], "", "success").then(function (result) {
                            if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                location.reload();
                            }
                            if (result.value == true) {
                                location.reload();
                            }
                        });
                    } else {
                        $.getJSON(laroute.route('translate'), function (json) {
                        swal.fire(json['Chỉnh sửa thất bại'], "", "success");
                        });
                    }
                });
                }
            });
        }
    },
    saveConfig: function () {
        var reset_member_ranking = $('#reset_member_ranking').val();
        var actived_loyalty = 0;

        if ($('#actived_loyalty').is(':checked')) {
            actived_loyalty = 1;
        }

        $.ajax({
            url: laroute.route('admin.point-reward-rule.update-config'),
            method: "POST",
            data: {
                reset_member_ranking: reset_member_ranking,
                actived_loyalty: actived_loyalty,
            },
            success: function (res) {
                $.getJSON(laroute.route('translate'), function (json) {
                    if (res.error == false) {
                        swal.fire(json['Chỉnh sửa thành công'], "", "success").then(function (result) {

                        });
                    } else {
                        swal.fire(json['Chỉnh sửa thất bại'], "", "success");
                    }
                });
            }
        });
    },
    saveEvent: function (t) {
        var data = [];
        var flag = true;
        $(t).parents('.div-event').find('.record_rule').each(function () {
            var temp = {};
            var point_reward_rule_id = $(this).find('.point_reward_rule_id').val();
            var isActived = $(this).find('.is_actived');
            var point_value = $(this).find('.point_value');
            var is_actived = 0;
            if (isActived.is(':checked')) {
                is_actived = 1;
            } else {
                is_actived = 0;
            }
            if (point_value.val() == '') {
                flag = false;
                $.getJSON(laroute.route('translate'), function (json) {
                point_value.parents('.col-lg-2').find('.error_point_value').text(json['Vui lòng nhập giá trị'])
                });
            } else {
                point_value.parents('.col-lg-2').find('.error_point_value').text('')
            }
            temp = {
                point_reward_rule_id: point_reward_rule_id,
                is_actived: is_actived,
                point_value: parseFloat(point_value.val()),
            };
            data.push(temp);
        });
        if (flag == true) {
            $.ajax({
                url: laroute.route('admin.point-reward-rule.update-event'),
                method: "POST",
                data: {data: data},
                success: function (res) {
                    $.getJSON(laroute.route('translate'), function (json) {
                    if (res.error == false) {
                        swal.fire(json['Chỉnh sửa thành công'], "", "success").then(function (result) {
                            if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                // location.reload();
                            }
                            if (result.value == true) {
                                // location.reload();
                            }
                        });
                    } else {
                        swal.fire(json['Chỉnh sửa thất bại'], "", "success");
                    }
                });
                }
            });
        }
    },
    defaultInput: function (t) {
        if ($(t).val() == '') {
            $(t).val(0);
        }
    },
    isNumber: function (t) {
        // $(t).parents('.row').find('.point_value').val('');
        var val = $(t).val();
        if (val == '+') {
            $(t).parents('.row').find('.div-input').empty();
            let $_tpl = $('#tpl-input-mask').html();
            // div-input
            $(t).parents('.row').find('.div-input').append($_tpl);
        } else if (val == '*') {
            $(t).parents('.row').find('.div-input').empty();
            let $_tpl = $('#tpl-numeric').html();
            // div-input
            $(t).parents('.row').find('.div-input').append($_tpl);


            // $(t).parents('.row').find('.point_value').unmask();
            // $(t).parents('.row').find('.point_value').numeric({ decimal : ".",  negative : false, scale: 10 });

        }
        $('.input-mask').mask('000,000,000', {reverse: true});

        $(".numeric").numeric({ decimal : ".",  negative : false, scale: 2 });
    },
    pointMaths: function () {
        $('.point_maths').each(function () {
            var val = $(this).val();
            if (val == '+') {
                $(this).parents('.row').find('.point_value').removeClass('numeric');
                $(this).parents('.row').find('.point_value').mask('000,000,000', {reverse: true});
            } else if (val == '*') {
                $(this).parents('.row').find('.point_value').removeClass('input-mask');
                $(this).parents('.row').find('.point_value').unmask();
                $(this).parents('.row').find('.point_value').numeric({ decimal : ".",  negative : false, scale: 2 });

            }
        })
    }
};
pointRewardRule.pointMaths();
