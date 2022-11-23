<?php
class Component
{

	public static function render($section, $data = null, $view = 'view/component')
	{
		if ($section && $view) {
			if (file_exists($view . '/' . $section . '.php')) {
				include($view . '/' . $section . '.php');
			}
		}
	}

	public static function exists($section, $view = 'view')
	{
		if ($section && $view) {
			if (file_exists($view . '/' . $section . '.php')) {
				return true;
			}
		}
		return false;
	}
}
