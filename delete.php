
<?php
    $i = $_POST['id'];
    $file   = file( "news.txt");
    unset($file[$i]);
    file_put_contents("news.txt", $file);
    $url = "index.php";
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
