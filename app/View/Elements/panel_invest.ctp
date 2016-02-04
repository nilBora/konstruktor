<div class="create-group">
    <div class="page-menu">
        <?=$this->Html->link(__('Create project'), array('controller' => 'InvestProject', 'action' => 'addProject'), array('class' => 'btn btn-default pull-left'))?>
        <?=$this->Html->link(__('My projects'), array('controller' => 'InvestProject', 'action' => 'listProjects', '?' => array('my' => 1)),
            array('class' => 'pull-right underlink', 'style' => "margin-top: 5px; margin-right: -16px;"))?>
    </div>
</div>

<div class="dropdown-panel-scroll">
    <ul class="group-list" style="padding-top: 70px" id="invest-search-projectResult">
<? foreach($aInvestCategory as $item) { ?>
        <li class="simple-list-item">
            <a href="<?=$this->Html->url(array('controller' => 'InvestProject', 'action' => 'listProjects', '?' => array('category' => $item['InvestCategory']['id'])))?>">
                <div class="user-list-item clearfix">
                    <div class="user-list-item-body noImage">
                        <div class="user-list-item-name"><?=$item['InvestCategory']['title']?></div>
                    </div>
                </div>
            </a>
        </li>
<? } ?>
    </ul>
</div>

<!-- Templates -->
<script type="text/x-tmpl" id="tmpl-invest-search-projectResult">
{% for (var i in o) { %}
    <li class="simple-list-item">
        <a href="<?=$this->Html->url(array('controller' => 'InvestProject', 'action' => 'view'))?>/{%=o[i].InvestProject.id%}">
            <div class="user-list-item clearfix">
                <div class="user-list-item-body noImage">
                    <div class="user-list-item-name">{%=o[i].InvestProject.name%}</div>
                </div>
            </div>
        </a>
    </li>
{% } %}
</ul>
</script>
<!--/ Templates -->

<script>
$('#invest-search-projects').on('submit', function () {
    var url = $(this).attr('action');
    var q = $(this).find('[name="q"]').val();
    if (!q) {
        return false;
    }
    $.get(url, {q: q}, function (response) {
        if (!response.data) {
            return;
        }
        $('#invest-search-projectResult').html(tmpl('tmpl-invest-search-projectResult', response.data));
    });
    return false;
});
</script>
