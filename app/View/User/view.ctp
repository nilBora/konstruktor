<?php
	$user_id = Hash::get($user, 'User.id');

	/* Breadcrumbs */
	$this->Html->addCrumb(Hash::get($user, 'User.full_name'), array('controller' => 'User', 'action' => 'view/'.$user_id));

// SOCIAL meta-s

	$scheme = isset($_SERVER['HTTP_SCHEME']) ? $_SERVER['HTTP_SCHEME'] : (
		 (
	  (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ||
	   443 == $_SERVER['SERVER_PORT']
		 ) ? 'https' : 'http'

	 );

    $actual_link = $scheme."://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$image_link = $scheme.'://'.$_SERVER['HTTP_HOST'].$this->Media->imageUrl($user['UserMedia'], 'thumb200x197');

    $title = Hash::get($user, 'User.full_name');
    $description = strlen(Hash::get($user, 'User.skills')) > 0 ? Hash::get($user, 'User.skills') : '';
    $description = strip_tags($description);

    echo $this->Html->meta(array('property' => 'og:url', 'content' => $actual_link),null,array('inline'=>false));
    echo $this->Html->meta(array('property' => 'og:image', 'content' => $image_link),null,array('inline'=>false));
    echo $this->Html->meta(array('property' => 'og:title', 'content' => $title),null,array('inline'=>false));
    echo $this->Html->meta(array('property' => 'og:site_name', 'content' => 'Konstruktor.com'),null,array('inline'=>false));
    echo $this->Html->meta(array('property' => 'og:description', 'content' => $description ),null,array('inline'=>false));

	echo $this->Html->meta(array('itemprop' => 'og:headline', 'content' => $title),null,array('inline'=>false));
	echo $this->Html->meta(array('itemprop' => 'og:description', 'content' => $description ),null,array('inline'=>false));

    echo $this->Html->meta(array('name' => 'twitter:url', 'content' => $actual_link),null,array('inline'=>false));
    echo $this->Html->meta(array('name' => 'twitter:image', 'content' => $image_link),null,array('inline'=>false));
    echo $this->Html->meta(array('name' => 'twitter:card', 'content' => 'summary_large_image'),null,array('inline'=>false));
    echo $this->Html->meta(array('name' => 'twitter:site', 'content' => '@konstruktor_com'),null,array('inline'=>false));
    echo $this->Html->meta(array('name' => 'twitter:title', 'content' => $title),null,array('inline'=>false));
    echo $this->Html->meta(array('name' => 'twitter:description', 'content' => $description),null,array('inline'=>false));

    $aMonths = array(
        '01' => __('January'),        '02' => __('February'),        '03' => __('March'),        '04' => __('April'),
        '05' => __('May'),            '06' => __('June'),            '07' => __('July'),            '08' => __('August'),
        '09' => __('September'),    '10' => __('October'),        '11' => __('November'),        '12' => __('December')
    );

    $aDays = array(
        '01' => 1,        '02' => 2,        '03' => 3,        '04' => 4,        '05' => 5,        '06' => 6,
        '07' => 7,        '08' => 8,        '09' => 9,        '10' => 10,        '11' => 11,        '12' => 12,
        '13' => 13,        '14' => 14,        '15' => 15,        '16' => 16,        '17' => 17,        '18' => 18,
        '19' => 19,        '20' => 20,        '21' => 21,        '22' => 22,        '23' => 23,        '24' => 24,
        '25' => 25,        '26' => 26,        '27' => 27,        '28' => 28,        '29' => 29,        '30' => 30,
        '31' => 31
    );

    $aYears = array();
    for($i = (int)date('Y', strtotime('-14 years')); $i> 1930; $i-- ) {
        $aYears[$i] = $i;
    }

//    $this->Html->css('jquery.Jcrop.min', null, array('inline' => false));
    $this->Html->css('cropper', null, array('inline' => false));

    $viewScripts = array(
//        'vendor/jquery/jquery.Jcrop.min',
        'vendor/jquery/jquery.iframe-transport',
        'vendor/exif',
        '/table/js/format',
        'cropper'
    );
    $this->Html->script($viewScripts, array('inline' => false));
    $allowEdit = $user_id == $currUserID && $currUserID;
?>

<style type="text/css">
    #phone.contentEditable {width: 100%}
    .age .date-block {display: inline-block; box-sizing: border-box; margin-bottom: 10px;}
    .age .date-block.day {width: 48%;}
    .age .date-block.year {width: 48%; float: right;}
    .age .date-block.month {width: 100%;}
    .controlSettings .controlBLock { margin-top: 10px; padding: 0 15px;}
        .controlSettings #Timezone.controlBLock .jq-selectbox__dropdown { width: auto;}
        .jq-selectbox__dropdown { max-height: 400px; overflow: auto;}
        .controlSettings .controlBLock, .controlSettings .linkBlock { display: inline-block; box-sizing: border-box;}
    .controlSettings .controlBLock {width: 22%}
    .controlSettings .linkBlock { width: 16%; text-align: right;}
    .baseInfo .linkBlock { display: inline-block; width: 100%; text-decoration: none; padding-top: 7px; }
        .baseInfo .linkBlock .glyphicons,
        .controlSettings .linkBlock .glyphicons {color: #1580be; font-size: 12px; vertical-align: top; white-space: nowrap; padding: 1px 4px 0 0;}
    .baseInfo [class*="col-sm-"] { padding: 0; }

    .age .jq-selectbox {width: 100%}

    .user-btn-block {
        width: 72px;
        padding: 0;
        margin-top: 7px;
        z-index: 50;
        margin-left: -8px;
    }

    .user-btn-block .btn {
        margin: 0 3px 6px 0;
    }

    @media(max-width: 768px) {
        .age .date-block {display: block;}
        .age .date-block.small {width: 100%}
        .age .date-block.small.right {float: none}
        .age .date-block.big {width: 100%; margin-bottom: 10px;}
        .user-btn-block {margin-left: 0;}
    }

    @media(max-width: 1023px) {
        .controlSettings .controlBLock { width: 49%; }
        .controlSettings .controlBLock .jq-selectbox { width: 100%; }
        .controlSettings .linkBlock { padding: 0 15px; width: 32%; text-align: left; }
    }
</style>
<?php $video_id = Hash::get($user, 'User.video_id') ;?>
<?php if ($video_id && count($userMedia) == 0) : ?>
	<div class="userViewVideo fixedLayout">
		<a href="javascript: void(0)" class="showPlayer">
			<span class="glyphicons play_button"></span>
			<?=__('Video presentation of my accomplishments')?>
		</a>
	</div>
	<div class="userViewVideoPlayer fixedLayout" style="display: none"></div>
<?php elseif (count($userMedia)) : ?>
	<div class="userViewVideo fixedLayout">
		<a href="javascript: void(0)" class="showPlayer">
			<span class="glyphicons play_button"></span>
			<?=__('Video presentation of my accomplishments')?>
		</a>
	</div>
	<div class="userViewVideoPlayer fixedLayout userVideo" style="display: none">
		<?php if($userMedia && $userMedia[0]['Media']['converted'] >= 12) : ?>
			<?php $file_url = $userMedia[0]['Media']['url_download']; ?>
			<video id="user-video" class="video-js vjs-default-skin vjs-big-play-centered"
				   controls preload="auto" data-setup=''>
				<source src="<?php echo preg_replace('/\..+$/','_360p.mp4', $file_url) ?>" type='video/mp4' label="360p"/>
				<source src="<?php echo preg_replace('/\..+$/','_360p.webm', $file_url) ?>" type='video/webm' label="360p"/>
				<source src="<?php echo preg_replace('/\..+$/','_360p.ogg', $file_url) ?>" type='video/ogg' label="360p"/>
				<source src="<?php echo preg_replace('/\..+$/','_480p.mp4', $file_url) ?>" type='video/mp4' label="480p"/>
				<source src="<?php echo preg_replace('/\..+$/','_480p.webm', $file_url) ?>" type='video/webm' label="480p"/>
				<source src="<?php echo preg_replace('/\..+$/','_480p.ogg', $file_url) ?>" type='video/ogg' label="480p"/>
				<source src="<?php echo preg_replace('/\..+$/','_720p.mp4', $file_url) ?>" type='video/mp4' label="720p"/>
				<source src="<?php echo preg_replace('/\..+$/','_720p.webm', $file_url) ?>" type='video/webm' label="720p"/>
				<source src="<?php echo preg_replace('/\..+$/','_720p.ogg', $file_url) ?>" type='video/ogg' label="720p"/>
				<source src="<?php echo preg_replace('/\..+$/','_1080p.mp4', $file_url) ?>" type='video/mp4' label="1080p"/>
				<source src="<?php echo preg_replace('/\..+$/','_1080p.webm', $file_url) ?>" type='video/webm' label="1080p"/>
				<source src="<?php echo preg_replace('/\..+$/','_1080p.ogg', $file_url) ?>" type='video/ogg' label="1080p"/>
				<p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
			</video>
		<?php else : ?>
			<?php
					$file_url = $userMedia[0]['Media']['url_download'];
					$ext = preg_replace('/^(.+\.)/', '', $file_url);
			?>
			<video id="user-video" class="video-js vjs-default-skin vjs-big-play-centered"
				   controls preload="auto" data-setup=''>
				<source src="<?= $file_url; ?>" type="video/<?= $ext;?>" />
			</video>
		<?php endif; ?>
	</div>
<?php endif; ?>




<div class="modal fade" id="cropper-modal" aria-hidden="true" aria-labelledby="bootstrap-modal-label" role="dialog" tabindex="-1" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="bootstrap-modal-label">Cropper</h4>
            </div>
            <div class="modal-body">
                <div id="cropper-modal-img-wrap">
                    <img class="img-responsive" src="" alt="Picture">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary save-upload">Save and Upload</button>
            </div>
        </div>
    </div>
</div>

<div class="row userviewInfo fixedLayout">
    <div class="col-sm-3 col-sm-push-9">
        <div class="avatar-img-User" style="overflow: hidden; display: inline-block;">
            <div class="avatar-user-div" style="overflow: hidden;width: 162px; height: 170px;">
                <?php echo $this->Avatar->user($user, array(
                    'id' => 'User'.$user_id,
                    'class' => 'ava-bottom img-responsive centered bit-top-margined',
                    'alt' => Hash::get($user, 'User.full_name'),
                    'title' => Hash::get($user, 'User.full_name'),
                    'data-id' => Hash::get($user, 'UserMedia.id'),
                    'data-resize' => 'thumb200x197',
                    //take down width and height by avatar border(usually 3px) x 2
                    'size' => 'thumb200x197',
                    'itemprop'=>'image',
                )); ?>
            </div>
            <?php if($allowEdit) : ?>
                <div class="inputFile needShow bit-top-margined centered" style="display: none;">
                    <input id="userAvatarChoose" class="filestyle fileuploader" type="file" data-object_type="User" data-object_id="<?=$user_id?>" data-progress_id="progress-User<?=$user_id?>" accept="image/*" style="width: 100%;">
                    <input id="userAvatarUpload" type="button" class="btn btn-primary save-button upload" value="<?=__('Save and upload')?>" style="display: none; width: 100%;">
                </div>

                <div class="progress" id="progress-User<?=$user_id?>" style="max-width: 161px; height: 0;">
                    <div class="progress-bar progress-bar-info" role="progressbar">
                        <span id="progress-stats"></span>
                    </div>
                </div>
            <?php endif; ?>
        </div>

    </div>

    <div class="col-sm-8 col-sm-pull-3 row baseInfo" itemscope itemtype="http://schema.org/Person">
        <h1 class="needHide" itemprop="name"><?=Hash::get($user, 'User.full_name')?></h1>
		<?php /*
        <!--div class="accountBalance-container needHide">
            <span class="accountBalance"><?php echo __('Current balance') ?>: <strong><?php echo $user['User']['balance']; ?> USD</strong></span>
            <a id="addFundsButton" class="linkBlock" style="width:auto;margin-left:10px;" href="javascript:void(0)">
                <i class="glyphicons plus"></i>
                <span class="underlink"><?php echo __('Add funds') ?></span>
            </a>
            <div id="addFundsBlock" class="linkBlock" style="width:auto;margin-left:10px;display: none;">
                <form id="addFundsForm" method="post" action="<?php echo $this->Html->url(array('plugin' => 'billing', 'controller' => 'billing_user', 'action' => 'payment')) ?>">
                    <input type="text" id="addFundsAmount" name="amount" value="10">
                    <button id="sendFundsForm" type="submit"><?php echo __('Pay') ?>
                    <button id="cancelFundsForm" type="button"><?php echo __('Cancel') ?>
                </form>
            </div>
        </div>
        <div class="clearfix"></div-->
		*/ ?>

        <div class="needShow clearfix" style="display: none;">
            <div style="position:relative;display: inline-block; width: 48%;">
                <h1 id="UserName" style="" placeholder="<?=__('First name').'...'?>"><?= explode(' ', $user['User']['full_name'], 2)[0] ?></h1>
            </div>

            <div style="position:relative; display: inline-block; width: 48%; float: right;">
                <h1 id="UserSurname" style="" placeholder="<?=__('Last name').'...'?>"><?= explode(' ', $user['User']['full_name'], 2)[1] ?></h1>
            </div>
        </div>
		<div class="description needHide" id="UserSkills" placeholder="<?=__('Skills').'...'?>" itemprop="jobTitle">
			<?php echo $this->Avatar->skills(Hash::get($user, 'User.skills')); ?>
		</div>
		<?php if($allowEdit) : ?>
			<?php if(!$userMedia) : ?>
				<div class="description needShow" id="YoutubeUrl" style="display: none;" contenteditable="contenteditable" placeholder="<?='http://youtube.com...'?>"><?=Hash::get($user, 'User.video_url')?></div>
			<?php endif; ?>

			<div class="needShow user-upload-video"  style="display: none">
				<?php if($userMedia) : ?>
					<span class="UserVideoAccomplishments-name"><?= $userMedia[0]['Media']['orig_fname']; ?><a href="javascript:void(0)" class="user-video-remove glyphicons circle_remove" data-id="<?=$userMedia[0]['Media']['id'];?>" title="Delete video"></a></span>
				<?php else : ?>
					<span class="fileuploader-wrapper btn btn-default"><?=__('Upload video')?><input type="file" id="video-user-upload"  data-object_type="UserVideoAccomplishments" data-object_id=""/></span>
				<?php endif; ?>
			</div>
			<div class="foldersAndFiles middleIcons clearfix" id="video-attach-user"></div>
		<?php endif; ?>

	<?php if($allowEdit) : ?>

        <div class="col-sm-3 needHide">
            <a class="linkBlock" href="<?=$this->Html->url(array('controller' => 'Statistic'))?>">
                <span class="glyphicons charts"></span>
                <span class="underlink"><?=__('Statistic')?></span>
            </a>
        </div>

        <div class="col-sm-3 needHide">
            <a class="linkBlock" href="<?=$this->Html->url(array('controller' => 'User', 'action' => 'tickets'))?>">
                <span class="glyphicons life_preserver"></span>
                <span class="underlink"><?=__('Support')?></span>
            </a>
        </div>

        <div class="col-sm-4 needHide">
            <a class="linkBlock" href="<?=$this->html->url(array('controller' => 'User', 'action' => 'timeManagement'))?>">
                <span class="glyphicons clock"></span>
                <span class="underlink"><?=__('Time management')?></span>
            </a>
        </div>

        <div class="col-sm-2 needHide">
            <a class="linkBlock" href="<?=$this->Html->url(array('controller' => 'User', 'action' => 'logout'))?>" onclick="return confirm('<?=__('Are you sure ?')?>">
                <span class="glyphicons exit"></span>
                <span class="underlink logout-fb"><?=__('Exit')?></span>
            </a>
        </div>

        <div class="col-sm-3 needShow" style="display: none; margin-top: 20px;">
            <a class="linkBlock" href="<?=$this->Html->url(array('controller' => 'User', 'action' => 'changeEmail'))?>">
                <span class="glyphicons message_full"></span>
                <span class="underlink"><?=__('Change email')?></span>
            </a>
        </div>

        <div class="col-sm-4 needShow" style="display: none; margin-top: 20px;">
            <a class="linkBlock" href="<?=$this->Html->url(array('controller' => 'User', 'action' => 'changePassword'))?>">
                <span class="glyphicons unlock"></span>
                <span class="underlink"><?=__('Change password')?></span>
            </a>
        </div>

        <div class="col-sm-3 needShow" style="display: none; margin-top: 20px;">
            <a class="linkBlock" href="<?= $this->Html->url(array('plugin' => 'billing', 'controller' => 'billing_user', 'action' => 'subscriptions'))?>">
                <span class="glyphicons money"></span>
                <span class="underlink"><?=__('Subscriptions')?></span>
            </a>
        </div>

        <div class="col-sm-3 needShow" style="display: none; margin-top: 20px;">
            <a class="linkBlock" href="<?=$this->Html->url(array('controller' => 'User', 'action' => 'skills'))?>">
                <span class="glyphicons blacksmith"></span>
                <span class="underlink"><?=__('Skills')?></span>
            </a>
        </div>

        <div class="col-sm-4 needShow" style="display: none; margin-top: 20px;">
            <a class="linkBlock" href="<?=$this->Html->url(array('controller' => 'User', 'action' => 'interests'))?>">
                <span class="glyphicons thumbs_up"></span>
                <span class="underlink"><?=__('Interests')?></span>
            </a>
        </div>
        <div class="col-sm-3 needShow" style="display: none; margin-top: 20px;">
            <a class="linkBlock" href="<?=$this->Html->url(array('controller' => 'User', 'action' => 'taskManagement'))?>">
                <span class="glyphicons blacksmith"></span>
                <span class="underlink"><?=__('Tasks')?></span>
            </a>
        </div>
        <div class="col-sm-3 needShow" style="display: none; margin-top: 20px;">
            <a class="linkBlock" href="<?=$this->Html->url(array('controller' => 'InvestProject', 'action' => 'listAllSponsors  '))?>">
                <span class="glyphicons thumbs_up"></span>
                <span class="underlink"><?=__('Investment management')?></span>
            </a>
        </div>
        <div class="col-sm-3 needShow" style="display: none; margin-top: 20px;">
            <a class="linkBlock" href="<?=$this->Html->url(array('controller' => 'User', 'action' => 'logout'))?>" onclick="return confirm('<?=__('Are you sure ?')?>">
                <span class="glyphicons exit"></span>
                <span class="underlink"><?=__('Exit')?></span>
            </a>
        </div>
	<?php endif; ?>

        <div class="clearfix">

		<?php if($currUserID) : ?>
			<?php if ($currUserID != $user_id) : ?>
			   <?php if($favUser) : ?>
					<a href="<?=$this->html->url(array('controller' => 'FavouriteUser', 'action' => 'deleteByUserId', $user['User']['id']))?>" class="btn btn-default" id="deleteFav"><?=__('Delete from favorites')?></a>
				<?php else : ?>
					<?=$this->Form->input('user_id', array('options' => $aFavListOptions, 'empty' => __('-- Select list --'), 'class' => 'formstyler', 'label' => false, 'div' => false, 'id' => 'favList', 'data-placeholder' => __('Add to favorites')))?>
				<?php endif; ?>
				<a href="<?=$this->Html->url(array('controller' => 'Chat', 'action' => 'index', $user_id))?>" class="btn btn-default"><?=__('Send message')?></a>
			<?php endif; ?>
		<?php else : ?>
			<a href="#register-popup" class="btn btn-default register-btn"><?=__('Send message')?></a>
			<a href="#register-popup" class="btn btn-default register-btn"><?=__('Add to favorites')?></a>
		<?php endif; ?>

		<?php if($currUserID) : ?>
			<?php if($user_id !== $currUserID) : ?>
				<?php if($subscription) : ?>
					<a href="<?=$this->Html->url(array('controller' => 'User', 'action' => 'deleteSubscription', $subscription['Subscription']['id']))?>" class="btn btn-default"><?=__('Unsubscribe')?></a>
				<?php else : ?>
					<a href="<?=$this->Html->url(array('controller' => 'User', 'action' => 'addSubscription', $user_id))?>" class="btn btn-default"><?=__('Subscribe')?></a>
				<?php endif; ?>
			<?php endif; ?>
		<?php endif; ?>

        </div>
    </div>

	<?php if($allowEdit) : ?>
		<div class="col-sm-1 col-sm-pull-3 user-btn-block">
			<div id="editMode" class="linkIcon">
				<div class="glyphicons wrench"></div>
				<div class="caption"><?=__('Edit')?></div>
			</div>
		</div>
	<?php endif; ?>
</div>


<div class="row fixedLayout">
    <div class="col-sm-4 userAddress" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
        <div class="location">
            <span class="city"><span class="glyphicons pin"></span><div class="caption" placeholder="<?=__('City/town of residence').'...'?>" itemprop="addressLocality"><?=Hash::get($user, 'User.live_place')?></div></span>
        </div>
<?php
    $country = Hash::get($user, 'User.live_country');
?>
        <div class="country needHide"><?=(isset($aCountryOptions[$country])) ?$aCountryOptions[$country] : ''?></div>
        <div class="country needShow" style="display: none;">
            <?=$this->Form->input('User.live_country', array('options' => $aCountryOptions, 'empty' => __('Select country'), 'label' => false, 'id' => 'Country', 'class' => 'formstyler countrySelect', 'value' => $country ))?>
        </div>
		<?php /* ?><!--div class="age"><?=$this->LocalDate->date(Hash::get($user, 'User.birthday'))?></div--> <?php */ ?>
        <div class="age needHide"><?=$this->LocalDate->birthDate(Hash::get($user, 'User.birthday'))?></div>

	<?php if($allowEdit) : ?>
		<?php
			$age = date_diff(date_create($user['User']['birthday']), date_create('now'))->y;
			$month = date('m', strtotime($user['User']['birthday']));
			$day = date('d', strtotime($user['User']['birthday']));
			$year = date('Y', strtotime($user['User']['birthday']));
		?>
			<div class="age needShow" style="display: none;">

				<?php /*
					<--div class="clearfix"></div>
					<div class="date-block small">
						<div id="UserAge" style="display: inline-block;"><?=$age?></div> <?=__('years')?>
					</div-->
				*/?>

				<div class="date-block month">
					<?=$this->Form->input('user_id', array('options' => $aMonths, 'empty' => __('Select month'), 'class' => 'formstyler', 'label' => false, 'div' => false, 'id' => 'BirthMonth', 'data-placeholder' => __('Select month'), 'value' => $month))?>
				</div>

				<div class="date-block day">
					<?=$this->Form->input('user_id', array('options' => $aDays, 'empty' => __('Select day'), 'class' => 'formstyler', 'label' => false, 'div' => false, 'id' => 'BirthDay', 'data-placeholder' => __('Select day'), 'value' => $day))?>
				</div>

				<div class="date-block year">
					<?=$this->Form->input('user_id', array('options' => $aYears, 'empty' => __('Select year'), 'class' => 'formstyler', 'label' => false, 'div' => false, 'id' => 'BirthYear', 'data-placeholder' => __('Select year'), 'value' => $year))?>
				</div>
			</div>
	<?php endif; ?>

    </div>
    <div class="col-sm-4 userEducation">
	<?php
		$university = Hash::get($user, 'User.university');
		$src = $this->Media->imageUrl(Hash::get($user, 'UniversityMedia'), 'thumb50x50');
		$mediaID = Hash::get($user, 'UniversityMedia.id');
	?>
	<?php if ($university || $allowEdit) : ?>
		<?php //echo $this->Html->image($src, array('alt' => $university, 'style' => 'width: 50px')); ?>

        <img id="UserUniversity<?=$user_id?>" src="<?=$src?>" alt="" class="pull-left" data-media_id="<?=$mediaID?>" data-resize="thumb50x50">
	<?php endif; ?>

        <div class="info">
            <div class="name" id="UniversityName" placeholder="<?=__('University/college').'...'?>"><?=$university?></div>
            <div id="Speciality" placeholder="<?=__('Occupation').'...'?>"><?=Hash::get($user, 'User.speciality')?></div>

			<?php if($allowEdit) : ?>
				<div class="photoButtons needShow" style="display: none;">
					<input id="userUniversityChoose" class="fileuploader filestyle" type="file" data-object_type="UserUniversity" data-object_id="<?=$user_id?>" data-progress_id="progress-UserUniversity<?=$user_id?>" accept="image/*">
					<span id="progress-UserUniversity<?=$user_id?>">
						<div id="progress-bar">
							<div id="progress-stats"></div>
						</div>
					</span>
				</div>
			<?php endif; ?>
        </div>
    </div>
    <div class="col-sm-4 userContacts">
        <div id="phone" placeholder="<?=__('Phone').'...'?>" itemprop="telephone"><?=Hash::get($user, 'User.phone')?></div>
		<div>
			<div class="social-small pull-right user-share needHide"><?=$this->element('social_share', array( 'title' => $title, 'content' => $description, 'imageUrl' => $image_link))?></div>
		</div>
    </div>
    <div class="clearfix"></div>

	<?php if($allowEdit) : ?>
		<div class="clearfix"></div>
		<div class="controlSettings needShow" style="display: none">
			<div class="controlBLock">
				<div class="form-group noBorder">
					<label><?=__('Interface language')?></label>
					<?=$this->Form->input('User.lang', array('label' => false, 'options' => $aLangOptions, 'id' => 'Language', 'class' => 'formstyler', 'value' => Hash::get($user, 'User.lang')))?>
				</div>
			</div>
			<div class="controlBLock">
				<div class="form-group noBorder">
					<label><?=__('Timezone')?></label>
					<?=$this->Form->input('User.timezone', array('label' => false, 'options' => $aTimezoneOptions, 'id' => 'Timezone', 'class' => 'formstyler', 'value' => Hash::get($user, 'User.timezone')))?>
				</div>
			</div>
		</div>
	<?php endif; ?>

    <?php
        $aAchiev = Hash::get($user, 'UserAchievement');
        if( $aAchiev ) {
            foreach($aAchiev as $key => &$achievement) {
                if($achievement['title'] == '')    unset($aAchiev[$key]);
            }
        } ?>
    <?php if ($aAchiev) :
        $aContainer = array('', '', '');
        $i = 0;
        foreach($aAchiev as $j => $achiev) {
            $aContainer[$i].= $this->element('Profile/profile_achiev', array('achiev' => $achiev, 'hide' => ($j >= 3)));
            $i++;
            if ($i >= 3) {
                $i = 0;
            }
        }
    ?>
    <div class="needHide">
        <h3><?=__('Achievements')?></h3>
        <div class="userAchievements clearfix">
            <?php foreach($aContainer as $container) : ?>
                <div class="item">
                    <?=$container?>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (count($aAchiev) > 3) : ?>
            <span class="showMore moreAchievements" onclick="$('.userAchievements .can-hide').fadeToggle(200); $('.moreAchievements .can-hide').toggle(); return false;">
                <span class="text can-hide"><?=__('Show more')?></span>
                <span class="text can-hide" style="display: none;"><?=__('Collapse')?></span>
                <span class="glyphicons repeat"></span>
            </span>
        <?php endif; ?>
    </div>
    <?php endif; //$aAchiev ?>

	<?php if ($currUserID) : ?>
		<?php if ($currUserID == $user_id) : ?>

			<?php /** Upload video files */ ?>
			<div class="video-block">
				<div class="video-block-title-btn">
					<h3><?=__('User video');?></h3>
					<div class="needShow group-upload-video"  style="display: none">
						<span class="fileuploader-wrapper btn btn-default"><?=__('Upload video')?><input type="file" id="video-manager-upload-input" multiple data-object_type="UserVideo" data-object_id=""/></span>
					</div>
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

				<?php /** Media block */ ?>
				<div class="row">
					<?php if (count($userVideos)) : ?>
						<?php foreach ($userVideos as $userVideo) : ?>
							<?php //if ($userVideo['Media']['converted'] >= 12) : ?>
								<div class="col-xs-3"><span class="video-block-link"><a href="<?=$userVideo['Media']['url_preview']?>" target="_blank" class="video-pop-this" data-url-down="<?=$userVideo['Media']['url_download']?>" data-converted="<?=$userVideo['Media']['converted'];?>"><?=$userVideo['Media']['orig_fname']?></a><a href="javascript:void(0)" class="user-video-remove glyphicons circle_remove" data-id="<?=$userVideo['Media']['id'];?>" title="Delete video"></a></span></div>
							<?php //endif; ?>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
			</div>

			<div class="show-after-upload">
				<div>
					<span>X</span>
					<p class="title"><?= __('Video has been successfully added to the site and soon will be available')?></p>
				</div>
			</div>
			<div class="clearfix"></div>
		<?php endif; ?>
	<?php endif; ?>

    <?php /** Adding user's accomplishments block to profile */ ?>
    <div class="row profile-achievements-block needShow" style="display: none">
        <div class="col-sm-3 leftFormBlock addNewField">
            <a href="javascript:void(0)" class="addNewInfo">
                <span class="glyphicons circle_plus"></span>
                <span class="title"><?=__('Add user\'s accomplishments')?></span>
            </a>
        </div>
        <div class="col-sm-9 rightFormBlock">

            <?php if (!$aAchiev) : ?>
				<div class="form-group noBorder no-items">
					<?=__('No achivements yet')?>
				</div>
            <?php else : ?>
				<?php foreach($aAchiev as $i => $row) : ?>
					<div id="achieve_<?=$i?>" class="group-fieldset achievementBlock" data-achiv-id="<?=Hash::get($row, 'id')?>">
						<div class="form-group">
							<span class="achiv-lable"><?=__('Achievement')?></span>
							<p class="achiv-title"><?=Hash::get($row, 'title')?></p>
						</div>
						<div class="form-group">
							<span class="achiv-lable"><?=__('Link to verified accomplishments')?></span>
							<p class="achiv-url"><?=Hash::get($row, 'url')?></p>
						</div>
						<div class="clearfix" style="margin-top: -10px; margin-bottom: 10px">
							<button type="button" class="btn btn-default pull-right removeAchievements"><?=__('Delete')?></button>
							<button type="button" class="btn btn-default pull-right editAchievements"><?=__('Edit')?></button>
							<button type="button" class="btn btn-default pull-right saveAchievements"><?=__('Save')?></button>
						</div>
					</div>
				<?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="clearfix"></div>

</div>

<?
    if ($aGroups) {
        $aContainer = array('', '', '');
        $i = 0;
        $j = 0;
        foreach($aGroups as $group) {
            $aContainer[$i].= $this->element('Profile/profile_groups', array('group' => $group, 'hide' => ($j >= 3)));
            $j++;
            $i++;
            if ($i >= 3) {
                $i = 0;
            }
        }
?>

<h3><?=__('Communities')?></h3>

<div class="row fixedLayout userProjects">
	<?php foreach($aContainer as $container) : ?>
		<div class="col-sm-6 col-md-4">
			<?=$container?>
		</div>
	<?php endforeach; ?>
</div>

<span class="showMore moreProjects"  onclick="$('.userProjects .can-hide').fadeToggle(200); $('.moreProjects .can-hide').toggle(); return false;">
	<?php if (count($aGroups) > 3) : ?>
		<span class="text can-hide"><?=__('Show more')?></span>
		<span class="text can-hide" style="display: none;"><?=__('Collapse')?></span>
		<span class="glyphicons repeat"></span>
	<?php endif; ?>
</span>

<?
    }

    if ($aArticles) {
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

<h3><?=__('Articles')?></h3>

<div class="row fixedLayout userArticles">
	<?php foreach($aContainer as $container) : ?>
		<div class="col-sm-6 col-md-4">
			<?=$container?>
		</div>
	<?php endforeach; ?>
</div>

<span class="showMore moreArticles" onclick="$('.userArticles .can-hide').fadeToggle(200); $('.moreArticles .can-hide').toggle(); return false;">
	<?php if (count($aArticles) > 3) : ?>
		<span class="text can-hide"><?=__('Show more')?></span>
		<span class="text can-hide" style="display: none;"><?=__('Collapse')?></span>
		<span class="glyphicons repeat"></span>
	<?php endif; ?>
</span>
<br /><br /><br />
<?
    }
?>

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

<script type='text/javascript'>

	// Init FB for Logout function
	window.fbAsyncInit = function () {
		FB.init({
			appId: '<?=Configure::read('fbApiKey')?>', // App ID
			status: true, // check login status
			cookie: true, // enable cookies to allow the server to access the session
			xfbml: true, // parse XFBML
			version: 'v2.5'
		});
	};

	(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.src = "//connect.facebook.net/en_US/sdk.js";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));

    $(document).ready(function() {

		$('.logout-fb').on('click', function(){
			FB.api('/me/permissions', 'delete', function(response) {
//				console.log(response.status); // true for successful logout.
			});
		});

        $('#addFundsButton').on('click', function(e){
            $(this).hide();
            $('#addFundsBlock ').show().css("display", "inline-block");;
        });
        $('#addFundsForm').submit(function(e){
            e.preventDefault();
            amount = $('#addFundsAmount').val();
            if(amount.match(/^[0-9\.]+$/)){
                this.submit();
            } else {
                alert('<?php echo __('Only numbers allowed for balance amount') ?>');
            }
        });
        $('#cancelFundsForm').on('click', function(e){
            $('#addFundsBlock ').hide();
            $('#addFundsButton').show();
        });

        if( $(".userViewVideo").length > 0 ) {
            $(".userViewVideo").on('click', function(e) {
                e.stopPropagation();
				var widthParent = $('.userViewVideo').width();
                $('.userViewVideo').slideUp('slow', function(){
					if ($('.userViewVideoPlayer').hasClass("userVideo")) {

						$('#user-video').css('width', widthParent+20);
//						var allowedHeight = $(window).height() - $('#preview-header').height() - 170;

						var vWidth = $('#user-video').width();
						var vHeight = vWidth / 16 * 10;

						$('#user-video').css('height', vHeight);
						$('.userViewVideoPlayer').css('height', vHeight);
					}
                    $('.userViewVideoPlayer').slideDown('slow', function(){
						if (!$(this).hasClass("userVideo")) {
							$('.userViewVideoPlayer').append('<iframe width="100%" height="360" src="//www.youtube.com/embed/<?=$video_id?>?rel=0" frameborder="0" allowfullscreen></iframe>');
						}
                    });
                });
            });
            $(document).on('click', function(e) {
                if (!$.contains($(".userViewVideoPlayer").get(0), e.target)) {
                    $('.userViewVideoPlayer').slideUp('slow', function(){
                        $('.userViewVideoPlayer iframe').remove();
                        $('.userViewVideo').slideDown();
						if ($('#user-video').length) {
							videojs('#user-video', {}, function(){
								this.pause();
							});
						}

                    });
                }
            });
        }

		// uploader
		$('#video-manager-upload-input, #video-user-upload').fileupload({
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
					$('.foldersAndFiles').find('a').fadeOut(400);
//					$('.show-after-upload').fadeIn(400);
					var inputId = data.fileInput.prop('id');
					$.post("/UserAjax/saveVideo.json", {data: {media_id:mediaId, object_type: file.object_type}}, function (response) {
						location.reload();
					});
				});
			},
			add: function (e, data) {
				var ul = $('#video-attach-list');
				var inputId = data.fileInput.prop('id');
				if (inputId == 'video-user-upload') {
					ul = $('#video-attach-user');
				}

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
		$('.user-video-remove').on('click', function () {
			if (!$(this).data('id')) {
				return;
			}
			if (!confirm('Are you sure?')) {
				return;
			}
			$.post("<?=$this->Html->url(array('controller' => 'UserAjax', 'action' => 'delVideo'))?>.json", {id: $(this).data('id')}, function (response) {
				location.reload(false);
			});
		});
    });

    $('#favList').change(function() {
        $.post('<?=$this->Html->url(array('controller' => 'FavouriteUser', 'action' => 'add'))?>', {data: {
                     fav_user_id: '<?=$user_id?>',
                     favourite_list_id: $(this).val(),
                     user_id: '<?=$currUserID?>'
        }
        }, function(response){
            location.reload();
        });
    });

<?php if($allowEdit) { ?>

    var editMode = false;
    var user_name = '';
    var user_surname = '';
    var city = '';
    var country = '';
    var birth_day = '';
    var birth_month = '';
    var birth_year = '';
    var phone = '';
    var university = '';
    var speciality = '';
    var timezone = '';
    var language = '';
    var videourl = '';
    var achievement = $('.profile-achievements-block .rightFormBlock .achievementBlock').length;

    $('#editMode').bind('click', function() {
        switchEditor();
    });

    // переключение режима редактирования
    switchEditor = function() {
        if( !editMode ) {
            enableEdit();
            $('#editMode').addClass('active');
            saveLeave = false;
        } else {
            if( sameCheck() ) {
                if(confirm("<?=__('Save changes?')?>")) {
                    var valid = validUserName();

                    if(valid){
                        saveSettings();
                        disableEdit();
                    }
                } else {
                    disableEdit();
                }
            } else {
                disableEdit();
            }
            $('#editMode').removeClass('active');
        }
    };

    // включение режима редактирования
    enableEdit = function() {
        $('.needHide').hide();
        $('.needShow').show();
        $('#UserName, #UserSurname, .city .caption, #UniversityName, #Speciality, #phone, #YoutubeUrl').addClass('contentEditable').addClass('needsclick');
        $('#UserName, #UserSurname, .city .caption, #UniversityName, #Speciality, #phone, #YoutubeUrl').prop('contentEditable', true);

        user_name = $('#UserName').text();
        user_surname = $('#UserSurname').text();
        city = $('.city .caption').text();
        country = $('#Country').val();
        birth_day = $('#BirthDay').val();
        birth_month = $('#BirthMonth').val();
        birth_year = $('#BirthYear').val();
        phone = $('#phone').text();
        university = $('#UniversityName').text();
        speciality = $('#Speciality').text();
        timezone = $('#Timezone').val();
        language = $('#Language').val();
        video_url = $('#YoutubeUrl').text();

        editMode = true;

        // заморочка с классами для грёбаного iOS-а
        $('#UserName, #UserSurname, .city .caption, #UniversityName, #Speciality, #phone, #YoutubeUrl').each( function() {
            if( $(this).text().length == 0 ) {
                $(this).removeClass('noPlaceholder');
            } else {
                $(this).addClass('noPlaceholder');
            }
        });

        $('.contentEditable').on('input', function() {
            if( $(this).text().length == 0 ) {
                $(this).removeClass('noPlaceholder');
            } else {
                $(this).addClass('noPlaceholder');
            }
        });
    };

    // отключение режима редактирования
    disableEdit = function() {
        $('#UserName, #UserSurname, .city .caption, #UniversityName, #Speciality, #phone, #YoutubeUrl, .achiv-title, .achiv-url').removeClass('contentEditable').removeClass('needsclick');
        $('#UserName, #UserSurname, .city .caption, #UniversityName, #Speciality, #phone, #YoutubeUrl, .achiv-title, .achiv-url').prop('contentEditable', false);
        $('.needHide').show();
        $('.needShow').hide();
        editMode = false;
        /*
        $('.contentEditable').off('input');
        */
		$('.saveAchievements').hide();
		$('.editAchievements').show();
    };

    // проверка на одинаковость старой и новой версии изменений
    sameCheck = function() {
        if(user_name !== $('#UserName').text()) { return true; };
        if(user_surname !== $('#UserSurname').text()) { return true; };
        if(city !== $('.city .caption').text()) { return true; };
        if(country !== $('#Country').val()) { return true; };
        if(birth_day !== $('#BirthDay').val()) { return true; };
        if(birth_month !== $('#BirthMonth').val()) { return true; };
        if(birth_year !== $('#BirthYear').val()) { return true; };
        if(phone !== $('#phone').text()) { return true; };
        if(university !== $('#UniversityName').text()) { return true; };
        if(speciality !== $('#Speciality').text()) { return true; };
        if(timezone !== $('#Timezone').val()) { return true; };
        if(language !== $('#Language').val()) { return true; };
        if(video_url !== $('#YoutubeUrl').text()) { return true; };
        /* Check if add some element to achievements */
        if(achievement != $('.profile-achievements-block .rightFormBlock .achievementBlock').length) { return true; };

        return false;
    };

    validUserName = function() {
        if($('#UserName').text().length < 2){
            $('#UserName').addClass('invalid');
            $('#UserName').siblings('.err-msg').remove();
            $('#UserName').parent().append('<div class="err-msg">Минимальна длина 2 символа</div>');
        } else {
            $('#UserName').removeClass('invalid').siblings('.err-msg').remove();
        }

        if($('#UserSurname').text().length < 2){
            $('#UserSurname').addClass('invalid');
            $('#UserSurname').siblings('.err-msg').remove();
            $('#UserSurname').parent().append('<div class="err-msg">Минимальна длина 2 символа</div>');
        } else {
            $('#UserName').removeClass('invalid').siblings('.err-msg').remove();
        }

        if($('#UserName').text().length >= 2 && $('#UserSurname').text().length >= 2) {
            return true;
        }

        return false;
    };

    //сохранение
    saveSettings = function() {
        var save_user_name = $('#UserName').text();
        var save_user_surname = $('#UserSurname').text();
        var save_city = $('.city .caption').text();
        var save_country = $('#Country').val();
        var save_birth_day = $('#BirthDay').val();
        var save_birth_month = $('#BirthMonth').val();
        var save_birth_year = $('#BirthYear').val();
        var save_phone = $('#phone').text();
        var save_university = $('#UniversityName').text();
        var save_speciality = $('#Speciality').text();
        var save_timezone = $('#Timezone').val();
        var save_language = $('#Language').val();
        var save_video_url = $('#YoutubeUrl').text();
        var achievementsData = $('.profile-achievements-block .rightFormBlock .achievementBlock');
        var achievements = [];

        achievementsData.each(function(index, element) {
            var achievement = {
                id: $(element).find('.UserAchievementId').val(),
                profile_id: $(element).find('.UserAchievementProfileId').val(),
                title: $(element).find('.UserAchievementTitle').val(),
                url: $(element).find('.UserAchievementUrl').val()
            }
            achievements[index] = achievement;
        });

        var today = new Date();
        var dob = '';

        dob = save_birth_year+'-'+save_birth_month+'-'+save_birth_day;

        /** sendData was modified, added UserAchievement */
        var sendData = {
                    User: {
                        full_name: save_user_name + ' ' + save_user_surname,
                        birthday: dob,
                        live_place: save_city,
                        live_country: save_country,
                        live_address: '',
                        timezone: save_timezone,
                        lang: save_language,
                        university: save_university,
                        speciality: save_speciality,
                        phone: save_phone,
                        video_url: save_video_url
                    },
                    UserAchievement: achievements
                };

        $.post('/UserAjax/saveSettings.json', {
                data: sendData,
            }, function (response) {
                console.log(response);
                location.reload();
                //disableEdit();
            }
        );
    }

    $('#userAvatarUpload').click(function(){
        $(this).data().submit();
    });

    /** Add achievements to new edit page, below is the template for adding achievements */
    $('.profile-achievements-block .addNewInfo').click(function(){
		/* For save achievements need to unset value */
		achievement = 0;

        $('.profile-achievements-block .no-items').remove();
        $('.profile-achievements-block .rightFormBlock').prepend(
            tmpl('profile-achiev', {i: $('.profile-achievements-block .rightFormBlock .achievementBlock').length})
        );
    });

    /** Allow edit achievements */
    $('.editAchievements').click(function(){
		$(this).hide();

		var achievements = $(this).closest('.achievementBlock');
		var achievementId = achievements.data("achiv-id");
		var title = achievements.find('.achiv-title');
		var url = achievements.find('.achiv-url');

		achievements.find('.saveAchievements').show();

		title.addClass('contentEditable').addClass('needsclick');
		title.prop('contentEditable', true);
		title.focus();
		url.addClass('contentEditable').addClass('needsclick');
		url.prop('contentEditable', true);

    });

	/** Save achievements */
	$('.saveAchievements').click(function(){
		$(this).hide();
		var achievements = $(this).closest('.achievementBlock');
		var achievementId = achievements.data("achiv-id");
		var title = achievements.find('.achiv-title');
		var url = achievements.find('.achiv-url');

		achievements.find('.editAchievements').show();

		$.post('/UserAjax/updateAchievements.json', {
			data: {
				id:achievementId,
				title:title.text(),
				url:url.text()
			}
		}, function (response) {
			title.addClass('contentEditable').removeClass('needsclick');
			title.prop('contentEditable', false);
			url.addClass('contentEditable').removeClass('needsclick');
			url.prop('contentEditable', false);
			achievement = 0;
		});
	});

	/** Delete achievements from db */
	$('.removeAchievements').on('click', function() {
		var achievement = $(this).closest('.achievementBlock');
		var achievementId = achievement.data("achiv-id");
		achievement.fadeOut(400);

		$.post('/UserAjax/deleteAchievements.json', {
			data: achievementId,
		}, function (response) {
//                    console.log(response);
		});
	});
<?php } ?>


</script>

<script type="text/x-tmpl" id="profile-achiev">
<div class="group-fieldset achievementBlock">
	<input type="hidden" name="UserAchievement[{%=o.i%}][id]" value="" class="UserAchievementId">
	<input type="hidden" name="UserAchievement[{%=o.i%}][profile_id]" value="<?=$currUserID?>" class="UserAchievementProfileId">
	<div class="form-group">
		<label for="achiv-title{%=o.i%}"><?=__('Achievement')?></label>
		<input class="form-control UserAchievementTitle" id="achiv-title{%=o.i%}" name="UserAchievement[{%=o.i%}][title]" placeholder="<?=__('Achievement')?>..." maxlength="255">
	</div>
	<div class="form-group">
		<label for="achiv-url{%=o.i%}"><?=__('Link to verified accomplishments')?></label>
		<input id="achiv-url{%=o.i%}" class="form-control UserAchievementUrl" type="text" name="UserAchievement[{%=o.i%}][url]" value="" placeholder="http://yoursite.com..."/>
	</div>
</div>
</script>
