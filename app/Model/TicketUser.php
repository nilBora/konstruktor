<?php
App::uses('AppModel', 'Model');
class TicketUser extends AppModel {
	public $useTable = 'users';
	public $primaryKey = 'ID';
	
	public $usedbConfig = 'tickets';
}