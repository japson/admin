<?
session_start();
$lognam=$_POST['lognam'];
$logpass=$_POST['logpass'];
$remember=$_POST['remember'];
$lognam= htmlspecialchars($lognam);
$logpass= htmlspecialchars($logpass);
$mdPassword = md5($logpass);

require_once('db_conn.php');
$sql ="SELECT kod, rol FROM editors WHERE login=? AND pssw=?";
$stmt = $db->prepare($sql);
//$stmt->bind_param('ss', $lognam, $mdPassword);
$stmt->execute(array($lognam, $mdPassword));
//$result = $stmt->get_result();
//$row=$result->fetch_array();
$row=$stmt->fetch();

if (empty($row['kod'])){$mass[0]['atribut']=0;$mass[0]['text']="Извините, введённый вами логин или пароль неверный.";}
else{

if ($row['rol']==1 or $row['rol']==2) {	
session_regenerate_id();
$cookie_auth= rand_string(10).$lognam;
$auth_key = md5($cookie_auth);
$auth_query = $db->query("UPDATE editors SET aunt = '" . $auth_key . "' WHERE login = '" . $lognam . "';");
	

$_SESSION['login'] = $lognam;
$_SESSION['rol'] = $row['rol'];
$_SESSION['id'] = $row['kod'];
$mass[0]['atribut']=1;$mass[0]['text']="Пользователь '$lognam' успешно вошел.";
	
	if($remember)
	{
	$korendir=$_SERVER['HTTP_HOST'];
	$korendir=str_replace('www.','',$korendir); 
	
	setcookie("auth_key", $auth_key, time() + 60 * 60 * 3 * 1, "/", $korendir, false, true);
	setcookie("rol", $row['rol'], time() + 60 * 60 * 3 * 1, "/", $korendir, false, true);
	setcookie("user", $lognam, time() + 60 * 60 * 24 * 7, "/", $korendir, false, true);

	$mass[0]['text']="Пользователь '$lognam' успешно вошел.";
	}
}//if rol
else {$mass[0]['atribut']=0;$mass[0]['text']="Нет доступа.";}
}//else

echo json_encode($mass);	

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

?>