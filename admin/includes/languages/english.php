<?php
function lang($phrase){
	static $lang=array(
	//Navbar link
	'Home-Admin'=>'Home',
	'categories'=>'categories',
	'Items'     =>'Items',
	'Members'   =>'Members',
	'Statistics'=>'Statistics',
	'Comments'=>'Comments',
	'Logs'      =>'Logs',
	);
	return $lang[$phrase];
}