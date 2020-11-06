<?php

  //共通変数・関数ファイルを読込み
  require('function.php');

  debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
  debug('「　トップページ　');
  debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
  debugLogStart();

  //ログイン認証
  require('auth.php');

?>

<?php
  $Page_Title = 'ホーム';
  require('./head/head.php');
?>

<body>
  　　
  <!--ヘッダー読み込み-->
  <?php
     require('header.php');
    ?>

  <!--メイン画像関係-->
  <main>

    <section class="hero" id="top-title">
      <div class="hero-title">Connecting people・Connecting case<br>
        <div>人々と案件を繋ぐ</div>
      </div>
    </section>

    <section id="about">
      <div class="container container-lightGray">
        <h2 class="container-title container-title-lightGray"><span>ABOUT</span></h2>
        <div class="container-body">
          <div class="text">『Connect』は数十人規模のエンジニアグループ内で単発案件やサービス立ち上げ案を投稿、その案件に手早く応募できるとてもシンプルな案件紹介アプリです。ランサーズなどの現在主流になりつつある案件紹介サービスはオプションや入力項目が色々ありすぎてわかりにくすぎるという問題があります。しかしこちらのアプリは基本的な機能のみに絞り込む事で主婦の方々でも投稿できるくらいの手軽さを実現しました。</div>
        </div>
      </div>
    </section>

    <section class="bgColor" id="latest case">
      <div class="container container-lightGray">
        <h2 class="container-title">

          <span>CONTACT</span></h2>
        <div class="container-body">
          <form action="" class="form form-m">
            <input class="input input-l" type="text" placeholder="お名前">
            <input class="input input-l" type="email" placeholder="email">
            <textarea class="input input-l input-textarea mb-xxl" placeholder="お問い合わせ内容"></textarea>

            <button class="btn btn-corp btn-l">送信</button>
          </form>
        </div>
      </div>
    </section>

  </main>

  <?php
     require('footer.php');
    ?>

</body>

</html>
