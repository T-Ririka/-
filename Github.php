<!DOCTYPE html>
<html lang="ja">
    
<head>
    <meta charset="UTF-8">
    <title>m5-01</title>
</head>

<body>
    
    <?php
    //DB接続設定
    $dsn =  "データベース名";
    $user = "ユーザー名";
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    //テーブルを作成
    $sql = "CREATE TABLE IF NOT EXISTS tbtest"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"  //名前
    . "comment TEXT,"   //コメント
    . "date TEXT,"   //日付
    . "password TEXT" //パスワード
    .");";
    $stmt = $pdo->query($sql);

?>

<?php   //編集フォーム
    if(!empty($_POST["editNum"]) && !empty($_POST["edPassword"])){
        
        $editNum=$_POST["editNum"];
        $editPass=$_POST["edPassword"];
        
        $sql = 'SELECT * FROM tbtest';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                if( $row["id"] == $editNum && $row["password"] == $editPass ){
                    $editName = $row["name"];
                    $editMsg = $row["comment"];
                    $editPass = $row["password"];
                }
            }
    } else {
        $editName = "";
        $editMsg = "";
        $editNum = "";
    }
?>

<?php  //入力フォーム
    if (!empty($_POST["name"]) && !empty($_POST["message"]) ) {
        
        //通常
        if(empty($_POST["checkBox"])){
            
            $sql = $pdo -> prepare("INSERT INTO tbtest (name, comment, date, password) VALUES (:name, :comment, :date, :password)");
            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql -> bindParam(':date', $date, PDO::PARAM_STR);
            $sql -> bindParam(':password', $password, PDO::PARAM_STR);
            $name = $_POST["name"];
            $comment = $_POST["message"];
            $date = date("Y/m/d h:i:s");
            $password = $_POST["password"];
            $sql -> execute();
        
        //編集
        } else {
            
            $numberCheck = $_POST["checkBox"];
            $editPass = $_POST["editPass"];
            
            $sql = 'SELECT * FROM tbtest';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                
                if ($row["id"] == $numberCheck && $editPass == $row["password"]){
                    
                    $id = $row["id"]; 
                    $name = $_POST["name"];
                    $comment = $_POST["message"];
                    $date = date("Y/m/d h:i:s");
                    $sql = 'UPDATE tbtest SET name=:name,comment=:comment ,date=:date WHERE id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                    $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                    
                } 
            }
        }
    }
?>

<?php  //削除フォーム
    if (!empty($_POST["deleteno"]) && !empty($_POST["dePassword"])) {

        $deNumber=$_POST["deleteno"];
        $dePassword=$_POST["dePassword"];
        
        $sql = 'SELECT * FROM tbtest';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                if ( $row["id"] == $deNumber && $dePassword == $row["password"]){
                    $id = $row["id"];
                    $sql = 'delete from tbtest where id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                }
            }
    }
?>
    
<form action="" method="post">
    【投稿フォーム】<br>
        <input type="text" name="name" placeholder="名前"
            value="<?php if(isset($editName)) {echo $editName;} ?>"> <br>
        <input type="text" name="message" placeholder="コメント"
            value="<?php if(isset($editMsg)) {echo $editMsg;} ?>">
            <!--投稿フォームが新規か編集か判断する -->
            <input type="hidden" name="checkBox"
                value="<?php if(isset($editNum)) {echo $editNum;} ?>"><br>
            <input type="hidden" name="editPass"
                value="<?php if(isset($editPass)) {echo $editPass;} ?>">
        <!--パスワードの入力欄-->
        <input type="text" name="password" placeholder="パスワード">
        <input type="submit" name="submit"> <br><br>
    【削除フォーム】<br>
        <input type="text" name="deleteno" placeholder="削除対象番号"><br>
        <input type="text" name="dePassword" placeholder="パスワード入力">
        <input type="submit" name="delete" value="削除"> <br><br>
    【編集番号指定用フォーム】<br>
        <input type="text" name="editNum" placeholder="編集対象番号"><br>
        <input type="text" name="edPassword" placeholder="パスワード入力">
        <input type="submit" name="edit" value="編集"><br><br><br>
</form>
    
<?php //テーブルの表示
    $sql = 'SELECT * FROM tbtest';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        echo $row["id"]." ";
        echo $row["name"]." ";
        echo $row["comment"]." ";
        echo $row["date"]." ";
        echo "<br>";
    }
?>

</body>

</html>