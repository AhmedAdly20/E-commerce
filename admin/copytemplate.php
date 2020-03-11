<?php 

	ob_start();

	session_start();

	$pageTitle = 'Cateogries';

	if (isset($_SESSION['Username'])) {

		include 'init.php';

		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

		if ($do == 'Manage') {

			echo "Welcome to manage page";

		} elseif ($do == 'Add') {

			echo "Welcome to Add page";

		} elseif ($do == 'Insert') {

			echo "Welcome to Add page";

		} elseif ($do == 'Edit') {

			echo "Welcome to Add page";

		} elseif ($do == 'Update') {

			echo "Welcome to Add page";

		} elseif ($do == 'Delete') {

			echo "Welcome to Add page";

		} elseif ($do == 'Activate') {

			echo "Welcome to Add page";

		}

	include $tpl . 'footer.php';	

	} else {

		header('Location: index.php');

		exit();
	}

	ob_end_flush();

?>