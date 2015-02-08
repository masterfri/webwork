<?php $this->beginContent('core.views.layouts.main'); ?>

	<?php $this->renderPartial('core.views.layouts.include.navbar'); ?>
	
	<div class="container">
		<?php $this->renderPartial('core.views.layouts.include.flash'); ?>
		<div class="pull-left hidden-xs">
			<?php $this->widget('zii.widgets.CBreadcrumbs', array(
				'homeLink' => '<li>' . CHtml::link('<i class="glyphicon glyphicon-home"></i>', array('/core')) . '</li>',
				'links' => $this->breadcrumbs,
				'separator' => ' ',
				'tagName' => 'ol',
				'htmlOptions' => array('class' => 'breadcrumb'),
				'activeLinkTemplate' => '<li><a href="{url}">{label}</a></li>',
				'inactiveLinkTemplate' => '<li class="active">{label}</li>',
			)); ?>
		</div>
		<div class="pull-right">
			<?php $this->widget('zii.widgets.CMenu', array(
				'items' => $this->menu,
				'encodeLabel' => false,
				'activateItems' => true,
				'htmlOptions' => array(
					'class' => 'nav nav-pills context-menu',
				),
				'submenuHtmlOptions' => array(
					'class' => 'dropdown-menu dropdown-menu-right pull-right',
					'role' => 'menu',
				),
			)); ?>
		</div>
		<div class="clearfix"></div>
	</div>

	<div id="content" class="container">
		<?php echo $content; ?>
	</div>
	<div class="footer">
		<p><a href="<?php echo Yii::app()->homeUrl; ?>"><?php echo CHtml::encode(Yii::app()->name); ?></a> &copy; 2014 - <?php echo date('Y'); ?> <a href="http://masterfri.org.ua">masterfri</a>.</p>
	</div>
<?php $this->endContent(); ?>
