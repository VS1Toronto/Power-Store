<?php
	if(!isset($_SESSION)){
		session_start();
	}
?>
<div id="bodyright">
    <h3 style='text-underline-position: under'><u>Add New Product</u></h3>
    <form method="post" enctype="multipart/form-data">  <!-- enctype="multipart/form-data" necessary to upload images -->
        <table>
            <tr>
                <td>Enter Product Name :</td>
                <td><input type="text" name="product_name" /></td>  <!-- Text entered will be stored in name -->
            </tr>
            <tr>
                <td>Select Category Name :</td>
                <td>
                    <select name="category_name">
                        <?php
                            include("includes/function.php"); echo view_all_categories(); 
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Select Sub Category Name :</td>
                <td>
                    <select name="sub_category_name">
                        <?php 
                            echo view_all_sub_categories();
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Select Product Image 1 :</td>
                <td><input type="file" name="product_image_1" /></td> <!-- Text entered will be stored in name -->
            </tr>
            <tr>
                <td>Select Product Image 2 :</td>
                <td><input type="file" name="product_image_2" /></td> <!-- Text entered will be stored in name -->
            </tr>
            <tr>
                <td>Select Product Image 3 :</td>
                <td><input type="file" name="product_image_3" /></td> <!-- Text entered will be stored in name -->
            </tr>
            <tr>
                <td>Select Product Image 4 :</td>
                <td><input type="file" name="product_image_4" /></td> <!-- Text entered will be stored in name -->
            </tr>
            <tr>
                <td>Enter Product Feature 1 :</td>
                <td><input type="text" name="product_feature_1" /></td> <!-- Text entered will be stored in name -->
            </tr>
            <tr>
                <td>Enter Product Feature 2 :</td>
                <td><input type="text" name="product_feature_2" /></td> <!-- Text entered will be stored in name -->
            </tr>
            <tr>
                <td>Enter Product Feature 3 :</td>
                <td><input type="text" name="product_feature_3" /></td> <!-- Text entered will be stored in name -->
            </tr>
            <tr>
                <td>Enter Product Feature 4 :</td>
                <td><input type="text" name="product_feature_4" /></td> <!-- Text entered will be stored in name -->
            </tr>
            <tr>
                <td>Enter Product Feature 5 :</td>
                <td><input type="text" name="product_feature_5" /></td> <!-- Text entered will be stored in name -->
            </tr>
            <tr>
                <td>Enter Price :</td>
                <td><input type="text" name="product_price" /></td> <!-- Text entered will be stored in name -->
            </tr>
            <tr>
                <td>Enter Discount Percent :</td>
                <td><input type="text" name="product_discount" /></td> <!-- Text entered will be stored in name -->
            </tr>
            <tr>
                <td>Enter Quantity:</td>
                <td><input type="text" name="product_quantity" /></td> <!-- Text entered will be stored in name -->
            </tr>
            <tr>
                <td>Enter Model No. :</td>
                <td><input type="text" name="product_model" /></td> <!-- Text entered will be stored in name -->
            </tr>
            <tr>
                <td>Enter Warranty :</td>
                <td><input type="text" name="product_warranty" /></td> <!-- Text entered will be stored in name -->
            </tr>
            <tr>
                <td>For Whome</td>
                <td>
                    <select name="product_for_whome">
                        <option></option>
                        <option value="men">Men</option>
                        <option value="women">Women</option>
                    </select>
                </td> <!-- Text entered will be stored in name -->
            </tr>
            <tr>
                <td>Enter Keyword :</td>
                <td><input type="text" name="product_keyword" /></td> <!-- Text entered will be stored in name -->
            </tr>
        </table>
        <center><button name="add_product">Add Product</button></center>
    </form>
</div>

<?php echo add_product(); ?>
