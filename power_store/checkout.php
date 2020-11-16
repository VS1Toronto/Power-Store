<?php
	if(!isset($_SESSION)){
		session_start();
	}
?>
<html>
<head>

    <title>Checkout</title>
    <link rel="stylesheet" href="css/style.css" />
    <script src="js/jquery.js"></script>
    <script src="js/cycle.js"></script>

</head>
<body>
    
    <!-- All page components moved to files in includes directory and included here -->
    <!-- -->
    <?php 
        include("includes/function.php"); 
        include("includes/header.php"); 
        include("includes/navbar.php");
        $user_email=$_SESSION['user_email'];
        if(!isset($_SESSION['user_email'])) {
            echo "<script>window.open('index.php', '_self');</script>";
        }
    ?>
    
    <div id='checkout_user'>
        <h3>Your Loggin id is : <?php echo $user_email ?></h3>
        <h2>Delivery Address</h2>
        
        <div id='checkout_user_left'>
            <?php echo checkout_user_address(); S?>
        </div>
        
        <div id='checkout_user_right'>
            <?php echo up_user_checkout(); S?>
        </div>
        
        <br clear="all" />
        
        <h2>Order Summary</h2>
        <div class="buy_now_cart" style="padding-bottom:100px">
            <form method="post" enctype="multipart/form-data">
                <table cellpadding="0" cellspacing="0">
                    <?php echo single_product(); ?>
                </table>
            </form>
        </div>
        
        <br clear="all" />

        <!-- This is here to maintain correct spacing for mobile phones -->
        <!-- -->
        <div class='buy_now_cart'>

        </div>
    </div>
    
    <br clear="all" />
    
    <?php
        include("includes/footer.php");
    ?>

</body>
</html>