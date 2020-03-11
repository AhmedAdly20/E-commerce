<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?php getTitle() ?></title>
	<link rel="stylesheet" type="text/css" href="layout/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="layout/css/style.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="layout/css/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="layout/css/jquery.selectBoxIt.css">
</head>
<body>
  <div class="upper-bar">
  	<div class="container">
      <?php
      if(isset($_SESSION['id'])) {
        $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ?");
        $stmt->execute(array($_SESSION['id']));
        $user = $stmt->fetch();
      }
      if (isset($_SESSION['user'])) { ?>

        <div class="dropdown my-info">
            <img class="img-fluid" alt="Responsive image" src="<?php echo "admin/uploads/photos/" . $user['Photo']; ?>"/>
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                 <?php echo $_SESSION['user']; ?>
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item" href="profile.php">My Profile</a>
            <a class="dropdown-item" href="profile.php#myads">My Items</a>
            <a class="dropdown-item" href="newad.php">Add New Item</a>
            <a class="dropdown-item" href="logout.php">Logout</a>
            </div>
        </div>

        <?php

      } else {

      ?>
          <a href="login.php">
            <span>Login/Signup</span>
          </a>
    <?php } ?>
    </div>
  </div>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container">
  <a class="navbar-brand" href="index.php">HOMEPAGE</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <?php

      foreach (getCat() as $cat) {
      	
      	echo '<li class="nav-item active"><a class="nav-link" href="cateogries.php?pageid=' . $cat['ID'] . '">';
      	echo $cat['Name'];
      	echo '<span class="sr-only">(current)</span></a></li>';
      }

       ?>
    </ul>
  </div>
</div>
</nav>