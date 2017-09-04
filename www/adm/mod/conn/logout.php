<?
session_start();
require_once('db_conn.php');


$korendir=$_SERVER['HTTP_HOST'];
	$korendir=str_replace('www.','',$korendir);
	
setcookie("auth_key", "",time() - (60 * 60 * 24 * 1), "/",$korendir);
setcookie("rol", "", time() - (60 * 60 * 24 * 1), "/",$korendir);
setcookie("user", "", time() - (60 * 60 * 24 * 1), "/",$korendir);

unset($_SESSION['login']);
unset($_SESSION['rol']);
unset($_SESSION['id']);
unset($_COOKIE[session_id()]);
session_unset(); // осов все переменные
session_destroy(); // всю информацию убиваем
//header('Location: index.php');  

include('form_log.php');
$mass[0]['atribut']=1;$mass[0]['text']=$perem;
echo json_encode($mass);
?>