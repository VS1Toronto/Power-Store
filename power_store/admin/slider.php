<?php
	if(!isset($_SESSION)){
		session_start();
	}
?>
<?php 
    include("includes/db.php");
    include("includes/function.php"); 

    $get_image=$conx->prepare("select * from slider");
    $get_image->setFetchMode(PDO:: FETCH_ASSOC);
    $get_image->execute();
    $row_image=$get_image->fetch();
?>
<div id="bodyright">
    <h3 style='text-underline-position: under'><u>Edit Image Slider</u></h3>
    <form method="post" enctype="multipart/form-data">
        <table>
            <tr>
                <td>Select Slider Image 1</td>
                <td><img src="../images/slider/<?php echo $row_image['slider_image_1']; ?>" style="width:60px; height:60px;"/><input type="file" name="product_image_1" /></td>
            </tr>
            <tr>
                <td>Select Slider Image 2</td>
                <td><img src="../images/slider/<?php echo $row_image['slider_image_2']; ?>" style="width:60px; height:60px;"/><input type="file" name="product_image_2" /></td>
            </tr>
            <tr>
                <td>Select Slider Image 3</td>
                <td><img src="../images/slider/<?php echo $row_image['slider_image_3']; ?>" style="width:60px; height:60px;"/><input type="file" name="product_image_3" /></td>
            </tr>
            <tr>
                <td>Select Slider Image 4</td>
                <td><img src="../images/slider/<?php echo $row_image['slider_image_4']; ?>" style="width:60px; height:60px;"/><input type="file" name="product_image_4" /></td>
            </tr>
        </table>
        <center><button name="edit_image_slider">Upload</button></center>
    </form>
<div>

<?php echo edit_image_slider(); ?>