<?php

Yii::import('zii.widgets.grid.CDataColumn');

class LinkColumn extends CDataColumn
{
	public $linkExpression;
	public $linkHtmlOptions = array();
	
	protected function renderDataCellContent($row,$data)
	{
		if ($this->value !== null) {
			$value = $this->evaluateExpression($this->value, array('data'=>$data, 'row'=>$row));
		} elseif($this->name!==null) {
			$value = CHtml::value($data, $this->name);
		}
		if ($this->linkExpression !== null) {
			$link = $this->evaluateExpression($this->linkExpression, array('data'=>$data, 'row'=>$row));
		} else {
			$link = array('view', 'id' => $data->primaryKey);
		}
		echo $value===null ? 
			$this->grid->nullDisplay : 
			CHtml::link($this->grid->getFormatter()->format($value, $this->type), $link, $this->linkHtmlOptions);
	}
}
