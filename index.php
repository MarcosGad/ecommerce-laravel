<?php
ob_start(); 
session_start();
$pagetitle='Homepage';
include'init.php';
?> 

<div class="container">

	<div class="row">

		<?php 

		    $allItems = getAllFrom('*','items','where approve = 1','','item_id'); 
			foreach ( $allItems as $item ) {
				
				echo'<div class="col-sm-6 col-md-3">'; 
				  echo'<div class="thumbnail item-box">'; 
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
		?>

    </div>

</div>




    
<?php 
include $tpl.'footer.php';
ob_end_flush(); 
?> 