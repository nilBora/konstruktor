<?php
	/* Breadcrumbs */
	$this->Html->addCrumb(Hash::get($currUser, 'User.full_name'), array('controller' => 'User', 'action' => 'view/'.Hash::get($currUser, 'User.id')));
	$this->Html->addCrumb(__('Statistic'), array('controller' => 'Statistic', 'action' => ''));

$dateFormat = (Hash::get($currUser, 'User.lang') == 'rus') ? 'dd.mm.yyyy' : 'mm/dd/yyyy';
if(Configure::read('Config.language') == 'rus'){
    $lang = 'ru';
}else{
    $lang = 'en';
}
?>

<?
$this->Html->script(array(
    'https://www.google.com/jsapi',
    'vendor/bootstrap-datetimepicker.min',
    'vendor/bootstrap-datetimepicker.ru.js',
), array('inline' => false));
?>

<div class="statisticPage">
    <div class="btn-group" id="statistic-period">
        <button type="button" class="btn btn-default" data-value="today"><?= __('Today') ?></button>
        <button type="button" class="btn btn-default active" data-value="week"><?= __('Week') ?></button>
        <button type="button" class="btn btn-default" data-value="month"><?= __('Month') ?></button>
        <button type="button" class="btn btn-default" data-value="year"><?= __('Year') ?></button>
    </div>
    <div class="rightButtons">
        <!--<b><?= __('Select period') ?>&emsp;</b>-->
        <div class="calandarPeriod">
            <div class="dateTime date" id="statistic-from">
                <span class="add-on"><i class="icon-th glyphicons calendar"></i></span>
                <input type="text" class="form-control" placeholder="<?= __('From') ?>" readonly="readonly">
                <input type="hidden" id="statistic-from-mirror" name="from">
            </div>
            <div class="dateTime date" id="statistic-to" style="margin-right: 75px;">
                <span class="add-on"><i class="icon-th glyphicons calendar"></i></span>
                <input type="text" class="form-control" placeholder="<?= __('To') ?>" readonly="readonly">
                <input type="hidden" id="statistic-to-mirror" name="to">
            </div>
        </div>
    </div>
</div>

<!-- Charts -->
<h3><?=__('My profile')?></h3>
<div id="statistic-profile-chart" style="height: 350px; margin-top: 34px"></div>
<h3><?=__('My groups')?></h3>
<div id="statistic-groups-chart" style="height: 350px; margin-top: 34px"></div>
<h3><?=__('My articles')?></h3>
<div id="statistic-articles-chart" style="margin-top: 34px"></div>
<!--/ Charts -->

<!-- Templates -->
<script type="text/x-tmpl" id="tmpl-statistic-articles-list">
<table class="table table-striped table-hover" style="width:100%">
    <tr>
        <th><?=__('Title')?></th>
        <th><?=__('Visitors')?></th>
    </tr>
{% for (var i=1; i<o.length; i++) { %}
    <tr>
        <td style="word-wrap: break-word; max-width:200px">{%=o[i][0]%}</td>
        <td style="width:20px" class="text-center">{%=o[i][1]%}</td>
    </tr>
{% } %}
</table>
</script>
<!--/ Templates -->

<script type="application/javascript">
google.load("visualization", "1", {packages:["corechart"]});
var options = {
    curveType: "function",
    title: '',
    hAxis: {
        title: '',
        format: 'long',
        titleTextStyle: {
            color: '#333'
        },
        textStyle: {
            fontSize: '10',
        },
    },
    vAxis: {
        title: '',
        titleTextStyle: {
            color: '#333',
        },
        gridlines: {
            color: '#eeefff'
        },
        format: '0',
        minValue: 0,
    },
    colors:['#A7E2E0'],
    series: {
        0: {areaOpacity: 0.1},
        1: {areaOpacity: 0.8}
    },
    lineWidth: 0,
    legend: {'position': 'none'},
    chartArea: {width: '88%', height: '85%'}
};
var renderSVG = function ($element) {
    $element.find('svg').find('g:first')
        .append('<rect x="'+
        $element.find('svg').find('g:first').find('rect:first').attr('x')
        +'" y="0" width="1" height="'+
        (
        parseInt($element.find('svg').find('g:first').find('rect:first').attr('y'),10)
        +
        parseInt($element.find('svg').find('g:first').find('rect:first').attr('height'),10)
        ) +
        '" stroke="none" stroke-width="0" fill="#000000"/>');
    $element.html($element.html());
};
var countHorizontalLines = function (maxValue) {
    console.log('max value: '+maxValue);
    var result = maxValue;

    if(result > 12) {
        var divider = 4;
        do {
            divider++;
            console.log('divide result: '+(result % divider));
        } while (result % divider != 0 && divider <= 13);
        console.log('total divider: '+divider);
        if(result % divider != 0) {
            result = divider;
        } else {
            do {
                result = Math.floor((result+1)/2);
            } while (result > 12);
        }
    } else {
        result++; //нулевая вертикаль
    }
    return result;
};
var statisticRender = function() {
    var params = {
        period: $('#statistic-period button.active').data('value'),
        from: $('#statistic-from-mirror').val(),
        to: $('#statistic-to-mirror').val()
    };
    $.post('/statistic/data.json', params, function (response) {
        // profile
        if (response == undefined || response.data.profile.length <= 1) {
            $('#statistic-profile-chart').html('<?= __('Data is empty') ?>');
        } else {
            var rdata = response.data.profile;
            var dataProfile = new google.visualization.DataTable();
            dataProfile.addColumn('string', 'Date');
            dataProfile.addColumn('number', 'Visitors');
            var isEmpty = true;
            var maxValue = 0;
            for (var i = 1; i < rdata.length; i++) {
                var tooltip = '<?= __('Date')?>: ' + rdata[i][0] + "\n <?= __('Visitors')?>: ";
                var value = rdata[i][1];
                tooltip += ' ' + value;
                if (value) {
                    isEmpty = false;
                }
                dataProfile.addRow([rdata[i][0], value]);
                if (value > maxValue) {
                    maxValue = value;
                    console.log(value);
                }
            }
            options.vAxis.gridlines.count = countHorizontalLines(maxValue);
            if (isEmpty) {
                $('#statistic-profile-chart').html('<?= __('Data is empty') ?>');
            } else {
                var chartProfile = new google.visualization.ColumnChart(document.getElementById('statistic-profile-chart'));
                chartProfile.draw(dataProfile, options);
                renderSVG($('#statistic-profile-chart'));
            }
        }

        // groups
        if (response == undefined || response.data.groups.length <= 1) {
            $('#statistic-groups-chart').html('<?= __('Data is empty') ?>');
            return;
        } else {
            var rdata = response.data.groups;
            var dataGroups = new google.visualization.DataTable();
            dataGroups.addColumn('string', 'Name');
            dataGroups.addColumn('number', 'Visitors');
            var isEmpty = true;
            var maxValue = 0;
            for (var i = 1; i < rdata.length; i++) {
                var tooltip = '<?= __('Title')?>: ' + rdata[i][0] + "\n <?= __('Visitors')?>: ";
                var value = parseInt(rdata[i][1], 10);
                tooltip += ' ' + value;
                if (value) {
                    isEmpty = false;
                }
                dataGroups.addRow([rdata[i][0], value]);
                if (value > maxValue) {
                    maxValue = value;
                }
            }
            options.vAxis.gridlines.count = countHorizontalLines(maxValue);
            if (isEmpty) {
                $('#statistic-groups-chart').html('<?= __('Data is empty') ?>');
            } else {
                var chartGroups = new google.visualization.ColumnChart(document.getElementById('statistic-groups-chart'));
                chartGroups.draw(dataGroups, options);
                renderSVG($('#statistic-groups-chart'));
            }
        }

        // articles
        if (response == undefined || response.data.articles.length <= 1) {
            $('#statistic-articles-chart').html('<?= __('Data is empty') ?>');
            return;
        } else {
            $('#statistic-articles-chart').html(tmpl('tmpl-statistic-articles-list', response.data.articles));
        }
    });
};
google.setOnLoadCallback(function () {
    statisticRender();
});
var statisticNoRender = false;
$('#statistic-period button').on('click', function () {
    statisticNoRender = true;
    $('#statistic-period button').removeClass('active');
    $('#statistic-from').datetimepicker("reset");
    $('#statistic-to').datetimepicker("reset");
    $(this).addClass('active');
    statisticRender();
    statisticNoRender = false;
});
$('#statistic-from, #statistic-to').on('change', function () {
    if (statisticNoRender){
        return;
    }
    $('#statistic-period button').removeClass('active');
    statisticRender();
});

$(window).resize(function () {
    statisticRender();
});

// Calendars
$('#statistic-from').datetimepicker({
    format: '<?= $dateFormat?>',
    weekStart: 1,
    autoclose: 1,
    todayHighlight: 1,
    startView: 2,
    minView: 2,
    language:"<?=$lang?>",
    linkField: 'statistic-from-mirror',
    linkFormat: 'yyyy-mm-dd'
});
$('#statistic-to').datetimepicker({
    format: '<?= $dateFormat?>',
    weekStart: 1,
    autoclose: 1,
    todayHighlight: 1,
    startView: 2,
    minView: 2,
    language:"<?=$lang?>",
    linkField: 'statistic-to-mirror',
    linkFormat: 'yyyy-mm-dd'
});
</script>
