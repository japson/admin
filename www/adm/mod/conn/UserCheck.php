<?
function UserCheck(){
global $db;
$korendir=$_SERVER['HTTP_HOST'];
$korendir=str_replace('www.','',$korendir); 
$mass[0]['atribut']=1;

$sql ="SELECT kod, rol, aunt FROM editors WHERE login=?";
$stmt = $db->prepare($sql);
//$stmt->bind_param('s', $_SESSION['login']);
$stmt->execute(array($_SESSION['login']));
//$result = $stmt->get_result();
$row=$stmt->fetch(PDO::FETCH_ASSOC);
//var_dump($_SESSION['login'].$row);
//echo $row['id'];
if (empty($row['kod'])){$mass[0]['atribut']=0;$mass[0]['text']="Извините, проблемы с сессией.";}
if (($row['rol']!=$_COOKIE['rol'])){$mass[0]['atribut']=0;$mass[0]['text']="Извините, проблемы с сеансом.Перелогинтесь";}
if (($row['aunt']!=$_COOKIE['auth_key'])){$mass[0]['atribut']=0;$mass[0]['text']="Извините, проблемы с сеансом.Перелогинтесь...";}

if($mass[0]['atribut']==0) {
	setcookie("auth_key", "", time() - 60 * 60 * 3 * 7, "/", $korendir, false, true);
	setcookie("rol", "", time() - 60 * 60 * 3 * 7, "/", $korendir, false, true);
	setcookie("user", "", time() - 60 * 60 * 24 * 7, "/", $korendir, false, true);
	
	unset($_SESSION['rol']);
	unset($_SESSION['login']);
	unset($_SESSION['id']);
	unset($_COOKIE[session_id()]);
	session_unset();
	//session_destroy();
	}
//header('Location:http://'. $_SERVER['HTTP_HOST'] .'/admin/index.php');	
return($mass);
//return($row['rol']." -- ".$_COOKIE['rol']."--  ".$_SESSION['login']);
}

?>