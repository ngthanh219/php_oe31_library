$(function () {
    var route = window.location.origin;

    $.ajax({
        url: route + '/admin/chart',
        type: 'GET',
        dataType: 'json',
        success: function (res) {
            var data = [];
            var months = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];

            var month = res.list.map(function (item) {
                return item.month;
            });

            var book = res.list.map(function (item) {
                return item.book;
            });

            var count = 0;
            for (let i = 0; i < months.length; i++) {
                if (month.includes(i + 1)) {
                    data.push([i + 1, book[count]]);
                    count++;
                } else {
                    data.push([i + 1, 0]);
                }
            }

            var bar_data = {
                data,
                color: 'green'
            }

            $.plot('#bar-chart', [bar_data], {
                label: "bar",
                grid: {
                    borderWidth: 1,
                    borderColor: '#f3f3f3',
                    tickColor: '#f3f3f3'
                },
                series: {
                    bars: {
                        line: 'text',
                        show: true,
                        barWidth: 0.2,
                        align: 'center'
                    },
                },
                xaxis: {
                    mode: "months",
                    tickLength: 0,
                    ticks: [
                        [1, 'January'],
                        [2, 'February'],
                        [3, 'March'],
                        [4, 'April'],
                        [5, 'May'],
                        [6, 'June'],
                        [7, 'July'],
                        [8, 'August'],
                        [9, 'September'],
                        [10, 'October'],
                        [11, 'November'],
                        [12, 'December'],
                    ]
                },
                yaxis: {
                    mode: "books",
                    tickLength: 0,
                },
            })
        },
        error: function (XHR, status, error) {

        },
        complete: function (res) {

        }
    });
})
