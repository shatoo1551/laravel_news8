<?php
//function.phpを起動
require_once('function.php');
//データを定義
$dsn= 'mysql:host=localhost;dbname=laravel_news;charset=utf8';
//データ接続
$dbh = dbConnect1($dsn);
//indexから受け取るidを受け取る
$id = $_GET['id'];
//コメントデータ取得
$comments= getAllcomments3($id);
//ニュースデータ取得
$news= getNews($id);    
?>

<!--データの出力-->
<!--データを１行ずつ取得する-->

<?php
    // タイムゾーン設定
    date_default_timezone_set('Asia/Tokyo');
    $error_message = array();

    if( !empty($_POST['submit']) ) {
        // 表示名の入力チェック
        if( empty($_POST['view_name'])  ){
            $error_message[] = '表示名を入力してください';
         
        }
        if(  empty($_POST['message'])  ) {
            $error_message[] = 'コメントを入力してください。';
        }
        if( empty($error_message) ) {
            NewCreateComments3($id);
            header('Location: detail.php?id='.$id);
        }	
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="detail.css" type="text/css"  rel="stylesheet">   
    <title>Document</title>
    
    <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
</head>
<header>
    <i><h1><a href="index.php">Laravel News</a><h1></1>
</header>
<body>
<?php if( empty($_POST['submit']) && !empty($_SESSION['success_message']) ): ?>
 <?php endif; ?>
        <div class="d">  
            <h2></h2>
        <b><?php echo $news['title'] ?></b><br><br>
        <?php echo $news['text'] ?><br>
            
        </div>
        <h2>コメントを書く</h2>
        <form method="post" >
            <div class="post">
                <dl>
                    <dt>名前：</dt>
                    <dd><input  name="view_name" type="text"></dd>
                    <dt>コメント：</dt>
                    <dd><input type="text" name="message" class="message"></dd>
                    <input type="submit" value="送信" name="submit" class="submit"> 
                </dl>
            </div>
        </form>

        <?php foreach ($error_message as $error): ?>
       <ul><li> <?php echo $error  ?> </li></ul>
    <?php endforeach;?>
        <hr>
        <section>
<!--ここにメッセージを表示させる-->
            <?php  if (empty($comments )){
                echo "コメントはありません";
            } ?>
            <?php foreach( $comments as $comment): ?>
            <div class="comment" >
                <article>
                    <!--<time><?php echo date('Y年m月d日 H:i', strtotime($comment['post_date'])); ?></time>-->
                    <h2 color="white"><?php echo $comment['view_name']; ?></h2>
                    <p><?php echo $comment['message']; ?></p>
                    <form method="post" action="delete_comment.php">
                        <input type="hidden" name="id" value=<?php echo  $comment['id'] ?> >
                        <input type="hidden" name="numberid" value=<?php echo  $id ?> >
                        <button type="submit" value="削除"　id="">削除</button>
                    </form>
                </article>
            </div>
            <?php endforeach; ?>
        </section>  
    </body>
</html>
