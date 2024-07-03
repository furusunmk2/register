<?php
////////////////////
//あと必要なこと
//開発スケジュール&変更履歴の編集・発表準備
////////////////////
$disp_num = isset($_POST["display"]) ? $_POST["display"] : "0"; // 表示される数字、デフォルトは０
$pre_num = isset($_POST["pre_num"]) ? $_POST["pre_num"] : "";// エスケープした前の数字
$operator = isset($_POST["operator"]) ? $_POST["operator"] : ""; // 現在の演算子
$ope=isset($_POST["ope"]) ? $_POST["ope"] : "";// エスケープした前の演算子
$disp_tmp = isset($_POST["display2"]) ? $_POST["display2"] : "";
$key_input = isset($_POST["key_input"]) ? $_POST["key_input"] : "";

// キーボード入力の処理
if ($key_input !== "") {
  if (is_numeric($key_input)) {
      $_POST["num_add"] = $key_input;
  //} elseif (in_array($key_input, ["+", "×"])) {
      //$_POST["operator"] = $key_input;
  } elseif ($key_input === "+") {
      $_POST["operator"] = "+";
      $operator ="+";
  } elseif ($key_input === "×") {
      $_POST["operator"] = "×";
      $operator ="×";
  } elseif ($key_input === "=") {
      $_POST["operator"] = "=";
      $operator ="=";
  } elseif ($key_input === "AC") {
      $_POST["allclear"] = "AC";
  } elseif ($key_input === "uriage") {
      $_POST["uriage"] = "uriage"; 
  } elseif ($key_input === "keijo") {
      $_POST["keijo"] = "keijo"; 
  } elseif ($key_input === "tax") {
      $_POST["tax"] = "tax"; 
  }
}

// 数字
if(isset($_POST["num_add"])) {
    if($ope == "="){ //計算後に数字を押すと自動でACする。
      $disp_num = "";
      $ope = "";
      $pre_num = "";
      $disp_tmp = "";
    }
    if (strlen($disp_num) < 8){
      $disp_num .= $_POST["num_add"];
    }
    $disp_num *= 1;    // 頭文字の０を消すための処理
    $disp_tmp = preg_replace('/^0([0-9]*)/', '$1', $disp_tmp);
    $disp_tmp = preg_replace('/\+0([0-9]*)\z/', '+$1', $disp_tmp);
    $disp_tmp = preg_replace('/×0([0-9]*)\z/', '×$1', $disp_tmp);
}


if(isset($_POST["num_add"])) {

  if (preg_match('/\A[0-9]{2}$/',$disp_tmp)) 
    $disp_tmp *= 1;
  if(!(preg_match('/[0-9]{8}$/',$disp_tmp))){
    $disp_tmp .= $_POST["num_add"];
  }

}
if(isset($_POST["operator"])){ 
  if(!(preg_match('/=/', $operator))){
    if (preg_match('/×$/',$disp_tmp)){
      $disp_tmp = substr($disp_tmp,0,-2);
      
    }
    if (preg_match('/\+$/',$disp_tmp)){
      $disp_tmp = substr($disp_tmp,0,-1);

    }
    if (preg_match('/=$/',$disp_tmp)){
      $disp_tmp = substr($disp_tmp,0,-1);

    }}
  else{
    if (preg_match('/\+|×$/',$disp_tmp)){
      $_POST["operator"]="";
    }
  }
  $disp_tmp .= $_POST["operator"];
  $disp_tmp = preg_replace('/^\+|^×|^=/', '', $disp_tmp);
}


if(isset($_POST["operator"])){   //現在の演算子が＝の場合
    if (preg_match('/=/', $operator)) {
        if ($disp_num=="0" or $pre_num=="" or $ope==""){
          $disp_tmp = substr($disp_tmp,0,-1);
        }
        elseif($ope == "="){ //計算後に数字を押すと自動でACする。
          $disp_num = "";
          $ope = "";
          $pre_num = "";
          $disp_tmp = "";
        }
        else{
          if(!(preg_match('/\+\z|×\z/',$disp_tmp))){
            if (preg_match('/\+/',$ope)){ //前の演算子が＋の場合
              $disp_num = $pre_num + $disp_num;
              $ope = $operator;
              $disp_tmp.= "=";
            }
            if (preg_match('/×/',$ope)){ //前の演算子が×の場合
              $disp_num = $pre_num * $disp_num;
              $ope = $operator;
              
          }
          

          }
        $disp_tmp.= "=";
        $disp_tmp = preg_replace('/==\z/', '=', $disp_tmp);
        $disp_tmp = preg_replace('/\+=\z/', '+', $disp_tmp);
        $disp_tmp = preg_replace('/×=\z/', '×', $disp_tmp);
        }
    }
    if (preg_match('/×|\+/', $operator)){  //現在の演算子が+、×の場合
        if ($disp_num =="0" && $pre_num ==""){  //数字の入力がない場合は反応しない
          $ope ="";
          $disp_tmp = "";
        }

        else{
          if(preg_match('/×|\+/', $ope)){ 
            if (preg_match('/\+/',$ope)){   //前の演算子が＋
              if (preg_match('/^0$|^-?[1-9][0-9]*$/',$disp_num)){   //複数回演算子が押された場合演算子の変更をする処理
                $disp_num = $pre_num + $disp_num;
                $pre_num = $disp_num;

              }
            $disp_num = "";
            $ope = $operator;
              if (preg_match('/×/', $operator)){
                $disp_tmp = preg_replace('/^([ -~×]{1,})\+([0-9]{1,8})\z/', '($1+$2)', $disp_tmp);

                $disp_tmp = preg_replace('/^([ -~×]{1,})\+([0-9]{1,8})×/', '($1+$2)×', $disp_tmp);
                $disp_tmp = preg_replace('/(\(\([ -~×]{1,})\+([0-9]{1,8})\)\)/', '$1+$2)', $disp_tmp);
                $disp_tmp = preg_replace('/^\(\(/', '(', $disp_tmp);
                if (mb_substr_count($disp_tmp,")")>1){
                  $disp_tmp = "(".$disp_tmp;}
              }


            }
            if (preg_match('/×/',$ope)){   //前の演算子が×
              if (preg_match('/^0$|^-?[1-9][0-9]*$/',$disp_num)){   //複数回演算子が押された場合演算子の変更をする処理
                $disp_num = $pre_num * $disp_num;
                $pre_num = $disp_num;
              }

            $disp_num = "";
            $ope = $operator;
            }
          }
          else{
            $pre_num = $disp_num;
            $disp_num ="";
            $ope = $operator;
          }
        }
    }
}

// AC
if(isset($_POST["allclear"])) {
  $disp_num = "0";
  $ope = "";
  $pre_num = "";
  $disp_tmp = "";
}

// TAX 
if(isset($_POST["tax"])) {
  if($ope == "="){ //=を押した直後はdisp_numで計算
    $disp_num = round($disp_num*1.1);//四捨五入
    $disp_tmp= $disp_num;
  }
  elseif (preg_match('/\+/',$ope)){ //前の演算子が＋の場合
    if (!($disp_num=="")) {//計算途中の場合反応しない
      $disp_num = $pre_num + $disp_num;
      $disp_num = round($disp_num*1.1);
      $disp_tmp= $disp_num;
    }
    
  }
  elseif (preg_match('/×/',$ope)){ //前の演算子が×の場合
    if (!($disp_num == "")) {//計算途中の場合反応しない
      $disp_num = $pre_num * $disp_num;
      $disp_num = round($disp_num*1.1);
      $disp_tmp= $disp_num;
    }
  }
  else{
    $disp_num = round($disp_num*1.1);
    $disp_tmp= $disp_num;
  }
}

// データベースに接続
const DB_CONNECTION = 'mysql';
const DB_HOST = '127.0.0.1';
const DB_PORT = '3306';
const DB_USERNAME = 'root';
const DB_PASSWORD = '';
const DB_DATABASE = '売上';

$db_connection = DB_CONNECTION;
$db_name = DB_DATABASE;
$db_host = DB_HOST;
$db_port = DB_PORT;

$dsn = "{$db_connection}:dbname={$db_name};host={$db_host};port={$db_port};charset=utf8;";
$db_user = DB_USERNAME;
$db_password = DB_PASSWORD;

try {
  $pdo = new PDO($dsn, $db_user, $db_password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
  echo "接続失敗: " . $e->getMessage();
  exit;
}

// 計上ボタンを押したら disp_numをデータベースに出力
if(isset($_POST["keijo"])){

  if (preg_match('/\+|×/',$disp_tmp)) {
    if (preg_match('/=/',$disp_tmp)) {
      $sql = "INSERT INTO `売上` (`計上日時`, `計上額`) VALUES (current_timestamp(), '{$disp_num}')";
      $stmt = $pdo->query($sql);
      $disp_num = "0";
      $ope = "";
      $pre_num = "";
      $disp_tmp = "";
    }
  }
  else{
    $sql = "INSERT INTO `売上` (`計上日時`, `計上額`) VALUES (current_timestamp(), '{$disp_num}')";
    $stmt = $pdo->query($sql);
    $disp_num = "0";
    $ope = "";
    $pre_num = "";
    $disp_tmp = "";
  }

}
////////////////////////////////////////////////////////////

// 売上ボタンを押したら(`計上額`)の合計を取得してdisp_numを上書き
if(isset($_POST["uriage"])){
  if ($disp_num =="0" && $pre_num ==""){
    $sql = "SELECT SUM(`計上額`) AS total_sum FROM `売上`";
    $stmt = $pdo->query($sql);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $sum = $result['total_sum'];
    $disp_tmp = "";
    if($sum == null){
      $disp_num = 0;
    }else{
      $disp_num = $sum;
    }
  }
}
// //////////////////////////////////////////////////////////////

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>電卓</title>
    <link rel="stylesheet" href="calculator.css">
    <script>
        document.addEventListener('keydown', function(event) {
            let key = event.key;
            let validKeys = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', ';', ':', '=', 'Enter', 'Backspace','-','^','@','['];
            if (validKeys.includes(key)) {
                let form = document.forms['register'];
                let inputField = document.createElement('input');
                inputField.type = 'hidden';
                if (key === 'Enter') key = '=';
                if (key === 'Backspace') key = 'AC';
                if (key === ';') key = '+';
                if (key === ':') key = '×';
                if (key === '-') key = 'uriage';
                if (key === '^') key = 'keijo';
                if (key === '@') key = 'tax';
                if (key === '[') {
                    window.location.href = 'login.php';
                    return;
                }
                inputField.name = 'key_input';
                inputField.value = key;
                form.appendChild(inputField);
                form.submit();
            }
        });
    </script>
</head>
<body>
    <form name="register" method="post" > 
        <table width="200" height="100">
          <!-- 画面 -->
          <tr>
            <td colspan="4">
              <!-- 値を直接表示 -->
              <input type="text" class="display" name="display2" value="<?php echo $disp_tmp; ?>" disabled>
              <input type="text" class="display" name="display" value="<?php echo $disp_num; ?>" disabled>
              <!-- 隠しフィールドで前の値を保持 -->
              <input type="hidden" name="display2" value="<?php echo $disp_tmp; ?>">
              <input type="hidden" name="display" value="<?php echo $disp_num; ?>">
              <input type="hidden" name="pre_num" value="<?php echo $pre_num; ?>" >
              <input type="hidden" name="ope" value="<?php echo $ope; ?>" >
            </td>
          </tr>
          <!-- ボタン類 -->
          <tr>
            <td colspan="2"><input class="1st" type="submit" value="AC" name="allclear"></td>
            <td colspan="2"><input class="1st" type="submit" value="税込み" name="tax"<?php if(preg_match('/\+\z|×\z/',$disp_tmp) or ($disp_num =="0")):?>
               id="lowlight" <?php else :?> id="normal" <?php endif; ?>></td>
          </tr>
          <tr>
            <!-- ボタンの値は直接表示 -->
            <td><input type="submit" value="7" name="num_add"></td>
            <td><input type="submit" value="8" name="num_add"></td>
            <td><input type="submit" value="9" name="num_add"></td>
            <td><input type="submit" value="×" name="operator"<?php if(preg_match('/×/',$ope) && isset($_POST["operator"])) : ?>
               id="highlight" <?php elseif ($disp_num==0 && $pre_num==0):?> id="lowlight" <?php else :?> id="normal" <?php endif; ?>></td>
          </tr>
          <!-- 2段目 -->
          <tr>
            <td><input type="submit" value="4" name="num_add"></td>
            <td><input type="submit" value="5" name="num_add"></td>
            <td><input type="submit" value="6" name="num_add"></td>
            <td><input type="submit" value="+" name="operator"<?php if(preg_match('/\+/',$ope) && isset($_POST["operator"])) : ?>
               id="highlight" <?php elseif ($disp_num==0 && $pre_num==0):?> id="lowlight" <?php else :?> id="normal" <?php endif; ?>></td>
          </tr>
          <!-- 3段目 -->
          <tr>
            <td><input type="submit" value="1" name="num_add"></td>
            <td><input type="submit" value="2" name="num_add"></td>
            <td><input type="submit" value="3" name="num_add"></td>
            <td rowspan="2" style="height: 100%;"><input type="submit" value="=" name="operator"<?php if(!(preg_match('/\+[0-9]{1,8}\z|×[0-9]{1,8}\z|=\z/',$disp_tmp))):?>
               id="lowlight" <?php else :?> id="normal" <?php endif; ?>></td>
          </tr>
          <!-- 4段目 -->
          <tr>
            <td><input type="submit" value="0" name="num_add"></td>
            <td><input type="submit" value="売上" name="uriage"<?php if(!($disp_num =="0")):?>
               id="lowlight" <?php else :?> id="normal" <?php endif; ?>></td>
            <td><input type="submit" value="計上" name="keijo"<?php if(preg_match('/\+\z|×\z|\+[0-9]{1,8}\z|×[0-9]{1,8}\z/',$disp_tmp) or ($disp_num =="0")):?>
               id="lowlight" <?php else :?> id="normal" <?php endif; ?>></td>
          </tr>
          <tr>
            <td colspan="4"><button type="button" onclick="location.href='login.php'">DataBase</button></td>
          </tr>
        </table>
    </form>
</body>
</html>