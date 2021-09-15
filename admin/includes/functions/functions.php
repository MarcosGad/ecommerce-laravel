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
**home redirect function v1.0
**this function accept parameters
**$errormsg=echo the error message
**$seconds=seconds before redirecting

function redirecthome($errormsg,$seconds=3){
	echo "<div class='alert alert-danger'>$errormsg</div>";
	echo "<div class='alert alert-info'>you will be redirected to home page after $seconds seconds</div>";
	header("refresh:$seconds;url=index.php");
	exit();
}
*/
/*
**home redirect function v2.0
**this function accept parameters
**$themsg=echo the message [error - success - warning]
**$url = the link you want to redirect to 
**$seconds=seconds before redirecting
*/
function redirecthome($TheMsg,$url = null,$seconds=3){
    
    if ($url == null){
        
        $url = 'index.php'; 
        
        $link = 'Home Page';
        
    }else {
        
        if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {
            
            $url = $_SERVER['HTTP_REFERER']; 
            
            $link = 'Previous Page';
            
        }else {
            
            
            $url = 'index.php'; 
            
            $link = 'Home Page';
          
        }
        
        
    }
    
	echo $TheMsg;
	echo "<div class='alert alert-info'>you will be redirected to $link page after $seconds seconds</div>";
	header("refresh:$seconds;url=$url");
	exit();
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





















/* 
** count number of items function v1.0 
** function to count number of items rows 
** $item = the item to count 
** $table = the table to choose from
*/ 

function countItems($item, $table) {
    
    global $con;    // trun on con 
    
    $stmt2 = $con->prepare("select count($item) from $table"); 
    
    $stmt2->execute(); 
    
    return $stmt2->fetchColumn(); 
}




















/*
** get latest records function v1.0
** function to get latest items from datebase[users-items-comments]
** $select = field to select 
** $table = the table to choose from 
** $order = the DESC ordering 
** $limit = number of records to get
*/
function getLatest($select,$table,$order,$limit=5) {

	 global $con;

	 $getstmt = $con->prepare("select $select from $table order by $order DESC limit $limit");

	 $getstmt->execute(); 

	 $rows = $getstmt->fetchAll(); 

	 return $rows; 

}












/* fun   of   all   */ 


function getAllFrom($field, $table, $where = null, $and = null, $orderfield , $ordering= "DESC") {

	 global $con;

	 $getAll = $con->prepare("select $field from $table $where $and order by $orderfield $ordering");

	 $getAll->execute(); 

	 $all = $getAll->fetchAll(); 

	 return $all; 

}
