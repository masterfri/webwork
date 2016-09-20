<?php

$this->pageHeading = Yii::t('core.crud', 'Service page');

$this->breadcrumbs = array(
	Yii::t('core.crud', 'Service page'),
);

?>
<?php if ($response !== null): ?> 
	<?php $this->renderPartial('../layouts/include/httpsh-response', array(
		'response' => $response,
	)); ?>
<?php endif; ?>
