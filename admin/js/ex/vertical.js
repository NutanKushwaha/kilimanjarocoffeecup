$(function () {

	var d1, d2, data, chartOptions;

	d1 = [
        [1325376000000, 1200], [1328054400000, 700], [1330560000000, 1000], [1333238400000, 600],
        [1335830400000, 350]
    ];
 
    d2 = [
        [1325376000000, 800], [1328054400000, 600], [1330560000000, 300], [1333238400000, 350],
        [1335830400000, 300]
    ];
 
    

    data = [{
    	label: ' Assigned Points',
    	data: d1
    }, {
    	label: ' Used Points',
    	data: d2
    }];

    chartOptions = {
        xaxis: {
            min: (new Date(2011, 11, 15)).getTime(),
            max: (new Date(2012, 04, 18)).getTime(),
            mode: "time",
            tickSize: [1, "month"],
            monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            tickLength: 0
        },
        grid: {
            hoverable: true,
            clickable: false,
            borderWidth: 0
        },
        bars: {
	    	show: true,
	    	barWidth: 12*24*60*60*300,
            fill: true,
            lineWidth: 1,
            order: true,
            lineWidth: 0,
            fillColor: { colors: [ { opacity: 1 }, { opacity: 1 } ] }
	    },
        
        tooltip: true,
        tooltipOpts: {
            content: '%s: %y'
        },
        colors: App.chartColors
    }


    var holder = $('#vertical-chart');

    if (holder.length) {
        $.plot(holder, data, chartOptions );
    }


});