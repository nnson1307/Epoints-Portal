//== Class definition
var Dashboard = function() {
    //== Support Tickets Chart.
    //** Based on Morris plugin - http://morrisjs.github.io/morris.js/
    var supportTickets2 = function() {
        if ($('#m_chart_support_tickets2').length == 0) {
            return;
        }

        var chart = new Chartist.Pie('#m_chart_support_tickets2', {
            series: [{
                    value: value_new,
                    className: 'custom',
                    meta: {
                        color: '#44a581' //mApp.getColor('brand')
                    },

                },
                {
                    value: value_processing,
                    className: 'custom',
                    meta: {
                        color: '#eb7c31' //mApp.getColor('accent')
                    }
                },
                {
                    value: value_out_of_date,
                    className: 'custom',
                    meta: {
                        color: '#7e7e7e' //mApp.getColor('warning')
                    }
                }
            ],
            labels: [value_new + '%', value_processing + '%', value_out_of_date + '%']
        }, {
            donut: true,
            donutWidth: 35,
            showLabel: true,
        });

        chart.on('draw', function(data) {
            if (data.type === 'slice') {
                // Get the total path length in order to use for dash array animation
                var pathLength = data.element._node.getTotalLength();

                // Set a dasharray that matches the path length as prerequisite to animate dashoffset
                data.element.attr({
                    'stroke-dasharray': pathLength + 'px ' + pathLength + 'px'
                });

                // Create animation definition while also assigning an ID to the animation for later sync usage
                var animationDefinition = {
                    'stroke-dashoffset': {
                        id: 'anim' + data.index,
                        dur: 1000,
                        from: -pathLength + 'px',
                        to: '0px',
                        easing: Chartist.Svg.Easing.easeOutQuint,
                        // We need to use `fill: 'freeze'` otherwise our animation will fall back to initial (not visible)
                        fill: 'freeze',
                        'stroke': data.meta.color
                    }
                };

                // If this was not the first slice, we need to time the animation so that it uses the end sync event of the previous animation
                if (data.index !== 0) {
                    animationDefinition['stroke-dashoffset'].begin = 'anim' + (data.index - 1) + '.end';
                }

                // We need to set an initial value before the animation starts as we are not in guided mode which would do that for us

                data.element.attr({
                    'stroke-dashoffset': -pathLength + 'px',
                    'stroke': data.meta.color
                });

                // We can't use guided mode as the animations need to rely on setting begin manually
                // See http://gionkunz.github.io/chartist-js/api-documentation.html#chartistsvg-function-animate
                data.element.animate(animationDefinition, false);
            }
        });
    }

    return {
        //== Init demos
        init: function() {
            supportTickets2();
        }
    };
}();

//== Class initialization on page load
jQuery(document).ready(function() {
    Dashboard.init();
});