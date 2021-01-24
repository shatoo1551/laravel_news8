<?php
    $i = $_POST['i'];
    $id= $_POST['id'];
    $file   = file( $id .".txt");
    unset($file[$i]);
    file_put_contents($id .".txt", $file);
    $url = "detail.php?i=" . $id;
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
<p><a href= "detail.php?i=<?php echo $id ?>">前へ戻る</a></p>

</body>
</html>
