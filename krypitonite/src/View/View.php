<?php

namespace Krypitonite\View;

class View{
	
	private static $_params;
	
	public static function render(){
	    
	}

	public static function setParams($params){
		self::$_params = $params;
	}
	
	public static function getParams(){
		return self::$_params;
	}
	
}