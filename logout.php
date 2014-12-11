<?php 

    // First we execute our common code to connection to the database and start the session 
    require("common.php"); 
     
    // We remove the user's data from the session 
    unset($_SESSION['user']); 
	
	setcookie("user", "", time()-60*60*24*30);
     
    // We redirect them to the login page 
    header("Location: login.php"); 
    die("Redirecting to: login.php");
?>