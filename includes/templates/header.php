<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title><?php gettitle()?></title>
	<link rel="stylesheet" href="<?php echo $css;?>bootstrap.css">
	<link rel="stylesheet" href="<?php echo $css;?>font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo $css;?>jquery-ui.css">
	<link rel="stylesheet" href="<?php echo $css;?>jquery.selectBoxIt.css">
	<link rel="stylesheet" href="<?php echo $css;?>front.css">
</head>
<body>
	<div class="upper-bar"> 
    <div class="container">

          <?php 
          if(isset($_SESSION['user'])){ ?> 

            <img class="my-image img-thumbnail img-circle" src="businessman-avatar.png" alt="" />
            <div class="btn-group my-info">
              <span class="btn dropdown-toggle" data-toggle="dropdown">
                <?php echo $sessionUser; ?> 
                <spna class="caret"></spna>
              </span>
                <ul class="dropdown-menu">
                  <li><a href="profile.php">My Profile</a></li>
                  <li><a href="newad.php">New Item</a></li>
                  <li><a href="profile.php#my-item">My Item</a></li>
                  <li><a href="logout.php">logout</a></li>

                </ul>

            </div>


            <?php 

             

              $userStatus = checkUserStatus($sessionUser); 

              if($userStatus == 1){

                  echo " your need Activiate";

                  //user is not active

              }


          }else{ ?> 
          
             <a href="login.php">
              <span class="pull-right">Login/Signup</span>
             </a>
          <?php } ?> 

    </div> 
  </div>
	
	<nav class="navbar navbar-inverse">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php">Homepage</a>
    </div>
    <div class="collapse navbar-collapse" id="app-nav">
      <ul class="nav navbar-nav navbar-right">
      		<?php 
                //foreach ( getCat() as $cat) {

                  $allCats = getAllFrom("*", "categories", "where parent = 0", "", "id", "ASC"); 

                  foreach ( $allCats as $cat) {
  
    	           echo '<li>
    	           <a href="categories.php?pageid='. $cat['id']  . '">

    	           ' . $cat['name'] . '
    	           

    	           </a></li>'; 

                  }  


      		?> 
	  </ul>
      
        </li>
      </ul>
    </div>
  </div>
</nav>
	
	
	
	