$("input[name=send_to]").change(function () {
    var radio = $("input[name=send_to]:checked").val();
    if (radio == 'group') {
        $("#cover-group").css("display", "block");
    } else {
        $("#cover-group").css("display", "none");
    }
});

function removeGroup(id)
{
    $("#"+id).remove();
}

function element(id, name)
{
    return '<div class="kt-section__content kt-section__content--solid-- col-6 div-group" id="'+id+'" style="margin-top: 10px;">\n' +
        '                                                        <div class="kt-searchbar">\n' +
        '                                                            <div class="input-group">\n' +
        '                                                                <input type="text" class="form-control group-name" readonly="readonly" value="'+name+'" >\n' +
        '                                                                <input type="hidden" name="group_id" value="'+id+'" >\n' +
        '                                                                <div class="input-group-prepend"><span class="input-group-text remove-group" onclick="removeGroup('+id+')" id="basic-addon1" style="cursor: pointer;">X</span></div>\n' +
        '                                                            </div>\n' +
        '                                                        </div>\n' +
        '                                                    </div>';
}

function handleClickGroup()
{
    $.getJSON('/admin/validation', function (json) {
        $.ajax({
            url: laroute.route("admin.notification.groupList"),
            method: "GET",
            data: {
                view: 'modal'
            },
            success: function (res) {
                group_radio = $("input[name=group_id]").val();
                $("#group-modal").html(res);
                // clear modal end point
                $("#end-point-modal").html('');
                $("#group-modal").find("#kt_modal_2").modal();
                // $('.select22').select2();
                getData(1);
                var globalFilter;
                $("#submit-search").click(function () {
                    var filter = {
                        // group_name: $("#search-name").val(),
                        name: $("#search-name").val(),
                        group_type: $("#search-type").val(),

                    };
                    globalFilter = filter;
                    getData(1, filter);
                    return false;
                });

                $(document).on('click', 'a.m-datatable__pager-link', function (event) {
                    event.preventDefault();
                    // $('li').removeClass('active');
                    // $(this).parent('li').addClass('active');
                    var page = $(this).attr('data-page');
                    if(page){
                        getData(page, globalFilter);
                    }
                });

                function getData(page, filter = null)
                {
                    $.ajax({
                        url: laroute.route("admin.notification.groupList", {page: page}),
                        method: "GET",
                        data: {
                            view: 'list',
                            filter: filter
                        },
                        success: function (res) {
                            $("#group-item-list").html(res);
                            $('input[name=group_radio]').each(function (i, obj) {
                                var id = $(this).val();
                                if (group_radio == id) {
                                    $(this).attr("checked", "checked");
                                }
                            });
                        }
                    })
                }

                $("#choose-group").click(function () {
                    radio = $('input[name=group_radio]:checked').val();
                    groupName = $('input[name=group_radio]:checked').attr('data-name');
                    if (radio) {
                        $(".div-group").remove(); // xóa group đã có
                        var elementGroup = element(radio, groupName);
                        $("#cover-group").prepend(elementGroup);
                        $("#close-btn").trigger("click");
                    } else {
                        swal.fire("", json.notification.group_checked, "error");
                    }
                });
            }
        });
    });
}
