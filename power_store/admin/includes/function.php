<?php

    function add_category() {
        include("includes/db.php");
        if(isset($_POST['add_category'])){
            $category_name=$_POST['category_name'];
            
            //  $conx     here because thats whats in the db.php script
            //
            $add_category=$conx->prepare("insert into main_categories(category_name)
                                        values
                                        ('$category_name')");
            
            if($category_name !== "" && $add_category->execute()){
                echo "<script>alert('Category Added Successfully !!!')</script>";
                echo "<script>window.open('admin_index.php?view_all_categories','_self');</script>";
            } else {
                echo "<script>alert('Category Not Added Successfully !!!')</script>";
            }
        }
    }


    function add_sub_category() {
        include("includes/db.php");
        if(isset($_POST['add_sub_category'])){
            $category_id=$_POST['main_category'];
            $sub_category_name=$_POST['sub_category_name'];
            
            $add_sub_category=$conx->prepare("insert into sub_categories(sub_category_name,category_id)
                                            values
                                            ('$sub_category_name','$category_id')");
            
            if($sub_category_name !== "" && $add_sub_category->execute()){
                echo "<script>alert('Sub Category Added Successfully !!!')</script>";
                echo "<script>window.open('admin_index.php?view_all_sub_categories','_self');</script>";
            } else {
                echo "<script>alert('Sub Category Not Added Successfully !!!')</script>";
            }
        }
    }


    function view_all_categories(){
        include("includes/db.php");
        $fetch_categories=$conx->prepare("select * from main_categories");
        $fetch_categories->setFetchMode(PDO:: FETCH_ASSOC);
        $fetch_categories->execute();
        
        while($row=$fetch_categories->fetch()):
            //  Here we target the column name in the main categories table in the database
            //
            echo"<option value='".$row['category_id']."'>".$row['category_name']."</option>";
        endwhile;  
    }


    function view_all_category(){
        include("includes/db.php");

        $fetch_categories=$conx->prepare("select * from main_categories ORDER BY category_name");
        $fetch_categories->setFetchMode(PDO:: FETCH_ASSOC);
        $fetch_categories->execute();
        $i = 1;

        while($row=$fetch_categories->fetch()):
            echo "<tr>
                    <td>".$i++."</td>
                    <td>".$row['category_name']."</td>
                    <td><a href='admin_index.php?edit_category=".$row['category_id']."'>Edit</a></td>
                    <td><a href='delete_category.php?delete_main_category=".$row['category_id']."'>Delete</a></td>
                  </tr>";
        endwhile;
    }


    function view_all_sub_categories(){
        include("includes/db.php");
        $fetch_sub_categories=$conx->prepare("select * from sub_categories");
        $fetch_sub_categories->setFetchMode(PDO:: FETCH_ASSOC);
        $fetch_sub_categories->execute();
        
        while($row=$fetch_sub_categories->fetch()):
            //  Here we target the column name in the main categories table in the database
            //
            echo"<option value='".$row['sub_category_id']."'>".$row['sub_category_name']."</option>";
        endwhile;  
    }


    function view_all_sub_category(){
        include("includes/db.php");

        $fetch_categories=$conx->prepare("select * from sub_categories ORDER BY sub_category_name");
        $fetch_categories->setFetchMode(PDO:: FETCH_ASSOC);
        $fetch_categories->execute();
        $i = 1;

        while($row=$fetch_categories->fetch()):
            echo "<tr>
                    <td>".$i++."</td>
                    <td>".$row['sub_category_name']."</td>
                    <td><a href='admin_index.php?edit_sub_category=".$row['sub_category_id']."'>Edit</a></td>
                    <td><a href='delete_category.php?delete_sub_category=".$row['sub_category_id']."'>Delete</a></td>
                  </tr>";
        endwhile;
    }


    function edit_image_slider() {

        include("includes/db.php");

        if(isset($_POST['edit_image_slider'])){

            //  Here the if conditionals state if the product_image_1 field and tmp_name field
            //  are not found in the $_FILES then do nothing else if they are found update the image
            //
            if($product_image_1_tmp = $_FILES['product_image_1']['tmp_name'] == "") {
    
            } else {
                $product_image_1 = $_FILES['product_image_1']['name'];
                $product_image_1_tmp = $_FILES['product_image_1']['tmp_name'];

                //  Here we save product images to directory     ../images/product_images
                //
                move_uploaded_file($product_image_1_tmp,"../images/slider/$product_image_1");
            
                $update_product_image_1=$conx->prepare("update slider set slider_image_1='$product_image_1'");
                $update_product_image_1->execute();
            }


            if($product_image_2_tmp = $_FILES['product_image_2']['tmp_name'] == "") {
    
            } else {
                $product_image_2 = $_FILES['product_image_2']['name'];
                $product_image_2_tmp = $_FILES['product_image_2']['tmp_name'];
                move_uploaded_file($product_image_2_tmp,"../images/slider/$product_image_2");

                $update_product_image_2=$conx->prepare("update slider set slider_image_2='$product_image_2'");
                $update_product_image_2->execute();
            }


            if($product_image_3_tmp = $_FILES['product_image_3']['tmp_name'] == "") {
    
            } else {
                $product_image_3 = $_FILES['product_image_3']['name'];
                $product_image_3_tmp = $_FILES['product_image_3']['tmp_name'];
                move_uploaded_file($product_image_3_tmp,"../images/slider/$product_image_3");

                $update_product_image_3=$conx->prepare("update slider set slider_image_3='$product_image_3'");
                $update_product_image_3->execute();
            }


            if($product_image_4_tmp = $_FILES['product_image_4']['tmp_name'] == "") {
    
            } else {
                $product_image_4 = $_FILES['product_image_4']['name'];
                $product_image_4_tmp = $_FILES['product_image_4']['tmp_name'];
                move_uploaded_file($product_image_4_tmp,"../images/slider/$product_image_4");

                $update_product_image_4=$conx->prepare("update slider set slider_image_4='$product_image_4'");
                $update_product_image_4->execute();
            }
            echo "<script>alert('Slider Updated Successfully !!!');</script>";
            
            //  You need this refresh to get the newly updated images 
            //  displaying on the edit image slider in the bodyright section
            //
            echo "<script>window.open('admin_index.php?slider','_self');</script>";
        }
    }


    function add_product(){
            include("includes/db.php");
            if(isset($_POST['add_product'])){

            //  Variables populated with Database Fields 
            //
            $product_name = $_POST['product_name'];
            $category_id = $_POST['category_name'];
            $sub_category_id = $_POST['sub_category_name'];
            
            //  Variables populated with images *** Cant use $_GET or $_POST as these are server side variables so use $_FILES ***
            //
            $product_image_1 = $_FILES['product_image_1']['name'];
            $product_image_1_tmp = $_FILES['product_image_1']['tmp_name'];

            $product_image_2 = $_FILES['product_image_2']['name'];
            $product_image_2_tmp = $_FILES['product_image_2']['tmp_name'];

            $product_image_3 = $_FILES['product_image_3']['name'];
            $product_image_3_tmp = $_FILES['product_image_3']['tmp_name'];

            $product_image_4 = $_FILES['product_image_4']['name'];
            $product_image_4_tmp = $_FILES['product_image_4']['tmp_name'];

            //  Here we save product images to directory     ../images/product_images
            //
            move_uploaded_file($product_image_1_tmp,"../images/product_images/$product_image_1");
            move_uploaded_file($product_image_2_tmp,"../images/product_images/$product_image_2");
            move_uploaded_file($product_image_3_tmp,"../images/product_images/$product_image_3");
            move_uploaded_file($product_image_4_tmp,"../images/product_images/$product_image_4");

            //  Variables populated with features
            //
            $product_feature_1 = $_POST['product_feature_1'];
            $product_feature_2 = $_POST['product_feature_2'];
            $product_feature_3 = $_POST['product_feature_3'];
            $product_feature_4 = $_POST['product_feature_4'];
            $product_feature_5 = $_POST['product_feature_5'];

            //  Variables populated with product data
            //
            $product_price = $_POST['product_price'];
            $product_discount = $_POST['product_discount'];
            
            //  This is entered into the values field below to show the sell price by inserting     product_discount_price     but setting the value as     '$product_sell_price'
            //
			$product_sell_price = $product_price - ($product_price * $product_discount / 100);
			
			$product_quantity = $_POST['product_quantity'];
            $product_model = $_POST['product_model'];
            $product_warranty = $_POST['product_warranty'];
            $product_for_whome = $_POST['product_for_whome'];
            $product_keyword = $_POST['product_keyword'];

            //  $conx     here because thats whats in the db.php script
            //
            //  NOW()     php function catches and stores exact date in product_added_date field in database
            //
            $add_product=$conx->prepare("insert into products
                                        (product_name,category_id,sub_category_id,
                                        product_image_1,product_image_2,product_image_3,
                                        product_image_4,product_feature_1,product_feature_2,
                                        product_feature_3,product_feature_4,product_feature_5,
                                        product_price,product_discount,product_discount_price,product_quantity,
                                        product_model,product_warranty,product_for_whome,product_keyword,product_added_date)
                                        values
                                        ('$product_name','$category_id','$sub_category_id','$product_image_1',
                                        '$product_image_2','$product_image_3','$product_image_4','$product_feature_1',
                                        '$product_feature_2','$product_feature_3','$product_feature_4','$product_feature_5',
                                        '$product_price','$product_discount','$product_sell_price','$product_quantity','$product_model','$product_warranty',
                                        '$product_for_whome','$product_keyword',NOW())");
            
            if($product_name != "" && $add_product->execute()){
                echo "<script>alert('Product Added Successfully !!!')</script>";
            } else {
                echo "<script>alert('Product Not Added Successfully !!!')</script>";
            }
        }
    }


    /*  THIS WAS DONE EARLIER AND IS LEFT IN CASE ITS NECESSARY

    function add_product(){
        include("includes/db.php");
        if(isset($_POST['add_product'])){
            $product_name=$_POST['product_name'];
            $category_name=$_POST['category_name'];
            
            //  $conx     here because thats whats in the db.php script
            //
            $add_category=$conx->prepare("insert into main_categories(category_name)values('$category_name')");
            
            if($product_name !== "" && $add_category->execute()){
                echo "<script>alert('Category Added Successfully !!!')</script>";
                echo "<script>window.open('admin_index.php?view_all_products','_self');</script>";
            } else {
                echo "<script>alert('Category Not Added Successfully !!!')</script>";
            }
        }
    }

    */


    function view_all_products(){
        include("includes/db.php"); 

        $fetch_pro=$conx->prepare("select * from products");
        $fetch_pro->setFetchMode(PDO:: FETCH_ASSOC);
        $fetch_pro->execute();
        $i=1;

        while($row=$fetch_pro->fetch()):
            echo"<tr>
                    <td>".$i++."</td>
                    <td><a href='admin_index.php?edit_product=".$row['product_id']."'>Edit</a></td>
                    <td><a href='delete_category.php?delete_product=".$row['product_id']."'>Delete</a></td>
                    <td>".$row['product_name']."</td>
                    <td>
                        <img src='../images/product_images/".$row['product_image_1']."' />
                        <img src='../images/product_images/".$row['product_image_2']."' />
                        <img src='../images/product_images/".$row['product_image_3']."' />
                        <img src='../images/product_images/".$row['product_image_4']."' />
                    </td>
                    <td>".$row['product_feature_1']."</td>
                    <td>".$row['product_feature_2']."</td>
                    <td>".$row['product_feature_3']."</td>
                    <td>".$row['product_feature_4']."</td>
                    <td>".$row['product_feature_5']."</td>
                    <td>".$row['product_price']."</td>
                    <td>".$row['product_discount']."%</td>
                    <td>".$row['product_discount_price']."</td>
                    <td>".$row['product_quantity']."</td>
                    <td>".$row['product_model']."</td>
                    <td>".$row['product_warranty']."</td>
                    <td>".$row['product_for_whome']."</td>
                    <td>".$row['product_keyword']."</td>
                    <td>".$row['product_added_date']."</td>
                </tr>";
        endwhile;
    }
    
    
    function view_all_discount_products(){
        include("includes/db.php"); 

        $fetch_pro=$conx->prepare("select * from products where product_discount_price >= 1");
        $fetch_pro->setFetchMode(PDO:: FETCH_ASSOC);
        $fetch_pro->execute();
        $i=1;

        while($row=$fetch_pro->fetch()):
            echo"<tr>
                    <td>".$i++."</td>
                    <td><a href='admin_index.php?edit_product=".$row['product_id']."'>Edit</a></td>
                    <td><a href='delete_category.php?delete_product=".$row['product_id']."'>Delete</a></td>
                    <td>".$row['product_name']."</td>
                    <td>
                        <img src='../images/product_images/".$row['product_image_1']."' />
                        <img src='../images/product_images/".$row['product_image_2']."' />
                        <img src='../images/product_images/".$row['product_image_3']."' />
                        <img src='../images/product_images/".$row['product_image_4']."' />
                    </td>
                    <td>".$row['product_feature_1']."</td>
                    <td>".$row['product_feature_2']."</td>
                    <td>".$row['product_feature_3']."</td>
                    <td>".$row['product_feature_4']."</td>
                    <td>".$row['product_feature_5']."</td>
                    <td>".$row['product_price']."</td>
                    <td>".$row['product_discount']."%</td>
                    <td>".$row['product_discount_price']."</td>
                    <td>".$row['product_quantity']."</td>
                    <td>".$row['product_model']."</td>
                    <td>".$row['product_warranty']."</td>
                    <td>".$row['product_for_whome']."</td>
                    <td>".$row['product_keyword']."</td>
                    <td>".$row['product_added_date']."</td>
                </tr>";
        endwhile;
    }
    
    
    function view_all_out_of_stock_products(){
        include("includes/db.php"); 

        $fetch_pro=$conx->prepare("select * from products where product_quantity < 1");
        $fetch_pro->setFetchMode(PDO:: FETCH_ASSOC);
        $fetch_pro->execute();
        $i=1;

        while($row=$fetch_pro->fetch()):
            echo"<tr>
                    <td>".$i++."</td>
                    <td><a href='admin_index.php?edit_product=".$row['product_id']."'>Edit</a></td>
                    <td><a href='delete_category.php?delete_product=".$row['product_id']."'>Delete</a></td>
                    <td>".$row['product_name']."</td>
                    <td>
                        <img src='../images/product_images/".$row['product_image_1']."' />
                        <img src='../images/product_images/".$row['product_image_2']."' />
                        <img src='../images/product_images/".$row['product_image_3']."' />
                        <img src='../images/product_images/".$row['product_image_4']."' />
                    </td>
                    <td>".$row['product_feature_1']."</td>
                    <td>".$row['product_feature_2']."</td>
                    <td>".$row['product_feature_3']."</td>
                    <td>".$row['product_feature_4']."</td>
                    <td>".$row['product_feature_5']."</td>
                    <td>".$row['product_price']."</td>
                    <td>".$row['product_discount']."%</td>
                    <td>".$row['product_discount_price']."</td>
                    <td>".$row['product_quantity']."</td>
                    <td>".$row['product_model']."</td>
                    <td>".$row['product_warranty']."</td>
                    <td>".$row['product_for_whome']."</td>
                    <td>".$row['product_keyword']."</td>
                    <td>".$row['product_added_date']."</td>
                </tr>";
        endwhile;
    }


    function edit_category() {
        include("includes/db.php");
        if(isset($_GET['edit_category'])){

            //  This is the category id from the $_GET in the bar
            //
            $category_id=$_GET['edit_category'];

			$fetch_cat_name=$conx->prepare("select * from main_categories where category_id='$category_id'");
			$fetch_cat_name->setFetchMode(PDO:: FETCH_ASSOC);
			$fetch_cat_name->execute();
			$row=$fetch_cat_name->fetch();

            echo "<form method='post'>
                    <table>
                        <tr>
                            <td>Update Category Name :</td>
                            <td><input type='text' name='update_category_name' value='".$row['category_name']."'/></td>
                        </tr>
                    </table>
                    <center><button name='update_category'>Update Category</button></center>
                </form>";

            if(isset($_POST['update_category'])){
                $update_category_name=$_POST['update_category_name'];
                $update_category=$conx->prepare("update main_categories set category_name='$update_category_name' where category_id='$category_id'"); 
                
                if($update_category->execute()){
                    echo "<script>alert('Category Updated Successfully');</script>";

                    //  _self is used here to open in the same page - _target would open in a new page
                    //
                    echo "<script>window.open('admin_index.php?view_all_categories','_self');</script>";
                }
            }
        }
    }


    function edit_sub_category(){
		include("includes/db.php");
		if(isset($_GET['edit_sub_category'])){
			$sub_category_id=$_GET['edit_sub_category'];
			
			$fetch_sub_category=$conx->prepare("select * from sub_categories where sub_category_id='$sub_category_id'");
			$fetch_sub_category->setFetchMode(PDO:: FETCH_ASSOC);
			$fetch_sub_category->execute();
			$row=$fetch_sub_category->fetch();
			$category_id=$row['category_id'];
			
			$fetch_category=$conx->prepare("select * from main_categories where category_id='$category_id'");
			$fetch_category->setFetchMode(PDO:: FETCH_ASSOC);
			$fetch_category->execute();
			$row_category=$fetch_category->fetch();
			echo "<form method='post'>
					<table>
                        <tr>
                            <td>Select Main Category Name : </td>
                            <td>
                                <select name='sub_category_name'>
									<option value='".$row['sub_category_id']."'>".$row['sub_category_name']."</option>
									"; echo view_all_categories();echo"
								</select>
                            </td>
                        </tr>
						<tr>
							<td>Update Sub Category Name : </td>
							<td><input type='text' name='up_sub_category' value='".$row['sub_category_name']."' /></td>
						</tr>
					</table>
					<center><button name='update_sub_category'>Update Sub Cat</button></center>
				</form>";

            if(isset($_POST['update_sub_category'])){
                $category_name=$_POST['main_category'];
                $sub_category_name=$_POST['up_sub_category'];
                
                $update_category=$conx->prepare("update sub_categories set sub_category_name='$sub_category_name',category_id='$category_name' where sub_category_id='$sub_category_id'");
                if($update_category->execute()){
                    echo"<script>alert('Sub Category Updated Successfully !!!')</script>";	
                    echo "<script>window.open('admin_index.php?view_all_sub_categories','_self');</script>";
                }                    
            }	      
        }
    }

    
    function edit_product(){
        include("includes/db.php");

        if(isset($_GET['edit_product'])){
            $product_id=$_GET['edit_product'];

            //  FIRST QUERY
            //
            $fetch_product=$conx->prepare("select * from products where product_id=$product_id");
            $fetch_product->setFetchMode(PDO:: FETCH_ASSOC);
            $fetch_product->execute();

            $row=$fetch_product->fetch();

            //  Used in second query below to get correct category from main categories table
            //  8
            //
            $category_id=$row['category_id'];

            //  Used in third query below to get correct sub category from sub categories table
            //
            $sub_category_id=$row['sub_category_id'];

            //  SECOND QUERY
            //
            $fetch_category=$conx->prepare("select * from main_categories where category_id=$category_id");
            $fetch_category->setFetchMode(PDO:: FETCH_ASSOC);
            $fetch_category->execute();
            $row_category=$fetch_category->fetch();
            $category_name=$row_category['category_name'];

            //  THIRD QUERY
            //
            $fetch_sub_category=$conx->prepare("select * from sub_categories where sub_category_id=$sub_category_id");
            $fetch_sub_category->setFetchMode(PDO:: FETCH_ASSOC);
            $fetch_sub_category->execute();
            $row_sub_category=$fetch_sub_category->fetch();
            $sub_category_name=$row_sub_category['sub_category_name'];


            echo "<form method='post' enctype='multipart/form-data'>  <!-- enctype='multipart/form-data' necessary to upload images -->
                <table>
                    <tr>
                        <td>Update Product Name :</td>
                        <td><input type='text' name='product_name' value='".$row['product_name']."' /></td>  <!-- Text entered will be stored in name -->
                    </tr>
                    <tr>
                        <td>Update Category Name :</td>
                        <td>
                            <select name='category_name'>
                                <option value='".$row['category_id']."'>".$category_name."</option>
                                "; echo view_all_categories();echo"
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Update Sub Category Name :</td>
                        <td>
                            <select name='sub_category_name'>
                                <option value='".$row['category_id']."'>".$sub_category_name."</option>
                                "; echo view_all_sub_categories(); echo"
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Update Product Image 1 :</td>
                        <td>
                            <input type='file' name='product_image_1' />    <!-- Text entered will be stored in name --> 
                            <img src='../images/product_images/".$row['product_image_1']."' style='width:60px;' />
                        </td> 
                    </tr>
                    <tr>
                        <td>Update Product Image 2 :</td>
                        <td>
                            <input type='file' name='product_image_2' />    <!-- Text entered will be stored in name --> 
                            <img src='../images/product_images/".$row['product_image_2']."' style='width:60px;' />
                        </td> 
                    </tr>
                    <tr>
                        <td>Update Product Image 3 :</td>
                        <td>
                            <input type='file' name='product_image_3' />    <!-- Text entered will be stored in name -->
                            <img src='../images/product_images/".$row['product_image_3']."' style='width:60px;' />
                        </td> 
                    </tr>
                    <tr>
                        <td>Update Product Image 4 :</td>
                        <td>
                            <input type='file' name='product_image_4' />    <!-- Text entered will be stored in name -->
                            <img src='../images/product_images/".$row['product_image_4']."' style='width:60px;' />
                        </td> 
                    </tr>
                    <tr>
                        <td>Update Product Feature 1 :</td>
                        <td><input type='text' name='product_feature_1' value='".$row['product_feature_1']."'/>  <!-- Text entered will be stored in name --></td> 
                    </tr>
                    <tr>
                        <td>Update Product Feature 2 :</td>
                        <td><input type='text' name='product_feature_2' value='".$row['product_feature_2']."'/>  <!-- Text entered will be stored in name --></td> 
                    </tr>
                    <tr>
                        <td>Update Product Feature 3 :</td>
                        <td><input type='text' name='product_feature_3' value='".$row['product_feature_3']."'/>  <!-- Text entered will be stored in name --></td> 
                    </tr>
                    <tr>
                        <td>Update Product Feature 4 :</td>
                        <td><input type='text' name='product_feature_4' value='".$row['product_feature_4']."'/>  <!-- Text entered will be stored in name --></td> 
                    </tr>
                    <tr>
                        <td>Update Product Feature 5 :</td>
                        <td><input type='text' name='product_feature_5' value='".$row['product_feature_5']."'/>  <!-- Text entered will be stored in name --></td> 
                    </tr>
                    <tr>
                        <td>Update Price :</td>
                        <td><input type='text' name='product_price' value='".$row['product_price']."'/>      <!-- Text entered will be stored in name --></td> 
                    </tr>
                    <tr>
                        <td>Update Product Discount :</td>
                        <td><input type='text' name='product_discount' value='".$row['product_discount']."'/>      <!-- Text entered will be stored in name --></td> 
                    </tr>
                    <tr>
                        <td>Update Quantity :</td>
                        <td><input type='text' name='product_quantity' value='".$row['product_quantity']."'/>      <!-- Text entered will be stored in name --></td> 
                    </tr>
                    <tr>
                        <td>Update Model No. :</td>
                        <td>
                            <input type='text' name='product_model' value='".$row['product_model']."'/>      <!-- Text entered will be stored in name -->
                        </td> 
                    </tr>
                    <tr>
                        <td>Update Warranty :</td>
                        <td><input type='text' name='product_warranty' value='".$row['product_warranty']."'/>   <!-- Text entered will be stored in name --></td> 
                    </tr>
                    <tr>
                    <td>For Whome</td>
                    <td>
                        <select name='product_for_whome'>
                            <option></option>
                            <option value='men'>Men</option>
                            <option value='women'>Women</option>
                        </select>
                    </td> <!-- Text entered will be stored in name -->
                </tr>
                    <tr>
                        <td>Update Keyword :</td>
                        <td><input type='text' name='product_keyword' value='".$row['product_keyword']."'/>    <!-- Text entered will be stored in name --></td> 
                    </tr>
                </table>
                <center><button name='update_product'>Update Product</button></center>
            </form>";

            if(isset($_POST['update_product'])){
                
                //  Variables populated with Database Fields 
                //
                $product_name = $_POST['product_name'];
                $category_id = $_POST['category_name'];
                $sub_category_id = $_POST['sub_category_name'];
                
                //-----------------------------------------------------------------------------------------------------------------------------
                //  EDIT IMAGES BLOCK
                //
                //  This was done to prevent the images being eliminated from the 
                //  images directory if you dont select any images when you edit a product
                //
                if($_FILES['product_image_1']['tmp_name'] == ""){

                } else {
                    //  Variables populated with images *** Cant use $_GET or $_POST as these are server side variables so use $_FILES ***
                    //
                    $product_image_1 = $_FILES['product_image_1']['name'];
                    $product_image_1_tmp = $_FILES['product_image_1']['tmp_name'];
                    //  Here we save product images to directory     ../images/product_images
                    //
                    move_uploaded_file($product_image_1_tmp,"../images/product_images/$product_image_1");
                    $update_image_1=$conx->prepare("update products set product_image_1='$product_image' where product_id='$product_id'");
                    $update_image_1=execute();
                }

                if($_FILES['product_image_2']['tmp_name'] == ""){

                } else {
                    //  Variables populated with images *** Cant use $_GET or $_POST as these are server side variables so use $_FILES ***
                    //
                    $product_image_2 = $_FILES['product_image_2']['name'];
                    $product_image_2_tmp = $_FILES['product_image_2']['tmp_name'];
                    //  Here we save product images to directory     ../images/product_images
                    //
                    move_uploaded_file($product_image_2_tmp,"../images/product_images/$product_image_2");
                    $update_image_2=$conx->prepare("update products set product_image_2='$product_image' where product_id='$product_id'");
                    $update_image_2=execute();
                }

                if($_FILES['product_image_3']['tmp_name'] == ""){

                } else {
                    //  Variables populated with images *** Cant use $_GET or $_POST as these are server side variables so use $_FILES ***
                    //
                    $product_image_3 = $_FILES['product_image_3']['name'];
                    $product_image_3_tmp = $_FILES['product_image_3']['tmp_name'];
                    //  Here we save product images to directory     ../images/product_images
                    //
                    move_uploaded_file($product_image_3_tmp,"../images/product_images/$product_image_3");
                    $update_image_3=$conx->prepare("update products set product_image_3='$product_image' where product_id='$product_id'");
                    $update_image_3=execute();
                }

                if($_FILES['product_image_4']['tmp_name'] == ""){

                } else {
                    //  Variables populated with images *** Cant use $_GET or $_POST as these are server side variables so use $_FILES ***
                    //
                    $product_image_4 = $_FILES['product_image_4']['name'];
                    $product_image_4_tmp = $_FILES['product_image_4']['tmp_name'];
                    //  Here we save product images to directory     ../images/product_images
                    //
                    move_uploaded_file($product_image_4_tmp,"../images/product_images/$product_image_4");
                    $update_image_4=$conx->prepare("update products set product_image_4='$product_image' where product_id='$product_id'");
                    $update_image_4=execute();
                }
                //  END EDIT IMAGES BLOCK
                //-----------------------------------------------------------------------------------------------------------------------------
                
                //  Variables populated with features
                //
                $product_feature_1 = $_POST['product_feature_1'];
                $product_feature_2 = $_POST['product_feature_2'];
                $product_feature_3 = $_POST['product_feature_3'];
                $product_feature_4 = $_POST['product_feature_4'];
                $product_feature_5 = $_POST['product_feature_5'];

                //  Variables populated with product data
                //
                $product_price = $_POST['product_price'];
                $product_discount = $_POST['product_discount'];
                
                //  This is entered into the values field below to show the sell price by using   product_discount_price='$product_sell_price'      
                //
                $product_sell_price = $product_price - ($product_price * $product_discount / 100);
                
                $product_quantity = $_POST['product_quantity'];
                $product_model = $_POST['product_model'];
                $product_warranty = $_POST['product_warranty'];
                $product_for_whome = $_POST['product_for_whome'];
                $product_keyword = $_POST['product_keyword'];

                $update_product=$conx->prepare("update products 
                                                set product_name='$product_name',category_id='$category_id',
                                                sub_category_id='$sub_category_id',product_feature_1='$product_feature_1',
                                                product_feature_2='$product_feature_2',product_feature_3='$product_feature_3',product_feature_4='$product_feature_4',
                                                product_feature_5='$product_feature_5',product_price='$product_price',product_discount='$product_discount',product_discount_price='$product_sell_price',
                                                product_quantity='$product_quantity',product_model='$product_model',product_warranty='$product_warranty',product_for_whome='$product_for_whome',product_keyword='$product_keyword'
                                                where product_id='$product_id'");

                if($update_product->execute()){
                    echo "<script>alert('Product Updated Successfully !!!')</script>";
                    echo "<script>window.open('admin_index.php?view_all_products','_self');</script>";
                } else {
                    echo "<script>alert('Product Not Updated Successfully !!!')</script>";
                }  
            }
        }
    }


    function delete_main_category(){
		include("includes/db.php");		
			$delete_category_id=$_GET['delete_main_category'];		
			$delete_category=$conx->prepare("delete from main_categories where category_id='$delete_category_id'");
			
			if($delete_category->execute()){
				echo "<script>alert('Category Deleted Successfully !!!')</script>";	
				echo "<script>window.open('admin_index.php?view_all_categories','_self');</script>";
			} else {
                echo "<script>alert('Category Not Deleted Successfully !!!')</script>";
            }	
    }


    function delete_sub_category(){
		include("includes/db.php");
		$delete_sub_category_id=$_GET['delete_sub_category'];
		
		$delete_sub_category=$conx->prepare("delete from sub_categories where sub_category_id='$delete_sub_category_id'");
		
		if($delete_sub_category->execute()){
            echo "<script>alert('Sub Category Deleted Successfully !!!')</script>";	
            echo "<script>window.open('admin_index.php?view_all_sub_categories','_self');</script>";
        } else {
            echo "<script>alert('Sub Category Not Deleted Successfully !!!')</script>";
        }	
    }


    function delete_product(){
        include("includes/db.php");
		$delete_product_id=$_GET['delete_product'];
		
		$delete_product=$conx->prepare("delete from products where product_id='$delete_product_id'");

        if($delete_product->execute()){
            echo "<script>alert('Product Deleted Successfully !!!')</script>";	
            echo "<script>window.open('admin_index.php?view_all_products','_self');</script>";
        } else {
            echo "<script>alert('Product Not Deleted Successfully !!!')</script>";
        }
    }

?>
