<?php
	session_start();
	$noNavBar = '';
	$pageTitle = 'Login';
	if (isset($_SESSION['Username'])) {
		header('Location: dashboard.php');
	}
	include "init.php";
	// check if the user coming from http post request
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$username = $_POST['user'];
		$password = $_POST['pass'];
		$hashedpass = sha1($password);
	// check if the user exist
		$stmt = $con->prepare("SELECT
									UserID, Username, Password
									FROM
										users
									WHERE
										Username = ?
									AND
										Password = ?
									AND
										GroupID = 1
									LIMIT 1");
		$stmt->execute(array($username,$hashedpass));
		$row = $stmt->fetch();
		$count = $stmt->rowCount();
	// if count >1 so database contains data about this uesrname
		if ($count>0) {
			$_SESSION['Username'] = $username; //REGISTER SESSION NAME
			$_SESSION['ID'] = $row['UserID']; // REGISTER SESSION ID
			header('Location: dashboard.php');
			exit();
		}
	}
?>
	<form class="login" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
		<h4>Admin Login</h4>
		<input class="form-control input" type="text" name="user" placeholder="Username" autocomplete="off">
		<input class="form-control" type="password" name="pass" placeholder="Password" autocomplete="new-password">
		<input class="btn btn-primary btn-block" type="submit" value="Login">
	</form>
<?php
	include $tpl . 'footer.php';
?>
