<?php

//共通変数・関数ファイルを読込み $dbFormData
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　案件一覧ページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

// 画面処理
// 画面表示用データ取得

//========
// GETパラメータを取得
//--------

// ページ関係の変数
$currentPageNum = (!empty($_GET['p'])) ? $_GET['p'] : 1; //デフォルトは１ページめ

// カテゴリー
$category = (!empty($_GET['c_id'])) ? $_GET['c_id'] : '';

// ソート順
$sort = (!empty($_GET['sort'])) ? $_GET['sort'] : '';

// パラメータに不正な値が入っているかチェック(is_intは変数の内容が整数型かどうかを調べるもの)
if(!is_int($currentPageNum)){
  error_log('エラー発生:指定ページに不正な値が入りました');
  header("Location:index.php"); //トップページへ
}

// 表示件数
$listSpan = 20;

// 現在の表示レコードを算出
$currentMinNum = (($currentPageNum-1)*$listSpan); //1ページ目なら(1-1)*20 = 0 、 ２ページ目なら(2-1)*20 = 20

// DBから案件データを取得
$dbProductData = getProductList($currentMinNum, $category, $sort);

// DBからカテゴリデータを取得
$dbCategoryData = getCategory();

debug('DBデータ：'.print_r($dbProductData,true));
debug('カテゴリデータ：'.print_r($dbCategoryData,true));

debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>

<?php
$Page_Title = 'CaseList';
require('./head/head-caselist.php');
?>

<body class="page-home page-2colum">

  <!-- ヘッダー -->
  <?php
      require('header.php');
    ?>

  <!-- メインコンテンツ -->
  <div id="contents" class="site-width">

    <!-- サイドバー -->
    <section id="sidebar">
      <form name="" method="get">
        <h1 class="title">カテゴリー</h1>
        <div class="selectbox">
          <span class="icn_select"></span>
          <select name="c_id" id="">

            <!--DB内に予め登録しておいたカテゴリ等を文字列として出力する処理-->

            <option value="0" <?php if(getFormData('c_id',true) == 0 ){ echo 'selected'; } ?>>選択してください</option>


            <?php
                foreach($dbCategoryData as $key => $val){
              ?>

            <option value="<?php echo $val['id'] ?>" <?php if(getFormData('c_id',true) == $val['id'] ){ echo 'selected'; } ?>>
              <?php echo $val['name']; ?>
            </option>


            <?php
                }
              ?>
          </select>
        </div>


        <h1 class="title">表示順</h1>
        <div class="selectbox">
          <span class="icn_select"></span>
          <select name="sort">


            <option value="0" <?php if(getFormData('sort',true) == 0 ){ echo 'selected'; } ?>>選択してください</option>


            <option value="1" <?php if(getFormData('sort',true) == 1 ){ echo 'selected'; } ?>>金額が安い順</option>


            <option value="2" <?php if(getFormData('sort',true) == 2 ){ echo 'selected'; } ?>>金額が高い順</option>


          </select>
        </div>


        <input type="submit" value="検索">
      </form>

    </section>



    <!-- Main -->

    <section id="main">

      <div class="search-title">

       <!--案件数表示処理-->
        <div class="search-left">
          <span class="total-num"><?php echo sanitize($dbProductData['total']); ?></span>件の案件が見つかりました
        </div>

        <div class="search-right">
          <span class="num">
          <?php echo (!empty($dbProductData['data'])) ? $currentMinNum+1 : 0; ?>
          </span> - <span class="num">
          <?php echo $currentMinNum+count($dbProductData['data']); ?></span>件 / <span class="num"><?php echo sanitize($dbProductData['total']); ?></span>件中
        </div>

      </div>


      <!--案件表示 メイン部分-->

      <div class="panel-list">

        <!--キー値を持たせる処理-->
        <?php
            foreach($dbProductData['data'] as $key => $val):
          ?>

     <!--案件ごとの詳細ページに移動する為にクエリパラメータを作成してhrefに貼り付ける処理-->

        <a href="caseDetail.php
        <?php echo (!empty(appendGetParam())) ?
        //true(上のforeach文で引っ張ってきた商品データをgetメソッドの形式で渡す処理)
        appendGetParam().'&p_id='.$val['id']:
        //false
        '?p_id='.$val['id']; ?>"
        class="panel">




        <!--画像があるかどうかを確認する処理。無い場合はサンプル画像を出力する(showImg参照)-->
        <!--altは出力する画像情報を伝える処理-->
        <!--sanitize関数は何かしらの手段を用いて入力されたコード等無力化する処理-->

          <div class="panel-head">

            <img src="<?php echo showImg(sanitize($val['pic1'])); ?>"
             alt="<?php echo sanitize($val['name']); ?>
             ">

          </div>

        <!--値段と名前を出力する処理-->
          <div class="panel-body">
            <p class="panel-title">
            <?php echo sanitize($val['name']); ?>

            <span class="price">¥<?php echo sanitize(number_format($val['price'])); ?></span>
            </p>
          </div>
        </a>

        <?php
            endforeach;
          ?>
      </div>

      <?php pagination($currentPageNum, $dbProductData['total_page']); ?>

    </section>

  </div>

  <?php
     require('footer.php');
    ?>

  <script src="./js/app.js"></script>
</body>

</html>
