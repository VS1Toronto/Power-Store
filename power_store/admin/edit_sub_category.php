<?php
	if(!isset($_SESSION)){
		session_start();
	}
?>
<h3>Edit Sub Category</h3>
<?php
    include("includes/function.php");
    echo edit_sub_category();
?>