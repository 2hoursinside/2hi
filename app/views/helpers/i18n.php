<?php
class I18nHelper extends HtmlHelper
{
	function url($url = null, $full = false)
	{
		return '/' .
		       Configure::read('Config.langCode') .
		       parent::url($url, $full);
	}
 
	function link($title, $url = null, $htmlAttributes = array(), $confirmMessage = false, $escapeTitle = true)
	{
		return parent::link($title, $url, $htmlAttributes, $confirmMessage, $escapeTitle);
	}
}
?>