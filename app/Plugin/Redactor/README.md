# Redactor WYSIWYG plugin for CakePHP 

**Introduction**

Got so tired of all the extensive integrations of WYSIWYG plugins and helpers for CakePHP with no regard to what is commonly percieved as good file uploading and management. 
I decided to quickly put something together that actually creates a media library based on the file uploads done through the WYSIWYG. 

After much research and testing through a few years I decided to use Redactor, being the most scaled back and modern. It also has alot of focus on security and is Amazon S3 compatible. 

This is a simple startup plugin for CakePHP integration with [Redactor](http://imperavi.com/redactor/) into [CakePHP](http://cakephp.org) as a light weight media library. 
It is intended to get you started with using Redactor and file management in CakePHP, not to take over the entire WYSIWYG experience and compensate for different unnecessary things.

This is useful when you are building custom stuff.

**Requirements**

* CakePHP 2.x (built with 2.5.4)
* PHP GD-1.8+
* Redactor 8+

## Changelog

**v0.2.1**
- Added readme text, and some init tweaks

**v0.2.0**
- Init

## Installation

Clone repo or download the files and add them to the folder Plugin/CakeRedactor

Go buy [Redactor](http://imperavi.com/redactor/) WYSIWYG Editor by Imperavi and download the files.

Add the files from the Redactor download to:

```php
CakeRedactor/webroot/redactor/redactor.css
CakeRedactor/webroot/redactor/redactor.js
CakeRedactor/webroot/redactor/redactor.min.js
CakeRedactor/webroot/redactor/redactor-font.eot
CakeRedactor/webroot/redactor/redactor-iframe.css
```

You might need to remove the following rows in the redactor.js and re-minify it. 

```js
// php code fix
html = html.replace('<!--?php', '<?php');
html = html.replace('?-->', '?>');

// php tags convertation
if (this.opts.phpTags) html = html.replace(/<section style="display: none;" rel="redactor-php-tag">([\w\W]*?)<\/section>/gi, '<?php\r\n$1\r\n?>');
```

Create the table 'mediafiles' in database: 
```sql
CREATE TABLE IF NOT EXISTS `mediafiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `mediatype` varchar(10) DEFAULT NULL,
  `tmp_name` varchar(255) DEFAULT NULL,
  `error` int(11) NOT NULL,
  `size` varchar(255) DEFAULT NULL,
  `filelink` varchar(255) DEFAULT NULL,
  `thumblink` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=33 ;

```

Add the following line to Config/bootstrap.php to load the plugin
```php
CakePlugin::load('CakeRedactor');
```

Add the following lines to Config/routes.php to make the imageGetJson operation for Redactor 'click' with CakePHP
```php
Router::setExtensions(array('json'));
Router::parseExtensions();
```

## Usage

Make sure your desired Controller uses the Helper or set it globally in your AppController. 

```php
public $helpers = array(
	'CakeRedactor.CakeRedactor'
);
```

Use the helper to create the form instead of the built in FormHelper. The helper will automatically load the required scripts to the scripts block. You can ofcourse move these to another location to suit your needs. 
```php
echo $this->CakeRedactor->redactor('content');
```

Customize the init.js file after your needs 
```js
$(function() {
	$('.redactor_box').redactor({
		focus: true,
		phpTags: false,
		imageUpload: '/cake_redactor/mediafiles/upload/image',
		fileUpload: '/cake_redactor/mediafiles/upload/file',
		imageGetJson: '/cake_redactor/mediafiles/getmediaimages.json',
		minHeight: 200
		/* buttons: ['html', '|', 'formatting', '|', 'bold', 'italic', 'deleted', '|', 
                'unorderedlist', 'orderedlist', 'outdent', 'indent', '|',
                'image', 'link', '|',
                'fontcolor', 'backcolor', '|', 'alignment', '|', 'horizontalrule'] */
	});
});
```

Modify your needs to the MediafilesController. Example:
```php
public function upload() { >> public function admin_upload() {
```

```php
$_FILES['file']['type'] == 'application/zip' // mime of your choosing
```

## Good luck! 

If you have any questions or want to give feedback. You can find me on the freenode network in the channels #croogo and #cakephp.
I will continue to update this plugin with each project I use it for. My intention is to leave it as independant as possible.
