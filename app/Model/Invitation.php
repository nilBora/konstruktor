<?php
App::uses('AppModel', 'Model');

class Invitation extends AppModel {

    public $useTable = 'invitations';

    const USER_EVENT = 1;

    //The Associations below have been created with all possible keys, those that are not needed can be removed

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'UserEvent' => array(
            'className' => 'UserEvent',
            'foreignKey' => 'object_id',
            'conditions' => array('Invitation.object_type' => Invitation::USER_EVENT),
        ),
    );
}