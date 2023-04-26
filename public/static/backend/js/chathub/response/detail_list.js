$(document).ready(function () {
    $('.select2').select2();
});

$('#autotable').PioTable({
    baseUrl: laroute.route('chathub.response.detail-list')
});

