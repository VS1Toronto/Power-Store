<?php
	if(!isset($_SESSION)){
		session_start();
	}
?>
<html>
<head>
    <title>Search</title>
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>

    <!-- All page components moved to files in includes directory and included here -->
    <!-- -->
    <?php 
        include("includes/function.php"); 
        include("includes/admin_header.php"); 
        include("includes/admin_navbar.php");
        echo admin_search();
        include("includes/admin_bodyright.php");
        include("includes/admin_footer.php"); 
    ?>

</body>
</html>