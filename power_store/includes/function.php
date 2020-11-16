<?php
	if(!isset($_SESSION)){
		session_start();
	}
?>
<?php

    //-------------------------------------------------------------------------------------------------------------------------------------------------------
    //  SIGNUP
	function signup(){
		include("includes/db.php");
		if(isset($_POST['user_signup'])){

			$user_name=$_POST['user_name'];
			$user_email=$_POST['user_email'];
			
			$user_image=$_FILES['user_image']['name'];
			$user_image_tmp=$_FILES['user_image']['tmp_name'];

			move_uploaded_file($user_image_tmp,"images/user_images/$user_image");
			
			$user_address = $_POST['user_address'];
			$user_country = $_POST['user_country'];
			$user_state = $_POST['user_state'];
			$user_pin_code = $_POST['user_pin_code'];
			$user_dob = $_POST['user_dob'];
			$user_phone = $_POST['user_phone'];
			
            $user_password = mt_rand();
            
            $check_email=$conx->prepare("select * from user where user_email='$user_email'");
            $check_email->setfetchMode(PDO:: FETCH_ASSOC);
            $check_email->execute();
            $user_count = $check_email->rowCount();
            
	

            if($user_count == 1){
				echo"<script>alert('Email Is Already Registerd Please Try Somthing Else')</script>";
			} else {
                $add_user=$conx->prepare("insert into user(user_name,user_email,user_image,user_address,
                                            user_country,user_state,user_pin_code,user_dob,user_phone,
                                            user_password,user_register_date)
                                            values
                                            ('$user_name','$user_email','$user_image','$user_address',
                                            '$user_country','$user_state','$user_pin_code','$user_dob','$user_phone',
                                            '$user_password',NOW())");	
                           
				if($add_user->execute()){
				    $get_info = $conx->prepare("select * from user where user_email='$user_email'");
                    $get_info->setFetchMode(PDO:: FETCH_ASSOC);
                    $get_info->execute();
                    $row_info = $get_info->fetch();
                    
                    $user_email = $row_info['user_email'];
                    $password = $row_info['user_password'];
                    

                    // Email the user their activation link
            		$to = "$user_email";						 
            		$from = "auto_responder@leedavidsoncontentmanagementsystem1.com";
            		$subject = 'Power Store Account Activation';
            		
					$message = "<html>
						<p>Helio new customer your registration details are here, to change your password move the cursor over the email displayed in the header on the main page after logging in with these details and choose change password from list.</p>
						<h5>Your Email : $user_email</h5>
						<h5>Your Password : $password</h5>
						Let's Try To Login By <a href='http://leedavidsoncontentmanagementsystem1.com/power_store/index.php'>Click Here</a>
			        </html>";
			        
            		$headers = "From: $from\n";
                    $headers .= "MIME-Version: 1.0\n";
                    $headers .= "Content-type: text/html; charset=iso-8859-1\n";
            		mail($to, $subject, $message, $headers);
					

                    
                    echo "<script>alert('User Registration Succcessfull Check Your Email We Send Password There !!!')</script>";
                    echo "<script>window.open('index.php','_self');</script>";
				}
				else{
                    echo"<script>alert('Registration Fail Please Try Again')</script>";
				}
			}
        }
    }	
    //-------------------------------------------------------------------------------------------------------------------------------------------------------
    



    
    //-------------------------------------------------------------------------------------------------------------------------------------------------------
    //  LOGIN
    //
    function login(){
		include("includes/db.php");

		if(isset($_POST['login_button'])){
			$email=$_POST['user_email'];
			$password=$_POST['login_password'];

			$select=$conx->prepare("select * from user where user_email='$email' AND user_password='$password'");
			$select->setFetchMode(PDO:: FETCH_ASSOC);
            $select->execute();
            
            //  Get user_id
            //
            $get_user_id=$select->fetch();
            $user_id=$get_user_id['user_id'];

            $check=$select->rowCount();
            if($check == 1){

                $_SESSION['user_email'] = $email;
    
                $ip_address=getIp();

                //  STEP 1
                //
                //  Here once the user logs in we select everything in the normal store
                //  cart that has their ip_address and store it as an array in     $get_user_products
                //
                $get_user_products=$conx->prepare("select * from cart where ip_address='$ip_address'");
                $get_user_products->setFetchMode(PDO:: FETCH_ASSOC);
                $get_user_products->execute();

                while($row=$get_user_products->fetch()):

                    $product_id=$row['product_id'];
                    $quantity=$row['quantity'];

                    //  STEP 2
                    //
                    //  Here once the user logs in we select everything in the users cart 
                    //  that already is in it and has their ip_address and store it as an array in     $user_cart
                    //
                    $user_cart=$conx->prepare("select * from user_cart where product_id='$product_id' AND ip_address='$ip_address' AND user_id='$user_id'");
                    $user_cart->setFetchMode(PDO:: FETCH_ASSOC);
                    $user_cart->execute();
                
                    $count_cart=$user_cart->rowCount();
                    if($count_cart == 1){

                        //  STEP 3
                        //
                        //  Here once the user logs in we select everything in the normal store cart
                        //  that has their ip_address and delete it because we have stored that data in
                        //  a previous step above as an array in     $get_user_products
                        //
                        $delete_cart=$conx->prepare("delete from cart where ip_address='$ip_address'");
                        $delete_cart->execute();
                    } else {
                        $add_to_user_cart=$conx->prepare("insert into user_cart(product_id,quantity,ip_address,user_id)
                                                            values
                                                            ('$product_id','$quantity','$ip_address','$user_id')");
                        $add_to_user_cart->execute();

                        $delete_cart=$conx->prepare("delete from cart where ip_address='$ip_address'");
                        $delete_cart->execute();
                    }
                endwhile;

                echo "<script>alert('Login Successful !!!')</script>";

                echo "<script>window.open('index.php','_self');</script>";

            } else {
                echo "<script>alert('Login Not Successful !!!')</script>";
            }
        }
    }
    //  END LOGIN
    //-------------------------------------------------------------------------------------------------------------------------------------------------------
    


    //-------------------------------------------------------------------------------------------------------------------------------------------------------
    //  CART
    

    //  GET IP ADDRESS FOR USE IN CART
    //
    function getIp() {
		$ip = $_SERVER['REMOTE_ADDR'];
	 
		if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
	 
		return $ip;
    }


    function add_cart() {
        include("includes/db.php");
        if(isset($_POST['cart_button'])) {

            //  This is the value of a hidden field in the product form
            //
            $product_id=$_POST['product_id'];
            $ip_address=getIp();


            if(!isset($_SESSION['user_email'])) {
                //  Check cart to make sure user doesnt enter same product twice
                //
                $check_cart=$conx->prepare("select * from cart where product_id='$product_id' AND ip_address='$ip_address'");
                $check_cart->execute();

                $row_check=$check_cart->rowCount();

                if($row_check == 1){
                    echo "<script>alert('This product has already been added to your cart')</script>";
                } else {
                    $add_cart=$conx->prepare("insert into cart(product_id,quantity,ip_address) values('$product_id','1','$ip_address')");

                    if($add_cart->execute()){
                        echo "<script>window.open('index.php','_self');</script>";
                    } else {
                        echo "<script>alert('Try Again !!!')</script>";
                    }
                }
            } else {
                $user_email=$_SESSION['user_email'];
                $get_user=$conx->prepare("select * from user where user_email='$user_email'");
                $get_user->setFetchMode(PDO:: FETCH_ASSOC);
                $get_user->execute();
                $row_user=$get_user->fetch();
                $user_id=$row_user['user_id'];

                //  Check cart to make sure user doesnt enter same product twice
                //
                $check_cart=$conx->prepare("select * from user_cart where product_id='$product_id' AND ip_address='$ip_address' AND user_id='$user_id'");
                $check_cart->execute();

                $row_check=$check_cart->rowCount();

                if($row_check == 1){
                    echo "<script>alert('This product has already been added to your cart')</script>";
                } else {
                    $add_cart=$conx->prepare("insert into user_cart(product_id,quantity,ip_address,user_id) values('$product_id','1','$ip_address','$user_id')");

                    if($add_cart->execute()){
                        echo "<script>window.open('index.php','_self');</script>";
                    } else {
                        echo "<script>alert('Try Again !!!')</script>";
                    }
                }
            }
        }
    }


    function cart_count(){
        include("includes/db.php");

        $ip_address=getIp();

        //  This toggles between the store cart and the user 
        //  cart depending on whether a user is logged in or not
        //
        if(!isset($_SESSION['user_email'])){
            $get_cart_item=$conx->prepare("select * from cart where ip_address='$ip_address'");
            $get_cart_item->execute();
        } else {
            $user_email=$_SESSION['user_email'];
            $get_user_id=$conx->prepare("select * from user where user_email='$user_email'");
            $get_user_id->setFetchMode(PDO:: FETCH_ASSOC);
            $get_user_id->execute();
            $row=$get_user_id->fetch();
            $user_id=$row['user_id'];

            $get_cart_item=$conx->prepare("select * from user_cart where ip_address='$ip_address' AND user_id='$user_id'");
            $get_cart_item->execute();
        }

        $count_cart=$get_cart_item->rowCount();

        echo $count_cart;
    }


    function cart_check(){

        include("includes/db.php");

        $ip_address=getIp();

        $get_cart_item=$conx->prepare("select * from cart where ip_address='$ip_address'");
        $get_cart_item->setFetchMode(PDO:: FETCH_ASSOC);
        $get_cart_item->execute();

        //  For checking cart and echoing <h2> message telling user if its empty
        //
        $cart_empty=$get_cart_item->rowCount();

        $net_total = 0;

        if($cart_empty == 0) {
            return 0;
        } else {
            return 1;
        }
    }
    
    
    
    function user_order() {
        include("includes/db.php");

        $user_email = $_SESSION['user_email'];

        $get_user = $conx->prepare("select * from user where user_email='$user_email'");
        $get_user->setFetchMode(PDO:: FETCH_ASSOC);
        $get_user->execute();
        $row_user=$get_user->fetch();
        $user_id=$row_user['user_id'];
        $ip_address = getIp();
        
        $get_payment = $conx->prepare("select * from payment where user_id='$user_id'");
        $get_payment->setFetchMode(PDO:: FETCH_ASSOC);
        $get_payment->execute();
        echo "<table cellpadding='0' cellspacing='0'>
                <tr>
                    <th>Invoice Number</th>
                    <th>Product Name</th>
                    <th>Image</th>
                    <th>Date</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>";
        
        while($row = $get_payment->fetch()):
            $product_id = $row['product_id'];
            $quantity = $row['quantity'];
            $amount = $row['amount'];
            $transaction_id = $row['transaction_id'];
            $date = $row['payment_date'];
            $status = $row['status'];
            
			$get_product=$conx->prepare("select * from products where product_id='$product_id'");
			$get_product->setFetchMode(PDO:: FETCH_ASSOC);
            $get_product->execute();
            $row_product=$get_product->fetch();
            
            echo "<tr>
                    <td>$transaction_id</td>
                    <td>".$row_product['product_name']."</td>
                    <td><img src='images/product_images/".$row_product['product_image_1']."'</td>
                    <td>$date</td>
                    <td>$amount</td>
                    <td>$quantity</td>
                    <td>$status</td>";
                    
                    
                    if($status == 'Complete') {
                        echo "<td>Completed</td>";
                    } else if($status == 'Cancelled')  {
                        echo "<td>Cancelled</td>";
                    } else {
                        echo "<td><a class='myorder_cancel' href='user_product.php?myorder&cancel=".$row['payment_id']."'>Cancel</a></td>";
                    }


                  echo "</tr>";  
        endwhile;
        if(isset($_GET['cancel'])) {
            $payment_id = $_GET['cancel'];
            
            $update_order = $conx->prepare("update payment set status='Cancelled' where payment_id='$payment_id' AND user_id='$user_id'");
            
            if($update_order->execute()) {
                echo "<script>alert('Order Cancelled Successfully')</script>";
                echo "<script>window.open('user_product.php?myorder', '_self')</script>";
            }
        }
    }



    function wish_display() {
        include("includes/db.php");

        $user_email = $_SESSION['user_email'];

        $get_user = $conx->prepare("select * from user where user_email='$user_email'");
        $get_user->setFetchMode(PDO:: FETCH_ASSOC);
        $get_user->execute();
        $row_user=$get_user->fetch();
        $user_id=$row_user['user_id'];
        $ip_address = getIp();
        
        
        //  Alert Tool
        //
        //echo "<script>alert('".$ip_address."')</script>";
        
        
        $get_cart_item=$conx->prepare("select * from user_wishlist where ip_address='$ip_address' AND user_id='$user_id'");
        $get_cart_item->setFetchMode(PDO:: FETCH_ASSOC);
        $get_cart_item->execute();


        //  For checking cart and echoing <h2> message telling user if its empty
        //
        $cart_empty=$get_cart_item->rowCount();

        $net_total = 0;

			if($cart_empty == 0) {
				echo "<center><h2>No Product Found in Cart </h2></center>";
				echo "<center><button id='cart_empty_button' ><a id='cart_empty_button_a_tag' href='index.php'>Store Page</a></button></center>";
			} else {

				//  $_POST['quantity']   here comes from the form field name below   -   name='quantity[".$row_cart['cart_id']."]'
				//
				if(isset($_POST['update_quantity'])){
					$quantity = $_POST['quantity'];

					foreach($quantity as $key => $value){   // $value holds quantity here
						$update_quantity = $conx->prepare("update user_wishlist set quantity='$value' where user_wish_id='$key'");              
						if($update_quantity->execute()){
							echo "<script>alert('Quantity Successfully Updated !!!');</script>";
							echo "<script>window.open('user_product.php?mywishlist','_self');</script>";
						}
					}
                }
                while($row_cart=$get_cart_item->fetch()):

                    //  This product_id is aquired from the cart table with the query above and 
                    //  used to identify the correct product in the prouct table with the query below
                    //
					$product_id=$row_cart['product_id'];

					$get_product=$conx->prepare("select * from products where product_id='$product_id'");
					$get_product->setFetchMode(PDO:: FETCH_ASSOC);
                    $get_product->execute();
                    
                    //  Populate variable with array of product data from the products table
                    //
                    $row_product=$get_product->fetch();
    
                    //  The style='width:60px;' was necessary here as it wouldn't listen to its class
                    //
                    echo "<tr>
                        <td><img src='images/product_images/".$row_product['product_image_1']."' /></td>
                        <td>".$row_product['product_name']."</td>
                        <td><input type='text' name='quantity[".$row_cart['user_wish_id']."]' value='".$row_cart['quantity']."' /><input type='submit' name='update_quantity' value='Save' /></td>
                        <td>".$row_product['product_price']."</td>
                        <td><a id='cart_delete' href='delete.php?user_delete_id=".$row_product['product_id']."'>Delete</a></td>
                        <td>";

                        //  PRICE
                        //
                        $quantity=$row_cart['quantity'];
                        $product_price=$row_product['product_price'];
                        
                        //  SUB TOTAL
                        //
                        $sub_total = $product_price * $quantity;
                        echo $sub_total;

                        //  NET TOTAL
                        //
                        $net_total = $net_total + $sub_total;
                        echo "<br>";
                    echo "</td>
                        </tr>
                        <br clear='all' ?>
                        ";
                endwhile;
                echo "<tr>
                        <td></td>
                        <td><a href='index.php'><button class='buy_now' type='button' style='margin-top:20px;'>Continue Shopping</button></a></td>
                        <td><a href='checkout.php'><button class='buy_now' type='button' style='margin-top:20px;'>Checkout</button></a></td>
                        <td></td><td class='my_cart_net_width'><b>Net Total =</b></td>
                        <td><b>$net_total</b></td>
                    </tr>";              
			}	
                    
            //  This is for the checkout button on the cart page
            //
            if(isset($_POST['cart_checkout'])){
			    echo"<script>window.open('checkout.php?checkout_cart','_self')</script>";
            }
    }



    function cart_display(){
        include("includes/db.php");

        $ip_address=getIp();

        //  Conditional to do one thing if there is no user logged in and something else if there is
        //
		if(!isset($_SESSION['user_email'])) {
            $get_cart_item=$conx->prepare("select * from cart where ip_address='$ip_address'");
            $get_cart_item->setFetchMode(PDO:: FETCH_ASSOC);
            $get_cart_item->execute();

            //  For checking cart and echoing <h2> message telling user if its empty
            //
            $cart_empty=$get_cart_item->rowCount();

            $net_total = 0;

            if($cart_empty == 0) {
                echo "<center><h2>No Product Found in Cart </h2></center>";
                echo "<center><button id='cart_empty_button' ><a id='cart_empty_button_a_tag' href='index.php'>Store Page</a></button></center>";
        } else {

            //  $_POST['quantity']   here comes from the form field name below   -   name='quantity[".$row_cart['cart_id']."]'
            //
            if(isset($_POST['update_quantity'])){
                $quantity = $_POST['quantity'];

                foreach($quantity as $key => $value){   // $value holds quantity here
                    $update_quantity=$conx->prepare("update cart set quantity='$value' where cart_id='$key'");              
                    if($update_quantity->execute()){
                        echo "<script>alert('Quantity Successfully Updated !!!');</script>";
                        echo "<script>window.open('cart.php','_self');</script>";
                    }
                }
            }

            while($row_cart=$get_cart_item->fetch()):

                //  This product_id is aquired from the cart table with the query above and 
                //  used to identify the correct product in the prouct table with the query below
                //
                $product_id=$row_cart['product_id'];

                $get_product=$conx->prepare("select * from products where product_id='$product_id'");
                $get_product->setFetchMode(PDO:: FETCH_ASSOC);
                $get_product->execute();

                //  Populate variable with array of product data from the products table
                //
                $row_product=$get_product->fetch();

                echo "<tr>
                        <td><img src='images/product_images/".$row_product['product_image_1']."' /></td>
                        <td>".$row_product['product_name']."</td>
                        <td><input type='text' name='quantity[".$row_cart['cart_id']."]' value='".$row_cart['quantity']."' /><input type='submit' name='update_quantity' value='Save' /></td>
                        <td>".'$ '.$row_product['product_price']."</td>
                        <td><a id='cart_delete' href='delete.php?delete_id=".$row_product['product_id']."'>Delete</a></td>
                        <td>";

                        //  PRICE
                        //
                        $quantity=$row_cart['quantity'];
                        $product_price=$row_product['product_price'];
                        
                        //  SUB TOTAL
                        //
                        $sub_total = $product_price * $quantity;
                        echo "$ $sub_total";

                        //  NET TOTAL
                        //
                        $net_total = $net_total + $sub_total;

                    echo "</td>
                    </tr>";
            endwhile;
            echo "<tr>
                    <td></td>
                    <td><a href='index.php'><button class='buy_now' type='button'>Continue Shopping</button></a></td>
                    <td><button id='buy_now' name='cart_checkout'>Checkout</button></td>
                    <td></td><td><b>Net Total = </b></td>
                    <td><b>$ $net_total</b></td>
                </tr>";
                if(isset($_POST['cart_checkout'])){
					echo"<script>alert('Please Login Or Signup');</script>";
					echo"<script>window.open('cart.php','_self')</script>";	
				}
			}
        } else {
			$user_email = $_SESSION['user_email'];
			$get_user = $conx->prepare("select * from user where user_email='$user_email'");
			$get_user->setFetchMode(PDO:: FETCH_ASSOC);
			$get_user->execute();
			$row_user=$get_user->fetch();
			$user_id=$row_user['user_id'];
			
			$get_cart_item=$conx->prepare("select * from user_cart where ip_address='$ip_address' AND user_id='$user_id'");
			$get_cart_item->setFetchMode(PDO:: FETCH_ASSOC);
			$get_cart_item->execute();

			//  For checking cart and echoing <h2> message telling user if its empty
			//
			$cart_empty=$get_cart_item->rowCount();

			$net_total = 0;

			if($cart_empty == 0) {
				echo "<center><h2>No Product Found in Cart </h2></center>";
				echo "<center><button id='cart_empty_button' ><a id='cart_empty_button_a_tag' href='index.php'>Store Page</a></button></center>";
			} else {

				//  $_POST['quantity']   here comes from the form field name below   -   name='quantity[".$row_cart['cart_id']."]'
				//
				if(isset($_POST['update_quantity'])){
					$quantity = $_POST['quantity'];

					foreach($quantity as $key => $value){   // $value holds quantity here
						$update_quantity=$conx->prepare("update user_cart set quantity='$value' where user_cart_id='$key'");              
						if($update_quantity->execute()) {
						    if(isset($_GET['checkout_cart'])) {
    							echo "<script>window.open('checkout.php?checkout_cart','_self');</script>";
                            } else {
                                echo "<script>alert('Quantity Successfully Updated !!!');</script>";
    							echo "<script>window.open('cart.php','_self');</script>";
                            }
						}
					}
                }
                while($row_cart=$get_cart_item->fetch()):

                    //  This product_id is aquired from the cart table with the query above and 
                    //  used to identify the correct product in the prouct table with the query below
                    //
					$product_id=$row_cart['product_id'];

					$get_product=$conx->prepare("select * from products where product_id='$product_id'");
					$get_product->setFetchMode(PDO:: FETCH_ASSOC);
                    $get_product->execute();
                    
                    //  Populate variable with array of product data from the products table
                    //
                    $row_product=$get_product->fetch();
    
                    //  The style='width:60px;' was necessary here as it wouldn't listen to its class
                    //
                    echo "<tr>
                        <td><img src='images/product_images/".$row_product['product_image_1']."' /></td>
                        <td>".$row_product['product_name']."</td>
                        <td><input type='text' name='quantity[".$row_cart['user_cart_id']."]' value='".$row_cart['quantity']."' /><input type='submit' name='update_quantity' value='Save' /></td>
                        <td>"."$".$row_product['product_price']."</td>
                        <td><a id='cart_delete' href='delete.php?user_delete_id=".$row_product['product_id']."'>Delete</a></td>
                        <td>";

                        //  PRICE
                        //
                        $quantity=$row_cart['quantity'];
                        $product_price=$row_product['product_price'];
                        
                        //  SUB TOTAL
                        //
                        $sub_total = $product_price * $quantity;
                        echo "$$sub_total";

                        //  NET TOTAL
                        //
                        $net_total = $net_total + $sub_total;
                        echo "<br>";
                    echo "</td>
                        </tr>
                        <br clear='all' ?>
                        ";
                endwhile;
                echo "<tr>
                        <td></td>";
                        if(isset($_GET['checkout_cart'])) {
                            echo " 
                                <td>
                                <form method='post'>
                                <a href='index.php'><button class='buy_now' type='button' style='margin-top:20px;'>Continue Shopping</button></a>
                                <button style='margin-top:20px;' id='buy_now' name='multi_buy'>Cash On Delivery</button></td>";
                                if(isset($_POST['multi_buy'])) {
                                    $get_cart = $conx->prepare("select * from user_cart where user_id='$user_id'");
                                    $get_cart->setfetchMode(PDO:: FETCH_ASSOC);
                                    $get_cart->execute();
                                    while($row_user = $get_cart->fetch()):
                                        
                                        //--------------------------------------------------------------------------------------------------------------------------------------------------
                                        //  GET PRODUCT BASED ON ID OF CURRENT ROW FROM "products" TABLE
                                        //  GET QUANTITY FROM "user_cart" TABLE
                                        //
                                        $product_id = $row_user['product_id'];
                                        $quantity = $row_user['quantity'];
                                        //--------------------------------------------------------------------------------------------------------------------------------------------------
                                        
                                        //--------------------------------------------------------------------------------------------------------------------------------------------------
                                        //  GET PRODUCT BASED ON ID OF EACH ROW ITERATED OVER IN WHILE LOOP
                                        //
                                        $get_product = $conx->prepare("select * from products where product_id='$product_id'");
                                        $get_product->setfetchMode(PDO:: FETCH_ASSOC);
                                        $get_product->execute();
                                        //--------------------------------------------------------------------------------------------------------------------------------------------------
                                        
                                        //--------------------------------------------------------------------------------------------------------------------------------------------------
                                        //  GET PRICE FROM QUERY FETCH RESULT HIGHER UP IN THIS METHOD
                                        //
                                        $row_product = $get_product->fetch();
                                        $price = 0;
                                        //--------------------------------------------------------------------------------------------------------------------------------------------------
                                        
                                        //--------------------------------------------------------------------------------------------------------------------------------------------------
                                        //  AMOUNT FOR PAYMENT TABLE CALCULATION
                                        //
                                        if($row_product['product_discount_price'] >= 1) {
                                            $price = $row_product['product_discount_price'];
                                        } else {
                                            $price = $row_product['product_price'];
                                        }
                                        //--------------------------------------------------------------------------------------------------------------------------------------------------
                                        
                                        $amount = $price * $quantity;
                                        $transaction_id = "ORDER ".substr(mt_rand(),0,10)." RECEIVED";      //   Here we generate a random number for the transaction ID
                                        
                                        //--------------------------------------------------------------------------------------------------------------------------------------------------
                                        //  INSERT ORDER INTO PAYMENT TABLE 
                                        //
                                        $insert = $conx->prepare("insert into payment(user_id,product_id,amount,quantity,transaction_id,ip_address,status,payment_date) 
                                                                                     values 
                                                                                     ('$user_id','$product_id','$amount','$quantity','$transaction_id','$ip_address','Pending',NOW())");
                                        if($insert->execute()) {
                                            

                                        //  GET USER INFORMATION
                                        //
                                        $ip_address = getIp();
                                        $user_email = $_SESSION['user_email'];
                                        $get_user = $conx->prepare("select * from user where user_email='$user_email'");
                                        $get_user->setFetchMode(PDO:: FETCH_ASSOC);
                                        $get_user->execute();
                                        $row_user_get = $get_user->fetch();
                                        $user_id = $row_user_get['user_id'];    
                                        
                                        
                                        //  GET PRODUCT INFORMATION
                                        //
                                        $get_product = $conx->prepare("select * from products where product_id='$product_id'");
                                        $get_product->setFetchMode(PDO:: FETCH_ASSOC);
                                        $get_product->execute();
                                        $row_product = $get_product->fetch();
                                        $main_quantity = $row_product['product_quantity'];

                                            
                                        $user_name = $row_user_get['user_name'];
                                        $user_email = $row_user_get['user_email'];
                                        $password = $row_user_get['user_password'];
                                        
                                        $amount_to_two_decimal = sprintf('%0.2f', round($amount, 2));
                                        $total_to_two_decimal = $amount_to_two_decimal;
                                        
                    
                                        // Email the user their activation link
                                		$to = "$user_email";						 
                                		$from = "auto_responder@leedavidsoncontentmanagementsystem1.com";
                                		$subject = 'Power Store Order Billing';
                                		
                    					$message = "<html>
                    					                <center><p><b>ATTENTION</b></p></center>
                    					                <center><p><b>SIMULATED ORDER FOR DEMONSTRATION PURPOSES ONLY</b></p></center>
                    					                <center><p><b>THIS IS NOT A REQUEST FOR MONEY</b></p></center>
                    					                <br>
                    					                <br>
                            							<center><p>Hello Customer $user_name We Received Your Order And Here Is Your Order Summary</p></center>
                            								<center style='font-family:verdana'>
                            									<table style='font-family:verdana' width='90%'>
                            										<tr>
                            											<center><th style='height:30px; font-size:14px; color:#FFFfff; background:#449944' colspan='6'>Order Details</th></center>
                            										</tr>
                            										<tr>
                            											<th style='height:25px; background:#COCOCO'>Order ID</th>
                            											<th style='height:25px; background:#COCOCO'>Item Name</th>
                            											<th style='height:25px; background:#COCOCO'>Quantity</th>
                            											<th style='height:25px; background:#COCOCO'>RRP Price</th>
                            											<th style='height:25px; background:#COCOCO'>Discount Price if Applicable</th>
                            										</tr>
                            										<tr>
                            											<td style='height:20px;text-align:center'>$transaction_id</td>
                            											<td style='height:20px;text-align:center'>".$row_product['product_name']."</td>
                            											<td style='height:20px;text-align:center'>$quantity</td>
                            											<td style='height:20px;text-align:center'>".$row_product['product_price']."</td>
                            											<td style='height:20px;text-align:center'>".$row_product['product_discount_price']."</td>
                            										</tr>
                            										<tr>
                                                                        <td></td><td></td><td></td><td></td>
                                                                        <td>Amount Payable : </td>
                        
                            											<td style='height:20px'><b>Payable Amount : $total_to_two_decimal</b></td>
                                                                    </tr>
                            									</table>
                            								</center>
                            								<p>To Go To Power Store <a href='http://leedavidsoncontentmanagementsystem1.com/power_store/index.php'>Click Here</a> Happy Shopping Thank You</p>
                            						</html>";
                    			        
                                		$headers = "From: $from\n";
                                        $headers .= "MIME-Version: 1.0\n";
                                        $headers .= "Content-type: text/html; charset=iso-8859-1\n";
                                		mail($to, $subject, $message, $headers);
                                            
                                            
                                            
                                            echo "<script>alert('We Received Your Order')</script>";
                                            echo "<script>window.open('user_product.php?myorder','_self')</script>";
                                        }
                                        //--------------------------------------------------------------------------------------------------------------------------------------------------
                                        
                                        //---------------------------------------------------------------------------------------------------------------------------------------------------
                                        //  UPDATE QUANTITY OF STOCK FOR THIS PRODUCT IN PRODUCTS TABLE
                                        //
                                        //  Here   $row_product['product_quantity']   is from the "product" Table and   $quantity   is from the "user_cart" table
                                        //
                                        $new_quantity = $row_product['product_quantity'] - $quantity;
                                        $update_quantity = $conx->prepare("update products set product_quantity='$new_quantity' where product_id='$product_id'");
                                        $update_quantity->execute();
                                        //--------------------------------------------------------------------------------------------------------------------------------------------------
                                       
                                    endwhile;   
                                    
                                    //------------------------------------------------------------------------------------------------------------------------------------------------------
                                    //  DELETE USER CART ENTRIES AFTER THEY HAVE BEEN PLACED IN THE PAYMENTS TABLE IN THE WHILE LOOP ABOVE
                                    //
                                    $delete_cart = $conx->prepare("delete from user_cart where user_id='$user_id'");
                                    $delete_cart->execute();
                                    //------------------------------------------------------------------------------------------------------------------------------------------------------
                                }
                        } else {
                            echo " 
                                <td><a href='index.php'><button class='buy_now' type='button' style='margin-top:20px;'>Continue Shopping</button></a></td>
                                <td><form method='post'><button style='margin-top:20px;' id='buy_now' name='checkout_cart'>Checkout</button></form></td>";
                        }
 
                        echo "<td></td><td class='my_cart_net_width'><b>Net Total =</b></td>
                        <td><b>$$net_total</b></td>
                    </tr>";   
                    
                    //  This is for the checkout button on the cart page
                    //
                    if(isset($_POST['checkout_cart'])){
					echo"<script>window.open('checkout.php?checkout_cart','_self')</script>";	
				}
                    
			}
		}			
    }
    //
    //  <td></td><td></td><td></td> - These are spacing out the buttons on the cart page
    

	
	function delete_cart_items(){
		include("includes/db.php");
		if(isset($_GET['delete_id'])){
            $product_id=$_GET['delete_id'];
            
            //  Call getIP() function to get user ip address
            //
            $ip_address=getIp();
            
			$delete_product=$conx->prepare("delete from cart where product_id='$product_id' AND ip_address='$ip_address'");	
			if($delete_product->execute()){
                echo "<script>alert('Product deleted from cart !!!')</script>";
                echo "<script>window.open('cart.php','_self');</script>";
			}	
		}	
	}


    function delete_user_cart(){
        include("includes/db.php");

        //  if   user_delete_id   is set meaning has the user logged in which means the else conditional in the   display_cart() 
        //  method is now the code flow here and the   user_delete_id   GET variable has been set by the user pushing delete at that point
        //
        if(isset($_GET['user_delete_id'])){

            //  Use this to identify which product to delete
            //
            $product_id=$_GET['user_delete_id'];

			$user_email = $_SESSION['user_email'];
			$get_user = $conx->prepare("select * from user where user_email='$user_email'");
			$get_user->setFetchMode(PDO:: FETCH_ASSOC);
			$get_user->execute();
			$row_user=$get_user->fetch();
            $user_id=$row_user['user_id'];
            
            $delete_product=$conx->prepare("delete from user_cart where product_id='$product_id' AND user_id='$user_id'");
            
            if($delete_product->execute()) {
                echo "<script>alert('Product deleted from cart !!!')</script>";
                echo "<script>window.open('cart.php','_self');</script>";
            }
        }
    }
    //  END CART
    //-------------------------------------------------------------------------------------------------------------------------------------------------------



    //-------------------------------------------------------------------------------------------------------------------------------------------------------
    //  WISHLIST
    function add_wishlist() {
        include("includes/db.php");
        if(isset($_POST['wish_button'])){
            if(!isset($_SESSION['user_email'])){
                echo "<script>alert('Login or Signup First');</script>";
            } else {
               
                $user_email=$_SESSION['user_email'];

                $get_user=$conx->prepare("select * from user where user_email='$user_email'");
                $get_user->setFetchMode(PDO:: FETCH_ASSOC);
                $get_user->execute();
                $row=$get_user->fetch();
                $user_id=$row['user_id'];
            
       
                //  This comes from a hidden field in the form
                //
                $product_id = $_POST['product_id'];

                //  Call getIP() function to get user ip address
                //
                $ip_address = getIp();
 
                $user_wish = $conx->prepare("select * from user_wishlist where product_id='$product_id' AND ip_address='$ip_address' AND user_id='$user_id'");
                $user_wish->setFetchMode(PDO:: FETCH_ASSOC);
                $user_wish->execute();
                
                $count_wish = $user_wish->rowCount();
                
				if($count_wish == 1){
					echo"<script>alert('The Product Is Already In Your Wishlist');</script>";
					echo "<script>window.open('index.php','_self');</script>";
				}else{
					$add_to_user_wish = $conx->prepare("insert into user_wishlist(product_id,quantity,ip_address,user_id)values('$product_id','1','$ip_address','$user_id')");
					$add_to_user_wish->execute();
					
					echo"<script>alert('Product Added To Your Wishlist');</script>";
					echo "<script>window.open('user_product.php?mywishlist','_self');</script>";
				}
            }
        }
    }
    //  END WISHLIST
    //-------------------------------------------------------------------------------------------------------------------------------------------------------



    //-------------------------------------------------------------------------------------------------------------------------------------------------------
    //  USER PRODUCT PAGE
    //
    function get_user_image() {
        include("includes/db.php");

        $user_email = $_SESSION['user_email'];

        $get_user=$conx->prepare("select * from user where user_email='$user_email'");
        $get_user->setFetchMode(PDO:: FETCH_ASSOC);
        $get_user->execute();

        $row = $get_user->fetch();
        echo "<img src='images/user_images/".$row['user_image']."' />";
    }


	function up_password(){
        include("includes/db.php");
		$user_email=$_SESSION['user_email'];
				
		$get_user=$conx->prepare("select * from user where user_email='$user_email'");
		$get_user->setFetchMode(PDO:: FETCH_ASSOC);
		$get_user->execute();
		
		$row=$get_user->fetch();
		$user_id=$row['user_id'];
		
		echo"<form method='post' enctype='multipart/form-data'>
				<table>
					<tr>
						<td>Enter Current Password</td>
						<td><input type='password' name='current_pass' required='required' maxlength='20' minlength='8' /></td>
					</tr>
					<tr>
						<td>Enter New Password</td> 
						<td><input type='password' name='new_password_one' required='required' maxlength='20' minlength='8' /></td>
					</tr>
					<tr>
						<td>Enter Confirm Password</td>
						<td><input type='password' name='new_password_two' required='required' maxlength='20' minlength='8' /></td>
					</tr>
				</table>
                <center><button id='up_user_password' type='submit' name='up_user_password' value='Save'>Save</button></center>
			</form>";
			
			if(isset($_POST['up_user_password'])){
				$user_email=$_SESSION['user_email'];
                $current_password = $_POST['current_password'];
                $new_password_one = $_POST['new_password_one'];
                $new_password_two = $_POST['new_password_two'];
				
				$check_password = $conx->prepare("select * from user where user_email='$user_email' AND user_password='$current_password'");
				$check_password->setFetchMode(PDO:: FETCH_ASSOC);
				$check_password->execute();

				if(strlen($new_password_one) < 8) {
					echo"<script>alert('Please Enter Above 8 Digit Password')</script>";
                    echo "<script>window.open('user_product.php?mypassword', '_self')</script>";	
				}
				
				if($new_password_one == $new_password_two) {
				    
				}else{
					echo"<script>alert('New Password And Current Password Do Not Match')</script>";
                    echo "<script>window.open('user_product.php?mypassword', '_self')</script>";
				}
				
				if($new_password_one && $new_password_two) {
					$up_password = $conx->prepare("update user set user_password='$new_password_two ' where user_email='$user_email'");
					if($up_password->execute()){
                        echo "<script>alert('Password Updated Successfully !!!')</script>";
                        echo "<script>window.open('user_product.php?mypassword', '_self')</script>";
					}
				}else{
                    echo "<script>alert('Passwords Do Not Match !!!')</script>";
                    echo "<script>window.open('user_product.php?mypassword', '_self')</script>";	
				}	
			}	
	}


    function user_product() {
        include("includes/db.php");

        $user_email = $_SESSION['user_email'];

        $get_user=$conx->prepare("select * from user where user_email='$user_email'");
        $get_user->setFetchMode(PDO:: FETCH_ASSOC);
        $get_user->execute();

        $row = $get_user->fetch();
        $user_id = $row['user_id'];

        echo "<form method='post' enctype='multipart/form-data'>
                <table>
                    <tr>
                        <td>Your Name :</td>
                        <td><input type='text' name='user_name' value='".$row['user_name']."' </td>
                    </tr>
                    <tr>
                        <td>Your Image :</td>
                        <td><input type='file' name='user_image' required </td>
                    </tr> 
                    <tr>
                        <td>Your Address :</td>
                        <td><input type='text' name='user_address' value='".$row['user_address']."' </td>
                    </tr> 
                    <tr>
                        <td>Your Pin Code :</td>
                        <td><input type='text' name='user_pin_code' value='".$row['user_pin_code']."' </td>
                    </tr>
                    <tr>
                        <td>Your Phone :</td>
                        <td><input type='text' name='user_phone' value='".$row['user_phone']."' </td>
                    </tr> 
                </table> 
                <center><button id='user_product_button_left' type='submit' name='up_user_info' value='up_user_info'>Save</button></center>
            </form>";

            if(isset($_POST['up_user_info'])) {
                $user_name = $_POST['user_name'];
                $user_address = $_POST['user_address'];
                $user_pin_code = $_POST['user_pin_code'];
                $user_phone = $_POST['user_phone'];

                if($_FILES['user_image']['tmp_name'] == '') {

                } else {
                    $user_image = $_FILES['user_image']['name'];
                    $user_image_tmp = $_FILES['user_image']['tmp_name'];  
                
                    move_uploaded_file($user_image_tmp, "images/user_images/$user_image");
                    
                    //  Update image column in database using user_id to locate correct record
                    //
                    $up_image = $conx->prepare("update user set user_image='$user_image' where user_id='$user_id'");
                    $up_image->execute();
                }

                $up_user_info = $conx->prepare("update user set user_name='$user_name',
                                                                user_image='$user_image',
                                                                user_address='$user_address',
                                                                user_pin_code='$user_pin_code',
                                                                user_phone='$user_phone'
                                                                where user_id='$user_id'");
                if($up_user_info->execute()) {
                    echo "<script>window.open('user_product.php','_self')</script>";
                };
            }

    }
    //  END USER PRODUCT PAGE
    //-------------------------------------------------------------------------------------------------------------------------------------------------------



    //-------------------------------------------------------------------------------------------------------------------------------------------------------
    //  ELECTRONICS FUNCTION ALTERED TO GIVE ALL PRODUCT ROWS FROM WHILE LOOP BUT ORIGINAL WAY OF DOING IT
    //  WITH AN INDIVIDUAL FUNCTION FOR EACH ROW IS BELOW THIS FUNCTION AND WILL BE CHANGED BACK TO LATER AS
    //  I PREFER COMPONENTS TO BE INDIVIDUAL INCASE I NEED TO MAKE CHANGES TO A SPECIFIC ONE AT A LATER TIME
    //
    function electronics(){
        include("includes/db.php");

        //---------------------------------------------------------------------------------------------------------------------------------------------------
        //  Query 1 A
        //
        //  Used to give <h3> product name bars dynamically
        //
        $fetch_category=$conx->prepare("select * from main_categories");
        $fetch_category->setFetchMode(PDO:: FETCH_ASSOC);
        $fetch_category->execute();

        while($row_category=$fetch_category->fetch()):  // fetch()   function here fetches our whole query
        
        //  Used in other queries below as a means of identifying the correct row of data
        //
        $category_id=$row_category['category_id'];
        
        echo "<h3>".$row_category['category_name']."</h3><ul>";
        //---------------------------------------------------------------------------------------------------------------------------------------------------
    

        //---------------------------------------------------------------------------------------------------------------------------------------------------
        //  Query 1 B
        //
        //  Used to give <h3> product name bars dynamically
        //
        //  Use to force last order created to top so it can be worked on
        //
        //  $fetch_product=$conx->prepare("select * from products where category_id='$category_id' ORDER BY 1 DESC LIMIT 0,3");
        //
        $fetch_product=$conx->prepare("select * from products where category_id='$category_id'LIMIT 0,3");
        $fetch_product->setFetchMode(PDO:: FETCH_ASSOC);
        $fetch_product->execute();

            while($row_product=$fetch_product->fetch()):
                echo "<li>
                        <form method='post' enctype='multipart/form-data'>
                            <a href='product_detail.php?product_id=".$row_product['product_id']."'>
                                <h4>".$row_product['product_name']."</h4>
                                <img src='images/product_images/".$row_product['product_image_1']."'/>
                                <center>
                                    <button id='product_button'><a href='product_detail.php?product_id=".$row_product['product_id']."'>View</a></button>
                                    <input type='hidden' value='".$row_product['product_id']."' name='product_id' />
                                    <button id='product_button' name='cart_button'>Cart</button>
                                    <button id='product_button' name='wish_button'>Wish</button>
                                </center>
                            </a>
                        </form>
                    </li>";
            endwhile;
            echo "</ul><br clear='all' />";
        endwhile;
        //---------------------------------------------------------------------------------------------------------------------------------------------------
    }

    /*  ORIGINAL ELECTRONICS FUNCTION WILL BE PUT BACK LATER AS i WANT THEM ALL 
        SEPERATE INCASE I WANT TO MAKE CSS CHANGES TO INDIVIDUAL ROWS

    function electronics(){
        include("includes/db.php");

        //---------------------------------------------------------------------------------------------------------------------------------------------------
        //  Query 1 A
        //
        //  Used to give <h3> product name bars dynamically
        //
        $fetch_category=$conx->prepare("select * from main_categories where category_id='1'");
        $fetch_category->setFetchMode(PDO:: FETCH_ASSOC);
        $fetch_category->execute();

        $row_category=$fetch_category->fetch();  // fetch()   function here fetches our whole query
        
        //  Used in other queries below as a means of identifying the correct row of data
        //
        $category_id=$row_category['category_id'];
        
        echo "<h3>".$row_category['category_name']."</h3>";
        //---------------------------------------------------------------------------------------------------------------------------------------------------
    

        //---------------------------------------------------------------------------------------------------------------------------------------------------
        //  Query 1 B
        //
        //  Used to give <h3> product name bars dynamically
        $fetch_product=$conx->prepare("select * from products where category_id='$category_id' LIMIT 0,3");
        $fetch_product->setFetchMode(PDO:: FETCH_ASSOC);
        $fetch_product->execute();

        while($row_product=$fetch_product->fetch()):
            echo "<li>
                    <form method='post' enctype='multipart/form-data'>
                        <a href='product_detail.php?product_id=".$row_product['product_id']."'>
                            <h4>".$row_product['product_name']."</h4>
                            <img src='images/product_images/".$row_product['product_image_1']."'/>
                            <center>
                                <button id='product_button'><a href='product_detail.php?product_id=".$row_product['product_id']."'>View</a></button>
                                <input type='hidden' value='".$row_product['product_id']."' name='product_id' />
                                <button id='product_button' name='cart_button'>Cart</button>
                                <button id='product_button'><a href='#'>Wish</a></button>
                            </center>
                        </a>
                    </form>
                </li>";
        endwhile;
        //---------------------------------------------------------------------------------------------------------------------------------------------------
    }
    */

    function crockery(){
        include("includes/db.php");

        //---------------------------------------------------------------------------------------------------------------------------------------------------
        //  Query 2 A
        //
        //  Used to give <h3> product name bars dynamically
        //
        $fetch_category=$conx->prepare("select * from main_categories where category_id='2'");
        $fetch_category->setFetchMode(PDO:: FETCH_ASSOC);
        $fetch_category->execute();

        $row_category=$fetch_category->fetch();  // fetch()   function here fetches our whole query
        
        //  Used in other queries below as a means of identifying the correct row of data
        //
        $category_id=$row_category['category_id'];
        
        echo "<h3>".$row_category['category_name']."</h3>";
        //---------------------------------------------------------------------------------------------------------------------------------------------------
    

        //---------------------------------------------------------------------------------------------------------------------------------------------------
        //  Query 2 B
        //
        //  Used to give <h3> product name bars dynamically
        $fetch_product=$conx->prepare("select * from products where category_id='$category_id' LIMIT 0,3");
        $fetch_product->setFetchMode(PDO:: FETCH_ASSOC);
        $fetch_product->execute();

        while($row_product=$fetch_product->fetch()):
            echo "<li>
                    <form method='post' enctype='multipart/form-data'>
                        <a href='product_detail.php?product_id=".$row_product['product_id']."'>
                            <h4>".$row_product['product_name']."</h4>
                            <img src='images/product_images/".$row_product['product_image_1']."'/>
                            <center>
                                <button id='product_button'><a href='product_detail.php?product_id=".$row_product['product_id']."'>View</a></button>
                                <input type='hidden' value='".$row_product['product_id']."' name='product_id' />
                                <button id='product_button' name='cart_button'>Cart</button>
                                <button id='product_button'><a href='#'>Wish</a></button>
                            </center>
                        </a>
                    </form>
                </li>";
        endwhile;
        //---------------------------------------------------------------------------------------------------------------------------------------------------
    }



    function dvd(){
        include("includes/db.php");

        //---------------------------------------------------------------------------------------------------------------------------------------------------
        //  Query 3 A
        //
        //  Used to give <h3> product name bars dynamically
        //
        $fetch_category=$conx->prepare("select * from main_categories where category_id='3'");
        $fetch_category->setFetchMode(PDO:: FETCH_ASSOC);
        $fetch_category->execute();

        $row_category=$fetch_category->fetch();  // fetch()   function here fetches our whole query
        
        //  Used in other queries below as a means of identifying the correct row of data
        //
        $category_id=$row_category['category_id'];
        
        echo "<h3>".$row_category['category_name']."</h3>";
        //---------------------------------------------------------------------------------------------------------------------------------------------------
    

        //---------------------------------------------------------------------------------------------------------------------------------------------------
        //  Query 3 B
        //
        //  Used to give <h3> product name bars dynamically
        $fetch_product=$conx->prepare("select * from products where category_id='$category_id' LIMIT 0,3");
        $fetch_product->setFetchMode(PDO:: FETCH_ASSOC);
        $fetch_product->execute();

        while($row_product=$fetch_product->fetch()):
            echo "<li>
                    <form method='post' enctype='multipart/form-data'>
                        <a href='product_detail.php?product_id=".$row_product['product_id']."'>
                            <h4>".$row_product['product_name']."</h4>
                            <img src='images/product_images/".$row_product['product_image_1']."'/>
                            <center>
                                <button id='product_button'><a href='product_detail.php?product_id=".$row_product['product_id']."'>View</a></button>
                                <input type='hidden' value='".$row_product['product_id']."' name='product_id' />
                                <button id='product_button' name='cart_button'>Cart</button>
                                <button id='product_button'><a href='#'>Wish</a></button>
                            </center>
                        </a>
                    </form>
                </li>";
        endwhile;
        //---------------------------------------------------------------------------------------------------------------------------------------------------
    }


    function books(){
        include("includes/db.php");

        //---------------------------------------------------------------------------------------------------------------------------------------------------
        //  Query 4 A
        //
        //  Used to give <h3> product name bars dynamically
        //
        $fetch_category=$conx->prepare("select * from main_categories where category_id='4'");
        $fetch_category->setFetchMode(PDO:: FETCH_ASSOC);
        $fetch_category->execute();

        $row_category=$fetch_category->fetch();  // fetch()   function here fetches our whole query
        
        //  Used in other queries below as a means of identifying the correct row of data
        //
        $category_id=$row_category['category_id'];
        
        echo "<h3>".$row_category['category_name']."</h3>";
        //---------------------------------------------------------------------------------------------------------------------------------------------------
    

        //---------------------------------------------------------------------------------------------------------------------------------------------------
        //  Query 4 B
        //
        //  Used to give <h3> product name bars dynamically
        $fetch_product=$conx->prepare("select * from products where category_id='$category_id' LIMIT 0,3");
        $fetch_product->setFetchMode(PDO:: FETCH_ASSOC);
        $fetch_product->execute();

        while($row_product=$fetch_product->fetch()):
            echo "<li>
                    <form method='post' enctype='multipart/form-data'>
                        <a href='product_detail.php?product_id=".$row_product['product_id']."'>
                            <h4>".$row_product['product_name']."</h4>
                            <img src='images/product_images/".$row_product['product_image_1']."'/>
                            <center>
                                <button id='product_button'><a href='product_detail.php?product_id=".$row_product['product_id']."'>View</a></button>
                                <input type='hidden' value='".$row_product['product_id']."' name='product_id' />
                                <button id='product_button' name='cart_button'>Cart</button>
                                <button id='product_button'><a href='#'>Wish</a></button>
                            </center>
                        </a>
                    </form>
                </li>";
        endwhile;
        //---------------------------------------------------------------------------------------------------------------------------------------------------
    }


    function timepiece(){
        include("includes/db.php");

        //---------------------------------------------------------------------------------------------------------------------------------------------------
        //  Query 5 A
        //
        //  Used to give <h3> product name bars dynamically
        //
        $fetch_category=$conx->prepare("select * from main_categories where category_id='5'");
        $fetch_category->setFetchMode(PDO:: FETCH_ASSOC);
        $fetch_category->execute();

        $row_category=$fetch_category->fetch();  // fetch()   function here fetches our whole query
        
        //  Used in other queries below as a means of identifying the correct row of data
        //
        $category_id=$row_category['category_id'];
        
        echo "<h3>".$row_category['category_name']."</h3>";
        //---------------------------------------------------------------------------------------------------------------------------------------------------
    

        //---------------------------------------------------------------------------------------------------------------------------------------------------
        //  Query 5 B
        //
        //  Used to give <h3> product name bars dynamically
        $fetch_product=$conx->prepare("select * from products where category_id='$category_id' LIMIT 0,3");
        $fetch_product->setFetchMode(PDO:: FETCH_ASSOC);
        $fetch_product->execute();

        while($row_product=$fetch_product->fetch()):
            echo "<li>
                    <form method='post' enctype='multipart/form-data'>
                        <a href='product_detail.php?product_id=".$row_product['product_id']."'>
                            <h4>".$row_product['product_name']."</h4>
                            <img src='images/product_images/".$row_product['product_image_1']."'/>
                            <center>
                                <button id='product_button'><a href='product_detail.php?product_id=".$row_product['product_id']."'>View</a></button>
                                <input type='hidden' value='".$row_product['product_id']."' name='product_id' />
                                <button id='product_button' name='cart_button'>Cart</button>
                                <button id='product_button'><a href='#'>Wish</a></button>
                            </center>
                        </a>
                    </form>
                </li>";
        endwhile;
        //---------------------------------------------------------------------------------------------------------------------------------------------------
    }


    function menswear(){
        include("includes/db.php");

        //---------------------------------------------------------------------------------------------------------------------------------------------------
        //  Query 6 A
        //
        //  Used to give <h3> product name bars dynamically
        //
        $fetch_category=$conx->prepare("select * from main_categories where category_id='6'");
        $fetch_category->setFetchMode(PDO:: FETCH_ASSOC);
        $fetch_category->execute();

        $row_category=$fetch_category->fetch();  // fetch()   function here fetches our whole query
        
        //  Used in other queries below as a means of identifying the correct row of data
        //
        $category_id=$row_category['category_id'];
        
        echo "<h3>".$row_category['category_name']."</h3>";
        //---------------------------------------------------------------------------------------------------------------------------------------------------
    

        //---------------------------------------------------------------------------------------------------------------------------------------------------
        //  Query 6 B
        //
        //  Used to give <h3> product name bars dynamically
        $fetch_product=$conx->prepare("select * from products where category_id='$category_id' LIMIT 0,3");
        $fetch_product->setFetchMode(PDO:: FETCH_ASSOC);
        $fetch_product->execute();

        while($row_product=$fetch_product->fetch()):
            echo "<li>
                    <form method='post' enctype='multipart/form-data'>
                        <a href='product_detail.php?product_id=".$row_product['product_id']."'>
                            <h4>".$row_product['product_name']."</h4>
                            <img src='images/product_images/".$row_product['product_image_1']."'/>
                            <center>
                                <button id='product_button'><a href='product_detail.php?product_id=".$row_product['product_id']."'>View</a></button>
                                <input type='hidden' value='".$row_product['product_id']."' name='product_id' />
                                <button id='product_button' name='cart_button'>Cart</button>
                                <button id='product_button'><a href='#'>Wish</a></button>
                            </center>
                        </a>
                    </form>
                </li>";
        endwhile;
        //---------------------------------------------------------------------------------------------------------------------------------------------------
    }


    function womenswear(){
        include("includes/db.php");

        //---------------------------------------------------------------------------------------------------------------------------------------------------
        //  Query 7 A
        //
        //  Used to give <h3> product name bars dynamically
        //
        $fetch_category=$conx->prepare("select * from main_categories where category_id='7'");
        $fetch_category->setFetchMode(PDO:: FETCH_ASSOC);
        $fetch_category->execute();

        $row_category=$fetch_category->fetch();  // fetch()   function here fetches our whole query
        
        //  Used in other queries below as a means of identifying the correct row of data
        //
        $category_id=$row_category['category_id'];
        
        echo "<h3>".$row_category['category_name']."</h3>";
        //---------------------------------------------------------------------------------------------------------------------------------------------------
    

        //---------------------------------------------------------------------------------------------------------------------------------------------------
        //  Query 7 B
        //
        //  Used to give <h3> product name bars dynamically
        $fetch_product=$conx->prepare("select * from products where category_id='$category_id' LIMIT 0,3");
        $fetch_product->setFetchMode(PDO:: FETCH_ASSOC);
        $fetch_product->execute();

        while($row_product=$fetch_product->fetch()):
            echo "<li>
                    <form method='post' enctype='multipart/form-data'>
                        <a href='product_detail.php?product_id=".$row_product['product_id']."'>
                            <h4>".$row_product['product_name']."</h4>
                            <img src='images/product_images/".$row_product['product_image_1']."'/>
                            <center>
                                <button id='product_button'><a href='product_detail.php?product_id=".$row_product['product_id']."'>View</a></button>
                                <input type='hidden' value='".$row_product['product_id']."' name='product_id' />
                                <button id='product_button' name='cart_button'>Cart</button>
                                <button id='product_button'><a href='#'>Wish</a></button>
                            </center>
                        </a>
                    </form>
                </li>";
        endwhile;
        //---------------------------------------------------------------------------------------------------------------------------------------------------
    }


    function car(){
        include("includes/db.php");

        //---------------------------------------------------------------------------------------------------------------------------------------------------
        //  Query 8 A
        //
        //  Used to give <h3> product name bars dynamically
        //
        $fetch_category=$conx->prepare("select * from main_categories where category_id='8'");
        $fetch_category->setFetchMode(PDO:: FETCH_ASSOC);
        $fetch_category->execute();

        $row_category=$fetch_category->fetch();  // fetch()   function here fetches our whole query
        
        //  Used in other queries below as a means of identifying the correct row of data
        //
        $category_id=$row_category['category_id'];
        
        echo "<h3>".$row_category['category_name']."</h3>";
        //---------------------------------------------------------------------------------------------------------------------------------------------------
    

        //---------------------------------------------------------------------------------------------------------------------------------------------------
        //  Query 8 B
        //
        //  Used to give <h3> product name bars dynamically
        $fetch_product=$conx->prepare("select * from products where category_id='$category_id' LIMIT 0,3");
        $fetch_product->setFetchMode(PDO:: FETCH_ASSOC);
        $fetch_product->execute();

        while($row_product=$fetch_product->fetch()):
            echo "<li>
                    <form method='post' enctype='multipart/form-data'>
                        <a href='product_detail.php?product_id=".$row_product['product_id']."'>
                            <h4>".$row_product['product_name']."</h4>
                            <img src='images/product_images/".$row_product['product_image_1']."'/>
                            <center>
                                <button id='product_button'><a href='product_detail.php?product_id=".$row_product['product_id']."'>View</a></button>
                                <input type='hidden' value='".$row_product['product_id']."' name='product_id' />
                                <button id='product_button' name='cart_button'>Cart</button>
                                <button id='product_button'><a href='#'>Wish</a></button>
                            </center>
                        </a>
                    </form>
                </li>";
        endwhile;
        //---------------------------------------------------------------------------------------------------------------------------------------------------
    }


    function product_details() {
        include("includes/db.php");

            if(isset($_GET['product_id'])){
                $product_id=$_GET['product_id'];
        
                $product_fetch=$conx->prepare("select * from products where product_id='$product_id'");
                $product_fetch->setFetchMode(PDO:: FETCH_ASSOC);
                $product_fetch->execute();

                $row_product=$product_fetch->fetch();

                //  Used to identify similar related products by category id in the next query below
                //
                $category_id=$row_product['category_id'];
                
                //  These variables are to get the discount to be displayed on the individual product pages
                //
                $product_price = $row_product['product_price'];
                $product_discount = $row_product['product_discount'];
                $product_sell_price = ($product_price * $product_discount / 100);

                echo    "<div id='product_image'>
                            <img src='images/product_images/".$row_product['product_image_1']."' />
                            <ul>
                                <li><img src='images/product_images/".$row_product['product_image_1']."'</li>
                                <li><img src='images/product_images/".$row_product['product_image_2']."'</li>
                                <li><img src='images/product_images/".$row_product['product_image_3']."'</li>
                                <li><img src='images/product_images/".$row_product['product_image_4']."'</li>
                            </ul>
                        </div>
                        <div id='product_features'>
                            <h3>".$row_product['product_name']."</h3><hr />
                            <ul>
                                <li>".$row_product['product_feature_1']."</li>
                                <li>".$row_product['product_feature_2']."</li>
                                <li>".$row_product['product_feature_3']."</li>
                                <li>".$row_product['product_feature_4']."</li>

                            </ul>
                            <ul>
                                <li>".$row_product['product_feature_5']."</li>
                                <li>Model No - ".$row_product['product_model']."</li>
                                <li>Warranty - ".$row_product['product_warranty']."</li>
                            </ul>

                            <br clear='all' />

                            <center>";  //  End Echo

                                if($product_discount > 0 && $row_product['product_quantity'] >= 1) {
                                    echo "<h4>RRP Price : $ ".$row_product['product_price']."</h4>
                                    <h4 style='color:#449944; text-decoration:none'>Discount : ".$row_product['product_discount']."%</h4>
                                    <h4 style='color:#449944; text-decoration:none'>Total Saving : $ $product_sell_price</h4>
                                    <h4 style='color:#000000; text-decoration:none'>Selling Price : $ ".$row_product['product_discount_price']."</h4>";
                                } else {
                                    echo "<h4 style='color:#000000; text-decoration:none'>Selling Price : $ ".$row_product['product_price']."</h4>";
                                }
                                
                                //  Start Echo
                                //
                                echo "
  
                                <form method='post'>
                                    <center>
                                    ";
                                        if($row_product['product_quantity'] >= 1) {
                                            echo "
                                            <div id='button_div'>
                                                <input type='hidden' value='".$row_product['product_id']."' name='product_id' />
                                                <button id='buy_now' name='buy_now'>Buy Now</button>
                                                <button id='cart' name='cart_button'>Add To Cart</button>
                                            </div>
                                            ";
                                        } else {
                                            echo "
                                            <div id='button_div'>
                                                <input type='hidden' value='".$row_product['product_id']."' name='product_id' />
                                                <button id='out_of_stock'>Out of Stock</button>
                                                <button id='out_of_stock'>Out of Stock</button>
                                            </div>
                                            ";
                                        }
                                        echo "
                                    </center>
                                </form>"; // End Echo

                            if(isset($_POST['buy_now'])) {
                                if(!isset($_SESSION['user_email'])) {
                                    echo "<script>alert('Please Login or Signup')</script>";
                                } else {
                                    //  ?checkout=$product_id'   this is how the product information displays in checkout.php
                                    //
                                    echo "<script>window.open('checkout.php?checkout=$product_id', '_self')</script>";
                                }
                            }

                            if($row_product['product_quantity'] < 1) {
                                echo "<h3>In Stock : <span style='color:#ff0000'>Out Of Stock</span></h3>";
                            } else {
                                echo "<h3>In Stock : <span style='color:#449944'>Available</span></h3>";
                            }
                                
                            //  Start Echo
                            //
                            echo "<div id='share'>
                            
                                    <div id='facebook'>
                                        <a href='http://www.facebook.com/sharer.php?u=http://leedavidsoncontentmanagementsystem1.com/power_store/product_detail.php?product_id=$product_id' title='Facebook Share' target='_blank'>Facebook Share</a>
                                    </div> 
                                    
                                    <div id='google_plus'>
                                        <a href='http://plus.google.com/share?url=http://leedavidsoncontentmanagementsystem1.com/power_store/product_detail.php?product_id=$product_id' target='_blank' title='Google Plus Share'>G+ Share</a>    
                                    </div>
                                    
                                    <div id='twitter'>
                                        <a href='https://twitter.com/intent/tweet?text=Check Out This At http://leedavidsoncontentmanagementsystem1.com/power_store/product_detail.php?product_id=$product_id' target='_blank' title='Twitter Tweet'>Tweet</a>
                                    </div> 

                                    <div id='whatsapp'>
                                        <a href='whatsapp://send?text=".$row_product['product_name']." http://leedavidsoncontentmanagementsystem1.com/power_store/product_detail.php?product_id=$product_id' target='_blank'>Whatsapp Share</a>
                                    </div> 
                                </div>

                                <span id='keystroke'>Cash On Delivery Available</span>
                                <span id='keystroke'>30 Day Replacement Guarantee</span>
                                <span id='keystroke'>Delivered In 2-3 Working Days</span>
                            </center>
                        </div>

                        <br clear='all' />
                        
                        <div id='similar_product'>
                            <h3>Related Product</h3>
                            <ul>";
                                echo add_cart();
                                $fetch_similar_products=$conx->prepare("select * from products where product_id!=$product_id and category_id='$category_id' LIMIT 0,7");
                                $fetch_similar_products->setFetchMode(PDO:: FETCH_ASSOC);
                                $fetch_similar_products->execute();

                                $row=$fetch_similar_products->fetch();

                                while($row=$fetch_similar_products->fetch()):
                                    echo "<li>
                                            <a href='product_detail.php?product_id=".$row['product_id']."'>
                                                <img src='images/product_images/".$row['product_image_1']."' />
                                                <p>".$row['product_name']."</p>
                                                <p>Price : £ ".$row['product_price']."</p>
                                            </a>                                       
                                        </li>";
                                
                                endwhile;

                            echo "</ul>

                        </div>";
        }
    }
    
    
    function checkout_user_address() {
        include("includes/db.php");
        
        $user_email = $_SESSION['user_email'];
        $get_user = $conx->prepare("select * from user where user_email='$user_email'");
        $get_user->setFetchMode(PDO:: FETCH_ASSOC);
        $get_user->execute();
        $row_user_get = $get_user->fetch();
        echo "
                <h3>".$row_user_get['user_name']."</h3>
                <p>".$row_user_get['user_address']."</p>
                <p>".$row_user_get['user_state']." , ".$row_user_get['user_country']."</p>
                <p>".$row_user_get['user_pin_code']." .</p>
                <p>Phone Number : ".$row_user_get['user_phone']." .</p>
    
             "; //  End Echo
    }
    
    
   function up_user_checkout() {
        include("includes/db.php");
        $user_email = $_SESSION['user_email'];
        $get_user = $conx->prepare("select * from user where user_email='$user_email'");
        $get_user->setFetchMode(PDO:: FETCH_ASSOC);
        $get_user->execute();
        $row_user_get = $get_user->fetch();
        echo "<form method='post' enctype='multipart/form-data'>
                <table>
                    <tr>
                        <td>Update User Name :</td>
                        <td><input required='required' type='text' name='user_name' value='".$row_user_get['user_name']."' /></td>
                    </tr>
                    <tr>
                        <td>Update User Address :</td>
                        <td><input required='required' type='text' name='user_address' value='".$row_user_get['user_address']."' /></td>
                    </tr>
                    <tr>
                        <td>Update User State :</td>
                        <td><input required='required' type='text' name='user_state' value='".$row_user_get['user_state']."' /></td>
                    </tr>
                    <tr>
                        <td>Update User Country :</td>
                        <td><input required='required' type='text' name='user_country' value='".$row_user_get['user_country']."' /></td>
                    </tr>
                    <tr>
                        <td>Update User Pin Code :</td>
                        <td><input required='required' type='text' name='user_pin_code' value='".$row_user_get['user_pin_code']."' /></td>
                    </tr>
                    <tr>
                        <td>Update User Phone Number : </td>
                        <td><input required='required' type='text' name='user_phone' value='".$row_user_get['user_phone']."' /></td>
                    </tr>
                </table>
                <center><button id='checkout_user_right_button' type='submit' name='up_user_address' value='Update'>Update</button></center>
              </form>;";
                if(isset($_POST['up_user_address'])) {
                    $user_name = $_POST['user_name'];
                    $user_address = $_POST['user_address'];
                    $user_state = $_POST['user_state'];
                    $user_country = $_POST['user_country'];
                    $user_pin_code = $_POST['user_pin_code'];
                    $user_phone = $_POST['user_phone'];
                    
                    $update_user=$conx->prepare("update user set user_name='$user_name',user_address='$user_address',user_state='$user_state',user_country='$user_country',user_pin_code='$user_pin_code',user_phone='$user_phone'");
                    	echo"<script>alert('Address Updated');</script>";
                    if($update_user->execute()){
                    	echo"<script>window.open('checkout.php','_self');</script>";	
                    }
              }
    }
    
    
    function single_product() {
        include("includes/db.php");
        if(isset($_GET['checkout'])) {
            
            //  GET USER INFORMATION
            //
            $ip_address = getIp();
            $user_email = $_SESSION['user_email'];
            $get_user = $conx->prepare("select * from user where user_email='$user_email'");
            $get_user->setFetchMode(PDO:: FETCH_ASSOC);
            $get_user->execute();
            $row_user_get = $get_user->fetch();
            $user_id = $row_user_get['user_id'];
            
            //  GET PRODUCT INFORMATION
            //
            $product_id = $_GET['checkout'];
            $get_product = $conx->prepare("select * from products where product_id='$product_id'");
            $get_product->setFetchMode(PDO:: FETCH_ASSOC);
            $get_product->execute();
            $row_product = $get_product->fetch();
            $main_quantity = $row_product['product_quantity'];
            
            //  GET QUANTITY INFORMATION
            //
            $get_quantity = $conx->prepare("select * from user_cart where user_id='$user_id' AND product_id='$product_id'");
            $get_quantity->setFetchMode(PDO:: FETCH_ASSOC);
            $get_quantity->execute();
            $row_quantity = $get_quantity->fetch();
            $quantity = $row_quantity['quantity'];
            
            $amount = 0;
            $sub_total = 0;
            
            //  AMOUNT FOR PAYMENT TABLE CALCULATION
            //
            if($row_product['product_discount_price'] >= 1) {
                $amount = $row_product['product_discount_price'];
            } else {
                $amount = $row_product['product_price'];
            }
            
            
            //  SUB TOTAL AMOUNT FOR PAYMENT TABLE CALCULATION
            //
            if($row_product['product_discount_price'] >= 1) {
                $sub_total = $row_quantity['quantity'] * $row_product['product_discount_price'];
            } else {
                $sub_total = $row_quantity['quantity'] * $row_product['product_price'];
            }

            
            $total = $sub_total;
            
            
            //  CHECK TO MAKE SURE PRODUCT NOT ALREADY IN DATABASE - IF IT IS DONT ADD A SECOND HOWEVER THE USER CAN ALTER THE QUANTITY TO ADD MORE
            //
			$row_cart_count=$get_quantity->rowCount();
			if($row_cart_count == 1) {
			    
			} else {
                $add_to_cart = $conx->prepare("insert into user_cart(product_id,quantity,ip_address, user_id) value ('$product_id','1','$ip_address','$user_id')");
                if($add_to_cart->execute()) {
                    echo "<script>window.open('checkout.php?checkout=$product_id','_self');</script>";
                }
			}
            $net_total = 0;
            
            echo "<form method='post'>
                    <table cellpadding='0' cellspacing='0'>
                        <tr>
                            <th>Image</th>
                            <th>Product Name</th>
                            <th>Quantity</th> 
                            <th>Price</th>
                            <th>Remove</th>
                            <th>Sub Total</th>
                        </tr>
                        <tr>
                            <td><img src='images/product_images/".$row_product['product_image_1']."' /></td>
                            <td>".$row_product['product_name']."</td>
                            <td><input type='text' pattern='[0-9]{0,3}'name='quantity' value='".$row_quantity['quantity']."' /><input type='submit' name='update_quantity' value='Save' /></td>
                            "; // End echo
                    
                                //  PRICE FOR CHECKOUT PAGE CALCULATION
                                //
                                if($row_product['product_discount_price'] >= 1) {
                                    echo "<td>".$row_product['product_discount_price']."</td>";
                                } else {
                                    echo "<td>".$row_product['product_price']."</td>";
                                }
                                
                            //  Start echo
                            //
                            echo "
                            <td><a id='cart_delete' href='delete.php?user_delete_id=".$row_product['product_id']."'>Delete</a></td>
                            
                            "; // End echo
                    
                                //  SUB TOTAL FOR CHECKOUT PAGE CALCULATION
                                //
                                if($row_product['product_discount_price'] >= 1) {
                                    $sub_total = $row_quantity['quantity'] * $row_product['product_discount_price'];
                                    echo "<td>".$sub_total."</td>";
                                } else {
                                    $sub_total = $row_quantity['quantity'] * $row_product['product_price'];
                                    echo "<td>".$sub_total."</td>";
                                }
                                
                            //  Start echo
                            //
                            echo "
                        </tr>
                        <tr>
                            <td></td><td></td><td></td><td></td>
                            <td>Amount Payable : </td>
         
                            "; // End echo
                        
                                //  AMOUNT PAYABLE
                                //
                                if($row_product['product_discount_price'] >= 1) {
                                    $sub_total = $row_quantity['quantity'] * $row_product['product_discount_price'];
                                    echo "<td>".$sub_total."</td>";
                                } else {
                                    $sub_total = $row_quantity['quantity'] * $row_product['product_price'];
                                    echo "<td>".$sub_total."</td>";
                                }
                                
                                    
                            //  Start echo
                            //
                            echo "
                
                        </tr>
                    </table>
                    
                    <center>
                        <a href='index.php'><button class='buy_now' type='button'>Continue Shopping</button></a>
                        <button class='buy_now' type='submit' name='single_buy'>Cash on Delivery</button>
                    </center>
                    
  
   
                    
                    
                    </form>"; // End echo
                    
                    if(isset($_POST['single_buy'])) {
                        $ip_address = getIp();
                        $transaction_id = "ORDER ".substr(mt_rand(),0.10)." RECEIVED";      //   Here we generate a random number for the transaction ID
                        $insert = $conx->prepare("insert into payment(product_id,user_id,amount,quantity,ip_address,status,payment_date,transaction_id) values ('$product_id','$user_id','$sub_total','$quantity','$ip_address','Pending',NOW(),'$transaction_id')");
                        if($insert->execute()) {
                            
                            $user_name = $row_user_get['user_name'];
                            $user_email = $row_user_get['user_email'];
                            $password = $row_user_get['user_password'];
                            
        
                            // Email the user their activation link
                    		$to = "$user_email";						 
                    		$from = "auto_responder@leedavidsoncontentmanagementsystem1.com";
                    		$subject = 'Power Store Order Billing';
                    		
                            $total_to_two_decimal = sprintf('%0.2f', round($total, 2));
                            $total_two_decimal = $total_to_two_decimal;
                    		
        					$message = "<html>
        					                <center><p><b>ATTENTION</b></p></center>
        					                <center><p><b>SIMULATED ORDER FOR DEMONSTRATION PURPOSES ONLY</b></p></center>
        					                <center><p><b>THIS IS NOT A REQUEST FOR MONEY</b></p></center>
        					                <br>
        					                <br>
                							<center><p>Hello Customer $user_name We Received Your Order And Here Is Your Order Summary</p></center>
                								<center style='font-family:verdana'>
                									<table style='font-family:verdana' width='90%'>
                										<tr>
                											<center><th style='height:30px; font-size:14px; color:#FFFfff; background:#449944' colspan='6'>Order Details</th></center>
                										</tr>
                										<tr>
                											<th style='height:25px; background:#COCOCO'>Order ID</th>
                											<th style='height:25px; background:#COCOCO'>Item Name</th>
                											<th style='height:25px; background:#COCOCO'>Quantity</th>
                											<th style='height:25px; background:#COCOCO'>RRP Price</th>
                											<th style='height:25px; background:#COCOCO'>Discount Price if Applicable</th>
                										</tr>
                										<tr>
                											<td style='height:20px;text-align:center'>$transaction_id</td>
                											<td style='height:20px;text-align:center'>".$row_product['product_name']."</td>
                											<td style='height:20px;text-align:center'>$quantity</td>
                											<td style='height:20px;text-align:center'>".$row_product['product_price']."</td>
                											<td style='height:20px;text-align:center'>".$row_product['product_discount_price']."</td>
                										</tr>
                										<tr>
                                                            <td></td><td></td><td></td><td></td>
                                                            <td>Amount Payable : </td>
            
                											<td style='height:20px'><b>Payable Amount : $total_two_decimal</b></td>
                                                        </tr>
                									</table>
                								</center>
                								<p>To Go To Power Store <a href='http://leedavidsoncontentmanagementsystem1.com/power_store/index.php'>Click Here</a> Happy Shopping Thank You</p>
                						</html>";
        			        
                    		$headers = "From: $from\n";
                            $headers .= "MIME-Version: 1.0\n";
                            $headers .= "Content-type: text/html; charset=iso-8859-1\n";
                    		mail($to, $subject, $message, $headers);
                            
                            echo "<script>alert('We Received Your Order')</script>";
                            echo "<script>window.open('user_product.php?myorder','_self')</script>";
                        }
                        
                        $new_quantity = $main_quantity - $quantity;
                        
                        $update_quantity = $conx->prepare("update products set product_quantity='$new_quantity' where product_id='$product_id'");
                        $update_quantity->execute();
                        
                        $del = $conx->prepare("delete from user_cart where product_id='$product_id' AND user_id='$user_id'");
                        $del->execute();
                    }

                    
                    if(isset($_POST['update_quantity'])) {
                        $quantity = $_POST['quantity'];
                        $update_quantity = $conx->prepare("update user_cart set quantity='$quantity' where product_id='$product_id' AND user_id='$user_id'");
                        if($update_quantity->execute()) {
                            echo "<script>window.open('checkout.php?checkout=$product_id','_self');</script>";
                        }
                      
                    }
        } else {
            echo cart_display();
        }
    }   


    //  Called by the navbar dropdown
    //
    function all_categories() {
        include("includes/db.php");

        $all_categories=$conx->prepare("select * from main_categories");
        $all_categories->setFetchMode(PDO:: FETCH_ASSOC);
        $all_categories->execute();



        while($row=$all_categories->fetch()):
            echo "<li id=''>
                    <a href='category_detail.php?category_id=".$row['category_id']."'>".$row['category_name']."</a>  
                </li>";
		endwhile;
    }


    //  Called by category detail page
    //
    function category_detail() {
        include("includes/db.php");

        // Here we check to see if the category_id variable is set as a GET
        //
        if(isset($_GET['category_id'])){
            $category_id=$_GET['category_id'];
            $category_product=$conx->prepare("select * from products where category_id='$category_id'");
            $category_product->setFetchMode(PDO:: FETCH_ASSOC);
            $category_product->execute();

			$category_name=$conx->prepare("select * from main_categories where category_id='$category_id'");
			$category_name->setFetchMode(PDO:: FETCH_ASSOC);
			$category_name->execute();

			$row=$category_name->fetch();
			$row_main_category=$row['category_name'];

            // Here we echo out the main category name in <h3> tags which have style set in style.css
            //
            echo "<h3>".$row_main_category."</h3>";

			while($row_category=$category_product->fetch()):
                echo "<li>
                        <a href='product_detail.php?product_id=".$row_category['product_id']."'>
                            <h4>".$row_category['product_name']."</h4>
                            <img src='images/product_images/".$row_category['product_image_1']."' />
                            <center>
                                <button id='product_button'><a href='product_detail.php?product_id=".$row_category['product_id']."'>View</a></button>
                                <button id='product_button'><a href='#'>Cart</a></button>
                                <button id='product_button'><a href='#'>Wish</a></button>
                            </center>
                        </a>
                    </li>";
            endwhile;
        }
    }


    function view_all_sub_categories() {
        include("includes/db.php");

        if(isset($_GET['category_id'])){
            $category_id=$_GET['category_id'];

            $sub_category=$conx->prepare("select * from sub_categories where category_id='$category_id'");
            $sub_category->setFetchMode(PDO:: FETCH_ASSOC);
            $sub_category->execute();

            //  This displays the title in the bodyright
            //
            echo "<h3>Sub Cat</h3>";

            while($row=$sub_category->fetch()):
                echo "<li><a href='category_detail.php?sub_category_id=".$row['sub_category_id']."'>".$row['sub_category_name']."</a></li>";
            endwhile;
        }
    }


    function sub_category_detail() {
        include("includes/db.php");

        // Here we check to see if the sub_category_id variable is set as a GET
        //
        if(isset($_GET['sub_category_id'])){
            $sub_category_id=$_GET['sub_category_id'];
            $sub_category_product=$conx->prepare("select * from products where sub_category_id='$sub_category_id'");
            $sub_category_product->setFetchMode(PDO:: FETCH_ASSOC);
            $sub_category_product->execute();

			$sub_category_name=$conx->prepare("select * from sub_categories where sub_category_id='$sub_category_id'");
			$sub_category_name->setFetchMode(PDO:: FETCH_ASSOC);
            $sub_category_name->execute();

			$row=$sub_category_name->fetch();
			$row_sub_category=$row['sub_category_name'];

            // Here we echo out the main category name in <h3> tags which have style set in style.css
            //
            echo "<h3>".$row_sub_category."</h3>";

			while($row_sub_cat=$sub_category_product->fetch()):
                echo "<li>
                        <a href='product_detail.php?product_id=".$row_sub_cat['product_id']."'>
                            <h4>".$row_sub_cat['product_name']."</h4>
                            <img src='images/product_images/".$row_sub_cat['product_image_1']."' />
                            <center>
                                <button id='product_button'><a href='product_detail.php?product_id=".$row_sub_cat['product_id']."'>View</a></button>
                                <button id='product_button'><a href='#'>Cart</a></button>
                                <button id='product_button'><a href='#'>Wish</a></button>
                            </center>
                        </a>
                    </li>";
            endwhile;
        }
    }


    function view_all_main_categories() {
        include("includes/db.php");

        if(isset($_GET['sub_category_id'])){
            $main_category=$conx->prepare("select * from main_categories");
            $main_category->setFetchMode(PDO:: FETCH_ASSOC);
            $main_category->execute();

            //  This displays the title in the bodyright
            //
            echo "<h3>Category</h3>";

            while($row=$main_category->fetch()):
                echo "<li><a href='category_detail.php?category_id=".$row['category_id']."'>".$row['category_name']."</a></li>";
            endwhile;
        }
    }


    function birthday_men() {
        include("includes/db.php");

        if(isset($_GET['birthday_men'])){
            $fetch_product=$conx->prepare("select * from products where product_for_whome='men'");
            $fetch_product->setFetchMode(PDO:: FETCH_ASSOC);
            $fetch_product->execute();

            echo "<h3>Birthday Gifts For Men</h3>";

            while($row_men=$fetch_product->fetch()):
                echo "<li>
                        <a href='product_detail.php?product_id=".$row_men['product_id']."'>
                            <h4>".$row_men['product_name']."</h4>
                            <img src='images/product_images/".$row_men['product_image_1']."' />
                            <center>
                                <button id='product_button'><a href='product_detail.php?product_id=".$row_men['product_id']."'>View</a></button>
                                <button id='product_button'><a href='#'>Cart</a></button>
                                <button id='product_button'><a href='#'>Wish</a></button>
                            </center>
                        </a>
                    </li>";
            endwhile;
        }
    }


    function birthday_women() {
        include("includes/db.php");

        if(isset($_GET['birthday_women'])){
            $fetch_product=$conx->prepare("select * from products where product_for_whome='women'");
            $fetch_product->setFetchMode(PDO:: FETCH_ASSOC);
            $fetch_product->execute();

            echo "<h3>Birthday Gifts For Women</h3>";

            while($row_women=$fetch_product->fetch()):
                echo "<li>
                        <a href='product_detail.php?product_id=".$row_women['product_id']."'>
                            <h4>".$row_women['product_name']."</h4>
                            <img src='images/product_images/".$row_women['product_image_1']."' />
                            <center>
                                <button id='product_button'><a href='product_detail.php?product_id=".$row_women['product_id']."'>View</a></button>
                                <button id='product_button'><a href='#'>Cart</a></button>
                                <button id='product_button'><a href='#'>Wish</a></button>
                            </center>
                        </a>
                    </li>";
            endwhile;
        }
    }


    function him() {
        include("includes/db.php");

        if(isset($_GET['men_watch'])){
            $men_watch="watch";
                      
            $watch=$conx->prepare("select * from products where product_for_whome='men' and product_name like '%$men_watch%'");
            $watch->setFetchMode(PDO:: FETCH_ASSOC);
            $watch->execute();

            echo "<h3>Watches For Men</h3>";

            while($row_watch=$watch->fetch()):
                echo "<li>
                        <a href='product_detail.php?product_id=".$row_watch['product_id']."'>
                            <h4>".$row_watch['product_name']."</h4>
                            <img src='images/product_images/".$row_watch['product_image_1']."' />
                            <center>
                                <button id='product_button'><a href='product_detail.php?product_id=".$row_watch['product_id']."'>View</a></button>
                                <button id='product_button'><a href='#'>Cart</a></button>
                                <button id='product_button'><a href='#'>Wish</a></button>
                            </center>
                        </a>
                    </li>";
            endwhile;
        }

        if(isset($_GET['men_belt'])){
            $men_belt="belt";
                      
            $belt=$conx->prepare("select * from products where product_for_whome='men' and product_name like '%$men_belt%'");
            $belt->setFetchMode(PDO:: FETCH_ASSOC);
            $belt->execute();

            echo "<h3>Belts For Men</h3>";

            while($row_belt=$belt->fetch()):
                echo "<li>
                        <a href='product_detail.php?product_id=".$row_belt['product_id']."'>
                            <h4>".$row_belt['product_name']."</h4>
                            <img src='images/product_images/".$row_belt['product_image_1']."' />
                            <center>
                                <button id='product_button'><a href='product_detail.php?product_id=".$row_belt['product_id']."'>View</a></button>
                                <button id='product_button'><a href='#'>Cart</a></button>
                                <button id='product_button'><a href='#'>Wish</a></button>
                            </center>
                        </a>
                    </li>";
            endwhile;
        }

        if(isset($_GET['men_cufflinks'])){
            $men_cufflinks="cufflinks";
                      
            $cufflinks=$conx->prepare("select * from products where product_for_whome='men' and product_name like '%$men_cufflinks%'");
            $cufflinks->setFetchMode(PDO:: FETCH_ASSOC);
            $cufflinks->execute();

            echo "<h3>Cufflinks For Men</h3>";

            while($row_cufflinks=$cufflinks->fetch()):
                echo "<li>
                        <a href='product_detail.php?product_id=".$row_cufflinks['product_id']."'>
                            <h4>".$row_cufflinks['product_name']."</h4>
                            <img src='images/product_images/".$row_cufflinks['product_image_1']."' />
                            <center>
                                <button id='product_button'><a href='product_detail.php?product_id=".$row_cufflinks['product_id']."'>View</a></button>
                                <button id='product_button'><a href='#'>Cart</a></button>
                                <button id='product_button'><a href='#'>Wish</a></button>
                            </center>
                        </a>
                    </li>";
            endwhile;
        }
    }


    function her() {
        include("includes/db.php");

        if(isset($_GET['women_watch'])){
            $women_watch="watch";
                      
            $watch=$conx->prepare("select * from products where product_for_whome='women' and product_name like '%$women_watch%'");
            $watch->setFetchMode(PDO:: FETCH_ASSOC);
            $watch->execute();

            echo "<h3>Watches For Women</h3>";

            while($row_watch=$watch->fetch()):
                echo "<li>
                        <a href='product_detail.php?product_id=".$row_watch['product_id']."'>
                            <h4>".$row_watch['product_name']."</h4>
                            <img src='images/product_images/".$row_watch['product_image_1']."' />
                            <center>
                                <button id='product_button'><a href='product_detail.php?product_id=".$row_watch['product_id']."'>View</a></button>
                                <button id='product_button'><a href='#'>Cart</a></button>
                                <button id='product_button'><a href='#'>Wish</a></button>
                            </center>
                        </a>
                    </li>";
            endwhile;
        }

        if(isset($_GET['women_belt'])){
            $women_scarf="scarf";
                      
            $scarf=$conx->prepare("select * from products where product_for_whome='women' and product_name like '%$women_scarf%'");
            $scarf->setFetchMode(PDO:: FETCH_ASSOC);
            $scarf->execute();

            echo "<h3>Scarfs For Women</h3>";

            while($row_scarf=$scarf->fetch()):
                echo "<li>
                        <a href='product_detail.php?product_id=".$row_scarf['product_id']."'>
                            <h4>".$row_scarf['product_name']."</h4>
                            <img src='images/product_images/".$row_scarf['product_image_1']."' />
                            <center>
                                <button id='product_button'><a href='product_detail.php?product_id=".$row_scarf['product_id']."'>View</a></button>
                                <button id='product_button'><a href='#'>Cart</a></button>
                                <button id='product_button'><a href='#'>Wish</a></button>
                            </center>
                        </a>
                    </li>";
            endwhile;
        }

        if(isset($_GET['women_earrings'])){
            $women_earrings="earrings";
                      
            $earrings=$conx->prepare("select * from products where product_for_whome='women' and product_name like '%$women_earrings%'");
            $earrings->setFetchMode(PDO:: FETCH_ASSOC);
            $earrings->execute();

            echo "<h3>Earrings For Women</h3>";

            while($row_earrings=$earrings->fetch()):
                echo "<li>
                        <a href='product_detail.php?product_id=".$row_earrings['product_id']."'>
                            <h4>".$row_earrings['product_name']."</h4>
                            <img src='images/product_images/".$row_earrings['product_image_1']."' />
                            <center>
                                <button id='product_button'><a href='product_detail.php?product_id=".$row_earrings['product_id']."'>View</a></button>
                                <button id='product_button'><a href='#'>Cart</a></button>
                                <button id='product_button'><a href='#'>Wish</a></button>
                            </center>
                        </a>
                    </li>";
            endwhile;
        }
    }


    function search() {
        include("includes/db.php");

        if(isset($_GET['search'])){
            $user_query = $_GET['user_query'];

            $search=$conx->prepare("select * from products where product_name like '%$user_query%' or product_keyword like '%$user_query%'");
            $search->setFetchMode(PDO:: FETCH_ASSOC);
            $search->execute();

            //  Here we include bodyleft because we removed it from the includes list in search.php
            //
            echo "<div id='bodyleft'><ul>";

            if($user_query == '' || $search->rowCount() == 0) {
                //echo "<h2>Product not found with this keyword</h2>";
                echo "<script>alert('Product not found with this keyword !!!')</script>";	
                echo "<script>window.open('index.php?view_all_products','_self');</script>";
            } else {

                while($row=$search->fetch()):
                    echo "<li>
                        <a href='product_detail.php?product_id=".$row['product_id']."'>
                            <h4>".$row['product_name']."</h4>
                            <img src='images/product_images/".$row['product_image_1']."' />
                            <center>
                                <button id='product_button'><a href='product_detail.php?product_id=".$row['product_id']."'>View</a></button>
                                <button id='product_button'><a href='#'>Cart</a></button>
                                <button id='product_button'><a href='#'>Wish</a></button>
                            </center>
                        </a>
                    </li>";
                endwhile;

                //  Here we close bodyleft after the while loop
                //
                echo "</ul></div>";
            }
        }
    }


    function slider() {
        include("includes/db.php");

        $get_image=$conx->prepare("select * from slider");
        $get_image->setFetchMode(PDO:: FETCH_ASSOC);
        $get_image->execute();

        $row_image=$get_image->fetch();

        echo "<img src='images/slider/".$row_image['slider_image_1']."' />
              <img src='images/slider/".$row_image['slider_image_2']."' />
              <img src='images/slider/".$row_image['slider_image_3']."' />
              <img src='images/slider/".$row_image['slider_image_4']."' />";
    }

?>