<?php
	if(!isset($_SESSION)){
		session_start();
	}
?>
<div id="bodyright">

    <h3 style='text-underline-position: under'><u>View All Sub Categories</u></h3>
    <form method="post" enctype="multipart/form-data">
        <table>
            <tr>
                <th>Category Number</th>
                <th>Category Name</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>      
            <?php include("includes/function.php"); echo view_all_sub_category(); ?>
        </table>
    </form>

    <div id="divider_1">
    </div>

    <h3 style='text-underline-position: under'><u>Add New Sub Category</u></h3>
    <form method="post">
        <table>
            <tr>
                <td>Select Category Name :</td>
                <td>
                    <select type="text" name="main_category">
                        <?php
                            echo view_all_categories(); 
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Enter Sub Category Name :</td>
                <td><input type="text" name="sub_category_name" /></td> <!-- Text entered will be stored in name -->
            </tr>
        </table>
        <center><button name="add_sub_category">Add Sub Cat</button></center>
    </form>
</div>

<?php
    echo add_sub_category(); 
?>