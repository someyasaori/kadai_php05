<?php

//外部ファイルから関数を読み込みDB接続(funcs.phpを呼び出す)
require_once('funcs.php');
$pdo = db_conn();

//対象のIDをGET通信で取得
$id = $_GET['id'];
// echo $id;

//データ取得SQLを作成し、内容を更新したいアイテムの詳細をDBから取ってくる（SELECT文）
$stmt = $pdo->prepare("SELECT * FROM gs_bm_table WHERE id=:id");
$stmt->bindValue(':id',$id,PDO::PARAM_INT);
$status = $stmt->execute();

//データ表示
$view = "";
if ($status == false) {
    sql_error($status);
} else {
    $result = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>お役立ちリンク集</title>
</head>
<body>
<h1>登録済みの内容を修正</h1>

<div class="update">
    <form method="POST" action="update.php">
        <p class="centering">タイトル<input type="text" name="title" value ="<?=$result['title']?>" id="title" size ="15"></p>
        <p class="centering">URL<input type="text" name="url" value ="<?=$result['url']?>" id="url" size ="30"></p>
        <p class="centering">詳細<input type="text" name="details" value ="<?=$result['details']?>" id="details" size ="30"></p>
        <p class="centering">タグ
        <select name="tag" value ="<?=$result['tag']?>" id="tag">
            <option value="VPP">VPP</option>
            <option value="再エネ">再エネ</option>
            <option value="リソース">リソース</option>
        </select></p>
        <p><input type="hidden" name ="id" value= "<?=$result['id']?>"></p>
        <p class="centering"><input type="submit" id="submit" value ="登録"></p>
    </form> 
</div>

</body>
</html>