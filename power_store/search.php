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
        include("includes/header.php"); 
        include("includes/navbar.php");
        echo search();
        include("includes/bodyright.php");
        include("includes/spacer_search.php");
        include("includes/footer.php"); 
    ?>

</body>
</html>