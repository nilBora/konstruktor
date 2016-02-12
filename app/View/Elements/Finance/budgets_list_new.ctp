<?
$dateFormat = (Hash::get($currUser, 'User.lang') == 'rus') ? 'dd.mm.yyyy' : 'mm/dd/yyyy';
if(Configure::read('Config.language') == 'rus'){
    $lang = 'ru';
}else{
    $lang = 'en';
}
$budgets = $aFinanceBudget;
$monthNames = array(
    1 => __(date('F', strtotime($month1))),
    2 => __(date('F', strtotime($month2))),
    3 => __(date('F', strtotime($month3))),
    4 => __(date('F', strtotime($month4))),
);
?>
<div class="budget">
    <div class="item head">
	<span class="category">
        <form id="finance-budget-filter">
            <? /* if (!empty($accountId)) {?>
            <a class="btn btn-default"  data-toggle="modal" data-target="#finance-modal-save-budget" href="<?= $this->Html->url(array('controller' => 'FinanceBudget', 'action' => 'addBudget', $id, !empty($accountId) ? $accountId : 0)) ?>">
                <?= __('New budget') ?>
            </a>
        <? } */ ?>

            <? if (!empty($aProjectOptions)) {?>
                <select data-placeholder="<?= __('All projects') ?>" name="groupProjectId" id="finance-budget-filter-project">
                    <option value="none"><?=__('All projects')?></option>
                    <? foreach ($aProjectOptions as $pid => $project) { ?>
                        <?
                        $selectedProject = '';
                        if (isset($groupProjectId) && $groupProjectId == $pid) {
                            $selectedProject = 'selected="true"';
                        }
                        ?>
                        <option <?= $selectedProject ?>
                            value="<?=$pid?>"><?=$project?></option>
                    <? } ?>
                </select>
            <? } ?>
            <input type="hidden" name="fromMonth" value="<?= $fromMonth ?>">
            <?  if (!isset($groupProjectId)) { ?>
                <select data-placeholder="<?= __('All accounts') ?>" name="accountId" id="finance-budget-filter-account">
                    <? if (empty($accountId)) {?>
                        <option><?=__('No accounts')?></option>
                    <? } ?>
                    <? foreach ($aFinanceAccount as $account) { ?>
                        <?
                        $selectedAccount = '';
                        if (isset($accountId) && $accountId == $account['FinanceAccount']['id']) {
                            $selectedAccount = 'selected="true"';
                        }
                        ?>
                        <option <?= $selectedAccount ?>
                            value="<?= $account['FinanceAccount']['id'] ?>"><?= $account['FinanceAccount']['name'] ?></option>
                    <? } ?>
                </select>
            <? } ?>
        </form>
	</span>
        <span class="value"></span>
        <span class="value"></span>
        <span class="value" style="position: relative; left: -60px; top: 90px;"><?= $monthNames[1] ?>, <?= date('Y', strtotime($month1)) ?></span>
        <span class="value" style="position: relative; left: -60px; top: 90px;"><?= $monthNames[2] ?>, <?= date('Y', strtotime($month2)) ?></span>
        <div class="rightButtons" style="padding-top: 10px;">
            <div class="dateTime date" id="finance-report-filter-fromMonth">
                <span class="add-on"><i class="icon-th glyphicons calendar"></i></span>
                <input type="text" class="form-control" placeholder="<?= __('Begin from') ?>" readonly="readonly">
                <input type="hidden" id="finance-report-filter-fromMonth-mirror" name="fromMonth"
                    <? if (@$this->request->query['month1']) { ?> value="<?= $this->request->query['month1']?>"<? } ?>>
            </div>
        </div>
<!--        <span class="value">--><?//= $monthNames[3] ?><!--, --><?//= date('Y', strtotime($month3)) ?><!--</span>-->
<!--        <span class="value">--><?//= $monthNames[4] ?><!--, --><?//= date('Y', strtotime($month4)) ?><!--</span>-->
    </div>

    <?
    $chartPlanExpense = $chartPlanIncome = $chartExpense = $chartIncome = array(
        $monthNames[1] => 0,
        $monthNames[2] => 0,
        $monthNames[3] => 0,
        $monthNames[4] => 0,
    );
    if (!empty($categories)) { ?>

<!--        <div class="title">--><?//= __('Expenses projected/actual') ?><!--</div>-->
        <?

        $totalExpense = 0;
        $expense1 = 0;
        $expense2 = 0;
        $expense3 = 0;
        $expense4 = 0;
        $expensePlan = 0;
        foreach ($categories as $i => $item) {
            /*
            if ($item['category']['type'] != 1 || !isset($budgets[$item['category']['id']])) {
                continue;
            }
            */
            //$budget = $budgets[$item['category']['id']]['FinanceBudget'];
            $sum_amount_1 = $item[0]['sum_amount_1'];
            $sum_amount_2 = $item[0]['sum_amount_2'];
            $sum_amount_3 = $item[0]['sum_amount_3'];
            $sum_amount_4 = $item[0]['sum_amount_4'];
            $sum_amount_1 *= -1;
            $sum_amount_2 *= -1;
            $sum_amount_3 *= -1;
            $sum_amount_4 *= -1;
            //$plan = $budget['plan'];
            $plan = isset($budgets[$item['category']['id']]['FinanceBudget']) ? $budgets[$item['category']['id']]['FinanceBudget']['plan'] : 0;
            $categoryName = $item['category']['name'];
            // chart
            $chartPlanExpense[$monthNames[1]] += $plan;
            $chartPlanExpense[$monthNames[2]] += $plan;
            $chartPlanExpense[$monthNames[3]] += $plan;
            $chartPlanExpense[$monthNames[4]] += $plan;
            ?>
            <?
            $expense1 += $sum_amount_1;
            $expense2 += $sum_amount_2;
            $expense3 += $sum_amount_3;
            $expense4 += $sum_amount_4;
            $expensePlan += $plan;
        }
        $totalExpense = $expense1 + $expense2 + $expense3 + $expense4;
        ?>

        <?php
//        echo "<pre>";
//        print_r($finOperationFull);
        $sum_amount_1 = $sum_amount_2 = 0;
        $sum_expence1 = $sum_expence2 = $sum_income1 = $sum_income2 = 0;
        $sum_amounts = $sum_amounts2 = array();
        if(isset($projectsFull) && !empty($projectsFull)):?>
            <div class="title"><?= __('Expenses projected/actual') ?></div>
            <?php
            foreach($projectsFull as $key=>$one):?>
                <div class="item">
                    <span class="category"><?=$one['Project']['title'];?></span>
                    <span class="value"></span>
                    <span class="value"></span>
                    <span class="value"></span>
                    <span class="value"></span>
                </div>

                <?php foreach($subprojectsFull as $k2=>$v2):?>

                    <?php if($v2['Subproject']['project_id'] == $one['Project']['id']):?>
                        <div class="item">
                            <span class="value"></span>
                            <span class="value"><?=$v2['Subproject']['title'];?></span>
                            <span class="value"></span>
                            <span class="value"></span>
                            <span class="value"></span>
                        </div>
                        <?php foreach($taskFull as $k3=>$v3):?>
                            <?php ///$sum_amounts[$v3['Task']['id']] = 0;?>
                            <?php  if($v3['Task']['subproject_id'] == $v2['Subproject']['id']):?>



                                <div class="item">
                                    <span class="value"></span>
                                    <span class="value"></span>
                                    <span class="value"><?=$v3['Task']['title']?></span>
                                    <span class="value">
                                        <?php $plan = (isset($crmTaskFull[$v3['Task']['id']]['CrmTask']['money']) ? $crmTaskFull[$v3['Task']['id']]['CrmTask']['money'] : 0);?>
                                        <span class="plan"><?=number_format($plan);?></span>
                                        <span class="slash">/</span>
                                        <?php $expence1 = isset( $v3['Task']['fullExpense_m1']) ?  $v3['Task']['fullExpense_m1'] : 0; ?>
                                        <?php $sum_expence1+=$expence1; ?>
                                        <span><?=$expence1;?></span>
                                        <div class="negative">

<!--                                            --><?// if (($delta = $plan - $sum_amount_1) < 0) { ?>
<!--                                                --><?//= //$delta ?>
<!--                                            --><?// } ?>
                                        </div>
                                    </span>
                                    <span class="value">
                                        <?php $plan = (isset($crmTaskFull[$v3['Task']['id']]['CrmTask']['money']) ? $crmTaskFull[$v3['Task']['id']]['CrmTask']['money'] : 0);?>
                                        <span class="plan"><?=number_format($plan);?></span>
                                        <span class="slash">/</span>
                                        <?php $expence2 = isset( $v3['Task']['fullExpense_m2']) ?  $v3['Task']['fullExpense_m2'] : 0; ?>
                                        <?php $sum_expence2+=$expence2; ?>
                                        <span><?=$expence2; ?></span>
                                        <div class="negative">
<!--                                            --><?// if (($delta = $plan - $sum_amount_1) < 0) { ?>
<!--                                                --><?//= $delta ?>
<!--                                            --><?// } ?>
                                        </div>
                                    </span>
                                </div>

                            <?php endif;?>
                        <?php endforeach;?>

                    <?php endif;?>
                <?php endforeach;?>

                <?php endforeach; ?>



            <div class="title"><?= __('Net Income projected/actual') ?></div>
            <?php
            $sum_amount_1 = $sum_amount_2 = 0;
            foreach($projectsFull as $key=>$one):?>
                <div class="item">
                    <span class="category"><?=$one['Project']['title'];?></span>
                    <span class="value"></span>
                    <span class="value"></span>
                    <span class="value"></span>
                    <span class="value"></span>
                </div>

                <?php foreach($subprojectsFull as $k2=>$v2):?>

                    <?php if($v2['Subproject']['project_id'] == $one['Project']['id']):?>
                        <div class="item">
                            <span class="value"></span>
                            <span class="value"><?=$v2['Subproject']['title'];?></span>
                            <span class="value"></span>
                            <span class="value"></span>
                            <span class="value"></span>
                        </div>
                        <?php foreach($taskFull as $k3=>$v3):?>
                            <?php  if($v3['Task']['subproject_id'] == $v2['Subproject']['id']):?>

                                <div class="item">
                                    <span class="value"></span>
                                    <span class="value"></span>
                                    <span class="value"><?=$v3['Task']['title']?></span>
                                        <span class="value">
                                            <?php $plan = (isset($crmTaskFull[$v3['Task']['id']]['CrmTask']['money']) ? $crmTaskFull[$v3['Task']['id']]['CrmTask']['money'] : 0);?>
                                            <span class="plan"><?=number_format($plan);?></span>
                                            <span class="slash">/</span>
                                            <?php $income1 = isset( $v3['Task']['fullIncome_m1']) ?  $v3['Task']['fullIncome_m1'] : 0; ?>
                                            <?php $sum_income1+=$income1; ?>
                                            <span><?=$income1; ?></span>
                                            <div class="negative">
<!--                                                --><?// if (($delta = $plan - $sum_amount_1) < 0) { ?>
<!--                                                    --><?//= //$delta ?>
<!--                                                --><?// } ?>
                                            </div>
                                        </span>
                                        <span class="value">
                                            <?php $plan = (isset($crmTaskFull[$v3['Task']['id']]['CrmTask']['money']) ? $crmTaskFull[$v3['Task']['id']]['CrmTask']['money'] : 0);?>
                                            <span class="plan"><?=number_format($plan);?></span>
                                            <span class="slash">/</span>
                                            <?php $income2 = isset( $v3['Task']['fullIncome_m2']) ?  $v3['Task']['fullIncome_m2'] : 0; ?>
                                            <?php $sum_income2+=$income2; ?>
                                            <span><?=$income2; ?></span>
                                            <div class="negative">
<!--                                                --><?// if (($delta = $plan - $sum_amount_1) < 0) { ?>
<!--                                                    --><?//= $delta ?>
<!--                                                --><?// } ?>
                                            </div>
                                        </span>
                                </div>

                            <?php endif;?>
                        <?php endforeach;?>

                    <?php endif;?>
                <?php endforeach;?>

            <?php endforeach; ?>
        <?php endif;?>
        <?
        $totalIncome = 0;
        $income1 = 0;
        $income2 = 0;
        $income3 = 0;
        $income4 = 0;
        $incomePlan = 0;
        foreach ($categories as $item) {
            if ($item['category']['type'] != 0 || !isset($budgets[$item['category']['id']])) {
                continue;
            }
            $budget = $budgets[$item['category']['id']]['FinanceBudget'];
            $sum_amount_1 = $item[0]['sum_amount_1'];
            $sum_amount_2 = $item[0]['sum_amount_2'];
            $sum_amount_3 = $item[0]['sum_amount_3'];
            $sum_amount_4 = $item[0]['sum_amount_4'];
            $plan = $budget['plan'];
            // chart
            $chartPlanIncome[$monthNames[1]] += $plan;
            $chartPlanIncome[$monthNames[2]] += $plan;
            $chartPlanIncome[$monthNames[3]] += $plan;
            $chartPlanIncome[$monthNames[4]] += $plan;
            ?>
            <?
            $income1 += $sum_amount_1;
            $income2 += $sum_amount_2;
            $income3 += $sum_amount_3;
            $income4 += $sum_amount_4;
            $incomePlan += $plan;
        }
        $balance1 = $income1 - $expense1;
        $balance2 = $income2 - $expense2;
        $balance3 = $income3 - $expense3;
        $balance4 = $income4 - $expense4;
        $planBalance = $incomePlan - $expensePlan;
        // chart
        $chartIncome[$monthNames[1]] = $income1;
        $chartIncome[$monthNames[2]] = $income2;
        $chartIncome[$monthNames[3]] = $income3;
        $chartIncome[$monthNames[4]] = $income4;

        $chartExpense[$monthNames[1]] = $expense1;
        $chartExpense[$monthNames[2]] = $expense2;
        $chartExpense[$monthNames[3]] = $expense3;
        $chartExpense[$monthNames[4]] = $expense4;
        ?>

        <div class="item total">
            <span class="text"><?= __('Balance')?></span>
            <span class="value">
                <span class="plan"></span>
                <span class="slash"></span>
                <span></span>
            </span>
            <span class="value">
                <span class="plan"></span>
                <span class="slash"></span>
                <span></span>
            </span>
            <span class="value" style="position: relative; left: -60px;">
                <span class="plan"><?=$sum_expence1; ?></span>
                <span class="slash">/</span>
                <span><?= $sum_income1 ?></span>
            </span>
            <span class="value" style="position: relative; left: -60px;">
                <span class="plan"><?= $sum_expence2 ?></span>
                <span class="slash">/</span>
                <span><?= $sum_income2 ?></span>
            </span>
        </div>
        <?
    }
    ?>
    <?php if(!empty($transact['month1']) || !empty($transact['month2'])):?>
    <div class="item total">
        <span class="text"><?php echo __d('billing', 'Transactions') ?></span>
        <span class="value">
            <span class="plan"></span>
            <span class="slash"></span>
            <span></span>
        </span>
        <span class="value">
            <span class="plan"></span>
            <span class="slash"></span>
            <span></span>
        </span>
        <span class="value" style="position: relative; left: -60px;">
            <span class="plan"><?=$transact['month1']; ?></span>
            <span class="slash"></span>
            <span></span>
        </span>
        <span class="value" style="position: relative; left: -60px;">
            <span class="plan"><?= $transact['month2'] ?></span>
            <span class="slash"></span>
            <span></span>
        </span>
    </div>
    <?php endif;?>
    <?php if(isset($investProject) && is_array($investProject)):?>
        <div class="item total">
            <span class="text"><?=__('Investments')?></span>
            <span class="value">
                <span class="plan"></span>
                <span class="slash"></span>
                <span></span>
            </span>
            <span class="value">
                <span class="plan"></span>
                <span class="slash"></span>
                <span></span>
            </span>
            <span class="value" style="position: relative; left: -60px;">
                <span class="plan"><?=$investProject['InvestProject']['funded_total']; ?></span>
                <span class="slash">/</span>
                <span><? echo number_format(($investProject['InvestProject']['total'] - $investProject['InvestProject']['funded_total']), 2, '.','');?></span>
            </span>
            <span class="value" style="position: relative; left: -60px;">
                <span class="plan"></span>
                <span class="slash"></span>
                <span></span>
            </span>
        </div>
    <?php endif;?>
</div>

<div class="budgetGraphic">
    <div class="title"><?= __('Revenue and Expenses Chart') ?></div>
    <div class="btn-group" id="finance-chart-budget-type">
        <button type="button" class="btn btn-default active" data-value="expense"><?= __('Expense') ?></button>
        <button type="button" class="btn btn-default" data-value="income"><?= __('Income') ?></button>
    </div>

    <div id="finance-budget-chart" style="height: 350px; margin-top: 34px"></div>
</div>

<script type="application/javascript">
    $(document).ready(function () {
// Init
        $('#finance-report-filter-fromMonth').datetimepicker({
            format: 'MM yyyy',
            weekStart: 1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 3,
            minView: 3,
            language:"<?= $lang ?>",
            linkField: 'finance-report-filter-fromMonth-mirror',
            linkFormat: 'yyyy-mm'
        });
        $('#finance-report-filter-fromMonth').datetimepicker('setDate', new Date("<?= $fromMonth ?>"));
// Events
        $('#finance-report-filter-fromMonth-mirror').on('change', function () {
            $('#finance-budget-filter').find('[name="fromMonth"]').val($(this).val());
            $('#finance-budget-filter').submit();
        });
    });
    // Charts
    var $chartPlanExpense = <?= json_encode($chartPlanExpense)?>;
    var $chartPlanIncome = <?= json_encode($chartPlanIncome)?>;
    var $chartExpense = <?= json_encode($chartExpense)?>;
    var $chartIncome = <?= json_encode($chartIncome)?>;

    var chartBudgetRender = function() {
        var budgetType = $('#finance-chart-budget-type button.active').data('value');
        var data = new google.visualization.DataTable();

        data.addColumn('string', 'Month'); // X-axis
        data.addColumn('number', '<?=__('fact')?>'); // Y-axis
        data.addColumn('number', '<?=__('plan')?>'); // Y-axis
        data.addColumn('number', '<?=__('diff')?>'); // Y-axis

        if (budgetType == 'expense') {
            var fact = $chartExpense;
            var plan = $chartPlanExpense;
        } else {
            var fact = $chartIncome;
            var plan = $chartPlanIncome;
        }

        var months = Object.keys(fact);
        for (var i = 0; i < months.length; i++) {
            var month = months[i];
            data.addRow([month.substr(0, 3), fact[month], plan[month], fact[month] - plan[month]]);
        }

        var options = {
            title: '',
            vAxis: {
                minValue: 0,
                gridlines: {color: 'transparent'}
            },
            pointSize: 11,
            colors:['#FF9396', '#22b3b0', '#9E6E6E'],
            series: {
                0: {areaOpacity: 1},
                1: {areaOpacity: 0.3},
                2: {areaOpacity: 1}
            },
            lineWidth: 0,
            'legend': {'position': 'top'},
            chartArea: {width: '90%', height: '85%'}
        };

        var chart = new google.visualization.AreaChart(document.getElementById('finance-budget-chart'));
        chart.draw(data, options);

        $(window).resize(function () {
            chart.draw(data, options);
        });
    };

    // Filter
    $('#finance-budget-filter [name="accountId"]').on('change', function () {
        $('#finance-budget-filter').submit();
    });

    $('#finance-budget-filter [name="groupProjectId"]').on('change', function () {
        $('#finance-budget-filter').submit();
    });

    // Load google charts
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(function () {
        chartBudgetRender();
    });

    // Events for chart re-rendering
    $('#finance-chart-budget-type button').on('click', function () {
        $('#finance-chart-budget-type button').removeClass('active');
        $(this).addClass('active');
        chartBudgetRender();
    });
</script>