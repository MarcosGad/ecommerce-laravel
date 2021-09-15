<?php
 session_start();
 $pagetitle='Cat';
 include'init.php';    
?> 

<div class="container">

	<h1 class="text-center">Show Category Items</h1>

	<div class="row">


		<?php 
		
			//$Category=isset($_GET['pageid'])&& is_numeric($_GET['pageid'])? intval($_GET['pageid']):0;

			if (isset($_GET['pageid'])&& is_numeric($_GET['pageid'])){

			   $Category = intval($_GET['pageid']); 

		    $allItems = getAllFrom("*","items","where cat_id = {$Category} ","and approve =1","item_id"); 

			foreach ( $allItems as $item ) {
				
				echo'<div class="col-sm-6 col-md-3">'; 
				  echo'<div class="thumbnail item-box">'; 
				  		echo '<span class="price">' . $item['price'] . '</span>';
				  		echo '<img class="img-responsive" src="businessman-avatar.png" alt="" />';
				  		echo '<div class="caption">'; 
				  			echo '<h3><a href="items.php?itemid=' . $item['item_id'] . '">' . $item['name'] . '</a></h3>'; 
				  			echo '<p>'. $item['description'] . '</p>';
				  			echo '<div class="date">'. $item['add_date'] . '</div>';

				  		echo '</div>'; 
				  echo '</div>'; 
			  echo '</div>';
			}

		}else {

			echo 'You Must Add page ID'; 
		}
		?>

    </div>

</div>






<?php 
include $tpl.'footer.php';