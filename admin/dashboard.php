<?php

ob_start(); 

session_start();
if(isset($_SESSION['username'])){
	$pagetitle='dashboard';
	include 'init.php';
    
    /* start dashboard page */ 


    
    ?> 
<div class="container home-stats text-center">
    <h1>Dashboard</h1>
    <div class="row">
        <div class="col-md-3">
        <div class="stat st-members">
            <i class="fa fa-users"></i>
        <div class="info">
             Total Members
             <span><a href="members.php"><?php echo countItems('userid', 'users') ?> </a></span></div>
        </div>
        </div>
        
        <div class="col-md-3">
        <div class="stat st-pending">
            <i class="fa fa-user-plus"></i>
            <div class="info">
                Pending Members
                <span><a href="members.php?do=manage&page=pending">
                <?php echo checkitem("regstatus","users", 0) ?> 
                </a></span>
            </div>
        </div>
        </div>
        
        <div class="col-md-3">
        <div class="stat st-items">
            <i class="fa fa-tag"></i>
        <div class="info">
            Total Items
            <span><a href="Items.php"><?php echo countItems('item_id', 'Items') ?> 
        </a></span>
        </div>
        </div>
        </div>
        
         <div class="col-md-3">
        <div class="stat st-comments">
            <i class="fa fa-comments"></i>
            <div class="info">
                Total Comments
                <span><a href="comments.php"><?php echo countItems('c_id', 'comments') ?> 
                 </a></span></div>
          </div>
        </div>
    </div>
</div>


<div class="latest">
<div class="container">
    <div class="row">
        <div class="col-sm-6">
            <div class="panel panel-default">

                <?php $numUsers = 5; ?> 

                <div class="panel-heading">
                    <i class="fa fa-users"></i> Latest <?php echo $numUsers ?> Registerd Users
                    <span class="toggle-info pull-right">
                        <i class="fa fa-plus fa-lg"></i>
                    </span>
                </div>
                <div class="panel-body">

                        <ul class="list-unstyled latest-users">
                        <?php 

                            $latestUsers=(getLatest("*","users","userid",$numUsers)); 

                            if(! empty($latestUsers)) {

                            foreach ($latestUsers as $user) {
                                
                                echo '<li>'. $user['username']; 
                                echo '<a href="members.php?do=edit&userid=' . $user['userid'] . '">';  
                                echo '<span class="btn btn-success pull-right">'; 
                                echo '<i class="fa fa-edit"></i> Edit'; 

                                  if($user['regstatus'] == 0){
            
                                        echo "<a href='members.php?do=Activate&userid=".$user['userid']."'class='btn btn-info pull-right activate'><i class='fa fa-info'></i> Activate </a>"; 
                                       }

                                echo'</span>';
                                echo'</a>';
                                echo'</li>';
                            }
                        } else {

                            echo "There's No Members To show";
                        }
                                    
                        ?> 
                        </ul>    
                </div>
            </div>
        </div>
        
        <div class="col-sm-6">
            <div class="panel panel-default">

                  <?php $numItems = 3; ?> 

                <div class="panel-heading">
                    <i class="fa fa-tag"></i> Latest <?php echo $numItems ?>  Items 
                     <span class="toggle-info pull-right">
                        <i class="fa fa-plus fa-lg"></i>
                    </span>
                </div>

                  <div class="panel-body">

                        <ul class="list-unstyled latest-users">
                        <?php 

                          $latestItems=(getLatest("*","items","item_id",$numItems));

                          if(! empty($latestItems)){
                            foreach ($latestItems as $item) {
                                
                                echo '<li>'. $item['name']; 
                                echo '<a href="items.php?do=edit&item_id=' . $item['item_id'] . '">'; 
                                echo '<span class="btn btn-success pull-right">'; 
                                echo '<i class="fa fa-edit"></i> Edit'; 

                                  if($item['approve'] == 0){
                                        
                                         echo "<a href='items.php?do=approve&item_id=".$item['item_id']."'class='btn btn-info pull-right activate'><i class='fa fa-check'></i> Approve </a>"; 
                                        
                                       }

                                echo'</span>';
                                echo'</a>';
                                echo'</li>';
                            }
                        }else {

                            echo "There's No Items To show";
                        }    
                        ?> 
                        </ul>    
                </div>
            </div>
        </div>
    </div>

    <!-- start latest comments --> 

    <div class="row">
        <div class="col-sm-6">
            <div class="panel panel-default">

                    
                <?php $uumComments = 4; ?> 

                <div class="panel-heading">
                    <i class="fa fa-comments-o"></i> Latest <?php echo $uumComments ?>  Comments
                    <span class="toggle-info pull-right">
                        <i class="fa fa-plus fa-lg"></i>
                    </span>
                </div>
                <div class="panel-body">
                    <?php 
                    $stmt=$con->prepare("select comments.*, users.Username as Member
                         from comments
                         inner join      
                         users on users.userid = comments.user_id
                         order by c_id DESC
                         limit $uumComments");
                           $stmt->execute();
                           $comments=$stmt->fetchall();

                           if (! empty($comments)){

                           foreach ($comments as $comment) {

                                echo '<div class="comment-box">'; 
                                    echo '<span class="member-n">
                                    <a href="members.php?do=edit&userid='. $comment['user_id'] .'">
                                        ' . $comment['Member'] . '</a></span>'; 

                                    echo '<p class="member-c">' . $comment['comment'] . '</p>';
                                echo '</div>';

                           }
                       }else {

                         echo "There's No Comments To Show";
                       }
                    ?> 
                    
                </div>
            </div>
        </div>
        
      
    </div>
    <!-- end latest comments --> 
</div>
</div>












    

    
    <?php 
    /* end dashboard page */ 

	include $tpl.'footer.php';
}else{
	//echo 'you are not authorized to view this page';
	header('location:index.php');
	exit();
}

ob_end_flush(); 