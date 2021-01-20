<?php
require_once('dbc.php');
//use News\Dbc ; 
$blogdata= getAllBlog();
//データベースのコネクションを使う
/*if (empty($news['category'])){
    exit('カテゴリは必須です');
}
if (empty($news['publish_status'])){
    exit('公開選択は必須です');
}
*/
//createNews($news);
$blogdata= getAllBlog();
$dbh = dbConnect();
//データを入れる場合try catchが必須
//prepare処理を行う
//トランザクションを行う(必ずデータを追加する前に処理を行う)
//$dbh->beginTransaction;
//データが機能しているか確認
/*$mysqli = new mysqli( 'localhost', 'laravel_user', 'saitech3', 'laravel_news');

// 接続エラーの確認
//書き込みの処理
if( $mysqli->connect_errno ) {
    $error_message[] = '書き込みに失敗しました。 エラー番号 '.$mysqli->connect_errno.' : '.$mysqli->connect_error;
} else {
    // あとでここにデータベースの処理を記述する
    $mysqli->set_charset('utf8');
    //データを登録するSQLを作成
    $sql = "INSERT INTO article (title, text ) 
    VALUES  ('$_POST[title]', '$_POST[text]')";
    // データを登録
        $res = $mysqli->query($sql);
        if( $res ) {
            $success_message = 'メッセージを書き込みました。';
        } else {
            $error_message[] = '書き込みに失敗しました。';
        }
        		
        // データベースの接続を閉じる
        $mysqli->close();
}
*/
function makeArticle(){
    require_once('dbc.php');
    $blogdata= getAllBlog();
    $blogdata= getAllBlog();
    $dbh = dbConnect();
    $sql = ' INSERT INTO 
    article(title, text )
    VALUES 
    (:title, :text )';
    try {
        $stmt = $dbh ->prepare($sql);
        //string 型　はPARAM_STR
        $stmt->bindValue(':title',$_POST['title'],PDO::PARAM_STR);
        $stmt->bindValue(':text',$_POST['text'],PDO::PARAM_STR);
        //実行
        $stmt->execute();
        //$dbh-> commit();
        echo "ブログを投稿しました";
        }catch(PDOExcepn $e) {
        //$dbh->rollBack();
        exit($e);
        };
    }
if( !empty($_POST['submit']) ) {
    if( empty($_POST['text'])  || empty($_POST['title'])) {
        $error_message[] = '入力してください。';
        $alert = "<script type='text/javascript'>alert('入力してください');</script>";
        echo $alert;
    }
    if( empty($error_message) ) {
    $alert = 
    "<script type='text/javascript'>
    let result = confirm('本当に送信しますか？');
    if (result) {
    alert( '送信しました');
    } else {
    alert('送信しませんでした');
    window.location.reload();        
    }
    </script>";
    echo $alert;

    makeArticle(); 

    $relord = 
    "<script type='text/javascript'>
    window.location.reload();        
    </script>";
    echo $relord;
       
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
    <style>
        .post{
            width:600px;

        }
        .post-p{
            width:100%;
        }
        .post-button{
            float:right;
        }
        .a{
            background-color:white;
            padding-left:10px;
            padding-top:20px;
            width:1075px;
        }
        header{
        height:40px;
        background-color: #C9FFC3;
        font-family: normal;
        font-size: 12px;
        }
        a{
            text-decoration: none;
            color:black;
        }

        body{
            background-color: rgb(67, 130, 247);
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
        <p>タイトル：
        <input  name="title" type="text"></p>
        <p>記事：
        <textarea name="text" id="text" cols="30" rows="10"></textarea></p>
        <br>
        <!--<p>カテゴリ：</p>
        <select name="category">
            <option value="1">日常</option>
            <option value="2">プログラミング</option>
        </select>
        <br>
        <input type="radio" name="publish_status" value="1" checked>公開
        <input type="radio" name="publish_status" value="2">非公開
        <br>-->
        <input class="button" name="submit" type="submit" value="送信">
        </div>
    </form>
    
<h1>ニュース一覧</h1>

<!--<p><a href="form.html">新規作成</a></p>-->

<table>
   <?php foreach ($blogdata as $column): ?>
    <!--カラムの中にそれぞれの値を当てはめる-->
    <div class="a">
            <b><?php echo $column['title'] ?></b><br><br>
            <!--<td><?php echo $column['post_at'] ?></td>-->
            <?php echo $column['text'] ?><br><br>
            <a href="detail.php?id=<?php echo $column['id'] ?>" > 記事全文・コメントを見る</a><br><br>
            <a href="delete.php?id=<?php echo $column['id'] ?>" > 削除</a><br><br>

           <!-- <form type="get"><button name="delete"  type="submit"> 削除</button>  </form>
            <?php 
                /*if(!empty($_GET['delete']) ){
                    deleteNews($column['id']);
                }*/
            ?>
            -->
    </div>
    <?php endforeach;    ?>
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
