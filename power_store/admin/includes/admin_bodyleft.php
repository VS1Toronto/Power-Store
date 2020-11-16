<!-- This clears bodyleft and bodyright from the CSS settings above it so it stays in correct position -->
<!-- -->
<br clear="all" />

    <div id="bodyleft">
        
        <h3>Content Management</h3>
        <ul>
            <li><a href="admin_index.php">Home</a></li>
        </ul>
            
            
        <h3 id="admin_tab">Category Management</h3>
        <ul>
            <!-- Pressing this button will set view_all_categories making the if condition below true -->
            <!-- -->
            <li><a href="admin_index.php?view_all_categories">View All Categories</a></li>

            <li><a href="admin_index.php?view_all_sub_categories">View All Sub Categories</a></li>
        </ul>
            
            
        <h3 id="admin_tab">Product Management</h3>
        <ul>
            <li><a href="admin_index.php?add_new_products">Add New Products</a></li>
            <li><a href="admin_index.php?view_all_products">View All Products</a></li>
            <li><a href="admin_index.php?view_all_discount_products">View All Discount Products</a></li>
            <li><a href="admin_index.php?view_all_out_of_stock_products">View All Out Of Stock Products</a></li>
        </ul>
        
        
        <h3 id="admin_tab">Slider Management</h3>
        <ul>
            <li><a href="admin_index.php?slider">Edit Image Slider</a></li>
        </ul>

    </div>
    <!-- End of bodyleft -->


    <!-- Using includes means you only reload that section of the page when you make a change like -->
    <!-- clicking a button which adds a variable to the GET Super Global Arrayincreasing website speed -->
    <!-- -->
    <?php
        if(isset($_GET['view_all_out_of_stock_products'])) {
            include("view_all_out_of_stock_products.php");
        }
    
        if(isset($_GET['view_all_discount_products'])) {
            include("view_all_discount_products.php");
        }
        
        if(isset($_GET['view_all_categories'])) {
            include("categories.php");
        }

        if(isset($_GET['view_all_sub_categories'])) {
            include("sub_categories.php");
        }

        if(isset($_GET['add_new_products'])) {
            include("add_products.php");
        }
    
        if(isset($_GET['view_all_products'])) {
            include("view_all_products.php");
        }

        if(isset($_GET['slider'])) {
            include("slider.php");
        }
    ?>

