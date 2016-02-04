<?php
App::uses('ModelBehavior', 'Model');

class LimitableBehavior extends ModelBehavior {

	protected $_defaultConfig = [
		'limitField' => 'limit_value',
		'remoteModel' => null, // in cake format; for plugin model use like "Plugin.Model"
		'remoteField' => null,
		'scope' => 'user_id',
	];

	protected $ownersWhitelist = array(
		67, //Vlad Krishtop
		71, //Yaroslav Eroshenko
	);

	public function setup(Model $Model, $config = []) {
		$config += $this->_defaultConfig;
		$this->settings[$Model->alias] = $config;
	}

	public function beforeSave(Model $Model, $options = []) {
		$limitField = $this->settings[$Model->alias]['limitField'];
		if(!isset($Model->data[$Model->alias][$limitField])){
			App::import('Model', 'Billing.BillingPlan');
			$billingPlanModel = new BillingPlan();
			$limitValue = $billingPlanModel->field(
				$limitField,
				array('id' => $Model->data[$Model->alias]['plan_id'])
			);
			$Model->data[$Model->alias][$limitField] = $limitValue;
		}
	}

	public function afterSave(Model $Model, $created, $options = []) {
		if(empty($this->settings[$Model->alias]['remoteModel'])
			||empty($this->settings[$Model->alias]['remoteField'])){
			//wrong configured behavior. exception needed
			return true;
		}
		$this->setLimit($Model);
		return true;
	}

	public function setLimit(Model $Model, $data = array()){
		if(!empty($data)){
			$Model->set($data);
		}
		if(empty($Model->data[$Model->alias])){
			return false;
		}
		$limitField = $this->settings[$Model->alias]['limitField'];

		if(!isset($Model->data[$Model->alias][$limitField])){
			$billingPlanModel = new BillingPlan();
			$limitValue = $billingPlanModel->field(
				$limitField,
				array('id' => $Model->data[$Model->alias]['plan_id'])
			);
			$Model->data[$Model->alias][$limitField] = $limitValue;
		}

		$scope = $this->settings[$Model->alias]['scope'];
		$remoteModel = $this->settings[$Model->alias]['remoteModel'];
		App::import('Model', $remoteModel);
		$remoteModel = new $remoteModel();

		if(in_array($Model->data[$Model->alias]['user_id'], $this->ownersWhitelist)){
			$Model->data[$Model->alias][$limitField] = 199999999999;
		}
		$result = $remoteModel->updateAll(
			array($remoteModel->alias.'.'.$this->settings[$Model->alias]['remoteField'] => $Model->data[$Model->alias][$limitField]),
			array($remoteModel->alias.'.'.$scope => $Model->data[$Model->alias]['user_id'])
		);
		if($result){
			//hardcoded group members manipulation
			if($remoteModel == 'GroupLimit'){
				$this->membersOperate($Model);
			}
			return true;
		}
		return false;
	}

	public function membersOperate(Model $Model, $ownerId = null){
		if(isset($Model->data[$Model->alias]['user_id'])&&!empty($Model->data[$Model->alias]['user_id'])){
			$ownerId = $Model->data[$Model->alias]['user_id'];
		}
		if(empty($ownerId)){
			return false;
		}
		$remoteModel = $this->settings[$Model->alias]['remoteModel'];

		App::import('Model', $remoteModel);
		$remoteModel = new $remoteModel();
		$limits = $remoteModel->findByOwnerId($ownerId);

		App::import('Model', 'Group');
		App::import('Model', 'GroupMember');
		$groupModel = new Group();
		$groupMemberModel = new GroupMember();
		$groupModel->recursive = -1;
		$groups = $groupModel->findAllByOwnerId($ownerId);
		if(($limits['GroupLimit']['members_limit'] == 0)&&($limits['GroupLimit']['members_used'] > 0)){
			//verify all groups for limit overload
			foreach($groups as $group){
				$groupMemberModel->recursive = -1;
				$groupMembers = $groupMemberModel->find('all', array(
					'conditions' => array(
						'GroupMember.group_id' => $group['Group']['id'],
						'GroupMember.approved' => true,
						'GroupMember.is_deleted' => false,
					),
					'order' => array('GroupMember.approve_date' => 'ASC')
				));
				$groupMembers = array_slice($groupMembers, 6);
				if(count($groupMembers) > 0){
					foreach($groupMembers as $key=>$member){
						$groupMembers[$key]['GroupMember'] = Hash::merge(
							$member['GroupMember'],
							array('approved' => false, 'is_invited' => false, 'approve_date' => '0000-00-00 00:00:00')
						);
	 				}
					$groupMemberModel->saveMany($groupMembers, array('callbacks' => true, 'counterCache' => true));
				}
			}
		} elseif($limits['GroupLimit']['members_limit'] < $limits['GroupLimit']['members_used']){
			//we need to decrease group members by some algorythm
		}
		//debug($groups);

	}

}
