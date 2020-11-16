<!-- This prevents an extra bodyright being sent to the screen -->
<!-- -->
<!-- *** Remember to close the php tags in the last php block *** -->
<!-- -->
<?php 
    if(!isset($_GET['view_all_categories'])){ 
    if(!isset($_GET['view_all_sub_categories'])){  
    if(!isset($_GET['add_new_products'])){  
    if(!isset($_GET['view_all_products'])){      
    if(!isset($_GET['view_all_discount_products'])){  
    if(!isset($_GET['view_all_out_of_stock_products'])){          
    if(!isset($_GET['slider'])){    
?>
<div id="bodyright">
    <?php
        if(isset($_GET['edit_category'])){
            include("edit_category.php");
        }
        if(isset($_GET['edit_sub_category'])){
            include("edit_sub_category.php");
        }
        if(isset($_GET['edit_product'])){
            include("edit_product.php");
        }
    ?>
</div>
<!-- End of bodyright -->
<?php } } } } } } } ?>
