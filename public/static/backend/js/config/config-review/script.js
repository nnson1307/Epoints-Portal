var view = {
    _init: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            for (let i = 1; i <= 5; i++) {
                $("#content_suggest_" + i + "").select2({
                    placeholder: json['Chọn cú pháp'],
                    tags: true,
                    // tokenSeparators: [",", " "],
                    createTag: function (tag) {
                        return {
                            id: tag.term,
                            text: tag.term,
                            rating_value: i,
                            isNew: true
                        };
                    }
                }).on("select2:select", function (e) {
                    if (e.params.data.isNew) {
                        // store the new tag:
                        $.ajax({
                            type: "POST",
                            url: laroute.route('config.config-review.insert-content-suggest'),
                            data: {
                                content_suggest: e.params.data.text,
                                rating_value: e.params.data.rating_value
                            },
                            success: function (res) {
                                // append the new option element end replace id
                                $("#content_suggest_" + i + "").find('[value="' + e.params.data.text + '"]')
                                    .replaceWith('<option selected value="' + res.id_content + '">' + e.params.data.text + '</option>');
                            }
                        });
                    }
                });
            }
        });

        $(document).ready(function () {
            $('#is_buy').select2();
            $('.input_int').ForceNumericOnly();
        });
    },
    checkAddImage: function (obj) {
        if ($(obj).is(':checked')) {
            $('.div_config_image').css('display', 'flex');
        } else {
            $('.div_config_image').css('display', 'none');
        }
    },
    checkAddVideo: function (obj) {
        if ($(obj).is(':checked')) {
            $('.div_config_video').css('display', 'flex');
        } else {
            $('.div_config_video').css('display', 'none');
        }
    },
    saveOrder: function (configReviewId) {
        var is_review_image = 0;
        if ($('#is_review_image').is(':checked')) {
            is_review_image = 1;
        }

        var is_review_video = 0;
        if ($('#is_review_video').is(':checked')) {
            is_review_video = 1;
        }

        var is_suggest = 0;
        if ($('#is_suggest').is(':checked')) {
            is_suggest = 1;
        }

        var is_review_google = 0;
        if ($('#is_review_google').is(':checked')) {
            is_review_google = 1;
        }

        var ratingValueGoogle = [];

        $('input[name="rating_value_google"]:checked').each(function() {
            ratingValueGoogle.push(this.value);
        });


        $.ajax({
            url: laroute.route('config.config-review.update-config-order'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                config_review_id: configReviewId,
                expired_review: $('#expired_review').val(),
                max_length_content: $('#max_length_content').val(),
                is_review_image: is_review_image,
                limit_number_image: $('#limit_number_image').val(),
                limit_capacity_image: $('#limit_capacity_image').val(),
                is_review_video: is_review_video,
                limit_number_video: $('#limit_number_video').val(),
                limit_capacity_video: $('#limit_capacity_video').val(),
                is_suggest: is_suggest,
                content_suggest_5: $('#content_suggest_5').val(),
                content_suggest_4: $('#content_suggest_4').val(),
                content_suggest_3: $('#content_suggest_3').val(),
                content_suggest_2: $('#content_suggest_2').val(),
                content_suggest_1: $('#content_suggest_1').val(),
                content_hint_5: $('#content_hint_5').val(),
                content_hint_4: $('#content_hint_4').val(),
                content_hint_3: $('#content_hint_3').val(),
                content_hint_2: $('#content_hint_2').val(),
                content_hint_1: $('#content_hint_1').val(),
                is_review_google: is_review_google,
                rating_value_google: ratingValueGoogle
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                            window.location.reload();
                        }
                        if (result.value == true) {
                            window.location.reload();
                        }
                    });
                } else {
                    swal(res.message, '', "error");
                }
            }
        });
    }
};

jQuery.fn.ForceNumericOnly =
    function () {
        return this.each(function () {
            $(this).keydown(function (e) {
                var key = e.charCode || e.keyCode || 0;
                // allow backspace, tab, delete, enter, arrows, numbers and keypad numbers ONLY
                // home, end, period, and numpad decimal
                return (
                    key == 8 ||
                    key == 9 ||
                    key == 13 ||
                    key == 46 ||
                    key == 110 ||
                    key == 190 ||
                    (key >= 35 && key <= 40) ||
                    (key >= 48 && key <= 57) ||
                    (key >= 96 && key <= 105));
            });
        });
    };