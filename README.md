# Power-Store

Live example hosted on server :

https://vs1toronto.com/power_store/index.php

User journey showcasing funcunionality :

https://www.behance.net/gallery/89806183/Php-Power-Store



In order to access admin panel when administrator logs in you have to manually insert that
administrators email address in the directory   power_store_main/power_store/admin/admin_index.php   
by removing the section of the code that instructs   INSERT YOUR EMAIL ADDRESS HERE   and replacing it 
with the administrators email address and the application will pick that up when you log in with the correct
administrator email address and the correct password for that account in the login section on the main index page.


Example :


	<?php
		if(!isset($_SESSION)){
			session_start();
		}
		
		//----------------------------------------------------------------------------------------------------
		//	In order to access admin panel when administrator logs in you have to manually insert that
		//	administrators email address here and the application will pick that up when you log in with
		//	the administrator email address and the correct password for that account in the login section
		//	on the index page
		//
		if(!isset($_SESSION['user_email']) && $_SESSION['user_email'] != "INSERT YOUR EMAIL ADDRESS HERE") {
			echo "<script>alert('You Do Not Have Permission to Enter Admin Panel');</script>";
			echo "<script>window.open('../index.php','_self');</script>";
			exit();
		}
		//----------------------------------------------------------------------------------------------------
	?>
	<html>
	<head>
		<title>Admin Panel</title>
		<link rel="stylesheet" href="css/style.css" />
	</head>
	<body>

		<!-- All page components moved to files in includes directory and included here -->
		<!-- -->
		<?php 
			include("includes/admin_header.php"); 
			include("includes/admin_navbar.php");
			include("includes/admin_bodyleft.php"); 
			include("includes/admin_bodyright.php");
			include("includes/admin_footer.php"); 
		?>


	</body>
	</html>
