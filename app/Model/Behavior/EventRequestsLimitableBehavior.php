<?php

App::uses('AppModel', 'Model');

class EventRequestsLimitableBehavior extends ModelBehavior {

	public function checkUsedRequests(Model $model, $userId){
		App::uses('UserEventRequestLimit', 'Model');
		$userEventRequestLimitModel = new UserEventRequestLimit();
		$counts = $userEventRequestLimitModel->findByUserId($userId);
		if ($counts['UserEventRequestLimit']['requests_used'] < $counts['UserEventRequestLimit']['requests_limit']){
			return true;
		}
		return false;
	}

	public function countUsedRequests(Model $model, $userId){
		//find subscription expire date
		//TODO: potentially we have risk to reset counter after each proposals increment
		App::uses('BillingSubscription', 'Billing.Model');
		$billingSubscription = new BillingSubscription();
		$billingSubscription->Behaviors->load('Containable');
		$subscription = $billingSubscription->find('first', array(
			'contain' => array('BillingGroup'),
			'conditions' => array(
				'BillingSubscription.user_id' => $userId,
				'BillingGroup.limit_units LIKE' => 'proposals'
			),
			'callbacks' => false
		));
		$periodEndDate = date('Y-m-d H:i:s');
		$periodStartDate = date('Y-m-d H:i:s', strtotime("-1 month"));
		if(!empty($subscription)){
			$periodEndDate = $subscription['BillingSubscription']['expires'];
			$periodStartDate = date('Y-m-d H:i:s', strtotime($periodEndDate) - (time() - strtotime("-3 month")));
		}

		App::uses('UserEventRequest', 'Model');
		$userEventRequestModel = new UserEventRequest();
		$requestsCount = $userEventRequestModel->find('count', array(
            'conditions' => array(
                'UserEventRequest.user_id' => $userId,
				array('UserEventRequest.created >=' => $periodStartDate),
				array('UserEventRequest.created <=' => $periodEndDate),
            ),
			'recursive' => -1
        ));
		$requestsCount = $requestsCount - 5;

		App::uses('UserEventRequestLimit', 'Model');
        $userEventRequestLimitModel = new UserEventRequestLimit();
        $limit = $userEventRequestLimitModel->findByUserId($userId);
        if(!empty($limit)){
            $userEventRequestLimitModel->id = $limit['UserEventRequestLimit']['id'];
            $userEventRequestLimitModel->saveField('requests_used', $requestsCount, array(
                'validate' => false,
                'callbacks' => false,
                'counterCache' => false,
            ));
        }
		return $requestsCount;
	}
}
