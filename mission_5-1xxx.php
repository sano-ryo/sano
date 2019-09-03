<html>

<meta charset="utf-8">

<form action="" method="POST">
<p><input type="text" placeholder="投稿者名" name="name"><br/>
<input type="text" placeholder="投稿内容" name="comment"><br/>
<input type="password" placeholder="任意のパスワード" name="pass">
<input type="submit" value="送信" name="submit"><br/>
<p><input type="text" placeholder="番号" name="num"><br/>
<input type="password" placeholder="パスワード" name="pass1">
<input type="submit" value="削除" name="delete">
<p><input type="text" placeholder="編集番号" name="edit"><br/>
<input type="text" placeholder="編集者名" name="editer"><br/>
<input type="text" placeholder="編集内容" name="recomment"><br/>
<input type="password" placeholder="パスワード" name="pass2">
<input type="submit" value="編集" name="editen">
<p><input type="reset" value="取り消し">
</form>

<?php

$dsn = 'データベース';//データベース作成
$user = 'ユーザー';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

$sql = "CREATE TABLE IF NOT EXISTS sanotb"//テーブル作成
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "date DATETIME,"
	. "pass char(32)"
	.");";
	$stmt = $pdo->query($sql);

if(empty($_POST['comment'])){//何も送信されていない場合
	if(empty($_POST['num'])){
		if(empty($_POST['edit'])){
			$word="入力してください。<br>";
			echo $word;
		}
	}
}

if(!empty($_POST['submit'])){//送信ボタンがおされた場合
	if(!empty($_POST['comment'])){//入力情報の取得およびtxtファイルへの書き込み
		$key="完成！";
		$comment=$_POST['comment'];
		
		If($key==$_POST['comment']){
			$word="おめでとう！<br>";
			echo $word;
		}
		else{
			$word="を受け取りました。<br>";
			echo $comment.$word;
		}
		
		if(empty($_POST['name'])){//名前の記入がない場合
			$name="匿名希望";
		}
		else{//名前の記入がある場合
			$name=$_POST['name'];
		}
		
		if(!empty($_POST['pass'])){//パスワードがある場合
			$pass=$_POST['pass'];
		}
		else{
			$pass="pass";
		}
		
		
		
		$date = new DateTime();
		$date = $date->format('Y/m/d H:i:s');
		
		$sql = $pdo -> prepare("INSERT INTO sanotb (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");//データベースに書き込み
		$sql -> bindParam(':name', $name, PDO::PARAM_STR);
		$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
		$sql -> bindParam(':date', $date, PDO::PARAM_STR);
		$sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
		$sql -> execute();
		
	}
}

if(!empty($_POST['delete'])){//削除ボタンが押された場合
	if(!empty($_POST['num'])){//削除欄に数字が入力された場合番号不一致で異なるtxtに書き込み
		$delete=$_POST['num'];
		if(ctype_digit($delete)){
			if(!empty($_POST['pass1'])){
				$sql = 'SELECT * FROM sanotb';//プラウザ上に表記
				$stmt = $pdo->query($sql);
				$results = $stmt->fetchAll();
				foreach ($results as $row){
					if($row['id']==$delete){
						$pass=$row['pass'];
						if($_POST['pass1']==$pass){//入力されたパスワードと比較
							
							echo $delete."を削除しました。"."<br>";
							
							$id = $delete;
							$sql = 'delete from sanotb where id=:id';
								$stmt = $pdo->prepare($sql);
								$stmt->bindParam(':id', $id, PDO::PARAM_INT);
								$stmt->execute();
						}
						else{
							echo"パスワードが違います。<br>";
						}
					}
				}
			}
			else{
				echo"パスワードを入力してください。<br>";
			}
		}
	}
}

if(!empty($_POST['editen'])){//編集ボタンを押された場合
$date=new DateTime();
$date = $date->format('Y/m/d H:i:s');
	if(!empty($_POST['edit'])){
		$edit=$_POST['edit'];
		if(ctype_digit($edit)){//編集番号が記入されると番号一致の箇所を編集
			if(!empty($_POST['pass2'])){
				$sql = 'SELECT * FROM sanotb';
				$stmt = $pdo->query($sql);
				$results = $stmt->fetchAll();
				foreach ($results as $row){
					if($row['id']==$edit){
						$pass=$row['pass'];
						if($_POST['pass2']==$pass){//入力されたパスワードと比較
							echo $edit."を編集しました。"."<br>";
							
							$id = $_POST["edit"];
							if(!empty($_POST['editer'])){
								$name=$_POST['editer'];
							}
							else{
								$name="匿名希望";
							}
							
							if(!empty($_POST['recomment'])){
								$comment=$_POST['recomment'];
							}
							else{
								$comment="　";
							}
							
							$sql = 'update sanotb set name=:name,comment=:comment,date=:date where id=:id';
								$stmt = $pdo->prepare($sql);
								$stmt->bindParam(':name', $name, PDO::PARAM_STR);
								$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
								$stmt->bindParam(':id', $id, PDO::PARAM_INT);
								$stmt->bindParam(':date', $date, PDO::PARAM_STR);
								$stmt->execute();
						}
						else{
							echo"パスワードが違います。<br>";
						}
					}
				}
			}
			else{
				echo"パスワードを入力してください。<br>";
			}
		}
	}
}


$sql = 'SELECT * FROM sanotb';//プラウザ上に表記
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		echo $row['date'].'<br>';
	echo "<hr>";
	}
?>