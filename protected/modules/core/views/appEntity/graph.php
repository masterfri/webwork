<?php

$this->pageHeading = Yii::t('core.crud', 'Application Graph');

$this->breadcrumbs = array(
	Yii::t('core.crud', 'Projects') => Yii::app()->user->checkAccess('view_project') ? array('project/index') : false, 
	CHtml::encode($application->project->name) => Yii::app()->user->checkAccess('view_project', array('project' => $application->project)) ? array('project/view', 'id' => $application->project->id) : false, 
	CHtml::encode($application->name) => Yii::app()->user->checkAccess('view_application', array('application' => $application)) ? array('application/view', 'id' => $application->id) : false, 
	Yii::t('core.crud', 'Graph')
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-arrow-left"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Back'), 
			'class' => 'btn btn-default',
		),
		'url' => array('index', 'application' => $application->id),
		'visible' => Yii::app()->user->checkAccess('design_application', array('application' => $application)),
	)
);

?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->pageHeading; ?></h3>
	</div>
	<div class="panel-body">
		<div id="app-graph"></div>
	</div>
</div>

<?php

$data = $application->getGraphJOSN();
$cs = Yii::app()->clientScript;
$cs->registerScriptFile('/rc/js/svg.min.js', CClientScript::POS_END);
$cs->registerScriptFile('/rc/js/svg.draggy.min.js', CClientScript::POS_END);
$cs->registerScriptFile('/rc/js/appgraph.js', CClientScript::POS_END);
$cs->registerScript('app-graph',
<<<ENDJS
var graphData = $data;
var container = document.querySelector("#app-graph");
var graph = new AppGraph(container, {
	'memoryPrefix': 'AppGraph{$application->id}_',
	'width': $(container).width()
});
graphData.forEach(function(entity) {
	graph.addNode({
		'name': entity.name,
		'attributes': entity.attributes
	});
});
graphData.forEach(function(entity) {
	entity.attributes.forEach(function(attribute) {
		if (attribute.relation && graph.hasNode(attribute.type)) {
			if (attribute.relation == 'many-to-many') {
				graph.connect(entity.name, attribute.type, attribute.name, undefined, AppGraph.LINK_CONNECTED);
			} else if (attribute.relation == 'many-to-one') {
				graph.connect(entity.name, attribute.type, attribute.name, undefined, AppGraph.LINK_BELONG);
			} else {
				graph.connect(entity.name, attribute.type, attribute.name, undefined, AppGraph.LINK_HAS);
			}
		}
	});
});
graph.adjustHeight();
ENDJS
);
