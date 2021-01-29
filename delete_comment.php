
<?php
    require_once('function.php');
    $numberid = $_POST['numberid'];
    $id = $_POST['id'];
    deleteComments($id);
    $url = "detail.php?id=". $numberid;
    header("Location:".$url);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<p><a href= "detail.php?id=<?php $id ?>">前へ戻る</a></p>

</body>
</html>
