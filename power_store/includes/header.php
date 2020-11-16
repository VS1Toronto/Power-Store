<?php
	if(!isset($_SESSION)){
		session_start();
	}
?>
<div id="header">

<div id="logo">

    <!--    Before Alterations in CSS                                           -->
    <!--                                                                        -->
    <!--    <img src="images/logo.png" style="width: 300px; height: 100px;"/>   -->
    <!--                                                                        -->
    <a href="index.php"><img src="images/logo.png"/></a>
</div>
<!-- End of logo -->


<div id="link">
    <ul>
        <li><a href="https://play.google.com/store/apps/details?id=com.core.android.power_store_app&hl=en_US">Download App</a></li>

            <!-- START OF SESSION CHECK CONDITIONAL -->
            <!-- -->
            <!-- Here we set a conditional to only show the login and signup -->
            <!-- options in the header if the user does not have a session started -->
            <!-- -->

            <?php if(!isset($_SESSION['user_email'])){?>

            <li><a href="#">Signup</a>
                <form method="post" enctype="multipart/form-data">
                    <table>
                        <tr>
                            <td>Enter Your Name</td>
                            <td><input type="text" name="user_name" autocomplete="off"/></td>
                        </tr>
                        <tr>
                            <td>Enter Your Email</td>
                            <td><input type="email" name="user_email" autocomplete="user_email"/></td>
                        </tr>
                        <tr>
                            <td>Upload Your Picture</td>
                            <td><input type="file" name="user_image"/></td>
                        </tr>
                        <tr>
                            <td>Enter Your Address</td>
                            <td><textarea name='user_address' autocomplete="user_address"></textarea></td>
                        </tr>
                        <tr>
                            <td>Enter Your Country</td>
                            <td><input type="text" name='user_country' autocomplete="user_country"/> </td>
                        </tr>
                        <tr>
                            <td>Enter Your State</td>
                            <td><input type="text" name='user_state' autocomplete="off"/> </td>
                        </tr>

                        <tr>
                            <td>Enter Your DOB</td>
                            <td><input type="date" name="user_dob" /></td>
                        </tr>
                        <tr>
                            <td>Enter Your Phone No.</td>
                            <td><input type="tel" name="user_phone" autocomplete="user_phone"/></td>
                        </tr>
                    </table>
                    <center>
                        <button id="signup_button" type="submit" name="user_signup" value="sign up">Sign Up</button>
                        <button id="reset_button" type="reset" name="user_reset" value="reset">Reset</button>
                    </center>
                </form>
            </li>

            <li>
                <a href="#">Login</a>
                <form  method="post" id='login'>
                    <table >
                        <tr>
                            <td>Enter Your Email</td>
                            <td><input type="email" name="user_email" autocomplete="off" /></td>
                        </tr>
                        <tr>
                            <td>Enter Your Password</td>
                            <td><input type="text" name="login_password" autocomplete="off" /></td>
                        </tr>
                    </table>
                    <center>
                            <button id="login_button" type="submit" name="login_button" value="Login">Login</button>
                            <button id="forgot_password_button" type="button" name="forgot_password" value="Forgot Password ?">Reset</button>
                    </center>
                </form>
            </li>

        <!-- END OF SESSION CHECK CONDITIONAL -->
        <!-- -->
        <!-- if conditional from above closed here -->
        <!-- -->
        <?php echo login(); } else { ?>
        <!-- Else conditional makes logout option either appear or disappear -->
        <!-- -->
        <li><a href='logout.php'>Logout</a></li>
        <!-- User email displayed by echoing the session super global array for the user email field  -->
        <!-- -->
        <li><a href='#'><?php echo $_SESSION['user_email']; ?></a>
            <ul>
                <?php if(isset($_SESSION['user_email']) && $_SESSION['user_email'] == "VS1Toronto@hotmail.com"){?>
                    <li><a href='admin/admin_index.php'>Admin</a></li>
                    <li><a href='user_product.php'>My Account</a></li>
                    <li><a href='user_product.php?myorder'>My Orders</a></li>
                    <li><a href='user_product.php?mycart'>My Shopping Cart <span style="float:right; margin-right:5%;"><?php echo cart_count(); ?></span></a></li>
                    <li><a href='user_product.php?mywishlist'>My Wishlist</a></li>
                    <li><a href='user_product.php?mypassword'>Change Password</a></li>
                    <li><a href='logout.php'>Logout</a></li>
                <?php } else { ?>
                    <li><a href='user_product.php'>My Account</a></li>
                    <li><a href='user_product.php?myorder'>My Orders</a></li>
                    <li><a href='user_product.php?mycart'>My Shopping Cart <span style="float:right; margin-right:5%;"><?php echo cart_count(); ?></span></a></li>
                    <li><a href='user_product.php?mywishlist'>My Wishlist</a></li>
                    <li><a href='user_product.php?mypassword'>Change Password</a></li>
                    <li><a href='logout.php'>Logout</a></li>
                <?php } ?>
            </ul>
        </li>
        <?php } ?>
    </ul>
</div>
<!-- End of link -->


<div id="search">
    <form method="get" action="search.php" enctype="multpart/form-data">
        <input type="text" name='user_query' placeholder="Search...">
        <button name="search" id="search_btn"></a>Go</button>
        <button name="cart_btn" id="cart_btn" type='button'><a href='cart.php'>Cart <?php echo cart_count(); ?></button></a><!-- Here this function echoes the cart count in the button -->
    </form>
</div>
<!-- End of search -->

</div>
<!-- End of header -->