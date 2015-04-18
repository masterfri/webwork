<?php

class MarkdownWidget extends CMarkdown
{
	public function transform($output)
	{
		return parent::transform(CHtml::encode($output));
	}
}
