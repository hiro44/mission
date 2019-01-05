<html>
<head>
<title>
</title>
<meta charset="utf-8">
</head>
<body>
<?php
//データベースへの接続
$dsn='データベース名';
$user='ユーザー名';
$password='パスワード';
$pdo=new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));

//テーブルの作成
$sql="CREATE TABLE IF NOT EXISTS mission"
."("
."id INT not null auto_increment primary key,"
."name char(32),"
."comment TEXT,"
."time DATETIME,"
."password TEXT,"
."flg INT"
.");";
$stmt=$pdo->query($sql);

$number_edit=$_POST['number_edit'];
$edit=$_POST['edit'];
$pass_edit=$_POST['password_edit'];
//編集機能
if($_POST['button_edit']!=""){
//検索
	$sql='SELECT*FROM mission';
	$stmt=$pdo->query($sql);
	$results=$stmt->fetchAll();
	foreach($results as $row){
		if($edit==$row['id']&&$pass_edit==$row['password']){
			$f_name=$row['name'];
			$f_comment=$row['comment'];
			$number_edit=$row['id'];
		}
	}
}else{
	$f_name="名前";
	$f_comment="コメント";
}
if($number_edit!=""){
//検索
	$sql='SELECT*FROM mission';
	$stmt=$pdo->query($sql);
	$results=$stmt->fetchAll();
	foreach($results as $row){
//実際に編集
		if($number_edit==$row['id']){
			if($_POST['submit']!=""){
				$id=$number_edit;
				$name=$_POST['name'];
				$comment=$_POST['comment'];
				$sql='update mission set name=:name,comment=:comment where id=:id';
				$stmt=$pdo->prepare($sql);
				$stmt->bindParam(':name',$name,PDO::PARAM_STR);
				$stmt->bindParam(':comment',$comment,PDO::PARAM_STR);
				$stmt->bindParam(':id',$id,PDO::PARAM_INT);
				$stmt->execute();
				$number_edit="";
			}else{
			}
		}else{
		}
	}
}else{
//送信部分
	if($_POST['submit']==""){
	}else if($_POST['submit']!=""){
		$sql=$pdo->prepare("INSERT INTO mission (id,name,comment,time,password,flg) VALUES('',:name,:comment,:time,:password,:flg)");
		$sql->bindParam(':name',$name,PDO::PARAM_STR);
		$sql->bindParam(':comment',$comment,PDO::PARAM_STR);
		$sql->bindParam(':time',$time,PDO::PARAM_STR);
		$sql->bindParam(':password',$password,PDO::PARAM_STR);
		$sql->bindParam(':flg',$flg,PDO::PARAM_INT);
		$name=$_POST['name'];
		$comment=$_POST['comment'];
		$time=new DateTime();
		$time=$time->format('Y-m-d H:i:s');
		$password=$_POST['password'];
		$flg=0;
		$sql->execute();
	}
}
//$sql='SHOW CREATE TABLE mission';
//$result=$pdo->query($sql);
//foreach($result as $row){
//	echo $row[1];
//	}
//echo "<hr>";
?>

<form action="mission_4-1.php" method="post">
<input type="text" name="name" value="<?php echo $f_name; ?>"><br>
<input type="text" name="comment" value="<?php echo $f_comment; ?>"><br>
<input type="text" name="password" value="パスワード">
<input type="submit" name="submit" value="送信"><br>
<input type="hidden" name="number_edit" value="<?php echo $number_edit; ?>"><br>
<input type="text" name="delete" value="削除対象番号"><br>
<input type="text" name="password_delete" value="パスワードを入力してください">
<input type="submit" name="button_delete" value="削除"><br><br>
<input type="text" name="edit" value="編集対象番号"><br>
<input type="text" name="password_edit" value="パスワードを入力してください">
<input type="submit" name="button_edit" value="編集"><br>
</form>

<?php
//削除部分
if($_POST['button_delete']!=""){
	$number_delete=$_POST['delete'];
	$pass_delete=$_POST['password_delete'];

//検索
	$sql='SELECT*FROM mission';
	$stmt=$pdo->query($sql);
	$results=$stmt->fetchAll();
	foreach($results as $row){
		if($number_delete==$row['id']&&$pass_delete==$row['password']){
//フラグを0から1へ変更する
			$id=$number_delete;
			$flg=1;
			$sql='update mission set flg=:flg where id=:id';
			$stmt=$pdo->prepare($sql);
			$stmt->bindParam(':flg',$flg,PDO::PARAM_INT);
			$stmt->bindParam(':id',$id,PDO::PARAM_INT);
			$stmt->execute();
		}else{
		}
	}
}
//表示部分
$sql='SELECT*FROM mission';
$stmt=$pdo->query($sql);
$results=$stmt->fetchAll();
foreach($results as $row){
	if($row['flg']==0){
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		echo $row['time'].'<br>';
	}
}
?>
</body>
</html>