<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title>mission_3-4</title>
    <style>
      form {
        margin-bottom: 20px;
      }
    </style>
  </head>
  <body>

    <?php
      $filename = "mission_3-4.txt";

     
  //投稿機能

  //ﾌｫｰﾑ内が空でない場合に以下を実行する
  if (!empty($_POST['name']) && !empty($_POST['comment'])) {

    //入力ﾃﾞｰﾀの受け取りを変数に代入
    $name = $_POST['name'];
    $comment = $_POST['comment'];

    //日付ﾃﾞｰﾀを取得して変数に代入
    $postedAt = date("Y年m月d日 H:i:s");

    // editNoがないときは新規投稿､ある場合は編集 ***ここで判断
    if (empty($_POST['editNO'])) {
      // 以下､新規投稿機能
      //ﾌｧｲﾙの存在がある場合は投稿番号+1､なかったら1を指定する
      if (file_exists($filename)) {
        $num = count(file($filename)) + 1;
      } else {
        $num = 1;
      }

      //書き込む文字列を組み合わせた変数
      $newdata = $num . "<>" . $name . "<>" . $comment . "<>" . $postedAt;

      //ﾌｧｲﾙを追記保存ﾓｰﾄﾞでｵｰﾌﾟﾝする
      $fp = fopen($filename, "a");

      //入力ﾃﾞｰﾀのﾌｧｲﾙ書き込み
      fwrite($fp, $newdata . "\n");
      fclose($fp);
    } else {
      // 以下編集機能
      //入力ﾃﾞｰﾀの受け取りを変数に代入
      $editNO = $_POST['editNO'];

      //読み込んだﾌｧｲﾙの中身を配列に格納する
      $ret_array = file($filename);

      //ﾌｧｲﾙを書き込みﾓｰﾄﾞでｵｰﾌﾟﾝ+中身を空に
      $fp = fopen($filename, "w");

      //配列の数だけﾙｰﾌﾟさせる
      foreach ($ret_array as $line) {

        //explode関数でそれぞれの値を取得
        $data = explode("<>", $line);

        //投稿番号と編集番号が一致したら
        if ($data[0] == $editNO) {

          //編集のﾌｫｰﾑから送信された値と差し替えて上書き
          fwrite($fp, $editNO . "<>" . $name . "<>" . $comment . "<>" . $postedAt . "\n");
        } else {
          //一致しなかったところはそのまま書き込む
          fwrite($fp, $line);
        }
      }
      fclose($fp);
    }
  }

      //編集選択機能

      //編集ﾌｫｰﾑの送信の有無で処理を分岐
      if (!empty($_POST['edit'])) {

          //入力ﾃﾞｰﾀの受け取りを変数に代入
          $edit = $_POST['edit'];

          //読み込んだﾌｧｲﾙの中身を配列に格納する
          $editCon = file($filename);

          //配列の数だけﾙｰﾌﾟさせる
          foreach ($editCon as $line) {

              //explode関数でそれぞれの値を取得
              $editdata = explode("<>",$line);

              //投稿番号と編集対象番号が一致したらその投稿の｢名前｣と｢ｺﾒﾝﾄ｣を取得
              if ($edit == $editdata[0]) {

                  //投稿のそれぞれの値を取得し変数に代入
                  $editnumber = $editdata[0];
                  $editname = $editdata[1];
                  $editcomment = $editdata[2];

                  //既存の投稿ﾌｫｰﾑに､上記で取得した｢名前｣と｢ｺﾒﾝﾄ｣の内容が既に入っている状態で表示させる
                  //formのvalue属性で対応
              }
            }
      }

      //編集実行機能

      //編集か新規投稿か判断
      if ((!empty($_POST['name'])) && (!empty($_POST['comment'])) && (!empty($_POST['editNO']))) {

          //入力ﾃﾞｰﾀの受け取りを変数に代入
          $editNO = $_POST['editNO'];

          //読み込んだﾌｧｲﾙの中身を配列に格納する
          $ret_array = file($filename);

          //ﾌｧｲﾙを書き込みﾓｰﾄﾞでｵｰﾌﾟﾝ+中身を空に
          $fp = fopen($filename,"w");

          //配列の数だけﾙｰﾌﾟさせる
          foreach ($ret_array as $line) {

              //explode関数でそれぞれの値を取得
              $data = explode("<>",$line);

              //投稿番号と編集番号が一致したら
              if ($data[0] == $editNO) {

                  //編集のﾌｫｰﾑから送信された値と差し替えて上書き
                  fwrite($fp,$editNO . "<>" . $name . "<>" . $comment . "<>" . $postedAt . "\n");
              } else {

                  //一致しなかったところはそのまま書き込む
                  fwrite($fp,$line);
              }
          }
          fclose($fp);
      }
    ?>

    <form action="mission_3-4.php" method="post">
      <input type="text" name="name" placeholder="名前" value="<?php if(isset($editname)) {echo $editname;} ?>"><br>
      <input type="text" name="comment" placeholder="ｺﾒﾝﾄ" value="<?php if(isset($editcomment)) {echo $editcomment;} ?>"><br>
      <input type="text" name="editNO" value="<?php if(isset($editnumber)) {echo $editnumber;} ?>">
      <input type="submit" name="submit" value="送信">
    </form>
   <form method="post" action="">
   <input type="text" name="delno" placeholder="削除対象番号">
      <input type="submit" name="delete" value="削除">
 
    </form>

    <form action="mission_3-4.php" method="post">
      <input type="text" name="edit" placeholder="編集対象番号">
      <input type="submit" value="編集">
    </form>

    <?php

  if(!empty($_POST["delno"])){//以下削除機能
        $delete = $_POST["delno"];
        $filenames = "mission_3-4.txt";//ﾌｧｲﾙ名の指定 
        $files = file($filenames);//file の読み込み
        $fp = fopen($filenames, "w");//ﾌｧｲﾙの上書き
      foreach($files as $file){
        $delDate = explode("<>",$file);
        if($delDate[0]==$delete){}
        else{
       fwrite($fp,$file);
        }
       }
       fclose($fp);
      }
      $filemei = "mission_3-4.txt";
      //表示機能

      //ﾌｧｲﾙの存在がある場合だけ行う
      if (file_exists($filemei)) {

          //読み込んだﾌｧｲﾙの中身を配列に格納する
          $array = file($filemei);

          //取得したﾌｧｲﾙﾃﾞｰﾀを全て表示する(ﾙｰﾌﾟ処理)
          foreach ($array as $word) {

                //explode関数でそれぞれの値を取得
                $getdata = explode("<>",$word);

                //取得した値を表示する
                echo $getdata[0] . " " . $getdata[1] . " " . $getdata[2] . " " . $getdata[3] . "<br>";
          }
      }
    ?>
  </body>
</html>





