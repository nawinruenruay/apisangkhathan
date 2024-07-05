<?php
class View{
	function __construct(){
		//echo "This View";
	}
	public function render($name , $noInclude = false){
		require 'views/'.$name.'.php';
	}
}
	
?>