<?php
/*
**title function v1.0
**title function that echo the page title incase the page
**has the variable $pagetitle and echo defult title for other pages
*/
function gettitle(){
	global $pagetitle;
if(isset($pagetitle)){
	echo $pagetitle;
}else{
	echo 'default';
}
}












/*
** get All  function v1.0
** function to get all from datebase

function getAllFrom($tableName, $orderBy) {

	 global $con;

	 $getAll = $con->prepare("select * from $tableName order by $orderBy DESC");

	 $getAll->execute(); 

	 $all = $getAll->fetchAll(); 

	 return $all; 

}

*************************************************************

function getAllFrom($tableName, $orderBy, $where = null) {

	 global $con;

	 $getAll = $con->prepare("select * from $tableName $where order by $orderBy DESC");

	 $getAll->execute(); 

	 $all = $getAll->fetchAll(); 

	 return $all; 

}

  */  

function getAllFrom($field, $table, $where = null, $and = null, $orderfield , $ordering= "DESC") {

	 global $con;

	 $getAll = $con->prepare("select $field from $table $where $and order by $orderfield $ordering");

	 $getAll->execute(); 

	 $all = $getAll->fetchAll(); 

	 return $all; 

}










/*
** get categories function v1.0
** function to get categories from datebase
*/
function getCat() {

	 global $con;

	 $getcat = $con->prepare("select * from categories");

	 $getcat->execute(); 

	 $cats = $getcat->fetchAll(); 

	 return $cats; 

}
















/*
** get Ad Items function v1.0
** function to get items from datebase

function getItems($CatID) {

	 global $con;

	 $getItems = $con->prepare("select * from items where cat_id = ? order by item_id DESC");

	 $getItems->execute(array($CatID)); 

	 $Items = $getItems->fetchAll(); 

	 return $Items; 

}
*/

function getItems($where , $value, $approve = null) {

	 global $con;

	 if($approve == null){

	 	$sql = 'and approve =1'; 

	 }else {

	 	$sql = null; 
	 }

	 $getItems = $con->prepare("select * from items where $where = ? $sql order by item_id DESC");

	 $getItems->execute(array($value)); 

	 $Items = $getItems->fetchAll(); 

	 return $Items; 

}














/*
** check if user is not activated v1.0
** function to check the regstatus of the user 
*/

function checkUserStatus($user) {

	 global $con;

	 $stmtx=$con->prepare("select 
							            username,regstatus 
								from
										users 
							    where 
										username=? 
								and 
										regstatus=0 ");

	$stmtx->execute(array($user));
	$status=$stmtx->rowCount();

	return $status; 
}






/*
**check items function v1.0
**function to check item in database [function accept parameters]
**$select=the item to select[example:user,item,category]
**$from=the table to select from[example:users,items,categories]
**$value=the value of select[example:osama,box,electronics]
*/
function checkitem($select,$from,$value){
	global $con;
	$statment=$con->prepare("select $select from $from where $select=?");
	$statment->execute(array($value));
	$count=$statment->rowcount();
	return $count;
}