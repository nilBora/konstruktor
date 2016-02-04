<?php
    //Debugger::dump($users);
    //Debugger::dump($groupAddresses);
    //Debugger::dump($aEvents);

    /* Breadcrumbs */
	$this->Html->addCrumb(__('Planet'), array('controller' => 'Timeline', 'action' => 'planet'));
?>
<style>#map-over-block>li:after,#menu .clockCanvas:before,.svgLogoHolder:after{content:''}#menu{visibility:hidden}#menu .companyLink{visibility:visible}#menu .clockCanvas:before{position:absolute;z-index:-1;top:-8%;left:-8%;box-sizing:border-box;width:116%;height:116%;opacity:.6;border-radius:100%;background-color:#fff;box-shadow:0 0 0 3px rgba(0,0,0,.3)}.container-fluid,body,html{position:relative}.menu-wrapper .companyLink a .text{margin-top:10px}body,html{height:100%}.container-fluid,.wrapper-container{height:100%;padding-right:0!important;padding-left:0!important}.wrapper-container{padding-top:161px}.mapLogo{position:absolute;z-index:60;bottom:20px;left:20px}.mapLogo img{display:inline-block;max-width:75px}#map-canvas,#map-over-block{position:absolute;top:0;left:0;overflow:hidden;width:100%;height:100%}#map-over-block{z-index:10000;margin:0;padding:0;list-style:none;user-select:none;pointer-events:none;-o-user-select:none}#map-over-block>li{position:absolute;top:0;left:0;box-sizing:border-box;width:5%;height:100%;list-style:none;cursor:wait;pointer-events:initial;background-color:#fff}#map-over-block>li:after{position:absolute;top:0;left:-10px;width:100%;height:100%;padding:0 10px;background-color:#fff}#map-over-block>li:nth-child(2){left:5%}#map-over-block>li:nth-child(3){left:10%}#map-over-block>li:nth-child(4){left:15%}#map-over-block>li:nth-child(5){left:20%}#map-over-block>li:nth-child(6){left:25%}#map-over-block>li:nth-child(7){left:30%}#map-over-block>li:nth-child(8){left:35%}#map-over-block>li:nth-child(9){left:40%}#map-over-block>li:nth-child(10){left:45%}#map-over-block>li:nth-child(11){left:50%}#map-over-block>li:nth-child(12){left:55%}#map-over-block>li:nth-child(13){left:60%}#map-over-block>li:nth-child(14){left:65%}#map-over-block>li:nth-child(15){left:70%}#map-over-block>li:nth-child(16){left:75%}#map-over-block>li:nth-child(17){left:80%}#map-over-block>li:nth-child(18){left:85%}#map-over-block>li:nth-child(19){left:90%}#map-over-block>li:nth-child(20){left:95%}.svgLogoHolder{position:absolute;z-index:5;top:0;right:0;bottom:0;left:0;display:block;max-width:160px;max-height:160px;margin:auto;-ms-transform:translateY(-30px);transform:translateY(-30px)}.svgLogoHolder .loadText{position:absolute;top:120%;left:0;width:100%;text-align:center}.svgLogoHolder:after{position:absolute;top:-16%;left:-15%;display:block;width:130%;height:130%;opacity:.3;border:10px solid #aaa;border-radius:200px}.svgLogoHolder svg{display:block;width:100%;height:100%;fill:#aaa}#map-over-block.map-show-now{visibility:hidden;transition:visibility 0s linear;transition-delay:1.2s}#map-over-block.map-show-now>li{visibility:hidden;transition:all .3s ease;opacity:0}#map-over-block.map-show-now>li:nth-child(10),#map-over-block.map-show-now>li:nth-child(11){transition-delay:.5s}#map-over-block.map-show-now>li:nth-child(12),#map-over-block.map-show-now>li:nth-child(9){transition-delay:.575s}#map-over-block.map-show-now>li:nth-child(13),#map-over-block.map-show-now>li:nth-child(8){transition-delay:.64s}#map-over-block.map-show-now>li:nth-child(14),#map-over-block.map-show-now>li:nth-child(7){transition-delay:.695s}#map-over-block.map-show-now>li:nth-child(15),#map-over-block.map-show-now>li:nth-child(6){transition-delay:.74s}#map-over-block.map-show-now>li:nth-child(16),#map-over-block.map-show-now>li:nth-child(5){transition-delay:.775s}#map-over-block.map-show-now>li:nth-child(17),#map-over-block.map-show-now>li:nth-child(4){transition-delay:.8s}#map-over-block.map-show-now>li:nth-child(18),#map-over-block.map-show-now>li:nth-child(3){transition-delay:.815s}#map-over-block.map-show-now>li:nth-child(19),#map-over-block.map-show-now>li:nth-child(2){transition-delay:.82s}#map-over-block.map-show-now>li:nth-child(1),#map-over-block.map-show-now>li:nth-child(20){transition-delay:.815s}#map-over-block.map-show-now>.svgLogoHolder .loadText{transition:all .4s ease;-ms-transform:translateY(-40px);transform:translateY(-40px);opacity:0}#map-over-block.map-show-now>.svgLogoHolder:after{transition:all .4s ease;-ms-transform:scale(.6);transform:scale(.6);opacity:0}#map-over-block.map-show-now>.svgLogoHolder svg{transition:all .4s ease;-ms-transform:scale(.3);transform:scale(.3);opacity:0}
</style>
<style>.k-marker,.triggerToGroup{overflow:hidden;box-sizing:border-box}.k-marker{font-size:0;line-height:0;position:relative;display:block;width:50px;height:50px;border-radius:100%;box-shadow:0 2px 2px rgba(0,0,0,.75)}.k-marker.user{background-color:#ccc}.k-marker.event{border:2px solid #94c7d4;background-color:#fff}.k-marker.event-conference .event-icon,.k-marker.event-meet .event-icon,.k-marker.event-none .event-icon,.k-marker.event-pay .event-icon,.k-marker.event-purchase .event-icon,.k-marker.event-sport .event-icon{background-image:url(/img/events.png)}.k-marker.event-call .event-icon,.k-marker.event-entertain .event-icon,.k-marker.event-mail .event-icon,.k-marker.event-task .event-icon{background-image:url(/img/icons-sprite-new-new.png)}.k-marker .event-icon{position:absolute;top:0;right:0;bottom:0;left:0;width:30px;height:30px;margin:auto;background-position:-40px -68px}.k-marker.event-meet{border-color:#ffcfa3}.k-marker.event-meet .event-icon{width:35px;height:28px;background-position:-268px -66px}.k-marker.event-call{border-color:#446cb3}.k-marker.event-call .event-icon{width:35px;height:32px;background-position:-312px -6px}.k-marker.event-mail{border-color:#7c98ca}.k-marker.event-none,.k-marker.event-task{border-color:#f47f72}.k-marker.event-mail .event-icon{width:34px;height:28px;background-position:-93px -10px}.k-marker.event-none .event-icon{width:27px;height:34px;background-position:-152px -66px}.k-marker.event-task .event-icon{width:27px;height:34px;background-position:-273px -5px}.k-marker.event-conference{border-color:#4dabf5}.k-marker.event-conference .event-icon{width:34px;height:20px;background-position:-315px -72px}.k-marker.event-sport{border-color:#e9c637}.k-marker.event-sport .event-icon{width:35px;height:30px;background-position:-114px -67px}.k-marker.event-purchase{border-color:#92d788}.k-marker.event-purchase .event-icon{width:35px;height:28px;background-position:-185px -67px}.k-marker.event-pay{border-color:#c66aab}.k-marker.event-pay .event-icon{width:35px;height:28px;background-position:-358px -67px}.k-marker.event-entertain{border-color:#d3517a}.k-marker.event-entertain .event-icon{width:32px;height:28px;background-position:-226px -8px}.k-marker span{position:relative;display:block;width:100%;height:100%;background-clip:content-box;background-size:100% auto}.k-marker img{display:inline-block;width:100%;max-width:100%}.triggerToGroup{position:relative;width:50px;height:50px;cursor:pointer;transition:border-color .3s ease,background-color .3s ease;border:2px solid #9d9a95;border-radius:100%;background-color:#fff;box-shadow:0 2px 2px rgba(0,0,0,.3)}.triggerToGroup .k-marker{display:none}.triggerToGroup:after{content:'\E208';font-family:'Glyphicons Regular';font-weight:400;font-style:normal;line-height:46px;-ms-transform:scale(1.6);transform:scale(1.6);text-transform:none;opacity:0;color:#fff;-webkit-font-smoothing:antialiased}.triggerToGroup:before{line-height:48px;color:#707070}.triggerToGroup:after,.triggerToGroup:before{font-size:24px;position:absolute;top:0;left:0;display:block;width:100%;height:100%;margin:0;transition:all .3s ease;text-align:center}.openedPanelSearch .triggerToGroup{border-color:#fff;background-color:#777}.openedPanelSearch .triggerToGroup:after{-ms-transform:none;transform:none;opacity:1}.openedPanelSearch .triggerToGroup:before{-ms-transform:scale(.4);transform:scale(.4);opacity:0}
</style>
<link rel="stylesheet" property="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css"/>

<!-- map-canvas-->
<div id="map-canvas" class="gpuAccel"></div>
<ul id="map-over-block"><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li>
	<div class="svgLogoHolder">
		<div class="loadText"><?php echo __('Loading...'); ?></div>
		<svg id="svgLogo" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 200 200" enable-background="new 0 0 200 200" xml:space="preserve">
			<polygon opacity="0.5" points="111.2,197.5 140.7,168 149.7,88.9 116.5,118 "/>
			<polygon opacity="0.5" points="18.9,45.9 95.2,44.5 85.5,2.9 "/>
			<polygon opacity="0.5" points="111.1,122.3 66.6,130.6 106.6,192.7 "/>
			<polygon opacity="0.5" points="151.4,121.3 180.1,95.9 188,52.9 155.8,82.9 "/>
			<polygon opacity="0.5" points="147.3,163.1 172.7,138.3 179.1,102.8 150.6,128.8 "/>
			<polygon points="61.6,82.5 111.3,115.6 150.7,81.6 101.8,53.6 "/>
			<polygon points="107.2,49.9 155,77.1 187,48.3 139.8,24.3 "/>
			<polygon points="16.6,95.2 56.5,122.7 55.8,85.4 12,57 "/>
			<polygon points="22.2,141.1 57.4,166 56.8,129.3 17.6,102.2 "/>
			<polygon points="14.8,51.5 56,79.2 96.4,49.8 "/>
			<polygon points="102.6,46.2 134.6,22.1 91.4,0.1 "/>
			<polygon points="61.5,126.2 105.4,118.3 60.9,88.8 "/>
			<polygon points="62.4,169.7 104.1,199.9 61.9,134.2 "/>
		</svg>
	</div>
</ul>
<div id="map-load"><div class="cssload-thecube"><div class="cssload-cube cssload-c1"></div><div class="cssload-cube cssload-c2"></div><div class="cssload-cube cssload-c4"></div><div class="cssload-cube cssload-c3"></div></div></div>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyAVYKJolB36eM2QFsJwcOOF7kCCwpcqU48"></script>
<script src="https://googlemaps.github.io/js-rich-marker/src/richmarker-compiled.js"></script>
<script src="https://google-maps-utility-library-v3.googlecode.com/svn/tags/markerclustererplus/2.1.2/src/markerclusterer_packed.js"></script>
<!-- end ==> map-canvas-->

<!-- map-panels-->
<style>.mapButton,.titleIcon{cursor:pointer;text-align:center}.mapPanel .title,.panelItem{border-bottom:1px solid #eee}.eventNav,.mapButton,.pEvent_footer,.titleIcon{text-align:center}.custom_filter{background:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAPCAQAAABUrcdQAAAAX0lEQVQoU72R0QkAIAhEHaJBdf8PQypOxYgIQrJMX+VFrKI2hiFit2OeZ44QckjG9TpoAlIURAg3UTuYJvsILJ3wZnibCyA36/XaAv43om7vPfwDsj5XgG92C6BYSqADTS4suVC5hzUAAAAASUVORK5CYII=) center center no-repeat}.mapButton,.mapPanel{background-color:#fff}.brackets:not(:empty):before{content:'('}.brackets:not(:empty):after{content:')'}.mapButton{font-size:16px;position:absolute;z-index:40;top:20px;right:120px;width:30px;height:30px;-webkit-transition:background-color .3s ease,box-shadow .3s ease;transition:background-color .3s ease,box-shadow .3s ease;border-radius:50%;box-shadow:0 0 0 2px rgba(0,0,0,.3)}.mapButton .glyphicons,.mapPanel{position:absolute;height:100%;top:0}.mapButton+.mapButton{right:180px}.mapButton .glyphicons{left:0;width:100%;-webkit-transition:opacity .3s ease,transform .3s ease;transition:opacity .3s ease,transform .3s ease;color:#707070}.mapButton .glyphicons:before{line-height:30px;margin-right:0}.mapButton .glyphicons+.glyphicons{-webkit-transform:scale(1.6);-ms-transform:scale(1.6);transform:scale(1.6);opacity:0;color:#fff}.mapButton .glyphicons.custom_filter{background-size:16px auto}.mapPanel{z-index:450;left:-285px;width:285px;-webkit-transition:transform .3s ease;transition:transform .3s ease;box-shadow:0 0 4px 0 rgba(0,0,0,.6)}.mapPanel .title{font-size:16px;font-weight:700;line-height:20px;padding:20px 18px;text-transform:uppercase;color:#666}.titleIcon{position:relative;float:right;width:26px;height:26px;margin-top:-4px;color:#999}.titleIcon>span{line-height:26px;position:absolute;top:0;left:0;display:block;width:100%;height:100%}.titleIcon>span:before{margin-right:0}.titleIcon:hover{color:#333}.openedPanelFilter #mapButtonFilter,.openedPanelSearch #mapButtonSearch{background-color:#777;box-shadow:0 0 0 2px rgba(255,255,255,.3)}.openedPanelFilter #mapButtonFilter .glyphicons:first-child,.openedPanelSearch #mapButtonSearch .glyphicons:first-child{-webkit-transform:scale(.4);-ms-transform:scale(.4);transform:scale(.4);opacity:0}.openedPanelFilter #mapButtonFilter .glyphicons:last-child,.openedPanelSearch #mapButtonSearch .glyphicons:last-child{-webkit-transform:none;-ms-transform:none;transform:none;opacity:1}.openedPanelEvent #mapPanelEvent,.openedPanelFilter #mapPanelFilter,.openedPanelSearch #mapPanelSearch{-webkit-transform:translateX(100%);-ms-transform:translateX(100%);transform:translateX(100%)}.openedPanelSearch.openedPanelFilter #mapPanelSearch{-webkit-transition-duration:.3s;transition-duration:.3s;-webkit-transform:translateX(200%);-ms-transform:translateX(200%);transform:translateX(200%)}.eventNav{font-size:160%;line-height:26px;display:inline-block;width:26px;cursor:default;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;vertical-align:top;color:#e5e5e5;-o-user-select:none}.eventNav.event-element{cursor:pointer;color:#999}.eventNav.event-element:hover{color:#333}.panelContent{position:absolute;top:61px;left:0;overflow-y:auto;width:100%;height:90%;height:calc(100% - 61px)}.panelContent .title{font-weight:400;padding-top:15px;padding-bottom:16px;cursor:pointer;text-transform:none}.panelContent .title:hover{background-color:#f7f7f7}.panelContent .title.openedNext .titleIcon .fa{-webkit-transform:rotate(90deg) translateX(2px);-ms-transform:rotate(90deg) translateX(2px);transform:rotate(90deg) translateX(2px)}.panelContent .title ins{text-decoration:none}.panelContent .panelEmpty>.title{cursor:default}.panelContent .panelEmpty>.title .titleIcon{opacity:0}.openedPanelFilter .mapPanel#mapPanelSearch{-webkit-transition-duration:.6s;transition-duration:.6s}.panelList{display:none}.panelList ul{margin:0;padding-left:0;list-style:none}.panelItem{position:relative;display:block;padding:6px 12px;text-decoration:none!important;outline:0;background-color:#fff}.panelItem:hover{background-color:#e7f1f1}.panelItem:hover .panelAvatar{border-color:#fff}.panelItem:after{content:'';display:block;clear:both;height:0}.panelItem span{display:block}.panelAvatar{float:left;width:50px;height:50px;margin-right:10px;border:2px solid #cdf6f4;border-radius:100px;background-repeat:no-repeat;background-position:center center;background-clip:content-box;background-size:100% auto}.panelInfo{overflow:hidden;padding-top:5px}.panelSubtitle,.panelTitle{overflow:hidden;max-width:100%;white-space:nowrap;text-overflow:ellipsis}.panelTitle{font-size:15px;font-weight:600;color:#313131}.panelSubtitle{font-size:13px;color:#616161}.filterItem{cursor:pointer;background-color:#fafafa}.filterItem .panelAvatar{border-radius:0;background-size:150% auto}#mapPanelEvent{background-color:#f9f9f9}#mapPanelEvent>.title{padding-top:15px;padding-bottom:15px;background-color:#fff}#mapPanelEvent .panelContent{top:57px;height:calc(100% - 57px)}.panelEventsList{position:relative;display:block;box-sizing:border-box;width:100%;min-height:100%;margin:0;padding:0;list-style:none}.pEvent_footer,.pEvent_footer .btn-accept{margin-top:8px}.panelEventsList>li{display:block;list-style:none}.panelEventsList.fadeListEvents{-webkit-transition:opacity .3s ease;transition:opacity .3s ease;opacity:0}.pEvent_preview{font-size:0;line-height:0;position:relative;display:block;margin-bottom:10px}.pEvent_preview img{display:block;width:100%;height:auto}.panelImgCrop .pEvent_preview{overflow:hidden;height:190px}.panelImgCrop .pEvent_preview img{-webkit-transform:translateY(-13%);-ms-transform:translateY(-13%);transform:translateY(-13%)}.pEvent_owners{padding-top:5px;border-top:1px solid #ccc}.pEvent_head{font-size:18px;font-weight:600;margin-bottom:5px;text-transform:uppercase}.pEvent_head .pEvent_currency{color:#12d09b}.pEvent_head>span{display:inline-block;vertical-align:top}.pEvent_info,.pEvent_owners{line-height:1.5em}.pEvent_info span{display:block}.pEvent_content{padding:0 18px 20px}.pEvent_footer .btn{min-width:150px;white-space:normal}.pEvent_footer .btn-color{color:#fff;border:none;background-color:#ffba4b}.pEvent_footer .btn-color:hover{box-shadow:inset 0 3px 5px rgba(0,0,0,.15)}.pEvent_footer .btn-color:active{box-shadow:inset 0 3px 5px rgba(0,0,0,.22)}.filterList{padding:15px 18px 0!important;background-color:#f9f9f9}.filterList li{position:relative;display:block;margin-bottom:5px}.elCheckbox,.elCheckbox ins,.elRadio,.elRadio ins{display:inline-block;vertical-align:top}.filterList li:last-child{margin-bottom:0}.filterList:last-child{padding-bottom:15px!important}.elCheckbox,.elCheckbox ins,.elCheckbox span,.elRadio,.elRadio ins,.elRadio span{line-height:14px}.elCheckbox .elChecker,.elRadio .elChecker{position:absolute;top:0;left:0;width:1px;height:1px;margin:0;pointer-events:none;opacity:0}.elCheckbox .elChecker:checked~ins,.elRadio .elChecker:checked~ins{border-color:transparent;box-shadow:inset 0 0 0 2px #fff}.elCheckbox .elChecker:checked~span,.elRadio .elChecker:checked~span{color:#028a64}.elCheckbox,.elRadio{font-weight:400;margin:0;cursor:pointer;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;-o-user-select:none}.elCheckbox span,.elRadio span{position:relative;display:inline}.elCheckbox ins,.elRadio ins{position:relative;box-sizing:border-box;width:14px;height:14px;margin-right:10px;-webkit-transition:box-shadow .15s linear,border-color .15s linear;transition:box-shadow .15s linear,border-color .15s linear;text-decoration:none;border:1px solid #fff;border-radius:3px;background-color:#12d09b;box-shadow:inset 0 0 0 1px rgba(0,0,0,.5),inset 0 0 0 14px #fff}.elRadio ins{border-radius:18px}.filterList .elChecker~span em{opacity:.62}.pEvent_title .glyphicons,.panelSubtitle .glyphicons{margin-top:-.1em;vertical-align:top}.pEvent_title .check,.panelSubtitle .check{margin-top:-.2em}.pEvent_infoList{font-size:0;line-height:0;position:relative;display:table;width:100%;margin:0 0 10px;padding:0;list-style:none}.pEvent_infoList>li{font-size:13px;line-height:1.42857143;position:relative;display:inline-block;box-sizing:border-box;width:50%;padding-left:26px}.pEvent_infoList .pEi-icon{position:absolute;display:block;background-color:transparent;background-repeat:no-repeat;background-position:center right}
</style>
<script type="text/javascript" src="/js/timeline_new.js"></script>

<div id="mapButtonSearch" class="mapButton" data-param='{"panel":"Search"}'><span class="glyphicons more"></span><span class="glyphicons remove_2"></span></div>
<div id="mapButtonFilter" class="mapButton" data-param='{"panel":"Filter"}'><span class="glyphicons custom_filter"></span><span class="glyphicons remove_2"></span></div>

<div id="mapPanelSearch" class="mapPanel">
	<div class="title"><?php echo __('Search'); ?><span data-trigger="#mapButtonSearch" class="titleIcon mapButtonTrigger"><span class="glyphicons remove_2"></span></span></div>
	<div class="panelContent">
		<div id="visibleUsers" class="panelGroup">
			<div class="title"><?php echo __('Users'); ?> <ins class="visibleCount brackets">0</ins><span class="titleIcon"><span class="fa fa-angle-right"></span></span></div>
			<div class="panelList">
				<ul class="visibleList"></ul>
			</div>
		</div>
		<div id="eventEvents" class="panelGroup">
			<div class="title"> <ins class="visibleCount brackets">0</ins><span class="titleIcon"><span class="fa fa-angle-right"></span></span></div>
			<div class="panelList">
				<ul class="visibleList"></ul>
			</div>
		</div>
		<div id="externalEvents" class="panelGroup" style="display: none;">
			<div class="title"><?php echo __('External events'); ?> <ins class="visibleCount brackets">0</ins><span class="titleIcon"><span class="fa fa-angle-right"></span></span></div>
			<div class="panelList">
				<ul class="visibleList"></ul>
			</div>
		</div>
		<div id="visibleGroups" class="panelGroup" style="display: none;">
			<div class="title"><?php echo __('Groups'); ?> <ins class="visibleCount brackets">0</ins><span class="titleIcon"><span class="fa fa-angle-right"></span></span></div>
			<div class="panelList">
				<ul class="visibleList"></ul>
			</div>
		</div>
		<div id="visibleInvests" class="panelGroup" style="display: none;">
			<div class="title"><?php echo __('Investment project'); ?>  <ins class="visibleCount brackets">0</ins><span class="titleIcon"><span class="fa fa-angle-right"></span></span></div>
			<div class="panelList">
				<ul class="visibleList"></ul>
			</div>
		</div>
	</div>
</div>

<div id="mapPanelFilter" class="mapPanel">
	<div class="title"><?php echo __('Filters'); ?><span data-trigger="#mapButtonFilter" class="titleIcon mapButtonTrigger"><span class="glyphicons remove_2"></span></span></div>
	<div class="panelContent">
		<div class="panelGroup">
			<div class="title"><?php echo __('By events'); ?><span class="titleIcon"><span class="fa fa-angle-right"></span></span></div>
			<div class="panelList">
				<ul id="filter_event" class="filterList">
					<li>
						<label class="elRadio">
							<input type="radio" name="filter_event" value="all_events" checked="checked" class="elChecker"/><ins>&nbsp;</ins><span><?php echo __('All events'); ?></span>
						</label>
					</li>
					<li>
						<label class="elRadio">
							<input type="radio" name="filter_event" value="my_events" class="elChecker"/><ins>&nbsp;</ins><span><?php echo __('My events'); ?></span>
						</label>
					</li>
				</ul>
				<ul id="filter_event_type" class="filterList">
					<li>
						<label class="elCheckbox">
							<input type="checkbox" name="filter_event_type" value="meet" checked="checked" class="elChecker"/><ins>&nbsp;</ins><span><?php echo __('Meetings'); ?></span>
						</label>
					</li>
					<li>
						<label class="elCheckbox">
							<input type="checkbox" name="filter_event_type" value="call" checked="checked" class="elChecker"/><ins>&nbsp;</ins><span><?php echo __('Telephone calls'); ?></span>
						</label>
					</li>
					<li>
						<label class="elCheckbox">
							<input type="checkbox" name="filter_event_type" value="mail" checked="checked" class="elChecker"/><ins>&nbsp;</ins><span><?php echo __('Emails'); ?></span>
						</label>
					</li>
					<li>
						<label class="elCheckbox">
							<input type="checkbox" name="filter_event_type" value="conference" checked="checked" class="elChecker"/><ins>&nbsp;</ins><span><?php echo __('Conferences'); ?></span>
						</label>
					</li>
					<li>
						<label class="elCheckbox">
							<input type="checkbox" name="filter_event_type" value="sport" checked="checked" class="elChecker"/><ins>&nbsp;</ins><span><?php echo __('Sport events'); ?></span>
						</label>
					</li>
					<li>
						<label class="elCheckbox">
							<input type="checkbox" name="filter_event_type" value="task" checked="checked" class="elChecker"/><ins>&nbsp;</ins><span><?php echo __('Tasks'); ?></span>
						</label>
					</li>
					<li>
						<label class="elCheckbox">
							<input type="checkbox" name="filter_event_type" value="purchase" checked="checked" class="elChecker"/><ins>&nbsp;</ins><span><?php echo __('Purchases'); ?></span>
						</label>
					</li>
					<li>
						<label class="elCheckbox">
							<input type="checkbox" name="filter_event_type" value="entertain" checked="checked" class="elChecker"/><ins>&nbsp;</ins><span><?php echo __('Entertainment'); ?></span>
						</label>
					</li>
					<li>
						<label class="elCheckbox">
							<input type="checkbox" name="filter_event_type" value="pay" checked="checked" class="elChecker"/><ins>&nbsp;</ins><span><?php echo __('Payments'); ?></span>
						</label>
					</li>
					<li>
						<label class="elCheckbox">
							<input type="checkbox" name="filter_event_type" value="none" checked="checked" class="elChecker"/><ins>&nbsp;</ins><span><?php echo __('Other events'); ?></span>
						</label>
					</li>
				</ul>
			</div>
		</div>
		<div class="panelGroup panelEmpty">
			<div class="title"><?php echo __('By groups'); ?><span class="titleIcon"><span class="fa fa-angle-right"></span></span></div>
			<div class="panelList"></div>
		</div>
		<div class="panelGroup panelEmpty">
			<div class="title"><?php echo __('By categories'); ?><span class="titleIcon"><span class="fa fa-angle-right"></span></span></div>
			<div class="panelList"></div>
		</div>
		<div class="panelGroup panelEmpty">
			<div class="title"><?php echo __('By friends'); ?><span class="titleIcon"><span class="fa fa-angle-right"></span></span></div>
			<div class="panelList">
			</div>
		</div>
	</div>
</div>
<div id="mapPanelEvent" class="mapPanel">
	<div class="title"><span class="eventNav eventPrev">&lt;</span><span class="eventNav eventNext">&gt;</span><span data-trigger="#mapButtonFilter" class="titleIcon mapPanelEventClose"><span class="glyphicons remove_2"></span></span></div>
	<div class="panelContent">
		<ul class="panelEventsList">
			<li class="panelEventsActive">
				<div class="pEvent_preview"><img src="/media/router/index/user/45/thumb300x150/image.png.png"/></div>
				<div class="pEvent_content">
					<div class="pEvent_head"><span class="pEvent_title">DD2</span> - <span class="pEvent_currency">$</span><span class="pEvent_price">234</span></div>
					<ul class="pEvent_info">
						<li><span class="pEi-calendar"></span><span class="pEi-value">09 Января</span></li>
						<li><span class="pEi-duration"></span><span class="pEi-value">0 минут</span></li>
					</ul>
					<div class="pEvent_decr">
						<p>SS2</p>
					</div>
					<div class="pEvent_footer"><a href="/User/task/7" style="background-color: #FFBA4B;color: #FFF;  border: none;" class="btn btn-default">Перейти к задаче</a></div>
				</div>
			</li>
		</ul>
	</div>
</div>
<!-- end ==> map-panels-->
<script>

/*------  variables  ------*/

	var MAP;
	var MyId;
	var onlyTasks = false;
	var MapElement = document.getElementById('map-canvas');
	var MapOver = document.getElementById('map-over-block');
	var ContainerFluid;
	var MapTimerEvent;
	var MapTimerAfterMarker;
	var PanelTimerEvent;
	var MapEvent_idle = 'idle';
	var MapEvent_dragstart = 'dragstart';
	var MapEvent_zoom_changed = 'zoom_changed';
	var MapLatLng = <?=json_encode($initCoords)?>;
	var markerUserClusterer;
	var MapTriggerShowSearch;
	var MapFirstTask = true;
	var LoadData;
	var MapRequest = true;
	var MapWriteResults = true;
	var MapRects = [];
	var MapConfig = {
		//_mapInitialize
		mapInitializeZoom: 7,
		disableDefaultUI: true,
		//markers
		maxZoom: 21,
		minZoom: 4,
		gridSize: 80,
		nearVal: 0.3,
		//timeouts
		timeoutIdle: 100,
		timeoutShow: 400,
		//anim time
		slideTime: 250,
		//classNames
		classMapLoad: 'map-loading',
		classMapShowNow: 'map-show-now',
		classMapPrepare: 'map-prepare',
		classOpenedTitle: 'openedNext',
		classOpenedPanel: 'openedPanel',
		classOpenedPanelSearch: 'openedPanelSearch',
		classOpenedPanelFilter: 'openedPanelFilter',
		classOpenedPanelEvent: 'openedPanelEvent',
		idPanelUsers: '#visibleUsers',
		idPanelEvents: '#eventEvents',
		idPanelEventsExternal: '#externalEvents',
		idPanelGroups: '#visibleGroups',
		idPanelInvests: '#visibleInvests',
		panelElems: {
			title: '.title',
			count: '.visibleCount',
			list: '.visibleList',
			panel: '.panelList'
		}
	};
	var MapGroups = {
		events: {},
		events_by_id: {},
		externals: {},
		externals_by_id: {},
		users: {},
		users_by_id: {},
		groups: {},
		groups_by_id: {},
		invests: {},
		invests_by_id: {}
	};
	var visibleTasks;
	var ElemCounts = {};
	var MapInstance = {
		panels: {},
		eventsArray: [],
		externalsArray: [],
		usersArray: [],
		groupsArray: [],
		investsArray: [],
		rememberedEvents: {},
		rememberedEventsExternal: {},
		rememberedUsers: {},
		rememberedGroups: {},
		rememberedInvests: {},
		rememberedEventsOld: {},
		rememberedEventsExternalOld: {},
		rememberedUsersOld: {},
		rememberedGroupsOld: {},
		rememberedInvestsOld: {},
		myLocation: new google.maps.LatLng(MapLatLng.lat, MapLatLng.lng),
		xhrString: '',
		beforePanelEvent: false,
		beforePanelEventCl: [],
		markerUsersOld: {},
		markerEventsOld: {},
		markerGroupsOld: {},
		markerInvestsOld: {},
		markerEventsExternalOld: {}
	};
	var EventInfo = ['created', 'period'];
	var Local = {
		months: [
			"<?php echo __(' January'); ?>",
			"<?php echo __(' February'); ?>",
			"<?php echo __(' March'); ?>",
			"<?php echo __(' April'); ?>",
			"<?php echo __(' May'); ?>",
			"<?php echo __(' June'); ?>",
			"<?php echo __(' July'); ?>",
			"<?php echo __(' August'); ?>",
			"<?php echo __(' September'); ?>",
			"<?php echo __(' October'); ?>",
			"<?php echo __(' November'); ?>",
			"<?php echo __(' December'); ?>"
		],
		eventTypes: {
			meet: "<?php echo __('Meeting'); ?>",
			call: "<?php echo __('Call'); ?>",
			mail: "<?php echo __('Send email'); ?>",
			conference: "<?php echo __('Conference'); ?>",
			sport: "<?php echo __('Sport events'); ?>",
			task: "<?php echo __('Task'); ?>",
			purchase: "<?php echo __('Purchase'); ?>",
			entertain: "<?php echo __('Entertainment'); ?>",
			pay: "<?php echo __('Payment'); ?>",
			none: "<?php echo __('Other events'); ?>"
		},
		weeks: "<?php echo __('weeks'); ?>",
		days: "<?php echo __('days'); ?>",
		hours: "<?php echo __('hours'); ?>",
		minutes: "<?php echo __('minutes'); ?>",
		created: "<?php echo __('Created'); ?>",
		period: "<?php echo __('Period'); ?>",
		author: "<?php echo __('Author'); ?>",
		responsible: "<?php echo __('Responsible'); ?>",
		tasks: "<?php echo __('Tasks'); ?>",
		events: "<?php echo __('Events'); ?>"

	};
	var mapStyle = {
		w: 70,
		h: 70,
		tc: '#ffffff',
		ts: 10,
		url1: '/img/cluster/',
		url2: '/img/cluster/',
		url3: '/img/events_bt.png'
	};
	var styles = [[{
		url: mapStyle.url1 + 'group0.png',
		width: mapStyle.w,
		height: mapStyle.h,
		textColor: mapStyle.tc,
		textSize: mapStyle.ts
	}, {
		url: mapStyle.url1 + 'group1.png',
		width: mapStyle.w,
		height: mapStyle.h,
		textColor: mapStyle.tc,
		textSize: mapStyle.ts
	}, {
		url: mapStyle.url1 + 'group2.png',
		width: mapStyle.w,
		height: mapStyle.h,
		textColor: mapStyle.tc,
		textSize: mapStyle.ts + 2
	}], [{
		url: mapStyle.url2 + 'cluster0.png',
		width: mapStyle.w,
		height: mapStyle.h,
		textColor: mapStyle.tc,
		textSize: mapStyle.ts
	}, {
		url: mapStyle.url2 + 'cluster1.png',
		width: mapStyle.w,
		height: mapStyle.h,
		textColor: mapStyle.tc,
		textSize: mapStyle.ts
	}, {
		url: mapStyle.url2 + 'cluster2.png',
		width: mapStyle.w,
		height: mapStyle.h,
		textColor: mapStyle.tc,
		textSize: mapStyle.ts + 2
	}], [{
		url: mapStyle.url3,
		width: mapStyle.w / 2,
		height: mapStyle.h / 2,
		textColor: mapStyle.tc,
		textSize: mapStyle.ts + 4
	}, {
		url: mapStyle.url3,
		width: mapStyle.w,
		height: mapStyle.h,
		textColor: mapStyle.tc,
		textSize: mapStyle.ts
	}, {
		url: mapStyle.url3,
		width: mapStyle.w,
		height: mapStyle.h,
		textColor: mapStyle.tc,
		textSize: mapStyle.ts + 2
	}]];

	var search = null;

/*------  map_functions  ------*/

	var _mapInitialize = function() {
			// create new google map
			MAP = new google.maps.Map(MapElement, {
				zoom: MapConfig.mapInitializeZoom,
				minZoom: MapConfig.minZoom,
				maxZoom: MapConfig.maxZoom,
				center: MapInstance.myLocation,
				disableDefaultUI: MapConfig.disableDefaultUI,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			});

			// add event idle
			google.maps.event.addListener(MAP, MapEvent_idle, function(){
				if (MapFirstTask) {
					console.warn(MapEvent_idle + ' first');
					_mapLoadPlanet();
				} else {
					console.warn(MapEvent_idle + ' wait');
					MapTimerEvent = setTimeout(function() {
						console.warn(MapEvent_idle + ' init');
						_mapLoadPlanet();
					}, MapConfig.timeoutIdle);
				}
			});
			google.maps.event.addListener(MAP, MapEvent_dragstart, function() {
				console.warn(MapEvent_dragstart);
				clearTimeout(MapTimerEvent);
			});
			google.maps.event.addListener(MAP, MapEvent_zoom_changed, function() {
				console.warn(MapEvent_zoom_changed);
				clearTimeout(MapTimerEvent);
			});
		},

		_mapDoOnce = function() {
			MapFirstTask = false;
			_mapShow();
		}

		_mapShow = function() {
			setTimeout(function() {
				MapOver.classList.add(MapConfig.classMapShowNow);
			}, MapConfig.timeoutShow);
		},

		_mapBounds = function() {
			var bounds = MAP.getBounds();
			var ne = bounds.getNorthEast();
			var sw = bounds.getSouthWest();
			MapInstance.boundsRect = {
				'minlat': sw.lat(),
				'maxlat': ne.lat(),
				'minlng': sw.lng(),
				'maxlng': ne.lng()
			};
		},

		_mapTestRect = function(rOld, rNew, prefix, m1, m2) {
			var flag = (rOld[m1+prefix] <= rNew[m1+prefix]
				&& rOld[m2+prefix] >= rNew[m2+prefix]);
			return flag;
		},

		_mapRectangles = function(rect) {
			if (MapFirstTask) {
				MapRects.push(rect);
			} else {
				MapRequest = true;
				for (var i = 0; i < MapRects.length; i++) {
					var old = MapRects[i];
					if (_mapTestRect(old, rect, 'lat', 'min', 'max')) {
						if (_mapTestRect(old, rect, 'lng', 'min', 'max')) {
							MapRequest = false;
							break;
						}
					}
				}
				if (MapRequest) {
					var flag = true;
					for (var i = 0; i < MapRects.length; i++) {
						var old = MapRects[i];
						if (_mapTestRect(old, rect, 'lat', 'max', 'min')) {
							if (_mapTestRect(old, rect, 'lng', 'max', 'min')) {
								console.log('Yes');
								MapRects[i] = rect;
								flag = false;
							}
						}
					}
					if (flag) {
						MapRects.push(rect);
					}
					var tmpRect = {};
					var tmpArr = [];
					for (var i = 0; i < MapRects.length; i++) {
						var curR = MapRects[i];
						if (tmpRect != curR) {
							tmpArr.push(curR)
						}
						tmpRect = MapRects[i];
					}
					MapRects = tmpArr;
				}
			}
		},

		_mapLoadPlanet = function() {
			_mapBounds();
			if (ContainerFluid.length) {
				ContainerFluid.addClass(MapConfig.classMapLoad);
			}
			if (MapFirstTask) {
				$.ajax({
					type: 'POST',
					'url': 'UserAjax/loadPlanetData',
					data: true,
					global: false,
					success: function(responseString) {
						LoadData = JSON.parse(responseString);
						MyId = LoadData.myId;
					}
				}).responseText;
			}
			_mapRectangles(MapInstance.boundsRect);
			//if search query not empty
			search = $('#searchInput').val() ? $('#searchInput').val() : null;
			if(search != null && search != '') {
				_clearWhenSearch();
				$('#returnMarkers').fadeIn(400);
			}
			if (MapRequest) {
				console.log('==> AJAX');
				console.time('loadPlanet');
				$.ajax({
					type: 'POST',
					'url': 'UserAjax/loadPlanet',
					data: {
						location: MapInstance.boundsRect,
						userKeys: MapInstance.usersArray,
						eventKeys: MapInstance.eventsArray,
						search: search
					},
					global: false,
					ifModified: true,
					success: function(responseString) {
						console.timeEnd('loadPlanet');
						console.time('markersInit');
						MapInstance.panelFlag = true;
						MapInstance.xhrData = JSON.parse(responseString);
						_mapMarkers();
					}
				}).responseText;
			} else {
				console.log('==> NO REQUEST');
				console.time('markersInit');
				MapInstance.panelFlag = true;
				MapInstance.xhrData = {
					events: "",
					users: "",
					groups: "",
					invests: "",
					externals: ""
				}
				_mapMarkers();
			}
		},

		_mapClearMarkers = function() {
			MapInstance.markerUsers = [];
			MapInstance.markerEvents = [];
			MapInstance.markerEventsExternal = [];
			MapInstance.markerGroups = [];
			MapInstance.markerInvests = [];
		},

		tmpUser = [
			'<a href="',
			'" id="marker-user-',
			'" class="k-marker user" data-toggle="tooltip" data-placement="bottom" title="',
			'"><span style="background-image:url(',
			');"></span></a>'
		],

		tmpEvent = [
			'<div class="k-marker event event-element event-',
			'" data-event="',
			'" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="',
			'"><div class="event-icon"></div></div>'
		],

		tmpGroup = [
			'<a href="',
			'" id="marker-group-',
			'" class="k-marker group" data-toggle="tooltip" data-placement="bottom" title="',
			'" target="_blank"><span style="background-image:url(',
			');"></span></a>'
		],

		tmpInvest = [
			'<a href="',
			'" id="marker-invest-',
			'" class="k-marker invest" data-toggle="tooltip" data-placement="bottom" title="',
			'" target="_blank"><span style="background:',
			'"></span></a>'
		],

		_mapSetElemens = function(elementType, remembered) {
			for (var key in MapInstance.xhrData[elementType]) {
				if (remembered.hasOwnProperty(key)) {
					continue;
				} else {
					var value = MapInstance.xhrData[elementType][key];
					MapInstance[elementType+'Array'].push(key);
					switch(elementType) {
						case 'events':
							_mapSetEvent(key, value, remembered);
							break;
						case 'externals':
							_mapSetEvent(key, value, remembered, elementType);
							break;
						case 'groups':
							_mapSetGroup(key, value, remembered);
							break;
						case 'invests':
							_mapSetInvest(key, value, remembered);
							break;
						default:
							_mapSetUser(key, value, remembered);
					}
				}
			}
		},

		_mapSetUser = function(key, value, remembered) {
			var position = new google.maps.LatLng(value.lat, value.lng);
			var userlink = '/User/view/'+key;
			var username = value.username;
			var userimage = value.image.replace(/noresize/,"thumb100x100");
			var userskills = value.skills;
			var itsme = (key == MyId);
			var content = ''+
				tmpUser[0]+userlink+
				tmpUser[1]+key+
				tmpUser[2]+username+
				tmpUser[3]+userimage+
				tmpUser[4];

			remembered[key] = {
				model: 'user',
				itsme: itsme,
				lat: parseFloat(value.lat),
				lng: parseFloat(value.lng),
				title: username,
				subtitle: userskills,
				image: userimage,
				link: userlink,
				key: key
			};

			var newMarker = new RichMarker({
				keyId: key,
				model: 'user',
				itsme: itsme,
				position: position,
				content: content,
				title: username,
				draggable: false,
				shadow: 'flat'
			});

			MapInstance.markerUsers.push(newMarker);
			_panelSetGroups(newMarker, 'users');
		},

		_mapSetEvent = function(key, value, remembered, elementType) {

			var my_events = value.user_id == MyId;
			var rId = value.recipient_id;
			var FlagEv = true;

			/*if (rId.length) {
				var rMy = (rId == MyId);
				if (!my_events) {
					FlagEv = rMy;
				}
				if (rMy) {
					my_events = true;
				}
			}*/

			if (!my_events && rId.length) {
				/*my_events = (rId == MyId);
				console.log(rId, MyId, my_events);*/
				FlagEv = (rId == MyId);
			}

			if (FlagEv) {
				var position = new google.maps.LatLng(value.lat, value.lng);
				var eventType = value.type;
				var eventTitle = value.title;
				var eventDescr = value.descr ? '\n'+value.descr : '';
				var my_events = value.user_id == MyId;
				var content = ''+
					tmpEvent[0]+eventType+' '+eventType+
					tmpEvent[1]+key+
					tmpEvent[2]+eventTitle+eventDescr+
					tmpEvent[3];

				var icon;
				switch(eventType) {
					case 'meet':
						icon = 'user';
						break;
					case 'call':
						icon = 'phone_alt';
						break;
					case 'mail':
						icon = 'send';
						break;
					case 'conference':
						icon = 'group';
						break;
					case 'sport':
						icon = 'rugby';
						break;
					case 'purchase':
						icon = 'shopping_cart';
						break;
					case 'entertain':
						icon = 'gamepad';
						break;
					case 'pay':
						icon = 'credit_card';
						break;
					case 'none':
						icon = 'calendar';
						break;
					default:
						icon = 'check';
				}
				value.icon = icon;

				if (elementType == 'externals') {
					var model = 'external';
				} else {
					var model = 'event';
				}


				value.image = value.image.replace(/noresize/,"thumb300x300");
				value.model = model;
				value.my_events = my_events;
				value.lat = parseFloat(value.lat);
				value.lng = parseFloat(value.lng);
				var created = _mapDate(value.created);
				value.created = created.D+' '+created.ms+' '+created.Y;
				var period = value.endtime - value.starttime;
				value.period = period/(60*60);

				remembered[key] = value;

				var visible = MapFilters.hide_event(eventType, my_events);

				var newMarker = new RichMarker({
					keyId: key,
					model: model,
					type: eventType,
					my_events: my_events,
					all_event: true,
					position: position,
					draggable: false,
					content: content,
					title: eventTitle,
					shadow: 'flat',
					visible: visible
				});

				if (elementType == 'externals') {
					MapInstance.markerEventsExternal.push(newMarker);
					_panelSetGroups(newMarker, 'externals');
				} else {
					MapInstance.markerEvents.push(newMarker);
					_panelSetGroups(newMarker, 'events');
				}
			}
		},

		_mapSetGroup = function(key, value, remembered) {

			var position = new google.maps.LatLng(value.lat, value.lng);
			var title = value.title;
			var groupId = value.id;
			var link = '/Group/view/'+groupId;
			var image = value.image;

			var content = ''+
				tmpGroup[0]+link+
				tmpGroup[1]+key+
				tmpGroup[2]+title+
				tmpGroup[3]+image+
				tmpGroup[4];

			value.link = link;
			value.model = 'group';
			value.lat = parseFloat(value.lat);
			value.lng = parseFloat(value.lng);
			var created = _mapDate(value.created);
			value.created = created.D+' '+created.ms+' '+created.Y;

			remembered[key] = value;

			var newMarker = new RichMarker({
				keyId: key,
				model: 'group',
				groupId: groupId,
				position: position,
				content: content,
				title: title,
				draggable: false,
				shadow: 'flat'
			});

			MapInstance.markerGroups.push(newMarker);
			//_panelSetGroups(newMarker, 'groups');
		},

		_mapSetInvest = function(key, value, remembered) {

			var position = new google.maps.LatLng(value.lat, value.lng);
			var title = value.title;
			var id = value.id;
			var link = '/InvestProject/view/'+id;

			var content = ''+
				tmpInvest[0]+link+
				tmpInvest[1]+key+
				tmpInvest[2]+title+
				tmpInvest[3]+'#fefefe url(/img/logo_footer_bwst.png); background-size: 100%; background-repeat: no-repeat;'+
				tmpInvest[4];

			value.link = link;
			value.model = 'invest';
			value.lat = parseFloat(value.lat);
			value.lng = parseFloat(value.lng);
			var created = _mapDate(value.created);
			value.created = created.D+' '+created.ms+' '+created.Y;

			remembered[key] = value;

			var newMarker = new RichMarker({
				keyId: key,
				model: 'invest',
				investId: id,
				position: position,
				content: content,
				title: title,
				draggable: false,
				shadow: 'flat'
			});

			MapInstance.markerInvests.push(newMarker);
			//_panelSetGroups(newMarker, 'invest');
		},

		_mapDate = function(data) {

			var arr = data.split('-');
			var obj = {
				Y: arr.shift(),
				M: parseInt(arr.shift()) - 1
			};
			arr = arr[0].split(' ');
			obj.D = parseInt(arr.shift());
			obj.t = arr[0];
			arr = obj.t.split(':');
			obj.h = arr.shift();
			obj.m = arr.shift();
			obj.s = arr[0];
			obj.ms = Local.months[obj.M].slice(1);
			return obj;
		},

		_mapMarkers = function() {
			_mapClearMarkers();
			_mapSetElemens('users', MapInstance.rememberedUsers);
			_mapSetElemens('events', MapInstance.rememberedEvents);
			_mapSetElemens('externals', MapInstance.rememberedEventsExternal);
			_mapSetElemens('groups', MapInstance.rememberedGroups);
			_mapSetElemens('invests', MapInstance.rememberedInvests);
			_panelInitSearch();
			_panelCheckEventElement();
			if (MapWriteResults) {
				_setOldMarkers(MapInstance.markerUsers, MapInstance.markerEvents, MapInstance.markerGroups, MapInstance.markerInvests, MapInstance.markerEventsExternal);
				MapWriteResults = false;
			}
			_mapPlaceMarkers(MapInstance.markerUsers.concat(MapInstance.markerEvents).concat(MapInstance.markerGroups).concat(MapInstance.markerInvests).concat(MapInstance.markerEventsExternal));
		},

		_mapPlaceMarkers = function(markersArray){
			if (MapFirstTask) {
				markerUserClusterer = new MarkerClusterer(MAP, markersArray, {
					maxZoom: MapConfig.maxZoom,
					styles: styles[1],
					ignoreHidden: true
				});
				google.maps.event.addListener(markerUserClusterer, "clusterclick", function (cluster) {
					if (MAP.getZoom() == MapConfig.maxZoom) {
						MapTriggerShowSearch = true;
					} else {
						MapTriggerShowSearch = false;
					}
				});
				_mapDoOnce();
			} else if (markersArray.length) {
				console.warn('addMarkers');
				markerUserClusterer.addMarkers(markersArray, false);
				//_panelInitSearch();
			} else {
				console.warn('nothing');
				//_panelInitSearch();
			}
			ContainerFluid.removeClass(MapConfig.classMapLoad);
			if (MAP.getZoom() == MapConfig.maxZoom) {
				if (MapTriggerShowSearch) {
					ContainerFluid
						.removeClass(MapConfig.classOpenedPanelEvent)
						.addClass(MapConfig.classOpenedPanelSearch);
				}
			}
			_MapAfterPaint();
			console.timeEnd('markersInit');
		},

		_MapAfterPaint = function() {
			clearTimeout(MapTimerAfterMarker);
			MapTimerAfterMarker = setTimeout(function() {
				$('.k-marker').tooltip();
			}, 500);
		},

		_MapFilterListEventType = function(ths) {
			markerUserClusterer.repaint();
			_MapAfterPaint();
			ths.list_event_show = [];
			ths.list_event_hide = [];
			$('#filter_event_type').find('.elChecker').each(function(i, el) {
				if (el.checked) {
					ths.list_event_show.push(el.value);
				} else {
					ths.list_event_hide.push(el.value);
				}
			});
		},

		_mapMarkersNearCycle = function(namer, array) {
			var Namer = namer.charAt(0).toUpperCase()+namer.slice(1)+'s';
			var arr = namer+'Visible';
			var rem = 'remembered'+Namer;
			for (var i = 0; i < MapInstance[arr].length; i++) {
				var k = MapInstance[arr][i];
				var el = MapInstance[rem][k];
				array.push({
					model: namer,
					keyId: k,
					lat: el.lat,
					lng: el.lng,
					toGroup: false
				});
			}
			return array;
		},

		distanceFrom = function(lat1,lng1, lat2, lng2, zoom) {
	        var radlat1 = Math.PI * lat1 / 180,
	        	radlat2 = Math.PI * lat2 / 180,
	        	radlon1 = Math.PI * lng1 / 180,
	        	radlon2 = Math.PI * lng2 / 180,
	        	theta = lng1 - lng2,
	        	radtheta = Math.PI * theta / 180,
	        	dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
	        dist = Math.acos(dist);
	        dist = dist * 180 / Math.PI;
	        dist = dist * 60 * 1.1515;

	        dist = dist * 1.609344 * 100;
	        var flag = (dist * (zoom - MapConfig.maxZoom) <= MapConfig.nearVal);

	        return flag;
	    },

		_mapMarkersNear = function(zoom) {
			var tmpArr = [];
			tmpArr = _mapMarkersNearCycle('user', tmpArr);
			tmpArr = _mapMarkersNearCycle('event', tmpArr);
			MapConfig.nearVal = 1 * (MapConfig.maxZoom / 100);
			for (var i = 0; i < tmpArr.length; i++) {
				var ths = tmpArr[i];
				for (var j = 0; j < tmpArr.length; j++) {
					if (i == j) {
						continue;
					}
					var nxt = tmpArr[j];
					if (nxt.toGroup) {
						continue;
					}
					var flg = distanceFrom(ths.lat, ths.lng, nxt.lat, nxt.lng, zoom);
					if (flg) {
						ths.toGroup = true;
						nxt.toGroup = true;
					}
				}
			}
			for (var i = 0; i < tmpArr.length; i++) {
				var el = tmpArr[i];
				if (el.toGroup) {
					$(MapGroups[el.model+'s_by_id'][el.keyId].b)
						.addClass('mapButtonTrigger triggerToGroup glyphicons more')
						.attr('data-trigger', '#mapButtonSearch');
				}
			}

		},

		MapFilters = {
			list_event_show: [],
			list_event_hide: [],
			myevents: false
		};

		MapFilters.hide_event = function(type, my) {
			var flag = true;
			if (this.myevents) {
				flag = my;
			}
			for (var i = 0; i < this.list_event_hide.length; i++) {
				if (this.list_event_hide[i] == type) {
					flag = false;
					break;
				}
			}
			return flag;
		};

		MapFilters.filter_event_type = function(filterEl) {
			var filterArray = MapGroups.events[filterEl.value];
			if (filterArray) {
				this.filter_change_event(filterArray, filterEl.checked);
			}
			_MapFilterListEventType(this);
		};

		MapFilters.filter_change_event = function(markerList, markerVis) {
			var my = this.myevents;
			for (var i = 0; i < markerList.length; i++) {
				var marker = markerList[i];
				console.log(my);
				if (my && !marker.my_events) {
					markerList[i].setVisible(false);
				} else {
					markerList[i].setVisible(markerVis);
				}
			}
		};

		MapFilters.filter_event = function(filterEl) {
			var condition = filterEl.value;
			this.myevents = (condition == 'my_events');
			for (var key in MapGroups.events) {
				var value = MapGroups.events[key];
				if (value) {
					var flag = $('#filter_event_type-'+key).prop('checked');
					this.filter_change_event(value, flag);
				}
			}
			_MapFilterListEventType(this);
		};

		google.maps.event.addDomListener(window, 'load', _mapInitialize);

/*------  panel_functions  ------*/

	var tmpPanelEvent = [
			'<div class="panelItem filterItem filtertype event-element event-',
			'"><span class="panelAvatar" style="background-image:url(',
			'</span><span class="panelInfo"><span class="panelSubtitle" title="',
			'</span><span class="panelTitle" title="',
			'</span></span></div>'
		],

		tmpPanelUser = [
			'<a href="',
			'" class="panelItem"><span class="panelAvatar" style="background-image:url(',
			');"></span><span class="panelInfo"><span class="panelTitle" title="',
			'</span><span class="panelSubtitle" title="',
			'</span></span></a>'
		],

		tmpPanelInvest = [
			'<a href="',
			'" class="panelItem">',
			'<span class="panelInfo"><span class="panelTitle" title="',
			'</span><span class="panelSubtitle" title="',
			'</span></span></a>'
		],

		tmpElemEvent = [
			'<div class="pEvent_preview"><img src="',
			'"></div><div class="pEvent_content"><div class="pEvent_head"><span class="pEvent_title">',
			'</span>',
			'</div>',
			'<div class="pEvent_footer">',
			'</div></div>'
		],

		_panelCheckEventElement = function() {
			if (ContainerFluid.hasClass(MapConfig.classOpenedPanelEvent)) {
				var visible = _panelSetVisible('event', PanelEvents, MapInstance.rememberedEvents);
				var keyId = PanelEventElems.list.children('li').data('element');
				var flag = true;
				for (var i = 0; i < visible.length; i++) {
					if (visible[i] == keyId) {
						flag = false;
					}
				}
				if (flag) {
					PanelEventElems.close.trigger('click');
				} else {
					_panelEventNav(keyId, visible);
				}
			}
		},

		_panelAfterPanelEvent = function() {
			ContainerFluid
				.removeClass(MapConfig.classOpenedPanelEvent);
			if (MapInstance.beforePanelEventCl.length) {
				ContainerFluid
					.addClass(MapInstance.beforePanelEventCl.join(' '));
			}
			_panelInitSearch();
			_panelBeforePanelEvent(false);
		},

		_panelBeforePanelEvent = function(bool) {
			if (bool && !MapInstance.beforePanelEvent) {
				if (ContainerFluid.hasClass(MapConfig.classOpenedPanelSearch)) {
					MapInstance.beforePanelEventCl
						.push(MapConfig.classOpenedPanelSearch);
				}
				if (ContainerFluid.hasClass(MapConfig.classOpenedPanelFilter)) {
					MapInstance.beforePanelEventCl
						.push(MapConfig.classOpenedPanelFilter);
				}
			} else {
				MapInstance.beforePanelEventCl = [];
			}
			MapInstance.beforePanelEvent = bool;
		},

		_panelMapButtonEvent = function(button) {
			var params = button.data('param');
			_panelBeforePanelEvent(false);
			ContainerFluid
				.removeClass(MapConfig.classOpenedPanelEvent)
				.toggleClass(MapConfig.classOpenedPanel+params.panel);
			_panelInitSearch();
		},

		_panelEventPrice = function(price) {
			var str = '';
			if (price !== null) {
				str = ' - <span class="pEvent_currency">$<span class="pEvent_price">'+price+'</span></span>';
			}
			return str;
		},

		_panelEventDescr = function(descr, type) {
			var str = '';
			if (descr.length) {
				str += '<div class="pEvent_decr"><p>'+descr+'</p></div>';
			}
			return str;
		},

		_panelEventOwners = function(data) {
			var arr = ['<div class="pEvent_owners">','</div>'];
			var str = Local.author+' - ';
			var link = '<a href="/User/view/'+data.user_id+'">'+data.user_full_name+'</a>';
			str += link;
			if (data.recipient_id.length) {
				var respons = '<br>'+Local.responsible+' - ';
				respons += '<a href="/User/view/'+data.recipient_id+'">'+data.recipient_full_name+'</a>';
				str += respons;
			}
			return arr.join(str);
		},

		_getPeriod = function(val) {
			var str;
			var last = 'minutes';
			var arr = ['weeks','days','hours',last];
			var obj = {
				minutes: val,
				hours: parseInt(val / 60)
			};
			obj.days = parseInt(obj.hours / 24);
			obj.weeks = parseInt(obj.days / 7);
			for (var i = 0; i < arr.length; i++) {
				var x = arr[i];
				var y = obj[x];
				if (y || x == last) {
					str = Local[x] +' - '+y;
					break;
				}
			}
			return str;
		},

		_panelEventInfo = function(data) {
			var arr = [];
			for (var i = 0; i < EventInfo.length; i++) {
				var namer = EventInfo[i];
				var str = '<span>';
				var val;
				switch(namer) {
					case 'created':
						str += Local[namer]+': '+data[namer];
						break;
					case 'period':
						str += Local[namer]+': '+_getPeriod(data[namer]);
						break;
					default:
						str += data[namer];
				}
				str += '</span>';
				arr.push(str);
			}
			return '<div class="pEvent_info">'+arr.join('\n')+'</div>';
		},

		_panelEventIcon = function(icon, type) {
			var str = '';
			if (!onlyTasks) {
				str += '<em class="glyphicons '+icon+'" title="'+Local.eventTypes[type]+'"></em>';
			}
			return str;
		},

		_panelEventLink = function(eShare, eID, rID) {
			var str = '';
			str += '<a href="/User/task/'+eID+'" class="btn btn-color"><?php echo __("Back to the task"); ?></a>';
			if (rID == MyId) {
				var flag = false;
				for (var i = 0; i < eShare.length; i++) {
					var d = eShare[i];
					console.log(d.user_id);
					console.log(d.user_id == MyId);
					console.log(d.acceptance);
					console.log(d.acceptance == 0);
					if (d.user_id == MyId && d.acceptance == 0) {
						flag = true;
						break;
					}
				}
				if (flag) {
					str += '<br/><a href="/User/task/'+eID+'" onclick="_panelAcceptEvent(\''+eID+'\',\''+rID+'\', this); return false;" class="btn btn-default btn-accept"><?php echo __("Accept"); ?></a>';
				}
			}
			return str;
		},

		_panelAcceptEvent = function(eventId, userId, thsBtn) {
//			console.log(eventId, userId, thsBtn);
			$.post('/UserAjax/acceptEvent.json', {
				fromMap: true,
				id: eventId,
				user_id:userId
			}, function (response) {
				if (response.status == 'OK') {
					var parent = $(thsBtn).closest('.pEvent_footer');
					$(thsBtn).remove();
					parent.append('<span class="btn" style="margin-top: 8px; cursor: default !important;"><?php echo __("You accepted task"); ?></span>')
				}
			});
		},

		_panelEventElementMarkup = function(data) {
			var cls = '';
			var img = data.image.split('/');
			if (img.pop() == 'no-photo.jpg') {
				cls = ' panelImgCrop';
				console.log(cls);
			}
			var str = '<li class="panelEventsActive'+cls+'" data-element="'+data.event_id+'">'+
				tmpElemEvent[0]+data.image+
				tmpElemEvent[1]+_panelEventIcon(data.icon, data.type)+data.title+
				tmpElemEvent[2]+_panelEventPrice(data.price)+
				tmpElemEvent[3]+
				_panelEventDescr(data.descr, data.type)+
				_panelEventOwners(data)+
				_panelEventInfo(data)+
				tmpElemEvent[4]+
				_panelEventLink(data.UserEventShare, data.event_id, data.recipient_id)+
				tmpElemEvent[5];
			return str;
		},

		_panelEventNavElemnts = function(flag, dir) {
			if (flag) {
				PanelEventElems[dir].addClass('event-element').data('event', flag);
			} else {
				PanelEventElems[dir].removeClass('event-element');
			}
		},

		_panelEventNav = function(eventKey, visArray) {
			var visible = visArray;
			if (typeof(visible) == 'undefined') {
				visible = _panelSetVisible('event', PanelEvents, MapInstance.rememberedEvents);
			}
			var flagPrev = false,
				flagNext = false,
				flagCurr = false;
			/*console.log(visible);
			console.log(MapGroups);
			var visible
			for (var i = 0; i < visible.length; i++) {
				visible[i]
			};*/
			if (visible.length > 1) {
				for (var i = 0; i < visible.length; i++) {
					var key = visible[i];
					if (key == eventKey) {
						flagCurr = true;
					}
					if (key != eventKey) {
						if (flagCurr && !flagNext) {
							flagNext = key;
							break;
						} else if (!flagCurr) {
							flagPrev = key;
						}
					}
				}
			}
			_panelEventNavElemnts(flagPrev, 'prev');
			_panelEventNavElemnts(flagNext, 'next');
		},

		_panelEventElement = function(eventKey) {
			var eventData = MapInstance.rememberedEvents[eventKey];
			var eventElement = _panelEventElementMarkup(eventData);
			PanelEventElems.list.html(eventElement);
			PanelEventElems.list.removeClass('fadeListEvents');
			_panelEventNav(eventKey);
		},

		_panelWorkFlag = function(className) {
			var flag = (ContainerFluid.hasClass(className) && MapInstance.panelFlag);
			if (flag) {
				MapInstance.panelFlag = false;
			}
			return flag;
		},

		_panelSetGroups = function(marker, model) {
			var branch = MapGroups[model];
			MapGroups[model+'_by_id'][marker.keyId] = marker;
			if (model == 'events') {
				var type = marker.type;
				if (!branch.hasOwnProperty(type)) {
					branch[type] = new Array;
				}
				branch[marker.type].push(marker);
			}
		},

		_panelSearchElems = function(iD, obj) {
			var group = $(iD);
			MapInstance.panels[obj] = {
				group: group
			};
			for (var key in MapConfig.panelElems) {
				MapInstance.panels[obj][key] = group.find(MapConfig.panelElems[key]);
			}
			return MapInstance.panels[obj];
		},

		_panelHide = function(obj) {
			obj.list.html('');
			obj.panel.stop().slideUp(MapConfig.slideTime, function() {
				obj.title.removeClass(MapConfig.classOpenedTitle);
			})
		},

		_panelSearchSetElements = function(branch, obj, data) {
			var string = '';
			switch(branch) {
				case 'external':
				case 'event':
					var fl = true;
					if (onlyTasks) {
						fl = (data.type === 'task');
					}
					if (fl) {
						string += ''+
							tmpPanelEvent[0]+data.type+'" data-id="'+data.event_id+'" data-event="'+data.event_id+
							tmpPanelEvent[1]+data.image+');">'+
							tmpPanelEvent[2]+data.title+'">'+_panelEventIcon(data.icon, data.type)+data.title+
							tmpPanelEvent[3]+data.descr+'">'+data.descr+
							tmpPanelEvent[4];
					}
					break;
				case 'group':
						var desc = (data.descr != null) ? data.descr : '';
						string += ''+
						tmpPanelUser[0]+data.link+
						tmpPanelUser[1]+data.image+
						tmpPanelUser[2]+data.title+'">'+data.title+
						tmpPanelUser[3]+desc+'">'+desc+
						tmpPanelUser[4];
					break;
				case 'invest':
						var desc = (data.descr != null) ? data.descr : '';
						string += ''+
						tmpPanelInvest[0]+data.link+
						tmpPanelInvest[1]+
						tmpPanelInvest[2]+data.title+'">'+data.title+
						tmpPanelInvest[3]+desc+'">'+desc+
						tmpPanelInvest[4];
					break;
				default:
					string += ''+
						tmpPanelUser[0]+data.link+
						tmpPanelUser[1]+data.image+
						tmpPanelUser[2]+data.title+'">'+data.title+
						tmpPanelUser[3]+data.subtitle+'">'+data.subtitle+
						tmpPanelUser[4];
			}
			if (string.length) {
				obj.arr.push(string);
			}
		},

		_panelSetVisible = function(branch, obj, remembered) {
			var R = MapInstance.boundsRect;
			var A = [];
			var T = [];
			var events = (branch == 'event');
			if (events) {
				for (var k in ElemCounts) {
					ElemCounts[k] = 0;
				}
			}
			for (var k in remembered) {
				var LAT = parseFloat(remembered[k].lat);
				if (LAT >= R.minlat && LAT <= R.maxlat) {
					var LNG = parseFloat(remembered[k].lng);
					if (LNG >= R.minlng && LNG <= R.maxlng) {
						if (events) {
							ElemCounts['filter_event-all_events']++;
							var type = remembered[k].type;
							var my = remembered[k].my_events;
							ElemCounts['filter_event_type-'+type]++;
							if (my) {
								ElemCounts['filter_event-my_events']++;
							}
							if (MapFilters.hide_event(type, my)) {
								if (type == 'task') {
									T.push(k);
								}
								A.push(k);
							}
						} else {
							A.push(k);
						}
					}
				}
			}
			MapInstance[branch+'Visible'] = A;
			if (events) {
				if (onlyTasks) {
					obj.count.html(T.length);
				}
				for (var k in ElemCounts) {
					if (ElemCounts[k] ||
						k == 'filter_event-all_events' ||
						k == 'filter_event-my_events') {
						$('#count-'+k).html(ElemCounts[k]);
					} else {
						$('#count-'+k).html('');
					}
				}
			}

			return A;
		},

		_panelSearchSet = function(branch, obj, remembered) {
			var visible = _panelSetVisible(branch, obj, remembered);
			if (onlyTasks) {
				if (branch != 'event') {
					obj.count.html(visible.length);
				}
			} else {
				obj.count.html(visible.length);
			}
			obj.arr = [];
			if (visible.length) {
				obj.group.removeClass('panelEmpty');
				for (var i = 0; i < visible.length; i++) {
					var data = remembered[visible[i]];
					_panelSearchSetElements(branch, obj, data);
				}
			} else {
				obj.group.addClass('panelEmpty');
				_panelHide(obj);
			}
		},

		_panelSearchPrint = function(obj) {
			obj.list.html('<li>'+obj.arr.join('</li>\n<li>')+'</li>');
		},

		_panelInitSearch = function(ignoreWorkFlag) {
			if (ignoreWorkFlag ||
				_panelWorkFlag(MapConfig.classOpenedPanelSearch) ||
				_panelWorkFlag(MapConfig.classOpenedPanelFilter)) {
				_panelSearchSet('user', PanelUsers, MapInstance.rememberedUsers);
				_panelSearchPrint(PanelUsers);
				_panelSearchSet('event', PanelEvents, MapInstance.rememberedEvents);
				_panelSearchPrint(PanelEvents);
				_panelSearchSet('external', PanelEventsExternal, MapInstance.rememberedEventsExternal);
				_panelSearchPrint(PanelEventsExternal);
				_panelSearchSet('group', PanelGroups, MapInstance.rememberedGroups);
				_panelSearchPrint(PanelGroups);
				_panelSearchSet('invest', PanelInvests, MapInstance.rememberedInvests);
				_panelSearchPrint(PanelInvests);
			} else {
				return false;
			}
		};

		_clearWhenSearch = function() {
			$('#searchInput').val('');
			//Rememmber data from old result
			MapInstance.rememberedUsersOld = MapInstance.rememberedUsers;
			MapInstance.rememberedEventsOld = MapInstance.rememberedEvents;
			MapInstance.rememberedEventsExternalOld = MapInstance.rememberedEventsExternal;
			MapInstance.rememberedGroupsOld = MapInstance.rememberedGroups;
			MapInstance.rememberedInvestsOld = MapInstance.rememberedInvests;
//			MapInstance.markerUsersOld = MapInstance.markerUsers;
//			MapInstance.markerEventsOld = MapInstance.markerEvents;
//			MapInstance.markerGroupsOld =  MapInstance.markerGroups;
//			MapInstance.markerInvestsOld = MapInstance.markerInvests;
//			MapInstance.markerEventsExternalOld = MapInstance.markerEventsExternal;
			//clear results
			MapInstance.rememberedUsers = {};
			MapInstance.rememberedEvents = {};
			MapInstance.rememberedEventsExternal = {};
			MapInstance.rememberedGroups = {};
			MapInstance.rememberedInvests = {};
			MapRequest = true;
			markerUserClusterer.clearMarkers();
			//Set panels visible after search
			$(MapConfig.idPanelEventsExternal).show();
			$(MapConfig.idPanelGroups).show();
			$(MapConfig.idPanelInvests).show();
			console.log(MapInstance);
		};

		_returnMarkersBack = function() {
			markerUserClusterer.clearMarkers();
			//Take data from old result
			MapInstance.rememberedUsers = MapInstance.rememberedUsersOld;
			MapInstance.rememberedEvents = MapInstance.rememberedEventsOld;
			MapInstance.rememberedEventsExternal = MapInstance.rememberedEventsExternalOld;
			MapInstance.rememberedGroups = MapInstance.rememberedGroupsOld;
			MapInstance.rememberedInvests = MapInstance.rememberedInvestsOld;

			MapInstance.markerUsers = MapInstance.markerUsersOld;
			MapInstance.markerEvents = MapInstance.markerEventsOld;
			MapInstance.markerGroups =  MapInstance.markerGroupsOld;
			MapInstance.markerInvests = MapInstance.markerInvestsOld;
			MapInstance.markerEventsExternal = MapInstance.markerEventsExternalOld;

//			$(MapConfig.idPanelEventsExternal).fadeOut(400);
//			$(MapConfig.idPanelGroups).fadeOut(400);
//			$(MapConfig.idPanelInvests).fadeOut(400);

			_panelInitSearch(true);
//			_panelCheckEventElement();
			_mapPlaceMarkers(MapInstance.markerUsersOld.concat(MapInstance.markerEventsOld).concat(MapInstance.markerGroupsOld).concat(MapInstance.markerInvestsOld).concat(MapInstance.markerEventsExternalOld));
		};

		_setOldMarkers  = function(markerUsers, markerEvents, markerGroups, markerInvests, markerEventsExternal) {
			MapInstance.markerUsersOld = markerUsers;
			MapInstance.markerEventsOld = markerEvents;
			MapInstance.markerGroupsOld =  markerGroups;
			MapInstance.markerInvestsOld = markerInvests;
			MapInstance.markerEventsExternalOld = markerEventsExternal;
		};


/*------  ready  ------*/

	jQuery(document).ready(function($) {

		var panSTname = Local.events;
		if (onlyTasks) {
			panSTname = Local.tasks;
		}
		$('#eventEvents').children('.title').prepend(panSTname+' ');

		MapOver.classList.add(MapConfig.classMapPrepare);
		ContainerFluid = $('.container-fluid');
		PanelUsers = _panelSearchElems(MapConfig.idPanelUsers, 'users');
		PanelEvents = _panelSearchElems(MapConfig.idPanelEvents, 'events');
		PanelEventsExternal = _panelSearchElems(MapConfig.idPanelEventsExternal, 'externals');
		PanelGroups = _panelSearchElems(MapConfig.idPanelGroups, 'groups');
		PanelInvests = _panelSearchElems(MapConfig.idPanelInvests, 'invests');
		PanelEventElems = {
			list: $('#mapPanelEvent ul.panelEventsList'),
			prev: $('#mapPanelEvent span.eventPrev'),
			next: $('#mapPanelEvent span.eventNext'),
			close: $('#mapPanelEvent span.mapPanelEventClose')
		};
		MapInstance.linkToTaskText = PanelEventElems.list.data('text');

		$('.mapButton').on('click', function(event) {
			event.preventDefault();
			_panelMapButtonEvent($(this));
		});

		function nxtSlideToggle(ths, nxt) {
			nxt.stop().slideToggle(MapConfig.slideTime, function() {
				if (nxt.is(':visible')) {
					ths.addClass(MapConfig.classOpenedTitle);
				} else {
					ths.removeClass(MapConfig.classOpenedTitle);
				}
			});
		}

		$('#mapPanelSearch').on('click', '.panelGroup .title', function(event) {
			event.preventDefault();
			var ths = $(this);
			var amt = parseInt(ths.children('.visibleCount').text());
			var nxt = ths.next();
			if (!!amt) {
				nxtSlideToggle(ths, nxt);
			}
		});

		$('#mapPanelFilter').on('click', '.panelGroup .title', function(event) {
			event.preventDefault();
			var ths = $(this);
			var nxt = ths.next();
			if (nxt.children().length) {
				nxtSlideToggle(ths, nxt);
			}
		});

		ContainerFluid.on('click', '.mapButtonTrigger', function(event) {
			event.preventDefault();
			var selector = $(this).data('trigger');
			$(selector).trigger('click');
		});

		ContainerFluid.on('click', '.event-element', function(event) {
			event.preventDefault();
			_panelBeforePanelEvent(true);
			ContainerFluid
				.addClass(MapConfig.classOpenedPanelEvent)
				.removeClass(
					MapConfig.classOpenedPanelSearch+' '+
					MapConfig.classOpenedPanelFilter);
			var tmpTime = 10;
			var ths = $(this);
			var key = ths.data('event');
			/*if (ths.hasClass('eventNav')) {
				PanelEventElems.list.addClass('fadeListEvents');
				tmpTime = 330;
			}*/
			PanelTimerEvent = setTimeout(function() {
				_panelEventElement(ths.data('event'));
			}, tmpTime);
		});

		ContainerFluid.on('click', '.mapPanelEventClose', function(event) {
			event.preventDefault();
			_panelAfterPanelEvent();
		});

		$('#mapPanelFilter').find('.elChecker').each(function(i, el) {
			var iD = el.name+'-'+el.value;
			el.id = iD;
			ElemCounts[iD] = 0;
			$(el).siblings('span').append('&nbsp;<em class="brackets" id="count-'+iD+'">0</em>');
		});

		$('#mapPanelFilter').on('change', '.elChecker', function(event) {
			if (MapFilters.hasOwnProperty(this.name)) {
				MapFilters[this.name](this);
				_panelInitSearch(true);
			}
		});

		$(window).load(function() {

		});
	});
</script>
<style>#map-load{position:absolute;top:0;right:0;bottom:0;left:0;display:none;width:60px;height:46px;margin:auto;-ms-transform:translateY(-80px);transform:translateY(-80px);pointer-events:none;opacity:.6}#map-load:before{content:'';position:absolute;top:50%;left:50%;width:94px;height:94px;margin:-17px 0 0 -48px;border:3px solid #777;border-radius:200px;background-color:#fff}.cssload-thecube,.cssload-thecube .cssload-cube{-webkit-transform:rotateZ(45deg);-moz-transform:rotateZ(45deg);-ms-transform:rotateZ(45deg);position:relative}.map-loading #map-load{display:block}.cssload-thecube{width:45px;height:45px;margin:30px auto 0;-o-transform:rotateZ(45deg);transform:rotateZ(45deg)}.cssload-thecube .cssload-cube{transform:rotateZ(45deg);border:1px solid #fff;float:left;width:50%;height:50%;-webkit-transform:scale(1.1);-moz-transform:scale(1.1);-ms-transform:scale(1.1);-o-transform:scale(1.1);transform:scale(1.1)}.cssload-thecube .cssload-cube:before{content:'';position:absolute;top:0;left:0;width:100%;height:100%;-webkit-transform-origin:100% 100%;-moz-transform-origin:100% 100%;-ms-transform-origin:100% 100%;-o-transform-origin:100% 100%;transform-origin:100% 100%;-webkit-animation:cssload-fold-thecube 1.32s infinite linear both;-moz-animation:cssload-fold-thecube 1.32s infinite linear both;-ms-animation:cssload-fold-thecube 1.32s infinite linear both;-o-animation:cssload-fold-thecube 1.32s infinite linear both;animation:cssload-fold-thecube 1.32s infinite linear both;background-color:#777}.cssload-thecube .cssload-c2{-webkit-transform:scale(1.1) rotateZ(90deg);-moz-transform:scale(1.1) rotateZ(90deg);-ms-transform:scale(1.1) rotateZ(90deg);-o-transform:scale(1.1) rotateZ(90deg);transform:scale(1.1) rotateZ(90deg)}.cssload-thecube .cssload-c3{-webkit-transform:scale(1.1) rotateZ(180deg);-moz-transform:scale(1.1) rotateZ(180deg);-ms-transform:scale(1.1) rotateZ(180deg);-o-transform:scale(1.1) rotateZ(180deg);transform:scale(1.1) rotateZ(180deg)}.cssload-thecube .cssload-c4{-webkit-transform:scale(1.1) rotateZ(270deg);-moz-transform:scale(1.1) rotateZ(270deg);-ms-transform:scale(1.1) rotateZ(270deg);-o-transform:scale(1.1) rotateZ(270deg);transform:scale(1.1) rotateZ(270deg)}.cssload-thecube .cssload-c2:before{-webkit-animation-delay:.165s;-moz-animation-delay:.165s;-ms-animation-delay:.165s;-o-animation-delay:.165s;animation-delay:.165s}.cssload-thecube .cssload-c3:before{-webkit-animation-delay:.33s;-moz-animation-delay:.33s;-ms-animation-delay:.33s;-o-animation-delay:.33s;animation-delay:.33s}.cssload-thecube .cssload-c4:before{-webkit-animation-delay:.495s;-moz-animation-delay:.495s;-ms-animation-delay:.495s;-o-animation-delay:.495s;animation-delay:.495s}@keyframes cssload-fold-thecube{0%,10%{transform:perspective(84px) rotateX(-180deg);opacity:0}25%,75%{transform:perspective(84px) rotateX(0);opacity:1}100%,90%{transform:perspective(84px) rotateY(180deg);opacity:0}}
</style>
