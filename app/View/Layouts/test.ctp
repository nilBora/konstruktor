<!doctype html>
<html lang="ru">
<head></head>
<body>
<?=$this->element('ga')?>
<h2><?=$testDescription?></h2>
<p>Exec Time: <?=$exec_time?> seconds</p>
<p>Success: <?=$successCount?>/<?=$totalCount?> (<?php echo  round(($successCount/$totalCount),2)*100?> %)</p>
<table class="tests_table">
	<th>Test Name</th>
	<th>Expected</th>
	<th>Result</th>
	<th>Assert</th>
<?php foreach($tests as $test){?>
	<tr>
		<td style="width:32%"><?=$test['test']?></td>
		<td style="width:32%">
		<?
		 if(is_array($test['expected'])){
			 print_r($test['expected']);
		 }else{
			 echo $test['expected'];
		 }
		?>
		</td>
		<td style="width:32%"><?
		 if(is_array($test['result'])){
			 print_r($test['result']);
		 }else{
			 echo $test['result'];
		 }
		?></td>
		<td ><?=$test['assert']?></td>
	</tr>
<?php }?>
</table>
<?=$this->fetch('content')?>
<style>
	.tests_table{
		width: 80%;font-size: 11px;
	}
	.tests_table td{
		border:solid 1px black;
	}
</style>
</body>
</html>
