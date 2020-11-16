<?php
	if(!isset($_SESSION)){
		session_start();
	}
?>
<div id="bodyright">

    <h3 style='text-underline-position: under'><u>View All Categories</u></h3>
    <form method="post" enctype="multipart/form-data">
        <table>
            <tr>
                <th>Category Number</th>
                <th>Category Name</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>      
            <?php include("includes/function.php"); echo view_all_category(); ?>
        </table>
    </form>

    <div id="divider_1">
    </div>

    <div id="add_category_title">
        <h3 style='text-underline-position: under'><u>Add New Category</u></h3>
    </div>
    <div id="add_category_table">
        <form method="post">
            <table>
                <tr>
                    <td>Enter Category Name :</td>
                    <td><input type="text" name="category_name" /></td> <!-- Text entered will be stored in name -->
                </tr>
            </table>
            <center><button name="add_category">Add Category</button></center>
        </form>
    </div>
</div>

<?php
    echo add_category(); 
?>