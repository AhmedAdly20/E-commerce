<?php
ob_start();
session_start();
include 'connect.php';
$stmt=$con->prepare("SELECT * FROM categories WHERE ID = ?");
 $stmt->execute(array($_GET['pageid']));
 $category=$stmt->fetch();
 $pageTitle = $category['Name'];
 include 'init.php'; 
 ?>
<div class="container">
	<h1 class="text-center"><?php echo $category['Name'] ?></h1>
	<div class="row">
		<?php
		foreach (getItems3($_GET['pageid']) as $item) {
			echo '<div class="col-sm-6 col-md-4">';
				echo '<div class="card item-box">';
					echo '<span class="price-tag">'. $item['Price'] .'</span>';
					echo '<img class="img-fluid" src="sams10.png" alt="" />';
					echo '<div class="caption">';
						echo '<h3><a href="items.php?itemid='.$item['ItemID'].'">' . $item['Name'] . '</a></h3>';
						echo '<p>' . $item['Description'] . '</p>';		
						echo '<div class="date">' . $item['AddDate'] . '</div>';			
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