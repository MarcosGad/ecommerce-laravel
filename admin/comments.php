<?php
/*
========================================================
==manage Comments page
==you can edit|delete|Approve Comments from here
========================================================
*/
session_start();
$pagetitle='Comments';
if(isset($_SESSION['username'])){
     include 'init.php';
$do=isset($_GET['do'])?$_GET['do']:'manage';

if($do == 'manage'){//manage Comments page
	
       
	$stmt=$con->prepare("select comments.*, items.name as item_name, users.Username as Member
	                     from comments
	                     inner join 
	                     items on items.item_id = comments.item_id
	                     inner join      
	                     users on users.userid = comments.user_id 
	                     order by c_id DESC");
	//execute the statment
	$stmt->execute();
	//assign to variable
	$rows=$stmt->fetchall();

	if (! empty($rows)){
?>

<h1 class="text-center">Manage Comments</h1>
<div class="container">
<div class="table-responsive">
<table class="main-table text-center table table-bordered ">
	<tr>
		<td>ID</td>
		<td>Comment</td>
		<td>Item Name</td>
		<td>User Name</td>
		<td>Added Date</td>
		<td>Control</td>
	<?php
	foreach($rows as $row ){
		echo "<tr>";
		echo "<td>".$row['c_id']."</td>";
		echo "<td>".$row['comment']."</td>";
		echo "<td>".$row['item_name']."</td>";
		echo "<td>".$row['Member']."</td>";
		echo "<td>".$row['comment_data']."</td>";
		echo "<td>
		<a href='comments.php?do=edit&comid=".$row['c_id']."'class='btn btn-success'><i class='fa fa-edit'></i> Edit </a>
		<a href='comments.php?do=delete&comid=".$row['c_id']."'class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>"; 
        
        if($row['status'] == 0){
            
           echo "<a href='comments.php?do=approve&comid=".$row['c_id']."'class='btn btn-info activate'><i class='fa fa-check'></i> Approve </a>"; 
        }
        
		echo "</td>";
		echo "</tr>";
	}

}else{

	echo'<div class="container">';
		echo'<div class="nice-message">There\'s No Comments to show</div>';
	echo'</div>';
}
	?>
	
	
	
	</table>
</div>
	</div>

<?php
	
}elseif($do == 'edit'){//edit page 
//check if get request userid is numeric & get the integer value of it
$comid=isset($_GET['comid'])&& is_numeric($_GET['comid'])? intval($_GET['comid']):0;
//select all data depend on this id
$stmt=$con->prepare("select * from comments where c_id=?");
//execute query
$stmt->execute(array($comid));
//fetch the data
$row=$stmt->fetch();
//the row count
$count=$stmt->rowCount();
//if there's such id show the form
	if($count > 0 ){
?>

<h1 class="text-center">Edit Comment</h1>
<div class="container">
<form class="form-horizontal" action="?do=update" method="POST">

	<input type="hidden" name="comid" value="<?php echo $comid ?>"/> 

	<!--start Comment field-->
	<div class="form-group form-group-lg">
	<label class="col-sm-2 control-label">Comment</label>
		<div class="col-sm-10 col-md-6">
			<textarea class="form-control" name="comment"><?php echo $row['comment'] ?></textarea>
		</div>
	</div>
	<!--end Comment field-->
	
	<!--start submit field-->
	<div class="form-group form-group-lg">
	<div class="col-sm-offset-2 col-sm-10">
		<input type="submit" value="save" class="btn btn-primary btn-lg"/>
		</div>
	</div>
	<!--end submit field-->
	</form>
</div>

<?php
//if there's no such id show error message
}else{
		 
        
        echo "<div class='container'>"; 
		$TheMsg='<div class = "alert alert-danger"> theres no such id </div>';
		redirecthome($TheMsg);
        echo "</div>";
	}
	
}elseif($do == 'update'){//update page
	echo "<h1 class='text-center'>Update Comment</h1>";
	echo "<div class='container'>";
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		//get variable from the form
		$comid=$_POST['comid'];
		$comment=$_POST['comment'];
		
		
		
		//update the database with this info
		$stmt=$con->prepare("update comments set comment=?  where c_id=?");

		$stmt->execute(array($comment,$comid));

		//echo success message
		$TheMsg = "<div class='alert alert-success'>".$stmt->rowcount().' record updated</div>';
            
            redirecthome($TheMsg, 'back' , 5);
            
		        
	
	}else{
		$TheMsg = '<div class = "alert alert-danger"> sorry you cant browse this page directly </div>';
        
        redirecthome($TheMsg);
	}
	
	echo "</div>";
}elseif($do == 'delete'){//delete member page
	echo "<h1 class='text-center'>Delete Comment</h1>";
	echo "<div class='container'>";
//check if get request userid is numeric & get the integer value of it
$comid=isset($_GET['comid'])&& is_numeric($_GET['comid'])? intval($_GET['comid']):0;

    
$check = checkitem('c_id','comments',$comid); 
    
    
    
if($check > 0){
    
	$stmt=$con->prepare("delete from comments where c_id=:zid");
	$stmt->bindparam(":zid",$comid);
	$stmt->execute();
		$TheMsg = "<div class='alert alert-success'>".$stmt->rowcount().' Record Deleted</div>';
        
        redirecthome($TheMsg, 'back');
        
}else{
        
		$TheMsg = '<div class="alert alert-danger"> this id is not exist </div>';
        
        redirecthome($TheMsg);
}


	echo '</div>';

}elseif ($do == 'approve') {

echo "<h1 class='text-center'>Approve Comment</h1>";
	echo "<div class='container'>";
//check if get request userid is numeric & get the integer value of it
$comid=isset($_GET['comid'])&& is_numeric($_GET['comid'])? intval($_GET['comid']):0;
        
  $check = checkitem('c_id','comments',$comid); 
    

    
if($check > 0){
    
	$stmt=$con->prepare("update comments set status = 1 where c_id= ?");
	$stmt->execute(array($comid));

		$TheMsg = "<div class='alert alert-success'>".$stmt->rowcount().' Record Approve </div>';
        
        redirecthome($TheMsg, 'back');
        
}else{
        
		$TheMsg = '<div class="alert alert-danger"> this id is not exist </div>';
        
        redirecthome($TheMsg);
}


	echo '</div>';


}



include $tpl.'footer.php';
	
}else{
	header('location:index.php');
	exit();
}
