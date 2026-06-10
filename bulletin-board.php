<?php
$message = "";
$dsn = 'mysql:dbname=データベース名;host=localhost';
$user = 'ユーザー名';
$db_password = 'パスワード名';
$pdo = new PDO($dsn, $user, $db_password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
$id = "";
$id_edit = "";
$name_edit = "";
$comment_edit = "";
if (!empty($_POST["edit"])){
    $id = $_POST["edit"];
    $sql = 'SELECT * FROM m5_4 where id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch();
    if ($row && $_POST["password_edit"] == $row["password"]){
        $name_edit = $row['name'];
        $comment_edit = $row['comment'];
        $id_edit = $id;
    }elseif (!$row){
            $message = "投稿が存在しません";
    }else{
        $message = "パスワードが違います";
    }
}
if (!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["password"])){
    if (!empty($_POST["edit_hidden"])){
        $id = $_POST["edit_hidden"];
        $sql = 'SELECT * FROM m5_4 where id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        if ($row && $_POST["password"] == $row["password"]){
            $name = $_POST["name"];
            $comment = $_POST["comment"];
            $date = date("Y-m-d H:i:s");
            $id_edit = $_POST["edit_hidden"];
            $sql = 'UPDATE m5_4 SET name=:name, comment=:comment, date=:date where id =:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':date', $date, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id_edit, PDO::PARAM_INT);
            $stmt->execute();
            $id_edit = "";
            $name_edit = "";
            $comment_edit = "";
        }elseif (!$row){
            $message = "投稿が存在しません";
        }else{
            $message = "パスワードが違います";
        }
    }else{
        $name = $_POST["name"];
        $comment = $_POST["comment"];
        $date = date("Y-m-d H:i:s");
        $password = $_POST["password"];
        $sql = 'INSERT INTO m5_4 (name, comment, date, password) VALUES (:name, :comment, :date, :password)';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->execute();
    }
}
if (!empty($_POST["delete"])){
    $id = $_POST["delete"];
    $sql = 'SELECT * FROM m5_4 where id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch();
    if ($row && $_POST["password_delete"] == $row["password"]){
        $sql = 'DELETE FROM m5_4 where id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }elseif (!$row){
        $message = "投稿が存在しません";
    }else{
        $message = "パスワードが違います";
    }
}
?>
<!DOCTYPE html>
<html lang = "ja">
<head>
    <meta charset = "UTF-8">
    <title>m5-4</title>
</head>
<body>
    
    <h1>掲示板</h1>
    <p>投稿時のパスワードを用いて削除、編集できます。</p>    
    <p style="color:red;"><?php echo $message;?></p>
    
    <form action = "" method = "post">
        <input type = "text" name = "name" value = "<?php echo $name_edit ?>" placeholder = "名前">
        <input type = "text" name = "comment" value = "<?php echo $comment_edit ?>" placeholder = "コメント">
        <input type = "password" name = "password" placeholder = "パスワード">
        <input type = "hidden" name = "edit_hidden" value = "<?php echo $id_edit ?>">
        <input type = "submit" name = "submit" value = "投稿">
    </form>
    <br />
    <form action = "" method = "post">
        <input type = "number" name = "delete" placeholder = "削除したい番号">
        <input type = "password" name = "password_delete" placeholder = "パスワード">
        <input type = "submit" name = "submit" value = "削除">
    </form>
    <form action = "" method = "post">
        <input type = "number" name = "edit" placeholder = "編集したい番号">
        <input type = "password" name = "password_edit" placeholder = "パスワード">
        <input type = "submit" name = "submit" value = "送信">
    </form>
    <?php
    $sql = 'SELECT * FROM m5_4 ORDER BY id';
    $stmt = $pdo->query($sql);
    foreach ($stmt as $row){
        echo $row['id'] . "<>";
        echo $row['name'] . "<>";
        echo $row['comment'] . "<>";
        echo $row['date'] . "<br>";
    }
    ?>
</body>
</html>