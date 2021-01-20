<!--/*
    ①一覧画面からidを受け取る
    GETリクエストidをURLLにつけて送る

    ②詳細ページでidを受け取る
    PHPの$_GETでidを取得する

    ③idを元にデータベースから記事を取得
    SELECT文でプレースホルダーを使う

    ④詳細ページに表示する
    HTMLにPHPを埋め込んで表示
    */
    ①require_onceを使う
    requier_once('dbc.php')
    ②namespaceを設定する
    ③useを使う
-->
<?php
require_once('dbc.php');
     $id = $_GET['id'];
     if(empty($id)){
         exit('IDが不正です');
     }
//$dbc= newDbc();
$dbh = dbConnect(); 
$result = /*$dbc->*/getBlog($_GET['id']);
?>

<!--データの出力-->
<!--データを１行ずつ取得する-->

<?php
    //メッセージを保存するファイルのパス指定
    define( 'FILENAME', $id .".txt" );
    // タイムゾーン設定
    date_default_timezone_set('Asia/Tokyo');
    // 変数の初期化
    $now_date = null;
    $data = null;
    $file_handle = null;
    $split_data = null;
    $message = array();
    $message_array = array();
    $success_message = null;
    $error_message = array();


    if( !empty($_POST['submit']) ) {
        // 表示名の入力チェック
        if( empty($_POST['message'])  || empty($_POST['view_name'])) {
            $error_message[] = '表示名を入力してください。';
            $alert = "<script type='text/javascript'>alert('入力してください');</script>";
            echo $alert;
        }
        //fopen(message.txt, "r" )
        //一つ目のパラメータはファイル名を含めたパスを指定し、2つ目のパラメータには「モード」を指定する。
        //2つ目のモードは用途に応じて様々な値があり、読み込みだけを行う[r」、書き込みを行う「w」、「a」などが用意されています。
        if( empty($error_message) ) {
            if( $file_handle = fopen( FILENAME, "a") ) {
            //開いたファイルにデータを書き込む
            // 書き込み日時を取得
            $now_date = date("Y-m-d H:i:s");
            $fp = fopen( FILENAME, 'r' );
            for( $count = 0; fgets( $fp ); $count++ );
            //書き込むデータを作成
            $data = "'".$_POST['view_name']."','".$_POST['message']."','".$now_date."','".$count."'\n";
            //書き込み
            fwrite($file_handle, $data);
            //ファイルを閉じる
            fclose( $file_handle);
            $_SESSION['success_message'] = 'メッセージを書き込みました。';
            }
            header('Location: detail.php?id='.$id );
        }	
    }
    if( $file_handle = fopen( FILENAME,'r') ) {
        while( $data = fgets($file_handle) ){
            //文字列を特定の文字で分割する関数
            $split_data = preg_split( '/\'/', $data);
            $message = array(
                'view_name'     => $split_data[1],
                'message'       => $split_data[3],
                'post_date'     => $split_data[5],
                'id'            => $split_data[7]
            );
/*最後に$message_arrayに$messageごと格納します。
この操作を投稿されたメッセージの数だけ繰り返すと、$message_arrayに全てのメッセージのデータが入るという流れです。*/
            array_unshift( $message_array, $message);
        }
        // ファイルを閉じる
        fclose( $file_handle);
    }
    if( !empty( $_POST['delete.$j'])){
    $file   = file( $id .".txt");
    unset($file[$j]);
    file_put_contents($id .".txt", $file);
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
<script>
</script>
<header>
    <i><h1><a href="index.php">Laravel News</a><h1></1>
</header>
<body>
<?php if( empty($_POST['submit']) && !empty($_SESSION['success_message']) ): ?>
	<p class="success_message"><?php echo $_SESSION['success_message']; ?></p>
	<?php unset($_SESSION['success_message']); ?>
 <?php endif; ?>

        <div class="d">  
            <h2></h2>
            <b><?php echo  $column['title']?></b><br><br>
            <?php echo  $column['text'] ?><br>
        </div>
        <h2>コメントを書く</h2>
        <form method="post" >
            <label for="view_name">表示名:</label>
            <input type="text" id="view_name"  name="view_name" value=""><br>
            <label for="message">コメント:</label>
            <input type="text" name="message" class="message"> 
            <input type="submit" value="送信" name="submit"> 
        </form>
        <script>
            $(function () {
                var num = Math.floor(5 * Math.random());
                $('div.message').addClass('background' + num);
            });
        </script>
        <hr>
        <section>
<!--ここにメッセージを表示させる-->
<!--message_arrayが空でないかをチェックする-->
            <?php if( !empty($message_array)  ): ?>
                <?php 
                 $i= 0;
                 $fp = fopen( FILENAME,'r' );
                 for( $count = 0; fgets( $fp ); $count++ );
                 ?>
            <?php 
                foreach( $message_array as $value ): ?>
            <div class="comment" background-color="white">
                <article>
                    <?php 
                    $i++; ?>
                    <p>ID:<?php echo $i?></p> 
                    <h2 color="white"><?php echo $value['view_name']; ?></h2>
                    <time><?php echo date('Y年m月d日 H:i', strtotime($value['post_date'])); ?></time>
                    <p><?php echo $value['message']; ?></p>
                    <form type="post" ><a href="delete_comment.php?i=<?php echo $count -$i  ?>&id=<?php echo $id  ?>">削除<a></form>
                </article>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </body>
</html>
