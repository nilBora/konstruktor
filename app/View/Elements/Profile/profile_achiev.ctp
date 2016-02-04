<?
	$title = Hash::get($achiev, 'title');
	$url = Hash::get($achiev, 'url');
	$class = ($hide) ? 'can-hide' : '';
	$style = ($hide) ? 'style="display: none"' : '';
		
	if($title && $url && $url!='http://') {
?>
<a href="<?=$url?>" target="_blank" class="underlink <?=$class?>" <?=$style?>><?=$title?></a>
<?
	} else {
?>
<span><?=$title?></span>
<?
	}
?>
