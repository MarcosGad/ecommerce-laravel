<?php
ob_start(); 
session_start();
$pagetitle='Create New Item';
include'init.php';

 if(isset($_SESSION['user'])){

 	if($_SERVER['REQUEST_METHOD'] == 'POST') {

 		$formErrors = array(); 

 		$name = filter_var($_POST['name'], FILTER_SANITIZE_STRING); 
 		$description = filter_var($_POST['description'], FILTER_SANITIZE_STRING); 
 		$price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT); 
 		$country = filter_var($_POST['country_made'], FILTER_SANITIZE_STRING); 
 		$status = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT); 
 		$category = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT); 
 		$tags = filter_var($_POST['tags'], FILTER_SANITIZE_STRING);

 		if(strlen($name) < 4) {

 			$formErrors[] = 'Item Title must be At least 4 Characters'; 
 		}

 		if(strlen($description) < 10) {

 			$formErrors[] = 'Item Description must be At least 10 Characters'; 
 		}

 		if(strlen($country) < 2) {

 			$formErrors[] = 'Item Country must be At least 2 Characters'; 
 		}

 		if(empty($price)) {

 			$formErrors[] = 'Item Price must be not empty'; 
 		}

 		if(empty($status)) {

 			$formErrors[] = 'Item Status must be not empty'; 
 		}

 		if(empty($category)) {

 			$formErrors[] = 'Item Category must be not empty'; 
 		}

 		if(empty($formErrors)){
                          
		//insert user info in database
		$stmt=$con->prepare("insert into 
		items (name,description,price,country_made,status,add_date,cat_id,member_id,tags)
		values(:zname, :zdescription, :zprice, :zcountry_made, :zstatus, now(), :zcat_id ,:zmember, :ztags)");
		$stmt->execute(array(
		'zname'=>$name,
		'zdescription'=>$description,
		'zprice'=>$price,
		'zcountry_made'=>$country,
		'zstatus'=>$status,
		'zcat_id'=>$category,
		'zmember'=>$_SESSION['uid'],
		'ztags'=>$tags
		));
		//echo succes$stmts message

		 if ($stmt) {

			$successMsg = 'Item Added';
			
            }

        }



 	}



?>

<h1 class="text-center"><?php echo $pagetitle; ?></h1>

<div class="Create-ad block">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading"><?php echo $pagetitle; ?></div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-8">
						
						<form class="form-horizontal main-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
							<!--start name field-->
								<div class="form-group form-group-lg">
								<label class="col-sm-3 control-label">Name</label>
								<div class="col-sm-10 col-md-9">
									<input pattern=".{4,}" title="This Field Require At Least 4" type="text" name="name" class="form-control live" placeholder="Name of the Item" data-class=".live-title" required />
								</div>
								</div>
							<!--end name field-->

							<!--start description field-->
								<div class="form-group form-group-lg">
								<label class="col-sm-3 control-label">Description</label>
								<div class="col-sm-10 col-md-9">
									<input pattern=".{10,}" title="This Field Require At Least 10" type="text" name="description" class="form-control live" placeholder="Description of the Item" data-class=".live-desc" required/>
								</div>
								</div>
							<!--end description field-->

							<!--start Price field-->
								<div class="form-group form-group-lg">
								<label class="col-sm-3 control-label">Price</label>
								<div class="col-sm-10 col-md-9">
									<input type="text" name="price" class="form-control live" placeholder="Price of the Item" 
									data-class=".live-price" required/>
								</div>
								</div>
							<!--end Price field-->

							<!--start Country Made field-->
								<div class="form-group form-group-lg">
								<label class="col-sm-3 control-label">Country</label>
								<div class="col-sm-10 col-md-9">
									<input type="text" name="country_made" class="form-control" placeholder="Country of Made" required/>
								</div>
								</div>
							<!--end Country Made field-->

							<!--start Status field-->
								<div class="form-group form-group-lg">
								<label class="col-sm-3 control-label">Status</label>
								<div class="col-sm-10 col-md-9">
									<select name="status" required>
										<option value="0">-----</option>
										<option value="1">New</option>
										<option value="2">like New</option>
										<option value="3">Used</option>
										<option value="4">Very Old</option>

									</select>
								</div>
								</div>
							<!--end status field-->


							<!--start categories field-->
								<div class="form-group form-group-lg">
								<label class="col-sm-3 control-label">Category</label>
								<div class="col-sm-10 col-md-9">
									<select name="category" required>
										<option value="0">-----</option>
										<?php 
											$cats = getAllFrom('*','categories','','','id'); 
											//$stmt2=$con->prepare("select * from categories");
											//$stmt2->execute();
							                //$cats=$stmt2->fetchall();
							                foreach ($cats as $cat) {

							                	echo "<option value='". $cat['id'] ."'> ".$cat['name']." </option>";
							                }
										?> 
										

									</select>
								</div>
								</div>
							<!--end categories field-->

							<!--start Country Tags field-->
							<div class="form-group form-group-lg">
							<label class="col-sm-3 control-label">Tags</label>
							<div class="col-sm-10 col-md-9">
								<input type="text" name="tags" class="form-control" placeholder="separate Tags with Comma (,)" >
							</div>
							</div>
			              <!--end Country Tags field-->
								
							<!--start submit field-->
							<div class="form-group form-group-lg">
							<div class="col-sm-offset-3 col-sm-9">
								<input type="submit" value="Add Item" class="btn btn-primary btn-sm"/>
								</div>
							</div>
							<!--end submit field-->
						</form>

					</div>
					<div class="col-md-4">
					   <div class="thumbnail item-box live-preview">
					  		<span class="price"> 
					  			$<span class="live-price">0</span>
					  		</span>
					  		<img class="img-responsive" src="businessman-avatar.png" alt="" />
					  		<div class="caption">
						  		<h3 class="live-title"> Title </h3>
						  		<p class="live-desc"> Description </p>
					  		</div>
					    </div>
					</div>

				</div>
				<!-- start looping errors --> 
				<?php 
					if(!empty($formErrors)) {

						foreach ($formErrors as $error ) {

							echo '<div class="alert alert-danger">' . $error  .'</div>';

						}

					}

					if(isset($successMsg)) {

		 				echo '<div class="alert alert-success">' . $successMsg . '</div>' ;
		 				
		 			    }

				?> 
				<!-- end looping errors -->

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
ob_end_flush();
?> 