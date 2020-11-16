<?php
	if(!isset($_SESSION)){
		session_start();
	}
?>
<?php
    include("includes/function.php");

    if(isset($_GET['delete_main_category'])){
        echo delete_main_category();
    }

    if(isset($_GET['delete_sub_category'])){
        echo delete_sub_category();
    }

    if(isset($_GET['delete_product'])){
        echo delete_product();
    }
?>