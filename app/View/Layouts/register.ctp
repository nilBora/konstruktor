<!DOCTYPE html>
<html lang="en">
  <head>
    <!--[if lt IE 9]>
    <meta http-equiv="Refresh" content="0; URL=/ie6/ie6.html" />
  <![endif]-->
    <?=$this->Html->charset(); ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, height=device-height, user-scalable=no, maximum-scale=1.0, initial-scale=1.0, minimum-scale=1.0">
    <title>Konstruktor: <?=__('Main page')?></title>
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="img/favicon/apple-touch-icon-144x144-precomposed.png" rel="apple-touch-icon-precomposed" sizes="144x144" />
    <link href="img/favicon/apple-touch-icon-114x114-precomposed.png" rel="apple-touch-icon-precomposed" sizes="114x114" />
    <link href="img/favicon/apple-touch-icon-72x72-precomposed.png" rel="apple-touch-icon-precomposed" sizes="72x72" />
    <link href="img/favicon/apple-touch-icon-57x57-precomposed.png" rel="apple-touch-icon-precomposed" />

<?
  echo $this->Html->meta('icon');
  $vendorCss = array(
    'bootstrap.min',
    'fonts'
  );

  $css = array(
    'interests',
    'style'
  );

  echo $this->Html->css(array_merge($vendorCss, $css));

  $aScripts = array(
    'vendor/jquery/jquery-1.10.2.min',
    'vendor/bootstrap.min',
    'vendor/fastclick',
  );

  echo $this->Html->script($aScripts);

  echo $this->fetch('meta');
  echo $this->fetch('css');
  echo $this->fetch('script');
?>

  <script type="text/javascript">
  $(function() {
    FastClick.attach(document.body);
  });

  $(document).ready(function(){
    $.ajaxSetup({ cache: false });
  });
  </script>
</head>
  <body>
    <?=$this->element('ga')?>
    <?=$this->fetch('content')?>
  </body>
</html>
