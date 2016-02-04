<?
	$title = Hash::get($project, 'Project.title');
	// $src = $this->Media->imageUrl($project, 'thumb50x50');
	// $src = ($src) ? $src : '/img/group-create-pl-image.jpg';
	$class = ($hide) ? 'can-hide' : '';
	$style = ($hide) ? 'style="display: none"' : ''
?>
<div class="news-article group-type progect-type <?=$class?>" <?=$style?>>
    <a href="<?=$this->Html->url(array('controller' => 'Project', 'action' => 'view', Hash::get($project, 'Project.id')))?>">
        <div class="news-article-title">
        </div>
        <div class="news-article-title subtitle clearfix">
            <!--div class="subtitle-image">
                <img src="/img/temp/t_logo3.png" alt="" />
            </div-->
            <div class="subtitle-body">
                <?=$title?>
                <!--div class="subtitle-body-info ">
                    1 <?=__('Members')?>
                </div-->
            </div>
        </div>
        <div class="news-article-pubdate">
	        <?=Hash::get($project, 'Project.descr')?>
	    </div>
    </a>
</div>