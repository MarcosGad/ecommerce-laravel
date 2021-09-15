<?php
session_start();
$nonavbar='';
$pagetitle='login';
if(isset($_SESSION['username'])){
	header('location:dashboard.php');//redirect to dashboard page
}
include'init.php';

//check if user coming from http post request
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$username=$_POST['user'];
	$password=$_POST['pass'];
	$hashpass=sha1($password);
	//echo $hashpass;
	//check if the user exist in database
	$stmt=$con->prepare("select 
							            userid,username,password 
								from
										users 
							    where 
										username=? 
								and 
										password=?
								and 
								        groupid=1
										limit 1");
	                          
	$stmt->execute(array($username,$hashpass));
	$row=$stmt->fetch();
	$count=$stmt->rowCount();
	//if count>0 this mean the database contain record about this username
	if($count>0){
		print_r($row);
		$_SESSION['username']=$username;//register session name
		$_SESSION['id']=$row['userid'];//register session id
		header('location:dashboard.php');//redirect to dashboard page
		exit();
	}
}
?>
<form class="login" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
<h4 class="text-center">Admin Login</h4>
<input type="text" class="form-control" name="user" placeholder="username" autocomplete="off" />
<input type="password" class="form-control" name="pass" placeholder="password" autocomplete="new-password" />
<input class="btn btn-primary btn-block" type="submit" value="login" />

</form>
<?php
include $tpl.'footer.php';
?>