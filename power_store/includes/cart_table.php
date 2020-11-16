<div class="cart">
    <form method="post" enctype="multipart/form-data">
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th>Image</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Remove</th>
                <th>Sub Total</th>
            </tr>
            <?php echo cart_display(); ?>
        </table>
    </form>
</div>