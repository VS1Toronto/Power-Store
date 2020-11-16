<div id="bodyleft">
    
    <div id="slider">
        <h2>DEALS OF THE DAY</h2>

        <!-- This extra <div> is necessary because slider code affect <h3> tag before this one -->
        <!-- -->
        <div id="slide">
            <?php echo slider(); ?>
        </div>
        
    </div>


    <ul><?php echo electronics(); ?></ul>


    <!-- Original way which I will revert back to as I want -->
    <!-- the option of changing each rows CSS individually if -->
    <!-- necessary - ? removed from start of each php block to -->
    <!-- comment out -->
    <!--

    <ul><php echo electronics(); ?></ul><br clear='all' ?>

    <ul><php echo crockery(); ?></ul><br clear='all' ?>

    <ul><php echo dvd(); ?></ul><br clear='all' ?>

    <ul><php echo books(); ?></ul><br clear='all' ?>

    <ul><php echo timepiece(); ?></ul><br clear='all' ?>

    <ul><php echo menswear(); ?></ul><br clear='all' ?>

    <ul><php echo womenswear(); ?></ul><br clear='all' ?>

    <ul><php echo car(); ?></ul><br clear='all' ?>

    -->

</div>
<!-- End of bodyleft -->



