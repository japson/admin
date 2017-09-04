<?
session_start();
require_once('conn/db_conn.php');
include('conn/UserCheck.php') ;
require_once('debug.php');
$arc=UserCheck();
//echo ('');
if(isset($_COOKIE['auth_key'])  and $arc[0]['atribut']==1)	{
	$mass_param=array(); $tabl=''; $$alias='';
	$kodrasdel=$_POST['krsd']; //код раздела картинок
	$data=json_decode($_POST['tbl']);
	//debug_to_console($data);
		if(gettype($data)=='object') {
			if(strlen($data->table)){$tabl=$data->table;}
			if(strlen($data->alias)){$alias=$data->alias;}
		}
	include('class/var_alt.php');
	$key=array_search($tabl, $massTablAlias);
	//debug_to_console($key);
		if(strlen($key)>0) {$tablic=$key;
		include('class/variables.php');
		//include('class/init_table.php');
		include('class/win_pictinput.php');
		$windinput= new WindowInputPictur($tablic,$db);
		$windinput->imgAlias();
		$out=$windinput->massPictur($kodrasdel);
		$out.=$windinput->outAction('delOverley');
		//debug_to_console($out);
		$mass[0]['atribut']=1;$mass[0]['text']=$out;
		}else {$mass[0]['atribut']=0;$mass[0]['text']="Извините, проблемы с таблицей.";}
}else {$mass[0]['atribut']=0;$mass[0]['text']="Извините, проблемы с правами.";}

echo json_encode($mass);			
?>