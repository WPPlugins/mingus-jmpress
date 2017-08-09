<?php
class MingusJmpress_Tools{
	public function jmKeys(){
		return array(
				'data-x',
				'data-y',
				'data-z',
				'data-r',
				'data-phi',
				'data-scale',
				'data-rotate',
				'data-rotate-x',
				'data-rotate-y',
				'data-rotate-z',
				'data-delegate',
				'data-src',
				'data-exclude',
				'data-next',
				'data-prev',
				'data-template',
				'data-jmpress'
			);
	}
	public function jmStyleKeys(){
		return array(
			'width',
			'height'
			);
	}
	public function guid($is_key = false){
		$uuid = '';
	    if (function_exists('com_create_guid')){
	        $uuid = com_create_guid();
	    }else{
	        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
	        $charid = strtoupper(md5(uniqid(rand(), true)));
	        $hyphen = chr(45);// "-"
	        $uuid = chr(123)// "{"
	                .substr($charid, 0, 8).$hyphen
	                .substr($charid, 8, 4).$hyphen
	                .substr($charid,12, 4).$hyphen
	                .substr($charid,16, 4).$hyphen
	                .substr($charid,20,12)
	                .chr(125);// "}"
	    }
	    if($is_key){
	    	$uuid = str_replace('{', '', $uuid);
	    	$uuid = str_replace('}', '', $uuid);
	    	$uuid = str_replace('-', '', $uuid);
	    }
	    return $uuid;
	}
}