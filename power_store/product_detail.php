<?php
	if(!isset($_SESSION)){
		session_start();
	}
?>
<html>
<head>
    <title>Product Detail</title>
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>

    <!-- All page components moved to files in includes directory and included here -->
    <!-- -->
    <?php 
        include("includes/function.php"); 
        include("includes/header.php"); 
        include("includes/navbar.php");
    ?>

    <!-- This was necessary to prevent the CSS in the above includes affecting the product details -->
    <!-- -->
    <br clear="all" />

    <?php
        echo product_details();
        include("includes/footer.php"); 
    ?>


</body>
</html>