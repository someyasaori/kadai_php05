<?php
//select.phpから処理を持ってくる
//1.対象のIDを取得
$id =$_GET["id"];


//2.DB接続します
require_once('funcs.php');
$pdo = db_conn();

//3.削除SQLを作成(detail.phpからエッセンスをコピペ)
$stmt = $pdo->prepare("DELETE FROM gs_bm_table WHERE id=:id");
$stmt->bindValue(':id',$id,PDO::PARAM_INT);
$status = $stmt->execute();


//４．データ削除処理後
if ($status == false) {
    sql_error($stmt);
} else {
    redirect('select.php');
}

?>