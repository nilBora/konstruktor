<?
	$s = sprintf('%09d', $id);
	echo substr($s, 0, 3).'-'.substr($s, 3, 3).'-'.substr($s, 6, 3);