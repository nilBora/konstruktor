<?php if($currUser['User']['is_confirmed'] && false): ?>
	<div class="confirmation_block" style="position: fixed; background: #ccc;padding: 10px; z-index: 999999999;">
			<p> Подтвердите адрес електронной почты чтобы получить доступ ко всем функциям. Сообщение для подтверждения было отправлино на адрес.</p>
			<a href="<?=$this->Html->url(array('controller' => 'User', 'action' => 'userConfirm', 'plugin' => false))?>" class="btn btn-default save-button" >Отправить подтверждение еще раз </a>
			<a href="<?=$this->Html->url(array('controller' => 'User', 'action' => 'changeEmail', 'plugin' => false))?>" class="btn btn-default save-button" >Изменить адрес электронной почты</a>
			<a href="<?=$this->Html->url(array('controller' => 'User', 'action' => 'changeEmail', 'plugin' => false))?>" class="btn btn-default save-button" >Подробнее</a>
	</div>
<?php endif;?>

<div id="header">
		<?php $dream_src = $this->webroot . 'img/group/crawn-s.png';?>

		<div class="row" style="display: inline-block;width:100% !important;">
				<div class="col-xs-4 chart">
						<div class="userLink">
								<?php echo $this->Avatar->userLink($currUser, array(
										'class' => 'rounded',
										'style' => 'width:56px;',
										//take down width and height by avatar border(usually 3px) x 2
										'size' => 'thumb64x64',
								)); ?>
								<span class="glyphicons cogwheel"></span>
						</div>
						<div class="myLinks">
								<img src="<?php echo $dream_src; ?>" class="crown-over-chart"/>
								<a href="" id="dream_chart"></a>
								<div class="group-select jq-selectbox clearfix" style="display: none;">
										<img class="group-logo" src="/img/no-photo-50.jpg">
										<?php
											$gr = array();

								$groupList = '';
								foreach ($userGroups as $key => $value) {
									if($key != 'create'){
										if(!empty($groupHeader) && $key == $groupHeader['Group']['id']){
											$selected = 'class="selected"';
										}else{
											$selected = '';
										}
											$crown_html = (isset($groupDreamInfo[$key]) && $groupDreamInfo[$key] == 1) ? '<span><img src="' . $dream_src . '" class="crawn-selectbox"/></span>' : '';
											$groupList .= '<li '.$selected.' data-id='.$key.'>' . $crown_html . '<span class="title">'.$value.'</span></li>';
										$gr[$key]['id'] = $key;
										$gr[$key]['title']= $value;
									}
								}
								foreach ($invites['Groups'] as $group) {
									$selected = '';
									if(!empty($groupHeader) && $key == $groupHeader['Group']['id']){
										$selected = 'class="selected"';
									}
									$hidden = '';
									if($group['Group']['hidden'] && !empty($group['GroupMember'])){
										$hidden = 'data-approved="'.$group['GroupMember']['approved'].'"';
									}

										$groupList .= '<li '.$selected.' '.$hidden.' class="invoice-li" data-id='.$group['Group']['id'].'>
										<div class="groupIcon">
											<img src="'.$group['GroupMedia']['url_img'].'">
										</div>
										<span class="title">'.$group['Group']['title'].'</span>
											<div class="btns">
												<span class="accept" data-id="'.$group['Group']['id'].'" >'.__('Confirm').'</span> /
												<span class="discart" data-id="'.$group['Group']['id'].'" >'.__('Decline').'</span>
											</div>
											<div class="groupsCount">
												<span>1</span>
											</div>
										</li>';
										$gr[$group['Group']['id']]['id'] = $group['Group']['id'];
										$gr[$group['Group']['id']]['title']= $group['Group']['title'];
								}
								$groupList .= '<li data-id="create" >'.$userGroups['create'].'</li>';
								$gr['create']= $userGroups['create'];
							?>
							<?php //echo $this->Form->input('DreamGroup', array('options' => $gr, 'label' => false, 'div' => false, 'id' => 'DreamGrouplist', 'class' => 'formstyler', 'value' => isset($_COOKIE["dream-chart"]) ? $_COOKIE["dream-chart"] : ''))?>

							<script>

								jQuery(document).ready(function(){
										var id = getCookie('dream-chart');
										if((id == '')||(id == 0)){
												id = jQuery('.jq-selectbox__dropdown .selected').data('id');
										}
										if(jQuery('.jq-selectbox__dropdown .selected').find('img').length > 0)
												$('.crown-over-chart').show();

									if(id == undefined){
											id = jQuery('.jq-selectbox__dropdown li:first-child').data('id');
									}

									jQuery("#dream_chart").attr("href", "/Group/view/"+id);
									jQuery.post("/GroupAjax/dreamStats/" +id + '.json', (function (respose) {
											if(respose.data) {
													var chartData = $.map( $.parseJSON(respose.data.state)  , function(el, key) { return [[new parseDate(key), el]]; });
													google.setOnLoadCallback( drawChart(chartData, respose.data.count) );
													jQuery(".myLinks .group-logo").attr("src", respose.data.logo);
													jQuery(".myLinks .jq-selectbox__select-text").html(respose.data.title);
											}
									}));
									jQuery('.groupTitle').click(function(){
										jQuery(this).addClass('active');
										jQuery(this).addClass('opened');
										jQuery(this).parent().children('.jq-selectbox__dropdown').show();
									});
									jQuery('.btns .accept').click(function(){
										var id = jQuery(this).data('id');

										$this = jQuery(this);
										jQuery.post(groupURL.inviteAccept, {
												data: {id: id}
										}, function (response) {
											if (response.status == 'OK' && response.data == 'done') {
												$this.parent().remove();
												$this.parent().parent().children('.groupIcon').remove();
												var count = jQuery('.groupsCount').text();
												count --;
												if(count > 0){
													jQuery('.groupsCount').html(count);
												}else{
													jQuery('.groupsCount').html('');
													jQuery('.groupsCount').hide();
												}
											}
										});
									});
									jQuery('.btns .discart').click(function(){
										var id = jQuery(this).data('id');
										$this = jQuery(this);
										jQuery.post(groupURL.inviteDecline, {
												data: {id: id}
										}, function (response) {
												if (response.status == 'OK' && response.data == 'done') {
													$this.parent().parent().remove();
													var count = jQuery('.groupsCount').text();
													count --;
													if(count > 0){
														jQuery('.groupsCount').html(count);
													}else{
														jQuery('.groupsCount').html('');
														jQuery('.groupsCount').hide();
													}
												}
										});
									});
									jQuery('.jq-selectbox__dropdown li').click(function(){
										if(!jQuery(this).hasClass('selected')){
												jQuery('.jq-selectbox__dropdown li').removeClass('selected');
												var _this = jQuery(this);
												if(_this.find('img').length > 0)
													$('.crown-over-chart').show();
												else
														$('.crown-over-chart').hide();
												jQuery(this).addClass('selected');
												var id = jQuery(this).data('id');
												var approved = jQuery(this).data('approved');
												if(approved == undefined){
													if(id != 'create'){
														jQuery("#dream_chart").attr("href", "/Group/view/"+id);
														jQuery('.jq-selectbox__select-text').html(jQuery(this).children('span.title').html());
														setCookie('dream-chart', id, 30);
														jQuery.post("/GroupAjax/dreamStats/" + id + '.json', (function (respose) {
																if(respose.data) {
																		//var chartData = $.map( $.parseJSON(respose.data.state)  , function(el, key) { return [[key, el]]; });
																		var chartData = $.map( $.parseJSON(respose.data.state)  , function(el, key) { return [[new parseDate(key), el]]; });
																		jQuery("#dream_chart").attr("href", "/Group/view/"+id);

																		google.setOnLoadCallback( drawChart(chartData, respose.data.count) );
																		jQuery(".myLinks .group-logo").attr("src", respose.data.logo);
																}
														}));
													}else{
														window.location = '/Group/edit'
													}
												}
										}
										//jQuery('.jq-selectbox__dropdown').show();
									});
									jQuery(document).click(function(e){

										if (!jQuery(e.target).parents().hasClass('jq-selectbox') && e.target.nodeName != 'OPTION') {
											if (jQuery('div.groupTitle.jq-selectbox__select.opened').length) {
												var selectbox = jQuery('div.jq-selectbox__select.opened');
												var selectboxp = jQuery('div.jq-selectbox__select.opened').parent();
												dropdown = jQuery('div.jq-selectbox__dropdown', selectboxp),
													jQuery('.groupTitle').removeClass('active');
												dropdown.hide().find('li.sel').addClass('selected');
												selectbox.removeClass('opened');
											}
										}
									})
								});
							</script>

							<div class="jq-selectbox__select groupTitle">
								<div class="jq-selectbox__select-text">
									<?php echo !empty($groupHeader)?$groupHeader['Group']['title']:array_values($userGroups)['0'];?>
								</div>
								<div class="jq-selectbox__trigger">
									<div class="jq-selectbox__trigger-arrow"></div>
								</div>
							</div>

							<div class="jq-selectbox__dropdown groupDropdown" style="display: none;">
								<ul class="group-list" >
									<?php echo $groupList; ?>
								</ul>
							</div>

							<?php if( count($invites['Groups'])>0): ?>
								<div class="groupsCount"><?php echo count($invites['Groups']);?></div>
							<?php endif;?>
						</div>

						<div class="overlay">
							<img src="/img/ajax_loader.gif" alt="loading...">
						</div>
					</div>
				</div>

				<div class="col-xs-4 search">
					<div class="searchLine" style="width: 100%">
						<span class="searchLine-box">
                            <input type="search" id="searchInput" placeholder="<?=__('Search users, groups, articles').'...'?>">
                            <span class="glyphicons search"></span>
                            <span id="returnMarkers" class="glyphicons unshare"></span>
                            <input type="hidden" id="controller-name" value="<?=$this->params['controller'];?>"/>
                            <input type="hidden" id="controller-action" value="<?=$this->params['action'];?>"/>
                        </span>
					</div>

					<div class="headerTimeline clearfix">
					    <span class="ajax-loader" style="display: none;"><img src="../img/ajax_loader.gif" alt="" style="width: 20px; height: 20px;"> <?=__('Loading...')?></span>
					    <!-- <div class="col-sm-6">
					         <div id="breadcrumb" style="display: none;">
					            <ol class="breadcrumb">
					                <li><a href="#">Home</a></li>
					                <li><a href="#">Library</a></li>
					                <li class="active">Data</li>
					            </ol>
					        </div>
					    </div> -->
					    <div class="col-sm-12">
							<?php if($this->request->controller == 'Statistic'): ?>
								<div class="btn-group" id="statistic-period">
									<button type="button" class="btn btn-default" data-value="today"><?= __('Today') ?></button>
									<button type="button" class="btn btn-default active" data-value="week"><?= __('Week') ?></button>
									<button type="button" class="btn btn-default" data-value="month"><?= __('Month') ?></button>
									<button type="button" class="btn btn-default" data-value="year"><?= __('Year') ?></button>
								</div>
							<?php else: ?>
								<div class="btn-group">
									<button id="showDay" class="btn btn-default active" type="button"><span><?=__('Day')?></span></button>
									<button id="showWeek" class="btn btn-default" type="button"><span><?=__('Week')?></span></button>
									<button id="showMonth" class="btn btn-default" type="button"><span><?=__('Month')?></span></button>
									<button id="showYear" class="btn btn-default" type="button"><span><?=__('Year')?></span></button>
								</div>
							<?php endif ?>
					    </div>
					</div>
				</div>

				<div class="col-xs-4 additional">
					<a href="#mmenu" class="mobile-nav">
						<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="344.339px" height="344.339px" viewBox="0 0 344.339 344.339">
						<g>
							<g>
								<g>
									<rect y="46.06" width="344.339" height="29.52"/>
								</g>
								<g>
									<rect y="156.506" width="344.339" height="29.52"/>
								</g>
								<g>
									<rect y="268.748" width="344.339" height="29.531"/>
								</g>
							</g>
						</g>
						</svg>
					</a>

					<div class="addPanel">
						<div class="menu-btn" id="chatLink" <?=($this->params['controller'] == 'Chat') ? 'class="active"' : ''?>>
							<div class="glyphicons chat"></div>
							<div class="caption"><?=__('Chat')?></div>
						</div>

						<a class="menu-btn articles <?=($this->params['controller'] == 'Article') ? 'active' : ''?>" href="/Article/all">
							<span class="glyphicons notes"></span>
							<div id="newsCount" class="count"></div>
							<div class="caption"><?=__('Articles')?></div>
						</a>

						<a class="menu-btn cloud <?=($this->params['controller'] == 'Cloud') ? 'active' : ''?>" href="/Cloud/index">
							<span class="glyphicons cloud"></span>
							<div id="cloudCount" class="count"></div>
							<div class="caption"><?=__('Files')?></div>
						</a>
					</div>
				</div>
		</div>

		<div class="openchat" id="open-user-list-panel"></div>

		<div class="clearfix"></div>

	<div id="breadcrumbs">
		<?php
			/*Get user name from full name */
			$userFullName = Hash::get($currUser, 'User.full_name');
			$userName = explode(' ', $userFullName);
			$userName = $userName[0];
		?>
		<?php echo $this->Html->getCrumbs('<span>></span>', array(
		'text' => __('Time').': '.$userName,
		'url' => '/Mytime',
		'escape' => false
		)); ?>
	</div>
</div>

<div id="mmenu">
	<ul>
		<li><a href="/User/view/<?php echo $currUser['User']['id'] ?>"><span class="mm-icon"><img src="/img/users81.png" alt=""></span>Профиль</a></li>
		<li><span id="mmenu-chatLink"><span class="mm-icon"><img src="/img/speech117.png" alt=""></span><?=__('Chat')?></span></li>
		<li><a href="/Article/all"><span class="mm-icon"><img src="/img/articles.png" alt=""></span><?=__('Articles')?></a></li>
		<li><a href="/Cloud/index"><span class="mm-icon"><img src="/img/file7.png" alt=""></span><?=__('Files')?></a></li>
		<li><a href="<?=$this->Html->url(array('controller' => 'Timeline', 'action' => 'index', 'plugin' => false))?>"><span class="mm-icon"><img src="/img/circular116.png" alt=""></span><?=__('My time')?></a></li>
		<li><a href="/Planet"><span class="mm-icon"><img src="/img/earth53.png" alt=""></span>Биржа задач</a></li>
	</ul>
</div>

<div id="menu">
	<div class="menu-wrapper">
		<div class="companyLink" >
			<a id="user<?=$currUser['User']['id']?>" href="<?=$this->Html->url(array('controller' => 'Timeline', 'action' => 'index', 'plugin' => false))?>" >
				<div class="clockCanvas">
					<canvas id="canvas" width="60" height="60"></canvas>
				</div>
				<div class="text"><?=__('My time')?></div>
			</a>
		</div>

		<a class="planet <?=$this->params['action'] == 'planet' ? 'active' : ''?>" href="/Planet">
			<img src="/img/panel/planet.png" alt="planet">

			<span><?php echo __('Tasks market') ?></span>
		</a>

		<div class="clearfix"></div>
	</div>
</div>

<div class="logo-konstruktor">
	<a class="logo" href="/Group/view/7">
		<img src="/img/panel/k-logo.png" alt="konstruktor" width="70px" >
	</a>
</div>

<audio id="msg-audio" preload="auto" style="display: none;">
	<source src="/snd/msg1_1.ogg"/>
	<source src="/snd/msg1_1.mp3"/>
	<source src="/snd/msg1_1.m4a"/>
	<source src="/snd/msg1_1.wav"/>
	<source src="/snd/msg1_1.aac"/>
</audio>

<div id="popover_content_wrapper" style="display: none">
	<div class="smileRow">
		<div class="smileSelect">٩(◕‿◕｡)۶</div>
		<div class="smileSelect">┬┴┬┴┤(･_├┬┴┬┴</div>
		<div class="smileSelect">ლ( ¤ 益 ¤ )┐</div>
		<div class="smileSelect">(-_-｡)</div>
	</div>
	<div class="smileRow">
		<div class="smileSelect">▄︻̷̿┻̿═━一</div>
		<div class="smileSelect">･｡ﾟ[̲̅$̲̅(̲̅ ͡° ͜ʖ ͡°̲̅)̲̅$̲̅]｡ﾟ.*</div>
		<div class="smileSelect">█▬█ █ ▀█▀</div>
		<div class="smileSelect">༼⁰o⁰；༽</div>
	</div>
	<div class="smileRow">
		<div class="smileSelect">ʕ◉ᴥ◉ʔ</div>
		<div class="smileSelect">¯\_(ツ)_/¯</div>
		<div class="smileSelect">♥</div>
		<div class="smileSelect">( ⊙ ʖ̯ ⊙ )</div>
		<div class="smileSelect">ʘ ͜ʖ ʘ</div>
		<div class="smileSelect">( ◔3◔)</div>
	</div>
	<div class="smileRow">
		<div class="smileSelect">(╭ರ_•́)</div>
		<div class="smileSelect">╰༼ ⋋ ‸ ⋌ ༽╯</div>
		<div class="smileSelect">ᕙ(▀̿̿Ĺ̯̿̿▀̿ ̿) ᕗ</div>
		<div class="smileSelect">┌། ≖ Ĺ̯ ≖ །┐</div>
		<div class="smileSelect">┌∩┐༼ ºل͟º ༽</div>
	</div>
	<div class="smileRow">
		<div class="smileSelect">┌[ ◔ ͜ ʖ ◔ ]┐</div>
		<div class="smileSelect">☆*:. o(≧▽≦)o .:*☆</div>
		<div class="smileSelect">s( ^ ‸ ^)-p</div>
		<div class="smileSelect">ᕙ( ͡° ͜ʖ ͡°)ᕗ</div>
	</div>
	<div class="smileRow">
		<div class="smileSelect">(⊃｡•́‿•̀｡)⊃</div>
		<div class="smileSelect">[ ⇀ ‿ ↼ ]</div>
		<div class="smileSelect">ᕕ╏ ͡ ▾ ͡ ╏┐</div>
		<div class="smileSelect">ლ(́◉◞౪◟◉‵ლ)</div>
	</div>
</div>

<style type="text/css">
	.clockCanvas {
		height: 60px;
		margin: 0 auto -5px auto;
		position: relative;
		width: 60px;
	}

	<?php if($this->params['controller'] == 'Timeline') { ?>
		.openchat{ right: 30px !important; }
		.user-list-windpw-minimize { right: 24px !important; }
		#header .companyLink { margin-right: 26px; }
	<?php } ?>

	<?php if($this->params['controller'] == 'Chat') { ?>
		.openchat{ height: 0 !important; }
	<?php } ?>
</style>

<script type="text/javascript"
	src="https://www.google.com/jsapi?autoload={
		'modules':[{
			'name':'visualization',
			'version':'1',
			'packages':['corechart']
		}]
	}"></script>

<script type="text/javascript">

$(window).resize();

$.ajax({
	url: '<?=$this->Html->url(array('controller' => 'ArticleAjax', 'action' => 'getCount'))?>',
	async: true
}).done(function(responses){
	if(responses > 0)
		$('#newsCount').html(responses);
});

$.ajax({
	url: '<?=$this->Html->url(array('controller' => 'CloudAjax', 'action' => 'getCount'))?>',
	async: true
}).done(function(responses){
	if(responses > 0)
		$('#cloudCount').html(responses);
});
//Search
var controller = $('#controller-name').val();
var action = $('#controller-action').val();

if (controller != 'Timeline') {
	if ( controller == 'Article' && (action == 'view' || action == 'edit') ) {
		$('#header .searchLine .glyphicons.search').on('click', function() {
			location.replace('/'+controller+'/all?search='+$('#searchInput').val());
		});
		$("body").keyup(function(event){
			if ($('#searchInput').val() != '') {
				if(event.keyCode == 13){
					location.replace('/'+controller+'/all?search='+$('#searchInput').val());
				}
			}
		});
		$('body').on('click', '#returnMarkers', function(){
			location.replace('/'+controller+'/all');
		});

	} else if ( controller == 'Article' && (action != 'view' || action != 'edit')) {
		$('#header .searchLine .glyphicons.search').on('click', function() {
			location.replace('/'+controller+'/'+action+'?search='+$('#searchInput').val());
		});
		$("body").keyup(function(event){
			if ($('#searchInput').val() != '') {
				if(event.keyCode == 13){
					location.replace('/'+controller+'/'+action+'?search='+$('#searchInput').val());
				}
			}
		});
		$('body').on('click', '#returnMarkers', function(){
			location.replace('/'+controller+'/'+action);
		});
	} else if ( controller == 'Cloud' && action == 'index' ) {
		$('#header .searchLine .glyphicons.search').on('click', function() {
			location.replace('/'+controller+'/'+action+'?search='+$('#searchInput').val());
		});
		$("body").keyup(function(event){
			if ($('#searchInput').val() != '') {
				if(event.keyCode == 13){
					location.replace('/'+controller+'/'+action+'?search='+$('#searchInput').val());
				}
			}
		});
		$('body').on('click', '#returnMarkers', function(){
			location.replace('/'+controller+'/'+action);
		});
	} else if ( controller == 'Cloud' ) {
		$('#header .searchLine .glyphicons.search').on('click', function() {
			location.replace('/'+controller+'/index?search='+$('#searchInput').val());
		});
		$("body").keyup(function(event){
			if ($('#searchInput').val() != '') {
				if(event.keyCode == 13){
					location.replace('/'+controller+'/'+action+'?search='+$('#searchInput').val());
				}
			}
		});
		$('body').on('click', '#returnMarkers', function(){
			location.replace('/'+controller+'/index');
		});
	} else {
		$('#header .searchLine .glyphicons.search').on('click', function() {
			location.replace("<?=$this->Html->url(array('controller' => 'Timeline', 'action' => 'index', '?' => array('search' => ''), 'plugin' => false))?>"+$('#searchInput').val());
		});
		$("#searchInput").keyup(function (e) {
			if (e.keyCode == 13) {
				location.replace("<?=$this->Html->url(array('controller' => 'Timeline', 'action' => 'index', '?' => array('search' => ''), 'plugin' => false))?>"+$('#searchInput').val());
			}
		});
	}
	if (location.search != "") {
		$('#returnMarkers').fadeIn(400);
	}

} else if ( controller == 'Timeline' && action == 'planet' ) {
	$('#header .searchLine .glyphicons.search').on('click', function() {
		_mapLoadPlanet();
	});

	$("body").keyup(function(event){
		if ($('#searchInput').val() != '') {
			if(event.keyCode == 13){
				_mapLoadPlanet();
			}
		}
	});

//	var buttonTmpl = '<span id="returnMarkers" class="glyphicons unshare"></span>';
//	$('.searchLine-box').append(buttonTmpl);

	$('body').on('click', '#returnMarkers', function(){
		_returnMarkersBack();
		$(this).fadeOut(400);
	});
}

if (controller != 'Chat') {
	$('#chatLink').on('click', function() {
		if( $('.user-list-slider .user-list-item:first').length != 0 ) {
			var roomId = $('.user-list-slider .user-list-item:first').data('room-id');
			location.replace('/Chat/room/'+roomId);
		} else {
			alert('<?=__('You have no dialogs')?>')
		}
	});
}

/*
$('#mmenu-chatLink').on('click', function() {
	if( $('.user-list-slider .user-list-item:first').length != 0 ) {
		var roomId = $('.user-list-slider .user-list-item:first').data('room-id');
		location.replace('/Chat/room/'+roomId);
	} else {
		alert('<?=__('You have no dialogs')?>')
	}
});
*/

jQuery(document).ready(function(){
    $("#mmenu").mmenu({
        "autoHeight": true,
        "navbar": {
            "title": ""
        },
        "offCanvas": {
            "position": "left"
        },
        "extensions": [
            "pageshadow",
            "border-full",
            "effect-menu-slide",
            "effect-listitems-drop"
        ]
    });
});

</script>
