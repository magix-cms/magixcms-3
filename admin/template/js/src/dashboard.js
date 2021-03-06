var dashboard = (function($, window, document, undefined){
    function setChart(content,type){
        var ctx = $("#chart");
        $.jmRequest({
            handler: "ajax",
            url: '/admin/template/js/json/'+content+'.json',
            method: 'GET',
            dataType: 'json',
            beforeSend:function(){
                var loader = $(document.createElement("div")).addClass("loader")
                    .append(
                        $(document.createElement("i")).addClass("fa fa-spinner fa-pulse fa-3x fa-fw"),
                        $(document.createElement("span")).append("Loading...").addClass("sr-only")
                    );
                $('#chart').before(loader);
            },
            success:function(json){
                $('.loader').remove();
                var labels = json.map(function(item) {
                    return item.month;
                });
                var jsonData = json.map(function(item) {
                    return item.mychart;
                });
                var data = {
                    labels: labels,
                    datasets: [
                        {
                            label: "",
                            fill: false,
                            lineTension: 0,
                            backgroundColor: "rgba(59,97,169,0.4)",
                            borderColor: "rgba(59,97,169,1)",
                            data: jsonData
                        },
                        {
                            label: "",
                            fill: false,
                            lineTension: 0,
                            backgroundColor: "rgba(61,168,63,0.4)",
                            borderColor: "rgba(61,168,63,1)",
                            data: []
                        },
                        {
                            label: "Panier moyen",
                            fill: false,
                            lineTension: 0,
                            backgroundColor: "rgba(214,67,67,0.4)",
                            borderColor: "rgba(214,67,67,1)",
                            data: []
                        },
                        {
                            label: "",
                            fill: false,
                            lineTension: 0,
                            backgroundColor: "rgba(80,191,216,0.4)",
                            borderColor: "rgba(80,191,216,1)",
                            data: []
                        },
                        {
                            label: "",
                            fill: false,
                            lineTension: 0,
                            backgroundColor: "rgba(229,119,46,0.4)",
                            borderColor: "rgba(229,119,46,1)",
                            data: []
                        },
                        {
                            label: "",
                            fill: false,
                            lineTension: 0,
                            backgroundColor: "rgba(131,61,168,0.4)",
                            borderColor: "rgba(131,61,168,1)",
                            data: []
                        }
                    ]
                };
                var chart = new Chart(ctx, {
                    type: type,
                    data: data,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        legend: {
                            display: false
                        },
                        tooltips: {
                            enabled: true,
                            mode: 'single',
                            callbacks: {
                                label: function(tooltipItem, data) {
                                    if (tooltipItem.datasetIndex == 2) {
                                        return tooltipItem.yLabel + ' €';
                                    } else {
                                        return tooltipItem.yLabel;
                                    }
                                }
                            }
                        }
                    }
                });

                $('.cchart').click(function () {
                    var index = $(this).data('dataset');
                    var name = $(this).data('content');
                    if(!$(this).hasClass('active')) {
                        $(this).addClass('active').parent('li').addClass('active');
                        $.jmRequest({
                            handler: "ajax",
                            url: '/admin/template/js/json/'+$(this).data('content')+'.json',
                            method: 'get',
                            dataType: 'json',
                            success:function(json){
                                var jsonData = json.map(function(item) {
                                    return item.mychart;
                                });

                                chart.data.datasets[index].data = jsonData;
                                //chart.data.datasets[index].label = name;
                                chart.update();
                            }
                        });
                    } else {
                        var active = $('.cchart.active');
                        if (active.length > 1) {
                            $(this).removeClass('active').parent('li').removeClass('active');
                            chart.data.datasets[index].data = [];
                            chart.data.datasets[index].label = "";
                            chart.update();
                        }
                    }
                });
            }
        });
    }
    return {
        //Fonction public
        run: function () {
            var currentChart = $('.cchart.current');
            setChart($(currentChart).data('content'),$(currentChart).data('chart'));
        }
    }
})(jQuery, window, document);