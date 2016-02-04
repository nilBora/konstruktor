<?php

App::uses('AdminController', 'Controller');

class CheckdbController extends AdminController {

	public $name = 'Checkdb';
	public $uses = array(	'ChatContact',	'ChatRoom',
							'ChatMember',	'ChatEvent',
							'ChatMessage',	'ChatUserData',
							'ChatContacts',	'Media',
							'Group',		'GroupAchievement',
							'GroupAddress',	'GroupVideo',
							'GroupMember',	'Contractor',
							'Order',		'OrderType',
							'OrderProduct',	'Product',
							'ProductType',	'Project',
							'ProjectEvent',	'ProjectMember',
							'Subproject',	'Task',
							'User',			'UserAchievement',
							'UserEvent',	'GCountry'
						);


	public function index() {

		$this->autoRender = false;

		$ret = ' ';

		$aUsers = $this->User->find('all'); //�������� ������
		$aUsers = Hash::combine($aUsers, '{n}.User.id', '{n}.User'); //������� ���������� �������� � �������� ��������

		$aMedia = $this->Media->find('all'); //�������� ������
		$aMedia = Hash::combine($aMedia, '{n}.Media.id', '{n}'); //������� ���������� �������� � �������� ��������

		$aMessages = $this->ChatMessage->find('all'); //�������� ������
		$aMessages = Hash::combine($aUsers, '{n}.ChatMessage.id', '{n}'); //������� ���������� �������� � �������� ��������

		$aRooms = $this->ChatRoom->find('all'); //�������� ������
		$aRooms = Hash::combine($aUsers, '{n}.ChatRoom.id', '{n}'); //������� ���������� �������� � �������� ��������



		// ------------------------------------------------------------------------
		// ----------------------------------------------------------- ChatContact CHECK
		// ------------------------------------------------------------------------

		$fulldata = $this->ChatContact->find('all');
		$ret .= '<hr>Checking LiveCountry<br>';

		$aCountry = $this->Country->find('all');
		$aCountry = Hash::combine($aCountry, '{n}.Country.country_code', '{n}');

		$fulldata = $this->User->find('all');
		$initDate = strtotime(Configure::read('Konstructor.created'));
		foreach( $fulldata as $entry )
		{
			if (!in_array(($entry['User']['live_country']), array_keys($aCountry)))
			{
				if(!$entry['User']['live_country'] == NULL)
				{
					$ret .= '	'.$entry['User']['id'].':	has error value in LIVE_COUNTRY: '.$entry['User']['live_country'].'<br>';
				}
			}
			if($entry['User']['is_admin'] > 0)
			{
				$ret .= '	'.$entry['User']['id'].':	User\'s "is_admin" field has '.$entry['User']['is_admin'].' value<br>';
			}
			$createDate = strtotime($entry['User']['created']);

			if($createDate < $initDate)
				$ret .= '	'.$entry['User']['id'].':	Incorrect registartion date: '.date('d-m-Y',$createDate).', but need to be less than '.date('d-m-Y',$initDate).' <br>';

		}


		$ret .= 'done<br><br>';

		// --------------------------------------------------------------
		// ----------------------------------------- CHAT ROOM USER CHECK
		// --------------------------------------------------------------

		$fulldata = $this->ChatMember->find('all');
		$ret .= '<hr>Checking ChatMember<br>';

		foreach( $fulldata as $entry )
		{
			$recId = $entry['ChatMember']['id'];
			if (!in_array(($entry['ChatMember']['user_id']), array_keys($aUsers)))
			{
				$ret .= '	'.$recId.':	user with "user_id = '.$entry['ChatMember']['user_id'].'" does not exist<br>';
			}
			if (!in_array(($entry['ChatMember']['room_id']), array_keys($aRooms)))
			{
				$ret .= '	'.$recId.':	room with "room_id = '.$entry['ChatMember']['room_id'].'" does not exist<br>';
			}
		}

		$ret .= 'done<br><br>';

		// ------------------------------------------------------------------------
		// ----------------------------------------------------------- ChatContact CHECK
		// ------------------------------------------------------------------------

		$fulldata = $this->ChatContact->find('all');
		$ret .= '<hr>Checking ChatContact<br>';

		foreach( $fulldata as $entry )
		{
			$recId = $entry['ChatContact']['id'];
			if (!in_array(($entry['ChatContact']['user_id']), array_keys($aUsers)))
			{
				$ret .= '	'.$recId.':	user with "user_id = '.$entry['ChatContact']['user_id'].'" does not exist<br>';
			}
			if (!in_array(($entry['ChatContact']['initiator_id']), array_keys($aUsers)))
			{
				$ret .= '	'.$recId.':	user (initiator) with "initiator_id = '.$entry['ChatContact']['initiator_id'].'" does not exist<br>';
			}
		}

		$ret .= 'done<br><br>';

		// --------------------------------------------------------------------------
		// ----------------------------------------------------------- ChatRoom CHECK
		// --------------------------------------------------------------------------

		$fulldata = $this->ChatRoom->find('all');
		$ret .= '<hr>Checking ChatRoom<br>';

		foreach( $fulldata as $entry )
		{
			$recId = $entry['ChatRoom']['id'];
			if (!in_array(($entry['ChatRoom']['initiator_id']), array_keys($aUsers)))
			{
				$ret .= '	'.$recId.':	user (initiator) with "initiator_id = '.$entry['ChatRoom']['initiator_id'].'" does not exist<br>';
			}
			if (!in_array(($entry['ChatRoom']['recipient_id']), array_keys($aUsers)))
			{
				$ret .= '	'.$recId.':	user (recipient) with "recipient_id = '.$entry['ChatRoom']['recipient_id'].'" does not exist<br>';
			}
		}

		$ret .= 'done<br><br>';


		// ----------------------------------------------------------
		// ----------------------------------------- CHAT EVENT CHECK
		// ----------------------------------------------------------

		$fulldata = $this->ChatEvent->find('all');
		$ret .= '<hr>Checking ChatEvent<br>';

		foreach( $fulldata as $entry )
		{
			$id = $entry['ChatEvent']['user_id'];
			$event = $entry['ChatEvent']['event_type'];
			$recId = $entry['ChatEvent']['id'];
			if (!in_array(($entry['ChatEvent']['event_type']), array_keys($aUsers)))
			{
				$ret .= '	'.$recId.':	user with "user_id = '.$id.'" does not exist<br>';
			}
			$id = $entry['ChatEvent']['initiator_id'];
			if (!in_array(($entry['ChatEvent']['initiator_id']), array_keys($aUsers)))
			{
				$ret .= '	'.$recId.':	initiator user with "initiator_id = '.$id.'" does not exist<br>';
			}
			if($event == 1 || $event == 2 || $event == 4)
			{
				$id = $entry['ChatEvent']['msg_id'];
				if (!in_array(($entry['ChatEvent']['msg_id']), array_keys($aMessages)))
				{
					$ret .= '	'.$recId.':	message with "msg_id = '.$id.'" does not exist<br>';
				}
			}
			if($event == 3 || $event == 4)
			{
				$id = $entry['ChatEvent']['recipient_id'];
				if (!$this->User->findById($id))
				{
					$ret .= '	'.$recId.':	recipient user with "recipient_id = '.$id.'" does not exist<br>';
				}
			}
			if($event == 6 || $event == 7)
			{
				$id = $entry['ChatEvent']['file_id'];
				if (!$this->Media->findById($id))
				{
					$ret .= '	'.$recId.':	file with "file_id = '.$id.'" does not exist<br>';
				}
			}
		}

		$ret .= 'done<br><br>';

		// ------------------------------------------------------------------------
		// ----------------------------------------------------------- GROUPS CHECK
		// ------------------------------------------------------------------------

		$fulldata = $this->Group->find('all');
		$ret .= '<hr>Checking Groups<br>';

		foreach( $fulldata as $entry )
		{
			$recId = $entry['Group']['id'];
			if (!in_array(($entry['Group']['owner_id']), array_keys($aUsers)))
			{
				$ret .= '	'.$recId.':	user (owner) with "owner_id = '.$entry['Group']['owner_id'].'" does not exist<br>';
			}
		}

		$ret .= 'done<br><br>';

		// ------------------------------------------------------------------------
		// ----------------------------------------------------------- GroupVideo CHECK
		// ------------------------------------------------------------------------

		$fulldata = $this->GroupVideo->find('all');
		$ret .= '<hr>Checking GroupVideos<br>';

		foreach( $fulldata as $entry )
		{
			$id = $entry['GroupVideo']['group_id'];
			$recId = $entry['GroupVideo']['id'];
			if (!$this->Group->findById($id))
			{
				$ret .= '	'.$recId.':	group with "group_id = '.$id.'" does not exist<br>';
			}
		}

		$ret .= 'done<br><br>';

		// ------------------------------------------------------------------------
		// ----------------------------------------------------------- GroupAddress CHECK
		// ------------------------------------------------------------------------

		$fulldata = $this->GroupAddress->find('all');
		$ret .= '<hr>Checking GroupAddress<br>';

		foreach( $fulldata as $entry )
		{
			$id = $entry['GroupAddress']['group_id'];
			$recId = $entry['GroupAddress']['id'];
			if (!$this->Group->findById($id))
			{
				$ret .= '	'.$recId.':	group with "group_id = '.$id.'" does not exist<br>';
			}
		}

		$ret .= 'done<br><br>';

		// ------------------------------------------------------------------------
		// ----------------------------------------------------------- GroupAchievement CHECK
		// ------------------------------------------------------------------------

		$fulldata = $this->GroupAchievement->find('all');
		$ret .= '<hr>Checking GroupAchievement<br>';

		foreach( $fulldata as $entry )
		{
			$id = $entry['GroupAchievement']['group_id'];
			$recId = $entry['GroupAchievement']['id'];
			if (!$this->Group->findById($id))
			{
				$ret .= '	'.$recId.':	group with "group_id = '.$id.'" does not exist<br>';
			}
		}

		$ret .= 'done<br><br>';

		// ------------------------------------------------------------------------
		// ----------------------------------------------------------- Order CHECK
		// ------------------------------------------------------------------------

		$fulldata = $this->Order->find('all');
		$ret .= '<hr>Checking Order<br>';

		foreach( $fulldata as $entry )
		{
			$id = $entry['Order']['contractor_id'];
			$recId = $entry['Order']['id'];
			if (!$this->Contractor->findById($id))
			{
				$ret .= '	'.$recId.':	contractor with "contractor_id = '.$id.'" does not exist<br>';
			}
		}

		$ret .= 'done<br><br>';

		// ------------------------------------------------------------------------
		// ----------------------------------------------------------- Product CHECK
		// ------------------------------------------------------------------------

		$fulldata = $this->Product->find('all');
		$ret .= '<hr>Checking Product<br>';

		foreach( $fulldata as $entry )
		{
			$id = $entry['Product']['product_type_id'];
			$recId = $entry['Product']['id'];
			if (!$this->ProductType->findById($id))
			{
				$ret .= '	'.$recId.':	product type with "product_type_id = '.$id.'" does not exist<br>';
			}
		}

		$ret .= 'done<br><br>';

		// ------------------------------------------------------------------------
		// ----------------------------------------------------------- OrderProduct CHECK
		// ------------------------------------------------------------------------

		$fulldata = $this->OrderProduct->find('all');
		$ret .= '<hr>Checking OrderProduct<br>';

		foreach( $fulldata as $entry )
		{
			$id = $entry['OrderProduct']['order_id'];
			$recId = $entry['OrderProduct']['id'];
			if (!$this->Order->findById($id))
			{
				$ret .= '	'.$recId.':	order with "order_id = '.$id.'" does not exist<br>';
			}
			$id = $entry['OrderProduct']['product_id'];
			if (!$this->Product->findById($id))
			{
				$ret .= '	'.$recId.':	product with "product_id = '.$id.'" does not exist<br>';
			}
		}

		$ret .= 'done<br><br>';

		// ------------------------------------------------------------------------
		// ----------------------------------------------------------- OrderType CHECK
		// ------------------------------------------------------------------------

		$fulldata = $this->OrderType->find('all');
		$ret .= '<hr>Checking OrderType<br>';

		foreach( $fulldata as $entry )
		{
			$id = $entry['OrderType']['order_id'];
			$recId = $entry['OrderType']['id'];
			if (!$this->Order->findById($id))
			{
				$ret .= '	'.$recId.':	order with "order_id = '.$id.'" does not exist<br>';
			}
			$id = $entry['OrderType']['product_type_id'];
			if (!$this->ProductType->findById($id))
			{
				$ret .= '	'.$recId.':	product type with "product_type_id = '.$id.'" does not exist<br>';
			}
		}

		$ret .= 'done<br><br>';

		// ------------------------------------------------------------------------
		// ----------------------------------------------------------- Product CHECK
		// ------------------------------------------------------------------------

		$fulldata = $this->Product->find('all');
		$ret .= '<hr>Checking Product<br>';

		foreach( $fulldata as $entry )
		{
			$id = $entry['Product']['product_type_id'];
			$recId = $entry['Product']['id'];
			if (!$this->ProductType->findById($id))
			{
				$ret .= '	'.$recId.':	product type with "product_type_id = '.$id.'" does not exist<br>';
			}
		}

		$ret .= 'done<br><br>';

		// -------------------------------------------------------------------------
		// ----------------------------------------------------------- Project CHECK
		// -------------------------------------------------------------------------

		$fulldata = $this->Project->find('all');
		$ret .= '<hr>Checking Project<br>';

		foreach( $fulldata as $entry )
		{
			$id = $entry['Project']['group_id'];
			$recId = $entry['Project']['id'];
			if (!$this->Group->findById($id))
			{
				$ret .= '	'.$recId.':	group with "group_id = '.$id.'" does not exist<br>';
			}
			if (!in_array(($entry['Project']['owner_id']), array_keys($aUsers)))
			{
				$ret .= '	'.$recId.':	owner (user) with "owner_id = '.$entry['Project']['owner_id'].'" does not exist<br>';
			}
		}

		$ret .= 'done<br><br>';

		// ------------------------------------------------------------------------
		// ----------------------------------------------------------- ProjectEvent CHECK
		// ------------------------------------------------------------------------

		$fulldata = $this->ProjectEvent->find('all');
		$ret .= '<hr>Checking ProjectEvent<br>';

		foreach( $fulldata as $entry )
		{
			$recId = $entry['ProjectEvent']['id'];
			if (!in_array(($entry['ProjectEvent']['user_id']), array_keys($aUsers)))
			{
				$ret .= '	'.$recId.':	owner (user) with "owner_id = '.$entry['ProjectEvent']['user_id'].'" does not exist<br>';
			}
			$id = $entry['ProjectEvent']['project_id'];
			if (!$this->Project->findById($id))
			{
				$ret .= '	'.$recId.':	project with "project_id = '.$id.'" does not exist<br>';
			}
		}

		$ret .= 'done<br><br>';

		// ------------------------------------------------------------------------
		// ----------------------------------------------------------- ProjectEvent CHECK
		// ------------------------------------------------------------------------

		$fulldata = $this->ProjectEvent->find('all');
		$ret .= '<hr>Checking ProjectEvent<br>';

		foreach( $fulldata as $entry )
		{
			$id = $entry['ProjectEvent']['project_id'];
			$recId = $entry['ProjectEvent']['id'];
			if (!$this->Project->findById($id))
			{
				$ret .= '	'.$recId.':	project with "project_id = '.$id.'" does not exist<br>';
			}
		}

		$ret .= 'done<br><br>';

		// ------------------------------------------------------------------------
		// ----------------------------------------------------------- ProjectMember CHECK
		// ------------------------------------------------------------------------

		$fulldata = $this->ProjectMember->find('all');
		$ret .= '<hr>Checking ProjectMember<br>';

		foreach( $fulldata as $entry )
		{
			$id = $entry['ProjectMember']['project_id'];
			$recId = $entry['ProjectMember']['id'];
			if (!$this->Project->findById($id))
			{
				$ret .= '	'.$recId.':	project with "project_id = '.$id.'" does not exist<br>';
			}
			if (!in_array(($entry['ProjectMember']['user_id']), array_keys($aUsers)))
			{
				$ret .= '	'.$recId.':	project with "project_id = '.$entry['ProjectMember']['user_id'].'" does not exist<br>';
			}
		}

		$ret .= 'done<br><br>';

		// ------------------------------------------------------------------------
		// ----------------------------------------------------------- Subproject CHECK
		// ------------------------------------------------------------------------

		$fulldata = $this->Subproject->find('all');
		$ret .= '<hr>Checking Subproject<br>';

		foreach( $fulldata as $entry )
		{
			$id = $entry['Subproject']['project_id'];
			$recId = $entry['Subproject']['id'];
			if (!$this->Project->findById($id))
			{
				$ret .= '	'.$recId.':	project with "project_id = '.$id.'" does not exist<br>';
			}
		}

		$ret .= 'done<br><br>';

		// ----------------------------------------------------------------------------
		// ----------------------------------------------------------- Task CHECK
		// ----------------------------------------------------------------------------

		$fulldata = $this->Task->find('all');
		$ret .= '<hr>Checking Task<br>';

		foreach( $fulldata as $entry )
		{
			$id = $entry['Task']['subproject_id'];
			$recId = $entry['Task']['id'];
			if (!$this->Subproject->findById($id))
			{
				$ret .= '	'.$recId.':	project with "project_id = '.$id.'" does not exist<br>';
			}
			if (!in_array(($entry['Task']['manager_id']), array_keys($aUsers)))
			{
				$ret .= '	'.$recId.':	user (manager) with "manager_id = '.$entry['Task']['manager_id'].'" does not exist<br>';
			}
			if (!in_array(($entry['Task']['creator_id']), array_keys($aUsers)))
			{
				$ret .= '	'.$recId.':	user (creator) with "creator_id = '.$entry['Task']['creator_id'].'" does not exist<br>';
			}
			if (!in_array(($entry['Task']['user_id']), array_keys($aUsers)))
			{
				$ret .= '	'.$recId.':	user with "user_id = '.$entry['Task']['user_id'].'" does not exist<br>';
			}
		}

		$ret .= 'done<br><br>';

		// ----------------------------------------------------------------------------
		// ----------------------------------------------------------- UserAchievement CHECK
		// ----------------------------------------------------------------------------

		$fulldata = $this->UserAchievement->find('all');
		$ret .= '<hr>Checking UserAchievement<br>';

		foreach( $fulldata as $entry )
		{
			$recId = $entry['UserAchievement']['id'];
			if (!in_array(($entry['UserAchievement']['user_id']), array_keys($aUsers)))
			{
				$ret .= '	'.$recId.':	user with "user_id = '.$entry['UserAchievement']['user_id'].'" does not exist<br>';
			}
		}

		$ret .= 'done<br><br>';

		// ----------------------------------------------------------------------------
		// ----------------------------------------------------------- UserEvent CHECK
		// ----------------------------------------------------------------------------

		$fulldata = $this->UserEvent->find('all');
		$ret .= '<hr>Checking UserEvent<br>';

		foreach( $fulldata as $entry )
		{
			$recId = $entry['UserEvent']['id'];
			if (!in_array(($entry['UserEvent']['user_id']), array_keys($aUsers)))
			{
				$ret .= '	'.$recId.':	user with "user_id = '.$entry['UserEvent']['user_id'].'" does not exist<br>';
			}
		}

		$ret .= 'done<br><br>';

		Debugger::dump($ret);
	}

}


?>
