<?php
	if(!isset($_SESSION)){
		session_start();
	}
?>
<h3>Edit Product</h3>
<?php
    include("includes/function.php");
    echo edit_product();
?>

