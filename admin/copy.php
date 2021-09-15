<?php
/*
========================================================
==manage        page
==you can add|edit|delete from here
========================================================
*/

ob_start(); 

session_start();

$pagetitle=' ';

if(isset($_SESSION['username'])){

   include 'init.php';

$do=isset($_GET['do'])?$_GET['do']:'manage';

if($do == 'manage'){
	

}elseif($do == 'add'){


}elseif($do=='insert'){
	

}elseif($do == 'edit'){
	
}elseif($do == 'update'){

}elseif($do == 'delete'){


}elseif ($do == 'Activate'){


}


include $tpl.'footer.php';
	
}else{

	header('location:index.php');
	exit();
}

ob_end_flush(); // release the output 

?> 