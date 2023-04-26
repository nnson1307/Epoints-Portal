var Report = {
    _init: function () {
            $("#date_search").datepicker({
                format: "yyyy",
                viewMode: "years",
                minViewMode: "years"
            }).on('changeDate', function(e){
                $(this).datepicker('hide');
                var people_object_group_id = $('#group_search').val();
                window.location.href = laroute.route('people.report', {'year' : e.format(),'people_object_group_id':people_object_group_id });
            });

        $('#group_search').change(function () {
            var peopleObjectGroupId = $("#group_search").val();
            var year = $("#date_search").val();
            window.location.href = laroute.route('people.report', {'year' : year,'people_object_group_id':peopleObjectGroupId });
        });
        // $.getJSON(laroute.route('translate'), function (json) {
        //     console.log(this.json);
        //     var arrRange = {};
        //     arrRange[json["Hôm nay"]] = [moment(), moment()];
        //     arrRange[json["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
        //     arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
        //     arrRange[json["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
        //     arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
        //     arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
        //     // $("#date_search").datepicker({
        //     //     format: "yyyy",
        //     //     viewMode: "years",
        //     //     minViewMode: "years"
        //     // });
        //
        // });
    },
}

$(document).ready(function (){
    $('.box-shadow-report').click(function (){
        $('.position-absolute-arrow img').toggleClass('rotate');
    });
})

Report._init();
