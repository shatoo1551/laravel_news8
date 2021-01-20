
<?php
    //メッセージを保存するファイルのパス指定
    define( 'FILENAME', "news.txt" );
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
        if( empty($_POST['title'])  || empty($_POST['text'])) {
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
            $data = "'".$_POST['title']."','".$_POST['text']."','".$now_date."','".$count."'\n";
            //書き込み
            fwrite($file_handle, $data);
            //ファイルを閉じる
            fclose( $file_handle);
            $_SESSION['success_message'] = 'メッセージを書き込みました。';
            }
            header('Location: index.php'.$id );
        }	
    }
    if( $file_handle = fopen( FILENAME,'r') ) {
        while( $data = fgets($file_handle) ){
            //文字列を特定の文字で分割する関数
            $split_data = preg_split( '/\'/', $data);
            $message = array(
                'title'         => $split_data[1],
                'text'          => $split_data[3],
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
    <link href="index.css" type="text/css" rel="stylesheet">
    <title>Document</title>
    <style>
        .post{
            width:100%;
            border-bottom: solid white 1px;
            padding-bottom: 10px;
        }
        .post-p{
            width:100%;
        }
        .post-button{
            float:right;
        }
        .box{
            background-color:white;
            padding-left:10px;
            padding-top:20px;
            width:1075px;
            color:black;
            text-decoration: none;
            width:100%;
            margin-bottom:10px
        }
        header{
        height:40px;
        background-color: #C9FFC3;
        font-family: normal;
        font-size: 12px;
        }
        body{
            background-color: rgb(67, 130, 247);

        }
        dt{
            float:left;
            clear:both;
        }
        dd{

        }
        .button{
            float:right;
        }
    </style>

</head>
<header>
    <i><h1><a href="index.php">Laravel News</a><h1></1>
</header>
<body>
    <?php 
   
       /* if ($_POST['title']>100){
            $alert = "<script type='text/javascript'>alert('タイトルは１０字以下にしてください');</script>";
            echo $alert;
        };
        */
    ?>

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
        <!--<p>カテゴリ：</p>
        <select name="category">
            <option value="1">日常</option>
            <option value="2">プログラミング</option>
        </select>
        <br>
        <input type="radio" name="publish_status" value="1" checked>公開
        <input type="radio" name="publish_status" value="2">非公開
        <br>-->
        </div>
    </form>
    
<h1>ニュース一覧</h1>
<!--<p><a href="form.html">新規作成</a></p>-->
件数:
<?php  
$fp = fopen( FILENAME,'r' );              
for( $count = 0; fgets( $fp ); $count++ );
$i=0; 
echo $count;
?>
件
<table>
   <?php foreach ($message_array as $column): ?>
    <!--カラムの中にそれぞれの値を当てはめる-->
    <div class="box">
            <?php  $i++; 
            echo $i;
            ?>
            <b><?php echo $column['title'] ?></b><br><br>
            <!--<td><?php echo $column['post_at'] ?></td>-->
            <?php echo $column['text'] ?><br><br>
            <a href="detail.php?i=<?php echo $count-$i ?>" > 記事全文・コメントを見る</a><br><br>
            <a href="delete.php?i=<?php echo $count-$i ?> ">削除</a><br><br>

           <!-- <form type="get"><button name="delete"  type="submit"> 削除</button>  </form>
            <?php 
                /*if(!empty($_GET['delete']) ){
                    deleteNews($column['id']);
                }*/
            ?>
            -->
    </div>
    <?php endforeach;?>
</table>

<?
    //１ページに表示させる記事数
    define ('MAX', '8');
    $news_array = array (
            $blogdata('title'), $blogdata('text')
            );
        //データ件数
    $news_array_num = count($news_array);
        // トータルページ数※ceilは小数点を切り捨てる関数
    $max_page = ceil($news_array_num / MAX); 
        
    if(!isset($_GET['page_id'])){ // $_GET['page_id'] はURLに渡された現在のページ数
    $now = 1; // 設定されてない場合は1ページ目にする
    }else{
        $now = $_GET['page_id'];
    }
    $start_no = ($now - 1) * MAX; // 配列の何番目から取得すればよいか
    // array_sliceは、配列の何番目($start_no)から何番目(MAX)まで切り取る関数
    $disp_data = array_slice($news_array, $start_no, MAX, true);
        
    foreach($disp_data as $val){ // データ表示
    echo $val['title'].$val['text'];
    }
    for($i = 1; $i <= $max_page; $i++){ // 最大ページ数分リンクを作成
        if ($i == $now) { // 現在表示中のページ数の場合はリンクを貼らない
            echo $now. '　'; 
        } else {
            echo "ko";
        }
    }        
?>


</body>
 </html>
