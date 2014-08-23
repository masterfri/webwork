<?php foreach($task->comments as $comment): ?>
	<div class="panel panel-default action-<?php echo $comment->action; ?>" id="comment-<?php echo $comment->id; ?>">
		<div class="panel-heading">
			<?php echo Yii::t('admin.crud', $comment->getActionExplanation(), array(
				'{author}' => CHtml::encode(CHtml::value($comment, 'created_by.displayName')),
				'{date}' => Yii::app()->format->formatDateTime($comment->time_created),
			)); ?>
		</div>
		<div class="panel-body">
			<?php 
				$this->beginWidget('CMarkdown'); 
				echo $comment->content;
				$this->endWidget(); 
			?>
		</div>
	</div>
<?php endforeach; ?>
