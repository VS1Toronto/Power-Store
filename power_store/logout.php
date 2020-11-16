<?php
    //  Starting session here because this page is not included in header
    //
    session_start();

    header("Location:index.php");

    //  Session destroyed ensuring user logged out
    //
    session_destroy();
?>