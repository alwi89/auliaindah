<?php
session_start();
unset($_SESSION['usrdridh']);
unset($_SESSION['lvldridh']);
header("location:login.php");
?>