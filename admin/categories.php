<?php
/*
========================================================
==categories page
========================================================
*/

ob_start(); 

session_start();

$pagetitle='Categories';

if(isset($_SESSION['username'])){

   include 'init.php';

   $do=isset($_GET['do'])?$_GET['do']:'manage';


if($do == 'manage'){

	$sort = 'asc'; 
	$sort_array = array('asc','desc');
	if(isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)){

		$sort = $_GET['sort']; 

	}
	
	$stmt2=$con->prepare("select * from categories where parent =  0 order by ordering $sort");
	//execute the statment
	$stmt2->execute();
	//assign to variable
	$cats=$stmt2->fetchall(); ?> 

    <h1 class="text-center">Manage Categories</h1>
    <div class="container categories">
    	<div class="panel panel-default">
    		<div class="panel-heading">
    		<i class="fa fa-edit"></i> Manage Categories
    		<div class="option pull-right">
    			<i class="fa fa-sort"></i> ordering: [
    			<a class="<?php if($sort == 'asc') { echo 'active'; } ?>" href="?sort=asc">Asc</a> |
    			<a class="<?php if($sort == 'desc') { echo 'active'; } ?>" href="?sort=desc">Desc</a> ]
    			<i class="fa fa-eye"></i> View: [
    			<span class="active" data-view="full">Full</span> | 
    			<span data-view="classic">Classic</span> ]
    		</div>
    	    </div>
    		<div class="panel-body">
    			<?php 
    			foreach ($cats as $cat ) {

    				echo "<div class='cat'>";

    		echo "<div class='hidden-buttons'>"; 
    		echo "<a href='categories.php?do=edit&catid=" . $cat['id'] . "' class='btn btn-xs btn-primary'><i class='fa fa-edit'></i> Edit </a>";
    		echo "<a href='categories.php?do=delete&catid=" . $cat['id'] . "' class='confirm btn btn-xs btn-danger'><i class='fa fa-close'></i> Delete </a>"; 
    		echo "</div>"; 
	    				echo "<h3>" . $cat['name'].'</h3>';

	    		echo "<div class='full-view'>"; 

	    				echo "<p>"; if($cat['description'] == '') { echo 'this Categories has no description'; }else {echo $cat['description']; } echo "</p>";

	    				if($cat['visibility'] == 1) { echo '<span class="Visibility"><i class="fa fa-eye"></i>Hidden</span>'; };

	    				if($cat['allow_comment'] == 1) { echo '<span class="commenting"><i class="fa fa-close"></i>Comment Disble</span>'; };

	    				if($cat['allow_ads'] == 1) { echo '<span class="advertises"><i class="fa fa-close"></i>Ads Disble</span>'; };

	    		echo "</div>"; 


    				// Get child catgories 

    			  $childCats = getAllFrom("*", "categories", "where parent = {$cat['id']}", "", "id", "ASC"); 

    			  if(!empty($childCats)){

    			  echo "<h4 class='child-head'>Child Categories</h4>";

    			  echo "<ul class='list-unstyled child-cats'>";

                  foreach (  $childCats as $c) {

    	           echo "<li class='c-link'>
    	           <a href='categories.php?do=edit&catid=" . $c['id'] . "'>" . $c['name'] . "</a>
    	           <a href='categories.php?do=delete&catid=" . $c['id'] . "' class='show-delete confirm'> Delete </a>
    	           </li>"; 

                  }

                  echo "</ul>"; 
                  }  

    			  echo "</div>";

                  echo "<hr>";

    			}

    			?> 
    		</div>
    	</div>

    	<a class="add-category btn btn-primary" href="categories.php?do=add"><i class="fa fa-plus"></i> Add New </a>

    </div>


<?php 

}elseif($do == 'add'){ ?> 


<h1 class="text-center">Add New Categories</h1>;
	<div class="container">
	<form class="form-horizontal" action="?do=insert" method="POST">
		<!--start name field-->
		<div class="form-group form-group-lg">
		<label class="col-sm-2 control-label">Name</label>
		<div class="col-sm-10 col-md-6">
			<input type="text" name="name" class="form-control" autocomplete="off" required="required" placeholder="Name of the Category" />
		</div>
		</div>
		<!--end name field-->
		<!--start description field-->
		<div class="form-group form-group-lg">
		<label class="col-sm-2 control-label">Description</label>
			<div class="col-sm-10 col-md-6">
			<input type="text" name="description" class="form-control" placeholder="describe the Category" />
			</div>
		</div>
		<!--end description field-->
		<!--start ordering field-->
	<div class="form-group form-group-lg">
		<label class="col-sm-2 control-label">Ordering</label>
		<div class="col-sm-10 col-md-6">
			<input type="text" name="ordering" class="form-control" placeholder="unmber to Arrange the Category"/>
		</div>
	</div>
	<!--end ordering field-->
	<!-- start parent (category type) --> 
    <div class="form-group form-group-lg">
		<label class="col-sm-2 control-label"> Parent? </label>
		<div class="col-sm-10 col-md-6">
			<select name="parent">
				<option value="0">None</option>
				<?php 
					$allCats = getAllFrom("*", "categories", "where parent = 0", "", "id");
					foreach($allCats as $cat){

						echo "<option value='" . $cat['id'] . "'>" . $cat['name'] . "</option>"; 
					}
				?>
			</select>
		</div>
	</div>

	<!-- end parent (category type) --> 
	<!--start visibility field-->
	<div class="form-group form-group-lg">
		<label class="col-sm-2 control-label">visible</label>
		<div class="col-sm-10 col-md-6">
			<div>
				<input id="v-yes" type="radio" name="visibility" value="0" checked />
				<label for="v-yes">Yes</label>
			</div>
			<div>
				<input id="v-no" type="radio" name="visibility" value="1" />
				<label for="v-no">No</label>
			</div>
		</div>
	</div>
	<!--end visibility field-->
	<!--start allow_comment field-->
	<div class="form-group form-group-lg">
		<label class="col-sm-2 control-label">Allow Commenting</label>
		<div class="col-sm-10 col-md-6">
			<div>
				<input id="com-yes" type="radio" name="allow_comment" value="0" checked />
				<label for="com-yes">Yes</label>
			</div>
			<div>
				<input id="com-no" type="radio" name="allow_comment" value="1" />
				<label for="com-no">No</label>
			</div>
		</div>
	</div>
	<!--end allow_comment field-->
	<!--start allow_ads field-->
	<div class="form-group form-group-lg">
		<label class="col-sm-2 control-label">Allow Ads</label>
		<div class="col-sm-10 col-md-6">
			<div>
				<input id="ads-yes" type="radio" name="allow_ads" value="0" checked />
				<label for="ads-yes">Yes</label>
			</div>
			<div>
				<input id="ads-no" type="radio" name="allow_ads" value="1" />
				<label for="ads-no">No</label>
			</div>
		</div>
	</div>
	<!--end allow_ads field-->
	<!--start submit field-->
	<div class="form-group form-group-lg">
	<div class="col-sm-offset-2 col-sm-10">
		<input type="submit" value="Add Category" class="btn btn-primary btn-lg"/>
		</div>
	</div>
	<!--end submit field-->
</form>

</div>


<?php 
}elseif($do=='insert'){
	

	if($_SERVER['REQUEST_METHOD']=='POST'){

		echo "<h1 class='text-center'>Insert Category</h1>";
		echo "<div class='container'>";
		//get variables from the form

		$name=$_POST['name'];
		$description=$_POST['description'];
		$parent=$_POST['parent'];
		$ordering=$_POST['ordering'];
		$visibility=$_POST['visibility'];
		$allow_comment=$_POST['allow_comment'];
		$allow_ads=$_POST['allow_ads'];

		
		    
        // check if Category exist in database 
            
        $check=checkitem("name","categories",$name);
            
        if ($check == 1){
            
            $TheMsg = '<div class="alert alert-danger"> Sorry this Category is exist </div>'; 
            
            redirecthome($TheMsg, 'back');
            
        }else {
              
		//insert Category info in database
		$stmt=$con->prepare("insert into 
		categories(name,description,parent,ordering,visibility,allow_comment,allow_ads)
		values(:zname, :zdescription, :zparent, :zordering, :zvisibility, :zallow_comment, :zallow_ads)");

		$stmt->execute(array(
		'zname'=>$name,
		'zdescription'=>$description,
		'zparent'=>$parent,
		'zordering'=>$ordering,
		'zvisibility'=>$visibility,
		'zallow_comment'=>$allow_comment,
		'zallow_ads'=>$allow_ads
		));
		//echo success message
		$TheMsg = "<div class='alert alert-success'>".$stmt->rowcount().' record inserted</div>';
            
            redirecthome($TheMsg, 'back');
            
		} // check if user
            
        
		}else{
        
        echo "<div class='container'>"; 
		$TheMsg='<div class="alert alert-danger">sorry you cant browse this page directly</div>';
		redirecthome($TheMsg, 'back');
        echo "</div>";
	}
	
	echo "</div>";
		
	


}elseif($do == 'edit'){

	$catid=isset($_GET['catid'])&& is_numeric($_GET['catid'])? intval($_GET['catid']):0;

//select all data depend on this id
$stmt=$con->prepare("select * from categories where id=?");
//execute query
$stmt->execute(array($catid));
//fetch the data
$cat=$stmt->fetch();
//the row count
$count=$stmt->rowCount();
//if there's such id show the form
	if($stmt->rowCount()>0){


?>
	
<h1 class="text-center">Edit Categories</h1>;
	<div class="container">
	<form class="form-horizontal" action="?do=update" method="POST">

        	<input type="hidden" name="catid" value="<?php echo $catid ?>"/>

		<!--start name field-->
		<div class="form-group form-group-lg">
		<label class="col-sm-2 control-label">Name</label>
		<div class="col-sm-10 col-md-6">
			<input type="text" name="name" class="form-control" required="required" placeholder="Name of the Category" value="<?php echo $cat['name'] ?>" />
		</div>
		</div>
		<!--end name field-->
		<!--start description field-->
		<div class="form-group form-group-lg">
		<label class="col-sm-2 control-label">Description</label>
			<div class="col-sm-10 col-md-6">
			<input type="text" name="description" class="form-control" placeholder="describe the Category" value="<?php echo $cat['description'] ?>"  />
			</div>
		</div>
		<!--end description field-->
		<!--start ordering field-->
	<div class="form-group form-group-lg">
		<label class="col-sm-2 control-label">Ordering</label>
		<div class="col-sm-10 col-md-6">
			<input type="text" name="ordering" class="form-control" placeholder="unmber to Arrange the Category" value="<?php echo $cat['ordering'] ?>">
		</div>
	</div>
	<!--end ordering field-->
	<!-- start parent (category type) --> 
    <div class="form-group form-group-lg">
		<label class="col-sm-2 control-label"> Parent? </label>
		<div class="col-sm-10 col-md-6">
			<select name="parent">
				<option value="0">None</option>
				<?php 
					$allCats = getAllFrom("*", "categories", "where parent = 0", "", "id");
					foreach($allCats as $c){

						echo "<option value='" . $c['id'] . "'";

						if($cat['parent'] == $c['id']) {echo 'selected';}

						echo ">" . $c['name'] . "</option>"; 
					}
				?>
			</select>
		</div>
	</div>

	<!-- end parent (category type) --> 
	<!--start visibility field-->
	<div class="form-group form-group-lg">
		<label class="col-sm-2 control-label">visible</label>
		<div class="col-sm-10 col-md-6">
			<div>
				<input id="v-yes" type="radio" name="visibility" value="0" <?php if($cat['visibility'] == 0) { echo 'checked'; } ?> />
				<label for="v-yes">Yes</label>
			</div>
			<div>
				<input id="v-no" type="radio" name="visibility" value="1" <?php if($cat['visibility'] == 1) { echo 'checked'; } ?>/>
				<label for="v-no">No</label>
			</div>
		</div>
	</div>
	<!--end visibility field-->
	<!--start allow_comment field-->
	<div class="form-group form-group-lg">
		<label class="col-sm-2 control-label">Allow Commenting</label>
		<div class="col-sm-10 col-md-6">
			<div>
				<input id="com-yes" type="radio" name="allow_comment" value="0" <?php if($cat['allow_comment'] == 0) { echo 'checked'; } ?> />
				<label for="com-yes">Yes</label>
			</div>
			<div>
				<input id="com-no" type="radio" name="allow_comment" value="1" <?php if($cat['allow_comment'] == 1) { echo 'checked'; } ?> />
				<label for="com-no">No</label>
			</div>
		</div>
	</div>
	<!--end allow_comment field-->
	<!--start allow_ads field-->
	<div class="form-group form-group-lg">
		<label class="col-sm-2 control-label">Allow Ads</label>
		<div class="col-sm-10 col-md-6">
			<div>
				<input id="ads-yes" type="radio" name="allow_ads" value="0" <?php if($cat['allow_ads'] == 0) { echo 'checked'; } ?> />
				<label for="ads-yes">Yes</label>
			</div>
			<div>
				<input id="ads-no" type="radio" name="allow_ads" value="1"  <?php if($cat['allow_ads'] == 1) { echo 'checked'; } ?> />
				<label for="ads-no">No</label>
			</div>
		</div>
	</div>
	<!--end allow_ads field-->
	<!--start submit field-->
	<div class="form-group form-group-lg">
	<div class="col-sm-offset-2 col-sm-10">
		<input type="submit" value="Save" class="btn btn-primary btn-lg"/>
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
	
	
}elseif($do == 'update'){

	//update page
	echo "<h1 class='text-center'>Update Categories</h1>";
	echo "<div class='container'>";
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		//get variable from the form
		$id=$_POST['catid'];
		$name=$_POST['name'];
		$description=$_POST['description'];
		$ordering=$_POST['ordering'];
		$parent=$_POST['parent'];
		$visibility=$_POST['visibility'];
		$allow_comment=$_POST['allow_comment'];
		$allow_ads=$_POST['allow_ads'];
		//condition ? true:false
		
		
		//update the database with this info
		$stmt=$con->prepare("update categories set name=?,description=?,ordering=?,parent=?,visibility=?,allow_comment=?,allow_ads=? where id=?");
		$stmt->execute(array($name,$description,$ordering,$parent,$visibility,$allow_comment,$allow_ads,$id));
		//echo success message
		$TheMsg = "<div class='alert alert-success'>".$stmt->rowcount().' record updated</div>';
            
            redirecthome($TheMsg, 'back' , 5);
            
		
        
	
	}else{
		$TheMsg = '<div class = "alert alert-danger"> sorry you cant browse this page directly </div>';
        
        redirecthome($TheMsg);
	}
	
	echo "</div>";

}elseif($do == 'delete'){

	echo "<h1 class='text-center'>Delete Category</h1>";
	echo "<div class='container'>";
//check if get request Catid is numeric & get the integer value of it
$catid=isset($_GET['catid'])&& is_numeric($_GET['catid'])? intval($_GET['catid']):0;
//select all data depend on this id
    
    
$check = checkitem('id','categories',$catid); 
    
    
    
if($check > 0){
    
	$stmt=$con->prepare("delete from categories where id = :zid");
	$stmt->bindparam(":zid",$catid);
	$stmt->execute();
		$TheMsg = "<div class='alert alert-success'>".$stmt->rowcount().' Record Deleted</div>';
        
        redirecthome($TheMsg,'back');
        
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