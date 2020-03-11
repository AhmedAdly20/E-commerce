<?php 
	
	session_start();

	$pageTitle = 'Create a new Ad';

	include 'init.php';

	if(isset($_SESSION['user'])) {

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			$formErrors = array();

			$name 		= filter_var($_POST['name'],FILTER_SANITIZE_STRING);
			$desc 		= filter_var($_POST['description'],FILTER_SANITIZE_STRING);
			$price 		= filter_var($_POST['price'],FILTER_SANITIZE_NUMBER_INT);
			$country 	= filter_var($_POST['country'],FILTER_SANITIZE_STRING);
			$status 	= filter_var($_POST['status'],FILTER_SANITIZE_STRING);
			$category 	= filter_var($_POST['category'],FILTER_SANITIZE_STRING);

			if(strlen($name) < 3) {

				$formErrors[] = 'Item Name Can\'t Be Less Than 3 Charachters ';

			}
			if(strlen($country) < 2) {

				$formErrors[] = 'Item Country Can\'t Be Less Than 2 Charachters ';

			}
			if(empty($price)) {

				$formErrors[] = 'Item Price Can\'t Be Empty';

			}
			if(empty($status)) {

				$formErrors[] = 'Item Status Can\'t Be Empty';

			}
			if(empty($category)) {

				$formErrors[] = 'Item Category Can\'t Be Empty';

			}
		if (empty($formErrors)) {

			// inesert form info into  database

			$stmt = $con->prepare("INSERT INTO 
									items (Name,Description,Price,CountryMade,Status,AddDate,MemberID,CatID)
									VALUES(:zname , :zdesc , :zprice , :zcountry , :zstatus , now(), :zmember , :zcat)");
			$stmt->execute(array(

				'zname' => $name,
				'zdesc'	=> $desc,
				'zprice' => $price,
				'zcountry' => $country,
				'zstatus' => $status,
				'zmember' => $_SESSION['id'],
				'zcat' => $category
				));

				if($stmt) {
					echo "<div class='alet alert-success text-center'>Item Has been added successfuly</div>";
				}
		}
	}

?>
<h1 class="text-center">Create New Item</h1>
<div class="create-ad block">
	<div class="container">
		<div class="card border-primary">
			<div class="card-header">Create a New Item</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-8">
						<form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
						<div class="form-group">
							<label class="col-sm-2 control-label">Name</label>
							<div class="col-sm-10">
								<input type="text" name="name" class="form-control live-name" required="Required" placeholder="The Name Of The Item">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Description</label>
							<div class="col-sm-10">
								<input type="text" name="description" class="form-control live-desc" placeholder="The Description Of The Item">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Price</label>
							<div class="col-sm-10 ">
								<input type="text" name="price" class="form-control live-price" required="Required" placeholder="The Price Of The Item">
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
							<label class="col-sm-2 control-label">Category</label>
							<div class="col-sm-10 col-md-9">
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
							<div class="col-sm-offset-2 col-sm-10">
								<input type="submit" value="Add Item" class="btn btn-primary">
							</div>
						</div>
					</form>
					</div>
					<div class="col-md-4">
						<div class="card item-box live-preview">
							<span class="price-tag">3.700 L.E</span>
							<img class="img-fluid" src="sams10.png" alt="" />
							<div class="caption">
								<h3>Note7</h3>	
								<p>Best Mobile Ever</p>		
							</div>
						</div>
					</div>
				</div>
				<!-- Start Looping Errors -->
				<?php 
				if(!empty($formErrors)) {
					foreach($formErrors as $error){
						echo '<div class="alert alert-danger">'.$error.'</div>';
					}
				}



				?>
				<!-- End Looping Errors -->
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


?>