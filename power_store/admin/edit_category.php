<?php
	if(!isset($_SESSION)){
		session_start();
	}
?>
<h3>Edit Category</h3>
<?php 
    include("includes/function.php"); 
    echo edit_category();
?>


