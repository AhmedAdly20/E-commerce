<?php 

	ob_start();

	session_start();

	$pageTitle = 'Items';

	if (isset($_SESSION['Username'])) {

		include 'init.php';

		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

		if ($do == 'Manage') {

			$stmt = $con->prepare("SELECT items.* ,
									categories.Name AS CategoryName ,
									users.Username
									FROM 
										items
									INNER JOIN 
										categories
									ON
										categories.ID = items.CatID
									INNER JOIN
										users
									ON
										users.UserID = items.MemberID"
								  );

			$stmt->execute();
			
			$items = $stmt->fetchAll();


			?>

			<h1 class="text-center">Manage Items</h1>
				<div class="container">
					<div class="table-responsive">
						<table class="main-table text-center table table-bordered">
							<tr>
								<td>#ID</td>
								<td>#Name</td>
								<td>#Description</td>
								<td>#Price</td>
								<td>#Adding Date</td>
								<td>#Category</td>
								<td>#Username</td>
								<td>#Control</td>
							</tr>
							<?php

							foreach ($items as $item ) {
								echo "<tr>";
									echo "<td>" . $item['ItemID'] . "</td>";
									echo "<td>" . $item['Name'] . "</td>";
									echo "<td>" . $item['Description'] . "</td>";
									echo "<td>" . $item['Price'] . "</td>";
									echo "<td>" . $item['AddDate'] . "</td>";
									echo "<td>" . $item['CategoryName'] . "</td>";
									echo "<td>" . $item['Username'] . "</td>";
									echo "<td>
										<a href='items.php?do=Edit&itemid=". $item['ItemID'] ."' class='btn btn-success'>Edit  <i class='fa fa-edit'></i></a> 
										<a href='items.php?do=Delete&itemid=". $item['ItemID'] ."' class='btn btn-danger confirm'>Delete <i class='fa fa-close'></i></a>";
										if ($item['Approve'] == 0) {

											echo "<a href='items.php?do=Approve&itemid=". $item['ItemID'] ."' class='btn btn-info activate'>Approve <i class='fa fa-check'></i></a>";

										}
										echo "</td>";
								echo "</tr>";
							}

							?>
						</table>
					</div>
					<a href="items.php?do=Add" class="btn btn-primary">Add New Item  <i class="fa fa-plus"></i></a>
				</div>
			<?php 

		} elseif ($do == 'Add') { ?>

			<h1 class="text-center">Add New Item</h1>
				<div class="container">
					<form class="form-horizontal" action="?do=Insert" method="POST">
						<div class="form-group">
							<label class="col-sm-2 control-label">Name</label>
							<div class="col-sm-10">
								<input type="text" name="name" class="form-control" required="Required" placeholder="The Name Of The Item">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Description</label>
							<div class="col-sm-10">
								<input type="text" name="description" class="form-control" placeholder="The Description Of The Item">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Price</label>
							<div class="col-sm-10">
								<input type="text" name="price" class="form-control" required="Required" placeholder="The Price Of The Item">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Country Of Made</label>
							<div class="col-sm-10">
								<input type="text" name="country" class="form-control" placeholder="The Country Of The Item">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Status</label>
							<div class="col-sm-10">
								<select name="status">
									<option value="0">...</option>
									<option value="1">New</option>
									<option value="2">Like New</option>
									<option value="3">Used</option>
									<option value="4">Old</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Member</label>
							<div class="col-sm-10">
								<select name="member">
									<option value="0">...</option>
									<?php

										$stmt = $con->prepare("SELECT * FROM users");
										$stmt->execute();
										$users = $stmt->fetchAll();
										foreach ($users as $user) {
												
											echo "<option value='". $user['UserID'] ."'>". $user['Username'] ."</opion>";

										}


									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Category</label>
							<div class="col-sm-10">
								<select name="category">
									<option value="0">...</option>
									<?php

										$stmt = $con->prepare("SELECT * FROM categories");
										$stmt->execute();
										$cats = $stmt->fetchAll();
										foreach ($cats as $cat) {
												
											echo "<option value='". $cat['ID'] ."'>". $cat['Name'] ."</opion>";

										}


									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Tags</label>
							<div class="col-sm-10">
								<input type="text" name="tags" class="form-control" placeholder="Separete Tags With,">
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<input type="submit" value="Add Item" class="btn btn-primary">
							</div>
						</div>
					</form>
				</div>	

			<?php

		} elseif ($do == 'Insert') {

			if($_SERVER['REQUEST_METHOD'] == 'POST') {
				echo "<h1 class='text-center'>Insert Item</h1>";
				echo '<div class="container">';
				// get variables from the form
				$name 			= 		$_POST['name'];
				$desc 			=		$_POST['description'];
				$price 			= 		$_POST['price'];
				$country 		= 		$_POST['country'];
				$status 		= 		$_POST['status'];
				$member 		= 		$_POST['member'];
				$category 		= 		$_POST['category'];
				$tags 			=		$_POST['tags'];


				// Validate The Form

				$formErrors = array();

				if (empty($name)) {

					$formErrors[] = 'Name Cant be empty';


				}

				if (empty($price)) {

					$formErrors[] = 'Price Cant be empty';

				}

				if ($status == 0) {

					$formErrors[] = 'You Must Choose The Status';

				}

				if ($member == 0) {

					$formErrors[] = 'You Must Choose The Member';

				}

				if ($category == 0) {

					$formErrors[] = 'You Must Choose The Category';

				}

				foreach ($formErrors as $error) {
					echo '<div class="alert alert-danger text-center">' . $error . '</div>';
				}

				//check if there is no error proceed this operation 
				if (empty($formErrors)) {

						// inesert form info into  database

						$stmt = $con->prepare("INSERT INTO 
												items (Name,Description,Price,CountryMade,Status,AddDate,MemberID,CatID,Tags)
												VALUES(:zname , :zdesc , :zprice , :zcountry , :zstatus , now(), :zmember , :zcat, :ztag)");
						$stmt->execute(array(

							'zname' => $name,
							'zdesc'	=> $desc,
							'zprice' => $price,
							'zcountry' => $country,
							'zstatus' => $status,
							'zmember' => $member,
							'zcat' => $category,
							'ztag' => $tags

						));

						// Echo Success Message

						$themsg = "<div class='alert alert-success text-center'>" . $stmt->rowCount() . " " . 'Record Inserted </div>';
						redirect($themsg,'back',5);
					}

			} else {
				$errorMsg = "<div class='alert alert-danger text-center'>SORRY YOU CANT Browse This Page Directly</div>";
				echo "<div class='container'>";
				redirect($errorMsg,'',5);
				echo "</div>";
			}
			echo "</div>";

		} elseif ($do == 'Edit') {

			$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

			$stmt = $con->prepare("SELECT
									*
									FROM
										items
									WHERE
										ItemID = ?");

			$stmt->execute(array($itemid));

			$item = $stmt->fetch();

			$count = $stmt->rowCount();

			if ($count > 0) { ?>
				
				<h1 class="text-center">Edit Item</h1>
					<div class="container">
						<form class="form-horizontal" action="?do=Update" method="POST">
							<input type="hidden" name="itemid" value="<?php echo $itemid ?>">
							<div class="form-group">
								<label class="col-sm-2 control-label">Name</label>
								<div class="col-sm-10">
									<input type="text" name="name" class="form-control" required="Required" placeholder="The Name Of The Item" value="<?php echo $item['Name'] ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">Description</label>
								<div class="col-sm-10">
									<input type="text" name="description" class="form-control" placeholder="The Description Of The Item" value="<?php echo $item['Description'] ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">Price</label>
								<div class="col-sm-10">
									<input type="text" name="price" class="form-control" required="Required" placeholder="The Price Of The Item" value="<?php echo $item['Price'] ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">Country Of Made</label>
								<div class="col-sm-10">
									<input type="text" name="country" class="form-control" placeholder="The Country Of The Item" value="<?php echo $item['CountryMade'] ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">Status</label>
								<div class="col-sm-10">
									<select name="status">
										<option value="1" <?php if($item['Status'] ==1){echo "selected";} ?>>New</option>
										<option value="2" <?php if($item['Status'] ==2){echo "selected";} ?>>Like New</option>
										<option value="3" <?php if($item['Status'] ==3){echo "selected";} ?>>Used</option>
										<option value="4" <?php if($item['Status'] ==4){echo "selected";} ?>>Old</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">Member</label>
								<div class="col-sm-10">
									<select name="member">
										<?php

											$stmt = $con->prepare("SELECT * FROM users");
											$stmt->execute();
											$users = $stmt->fetchAll();
											foreach ($users as $user) {
													
												echo "<option value='". $user['UserID'] ."'";
												 if($item['MemberID'] == $user['UserID']){echo "selected";}
												 echo ">". $user['Username'] ."</opion>";

											}


										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">Category</label>
								<div class="col-sm-10">
									<select name="category">
										<?php

											$stmt = $con->prepare("SELECT * FROM categories");
											$stmt->execute();
											$cats = $stmt->fetchAll();
											foreach ($cats as $cat) {
													
												echo "<option value='". $cat['ID'] ."'";
												if($item['CatID'] == $cat['ID']){echo "selected";}
												echo ">". $cat['Name'] ."</opion>";

											}


										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<input type="submit" value="Save Item" class="btn btn-primary">
								</div>
							</div>
						</form>
						<?php 
							//Select All Users exept admins

							$stmt = $con->prepare("SELECT
														comments.*, users.Username AS Member
												   FROM
												   	   comments
												   	INNER JOIN
												   		users
												   	ON
												   		users.UserID = comments.UserID
												   	WHERE
												   		ItemID = ?
							  						");
							$stmt->execute(array($itemid));
							// assign to var
							$rows = $stmt->fetchAll();

							if(!empty($rows)) {
							?>

							<h1 class="text-center">Manage [ <?php echo $item['Name']; ?> ] Comments</h1>
								<div class="table-responsive">
									<table class="main-table text-center table table-bordered">
										<tr>
											<td>#Comment</td>
											<td>#UserName</td>
											<td>#Added Date</td>
											<td>#Control</td>
										</tr>
										<?php

										foreach ($rows as $row ) {
											echo "<tr>";
												echo "<td>" . $row['Comment'] . "</td>";
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
							<?php } ?>
					</div>	

		<?php 
		} else {
			echo "<div class='container'>";
			$msg = "<div class='text-center alert alert-danger'>This Is Wrong ID</div>";
			redirect($msg,'back',5);
			echo "</div>";
		}

		} elseif ($do == 'Update') {

			echo "<h1 class='text-center'>Update Items</h1>";
			echo '<div class="container">';

			if($_SERVER['REQUEST_METHOD'] == 'POST') {

				// get variables from the form

				$id 		= 		$_POST['itemid'];
				$name 		= 		$_POST['name'];
				$desc 		= 		$_POST['description'];
				$price 		= 		$_POST['price'];
				$country 	= 		$_POST['country'];
				$status 	= 		$_POST['status'];
				$member 	= 		$_POST['member'];
				$category 	= 		$_POST['category'];

				// Validate The Form

				$formErrors = array();

				if (empty($name)) {

					$formErrors[] = 'Name Cant be empty';


				}

				if (empty($price)) {

					$formErrors[] = 'Price Cant be empty';

				}

					foreach ($formErrors as $error) {
						echo '<div class="alert alert-danger">' . $error . '</div>';
					}

				//check if there is no error proceed this operation 
				if (empty($formErrors)) {

					// update database

					$stmt = $con->prepare("UPDATE
												items
					 						SET
					 							Name = ?,
					 						 	Description = ?,
					 						  	Price = ?,
					 						   	CountryMade = ?,
					 						   	Status = ?,
					 						   	CatID = ?,
					 						   	MemberID = ?
					 						WHERE
					 							ItemID = ?");
					$stmt->execute(array($name,$desc,$price,$country,$status,$category,$member,$id));

					// Echo Success Message

					echo  "<div class='alert alert-success text-center'>" . $stmt->rowCount() . " " . 'Record Updated </div>';
					redirect("",'back',5);
				}

			} else {
				$errorMsg = "<div class='alert alert-danger text-center'>SORRY YOU CANT Browse This Page Directly</div>";
					echo "<div class='container'>";
					redirect($errorMsg,'back',5);
					echo "</div>";
			}
			echo "</div>";

		} elseif ($do == 'Delete') {

			echo '<h1 class="text-center">Delete Items</h1>';
				echo '<div class="container">';

	
			$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
			$check = checkItem('ItemID','items',$itemid);
			if ($check > 0) {

				$stmt = $con->prepare("DELETE FROM items WHERE ItemID = :zid");
				$stmt->bindParam("zid",$itemid);
				$stmt->execute();
				$Msg = "<div class='alert alert-success text-center'>" . $stmt->rowCount() . " " . 'Record Deleted </div>';
				redirect($Msg,'back');

			}else {

				$Msg = "<div class='alert alert-danger text-center'>This ID Is Not Exist</div>";
				redirect($Msg,'back');

			}
			echo "</div>";

		} elseif ($do == 'Approve') {

			echo '<h1 class="text-center">Approve Items</h1>';
			echo '<div class="container">';

	
			$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

			$check = checkItem('ItemID','items',$itemid);

			if ($check > 0) {

				$stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE ItemID = ?");

				$stmt->execute(array($itemid));

				$Msg = "<div class='alert alert-success text-center'>" . $stmt->rowCount() . " " . 'Record Approved </div>';

				redirect($Msg,'back');

			}else {

				$Msg = "<div class='alert alert-danger text-center'>This ID Is Not Exist</div>";
				redirect($Msg,'back');

			}
			echo "</div>";

		}

		include $tpl . 'footer.php';	

	} else {

		header('Location: index.php');

		exit();
	}

	ob_end_flush();

?>