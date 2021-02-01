
<?php
    require_once('function.php');
    $id = $_POST['id'];
    deleteNews($id);
    $url = "index.php";
    header("Location:".$url);
?>

