<?php
class Input
{
	public static function exists($type = 'post')
	{
		switch ($type) {
			case 'post':
				return (!empty($_POST)) ? TRUE : FALSE;
				break;
			case 'get':
				return (!empty($_GET)) ? TRUE : FALSE;
				break;
			default:
				return false;
				break;
		}
	}

	public static function get($item)
	{
		if (isset($_POST[$item])) {
			return $_POST[$item];
		} else if (isset($_GET[$item])) {
			return $_GET[$item];
		}
		return '';
	}


	public static function is($check, $route = 'page', $post = false)
	{
		if (isset($_POST[$route])) {
			return $_POST[$route] == $check ? $_POST[$route] : false;
		} else if (isset($_GET[$route])) {
			return !$post && $_GET[$route] == $check ? $_GET[$route] : false;
		}
		return false;
	}
}
