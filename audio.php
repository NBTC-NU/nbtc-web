<?php

if (!isset($_COOKIE["user"])){
  header('Location: login.php');
  exit;
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
					<li class="active"><a href="audio.php">Audio Stream</a>
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
			<h2>Audio Stream</h2>
			<br>
			<div id="Show_Video"> 
			</div>
		</div>

		
		<hr>
		
		<div class="jumbotron">
			<h2>Remote control</h2>
			<br>
			<div class="row">

				<div class="col-xs-3">
				</div>
				
				<div class="col-xs-3">
					<center>
						<h3>Channel</h3>
					</center>

					<br>
					<br>

					<div class="input-group number-spinner">
						<span class="input-group-btn data-dwn">
							<button class="btn btn-default btn-info" data-dir="dwn"><span class="glyphicon glyphicon-minus"></span></button>
						</span>
						<input type="text" class="form-control text-center" value="1" min="1" max="68">
						<span class="input-group-btn data-up">
							<button class="btn btn-default btn-info" data-dir="up"><span class="glyphicon glyphicon-plus"></span></button>
						</span>
					</div>

					
				</div>
				
				<div class="col-xs-3">
					<center>
						<h3>Volume</h3>
						<div style="height: 100px">
							<div id="shor" class="slider svert"></div>
						</div>
					</center>
				
				</div>
				<div class="col-xs-3">
				
				</div>
			</div>
		</div>
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
        <script src="noUiSlider/jquery.nouislider.all.min.js"></script>
		<script>
		$(function() {
			var action;
			$(".number-spinner button").mousedown(function () {
				btn = $(this);
				input = btn.closest('.number-spinner').find('input');
				btn.closest('.number-spinner').find('button').prop("disabled", false);

				if (btn.attr('data-dir') == 'up') {
					action = setInterval(function(){
						if ( input.attr('max') == undefined || parseInt(input.val()) < parseInt(input.attr('max')) ) {
							input.val(parseInt(input.val())+1);
						}else{
							btn.prop("disabled", true);
							clearInterval(action);
						}
					}, 50);
				} else {
					action = setInterval(function(){
						if ( input.attr('min') == undefined || parseInt(input.val()) > parseInt(input.attr('min')) ) {
							input.val(parseInt(input.val())-1);
						}else{
							btn.prop("disabled", true);
							clearInterval(action);
						}
					}, 50);
				}
			}).mouseup(function(){
				clearInterval(action);
			});
		});
		</script>
		
        <script>
			
            $(document).ready(function() {
                $.material.init();
				
				jwplayer('Show_Video').setup({
					file: 'rtmp://10.46.30.125/oflaDemo/test',
					width: '100%',
					height:'30',
					mute: 'false',
					autostart: 'true',
					repeat: 'true'
				});
				
				$("#shor").noUiSlider({
                    orientation: "vertical",
                    start: 40,
                    connect: "lower",
                    range: {
                        min: 0,
                        max: 100
                    }
                });
            });
        </script>
    </body>
</html>