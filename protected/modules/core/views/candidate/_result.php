<div class="panel candidate-result <?php echo ViewHelper::candidateResultClass($data) ?>">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo Yii::t('core.crud', 'Question #{number}, category: {category}, level: {level}', array(
			'{number}' => $index + 1,
			'{category}' => $data->question->category ?: 'Undefined',
			 '{level}' => $data->question->levelName,
		)); ?></h3>
	</div>
	<div class="panel-body">
		<?php 
			$this->beginWidget('MarkdownWidget'); 
			echo $data->question->text;
			$this->endWidget(); 
		?>
	</div>
	<?php if ($data->answer): ?>
		<div class="panel-body answer">
			<?php $this->beginWidget('MarkdownWidget'); 
			echo $data->answer->text;
			$this->endWidget(); ?>
		</div>
		<div class="panel-footer foot-details">
			<?php echo Yii::t('core.crud', 'Answer given in {time}', array('{time}' => Yii::app()->format->formatSeconds($data->getAnswerTime()))); ?>
			<?php if (($delay = $data->getAnswerDelay()) > 0): ?>
				<?php echo Yii::t('core.crud', '(delayed for {time})', array('{time}' => Yii::app()->format->formatSeconds($data->getAnswerDelay()))); ?>
			<?php endif ?>
		</div>
	<?php else: ?>
		<div class="panel-body no-answer">
			<?php echo Yii::t('core.crud', 'No answer given'); ?>
		</div>
	<?php endif ?>
</div>