<?php

	/*
	===================================================
	==Manage Member Page
	==You Can Add Delete Members
	===================================================
	*/
	session_start();

	$pageTitle = 'Members';

	if (isset($_SESSION['Username'])) {
		include 'init.php';

		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

		if($do == 'Manage') { 

			$query ='';

			if (isset($_GET['page']) && $_GET['page'] == 'Pending') {

				$query = 'AND ReqStatus = 0';

			}

			//Select All Users exept admins

			$stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 $query");
			$stmt->execute();
			// assign to var
			$rows = $stmt->fetchAll();


			?>

			<h1 class="text-center">Manage Members</h1>
				<div class="container">
					<div class="table-responsive">
						<table class="main-table manage-members text-center table table-bordered">
							<tr>
								<td>#ID</td>
								<td>#Photo</td>
								<td>#UserName</td>
								<td>#Email</td>
								<td>#FullName</td>
								<td>#Register Date</td>
								<td>#Control</td>
							</tr>
							<?php

							foreach ($rows as $row ) {
								echo "<tr>";
									echo "<td>" . $row['UserID'] . "</td>";
									echo "<td><img class='img-fluid img-thumbnail' src='uploads/photos/". $row['Photo'] ."' alt=''/></td>";
									echo "<td>" . $row['Username'] . "</td>";
									echo "<td>" . $row['Email'] . "</td>";
									echo "<td>" . $row['FullName'] . "</td>";
									echo "<td>" . $row['DOR'] . "</td>";
									echo "<td>
										<a href='members.php?do=Edit&userid=". $row['UserID'] ."' class='btn btn-success'>Edit  <i class='fa fa-edit'></i></a> 
										<a href='members.php?do=Delete&userid=". $row['UserID'] ."' class='btn btn-danger confirm'>Delete <i class='fa fa-close'></i></a>";
										if ($row['ReqStatus'] == 0) {

											echo "<a href='members.php?do=Activate&userid=". $row['UserID'] ."' class='btn btn-info activate'>Activate <i class='fa fa-check'></i></a>";

										}
										echo "</td>";
								echo "</tr>";
							}

							?>
						</table>
					</div>
					<a href="members.php?do=Add" class="btn btn-primary">Add New Members  <i class="fa fa-plus"></i></a>
				</div>
		<?php }
		elseif($do == 'Add') { ?>

			<h1 class="text-center">Add New Member</h1>
				<div class="container">
					<form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">
						<div class="form-group">
							<label class="col-sm-2 control-label">UserName</label>
							<div class="col-sm-10">
								<input type="text" name="Username" class="form-control" required="Required" placeholder="Username You Will Use To LogIn">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Password</label>
							<div class="col-sm-10">
								<input type="Password" name="password" class="password form-control" placeholder="Minimum 6 charchaters" required="Required">
								<i class="showpass fa fa-eye fa-lg"></i>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">E-Mail</label>
							<div class="col-sm-10">
								<input type="email" name="email" class="form-control" required="Required" placeholder="Email Address Must be Valid">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Full Name</label>
							<div class="col-sm-10">
								<input type="text" name="full" placeholder="Type Your FullName" class="form-control" required="Required">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">User Photo</label>
							<div class="col-sm-10">
								<input type="file" name="photo" required="Required" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<input type="submit" value="Add Member" class="btn btn-primary">
							</div>
						</div>
					</form>
				</div>	

		<?php 
			}	

		elseif ($do == 'Insert') {

			if($_SERVER['REQUEST_METHOD'] == 'POST') {
				echo "<h1 class='text-center'>Insert Category</h1>";
				echo '<div class="container">';
				// get variables from the form

				$photoName 	= 		$_FILES['photo']['name'];
				$photoSize 	= 		$_FILES['photo']['size'];
				$photoTmp 	= 		$_FILES['photo']['tmp_name'];
				$photoType 	= 		$_FILES['photo']['type'];

				$photosExtensions = array("jpeg","jpg","png","gif");

				$photo1 = explode(".",$photoName);

				$photo2 = end($photo1);

				$photoExtension = strtolower($photo2);

				$user 		= 		$_POST['Username'];
				$pass 		=		$_POST['password'];
				$mail 		= 		$_POST['email'];
				$name 		= 		$_POST['full'];

				$hashpass   = 		sha1($pass);
				// Validate The Form

				$formErrors = array();

				if (strlen($user) < 4) {

					$formErrors[] = 'Username Cant be less than <strong> 4 charchaters</strong>';

				}

				if (strlen($user) > 20) {

					$formErrors[] = 'Username Cant be more than 20 charchaters <strong> 20 charchaters</strong>';

				}

				if (strlen($pass) < 6) {

					$formErrors[] = 'Password Cant be less than <strong> 6 Charchaters</strong>';

				}

				if (empty($user)) {

					$formErrors[] = 'Username Cant be empty';

				}

				if (empty($name)) {

					$formErrors[] = 'FullName Cant be empty';


				}

				if (empty($mail)) {

					$formErrors[] = 'Email Cant be empty';

				}

				if (!in_array($photoExtension, $photosExtensions)){

					$formErrors[] = 'This Exstintion is not Allowed';

				}

				if ($photoSize > 4194304) {
					$formErrors[] = 'Photo Cant be More Than 4MB';
				}

				foreach ($formErrors as $error) {
					echo '<div class="alert alert-danger">' . $error . '</div>';
				}

				//check if there is no error proceed this operation 
				if (empty($formErrors)) {

					$Photo = rand(0,1000000) . '_' . $photoName;

					move_uploaded_file($photoTmp, "uploads\photos\\" . $Photo);


					// Check if user is exsist in database

					$check = checkItem("Username","users",$user);

					if ($check == 1) {
						$msg = '<div class="alert alert-danger text-center">Sorry This User Is Exist</div>';
						redirect($msg,'back',5);

					}else {

						// inesert form info into  database

						$stmt = $con->prepare("INSERT INTO 
												users (Username,Password,Email,FullName,ReqStatus,DOR,Photo)
												VALUES(:zuser , :zpass , :zmail , :zfull,1,now(),:zphoto)");
						$stmt->execute(array(

							'zuser' => $user,
							'zpass'	=> $hashpass,
							'zmail' => $mail,
							'zfull' => $name,
							'zphoto'=> $Photo
						));

						// Echo Success Message

						$themsg = "<div class='alert alert-success text-center'>" . $stmt->rowCount() . " " . 'Record Inserted </div>';
						redirect($themsg,'back',5);
					}
			}

			} else {
				$errorMsg = "<div class='alert alert-danger text-center'>SORRY YOU CANT Browse This Page Directly</div>";
				echo "<div class='container'>";
				redirect($errorMsg,'',5);
				echo "</div>";
			}
			echo "</div>";

		}

		elseif ($do == 'Edit') {

			$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
			$stmt = $con->prepare("SELECT
									*
									FROM
										users
									WHERE
										UserID = ?
									LIMIT 1");
			$stmt->execute(array($userid));
			$row = $stmt->fetch();
			$count = $stmt->rowCount();
			if ($stmt->rowCount() > 0) { ?>
				<h1 class="text-center">Edit Members</h1>
				<div class="container">
					<form class="form-horizontal" action="?do=Update" method="POST">
						<input type="hidden" name="userid" value="<?php echo $userid ?>">
						<div class="form-group">
							<label class="col-sm-2 control-label">UserName</label>
							<div class="col-sm-10">
								<input type="text" name="Username" value="<?php echo $row['Username'] ?>" class="form-control" required="Required">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Password</label>
							<div class="col-sm-10">
								<input type="hidden" name="oldPassword" value="<?php echo $row['Password'] ?>">
								<input type="Password" name="newPassword" class="form-control" placeholder="Leave Plank IF YOU Dont Want To Change">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">E-Mail</label>
							<div class="col-sm-10">
								<input type="email" name="email" value="<?php echo $row['Email'] ?>" class="form-control" required="Required">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Full Name</label>
							<div class="col-sm-10">
								<input type="text" name="full" value="<?php echo $row['FullName'] ?>" class="form-control" required="Required">
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
		
		echo "<h1 class='text-center'>Update Members</h1>";
		echo '<div class="container">';

		if($_SERVER['REQUEST_METHOD'] == 'POST') {

			// get variables from the form

			$id 		= 		$_POST['userid'];
			$user 		= 		$_POST['Username'];
			$mail 		= 		$_POST['email'];
			$name 		= 		$_POST['full'];

			// Password Trick

			$pass = empty($_POST['newPassword']) ? $_POST['oldPassword'] : sha1($_POST['newPassword']);

			// Validate The Form

			$formErrors = array();

			if (strlen($user) < 4) {

					$formErrors[] = 'Username Cant be less than <strong> 4 charchaters</strong>';

				}

				if (strlen($user) > 20) {

					$formErrors[] = 'Username Cant be more than 20 charchaters <strong> 20 charchaters</strong>';

				}

				if (empty($user)) {

					$formErrors[] = 'Username Cant be empty';

				}

				if (empty($name)) {

					$formErrors[] = 'FullName Cant be empty';


				}

				if (empty($mail)) {

					$formErrors[] = 'Email Cant be empty';

				}

				foreach ($formErrors as $error) {
					echo '<div class="alert alert-danger">' . $error . '</div>';
				}

			//check if there is no error proceed this operation 
			if (empty($formErrors)) {

				$stmt2 = $con->prepare("SELECT * 
											FROM users
										WHERE 
											Username = ?
										AND 
											UserID != ?
										");

				$stmt2->execute(array($user,$id));

				$count = $stmt2->rowCount();

				if ($count == 1) {

					echo "<div class='alert alert-danger text-center'>This UserName Is Exist</div>";
					redirect("",'back',3);

				} else {
					$stmt = $con->prepare("UPDATE users SET Username = ?, Email = ?, FullName = ?, Password = ? WHERE UserID = ?");
					$stmt->execute(array($user,$mail,$name,$pass,$id));

					// Echo Success Message

					echo  "<div class='alert alert-success text-center'>" . $stmt->rowCount() . " " . 'Record Updated </div>';
					redirect("",'back',5);
				}
			}

		} else {
			$errorMsg = "<div class='alert alert-danger text-center'>SORRY YOU CANT Browse This Page Directly</div>";
				echo "<div class='container'>";
				redirect($errorMsg,'back',5);
				echo "</div>";
		}
		echo "</div>";

	} elseif ($do == 'Delete') {

			echo '<h1 class="text-center">Delete Members</h1>';
				echo '<div class="container">';

	
			$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
			$check = checkItem('userid','users',$userid);
			if ($check > 0) {

				$stmt = $con->prepare("DELETE FROM users WHERE UserID = :zuser");
				$stmt->bindParam("zuser",$userid);
				$stmt->execute();
				$Msg = "<div class='alert alert-success text-center'>" . $stmt->rowCount() . " " . 'Record Deleted </div>';
				redirect($Msg,'back');

			}else {

				$Msg = "<div class='alert alert-danger text-center'>This ID Is Not Exist</div>";
				redirect($Msg,'back');

			}
			echo "</div>";

	} elseif($do == 'Activate'){

		echo '<h1 class="text-center">Activate Members</h1>';
			echo '<div class="container">';

	
			$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

			$check = checkItem('userid','users',$userid);

			if ($check > 0) {

				$stmt = $con->prepare("UPDATE users SET ReqStatus = 1 WHERE UserID = ?");

				$stmt->execute(array($userid));

				$Msg = "<div class='alert alert-success text-center'>" . $stmt->rowCount() . " " . 'Record Activated </div>';

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