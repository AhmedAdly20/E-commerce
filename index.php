<?php 
	ob_start();
	session_start();

	$pageTitle = 'Homepage';

	include 'init.php';

?>

<div class="container">
	<div class="row">
		<?php
		foreach (getAll("items WHERE Approve = 1") as $item) {
			echo '<div class="col-sm-6 col-md-4">';
				echo '<div class="card item-box">';
					echo '<span class="price-tag">'. $item['Price'] .'</span>';
					echo '<img class="img-fluid" src="sams10.png" alt="" />';
					echo '<div class="caption">';
						echo '<h3><a href="items.php?itemid='.$item['ItemID'].'">' . $item['Name'] . '</a></h3>';
						echo '<p>' . $item['Description'] . '</p>';				
					echo '</div>';
				echo '</div>';
			echo '</div>';
		}
		?>
	</div>
</div>



<?php

	include 'includes/templates/footer.php';

	ob_end_flush();
?>