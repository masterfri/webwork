<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo Yii::t('core.crud', 'Answer #{number}', array('{number}' => $index + 1)) ?></h3>
	</div>
	<?php $this->widget('DetailView', array(
		'data' => $data,
		'attributes' => array(
			'score',
		),
	)); ?>
	<div class="panel-body">
		<?php 
			$this->beginWidget('MarkdownWidget'); 
			echo $data->text;
			$this->endWidget(); 
		?>
	</div>
</div>