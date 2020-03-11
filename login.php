<?php
	ob_start();
 	session_start();

	$pageTitle = 'Login';

	if (isset($_SESSION['user'])) {
		header('Location: index.php');
	}

	include 'init.php'; 

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		if (isset($_POST['login'])) {

			$username = $_POST['username'];
			$password = $_POST['password'];
			$hashedpass = sha1($password);

			// check if the user exist

			$stmt = $con->prepare("SELECT
										UserID,Username, Password
										FROM
											users
										WHERE
											Username = ?
										AND
											Password = ?
										");

			$stmt->execute(array($username,$hashedpass));
			$get = $stmt->fetch();
			$count = $stmt->rowCount();

			// if count >1 so database contains data about this uesrname

			if ($count>0) {

				$_SESSION['user'] = $username;

				$_SESSION['id'] = $get['UserID'];
				
				header('Location: index.php');

				exit();

			}

		} else {

			$formErrors = array();

			if (isset($_POST['username'])) {
				
				$filteredUser = filter_var($_POST['username'],FILTER_SANITIZE_STRING);

				if (strlen($filteredUser)<3) {
					
					$formErrors[] = 'Username can not be less than 3 letters';

				}
			}

			if (isset($_POST['password']) && isset($_POST['password2'])) {

				if (empty($_POST['password'])) {
					$formErrors[] = 'Password cant be empty';
				}
				
				$pass1 = sha1($_POST['password']);
				$pass2 = sha1($_POST['password2']);

				if($pass1 !== $pass2) {
					
					$formErrors[] = 'Password are not match';

				}
			}

			if (isset($_POST['email'])) {
				
				$filteredEmail = filter_var($_POST['email'],FILTER_SANITIZE_EMAIL);

				if (filter_var($filteredEmail,FILTER_VALIDATE_EMAIL) != true) {
					
					$formErrors[] = 'This is not valid Email';

				}
			}
			if (empty($formErrors)) {

					// Check if user is exsist in database

					$check = checkItem("Username","users",$_POST['username']);

					if ($check == 1) {

						$formErrors[] = "This Username is exist";

					} else {

						// inesert form info into  database

						$stmt = $con->prepare("INSERT INTO 
												users (Username,Password,Email,ReqStatus,DOR)
												VALUES(:zuser , :zpass , :zmail ,0,now())");
						$stmt->execute(array(

							'zuser' => $_POST['username'],
							'zpass'	=> sha1($_POST['password']),
							'zmail' => $_POST['email']
						));

						// Echo Success Message

						$successMsg = "Thanks For Your Registeration <br> Now Wait For Admin To activate Your Account";
					}
			}
		}
	}

?>

<div class="container login-page">
	<h1 class="text-center">
		<span data-class="login" class="selected">Login</span> |
		<span data-class="signup">Signup</span>
	</h1>
	<!--Start Login Form-->
	<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
		<input class="form-control" type="text" name="username" autocomplete="off" placeholder="Type Your UserName" required="">
		<input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Type Your Password" required="">
		<input class="btn btn-primary btn-block" name="login" type="submit" value="Login">
	</form>
	<!--End Login Form-->
	<!--Start SignUp Form-->
	<form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
		<input class="form-control" pattern=".{3,}" title="Username cant be less than 3 charachters" type="text" name="username" autocomplete="off" placeholder="Your UserName" required="">
		<input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Type Your Password" minlength="6">
		<input class="form-control" type="password" name="password2" autocomplete="new-password" placeholder="Type Your Password Again">
		<input class="form-control" type="email" name="email" placeholder="Your E-Mail Must Be Valid">
		<input class="btn btn-success btn-block" name="signup" type="submit" value="Signup">
	</form>
	<!--End SignUp Form-->
	<div class="the-errors text-center">
		<?php

		if (!empty($formErrors)) {
			
			foreach ($formErrors as $error) {
				
				echo '<div class="alert alert-danger text-center">'. $error .'</div>';

			} 
		}

		if (isset($successMsg)) {
			
			echo '<div class="alert alert-success text-center">'. $successMsg .'</div>';

		}

		?>
	</div>
</div>

<?php include 'includes/templates/footer.php'; 
ob_end_flush();
?>