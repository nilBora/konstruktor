<?php
class RedactorHelper extends AppHelper{

	//dependancies
	public $helpers = array('Html','Form');

	//load
	public function redactor($field, $options = array()) {
		$scripts = array(
			'redactor/redactor', // main editor script
			'redactor/plugins/table/table',
			'redactor/plugins/video/video',
			'redactor/init'
		);
		$this->Html->script($scripts, array('inline' => false));
		$this->Html->css('/js/redactor/redactor', null, array('inline' => false));

		return $this->textarea($field, 'redactor', $options);

	}

	//input type
	public function textarea($field, $editor = false, $options = array()){
		$options = array_merge(array(
			'label' => false,
			'type' => 'textarea',
			'class' => "redactor_box $editor"),
			$options
		);

		$html = $this->Form->input($field, $options);

		return $html;
	}

	public function tiny($field, $options = array()) {
		$scripts = array(
			'tinymce.min.js',
			'theme.min.js',
			'tinyPlugins/advlist/plugin.min.js',
			'tinyPlugins/autolink/plugin.min.js',
			'tinyPlugins/lists/plugin.min.js',
			'tinyPlugins/link/plugin.min.js',
			'tinyPlugins/image/plugin.min.js',
			'tinyPlugins/charmap/plugin.min.js',
			'tinyPlugins/print/plugin.min.js',
			'tinyPlugins/cloudfilemanager/plugin.js',
			'tinyPlugins/preview/plugin.min.js',
			'tinyPlugins/responsivefilemanager/plugin.min.js',
			'tinyPlugins/anchor/plugin.min.js',
			'tinyPlugins/searchreplace/plugin.min.js',
			'tinyPlugins/visualblocks/plugin.min.js',
			'tinyPlugins/code/plugin.min.js',
			'tinyPlugins/fullscreen/plugin.min.js',
			'tinyPlugins/insertdatetime/plugin.min.js',
			'tinyPlugins/media/plugin.js',
			'tinyPlugins/table/plugin.min.js',
			'tinyPlugins/contextmenu/plugin.min.js',
			'tinyPlugins/paste/plugin.min.js',
			'tinyPlugins/code/plugin.min.js',
			'tiny-init.js'
		);
		$css = array(
			'../skins_tiny/lightgray/content.min.css',
			'../skins_tiny/lightgray/skin.min.css',
			'../skins_tiny/lightgray/content.inline.min.css'
		);
		$this->Html->script($scripts, array('inline' => false));
		$this->Html->css($css, array('inline' => false));

		return $this->textarea($field, 'tiny-mce', $options);

	}

}
