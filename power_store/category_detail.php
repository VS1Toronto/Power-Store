<?php
	if(!isset($_SESSION)){
		session_start();
	}
?>
<html>
<head>
    <title>Category Detail</title>
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>

    <!-- All page components moved to files in includes directory and included here -->
    <!-- -->
    <?php 
        include("includes/function.php"); 
        include("includes/header.php"); 
        include("includes/navbar.php");

        echo "<div id='bodyleft'><ul>"; category_detail(); sub_category_detail(); birthday_men(); birthday_women(); him(); her(); echo "</ul></div>";

        if(isset($_GET['category_id']) or isset($_GET['sub_category_id'])) {
        
        //  This provides the list of sub categories in the bodyright in the category detail page
        //
        echo "<div class='bodyright' id='bodyright'>
                
                <ul>"; view_all_sub_categories(); view_all_main_categories(); echo "</ul>
            </div><br clear='all'>";
        } else {
            include("includes/bodyright.php");
        }
        
        //  This is to deal with issues of the footer moving up the screen if not enough products are present
        //
        include("includes/spacer_category_detail.php");
        include("includes/footer.php"); 
    ?>

</body>
</html>