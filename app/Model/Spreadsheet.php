<?
App::uses('AppModel', 'Model');
class Spreadsheet extends AppModel {
	public $useTable = 'sheet_sheet';
    public $primaryKey = 'sheetid';
	public $belongsTo = 'User';
	
}	