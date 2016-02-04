<?php if ($this->Paginator->hasPage(2)) { ?>
	<p style="text-align: center;">
	<?php
		echo $this->Paginator->counter(array(
			'format' => __('Page {:page} of {:pages}, {:count} total items')
		));
	?>
	</p>
	<div class="text-center">
	<?php
		if (isset($options)&&!empty($options)){
			echo $this->Paginator->options($options);
		}
		echo $this->Paginator->pagination(array(
			'class' => 'pagination alternate'
		));
	?>
	</div>
<?php } ?>