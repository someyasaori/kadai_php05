<?php
//Sessionスタート
session_start();

//関数を呼び出す
require_once('funcs.php');

//ログインチェック
loginCheck();
$user_name = $_SESSION['name'];
$id = $_SESSION['id'];

//以降はログインユーザーのみ

//検索条件取得
$year =$_POST["year"];
$month = $_POST["month"];
// $date = $_POST["date"];

//DB接続

$pdo = db_conn(); 

//IDと一致するテーブル名を作成する
$table_name = "id".$id;

//検索に用いる日時（月初と月末）を定義する
$input_month = $year.'-'.$month.'-'.'1';
$search_month = date('Y-m-d', strtotime($input_month));
$end_month = date('Y-m-d H:i:s', strtotime('last day of '. $search_month.'23:59:59'));

//ログインした人のデータが登録されているテーブルから検索条件に当てはまるものを探す

//指定した月

// SELECT DATE_FORMAT(カラム１, '%Y-%m-%d') AS time, SUM(合計値を求めるカラム) AS sum FROM テーブル名 GROUP BY DATE_FORMAT(カラム１, '%Y%m%d');
$stmt = $pdo->prepare("SELECT DATE_FORMAT(time,'%Y-%m-%d') AS day, SUM(Wh) AS Wh FROM $table_name GROUP BY DATE_FORMAT(time, '%Y%m%d') WHERE time BETWEEN '$input_month' AND '$end_month' ");

// $stmt = $pdo->prepare("SELECT * FROM $table_name WHERE time BETWEEN '$input_month' AND '$end_month' ");
$status = $stmt->execute();

if($status==false){
    sql_error($stmt);
  }else{
    while ($result = $stmt->fetch(PDO::FETCH_ASSOC)){
        $view .= "<tr>";
        $view .= "<td>".h($result['day']).'</td><td>'.h($result['Wh']);
        $view .= "</tr>";
    }
    
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>でんき料金サマリー</title>
</head>
<body>

<h2>検索した月の電気使用量</h2>


<table class="result">
    <tr>
    <th>日にち</th>
    <th>電気使用量（Wh）</th>
    </tr>
    <?= $view ?>
</table>

<p class="return"><a href="index.php">目次に戻る</a></p>

</body>
</html>