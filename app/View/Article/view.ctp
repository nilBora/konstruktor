<?php
    $this->Html->script(array(
        'jquery.mCustomScrollbar.concat.min.js',
        'dropzone.js',
        'select2.full.min.js',
        'vendor/bootstrap-datetimepicker.min',
        'vendor/bootstrap-datetimepicker.ru.js',
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
        'tinyPlugins/autoresize/plugin.min.js',
        '/vendor/formstyler.js'
    ), array('inline' => false));

    $css = array(
        'jquery.mCustomScrollbar.min.css',
        'dropzone.css',
        'select2.min.css',
        'bootstrap-datetimepicker.css',
        'content.min.css',
        'skin.min.css',
        '../skins_tiny/lightgray/content.min.css',
        '../skins_tiny/lightgray/skin.min.css',
        '../skins_tiny/lightgray/content.inline.min.css'
    );

$this->Html->css($css, array('inline' => false));



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

$aMonths = array( __(' January'), __(' February'), __(' March'), __(' April'), __(' May'), __(' June'),
	__(' July'), __(' August'), __(' September'), __(' October'), __(' November'), __(' December') );

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

?>

<script>

	jQuery(document).ready(function($) {
		$('.similar-article_list').mCustomScrollbar({});
		var myDropzone = new Dropzone('div#myId',{
			url: "/media/ajax/upload",
			autoProcessQueue: true,
			paramName : 'files',
			acceptedFiles: ".jpg, .png, .gif, .jpeg, .bmp",
			maxFilesize : 5,
			maxFiles : 1,
			parallelUploads: 1,
			addRemoveLinks: true
		});
		myDropzone.on("maxfilesexceeded", function(file) {
			this.removeFile(file);
		});
		myDropzone.on("removedfile", function(file) {
			var imageId = file.previewElement.dataId;
			$.ajax({
				type: 'POST',
				url: '/media/ajax/delete',
				dataType: 'JSON',
				data:{
					id: imageId,
					object_type: "Article",
					object_id: "<?php echo Hash::get($article,'Article.id');?>"
				},
				success: function(data){

				}
			});
		});
		myDropzone.on("success", function(file,response) {
				if( typeof response != 'object' ) {
					response = $.parseJSON(response);
				}
				var moveImage = response.files[0];
				moveImage.object_id = "<?php echo Hash::get($article,'Article.id');?>";
				moveImage.object_type = 'Article';
				var ret = '';
				$.ajax({
					type: "POST",
					url: "/media/ajax/move.json",
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
							file.previewElement.dataId = response.data[0].Media.id;
						}
					},
					error: function() {
						ret = 'error'
					}
				});
		});

		if( typeof $('#myId').attr('data-file') != 'undefined' ) {
			var dataFileJson = $.parseJSON($('#myId').attr('data-file'));
			for(var i in dataFileJson) {
				var mockFile = {name: dataFileJson[i].name, size: dataFileJson[i].size, accepted: true};
				myDropzone.emit("addedfile", mockFile);
				myDropzone.emit("thumbnail", mockFile, dataFileJson[i].path);
				myDropzone.emit("complete", mockFile);
				mockFile.previewElement.dataId = dataFileJson[i].id;
				myDropzone.files.push(mockFile);
				myDropzone._updateMaxFilesReachedClass();
			}
		}

		function formatState (state) {
			if (!state.id) { return state.text; }
			var $state = $(
				//'<span><span class="st_img"><img src="../../html/img/min_logo_input.png" alt=""></span>'+ state.text + '</span>'
				'<span>'+ state.text + '</span>'
			);
			return $state;
		};

		// $('.selectStyler').select2({
		// 	templateResult: formatState,
		// 	templateSelection: formatState,
		// 	minimumResultsForSearch:-1
		// });

		$('.stylerSelectBig').styler({
		});

		$('.magnificP').on('click', function(e){
			e.preventDefault();
			$(this).siblings('.hiddenRecommended').toggleClass('showCat');
		});

		$('.selectStylerDate').datetimepicker({
			locale:'ru',
			autoclose:true,
			startDate: new Date()
		});

		var htmlClick = $()

		$('.tabsFroala').on('click', function(e){
			e.preventDefault();
			if (!$(this).hasClass('selected')) {
				$(this).addClass('selected').siblings().removeClass('selected');
				$('#articleView-id').data('fa.editable').html();
			};
		});

		    function scrollGo(x, y, z) {
		        var scrollerHeight;
		        var scrollerElement;
		        if (x) {
		            scrollerElement = y.offset().top;
		            scrollerHeight = scrollerElement;
		        } else {
		            scrollerHeight = 0;
		            scrollerElement = $(document).height();
		        }
		        var scrollerUpSdeed = ($(document).scrollTop() / scrollerElement).toFixed(2) * 1000;
		        if (z) {
		            scrollerUpSdeed = scrollerElement / 2;
		        }
		        $('body, html').stop().animate({
		            scrollTop: scrollerHeight
		        }, scrollerUpSdeed);
		    }

		        if ($('#scrollerUp').length) {
		        $(window).scroll(function() {
		            ($(this).scrollTop() > 500) ? $('#scrollerUp').stop().show(300) : $('#scrollerUp').stop().hide(300);
		        });
		        $('#scrollerUp').on('click', function() {
		            var scrollerUpSdeed = ($(document).scrollTop() / $(document).height()).toFixed(2) * 1000;
		            $('body, html').stop().animate({
		                scrollTop: 0
		            }, scrollerUpSdeed);
		        });
		    }

		    $('.data_scroll').on('click', function(event) {
		        event.preventDefault();
		        scrollGo(true, $('.' + $(this).data('link')), true)


    });
			$(document).mouseup(function(e) {
			    var div = $(".hiddenRecommended, .magnificP");
			    if (!div.is(e.target) && div.has(e.target).length === 0) {
			        div.removeClass('showCat');
			    }
			});

	});

</script>

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

	.froala-view.froala-element.not-msie.f-inline p{
		line-height: 35px;
		margin:0 14px;
	}

	.froala-box:after{
		display: none;
	}

	.froala-element.f-placeholder+span.fr-placeholder{
		top:10px;
		left:14px;
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
		border: 1px dashed #c9c9c9;
		border-radius: 4px;
		box-sizing:border-box;
		font-size:16px;
		text-align:left;
		height: 36px;
		line-height: 36px;
		padding:0 15px;
		color:#999;
		font-family: 'Open Sans', sans-serif;
		font-weight: 400;
	}

	.froala-element.f-placeholder + span.fr-placeholder{
		color:#999999;
		font-size:16px;
		font-family: 'Open Sans', sans-serif;
		font-weight: 400;
	}

	a[href="http://editor.froala.com"]{
		display: none !important;
		background-color:transparent !important;
	}

	.froala-wrapper + div{
		display: none !important;
		border:none !important;
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

<style type="text/css">
	#breadcrumbs{
		text-align:right;
		padding-left: 28px;
	}

	#breadcrumbs a:last-child{
		text-transform: uppercase;
	}

	.fixedLayout{
		max-width: 1170px;
		padding:0 30px;
	}

	.similar-article_title{
		padding-top: 30px;
	}

	.similar-article_title, .similar-tag_title{
		color:#222222;
		font-size:14px;
		font-family: 'Open Sans', sans-serif;
		text-transform: uppercase;
		font-weight: 700;
		text-align: center;
		margin-bottom: 32px;
	}

    .similar-article_item{
        margin-bottom:55px;
    }

    .similar-article_item:last-child{
        margin-bottom:0;
    }


    .similar-article_item-title{
        color:#666666;
        font-size:14px;
        padding:0px 4%;
        font-family: 'Open Sans', sans-serif;
        font-weight: bold;
        text-decoration: none;
        text-transform: uppercase;
        margin-bottom: 15px;
        display: block;
    }

    .similar-article_item-title:hover{
    	color:#36b7ff;
    }

    .authorInformation{
    	padding:0px 4%;
        line-height: 27px;
    }

    .nameAuthorArt{
    	color:#999999;
    	font-size:12px;
    	font-family: 'Open Sans', sans-serif;
    	font-weight: 400;
    	margin-right: 14px;
    }

    .nameAuthorArt p{
        margin:0;
    }

    .tagsLinkThemes a{
		font-family: 'Open Sans', sans-serif;
		font-size:12px;
	    color:#36b7ff;
	    text-decoration: none;
    }

	.similar-article_item-image, .nameAuthorArt, .tagsLinkThemes{
		float: left;
	}

	.similar-article_item-date, .similar-article_item-users, .similar-article_item-backing{
		font-size:12px;
		color:#999999;
		font-family: 'Open Sans', serif;
		font-weight: 400;
		letter-spacing: 1px;
	}

	.similar-article_item-backing a{
		text-decoration: none;
	    color: #999999;
	}

	.similar-article_item-backing{
		position: relative;
	}

	.items-tags{
		float: left;
		vertical-align: top;
		height: 30px;
		line-height: 30px;
		padding:0px 15px;
		margin-right: 7px;
		margin-top: 8px;
		border:1px solid #eeeeee;
		font-family: 'Open Sans', serif;
		color:#999999;
		font-size:14px;
	}

	.items-tags a{
		font-family: 'Open Sans', serif;
		color:#999999;
		font-size:14px;
		text-decoration: none;
	}

    .similar-article_list{
        max-height: 728px;
        border-top:none;
        border-left:1px solid #e6e6e6;
        border-right:1px solid #e6e6e6;
        border-bottom:1px solid #e6e6e6;
        border-radius: 4px;
        box-shadow: 0px 2px 2px 0px rgba(234,241,245,1);
    }

	.similar-article_list, .tag-cloud_items{
		border-bottom:1px solid #cecdcc;
		background:#fff;
		border-radius: 4px;
	}

	.similar-article_list + .similar-article_list{
		margin-top: 38px;
	}

	.similar-article_list + .tag-cloud_items{
		margin-top: 38px;
		padding-top:30px;
	}

    .tag-cloud_items{
        padding:0px 4% 30px;
        border-top:none;
        border-left:1px solid #e6e6e6;
        border-right:1px solid #e6e6e6;
        border-bottom:1px solid #e6e6e6;
        border-radius: 0 0 4px 4px;
        box-shadow: 0px 2px 2px 0px rgba(234,241,245,1);
    }

    .similar-article_item-image{
        float: left;
        margin-right: 8px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        overflow:hidden;
        line-height: 14px;
        text-align: center;
    }
    .similar-article_item-image img{
		height: 100%;
		width:100%;
		vertical-align: middle;
    }

    .wrapR{
        overflow: hidden;
        padding:10px 0;
        border-top:1px solid #eaf1f5;
        border-bottom:1px solid #eaf1f5;
    }

	.similar-article_item{
		display: block;
	}

    .clear{
        clear:both;
    }

    .similar-article_item-date, .similar-article_item-users, .similar-article_item-backing, .similar-article_item-comments{
        float: left;
        vertical-align: top;
        padding-left:24px;
    }

    .similar-article_item-comments{
    	padding-left:30px;
    }

    .date_article > div + div {
        margin-left: 30px;
    }



	.date_article  .similar-article_item-backing{
		padding-left:0;
	}

    .date_article div + a{
    	margin-left: 30px;
    }

    .similar-article_item-meta{
    	text-align: center;
    }
    .similar-article_item-meta div:first-child{
		margin-left:0;
		margin-right: 2%;
    }
    .similar-article_item-meta div:last-child{
		margin-right: 0;
    }

    .similar-article_item-meta div{
    	display: inline-block;
    	vertical-align: top;
        margin: 0px 13%;
        float: none;
    }

    .similar-article_item-date{
        background:url(/img/date.png)no-repeat center left;
        background-size:16px 16px;
    }

    .similar-article_item-users{
		background:url(/img/ViewsPic.png)no-repeat center left;
		background-size:16px 11px;
    }

    .similar-article_item-backing{
        background:url(/img/share-icon.png)no-repeat center left;
        background-size:16px 16px;
    }


    .downWrapp .similar-article_item-backing{
    	background:none;
    	background-size:0;
    }

    .downWrapp .similar-article_item-backing > a{
    	padding-left:24px;
        background:url(/img/share-icon.png)no-repeat center left;
        background-size:16px 16px;
    }

    .similar-article_item-comments{
        background:url(/img/comments.png)no-repeat center left;
		background-size:21px 15px;
    }

    .similar-article_item-body{
        display: block;
        margin-top: 12px;
    }

    .imgAuthor{
        border-radius:50%;
        overflow:hidden;
        width: 32px;
        height: 32px;
        margin-right:8px;
        float: left;
    }

	.imgAuthor img{
		width: 100%;
		height: 100%;
		vertical-align: top !important;
	}

    .wrapperLeft{
    	background:#fff;
        margin-top: 29px;
        padding-bottom:52px;
        padding-left:0px;
        padding-right:0px;
        border: 1px solid #ccc;
        border-top:none;
        border-left:1px solid #e6e6e6;
        border-right:1px solid #e6e6e6;
        border-bottom:1px solid #e6e6e6;
        border-radius:4px 4px 4px 4px;
        box-shadow: 0px 2px 0px 0px rgba(0,0,0,0.1);
    }

	.wrappRight{
		margin-top: 29px;
		border-radius:4px 4px 4px 4px;
	}

    .description_author-article{
    	float: left;
        color:#999999;
        font-size:14px;
        margin-top: 6px;
        font-family: 'Open Sans', sans-serif;
        max-width: 100px;
        margin-right: 20px;
        white-space: nowrap;
        text-overflow:ellipsis;
        overflow: hidden;
    }

	.upperW{
		text-align: center;
	}

	.imgWrapBig{
		margin-top: 38px;
	}

    .date_article{
    	float: left;
        margin-top: 8px;
    }

	.date_article .similar-article_item-date, .date_article .similar-article_item-users{
		font-size:12px;
		font-family: 'Open Sans', sans-serif;
		font-weight: 400;
		letter-spacing: 1px;
	}

	.author_words{
		display: inline-block;
		color:#333333;
		font-family: 'Open Sans', sans-serif;
		font-weight: bold;
		font-style:italic;
		font-size:16px;
		text-align: center;
		padding:0px 14%;
		margin-top:46px;
	}

	.contentText{
		padding-bottom:20px;
		margin-top:20px;
	}

	.contentText img{
		max-width:100%;
		height:auto;
	}

	.contentText p{
		padding:0px 10%;
		font-family: 'Open Sans', sans-serif;
		font-size: 16px;
		color:#666666;
		letter-spacing: 0px;
		margin:16px 0;
	}

	.contentText a{
		color:#36b7ff;
	}

	.form-control:focus{
	    border-color: #dcdcdc;
		box-shadow: none;
	}

	.contentText blockquote{
		border-left: 8px solid #12d09b;
		padding: 0px 10% 0 3%;
		font-size: 16px;
		margin-left: 13%;
		font-family: 'Open Sans', sans-serif;
		font-weight: 700;
		font-style:italic;
	}

	.titleComment{
		text-transform: uppercase;
		color:#666666;
		font-size: 16px;
		font-family: 'Open Sans', sans-serif;
		font-weight: bold;
		margin-bottom:31px;
	}

	.textAreaComment{
		color:#999999;
		border:1px solid #dcdcdc;
		border-radius:4px;
		padding:14px;
		height:100px !important;
		box-sizing:border-box;
		resize:none;
		font-family: 'Open Sans', sans-serif;
		font-weight: 400;
	}
	.textAreaComment:hover{
		background:rgba(238, 238, 238, 0.30);
	}

	.textAreaComment:focus{
		outline:none;
	}

	.item.upperComment + .commentsArticle, .item.subComment + .commentsArticle{
		width:100%;
	}

	.btnComment{
		border: 2px solid #ffc24c;
		border-radius: 4px;
		background: #fff;
		color: #ffc24c;
		height: 32px;
		line-height: 30px;
		box-sizing: border-box;
		font-family: 'Open Sans', sans-serif;
		font-weight: bold;
		float: right;
		max-width: 120px;
		width: 100%;
		margin-top: 10px;
	}

	.btnComment:hover{
		color: #fff;
	    border-color: transparent;
	    background-color: #ffb547;
	}

	.btnComment:focus, .btnComment:active{
		outline: 0;
	    background-image: none;
	    box-shadow: inset 0 0 7px rgba(0, 0, 0, .2);
	}

	.imgComment{
		display: inline-block;
	    vertical-align: top;
	    margin-right: 3%;
	    width: 17%;
		max-height:100px;
	}

	.imgComment + .submitMessage{
		width: 79%;
		display: inline-block;
		vertical-align: top;
	}

	.wrapperTabsFroala{
		margin-top: 62px;
		text-align:right;
		position: relative;
		top:3px;
	}



	.froala-editor{
		border:1px solid #bcbcbc;
		border-radius:4px 4px 0 0;
		background:#f5f5f5;
	}

	.froala-wrapper.f-basic{
		border:1px solid #bcbcbc;
		border-radius:0px 0px 4px 4px;
	}

	.froala-element.f-basic.f-placeholder+span.fr-placeholder{
		margin:0;
	}

	.froala-editor span.f-sep{
		height: 24px;
		border-color:#d3d3d3;
		border:none;
		background:#d3d3d3;
		width:1px;
		margin:9px 0;
	}

	.froala-editor button.fr-bttn, .froala-editor button.fr-trigger{
		color:#aaaaaa;
	}

	.f-html .froala-element{
		background:#fff;
	}

	.f-html .froala-element textarea{
		color:#999;
	}

	.froala-editor .fr-dropdown .fr-dropdown-menu{
		background:#f5f5f5;
		border: 1px solid #bcbcbc;
		top:42px;
		left:-1px;
	}

	.froala-editor button.fr-bttn, .froala-editor button.fr-trigger{
		line-height: 42px;
	}

	.froala-editor .fr-dropdown .fr-dropdown-menu li a{color: #aaaaaa;}

	.froala-editor .fr-dropdown .fr-dropdown-menu li a:hover{
		background: #B4B4B4!important;
	}

	.froala-editor .fr-dropdown .fr-dropdown-menu li.active a{
		background: #858585 !important;
	}

	.froala-editor .fr-trigger:after{
		border-top-color: #848484;
		top: 18px;
		right: 6px;
	}

	.froala-box.fr-fullscreen{
		max-width: 100%;
	}

	.froala-wrapper.f-basic{
		max-height:100%;
	}

	.putArt{
		height: 42px;
		line-height: 42px;
		font-size:16px;
		max-width: 140px;
	    color: #fff;
	    background-color: #fbaa31;
		font-family: 'Open Sans', sans-serif;
		border-radius: 4px;
		box-shadow: 0px 2px 4px rgba(0,0,0,0.3);
		border:none;
	}

	.putArt:hover{
	    color: #fff !important;
	    background-color: #ffb547 !important;
	}

	.putArt:active{
        box-shadow: inset 0 0 7px rgba(0, 0, 0, .2);
        background-color: #ffb547 !important;
	}

	.chernArt{
		color:#999999;
		border:none;
		max-width: 174px;
		height: 42px;
		text-align: center;
		line-height: 42px;
	}

	.chernArt:hover{
		color:#ffb547;
	}

	.chernArt:active{
		box-shadow: none;
	}

	.putArt, .chernArt{
		display: block !important;
		padding:0;
		margin:30px auto !important;
	}

	.wrapperLeft.art_create{
		float: none;
		margin:52px auto 0;
		border:none;
		background:transparent;
	}

    .linkDel{
        float: right;
        display: inline-block;
        vertical-align: middle;
        width: 24px;
        height: 24px;
        border:2px solid #c8c8c8;
        border-radius:50%;
        background:url(/img/shop_pic.png)no-repeat center center;
        background-size: 12px 12px;
        -webkit-transition: opacity 0.3s ease;
        -o-transition: opacity 0.3s ease;
        transition: opacity 0.3s ease;
    }

    .linkDel:hover{
        opacity:0.7;
    }

    .linkEditBtn{
        float: right;
        display: inline-block;
        vertical-align: middle;
        width: 24px;
        height: 24px;
        border-radius:50%;
        background:url(/img/edit_pic.png)no-repeat center center;
        background-size:12px 12px;
        -webkit-transition: opacity 0.3s ease;
        -o-transition: opacity 0.3s ease;
        transition: opacity 0.3s ease;
    }

    .linkEditBtn + .linkDel{
    	margin-right:8px;
    }

    .linkEditBtn:hover{
        opacity:0.7;
    }

	.changesArt{
		color:#666666;
		font-family: 'Open Sans', sans-serif;
		font-size: 16px;
	}

	.wrapperTopChanges{
		margin-bottom: 0px;
		margin-top:8px;
		margin-right:8px;
	}

	.select2-container{
		max-width: 370px !important;
		width:100% !important;
	}

.select2-container--default .select2-selection--single{
		border:1px solid #dcdcdc;
		border-radius:4px;
		outline:none;
	}

	.select2-container--open .select2-dropdown--below{
		margin-top:17px;
		border-radius:4px 4px 0 0;
	}

	.select2-container--open .select2-dropdown--below:before{
		content: '';
		position: absolute;
		left: -1px;
		box-sizing: content-box;
		width: 100%;
		height: 6px;
		padding: 0 1px;
		border-radius: 4px 4px 0 0;
		background-color: #fbaa31;
		bottom:99%;
	}

	.select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b{
		border-color: transparent transparent #fbaa31 transparent;
		border-width: 0 4px 5px 4px;
	}

	.select2.select2-container.select2-container--default.select2-container--focus.select2-container--above.select2-container--open .select2-selection.select2-selection--single{
		border:1px solid #fbaa31;
		border-radius:4px;
		outline:none;
	}

	.select2-dropdown.select2-dropdown--above{
		margin-top: -17px;
		border-bottom:none;
	}	

	.select2-dropdown.select2-dropdown--above:after{
	    content: ' ';
	    position: absolute;
	    left: 46px;
	    width: 0;
	    height: 0;
	    margin-left: -6px;
	    pointer-events: none;
	    border: solid transparent;
	    border-width: 6px;
	    border-color: rgba(251, 170, 49, 0);
	    bottom: 0;
	    border-top-color: #fbaa31;
	    margin-bottom: -12px;
	}	

	.select2-dropdown.select2-dropdown--above:before{
		content: '';
		position: absolute;
		left: -1px;
		box-sizing: content-box;
		width: 100%;
		height: 6px;
		padding: 0 1px;
		border-radius: 0 0 4px 4px;
		background-color: #fbaa31;
		bottom: 0;
	}

	.select2-container--open .select2-dropdown--below:after{
		content: ' ';
		position: absolute;
		left: 46px;
		width: 0;
		height: 0;
		margin-left: -6px;
		pointer-events: none;
		border: solid transparent;
		border-width: 6px;
		border-color: rgba(251, 170, 49, 0);
		bottom: 99%;
	    border-bottom-color: #fbaa31;
	    margin-bottom:6px;
	}



	.select2-container--default .select2-results__option--highlighted[aria-selected]{
		background:#f1f1f1;
		color:#666;
	}

	.select2-dropdown{
        border-color: #fbaa31 !important;
        border-radius:0px 0px 4px 4px;
	}

	.select2-results li + li{
		border-top:1px solid #bbb;
	}

	.select2.select2-container--below.select2-container--open .select2-selection.select2-selection--single{
		border:1px solid #ffa836;
		border-radius:4px;
		outline:none;
	}

	

	.select2-container .select2-selection--single{
		height:32px;
		line-height: 32px;
	}

	.select2-container .select2-selection--single .select2-selection__rendered{
		padding-left:6px;
	}

	.select2-container--default .select2-selection--single .select2-selection__rendered{
		color:#666666;
		font-family: 'Open Sans', sans-serif;
		text-transform: uppercase;
		font-weight: bold;
		font-size: 14px;
		line-height: 32px;
	}

	.select2.select2-container.select2-container--default.select2-container--below.select2-container--open.select2-container--focus, .select2.select2-container.select2-container--default.select2-container--below.select2-container--focus{
		outline:none !important;
		box-shadow: none;
	}

	.select2-container--default .select2-selection--single .select2-selection__arrow{
		height: 32px;
	}

	.select2-container--default .select2-selection--single .select2-selection__arrow b{
		border-color: #bbbbbb transparent transparent transparent;
	}

	.wrappSoc{
		border-top:1px solid #dcdcdc;
		max-width: 630px;
		width:80%;
		margin:0 auto;
		padding:18px 0;
	}

    .socLinks a{
        float: left;
        vertical-align: top;
        width:28px;
        height: 28px;
        border-width:2px;
        border-style: solid;
        line-height:22px;
        border-radius: 50%;
        margin-right: 5px;
        text-align: center;
        position: relative;
    }

    .socIconsInside a{
    	display: inline-block;
    	vertical-align: top;
    	margin: 0px 5%;
        border-width:2px;
        border-style: solid;
        line-height:36px;
        border-radius: 50%;
        width:34px;
        height: 34px;
        text-align: center;
    }

    .socIconsInside{
    	text-align: center;
    	margin:28px -26% 0;
    }

     .socIconsInside a{
		width:34px;
		height: 34px;
     }

    .socLinks a span, .socIconsInside a span{
        display: inline-block;
        vertical-align: middle;
    }

    .lFb:hover svg{
       fill:#3a589e;
       width:12px !important;
       height: 12px !important;
    }

    .lFb svg{
       width:12px !important;
       height: 12px !important;
       fill:#bbbbbb;
    }

    .fb svg{
       width:18px !important;
       height: 18px !important;
       fill:#bbbbbb;
    }

    .lFb, .fb{
        border-color:#bbbbbb;
    }

    .fb:hover svg{
    	fill:#3a589e;
    }

    .ggpl:hover svg{
    	fill:#de4e43;
    }

    .lGg, .ggpl{
        border-color:#bbbbbb;
    }

    .lGg:hover, .ggpl:hover{
    	border-color:#de4e43;
    }

    .lGg:hover svg{
		fill:#de4e43;
		width:12px !important;
		height: 12px !important;
    }

    .lTw, .twt{
        border-color:#bbbbbb;
    }

    .lTw:hover svg{
		fill:#55acee;
    }

    .twt:hover svg{
		fill:#55acee;
    }

    .lMail{
        border-color:#bbbbbb;
    }

    .lMail:hover svg{
		fill:#7ce6ff;

    }

    .lMail:hover{
    	border-color:#7ce6ff;
    }

    .lFb:hover, .fb:hover{
        border-color:#3a589e;
    }

    .lGg svg{
        fill:#bbbbbb;
		width:12px !important;
		height: 12px !important;
    }

    .ggpl svg{
        fill:#bbbbbb;
		width:18px !important;
		height: 18px !important;
    }

    .lGg:hover{
        border-color:#de4e43;
    }

    .lTw svg{
        fill:#bbbbbb;
		width:12px !important;
		height: 12px !important;
    }

    .twt svg{
        fill:#bbbbbb;
		width:18px !important;
		height: 18px !important;
    }

    .lTw:hover, .twt:hover{
        border-color:#55acee;
    }

    .lMail svg{
        fill:#bbbbbb;
		width:12px !important;
		height: 12px !important;
    }

    .lMail:hover{
        border-color:#7ce6ff;
    }

	.skp{
		border-color:#bbbbbb;
	}

	.skp:hover{
		border-color:#00aff0;
	}

	.socLinks{
		float: left;
		margin-top: 5px;
	}

	.tagsRight{
		float: right;
	}

	.tagsRight .items-tags{
		margin-top: 0;
	}

    .linesBot{
        width:80%;
        background:#dcdcdc;
        height:2px;
        margin:3px auto;
    }

	.titleArticleUser{
		font-size:20px;
		font-family: 'Open Sans', sans-serif;
		font-weight: bold;
		text-transform: uppercase;
	}

	.imgWrappPreview{
		margin-top: 30px;
	}

	.commentsArticle{
		width:80%;
		margin:48px auto 0;
	}

	.wrapperSelectsBottom{
		margin-top: 32px;
	}

	.st_img{
		margin-right: 10px;
		border:2px solid #28a89d;
		border-radius: 50%;
		height: 24px;
		width: 24px;
		display: inline-block;
		text-align: center;
		line-height: 18px;
	}

	.st_img img{
		vertical-align: middle;
	}

	.select2-results__option{
		color:#666;
		font-size:14px;
		font-family: 'Open Sans', sans-serif;
		font-weight: bold;
	}


	.groupPick, .datePick{
		max-width: 340px;
		width:100%;
	}

	.groupPick:focus{
		outline:none;
		box-shadow: none;
	}

	.groupPick{
		float:left;
	}

	.datePick{
		float:right;
	}

	.selectStylerDate{
		border:1px solid #c9c9c9;
		border-radius: 4px;
		max-width:370px;
		width:100%;
		height: 36px;
		line-height: 36px;
	}

	.selectStylerDate.input-group .form-control{
		text-align:right;
		color:#999;
		font-size:14px;
		font-family: 'Open Sans', sans-serif;
	}

	.input-group .input-group-addon{
		padding: 0 15px 0 6px;
		background:#fff;
	}

	.selectStylerDate.input-group.date .input-group-addon .glyphicon.glyphicon-th{
		width:16px;
		height:18px;
		margin-right: 5px;
	}

	.selectStylerDate.input-group.date .input-group-addon .textPublic{
		display: inline-block;
		vertical-align: top;
		margin-top: 2px;
		font-size:16px;
		color:#666;
		font-family: 'Open Sans', sans-serif;
		font-weight: bold;
		width:auto;
		height:auto;
	}

	.selectStylerDate .glyphicon.glyphicon-th{
		display: inline-block;
		width: 16px;
		height: 18px;
		background:url(../../html/img/date_pick_art.png)no-repeat center center;
	}

	.wrapper-btn-public-save{
		margin-top: 40px;
	}

	.selectStylerDate .form-control:focus{
		border:none;
		box-shadow: none;
	}

	.select2-selection__rendered:focus{
		outline:none;
		box-shadow: none;
	}

	.stylerSelectBig.jq-selectbox.opened .jq-selectbox__select, .stylerSelectBig.jq-selectbox .jq-selectbox__select:active{
	    background: #fff;
	    border-color: #ffa836;
	    color: #666;
	}

	.stylerSelectBig .jq-selectbox__select{
		height:36px;
	}

	.stylerSelectBig .jq-selectbox__trigger-arrow{
		top:15px;
		width:9px;
		height: 6px;
		background:url(/img/arrw_spr.png);
	}	

	.stylerSelectBig .jq-selectbox__dropdown{
		margin-left:0 !important;
		box-shadow: none;
		border: 1px solid #ffa836;
		margin-top:16px;
	}

	.stylerSelectBig .jq-selectbox__select-text{
	    color: #666666;
	    font-family: 'Open Sans', sans-serif;
	    text-transform: uppercase;
	    font-size: 14px;
	    line-height: 28px;
	    font-weight: bold;
	}


	.stylerSelectBig  li + li{
		border-top:1px solid #666;
	}

	.stylerSelectBig .jq-selectbox__dropdown:before{
		content: '';
		position: absolute;
		left: -1px;
		box-sizing: content-box;
		width: 100%;
		height: 6px;
		padding: 0 1px;
		border-radius: 4px 4px 0 0;
		background-color: #fbaa31;
		bottom: 99%;
	}

	.stylerSelectBig .jq-selectbox__dropdown:after{
	    content: ' ';
	    position: absolute;
	    left: 46px;
	    width: 0;
	    height: 0;
	    margin-left: -6px;
	    pointer-events: none;
	    border: solid transparent;
	    border-width: 6px;
	    border-color: rgba(251, 170, 49, 0);
	    bottom: 99%;
	    border-bottom-color: #fbaa31;
	    margin-bottom: 6px;
	}

	.stylerSelectBig.jq-selectbox{
		max-width:370px;
		width:100%;
	}

	.stylerSelectBig .jq-selectbox__select:hover{
		border-color:#ffb547 !important;
	}

	.stylerSelectBig li:hover{
	    background: #f1f1f1;
    	color: #666;
	}

	.dopMarT .bttn-wrapper button:last-child{
		float:right;
	}

	.tabsFroala{
		display: inline-block;
		vertical-align: top;
		cursor: pointer;
		border:1px solid transparent;
		border-bottom:none;
		padding:0px 10px;
		text-decoration: none;
		color:#999999;
		font-size: 12px;
		font-family: 'Open Sans', sans-serif;
		font-weight: 400;
		height: 28px;
		line-height: 28px;
		-webkit-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		-o-user-select: none;
		user-select: none;
	}

	.tabsFroala.selected{
		border-top:1px solid #c9c9c9;
		border-left:1px solid #c9c9c9;
		border-right:1px solid #c9c9c9;
		background:#f5f5f5;
		border-radius:4px 4px 0 0;
		position: relative;
		z-index:1111;
	}

	.tabsFroala:last-child.selected:before{
		content:'';
		position: absolute;
		bottom:0;
		right:0;
		width:10px;
		height:10px;
		background:#f5f5f5;
	}

    .shortDescrArt{
    	margin-top: 14px;
    	padding:0px 4%;
    }

    .tagsOut{
    	float: left;
    	margin-right: 24px;
        margin-top: 8px;
    }

    .tagsOut a{
        color:#4cbcff;
        font-size: 12px;
        font-weight: bold;
    	font-family: 'Open Sans';
        text-decoration: none;
    }

    .wrapperDownInfo{
    	padding:10px 12%;
    }

    .fll{
        float:left;
        margin-right: 20px;
    }

    .descriptionComment{
        overflow: hidden;
        padding-bottom:7px;
        border-bottom: 1px solid #eee;
    }

.upperComment{
    display: block;
    padding: 16px 0 21px 0;

}

.upperComment > a{
	width: 50px;
	height: 50px;
	float: left;
	margin-right: 20px;
}

.wrappTimeBtn{
	margin-top: 20px;
}

.subComment >a{
	width: 50px;
	height: 50px;
	float: left;
	margin-right: 20px;
}

.subComment{
    margin-left:74px;
    display: block;
    padding: 0 0 28px 0;
}

.stylingCom{
	width:100%;
	height: 100%;
}


.treeComments{
    max-width: 80%;
    margin:0 auto;
}

.textMsg{
    font-size: 15px;
    display: block;
}

.timeCom{
    font-size:11px;
    color: gray;
    float: left;
}

.sendMsg{
    float: right;
    text-decoration: none;
    color: #616161;
    font-size: 11px;
    display: block;
}

.imgUserWrapp{
	display: none;
}

.imgUserWrapp img{
    border-top-width: 0;
    border-left-width: 0;
    border-right-width: 0;
    border-bottom-width: 3px;
    border-style: solid;
    border-color: #cdf6f4;
    height: 100%;
    width:100%;
    vertical-align: middle;
}

.mce-fullscreen{
    z-index: 999;
}

	.art_create .imgAuthor,.art_create .tagsOut, .art_create .description_author-article, .art_create .wrapperTopChanges,
	.art_create .imgWrappPreview, .art_create .date_article, .art_create .wrappSoc,
	.art_create .linesBot, .art_create .commentsArticle,.art_create #articleComments, .art_create+.wrappRight {
		display:none;
	}
	.dropzone,.wrapperTabsFroala,.wrapper-btn-public-save, .wrapperSelectsBottom{
		display: none;
	}
	.art_create .dropzone, .art_create .wrapperTabsFroala, .art_create .wrapper-btn-public-save, .art_create .wrapperSelectsBottom{
		display:block;
	}

	.dropzone{
	    background: #f1f1f1 url(/img/back_drop.png)no-repeat center 64px;
	}

	.commentsArticle img{
		width:100%;
		height: 100%;
		max-height: 100px;
	}

	.commentsArticle .message{
		width:auto;
		overflow: hidden;
		margin:0;
	}

	body{
		background-color:#f9f9f9;
	}

	.titleArt{
		padding:0 4%;
		font-size: 20px;
		font-family: 'Open Sans', sans-serif;
		font-weight: 700;
		text-transform: uppercase;
	}

	.data_scroll, .data_scroll:focus, .data_scroll:active{
		text-decoration: none;
	    font-size: 12px;
	    color: #999999;
	    font-family: 'Open Sans', serif;
	    font-weight: 400;
	    letter-spacing: 1px;
	}

	.clickedBtn + .hiddenMail{
		position: absolute;
		top:0px;
		left:35px;
		visibility:hidden;
		width:260px;
		-webkit-transition: all 0.4s linear;
		-o-transition: all 0.4s linear;
		transition: all 0.4s linear;
		opacity:0;
		background:#fff;
	}

	.clickedBtn{
		position: relative;
	}

	.clickedBtn.widthForm + .hiddenMail{
		left:45px;
		visibility:visible;
		font-size: 14px;
		opacity:1;
		width:260px;
	}

	.lastM{
		height:28px;
		line-height: 28px;
		display: inline-block;
		position: relative;
	}

	.hiddenMail .inpMail{
		border:1px solid #c8d0de;
		border-radius: 4px;
		line-height:28px;
		padding:0px 10px;
		background:#fff;
		height: 28px;
		float:left;
		margin-right: 8px;
	}

	.hiddenMail .inpBtn{
		background:#fff;
		border:1px solid #fbaa31;
		font-size: 14px;
		color:#fbaa31;
		height:28px;
		line-height: 28px;
		float:left;
		border-radius: 4px;
	}

	#shareToEmail .error {
		position: absolute;
		color: #ff2222;
		font: 12px 'Open Sans', sans-serif;
		margin-left: 3px;
		width: 183px;
	}

	#shareToEmail .success {
		font: 14px 'Open Sans', sans-serif;
		color: #11C128;
		white-space: nowrap;
		position: relative;
		top: 5px;
		left: 5px;
	}

	.uppWrap, .downWrapp{
		display: block;
		text-align: center;
	}

	.uppWrap{
		line-height: 32px;
	}

	.uppWrap .description_author-article{
		overflow:visible;
		white-space: normal;
		text-overflow:none;
		max-width: 100%;
	}

	.description_author-article b{
		font-weight: normal;
	}

	.downWrapp{
		margin-top: 20px;
	}

	.downWrapp .date_article{
		float: none;
	}

	.uppWrap > div, .downWrapp .date_article > div, .downWrapp .date_article > a{
		float: none !important;
		display: inline-block;
		vertical-align: top;
		margin:0 4%;
	}

	.uppWrap div{
		margin:0px 1%;
	}

	div.mce-edit-area{
		padding:0px 60px;
	}

	/* defualt-datetimepicker */
.defualt-datetimepicker[readonly] {
    color:#959595 !important;
    border-color:#bbb !important;
    background-color:#fff !important;
}

.dropdown-menu.datetimepicker {
    width:294px;
    margin-top:17px;
    padding:20px;
    color:#666;
    border-color:#fbaa31;
    border-radius:0 0 4px 4px;
}

[class*=' datetimepicker-dropdown']:before {
    content:'';
    position:absolute;
    top:auto;
    bottom:100%;
    left:-1px;
    display:block;
    box-sizing:content-box;
    width:100%;
    height:6px;
    padding:0 1px;
    border:none;
    border-radius:4px 4px 0 0;
    background-color:#fbaa31;
}

[class*=' datetimepicker-dropdown']:after {
    content:' ';
    position:absolute;
    top:auto;
    bottom:100%;
    left:46px;
    width:0;
    height:0;
    margin-bottom:6px;
    margin-left:-6px;
    pointer-events:none;
    border:solid transparent;
    border-width:6px;
    border-color:rgba(251, 170, 49, 0);
    border-bottom-color:#fbaa31;
}

.datetimepicker th.switch {
    font-size:16px;
    line-height:1.45em;
    width:180px;
    text-transform:uppercase;
}

.datetimepicker thead tr:first-child th:hover,
.datetimepicker tfoot tr:first-child th:hover {
    border-radius:30px;
    background-color:#f1f1f1;
}

.table-condensed {
    width:100%;
}
.table-condensed .dow,
.table-condensed .month {
    text-transform:uppercase;
}
.table-condensed .prev .glyphicons,
.table-condensed .next .glyphicons {
    display:block;
    width:22px;
    height:22px;
    opacity:.5;
    background-repeat:no-repeat;
    background-position:center center;
}
.table-condensed .prev .glyphicons:before,
.table-condensed .next .glyphicons:before {
    display:none;
}
.table-condensed .prev:hover .glyphicons,
.table-condensed .next:hover .glyphicons {
    opacity:1;
}
.table-condensed .prev .glyphicons {
    background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAOBAMAAADkjZCYAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAALVBMVEX///9ERERERERERERERERERERERERERERERERERERERERERERERERERET///+Ho0KDAAAADXRSTlMAEbsz7plmiHfMRKpVEja6iwAAAAFiS0dEAIgFHUgAAAAJcEhZcwAACxIAAAsSAdLdfvwAAABFSURBVAjXY2BgYGBUBhIMJleBBJtvAZDsvQYkRK8YAKXmTgQyRS4JAEkusByD5W0Qh1F3MojDeuUAiMq9ASK53EAkgwAAE6AMfBxic2QAAAAASUVORK5CYII=);
}
.table-condensed .next .glyphicons {
    margin-left:4px;
    background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAOBAMAAADkjZCYAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAALVBMVEX///9ERERERERERERERERERERERERERERERERERERERERERERERERERET///+Ho0KDAAAADXRSTlMAM7sRme6IZnfMRKpV4BK9LAAAAAFiS0dEAIgFHUgAAAAJcEhZcwAACxIAAAsSAdLdfvwAAABBSURBVAjXYxAyYAAC30AQyXa1AETV3gCRjLHOIMryJkgBs24wWH4BkLl3IpApeQkozBPrAGT2XgepK10AIplBBAAc1wzza4ZqYQAAAABJRU5ErkJggg==);
}

.datetimepicker-years .table-condensed > tbody > tr:first-child > td,
.datetimepicker-months .table-condensed > tbody > tr:first-child > td,
.datetimepicker-hours .table-condensed > tbody > tr:first-child > td,
.datetimepicker-minutes .table-condensed > tbody > tr:first-child > td,
.datetimepicker-days .table-condensed > thead > tr:last-child > th {
    padding-top:20px;
}

.datetimepicker-days .table-condensed > thead > tr:last-child > th {
    color:#444;
}

.datetimepicker table tr td.old,
.datetimepicker table tr td.new {
    color:#bbb;
}

.datetimepicker table tr td {
    position:relative;
    /* &:hover {

            background: $colorLightF1;

        } */
}
.datetimepicker table tr td.day {
    width:36px;
    height:36px;
    border-radius:30px;
}
.datetimepicker table tr td.day:after {
    content:'';
    position:absolute;
    position:absolute;
    z-index:-1;
    top:0;
    right:0;
    bottom:0;
    left:0;
    display:block;
    width:30px;
    height:30px;
    margin:auto;
    border-radius:30px;
}
.datetimepicker table tr td.day:hover {
    background-color:transparent;
}
.datetimepicker table tr td.day:hover:after {
    background:#f1f1f1;
}
.datetimepicker table tr td.day.today {
    color:#fff;
    background:none !important;
    text-shadow:0 -1px 0 rgba(0, 0, 0, .25);
}
.datetimepicker table tr td.day.today:hover {
    color:#444;
    text-shadow:none;
}
.datetimepicker table tr td.day.today:after {
    background:#36b7ff !important;
}
.datetimepicker table tr td.active,
.datetimepicker table tr td.active.today,
.datetimepicker table tr td .active {
    background-color:#fbaa31 !important;
}
.datetimepicker table tr td.active:hover,
.datetimepicker table tr td.active.today:hover,
.datetimepicker table tr td .active:hover {
    color:#444 !important;
    text-shadow:none !important;
}
.datetimepicker table tr td .year,
.datetimepicker table tr td .month {
    position:relative;
    background:none !important;
}
.datetimepicker table tr td .year:after,
.datetimepicker table tr td .month:after {
    width:50px;
    height:50px;
}
.datetimepicker table tr td .year:after,
.datetimepicker table tr td .month:after {
    content:'';
    position:absolute;
    position:absolute;
    z-index:-1;
    top:0;
    right:0;
    bottom:0;
    left:0;
    display:block;
    margin:auto;
    border-radius:30px;
}
.datetimepicker table tr td .active.year:after,
.datetimepicker table tr td .active.month:after {
    background-color:#fbaa31 !important;
}
.datetimepicker table tr td .year:hover,
.datetimepicker table tr td .month:hover {
    background-color:transparent;
}
.datetimepicker table tr td .year:hover:after,
.datetimepicker table tr td .month:hover:after {
    background:#f1f1f1;
}
.datetimepicker table tr td .hour,
.datetimepicker table tr td .minute {
    border-radius:30px;
}

.selectStylerDate.datetimepicker{
	padding:0;
	margin-top: 0;
}

.magnificP{
	display: inline-block;
	vertical-align: top;
}

.hiddenRecommended{
	position: absolute;
	top: 26px;
	left: 50%;
	opacity: 0;
	margin-left: -160px;
	visibility: hidden;
	border-top: 6px solid #fbaa31;
	border-left: 1px solid #e2e2e2;
	border-right: 1px solid #e2e2e2;
	border-bottom: 1px solid #e2e2e2;
	border-radius: 4px 4px 0 0;
	background: #fff;
	padding: 30px;
	-webkit-transition: all 0.3s ease;
	-o-transition: all 0.3s ease;
	transition: all 0.3s ease;
}

.hiddenRecommended.showCat{
	opacity:1;
	visibility:visible;
	top:24px;
}

.hiddenRecommended:before{
    content: ' ';
    position: absolute;
    top: auto;
    bottom: 100%;
    left: 50%;
    width: 0;
    height: 0;
    margin-bottom: 6px;
    margin-left: -6px;
    pointer-events: none;
    border: solid transparent;
    border-width: 6px;
    border-color: rgba(251, 170, 49, 0);
    border-bottom-color: #fbaa31;
}

.hiddenRecommended p{
	text-transform: uppercase;
	color:#555555;
	font-size: 16px;
	font-family: 'Open Sans', sans-serif;
	font-weight: 700;
	white-space:nowrap;
}

.recIn{
	border:1px solid #c8d0de;
	border-radius:4px;
    padding: 0px 40px 0 15px;
	height: 36px;
	color:#999999;
	font-size: 14px;
	font-family: 'Open Sans', sans-serif;
	font-weight: 400;
	width:100%;
}

.recBtn{
	position: absolute;
	right:3%;
	z-index:111;
	top:50%;
	margin-top: -12px;
	width: 24px;
	height: 24px;
	line-height: 24px;
	box-sizing:border-box;
	border:1px solid #7ce6ff;
	background:#fff url(/img/soLinkPop.png) no-repeat center center;
	border-radius:12px;
}

.wrappInpRec{
	width:270px;
	position: relative;
}


	@media only screen and (max-width:1452px){
/*		.imgComment{
			float: none;
		}

		.imgComment + .submitMessage{
			width:100%;
			margin-top: 20px;
		}*/
	}

	@media only screen and (max-width:1280px){
		.groupPick, .datePick{
			max-width: 260px;
			width:100%;
		}
	}

	@media only screen and (max-width:1180px){
		.similar-article_item-meta div{
			margin:0 4%;
		}
	}

	@media only screen and (max-width:1090px){

		.similar-article_item-meta div{
			margin:2px 4%;
		}
	}

	@media only screen and (max-width:1024px){

		.groupPick, .datePick{
			max-width: 100%;
			float: none;
			margin:10px 0;
			width:100%;
		}

		.groupPick .select2-container, .datePick .select2-container{
			width:100%;
			max-width: 100% !important;
		}

		.selectStylerDate{
			max-width: 100%;
		}
	}

	@media only screen and (max-width:767px){
		.wrappRight{
			padding:0;
		}

		.tag-cloud_items{
			padding-top: 16px;
		}

		.titleArt{
			padding-top:16px;
		}
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
				$('.sendingProccess').removeClass('sendingProccess');
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
				var el = $(this);
				if(!el.hasClass('sendingProccess')){
					el.addClass('sendingProccess');
					$.post( "<?=$this->Html->url(array('controller' => 'ArticleAjax', 'action' =>  'addComment'))?>.json", $(this).parents('form').serialize(), function(response) {
						updateComments();
					});
				}
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
					var el = $(this);
					if(!el.hasClass('sendingProccess')) {
						el.addClass('sendingProccess');
						$.post("<?=$this->Html->url(array('controller' => 'ArticleAjax', 'action' => 'addComment'))?>.json", $(this).parents('form').serialize(), function (response) {
							updateComments();
						});
					}
				});

				//smile init for answer message input block
			});

			// Comment: Клик по "edit"
			$('.item .edit').unbind('click');
			$('.item .edit').click(function(){

				$(this).unbind('click').addClass('non-active');
				$(this).closest('.item').find('.answer').unbind('click').addClass('non-active');

				$('.inner.submitMessage').remove();

				var msg = $('.textMsg' ,$(this).parents('.item')).text();
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

		$('#articleEdit').on('click', function (event) {
			event.preventDefault();
			switchEditor();
		});

		$('#publishEdit').on('click', function (event) {
			event.preventDefault();
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
				disableEdit();
			}
		};

		enableEdit = function() {
			$('#articleTitle ').addClass('editable');
			$('#articleTitle').show();
			$('.titleArt').hide();
			$('#articleEdit ').addClass('active');
			$('.wrapperLeft ').addClass('art_create');
			$('#articleView-id ').removeClass('contentText');
			$('#ArticleCategoryBlock').show();
			$('#saveArticle').show();
			$('#publishEdit').show();
			$('#removeLink').show();
			$('#imageUploadControls').show();
			initEditor();
			bodyBefore = tinyMCE.get('articleView-id').getContent();
			titleBefore =  $('#articleTitle').val();
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
			$('#articleView-id').tinymce().remove();
			$('#articleTitle').hide();
			$('.titleArt').show();
			$('#articleTitle ').removeClass('editable');
			$('#articleEdit ').removeClass('active');
			$('.wrapperLeft ').removeClass('art_create');
			$('#articleView-id ').addClass('contentText');
			editMode = false;
		}

		saveArticle = function(auto) {
			if( auto != true ) auto = false;
			var title = $('#articleTitle').val();
			var body = tinyMCE.get('articleView-id').getContent();

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
							created: $('#createdArticle').val(),
							cat_id: $('#catId').val()
						}
					}, function (response) {
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
									timeout: 5000
								});
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
									timeout: 5000
								});
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
			if(title.length > 50) {
				allow = false;
				$('#articleTitle').popover({ toggle: 'popover', placement: 'bottom', content: "<?=__('Title can not be more than 50 symbols')?>" });
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

			tinymce.init({
				selector: '#articleView-id',
				plugins: [
					'advlist autolink lists link image charmap print preview anchor',
					'searchreplace visualblocks code fullscreen',
					'insertdatetime media table contextmenu paste code cloudfilemanager autoresize'
				],
				autoresize_on_init: false,
				relative_urls: false,
				image_advtab: true,
				external_filemanager_path:"/Cloud/index",
				filemanager_title:"<?php echo __('File manager');?>" ,
				toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link cloudfilemanager'
			});

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
		}

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
	$(window).load(function(){
		<?php if(!$article['Article']['title']) { ?>
		switchEditor();
		<?php } ?>
	});
</script>

<div class="wrapperOutside">
	<div class="wrapperLeft col-sm-8 <?php echo empty($this->request->params['pass']) ? 'art_create' : '' ; ?>">
		<? if ($isArticleAdmin && ($article['Article']['title'] != null)) : ?>
			<div>
				<div class="wrapperTopChanges">
					<?php echo $this->Html->link('', array('controller' => 'Article', 'action' => 'view', $article['Article']['id']), array('class' => 'linkEditBtn', 'id'=>'articleEdit')); ?>
					<?php echo $this->Html->link('', array('controller' => 'Article', 'action' => 'delete', $article['Article']['id']), array('class' => 'linkDel', 'id'=>'removeLink')); ?>
					<div class="clear"></div>
				</div>
			</div>
		<?php endif; ?>

		<div class="upperW">
			<h2 class="titleArt"><?=Hash::get($article, 'Article.title')?></h2>
			<input id="articleTitle" type="text" style="display: none; width:100%;" value="<?=Hash::get($article, 'Article.title')?>" placeholder="<?php echo __('Article title') ?>">
		</div>

		<div id="myId" class="dropzone"
			<?php if( Hash::get($article, 'ArticleMedia.id')>0): ?>
				data-file='[
                    <?php if(Hash::get($article, 'ArticleMedia.id')>0): ?>
                        {"name":"<?php echo Hash::get($article, 'ArticleMedia.orig_fname'); ?>", "size":"<?php echo Hash::get($article, 'ArticleMedia.orig_fsize'); ?>", "path":"<?php echo Hash::get($article, 'ArticleMedia.url_img'); ?>", "id":"<?php echo Hash::get($article, 'ArticleMedia.id'); ?>"}
                    <?php endif; ?>
                ]'
			<?php endif; ?>>
			<div class="dz-message" data-dz-message><span><?php echo __('Add photos / video preview'); ?></span></div>
		</div>
		<?php if(Hash::get($article, 'ArticleMedia.id')>0): ?>
		<div class="imgWrappPreview">
			<img src="<?php echo Hash::get($article, 'ArticleMedia.url_img'); ?>" alt="" width="100%">
		</div>
		<?php endif ?>

	<div class="wrapperDownInfo">
		<div class="uppWrap">

			<?php if(Hash::get($article,'Article.group_id')>0): ?>
				<div class="imgAuthor">
					<a href="<?php echo $this->Html->url(array('controller' => 'Group', 'action'=>'view',Hash::get($article,'Article.group_id'))) ?>">
						<img class="rounded avatar" src="<?=$this->Media->imageUrl($article['GroupMedia'], '34x34')?>" alt="<?php echo Hash::get($article,'Group.title'); ?>">
					</a>
				</div>
				<div class="description_author-article">
					<b><?php echo Hash::get($article,'Group.title') ?></b>
				</div>
			<?php else: ?>
				<div class="imgAuthor">
					<a href="<?php echo $this->Html->url(array('controller' => 'User', 'action'=>'view',Hash::get($article,'Article.owner_id'))) ?>">
						<?php echo $this->Avatar->user($user, array('size' => 'thumb34x34' ,'class'=>"rounded avatar")); ?>
					</a>
				</div>

				<div class="description_author-article">
					<b><?php echo Hash::get($user,'User.full_name'); ?></b>
				</div>
			<?php endif; ?>

		    <div class="tagsOut">
				<?php echo $this->Html->link(__($aCategoryOptions[Hash::get($article,'Article.cat_id')]),array('controller'=>'Article','action'=>'category',Hash::get($article,'Article.cat_id'))); ?>
		    </div>
		    <div class="clear"></div>
		</div>

		<div class="downWrapp">
		    <div class="date_article">
				<?php
				$month = $aMonths[(int)date('m',strtotime(Hash::get($article, 'Article.created'))) - 1];
				$day = date('d',strtotime(Hash::get($article, 'Article.created')));
				?>
				<div class="similar-article_item-date"><?php echo $day.' '.$month; ?></div>
				<div class="similar-article_item-users"><?php echo Hash::get($article, 'Article.hits') ?></div>
				<div class="similar-article_item-backing"><a href="" class="magnificP"><?php echo Hash::get($article, 'Article.shared') ?></a>
					<div class="hiddenRecommended">
						<p>Порекомендовать друзьям</p>

						<div class="wrappInpRec">
							<input type="text" class="recIn" placeholder="Введите email друга">
							<a href="" class="recBtn"></a>
						</div>

						<div class="socIconsInside">
							<a href="" class="fb"><span><svg version="1.1" id="Слой_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
				   viewBox="0 0 191.4 409.6" enable-background="new 0 0 191.4 409.6" xml:space="preserve">
                    <g>
						<path d="M127.9,409.6H51.2V204.8H0v-70.6l51.2,0l-0.1-41.6C51.1,35,66.7,0,134.6,0H191v70.6h-35.3c-26.4,0-27.7,9.9-27.7,28.3
                            l-0.1,35.3h63.5l-7.5,70.6l-55.9,0L127.9,409.6z M127.9,409.6"/>
					</g>
                    </svg>
		</span></a>
							<a href="" class="ggpl">		<span><svg version="1.1" id="Слой_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
				   viewBox="0 0 75.3 77.5" enable-background="new 0 0 75.3 77.5" xml:space="preserve">
                    <g>
						<path d="M49,0H30.5c-8.2,0-13.9,1.8-19.1,6C7.5,9.5,5,14.6,5,19.6c0,7.7,5.9,15.9,16.7,15.9c1,0,2.2-0.1,3.2-0.2l-0.1,0.4
                            c-0.4,1-0.9,2-0.9,3.6c0,3,1.4,4.9,2.8,6.6L27,46l-0.3,0c-4.5,0.3-12.8,0.9-18.9,4.6C0.6,55,0,61.2,0,63c0,7.2,6.7,14.4,21.7,14.4
                            c17.4,0,26.5-9.6,26.5-19.1c0-7-4.1-10.5-8.5-14.2L36,41.3c-1.1-0.9-2.6-2.1-2.6-4.3c0-2.1,1.4-3.5,2.7-4.8l0.1-0.1
                            c4-3.1,8.5-6.7,8.5-14.4c0-7.7-4.8-11.7-7.2-13.6h6.1c0.1,0,0.1,0,0.2-0.1l5.3-3.3c0.1-0.1,0.2-0.3,0.2-0.5C49.4,0.1,49.2,0,49,0
                            L49,0z M26.6,73.2c-10.6,0-17.7-5-17.7-12.3c0-4.8,2.9-8.3,8.7-10.4c4.6-1.5,10.5-1.6,10.6-1.6c1,0,1.5,0,2.3,0.1
                            c7.4,5.3,11,8.1,11,13.3C41.4,69,35.8,73.2,26.6,73.2L26.6,73.2z M26.5,32.6c-8.9,0-12.6-11.7-12.6-18c0-3.2,0.7-5.6,2.2-7.5
                            c1.6-2,4.4-3.3,7.1-3.3c8.2,0,12.7,11,12.7,18.6c0,1.2,0,4.8-2.5,7.4C31.7,31.5,28.9,32.6,26.5,32.6L26.5,32.6z M26.5,32.6"/>
						<path d="M74.9,36.2h-9.7v-9.7c0-0.2-0.2-0.4-0.4-0.4h-4.2c-0.2,0-0.4,0.2-0.4,0.4v9.7h-9.7c-0.2,0-0.4,0.2-0.4,0.4v4.2
                            c0,0.2,0.2,0.4,0.4,0.4h9.7V51c0,0.2,0.2,0.4,0.4,0.4h4.2c0.2,0,0.4-0.2,0.4-0.4v-9.8h9.7c0.2,0,0.4-0.2,0.4-0.4v-4.2
                            C75.3,36.4,75.1,36.2,74.9,36.2L74.9,36.2z M74.9,36.2"/>
					</g>
                </svg></span></a>
							<a href="" class="twt">		<span><svg version="1.1" id="Слой_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
				   viewBox="0 0 409.6 332.9" enable-background="new 0 0 409.6 332.9" xml:space="preserve">
                    <g>
						<path  d="M409.6,39.4c-15.1,6.7-31.3,11.2-48.3,13.2c17.4-10.4,30.7-26.9,36.9-46.5c-16.2,9.6-34.2,16.6-53.4,20.4
                            C329.6,10.2,307.8,0,283.6,0c-46.4,0-84,37.6-84,84c0,6.6,0.7,13,2.2,19.2C131.9,99.7,70,66.2,28.5,15.4
                            c-7.2,12.4-11.4,26.8-11.4,42.2c0,29.2,14.8,54.9,37.4,69.9c-13.8-0.4-26.7-4.2-38.1-10.5c0,0.4,0,0.7,0,1.1
                            c0,40.7,29,74.7,67.4,82.4c-7.1,1.9-14.5,2.9-22.1,2.9c-5.4,0-10.7-0.5-15.8-1.5c10.7,33.4,41.7,57.7,78.5,58.4
                            c-28.8,22.5-65,36-104.4,36c-6.8,0-13.5-0.4-20-1.2c37.2,23.8,81.4,37.8,128.8,37.8c154.6,0,239.1-128,239.1-239.1
                            c0-3.6-0.1-7.3-0.2-10.9C384.1,71.1,398.3,56.3,409.6,39.4L409.6,39.4z M409.6,39.4"/>
					</g>
                </svg></span></a>
							<!-- <a href="" class="skp"></a> -->
						</div>
					</div>
				</div>
				<a href="" class="data_scroll similar-article_item-comments" data-link="block_1"><div class=""><?php echo count($aEvents); ?></div></a>
				<div class="clear"></div>
		    </div>
		</div>
	</div>

    <div class="contentText dopMarT" id="articleView-id">

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

		<div class="wrapperSelectsBottom">
			<div class="groupPick">
				<select name="" id="catId" class="stylerSelectBig">
					<?php foreach($aCategoryOptions as $catId => $catTag): ?>
						<?php if($catTag): ?>
							<option value="<?php echo $catId ?>" <?php echo $catId == Hash::get($article,'Article.cat_id') ? 'selected' : '' ?>><?php echo $catTag ?></option>
						<?php endif ?>
					<?php endforeach ?>
				</select>
			</div>
			<div class="datePick">
				<div class="input-group date selectStylerDate datetimepicker" data-provide="datepicker">
					<div class="input-group-addon">
						<span class="glyphicon glyphicon-th"></span>
						<span class="textPublic">Опубликовать:</span>
					</div>
					<input type="text" name="created" id="createdArticle" value="<?php echo Hash::get($article,'Article.created') ?>" class="form-control">
				</div>

			</div>
			<div class="clear"></div>
		</div>

    <div class="wrappSoc" style="">
        <?php if( $article['Article']['title'] != null ) { 	echo $this->element('social_share_new', array( 'title' => $title, 'content' => $description, 'imageUrl' => $image_link));} ?>
        <div class="tagsRight">
			<div class="items-tags">
				<?php echo $this->Html->link(__($aCategoryOptions[Hash::get($article,'Article.cat_id')]),array('controller'=>'Article','action'=>'category',Hash::get($article,'Article.cat_id'))); ?>
			</div>
        </div>
        <div class="clear"></div>
    </div>


	<div class="linesBot"></div>
	<div class="linesBot"></div>



	<div class="wrapper-btn-public-save" style="width: 100%; text-align: center;">
		<div class="btn needsclick putArt" id="publishSave" style="display: inline-block; margin-right: 20px;"><?=__('Publish')?></div>
		<div class="btn needsclick chernArt" id="draftSave" style="display: inline-block;"><?=__('Save as draft')?></div>
	</div>


	<div id="articleComments" class="block_1"></div>

</div>

	<div class="wrappRight col-sm-4"><!--style="display:none" - для создания статьи-->

	<?php if( count($aSimilarArticles) ): ?>
		<div class="similar-article_list">
			<div class="similar-article_title"><?php echo __('Similar articles'); ?></div>

			<?php foreach( $aSimilarArticles as $aSimilarArticle ): ?>
				<div class="similar-article_item">
					<div class="similar-article_item-body">
						<a href="<?=$this->Html->url(array('controller' => 'Article', 'action' => 'view',$aSimilarArticle['Article']['id']))?>" class="similar-article_item-title"><?php echo $aSimilarArticle['Article']['title'] ?></a>
					</div>

					<div class="authorInformation">

						<?php if(Hash::get($aSimilarArticle,'Article.group_id')>0): ?>
							<a href="/Group/view/<?=$aSimilarArticle['Article']['group_id']?>" class="similar-article_item-image">
								<img class="avatar rounded" src="<?=$this->Media->imageUrl($aSimilarArticle['GroupMedia'], 'thumb100x100')?>" alt="<?php echo Hash::get($aSimilarArticle,'Group.title'); ?>">
							</a>

							<div class="nameAuthorArt">
								<p><?php echo Hash::get($aSimilarArticle,'Group.title') ?></p>
							</div>
						<?php else: ?>
							<a href="/User/view/<?=$aSimilarArticle['Article']['owner_id']?>" class="similar-article_item-image">
								<?php echo $this->Avatar->user($aSimilarArticle, array('size' => 'thumb100x100', 'class' => 'rounded')); ?>
							</a>

							<div class="nameAuthorArt">
								<p><?php echo $aSimilarArticle['User']['full_name'] ?></p>
							</div>
						<?php endif; ?>

						<div class="tagsLinkThemes">
							<a href="/Article/category/<?php echo $aSimilarArticle['Article']['cat_id']; ?>"><?php echo $aCategoryOptions[ $aSimilarArticle['Article']['cat_id'] ]; ?></a>
						</div>
						<div class="clear"></div>
					</div>

					<div class="shortDescrArt">
						<p><?php echo $this->Text->truncate(strip_tags($aSimilarArticle['Article']['body']), 50, array( 'ellipsis' => '...', 'exact' => false )) ?></p>
					</div>

					<div class="wrapR">
						<div class="similar-article_item-meta">
							<div class="similar-article_item-date"><?php echo date('d.m.Y'); ?></div>
							<div class="similar-article_item-users"><?php echo $aSimilarArticle['Article']['hits'] ?></div>
							<div class="similar-article_item-backing"><a href="" class="magnificP"></a><?php echo $aSimilarArticle['Article']['shared'] ?></div>
						</div>

					</div>
					<div class="clear"></div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif ?>
	<?php if( count($aPopularArticles) ): ?>
		<div class="similar-article_list">
			<div class="similar-article_title"><?php echo __('Other interesting articles'); ?></div>
			<?php foreach( $aPopularArticles as $aPopularArticle ): ?>
				<div class="similar-article_item">
					<div class="similar-article_item-body">
						<a href="<?=$this->Html->url(array('controller' => 'Article', 'action' => 'view',$aPopularArticle['Article']['id']))?>" class="similar-article_item-title"><?php echo $aPopularArticle['Article']['title'] ?></a>
					</div>

					<div class="authorInformation">

						<?php if(Hash::get($aPopularArticle,'Article.group_id')>0): ?>
							<a href="/Group/view/<?=$aPopularArticle['Article']['group_id']?>" class="similar-article_item-image">
								<img class="avatar rounded" src="<?=$this->Media->imageUrl($aPopularArticle['GroupMedia'], 'thumb100x100')?>" alt="<?php echo Hash::get($aPopularArticle,'Group.title'); ?>">
							</a>
							<div class="nameAuthorArt">
								<p><?php echo Hash::get($aPopularArticle,'Group.title') ?></p>
							</div>
						<?php else: ?>
							<a href="/User/view/<?=$aPopularArticle['Article']['owner_id']?>" class="similar-article_item-image">
								<?php echo $this->Avatar->user($aPopularArticle, array('size' => 'thumb100x100', 'class' => 'rounded')); ?>
							</a>
							<div class="nameAuthorArt">
								<p><?php echo $aPopularArticle['User']['full_name'] ?></p>
							</div>
						<?php endif; ?>

						<div class="tagsLinkThemes">
							<a href="/Article/category/<?php echo $aPopularArticle['Article']['cat_id']; ?>"><?php echo $aCategoryOptions[ $aPopularArticle['Article']['cat_id'] ]; ?></a>
						</div>
					<div class="clear"></div>
					</div>

					<div class="shortDescrArt">
						<p><?php echo $this->Text->truncate(strip_tags($aPopularArticle['Article']['body']), 50, array( 'ellipsis' => '...', 'exact' => false )) ?></p>
					</div>

					<div class="wrapR">
						<div class="similar-article_item-meta">
							<div class="similar-article_item-date"><?php echo date('d.m.Y'); ?></div>
							<div class="similar-article_item-users"><?php echo $aPopularArticle['Article']['hits'] ?></div>
							<div class="similar-article_item-backing"><a href="" class="magnificP"></a><?php echo $aPopularArticle['Article']['shared'] ?></div>
						</div>

					</div>
					<div class="clear"></div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

		<div class="tag-cloud_items">
			<div class="similar-tag_title"><?php echo __('Tag cloud'); ?></div>
			<?php foreach($aCategoryOptions as $catId => $catTag): ?>
				<?php if($catTag): ?>
					<div class="items-tags">
						<?php echo $this->Html->link(__($catTag),array('controller'=>'Article','action'=>'category',$catId)); ?>
					</div>
				<?php endif ?>
			<?php endforeach ?>
			<div class="clear"></div>
		</div>

	</div>
</div>

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
	<div class="commentsArticle" >
		<div class="titleComment">
            <?=__('Leave your comment')?>
        </div>
        <div class="imgComment">
            <?php echo $this->Avatar->user($currUser, array('size' => 'thumb100x100','class'=>'rounded')); ?>
        </div>
        <form action="" method="" id="ArticleInnerForm" class="inner submitMessage">
            <textarea id="innerMessageTitle" class="form-control textAreaComment" name="data[message]">{%= o.message ? o.message : '' %}</textarea>
            <input type="button" value="<?php echo __('Send');?>" class="btnComment submitBtn">
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
        </form>
        <div class="clear"></div>
    </div>
    </script>
<?php endif; ?>
