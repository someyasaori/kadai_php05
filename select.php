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

//ログインした人のデータが登録されているテーブル全て
// $stmt = $pdo->prepare("SELECT * FROM $table_name");

//ログインした人のデータが登録されているテーブルから検索条件に当てはまるものを探す

//指定した月毎の合計
$stmt1 =$pdo->prepare ("SELECT SUM(Wh) as Wh FROM $table_name WHERE time BETWEEN '$input_month' AND '$end_month' ");
$status = $stmt1->execute();
if($row1 = $stmt1 -> fetch()){
    $sum_selected_month = $row1['Wh'];
    }

//今月の合計
$this_month = date('Y-m-d', strtotime('first day of this month'));
$today = date('Y-m-d H:i:s', strtotime('now'));
$stmt2 =$pdo->prepare ("SELECT SUM(Wh) as Wh FROM $table_name WHERE time BETWEEN '$this_month' AND '$today' ");
$status = $stmt2->execute();
if($row2 = $stmt2 -> fetch()){
    $sum_this_month = $row2['Wh'];
    }

//先月の合計
// $last_month = date('Y-m-d', strtotime('first day of last month'));
// $end_of_last_month = date('Y-m-d H:i:s', strtotime('last day of '. $last_month.'23:59:59'));
$this_this_month = date('m', strtotime('this month')) ;
$this_year =  date('Y', strtotime('this month')) ;
$one_month_before= $this_this_month - 1;
$month_one  = $this_year.'-'.$one_month_before.'-'.'1';
$end_month_one = date('Y-m-d H:i:s', strtotime('last day of '. $month_one.'23:59:59'));

$stmt3 =$pdo->prepare ("SELECT SUM(Wh) as Wh FROM $table_name WHERE time BETWEEN '$month_one' AND '$end_month_one' ");

$status = $stmt3->execute();

if($row3 = $stmt3 -> fetch()){
    $sum_last_month = $row3['Wh'];
    }

//2か月前の合計
$two_month_before= $this_this_month - 2;
$month_two  = $this_year.'-'.$two_month_before.'-'.'1';
$end_month_two = date('Y-m-d H:i:s', strtotime('last day of '. $month_two.'23:59:59'));

$stmt4 =$pdo->prepare ("SELECT SUM(Wh) as Wh FROM $table_name WHERE time BETWEEN '$month_two' AND '$end_month_two' ");
$status = $stmt4->execute();

if($row4 = $stmt4 -> fetch()){
    $sum_two_month_before = $row4['Wh'];
    }

//3か月前の合計
$this_month = date('m', strtotime('this month')) ;
$this_year =  date('Y', strtotime('this month')) ;
$three_month_before= $this_this_month - 3;
$month_three  = $this_year.'-'.$three_month_before.'-'.'1';
$end_month_three = date('Y-m-d H:i:s', strtotime('last day of '. $month_three.'23:59:59'));

$stmt5 =$pdo->prepare ("SELECT SUM(Wh) as Wh FROM $table_name WHERE time BETWEEN '$month_three' AND '$end_month_three' ");
$status = $stmt5->execute();

if($row5 = $stmt5 -> fetch()){
    $sum_three_month_before = $row5['Wh'];
    }

// if($stmt==false){
//     sql_error($stmt);
// }else{

// if($status==false){
//     $error = $stmt->errorInfor();
//     exit("ErrorQuery:". $error[2]);
// }else{
    // while ($result = $stmt->fetch(PDO::FETCH_ASSOC)){
    //     $view .= "<tr>";
    //     $view .= "<td>".h($result['time']).'</td><td>'.h($result['W']).'</td><td>'.h($result['Wh']);
    //     $view .= "</tr>";
    // }
//     $row = $stmt->fetch();
//     echo $row;
//     exit();
// }

?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
    <title>でんき料金サマリー</title>
</head>
<body>
<h2>最近のでんきの使い方は？</h2>

<canvas id="chart" height="100" width="200"></canvas>

<p class="return"><a href="index.php">トップに戻る</a></p>

<!-- JQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<!-- JQuery -->

<script>

//年月表示の整理
let this_month = '<?= $this_year ?>'+'/'+'<?= $this_month ?>'
let one_month_before = '<?= $this_year ?>'+'/'+'<?= $one_month_before ?>'
let two_month_before = '<?= $this_year ?>'+'/'+'<?= $two_month_before ?>'
let three_month_before = '<?= $this_year ?>'+'/'+'<?= $three_month_before ?>'

//Chart.jsで棒グラフを描く
jQuery (function ()
{const config = {
        type: 'bar',
        data: barChartData,
        responsive : true
        }

    const context = jQuery("#chart")
    const chart = new Chart(context,config)
})

const barChartData = {
    labels : [three_month_before, two_month_before, one_month_before, this_month],
    datasets : [
        {
        label: "電気使用量(kWh)",
        backgroundColor: "rgba(60,179,113,0.5)",
        data : [<?= $sum_three_month_before ?>,<?= $sum_two_month_before ?>,<?= $sum_last_month ?>,<?= $sum_this_month ?>]
        },   
    ]
}

</script>

</body>
</html>