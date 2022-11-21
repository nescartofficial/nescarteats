<?php
class Template {

	public static function render($section, $view){
		if($section && $view){
			if(file_exists($view.'/'.$section.'.php')){
				include_once($view.'/'.$section.'.php');
			}
		}
		
	}
}