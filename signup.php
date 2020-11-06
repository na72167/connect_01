<?php

  //関数関係のファイルを纏めたもの
  require('function.php');

  debug('「「「「「「「「「「「「「「「「「「「');
  debug('アカウント作成ページ');
  debug('「「「「「「「「「「「「「');
  debugLogStart();

  //post送信された後の処理
  if(!empty($_POST)){

    //変数にユーザー情報を代入
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $pass_re = $_POST['pass_re'];

    //必須項目チェック
    validRequired($email, 'email');
    validRequired($pass, 'pass');
    validRequired($pass_re, 'pass_re');

    if(empty($err_ms)){

      //emailの形式チェック
      validEmail($email, 'email');
      //最大文字数チェック
      validMaxLen($email, 'email');
      //重複チェック
      validEmailDup($email);

      //パスワードの半角英数字チェック
      validHalf($pass,'pass');
      //最大文字数チェック
      validMaxLen($pass,'pass');
      //最小文字数チェック
      validMinLen($pass,'pass');


      //パスワード（再入力）の最大文字数チェック
      validMaxLen($pass_re,'pass_re');
      //最小文字数チェック
      validMinLen($pass_re,'pass_re');

      if(empty($err_ms)){
        //パスワードとパスワード再入力が合っているかチェック
        validMatch($pass, $pass_re, 'pass_re');

        if(empty($err_ms)){
          //例外処理
          try {
            // DBへ接続
            $dbh = dbConnect();
            // SQL文作成
            $sql = 'INSERT INTO users (email,password,login_time,create_date) VALUES(:email,:pass,:login_time,:create_date)';
            $data = array(':email' => $email, ':pass' => password_hash($pass, PASSWORD_DEFAULT),
                          ':login_time' => date('Y-m-d H:i:s'),
                          ':create_date' => date('Y-m-d H:i:s'));
            // クエリ実行
            $stmt = queryPost($dbh, $sql, $data);

            // クエリ成功の場合
            if($stmt){

            //ログイン有効期限（デフォルトを１時間とする）
            $sesLimit = 60*60;

            // 最終ログイン日時を現在日時に
            $_SESSION['login_date'] = time();
            $_SESSION['login_limit'] = $sesLimit;

            // ユーザーIDを格納(最後に追加されたIDを追加する。)
            $_SESSION['user_id'] = $dbh->lastInsertId();

            debug('セッション変数の中：'.print_r($_SESSION,true));

            header("Location:mypage.php"); //マイページへ
           }
          } catch (Exception $e) {
            error_log('エラー発生:' . $e->getMessage());
            $err_msg['common'] = ERROR_MS_07;
          }
        }
      }
    }
  }
?>

<?php
  $Page_Title = 'アカウント作成';
  require('./head/head-signup.php');
?>

<body>

  <?php
    require('header.php');
    ?>

  <div id="contents" class="site-position">

    <section id="main">

      <div class="form-container">



        <form action="" method="post" class="form">
          <h2 class="title">会員登録</h2>

          <!--フォームの色変更-->
          <div class="area-msg">

            <!--例外処理発生時に出力されるメッセージを出す処理-->
            <?php if(!empty($err_ms['common'])) echo $err_ms['common'];?>

            <!--email関係の処理-->

            <!--エラー発生時色変え用classを追加する処理-->

            <label class="<?php if(!empty($err_ms['email'])) echo 'err'; ?>">
              Email

              <!--value内の処理は何かしらのエラーが出て会員登録画面に遷移した時,前に打ち込んだ文を出力する処理-->

              <input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo $_POST['email']; ?>">

            </label>


            <!--email関係のエラーメッセージ出力処理-->
            <div class="area-ms">
              <?php
              if(!empty($err_ms['email'])) echo $err_ms['email'];
              ?>
            </div>


            <!--エラー発生時色変え用classを追加する処理(passフォーム用)-->
            <label class="<?php if(!empty($err_ms['pass'])) echo 'err'; ?>">

              パスワード <span style="font-size:12px">※英数字６文字以上</span>

              <!--前文保存処理-->
              <input type="password" name="pass" value="<?php if(!empty($_POST['pass'])) echo $_POST['pass']; ?>">
            </label>

            <!--エラーメッセージ出力処理-->
            <div class="area-msg">
              <?php
              if(!empty($err_ms['pass'])) echo $err_ms['pass'];
              ?>
            </div>

            <!--エラー発生時色変え用classを追加する処理(passフォーム用)-->
            <label class="<?php if(!empty($err_ms['pass_re'])) echo 'err'; ?>">

              パスワード（再入力）

              <!--入力情報保存処理-->
              <input type="password" name="pass_re" value="<?php if(!empty($_POST['pass_re'])) echo $_POST['pass_re']; ?>">
            </label>

            <!--エラー出力処理-->
            <div class="area-msg">
              <?php
              if(!empty($err_ms['pass_re'])) echo $err_ms['pass_re'];
              ?>
            </div>

            <div class="btn-container">
              <input type="submit" class="btn btn-mid" value="登録する">
            </div>

          </div>
        </form>
      </div>

    </section>

  </div>

  <?php
    require('footer.php');
    ?>

</body>

</html>
