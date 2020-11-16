<?php
	if(!isset($_SESSION)){
		session_start();
	}
?>
<html>
<head>

    <title>Power Store</title>
    <link rel="stylesheet" href="css/style.css" />
    <script src="js/jquery.js"></script>
    <script src="js/cycle.js"></script>

    <!-- This slider code is calling the js directory for the method and referencing  -->
    <!-- bodyleft.php where the images are sourced and held in a <div> with id of slide -->
    <!-- -->
    <script>
        $('#slide').cycle('all');
    </script>
    
</head>
<body>
    <!-- All page components moved to files in includes directory and included here -->
    <!-- -->
    <?php 
        include("includes/function.php"); 
        include("includes/header.php"); 
        include("includes/navbar.php");
        include("includes/bodyleft.php"); 
        include("includes/bodyright.php");
        include("includes/footer.php");
        echo add_cart(); 
        echo signup();
        echo add_wishlist();
    ?>

</body>
</html>