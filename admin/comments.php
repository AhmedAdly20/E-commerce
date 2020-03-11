<?php

	/*
	===================================================
	==Manage Comments Page
	==You Can Add Delete Comments
	===================================================
	*/
	session_start();

	$pageTitle = 'Comments';

	if (isset($_SESSION['Username'])) {
		include 'init.php';

		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

		if($do == 'Manage') { 

			//Select All Users exept admins

			$stmt = $con->prepare("SELECT
										comments.*, items.Name AS Item , users.Username AS Member
								   FROM
								   	   comments
								   INNER JOIN
								   		items
								   	ON
								   		items.ItemID = comments.ItemID
								   	INNER JOIN
								   		users
								   	ON
								   		users.UserID = comments.UserID
			  						");
			$stmt->execute();
			// assign to var
			$rows = $stmt->fetchAll();


			?>

			<h1 class="text-center">Manage Comments</h1>
				<div class="container">
					<div class="table-responsive">
						<table class="main-table text-center table table-bordered">
							<tr>
								<td>#ID</td>
								<td>#Comment</td>
								<td>#Item Name</td>
								<td>#UserName</td>
								<td>#Added Date</td>
								<td>#Control</td>
							</tr>
							<?php

							foreach ($rows as $row ) {
								echo "<tr>";
									echo "<td>" . $row['CID'] . "</td>";
									echo "<td>" . $row['Comment'] . "</td>";
									echo "<td>" . $row['Item'] . "</td>";
									echo "<td>" . $row['Member'] . "</td>";
									echo "<td>" . $row['CommentDate'] . "</td>";
									echo "<td>
										<a href='comments.php?do=Edit&comid=". $row['CID'] ."' class='btn btn-success'>Edit  <i class='fa fa-edit'></i></a> 
										<a href='comments.php?do=Delete&comid=". $row['CID'] ."' class='btn btn-danger confirm'>Delete <i class='fa fa-close'></i></a>";
										if ($row['Status'] == 0) {

											echo "<a href='comments.php?do=Approve&comid=". $row['CID'] ."' class='btn btn-info activate'>Approve <i class='fa fa-check'></i></a>";

										}
										echo "</td>";
								echo "</tr>";
							}

							?>
						</table>
					</div>
				</div>
		<?php }

		elseif ($do == 'Edit') {

			$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

			$stmt = $con->prepare("SELECT
									*
									FROM
										comments
									WHERE
										CID = ?
									");

			$stmt->execute(array($comid));

			$row = $stmt->fetch();

			$count = $stmt->rowCount();

			if ($stmt->rowCount() > 0) { ?>

				<h1 class="text-center">Edit Comments</h1>
				<div class="container">
					<form class="form-horizontal" action="?do=Update" method="POST">
						<input type="hidden" name="comid" value="<?php echo $comid ?>">
						<div class="form-group">
							<label class="col-sm-2 control-label">Comment</label>
							<div class="col-sm-10">
								<textarea class="form-control" name="comment"> <?php echo $row['Comment']; ?> </textarea>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<input type="submit" value="Save" class="btn btn-primary">
							</div>
						</div>
					</form>
				</div>

		<?php 
		} else {
			echo "<div class='container'>";
			$msg = "<div class='text-center alert alert-danger'>This Is Wrong ID</div>";
			redirect($msg,'back',5);
			echo "</div>";
		}
	} elseif ($do == 'Update') {
		
		echo "<h1 class='text-center'>Update Comments</h1>";
		echo '<div class="container">';

		if($_SERVER['REQUEST_METHOD'] == 'POST') {

			// get variables from the form

			$comid 		= 		$_POST['comid'];
			$comment 	= 		$_POST['comment'];

			// Validate The Form

			// update database

			$stmt = $con->prepare("UPDATE comments SET Comment = ? WHERE CID = ?");
			$stmt->execute(array($comment,$comid));

			// Echo Success Message

			echo  "<div class='alert alert-success text-center'>" . $stmt->rowCount() . " " . 'Record Updated </div>';
			redirect("",'back',5);

		} else {
			$errorMsg = "<div class='alert alert-danger text-center'>SORRY YOU CANT Browse This Page Directly</div>";
				echo "<div class='container'>";
				redirect($errorMsg,'back',2);
				echo "</div>";
		}
		echo "</div>";

	} elseif ($do == 'Delete') {

			echo '<h1 class="text-center">Delete Comments</h1>';
				echo '<div class="container">';

	
			$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

			$check = checkItem('CID','comments',$comid);

			if ($check > 0) {

				$stmt = $con->prepare("DELETE FROM comments WHERE CID = :ZID");
				$stmt->bindParam("ZID",$comid);
				$stmt->execute();
				$Msg = "<div class='alert alert-success text-center'>" . $stmt->rowCount() . " " . 'Record Deleted </div>';
				redirect($Msg,'back');

			}else {

				$Msg = "<div class='alert alert-danger text-center'>This ID Is Not Exist</div>";
				redirect($Msg,'back');

			}
			echo "</div>";

	} elseif($do == 'Approve'){

		echo '<h1 class="text-center">Activate Comments</h1>';
			echo '<div class="container">';

	
			$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

			$check = checkItem('CID','comments',$comid);

			if ($check > 0) {

				$stmt = $con->prepare("UPDATE comments SET Status = 1 WHERE CID = ?");

				$stmt->execute(array($comid));

				$Msg = "<div class='alert alert-success text-center'>" . $stmt->rowCount() . " " . 'Record Approved </div>';

				redirect($Msg,'back');

			}else {

				$Msg = "<div class='alert alert-danger text-center'>This ID Is Not Exist</div>";
				redirect($Msg,'back');

			}
			echo "</div>";


	}

		include $tpl . 'footer.php';	
	}else{

		header('Location: index.php');

		exit();
	}