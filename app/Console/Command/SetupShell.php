<?php

App::uses('File', 'Utility');
App::uses('Folder', 'Utility');
App::uses('ConnectionManager', 'Model');

class SetupShell extends AppShell {

	/**
	 * Connection used
	 *
	 * @var string
	 */
	public $connection = 'default';

	/**
	 * Type of migration, can be 'app' or a plugin name
	 *
	 * @var string
	 */
	public $type = 'app';

	public function startup() {
		Configure::write('debug', 2);

		$this->out(__d('cake_console', '<warning>Konstruktor Setup Shell</warning>'));
		$this->hr(0, 100);

		if (!config('database')) {
			$this->out('<warning>'.__d('cake_console', 'Your database configuration was not found in .env files.').'</warning>');
		}

		if (!empty($this->params['connection'])) {
			$this->connection = $this->params['connection'];
		}

		if (!empty($this->params['plugin'])) {
			$this->type = $this->params['plugin'];
		}
	}

	/**
	 * Display help/options
	 */
	public function getOptionParser() {
		$parser = parent::getOptionParser();
		$parser->description(__d('cake_console', 'Cake Setup Shell')
			)->addOption('plugin', array(
				'short' => 'p',
				'help' => __d('cake_console', 'Plugin name to be used')
			))->addOption('connection', array(
				'short' => 'c',
				'default' => 'default',
				'help' => __d('cake_console', 'Set db config <config>. Uses \'default\' if none is specified.')
			))->addSubcommand('update', array(
				'help' => __d('cake_console', 'Update actions for Application or Plugin'),
			))
		;
		return $parser;
	}

	public function update(){
		if ($this->type == 'app'){
			$plugins = CakePlugin::loaded();
		} else {
			$plugins[] = $this->type;
		}

		$this->out(__d('cake_console', '<info>-- Migration status in app --</info>'));
		$this->dispatchShell('Migrations.migration status -i '.$this->connection);
		$this->hr(1, 100);

		if ($this->type == 'app'){
			$this->out(__d('cake_console', '<info>-- Application core database migrations --</info>'));
			$this->dispatchShell('Migrations.migration run all -i '.$this->connection);
			$this->out(__d('cake_console', '<info>- Application core database updated</info>'));
			$this->hr(1, 100);
		}

		foreach($plugins as $plugin){
			$plugin_migration_folder = new Folder(CakePlugin::path($plugin).'Config'.DS.'Migration');
			list($m_folders, $m_files) = $plugin_migration_folder->read(true, array('empty'));
			if(count($m_files)){
				$this->out(__d('cake_console', '<info>-- %s plugin database migrations</info>', $plugin));
				$this->dispatchShell('migration run all --plugin '.$plugin.' -i '.$this->connection);
				$this->out(__d('cake_console', '<info>- %s plugin database updated</info>', $plugin));
				$this->hr(1, 100);
			}
		}
		$this->out(__d('cake_console', '<info>-- Build static assets --</info>'));
		$this->dispatchShell('AssetCompress.AssetCompress build -f');
		$this->hr(1, 100);
		$this->out(__d('cake_console', '<warning>All done</warning>'));
	}
}
