<?foreach ($aCurrencyBalances as $currency => $balance) {
	$style = 'style=""';
	if ($balance < 0) {
		$style = 'style="color: red"';
	}
	?>
	<div class="count" <?= $style ?>>
		<?= $this->Money->symbolFor($currency)?> <?= $this->Money->format($balance) ?>
	</div>
<? } ?>