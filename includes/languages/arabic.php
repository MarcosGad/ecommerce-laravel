<?php
function lang($phrase){
	static $lang=array(
	'message'=>'مرحبا',
	'admin'=>'ادمن'
	);
	return $lang[$phrase];
}