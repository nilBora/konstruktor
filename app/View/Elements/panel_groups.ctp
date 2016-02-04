<div class="create-group">
    <div class="page-menu">
        <a href="<?=$this->Html->url(array('controller' => 'Group', 'action' => 'edit'))?>" class="btn btn-default"><?=__('Create group')?></a>
		<!--?=$this->Html->link(__('My sales'), array('controller' => 'User', 'action' => 'mySells'),
			array('class' => 'pull-right underlink', 'style' => "margin-top: 5px; margin-right: -17px;"))?-->
    </div>
</div>
<div class="dropdown-panel-scroll">
	<?=$this->element('group_list')?>
</div>

<?
    if(count($aInvites)) {
?>
<script>
    $('#groupInviteCount').html(<?=count($aInvites)?>);
</script>
<?
    }
?>