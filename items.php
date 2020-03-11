<?php 
	ob_start();
	session_start();

	include 'connect.php';

	$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

	$stmt = $con->prepare("SELECT
								items.*,
								categories.Name AS CatName,
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
								users.UserID = items.MemberID
							WHERE 
								ItemID = ?
							AND
								Approve = 1");

	$stmt->execute(array($itemid));

	$count = $stmt->rowCount();

	$item = $stmt->fetch();

	$pageTitle = $item['Name'];

	include 'init.php';

	if($count > 0){

?>

<h1 class="text-center"><?php echo $item['CatName']; ?></h1>
<div class="container">
	<div class="row">
		<div class="col-md-3 image">
			<img class="img-fluid center-block" src="sams10.png" alt="" />
		</div>
		<div class="col-md-9 item-info">
			<h2><?php echo $item['Name']; ?></h2>
			<p><?php echo $item['Description']; ?></p>
			<ul class="list-unstyled">
				<li>
					<i class="fa fa-calendar fa-fw"></i>
					<span>Added Date</span><?php echo $item['AddDate']; ?>
				</li>
				<li>
					<i class="fa fa-money fa-fw"></i>
					<span>Price</span>: <?php echo $item['Price']; ?>
				</li>
				<li>
					<i class="fa fa-building fa-fw"></i>
					<span>Made In</span>: <?php echo $item['CountryMade']; ?>
				</li>
				<li>
					<i class="fa fa-tags fa-fw"></i>
					<span>Category</span>: <a href="cateogries.php?pageid=<?php echo $item['CatID'] ?>"><?php echo $item['Name'] ?></a>
				</li>
				<li>
					<i class="fa fa-user fa-fw"></i>
					<span>Added By</span>: <a href="#"><?php echo $item['Username']?></a>
				</li>
			</ul>
		</div>
	</div>
	<hr class="custom-hr">
	<!-- Start Add Comment -->
	<?php if(isset($_SESSION['user'])){
	?>
		<div class="row">
			<div class="col-md-offset-3">
				<div class="add-comment">
					<h3>Add Your Comment</h3>
					<form action="<?php echo $_SERVER['PHP_SELF'] .'?itemid=' . $item['ItemID'] ?>" method="POST">
						<textarea name="comment" class="form form-control" placeholder="Write A Comment .." required=""></textarea>
						<input style="margin-bottom: 10px;" class="btn btn-primary" type="submit" value="Add Comment">
					</form>
					<?php 
					if($_SERVER['REQUEST_METHOD'] == 'POST'){

						$comment 	= filter_var($_POST['comment'],FILTER_SANITIZE_STRING);
						$userid 	= $_SESSION['id'];
						$itemid 	= $item['ItemID'];

						if(!empty($_POST['comment'])) {

							$stmt = $con->prepare("INSERT INTO
							comments(Comment,Status,CommentDate,ItemID,UserID)
							VALUES(:zcomment,0,NOW(),:zitem,:zuser)");

							$stmt->execute(array(

								'zcomment' => $comment,
								'zitem'    => $itemid,
								'zuser'	   => $userid

							));

							if ($stmt) {

								echo "<div class='alert alert-success'>Comment Added Successfuly</div>";

							}

						}
					}

					?>
				</div>
			</div>
		</div>
	<!-- End Add Comment -->
	<?php }
		else{
			echo "<div class='alert alert-danger text-center'><a href='login.php'>Login | Register</a> To Add A Comment</div>";
		}
	?>
	<hr class="custom-hr">
	<?php
			$stmt = $con->prepare("SELECT
										comments.*, users.Username
									FROM
										comments
									INNER JOIN
										users
									ON
										users.UserID = comments.UserID
									WHERE
										ItemID = ?
									AND
										Status = 1
									ORDER By
										CID DESC ");

			$stmt->execute(array($itemid));

			$comments = $stmt->fetchAll();


			foreach ($comments as $comment) { 
				$stmt = $con->prepare("SELECT * FROM users WHERE UserID = ?");
       			$stmt->execute(array($comment['UserID']));
        		$user = $stmt->fetch();
				?>
				<div class="comments-box">
					<div class='row'>
						<div class="col-sm-2 text-center">
							<img class="img-fluid img-thumbnail rounded-circle d-block m-auto" src="<?php echo "admin/uploads/photos/" . $user['Photo']; ?>" alt="" />
							<a href="#"><?php echo $comment['Username']; ?></a>	
						</div>
						<div class="col-sm-10">
							<p class="lead"><?php echo $comment['Comment']; ?></p>
						</div>
					</div>
					<hr class="custom-hr">
				</div>
			<?php } ?>

</div> 

<?php

	} else {

		echo "<div class='alert alert-danger text-center'>This Item Might be want to be approved or there is no item with this id</div>";

	}

	include 'includes/templates/footer.php';
	ob_end_flush();

?>