<?php
//detail.phpで入力したデータ（POSTデータ）を取得
$title =$_POST["title"];
$url= $_POST["url"];
$details = $_POST["details"];
$tag = $_POST["tag"];
$id = $_POST["id"];

//DB接続（mysql）
require_once('funcs.php');
$pdo = db_conn();

//データ更新SQL作成
// $stmt = $pdo->prepare("UPDATE gs_an_table SET name = :name, email = :email, age = :age, content = :content, indate = sysdate() WHERE id = :id;" );

$stmt = $pdo->prepare("UPDATE gs_bm_table SET title = :title, url = :url, details = :details, tag= :tag, indate = sysdate() WHERE id = :id;" );


//バインド変数
$stmt->bindValue(':title', $title, PDO::PARAM_STR);
$stmt->bindValue(':url', $url, PDO::PARAM_STR);
$stmt->bindValue(':details', $details, PDO::PARAM_STR);
$stmt->bindValue(':tag', $tag, PDO::PARAM_STR);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
// 文字の場合 PDO::PARAM_STR、数値の場合 PDO::PARAM_INT

// 5. 実行
$status = $stmt->execute();

//４．データ編集処理後
if ($status == false) {
    sql_error($stmt);
} else {
    redirect('select.php');
}


// //データ登録処理後エラーがあった場合
// if($status==false){
//     $error = $stmt->errorInfo();
// }else{

// //エラー無ければselect.phpヘリダイレクト
// header('Location:select.php');
// }


?>