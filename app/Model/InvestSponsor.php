<?php

/**
 * Class InvestReward
 */
class InvestSponsor extends AppModel {

	public $useTable = 'invest_sponsor';

	public $belongsTo = array(
		'InvestProject' => array(
			'className' => 'InvestProject',
			'foreignKey' => 'project_id',
			//cache total sponsors count
			'counterCache' => 'funders_total',
			'counterScope' => array(
				'InvestSponsor.canceled' => 0
			),
			//cache sum per project totally
			'sumCache' => 'funded_total',
			'sumField' => 'amount',
			'sumScope' => array('InvestSponsor.canceled' => 0),
		),
		'InvestReward' => array(
			'className' => 'InvestReward',
			'foreignKey' => 'reward_id',
			//cache only reward sponsors count
			'counterCache' => 'funders',
			'counterScope' => array(
				'InvestSponsor.canceled' => 0
			),
			//cache sum per reward
			'sumCache' => 'funded',
			'sumField' => 'amount',
			'sumScope' => array('InvestSponsor.canceled' => 0),
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		),
	);


}
