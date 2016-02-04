<?php
	$id = Hash::get($article, 'Article.id');
	/* Breadcrumbs */
	$this->Html->addCrumb(__('Articles'), array('controller' => 'Article', 'action' => 'all'));
	if (Hash::get($article, 'Article.title')) {
		$breadcrumbsTitle = Hash::get($article, 'Article.title');
		if ( iconv_strlen($breadcrumbsTitle) > 40 ) {
			$breadcrumbsTitle = mb_substr($breadcrumbsTitle, 0,39).'…';
		}
		$this->Html->addCrumb($breadcrumbsTitle, array('controller' => 'Article', 'action' => 'view/'.$id));
	} else {
		$this->Html->addCrumb(__('Create article'), array('controller' => 'Article', 'action' => 'view'));
	}


    $viewScripts = array('vendor/jquery/jquery.linkify.min');
    $this->Html->script($viewScripts, array('inline' => false));

	$scheme = isset($_SERVER['HTTP_SCHEME']) ? $_SERVER['HTTP_SCHEME'] : (
		(
		(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ||
		443 == $_SERVER['SERVER_PORT']
		) ? 'https' : 'http'

	);

    // SOCIAL meta-s
    $actual_link = $scheme."://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $image_link = $scheme.'://'.$_SERVER['HTTP_HOST'].$this->Media->imageUrl($article['ArticleMedia'], 'thumb256x256');

    $title = Hash::get($article, 'Article.title');
    $description = strlen(Hash::get($article, 'Article.body')) > 0 ? Hash::get($article, 'Article.body') : '';
    $description = strip_tags($description);

    echo $this->Html->meta(array('property' => 'og:url', 'content' => $actual_link),null,array('inline'=>false));
    echo $this->Html->meta(array('property' => 'og:image', 'content' => $image_link),null,array('inline'=>false));
    echo $this->Html->meta(array('property' => 'og:title', 'content' => $title),null,array('inline'=>false));
    echo $this->Html->meta(array('property' => 'og:type', 'content' => 'article'),null,array('inline'=>false));
    echo $this->Html->meta(array('property' => 'og:site_name', 'content' => 'Konstruktor.com'),null,array('inline'=>false));
    echo $this->Html->meta(array('property' => 'og:description', 'content' => $description ),null,array('inline'=>false));
	echo $this->Html->meta(array('itemprop' => 'og:headline', 'content' => $title),null,array('inline'=>false));
	echo $this->Html->meta(array('itemprop' => 'og:description', 'content' => $description ),null,array('inline'=>false));

    echo $this->Html->meta(array('name' => 'twitter:card', 'content' => 'summary_large_image'),null,array('inline'=>false));
    echo $this->Html->meta(array('name' => 'twitter:site', 'content' => '@konstruktor_com'),null,array('inline'=>false));
    echo $this->Html->meta(array('name' => 'twitter:url', 'content' => $actual_link),null,array('inline'=>false));
    echo $this->Html->meta(array('name' => 'twitter:image', 'content' => $image_link),null,array('inline'=>false));
    echo $this->Html->meta(array('name' => 'twitter:title', 'content' => $title),null,array('inline'=>false));
    echo $this->Html->meta(array('name' => 'twitter:description', 'content' => $description ),null,array('inline'=>false));

    $this->Html->css('//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css', array('inline' => false));
    $this->Html->css('/Froala/css/froala_editor.min.css', array('inline' => false));

?>

<style type="text/css">
	.froala-popup.froala-video-cloud-popup {
		width: 300px;
	}
	.f-popup-line.videoCloud {
		max-height: 100px;
		overflow: auto;
	}
	.froala-video-cloud-popup textarea {
		width: 250px !important;
	}

	.froala-video-cloud-popup .f-popup-line {
		padding: 0 !important;
		margin: 7px !important;
	}

	.froala-video-cloud-popup .f-popup-line button {
		float: right !important;
	}
	.cloud-video {
		margin: 0 auto;
	}

	.froala-editor .froala-popup div.f-popup-line.videoCloud label {
		float: none;
		cursor: pointer;
	}
    .enter-massage-label {
        display: inline-block;
        padding: 5px 0 0;
        color: #AEAEAE;
        font-size: 11px;
        font-weight: 400;
        float: right;
    }

    .popover {
        max-width: 400px;
        width: auto;
    }

    .submitMessage #sendChatSmile {
        position: absolute;
        bottom: 15px;
        left: 0;

        width: 30px;
        height: 30px;
        padding: 4px;
    }

    .submitMessage #sendChatSmile .smile { width: 20px!important; height: 20px!important; }
    .submitMessage #sendChatSmile:hover .smile { background-position: 0 -40px; }
    .submitMessage #sendChatSmile:active .smile,
    .submitMessage #sendChatSmile.active .smile { background-position: 0 -20px; }

    .submitMessage .form-group { padding-left: 45px; }

    /* Smile Popover */
    .submitMessage .popover { width: 500px; max-width: 500px; background: white; color: #5B5B5B; border: 1px solid #ccc; border-radius: 3px; box-shadow: 0 25px 15px -20px rgba(0, 0, 0, .5); padding: 16px; font-family: 'Open Sans' }
    .submitMessage .popover-content { padding: 0; }
    .submitMessage .popover .smileRow { text-align: justify; }
    .submitMessage .popover .smileRow:before { content: ''; display: block; width: 100%; margin-bottom: -1.2em; }
    .submitMessage .popover .smileRow:after { content: ''; display: inline-block; width: 100%; }
    .submitMessage .popover .smileRow .smileSelect { display: inline-block; position: relative; top: 1.2em; padding: 5px 10px; box-sizing: border-box; }
    .submitMessage .popover .smileRow .smileSelect:hover { color: #fff; background: #25b5be; cursor: pointer; }

    .editable {
        border: 1px dashed #00B6AF;
        padding: 10px;
    }

    .controlButtons .btn {
        margin: 0 10px;
    }

    .taskViewTitle .fr-placeholder {
        padding: 10px!important;
    }

    .articleAuthor .jq-selectbox__select-text {
        height: 20px!important;
    }

    h2 {
        color: #313131;
        font-family: "Roboto",sans-serif;
        font-weight: 900;
        word-wrap: break-word;
    }

    .controlButtons {
        margin-top: 25px;
    }
</style>

<?php if(!$currUserID){ ?>
<script type="text/javascript">

$(document).ready(function(){
    $('.register-btn').magnificPopup({
        type:'inline',
        midClick: true
    });
});
</script>
<?php } ?>

<script type="text/javascript">
$(document).ready(function(){
    initInnserSmiles = function() {
        if ((/iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent))) {
            $('.inner #sendChatSmile').popover({
                html : true,
                placement: 'top',
                class: 'smilesPopover',
                trigger: 'click',
                content: function() {
                  return $('#popover_content_wrapper').html();
                }
            });

            $('body').on('touchstart', function(e) {
                if( !($(e.target).is('#sendChatSmile') || $(e.target).is('.smileSelect') || $(e.target).parents('#sendChatSmile').length > 0) ) {
                    $('#sendChatSmile').popover('hide');
                }
            });
        } else if((navigator.userAgent.indexOf("Safari") > -1) || (navigator.userAgent.indexOf("Mozilla") > -1)) {
            $('.inner #sendChatSmile').popover({
                html : true,
                placement: 'top',
                class: 'smilesPopover',
                trigger: 'click',
                content: function() {
                  return $('#popover_content_wrapper').html();
                }
            });
        } else {
            $('.inner #sendChatSmile').popover({
                html : true,
                placement: 'top',
                class: 'smilesPopover',
                trigger: 'focus',
                content: function() {
                  return $('#popover_content_wrapper').html();
                }
            });
            console.log(navigator.userAgent);
        }
    }

    updateComments = function() {
        $.post( "<?=$this->Html->url(array('controller' => 'ArticleAjax', 'action' =>  'comments', $id))?>", $(this).parents('form').serialize(), function(response) {
            $('#articleComments').html(response);
            initHandlers();
        });
    }

    initHandlers = function() {
        $('#message-title').autosize();

        $('#message-title').off('keyup copy cut paste change');
        $('#message-title').on('keyup copy cut paste change', function() {
            $('#message-title').trigger('autosize.resize');
        });

        $('.submitBtn').unbind('click');
        $('.submitBtn').bind('click', function(event) {
            var parent = $(this).parents('.submitMessage');
            if( $('textarea', parent).val().length < 1 ) {
                alert("<?=__('Message can not be empty')?>");
                return false;
            }
            $.post( "<?=$this->Html->url(array('controller' => 'ArticleAjax', 'action' =>  'addComment'))?>.json", $(this).parents('form').serialize(), function(response) {
                updateComments();
            });
        });

        // Comment: Клик по "answer"
        $('.item .answer').unbind('click');
        $('.item .answer').click(function(){

			$(this).unbind('click').addClass('non-active');
			$(this).closest('.item').find('.edit').unbind('click').addClass('non-active');

            $('.inner.submitMessage').remove();

            var html = tmpl('commentAnswer', {parent: $(this).data('parent_id'), article: '<?=$id?>'});
            $(html).insertAfter($(this).parents('.item'));
            $('#innerMessageTitle').autosize();

            initInnserSmiles();

            $('#innerMessageTitle').keydown(function (event) {
                if (event.ctrlKey && event.keyCode == 13) {
                    var value = $(this).val();
                    $(this).val(value+'\n');
                } else {
                    if (event.keyCode == 13) {
                        event.preventDefault();
                        if( $(this).val().length < 1 ) {
                            alert("<?=__('Message can not be empty')?>");
                            return false;
                        }
                        var parent = $(this).parents('form');
                        $('.submitBtn', $(parent)).trigger('click');
                    }
                }
            });

            $('.inner.submitMessage .submitBtn').unbind('click');
            $('.inner.submitMessage .submitBtn').bind('click', function(event) {
                var parent = $(this).parents('.submitMessage');
                if( $('textarea', parent).val().length < 1 ) {
                    alert("<?=__('Message can not be empty')?>");
                    return false;
                }
                $.post( "<?=$this->Html->url(array('controller' => 'ArticleAjax', 'action' =>  'addComment'))?>.json", $(this).parents('form').serialize(), function(response) {
                    updateComments();
                });
            });

            //smile init for answer message input block
        });

        // Comment: Клик по "edit"
        $('.item .edit').unbind('click');
        $('.item .edit').click(function(){

			$(this).unbind('click').addClass('non-active');
			$(this).closest('.item').find('.answer').unbind('click').addClass('non-active');

            $('.inner.submitMessage').remove();

            var msg = $('.msgText' ,$(this).parents('.item')).text();
            var html = tmpl('commentAnswer', {event: $(this).data('event_id'), article: '<?=$id?>', message: msg});
            $(html).insertAfter($(this).parents('.item'));
            $('#innerMessageTitle').autosize();

            initInnserSmiles();

            $('#innerMessageTitle').unbind('keydown');
            $('#innerMessageTitle').keydown(function (event) {
                if (event.ctrlKey && event.keyCode == 13) {
                    var value = $(this).val();
                    $(this).val(value+'\n');
                } else {
                    if (event.keyCode == 13) {
                        event.preventDefault();
                        if( $(this).val().length < 1 ) {
                            alert("<?=__('Message can not be empty')?>");
                            return false;
                        }
                        var parent = $(this).parents('form');
                        $('.submitBtn', $(parent)).trigger('click');
                    }
                }
            });

            $('.inner.submitMessage .submitBtn').unbind('click');
            $('.inner.submitMessage .submitBtn').bind('click', function(event) {
                var parent = $(this).parents('.submitMessage');
                if( $('textarea', parent).val().length < 1 ) {
                    alert("<?=__('Message can not be empty')?>");
                    return false;
                }
                $.post( "<?=$this->Html->url(array('controller' => 'ArticleAjax', 'action' =>  'editComment'))?>.json", $(this).parents('form').serialize(), function(response) {
                    updateComments();
                });
            });
        });

        // Comment: Клик по "remove"
        $('.item .remove').unbind('click');
        $('.item .remove').click(function(){
            $('.inner.submitMessage').remove();
            if(confirm('<?=__('Do you really want to delete this comment?')?>')) {
                $.post( "<?=$this->Html->url(array('controller' => 'ArticleAjax', 'action' => 'removeComment'))?>.json", {event_id: $(this).data('event_id'), user_id: '<?=$currUserID?>'}, function(response) {
                    updateComments();
                });
            }
        });

        $('#message-title').unbind('keydown');
        $('#message-title').keydown(function (event) {
            if (event.ctrlKey && event.keyCode == 13) {
                var value = $(this).val();
                $(this).val(value+'\n');
            } else {
                if (event.keyCode == 13) {
                    event.preventDefault();
                    if( $(this).val().length < 1 ) {
                        alert("<?=__('Message can not be empty')?>");
                        return false;
                    }
                    var parent = $(this).parents('form');
                    $('.submitBtn', $(parent)).trigger('click');
                }
            }
        });

        $('.item .msgText').linkify({
            tagName: 'a',
            target: '_blank',
            newLine: '\n',
            linkClass: 'underlink',
            linkAttributes: null
        });
    };

    function iFrameResize() {
        var iFrameWidth = $('.articleView .fixedLayout').width();
        var iFrameHeight = iFrameWidth * 0.5625;
        $('.articleView .fixedLayout iframe').prop("height", iFrameHeight);
    }

    $(window).resize(function() { iFrameResize(); }).resize();

<?php if( $article['Article']['title'] != null ) { ?>
    setTimeout( function() {
        updateComments();
    }, 2000);
<? } ?>

    //редактор
    var firstSave = false;

    var bodyBefore = '';
    var titleBefore = '';
    var publishBefore = '';
    var categoryBefore = '';
    var editMode = false;
    var publishMode = <?php if($article['Article']['published']) { ?> true <?php } else { ?> false <?php } ?>;

    $('#articleEdit').bind('click', function (event) {
        switchEditor();
    });

    $('#publishEdit').bind('click', function (event) {
        changePublish();
    });

    changePublish = function() {
        if( !publishMode ) {
            publishMode = true;
            changePublishButton();
        } else {
            publishMode = false;
            changePublishButton();
        }
    };

    changePublishButton = function() {
        if( publishMode ) {
            $('#publishEdit .glyphicons').removeClass('eye_close');
            $('#publishEdit .glyphicons').addClass('eye_open');
            $('#publishEdit .caption').text('<?=__('Published')?>');
        } else {
            $('#publishEdit .glyphicons').addClass('eye_close');
            $('#publishEdit .glyphicons').removeClass('eye_open');
            $('#publishEdit .caption').text('<?=__('Unpublished')?>');
        }
    }

    switchEditor = function() {
        if( !editMode ) {
            enableEdit();
            saveLeave = false;
        } else {
            if( sameCheck() ) {
                /*
                if(confirm("<?=__('Save changes?')?>")) {
                    saveArticle();
                } else {
                    $('.articleView').html(bodyBefore);
                    $('#articleTitle').text(titleBefore);
                    publishMode = publishBefore;
                    changePublishButton();
                    $('#ArticleCategory').val(categoryBefore).change();
                    //disableEdit();
                }
                */

                // Нотифаер

                var n = noty({
                    text        : '<?=__('Save changes?')?>',
                    dismissQueue: true,
                    layout      : 'center',
                    theme: 'someOtherTheme',
                    animation: {
                        open: 'animated fadeInUp', // Animate.css class names
                        close: 'animated fadeOutDown', // Animate.css class names
                    },
                    buttons     : [
                        {addClass: 'btn btn-primary', text: '<?=__('Yes')?>', onClick: function ($noty) {
                            $noty.close();
                            saveArticle();
                            //noty({dismissQueue: true, force: true, layout: 'top', theme: 'defaultTheme', text: 'You clicked "Ok" button', type: 'success'});
                        }
                        },
                        {addClass: 'btn btn-default pull-right', text: '<?=__('No')?>', onClick: function ($noty) {
                            $noty.close();
                            $('.articleView').html(bodyBefore);
                            $('#articleTitle').text(titleBefore);
                            disableEdit();
                            $('#ArticleCategory').val(categoryBefore).change();
                            //noty({dismissQueue: true, force: true, layout: 'center', theme: 'defaultTheme', text: 'You clicked "Cancel" button', type: 'error'});
                        }
                        }
                    ]
                });
            } else {
                disableEdit();
            }
        }
    };

    sameCheck = function() {
        return (bodyBefore != $('.articleView').editable('getHTML') ||
                titleBefore != $('#articleTitle').editable('getText') ||
                publishBefore != publishMode ||
                categoryBefore != $('#ArticleCategory').val());
    }

    enableEdit = function() {
        $('#articleTitle ').addClass('editable');
        $('#articleEdit ').addClass('active');
        $('#articleEdit .caption').text('<?=__('End')?>');
        $('#ArticleCategoryBlock').show();
        $('#saveArticle').show();
        $('#publishEdit').show();
        $('#removeLink').show();
        $('#imageUploadControls').show();
        initEditor();
        $('#articleTitle').editable({
            inlineMode: true,
            key: '<?=Configure::read('froalaEditorKey')?>',
            placeholder: '<?=__('Article title')?>...'
        })
        bodyBefore = $('.articleView').editable('getHTML');
        titleBefore = $('#articleTitle').editable('getText');
        publishBefore = publishMode;
        categoryBefore = $('#ArticleCategory').val();
        editMode = true;
    }

    disableEdit = function() {
        $('#ArticleCategoryBlock').hide();
        $('#saveArticle').hide();
        $('#publishEdit').hide();
        $('#removeLink').hide();
        $('#imageUploadControls').hide();
        $('.articleView').editable('destroy');
        $('#articleTitle').editable('destroy');
        $('#articleTitle ').removeClass('editable');
        $('#articleEdit ').removeClass('active');
        $('#articleEdit .caption').text('<?=__('Edit')?>');
        editMode = false;
    }

    saveArticle = function(auto) {
        if( auto != true ) auto = false;
        var title = $('#articleTitle').editable('getText');
        var body = $('.articleView').editable('getHTML');

        pass = true;
        if( !auto ) {
            pass = validateArticle(title, body);
        }

        if( pass ) {
            $.post(articleURL.saveArticle, {
                    data: {
                        id: '<?=$id?>',
                        body: body,
                        title: title,
                        published: +publishMode,
                        cat_id: $('#ArticleCategory').val()
                    }
                }, function (response) {
                    console.log(response);


                if(firstSave) {
                    window.location.replace("<?=$this->Html->url(array('controller' => 'Article', 'action' => 'view',$id))?>");
                } else {
                        disableEdit();

                        if(response.status == 'OK') {
                            noty({
                                dismissQueue: true,
                                force: true,
                                layout: 'bottomRight',
                                theme: 'someOtherTheme',
                                animation: {
                                    open: 'animated fadeInUp', // Animate.css class names
                                    close: 'animated fadeOutDown', // Animate.css class names
                                },
                                text: '<?=__('Article succesfully saved')?>',
                                type: 'success',
                                timeout: 5000}
                            );
                        } else {
                            noty({
                                dismissQueue: true,
                                force: true,
                                layout: 'bottomRight',
                                theme: 'someOtherTheme',
                                animation: {
                                    open: 'animated fadeInUp', // Animate.css class names
                                    close: 'animated fadeOutDown', // Animate.css class names
                                },
                                text: '<?=__('Article succesfully saved')?>',
                                type: 'error',
                                timeout: 5000}
                            );
                        }
                    }
                }
            );
        }
    }

    $('#publishSave').on('click', function() {
        firstSave = true;
        publishMode = true;
        saveArticle();
    });

    $('#draftSave').on('click', function() {
        firstSave = true;
        publishMode = false;
        saveArticle();
    });

    validateArticle = function(title, body) {
        allow = true;
        if(title.length == 0) {
            allow = false;
            $('#articleTitle').popover({ toggle: 'popover', placement: 'bottom', content: "<?=__('Title can not be empty')?>" });
            $('#articleTitle').popover('show');
            $('#articleTitle').on('editable.focus', function (e, editor) {
                $('#articleTitle').popover('destroy');
                $('#articleTitle').off('editable.focus');
            });
        }

        if($('#ArticleCategory').val() == 0) {
            allow = false;
            $('#ArticleCategory-styler .jq-selectbox__select').popover({ toggle: 'popover', placement: 'bottom', content: "<?=__('Select article category')?>" });
            $('#ArticleCategory-styler .jq-selectbox__select').popover('show');
            $('#ArticleCategory-styler .jq-selectbox__select').on('click', function (e, editor) {
                $('#ArticleCategory-styler .jq-selectbox__select').popover('destroy');
                $('#ArticleCategory-styler .jq-selectbox__select').off('editable.focus');
            });
        }
        return(allow);
    }

    //Text editor
    moveImage = {};
	tmplCV = function() {
		var tmpl = '';
		var user_id = true;
		$.ajax({
			type: 'POST',
			url: "/CloudAjax/showUserCloudVideo.json",
			data: {user_id: user_id},
			success: function (res) {
				var data = $.parseJSON(res);
				tmpl += '<div class="f-popup-line videoCloud">';
				$.each(data, function(i, el){
					var name = el.orig_fname;
					if (name.length > 30) {
						name = name.substring(0, 30) + '...';
					}
					tmpl += '<div><input type="radio" id="video_'+i+'" name="video" value="' + el.url_download + '"><label for="video_'+i+'">' + name + '</label></div>';
				});
				tmpl += '</div>';
			},
			async:false
		});
		return tmpl;
	}
	var tmplCV = tmplCV();

    initEditor = function() {

        $('.articleView').editable({
            inlineMode: false,
            key: '<?=Configure::read('froalaEditorKey')?>',
            imageUploadURL: mediaURL.froalaUpload,
            placeholder: '<?=__('Type something')?>...',
            minHeight: 400,
			zVideoCloud: tmplCV,
			buttons: ["bold", "italic", "underline", "strikeThrough", "fontSize",
				"fontFamily", "color", "sep", "formatBlock", "blockStyle", "align",
				"insertOrderedList", "insertUnorderedList", "outdent", "indent", "sep",
				"createLink", "insertImage", "insertVideo", "table", "undo", "redo",
				"html","insertCloudVideo"]
        })

        $('.articleView').off('editable.beforeImageUpload');
        $('.articleView').on('editable.beforeImageUpload', function (e, editor, images) {
            moveImage = {};

            moveImage = {
                type: images[0].type,
                name: images[0].name,
                size: images[0].size,
                object_id: '<?=$currUserID?>',
                object_type: 'UserMedia'
            }

        });

        $('.articleView').off('editable.afterImageUpload');
        $('.articleView').on('editable.afterImageUpload', function (e, editor, response) {
            moveImage.url = 'https://konstrukt.dev' + $.parseJSON(response).link;
            console.log(moveImage);
            var ret = '123';
            $.ajax({
                type: "POST",
                url: mediaURL.move,
                async: false,
                data: moveImage,
                success: function(response) {
                    console.log('ajax');
                    console.log(response);
                    console.log('------------------------');
                    if( response.data[0].Media.orig_w < 1000 ) {
                        ret = '{"link": "' + response.data[0].Media.url_download + '" }';
                    } else {
                        var url = response.data[0].Media.url_img;
                        url = url.replace('noresize', '1000x');
                        ret = '{"link": "' + url + '" }';
                    }
                },
                error: function() {
                    ret = 'error'
                }
            });
            console.log(ret);
            return ret;
        });

        $('.articleView').off('editable.imageError');
        $('.articleView').on('editable.imageError', function (e, editor, error) {
            // Custom error message returned from the server.
            if (error.code == 0) { console.log('error 0: Custom error message returned from the server'); }
            // Bad link.
            else if (error.code == 1) { console.log('error 1: Bad link'); }
            // No link in upload response.
            else if (error.code == 2) { console.log('error 2: No link in upload response'); }
            // Error during file upload.
            else if (error.code == 3) { console.log('error 3: Error during file upload'); }
            // Parsing response failed.
            else if (error.code == 4) { console.log('error 4: Parsing response failed'); }
            // File too text-large.
            else if (error.code == 5) { console.log('error 5: File too text-large'); }
            // Invalid file type.
            else if (error.code == 6) { console.log('error 6: Invalid file type'); }
            // File can be uploaded only to same domain in IE 8 and IE 9.
            else if (error.code == 7) { console.log('error 7: File can be uploaded only to same domain in IE 8 and IE 9'); }
        });
    }

    <?php if( $article['Article']['title'] == null ) { ?>
        switchEditor();
    <?php } ?>


	//Video.js player init and some setup
	resizeVideoCloud = function() {
		$('.cloud-video').css('width', '70%');
		var allowedHeight = $(window).height() - $('#preview-header').height() - 170;

		var vWidth = $('.cloud-video').width();
		var vHeight = vWidth/16*10;

		if(allowedHeight < vHeight) {
			vHeight = allowedHeight;
			vWidth = vHeight / 10 * 16;
			$('.cloud-video').css('width', vWidth);
		}
		$('.cloud-video').css('height', vHeight);
	}
//
	var videoFiles = $('.cloud-video');
	videoFiles.each(function(i, el){
		var videoFileId = $(el).prop('id');
		videojs(document.getElementById(videoFileId)).videoJsResolutionSwitcher();
		videojs(document.getElementById(videoFileId)).ready(function(){
			resizeVideoCloud();
			this.currentResolution('360p')
		});
	});

});
</script>


<div class="row taskViewTitle fixedLayout">
    <div class="col-sm-4 col-sm-push-8 controlButtons" style="padding-right: 0;">
<?
    if ($isArticleAdmin && ($article['Article']['title'] != null)) {
?>
        <div class="linkIcon" id="articleEdit">
            <div class="glyphicons pencil"></div>
            <div class="caption"><?=__('Edit')?></div>
        </div>

        <div class="linkIcon" id="publishEdit" style="display: none; width: 100px;">
            <div class="glyphicons <?=$article['Article']['published'] ? 'eye_open' : 'eye_close'?>"></div>
            <div class="caption"><?=$article['Article']['published'] ? __('Published') : __('Unpublished')?></div>
        </div>

        <a id="removeLink" class="linkIcon" href="<?=$this->Html->url(array('controller' => 'Article', 'action' => 'delete', $id))?>" onclick="return confirm('<?=__('Are you sure to delete this record?')?>')" style="display: none;">
            <div class="glyphicons bin"></div>
            <div class="caption"><?=__('Remove')?></div>
        </a>
<?
     }
?>

    </div>
    <div class="col-sm-8 col-sm-pull-4">
        <h2 id="articleTitle"><?=Hash::get($article, 'Article.title')?></h2>
    </div>
</div>

<div class="articleAuthor fixedLayout">
    <div class="item">
        <span class="date">
            <span class="glyphicons clock"></span><?=$this->LocalDate->dateTime($article['Article']['created'])?>
        </span>
        <span class="info">
<?
    if(!$article['Article']['group_id']) {
?>
        <?php echo $this->Avatar->userLink($user, array(
            'size' => 'thumb100x100'
        )); ?>
        <a href="<?=$this->Html->url(array('controller' => 'User', 'action' => 'view', $user['User']['id']))?>" class="underlink"><?=$user['User']['full_name']?></a>
<?
        if($currUserID){
            if($user['User']['id'] !== $currUserID) {
                if($subscription) {
?>
    <a href="<?=$this->Html->url(array('controller' => 'User', 'action' => 'deleteSubscription', $subscription['Subscription']['id']))?>" class="btn btn-default"><?=__('Unsubscribe')?></a>
<?
                } else {
?>
    <a href="<?=$this->Html->url(array('controller' => 'User', 'action' => 'addSubscription', $user['User']['id']))?>" class="btn btn-default"><?=__('Subscribe')?></a>
<?
                }
            }
        } else {
?>
    <a href="#register-popup" class="btn btn-default register-btn"><?=__('Subscribe')?></a>
<?
        }
    } else {
?>
        <?php echo $this->Avatar->groupLink($group, array(
            'size' => 'thumb100x100'
        ))?>
        <a href="<?=$this->Html->url(array('controller' => 'Group', 'action' => 'view', $group['Group']['id']))?>" class="underlink"><?=$group['Group']['title']?></a>
<?
        if($currUserID){
            if(!$isGroupAdmin) {
                if($subscription) {
?>
    <a href="<?=$this->Html->url(array('controller' => 'Group', 'action' => 'deleteSubscription', $subscription['Subscription']['id']))?>" class="btn btn-default"><?=__('Unsubscribe')?></a>
<?
                } else {
?>
    <a href="<?=$this->Html->url(array('controller' => 'Group', 'action' => 'addSubscription', $article['Article']['group_id']))?>" class="btn btn-default"><?=__('Subscribe')?></a>
<?
                }
            }
        }
    }
?>
        </span>
    </div>

<?
    if($isArticleAdmin) {
        $aCategoryOptions[0] = __('Select category').'...';
?>
    <div style="display: inline-block; height: 31px; margin-top: 20px;">
        <div id="ArticleCategoryBlock" style="display: none; height: 31px;">
            <?=$this->Form->input('cat_id', array('options' => $aCategoryOptions, 'placeholder' => __('Article section').'...', 'label' => false, 'class' => 'formstyler', 'id' => 'ArticleCategory', 'value' => $article['Article']['title'] ? $article['Article']['cat_id'] : '0'))?>
        </div>
    </div>
<? } ?>
</div>

<?php if($isArticleAdmin) {
           if(!$article['ArticleMedia']['id']) {
            $article['ArticleMedia']['url_img'] = '/img/no-photo-wide.jpg';
           }
?>
    <div class="leftFormBlock" style="max-width: 300px">
        <div class="avatar-img-article">
            <img id="Article<?=$id?>" src="<?=$this->Media->imageUrl($article['ArticleMedia'], '600x')?>" alt="<?=$article['Article']['title']?>" title="<?=$article['Article']['title']?>" class="img-responsive"  data-resize="600x" data-media_id="<?=$article['ArticleMedia']['id']?>"/>
        </div>
        <div id="imageUploadControls" style="display: none;">
            <div class="inputFile">
                <input class="filestyle fileuploader" type="file" data-object_type="Article" data-object_id="<?=$id?>" data-progress_id="progress-Article<?=$id?>" accept="image/*"/>
            </div>
            <div class="progress" id="progress-Article<?=$id?>" style="height: 0">
                <div class="progress-bar progress-bar-info" role="progressbar">
                    <span id="progress-stats"></span>
                </div>
            </div>
        </div>
    </div>
    <br />
    <br />
<?php } else if($article['ArticleMedia']['id'] && !$article['Article']['video_url'] ) { ?>
    <div style="max-width: 400px;">
        <img src="<?=$this->Media->imageUrl($article['ArticleMedia'], '600x')?>" alt="<?=$user['User']['full_name']?>" title="<?=$user['User']['full_name']?>" style="width: 100%">
    </div>

    <br />
    <br />
<?php } ?>
<div class="clearfix"></div>
<div class="articleView fixedLayout" id="articleView-id">

<?
    if($article['Article']['type'] == 'text') {
?>
    <?=Hash::get($article, 'Article.body')?>
<?
    } else {
        $video_id = Hash::get($article, 'Article.video_url');
        $video_id = str_replace(array('http://', 'https://', 'www.', 'youtube.com/', 'youtu.be/', 'watch?v='), '', $video_id);
        list($video_id) = explode('&',$video_id);
?>
    <div class="fixedLayout">
        <iframe src="//www.youtube.com/embed/<?=$video_id?>?rel=0" frameborder="0" allowfullscreen width="100%"></iframe>
    </div>
<?
    }
?>
</div>
<div class="clearfix"></div>
<br />

<?php if( $article['Article']['title'] != null ) {
    echo $this->element('social_share', array( 'title' => $title, 'content' => $description, 'imageUrl' => $image_link));
    }
?>

<br />
<br />

<? if($article['Article']['title'] == null) { ?>
<div style="width: 100%; text-align: center;">
    <div class="btn btn-primary needsclick" id="publishSave" style="display: inline-block; margin-right: 20px;"><?=__('Publish')?></div>
    <div class="btn btn-default needsclick" id="draftSave" style="display: inline-block;"><?=__('Save as draft')?></div>
</div>
<? } ?>

<br />
<br />

<div id="articleComments"></div>

<?php if(!$currUserID){ ?>
    <div id="register-popup" class="mfp-hide" >
        <div id="password_form_block" class="hidable" style="display: none;">
            <?=$this->element('User/pass_forget_form')?>
        </div>

        <div id="register_form_block" >
            <?=$this->element('User/register_form')?>
        </div>
    </div>
<?php } ?>

<?php if($currUserID) : ?>

	<script type="text/x-tmpl" id="commentAnswer">
	<div class="inner submitMessage">
		<?php echo $this->Avatar->user($currUser, array(
			'class' => 'pull-left',
			'style' => 'width:50px;',
			'size' => 'thumb100x100'
		))?>
		<form class="message" id="ArticleInnerForm">
			<div class="form-group">
				<div id="sendChatSmile" class="icon_enter btn btn-default"><span class="smile"></span></div>
				<label><?=__('Leave your comment')?></label>
				<textarea id="innerMessageTitle" class="form-control" name="data[message]">{%= o.message ? o.message : '' %}</textarea>
				<div class="btn btn-default submitBtn"><span class="submitArrow"></span></div>
			</div>
			<input type="hidden" name="data[user_id]" value="<?=$currUserID?>">
	{% if(o.parent) { %}
			<input type="hidden" name="data[parent_id]" value="{%=o.parent%}">
	{% } %}

	{% if(o.article) { %}
			<input type="hidden" name="data[article_id]" value="{%=o.article%}">
	{% } %}

	{% if(o.event) { %}
			<input type="hidden" name="data[id]" value="{%=o.event%}">
	{% } %}
			<div class="clearfix"></div>
		</form>
	</div>
	</script>

<?php endif; ?>
