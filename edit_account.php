<?php 

if (!isset($_COOKIE["user"])){
  header('Location: login.php');
  exit;
}

$result = -1;

    // First we execute our common code to connection to the database and start the session 
    require("common.php"); 
     
    // At the top of the page we check to see whether the user is logged in or not 
    if(empty($_SESSION['user'])) 
    { 
        // If they are not, we redirect them to the login page. 
        header("Location: login.php"); 
         
        // Remember that this die statement is absolutely critical.  Without it, 
        // people can view your members-only content without logging in. 
        die("Redirecting to login.php"); 
    } 
     
    // This if statement checks to determine whether the edit form has been submitted 
    // If it has, then the account updating code is run, otherwise the form is displayed 
    if(!empty($_POST)) 
    { 
        // Make sure the user entered a valid E-Mail address 
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) 
        { 
            die("Invalid E-Mail Address"); 
        } 
         
        // If the user is changing their E-Mail address, we need to make sure that 
        // the new value does not conflict with a value that is already in the system. 
        // If the user is not changing their E-Mail address this check is not needed. 
        if($_POST['email'] != $_SESSION['user']['email']) 
        { 
            // Define our SQL query 
            $query = " 
                SELECT 
                    1 
                FROM users 
                WHERE 
                    email = :email 
            "; 
             
            // Define our query parameter values 
            $query_params = array( 
                ':email' => $_POST['email'] 
            ); 
             
            try 
            { 
                // Execute the query 
                $stmt = $db->prepare($query); 
                $result = $stmt->execute($query_params); 
            } 
            catch(PDOException $ex) 
            { 
                // Note: On a production website, you should not output $ex->getMessage(). 
                // It may provide an attacker with helpful information about your code.  
                die("Failed to run query: " . $ex->getMessage()); 
            } 
             
            // Retrieve results (if any) 
            $row = $stmt->fetch(); 
            if($row) 
            { 
                die("This E-Mail address is already in use"); 
            } 
        } 
         
        // If the user entered a new password, we need to hash it and generate a fresh salt 
        // for good measure. 
        if(!empty($_POST['password'])) 
        { 
			if($_POST['password'] != $_POST['repassword']){
				 die("Don't match between password and re-password."); 
			}
			else{
				$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647)); 
				$password = hash('sha256', $_POST['password'] . $salt); 
				for($round = 0; $round < 65536; $round++) 
				{ 
					$password = hash('sha256', $password . $salt); 
				} 
			}
			
            
        } 
        else 
        { 
            // If the user did not enter a new password we will not update their old one. 
            $password = null; 
            $salt = null; 
        } 
         
        // Initial query parameter values 
        $query_params = array( 
            ':email' => $_POST['email'], 
            ':firstname' => $_POST['firstname'], 
            ':lastname' => $_POST['lastname'], 
            ':user_id' => $_SESSION['user']['id'],
            
        ); 
         
        // If the user is changing their password, then we need parameter values 
        // for the new password hash and salt too. 
        if($password !== null) 
        { 
            $query_params[':password'] = $password; 
            $query_params[':salt'] = $salt; 
        } 
         
        // Note how this is only first half of the necessary update query.  We will dynamically 
        // construct the rest of it depending on whether or not the user is changing 
        // their password. 
        $query = " 
            UPDATE users 
            SET 
                 email = :email 
              ,  firstname = :firstname 
              ,  lastname = :lastname 
				
        "; 
         
        // If the user is changing their password, then we extend the SQL query 
        // to include the password and salt columns and parameter tokens too. 
        if($password !== null) 
        { 
            $query .= " 
                , password = :password 
                , salt = :salt 
            "; 
        } 
         
        // Finally we finish the update query by specifying that we only wish 
        // to update the one record with for the current user. 
        $query .= " 
            WHERE 
                id = :user_id 
        "; 
         
        try 
        { 
            // Execute the query 
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
			$result = 0;
        } 
        catch(PDOException $ex) 
        { 
            // Note: On a production website, you should not output $ex->getMessage(). 
            // It may provide an attacker with helpful information about your code.  
            die("Failed to run query: " . $ex->getMessage()); 
        } 
		
		$_SESSION['user']['email'] = $_POST['email']; 
        $_SESSION['user']['firstname'] = $_POST['firstname']; 
        $_SESSION['user']['lastname'] = $_POST['lastname']; 
        
         
    } 
     
?>



<!DOCTYPE html>
<html lang="en">
    <head>
	    <meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">
		<link rel="shortcut icon" href="">
		<title>NBTC_NU</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/ripples.min.css" rel="stylesheet">
        <link href="css/material-wfont.min.css" rel="stylesheet">
		
	<?
	
	if ($_POST != null && $result == 0){
		print "<script type='text/javascript'>
				$(document).ready(function(){
					alert('บันทึกข้อมูลเรียบร้อย');
				});
				</script>";
	}
	
	?>	
		
		
    </head>

 	    <style type="text/css">
    /* Special class on .container surrounding .navbar, used for positioning it into place. */
.navbar-wrapper {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  z-index: 20;
  margin-top: 20px;
}

/* Flip around the padding for proper display in narrow viewports */
.navbar-wrapper .container {
  padding-left: 0;
  padding-right: 0;
}
.navbar-wrapper .navbar {
  padding-left: 15px;
  padding-right: 15px;
}

.navbar-content
{
    width:320px;
    padding: 15px;
    padding-bottom:0px;
}
.navbar-content:before, .navbar-content:after
{
    display: table;
    content: "";
    line-height: 0;
}
.navbar-nav.navbar-right:last-child {
margin-right: 15px !important;
}
.navbar-footer 
{
    background-color:#DDD;
}
.navbar-footer-content { padding:15px 15px 15px 15px; }
.dropdown-menu {
padding: 0px;
overflow: hidden;
}   


@media ( max-width: 585px ) {
    .input-group span.input-group-btn,.input-group input,.input-group button{
        display: block;
        width: 100%;
        border-radius: 0;
        margin: 0;
    }
    .input-group {
        position: relative;   
    }
    .input-group span.data-up{
        position: absolute;
        top: 0;
    }
    .input-group span.data-dwn{
        position: absolute;
        bottom: 0;
    }
    .form-control.text-center {
        margin: 34px 0;
    }
    .input-group-btn:last-child>.btn, .input-group-btn:last-child>.btn-group{
        margin-left:0;
    }


 </style>
 


    <body>

        <!-- Your site -->

<div class="container">
		<div class="navbar navbar-inverse">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse"> <span class="icon-bar"></span>
				 <span class="icon-bar"></span>
				 <span class="icon-bar"></span>
				</button><a class="navbar-brand" href="#">NBTC-NU</a>
			</div>
			<div class="navbar-collapse collapse navbar-responsive-collapse">
				<ul class="nav navbar-nav">
					<li><a href="main.php">Video Stream</a>
					</li>
					<li><a href="audio.php">Audio Stream</a>
					</li>
					<li><a href="#">OCR</a>
					</li>
				</ul>
				<!--
				<form class="navbar-form navbar-left">
					<input type="text" class="form-control col-lg-8" placeholder="Search">
				</form>
				-->
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Account
											<b class="caret"></b></a>

						<ul class="dropdown-menu">
							<li>
								<div class="navbar-content">
									<div class="row">
										<div class="col-md-5">
											<img src="pic/photo.jpg" alt="Alternate Text" class="img-responsive" />
											<p class="text-center small"> <a href="#">Change Photo</a>
											</p>
										</div>
										<div class="col-md-7"> <span><?=$_SESSION['user']['firstname'].' '.$_SESSION['user']['lastname'];?></span>

											<p class="text-muted small"><?=$_SESSION['user']['email'];?></p>
											<div class="divider"></div> <a href="edit_account.php" class="btn btn-primary btn-sm active">View Profile</a>

										</div>
									</div>
								</div>
								<div class="navbar-footer">
									<div class="navbar-footer-content">
										<div class="row">
											<div class="col-md-6"> <a href="edit_account.php" class="btn btn-default btn-sm">Change Passowrd</a>

											</div>
											<div class="col-md-6"> <a href="logout.php" class="btn btn-default btn-sm pull-right">Sign Out</a>

											</div>
										</div>
									</div>
								</div>
							</li>
						</ul>
					</li>

				</ul>
			</div>
		</div>
		
		<div class="jumbotron">
			<h2>Profile</h2>
			<form action="edit_account.php" method="post">
			<div class="row">
					  <div class="col-xs-6">
						  <div class="form-group">
								<label for="exampleInputEmail1">ชื่อผู้ใช้
								:</label><br>
								<input class="form-control" type="text" value="<?php echo htmlentities($_SESSION['user']['username'], ENT_QUOTES, 'UTF-8'); ?>" disabled>
							</div>
							
							
						  <div class="form-group">
								<label for="exampleInputEmail1">ชื่อ</label><br>
								<input class="form-control" name="firstname" type="text" value="<?php echo htmlentities($_SESSION['user']['firstname'], ENT_QUOTES, 'UTF-8'); ?>">
						  </div>
						  

						 <div class="form-group">
                            <label for=
                            "exampleInputEmail1">Create new a password:</label><br>
                            <input class="form-control" name="password" type="password" value=""><br><i>หากไม่ต้องการเปลี่ยนแปลงรหัสผ่าน ให้เว้นว่างไว้</i><br>
                           
                        </div>
						  
							
					  </div>
					  <div class="col-xs-6">
							
						   <div class="form-group">
								<label for="exampleInputEmail1">E-Mail
								Address:</label><br>
								<input class="form-control" name="email" type=
								"text" value=
								"<?php echo htmlentities($_SESSION['user']['email'], ENT_QUOTES, 'UTF-8'); ?>">
							</div>	
							
						  <div class="form-group">
								<label for="exampleInputEmail1">นามสกุล</label><br>
								<input class="form-control" name="lastname" type="text" value="<?php echo htmlentities($_SESSION['user']['lastname'], ENT_QUOTES, 'UTF-8'); ?>">
						  </div>
						  
						 
						  
						 <div class="form-group">
                            <label for=
                            "exampleInputEmail1">Re enter password:</label><br>
                            <input class="form-control" name="repassword" type="password" value="">
                           
                         </div>
						  
					  
					  </div>
			</div>
			<div class="row">
				<div class="col-xs-4">
				
				</div>
				<div class="col-xs-4">
					<input class="btn btn-block btn-primary" type="submit" value="บันทึกข้อมูล">
				</div>
				<div class="col-xs-4">
				
				</div>

			</div>
			</form>
		</div>

		
		<hr>
		
		<hr>
		<div class="footer">
			<div class="row">
			  <div class="col-lg-12">
				<p>Made by <a href="http://thomaspark.me" rel="nofollow">Chakkrit Termritthikun</a>. Contact him at <a href="mailto:thomas@bootswatch.com">chakkritte57@nu.ac.th</a>.</p>
				<p>Code released under the <a href="https://github.com/thomaspark/bootswatch/blob/gh-pages/LICENSE">MIT License</a>.</p>
				<p>Based on <a href="http://getbootstrap.com" rel="nofollow">Bootstrap</a>. Icons from <a href="http://fortawesome.github.io/Font-Awesome/" rel="nofollow">Font Awesome</a>. Web fonts from <a href="http://www.google.com/webfonts" rel="nofollow">Google</a>.</p>
			  </div>
			</div>
		</div>	

	</div>    
		
        <!-- Your site ends -->
        <script src="js/jquery-1.10.2.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
		<script src="js/jwplayer.js"></script>
        <script src="js/ripples.min.js"></script>
        <script src="js/material.min.js"></script>
		<script src="js/snackbar.min.js"></script>
        <script>
            $(document).ready(function() {
                $.material.init();
            });
        </script>
    </body>
</html>