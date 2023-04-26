// console.log();
$(document).ready(function () {

});
let routess = laroute.route('admin.email.list-detail');
var sub=routess.substring(0, 19) + '/' + $('#campaign_id').val();

$('#autotable').PioTable({
    baseUrl: sub
});