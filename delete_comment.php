
<?php
    require_once('function.php');
    $numberid = $_POST['numberid'];
    $id = $_POST['id'];
    deleteComments($id);
    $url = "detail.php?id=". $numberid;
    header("Location:".$url);
?>

