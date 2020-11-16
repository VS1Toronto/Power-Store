<?php
	if(!isset($_SESSION)){
		session_start();
	}
?>
<div class="products_scroll" id="bodyright">
    <h3 style='text-underline-position: under'><u>View All Products</u></h3>
    <form method="post" enctype="multipart/form-data">  <!-- enctype="multipart/form-data" necessary to upload images -->
        <table>
            <tr>
                <th>Product Number</th>
                <th>Edit</th>
                <th>Delete</th>
                <th>Product Name</th>
                <th>Product Images</th>
                <th>Feature 1</th>
                <th>Feature 2</th>
                <th>Feature 3</th>
                <th>Feature 4</th>
                <th>Feature 5</th>
                <th>Price</th>
                <th>Discount</th>
                <th>Quantity</th>
                <th>Sell Price</th>
                <th>Model Number</th>
                <th>Warranty</th>
                <th>For Whome</th>
                <th>Keyword</th>
                <th>Added Date</th>
            </tr>
            <?php include("includes/function.php"); echo view_all_products() ?>
        </table>
    </form>
</div>

