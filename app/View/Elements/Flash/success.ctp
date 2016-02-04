<?php
$class = 'message';
if (!empty($params['class'])) {
    $class .= ' ' . $params['class'];
}
?>
<div id="<?php echo h($key) ?>Message" class="alert alert-success alert-dismissible <?php echo h($class) ?>" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <?php echo h($message) ?>
</div>
