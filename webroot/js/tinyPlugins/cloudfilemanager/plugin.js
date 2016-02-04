/**
 * plugin.js
 *
 * Released under LGPL License.
 * Copyright (c) 1999-2015 Ephox Corp. All rights reserved
 *
 * License: http://www.tinymce.com/license
 * Contributing: http://www.tinymce.com/contributing
 */

/*jshint unused:false */
/*global tinymce:true */

/**
 * Example plugin that adds a toolbar button and menu item.
 */
tinymce.PluginManager.add('cloudfilemanager', function(editor, url) {
	// Add a button that opens a window
	editor.addButton('cloudfilemanager', {
		text: 'Cloud',
		icon: 'folder',
		onclick: function() {
			// Open window
			editor.windowManager.open({
				title: 'Мои файлы',
				width: 600,
				height: 400,
				file : '/Cloud/fortiny',
				buttons: [
					{
						text: 'Insert',
						onclick: function() {
							// Top most window object
							var win = editor.windowManager.getWindows()[0];
							var winElement = win.getContentWindow().document.querySelector('#cloud-manager-list .item.active');

							if(winElement){
								var winElementMedia = winElement.getAttribute('data-media');
								var winElementType  = winElement.getAttribute('data-type');
								var winElementUrl   = winElement.getAttribute('data-url');
								if(winElementType == 'file' && winElementMedia != 'false' ){
									editor.insertContent('<img src="' + winElementMedia + '"/>');
									win.close();
								}else if(winElementType=='file' && winElement.getAttribute('data-video') == 'mp4'){
									editor.insertContent('<video src="' + winElement.getAttribute('data-url-down') + '" controls preload></video>');
									win.close();
								}else if(winElementType=='file'){
									editor.insertContent('<a href="' + winElementUrl + '">ссылка</a>');
									win.close();
								}else{
									alert('Выбраный элемент должен быть файлом а не папкой.')
								}
							}else{
								alert('Не выбран файл!')
							}

						}
					},

					{text: 'Close', onclick: 'close'}
				],
				onsubmit: function(e) {
					// Insert content when the window form is submitted
					editor.insertContent('Title: ' + e.data.title);
				}
			});
		}
	});
});
