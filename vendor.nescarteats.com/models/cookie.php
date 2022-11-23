<?php
class Cookie {
	
	public static function exists($name){
		return (isset($_COOKIE[$name]))? True : False;
	}
	
	
	public static function get($name){
		return $_COOKIE[$name];
	}
	
	
	public static function put($name, $value, $expiry){
		if(setcookie($name, $value, time() + $expiry, '/')){
			return True;
		}else{
			return False;
		}
		
	}
	
	
	public static function delete($name){
		self::put($name, '', (time() - 1));
	}
}