<?php

require_once Yii::getPathOfAlias('ext.html2pdf') . '/html2pdf.class.php';

class RenderPdf extends COutputProcessor
{
	public $filename = 'output.pdf';
	public $orientation = 'P';
	public $format = 'A4';
	public $language = 'en';
	public $margins = array(15, 10, 10, 10);
	
	public function processOutput($output)
	{
		$html2pdf = new HTML2PDF($this->orientation, $this->format, $this->language, true, 'UTF-8', $this->margins);
        $html2pdf->writeHTML($output);
        $html2pdf->Output($this->filename);
	}
}
