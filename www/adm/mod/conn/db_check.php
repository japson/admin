<?
session_start();
$lognam=$_POST['lognam'];
$logpass=$_POST['logpass'];
$remember=$_POST['remember'];
$lognam= htmlspecialchars($lognam);
$logpass= htmlspecialchars($logpass);
$mdPassword = md5($logpass);
require_once('../debug.php');
if(strlen($captcha)){exit();}
require_once('db_conn.php');
$cant=blockerip($lognam,0);

if($cant[0]) {

    $sql = "SELECT kod, rol FROM editors WHERE login=? AND pssw=?";
    $stmt = $db->prepare($sql);
//$stmt->bind_param('ss', $lognam, $mdPassword);
    $stmt->execute(array($lognam, $mdPassword));
//$result = $stmt->get_result();
//$row=$result->fetch_array();
    $row = $stmt->fetch();
//debug_to_console (  getdate() );

    if (empty($row['kod'])) {
        $mass[0]['atribut'] = 0;
        $mass[0]['text'] = "Извините, введённый вами логин или пароль неверный.";
        $cant = blockerip($lognam, 1);
    }     //echo('пошел в жопу'.date("Y-m-d H:i:s"));
    else {

        if ($row['rol'] == 1 or $row['rol'] == 2) {
            //session_regenerate_id();
            $cookie_auth = rand_string(10) . $lognam;
            $auth_key = md5($cookie_auth);
            $auth_query = $db->query("UPDATE editors SET aunt = '" . $auth_key . "' WHERE login = '" . $lognam . "';");


            $_SESSION['login'] = $lognam;
            $_SESSION['rol'] = $row['rol'];
            $_SESSION['id'] = $row['kod'];
            $mass[0]['atribut'] = 1;
            $mass[0]['text'] = "Пользователь '$lognam' успешно вошел.";

            if ($remember) {
                $korendir = $_SERVER['HTTP_HOST'];
                $korendir = str_replace('www.', '', $korendir);

                setcookie("auth_key", $auth_key, time() + 60 * 60 * 3 * 1, "/", $korendir, false, true);
                setcookie("rol", $row['rol'], time() + 60 * 60 * 3 * 1, "/", $korendir, false, true);
                setcookie("user", $lognam, time() + 60 * 60 * 24 * 7, "/", $korendir, false, true);

                $mass[0]['text'] = "Пользователь '$lognam' успешно вошел.";
                clearclient($lognam);
            }
        }//if rol
        else {
            $mass[0]['atribut'] = 0;
            $mass[0]['text'] = "Нет доступа.";
            $cant = blockerip($lognam, 1);
        }
    }//else

    echo json_encode($mass);
}else{$mass[0]['atribut'] = 0; $mass[0]['text'] = "Попытка кончились.";echo json_encode($mass);}
//---------------
function rand_string($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
function blockerip($lognam,$param){
    global $db;
    $ip = $_SERVER['REMOTE_ADDR'];
    if($param) { // увеличиваем попытки
        $sql = "SELECT * FROM userip WHERE login=? AND ip=?";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($lognam, $ip));
        $nomer=1;
        if ($sms = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
            $nomer=$sms[0]['nomer']+1;
            $sql2 =  "UPDATE userip SET nomer = ?, dater=?, times=? WHERE login = '" . $lognam . "' and ip='".$ip."';";
            $stmt2 = $db->prepare($sql2);
          // debug_to_console ($stmt2);
            $stmt2->execute(array($nomer,date('Y-m-d H:i:s'),time()));


        } else {
            $sql2 = "INSERT INTO userip ( login, ip, dater, nomer,times ) VALUES(?,?,?,?, ?); ";
            $stmt2 = $db->prepare($sql2);
            $stmt2->execute(array($lognam, $ip,date('Y-m-d H:i:s'),$nomer,time()));
            //stmt2->execute(array($lognam, $ip,date()));
        }
        $tmp=array(1,'client is '.$nomer);
    }else{ // проверяем попытки
        $sql = "SELECT * FROM userip WHERE login=? AND ip=?";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($lognam, $ip));
        if ($sms = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
            if($sms[0]['nomer']>2){ $tmp=checktime($sms[0]);}

            else{$tmp=array(1,'client is just clear ');}
        } else {$tmp=array(1,'client is clear');}

    }
    return $tmp;
 //  debug_to_console (array($lognam,$ip));
}
function checktime($mass){
    $currtime=time();
    $savetime=$mass['times'];
    $delta=$currtime-$savetime;
    $nomer=$mass['nomer'];
    $stop=5*60;
    $hour=$delta/60/60;
    $currstop=$nomer*$stop;
 //   debug_to_console (':: '.$currstop);
    if($delta>$currstop){$tmp=array(1,'client check one ');} else{$tmp=array(0,'its very early');}
 return $tmp;
}

function clearclient($lognam){
    global $db;
    $ip = $_SERVER['REMOTE_ADDR'];
    $sql2 =  "UPDATE userip SET nomer = ?, dater=?, times=? WHERE login = '" . $lognam . "' and ip='".$ip."';";
    $stmt2 = $db->prepare($sql2);
    //debug_to_console ($stmt2);
    $stmt2->execute(array(0,date('Y-m-d H:i:s'),time()));
}

?>