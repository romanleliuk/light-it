<?php
/**
 * General view
 */
class View
{
	/**
	 * Default view
	 * @var string
	 */
	public $template_view = 'template_view.php';
	
	/**
	 * Generate page, extract data
	 * @param string $content_view - selected view
	 * @param string $template_view - standart view
	 * @param mixed $data - information ready for output
	 * @return —
	 */
	function generate($content_view, $template_view, $data = null) {
		if(is_array($data)) { 
			extract($data);
		}
		include config::PATH_VIEWS.$template_view;
	}
}