<nav class="navbar navbar-default" role="navigation">
	<div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
			<span class="sr-only"><?php echo Yii::t('admin.crud', 'Toggle navigation'); ?></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<a class="navbar-brand" href="<?php echo $this->createUrl('/admin'); ?>"><?php echo CHtml::encode(Yii::app()->name); ?></a>
	</div>

	<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		<?php echo CHtml::link('<i class="glyphicon glyphicon-off"></i> ' . Yii::t('admin.crud', 'Logout'), array('/admin/default/logout'), array('class' => 'btn btn-default navbar-btn pull-right logout-btn')) ?>
		
		<p class="pull-right navbar-text">
			<i class="glyphicon glyphicon-user"></i> <?php echo  Yii::t('admin.crud', 'You are logged as'); ?> <b><?php echo Yii::app()->user->name; ?></b>
		</p>
		
		<?php $this->widget('Menu', array(
			'items' => array(
				array(
					'label' => '<i class="glyphicon glyphicon-user"></i> ' . Yii::t('admin.crud', 'Users') . ' <b class="caret"></b>',
					'url' => '#',
					'itemOptions' => array(
						'class' => 'dropdown',
					),
					'linkOptions' => array(
						'class' => 'dropdown-toggle',
						'data-toggle' => 'dropdown',
					),
					'items' => array(
						array(
							'label' => '<i class="glyphicon glyphicon-user"></i> ' . Yii::t('admin.crud', 'Users'),
							'url' => array('/admin/user'),
							'visible' => Yii::app()->user->checkAccess('view_user'),
						),
						array(
							'label' => '<i class="glyphicon glyphicon-star"></i> ' . Yii::t('admin.crud', 'Rate'),
							'url' => array('/admin/rate'),
							'visible' => Yii::app()->user->checkAccess('view_rate'),
						),
						array(
							'label' => '<i class="glyphicon glyphicon-flash"></i> ' . Yii::t('admin.crud', 'Activity'),
							'url' => array('/admin/activity'),
							'visible' => Yii::app()->user->checkAccess('view_activity'),
						),
					),
				),
				array(
					'label' => '<i class="glyphicon glyphicon-briefcase"></i> ' . Yii::t('admin.crud', 'Project') . ' <b class="caret"></b>',
					'url' => '#',
					'itemOptions' => array(
						'class' => 'dropdown',
					),
					'linkOptions' => array(
						'class' => 'dropdown-toggle',
						'data-toggle' => 'dropdown',
					),
					'items' => array(
						array(
							'label' => '<i class="glyphicon glyphicon-briefcase"></i> ' . Yii::t('admin.crud', 'Project'),
							'url' => array('/admin/project'),
							'visible' => Yii::app()->user->checkAccess('view_project'),
						),
						array(
							'label' => '<i class="glyphicon glyphicon-time"></i> ' . Yii::t('admin.crud', 'Time Entry'),
							'url' => array('/admin/timeEntry'),
							'visible' => Yii::app()->user->checkAccess('view_time_entry'),
						),
						array(
							'label' => '<i class="glyphicon glyphicon-tag"></i> ' . Yii::t('admin.crud', 'Tag'),
							'url' => array('/admin/tag'),
							'visible' => Yii::app()->user->checkAccess('view_tag'),
						),
					),
				),
				array(
					'label' => '<i class="glyphicon glyphicon-usd"></i> ' . Yii::t('admin.crud', 'Payment') . ' <b class="caret"></b>',
					'url' => '#',
					'itemOptions' => array(
						'class' => 'dropdown',
					),
					'linkOptions' => array(
						'class' => 'dropdown-toggle',
						'data-toggle' => 'dropdown',
					),
					'items' => array(
						array(
							'label' => '<i class="glyphicon glyphicon-usd"></i> ' . Yii::t('admin.crud', 'Payment'),
							'url' => array('/admin/payment'),
							'visible' => Yii::app()->user->checkAccess('view_payment'),
						),
						array(
							'label' => '<i class="glyphicon glyphicon-list-alt"></i> ' . Yii::t('admin.crud', 'Invoice'),
							'url' => array('/admin/invoice'),
							'visible' => Yii::app()->user->checkAccess('view_invoice'),
						),
					),
				),
				array(
					'label' => '<i class="glyphicon glyphicon-folder-open"></i> ' . Yii::t('admin.crud', 'Manage Content') . ' <b class="caret"></b>',
					'url' => '#',
					'itemOptions' => array(
						'class' => 'dropdown',
					),
					'linkOptions' => array(
						'class' => 'dropdown-toggle',
						'data-toggle' => 'dropdown',
					),
					'items' => array(
						array(
							'label' => '<i class="glyphicon glyphicon-folder-open"></i> ' . Yii::t('admin.crud', 'Files'),
							'url' => array('/admin/file'),
							'visible' => Yii::app()->user->checkAccess('view_file'),
						),
						array(
							'label' => '<i class="glyphicon glyphicon-folder-open"></i> ' . Yii::t('admin.crud', 'File Categories'),
							'url' => array('/admin/fileCategory'),
							'visible' => Yii::app()->user->checkAccess('view_file_category'),
						),
						/*
						array(
							'label' => Yii::t('admin.crud', 'Milestone'),
							'url' => array('/admin/milestone'),
							'visible' => Yii::app()->user->checkAccess('view_milestone'),
						),
						
						
						array(
							'label' => Yii::t('admin.crud', 'Task'),
							'url' => array('/admin/task'),
							'visible' => Yii::app()->user->checkAccess('view_task'),
						),
						*/
					),
				),
				array(
					'label' => '<i class="glyphicon glyphicon-wrench"></i> ' . Yii::t('admin.crud', 'Settings') . ' <b class="caret"></b>',
					'url' => '#',
					'itemOptions' => array(
						'class' => 'dropdown',
					),
					'linkOptions' => array(
						'class' => 'dropdown-toggle',
						'data-toggle' => 'dropdown',
					),
					'items' => array(
					),
				),
			),
			'encodeLabel' => false,
			'htmlOptions' => array(
				'class' => 'nav navbar-nav',
			),
			'submenuHtmlOptions' => array(
				'class' => 'dropdown-menu',
			),
		)); ?>
	</div><!-- /.navbar-collapse -->
</nav>
