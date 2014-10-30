<?php $this->beginWidget('RenderPdf'); ?>
<style>
h1 {margin: 6mm 0 3mm 0;}
h2 {margin: 8mm 0 3mm 0;}
h3 {margin: 6mm 0 3mm 0;}
h4 {margin: 6mm 0 3mm 0;}
p {margin: 0 0 3mm 0;}
li {padding-bottom: 3mm;}
p, li {line-height: 140%;}
table {border-collapse: collapse; border-spacing: 0;}
table td {font-size: 90%; padding: 0 0 3mm 0;}
</style>
<page style="font-family:dejavusans;">
	<page_header>
		<div style="color: #aaa; font-family:dejavusans;">
			<?php echo Yii::app()->format->formatDate(time()); ?>
		</div>
	</page_header>
	<page_footer>
		<div style="color: #aaa; font-family:dejavusans;">
			<?php echo Yii::t('admin.crud', 'Page [[page_cu]] of [[page_nb]]'); ?>
		</div>
	</page_footer>

	<div style="text-align: center; padding-top: 100mm;">
		<h1><?php echo CHtml::encode($model->name); ?></h1>
		<h3 style="color:#777;"><?php echo Yii::t('admin.crud', 'Technical specification of project'); ?></h3>
	</div>
</page>
<page style="font-family:dejavusans;" backtop="10mm" backbottom="10mm">
	<page_header>
		<div style="right; color: #aaa; font-family:dejavusans;">
			<?php echo Yii::app()->format->formatDate(time()); ?>
		</div>
	</page_header>
	<page_footer>
		<div style="color: #aaa; font-family:dejavusans;">
			<?php echo Yii::t('admin.crud', 'Page [[page_cu]] of [[page_nb]]'); ?>
		</div>
	</page_footer>
	<?php if ('' != $model->scope): ?>
		<h2><?php echo Yii::t('project', 'Scope'); ?></h2>
		<?php 
			$this->beginWidget('CMarkdown'); 
			echo $model->scope;
			$this->endWidget(); 
		?>
	<?php endif; ?>
	<?php 
		$estimate_min = 0;
		$estimate_max = 0;
	?>
	<?php if(count($tasks = $model->getTasks(array('condition' => 'milestone_id = 0')))): ?>
		<h2><?php echo Yii::t('admin.crud', 'General tasks'); ?></h2>
		<?php foreach($tasks as $task): ?>
			<?php $this->renderPartial('_pdf_task', array(
				'task' => $task,
			)); ?>
			<?php 
				list($min, $max) = $task->getEstimateRange();
				$estimate_min += $min;
				$estimate_max += $max;
			?>
		<?php endforeach; ?>
	<?php endif; ?>
	<?php foreach ($model->getMilestones() as $milestone): ?>
		<h2><?php echo CHtml::encode($milestone->name); ?></h2>
		<table style="color: #777; width: 100%;">
			<tr>
				<td style="width: 34%;">
					<?php echo Yii::t('milestone', 'Due Date'); ?>:
					<?php if (!empty($milestone->due_date) && '0000-00-00' != $milestone->due_date): ?>
						<?php echo Yii::app()->format->formatDate($milestone->due_date); ?>
					<?php else: ?>
						<span class="not-set"><?php echo Yii::t('admin.crud', 'Not set'); ?></span>
					<?php endif; ?>
				</td>
			</tr>
		</table>
		<?php if ('' != $milestone->description): ?>
			<?php 
				$this->beginWidget('CMarkdown'); 
				echo $milestone->description;
				$this->endWidget(); 
			?>
		<?php endif; ?>
		<?php foreach($milestone->getTasks() as $task): ?>
			<?php $this->renderPartial('_pdf_task', array(
				'task' => $task,
			)); ?>
			<?php 
				list($min, $max) = $task->getEstimateRange();
				$estimate_min += $min;
				$estimate_max += $max;
			?>
		<?php endforeach; ?>
	<?php endforeach; ?>
	<h2><?php echo Yii::t('admin.crud', 'Summary'); ?></h2>
	<p>
		<?php echo Yii::t('admin.crud', 'Total estimate'); ?>: 
		<?php echo ViewHelper::formatEstimate(array($estimate_min, $estimate_max)); ?>
	</p>
</page>
<?php $this->endWidget(); ?>
