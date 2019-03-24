<?php $this->beginContent('examine.views.layouts.main'); ?>

	<div id="header">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 col-lg-offset-2">
					<div id="header-content" class="header-content" style="display: <?php echo $this->showHeaderContent ? 'block' : 'none' ?>">
						<div class="pull-left timer">
							<?php echo Yii::t('examine.core', 'Time:') ?> <span id="timer">-:--</span>
						</div>
						<div class="pull-right">
							<a class="btn btn-danger" href="<?php echo $this->createTokenUrl('giveup'); ?>" onclick="$('#giveup-modal').modal('show'); return false;"><?php echo Yii::t('examine.core', 'Give Up') ?></a>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
				
	<div class="container">
		<div class="row">
			<div class="col-lg-8 col-lg-offset-2">
				<div id="content">
					<?php echo $content; ?>
				</div>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="giveup-modal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<?php echo Yii::t('examine.core', 'Are you sure you want to give up your exam?') ?>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo Yii::t('examine.core', 'No, I changed my mind!') ?></button>
					<a href="<?php echo $this->createTokenUrl('giveup'); ?>" class="btn btn-primary"><?php echo Yii::t('examine.core', 'Yes, I\'m sure') ?></a>
				</div>
			</div>
		</div>
	</div>
	
	<div class="footer">
		<p><?php echo Yii::t('examine.core', 'Gandalf') ?> v<?php echo $this->module->getVersion(); ?> &copy; <?php echo date('Y'); ?> <a href="http://masterfri.org.ua">masterfri</a>.</p>
	</div>
	
<?php $this->endContent(); ?>
