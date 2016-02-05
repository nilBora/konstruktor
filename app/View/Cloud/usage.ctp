<?php
    $this->Html->addCrumb(__('Cloud'), array('controller' => 'Cloud', 'action' => 'index'));
    $this->Html->addCrumb(__('File Usage via Pie Chart'), array('controller' => 'Cloud', 'action' => 'usage'));
?>
<div>
    <div id="chart-container" class="col-xs-8"></div>
    <div id="chart-description" class="col-xs-4" style="font-weight: bold;color: red;">
        <div style="margin-top: 130px;">
            <span style="margin-left: 50px;">
                <?php echo $this->Html->link(__("Buy More Space", true), array('controller' => "StorageLimit", 'action' => "buyMoreSpace"), array('class' => 'btn btn-default textIconBtn'));?>
            </span>
        </div>
    </div>
</div>

<script>
    var data = <?php echo $data; ?>;

    for (var i = data.length - 1; i >= 0; i--) {
        if(data[i].name == "Projects"){
            data[i].name = "<?php echo __('Projects'); ?>";
        }
        else if(data[i].name == "Cloud"){
            data[i].name = "<?php echo __('Cloud'); ?>";
        }
        else if(data[i].name == "Messages"){
            data[i].name = "<?php echo __('Messages'); ?>";
        }
    };

    Highcharts.setOptions({
        colors: ['#ffa726', '#9a0199', '#02c090']
    });
//    var series =
    $(function () {
        $('#chart-container').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: '<?php echo __('File Usage via Pie Chart'); ?>'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        useHTML: true,
                        formatter: function () {
                            return '<span style="color:' + this.point.color + '">' + this.point.name + '</span>';
                        }
                    },
                    point: {
                        events: {
                            legendItemClick: function () {
                                return false;
                            }
                        }
                    },
                    showInLegend: true
                }
            },

            legend: {
                enabled: true,
                align: 'right',
                verticalAlign: 'middle',
                layout: 'vertical',
                itemMarginTop: 5,
                itemMarginBottom: 5,
                useHTML: true,
                labelFormatter: function () {
                    if(this.name == 'Usage') {
                        return '<span class="highcharts-legend-item-usage">' + this.name + ': ' + this.size + '</span>';
                    }
                    else
                        return this.name + ': ' + this.size;
                },
            },
            series: [{
                name: "<?php echo __('File Usage'); ?>",
                colorByPoint: true,
                data: data
            }],
            credits: {
                enabled: false
            },
            exporting: {
                enabled: false
            }
        });
    });
</script>
