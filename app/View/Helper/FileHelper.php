<?php
App::uses('AppHelper', 'View/Helper');

class FileHelper extends AppHelper {
	/**
	 * List of supported types of files
	 * @var array
	 */
	public $supportedTypes = array('txt', 'doc', 'rtf', 'log', 'tex', 'msg', 'text', 'wpd', 'wps', 'docx', 'page', 'csv', 'dat', 'tar', 'xml', 'vcf', 'pps', 'key', 'ppt', 'pptx', 'sdf', 'gbr', 'ged', 'mp3', 'm4a', 'waw', 'wma', 'mpa', 'iff', 'aif', 'ra', 'mid', 'm3v', 'e_3gp', 'shf', 'avi', 'asx', 'mp4', 'webm', 'ogg', 'e_3g2', 'mpg', 'asf', 'vob', 'wmv', 'mov', 'srt', 'm4v', 'flv', 'rm', 'png', 'psd', 'psp', 'jpg', 'tif', 'tiff', 'gif', 'bmp', 'tga', 'thm', 'yuv', 'dds', 'ai', 'eps', 'ps', 'svg', 'pdf', 'pct', 'indd', 'xlr', 'xls', 'xlsx', 'db', 'dbf', 'mdb', 'pdb', 'sql', 'aacd', 'app', 'exe', 'com', 'bat', 'apk', 'jar', 'hsf', 'pif', 'vb', 'cgi', 'css', 'js', 'php', 'xhtml', 'htm', 'html', 'asp', 'cer', 'jsp', 'cfm', 'aspx', 'rss', 'csr', 'less', 'otf', 'ttf', 'font', 'fnt', 'eot', 'woff', 'zip', 'zipx', 'rar', 'targ', 'sitx', 'deb', 'e_7z', 'pkg', 'rpm', 'cbr', 'gz', 'dmg', 'cue', 'bin', 'iso', 'hdf', 'vcd', 'bak', 'tmp', 'ics', 'msi', 'cfg', 'ini', 'prf');

	/**
	 * Format to human file size
	 * @param $bytes
	 * @param int $decimals
	 * @return string format to human file size
	 */
	public function humanFilesize($bytes, $decimals = 2) {
		$sz = array(' B', ' KB', ' MB', ' GB', ' TB', ' PB');
		$factor = floor((strlen($bytes) - 1) / 3);
		return str_replace('.', ',', sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor]);
	}

	/**
	 * Check if contains in supported types of files
	 * @param $extension
	 * @return bool
	 */
	public function hasType($extension) {
		return in_array($extension, $this->supportedTypes);
	}

	public function isImage($type) {
		return in_array($type, array('jpg', 'jpeg', 'gif', 'png'));
	}

	public function isVideo($type) {
		return in_array($type, array('mp4', 'webm', 'ogg', 'avi', 'mov', 'qt', 'wmv', 'mkv', 'm4v'));
	}
}
