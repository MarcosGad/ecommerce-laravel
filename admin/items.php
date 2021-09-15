<?php
/*
========================================================
==manage items page
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


    
	$stmt=$con->prepare("SELECT items.*, categories.name AS Category_Name, users.username AS itemUser FROM items

                          INNER JOIN categories ON categories.id = items.cat_id

                          INNER JOIN users ON users.userid = items.member_id 

                          order by item_id DESC");
                                                                                        
	//execute the statment
	$stmt->execute();
	//assign to variable
	$items=$stmt->fetchall();

	if (! empty($items)) {
?>

<h1 class="text-center">Manage Items</h1>
<div class="container">
<div class="table-responsive">
<table class="main-table text-center table table-bordered ">
	<tr>
		<td>ID</td>
		<td>Name</td>
		<td>Description</td>
		<td>Price</td>
		<td>Category</td>
		<td>Username</td>
		<td>Adding Date</td>

		<td>Control</td>
	<?php
	foreach($items as $item ){
		echo "<tr>";
		echo "<td>".$item['item_id']."</td>";
		echo "<td>".$item['name']."</td>";
		echo "<td>".$item['description']."</td>";
		echo "<td>".$item['price']."</td>";
		echo "<td>".$item['Category_Name']."</td>";
		echo "<td>".$item['itemUser']."</td>";
		echo "<td>".$item['add_date']."</td>";
		echo "<td>
		<a href='items.php?do=edit&item_id=".$item['item_id']."'class='btn btn-success'><i class='fa fa-edit'></i> Edit </a>
		<a href='items.php?do=delete&item_id=".$item['item_id']."'class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>"; 

		 if($item['approve'] == 0){
            
           echo "<a href='items.php?do=approve&item_id=".$item['item_id']."'class='btn btn-info activate'><i class='fa fa-check'></i> Approve </a>"; 
        }
        
		echo "</td>";
		echo "</tr>";
	}
	?>
	
	
	
	</table>
</div>
<a href="items.php?do=add" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> new Item </a>
	</div>

	<?php }else{

	echo'<div class="container">';
		echo'<div class="nice-message">There\'s No Items to show</div>';
		echo '<a href="items.php?do=add" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> new Item </a>'; 
	echo'</div>';
}
	

}elseif($do == 'add'){ ?> 
	<h1 class="text-center">Add New Item</h1>;
	<div class="container">
	<form class="form-horizontal" action="?do=insert" method="POST">
	<!--start name field-->
		<div class="form-group form-group-lg">
		<label class="col-sm-2 control-label">Name</label>
		<div class="col-sm-10 col-md-6">
			<input type="text" name="name" class="form-control" required="required" placeholder="Name of the Item" />
		</div>
		</div>
	<!--end name field-->

	<!--start description field-->
		<div class="form-group form-group-lg">
		<label class="col-sm-2 control-label">Description</label>
		<div class="col-sm-10 col-md-6">
			<input type="text" name="description" class="form-control" required="required" placeholder="Description of the Item" />
		</div>
		</div>
	<!--end description field-->

	<!--start Price field-->
		<div class="form-group form-group-lg">
		<label class="col-sm-2 control-label">Price</label>
		<div class="col-sm-10 col-md-6">
			<input type="text" name="price" class="form-control" required="required" placeholder="Price of the Item" />
		</div>
		</div>
	<!--end Price field-->

	<!--start Country Made field-->
		<div class="form-group form-group-lg">
		<label class="col-sm-2 control-label">Country</label>
		<div class="col-sm-10 col-md-6">
			<input type="text" name="country_made" class="form-control" required="required" placeholder="Country of Made" />
		</div>
		</div>
	<!--end Country Made field-->

	<!--start Status field-->
		<div class="form-group form-group-lg">
		<label class="col-sm-2 control-label">Status</label>
		<div class="col-sm-10 col-md-6">
			<select name="status">
				<option value="0">-----</option>
				<option value="1">New</option>
				<option value="2">like New</option>
				<option value="3">Used</option>
				<option value="4">Very Old</option>

			</select>
		</div>
		</div>
	<!--end status field-->

	<!--start Members field-->
		<div class="form-group form-group-lg">
		<label class="col-sm-2 control-label">Member</label>
		<div class="col-sm-10 col-md-6">
			<select name="member">
				<option value="0">-----</option>
				<?php 
					$stmt=$con->prepare("select * from users");
					$stmt->execute();
	                $users=$stmt->fetchall();
	                foreach ($users as $user) {

	                	echo "<option value='". $user['userid'] ."'> ".$user['username']." </option>";
	                }
				?> 
				

			</select>
		</div>
		</div>
	<!--end Members field-->

	<!--start categories field-->
		<div class="form-group form-group-lg">
		<label class="col-sm-2 control-label">Category</label>
		<div class="col-sm-10 col-md-6">
			<select name="category">
				<option value="0">-----</option>
				<?php 

				     $allCats = getAllFrom("*", "categories","where parent = 0","","id"); 

					//$stmt2=$con->prepare("select * from categories");
					//$stmt2->execute();
	                //$cats=$stmt2->fetchall();

	                foreach ($allCats as $cat) {

	                	echo "<option value='". $cat['id'] ."'> ".$cat['name']." </option>";

	                	$childCats = getAllFrom("*", "categories","where parent = {$cat['id']}","","id"); 

	                	foreach ($childCats as $child){

	                		echo "<option value='". $child['id'] ."'>--- ".$child['name']." </option>";
	                	}

	                }
				?> 
				

			</select>
		</div>
		</div>
	<!--end categories field-->

	<!--start Country Tags field-->
		<div class="form-group form-group-lg">
		<label class="col-sm-2 control-label">Tags</label>
		<div class="col-sm-10 col-md-6">
			<input type="text" name="tags" class="form-control" placeholder="separate Tags with Comma (,)" >
		</div>
		</div>
	<!--end Country Tags field-->
		
	<!--start submit field-->
	<div class="form-group form-group-lg">
	<div class="col-sm-offset-2 col-sm-10">
		<input type="submit" value="Add Item" class="btn btn-primary btn-sm"/>
		</div>
	</div>
	<!--end submit field-->
</form>

</div>


<?php 


}elseif($do=='insert'){

	//insert items page
	if($_SERVER['REQUEST_METHOD']=='POST'){
		echo "<h1 class='text-center'>Insert Item</h1>";
		echo "<div class='container'>";
		//get variables from the form
		$name=$_POST['name'];
		$description=$_POST['description'];
		$price=$_POST['price'];
		$country_made=$_POST['country_made'];
		$status=$_POST['status'];

		$member=$_POST['member'];
		$category=$_POST['category'];
		$tags=$_POST['tags'];


		//validate the form
		$formerrors=array();
		
		if(empty($name)){
			$formerrors[]='Name can\'t be <strong>empty</strong>';
		}

		if(empty($description)){
			$formerrors[]='Description can\'t be <strong>empty</strong>';
		}

		if(empty($price)){
			$formerrors[]='Price can\'t be <strong>empty</strong>';
		}

		if(empty($country_made)){
			$formerrors[]='Country can\'t be <strong>empty</strong>';
		}

		if($status == 0){
			$formerrors[]='You Must Choose the <strong>Status</strong>';
		}

		if($member == 0){
			$formerrors[]='You Must Choose the <strong>Member</strong>';
		}
		
		if($category == 0){
			$formerrors[]='You Must Choose the <strong>Category</strong>';
		}
		
		
		//loop into errors array and echo it
		foreach($formerrors as $error){
			echo '<div class="alert alert-danger">'.$error.'</div>';
		}
		//check if there's no error proceed the insert operation
		if(empty($formerrors)){
                          
		//insert user info in database
		$stmt=$con->prepare("insert into 
		items (name,description,price,country_made,status,add_date,cat_id,member_id,tags)
		values(:zname, :zdescription, :zprice, :zcountry_made, :zstatus, now(), :zcat_id ,:zmember_id, :ztags)");
		$stmt->execute(array(
		'zname'=>$name,
		'zdescription'=>$description,
		'zprice'=>$price,
		'zcountry_made'=>$country_made,
		'zstatus'=>$status,
		'zcat_id'=>$category,
		'zmember_id'=>$member,
		'ztags'=>$tags
		));
		//echo success message
		$TheMsg = "<div class='alert alert-success'>".$stmt->rowcount().' record inserted</div>';
            
            redirecthome($TheMsg, 'back');
            
            
        }
		}else{
        
        echo "<div class='container'>"; 
		$TheMsg='<div class="alert alert-danger">sorry you cant browse this page directly</div>';
		redirecthome($TheMsg);
        echo "</div>";
	}
	
	echo "</div>";
		
	

}elseif($do == 'edit'){

//check if get request userid is numeric & get the integer value of it
$itemid=isset($_GET['item_id'])&& is_numeric($_GET['item_id'])? intval($_GET['item_id']):0;
//select all data depend on this id
$stmt=$con->prepare("select * from items where item_id = ?");
//execute query
$stmt->execute(array($itemid));
//fetch the data
$item=$stmt->fetch();
//the row count
$count=$stmt->rowCount();
//if there's such id show the form
	if($count > 0){ ?>


<h1 class="text-center">Edit Item</h1>;
	<div class="container">
	<form class="form-horizontal" action="?do=update" method="POST">

		 <input type="hidden" name="itemid" value="<?php echo $itemid ?>"/>

	<!--start name field-->
		<div class="form-group form-group-lg">
		<label class="col-sm-2 control-label">Name</label>
		<div class="col-sm-10 col-md-6">
			<input type="text" name="name" class="form-control" required="required" placeholder="Name of the Item" value="<?php echo $item['name'] ?>" />
		</div>
		</div>
	<!--end name field-->

	<!--start description field-->
		<div class="form-group form-group-lg">
		<label class="col-sm-2 control-label">Description</label>
		<div class="col-sm-10 col-md-6">
			<input type="text" name="description" class="form-control" required="required" placeholder="Description of the Item" value="<?php echo $item['description'] ?>" />
		</div>
		</div>
	<!--end description field-->

	<!--start Price field-->
		<div class="form-group form-group-lg">
		<label class="col-sm-2 control-label">Price</label>
		<div class="col-sm-10 col-md-6">
			<input type="text" name="price" class="form-control" required="required" placeholder="Price of the Item" value="<?php echo $item['price'] ?>" />
		</div>
		</div>
	<!--end Price field-->

	<!--start Country Made field-->
		<div class="form-group form-group-lg">
		<label class="col-sm-2 control-label">Country</label>
		<div class="col-sm-10 col-md-6">
			<input type="text" name="country_made" class="form-control" required="required" placeholder="Country of Made" value="<?php echo $item['country_made'] ?>" />
		</div>
		</div>
	<!--end Country Made field-->

	<!--start Status field-->
		<div class="form-group form-group-lg">
		<label class="col-sm-2 control-label">Status</label>
		<div class="col-sm-10 col-md-6">
			<select name="status">
				<option value="1" <?php if($item['status'] == 1) { echo 'selected'; } ?> >New</option>
				<option value="2" <?php if($item['status'] == 2) { echo 'selected'; } ?> >like New</option>
				<option value="3" <?php if($item['status'] == 3) { echo 'selected'; } ?> >Used</option>
				<option value="4" <?php if($item['status'] == 4) { echo 'selected'; } ?> >Very Old</option>

			</select>
		</div>
		</div>
	<!--end status field-->

	<!--start Members field-->
		<div class="form-group form-group-lg">
		<label class="col-sm-2 control-label">Member</label>
		<div class="col-sm-10 col-md-6">
			<select name="member">
				<?php 
					$stmt=$con->prepare("select * from users");
					$stmt->execute();
	                $users=$stmt->fetchall();
	                foreach ($users as $user) {

	                	echo "<option value='". $user['userid'] ."'"; 
	                	      if($item['member_id'] == $user['userid'] ) { echo 'selected'; } 
	                	      echo " > ".$user['username']." </option>";
	                }
				?> 
				

			</select>
		</div>
		</div>
	<!--end Members field-->

	<!--start categories field-->
		<div class="form-group form-group-lg">
		<label class="col-sm-2 control-label">Category</label>
		<div class="col-sm-10 col-md-6">
			<select name="category">
				<?php 

				    $allCats = getAllFrom("*", "categories","where parent = 0","","id"); 

					//$stmt2=$con->prepare("select * from categories");
					//$stmt2->execute();
	                //$cats=$stmt2->fetchall();

	                foreach ($allCats as $cat) {

	                	echo "<option value='". $cat['id'] ."'";
	                	if($item['cat_id'] == $cat['id'] ) { echo 'selected'; } 
	                	echo "> ".$cat['name']." </option>";
	                }
				?> 
				

			</select>
		</div>
		</div>
	<!--end categories field-->

	<!--start Country Tags field-->
		<div class="form-group form-group-lg">
		<label class="col-sm-2 control-label">Tags</label>
		<div class="col-sm-10 col-md-6">
			<input type="text" name="tags" value="<?php echo $item['tags'] ?>" class="form-control" placeholder="separate Tags with Comma (,)" >
		</div>
		</div>
	<!--end Country Tags field-->
	
		
	<!--start submit field-->
	<div class="form-group form-group-lg">
	<div class="col-sm-offset-2 col-sm-10">
		<input type="submit" value="Save Item" class="btn btn-primary btn-sm"/>
		</div>
	</div>
	<!--end submit field-->
</form>


<!-------------------------- start comments ------------------------------> 
<?php 

$stmt=$con->prepare("select comments.*, users.Username as member
	                     from comments
	                     inner join      
	                     users on users.userid = comments.user_id 
	                     where item_id =?");
	//execute the statment
	$stmt->execute(array($itemid));
	//assign to variable
	$rows=$stmt->fetchall();

	if (!empty($rows)) {
 ?>

<h1 class="text-center">Manage [ <?php echo $item['name'] ?> ] Comments</h1>
<div class="table-responsive">
<table class="main-table text-center table table-bordered ">
	<tr>
		<td>Comment</td>
		<td>User Name</td>
		<td>Added Date</td>
		<td>Control</td>
	<?php
	foreach($rows as $row ){
		echo "<tr>";
		echo "<td>".$row['comment']."</td>";
		echo "<td>".$row['member']."</td>";
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
	?>
	
	
	
	</table>
	</div>

<?php } ?>
	<!-------------------------- end comments ------------------------------> 

</div>

<?php
//if there's no such id show error message
}else{
		 
        
        echo "<div class='container'>"; 
		$TheMsg='<div class = "alert alert-danger"> theres no such id </div>';
		redirecthome($TheMsg);
        echo "</div>";
	}
	
}elseif($do == 'update'){

 //update page
 echo "<h1 class='text-center'>Update item</h1>";
 echo "<div class='container'>";
 if($_SERVER['REQUEST_METHOD'] == 'POST'){
  //get variable from the form
  $id=$_POST['itemid'];
  $name=$_POST['name'];
  $description=$_POST['description'];
  $price=$_POST['price'];
  $country_made=$_POST['country_made'];
  $status=$_POST['status'];
  $member=$_POST['member'];
  $category=$_POST['category'];
  $tags=$_POST['tags'];

  //validate the form
  $formerrors=array();
  
  if(empty($name)){
   $formerrors[]='Name can\'t be <strong>empty</strong>';
  }

  if(empty($description)){
   $formerrors[]='Description can\'t be <strong>empty</strong>';
  }

  if(empty($price)){
   $formerrors[]='Price can\'t be <strong>empty</strong>';
  }

  if(empty($country_made)){
   $formerrors[]='Country can\'t be <strong>empty</strong>';
  }

  if($status == 0){
   $formerrors[]='You Must Choose the <strong>Status</strong>';
  }

  if($member == 0){
   $formerrors[]='You Must Choose the <strong>Member</strong>';
  }
  
  if($category == 0){
   $formerrors[]='You Must Choose the <strong>Category</strong>';
  }
  
  
  //loop into errors array and echo it
  foreach($formerrors as $error){
   echo '<div class="alert alert-danger">'.$error.'</div>';
  }
  

  if(empty($formerrors)){

  
  //update the database with this info
  $stmt=$con->prepare("update items set name=?,description=?,price=?,country_made =?,status=?,cat_id=?,member_id=?,tags=? where item_id=?");
  $stmt->execute(array($name,$description,$price,$country_made,$status,$category,$member,$tags,$id));
  //echo success message
  $TheMsg = "<div class='alert alert-success'>".$stmt->rowcount().' record updated</div>';
            
            redirecthome($TheMsg, 'back');
            
  
    }
 
 }else{
  $TheMsg = '<div class = "alert alert-danger"> sorry you cant browse this page directly </div>';
        
        redirecthome($TheMsg);
 }
 
 echo "</div>";



}elseif($do == 'delete'){

	//delete member page
	echo "<h1 class='text-center'>Delete Item</h1>";
	echo "<div class='container'>";

//check if get request itemid is numeric & get the integer value of it
$itemid=isset($_GET['item_id'])&& is_numeric($_GET['item_id'])? intval($_GET['item_id']):0;
//select all data depend on this id
    
$check = checkitem('item_id','items',$itemid); 
            
if($check > 0){
    
	$stmt=$con->prepare("delete from items where item_id=:zitem");
	$stmt->bindparam(":zitem",$itemid);
	$stmt->execute();
		$TheMsg = "<div class='alert alert-success'>".$stmt->rowcount().' Record Deleted</div>';
        
        redirecthome($TheMsg,'back');
        
}else{
        
		$TheMsg = '<div class="alert alert-danger"> this id is not exist </div>';
        
        redirecthome($TheMsg);
}


	echo '</div>';



}elseif($do == 'approve'){

	echo "<h1 class='text-center'>Approve Item</h1>";
	echo "<div class='container'>";
//check if get request userid is numeric & get the integer value of it
$itemid=isset($_GET['item_id'])&& is_numeric($_GET['item_id'])? intval($_GET['item_id']):0;
        
$check = checkitem('item_id','items',$itemid); 
    
//echo $check; 

    
if($check > 0){
    
	$stmt=$con->prepare("update items set approve = 1 where item_id= ?");
	$stmt->execute(array($itemid));

		$TheMsg = "<div class='alert alert-success'>".$stmt->rowcount().' Record Activate </div>';
        
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

ob_end_flush(); // release the output 

?> 