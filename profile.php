<?php 
	ob_start();
	session_start();

	include 'connect.php';

	if(isset($_SESSION['user'])) {

		$getUser = $con->prepare("SELECT * FROM users WHERE Username = ?");

		$getUser->execute(array($_SESSION['user']));

		$info = $getUser->fetch();

		$pageTitle = $info['Username'];

		include 'init.php';
		
?>
<h1 class="text-center">My Profile</h1>
<div class="information block">
	<div class="container">
		<div class="card border-primary">
			<div class="card-header">My Information</div>
			<div class="card-body">
				<ul class="list-unstyled">
					<li>
						<i class="fa fa-unlock-alt fa-fw"></i>
						<span>Name</span>: <?php echo $info['Username']; ?>
					</li>
					<li>
						<i class="fa fa-envelope-o fa-fw"></i>
						<span>E-Mail</span>: <?php echo $info['Email']; ?>
					</li>
					<li>
						<i class="fa fa-user fa-fw"></i>
						<span>FullName</span>: <?php echo $info['FullName']; ?>
					</li>
					<li>
						<i class="fa fa-calendar fa-fw"></i>
						<span>Registerd Date</span>: <?php echo $info['DOR']; ?>
					</li>
					<li>
						<i class="fa fa-tags fa-fw"></i>
						<span>Favourite Category</span>: 
					</li>
				</ul>
				<a style="color: #FFF;" class="btn btn-primary">Edit Informations</a>
			</div>
		</div>
	</div>
</div>

<div id="myads" class="ads block">
	<div class="container">
		<div class="card border-primary">
			<div class="card-header">My Advertisments</div>
			<div class="card-body">
					<?php
					if(!empty(getItems2("MemberID",$info['UserID']))) {
						echo '<div class="row">';
						foreach (getItems2("MemberID",$info['UserID']) as $item) {
							echo '<div class="col-sm-6 col-md-4">';
								echo '<div class="card item-box">';
								if($item['Approve']==0){echo "<span class='not-approved'>Not Approved</span>";}
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
						echo "</div>";
					}else{
						echo "<p>You Have not Shared Any Ads Yet, Create <a href='newad.php'>New Ad</a></p>";
					}
					?>
			</div>
		</div>
	</div>
</div>

<div class="my-comments block">
	<div class="container">
		<div class="card border-primary">
			<div class="card-header">Latest Comments</div>
			<div class="card-body">
			<?php
				$stmt = $con->prepare("SELECT Comment FROM comments WHERE UserID = ?");

				$stmt->execute(array($info['UserID']));
							
				$comments = $stmt->fetchAll();

				if (!empty($comments)) {
					foreach ($comments as $comment) {
						echo "<p>" . $comment['Comment'] . "</p>";
					}
				} else{
					echo "There Is No Recent Comments From You";
				}
			?>
			</div>
		</div>
	</div>
</div>

<?php 

	} else{
		header('Location: login.php');
		exit();
	}

	include 'includes/templates/footer.php';

	ob_end_flush();
?>