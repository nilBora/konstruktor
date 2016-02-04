<?php
    $this->Html->script(array('login.js'), array('inline' => false));

    $groupID = Hash::get($group, 'Group.id');
//    $group_logo = $this->Media->imageUrl(Hash::get($group, 'GroupMedia'), 'thumb200x200');
    $title = Hash::get($group, 'Group.title');

    $aEmployment = array(
        '4' => __('Full-time'),
        '3' => __('Part-time'),
        '2' => __('Project emplyment'),
        '1' => __('Volunteer'),
        '0' => __('Internship'),
    );
    $aShedules = array(
        '4' => __('Full day'),
        '3' => __('Shifts'),
        '2' => __('Flexible shifts'),
        '1' => __('Remote work'),
        '0' => __('Rotational basis'),
    );
    $aCurrencies = array(
        'USD' => __('USD'),
        'EUR' => __('EUR'),
        'RUR' => __('RUR'),
    );

	/* Breadcrumbs */
	$this->Html->addCrumb($title, array('controller' => 'Group', 'action' => 'view/'.$groupID));
	$this->Html->addCrumb(__('Employment opportunities'), array('controller' => 'Group', 'action' => 'vacancies'));
?>



<style type="text/css">
    .groupVacancies {
        margin: 20px auto;
    }
    .groupVacancies .item .title { margin: 5px; padding: 3px; font-size: 16px; font-weight: 900; }
    .groupVacancies .item .details { width: 100%; background: #eee; margin: 0; font-weight: 900; border-radius: 4px; }
    .groupVacancies .item .details .row { padding: 5px; margin: 0; width: 100%; }
    .groupVacancies .item .details .value { font-weight: 100; }
    /*.groupVacancies .item .details .more { display: none; }*/
    .groupVacancies .item .details.expand .more { display: block; }
    .groupVacancies .item .description { padding: 15px 10px 10px; }
    .groupVacancies .item .details .row.more { border-top: 1px solid #ddd; }
    .jq-selectbox__dropdown { max-height: 290px; overflow: auto; }
    .country { margin-top: 25px; }
    .address-wrap .addressFlag { width: 18px;}
    .btns.vacancy .responseBtn {
        border-color: #22b5ae;
        color: #22b5ae;
        background: transparent;
    }
    .vacancy-wrap {
        overflow: hidden;
        float: left;
        padding: 5px;
        height: 380px;
        width: 32% !important;
        border: 1px solid #eee;
        background: #eee;
        margin-left: 1%;
    }
    .vacancy-wrap .middle-wrap, .item {

    }
    .groupAccess .item.pending {
        margin-right: 10px !important;
    }
    .btns.vacancy {
        position: absolute;
        right: 0;
        bottom: 0;
        padding: 5px;
    }
    .groupAccess .item.pending .ava {
        float: left;
        margin: 10px !important;
        display: block;
        width: 50px;
        height: auto;
    }
    .groupAccess .item.pending .buttonsControls {
        float: right;
        border-left: 1px solid #e6e6e6;
        width: 33px !important;
        text-align: center;
    }


    .country-name {
        margin-left: 7px;
    }
    .empty-space {
        width: 100%;
        height: 10px;
        overflow: hidden;
    }

    #vacancy-modal a:focus {outline: none;}
    #vacancy-modal a { text-decoration: none; color: #fff;}
    #vacancy-modal a:hover ,#vacancy-modal a:focus {  text-decoration: none; color: #fff; }

    .dottedLine { border-bottom: 1px dotted #fff;}
    .dottedLine:hover { border-bottom-color: transparent;}

    .container { padding-top: 33px; }
    @media (max-width: 767px) { .container {padding-top: 5px; width: 330px; margin 0 auto;} }

    .form-control::-webkit-input-placeholder {color: #c0d0de;}
    .form-control::-moz-placeholder {color: #c0d0de;}/* Firefox 19+ */
    .form-control:-moz-placeholder {color: #c0d0de;}/* Firefox 18- */
    .form-control:-ms-input-placeholder { color: #c0d0de;}
    .form-control{ -webkit-border-radius: 0; border-radius: 0;}
    /*.input.text, .input.text input { height: 28px; }*/
    .tokenfield {min-height: 28px!important;}
    .tf-input .input.text { height: auto; margin: -2px 0; }

    .form-group { border-bottom: 2px solid #fff; margin-bottom: 12px; padding-bottom: 3px;}
    #vacancy-modal .form-group input { border: none; -webkit-box-shadow: none; box-shadow: none; padding: 0; font-weight: 400; resize: none; background: none; color: #fff; font-size: 15px;}
    .form-group input:focus { -webkit-box-shadow: none; box-shadow: none; outline: none;}
    .form-group input:-webkit-autofill { background-color: transparent !important; background-image:none !important; }

    .input-group .input-group-addon { background: none; border: none; font-size: 15px; padding: 0 0 0 15px; color: #fff; display: table-cell; }

    .likely__icon {
        fill: white;
    }

    #vacancy-modal .btn {
        -webkit-transition: all .2s linear;
        -moz-transition: all .2s linear;
        -ms-transition: all .2s linear;
        -o-transition: all .2s linear;
        transition: all .2s linear;}
    #vacancy-modal .btn.btn:hover {
        -webkit-transition: all 0s linear;
        -moz-transition: all 0s linear;
        -ms-transition: all 0s linear;
        -o-transition: all 0s linear;
        transition: all 0s linear;}
    #vacancy-modal .btn .btn:active, .btn-default.active {
        -webkit-transition: all 0s linear;
        -moz-transition: all 0s linear;
        -ms-transition: all 0s linear;
        -o-transition: all 0s linear;
        transition: all 0s linear;}

    #vacancy-modal.btn-default { background-color: transparent; border: 1px solid white; color: rgba(255, 255, 255, .7); }
    #vacancy-modal.btn-default:hover { background-color: rgba(255, 255, 255, .2); border: 1px solid white; color: #fff; }
    /*.btn-default:active, .btn-default.active { background-color: rgba(255, 255, 255, .2); border: 1px solid white; color: #fff; }*/

    #vacancy-modal .btn-primary { background-color: #FFF; border-color: #22b5ae; color: #616161; }
    #vacancy-modal .btn-primary:hover { background-color: #FFF; border-color: #22b5ae; color: #22b5ae; }
    #vacancy-modal .btn-primary:active { background-color: #22b5ae; border-color: #22b5ae; color: #fff; }

    #vacancy-modal .btn-primary, .btn-primary:focus, .btn-primary.focus { background-color: #25b5be; border: none; color: #fff; }
    #vacancy-modal .btn-primary:hover { background-color: #45d5de; border: none; color: #fff; }
    #vacancy-modal .btn-primary:active, .btn-primary.active { background-color: #45d5de; border: none; color: #fff; }

    #vacancy-modal .btn-default.disabled, .btn-default[disabled] { background: none;}
    #vacancy-modal .btn:focus,
    #vacancy-modal .btn:active:focus,
    #vacancy-modal .btn.active:focus,
    #vacancy-modal .btn.focus,
    #vacancy-modal .btn:active.focus,
    #vacancy-modal .btn.active.focus { outline: none; }

    #vacancy-modal .btn .halflings { }
    #vacancy-modal .btn .halflings:before { margin-top: -5px; }

    #vacancy-modal .modal-title {
        color: white !important;
    }

    #vacancy-modal .modal-footer {
        background: white !important;
    }
    .hidable .formFields { width: 100%; min-height: 200px; }

    @media (min-width: 1024px) {
        .hidable .formFields { position: relative; min-height: 95px; }
        .hidable .formFields .form-group { margin-bottom: 15px; }
        .hidable .formFields .form-group.half { width: 45%;}
        #register_form_block .formFields .form-group.half {
            /*width: 45%;*/
        }
        .hidable .formFields .form-group.half input { font-size: 13px!important;}
        .formFields .form-group.half.left { float: left; }
        .formFields .form-group.half.right { float: right; }
    }
    #vacancy-modal .modal-content {
        background-size: cover;
        font-family: "Open Sans",sans-serif;
        font-size: 15px;
    }
    .vacancy-thumb {
        display: table-cell;
        vertical-align: top;
        max-width: 80px;
        max-height: 80px;
    }

    .vacancy-thumb img {
        max-width: 80px;
        max-height: 80px;
        height: auto;
    }

    .vacancy-title {
        color: #313131;
        font-family: "Roboto",sans-serif;
        font-weight: 900;
        line-height: 80px;
        word-wrap: break-word;
        font-size: 36px;
        padding: 0 7px;
    }

    .projectViewTitle {
        margin-top: 33px;
    }

    .vacancy-description {
        width: 98%;
        margin: 10px auto;
    }

    .description .row {
        padding: 0 10px;
        margin: 0;
        width: 100%;
    }

</style>

<div class="row projectViewTitle fixedLayout">
	<?php if (!$currUser) : ?>
    <div>
        <div class="thumb vacancy-thumb" style="float: left">
            <?php echo $this->Avatar->group($group, array(
                'size' => 'thumb200x200'
            )); ?>
        </div>
       	<div class="vacancy-title" style="float: left"><?=__('Employment opportunities')?> "<?=$title?>"</div>
    </div>
	<?php endif; ?>
    <div style="clear: both; float: none"></div>
    <div class="col-sm-4 col-sm-push-8 controlButtons">
        <? if($isGroupAdmin || $isGroupResponsible) { ?><div class="btn btn-default smallBtn" onclick="addVacancy();" data-toggle="tooltip" data-placement="bottom" title="<?=__('Job opening')?>"><span class="glyphicons plus"></span></div><? } ?>

        <? if($loggedIn){ ?>
            <a class="btn btn-default" href="<?=$this->Html->url(array('controller' => 'Group', 'action' => 'view', $groupID))?>" data-toggle="tooltip" data-placement="bottom" title="<?= __('Back to group') ?>"><?= __('Back to group') ?></a>
        <? } else {?>
            <a class="btn btn-default register-btn" href="#register-popup" data-toggle="tooltip" data-placement="bottom" title="<?= __('Back to group') ?>">
                <?= __('Back to group') ?>
            </a>
        <? } ?>

    </div>
    <div class="col-sm-8 col-sm-pull-4">


    </div>
    <div class="groupAccess clearfix"></div>
<div class="fixedLayout groupVacancies">

    <?php 
    $i = 0;
    $balance = 0;
    foreach($data as $country => $aVacancies) {
        $aContainer = array('', '', '');
        $aContainer = [];
        foreach($aVacancies as $vacancy) {
            $aContainer[] = $this->element('/Group/group_vacancy', array('vacancy' => $vacancy, 'isAdmin' => ($isGroupAdmin || $isGroupResponsible), 'member' => $isMember));
        }

        foreach($aContainer as $container) {
            if($i%3 == 0) :?>
                <div class="empty-space"></div>
                <?php endif;?>
        <div class="col-sm-4 vacancy-wrap">
            <div class="middle-wrap">
                <div class="address-wrap">
                    <img class="addressFlag" src="/img/flags/48/<?=$country;?>.png" /><span class="country-name"><?=$countryNames[$country]?></span>
                </div>
                <div class="articleColumn">
                    <?=$container?>
                </div>
            </div>
        </div>
<?
            $i++;

        }
    }
?>
</div>
<br>
<br>
<br>
<div class="empty-space"></div>
<div id="VacancyPopup" class="modal fade" tabindex="-1" role="dialog">
    <div class="outer-modal-dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <span class="glyphicons circle_remove" data-dismiss="modal"></span>
                <h4><?=__('Job opening')?></h4>
                <?=$this->Form->create('GroupVacancy', array('url' => array('controller' => 'Group', 'action' => 'addVacancy', $groupID)))?>
                    <?=$this->Form->hidden('GroupVacancy.id')?>
                    <div class="form-group">
                        <?=$this->Form->input('GroupVacancy.title', array('label' => __('Title'), 'placeholder' => __('Title').'...', 'class' => 'form-control', 'required' => true));?>
                    </div>
                    <div class="form-group">
                        <?=$this->Form->input('GroupVacancy.descr', array('label' => __('Description'), 'placeholder' => __('Description').'...', 'class' => 'form-control', 'type' => 'text'))?>
                    </div>
                    <div class="form-group">
                        <label><?=__('Employment')?></label>
                        <?=$this->Form->input('GroupVacancy.employment', array('options' => $aEmployment, 'class' => 'formstyler', 'label' => false))?>
                    </div>
                    <div class="form-group">
                        <label><?=__('Working hours')?></label>
                        <?=$this->Form->input('GroupVacancy.shedule', array('options' => $aShedules, 'class' => 'formstyler', 'label' => false))?>
                    </div>
                    <div class="form-group">
                        <label><?=__('Country')?></label>
                        <?=$this->Form->input('GroupVacancy.country', array('options' => $countryNames, 'class' => 'formstyler', 'label' => false))?>
                    </div>
                    <div class="form-group">
                        <label><?=__('City')?></label>
                        <?=$this->Form->input('GroupVacancy.city', array('label' => false, 'placeholder' => __('City').'...', 'class' => 'form-control'));?>
                    </div>
                    <div class="form-group" style="width: 115px; display: inline-block; margin-right: 5px;">
                        <label><?=__('Wages')?></label>
                        <?=$this->Form->input('GroupVacancy.wage', array('label' => false, 'placeholder' => __('Wages').'...', 'class' => 'form-control', 'required' => true));?>
                    </div>
                    <div class="form-group" style="width: 115px; display: inline-block;">
                        <label><?=__('Experience')?></label>
                        <?=$this->Form->input('GroupVacancy.experience', array('label' => false, 'placeholder' => __('Experience').'...', 'class' => 'form-control', 'required' => true));?>
                    </div>
                    <div class="form-group" style="width: 81px; display: inline-block; float: right; height: 65px;">
                        <label><?=__('Currency')?></label>
                        <?=$this->Form->input('GroupVacancy.currency', array('options' => $aCurrencies, 'class' => 'formstyler', 'label' => false))?>
                    </div>
                    <div class="clearfix">
                        <button type="submit" class="btn btn-default"><?=__('Add')?></button>
                    </div>
                <?=$this->Form->end()?>
            </div>
        </div>
    </div>
</div>

<?php if(!$loggedIn): ?>

    <div class="modal fade" id="vacancy-modal" aria-hidden="true" aria-labelledby="bootstrap-modal-label" role="dialog" tabindex="-1" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="bootstrap-modal-label">Authorization Required</h4>
                </div>
                <div class="modal-body">
                    <div id="register_form_block" style="box-sizing: border-box; padding: 20px; padding-top: 15px; border: 1px solid rgba(255, 255, 255, .8); border-radius: 4px; background: rgba(0, 0, 0, .45)">
                        <?=$this->element('User/register_form')?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close');?></button>
                </div>
            </div>
        </div>
    </div>

    <?php else: ?>
    <div id="vacancy-more-component" style="display: none; clear: both">
        <div class="clearfix">
            <a href="javascript:void(0)" class="vacancy-more-link"><?php echo __('More');?></a>
        </div>
    </div>

<?php endif; ?>


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

<style type="text/css">
    .register.row .fbLogin {
        line-height: inherit;
        background: inherit;
    }
    .register.row .fbLogin:hover {
        background: inherit;
    }
    .vacancy-more {
        display: none;
    }
    .vacancy-more-link {
        color: #ff6363 !important;
        text-decoration: none;
        font-size: 15px;
        font-weight: 600;
        float: right;
        margin: 10px 50px 0 0;
    }
    .vacancy-more-link:hover {
        color: red !important;
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
    var loggedIn = '<?php echo ($loggedIn) ? true : false;?>' ? true : false;
    var passed_interests;

    var redirect_uri = '<?=$this->Html->url(array('controller' => 'Group', 'action' => 'vacancies', $groupID))?>';

    $(document).on('ready', function() {
        $('<input>').attr({
            type: 'hidden',
            id: 'vacancy_id',
            name: 'vacancy_id',
            value: ''
        }).appendTo('#regForm');

        $('#VacancyPopup').on('hidden.bs.modal', function () {
            $('.wrapper-container').css('position', 'absolute');
        });

        $('.wrapper-container').on('click', '.vacancy-more-link', function(){
            $('.vacancy-more').toggle();
            if($('.vacancy-more').is(':visible')){
                $(this).text("<?php echo __('Less')?>");
            }
            else {
                $(this).text("<?php echo __('More')?>");
            }
        });

<?
    if( $currUserID ) {
?>
        vacResponse = function(vacID) {
            var postData = { vacancy_id: vacID };
            $.post( groupURL.vacancyResponse, postData, function (response) {
                vacancy = $('#vacancy-'+vacID);
                $('.responseBtn', $(vacancy)).remove();
            });
        }
<?
    } else {
?>
        vacResponse = function(vacID) {
            if(!loggedIn) {
                $('#vacancy_id').val(vacID);
                $('#vacancy-modal').modal('show');
                return false;
            }
            var postData = { vacancy_id: vacID };
            $.post( groupURL.vacancyResponse, postData, function (response) {
                vacancy = $('#vacancy-'+vacID);
                $('.responseBtn', $(vacancy)).remove();
            });
        }
<?
    }
    if ($isGroupAdmin || $isGroupResponsible) {
?>
        updateResponses = function() {
            $.post( "<?=$this->Html->url(array('controller' => 'GroupAjax', 'action' =>  'vacancyResponses'))?>", {id: '<?=$groupID?>'}, function(response) {
                $('.groupAccess').html(response)
                var c = 0;
                var rejected_count = $(".rejected > .denied").length;
                if(rejected_count > 3) {
                    $(".rejected > .denied").slice(c, c+3).wrapAll("<div class='shift' />");
                    $(".rejected > .denied").slice(0, rejected_count).wrapAll("<div class='vacancy-more' />");
                    $(".rejected").append($('#vacancy-more-component').html());
                    $('#vacancy-more-component').remove();
                }


            });
        }

        vacAccept = function(vacID) {
            var postData = { response_id: vacID };
            $.post( groupURL.vacancyApprove, postData, function (response) {
                console.log(response);
                updateResponses();
            });
        }

        vacDecline = function(vacID) {
            var postData = { response_id: vacID };
            $.post( groupURL.vacancyDecline, postData, function (response) {
                console.log(response);
                updateResponses();
            });
        }

        editVacancy = function(id) {
            var vacancy = $('#vacancy-'+id);
            $('#GroupVacancyId').val(id);

            //TODO заполнение полей попапа
            $('#GroupVacancyTitle').val( $('.title', $(vacancy)).data('val') );
            $('#GroupVacancyCity').val( $('.city .value', $(vacancy)).data('val') );
            $('#GroupVacancyWage').val( $('.wage .value', $(vacancy)).data('val') );
            $('#GroupVacancyEmplyment').val( $('.employment .value', $(vacancy)).data('val') ).change();
            $('#GroupVacancyShedule').val( $('.shedule .value', $(vacancy)).data('val') ).change();
            $('#GroupVacancyCountry').val( $(vacancy).data('country') ).change();
            $('#GroupVacancyExperience').val( $('.experience .value', $(vacancy)).data('val') );
            $('#GroupVacancyDescr').val( $('.description', $(vacancy)).data('val') );
            $('.wrapper-container').css('position', 'initial');
            $('#VacancyPopup').modal('show');
        }

        addVacancy = function() {
            $('#GroupVacancyTitle').val('');
            $('#GroupVacancyCity').val('');
            $('#GroupVacancyWage').val('0');
            $('#GroupVacancyEmplyment').val('0').change();
            $('#GroupVacancyShedule').val('0').change();
            $('#GroupVacancyCountry').val('AF').change();
            $('#GroupVacancyExperience').val('0');
            $('#GroupVacancyDescr').val('');
            $('.wrapper-container').css('position', 'initial');
            $('#VacancyPopup').modal('show');
        }

        $('.removeBtn').on('click', function(e) {
            if(!confirm('<?=__('Are you sure ?')?>')) {
                e.preventDefault();
            }
        });

        $('.editBtn, .responseBtn, .removeBtn').on('click', function(e) {
            e.stopPropagation();
        });

        updateResponses();
        $('body').off('click', '#regSubmit');
<?
      }
?>

    });
</script>
