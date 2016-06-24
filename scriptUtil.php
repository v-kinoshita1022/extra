<?php
//共通処理
//topに戻る

require_once('../util/defineUtil.php');
require_once("../common/common.php");//共通ファイル読み込み


function return_top(){//トップページへ移動
    return "<a href='".ROOT_URL."'>トップページへ戻る</a>";
}

function return_seach(){//商品検索へ移動
    return "<a href='".SEARCH."'>買い物を始める</a>";
}

function item(){//使いません
    return "<a href='".ITEM."'>";
}

function goto_cart(){//カート
    return "<a href='".CART."'>カート</a>";
}


function cart(){
  $i=0;
  global $appid;
  $url = "http://shopping.yahooapis.jp/ShoppingWebService/V1/itemLookup?appid=".$appid."&itemcode=".$_SESSION['goods'][$i++]['code']."&responsegroup=medium";
  $xml = simplexml_load_file($url);
  if ($xml["totalResultsReturned"] != 0) {//検索件数が0件でない場合,変数$hitsに検索結果を格納します。

       $hit = $xml->Result->Hit;
     }
}

function bind_p2s($name){//存在チェック＆代入
if(!empty($_POST[$name])){
        $_SESSION[$name] = $_POST[$name];
        return $_POST[$name];
    }else{
        $_SESSION[$name] = null;
        return null;
    }
  }


  /**
   * フォームの再入力時に、すでにセッションに対応した値があるときはその値を返却する
   * @param type $name formのname属性
   * @return type セッションに入力されていた値
   */
  function form_value($name){
      if(isset($_POST['push']) && $_POST['push']=='REINPUT'){
          if(isset($_SESSION[$name])){
              return $_SESSION[$name];
          }
      }
  }
function login(){//ログインボタン
  //現在のurlを保存する
  $redirectUrl = 'http://'.($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
  return "
    <form action='login.php' method='post'>
    <input type='submit' name='login' value='ログイン'>
    <input type='hidden' name='redirect' value=".$redirectUrl.">
    </from>";
  }

function push_return($redirectUrl = ''){//ログイン成功で元のページに戻るリンク
    if (!$redirectUrl) {
      return;
    }

    return "<a href=".$redirectUrl.">戻る</a>";
}

function login_2($redirectUrl){
    $redirectUrl = $_POST['redirect']?$_POST['redirect']:'';
    header("Location: {$redirect}");
exit;
}

function login_now($login_now = null){//ログイン状態によって表示を変える
  if (!$login_now){
    return login();
  }
 global $user_name;
  return logout().account($user_name).goto_cart();
}

function logout(){
  return "<form action='top.php'>
          <input type='submit' name='logout' value='ログアウト'>
          <input type='hidden' name='session_destroy' value=".session_destroy().">
          </form>";
      }

function account($name){
  return "ようこそ<a href='".MYDATA."'>".$name."</a>さん！";
}
