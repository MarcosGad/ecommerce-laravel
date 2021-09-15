<?php
 session_start();
 $pagetitle='Tage';
 include'init.php';    
?> 

<div class="container">

	

	<div class="row">


		<?php 
		
			
			if (isset($_GET['name'])){

				$tag  = $_GET['name']; 
				echo "<h1 class='text-center'>" .   $tag  . "</h1>"; 


		    $tagItems = getAllFrom("*","items","where tags like '%$tag%'","and approve =1","item_id"); 

			foreach ($tagItems as $item ) {
				
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

			echo 'You Must Enter tag name'; 
		}
		?>

    </div>

</div>






<?php 
include $tpl.'footer.php';