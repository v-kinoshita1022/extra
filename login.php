<?php
require_once("../util/dbaccess.php");//共通ファイル読み込み
require_once("../util/scriptUtil.php");
//print($_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]).'<br>';
//print($_SERVER["REQUEST_URI"]);

session_start();
$name= $_POST["name"]?$_POST['name']:null;
$password= $_POST["password"]?$_POST['password']:null;
 ?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>ログイン</title>
</head>
<body>
<div id="header">
<a href="<?php echo ROOT_URL ?>"><h1>かごゆめ</h1></a></div>

  <form action="login.php" method="post">
   ユーザーネーム<input type="text" name="name" value="<?php echo $name; ?>">
   パスワード<input type="text" name="password" value="<?php echo $password; ?>">
   <br><br>

   <?php if(!empty($name && $password)){//ユーザー名とパスが入力されているならDBでand検索

      $login = login_search($name,$password);//DBアクセス　ユーザー検索結果
      //var_dump($login);

          if(isset($login)){//検索結果がtrueならユーザー名とIDをセッションに保存
             echo 'ログインに成功しました。';
              $_SESSION['name'] = $login[0]['name'];
              $_SESSION['userID'] = $login[0]['userID'];

              echo push_return();
          }else{
            echo '入力が正しくありません';
          }
      }?>

   <input type="submit" value="ログイン">
  </form>
   <br><br>

  <form action="registration.php" method="post">
    <input type="hidden" name="push" value="registration">
   <input type="submit" name="registration"value="新規登録">
  </form>


</body>
</html>
