/*!
 * License http://opensource.org/licenses/MIT
 * Copyright 2015 Anton Filinkov
 */

(function ($) {
	$.Editable.DEFAULTS = $.extend($.Editable.DEFAULTS, {
		defaultVideoCloudAlignment: 'center',
		textNearVideoCloud: true
	});

	$.Editable.VIDEOCLOUD_PROVIDERS = [];

	$.Editable.videocloud_commands = {
		floatVideoCloudLeft: {
			title: 'Float Left',
			icon: {
				type: 'font',
				value: 'fa fa-align-left'
			}
		},

		floatVideoCloudNone: {
			title: 'Float None',
			icon: {
				type: 'font',
				value: 'fa fa-align-justify'
			}
		},

		floatVideoCloudRight: {
			title: 'Float Right',
			icon: {
				type: 'font',
				value: 'fa fa-align-right'
			}
		},

		removeVideoCloud: {
			title: 'Remove Video',
			icon: {
				type: 'font',
				value: 'fa fa-trash-o'
			}
		}
	};

	$.Editable.DEFAULTS = $.extend($.Editable.DEFAULTS, {
		videoCloudButtons: ['floatVideoCloudLeft', 'floatVideoCloudNone', 'floatVideoCloudRight', 'removeVideoCloud']
	});

	$.Editable.commands = $.extend($.Editable.commands, {
		insertCloudVideo: {
			title: 'Insert Video from Cloud',
			icon: 'fa fa-file-video-o',
			callback: function () {
				this.insertCloudVideo();
			},
			undo: false
		}
	});

	/**
	 * Insert Cloud Video.
	 */
	$.Editable.prototype.insertCloudVideo = function () {
		if (!this.options.inlineMode) {
			this.closeImageMode();
			this.imageMode = false;
			this.positionPopup('insertCloudVideo');
		}

		if (this.selectionInEditor()) {
			this.saveSelection();
		}

		this.showinsertCloudVideo();
	};


	$.Editable.prototype.insertCloudVideoHTML = function () {
		var tmpl = this.options.zVideoCloud;
		return '<div class="froala-popup froala-video-cloud-popup" style="display: none;"><h4><span data-text="true">Insert Video from Cloud</span><i title="Cancel" class="fa fa-times" id="f-video-close-' + this._id + '"></i></h4>' + tmpl + '<div class="f-popup-line"><button data-text="true" class="f-ok f-submit fr-p-bttn" id="f-video-cloud-ok-' + this._id + '">OK</button></div></div>';
	};

	$.Editable.prototype.buildinsertCloudVideo = function () {
		this.$cloudVideo_wrapper = $(this.insertCloudVideoHTML());
		this.$popup_editor.append(this.$cloudVideo_wrapper);

		this.addListener('hidePopups', this.hideCloudVideoWrapper);

		// Stop event propagation in video wrapper.
		this.$cloudVideo_wrapper.on('mouseup touchend', $.proxy(function (e) {
			if (!this.isResizing()) {
				e.stopPropagation();
			}
		}, this));

		this.$cloudVideo_wrapper.on('click', 'button#f-video-cloud-ok-' + this._id, $.proxy(function () {
			var $input = this.$cloudVideo_wrapper.find('input:checked');
			if ($input.val() !== '') {
				this.writeVideoCloud($input.val(), false, $input.attr('id'));
			}
		}, this));

		this.$cloudVideo_wrapper.on(this.mouseup, 'i#f-video-close-' + this._id, $.proxy(function () {
			this.$bttn_wrapper.show();
			this.hideCloudVideoWrapper();

			if (this.options.inlineMode && !this.imageMode && this.options.buttons.length === 0) {
				this.hide();
			}

			this.restoreSelection();
			this.focus();

			if (!this.options.inlineMode) {
				this.hide();
			}
		}, this));

		this.$cloudVideo_wrapper.on('click', function (e) {
			e.stopPropagation();
		});

		this.$cloudVideo_wrapper.on('click', '*', function (e) {
			e.stopPropagation();
		});

		// Remove video on delete key hit.
		this.$window.on('keydown.' + this._id, $.proxy(function (e) {
			if (this.$element.find('.f-video-cloud-editor.active').length > 0) {
				var keyCode = e.which;
				// Delete.
				if (keyCode == 46 || keyCode == 8) {
					e.stopPropagation();
					e.preventDefault();
					setTimeout($.proxy(function () {
						this.removeVideoCloud();
					}, this), 0);
					return false;
				}
			}
		}, this));
	};

	$.Editable.prototype.destroyVideoCloud = function () {
		this.$cloudVideo_wrapper.html('').removeData().remove();
	};

	$.Editable.prototype.initCloudVideo = function () {
		this.buildinsertCloudVideo();

		this.addVideoCloudControls();

		this.addListener('destroy', this.destroyVideoCloud);
	};

	$.Editable.initializers.push($.Editable.prototype.initCloudVideo);

	$.Editable.prototype.hideVideoCloudEditorPopup = function () {
		if (this.$videocloud_editor) {
			this.$videocloud_editor.hide();
			$('div.f-video-cloud-editor').removeClass('active');

			this.$element.removeClass('f-non-selectable');
			if (!this.editableDisabled && !this.isHTML) {
				this.$element.attr('contenteditable', true);
			}
		}
	};

	$.Editable.prototype.showVideoCloudEditorPopup = function () {
		this.hidePopups();

		if (this.$videocloud_editor) {
			this.$videocloud_editor.show();
		}

		this.$element.removeAttr('contenteditable');
	};

	$.Editable.prototype.addVideoCloudControlsHTML = function () {
		this.$videocloud_editor = $('<div class="froala-popup froala-video-cloud-editor-popup" style="display: none">');

		var $buttons = $('<div class="f-popup-line">').appendTo(this.$videocloud_editor);

		for (var i = 0; i < this.options.videoCloudButtons.length; i++) {
			var cmd = this.options.videoCloudButtons[i];
			if ($.Editable.videocloud_commands[cmd] === undefined) {
				continue;
			}
			var button = $.Editable.videocloud_commands[cmd];

			var btn = '<button class="fr-bttn" data-callback="' + cmd + '" data-cmd="' + cmd + '" title="' + button.title + '">';

			if (this.options.icons[cmd] !== undefined) {
				btn += this.prepareIcon(this.options.icons[cmd], button.title);
			} else {
				btn += this.prepareIcon(button.icon, button.title);
			}

			btn += '</button>';

			$buttons.append(btn);
		}

		this.addListener('hidePopups', this.hideVideoCloudEditorPopup);

		this.$popup_editor.append(this.$videocloud_editor);

		this.bindCommandEvents(this.$videocloud_editor);
	};

	$.Editable.prototype.floatVideoCloudLeft = function () {
		var $activeVideo = $('div.f-video-cloud-editor.active');

		$activeVideo.attr('class', 'f-video-cloud-editor active fr-fvl');

		this.triggerEvent('videoFloatedLeft');

		$activeVideo.click();
	};

	$.Editable.prototype.floatVideoCloudRight = function () {
		var $activeVideo = $('div.f-video-cloud-editor.active');

		$activeVideo.attr('class', 'f-video-cloud-editor active fr-fvr');


		this.triggerEvent('videoFloatedRight');

		$activeVideo.click();
	};

	$.Editable.prototype.floatVideoCloudNone = function () {
		var $activeVideo = $('div.f-video-cloud-editor.active');

		$activeVideo.attr('class', 'f-video-cloud-editor active fr-fvn');

		this.triggerEvent('videoFloatedNone');

		$activeVideo.click();
	};

	$.Editable.prototype.removeVideoCloud = function () {
		$('div.f-video-cloud-editor.active').remove();

		this.hide();

		this.triggerEvent('videoRemoved');

		this.focus();
	};

	$.Editable.prototype.refreshVideo = function () {

		this.$element.find('iframe, object').each (function (index, iframe) {
			var $iframe = $(iframe);

			for (var i = 0; i < $.Editable.VIDEOCLOUD_PROVIDERS.length; i++) {
				var vp = $.Editable.VIDEOCLOUD_PROVIDERS[i];

				if (vp.test_regex.test($iframe.attr('src'))) {
					if ($iframe.parents('.f-video-cloud-editor').length === 0) {
						$iframe.wrap('<div class="f-video-cloud-editor fr-fvn" data-fr-verified="true" contenteditable="false">');
					}

					break;
				}
			}
		});

		if (this.browser.msie) {
			this.$element.find('.f-video-cloud-editor').each (function () {
				this.oncontrolselect = function () {
					return false;
				};
			});
		}

		if (!this.options.textNearVideoCloud) {
			this.$element.find('.f-video-cloud-editor')
				.attr('contenteditable', false)
				.addClass('fr-tnv');
		}
	};

	$.Editable.prototype.addVideoCloudControls = function () {
		this.addVideoCloudControlsHTML();

		this.addListener('sync', this.refreshVideo);

		this.$element.on('mousedown', 'div.f-video-cloud-editor', $.proxy(function (e) {
			e.stopPropagation();
		}, this));

		this.$element.on('click touchend', 'div.f-video-cloud-editor', $.proxy(function (e) {
			if (this.isDisabled) return false;

			e.preventDefault();
			e.stopPropagation();

			var target = e.currentTarget;

			this.clearSelection();

			this.showVideoCloudEditorPopup();
			this.showByCoordinates($(target).offset().left + $(target).width() / 2, $(target).offset().top + $(target).height() + 3);

			$(target).addClass('active');

			this.refreshvideoCloudButtons(target);
		}, this));
	};

	$.Editable.prototype.refreshvideoCloudButtons = function (video_editor) {
		var video_float = $(video_editor).attr('class');
		this.$videocloud_editor.find('[data-cmd]').removeClass('active');

		if (video_float.indexOf('fr-fvl') >= 0) {
			this.$videocloud_editor.find('[data-cmd="floatVideoCloudLeft"]').addClass('active');
		}
		else if (video_float.indexOf('fr-fvr') >= 0) {
			this.$videocloud_editor.find('[data-cmd="floatVideoCloudRight"]').addClass('active');
		}
		else {
			this.$videocloud_editor.find('[data-cmd="floatVideoCloudNone"]').addClass('active');
		}
	};

	$.Editable.prototype.writeVideoCloud = function (video_obj, embeded, video_id) {
		var video = null;
		video = this.clean(video_obj, true, false);

		if (video) {
			this.restoreSelection();
			this.$element.focus();

			var aligment = 'fr-fvn';
			if (this.options.defaultVideoCloudAlignment == 'left') aligment = 'fr-fvl';
			if (this.options.defaultVideoCloudAlignment == 'right') aligment = 'fr-fvr';

			if (!this.textNearVideoCloud) aligment += ' fr-tnv';

			try {

				var regex = /\..+$/;
				//Video template
				var tmpl = '<p></p>';
				tmpl += '<div contenteditable="false" class="f-video-cloud-editor ' + aligment + '" data-fr-verified="true">';
				tmpl += '<video id="' + video_id + '" class="video-js vjs-default-skin vjs-big-play-centered cloud-video" controls preload="auto">';
				tmpl += '<source src="' + video.replace(regex , '_360p.mp4') + '" type="video/mp4" label="360p"/>';
				tmpl += '<source src="' + video.replace(regex , '_360p.webm') + '" type="video/webm" label="360p"/>';
				tmpl += '<source src="' + video.replace(regex , '_360p.ogg') + '" type="video/ogg" label="360p"/>';
				tmpl += '<source src="' + video.replace(regex , '_480p.mp4') + '" type="video/mp4" label="480p"/>';
				tmpl += '<source src="' + video.replace(regex , '_480p.webm') + '" type="video/webm" label="480p"/>';
				tmpl += '<source src="' + video.replace(regex , '_480p.ogg') + '" type="video/ogg" label="480p"/>';
				tmpl += '<source src="' + video.replace(regex , '_720p.mp4') + '" type="video/mp4" label="720p"/>';
				tmpl += '<source src="' + video.replace(regex , '_720p.webm') + '" type="video/webm" label="720p"/>';
				tmpl += '<source src="' + video.replace(regex , '_720p.ogg') + '" type="video/ogg" label="720p"/>';
				tmpl += '<source src="' + video.replace(regex , '_1080p.mp4') + '" type="video/mp4" label="1080p"/>';
				tmpl += '<source src="' + video.replace(regex , '_1080p.webm') + '" type="video/webm" label="1080p"/>';
				tmpl += '<source src="' + video.replace(regex , '_1080p.ogg') + '" type="video/ogg" label="1080p"/>';
				tmpl += '</video>';
				tmpl += '</div>';
				tmpl += '<p></p>';

				this.insertHTML(tmpl);

			}
			catch (ex) {}

			this.$bttn_wrapper.show();
			this.hideCloudVideoWrapper();
			this.hide();

			// call with (video)
			this.triggerEvent('videoInserted', [video]);
		} else {
			// call with ([])
			this.triggerEvent('videoError');
		}
	};

	$.Editable.prototype.showCloudVideoWrapper = function () {
		if (this.$cloudVideo_wrapper) {
			this.$cloudVideo_wrapper.show();
			this.$cloudVideo_wrapper.find('.f-popup-line input').val()
		}
	};

	$.Editable.prototype.hideCloudVideoWrapper = function () {
		if (this.$cloudVideo_wrapper) {
			this.$cloudVideo_wrapper.hide();
			this.$cloudVideo_wrapper.find('input').blur()
			this.$cloudVideo_wrapper.find('input').attr('checked', false);
		}
	};

	$.Editable.prototype.showinsertCloudVideo = function () {
		this.hidePopups();
		this.showCloudVideoWrapper();
	};

})(jQuery);
