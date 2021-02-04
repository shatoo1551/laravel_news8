<?php    
//関数①：データベース接続   
function dbConnect() {
    $dsn= 'mysql:host=localhost;dbname=laravel_news;charset=utf8';
    $user= 'laravel_user';
    $pass= 'saitech3';  
    //データが機能しているか確認
    try{
        $dbh= new PDO($dsn,$user,$pass,[
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
        //$eとはエラーの中身
        } 
    catch (PODException $e) {
        //エラーメッセージを表示
        echo '接続失敗' .$e-> getMessage();
        //処理を終了
        exit();
        }
    //データに接続しているか確認 
    return $dbh;
}


//関数②：データの取得(引数なし、送り値：取得したデータ)
function getAllNews1($dbh ){
    //echo '接続成功';
    //データを取得する
    //①SQLの取得
    $sql = 'SELECT * FROM article' ;
    //②SQLの実行
    $stmt = $dbh-> query($sql);
    //③結果を受け取る
    $result  = $stmt->fetchall(PDO::FETCH_ASSOC);
    //④結果の表示   
    return $result;
    $dbh =null;
}


//関数②：PDO データ取得（簡単） pageIDによって変わる
function getAllcomments($id) {
    if(empty($id)){
        exit('IDが不正です');
    }
    $dbh=dbConnect();
    //SQL準備
    $stmt=$dbh->prepare('SELECT * FROM comments Where newsnumber = :id');
    $stmt-> bindValue(':id', (int)$id,\PDO::PARAM_INT);
    //SQL実行
    $stmt-> execute();
    //結果を表示
    $result = $stmt -> fetchall(\PDO::FETCH_ASSOC);
    return $result;
}


//関数③：個々のニュースデータを取得
//引数: $id
//返り値：$result
function getNews($id) {
if(empty($id)){
    exit('IDが不正です');
}
$dsn= 'mysql:host=localhost;dbname=laravel_news;charset=utf8';
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



//関数④：Mysqli(オブジェクト型)  データベースに投稿する
function NewCreateArticle4(){
        $mysqli = new mysqli( 'localhost', 'laravel_user', 'saitech3', 'laravel_news');
        //エラー確認
        if( $mysqli->connect_errno ) {
        $error_message[] = '書き込みに失敗しました。 エラー番号 '.$mysqli->connect_errno.' : '.$mysqli->connect_error;
        } else {
                // 文字コード設定
                $mysqli->set_charset('utf8');
                
                // 書き込み日時を取得
                $now_date = date("Y-m-d H:i:s");
                
                // データを登録するSQL作成
                $sql = "INSERT INTO article (title , text ) VALUES ( '$_POST[title]', '$_POST[text]')";

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
    }


//関数④：mysqli オブジェクト思考型
function NewCreateComments3($id) {
    $mysqli = new mysqli( 'localhost', 'laravel_user', 'saitech3', 'laravel_news');
    //エラー確認
    if( $mysqli->connect_errno ) {
    $error_message[] = '書き込みに失敗しました。 エラー番号 '.$mysqli->connect_errno.' : '.$mysqli->connect_error;
    } else {
            // 文字コード設定
            $mysqli->set_charset('utf8');
            
            // 書き込み日時を取得
            $now_date = date("Y-m-d H:i:s");
            
            // データを登録するSQL作成
            $sql = "INSERT INTO comments (view_name , message , newsnumber  ) VALUES ( '$_POST[view_name]', '$_POST[message]', '$id')";

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
}


//関数⑤:データを削除する
function deleteNews($id) {
    if(empty($id)){
        exit('IDが不正です');
    }

    $dbh=dbConnect();
    $stmt=$dbh->prepare('DELETE FROM article Where id = :id');
    $stmt-> bindValue(':id', (int)$id,\PDO::PARAM_INT);
    //SQL実行
    $stmt-> execute();
    //結果を表示
    echo 'ニュースを削除しました';
}


//関数⑥:コメントを削除する
function deleteComments($id) {
    if(empty($id)){
        exit('IDが不正です');
    }

    $dbh=dbConnect();
    $stmt=$dbh->prepare('DELETE FROM comments Where id = :id');
    $stmt-> bindValue(':id', (int)$id,\PDO::PARAM_INT);
    //SQL実行
    $stmt-> execute();
    //結果を表示
    echo 'ニュースを削除しました';
}

?>