<?php
	// function to set the title for every page

	function getallcats() {

		global $con;

		$stmt = $con->prepare("SELECT * FROM categories WHERE Parent = 0");

		$stmt->execute();

		$cats = $stmt->fetchAll();

		return $cats;

	}

	function getTitle() {

		global $pageTitle;
		if (isset($pageTitle)) {
			echo $pageTitle;
		} else {
			echo "Default";
		}

	}
	// function to get back to home page if there is an error or somthing done(redirect)
	/* parameters 1-error message 2- seconds before redirected
	**redirectHome V1.0
	*/
	function redirectHome($errorMsg,$seconds = 3) {

		echo "<div class='alert alert-danger text-center'>$errorMsg</div>";
		echo "<div class='alert alert-info text-center'>Your Are Going to be Directed To Home Page in $seconds Seconds</div>";
		header("refresh:$seconds;url=index.php");
		exit();
	}

	// function to get back to home page if there is an error or somthing done(redirect)
	/* parameters 1-the message, 2-The link you will be redirected to, 3- seconds before redirected
	**redirectHome V2.0
	*/
	function redirect($themsg,$url=null,$seconds = 3) {

		if ($url ===null) {

			$url = 'index.php';

		} else {

			if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !=='') {
				$url = $_SERVER['HTTP_REFERER'];
				$link = 'Previous Page';
			} else{
				$url='index.php';
				$link = 'Home Page';
			}
		}

		echo $themsg;
		echo "<div class='alert alert-info text-center'>Your Are Going to be Directed To $link in $seconds Seconds</div>";
		header("refresh:$seconds;url=$url");
		exit();
	}
	//
	/** Function to Check Items in Database
	***Parameters 1-$select-> The item to select , 2-$from-> the table to select from
	***3-$value-> The value of select
	*/
	function checkItem($select,$from,$value) {

		global $con;

		$statement  = $con->prepare("SELECT $select FROM $from WHERE $select = ?");

		$statement->execute(array($value));

		$count = $statement->rowCount();

		return $count;


	}

	/**
	***COUNTITEMS V1.0
	***COUNT NUMBER OF ITEMS
	***FUNCTION TO COUNT TOTAL NUMBERS
	*** First parametar $item-> That you want to count
	***	Second parametar $table-> The table that item in it
	*/
	function countItems($item,$table) {

		global $con;

		$stm2 = $con->prepare("SELECT COUNT($item) FROM $table");

		$stm2->execute();

		return $stm2->fetchColumn();
	}

	/*
	***Get Latest Records Function V1.0
	***FUNCTION TO GET LATEST ITEM FROM DATABASE
	***$select-> Field to select
	***$table-> table to select from it
	***$order-> The ordering by 
	***$limin-> number of records to show
	*/

	function getLatest($select,$table,$order,$limit = 5) {

		global $con;

		$getStmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");

		$getStmt->execute();

		$row = $getStmt->fetchAll();

		return $row;


	}