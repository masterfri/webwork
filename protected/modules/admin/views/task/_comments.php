<?php foreach($comments as $comment): ?>
	<div class="panel panel-default <?php echo empty($comment->content) ? 'no-body' : ''; ?> action-<?php echo $comment->action; ?>" id="comment-<?php echo $comment->id; ?>">
		<div class="panel-heading">
			<?php echo Yii::t('admin.crud', $comment->getActionExplanation(), array(
				'{author}' => CHtml::encode(CHtml::value($comment, 'created_by.displayName')),
				'{date}' => Yii::app()->format->formatDateTime($comment->time_created),
			)); ?>
		</div>
		<?php if (!empty($comment->content)): ?>
			<div class="panel-body">
				<?php 
					$this->beginWidget('CMarkdown'); 
					echo $comment->content;
					$this->endWidget(); 
				?>
			</div>
		<?php endif; ?>
		<?php if (count($comment->attachments)): ?>
			<div class="panel-footer attachments">
				<?php foreach ($comment->attachments as $attachment): ?>
					<a class="thumbnail" target="_blank" href="<?php echo $attachment->getUrl(); ?>">
						<?php if ($attachment->getIsImage()): ?>
							<?php echo CHtml::image($attachment->getUrlResized(150, 100), '', array('title' => $attachment->title)); ?>
						<?php else: ?>
							<span class="no-thumb">
								<span class="file-name">
									<?php echo CHtml::encode($attachment->title); ?>
								</span>
								<span class="file-type">
									<?php echo CHtml::encode($attachment->mime); ?>
								</span>
								<span class="file-size">
									<?php echo $attachment->getFriendlySize(); ?>
								</span>
							</span>
						<?php endif; ?>
					</a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
<?php endforeach; ?>
