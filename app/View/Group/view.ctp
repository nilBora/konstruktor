<?php

	$groupID = Hash::get($group, 'Group.id');
	$title = Hash::get($group, 'Group.title');

	/* Breadcrumbs */
	$this->Html->addCrumb($title, array('controller' => 'Group', 'action' => 'view/'.$groupID));

	$this->Html->css(array('jquery.fancybox.css', 'fancy-fix.css'), array('inline' => false));
	$this->Html->script(array('vendor/jquery/jquery.fancybox.pack'), array('inline' => false));

	$financeID = Hash::get($group, 'Group.finance_project_id');


	$is_dream = Hash::get($group, 'Group.is_dream') == 1 ? 1 : 0;
	if($is_dream) {
		$dream_src = $this->webroot . 'img/group/crawn-s.png';
		$dream_data_img = $this->webroot . 'img/group/crawn-transparent-s.png';
	}
	else {
		$dream_src = $this->webroot . 'img/group/crawn-transparent-s.png';
		$dream_data_img = $this->webroot . 'img/group/crawn-s.png';
	}
	$src = $this->Media->imageUrl(Hash::get($group, 'GroupMedia'), 'thumb200x200');

	$videoUrl = Hash::get($group, 'Group.video_url');

// SOCIAL meta-s

	$scheme = isset($_SERVER['HTTP_SCHEME']) ? $_SERVER['HTTP_SCHEME'] : (
		(
		(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ||
		443 == $_SERVER['SERVER_PORT']
		) ? 'https' : 'http'

	);

	$actual_link = $scheme."://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$image_link = $scheme.'://'.$_SERVER['HTTP_HOST'].$this->Media->imageUrl($group['GroupMedia'], 'thumb256x256');
	$description = strlen(Hash::get($group, 'Group.descr')) > 0 ? Hash::get($group, 'Group.descr') : '';
	$description = strip_tags($description);

	echo $this->Html->meta(array('property' => 'og:url', 'content' => $actual_link),null,array('inline'=>false));
	echo $this->Html->meta(array('property' => 'og:image', 'content' => $image_link),null,array('inline'=>false));
	echo $this->Html->meta(array('property' => 'og:title', 'content' => $title),null,array('inline'=>false));
	echo $this->Html->meta(array('property' => 'og:site_name', 'content' => 'Konstruktor.com'),null,array('inline'=>false));
	echo $this->Html->meta(array('property' => 'og:description', 'content' => $description ),null,array('inline'=>false));

	echo $this->Html->meta(array('name' => 'twitter:url', 'content' => $actual_link),null,array('inline'=>false));
	echo $this->Html->meta(array('name' => 'twitter:image', 'content' => $image_link),null,array('inline'=>false));
	echo $this->Html->meta(array('name' => 'twitter:card', 'content' => 'summary_large_image'),null,array('inline'=>false));
	echo $this->Html->meta(array('name' => 'twitter:site', 'content' => '@konstruktor_com'),null,array('inline'=>false));
	echo $this->Html->meta(array('name' => 'twitter:title', 'content' => $title),null,array('inline'=>false));
	echo $this->Html->meta(array('name' => 'twitter:description', 'content' => $description),null,array('inline'=>false));

?>
<?
$this->Html->script(array(
	'vendor/bootstrap-datetimepicker.min',
	'vendor/bootstrap-datetimepicker.ru.js',
	'youtube.js',
), array('inline' => false));

$dateFormat = (Hash::get($currUser, 'User.lang') == 'rus') ? 'dd.mm.yyyy' : 'mm/dd/yyyy';
if(Configure::read('Config.language') == 'rus'){
	$lang = 'ru';
}else{
	$lang = 'en';
}
?>
<?
	if($videoUrl) {
		$video_id = str_replace(array('http://', 'https://', 'www.', 'youtube.com/', 'youtu.be/', 'watch?v='), '', $videoUrl);
?>

<div class="userViewVideo fixedLayout">
	<a href="javascript: void(0)" class="showPlayer">
		<span class="glyphicons play_button"></span>
		<?=$title?>
	</a>
</div>

<div class="userViewVideoPlayer fixedLayout" style="display: none"></div>
<script type='text/javascript'>
	$(document).ready(function() {
		$(".userViewVideo").click ( function(e) {
			e.stopPropagation();
			$(this).slideUp('slow', function(){
				$('.userViewVideoPlayer').slideDown('slow', function(){
					$('.userViewVideoPlayer').append('<iframe width="100%" height="360" src="//www.youtube.com/embed/<?=$video_id?>?rel=0" frameborder="0" allowfullscreen></iframe>');
				});
			});
		});

		$(document).on('click touchstart', function(e) {
			if (!$.contains($(".userViewVideo").get(0), e.target)) {
				$('.userViewVideoPlayer').slideUp('slow', function(){
					$('.userViewVideoPlayer iframe').remove();
					$('.userViewVideo').slideDown();
				});
			}
		});
	});
</script>

<?
	}
?>

<style>
    .group-title {
        color: #313131;
        font-family: "Roboto",sans-serif;
        font-weight: 900;
        line-height: 75px;
        word-wrap: break-word;
        font-size: 36px;
        padding: 0 7px;
        float: left;
    }
    .group-description {
        width: 98%;
        margin: 10px auto;
    }
    .korona {
        float: left;
        cursor: pointer;
        line-height: 75px;
    }
    .logo-wrap {
        overflow: hidden;
    }
    .groupViewInfo {
        position: relative;
    }
    .groupViewInfo h1 {
        max-width: inherit !important;
        padding: 0 0 10px 0 !important;
    }
    #group-create-4 {
        height: 34px;
    }
    .baseInfoRow .leftFormBlock {
        padding: 0 8px;
    }
    .controlButtons-editMode,.controlButtons-editMode-video {
        background: #fff;
    }
    .leftFormBlockEditMode {
        /*width: 100% !important;*/
        /*padding-top: 120px;*/
    }
    .photoCollection {
        list-style-type: none;
        padding: 0;
    }
    #achiv-title0 {
        height: 34px;
    }
    .col-sm-9.rightFormBlock button {
        display: none;
    }

</style>


<div class="row groupViewInfo fixedLayout" data-group-id="<?=$groupID?>" itemscope itemtype="http://schema.org/Organization">
    <div class="col-sm-8">
        <div class="logo-wrap">
            <div class="thumb" style="float:left;">
                <?php echo $this->Avatar->group($group, array(
                    'size' => 'thumb200x200',
                    'title'=>$title,
                    'itemprop' => 'image',
                )); ?>
            </div>
            <div class="group-title" itemprop="name"><?=$title?></div>
            <?php if($isGroupAdmin): ?>
                <div class="korona" data-mark="<?php echo $is_dream; ?>" title="<?php echo ($is_dream)? __('Snap to dream'): ''?>">
                    <img class="group-dream" src="<?php echo $dream_src; ?>" data-img="<?php echo $dream_data_img;?>" />
                </div>
            <?php endif;?>
        </div>

		<div style="float: left; clear: both"></div>
		<div class="description"><?=Hash::get($group, 'Group.descr')?></div>
		<?php if($isGroupAdmin):?>
			<div id="group_settings" style="display: none;">
				<?php echo $this->element('Group/group_settings', ['id' => $groupID]);?>
			</div>
		<?php endif;?>
	</div>
	<div class="col-sm-4">
		<div class="controlButtons" style="margin-top: 1px;">
			<a class="linkIcon" href="<?=$this->Html->url(array('controller' => 'Group', 'action' => 'vacancies', $groupID))?>">
				<div class="glyphicons tie"></div>
				<div class="caption"><?=__('Jobs')?></div>
			</a>


<?
if($currUserID){
	if (!($isGroupAdmin || $isGroupResponsible)) {
?>
			<a class="linkIcon" href="<?=$this->Html->url(array('controller' => 'Chat', 'action' => 'group', $groupID))?>">
				<div class="glyphicons chat"></div>
				<div class="caption"><?=__('Message')?></div>
			</a>
<?
	}
	if (!$joined && !$isGroupAdmin) {
?>
			<a id="joinGroup" class="linkIcon" href="javascript:void(0)" onclick="Group.join(<?=$groupID?>, <?=$currUserID?>)">
				<div class="glyphicons user_add"></div>
				<div class="caption"><?=__('Join')?></div>
				<span class="joined hide"><?=__('Your invitation was sent to the group administrator')?></span>
			</a>
<?
	}
}

if ($isGroupAdmin || $isGroupResponsible) {
?>
			<a class="linkIcon" href="<?=$this->Html->url(array('controller' => 'FinanceProject', 'action' => 'index', $financeID))?>">
				<div class="glyphicons credit_card"></div>
				<div class="caption"><?=__('Finances')?></div>
			</a>
			<!--a href="<?=$this->Html->url(array('controller' => 'InvestProject', 'action' => 'listProjects', '?' => array('my' => 1)))?>"></a-->
			<?php if(!empty($group['InvestProject']['id'])):?>
				<a class="linkIcon" href="<?=$this->Html->url(array('controller' => 'InvestProject', 'action' => 'view', $group['InvestProject']['id']))?>">
					<div class="glyphicons briefcase"></div>
					<div class="caption"><?=__('Investments')?></div>
				</a>
			<?php else: ?>
				<a class="linkIcon" href="javascript:investments()">
					<div class="glyphicons briefcase"></div>
					<div class="caption"><?=__('Investments')?></div>
				</a>
			<?php endif;?>
			<a class="linkIcon" href="<?=$this->Html->url(array('controller' => 'Device', 'action' => 'checkout'))?>">
				<div class="glyphicons ipad"></div>
				<div class="caption"><?=__('Devices')?></div>
			</a>
<?
}

if ($isGroupMember || ($isGroupAdmin || $isGroupResponsible)) {
?>
			<a class="linkIcon" href="<?=$this->Html->url(array('controller' => 'Group', 'action' => 'members', $groupID))?>">
				<div class="glyphicons group"></div>
				<div class="caption"><?=__('Members')?></div>
			</a>
<?
}

if ($isGroupAdmin || $isGroupResponsible) {
?>
			<a class="linkIcon group-settings" href="<?=$this->Html->url(array('controller' => 'Group', 'action' => 'edit', $groupID))?>">
				<div class="glyphicons wrench"></div>
				<div class="caption"><?=__('Settings')?></div>
			</a>
<?
}
if(!$currUserID){
?>
	<a id="joinGroup" class="linkIcon register-btn" href="#register-popup">
		<div class="glyphicons user_add"></div>
		<div class="caption"><?=__('Join')?></div>
		<span class="joined hide"><?=__('Your invitation was sent to the group administrator')?></span>
	</a>
<?
}
?>
		</div>
			<div class="groupAddress">
<?
	$aGroupAddress = Hash::get($group, 'GroupAddress');

	$headOfficeExist = false;
	foreach($aGroupAddress as $i => $groupAddress) {
		if($groupAddress['head_office'] == 1){
			$headOfficeExist = true;
			break;
		}
	}
	foreach($aGroupAddress as $i => $groupAddress) {
		$class = ($groupAddress['head_office'] == 1 or (!$headOfficeExist and $i==0)) ? '' : 'can-hide';
		$style = ($groupAddress['head_office'] == 1 or (!$headOfficeExist and $i==0)) ? '' : 'style="display: none"';
		$url = Hash::get($groupAddress, 'url');
		$email = Hash::get($groupAddress, 'email');

		$country = Hash::get($groupAddress, 'country');
		if(isset($countryNames[$country])){
			$country = $countryNames[$country];
		}
?>
			<div class="addressEntry <?=$class?>" <?=$style?>>
				<span  itemprop="address" itemscope itemtype="http://schema.org/PostalAddress"><span class="glyphicons pin"></span> <? if($groupAddress['address']) { ?><span itemprop="addressLocality"><?=Hash::get($groupAddress, 'address')?>, <? } ?><?=$country?><span> </span>
				<? if($groupAddress['phone']) { ?> <div class="pad8" itemprop="telephone"><span class="glyphicons phone_alt"></span><?=Hash::get($groupAddress, 'phone')?></div> <? } ?>
				<? if($groupAddress['fax']) { ?> <div class="pad8" itemprop="faxNumber"><span class="glyphicons fax"></span><?=Hash::get($groupAddress, 'fax')?></div> <? } ?>
				<? if($groupAddress['zip_code']) { ?> <div class="pad8"><span class="glyphicons message_full"></span><?=Hash::get($groupAddress, 'zip_code')?></div> <? } ?>
				<div class="pad8">
					<? if(strlen($url) > 8) { ?> <a href="<?=$url?>" class="underlink"><?=$url?></a><br> <? } ?>
					<? if($email) { ?> <a href="mailto:<?=$email?>" class="underlink" itemprop="email"><?=$email?></a> <? } ?>
				</div>
			</div>
<?
	}
?>
			<div class="pad8">
<?
	if(count($aGroupAddress) > 1) {
?>
				<a href="<?=$this->Html->url(array('controller' => 'Group', 'action' => 'addresses', $groupID))?>" class="underlink">
					<span class="can-hide" ><span class="glyphicons map"></span><?=__('All addresses')?></span>
				</a>
<?
	}
?>
				<div class="social-small"><?=$this->element('social_share', array( 'title' => $title, 'content' => $description, 'imageUrl' => $image_link))?></div>
			</div>
		</div>
	</div>
</div>

<?php
    $aGroupGallery = Hash::get($group, 'GroupGallery');
    $aGroupGallery = ($aGroupGallery) ? $aGroupGallery : array();

    $aGroupVideo = Hash::get($group, 'GroupVideo');
    $aGroupVideo = ($aGroupVideo) ? $aGroupVideo : array(); ?>

	<?php if ($aGroupGallery || $aGroupVideo) : ?>

<div class="group-view-content">
    <div class="groupGallery clearfix fixedLayout">
		<?php foreach($aGroupVideo as $video) : ?>
			<?php if ($video['media_id'] == 0) : ?>
				<a rel="photoalobum" href="<?=$video['url']?>" target="_blank"><img alt="" src="https://img.youtube.com/vi/<?=$video['video_id']?>/mqdefault.jpg" class="img-responsive"></a>
			<?php endif; ?>
		<?php endforeach; ?>

		<?php foreach($aGroupGallery as $media) :
			$src = $this->Media->imageUrl($media, 'thumb320x180');
			$orig = $this->Media->imageUrl($media, 'noresize');

			$imageInfo = (getimagesize(substr($media['url_download'], 1)));
			if($imageInfo[0] > 720) {
				$orig = $this->Media->imageUrl($media, '720x');
			}
		?>
            <a href="<?=$orig?>" class="fancybox" rel="photoalobum"><img src="<?=$src?>" alt="" class="img-responsive"/></a>
		<?php endforeach; ?>
    </div>

    <?php endif; ?>
	<?php
        $aGroupAchievement = Hash::get($group, 'GroupAchievement');

        if( $aGroupAchievement ) {
            foreach($aGroupAchievement as $key => &$achievement) {
                if($achievement['title'] == '')    unset($aGroupAchievement[$key]);
            }
        }

        if ($aGroupAchievement) {
    ?>
    <h3><?=__('Achievements')?></h3>

    <div class="userAchievements clearfix">
		<?php foreach($aGroupAchievement as $i => $achieve) :
				$class = ($i > 2) ? 'can-hide' : '';
				$style = ($i > 2) ? 'style="display: none"' : '';
				$url = Hash::get($achieve, 'url');
		?>
			<div class="item <?=$class?>" <?=$style?>>
				<a href="<?=$url?>" class="underlink">
					<?=Hash::get($achieve, 'title');?>
				</a>
			</div>
		<?php endforeach; ?>
    </div>

    <?
            if (count($aGroupAchievement) > 3) {
    ?>
    <span class="showMore moreAchievements" onclick="$('.userAchievements .can-hide').fadeToggle(200); $('.moreAchievements .can-hide').toggle(); return false;">
        <span class="text can-hide"><?=__('Show more')?></span>
        <span class="text can-hide" style="display: none"><?=__('Collapse')?></span>
        <span class="glyphicons repeat"></span>
    </span>
    <?
            }
        }
    ?>

    <?php $users = array(); ?>
    <?php foreach( $aMembers AS $key => $obj ): ?>
        <?php if( isset($aUsers[$obj['GroupMember']['user_id']]) ): ?>
            <?php $users[] = $aUsers[$obj['GroupMember']['user_id']]; ?>
        <?php endif; ?>
    <?php endforeach; ?>
    <?php if( count($users) ): ?>
        <h3><?=__('Team')?></h3>

        <div class="groupCommand clearfix">
            <?
            foreach($aMembers as $member) {
                if(isset($aUsers[$member['GroupMember']['user_id']])){
                    $user = $aUsers[$member['GroupMember']['user_id']];
                } else {
                    $user = NULL;
                }
                $role = $member['GroupMember']['role'];
                ?>
                <?php if($user): ?>
                    <a href="<?=$this->html->url(array('controller' => 'User', 'action' => 'view', $user['User']['id']))?>" class="item" itemprop="alumni" itemscope itemtype="http://schema.org/Person">
                        <?php echo $this->Avatar->user($user, array(
                            'style' => 'width: 100px',
                            'size' => 'thumb200x200',
                            'title' => $user['User']['full_name'],
                        )); ?>
                        <div class="name" itemprop="name"><?=$user['User']['full_name']?></div>
                        <div class="position"><p style="dysplay: block;  white-space: nowrap; overflow: hidden; padding: 5px;  text-overflow: ellipsis;"><?=$role?> </p></div>
                    </a>
                <?php endif; ?>
                <?
            }
            ?>
        </div>
    <?php endif; ?>

    <?
        $aContainer = array('', '', '');
        $i = 0;
        $j = 0;
        $isProjectsMember = 0;
        foreach($aProjects as $project) {
            if (((in_array($currUserID, $aProjectMembers[$project['Project']['id']])) || ($isGroupAdmin || $isGroupResponsible)) && !Hash::get($project, 'Project.closed')) {
                $isProjectsMember++;
                $aContainer[$i].= $this->element('Group/group_projects', array('project' => $project, 'hide' => ($j >= 3)));
                $i++;
                $j++;
                if ($i >= 3) {
                    $i = 0;
                }
            }
        }
        if ($isProjectsMember || ($isGroupAdmin || $isGroupResponsible)) {
    ?>

    <h3><?=__('Projects')?>

    <?
            if ($isGroupAdmin || $isGroupResponsible) {
    ?>
            <a class="btn btn-default" href="<?=$this->Html->url(array('controller' => 'Project', 'action' => 'edit', 'Project.group_id' => $groupID))?>">
                <?=__('New project')?>
            </a>
    <?
            }
    ?>
    </h3>

    <div class="row fixedLayout userProjects">
    <?
            if ($aProjects) {
                foreach($aContainer as $container) {
    ?>
            <div class="col-sm-4">
                <?=$container?>
            </div>
    <?
                }
            } else {
    ?>
            <div class="col-md-4">
                <?=__('Project has not been created yet')?>
            </div>
    <?
            }
    ?>
    </div>
    <?
            if (count($aProjects) > 3) {
    ?>
        <span class="showMore moreProjects" onclick="$('.userProjects .can-hide').fadeToggle(200); $('.moreProjects .can-hide').toggle(); return false;">
            <span class="text can-hide"><?=__('Show more')?></span>
            <span class="text can-hide" style="display: none;"><?=__('Collapse')?></span>
            <span class="glyphicons repeat"></span>
        </span>
    <?
            }
        }
    ?>
    <h3><?=__('Articles')?>
    <?
    if($currUserID){
        if(!($isGroupAdmin || $isGroupResponsible)) {
            if($subscription) {
    ?>
        <a href="<?=$this->Html->url(array('controller' => 'Group', 'action' => 'deleteSubscription', $subscription['Subscription']['id']))?>" class="btn btn-default"><?=__('Unsubscribe')?></a>
    <?
            } else {
    ?>
        <a href="<?=$this->Html->url(array('controller' => 'Group', 'action' => 'addSubscription', $groupID))?>" class="btn btn-default"><?=__('Subscribe')?></a>
    <?
            }
        } else {
    ?>
        <a class="btn btn-default" href="<?=$this->Html->url(array('controller' => 'Article', 'action' => 'view', 'group_id' => $groupID))?>">
            <?=__('Add article')?>
        </a>
    <?
        }
    }
    ?>
    </h3>
    <?
        if($aArticles) {
            $aContainer = array('', '', '');
            $i = 0;
            foreach($aArticles as $j => $article) {
                $aContainer[$i].= $this->element('Profile/profile_articles', array('article' => $article, 'hide' => ($j >= 6)));
                $i++;
                if ($i >= 3) {
                    $i = 0;
                }
            }
    ?>
    <div class="row fixedLayout userArticles">
    <?
            foreach($aContainer as $container) {
    ?>
            <div class="col-sm-6 col-md-4">
                <?=$container?>
            </div>
    <?
            }
    ?>
    </div>

    <?
            if (count($aArticles) > 3) {
    ?>
    <span class="showMore moreArticles" onclick="$('.userArticles .can-hide').fadeToggle(200); $('.moreArticles .can-hide').toggle(); return false;">
        <span class="text can-hide"><?=__('Show more')?></span>
        <span class="text can-hide" style="display: none;"><?=__('Collapse')?></span>
        <span class="glyphicons repeat"></span>
    </span>
    <?
            }
        } else {
    ?>
    <div class="noArticles">
        <?=__('No articles here yet...')?>
    </div>
    <?
        }
    ?>

	<?php /** Upload video files */ ?>
	<div class="video-block">
		<div class="video-block-title-btn">
			<h3><?=__('Group video');?></h3>
			<?php if ($isGroupAdmin || $isGroupResponsible) { ?>
			<div class="needShow group-upload-video">
				<span class="fileuploader-wrapper btn btn-default"><?=__('Upload video')?><input type="file" id="video-manager-upload-input" multiple data-object_type="GroupVideo" data-object_id=""/></span>
			</div>
			<?php } ?>
		</div>


		<div class="foldersAndFiles middleIcons clearfix" id="video-attach-list"></div>
		<div>
			<span id="video-files"></span>
		</div>
		<!-- Uploader upload file view -->
		<div class="hide" id="video-tpl-file-upload">
			<a href="javascript:void(0)" class="item">
				<span class="filetype"></span>
				<div class="title"></div>
				<div class="progress video">
					<div style="width: 0%;" aria-valuemax="100" aria-valuemin="0" aria-valuenow="90" role="progressbar" class="progress-bar progress-bar-info"><span class="percentage"></span></div>
				</div>
			</a>
		</div>
		<!-- /Uploader upload file view -->
	</div>

	<div class="show-after-upload">
		<div>
			<span>X</span>
			<p class="title"><?= __('Video has been successfully added to the site and soon will be available')?></p>
		</div>
	</div>
	<div class="clearfix"></div>

	<?php /** Media block */ ?>
	<div class="row">
		<?php if(count($groupVideos)) : ?>
			<?php foreach ($groupVideos as $groupVideo) : ?>
				<?php //if ($groupVideo['Media']['converted']) : ?>
					<div class="col-xs-3"><span class="video-block-link"><a href="<?=$groupVideo['Media']['url_preview']?>" target="_blank" class="video-pop-this" data-url-down="<?=$groupVideo['Media']['url_download']?>" data-converted="<?=$groupVideo['Media']['converted'];?>"><?=$groupVideo['Media']['orig_fname']?></a><a href="javascript:void(0)" class="group-video-remove glyphicons circle_remove" data-id="<?=$groupVideo['Media']['group_video_id'];?>" data-media-id="<?=$groupVideo['Media']['id'];?>" data-group-id="<?=$groupID;?>" title="Delete video"></a></span></div>
				<?php //endif; ?>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
</div>


<?php if(!$currUserID){ ?>
    <div id="register-popup" class="mfp-hide" >
        <div id="password_form_block" class="hidable" style="display: none;">
            <?=$this->element('User/pass_forget_form')?>
        </div>

        <div id="register_form_block" >
            <?=$this->element('User/register_form')?>
        </div>
    </div>
<?php } ?>


<style>
.groupViewInfo{
	border-bottom: 3px solid #eee;
	margin-bottom: 5px;
	padding-bottom: 40px;
}
.userArticles .edit-bar li{
	display: inline-block;
	vertical-align: middle;
	color: #818181;
	font-size: 11px;
}
.InvestProject-totals .form-group{
	border: none;
	margin-bottom: 10px;
}
.InvestProject-totals .form-group input{
	font-size: 12px;
	border: 1px solid #ccc;
	border-radius: 5px;
	padding: 0 5px;
}

.needSum .jq-selectbox{
	width: 50px;
	float: right;
	position: absolute !important;
	top: 2px;
	right: 16px;
}
.needSum .jq-selectbox .jq-selectbox__select{
	border: none;
	height: 30px;
}
.userArticles .edit-bar li, .userArticles .edit-bar li{
	display: inline-block;
	vertical-align: middle;
	color: #818181;
	font-size: 11px;
}

.fixedLayout{
	/*max-width: none;*/
}
#sorted-styler{
	float: right;
}
.edit-bar a{
	color: #999;
	text-decoration: none;
	font-size: 19px;
	padding: 5px;

}
.edit-bar .glyphicons:before{
	position: relative;
}
.edit-bar .glyphicons.eye_open:before{
	top: 	2px;
}
.edit-bar .glyphicons.edit:before{
	top: 0px;
}
.popup-back{
	display: none; position: fixed !important; top: 0px !important; z-index: 9999 !important; left: 0px !important; width: 100% !important; bottom: 0px !important; background: rgba(204, 204, 204,0.8)!important;
}

.popup-content{
	z-index: 9999 !important;  position: absolute !important; top:50% !important; left:50% !important;  display: none; max-width:830px !important; margin:0px auto 40px !important; transform:translate(-50%,-50%);
}

.popup-content > div{
	max-height:100% !important;
	background: #fff !important;  position:static !important; height:100% !important;
}

.row.InvestProject-totals{
	margin-top: 15px;
}

</style>
<br /><br /><br />
<?= $this->element('Invest/project_add')?>
<script type="text/x-tmpl" id="tmpl-invest-project-reward-form">
<div class="back" style="margin-top: 20px;position: relative;">
	<span style="position: absolute;top: -25px;right: 0;" class="reward-remove glyphicons remove"></span>
	<div class="row">
		<div class="col-sm-6">
			<div class="form-group"  style="margin-bottom: 10px;">
				<input name="InvestReward[{%=o.index%}][name]" type="text" class="form-control" placeholder="<?= __('Title') ?>" value="" data-label="<?= __('Title') ?>" required="true" />
			</div>
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group"  style="margin-bottom: 10px;">
						<div class="input-group">
							<input name="InvestReward[{%=o.index%}][total]" type="number" min="0" step="0.01" class="form-control" placeholder="<?= __('Amount') ?>" value="" data-label="<?= __('Amount') ?>" data-label="<?= __('Amount') ?>" required="true"/>
						</div>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group" style="margin-bottom: 10px;">
						<div class="input-group">
							<!--div class="input-group-addon glyphicons calendar"></div-->
							<input type="text" class="form-control" data-label="<?= __('Delivery date') ?>" placeholder="<?= __('Delivery date') ?>" required="true" id="invest-project-reward-{%=o.index%}" readonly/>
							<input name="InvestReward[{%=o.index%}][created]" type="hidden" placeholder="<?= __('Delivery date') ?>" data-label="<?= __('Delivery date') ?>" required="true" id="invest-project-reward-{%=o.index%}-mirror"/>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-6">
		<div class="form-group"  style="margin-bottom: 10px;">
			<textarea name="InvestReward[{%=o.index%}][description]" type="text" class="form-control" placeholder="<?= __('Description') ?>" value="" data-label="<?= __('Description') ?>" required="true"  rows="5" cols="40"></textarea>
		</div>
		</div>
	</div>
</div>
</script>


<?php if(!$currUserID){ ?>
<script type="text/javascript">
$(document).ready(function(){
    $('.register-btn').magnificPopup({
        type:'inline',
        midClick: true
    });
});
</script>
<?php } ?>

<script type="text/javascript">

add = function(){
	//   popup-content
	var body = $('body');
	var popup = $('.popup-back');
	var content = $('.popup-content');
	body.append(popup);
	popup.show();
	body.append(content);
	content.show();
}
edit = function(){
	//   popup-content
	var body = $('body');
	var popup = $('.popup-back');
	var content = $('.popup-edit-content');
	body.append(popup);
	popup.show();
	body.append(content);
	content.show();


}

$(document).ready(function () {

// Init

$('.popup-content .close-button').on('click',function(){
	var popup = $('body .popup-back');
	var content = $('body .popup-content');
	popup.hide();
	content.hide();
})

	$('select, input.filestyle, input.checkboxStyle').styler({fileBrowse: 'Загрузить фото'});
	var investRemoveMedia = function (id) {
		$.post(investURL.removeMedia + '/' + id);
	};
// Avatar Uploader
	$('#invest-project-avatar-uploader').fileupload({
		url: mediaURL.upload,
		dataType: 'json',
		done: function (e, data) {
			var file = data.result.files[0];
			file.object_type = $(data.fileInput).data('object_type');
			file.object_id = $(data.fileInput).data('object_id');
			$.post(mediaURL.move, file, function (response) {
				var id = response.data[0].Media.id;
				var src = response.data[0].Media.url_download;
				var oldAvatarId = $('#invest-project-avatar').val();
				if (oldAvatarId) {
					investRemoveMedia(oldAvatarId);
				}
				$('#invest-project-avatar').val(id);
				$('#invest-project-avatar-img').attr('src', src);
			});
			// progress
			$('#invest-project-avatarProgress').height(0);
			$('#invest-project-avatarProgress #progress-stats').html('');
			$('#invest-project-avatarProgress .progress-bar').attr('style', 'width: 0%');
		},
		add: function (e, data) {
			var file = data.files[0];
			var filetype = file.type;
			if(filetype != 'image/gif' && filetype != 'image/png' && filetype != 'image/jpg' && filetype != 'image/jpeg') {
				return false;
			}
			data.submit();
		},
		progress: function (e, data) {
			var progress = parseInt(data.loaded / data.total * 100, 10);
			$('#invest-project-avatarProgress').height(20);
			$('#invest-project-avatarProgress #progress-stats').html(progress + '%');
			$('#invest-project-avatarProgress .progress-bar').attr('style', 'width: ' + progress + '%');
		}
	});
// Gallery
	var investProjectGallery = {
		templates: {
			gallery: 'tmpl-invest-project-gallery',
			videoInputs: 'tmpl-invest-project-video-inputs',
			imageInputs: 'tmpl-invest-project-image-inputs'
		},
		containers: {
			gallery: '#invest-project-gallery',
			videoInputs: '#invest-project-video-inputs',
			imageInputs: '#invest-project-image-inputs'
		},
		images: [],
		addItem: function (item) {
			this.images.push(item);
		},
		addVideo: function (link) {
			if (!link) {
				return;
			}
			if (!Youtube.isValidLink(link)) {
				alert('<?= __('Invalid youtube video') ?>');
				return;
			}
			var image = this.videoImage(link);
			if (!image) {
				return;
			}
			var videoId = this.videoId(link);
			this.addItem({
				type: 'video',
				image: image,
				videoId: videoId
			});
			this.render();
		},
		addImage: function (image, id) {
			if (!image || !id) {
				return;
			}
			this.addItem({
				type: 'image',
				image: image,
				id: id
			});
			this.render();
		},
		deleteItem: function (index) {
			if (!this.images[index]) {
				return;
			}
			if (this.images[index].type == 'image') {
				investRemoveMedia(this.images[index].id);
			}
			this.images.splice(index, 1);
			this.render();
		},
		render: function () {
			$(this.containers.gallery).html(tmpl(this.templates.gallery, this.images));
			$(this.containers.videoInputs).html(tmpl(this.templates.videoInputs, this.images));
			$(this.containers.imageInputs).html(tmpl(this.templates.imageInputs, this.images));
			// events for gallery element's
			$(this.containers.gallery).find('.remove').on('click', function () {
				investProjectGallery.deleteItem($(this).data('index'));
			});
		},
		videoId: function (url) {
			var videoId = url.match(/(?:https?:\/{2})?(?:w{3}\.)?youtu(?:be)?\.(?:com|be)(?:\/watch\?v=|\/)([^\s&]+)/);
			if(videoId == null || !videoId[1]) {
				return;
			}
			videoId = videoId[1];
			return videoId;
		},
		videoImage: function (url) {
			var videoId = this.videoId(url);
			if (videoId) {
				return 'http://img.youtube.com/vi/' + videoId + '/default.jpg';
			}
		}
	}
	$('#invest-project-addVideo-modal button').on('click', function () {
		var $input = $('#invest-project-addVideo-modal input');
		investProjectGallery.addVideo($input.val());
		$input.val('');

	});
	$('#invest-project-gallery-uploadImage').fileupload({
		url: mediaURL.upload,
		dataType: 'json',
		done: function (e, data) {
			var file = data.result.files[0];
			file.object_type = $(data.fileInput).data('object_type');
			file.object_id = $(data.fileInput).data('object_id');
			$.post(mediaURL.move, file, function (response) {
				var image = response.data[0].Media.image;
				var id = response.data[0].Media.id;
				investProjectGallery.addImage(image, id);
			});
		},
		add: function (e, data) {
			var file = data.files[0];
			var filetype = file.type;
			if(filetype != 'image/gif' && filetype != 'image/png' && filetype != 'image/jpg' && filetype != 'image/jpeg') {
				return false;
			}
			data.submit();
		},
		progress: function (e, data) {
			var progress = parseInt(data.loaded / data.total * 100, 10);
		}
	});
// Imperavi-redactor image-uploader
	// button to redactor panel
	$('#redactor-toolbar-0').append($('#tmpl-invest-redactorUploadButton').html());
	$('#imperaviImageUploadButton').on('click', function () {
		$('#imperaviImageUpload').trigger('click');
	});
	var investProjectBodyImages = [];
	$('#imperaviImageUpload').fileupload({
		url: mediaURL.upload,
		dataType: 'json',
		done: function (e, data) {
			var file = data.result.files[0];
			file.object_type = $(data.fileInput).data('object_type');
			file.object_id = $(data.fileInput).data('object_id');
			$.post(mediaURL.move, file, function (response) {
				var id = response.data[0].Media.id;
				var imgUrl = response.data[0].Media.url_download;
				var imgName = response.data[0].Media.orig_fname;
				$('.redactor-editor').append('<p><img src="' + imgUrl + '" alt="' + imgName + '"></p>');
				$('.redactor_box.redactor').redactor('code.sync');
				investProjectBodyImages.push(id);
				$('#invest-project-body-inputs').html(tmpl('tmpl-invest-project-body-inputs', investProjectBodyImages));
			});
		},
		add: function (e, data) {
			var file = data.files[0];
			var filetype = file.type;
			if(filetype != 'image/gif' && filetype != 'image/png' && filetype != 'image/jpg' && filetype != 'image/jpeg') {
				return false;
			}
			data.submit();
		},
		progress: function (e, data) {
			var progress = parseInt(data.loaded / data.total * 100, 10);
		}
	});
// Reward's
	var investProjectRewardCounter = 0;
	$('#invest-project-reward-add-button').on('click', function () {
		var $newReward = $(tmpl('tmpl-invest-project-reward-form', {index: investProjectRewardCounter}));
		$newReward.find('#invest-project-reward-' + investProjectRewardCounter).datetimepicker({
			format: '<?= $dateFormat?>',
			weekStart: 1,
			autoclose: 1,
			todayHighlight: 1,
			startView: 2,
			minView: 2,
			language:"<?=$lang?>",
			linkField: 'invest-project-reward-' + investProjectRewardCounter + '-mirror',
			linkFormat: 'yyyy-mm-dd hh:ii:ss'
		});

		$newReward.find('#invest-project-reward-' + investProjectRewardCounter).datetimepicker().on('focus', function () {
			var dts = $('.datetimepicker');
			for(var i = 0; i <= (dts.length - 1);i++) {
				var dt = $(dts[i]);
				dt.css('left', parseInt(dt.css('left')) - 50);
			}
		});

		$newReward.find('.reward-remove').on('click', function () {
			$newReward.remove();
		});
		$('#invest-project-reward-list').prepend($newReward);
		investProjectRewardCounter++;
	});
	//$('#invest-project-reward-add-button').trigger('click');
// Video
	$('[name="InvestProject[video]"]').on('blur', function () {
		if ($(this).val()) {
			if (!Youtube.isValidLink($(this).val())) {
				alert('<?= __('Invalid youtube video') ?>');
			}
		}
	});
// Submit
	$('#invest-project-create').on('submit', function () {
		// ipad validation
		var required = $('#invest-project-create').find('[required="true"]');
		for(var i = 0; i <= (required.length - 1);i++)
		{
			if($(required[i]).val() == '')
			{
				alert("'" + $(required[i]).data('label') + "'" + ' <?= __('is required') ?>');
				return false;
			}
		}

		// duration
		if ($('[name="InvestProject[duration]"]').val() != parseInt($('[name="InvestProject[duration]"]').val())) {
			alert("<?= __('Enter a integer number to Project duration') ?>");
			return false;
		}
		if ($('[name="InvestProject[duration]"]').val() > 60) {
			alert('<?= __('Project duration (60 days maximum)') ?>');
			return false;
		}

		// youtube link
		if ($('[name="InvestProject[video]"]').val()) {
			if (!Youtube.isValidLink($('[name="InvestProject[video]"]').val())) {
				alert('<?= __('Invalid youtube video') ?>');
				return false;
			}
		}
	});
// fix select
	$('.jq-selectbox.jqselect.category ul').height(300);

	// uploader
	$('#video-manager-upload-input').fileupload({
		url: mediaURL.upload,
		dataType: 'json',
		done: function (e, data) {
			var file = data.result.files[0];
			if(file.hasOwnProperty('error') && file['error'] == 'File Storage limit exceeded') {
				var temp = $('#video-manager-list .item .filetype');
				var temp_parent = temp.parent().has('.progress');
				temp_parent.empty().html('<div style="color: red;">' + file['error'] + '</div>');
				setTimeout(function () {
					temp_parent.fadeOut('slow', function () {
						temp_parent.remove();
					})
				}, 3000);
				return false;
			}
			file.object_type = $(data.fileInput).data('object_type');
			file.object_id = $(data.fileInput).data('object_id');

			$.post(mediaURL.move, file, function (response) {
				var mediaId = response.data[0].Media.id;
				var groupId = $('.groupViewInfo').data('group-id');
				$('.foldersAndFiles').find('a').fadeOut(400);
//				$('.show-after-upload').fadeIn(400);
				$.post("/GroupAjax/saveVideo.json", {data: {media_id: mediaId, group_id: groupId}}, function (response) {
					location.reload();
				});
			});
		},
		add: function (e, data) {
			var ul = $('#video-attach-list');
			var fileType = data.files[0].name.split('.').pop().toLowerCase().replace('jpeg', 'jpg');
			var $tpl = $('#video-tpl-file-upload .item').clone();
			var fileClass = Cloud.hasType(fileType) ? 'filetype ' + fileType : 'glyphicons file';
			$tpl.find('.filetype').attr('class', fileClass);
			$tpl.find('.title').text(data.files[0].name);
			data.context = $tpl.prependTo(ul);
			data.submit();
		},
		progress: function (e, data) {
			var progress = parseInt(data.loaded / data.total * 100, 10);
			data.context.find('.percentage').html(progress + '%');
			data.context.find('.progress-bar').attr('style', 'width: ' + progress + '%');
		},
		fail: function (e, data) {
			if(data.jqXHR.status = 403){
				$('#space-notification-modal').modal('show');
			}
		}
	});

	$('.show-after-upload span').on('click', function(){
		$(this).closest('.show-after-upload').fadeOut(400);
	});

	// delete video file
	$('.group-video-remove').on('click', function () {
		if (!$(this).data('id')) {
			return;
		}
		if (!confirm('Are you sure?')) {
			return;
		}
		$.post("<?=$this->Html->url(array('controller' => 'GroupAjax', 'action' => 'delGalleryVideo'))?>.json", {data: {id: $(this).data('id'), group_id: $(this).data('group-id'), media_id: $(this).data('media-id')}}, function (response) {
			location.reload(false);
		});
	});
});
</script>
<script type="text/javascript">
var investments = function(){
    //	var body = $('body');
	var popup = $('.popup-back');
	var content = $('.popup-content');
    //	body.append(popup);
	popup.show();
    //	body.append(content);
	content.show();
	$('select').styler();
}
$(document).ready(function(){
	$('.popup-content .close-button').on('click',function(){
		var popup = $('body>.popup-back');
		var content = $('body>.popup-content');
		popup.hide();
		content.hide();
	})
	var group_id = '<?php echo $groupID?>';
	$('.fancybox').fancybox({
		padding: 5
	});
	$('.korona').click(function(){
		var to_mark;
		var _this = $(this);
		if($(this).data('mark') == '0') {
			to_mark = 1;
		}
		else {
			to_mark = 0;
		}
		$.ajax({
			url: groupURL.setDream,
			async: false,
			data: {group_id: group_id, mark: to_mark},
			type: "post",
			dataType: 'json'
		}).done(function(response){
			_this.data('mark', to_mark);
			var img_obj = _this.children('img');
			var old_src = img_obj.attr('src');
			img_obj.attr('src', img_obj.data('img'));
			img_obj.data('img', old_src);
			var new_title;
			if(to_mark == 1) {
				new_title = '<?php echo __('Snap to dream');?>';
			}
			else {
				new_title = '';
			}
			_this.attr('title', new_title);
		});

	});
	$('.group-settings').click(function(e){
		e.preventDefault();
		if(!$('.logo-wrap').is(":visible") ) {
			if(group_settings_flag) {
				var result = confirm("<?=__('Save changes?')?>");
				if (result == true) {
					$('#Group').submit();
					return;
				} else {
					$('#group_settings, .logo-wrap, .description, .groupAddress, .group-view-content').toggle();
					$('.row.groupViewInfo .col-sm-8').toggleClass('leftFormBlockEditMode');
					$('.row.groupViewInfo .col-sm-4').toggleClass('controlButtons-editMode');
					return;
				}
			}
		}
		if($('.userViewVideo').length > 0)
			$('.row.groupViewInfo .col-sm-4').toggleClass('controlButtons-editMode-video');
		else
			$('.row.groupViewInfo .col-sm-4').toggleClass('controlButtons-editMode');
		$('#group_settings, .logo-wrap, .description, .groupAddress, .group-view-content').toggle();
		$('.row.groupViewInfo .col-sm-8').toggleClass('leftFormBlockEditMode');

		$('#GroupDescr').trigger('autosize.resize');
	})
});
</script>
