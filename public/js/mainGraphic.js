function getData(url) {
    $.ajax({
        type : 'get',
        url : url,
        success : function (data) {
            loadGraphic(data);
        },
        error : function () {

        }
    });
}

function loadGraphic(data) {
    $("#spaceGraphic").highcharts({
        chart: {
            zoomType: 'xy'
        },
        title: {
            text: ''
        },
        xAxis: [{
            categories: data.categories
        }],
        yAxis: [{ // Primary yAxis
            labels: {
                format: '{value} Bsf',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
        },
        title: {
            text: 'Ganancias',
                style: {

                color: Highcharts.getOptions().colors[1]
            }
        }
        }],
        tooltip: {
            shared: true
        },
        legend: {
            layout: 'vertical',
                align: 'left',
                x: 100,
                verticalAlign: 'top',
                y: 120,
                floating: true,
                backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
        },
        series: [{
            name: 'Ganancias',
            type: 'column',
            data: data.data,
            tooltip: {
                valueSuffix: ''
            }
        }],
            credits: {
            enabled: false
        }
        });
}