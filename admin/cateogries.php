<?php 

	ob_start();

	session_start();

	$pageTitle = 'Cateogries';

	if (isset($_SESSION['Username'])) {

		include 'init.php';

		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

		if ($do == 'Manage') {

			$sort='ASC';

			$sort_array = array('ASC','DESC');

			if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)){

				$sort = $_GET['sort'];

			}

			$stm2 = $con->prepare("SELECT * FROM categories ORDER BY Ordering $sort ");

			$stm2->execute();

			$cats = $stm2->fetchALL(); ?>

			<h1 class="text-center">Add New Cateogry</h1>
			<div class="container categories">
				<div class="card card-default">
					<div class="card-header">
						Manage Cateogries
						<div class="ordering pull-right">
							Ordering:
							[ <a class="<?php if($sort=='ASC'){echo"active";} ?>" href="?sort=ASC"><i class="fa fa-chevron-up"></i></a> |
							<a class="<?php if($sort=='DESC'){echo"active";} ?>" href="?sort=DESC"><i class="fa fa-chevron-down"></i></a> ]
							View:
							[ <span class="option choosed" data-view="full">Full</span> |
							<span class="option" data-view="classic">Classic</span> ]
						</div>
					</div>
						<div class="card-body">
							<?php 
								foreach($cats as $cat) {

									echo "<div class='cat'>";
										echo "<div class='hidden-buttons'>";
											echo "<a href='cateogries.php?do=Edit&catid=". $cat['ID'] . "' class='btn btn-xs btn-primary'>Edit <i class='fa fa-edit'></i></a>";
											echo "<a href='cateogries.php?do=Delete&catid=". $cat['ID'] . "' class='confirm btn btn-xs btn-danger'>Delete <i class='fa fa-close'></i></a>";
										echo "</div>";
										echo "<h3>" . $cat['Name'] . "</h3>";
										echo "<div class='full-view'>";
										echo "<p>"; if($cat['Description'] == ''){echo "There Is No Description";}else{
											echo $cat['Description'];
										} echo "</p>";
										if($cat['Visibility']==1){echo "<span class='vis-dis'>Hidden</span>";}else{
											echo "<span class='vis-ena'>Visibile</span>";
										}
										if($cat['AllowComment']==1){echo "<span class='com-dis'>Comments Disabled</span>";}else{echo "<span class='com-ena'>Comments Enabled</span>";}
										if($cat['AllowAds']==1){echo "<span class='ads-dis'>Ads Disabled</span>";}else{
											echo "<span class='ads-ena'>Ads Enabled</span>";
										}
										echo "</div>";
									echo "</div>";
									echo "<hr>";
								}
							?>
						</div>
				</div>
				<a href="cateogries.php?do=Add" class="btn btn-primary add-cat">Add New Cateogry <i class="fa fa-plus"></i></a>
			</div>


			<?php

		} elseif ($do == 'Add') { ?>

				<h1 class="text-center">Add New Cateogry</h1>
				<div class="container">
					<form class="form-horizontal" action="?do=Insert" method="POST">
						<div class="form-group">
							<label class="col-sm-2 control-label">Name</label>
							<div class="col-sm-10">
								<input type="text" name="name" class="form-control" required="Required" placeholder="The Name Of The Cateogry">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Description</label>
							<div class="col-sm-10">
								<input type="text" name="description" class="form-control" placeholder="Descripe Your Cateogry">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Ordering</label>
							<div class="col-sm-10">
								<input type="text" name="ordering" class="form-control" placeholder="Number to Arrange the Cateogry">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Visibility</label>
							<div class="col-sm-10">
								<div>
									<input id="vis-yes" type="radio" name="visibility" value="0" checked="">
									<label for="vis-yes">Yes</label>
								</div>
								<div>
									<input id="vis-no" type="radio" name="visibility" value="1">
									<label for="vis-no">No</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Commenting</label>
							<div class="col-sm-10">
								<div>
									<input id="com-yes" type="radio" name="commenting" value="0" checked="">
									<label for="com-yes">Yes</label>
								</div>
								<div>
									<input id="com-no" type="radio" name="commenting" value="1">
									<label for="com-no">No</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Ads</label>
							<div class="col-sm-10">
								<div>
									<input id="ads-yes" type="radio" name="ads" value="0" checked="">
									<label for="ads-yes">Yes</label>
								</div>
								<div>
									<input id="ads-no" type="radio" name="ads" value="1">
									<label for="ads-no">No</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<input type="submit" value="Add Cateogry" class="btn btn-primary">
							</div>
						</div>
					</form>
				</div>	



			<?php

		} elseif ($do == 'Insert') {

			if($_SERVER['REQUEST_METHOD'] == 'POST') {
				echo "<h1 class='text-center'>Insert Cateogry</h1>";
				echo '<div class="container">';
				// get variables from the form
				$name 			= 		$_POST['name'];
				$desc 			=		$_POST['description'];
				$order 			= 		$_POST['ordering'];
				$visibil 		= 		$_POST['visibility'];
				$comment 		= 		$_POST['commenting'];
				$ads 			= 		$_POST['ads'];

					// Check if category is exsist in database

				$check = checkItem("Name","categories",$name);

				if ($check == 1) {
					$msg = '<div class="alert alert-danger text-center">Sorry This Cateogry Is Exist</div>';
					redirect($msg,'back',5);

				}else {

					// inesert form info into  database

					$stmt = $con->prepare("INSERT INTO 
											categories (Name,Description,Ordering,Visibility,AllowComment,AllowAds)
											VALUES(:zname , :zdesc , :zorder , :zvisib, :zcomm , :zads)");
					$stmt->execute(array(

						'zname' => $name,
						'zdesc'	=> $desc,
						'zorder' => $order,
						'zvisib' => $visibil,
						'zcomm' => $comment,
						'zads' => $ads

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

			$catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
			$stmt = $con->prepare("SELECT
									*
									FROM
										categories
									WHERE
										ID = ?
									");
			$stmt->execute(array($catid));
			$cat = $stmt->fetch();
			$count = $stmt->rowCount();
			if ($stmt->rowCount() > 0) { ?>


				<h1 class="text-center">Edit Cateogry</h1>
				<div class="container">
					<form class="form-horizontal" action="?do=Update" method="POST">
						<input type="hidden" name="catid" value="<?php echo $catid ?>">
						<div class="form-group">
							<label class="col-sm-2 control-label">Name</label>
							<div class="col-sm-10">
								<input type="text" name="name" class="form-control" required="Required" placeholder="The Name Of The Cateogry" value="<?php echo $cat['Name'] ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Description</label>
							<div class="col-sm-10">
								<input type="text" name="description" class="form-control" placeholder="Descripe Your Cateogry" value="<?php echo $cat['Description'] ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Ordering</label>
							<div class="col-sm-10">
								<input type="text" name="ordering" class="form-control" placeholder="Number to Arrange the Cateogry" value="<?php echo $cat['Ordering'] ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Visibility</label>
							<div class="col-sm-10">
								<div>
									<input id="vis-yes" type="radio" name="visibility" value="0" <?php if($cat['Visibility']==0){echo 'checked';} ?> >
									<label for="vis-yes">Yes</label>
								</div>
								<div>
									<input id="vis-no" type="radio" name="visibility" value="1" <?php if($cat['Visibility']==1){echo 'checked';} ?> >
									<label for="vis-no">No</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Commenting</label>
							<div class="col-sm-10">
								<div>
									<input id="com-yes" type="radio" name="commenting" value="0" <?php if($cat['AllowComment']==0){echo 'checked';} ?>>
									<label for="com-yes">Yes</label>
								</div>
								<div>
									<input id="com-no" type="radio" name="commenting" value="1" <?php if($cat['AllowComment']==1){echo 'checked';} ?>>
									<label for="com-no">No</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Ads</label>
							<div class="col-sm-10">
								<div>
									<input id="ads-yes" type="radio" name="ads" value="0" <?php if($cat['AllowAds']==0){echo 'checked';} ?>>
									<label for="ads-yes">Yes</label>
								</div>
								<div>
									<input id="ads-no" type="radio" name="ads" value="1" <?php if($cat['AllowAds']==1){echo 'checked';} ?>>
									<label for="ads-no">No</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<input type="submit" value="Update Cateogry" class="btn btn-primary">
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

		echo "<h1 class='text-center'>Upate Cateogry</h1>";
		echo '<div class="container">';

		if($_SERVER['REQUEST_METHOD'] == 'POST') {

			// get variables from the form

			$id 		= 		$_POST['catid'];
			$name 		= 		$_POST['name'];
			$desc 		= 		$_POST['description'];
			$order 		= 		$_POST['ordering'];
			$visib 		= 		$_POST['visibility'];
			$comm 		= 		$_POST['commenting'];
			$ads 		= 		$_POST['ads'];



			// update database

			$stmt = $con->prepare("UPDATE categories
										SET 
										 	Name = ? ,
										 	Description = ? ,
										  	Ordering = ? ,
										    Visibility = ? ,
										    AllowComment = ? ,
										    AllowAds = ?
										WHERE ID = ?");
			$stmt->execute(array($name,$desc,$order,$visib,$comm,$ads,$id));

			// Echo Success Message

			echo  "<div class='alert alert-success text-center'>" . $stmt->rowCount() . " " . 'Record Updated </div>';
			redirect("",'back',5);

		} else {
			$errorMsg = "<div class='alert alert-danger text-center'>SORRY YOU CANT Browse This Page Directly</div>";
				echo "<div class='container'>";
				redirect($errorMsg,'back',5);
				echo "</div>";
		}
		echo "</div>";

		} elseif ($do == 'Delete') {

			echo '<h1 class="text-center">Delete Cateogry</h1>';
				echo '<div class="container">';

	
			$catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
			$check = checkItem('ID','categories',$catid);
			if ($check > 0) {

				$stmt = $con->prepare("DELETE FROM categories WHERE ID = :zid");
				$stmt->bindParam("zid",$catid);
				$stmt->execute();
				$Msg = "<div class='alert alert-success text-center'>" . $stmt->rowCount() . " " . 'Record Deleted </div>';
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