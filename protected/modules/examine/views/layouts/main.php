<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo Yii::t('examine', 'Gandalf') ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" href="/rc/bootstrap/css/bootstrap.css" type="text/css">
		<link rel="stylesheet" href="/rc/css/examine.css" type="text/css">
		<?php
			$cs = Yii::app()->clientScript;
			$cs->registerCoreScript('jquery');
			$cs->registerCoreScript('bbq');
			$cs->registerScriptFile('/rc/bootstrap/js/bootstrap.js', CClientScript::POS_END);
			$cs->registerScriptFile('/rc/ajax-bindings/ajax-bindings.js', CClientScript::POS_END);
			$cs->registerScriptFile('/rc/js/examine.js', CClientScript::POS_END);
		?>
	</head>
	<body>
		<?php echo $content; ?>
	</body>
</html>

