<?php
    //Class Dbc 
    //{
            

        
        //MySqlデータベースに接続する
        //das = 'mysql:host=localhost;dbname=データの名前;charset=utf8';
        //$user='root'
        //$pass='root'
        //$dsn= 'mysql:host=localhost;dbname=laravel_news;charset=utf8';
        //$user= 'laravel_user';
        //$pass= 'saitech3';
        //PDO接続を呼び出す(PDOクラスのインスタンスを作成する)
        //$dbh= new PDO($dsn,$user,$pass);
        //接続したら表示させる
        //try~catch文とは？
        //例外処理と呼ばれる
        /*try {
            /通常処理を書く。問題がなければ次へ
        }catch(){
            例外(エラー)が発生した場合に処理
        }*/
        /*try{
            $dbh= new PDO($dsn,$user,$pass,[
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
            //echo '接続成功';
            //データを取得する
            //①SQLの取得
            //②SQLの実行
            //③結果を受け取る
            //④結果の表示
            $sql = 'SELECT * FROM article';
            $stmt = $dbh-> query($sql);
            $result  = $stmt->fetchall(PDO::FETCH_ASSOC);
            var_dump($result);
            $dbh =null;
            //$eとはエラーの中身
        } catch (PODException $e) {
            //エラーメッセージを表示
            echo '接続失敗' .$e-> getMessage();
            //処理を終了
            exit();
        }
        */
        //namespace News\Dbc;
    
        //関数1:データベースに接続する
        //データに接続しているか確認 
        //var_dump($dbh);*/  
        function dbConnect() {
        
        $dsn= 'mysql:host=localhost;dbname=laravel_news;charset=utf8';
        $user= 'laravel_user';
        $pass= 'saitech3';  
       
        
        //データが機能しているか確認
        try{
            $dbh= new \PDO($dsn,$user,$pass,[
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            ]);
            //$eとはエラーの中身
        } catch (PODException $e) {
            //エラーメッセージを表示
            echo '接続失敗' .$e-> getMessage();
            //処理を終了
            exit();
        }
        //データに接続しているか確認 
        return $dbh;
        }

        dbConnect();

        //関数２：データの取得(引数なし、送り値：取得したデータ)
        function getAllBlog(){
            $dsn= 'mysql:host=localhost;dbname=laravel_news;charset=utf8';
            $user= 'laravel_user';
            $pass= 'saitech3';
            $dbh= new \PDO($dsn,$user,$pass,[
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                ]);
            //echo '接続成功';
            //データを取得する
            //①SQLの取得
            $sql = 'SELECT * FROM article';
            //②SQLの実行
            $stmt = $dbh-> query($sql);
            //③結果を受け取る
            $result  = $stmt->fetchall(\PDO::FETCH_ASSOC);
            //④結果の表示   
            return $result;
            $dbh =null;
        }
        //データを表示
        $blogdata= getAllBlog();

        //関数③：個々のデータを取得
        //引数: $id
        //返り値：$result
        function getBlog($id) {
            if(empty($id)){
                exit('IDが不正です');
            }
            $dbh=dbConnect();
            //SQL準備
            $stmt=$dbh->prepare('SELECT * FROM article Where id = :id');
            $stmt-> bindValue(':id', (int)$id,\PDO::PARAM_INT);
            //SQL実行
            $stmt-> execute();
            //結果を表示
            $result = $stmt -> fetch(\PDO::FETCH_ASSOC);
            if (!$result){
                exit('ニュースがありません');
                }
            return $result;
        }

        //関数④：新しく投稿する
        function createNews　($news){
        $sql = ' INSERT INTO 
            article(title, text )
        VALUES 
            (:title, :text )';

        $dbh = dbConnect();
        //データを入れる場合try catchが必須
        //prepare処理を行う
        //トランザクションを行う(必ずデータを追加する前に処理を行う)
        //$dbh->beginTransaction;
        //データが機能しているか確認
        try {
            $stmt = $dbh ->prepare($sql);
            //string 型　はPARAM_STR
            $stmt->bindValue(':title',$news['title'],PDO::PARAM_STR);
            $stmt->bindValue(':text',$news['text'],PDO::PARAM_STR);
            //実行
            $stmt->execute();
            $dbh-> commit();
            echo "ブログを投稿しました";
        }catch(PDOExcepn $e) {
            $dbh->rollBack();
            exit($e);
        }
        }

        //関数⑤:データを削除する
        function deleteNews($id) {
            if(empty($id)){
                exit('IDが不正です');
            }
            $dbh=dbConnect();
            //SQL準備
            $stmt=$dbh->prepare('DELETE FROM article Where id = :id');
            $stmt-> bindValue(':id', (int)$id,\PDO::PARAM_INT);
            //SQL実行
            $stmt-> execute();
            //結果を表示
            echo 'ニュースを削除しました';
        }
    //}



    function makeNewstxt(){
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
                    'title'     => $split_data[1],
                    'text'       => $split_data[3],
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
    }

 makeNewstxt();

?>

<!--
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href=dbc.css type="text/css" rel="stylesheet">
    <title>Document</title>
</head>
<body>
<h1>ブログ一覧</h1>
<table>
    <tr>
        <th>No</th>
        <th>タイトル</th>
        <th>カテゴリ</th>
    </tr>
    <?php foreach ($blogdata as $column): ?>
    カラムの中にそれぞれの値を当てはめる
    <div class="a">
        <tr class="b">
            <td><?php echo $column['id'] ?></td>
            <td><?php echo $column['title'] ?></td>
            <td><?php echo $column['text'] ?></td>
            <td><a href="detail.php?id=<?php echo $column['id'] ?>" > 詳細</a></td>
        </tr>
    </div>
    <?php endforeach; ?>
</table>
</body>
</html>-->