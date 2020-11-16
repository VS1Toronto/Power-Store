<?php

    //  This is a different page from where the logged in user deletes a product so we have
    //  to start a session here or we will not get returned back to the page where we deleted from 
    //
    session_start();

    include("includes/function.php");

    //  If the user has not logged in then show funtion   delete_cart_items()
    //  else if the user has logged in then show function   delete_user_cart()
    //
    if(!isset($_SESSION['user_email'])){
        echo delete_cart_items();
    } else {
        echo delete_user_cart();
    }
?>