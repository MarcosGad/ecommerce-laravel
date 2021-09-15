<?php
ob_start();  
session_start();
$pagetitle='login';

if(isset($_SESSION['user'])){
	header('location:index.php');
}

include'init.php';

//check if user coming from http post request
if($_SERVER['REQUEST_METHOD'] == 'POST'){

	if(isset($_POST['login'])) {

	$user=$_POST['username'];
	$pass=$_POST['password'];
	$hashpass=sha1($pass);
	//check if the user exist in database
	$stmt=$con->prepare("select 
							            userid,username,password 
								from
										users 
							    where 
										username=? 
								and 
										password=? ");
										
	                          
	$stmt->execute(array($user,$hashpass));
	$get=$stmt->fetch();
	$count=$stmt->rowCount();
	//if count>0 this mean the database contain record about this username
	if($count>0){

		$_SESSION['user']=$user;//register session name

		$_SESSION['uid']=$get['userid'];//register session userid


		header('location:index.php');//redirect to dashboard page

		exit();
	}

	}else{

		$formErrors = array(); 

		$username = $_POST['username']; 
		$password = $_POST['password']; 
		$password2 = $_POST['password2']; 
		$email = $_POST['email']; 


		if (isset($username)) {

			$filterdUser = filter_var($username, FILTER_SANITIZE_STRING); 

			if (strlen($filterdUser) < 4) {

				$formErrors[] = 'Username Must Be Larger Than 4 Caracters'; 
			}
		}

		if (isset($password) && isset($password2)) {

			 if(empty($password)) {

			 	$formErrors[] = 'Sorry Password Cant Be Empty'; 
			 }

			
			 if(sha1($password) !== sha1($password2)) {

			 	$formErrors[] = 'Sorry Password Is Not Match'; 
			 }

		}

		if (isset($email)) {

			$filterdEmail = filter_var($email, FILTER_SANITIZE_EMAIL); 

			if(filter_var($filterdEmail, FILTER_VALIDATE_EMAIL) != true){

			 	$formErrors[] = 'This Email Is Not Valid'; 

			}

		}

		if(empty($formerrors)){
            
        // check if user exist in database 
            
        $check=checkitem("username","users",$username);
            
        if ($check == 1){
            
			 	$formErrors[] = 'Soory This User Is Exists'; 
                        
        }else {
            
		//insert user info in database
		$stmt=$con->prepare("insert into 
		users (username,password,email,regstatus,data)
		values(:zuser, :zpass, :zmail, 0, now())");
		$stmt->execute(array(
		'zuser'=>$username,
		'zpass'=>sha1($password),
		'zmail'=>$email,
		));
		//echo success message

			 	$successMsg = 'Congrats You Are Now Register User'; 
                        
		} // check if user
           
        }

	}
}

?> 
 
 <div class="container login-page">
 	<h1 class="text-center"> 
 		<span class="selected" data-class="login">Login</span> |
 		 <span data-class="signup">Signup</span> 
 	</h1>


 	<!-- start form login --> 
 	<form class="login" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">

 		<div class="input-container">
 		<input class="form-control" type="text" name="username" autocomplete="off" placeholder="Type your username" required >
 		</div>

 		<div class="input-container">
 		<input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Type your password" required>
 	    </div>

 		<input class="btn btn-primary btn-block" name="login" type="submit" value="Login">
 	</form>
     <!-- end form login --> 


     <!-- start form signup --> 
 	<form class="signup" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">

 		<div class="input-container">
 		<input pattern=".{4,}" title="Username must be 4 Chars" class="form-control" type="text" name="username" autocomplete="off" placeholder="Type your username" required />
 	    </div>

 	    <div class="input-container">
 		<input minlength="4" class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Type a complex password" required />
 	    </div>

 	    <div class="input-container">
 		<input minlength="4" class="form-control" type="password" name="password2" autocomplete="new-password" placeholder="Type a password again" required />
 		</div>

 		 <div class="input-container">
 		 <input class="form-control" type="email" name="email" placeholder="Type a valid email" required />
 		 </div>


 		<input class="btn btn-success btn-block" name="signup" type="submit" value="Signup">
 	</form>
 	<!-- end form signup --> 

 	<div class="the-errors text-center">
 		<?php 

 			if(!empty($formErrors)) {

 				foreach ($formErrors as $error){

 					echo '<div class="msg error">' . $error . '</div>' ;
 				}
 			}

 			if(isset($successMsg)) {

 				echo '<div class="msg success">' . $successMsg . '</div>' ;
 			}

 		  ?> 
 	</div>

 </div>






 <?php 
include $tpl.'footer.php';
ob_end_flush(); 
