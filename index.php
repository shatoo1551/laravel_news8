<?php
//function.phpを起動
require_once('function.php');
//どのデータを持ってくるかを定義
$dsn= 'mysql:host=localhost;dbname=laravel_news;charset=utf8';
//データベース接続&確認 mysqli取得
$dbh = dbConnect();
//データ取得をそして格納
$newsdata = getAllNews1($dbh);
//エラーメッセージ配列作成
$error_message = array();
//送信ボタンを押されたら
if( !empty($_POST['submit']) ) {
    if( empty($_POST['title'])  ){
        $error_message[] = 'タイトルを表示してください';
    }
    if(  empty($_POST['text'])  ) {
        $error_message[] = '記事を入力してください。';
    }
    
    if ( mb_strlen($_POST['title']) > 30){
        $error_message[] = 'タイトルは30字以内で！';
    }
    if( empty($error_message) ) {
    //入力されたデータを送信する
    NewCreateArticle4();
    header('Location: index.php' );
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="index.css" type="text/css" rel="stylesheet">
    <title>Document</title>
</head>
<header>
    <i><h1><a href="index.php">Laravel News</a><h1></1>
</header>
<body>
    <h2>さぁ、最新のニュースをシェアしましょう</h2>
    <form  method="POST">
        <div class="post">
        <dl>
            <dt>タイトル：</dt>
            <dd><input  name="title" type="text"></dd>
            <dt>記事：</dt>
            <dd><textarea name="text" id="text" cols="50" rows="10"></textarea></dd>
            <input class="button" name="submit" type="submit" value="送信">
        </dl>
        </div>
    </form>
    <?php foreach ($error_message as $error): ?>
       <ul><li> <?php echo $error  ?> </li></ul>
    <?php endforeach;?>

<h1>ニュース一覧</h1>

<!--<p><a href="form.html">新規作成</a></p>-->
<?php  if (empty($newsdata )){
                echo "ニュースはありません";
            } ?>
<table>
   <?php foreach ($newsdata as $column): ?>
    <!--カラムの中にそれぞれの値を当てはめる-->
    <div class="box">
            <b><?php echo $column['title'] ?></b><br><br>
            <!--<td><?php echo $column['post_at'] ?></td>-->
            <?php echo $column['text'] ?><br><br>
            <a href="detail.php?id=<?php echo $column['id'] ?>" > 記事全文・コメントを見る</a><br><br>
            <form method="post" action="delete.php">
                <input type="hidden" name="id" value= <?php echo $column['id'] ?> >
                <button type="submit" value="削除"　id="">削除</button>
            </form>
    </div>
    <?php endforeach; ?>
</table>
</body>
 </html>
