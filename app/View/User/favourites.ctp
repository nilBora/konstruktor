<?php
	/* Breadcrumbs */
	$this->Html->addCrumb(Hash::get($currUser, 'User.full_name'), array('controller' => 'User', 'action' => 'view/'.Hash::get($currUser, 'User.id')));
	$this->Html->addCrumb(__('Favorite users'), array('controller' => 'User', 'action' => 'favourites'));

    //Debugger::dump($aFavLists);
    //Debugger::dump($aFavUsers);
    //Debugger::dump($aUsers);

        foreach($aFavUsers as $favListID => $favList) {
            if(!($favListID == '0')) {
?>
    <h3 class="userList"><?=$aFavLists[$favListID]['FavouriteList']['title']?>
        <a class="btn btn-default smallBtn editList" href="javascript:void(0)" onclick="editFavList(this, '<?=$favListID?>', '<?=$aFavLists[$favListID]['FavouriteList']['title']?>')">
            <span class="glyphicons pencil"></span>
        </a>
        <a class="btn btn-default smallBtn editList" href="<?=$this->Html->url(array('controller' => 'FavouriteList', 'action' => 'delete', $favListID))?>">
            <span class="glyphicons bin"></span>
        </a>
        <a class="btn btn-default newTask" onclick="moveMember(this, <?=$favListID?>)">
            <?=__('Add user')?>
        </a>
    </h3>
<?
        } else {
?>
    <br /><br /><br /><br />
<?
        }
?>
    <div class="groupCommand clearfix">
<?
    if(!$favList) {
?>
        <div><?=__('No users in this list')?></div>
<?
    } else {
        foreach($favList as $userID) {
            $user = $aUsers[$userID];
?>
        <div class="item">
            <a class="remove" href="<?=$this->html->url(array('controller' => 'FavouriteUser', 'action' => 'deleteByUserId', $user['User']['id']))?>">
                <span class="glyphicons circle_remove"></span>
            </a>
            <a href="<?=$this->html->url(array('controller' => 'User', 'action' => 'view', $user['User']['id']))?>">
                <?php echo $this->Avatar->user($user, array(
                    'style' => 'width: 100px',
                    'size' => 'thumb200x200'
                )); ?>
            </a>
            <a href="<?=$this->html->url(array('controller' => 'User', 'action' => 'view', $user['User']['id']))?>" class="name">
                <?=$user['User']['full_name']?>
            </a>
        </div>

<?
        }
    }
?>
    </div>
<?
    }
?>

<div id="FavListPopup" class="modal fade" tabindex="-1" role="dialog">
    <div class="outer-modal-dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <span class="glyphicons circle_remove" data-dismiss="modal"></span>
                <?=$this->Form->create('FavouriteList', array('url' => array('controller' => 'FavouriteList', 'action' => 'edit')))?>

                    <?=$this->Form->hidden('FavouriteList.id')?>
                    <?=$this->Form->hidden('FavouriteList.user_id', array('value' => $currUserID))?>

                    <div class="form-group">
                        <label><?=__('List title')?></label>
                        <?=$this->Form->input('FavouriteList.title', array('label' => false, 'class' => 'form-control', 'placeholder' => __('Title').'...', 'required' => 'required', 'maxlength' => '36'))?>
                    </div>

                    <div class="clearfix">
                        <button type="submit" class="btn btn-primary"></button>
                    </div>

                <?=$this->Form->end()?>
            </div>
        </div>
    </div>
</div>

<div id="UserMovePopup" class="modal fade" tabindex="-1" role="dialog">
    <div class="outer-modal-dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <span class="glyphicons circle_remove" data-dismiss="modal"></span>
                <h4><?=__('Add user')?></h4>
                    <?=$this->Form->create('FavouriteUser', array('url' => array('controller'=>'FavouriteUser','action'=>'move')))?>

                    <?=$this->Form->hidden('FavouriteUser.favourite_list_id', array('id' => 'MoveListId'))?>
                    <?=$this->Form->hidden('FavouriteUser.user_id', array('value' => $currUserID))?>

                    <div class="form-group noBorder">
                        <?=$this->Form->input('fav_user_id', array('options' => $aFavUsersOptions, 'class' => 'formstyler', 'label' => false, 'required' => 'required'))?>
                    </div>

                    <div class="clearfix">
                        <button type="submit" class="btn btn-primary"><?=__('Add')?></button>
                    </div>

                <?=$this->Form->end()?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function moveMember(e, favListID) {
        $('#MoveListId').val(favListID);
        $('#UserMovePopup').modal();
    }

    function editFavList(e, favListID, favListTitle) {
        $('#FavListPopup').hide();
        //$('.drop-add-sub-project-user').show().css({'top': $(e).offset().top});
        $('#FavouriteListId').val(favListID);
        $('#FavouriteListTitle').val(favListTitle);
        $('#FavListPopup h4').html('<?=__('Edit favourites list')?>');
        $('#FavListPopup .btn-primary').html('<?=__('Edit')?>');
        $('#FavListPopup').modal();
    }

    function addFavList(e) {
        $('#FavListPopup').hide();
        //$('.drop-add-sub-project-user').show().css({'top': $(e).offset().top});
        //$('#FavouriteListId).val('');
        $('#FavouriteListId').val('');
        $('#FavouriteListTitle').val('');
        $('#FavListPopup h4').html('<?=__('Add favourites list')?>');
        $('#FavListPopup .btn-primary').html('<?=__('Add')?>');
        $('#FavListPopup').modal();
    }

    $(function(){
        $("textarea[maxlength], input[maxlength]")
            .keydown(function(event){
            return !$(this).attr("maxlength") || this.value.length < $(this).attr("maxlength") || event.keyCode == 8 || event.keyCode == 46;
        })
        .keyup(function(event){
            var limit = $(this).attr("maxlength");
            if (!limit) return;
            if (this.value.length <= limit) return true;
        else {
            this.value = this.value.substr(0,limit);
            return false;
          }
        });
    });
</script>
