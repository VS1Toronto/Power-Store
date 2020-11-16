<?php
	if(!isset($_SESSION)){
		session_start();
	}
?>
<html>
<head>
    <title>Cart</title>
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
    

        <!-- Table moved to function cart_display() in function.php so it -->
        <!-- disappears because of if conditional it is in when the cart is empty -->
        <!-- -->
        <!-- *** This caused a problem in the CSS so I had to move this table into cart_table.php *** -->
        <!-- *** to ensure the CSS still works through includes and I need to maintain the form here *** -->
        <!-- *** in order to maintain the footer in place *** -->
        <!-- -->
        <div class="cart">
            <form method="post" enctype="multipart/form-data">
                <?php
                    if(cart_check()){
                        include("includes/cart_table.php");
                    } else {
                        include("includes/cart_table.php");
                        //echo cart_display();
                    }
                ?>
            </form>
        </div>
        
    <!-- This is for the buy now option checkout page to avoid the large height cause if   class="cart"   is used -->
    <!-- -->
    <div id='buy_now_cart'>    
    </div>


    <?php
        //  This is to deal with issues of the footer moving up the screen if not enough products are present
        //
        include("includes/spacer_cart.php");
        include("includes/footer.php");
    ?>

</body>
</html>