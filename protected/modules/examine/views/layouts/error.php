<?php $this->beginContent('examine.views.layouts.main'); ?>

	<div class="container">
		<div class="row">
			<div class="col-lg-8 col-lg-offset-2">
				<?php echo $content; ?>
			</div>
		</div>
	</div>
	
	<div class="footer">
		<p><?php echo Yii::t('examine.core', 'Gandalf') ?> v<?php echo $this->module->getVersion(); ?> &copy; <?php echo date('Y'); ?> <a href="http://masterfri.org.ua">masterfri</a>.</p>
	</div>
	
<?php $this->endContent(); ?>
