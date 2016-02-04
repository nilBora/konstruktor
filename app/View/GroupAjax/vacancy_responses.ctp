<?
    $approve_title = false;
    $pending_title = false;
    $rejected_title = false;
	if ($isGroupAdmin || $isGroupResponsible) {
        foreach($aResponses as $key => $vacancy_responses) { ?>
            <div class="<?php echo $key;?> clearfix">
            <?php
            foreach($vacancy_responses as $response) {
                $user = $aResponsedUsers[$response['VacancyResponse']['user_id']];
                $vacancy = $response['GroupVacancy'];
                $urlView = $this->Html->url(array('controller' => 'User', 'action' => 'view', $user['User']['id']));
                $class='';
                switch ($key) {
                    case 'approved':
                        $class = 'access';
                        if(!$approve_title) {
                            $approve_title = true;
                            ?>
                            <h3><?php echo __('Approved');?></h3>
                            <?php
                        }
                        break;
                    case 'pending':
                        if(!$pending_title) {
                            $pending_title = true;
                            ?>
                            <h3><?php echo __('Pending');?></h3>
                            <?php
                        }
                        $class = 'pending';
                        break;
                    case 'rejected':
                        if(!$rejected_title) {
                            $rejected_title = true;
                            ?>
                            <h3><?php echo __('Rejected');?></h3>
                            <?php
                        }
                        $class = 'denied';
                        break;
                }
                ?>
                <div class="item <?=$class?>">
                    <a href="<?=$urlView?>">
						<?php echo $this->Avatar->user($user, array(
							'class' => 'ava',
							'size' => 'thumb50x50'
						)); ?>
                        <div class="info">
                            <span class="name"><?=$vacancy['title']?></span>
                            <span class="position"><?=$user['User']['full_name']?></span>
                        </div>
                    </a>

                    <?
                    if( $response['VacancyResponse']['approve'] == '0' ) {
                        ?>
                        <div class="buttonsControls">
                            <div class="accept" onclick="vacAccept('<?=$response['VacancyResponse']['id']?>')" ><span class="glyphicons ok_2"></span></div>
                            <div class="remove" onclick="vacDecline('<?=$response['VacancyResponse']['id']?>')" ><span class="glyphicons bin"></span></div>
                        </div>
                        <?
                    }
                    ?>
                </div>
                <?
            }
            ?>
            </div>
            <?php
        }
	}
?>
