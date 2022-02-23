<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>php</title>
</head>
<body>
    <!---------------------- 投稿するときの入力フォーム　開始 ---------------------->

    <?php if(empty($_POST["edit_num"])): 
        // 投稿フォームの値(名前、コメント)を空にする
        $name_value = "";
        $comment_value = "";
        // 編集する番号の値を空にする
        $edit_value = "";
    ?>
    <form action="" method="post">
        <input type="text" name="name" value="<?php echo $name_value ?>" placeholder="名前"><br>
        <input type="text" name="comment" value="<?php echo $comment_value ?>" placeholder="コメント">
        <input type="hidden" name="edit" value="<?php echo $edit_value ?>">
        <input type="submit" name="submit"><br><br>
        <input type="text" name="delete" placeholder="削除する番号">
        <input type="submit" name="submit" value="削除"><br><br>
        <input type="text" name="edit_num" placeholder="編集する番号">
        <input type="submit" name="submit" value="編集">
    </form>
    <?php endif; ?>

    <!---------------------- 投稿するときの入力フォーム　終了 ---------------------->



    <?php
    // ----------------------------------- 投稿　開始 -----------------------------------
    $filename = "index.txt";
    // ファイルの中身を配列に格納
    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if(empty($lines)){
        // 配列が空のとき$idは1
        $id = 1;
    }else{
        // 空でないとき$idは最大値に1を足した値
        $id = (int)max(file($filename)) + 1;
    }
    // 名前とコメントが空でないことを確認
    if(!empty($_POST["name"])  && !empty($_POST["comment"])){
        // 送信された名前を$nameに格納する
        $name = $_POST["name"];
        // 送信されたコメントを$commentに格納する
        $comment = $_POST["comment"];
        // 日付を取得して$dateに格納する
        $date = date("Y/m/d/ H:i:s");
        // 変数を組み合わせて$strに格納する
        $str = "$id<>$name<>$comment<>$date";
        // ファイルを追記モードで開ける
        $fp = fopen($filename,"a");
        // ファイルに$strを書き込む
        fwrite($fp,$str.PHP_EOL);
        // ファイルを閉じる
        fclose($fp);
        echo "書き込みました<br>";
    }

    // ----------------------------------- 投稿　終了 -----------------------------------



    // ----------------------------------- 編集　開始 -----------------------------------

    // <送信された番号の投稿内容をフォームに表示>
    if(!empty($_POST["edit_num"])){
        $edit = $_POST["edit_num"];
        // ファイルの中身を配列として$linesに格納
        $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach($lines as $line){
            // $lineの内容を<>で分割してそれぞれの値を$elineに格納
            $eline = explode("<>",$line);
             // 配列$elineの内容を一つづつ表示
            // 投稿番号と送信された$editが等しければ
            if($eline[0] == $edit){
                // フォームに編集する内容を表示させるためvalueに値を格納
                $edit_value = $eline[0];
                $name_value = $eline[1];
                $comment_value = $eline[2];
            }
        }
    }
    ?>
    <!---------------------- 編集するときの入力フォーム　開始 ---------------------->

    <!-- 編集番号が表示されたときに表示する -->
    <?php if(!empty($_POST["edit_num"])): ?>
            <form action="" method="post">
            <input type="text" name="edit_name" value="<?php echo $name_value ?>" placeholder="名前"><br>
            <input type="text" name="edit_comment" value="<?php echo $comment_value ?>" placeholder="コメント">
            <input type="hidden" name="edit" value="<?php echo $edit_value ?>">
            <input type="submit" name="submit"><br><br>
            <input type="text" name="delete" placeholder="削除する番号">
            <input type="submit" name="submit" value="削除"><br><br>
            <input type="text" name="edit_num" placeholder="編集する番号">
            <input type="submit" name="submit" value="編集">
            </form>
    <?php endif; ?>
    
    <!---------------------- 編集するときの入力フォーム　終了 ---------------------->


    
    <!-- <編集後、送信された番号の投稿を上書き保存> -->
    <?php
     if(!empty($_POST["edit_name"]) && !empty($_POST["edit_comment"])){
        $edit_num = $_POST["edit"];
        $edit_name = $_POST["edit_name"];
        $edit_comment = $_POST["edit_comment"];
        $date = date("Y/m/d/ H:i:s");
        $filename = "index.txt";
        // ファイルの中身を配列として$linesに格納
        $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        // テキストファイルの中身を空にする
        file_put_contents($filename,'');
        foreach($lines as $line){
            // $lineの内容を<>で分割してそれぞれの値を$elineに格納
            $eline = explode("<>",$line);
            // 配列$elineの内容を一つづつ表示
            // 投稿番号と送信された$editが等しければ
            if($eline[0] == $edit_num){
                $line = "$edit_num<>$edit_name<>$edit_comment<>$date";
                $fp = fopen($filename,"a");
                // ファイルに$strを書き込む
                fwrite($fp,$line.PHP_EOL);
                // ファイルを閉じる
                fclose($fp);
            }else{
                $fp = fopen($filename,"a");
                // ファイルに$strを書き込む
                fwrite($fp,$line.PHP_EOL);
                // ファイルを閉じる
                fclose($fp);
            }
         }
    }

    // ----------------------------------- 編集　終了 ------------------------------------ 


    // ----------------------------------- 削除　開始 ------------------------------------ 

    if(!empty($_POST["delete"])){
        $delete = $_POST["delete"];
         // ファイルの中身を配列として$linesに格納
         $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
         // テキストファイルの中身を空にする
         file_put_contents($filename,'');
         foreach($lines as $line){
            //  $lineの内容を<>で分割してそれぞれの値を$配列としてelineに格納
            $eline = explode("<>",$line);
            // 投稿番号がdeleteと等しくなければファイルに書き込む
            if($eline[0] != $delete){
                $fp = fopen($filename,"a");
                fwrite($fp,$line.PHP_EOL);
                fclose($fp);
            }
         }
    }

    // ----------------------------------- 削除　開始 ------------------------------------ 


    // ----------------------------------- 投稿内容表示　開始 ------------------------------------ 

    if(file_exists($filename)){
        // 配列$linesにファイルの中身を格納する
        $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        // 配列$linesの中身を一つずつ$lineとして取り出す
        foreach($lines as $line){
            // $lineの内容を<>で分割してそれぞれの値を$elineに格納
            $eline = explode("<>",$line);
            // 配列$elineの内容を一つづつ表示
            for($i=0;$i<4;$i++){
                echo $eline[$i]." ";
            }
            echo "<br>";
        }
    }
    // ----------------------------------- 投稿内容表示　終了 ------------------------------------ 


    ?>

</body>
</html>