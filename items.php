<?php
session_start();
$pagetitle='Show Items';
include'init.php';

//check if get request userid is numeric & get the integer value of it
$itemid=isset($_GET['itemid'])&& is_numeric($_GET['itemid'])? intval($_GET['itemid']):0;
//select all data depend on this id
$stmt=$con->prepare("SELECT items.*, categories.name AS Category_Name, users.username AS itemUser FROM items

                          INNER JOIN categories ON categories.id = items.cat_id

                          INNER JOIN users ON users.userid = items.member_id 

	                      where item_id = ?  and approve = 1");
//execute query
$stmt->execute(array($itemid));

$count = $stmt->rowCount(); 

if($count > 0){

$item=$stmt->fetch();
?>

<h1 class="text-center"><?php echo $item['name'];  ?> </h1>

<div class="container">
	<div class="row">
		<div class="col-md-3">
			<img class="img-responsive img-thumbnail center-block" src="businessman-avatar.png" alt="" />
		</div>
		<div class="col-md-9 item-info">
			<h2><?php echo $item['name']; ?></h2> 
			<p><?php echo $item['description']; ?></p>
			<ul class="list-unstyled">
			<li>
				<i class="fa fa-calendar fa-fw"></i>
				<span>Added Date</span> : <a href=""><?php echo $item['add_date']; ?></a>
			</li>  
			<li>
				<i class="fa fa-money fa-fw"></i>
				<span>Price</span> : <a href="">$<?php echo $item['price']; ?></a>
			</li>
			<li>
				<i class="fa fa-building fa-fw"></i>
				<span>Made In</span> : <a href=""><?php echo $item['country_made']; ?></a>
			</li>
            <li>
            	<i class="fa fa-tags fa-fw"></i>
            	<span>Category</span> : <a href="categories.php?pageid=<?php echo $item['cat_id'] ?>"><?php echo $item['Category_Name']; ?></a>
            </li>
            <li>
            	<i class="fa fa-user fa-fw"></i>
            	<span>Added by</span> : <a href="#"><?php echo $item['itemUser']; ?></a>
            </li>
              <li class="tags-items">
            	<i class="fa fa-user fa-fw"></i>
            	<span>Tags</span> : 
            	<?php 
            		$allTags = explode(",",$item['tags']); 
            		foreach($allTags as $tag) {
            			$tag = str_replace(' ', '',$tag);
            			$lowertag = strtolower($tag);
            			if(! empty($tag)){
            			echo "<a href='tags.php?name={$lowertag}'>" .  $tag.  '</a>'; 
            		    }
            		}


            	?>
            </li>
            </ul>
		</div>
	</div>
	<hr class="custom-hr">

<?php  if(isset($_SESSION['user'])){ ?> 

	<!-- start add comment --> 
		<div class="row">
			<div class="col-md-offset-3"> 
				<div class="add-comment">
				<h3>Add Your Comment</h3>
					<form action="<?php echo $_SERVER['PHP_SELF'] .'?itemid=' . $item['item_id'] ?>" method="POST">
						<textarea name="Comment" required></textarea>
						<input class="btn btn-primary" type="submit" value="Add Comment">
					</form>
					<?php 

						if($_SERVER['REQUEST_METHOD'] == 'POST'){

							$comment = filter_var($_POST['Comment'], FILTER_SANITIZE_STRING);
							$itemid = $item['item_id']; 
							$userid = $_SESSION['uid']; 
							

							if(!empty($comment)) {

								$stmt = $con->prepare("insert into comments(comment,status,comment_data,item_id,user_id)
									                                values(:zcomment,0,now(),:zitemid,:zuserid)"); 

								$stmt->execute(array(

									'zcomment' => $comment,
									'zitemid' => $itemid,
									'zuserid' => $userid

								)); 

								if($stmt) {

									echo '<div class="alert alert-success">Comment Added</div>';
								}
							}

						}


					?> 
			    </div>
			</div>
		</div>

  <!-- end add comment --> 
<?php }else {

		echo'<a href="login.php">Login</a> or <a href="login.php">Register</a> To Add Comment'; 

        } ?> 
	<hr class="custom-hr">
		<?php

		   	 $stmt=$con->prepare("select comments.*, users.Username as Member
	                     from comments
	                     inner join      
	                     users on users.userid = comments.user_id 
	                     where item_id=? and status = 1 
	                     order by c_id DESC");
			//execute the statment
			$stmt->execute(array($item['item_id']));
			//assign to variable
			$comments=$stmt->fetchall();

		   	 ?> 
				<?php 
					foreach ($comments as $comment){ ?> 
						<div class="comment-box">
							 <div class="row">
								<div class="col-sm-2 text-center">
									<img class="img-responsive img-thumbnail img-circle" src="businessman-avatar.png" alt="" />
									<?php echo $comment['Member']  ?>
							    </div>

							    <div class="col-sm-10">
									 <p class="lead"><?php echo $comment['comment'] ?></p>
									  		
								</div>  
							 </div>
						</div> 
						<hr class="custom-hr">
						<?php } ?> 


<?php 

}else {

	echo'<div class="alert alert-danger">there\'s no such ID or this item is waiting approval</div>'; 
}

include $tpl.'footer.php';
?> 