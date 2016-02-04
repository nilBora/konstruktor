<div class="row groupViewInfo fixedLayout">
	<div class="col-sm-8 col-md-9">
		<div class="thumb">
            <img src="<?= $this->Media->imageUrl($investProject['Avatar'], 'thumb200x200') ?>" alt="" class="blockLine" onerror="this.src='/img/no-photo.jpg'"/>
        </div>
		<h1><?= $investProject['InvestProject']['name'] ?></h1>
		<?php if( $investProject['InvestProject']['id'] == 8 ) { ?>
			<img class="funded" src='/img/funded.png' style="position: absolute; width: 75px; top: 0; left: 205px;">
		<?php } ?>
	</div>
	<div class="col-sm-4 col-md-3">
		<?= $this->element('Invest/project_nav') ?>
	</div>
</div>
