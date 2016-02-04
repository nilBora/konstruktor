<?
	$title = Hash::get($achiev, 'title');
	$class = ($hide) ? 'can-hide' : '';
	$style = ($hide) ? 'style="display: none"' : ''
?>
<a href="<?=Hash::get($achiev, 'url')?>" target="_blank" class="achivement-block fs15 <?=$class?>" <?=$style?>>
    <?=Hash::get($achiev, 'title')?>
</a>
