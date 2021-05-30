<?php
//start the session
session_start();

//if user not logged in
if(!isset($_SESSION['id']) && !isset($_SESSION['username'])){
  header("Location:login.php");
  exit();
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>HomePage</title>
</head>
<body>
  <h1>Welcome to <?php print($_SESSION['username']);?></h1>
  <a href="logout.php">Logout</a>
</body>
</html>
