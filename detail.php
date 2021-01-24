<?php
$i = $_GET['i'];
$file   = file( "news.txt");
$data1 = $file[$i];
$split_data1 = preg_split( '/\'/', $data1);
$message1 = array(
    'title'         => $split_data1[1],
    'text'          => $split_data1[3],
    'post_date'     => $split_data1[5],
    'id'            => $split_data1[7]
);

?>

<!--データの出力-->
<!--データを１行ずつ取得する-->

<?php
    //メッセージを保存するファイルのパス指定
    define( 'FILENAME1', $i.".txt" );
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
        //一つ目のパラメータはファイル名を含めたパスを指定し、2つ目のパラメータには「モード」を指定する。
        //2つ目のモードは用途に応じて様々な値があり、読み込みだけを行う[r」、書き込みを行う「w」、「a」などが用意されています。
        if( empty($error_message) ) {
            if( $file_handle = fopen( FILENAME1, "a") ) {
            //開いたファイルにデータを書き込む
            // 書き込み日時を取得
            $now_date = date("Y-m-d H:i:s");
            $fp = fopen( FILENAME1, 'r' );
            for( $count = 0; fgets( $fp ); $count++ );
            //書き込むデータを作成
            $data = "'".$_POST['view_name']."','".$_POST['message']."','".$now_date."','".$count."'\n";
            //書き込み
            fwrite($file_handle, $data);
            //ファイルを閉じる
            fclose( $file_handle);
            $_SESSION['success_message'] = 'メッセージを書き込みました。';
            }
            header('Location: detail.php?i='.$i);
        }	
    }
    if( $file_handle = fopen( FILENAME1,'r') ) {
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
    $file   = file( $i .".txt");
    unset($file[$j]);
    file_put_contents($i.".txt", $file);
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
	<p class="success_message"><?php echo $_SESSION['success_message']; ?></p>
	<?php unset($_SESSION['success_message']); ?>
 <?php endif; ?>

        <div class="d">  
            <h2></h2>
   
        <b><?php echo $message1['title'] ?></b><br><br>
        <?php echo $message1['text'] ?><br>
            
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
                 $ii= 0;
                 $fp = fopen( FILENAME1,'r' );
                 for( $count = 0; fgets( $fp ); $count++ );
                 ?>
            <?php 
                foreach( $message_array as $value ): ?>
            <div class="comment" background-color="white">
                <article>
                    <?php 
                    $ii++; ?>
                    ID:<?php echo $ii?>
                    <time><?php echo date('Y年m月d日 H:i', strtotime($value['post_date'])); ?></time>
                    <h2 color="white"><?php echo $value['view_name']; ?></h2>
                    <p><?php echo $value['message']; ?></p>
                    <form method="post" action="delete_comment.php">
                        <input type="hidden" name="i" value=<?php echo $count -$ii ?> >
                        <input type="hidden" name="id" value=<?php echo  $i ?> >
                        <button type="submit" value="削除"　id="">削除</button>
                    </form>
                </article>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </body>
</html>
