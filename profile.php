<?php
session_start();
$pagetitle='Profile';
include'init.php';

 if(isset($_SESSION['user'])){

 $getUser = $con->prepare("select * from users where username=?"); 
 $getUser->execute(array($sessionUser)); 
 $info = $getUser->fetch(); 
 $userid = $info['userid']; 
 

?>

<h1 class="text-center">My Profile</h1>

<div class="information">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">My Information</div>
			<div class="panel-body">
				<ul class="list-unstyled">
				<li> 
					<i class="fa fa-unlock-alt fa-fw"></i>
					<span>Login Name</span> : <?php echo $info['username'];  ?>
			    </li> 

				<li>
					<i class="fa fa-envelope-o fa-fw"></i>
				    <span>Email</span> : <?php echo $info['email'];  ?> 
				</li> 

				<li>
					<i class="fa fa-user fa-fw"></i>
				    <span>Full Name</span> : <?php echo $info['fullname'];  ?> 
				</li>

				<li>
					<i class="fa fa-calendar fa-fw"></i>
				    <span>Register Date</span> : <?php echo $info['data'];  ?>
				</li>

				<li>
					 <i class="fa fa-tags fa-fw"></i>
					 <span>Fav Category</span> :
				</li> 

				</ul>
				<a href="#" class="btn btn-default">Edit Information</a>
			</div>
		</div>
	</div>
</div>    

<div id="my-item" class="my-ad">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">My Items</div>
			<div class="panel-body">
		<?php 

			$myItems = getAllFrom("*", "items","where member_id = $userid" ,"","item_id"); 

			//if(! empty(getItems('member_id', $info['userid']))) {

			if(! empty($myItems)) {

                echo'<div class="row">'; 
			foreach ($myItems as $item ) {
				
				echo'<div class="col-sm-6 col-md-3">'; 
				  echo'<div class="thumbnail item-box">'; 
				  		if ($item['approve'] == 0) { echo'<span class="approve-status">Not Approve</span>'; }
				  		echo '<span class="price">$' . $item['price'] . '</span>';
				  		echo '<img class="img-responsive" src="businessman-avatar.png" alt="" />';
				  		echo '<div class="caption">'; 
				  			echo '<h3><a href="items.php?itemid=' . $item['item_id'] . '">' . $item['name'] . '</a></h3>'; 
				  			echo '<p>'. $item['description'] . '</p>';
				  			echo '<div class="date">'. $item['add_date'] . '</div>';

				  		echo '</div>'; 
				  echo '</div>'; 
			  echo '</div>';
			}  
			 echo'</div>'; 
			} else {

				echo 'Sorry There\'s No Ads To Show, Create <a href="newad.php"> New Ad </a>'; 
		}
		?>
		</div>
		</div>
	</div>
</div> 


<div class="my-comments">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">Letest Comments</div>
			<div class="panel-body">

				<?php 

				$comments = getAllFrom("comment", "comments","where user_id = $userid" ,"","c_id","ASC"); 

					//$stmt=$con->prepare("select comment from comments where user_id =?");
	                     
							//$stmt->execute(array($userid));
							//$comments=$stmt->fetchall();

							if(! empty($comments)){

								foreach ($comments as $comment ) {

									echo '<p>' . $comment['comment'] . '</p>';
								}

							}else {

								echo "There's No Commnets to show";

							}
				?> 
			</div>
		</div>
	</div>
</div> 




<?php 

}else {

	header('Location:login.php'); 
	exit(); 
}

include $tpl.'footer.php';
?> 