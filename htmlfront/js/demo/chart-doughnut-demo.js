var data = {
    labels: ["30 khách đang phục vụ", "10 khách đang phục vụ"],
    datasets: [{
        data: [30, 70],
        backgroundColor: ['#f8e367', '#93ccce'],
    }],

};
var options = {
    animation: {
        animateRotate: true,
        animateScale: true
    },
    title: {
        display: false,
        position: 'left'
    },
    cutoutPercentage: 70

}
var ctx = $("#myDoughnutChart");
var myChart = new Chart(ctx, {
    type: 'doughnut',
    data: data,
    options: options
});