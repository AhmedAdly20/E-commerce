<?php

	$do='';
	if (isset($_GET['do'])) {
		$do = $_GET['do'];
	} else {
		$do='Manage';
	}
	if ($do == 'Manage') {
		echo "Welcome You Are in Manage Category";
		echo '<a href="page.php?do=Add">Add New Category +</a>';
	} 

	elseif ($do == 'Add') {
		echo "Welcome You Are in Add ";
	}

	else {
		echo "Error" ;
	}