$('.divTableRow').nextUntil('.divTableRow').hide();

$('.category-toggle').on("click", function () {
    var parent = $(this).closest('.divTableRow');

    $(parent).nextUntil('.divTableRow').slideToggle(200);
});

$(".category-move").on('mousedown', function () {
    var level = $(this).attr("data-level");
    var parent_id = $(this).attr("data-parent-id");
    $(function () {
        $(".sortable").sortable({
            placeholder: "ui-state-highlight",
            handle: ".category-move",
            items: ".level-" + level + "-" + parent_id,
            stop: function (event, ui) {
                // $( ".sortable" ).sortable( "cancel" );
                var id_array = [];
                var sequence_array = [];
                $(".sortable").find(".level-" + level + "-" + parent_id).each(function () {
                    id_array.push($(this).attr("data-id"));
                    sequence_array.push($(this).attr("data-sequence"));
                });

                $.ajax({
                    method: 'POST',
                    url: laroute.route('product.product-category.sortSequence'),
                    data: {_token: token, id_array: id_array, sequence_array: sequence_array},
                    success: function (result) {
                        if (result) {
                            toastr.success(sort_sequence);
                        }
                    }
                });
            }
        });

        // $(".sortable").on("click", ".category-click", function () {
        //     alert(567);
        // });
        // $( ".sortable" ).sortable( "disable" );
    });
});//.closest('.divTableRow'); alert(e.attr('class'))

$(".category-click-up").click(function () {
    var level = $(this).attr("data-level");
    var parent_id = $(this).attr("data-parent-id");
    var e = $(this).closest(".level-" + level + "-" + parent_id);

    if (e.prev().attr('class') == e.attr('class')) {
        // move up:
        e.prev().insertAfter(e);

        var id_array = [];
        var sequence_array = [];
        $(".sortable").find(".level-" + level + "-" + parent_id).each(function () {
            id_array.push($(this).attr("data-id"));
            sequence_array.push($(this).attr("data-sequence"));
        });

        $.ajax({
            method: 'POST',
            url: laroute.route('product.product-category.sortSequence'),
            data: {_token: token, id_array: id_array, sequence_array: sequence_array},
            success: function (result) {
                if (result) {
                    toastr.success(sort_sequence);
                }
            }
        });
    }
});

$(".category-click-down").click(function () {
    var level = $(this).attr("data-level");
    var parent_id = $(this).attr("data-parent-id");
    var e = $(this).closest(".level-" + level + "-" + parent_id);
    if (e.next().attr('class') == e.attr('class')) {
        // move down:
        e.next().insertBefore(e);

        var id_array = [];
        var sequence_array = [];
        $(".sortable").find(".level-" + level + "-" + parent_id).each(function () {
            id_array.push($(this).attr("data-id"));
            sequence_array.push($(this).attr("data-sequence"));
        });

        $.ajax({
            method: 'POST',
            url: laroute.route('product.product-category.sortSequence'),
            data: {_token: token, id_array: id_array, sequence_array: sequence_array},
            success: function (result) {
                if (result) {
                    toastr.success(sort_sequence);
                }
            }
        });
    }
});