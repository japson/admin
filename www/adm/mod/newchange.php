<?
session_start();
require_once('conn/db_conn.php');
include('conn/UserCheck.php') ;
require_once('debug.php');
$arc=UserCheck();
//echo ('');
if(isset($_COOKIE['auth_key'])  and $arc[0]['atribut']==1)	{
	$action=$_POST['tab']; $keysword=''; $description='';
	$param=json_decode($_POST['param']);
	$text=json_decode($_POST['text']);
	$textseo=json_decode($_POST['textseo']);
	if(gettype($param)=='object') {
			if(strlen($param->table)){$tabl=$param->table;}
			if(strlen($param->kodarticle)){$kodarticle=$param->kodarticle;}
	}
	if(gettype($textseo)=='object') {
			if(strlen($textseo->keys)){$keysword=$textseo->keys;}
			if(strlen($textseo->decript)){$description=$textseo->decript;}
	}
	include('class/var_alt.php');
	$key=array_search($tabl, $massTablAlias);
	//debug_to_console($key);
		if(strlen($key)>0) {$tablic=$key;
			include('class/variables.php');
			include('class/init_table.php');
			include('class/menu_table.php');
			include('class/rasd_table.php');
			$test=new RasdelTable($tablic,$db);
			$test->initText($text,$kodarticle,$description,$keysword);
			$vyvod=$test->changeText($action);
			$mass[0]['atribut']=1;$mass[0]['text']=$vyvod;
		}else {$mass[0]['atribut']=0;$mass[0]['text']="Извините, проблемы с таблицей.";}
}else {$mass[0]['atribut']=0;$mass[0]['text']="Извините, проблемы с правами.";}

echo json_encode($mass);	
?>