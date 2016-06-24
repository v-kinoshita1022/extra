<?php
/** @mainpage
*  商品検索フォームを表示
*/

/**
*@file
* @brief 商品検索フォームを表示
*
* 商品検索フォームを表示し、
* フォームから入力された値を条件に、検索APIを利用して、検索した結果をhtmlに埋め込んで表示します。
* 検索結果に対して、カテゴリーによる絞り込みと、並び順の変更ができます。
*
* PHP version 5
*/

require_once("../common/common.php");//共通ファイル読み込み(使用する前に、appidを指定してください。)
require_once("../util/scriptUtil.php");
require_once("../util/defineUtil.php");
session_start();
$login_now = isset($_SESSION['userID']) ? $_SESSION['userID'] : null;
$user_name = isset($_SESSION['name']) ? $_SESSION['name'] : null;

//if(!isset($login_now)){
  //$login_now = null;
//}

$hits = array();
$query = !empty($_GET["query"]) ? $_GET["query"] : ""; //$query=検索ワード
//↓空でなく、$sortOrderに$  _GET["sort"]が含まれるなら
$sort =  !empty($_GET["sort"]) && array_key_exists($_GET["sort"], $sortOrder) ? $_GET["sort"] : "-score";
//↓$_GET["category_id"]が数字で、$categoriesに$_GET["category_id"]
$category_id = isset($_GET["category_id"]);
$category_id = ctype_digit($category_id) && array_key_exists($category_id, $categories) ? $category_id : 1;

if ($query != "") {//検索ワードが入っているなら
  $query4url = rawurlencode($query);//検索ワード rawurlencode＝指定に基づいてエンコする
  $sort4url = rawurlencode($sort);//ソート
  $url = "http://shopping.yahooapis.jp/ShoppingWebService/V1/itemSearch?appid=$appid&query=$query4url&category_id=$category_id&sort=$sort4url";
  $xml = simplexml_load_file($url);
  if ($xml["totalResultsReturned"] != 0) {//検索件数が0件でない場合,変数$hitsに検索結果を格納します。
    $hits = $xml->Result->Hit;
  }
}
?>
<html>
<head>
  <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
  <title>商品検索</title>
  <link rel="stylesheet" type="text/css" href="../css/prototype.css"/>
</head>
<body>
  <div id="header">
    <h1><a href="top.php">かごゆめ</a></h1>
  </div>
  <form action="./search.php" class="Search">
    表示順序:
    <select name="sort">
      <?php foreach ($sortOrder as $key => $value) { ?>
        <option value="<?php echo h($key); ?>" <?php if($sort == $key) echo "selected=\"selected\""; ?>><?php echo h($value);?></option>
        <?php } ?>
      </select>

      キーワード検索：
      <select name="category_id">
        <?php foreach ($categories as $id => $name) { ?>
          <option value="<?php echo h($id); ?>" <?php if($category_id == $id) echo "selected=\"selected\""; ?>><?php echo h($name);?></option>
          <?php } ?>
        </select>
        <input type="text" name="query" value="<?php echo h($query); ?>"/>
        <input type="submit" value="検索"/>
      </form>

      <?php if($query == null){echo  '<font color = "red";> ※検索ワードを入力してください<br><br></font>';
        echo '主な機能<br>';
        echo '商品検索、カートに追加、購入の購買機能<br>';
        echo 'ユーザー登録、購入履歴一覧等アカウト機能<br>';}?>


        <?php $i = 1;?>
        <?php foreach ($hits as $hit) { ;?>
          <div class="Item">
            <p><h1><?php echo $i++.'位'; ?></h1>
              <h2><a href="<?php echo ITEM; ?>?Code=<?php echo h($hit->Code);?>"><?php echo h($hit->Name); ?></a></h2>
              <a href="<?php echo ITEM;?>?Code=<?php echo h($hit->Code);?>"><img src="<?php echo h($hit->Image->Medium);?>" /></a>
              <?php echo h($hit->Price).'円';   ?></p>
            </div>
          </form>
          <?php }
          ?>

          <br><br><br><br><br><br><br>
          <?php var_dump($user_name);echo login_now($login_now); ?>
          <div>
            <a href="#header">トップへ戻る</a>
          </div>

        </body>
        </html>
