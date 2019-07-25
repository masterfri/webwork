<?php

class MarkdownWidget extends CMarkdown
{
	public $attachments = null;
	
	public function transform($output)
	{
		$output = $this->sanitizeHtml($output);
		if (is_array($this->attachments)) {
			$output = ViewHelper::processAttachments($output, $this->attachments);
		}
		return parent::transform($output);
	}
	
	protected function sanitizeHtml($text)
	{
		$s = 0;
		$result = '';
		$l = strlen($text);
		$a = '/qwertyuiopasdfghjklzxcvbnm';
		while (false !== ($p = strpos($text, '<'))) {
			if (stripos($a, substr($text, $p + 1, 1)) !== false) {
				$result .= substr($text, 0, $p) . '&lt;';
				$text = substr($text, $p + 1);
				if (false !== ($p = strpos($text, '>'))) {
					$result .= substr($text, 0, $p) . '&gt;';
					$text = substr($text, $p + 1);
				}
			} else {
				$result .= substr($text, 0, $p) . '<';
				$text = substr($text, $p + 1);
			}
		}
		$result .= $text;
		return $result;
	}
}
