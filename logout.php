<?php

session_start();
session_unset();
session_destroy();

// header("location: index.php");
echo "<script>window.location='index.php';</script>";
?>

<script>
    
    // function preventBack(){
    //     window.history.forward();
    // }
    // setTimeout("preventBack()", 0);
    // window.onload = function (){
    //     null
    // };
    
</script>