<div class="question" data-onload="question.loaded" data-question-time="<?php echo $question->time ?>" data-time-questioned="<?php echo $question->time_questioned ?>" data-time-now="<?php echo date('Y-m-d H:i:s') ?>">
	<h3><?php echo Yii::t('examine.core', 'Question {number} out of {total}', array(
		'{number}' => $number,
		'{total}' => $total,
	)) ?></h3>
	<div class="text">
		<?php 
			$this->beginWidget('MarkdownWidget'); 
			echo $question->text;
			$this->endWidget(); 
		?>
	</div>
	<div class="answers">
		<?php $form=$this->beginWidget('ActiveForm', array(
			'id' => 'answers',
			'action' => $this->createTokenUrl('answer', array('question' => $question->id)),
			'enableClientValidation' => false,
			'htmlOptions' => array(
				'data-raise' => 'ajax-request',
				'data-destination' => '#content',
			),
		)); ?>
			<?php foreach ($question->getShuffledAnswers()->getData() as $answer): ?>
				<div class="answer">
					<label>
						<?php echo CHtml::radioButton('answer', false, array(
							'style' => 'display: none;',
							'value' => $answer->id,
							'onclick' => 'sendAnswer(this)',
						)); ?>
						<?php 
							$this->beginWidget('MarkdownWidget'); 
							echo $answer->text;
							$this->endWidget(); 
						?>
					</label>
				</div>
			<?php endforeach ?>
		<?php $this->endWidget(); ?>
	</div>
</div>