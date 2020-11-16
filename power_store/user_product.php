<?php
	if(!isset($_SESSION)){
		session_start();
	}
?>
<html>
<head>

    <title>User Product</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <!-- All page components moved to files in includes directory and included here -->
    <!-- -->
    <?php 
        include("includes/function.php"); 
        include("includes/header.php"); 
        include("includes/navbar.php");
    ?>

    <!-- This arrangement shows the cart or user product page information -->
    <!-- depending on what has been clicked and what GET variables that sets -->
    <!-- -->
    <div id='user_left'>
            <?php
            	if(isset($_GET['mycart'])){
					echo"<h3>My Cart</h3>
							<form class='mycart' method='post' enctype='multipart/form-data'>
                                <table>
                                    ";echo cart_display();
                                    echo"
                                </table>	
                            </form>
                        <br clear='all' />";
				} else if(isset($_GET['mywishlist'])) {
                    echo "<h3>My Wishlist</h3>
                            <form class='mycart' method='post' enctype='multipart/form-data'>
                                <table>
                                    ";echo wish_display();
                                    echo"
                                </table>	
                            </form>
                        <br clear='all' />";
                } else if(isset($_GET['mypassword'])) {
                    echo "<h3>Update Password</h3>";
                    echo up_password();
                } else if(isset($_GET['myorder'])) {
                    echo "<h3>My Orders</h3>
                        <form class='myorder' method='post' enctype='multipart/form-data'>
                            <table>
                                ";echo user_order();
                                echo"
                            </table>	
                        </form>
                        <br clear='all' />";
                }
                else {
            ?>
                <h3>My Account</h3>
                <?php echo user_product(); 
                } ?>
        </div>
    </div>

    <div id='user_right'>
        <h3>Welcome</h3>
        <?php echo get_user_image() ?>
        <ul>
            <li><a href="user_product.php">My Account</a></li>
            <li><a href="user_product.php?myorder">My Order</a></li>
            <li><a href="user_product.php?mycart">My Shopping Cart</a></li>
            <li><a href="user_product.php?mywishlist">My Wishlist</a></li>
            <li><a href="user_product.php?mypassword">Change Password</a></li>
            <li><a href='logout.php'>Logout</a></li>    
        </ul>
    </div>

    <!--  Seperate CSS in divs above from footer -->
    <!-- -->
    <br clear="all" />
    
    
    <?php
        //  This is to deal with issues of the footer moving up the screen if not enough products are present
        //
        include("includes/spacer_user_product.php");
        include("includes/footer.php");
    ?>

</body>
</html>