<?php 
    require_once('dbc.php');
    $id = $_GET['id'];
    if(empty($id)){
        exit('IDが不正です');
    }
    $dbh = dbConnect(); 
    $result = /*$dbc->*/deleteNews($_GET['id']);
    //header('Location: index.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<p><a href= "index.php">ホームへ戻る</a></p>
</body>
</html>