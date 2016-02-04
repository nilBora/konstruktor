<?
	$hostname = 'http://'.$_SERVER['HTTP_HOST'];

	$aMonths = array(__('Jan'), __('Feb'), __('Mar'), __('Apr'), __('May'), __('Jun'), __('Jul'), __('Aug'), __('Sep'), __('Oct'), __('Nov'), __('Dec'));
	$aDays = array(__('Sun'), __('Mon'), __('Tue'), __('Wed'), __('Thur'), __('Fri'), __('Sat'));
?>
<body style="font-family: sans-serif;">
	<div style="max-width: 800px;">
		<table style="border-collapse:collapse; border:0px solid #ffffff; color:#000000;width:100%" cellpadding="0" cellspacing="0">
			<tr>
				<td>
					<img src="http://konstruktor.com/img/email/logo_large.png" style="width: 125px; height: 107px;"/>
				</td>
				<td>
					<div style="width: 475px; font: normal 22px Arial; float: right; margin-top: 85px; text-align: right;"><?=__('Creative Environment')?></div>
				</td>
			</tr>
		</table>
		<br /><br />
		<?__('Updates in last 12 hours:')?>
		<br /><br />
		<table style="border-collapse:collapse; border:0px solid #ffffff; color:#000000; width: 92%; margin-left: 8%; color: #313131;" cellpadding="0" cellspacing="0">
<?
	$prevDay = '';
	$timeOffset = 0;
	for( $i=0; $i<12; $i++) {
		$hourFrom = $timeTo - (($i+1)*3600);
		$hourTo = $timeTo - (($i)*3600);

		$hEvents = 0;
		foreach( $data['events'] as $time => $event ) {
			$created = strtotime( $time );
			if( $created < $hourTo && $created >= $hourFrom) $hEvents+=1;
		}
		if( $hEvents > 0 ) {
			if($prevDay != date('d', $hourTo)) {
				$prevDay = date('d', $hourTo);
?>
			<tr style="border-left: 2px solid #ffffff;">
				<td style="box-sizing: border-box; padding-left: 15px; padding-bottom: 25px; font-family: sans-serif; font-size: 12px;">
					<div style="background: url('<?=$hostname?>/img/calendar-back.png') no-repeat 0 0; width: 100px; height: 130px; position: relative; text-align: center; margin-left: -67px; margin-bottom: -30px;">
						<div class="date" style="font-size: 42px; color: #23b5ae; padding: 15px 0 0 0; height: 62px; line-height: 60px;"><?=date('j', $hourTo)?></div>
						<div class="month" style="font-size: 13px; color: #616161; text-transform: uppercase;"><?=$aMonths[ date('n', $hourTo)-1 ]?></div>
						<div class="weekday" style="font-size: 13px; color: #818181; position: absolute; right: -3px; top: 40px; background: #fff; padding: 4px 0 4px 0;"><?=$aDays[ date('w', $hourTo) ]?></div>
					</div>
				</td>
			</tr>
<?
			}
?>

			<tr style="border-left: 2px solid #f0f0f0;">
				<td style="box-sizing: border-box; padding-left: 15px; padding-bottom: 25px; font-family: sans-serif; font-size: 12px; ">
					<?= date( 'H:i', $hourTo ) ?>
				</td>
			</tr>
<?
			foreach( $data['events'] as $time => $event ) {
				$created = strtotime( $time );
				$key = key($event);
				if( $created < $hourTo && $created >= $hourFrom) {
?>
			<tr style="border-left: 2px solid #f0f0f0;">
				<td style="box-sizing: border-box; padding-left: 20%; padding-bottom: 25px; font-family: sans-serif; font-size: 15px;">
<?
					switch( $key ) {
						case 'UserEvent':
							$eUser = $data['users'][$event['UserEvent']['recipient_id']];
?>
						<div id="user-event_297" style="cursor: pointer;">
							<div class="eventTime" style="float: left; font-size: 11px; color: #414141; font-style: italic; display: inline-block; vertical-align: middle; margin-top: 5px;"><?= date( 'H:i', $created ) ?></div>
							<div class="glyphicons user" style="float: left; color: #616161; font-size: 16px; line-height: 16px; margin: 2px 0 0 2px;"></div>
							<div class="text" style="display: block; margin-left: 65px;">
								 <div class="title">12321</div>
								 <div class="clearfix" style="margin: 4px 0; height: 50px">
									 <img class="ava blockLine" src="<?=$hostname.$this->Media->imageUrl($eUser['UserMedia'], 'thumb256x256')?>" alt="<?=$eUser['User']['full_name']?>" onclick="window.location.href='/User/view/105'" style="float: left; width: 50px; height: 50px; margin: 0; cursor: pointer;">
									 <a class="userLink" href="<?=$hostname?>/User/view/105" style="float: left; margin-left: 12px; line-height: 24px; color: #1580be; text-decoration: underline;"><?=$eUser['User']['full_name']?></a>
								 </div>
								 <div class="descr">asdv</div>
							</div>
						</div>
						<br /><br />
<?
							break;
						case 'Article':
							$eUser = $data['users'][$event['Article']['owner_id']];
							$eGroup = $event['Article']['group_id'] ? $data['groups'][$event['Article']['group_id']] : null;
							$url = $hostname.$this->Html->url(array('controller' => 'Article', 'action' => 'view', $event['Article']['id']));
?>
						<div class="event clearfix">
							<span class="eventTime" style="float: left; font-size: 11px; color: #414141; font-style: italic; display: inline-block; vertical-align: middle; margin-top: 5px;"><?=date('H:i', $created)?></span>
						<img class="ava blockLine" style="width: 50px; height: auto; float: left; display: block; margin-left: 15px;"
						<?
							if($eGroup) {
						?>
										src="<?=$hostname.$this->Media->imageUrl($eGroup['GroupMedia'], 'thumb256x256')?>" alt="<?=$eGroup['Group']['title']?>"
						<?
							} else {
						?>
										src="<?=$hostname.$this->Media->imageUrl($eUser['UserMedia'], 'thumb256x256')?>" alt="<?=$eUser['User']['full_name']?>"
						<?
							}
						?>
						/>
							<span class="articleText" style="display: block; margin-left: 110px; margin-right: 0;">
								<div><?=__('A new article was published')?></div>
								<a href="<?=$url?>" class="description" style="font-size: 11px; font-style: italic; height: 34px; overflow: hidden; display: block; color: #1580be; text-decoration: underline;"><?=$event['Article']['title']?></a>
							</span>
						</div>
						<br /><br />
<?
							break;
						case 'ChatEvent':
							$date = date('H:i', $created);
							$eUser = $data['users'][$event['ChatEvent']['initiator_id']];
							$chatUrl = $hostname.$this->Html->url(array('controller' => 'Chat', 'action' => 'room', $event['ChatEvent']['room_id']));
							$userUrl = $hostname.$this->Html->url(array('controller' => 'User', 'action' => 'view', $eUser['User']['id']));
							if(!$event['ChatEvent']['file_id']) {
?>
					<div class="event clearfix" style="margin-bottom: 30px; box-sizing: border-box; text-align: left; word-wrap: break-word; font-weight: 400;">
						<span class="eventTime" style="float: left; font-size: 11px; color: #414141; font-style: italic; display: inline-block; vertical-align: middle; margin-top: 5px;"><?=$date?></span>
						<a href="<?=$userUrl?>" style="text-decoration: none;"><img class="ava blockLine" src="<?=$hostname.$this->Media->imageUrl($eUser['UserMedia'], 'thumb256x256')?>" alt="<?=$eUser['User']['full_name']?>" style="width: 50px; height: auto; float: left; display: block; margin-left: 15px;"></a>
						<a href="<?=$chatUrl?>" style="display: block; box-sizing: border-box; margin-left: 110px; background: #f8f8f0; padding: 8px 19px 14px 19px; position: relative; text-decoration: none; color: #313131;"><?=$data['messages'][$event['ChatEvent']['msg_id']]['message']?></a>
					</div>
<?
							} else {
							$file = $data['files'][$event['ChatEvent']['file_id']];
?>
					<div class="event clearfix taskFile">
						<span class="eventTime" style="float: left; font-size: 11px; color: #414141; font-style: italic; display: inline-block; vertical-align: middle; margin-top: 5px;"><?=$date?></span>
<?
								if( $file['file'] == 'image' ) {
?>
						<a href="<?=$hostname.$file['url_download']?>"><img src="<?=$hostname.$this->Media->imageUrl($file, 'thumb256x256')?>" alt="<?=$file['file']?>" style="width: 50px; height: auto; float: left; display: block; margin-left: 15px;"></a>
<?
								} else {
?>
						<a href="<?=$hostname.$file['url_download']?>"><img src="<?=$hostname.'/img/unknown_file.jpg'?>" style="width: 50px; height: auto; float: left; display: block; margin-left: 15px;"></a>
<?
								}
?>
						<div class="text" style=" display: block; margin-left: 110px;">
							<a href="<?=$hostname.$file['url_download']?>" target="_blank" style="color: #1580be;"><?=$file['orig_fname']?></a>
							<div class="description" style="margin-top: 5px; font-size: 11px; font-style: italic; height: 34px; overflow: hidden; display: block;"><?=__('You received a file from ')?> <a href="{%=url%}"><?=$eUser['User']['full_name']?></a></div>
						</div>
					</div>
					<br /><br />
<?
							}
							break;
						case 'ProjectEvent':

							$date = date('H:i', $created);
							$eUser = $data['users'][$event['ProjectEvent']['user_id']];
							$userUrl = $hostname.$this->Html->url(array('controller' => 'User', 'action' => 'view', $eUser['User']['id']));
							$taskUrl = $hostname.$this->Html->url(array('controller' => 'Project', 'action' => 'task', $event['ProjectEvent']['task_id']));
							$task = $data['tasks'][$event['ProjectEvent']['task_id']];
							$msg = $data['messages'][$event['ProjectEvent']['msg_id']]['message'];

							$taskLink = '<a href="'.$taskUrl.'" style="color: #1580be;">'.$task['Task']['title'].'</a>';
							$userLink = '<a href="'.$userUrl.'" style="color: #1580be;">'.$eUser['User']['full_name'].'</a>';

							$str = __('%s commented task %s', $userLink, $taskLink);

							if( $msg != '&nbsp;' )
?>
					<div class="event clearfix taskMsg">
						<span class="eventTime" style="float: left; font-size: 11px; color: #414141; font-style: italic; display: inline-block; vertical-align: middle; margin-top: 5px;"><?=$date?></span>
						<a href="<?=$userUrl?>"><img src="<?=$hostname.$this->Media->imageUrl($eUser['UserMedia'], 'thumb256x256')?>" alt="<?=$eUser['User']['full_name']?>" style="width: 50px; height: auto; float: left; display: block; margin-left: 15px;"></a>
						<span class="articleText" style="display: block; margin-left: 110px; margin-right: 0;">
							<div><?=$str?></div><div class="description" style="font-size: 11px; font-style: italic; height: 34px; overflow: hidden; display: block;"><?=$msg?></div>
						</span>
					</div>
<?
							$files = Hash::extract($data, 'files.{n}[object_id='.$event['ProjectEvent']['msg_id'].']');
							if($files) {
								foreach($files as $file) {
?>
						<div class="event clearfix taskFile" style="margin-top: 10px;">
							<span class="eventTime" style="float: left; font-size: 11px; color: #414141; font-style: italic; display: inline-block; vertical-align: middle; margin-top: 5px;"><?=$date?></span>
<?
						if( $file['file'] == 'image' ) {
?>
							<a href="<?=$hostname.$file['url_download']?>"><img src="<?=$hostname.$this->Media->imageUrl($file, 'thumb256x256')?>" alt="<?=$file['file']?>" style="width: 50px; height: auto; float: left; display: block; margin-left: 15px;"></a>
							<div class="articleText" style=" display: block; margin-left: 110px;">
								<a href="<?=$hostname.$file['url_download']?>" target="_blank" style="color: #1580be;"><?=$file['orig_fname']?></a>
								<div class="description" style="margin-top: 5px; font-size: 11px; font-style: italic; height: 34px; overflow: hidden; display: block;"><?=__('%s attached file to task %s', $userLink, $taskLink)?></div>
							</div>
<?
						} else {
?>
							<a href="<?=$hostname.$file['url_download']?>"><img src="<?=$hostname.'/img/unknown_file.jpg'?>" alt="<?=$file['file']?>" style="width: 50px; height: auto; float: left; display: block; margin-left: 15px;"></a>
							<div class="articleText" style=" display: block; margin-left: 110px;">
								<a href="<?=$hostname.$file['url_download']?>" target="_blank" style="color: #1580be;"><?=$file['orig_fname']?></a>
								<div class="description" style="margin-top: 5px; font-size: 11px; font-style: italic; height: 34px; overflow: hidden; display: block;"><?=__('%s attached file to task %s', $userLink, $taskLink)?></div>
							</div>
<?
						}
?>
						</div>
<?
								}
							}
?>
						<br /><br />
<?
							break;
					}
?>

				</td>
			</tr>
<?
				}
			}
		}
	}
?>
		</table>

		<br /><br />
		<div style="font: normal 15px/25px Arial; color: #212121;"><?=__('With best regards')?>,<br /><?=__('your %s', '<a href="http://konstruktor.com" style="color: #1580be; text-decoration: none; border-bottom: 1px solid #cae2f0;">Konstruktor.com</a>')?></div>
	</div>
</body>


<?
		//Debugger::dump( $data['events'] );
		//Debugger::dump( $data['files'] );
?>
