<nav class="navbar navbar-default" role="navigation">
	<div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-navbar-collapse">
			<span class="sr-only"><?php echo Yii::t('core.crud', 'Toggle navigation'); ?></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<a class="navbar-brand" href="<?php echo Yii::app()->homeUrl; ?>">
			<img src="/rc/img/logo-sm.png" title="<?php echo CHtml::encode(Yii::app()->name); ?>" />
		</a>
	</div>

	<div class="collapse navbar-collapse" id="bs-navbar-collapse">
		<?php echo CHtml::link('<i class="glyphicon glyphicon-off"></i> ' . Yii::t('core.crud', 'Logout'), array('default/logout'), array('class' => 'btn btn-default navbar-btn pull-right logout-btn')) ?>
		
		<p class="pull-right navbar-text visible-lg">
			<i class="glyphicon glyphicon-user"></i> <?php echo  Yii::t('core.crud', 'You are logged as'); ?> <a href="<?php echo $this->createUrl('user/profile'); ?>"><b><?php echo Yii::app()->user->name; ?></a></b>
		</p>
		
		<p class="pull-right notifications" id="notifications">
			<a class="btn btn-default" href="<?php echo $this->createUrl('default/updated'); ?>"><i class="glyphicon glyphicon-bell"></i></a>
			<audio><source src="/rc/audio/notify.mp3" type="audio/mpeg" /></audio>
		</p>
		
		<?php $this->widget('Menu', array(
			'items' => array(
				array(
					'label' => '<i class="glyphicon glyphicon-user"></i> ' . Yii::t('core.crud', 'Users') . ' <b class="caret"></b>',
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
							'label' => '<i class="glyphicon glyphicon-user"></i> ' . Yii::t('core.crud', 'Users'),
							'url' => array('user/index'),
							'visible' => Yii::app()->user->checkAccess('view_user'),
						),
						array(
							'label' => '<i class="glyphicon glyphicon-star"></i> ' . Yii::t('core.crud', 'Rates'),
							'url' => array('rate/index'),
							'visible' => Yii::app()->user->checkAccess('view_rate'),
						),
						array(
							'label' => '<i class="glyphicon glyphicon-flash"></i> ' . Yii::t('core.crud', 'Activities'),
							'url' => array('activity/index'),
							'visible' => Yii::app()->user->checkAccess('view_activity'),
						),
						array(
							'label' => '<i class="glyphicon glyphicon-user"></i> ' . Yii::t('core.crud', 'Candidates'),
							'url' => array('candidate/index'),
							'visible' => Yii::app()->user->checkAccess('view_candidate'),
						),
					),
				),
				array(
					'label' => '<i class="glyphicon glyphicon-briefcase"></i> ' . Yii::t('core.crud', 'Projects') . ' <b class="caret"></b>',
					'url' => '#',
					'itemOptions' => array(
						'class' => 'dropdown',
					),
					'linkOptions' => array(
						'class' => 'dropdown-toggle',
						'data-toggle' => 'dropdown',
					),
					'items' => CMap::mergeArray(array_map(function($project) {
							$addons = array();
							$item = array(
								'label' => '<i class="glyphicon glyphicon-file"></i> ' . CHtml::encode($project->name),
								'url' => array('project/view', 'id' => $project->id),
							);
							if (Yii::app()->user->checkAccess('view_task', array('project' => $project))) {
								$addons[] = array(
									'label' => '<i class="glyphicon glyphicon-tasks"></i>',
									'url' => array('task/index', 'project' => $project->id),
									'options' => array(
										'title' => Yii::t('core.crud', 'Tasks'),
										'class' => 'menu-addon',
									),
								);
							}
							if (Yii::app()->user->checkAccess('create_task', array('project' => $project))) {
								$addons[] = array(
									'label' => '<i class="glyphicon glyphicon-plus"></i>',
									'url' => array('task/create', 'project' => $project->id),
									'options' => array(
										'title' => Yii::t('core.crud', 'Create Task'),
										'class' => 'menu-addon',
									),
								);
							}
							if (!empty($addons)) {
								$item['addon'] = $addons;
								$item['itemOptions'] = array('class' => 'has-addon');
							}
							return $item;
						}, Project::getUserBundle()), array(
							array(
								'label' => '<i class="glyphicon glyphicon-briefcase"></i> ' . Yii::t('core.crud', 'Manage Projects'),
								'url' => array('project/index'),
								'visible' => Yii::app()->user->checkAccess('view_project'),
							),
							array(
								'label' => '',
								'itemOptions' => array(
									'class' => 'divider',
								)
							),
							array(
								'label' => '<i class="glyphicon glyphicon-th"></i> ' . Yii::t('core.crud', 'Schedule'),
								'url' => array('schedule/index'),
								'visible' => Yii::app()->user->checkAccess('view_schedule'),
							),
							array(
								'label' => '<i class="glyphicon glyphicon-tag"></i> ' . Yii::t('core.crud', 'Tags'),
								'url' => array('tag/index'),
								'visible' => Yii::app()->user->checkAccess('view_tag'),
							),
						)
					),
				),
				array(
					'label' => '<i class="glyphicon glyphicon-check"></i> ' . Yii::t('core.crud', 'Reports') . ' <b class="caret"></b>',
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
							'label' => '<i class="glyphicon glyphicon-time"></i> ' . Yii::t('core.crud', 'Time Entries'),
							'url' => array('timeEntry/index'),
							'visible' => Yii::app()->user->checkAccess('view_time_entry'),
						),
						array(
							'label' => '<i class="glyphicon glyphicon-usd"></i> ' . Yii::t('core.crud', 'Payments'),
							'url' => array('payment/index'),
							'visible' => Yii::app()->user->checkAccess('view_payment'),
						),
						array(
							'label' => '<i class="glyphicon glyphicon-list-alt"></i> ' . Yii::t('core.crud', 'Invoices'),
							'url' => array('invoice/index'),
							'visible' => Yii::app()->user->checkAccess('view_invoice'),
						),
					),
				),
				array(
					'label' => '<i class="glyphicon glyphicon-folder-open"></i> ' . Yii::t('core.crud', 'Manage Content') . ' <b class="caret"></b>',
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
							'label' => '<i class="glyphicon glyphicon-folder-open"></i> ' . Yii::t('core.crud', 'Files'),
							'url' => array('file/index'),
							'visible' => Yii::app()->user->checkAccess('view_file'),
						),
						array(
							'label' => '<i class="glyphicon glyphicon-folder-open"></i> ' . Yii::t('core.crud', 'File Categories'),
							'url' => array('fileCategory/index'),
							'visible' => Yii::app()->user->checkAccess('view_file_category'),
						),
						array(
							'label' => '<i class="glyphicon glyphicon-list-alt"></i> ' . Yii::t('core.crud', 'Examination'),
							'url' => array('question/index'),
							'visible' => Yii::app()->user->checkAccess('view_question'),
						),
					),
				),
				array(
					'label' => '<i class="glyphicon glyphicon-wrench"></i> ' . Yii::t('core.crud', 'Settings') . ' <b class="caret"></b>',
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
							'label' => '<i class="glyphicon glyphicon-wrench"></i> ' . Yii::t('core.crud', 'General'),
							'url' => array('options/generalOptions'),
							'visible' => Yii::app()->user->checkAccess('update_general_options'),
						),
						array(
							'label' => '<i class="glyphicon glyphicon-calendar"></i> ' . Yii::t('core.crud', 'Holidays'),
							'url' => array('holiday/index'),
							'visible' => Yii::app()->user->checkAccess('view_holiday'),
						),
						array(
							'label' => '<i class="glyphicon glyphicon-time"></i> ' . Yii::t('core.crud', 'Working Hours'),
							'url' => array('workingHours/index'),
							'visible' => Yii::app()->user->checkAccess('view_working_hours'),
						),
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
