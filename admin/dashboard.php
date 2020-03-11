<?php
	ob_start(); // OutBut Buffering Starting

	session_start();

	if (isset($_SESSION['Username'])) {

		$pageTitle='Dashboard';

		include 'init.php';
		/* Start Dashboard Page */
		$latestUsers = 5;
		$theLatestUsers = getLatest("*","users WHERE ReqStatus = 1","UserID",$latestUsers);
		$latestitems = 5;
		$thelatestitems = getLatest("*","items","ItemID",$latestitems)
		?>

		<div class="container home-stats text-center">
			<h1 class="text-center">Dashboard</h1>
			<div class="row">
				<div class="col-md-3">
					<div class="stat st-members">
						Total Members
						<span><a href="members.php" target="_blank"><?php echo countItems('UserID','users'); ?></a></span>
					</div>
				</div>
				<div class="col-md-3">
					<div class="stat st-pending">
						Pending Members
						<span><a href="members.php?do=Manage&page=Pending"><?php echo countItems('UserID','users WHERE ReqStatus = 0'); ?></a></span>
					</div>
				</div>
				<div class="col-md-3">
					<div class="stat st-items">
						Total Items
						<span><a href="items.php" target="_blank"><?php echo countItems('ItemID','items'); ?></a></span>
					</div>
				</div>
				<div class="col-md-3">
					<div class="stat st-comments">
						Total Comments
						<span><a href="comments.php" target="_blank"><?php echo countItems('CID','comments'); ?></a></span>
					</div>
				</div>
			</div>
		</div>
		<div class="container latest">
			<div class="row">
				<div class="col-sm-6">
					<div class="card card-default">
						<div class="card-header">
							<i class="fa fa-users"> Latest <?php echo $latestUsers ?> Registered Users</i>
						</div>
						<div class="card-body"> 
							<ul class="list-unstyled latest-users">
								<?php 
								foreach ($theLatestUsers as $user) {
			
									echo "<li>" . $user['Username'] . "<a href='members.php?do=Edit&userid=". $user['UserID'] ."'><span class='btn btn-success'>Edit <i class='fa fa-edit'></i></span></a></li>";

								}?>
							</ul>
						</div>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="card card-default">
						<div class="card-header">
							<i class="fa fa-tag"> Latest Items</i>
						</div>
						<div class="card-body">
							<ul class="list-unstyled latest-users">
								<?php 
								foreach ($thelatestitems as $item) {
			
									echo "<li>" . $item['Name'] . "<a href='items.php?do=Edit&itemid=". $item['ItemID'] ."'><span class='btn btn-success'>Edit <i class='fa fa-edit'></i></span></a></li>";

								}?>
							</ul>
						</div>
					</div>
				</div>
			</div>
		<div class="row">
			<div class="col-sm-6">
				<div class="card card-default">
					<div class="card-header">
						<i class="fa fa-comments"> Latest <?php echo $latestUsers ?> Comments</i>
					</div>
					<div class="card-body">
					<?php

						$stmt = $con->prepare("SELECT
														comments.*, users.Username AS Member
												   FROM
												   	   comments
												   	INNER JOIN
												   		users
												   	ON
												   		users.UserID = comments.UserID
												   	ORDER BY
												   		CID DESC
							  						");
							$stmt->execute();
							// assign to var
							$comments = $stmt->fetchAll();

							foreach ($comments as $comment) {
								
								echo "<div class='comment-box'>";
									echo "<span class='member-name'>" . $comment['Member'] . "</span>";
									echo "<p class='member-comm'>" . $comment['Comment'] . "</p>";
								echo "</div>";

							}

					?>
					</div>
				</div>
			</div>
		</div>
		</div>

		<?php

		/* End Dashboard Page */

		include $tpl . 'footer.php';

	} else {

		header('Location: index.php');

		exit();

	}

	ob_end_flush();

?>