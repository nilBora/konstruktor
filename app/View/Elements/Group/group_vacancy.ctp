<?
	$id = Hash::get($vacancy, 'GroupVacancy.id');
	$country = Hash::get($vacancy, 'GroupVacancy.country');
	$title = Hash::get($vacancy, 'GroupVacancy.title');
	$wage = $vacancy['GroupVacancy']['wage'] ? $vacancy['GroupVacancy']['wage'] : __('N/A');
	$city = $vacancy['GroupVacancy']['city'] ? $vacancy['GroupVacancy']['city'] : __('N/A');
	$employment = $vacancy['GroupVacancy']['employment'];
	$shedule = $vacancy['GroupVacancy']['shedule'];
	$experience = $vacancy['GroupVacancy']['experience'] ? $vacancy['GroupVacancy']['experience'] : __('N/A');

	$aEmployment = array(
		'4' => __('Full-time'),
		'3' => __('Part-time'),
		'2' => __('Project emplyment'),
		'1' => __('Volunteer'),
		'0' => __('Internship'),
	);
	$aShedules = array(
		'4' => __('Full day'),
		'3' => __('Shifts'),
		'2' => __('Flexible shifts'),
		'1' => __('Remote work'),
		'0' => __('Rotational basis'),
	);
?>

<div id="vacancy-<?=$id?>" class="item" data-id="<?=$id?>" data-country="<?=$country?>">
	<div class="title" data-val="<?=$title?>"><?=$title?></div>
	<div class="details">
		<div class="row">
			<div class="col-sm-6 wage">
				<?=__('Wages')?>
				<div class="value" data-val="<?=$vacancy['GroupVacancy']['wage']?>"><?=$wage.' '.$vacancy['GroupVacancy']['currency']?></div>
			</div>
			<div class="col-sm-6 city">
				<?=__('City')?>
				<div class="value" data-val="<?=$vacancy['GroupVacancy']['city']?>"><?=$city?></div>
			</div>
		</div>
		
		<div class="row more">
			<div class="col-sm-6 employment">
				<?=__('Employment')?>
				<div class="value" data-val="<?=$employment?>"><?=$aEmployment[$employment]?></div>
			</div>
			<div class="col-sm-6 shedule">
				<?=__('Working hours')?>
				<div class="value" data-val="<?=$shedule?>"><?=$aShedules[$shedule]?></div>
			</div>
		</div>
		
		<div class="row more">
			<div class="col-sm-4 col-offset-8 experience">
				<?=__('Experience')?>
				<div class="value" data-val="<?=$vacancy['GroupVacancy']['experience']?>"><?=$experience.' '.__('years')?></div>
			</div>
		</div>
	</div>
	<div class="description" data-val="<?=$vacancy['GroupVacancy']['descr']?>">
        <div class="row">
            <?=$vacancy['GroupVacancy']['descr']?>
        </div>
	</div>
	
	<div class="btns vacancy">
	<? if($isAdmin) { ?>
		<div class="btn btn-default smallBtn editBtn" onclick="editVacancy('<?=$id?>')"><span class="glyphicons pencil"></span></div>
		<a class="btn btn-default smallBtn removeBtn" href="<?=$this->Html->url(array('controller' => 'Group', 'action' => 'removeVacancy', $id))?>"><span class="glyphicons bin"></span></a>
	<? } else if(!$isAdmin && !$isMember && !isset($vacancy['GroupVacancy']['approve']) ) { ?>
		<? if( $currUserID ) { ?>
			<div class="btn btn-default responseBtn" onclick="vacResponse('<?=$id?>')">Откликнуться</div>
		<? } else { ?>
			<a href="#register-popup" class="btn btn-default responseBtn register-btn">Откликнуться</a>
		<? } ?>
	<? } ?>
	</div>
	<div class="clearfix"></div>
</div>